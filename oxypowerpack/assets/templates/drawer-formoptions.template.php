<div class="oxypowerpack-action-modal-backdrop" style="background-color: rgba(0,0,0, 0.1); position: fixed; top:0; left: 0; width: 100%; height: 100%; z-index: 10000; display:flex; justify-content:center; align-items:center;" ng-if="oxyPowerPack.formOptions.formOptionsModal==true">
    <div class="card border-primary shadow-lg" style="width: 450px; height: 300px;">
        <div class="card-header rounded-0 p-1">
            Field Options
            <span class="badge badge-secondary add-action-button" ng-click="oxyPowerPack.formOptions.addFormOption()" style="cursor:pointer;">+</span>
            <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.formOptions.formOptionsModal=false;oxyPowerPack.formOptions.stopEditingFormOption();">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body helper-area p-0">
            <div class="list-group border-0 post-list admin-menu-list" ng-if="oxyPowerPack.formOptions.currentFormOption==null">
                <div ng-repeat="formOption in iframeScope.component.options[iframeScope.component.active.id].model['oxy-power-form-field_oxyPowerPackFormOptions']" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-click="oxyPowerPack.formOptions.editFormOption(formOption)">
                    {{formOption.label | oppBase64}} <small>{{formOption.value | oppBase64}}</small>
                    <span class="badge badge-danger badge-pill pull-right delete-action" ng-click="oxyPowerPack.formOptions.removeFormOptionAtIndex($index);">Delete</span>
                </div>
            </div>
            <div class="helper-area p-2" ng-show="!!oxyPowerPack.formOptions.currentFormOption">
                <button type="button" ng-click="oxyPowerPack.formOptions.stopEditingFormOption()" class="btn btn-link btn-sm">&lt; Back</button>
                <h4>Option</h4>
                <div class="oxygen-file-input">
                    Label:
                    <input type="text" id="oppCurrentFormOptionLabel" class="form-control-sm" />
                </div>
                <div class="oxygen-file-input">
                    Value:
                    <input type="text" id="oppCurrentFormOptionValue" class="form-control-sm" />
                </div>
            </div>
        </div>
    </div>
</div>
