console.log("Start of s2wms map script:");
(function($, Drupal, drupalSettings, once) {



  console.log("Attaching s2wms script to drupal behaviours:");
  /** Attach the metsis map to drupal behaviours function */
  Drupal.behaviors.s2wms = {
    attach: function(context, drupalSettings) {
      //updateMap = false;
      once('wmsMap','div.map').forEach(function(element) {
        console.log('Invoking wmsviewer....');


        var lat = 71;
        var lon = 15;
        //Get the site name
        //
        //Get variables sent from forms via drupalSettings object
        var updateMap = drupalSettings.s2wms.update_map;
        var products = drupalSettings.s2wms.wms;
        var default_layers = drupalSettings.s2wms.default_layers;
        var product_titles = drupalSettings.s2wms.product_titles;
        var year = drupalSettings.s2wms.year;
        var month = drupalSettings.s2wms.month;
        var platform = drupalSettings.s2wms.platform;


        console.log('Year: ' + year);
        console.log('Month: ' + month);

        var firstDay = new Date(year, month-1, 1);
        var lastDay = new Date(year, month, 0);




        /*
         * POPULATE SOME Forms
         */

/*
           $('#form-wrapper')
 .append(
     $(document.createElement('label')).prop({
         for: 'layers'
     }).html('Choose layer: ')
 )
 .append(
     $(document.createElement('select')).prop({
         id: 'layers',
         name: 'layers'
     })
 )

 for (const val of default_layers) {
     $('#layers').append($(document.createElement('option')).prop({
         value: val,
         text: val
     }))
 }
*/


        //console.log(updateMap);
        //var defzoom = drupalSettings.satellite_passage.defzoom;
      //  console.log(products);
        //var lon = drupalSettings.satellite_passage.lon;
        //var lat = drupalSettings.satellite_passage.lat;
        // define some interesting projections
        // WGS 84 / EPSG Norway Polar Stereographic

        const image = new ol.style.Circle({
          radius: 5,
          fill: null,
          stroke: new ol.style.Stroke({color: 'red', width: 1}),
        });

        const styles = {
  'Point': new ol.style.Style({
    image: image,
  }),
  'LineString': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'green',
      width: 1,
    }),
  }),
  'MultiLineString': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'green',
      width: 1,
    }),
  }),
  'MultiPoint': new ol.style.Style({
    image: image,
  }),
  'MultiPolygon': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'yellow',
      width: 1,
    }),
    fill: new ol.style.Fill({
      color: 'rgba(255, 255, 0, 0.1)',
    }),
  }),
  'Polygon': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'blue',
      //lineDash: [4],
      width: 1,
    }),
    fill: new ol.style.Fill({
      color: 'rgba(0, 0, 255, 0.1)',
    }),
  }),
  'GeometryCollection': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'magenta',
      width: 2,
    }),
    fill: new ol.style.Fill({
      color: 'magenta',
    }),
    image: new ol.style.Circle({
      radius: 10,
      fill: null,
      stroke: new ol.style.Stroke({
        color: 'magenta',
      }),
    }),
  }),
  'Circle': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'red',
      width: 2,
    }),
    fill: new ol.style.Fill({
      color: 'rgba(255,0,0,0.2)',
    }),
  }),
};

const styleFunction = function (feature) {
  return styles[feature.getGeometry().getType()];
};

        //Update map and layers and unhide
    //if(Boolean(updateMap)) {


// Add more styles
  var styleRed = new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: '#f00',
      width: 1
    }),
    fill: new ol.style.Fill({
      color: 'rgba(255,0,0,0.3)'
    })
  });

  var styleGreen = new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: '#0f0',
      width: 1
    }),
    fill: new ol.style.Fill({
      color: 'rgba(0,255,0,0.3)'
    })
  })


var styleEmpty = new ol.style.Style({});


