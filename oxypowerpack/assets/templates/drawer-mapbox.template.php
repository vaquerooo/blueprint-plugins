<div class="oxypowerpack-action-modal-backdrop" style="background-color: rgba(0,0,0, 0.1); position: fixed; top:0; left: 0; width: 100%; height: 100%; z-index: 10000; display:flex; justify-content:center; align-items:center;" ng-if="oxyPowerPack.powermap.infoModal==true">
    <div class="card border-primary shadow-lg" style="width: 700px; height: 300px;">
        <div class="card-header rounded-0 p-1">
            About MapBox Base Maps
            <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.powermap.infoModal=false;">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body helper-area p-0">
            <div class="list-group border-0 post-list admin-menu-list">
                <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0">
                    <img src="<?=plugin_dir_url(__FILE__)?>../img/mapbox-preview-light.png" style="width: 175px; float:left; margin-right:30px;">
                    <p><strong>MapBox Light & Dark</strong></p>
                    <p>Mapbox Light and Mapbox Dark are subtle, full-featured maps designed to provide geographic context while highlighting the data on your analytics dashboard, data visualization, or data overlay.</p>
                </div>
                <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0">
                    <img src="<?=plugin_dir_url(__FILE__)?>../img/mapbox-preview-outdoors.png" style="width: 175px; float:left; margin-right:30px;">
                    <p><strong>MapBox Outdoors</strong></p>
                    <p>Mapbox Outdoors is a general-purpose map with curated tilesets and specialized styling tailored to hiking, biking, and the most adventurous use cases.</p>
                </div>
                <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0">
                    <img src="<?=plugin_dir_url(__FILE__)?>../img/mapbox-preview-streets.png" style="width: 175px; float:left; margin-right:30px;">
                    <p><strong>MapBox Streets</strong></p>
                    <p>Mapbox Streets is a comprehensive, general-purpose map that emphasizes accurate, legible styling of road and transit networks.</p>
                </div>
                <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0">
                    <img src="<?=plugin_dir_url(__FILE__)?>../img/mapbox-preview-satellite.png" style="width: 175px; float:left; margin-right:30px;">
                    <p><strong>MapBox Satellite</strong></p>
                    <p>Mapbox Satellite is a full global base map that is perfect as a blank canvas or an overlay for your own data.</p>
                    <p>Mapbox Satellite & Streets combines Mapbox Satellite with vector data from Mapbox Streets.</p>
                </div>
            </div>
        </div>
    </div>
</div>
