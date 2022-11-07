<?php

namespace Drupal\sentinel_passage_wms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
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

//use  const Drupal\sentinel_passage_wms\SentinelUtmTiles\SEARCH_TILES as util;

/**
 * Class SentinelForm.
 */
class SentinelForm extends FormBase
{
    /**
     * Solarium\Core\Query\Helper definition.
     *
     * @var \Solarium\Core\Query\Helper
     */
    protected $solariumQueryHelper;

    /**
     * Psr\Log\LoggerAwareInterface definition.
     *
     * @var \Psr\Log\LoggerAwareInterface
     */
    protected $searchApiSolrCommandHelper;

    /**
     * Drupal\Core\Logger\LoggerChannelFactoryInterface definition.
     *
     * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
     */
    protected $loggerFactory;

    /**
     * Drupal\Core\Config\ConfigFactoryInterface definition.
     *
     * @var \Drupal\Core\Config\ConfigFactoryInterface
     */
    protected $configFactory;



    public const SEARCH_TILES = array(
    0 => 'T25WE',
    6 => 'T25WF',
    12 => 'T25XE',
    25 => 'T25XF',
    26 => 'T26VN',
    27 => 'T26VP',
    28 => 'T26WM',
    34 => 'T26WN',
    43 => 'T26WP',
    52 => 'T26XM',
    63 => 'T26XN',
    76 => 'T26XP',
    77 => 'T27VU',
    78 => 'T27VV',
    80 => 'T27VW',
    82 => 'T27VX',
    84 => 'T27WV',
    93 => 'T27WW',
    102 => 'T27WX',
    111 => 'T27XV',
    122 => 'T27XW',
    135 => 'T27XX',
    136 => 'T28VC',
    139 => 'T28VD',
    144 => 'T28VE',
    149 => 'T28VF',
    156 => 'T28WD',
    165 => 'T28WE',
    172 => 'T28WF',
    178 => 'T28XD',
    189 => 'T28XE',
    201 => 'T28XF',
    202 => 'T29VL',
    211 => 'T29VM',
    220 => 'T29VN',
    229 => 'T29VP',
    237 => 'T29WM',
    243 => 'T29WN',
    248 => 'T29WP',
    251 => 'T29XM',
    262 => 'T29XN',
    272 => 'T30VU',
    280 => 'T30VV',
    288 => 'T30VW',
    297 => 'T30VX',
    306 => 'T30WV',
    309 => 'T30WW',
    313 => 'T30WX',
    314 => 'T30XV',
    319 => 'T30XW',
    324 => 'T31VC',
    333 => 'T31VD',
    342 => 'T31VE',
    351 => 'T31VF',
    353 => 'T31WD',
    356 => 'T31WE',
    362 => 'T31WF',
    370 => 'T31WG',
    371 => 'T31XD',
    379 => 'T31XE',
    389 => 'T31XF',
    397 => 'T32VK',
    405 => 'T32VL',
    414 => 'T32VM',
    423 => 'T32VN',
    432 => 'T32VP',
    441 => 'T32WM',
    449 => 'T32WN',
    458 => 'T32WP',
    467 => 'T33VU',
    476 => 'T33VV',
    485 => 'T33VW',
    494 => 'T33VX',
    503 => 'T33WU',
    504 => 'T33WV',
    513 => 'T33WW',
    522 => 'T33WX',
    531 => 'T33WY',
    532 => 'T33XU',
    536 => 'T33XV',
    549 => 'T33XW',
    562 => 'T33XX',
    573 => 'T33XY',
    574 => 'T34VC',
    583 => 'T34VD',
    592 => 'T34VE',
    601 => 'T34VF',
    610 => 'T34WD',
    618 => 'T34WE',
    627 => 'T34WF',
    636 => 'T35VL',
    645 => 'T35VM',
    654 => 'T35VN',
    663 => 'T35VP',
    672 => 'T35WL',
    673 => 'T35WM',
    682 => 'T35WN',
    691 => 'T35WP',
    700 => 'T35WQ',
    701 => 'T35XL',
    707 => 'T35XM',
    720 => 'T35XN',
    732 => 'T35XP',
    741 => 'T35XQ',
    742 => 'T36VU',
    751 => 'T36VV',
    760 => 'T36VW',
    769 => 'T36VX',
    777 => 'T36WV',
    785 => 'T36WW',
    794 => 'T36WX',
    803 => 'T37VC',
    811 => 'T37VD',
    819 => 'T37VE',
    827 => 'T37VF',
    835 => 'T37WC',
    836 => 'T37WD',
    845 => 'T37WE',
    854 => 'T37WF',
    863 => 'T37XC',
    867 => 'T37XD',
    877 => 'T37XE',
    886 => 'T37XF',
    887 => 'T38KL',
    888 => 'T38VL',
    896 => 'T38VM',
    904 => 'T38VN',
    912 => 'T38VP',
    920 => 'T38WM',
    929 => 'T38WN',
    938 => 'T38WP',
    947 => 'T38XM',
    957 => 'T38XN',
    970 => 'T38XP',
    971 => 'T39VU',
    979 => 'T39VV',
    987 => 'T39VW',
    995 => 'T39VX',
    1003 => 'T39WV',
    1012 => 'T39WW',
    1021 => 'T39WX',
    1030 => 'T39XV',
    1041 => 'T39XW',
    1053 => 'T39XX',
    1054 => 'T40VC',
    1062 => 'T40VD',
    1070 => 'T40VE',
    1078 => 'T40VF',
    1086 => 'T40WD',
    1095 => 'T40WE',
    1104 => 'T40WF',
    1113 => 'T40XD',
    1124 => 'T40XE',
    1137 => 'T40XF',
    1138 => 'T41VL',
    1146 => 'T41VM',
    1154 => 'T41VN',
    1162 => 'T41VP',
    1169 => 'T41WM',
    1178 => 'T41WN',
    1187 => 'T41WP',
    1196 => 'T41XM',
    1207 => 'T41XN',
    1219 => 'T41XP',
    1220 => 'T42VU',
    1228 => 'T42VV',
    1236 => 'T42VW',
    1244 => 'T42VX',
    1252 => 'T42WV',
    1261 => 'T42WW',
    1270 => 'T42WX',
    1279 => 'T42XV',
    1290 => 'T42XW',
    1303 => 'T42XX',
    1304 => 'T43VC',
    1312 => 'T43XD',
    1323 => 'T47UN',
    1324 => 'T50LQ',
    );