// Defining some projections
          console.log('Creating  projections and base map as hidden element');
              proj4.defs('EPSG:5939', '+proj=stere +lat_0=90 +lat_ts=90 +lon_0=18 +k=0.994 +x_0=2000000 +y_0=2000000 +datum=WGS84 +units=m +no_defs');
              ol.proj.proj4.register(proj4);
              var proj5939 = ol.proj.get('EPSG:5939');
              var ex5939 = [-7021620.2399999998, -6741017.03, 10047367.779999999, 8027970.99];
              proj5939.setExtent(ex5939);
              ol.proj.addProjection(proj5939);

              // WGS 84 -- WGS84 - World Geodetic System 1984
              proj4.defs('EPSG:4326', '+proj=longlat +ellps=WGS84 +datum=WGS84 +units=degrees');
              ol.proj.proj4.register(proj4);
              var proj4326 = ol.proj.get('EPSG:4326');
              var ex4326 = [-90, 30, 90, 90];
              proj4326.setExtent(ex4326);
              ol.proj.addProjection(proj4326);

              // WGS 84 / North Pole LAEA Europe
              proj4.defs('EPSG:3575', '+proj=laea +lat_0=90 +lon_0=10 +x_0=0 +y_0=0 +datum=WGS84 +units=m +no_defs');
              ol.proj.proj4.register(proj4);
              var proj3575 = ol.proj.get('EPSG:3575');
              var ex3575 = [-3e+06, -3e+06, 7e+06, 7e+06];
              proj3575.setExtent(ex3575);
              ol.proj.addProjection(proj3575);

              // WGS 84 / UPS North (N,E)
          /*    proj4.defs('EPSG:32661', '+proj=sterea +lat_0=90 +lat_ts=90 +lon_0=0 +k=0.994 +x_0=2000000 +y_0=2000000 +datum=WGS84 +units=m +no_defs');
              ol.proj.proj4.register(proj4);
              var proj32661 = ol.proj.get('EPSG:32661');
              var ex32661 = [-4e+06, -6e+06, 8e+06, 8e+06];
              proj32661.setExtent(ex32661);
              ol.proj.addProjection(proj32661);
      */
      /*
              proj4.defs(
        'EPSG:3413',
        '+proj=stere +lat_0=90 +lat_ts=70 +lon_0=-45 +k=1 ' +
          '+x_0=0 +y_0=0 +datum=WGS84 +units=m +no_defs'
      );
          ol.proj.proj4.register(proj4);
      var proj3413 = ol.proj.get('EPSG:3413');
      proj3413.setExtent([-4194304, -4194304, 4194304, 4194304]);
      */

      // 32661
      /* proj4.defs('EPSG:32661', '+proj=stere +lat_0=90 +lat_ts=90 +lon_0=0 +k=0.994 +x_0=2000000 +y_0=2000000 +datum=WGS84 +units=m +no_defs');
      ol.proj.proj4.register(proj4);
      var ext32661 = [-6e+06, -3e+06, 9e+06, 6e+06];
      var center32661 = [0.0, 80.0];
      var proj32661 = new ol.proj.Projection({
        code: 'EPSG:32661',
        extent: ext32661
      });
      */

      // WGS 84 / UPS North (N,E)
      proj4.defs('EPSG:32661', '+proj=stere +lat_0=90 +lat_ts=90 +lon_0=0 +k=0.994 +x_0=2000000 +y_0=2000000 +ellps=WGS84 +datum=WGS84 +units=m +no_defs ');
      ol.proj.proj4.register(proj4);
      var proj32661 = ol.proj.get('EPSG:32661');
      var ex32661 = [-4e+06, -6e+06, 8e+06, 8e+06];
      proj32661.setExtent(ex32661);
      proj32661.setGlobal(true);
      ol.proj.addProjection(proj32661);

      var ext = ex32661;
      var prj = proj32661;

              //var ext = ex5939;
              //var prj = proj5939;
              //var ext = ext32661;
              //var prj = proj32661;
              //var ext = ex3575;
              ///var prj = proj3575;
              //var ext = ex4326;
              //var prj = proj4326;
              //var prj = proj3413;
              //var ext =  proj3413.getExtent();


              //vectorSource.addFeature(new Feature(new Circle([5e6, 7e6], 1e6)));
              var kmlSource = new ol.source.Vector({
                //projection: 'EPSG:4326',
                crossOrigin: 'anonymous',
                //url: '/modules/metno/sentinel_passage_wms/assets/S2A_acquisition_plan_norwAOI.kml',
                //url: '/modules/metno/sentinel_passage_wms/assets/mergedKML_1666015062742.kml',
                //wrapX: false,
                format: new ol.format.KML({
                  extractStyles: false,
                  extractAttributes: true
                }),

              });

              var kmlInitStyleFunction = function(feature,res) {
                  let now = new Date();
                  firstDay.setDate(now.getDate() - 7);
                  //let lastDay = new Date(year, month, 0);
                  //console.log('First day: ' +firstDay);
                  //console.log('Last day: ' +lastDay);
                  //console.log('Now: ' +now);
                  if(now.getFullYear() === lastDay.getFullYear() && now.getMonth() === lastDay.getMonth()) {
                    lastDay = now;
                  }
                  //firs.setDate(now.getDate()-1);
                  let stop = new Date(Date.parse(feature.get("ObservationTimeStop")));
                  let start = new Date(Date.parse(feature.get("ObservationTimeStart")));

                  //console.log('start: ' +start);
                  //console.log('stop:' + stop);

                  if(stop <= lastDay && start >= firstDay) {
                  //if(stop <= lastDay) {
                    //console.log("setting style");
                    feature.setStyle(styles['Polygon']);

                  }
                  else {
                    feature.setStyle(undefined);
                  }

              };

              var kmlStyleFunction = function(feature,res) {
                  let now = new Date();
                  //let firstDay = new Date(year, month-1, 1);
                  //let lastDay = new Date(year, month, 0);
                  //console.log('First day: ' +firstDay);
                  //console.log('Last day: ' +lastDay);
                  //console.log('Now: ' +now);
                  if(now.getFullYear() === lastDay.getFullYear() && now.getMonth() === lastDay.getMonth()) {
                    lastDay = now;
                  }
                  //firs.setDate(now.getDate()-1);
                  let stop = new Date(Date.parse(feature.get("ObservationTimeStop")));
                  let start = new Date(Date.parse(feature.get("ObservationTimeStart")));

                  //console.log('start: ' +start);
                  //console.log('stop:' + stop);

                  if(stop <= lastDay && start >= firstDay) {
                  //if(stop <= lastDay) {
                    //console.log("setting style");
                    feature.setStyle(styles['Polygon']);

                  }
                  else {
                    feature.setStyle(undefined);
                  }

              };


              var wmsStyleFunction = function(feature,res) {
                if(feature.get('selected')) {
                  feature.setStyle(styleGreen);

                  }
                  else {
                    feature.setStyle(undefined);
                  }

              };


              var vectorLayer = new ol.layer.Vector({
                background: '#1a2b39',
                title: 'Sentinel2 passage',
                opacity: 0.4,
                source: kmlSource,
                //style: kmlStyleFunction,
/*
                function(feature) {
                  let now = new Date();
                  now.setDate(now.getDate()-1);
                  let stop = new Date(Date.parse(feature.get("ObservationTimeStop")));
                  if(stop < now) {
                    feature.setStyle(styles['Polygon']);

                  }
                }*/
              });
              /*
                source: new ol.source.Vector({
                  url: 'https://metsis-dev.local/modules/metno/sentinel_passage_wms/assets/geojson/passages_new.geojson',
                  format: new ol.format.GeoJSON(),
                }),
                style: styleFunction,
                options: {
                  dataProjection: 'EPSG:4326',
                  featureProjection: 'EPSG:32661',
                },
              });*/
              //console.log(vectorLayer);

              var layer = {};

              // Base layer WMS
              layer['base'] = new ol.layer.Tile({
                title: 'base',
                baseLayer: true,
                //displayInLayerSwitcher: false,
                type: 'base',
                source: new ol.source.OSM({
                })
              });

              var stamenTerrain = new ol.layer.Tile({
                title: "stamenTerrain",
                baseLayer: true,
                //visible: false,
                source: new ol.source.XYZ({
                  attributions: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, under <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a>. Data by <a href="http://openstreetmap.org">OpenStreetMap</a>, under <a href="http://www.openstreetmap.org/copyright">ODbL</a>.',
                  url: 'https://stamen-tiles.a.ssl.fastly.net/terrain/{z}/{x}/{y}.jpg',
                  crossOrigin: 'anonymous',
                }),
              });

              const osmHumanitarian = new ol.layer.Tile({
                title: 'OSMHumanitarian',
                baseLayer: true,
                visible: false,
                source: new ol.source.OSM({
                  url: 'https://{a-c}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png',
                  crossOrigin: 'anonymous',
                }),
              });

              const osmStandard = new ol.layer.Tile({
                title: 'OSMStandard',
                baseLayer: true,
                visible: true,
                source: new ol.source.OSM({}),
              });


              //Adding timepicker
              //const label = document.createElement("label");
              //label.setAttribute("for", "datepicker");
              //label.innerHTML = "Select timeframe of interest: ";
              //document.getElementById('day-wrapper').appendChild(label);
              /*
              var newInput = document.createElement("input");
              newInput.setAttribute('id','datepicker');
              newInput.setAttribute('class','datepicker');
              document.getElementById('day-wrapper').appendChild(newInput);
              */
              const picker = new easepick.create({
                element: '#datepicker',
                zIndex: 99,
                css: [
                  'https://cdn.jsdelivr.net/npm/@easepick/core@1.2.0/dist/index.css',
                  'https://cdn.jsdelivr.net/npm/@easepick/lock-plugin@1.2.0/dist/index.css',
                  'https://cdn.jsdelivr.net/npm/@easepick/range-plugin@1.2.0/dist/index.css',
                ],
                plugins: ['RangePlugin','LockPlugin'],
                RangePlugin: {
                  tooltip: true,
                },
                LockPlugin: {
                  minDate: firstDay,
                  maxDate: lastDay,
                },

              });

     // refresh layout
     //picker.renderAll();


          console.log('Updating map/showing map');
          document.getElementById("map-wrapper").style.height = '600px';
          const wmsLayerGroup = new ol.layer.Group({
            title: "Selected Passage",
            openInLayerSwitcher: true,
            layers: [],
          });


          for(let i = 0; i < products.length; i++){
            wmsLayerGroup.getLayers().push(
              new ol.layer.Tile({
                title: product_titles[i],
                visible: true,
                keepVisible: false,
                //extent: ol.proj.transformExtent(bbox, 'EPSG:4326', selected_proj),
                //projections: ol.control.Projection.CommonProjections(outerThis.projections, (layerProjections) ? layerProjections : wmsProjs),
                //dimensions: getTimeDimensions(),
                //styles: ls[i].Style,
                source: new ol.source.TileWMS(({
                  url: products[i],
                  //reprojectionErrorThreshold: 0.1,
                  //projection: selected_proj,
                  params: {
                    'LAYERS': "true_color_vegetation",
                    'VERSION': "1.3.0",
                    'FORMAT': 'image/png',
                    'TILE': true,
                    'TRANSPARENT': true,
                  },
                  crossOrigin: 'anonymous',

                })),
              }));
          }

          //Calculate map extent for combined layers.
    /*      var layersExtent = new ol.extent.createEmpty();

TODO: DOES NOT WORK ON WMS LAYERS
          tmpLayers = wmsLayerGroup.getLayers();
          tmpLayers.forEach(function(layer) {
            ol.extent.extend(layersExtent, layer.getExtent());
          });*/






          // build up the map
          var map = new ol.Map({
            controls: ol.control.defaults().extend([
              new ol.control.FullScreen(),
              new ol.control.ScaleLine(),
            ]),
            target: 'map-wrapper',
            layers: [
              //layer['base'],
              //stamenTerrain,
              //osmHumanitarian,
              osmStandard,
              vectorLayer,
              wmsLayerGroup,
            ],
            view: new ol.View({
              zoom: 3,
              minZoom: 1,
              mazZoom:  8,
              //center: extent.getCenter(),
              //projection: 'EPSG:32661',
              center: ol.proj.transform([lon, lat], "EPSG:4326", prj),
              extent: ext,
              projection: prj,
            })
          });
          console.log("Created map with projection object: " + prj.getCode());
          //console.log(prj);

          var layerSwitcher = new ol.control.LayerSwitcher({
            collapsed: true,
            reordering: false,
            show_progress: true,
          });
          map.addControl(layerSwitcher);

          //Mouseposition
          var mousePositionControl = new ol.control.MousePosition({
            coordinateFormat: function(co) {
              return ol.coordinate.format(co, template = 'lon: {x}, lat: {y}', 2);
            },
            projection: 'EPSG:4326', //Map hat 3857
          });
          map.addControl(mousePositionControl);


          //map.getView(setCenter(ol.extent.getCenter(featuresExtent)));
          //map.getView().fit(vectorLayer.getSource().getExtent());
          //map.getView().setZoom(map.getView().getZoom());
  /*       map.forEachFeatureAtPixel(evt.pixel, function(feature, layer) {
            feature.setStyle(styleRed);
            return feature;
          });
*/
const tooltip = document.getElementById('tooltip');
const overlay = new ol.Overlay({
  element: tooltip,
  offset: [10, 0],
  positioning: 'top'
});
map.addOverlay(overlay);






