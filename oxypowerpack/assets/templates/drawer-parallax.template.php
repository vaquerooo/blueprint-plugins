<div class="oxypowerpack-action-modal-backdrop" style="background-color: rgba(0,0,0, 0.1); position: fixed; top:0; left: 0; width: 100%; height: 100%; z-index: 10000; display:flex; justify-content:center; align-items:center;" ng-if="oxyPowerPack.parallaxModal==true">
    <div class="card border-primary shadow-lg" style="width: 250px; height: 120px;">
        <div class="card-header rounded-0 p-1">
            Parallax Effect
            <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.parallaxModal=false;iframeScope.setOptionModel('oxyPowerPackParallax', iframeScope.component.options[iframeScope.component.active.id].model.oxyPowerPackParallax);">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body helper-area p-2">
            <p>
                Parallax Speed Value
            </p>
            <select class="form-control-sm" ng-model="iframeScope.component.options[iframeScope.component.active.id].model.oxyPowerPackParallax" ng-change="oxyPowerPack.parallaxModal=false;iframeScope.setOptionModel('oxyPowerPackParallax', iframeScope.component.options[iframeScope.component.active.id].model.oxyPowerPackParallax);">
                <option ng-selected="iframeScope.component.options[iframeScope.component.active.id].model.oxyPowerPackParallax == item.value;" ng-repeat="item in oxyPowerPack.parallaxOptions" ng-value="item.value">{{item.title}}</option>
            </select>
        </div>
    </div>
</div>
