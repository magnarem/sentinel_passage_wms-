<?php

namespace Drupal\sentinel_passage_wms\Controller;

use Drupal\Core\Controller\ControllerBase;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\search_api\Entity\Index;
use Drupal\search_api\Query\QueryInterface;
use Solarium\QueryType\Select\Query\Query;
use Drupal\search_api_solr\Plugin\search_api\backend\SearchApiSolrBackend;
use Drupal\Component\Serialization\Json;
use Drupal\geofield\GeoPHP\GeoPHPInterface;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\SettingsCommand;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\DataCommand;

/**
 * Class SentinelAjaxController.
 */
class SentinelAjaxController extends ControllerBase
{
    protected $solrConnector;


    public const PRODUCT = [
      'S2A' => 'Sentinel-2A',
      'S2B' => 'Sentinel-2B',
    ];

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        $instance = parent::create($container);
        /**
         * TODO: This should be moved elsewhere where from a service ore Something
         */
        $index = Index::load('metsis');

        /** @var SearchApiSolrBackend $backend */
        $backend = $index->getServerInstance()->getBackend();

        $connector = $backend->getSolrConnector();
        $instance->solrConnector = $connector;
        return $instance;
    }


    /**
     * Getwmsresources.
     *
     * @return string
     *   Return Hello string.
     */
    public function getWmsResources()
    {
        $query_from_request = \Drupal::request()->query->all();
        $params = \Drupal\Component\Utility\UrlHelper::filterQueryParameters($query_from_request);

        $startDate = $params['start'];
        $endDate = $params['stop'];
        $wkt = $params['wkt'];
        $platform =  self::PRODUCT[$params['platform']];


        \Drupal::logger('s2wms')->debug('Got start date: ' .$startDate);
        \Drupal::logger('s2wms')->debug('Got end date: ' .$endDate);
        \Drupal::logger('s2wms')->debug('Got wkt: ' .$wkt);


        $polygon = \geoPHP::load($wkt, 'wkt');
        \Drupal::logger('s2wms')->debug('Got bbox: ' .implode(', ', $polygon->getBbox()));

        //Get the bbox of the polygon
        $bbox = $polygon->getBbox();
        //dpm($bbox);
        //Translate bbox to solr envelope.
        $envelope = 'ENVELOPE('.$bbox['minx'].','.$bbox['maxx'].','.$bbox['maxy'].','.$bbox['miny'].')';
        \Drupal::logger('s2wms')->debug('Solr envelope: ' .$envelope);


        //Create select query
        $query = $this->solrConnector->getSelectQuery();

        $query->setQuery('*:*');

        $query->addSort('timestamp', $query::SORT_DESC);
        $query->setRows(400);
        //$query->setFields(self::SEARCH_FIELDS);

        //Filter on platform name
        //$platform = self::PRODUCT[$values['select_platform']];
        $query->createFilterQuery('platform')->setQuery('platform_short_name:'. $platform);

        //Filter on chosen date:
        //$date_start = $values['years'].'-'.$values['months'].'-'.$values['days'].'T00:00:00Z';
        //$date_end = $values['years'].'-'.$values['months'].'-'.$values['days'].'T23:59:59Z';
        //dpm($date_start);
        //dpm($date_end);
        $query->createFilterQuery('start')->setQuery('temporal_extent_start_date:(['.$startDate.'Z TO *])');
        $query->createFilterQuery('stop')->setQuery('temporal_extent_end_date:([* TO '.$endDate.'Z])');

        $query->createFilterQuery('bbox')->setQuery('{!field f=bbox score=overlapRatio}Within('.$envelope.')');
        $query->setFields(['data_access_url_ogc_wms','title']);
        //$query->setRows($found);

        //First we return empty query to get result count
        $result = $this->solrConnector->execute($query);
        $found = $result->getNumFound();
        \Drupal::logger('sentinel_wms')->debug("WMS query get wms sources found: " . $found);


        //Store the query for later use.
        //$this->wmsQuery = $query;

        //Create tiles array:
        $wms = [];
        $titles = [];
        foreach ($result as $doc) {
            foreach ($doc as $field => $value) {
                if ($field === 'data_access_url_ogc_wms') {
                    $wms[] = $value[0];
                }
                if ($field === 'title') {
                    $titles[] = $value[0];
                }
            }
        }
        $this->productTitles = $titles;
        //First we return empty query to get result count


        //dpm($wms);

        $settings = [
          's2wms' => [
          'wms_urls' => $wms,
          'titles' => $titles,
        ],
        ];
        $response = new AjaxResponse();
        //$response->addCommand(new DataCommand('#map-wrapper', 'visualise', $settings));
        $response->addCommand(new SettingsCommand($settings, true));
        //$response->addCommand(new InvokeCommand('#map-wrapper', 'visualise'));

        //\Drupal::logger('metsis_search_map_search_controller')->debug(\Drupal::request()->getRequestUri());
        return $response;
    }
}