/*
 * Render the wms layers;
 */

function renderPassage(products,product_titles) {
  console.log(products.length);
  if(products.length == 0) {
    alert('Could not find any products for this passage and timeframe...');
  }
  for(let i = 0; i < products.length; i++){
    wmsLayerGroup.getLayers().push(
      new ol.layer.Tile({
        title: product_titles[i],
        visible: true,
        keepVisible: false,
        //extent: ol.proj.transformExtent(bbox, 'EPSG:4326', selected_proj),
        //projections: ol.control.Projection.CommonProjections(outerThis.projections, (layerProjections) ? layerProjections : wmsProjs),
        //dimensions: getTimeDimensions(),
        //styles: ls[i].Style,
        source: new ol.source.TileWMS(({
          url: products[i],
          serverType: 'geoserver',
           // Countries have transparency, so do not fade tiles:
          transition: 0,
          //reprojectionErrorThreshold: 0.1,
          //projection: selected_proj,
          params: {
            'LAYERS': "true_color_vegetation",
            'VERSION': "1.3.0",
            'FORMAT': 'image/png',
            'TILE': true,
            'TRANSPARENT': true,
          },
          crossOrigin: 'anonymous',

        })),
      }));


}
//map.addLayer(wmsLayerGroup);
}


const lockPlugin = picker.PluginManager.getInstance('LockPlugin');
const rangePlugin = picker.PluginManager.getInstance('RangePlugin');