    public const SEARCH_FIELDS = [
      'id',
      'platform_short_name',
      'title',
      'bbox',
      'temporal_extent_start_date',
      'temporal_extent_end_date',
      'data_access_url_ogc_wms',
      'data_access_wms_layers',
      'platform_orbit_relative',
      'platform_orbit_absolute',
      'platform_instrument_product_type',
      'platform_ancillary_cloud_coverage',
    ];

    public const FIRST_YEARS = [
      'S2A' => '2015',
      'S2B' => '2017',
    ];

    public const PRODUCT = [
      'S2A' => 'Sentinel-2A',
      'S2B' => 'Sentinel-2B',
    ];

    public const LAYERS = [
          "true_color_vegetation" => "True Color Vegetation Composite",
          "false_color_vegetation" => "False Color Vegetation Composite",
          "false_color_glacier" => "False Color Glacier Composite",
      ];
    protected $solrConnector;


    //protected $years = null; //Class variable to hold the years options list
    //protected $months = null //class variable to hold the months options list
    protected $default_product = 'S2A'; //The default sentinel product
    protected $wmsQuery; //Store the latest wms resources query for selected dates and passage.
    protected $updateMap; //boolean for js to update the map or not
    protected $productTitles; //Hold titles for the products to visualise



    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        $instance = parent::create($container);
        $instance->solariumQueryHelper = $container->get('solarium.query_helper');
        $instance->searchApiSolrCommandHelper = $container->get('search_api_solr.command_helper');
        $instance->loggerFactory = $container->get('logger.factory');
        $instance->configFactory = $container->get('config.factory');
        /**
         * TODO: This should be moved elsewhere where from a service ore Something
         */
        $index = Index::load('metsis');

        /** @var SearchApiSolrBackend $backend */
        $backend = $index->getServerInstance()->getBackend();

