<div class="oxypowerpack-action-modal-backdrop" style="background-color: rgba(0,0,0, 0.1); position: fixed; top:0; left: 0; width: 100%; height: 100%; z-index: 10000; display:flex; justify-content:center; align-items:center;" ng-show="oxyPowerPack.powermap.featuresModal==true">
    <div class="card border-primary shadow-lg" style="width: 700px; height: 450px;">
        <div class="card-header rounded-0 p-1">
            Edit map features
            <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.powermap.closeFeaturesMap()">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body p-0 container">
            <div id="oppFeaturesMap"></div>
            <div class="row mx-0 my-2">
                <div class="col-sm-6">
                    <button class="btn btn-sm btn-success btn-block" ng-click="oxyPowerPack.powermap.saveFeaturesMap();" type="button">Ok</button>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-sm btn-danger btn-block" ng-click="oxyPowerPack.powermap.closeFeaturesMap();" type="button">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="oxypowerpack-action-modal-backdrop" style="background-color: rgba(0,0,0, 0.1); position: fixed; top:0; left: 0; width: 100%; height: 100%; z-index: 11000; display:flex; justify-content:center; align-items:center;" ng-show="oxyPowerPack.powermap.propertiesModal==true">
    <div class="card border-primary shadow-lg" style="width: 350px;">
        <div class="card-header rounded-0 p-1">
            Feature Properties
            <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.powermap.closePropertiesModal()">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body p-0 container">
            <div class="list-group border-0 post-list admin-menu-list">
                <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0">
                    <label ng-show="oxyPowerPack.powermap.currentlyEditingFeature.geometry.type!='Point'">Stroke Color</label>
                    <label ng-show="oxyPowerPack.powermap.currentlyEditingFeature.geometry.type=='Point'">Marker Color</label>
                    <input class="pull-right form-control-sm" type="color" id="powermap_properties_stroke_color">
                </div>
            </div>

            <div class="list-group border-0 post-list admin-menu-list">
                <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0">
                    <label ng-show="oxyPowerPack.powermap.currentlyEditingFeature.geometry.type!='Point'">Stroke Width</label>
                    <input class="pull-right form-control-sm" ng-show="oxyPowerPack.powermap.currentlyEditingFeature.geometry.type!='Point'" type="number" min="1" max="6" step="1" id="powermap_properties_stroke_width">
                </div>
            </div>

            <div class="list-group border-0 post-list admin-menu-list">
                <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0">
                    <label ng-show="oxyPowerPack.powermap.currentlyEditingFeature.geometry.type=='Polygon'">Fill Color</label>
                    <input class="pull-right form-control-sm" ng-show="oxyPowerPack.powermap.currentlyEditingFeature.geometry.type=='Polygon'" type="color" id="powermap_properties_fill_color">
                </div>
            </div>

            <div class="list-group border-0 post-list admin-menu-list">
                <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0">
                    <label ng-show="oxyPowerPack.powermap.currentlyEditingFeature.geometry.type=='Polygon'">Fill Opacity</label>
                    <input class="pull-right form-control-sm" ng-show="oxyPowerPack.powermap.currentlyEditingFeature.geometry.type=='Polygon'" type="number" min="0" max="1" step="0.05" id="powermap_properties_fill_opacity">
                </div>
            </div>

            <div class="list-group border-0 post-list admin-menu-list">
                <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0">
                    <label>Popup Content (blank = no popup)</label>
                    <textarea class="form-control" style="resize=none;" id="powermap_properties_description" rows="5" cols="20"></textarea>
                </div>
            </div>
            <div class="list-group border-0 post-list admin-menu-list">
                <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0">
                    <label for="powermap_properties_popup_open">Show popup open on load</label>
                    <input class="pull-right form-control-sm" type="checkbox" id="powermap_properties_popup_open" name="powermap_properties_popup_open" value="open">
                </div>
            </div>

            <div class="row mx-0 my-2">
                <div class="col-sm-6">
                    <button class="btn btn-sm btn-success btn-block" ng-click="oxyPowerPack.powermap.saveProperties();" type="button">Ok</button>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-sm btn-danger btn-block" ng-click="oxyPowerPack.powermap.closePropertiesModal();" type="button">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>