picker.on('preselect', (e) => {
//console.log('preselect');
//lockPlugin.options.minDate = firstDay;
//lockPlugin.options.maxDate = lastDay;

});


//Highlight passages on pointer move
let selected = null;
map.on('pointermove', function (e) {
  if (e.dragging) {

    return;
  }

  if (selected !== null) {
    if(selected.get('selected')) {
      selected.setStyle(styleGreen);
      selected = null;
      tooltip.style.display = 'none';
      overlay.setPosition(undefined);

    }
    else {
    selected.setStyle(undefined);
    selected = null;
    tooltip.style.display = 'none';
    overlay.setPosition(undefined);
  }
  }

const feature = map.forEachFeatureAtPixel(e.pixel, function (f) {
    selected = f;
    if(selected.get('selected')) {
        f.setStyle(styleGreen);
      }
      else {
    f.setStyle(styleRed);   //add your style here
  }
    tooltip.style.display = f ? '' : 'none';
    if (f) {
      overlay.setPosition(e.coordinate);
      tooltip.innerHTML = 'ID: ' +f.get('ID');
      tooltip.innerHTML += '<br>Start: '+f.get('ObservationTimeStart');
      tooltip.innerHTML += '<br>Stop: '+f.get('ObservationTimeStop');
    }
    else {
      tooltip.style.display = 'none';
      overlay.setPosition(undefined);
      //tooltip.style.display = 'hidden';
      //tooltip.innerHTML = '';
    }
    return true;
  });

/*
  if (selected) {
    //status.innerHTML = '&nbsp;Hovering: ' + selected.get('name');
    //console.log(selected);
  } else {
    //status.innerHTML = '&nbsp;';
  }*/
});