        $connector = $backend->getSolrConnector();
        $instance->solrConnector = $connector;
        $instance->years = null;
        $instance->updateMap = false;
        return $instance;
    }

    /**)
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'sentinel_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        //dpm('.............buildForm..............');
        //dpm($form_state->getTriggeringElement()['#name']);
        #dpm($form_state);
        $form['dev_message'] = [
          '#type' => 'markup',
          '#prefix' => '<div class="w3-panel w3-leftbar w3-text-red">',
          '#suffix' => '</div>',
          '#markup' => '<em><strong> Under development</em></strong><br> Use the year and month dropdowns to change the timeframe, and use the calendar to choose the date range of interest. The visulised wms tiles will be cleared when a dropdown is selected.',
          '#allowed_tags' => ['div','em','strong','br'],
        ];
        //Fetch the current form values
        $values = $form_state->getValues();
        //  dpm($values); //DEBUG
        //  dpm($this->years);


        //Get the element that triggered this form rebuild
        $el = $form_state->getTriggeringElement();
        if (isset($el['#name'])) {
            $triggering_element = $el['#name'];
        } else {
            $triggering_element = '';
        }


        // Disable caching on this form.
        //$form_state->disableCache();

        //Set Sentinel2A as default
        if (!$form_state->hasValue('select_platform')) {
            $form_state->setValue('select_platform', 'S2A');
        }


        $form['form_wrapper'] = [
                  '#type' => 'container',
                  '#attributes' => [ 'id' => 'form-wrapper'],
            ];


        //Build the form
        $form['form_wrapper']['select_platform'] = [
            '#type' => 'radios',
            '#title' => $this->t('Select product:'),
            '#options' => [
              'S2A' => $this->t('Sentinel2-A'),
              'S2B' => $this->t('Sentinel2-B'),
            ],
            '#default_value' => $form_state->getValue('select_platform'),

            '#ajax' => [
              'wrapper' => 'year-wrapper',
              'callback' => '::getYearsCallback',
              'event' => 'change'
            ],

          ];






        $form['form_wrapper']['year_wrapper'] = [
              '#type' => 'container',
              '#attributes' => [ 'id' => 'year-wrapper'],
        ];


        //Populate the years form if it was not populated yet.
        if ($triggering_element === 'select_platform') {
            //    dpm('do years query triggering element select_platform');
            $years = $this->doDatesQuery($form_state->getValues(), '+1YEAR');
            $form_state->set('years', $years);
            //$this->years = $years;
        }

        if (!$form_state->has('years')) {
            //  dpm('do years query empty years');
            $years = $this->doDatesQuery($form_state->getValues(), '+1YEAR');
            $form_state->set('years', $years);
        }

        /*
                if (null != $this->years || $triggering_element === 'select_platform') {
        */

        if (!$form_state->hasValue('years')) {
            $form_state->setValue('years', date('Y'));
        }
        if ($form_state->has('years')) {
            $form['form_wrapper']['year_wrapper']['years'] = [
            '#type' => 'select',
            '#title' => $this->t('Year:'),
            '#options' => $form_state->get('years'),
            '#empty_option' => $this->t('- Year -'),
            '#default_value' => $form_state->getValue('years'),
            '#ajax' => [
              'wrapper' => 'month-wrapper',
              'callback' => '::getMonthsCallback',
              'event' => 'change'
            ],
          ];
        }

        $form['form_wrapper']['date_wrapper'] = [
                              '#type' => 'container',
                              '#attributes' => [ 'id' => 'date-wrapper'],
                        ];

        $form['form_wrapper']['date_wrapper']['month_wrapper'] = [
                      '#type' => 'container',
                      '#attributes' => [ 'id' => 'month-wrapper'],
                ];


        if ($triggering_element === 'years') {
            //    dpm('do years query triggering element select_platform');
            $months = $this->doDatesQuery($form_state->getValues(), '+1MONTH');
            $form_state->set('months', $months);
            //$this->months = $months;
        }

        if (!$form_state->has('months')) {
            //  dpm('do months query empty months');
            $months = $this->doDatesQuery($form_state->getValues(), '+1MONTH');
            //$form_state->set('years', $years);
            $form_state->set('months', $months);
        }
        if (!$form_state->hasValue('months')) {
            $form_state->setValue('months', date('m'));
        }
        if ($form_state->has('months')) {
            $form['form_wrapper']['date_wrapper']['month_wrapper']['months'] = [
                          '#type' => 'select',
                          '#title' => $this->t('Select month'),
                          '#options' => $form_state->get('months'),
                          '#empty_option' => $this->t('- Month -'),
                          '#default_value' => $form_state->getValue('months'),
                          '#ajax' => [
                            'wrapper' => 'day-wrapper',
                            'callback' => '::getDaysCallback',
                            'event' => 'change',
                          ],
                        ];
        }


        $form['form_wrapper']['date_wrapper']['day_wrapper'] = [
                          '#type' => 'container',
                          '#attributes' => [ 'id' => 'day-wrapper'],
                        ];


        $form['form_wrapper']['date_wrapper']['day_wrapper']['date_picker'] = [
                                                  '#type' => 'textfield',
                                                  '#title' => $this->t('Select timeframe of interest'),
                                                  '#attributes' => [
                                                    'id' => 'datepicker',
                                                    'class' => ['datepicker'],
                                                  ],
                                                  '#default_value' => '',
                                                  '#size' => 20,
                                                ];

        /*
                                    if ($triggering_element === 'months') {
                                        $values = $form_state->getValues();
                                        if (!$form_state->has('days')) {
                                            $days = $this->doDatesQuery($values, '+1DAY');
                                            $form_state->set('days', $days);
                                        } else {
                                            $days = $this->doDatesQuery($values, '+1DAY');
                                            $form_state->set('days', $days);
                                        }
                                    }
                                    if ($form_state->hasValue('months')) {
                                        $form['form_wrapper']['date_wrapper']['day_wrapper']['days'] = [
                                  '#type' => 'select',
                                  '#title' => $this->t('Select day'),
                                  '#default_value' => $form_state->getValue('days'),
                                  '#options' => $form_state->get('days'),
                                  '#empty_option' => $this->t('- Days -'),
                                  '#ajax' => [
                                'wrapper' => 'tile-wrapper',
                                'callback' => '::getTilesCallback',
                                'event' => 'change'
                                  ],
                                ];
                                    }

                                    $form['form_wrapper']['tile_wrapper'] = [
                                  '#type' => 'container',
                                  '#attributes' => [ 'id' => 'tile-wrapper'],
                                ];
                                    $values = $form_state->getValues();
                                    if ($triggering_element === 'days') {
                                        if (!$form_state->has('tiles')) {
                                            $tiles = $this->doTileQuery($values);
                                            $form_state->set('tiles', $tiles);
                                        } else {
                                            $tiles = $this->doTileQuery($values);
                                            $form_state->set('tiles', $tiles);
                                        }
                                    }
                                    if ($form_state->hasValue('days')) {
                                        //dpm('tileform');
                                        $form['form_wrapper']['tile_wrapper']['select_passage_code'] = [
                                '#type' => 'select',
                                '#title' => $this->t('Select passage code'),
                                '#default_option' => $form_state->getValue('select_passage_code'),
                                //'#options' => self::SEARCH_TILES,
                                '#options' => $form_state->get('tiles'),
                                '#empty_option' => $this->t('- Select tile passage -'),
                                '#ajax' => [
                                  'wrapper' => 'map-wrapper',
                                  'callback' => '::getWmsMapCallback',
                                  'event' => 'change'
                                ],
                                  ];
                                    }
                                */



        //Build the form
        $form['form_wrapper']['layers'] = [
            '#type' => 'select',
            '#title' => $this->t('Change layer'),
            '#options' => self::LAYERS,
            '#default_value' => self::LAYERS['true_color_vegetation'],

            '#ajax' => [
              'wrapper' => 'map-wrapper',
              'callback' => '::mapCallback',
              'event' => 'change'
            ],

          ];

        $form['form_wrapper']['reset'] = [
              '#type' => 'button',
              '#value' => $this->t('Redraw passages'),

              '#ajax' => [
                //'wrapper' => 'map-wrapper',
                'callback' => '::resetCallback',
                //'event' => 'change'
              ],

            ];

            $form['form_wrapper']['remove'] = [
                  '#type' => 'button',
                  '#value' => $this->t('Remove WMS'),

                  '#ajax' => [
                    //'wrapper' => 'map-wrapper',
                    'callback' => '::resetWmsCallback',
                    //'event' => 'change'
                  ],

                ];

        $form['progress_wrapper'] = [
    '#type' => 'container',
    '#attributes' => [
      'id' => 'progress-wrapper',
      //'class' => 'map'
    ],
  ];


        $form['map_wrapper'] = [
  '#type' => 'container',
  '#attributes' => [
    'id' => 'map-wrapper',
    'class' => 'map'
  ],
];

        $form['map_wrapper']['tooltip'] = [
'#type' => 'container',
'#attributes' => [
'id' => 'tooltip',
//'class' => 'map'
],
];
        //dpm($this->updateMap);
        $form['#attached']['library'][] = 'sentinel_passage_wms/sentinel_passage_wms';
        //$form['#cache']['max-age'] = 0;
        $form['#attached']['drupalSettings']['s2wms'] = [
            'update_map' => $this->updateMap,
            'wms' => [],
            'default_layers' => self::LAYERS,
            'product_titles' => [],
            'year' => $form_state->getValue('years'),
            'month' => $form_state->getValue('months'),
            'platform' => $form_state->getValue('select_platform'),
      ];

        //dpm($values);
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        foreach ($form_state->getValues() as $key => $value) {
            // @TODO: Validate fields.
        }
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        // Display result.
        foreach ($form_state->getValues() as $key => $value) {
            \Drupal::messenger()->addMessage($key . ': ' . ($key === 'text_format' ? $value['value'] : $value));
        }
        \Drupal::messenger()->addMessage($form_state->getTriggeringElement());
    }


    /**
     * Ajax Select form callback fuctions.
     *
     * Return the parts of the form that shall be updated during ajax events
     */
    public function getYearsCallback(array &$form, FormStateInterface $form_state)
    {
        $years = $this->doDatesQuery($form_state->getValues(), '+1YEAR');
        $form_state->set('years', $years);
        $form['form_wrapper']['year_wrapper']['years']['#options'] = $years;

        $settings = [
          's2wms' => [
          'update_map' => true,
          'years' => $form_state->getValue('years'),
          'months' => $form_state->getValue('months'),
          'platform' => $form_state->getValue('select_platform'),

        ],
      ];
        $response = new AjaxResponse();
        $response->addCommand(new InvokeCommand(null, 'changeDatesCallback', [$form_state->getValues()]));
        $response->addCommand(new ReplaceCommand('#year-wrapper', $form['form_wrapper']['year_wrapper']));

        //return $form['form_wrapper']['year_wrapper'];
        return $response;
    }

    public function getMonthsCallback(array &$form, FormStateInterface $form_state)
    {
        $months = $this->doDatesQuery($form_state->getValues(), '+1MONTH');
        $form_state->set('months', $months);
        $form['form_wrapper']['date_wrapper']['month_wrapper']['months']['#options'] = $months;

        $settings = [
          's2wms' => [
          'update_map' => true,
          'years' => $form_state->getValue('years'),
          'months' => $form_state->getValue('months'),
          'platform' => $form_state->getValue('select_platform'),

        ],
      ];
        $response = new AjaxResponse();
        $response->addCommand(new InvokeCommand(null, 'changeDatesCallback', [$form_state->getValues()]));
        $response->addCommand(new ReplaceCommand('#month-wrapper', $form['form_wrapper']['date_wrapper']['month_wrapper']));


        //return $form['form_wrapper']['date_wrapper']['month_wrapper'];
        return $response;
    }

    public function getDaysCallback(array &$form, FormStateInterface $form_state)
    {
        $settings = [
        's2wms' => [
        'update_map' => true,
        'years' => $form_state->getValue('years'),
        'months' => $form_state->getValue('months'),
        'platform' => $form_state->getValue('select_platform'),

      ],
    ];
        $response = new AjaxResponse();
        $response->addCommand(new InvokeCommand(null, 'changeDatesCallback', [$form_state->getValues()]));
        //$response->addCommand(new ReplaceCommand('#month-wrapper', $form['form_wrapper']['date_wrapper']['month_wrapper']));


        //return $form['form_wrapper']['date_wrapper']['day_wrapper'];
        return $response;
    }

    public function getTilesCallback(array &$form, FormStateInterface $form_state)
    {
        return $form['form_wrapper']['tile_wrapper'];
    }

    public function getWmsMapCallback(array &$form, FormStateInterface $form_state)
    {
        $tile = $form_state->getValue('select_passage_code');
        //dpm($tile);
        $wmsResources = $this->doWmsQuery($tile);
        $form['map_wrapper']['#attached']['drupalSettings']['s2wms'] = [
        'update_map' => true,
        'wms' => $wmsResources,
        'product_titles' => $this->productTitles,
        'default_layers' => implode(',', self::LAYERS),
        'platform' => $form_state->getValue('select_platform'),
      ];
        return $form['map_wrapper'];
        /*
                $response = new AjaxResponse();
                $response->setData(['update_map' => $this->updateMap]);
                $response->addCommand(new ReplaceCommand('#map-wrapper', $form['map-wrapper']));
                return $response;
        */
    }

    public function mapCallback(array &$form, FormStateInterface $form_state)
    {
        //$values = $form_state->getValues();
        $selected_layer = $form_state->getValue('layers');
        $values = $form_state->getValues();
        $response = new AjaxResponse();
        //$response->setData(['update_map' => $this->updateMap]);
        //$response->addCommand(new ReplaceCommand('#map-wrapper', $form['map-wrapper']));
        $response->addCommand(new InvokeCommand(null, 'changeLayerCallback', [$selected_layer]));
        return $response;
    }

    public function resetCallback(array &$form, FormStateInterface $form_state)
    {
        //$values = $form_state->getValues();
        //$selected_layer = $form_state->getValue('layers');
        $values = $form_state->getValues();
        $response = new AjaxResponse();
        //$response->setData(['update_map' => $this->updateMap]);
        //$response->addCommand(new ReplaceCommand('#map-wrapper', $form['map-wrapper']));
        $response->addCommand(new InvokeCommand(null, 'resetPassageCallback', [$values]));
        return $response;
    }

    public function resetWmsCallback(array &$form, FormStateInterface $form_state)
    {
        //$values = $form_state->getValues();
        //$selected_layer = $form_state->getValue('layers');
        $values = $form_state->getValues();
        $response = new AjaxResponse();
        //$response->setData(['update_map' => $this->updateMap]);
        //$response->addCommand(new ReplaceCommand('#map-wrapper', $form['map-wrapper']));
        $response->addCommand(new InvokeCommand(null, 'resetWmsCallback', [$values]));
        return $response;
    }


    /**
     * Query solr for date facets giving the product
     */
    public function doDatesQuery($values, $gap)
    {

        //Get the selected tile
        //dpm('Doing date query: ' . $gap); // DEBUG

        //Create select query
        $query = $this->solrConnector->getSelectQuery();


        //Create the query
        //$query->setQuery('title:'.$values['select_platform'].'*'.$tile.'*');
        $query->setQuery('*:*');

        $query->addSort('timestamp', $query::SORT_DESC);
        $query->setRows(0);
        $query->setFields(self::SEARCH_FIELDS);

        //Filter on platform name
        $platform = self::PRODUCT[$values['select_platform']];
        //dpm($platform);
        $query->createFilterQuery('platform')->setQuery('platform_short_name:('.$platform.')');


        // create Facetquery
        $facetSet = $query->getFacetSet();
        //Create facet range query with granularity
        $facet = $facetSet->createFacetRange('dates');
        $facet->setField('temporal_extent_start_date');
        if ($gap === '+1YEAR') {
            if (!isset($values['select_platform'])) {
                $year = self::FIRST_YEARS['S2A'];
            } else {
                $year = self::FIRST_YEARS[$values['select_platform']];
            }
            $facet->setStart($year.'-01-01T00:00:00Z');
            $facet->setEnd('NOW');
        } elseif ($gap === '+1MONTH') {
            $facet->setStart($values['years'].'-01-01T00:00:00Z');
            $facet->setEnd($values['years'].'-12-31T23:59:59Z');
        } else {
            $year = (int) $values['years'];
            $month =  (int) $values['months'];
            $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $facet->setStart($values['years'].'-'.$values['months'].'-01T00:00:00Z');
            $facet->setEnd($values['years'].'-'.$values['months'].'-'.$day.'T23:59:59Z');
        }


        $facet->setGap($gap);
        $facet->setMinCount(1);

        $result = $this->solrConnector->execute($query);

        $facet_result = $result->getFacetSet()->getFacet('dates');
        //dpm($facet_result);
        // The total number of documents found by Solr.
        $found = $result->getNumFound();
        \Drupal::logger('sentinel_wms')->debug("Date query - ".$gap.": " . sizeof($facet_result->getValues()));

        //Populate years option array from facet results
        $dates = [];
        foreach ($facet_result as $value => $count) {
            if ($gap === '+1YEAR') {
                $date = substr($value, 0, 4);
            } elseif ($gap === '+1MONTH') {
                $date = substr($value, 5, 2);
            } else {
                $date = substr($value, 8, 2);
            }


            $dates[(string) $date] = (string) $date;
        }
        if ($gap === '+1MONTH') {
            $months = [];
            if(isset($values['year'])) {
              $newyear = $values['year'];
            }
            else {
              $newyear = null;
            }
            foreach ($dates as $value) {
                $months[$value] = date("F", mktime(0, 0, 0, $value, $newyear));
                // code...
            }
            $dates = $months;
        }
        //dpm($dates);

        return $dates;
    }
    public function doTileQuery($values)
    {


        //Create select query
        $query = $this->solrConnector->getSelectQuery();


        //Create the query
        //$query->setQuery('title:'.$values['select_platform'].'*'.$tile.'*');
        $query->setQuery('*:*');

        $query->addSort('timestamp', $query::SORT_DESC);
        $query->setRows(0);
        $query->setFields(self::SEARCH_FIELDS);

        //Filter on platform name
        $platform = self::PRODUCT[$values['select_platform']];
        $query->createFilterQuery('platform')->setQuery('platform_short_name:'.$platform);

        //Filter on chosen date:
        $date_start = $values['years'].'-'.$values['months'].'-'.$values['days'].'T00:00:00Z';
        $date_end = $values['years'].'-'.$values['months'].'-'.$values['days'].'T23:59:59Z';
        //dpm($date_start);
        //dpm($date_end);
        $query->createFilterQuery('dates')->setQuery('temporal_extent_start_date:(['.$date_start.' TO '.$date_end.'])');


        //First we return empty query to get result count
        $result = $this->solrConnector->execute($query);
        $found = $result->getNumFound();
        \Drupal::logger('sentinel_wms')->debug("Tile query get #of datasets - : " . $found);

        //Reuse query object and get all titles of all datasets.
        $query->setFields(['title']);
        $query->setRows($found);

        //First we return empty query to get result count
        $result = $this->solrConnector->execute($query);
        $found = $result->getNumFound();
        \Drupal::logger('sentinel_wms')->debug("Tile query get wms sources - : " . $found);


        //Store the query for later use.
        $this->wmsQuery = $query;

        //Create tiles array:
        $tiles = [];
        foreach ($result as $doc) {
            foreach ($doc as $field => $value) {
                if ($field === 'title') {
                    $arr = explode('_', $value[0]);
                    //$tile = ltrim($arr[5], 'T');
                    $tile = substr($arr[5], 0, -1);
                    $tiles[$tile] = $tile;
                }
            }
        }
        $tiles = array_unique($tiles, SORT_REGULAR);
        //dpm($tiles);

        return $tiles;
    }

    public function doWmsQuery($tile)
    {


        //Create select query
        $query = $this->wmsQuery;


        //Create the query
        //$query->setQuery('title:'.$values['select_platform'].'*'.$tile.'*');
        //$query->setQuery('title:*'.$tile.'*');
        $query->createFilterQuery('bbox')->setQuery('{!field f=bbox score=overlapRatio}Within(ENVELOPE(8.9994170118000003,12.1968579686,72.099581131099995,55.844830526199999))');
        $query->setFields(['data_access_url_ogc_wms','title']);
        //$query->setRows($found);

        //First we return empty query to get result count
        $result = $this->solrConnector->execute($query);
        $found = $result->getNumFound();
        \Drupal::logger('sentinel_wms')->debug("WMS query get wms sources - : " . $found);


        //Store the query for later use.
        $this->wmsQuery = $query;

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
        return $wms;
    }
}
