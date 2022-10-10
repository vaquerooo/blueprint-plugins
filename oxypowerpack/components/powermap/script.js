function oxy_setup_powermap(scriptEl, powermapId, base_map, access_token, map_lng, map_lat, map_zoom, interactive, pitch, bearing, buildings_3d, geojson, slowfly){
    var mapEl = scriptEl == null ? jQuery(document.getElementById( powermapId )).find('.oppmap')[0] : jQuery(scriptEl).prev().find('.oppmap')[0];
    if(!mapEl) return;

    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    if(geojson.includes('oxy_base64_encoded::')){
        geojson = atob( geojson.replace('oxy_base64_encoded::', '') );
    }

    var mapStyle = null;

    var base_map_override = getParameterByName(powermapId);
    if(base_map_override != null && base_map_override != '') base_map = base_map_override.trim();

    // fallback to openstreetmaps if no mapbox access token is available anymore
    if(access_token.trim() == ''){
        base_map = 'osm';
    }
    switch(base_map){
        case 'mapbox_streets':
            mapStyle = 'mapbox://styles/mapbox/streets-v11';
            break;
        case 'mapbox_outdoors':
            mapStyle = 'mapbox://styles/mapbox/outdoors-v11';
            break;
        case 'mapbox_light':
            mapStyle = 'mapbox://styles/mapbox/light-v10';
            break;
        case 'mapbox_dark':
            mapStyle = 'mapbox://styles/mapbox/dark-v10';
            break;
        case 'mapbox_satellite':
            mapStyle = 'mapbox://styles/mapbox/satellite-v9';
            break;
        case 'mapbox_satellite_streets':
            mapStyle = 'mapbox://styles/mapbox/satellite-streets-v11';
            break;
        default:
            base_map = 'osm';
            mapStyle = {
                'version': 8,
                'sources': {
                    'raster-tiles': {
                        'type': 'raster',
                        'tiles': [
                            'https://a.tile.openstreetmap.org/{z}/{x}/{y}.png',
                            'https://b.tile.openstreetmap.org/{z}/{x}/{y}.png',
                            'https://c.tile.openstreetmap.org/{z}/{x}/{y}.png'
                        ],
                        'tileSize': 256,
                        'attribution':
                            ''
                    }
                },
                'layers': [
                    {
                        'id': 'simple-tiles',
                        'type': 'raster',
                        'source': 'raster-tiles',
                        'maxZoom': 17
                    }
                ]
            };
            break;
    }

    if(access_token.trim() != ''){
        mapboxgl.accessToken = access_token.trim();
    }

    var thisMap = new mapboxgl.Map({
        container: mapEl, // container id
        style: mapStyle,
        center: [map_lng, map_lat], // starting position
        zoom: slowfly == 'true' ? 11 : map_zoom, // starting zoom
        attributionControl: false,
        interactive: scriptEl == null ? false : interactive == 'true',
        pitch: pitch,
        bearing: slowfly == 'true' ? 180 : bearing
    });
    if(interactive =='true'){
        var nav = new mapboxgl.NavigationControl();
        thisMap.addControl(nav, 'bottom-right');
    }

    thisMap.on('load', function() {

        // Add the geojson
        if(geojson.trim() != ''){
            var featureData = JSON.parse(geojson);
            thisMap.addSource('user-data', {
                'type': 'geojson',
                'data': featureData
            });

            thisMap.addLayer({
                'id': 'polygons',
                'type': 'fill',
                'source': 'user-data',
                'paint': {
                    'fill-color': ['get', 'fill_color'],
                    'fill-opacity': ['get', 'fill_opacity']
                },
                'filter': ['==', '$type', 'Polygon']
            });

            thisMap.addLayer({
                'id': 'polygon-boundaries',
                'type': 'line',
                'source': 'user-data',
                'paint': {
                    'line-width': ['get','stroke_width'],
                    'line-color': ['get','stroke_color']
                },
                'filter': ['==', '$type', 'Polygon']
            });

            thisMap.addLayer({
                'id': 'lines',
                'type': 'line',
                'source': 'user-data',
                'paint': {
                    'line-width': ['get','stroke_width'],
                    'line-color': ['get','stroke_color']
                },
                'filter': ['==', '$type', 'LineString']
            });

            thisMap.on('click', 'polygons', function(e) {
                thisMap.flyTo({ center: e.lngLat });
                if(e.features[0].properties.description.trim() != '')
                    new mapboxgl.Popup()
                        .setLngLat(e.lngLat)
                        .setHTML(e.features[0].properties.description)
                        .addTo(thisMap);
            });
            thisMap.on('click', 'lines', function(e) {
                thisMap.flyTo({ center: e.lngLat });
                if(e.features[0].properties.description.trim() != '')
                    new mapboxgl.Popup()
                        .setLngLat(e.lngLat)
                        .setHTML(e.features[0].properties.description)
                        .addTo(thisMap);
            });
            // Change the cursor to a pointer when the mouse is over the states layer.
            thisMap.on('mouseenter', 'polygons', function() {
                thisMap.getCanvas().style.cursor = 'pointer';
            });
            thisMap.on('mouseleave', 'polygons', function() {
                thisMap.getCanvas().style.cursor = '';
            });
            thisMap.on('mouseenter', 'lines', function() {
                thisMap.getCanvas().style.cursor = 'pointer';
            });
            thisMap.on('mouseleave', 'lines', function() {
                thisMap.getCanvas().style.cursor = '';
            });

            featureData.features.forEach(function(marker) {
                if(marker.geometry.type != "Point") {
                    if(marker.properties.description.trim() != '' && typeof marker.properties.popup_open !== 'undefined' && marker.properties.popup_open == 'true'){
                        var coordinates = marker.geometry.type == 'Polygon' ? marker.geometry.coordinates[0] : marker.geometry.coordinates;
                        var bounds = coordinates.reduce(function(bounds, coord) {
                            return bounds.extend(coord);
                        }, new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));
                        new mapboxgl.Popup()
                            .setLngLat(bounds.getCenter()  )
                            .setHTML(marker.properties.description)
                            .addTo(thisMap);
                    }
                    return;
                }
                var theMarker = new mapboxgl.Marker({color: marker.properties.stroke_color})
                    .setLngLat(marker.geometry.coordinates)
                    .addTo(thisMap);
                theMarker.getElement().style.cursor = 'pointer';
                theMarker.getElement().onclick=function(){
                    thisMap.flyTo({ center: marker.geometry.coordinates });
                };
                if(marker.properties.description.trim() != '') theMarker.setPopup(new mapboxgl.Popup().setHTML(marker.properties.description));
                if(typeof marker.properties.popup_open !== 'undefined' && marker.properties.popup_open == 'true') theMarker.togglePopup();
            });
        }

        if(buildings_3d == 'true' && base_map != 'osm' && base_map != 'mapbox_satellite'){

            // Insert the 3D buildings layer beneath any symbol layer.
            var layers = thisMap.getStyle().layers;

            var labelLayerId;
            for (var i = 0; i < layers.length; i++) {
                if (layers[i].type === 'symbol' && layers[i].layout['text-field']) {
                    labelLayerId = layers[i].id;
                    break;
                }
            }

            thisMap.addLayer(
                {
                    'id': '3d-buildings',
                    'source': 'composite',
                    'source-layer': 'building',
                    'filter': ['==', 'extrude', 'true'],
                    'type': 'fill-extrusion',
                    'minzoom': 15,
                    'paint': {
                        'fill-extrusion-color': '#aaa',
                        // use an 'interpolate' expression to add a smooth transition effect to the
                        // buildings as the user zooms in
                        'fill-extrusion-height': [
                            'interpolate',
                            ['linear'],
                            ['zoom'],
                            15,
                            0,
                            15.05,
                            ['get', 'height']
                        ],
                        'fill-extrusion-base': [
                            'interpolate',
                            ['linear'],
                            ['zoom'],
                            15,
                            0,
                            15.05,
                            ['get', 'min_height']
                        ],
                        'fill-extrusion-opacity': 0.6
                    }
                },
                labelLayerId
            );
        }

        if(slowfly == 'true'){
            thisMap.flyTo({
                // These options control the ending camera position: centered at
                // the target, at zoom level 9, and north up.
                center: [map_lng, map_lat],
                zoom: map_zoom,
                bearing: bearing,

                // These options control the flight curve, making it move
                // slowly and zoom out almost completely before starting
                // to pan.
                speed: 0.5, // make the flying slow
                curve: 1, // change the speed at which it zooms out

                // This can be any easing function: it takes a number between
                // 0 and 1 and returns another number between 0 and 1.
                easing: function(t) {
                    return t;
                },

                // this animation is considered essential with respect to prefers-reduced-motion
                essential: true
            });
        }

    });

}