//Visualise layers on mouse click.
let clicked = null;
map.on('click', function (e) {
/*  if (clicked !== null) {
    clicked.setStyle(undefined);
    clicked = null;
  }
*/
  map.forEachFeatureAtPixel(e.pixel, function (f) {
    clicked = f;
    f.setStyle(styleGreen);
    f.set('selected',true);  //add your style here
    return true;
  });

  if (clicked) {
    let startDate = clicked.get("ObservationTimeStart");
    let endDate = clicked.get("ObservationTimeStop");
    let geomCloned = clicked.getGeometry().clone();
    let geom = geomCloned.transform('EPSG:32661', 'EPSG:4326');
    console.log(startDate);
    console.log(endDate);
    //console.log(geomCloned.getExtent());
    //console.log(geom.getExtent());
    wkt_format = new ol.format.WKT();
    geomC_wkt = wkt_format.writeGeometry(geomCloned);
    geom_wkt = wkt_format.writeGeometry(geom);
    //console.log(geomC_wkt);
    //console.log(geom_wkt);
    //status.innerHTML = '&nbsp;Hovering: ' + selected.get('name');
    //console.log(selected);

    var myurl = '/sentinel_passage_wms/getWmsResources?start=' + startDate + '&stop=' + endDate + '&wkt=' + geom_wkt +'&platform=' + platform;
    //console.log('calling controller url: ' + myurl);
    data = Drupal.ajax({
      url: myurl,
      async: true,
      dataType: 'json',
      success: function(response) {
        //console.log('Response sucess');
        var wms_urls = response[0].settings.s2wms.wms_urls;
        var titles = response[0].settings.s2wms.titles;
        renderPassage(wms_urls,titles);
        progress_bar();
        vectorLayer.getSource().forEachFeature(function (f) {
          if(f.get('selected')) {
            f.setStyle(styleGreen);

            }
            else {
              f.setStyle(styleEmpty);
            }
        });

        //console.log(response[0].settings.s2wms.wms_urls);
      },

    }).execute();

  } else {
    //status.innerHTML = '&nbsp;';
  }
});

