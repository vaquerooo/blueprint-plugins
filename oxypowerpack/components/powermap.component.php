<?php
defined('ABSPATH') or die();

class OxyPowerPackPowerMap extends OxyPowerPackEl
{
    function init() {
        $this->El->inlineJS('window.oxypowerpack_mapbox_key = "' . get_option('oxypowerpack_mapbox_key', '') .'";' . file_get_contents( plugin_dir_path(__FILE__) . 'powermap/inline.js' ));
        $this->El->CSS(plugin_dir_url(__FILE__) . '../assets/vendor/mapbox/mapbox-gl.css');
        $this->El->JS(array(
            plugin_dir_url(__FILE__) . '../assets/vendor/mapbox/mapbox-gl.js',
            plugin_dir_url(__FILE__) . 'powermap/script.js'
        ), array('jquery'));

        // This will be implemented in Oxygen 3.4 so we are going to encode the data ourselves
        //$this->El->base64_options = array(
        //    'oxy-power-map_geojson'
        //);


        add_action('ct_toolbar_component_settings', function(){
            ?>
            <?php
        });
    }

    function button_place() {
        return "oxypowerpack::power";
    }

    function button_priority() {
        return 1;
    }

    function icon() {
        return plugin_dir_url(__FILE__) . '../assets/img/icon-powermap.svg';
    }

    function description() {
        return "Powerful WebGL-based map with multiple and configurable markers and other features.";
    }

    function name() {
        return "Power Map";
    }

    function options(){
        return array(
            "server_side_render" => true
        );
    }

    function defaultCSS() {
        return '';
    }

    function render($options, $defaults, $content){
        echo '<div class="oppmap"></div>';
    }

    function controls(){

        $interactive_control = $this->addOptionControl(
            array(
                "type" => 'buttons-list',
                "name" => 'Map Type',
                "slug" => 'interactive',
                "default" => 'true'
            )
        );
        $interactive_control->setValue( array("true"=>"Interactive","false"=>"Static Map") );
        if(method_exists('OxygenElementControl', 'rebuildElementOnChange')) $interactive_control->rebuildElementOnChange();;

        $base_map_section = $this->addControlSection("base_map", __("Base Map"), "assets/icon.png", $this);

        $basemap_control = $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => 'Base Map',
                "slug" => 'base_map',
                "default" => get_option('oxypowerpack_mapbox_key', '') == '' ? 'osm' : 'mapbox_streets',
            ),
            $base_map_section
        );
        $basemap_control->setValue(
            array(
                'osm' => 'Basic OpenStreetMaps (Raster)',
                'mapbox_streets' => 'MapBox Streets (Vector)',
                'mapbox_outdoors' => "MapBox Outdoors (Vector)",
                'mapbox_light' => "MapBox Light (Vector)",
                'mapbox_dark' => "MapBox Dark (Vector)",
                'mapbox_satellite' => "MapBox Satellite (Raster)",
                'mapbox_satellite_streets' => "MapBox Satellite & Streets (Hybrid)"
            )
        );
        if(method_exists('OxygenElementControl', 'rebuildElementOnChange')) $basemap_control->rebuildElementOnChange();;

        $this->addCustomControl('<div style="border: none; position:relative; top:-25px;" ng-click="oxyPowerPack.powermap.infoModal=true;" class="oxygen-apply-button">Learn about MapBox maps</div><div ng-click="oxyPowerPack.powermap.openLocationMap();" class="oxygen-apply-button">Configure initial state</div>',"", $base_map_section);
        $buildings3d_control = $this->addOptionControl(
            array(
                "type" => 'checkbox',
                "name" => 'Show 3D buildings',
                "slug" => '3d_buildings',
                "condition" => 'base_map!=osm&&base_map!=mapbox_satellite'
            ),
            $base_map_section
        );
        $buildings3d_control->setValue('false');
        if(method_exists('OxygenElementControl', 'rebuildElementOnChange')) $buildings3d_control->rebuildElementOnChange();
        $slowfly_control = $this->addOptionControl(
            array(
                "type" => 'checkbox',
                "name" => 'Slowly fly to configured location at start',
                "slug" => 'slowfly'
            ),
            $base_map_section
        );
        $slowfly_control->setValue('false');
        if(method_exists('OxygenElementControl', 'rebuildElementOnChange')) $slowfly_control->rebuildElementOnChange();

        // Set the initial location to somewhere near the coffee shop I'm at right now
        $this->addOptionControl(
            array(
                "type" => 'text',
                "name" => 'Map Longitude',
                "slug" => 'map_lng'
            )
        )->setValue(-74.012830927893219)->hidden();
        $this->addOptionControl(
            array(
                "type" => 'text',
                "name" => 'Map Latitude',
                "slug" => 'map_lat'
            )
        )->setValue(40.704124824667701)->hidden();
        $this->addOptionControl(
            array(
                "type" => 'text',
                "name" => 'Map Zoom',
                "slug" => 'map_zoom'
            )
        )->setValue(15.4991)->hidden();
        $this->addOptionControl(
            array(
                "type" => 'text',
                "name" => 'Pitch',
                "slug" => 'map_pitch'
            )
        )->setValue(0)->hidden();
        $this->addOptionControl(
            array(
                "type" => 'text',
                "name" => 'Bearing',
                "slug" => 'map_bearing'
            )
        )->setValue(0)->hidden();
        $this->addOptionControl(
            array(
                "type" => 'text',
                "name" => 'geojson',
                "slug" => 'geojson'
            )
        )->setValue('')->hidden();


        $source_section = $this->addControlSection("source", __("Data Source (markers)"), "assets/icon.png", $this);

        $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => 'Markers Source',
                "slug" => 'markers_source',
            ),$source_section
        )->setValue(
            array(
                'manual' => 'Manual Input',
                'acf_field' => 'ACF Field (single marker)',
                'acf_repeater' => "ACF Repeater (multiple markers)"
            )
        );

        $this->addCustomControl('<div ng-click="oxyPowerPack.powermap.openFeaturesMap();" class="oxygen-apply-button" ng-if="iframeScope.component.options[iframeScope.component.active.id].model[\'oxy-power-map_markers_source\']==\'manual\'">Edit Map Data</div>','',$source_section);

        /*
        $default_marker_section = $this->addControlSection("default_marker", __("Default Marker Style"), "assets/icon.png", $this);

        $this->addOptionControl(
            array(
                "type" => 'checkbox',
                "name" => 'Custom Default Maker Icon',
                "slug" => 'custom_default_marker'
            ),
            $default_marker_section
        )->setValue('false');

        $this->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => 'Default Marker',
                "slug" => 'marker_svg',
                "default" => 'FontAwesomeicon-map-marker',
                "condition" => 'custom_default_marker=true'
            ),
            $default_marker_section
        )->setValue('FontAwesomeicon-map-marker');
        */
    }
    function afterInit() {
        if(method_exists('OxygenElementControl', 'rebuildElementOnChange')) $this->removeApplyParamsButton();
    }
}
global $oxyPowerPackComponents;
$oxyPowerPackComponents['oxy-power-map'] = new OxyPowerPackPowerMap();
