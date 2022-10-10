<div class="oxypowerpack-action-modal-backdrop" style="background-color: rgba(0,0,0, 0.1); position: fixed; top:0; left: 0; width: 100%; height: 100%; z-index: 10000; display:flex; justify-content:center; align-items:center;" ng-show="oxyPowerPack.maintenanceModal==true">
    <div class="card border-primary shadow-lg" style="width: 920px; height: 240px;">
        <div class="card-header rounded-0 p-1">
            Maintenance Mode Settings
            <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.maintenanceModal=false">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body py-0 px-1 helper-area" id="oxypowerpack-maintenance-form">
            <div ng-show="oxyPowerPack.maintenanceModeFormBusy" class="progress-bar progress-bar-striped progress-bar-animated" style="height:25px;" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        </div>
    </div>
</div>