//Custom functions
$.fn.changeLayerCallback = function(argument) {
  console.log('Change layer is called.');
  // Set textfield's value to the passed arguments.
  console.log(argument);
  wmsLayerGroup.getLayers().forEach(function(l) {
    l.getSource().updateParams({'LAYERS': argument});
  });
  progress_bar();

};

$.fn.changeDatesCallback = function(argument) {
  console.log('Change dates is called.');
  vectorLayer.getSource().clear();
  picker.clear();
  // Set textfield's value to the passed arguments.
   year = argument.years;
   month = argument.months;
   platform = argument.select_platform;

    firstDay = new Date(year, month-1, 1);
    lastDay = new Date(year, month, 0);

  let platform_lc = argument.select_platform.toLowerCase();
  console.log(platform);
  console.log(year);
  console.log(month);
wmsLayerGroup.getLayers().clear();
let now = new Date();
if(now.getFullYear() === lastDay.getFullYear() && now.getMonth() === lastDay.getMonth()) {
  kmlSource.setUrl('/modules/metno/sentinel_passage_wms/assets/'+platform_lc+'_latest_norwAOI.kml');
  rangePlugin.setDateRange(firstDay.setDate(now.getDate() - 7),lastDay.setDate(now.getDate() - 1));
  vectorLayer.setStyle(kmlInitStyleFunction);

}
else {
  kmlSource.setUrl('/modules/metno/sentinel_passage_wms/assets/'+platform_lc+'_'+year+'_norwAOI.kml');
  vectorLayer.setStyle(kmlStyleFunction);
}

picker.gotoDate(firstDay);

lockPlugin.options.minDate = firstDay;
lockPlugin.options.maxDate = lastDay;
picker.renderAll();
vectorLayer.setSource(kmlSource);
//vectorLayer.redraw(true);
vectorLayer.getSource().refresh();
vectorLayer.changed();

};


/*
$(document).on("change", 'input[type=select][name=years]', function(ev) {
  console.log('years select change');
  console.log(ev);
});
*/

/*
$(document).ajaxComplete(function(ev, xhr, opts) {
    console.log('AjaxComplete');
    console.log(ev);
    console.log(xhr);
    console.log(opts);


    });
*/

