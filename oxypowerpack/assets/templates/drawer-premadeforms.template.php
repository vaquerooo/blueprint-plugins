<div class="oxypowerpack-action-modal-backdrop" style="background-color: rgba(0,0,0, 0.1); position: fixed; top:0; left: 0; width: 100%; height: 100%; z-index: 10000; display:flex; justify-content:center; align-items:center;" ng-if="oxyPowerPack.premadeForms.premadeFormsModal==true">
    <div class="card border-primary shadow-lg" style="width: 550px;">
        <div class="card-header rounded-0 p-1">
            Select a starter form
            <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.premadeForms.premadeFormsModal=false">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body helper-area p-0" style="max-height:400px;">
            <div class="list-group border-0 post-list admin-menu-list">
                <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-click="iframeScope.addComponent('oxy-power-form','','',{});oxyPowerPack.premadeForms.premadeFormsModal=false;">
                    Blank - Start from scratch
                    <div style="width: 100%; height:100px; border: 4px dotted black;"></div>
                </div>
                <div ng-repeat="premadeForm in oxyPowerPack.premadeForms.forms" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-click="oxyPowerPack.premadeForms.insertPremadeForm(premadeForm);">
                    {{premadeForm.name}}
                    <img ng-src="{{premadeForm.image}}" style="width:100%;">
                </div>
            </div>
        </div>
    </div>
</div>