//Put default latest kml source.
/*
kmlSource.setUrl('/modules/metno/sentinel_passage_wms/assets/S2A_acquisition_plan_norwAOI.kml');
kmlStyleFunction = function(feature) {
    let now = new Date();
    //let firstDay = new Date(year, month-1, 1);
    //let lastDay = new Date(year, month, 0);
    //console.log('First day: ' +firstDay);
    //console.log('Last day: ' +lastDay);
    //console.log('Now: ' +now);
    if(now.getFullYear() === lastDay.getFullYear() && now.getMonth() === lastDay.getMonth()) {
      lastDay = now;
    }
    //firs.setDate(now.getDate()-1);
    let stop = new Date(Date.parse(feature.get("ObservationTimeStop")));
    let start = new Date(Date.parse(feature.get("ObservationTimeStart")));

    //console.log('start: ' +start);
    //console.log('stop:' + stop);

    if(stop <= lastDay && start >= firstDay) {
    //if(stop <= lastDay) {
      //console.log("setting style");
      feature.setStyle(styles['Polygon']);

    }
    else {
      feature.setStyle(styleEmpty);
    }

};
vectorLayer.setStyle(kmlStyleFunction);
vectorLayer.getSource().refresh();
vectorLayer.changed();
*/
// create a progress bar to show the loading of tiles
function progress_bar() {
  console.log("Register progress-bar")
  var tilesLoaded = 0;
  var tilesPending = 0;
  //load all S1 and S2 entries
  map.getLayers().forEach(function(layer, index, array) {

    if (layer.get('title') === 'Selected Passage' &&  layer instanceof ol.layer.Group) {
      console.log( layer instanceof ol.layer.Group);
      layer.getLayers().forEach(function(layer,index, array) {
        //console.log(array.length);
        //console.log(Object.getPrototypeOf(layer));
        if( layer instanceof ol.layer.Group ) {
        layer.getLayers().forEach(function(layer,index, array) {
        //for all tiles that are done loading update the progress bar
        //layer.getSource().refresh();
        layer.getSource().on('tileloadend', function() {
          tilesLoaded += 1;
          var percentage = Math.round(tilesLoaded / tilesPending * 100);
          document.getElementById('progress-wrapper').style.width = percentage + '%';
          // fill the bar to the end
          if (percentage >= 100) {
            document.getElementById('progress-wrapper').style.width = '100%';
            tilesLoaded = 0;
            tilesPending = 0;
          }
        });

        //for all tiles that are staring to load update the number of pending tiles
        layer.getSource().on('tileloadstart', function() {
          ++tilesPending;
        });
      });
    }
    else {
      layer.getSource().on('tileloadend', function() {
        tilesLoaded += 1;
        var percentage = Math.round(tilesLoaded / tilesPending * 100);
        document.getElementById('progress-wrapper').style.width = percentage + '%';
        // fill the bar to the end
        if (percentage >= 100) {
          document.getElementById('progress-wrapper').style.width = '100%';
          tilesLoaded = 0;
          tilesPending = 0;
        }
      });

      //for all tiles that are staring to load update the number of pending tiles
      layer.getSource().on('tileloadstart', function() {
        ++tilesPending;
      });
    }
    });
  }
  });
  //$('#bottomMapPanel').show();

}

/*
kmlStyleFunction = function(feature) {
    let now = new Date();
    //let firstDay = new Date(year, month-1, 1);
    //let lastDay = new Date(year, month, 0);
    //console.log('First day: ' +firstDay);
    //console.log('Last day: ' +lastDay);
    //console.log('Now: ' +now);
    if(now.getFullYear() === lastDay.getFullYear() && now.getMonth() === lastDay.getMonth()) {
      lastDay = now;
    }
    //firs.setDate(now.getDate()-1);
    let stop = new Date(Date.parse(feature.get("ObservationTimeStop")));
    let start = new Date(Date.parse(feature.get("ObservationTimeStart")));

    //console.log('start: ' +start);
    //console.log('stop:' + stop);

    if(stop <= lastDay && start >= firstDay) {
    //if(stop <= lastDay) {
      //console.log("setting style");
      feature.setStyle(styles['Polygon']);

    }
    else {
      feature.setStyle(styleEmpty);
    }

};
vectorLayer.setStyle(kmlStyleFunction);
vectorLayer.getSource().refresh();
vectorLayer.changed();
*/

//Add date picker select callback
picker.on('select', (e) => {
console.log('dateselect function');
//vectorLayer.getSource().clear();
wmsLayerGroup.getLayers().clear();
const { start, end } = e.detail;
firstDay = start;
lastDay = end;
//picker.clear();
//lockPlugin.options.minDate = firstDay;
//lockPlugin.options.maxDate = lastDay;
//picker.renderAll();
/*
vectorLayer.setStyle(function (feature) {


//firs.setDate(now.getDate()-1);
let stop = new Date(Date.parse(feature.get("ObservationTimeStop")));
let start = new Date(Date.parse(feature.get("ObservationTimeStart")));

//console.log('start: ' +start);
//console.log('stop:' + stop);

if(stop <= lastDay && start >= firstDay) {
//if(stop <= lastDay) {
  //console.log("setting style");
  feature.setStyle(styles['Polygon']);

}
});
*/
vectorLayer.setStyle(kmlStyleFunction);

vectorLayer.getSource().refresh();
vectorLayer.changed();
//vectorLayer.getSource().refresh();
//console.log(e);
});

let args = {
  years: year,
  months: month,
  select_platform: platform
};
$.fn.changeDatesCallback(args);

//vectorLayer.setStyle(kmlInitStyleFunction);
//kmlSource.refresh();
//kmlSource.changed();


});
},
};
})(jQuery, Drupal, drupalSettings, once);
