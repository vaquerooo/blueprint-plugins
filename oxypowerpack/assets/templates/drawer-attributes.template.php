<div class="oxypowerpack-action-modal-backdrop" style="background-color: rgba(0,0,0, 0.1); position: fixed; top:0; left: 0; width: 100%; height: 100%; z-index: 10000; display:flex; justify-content:center; align-items:center;" ng-if="oxyPowerPack.attributes.attributesModal==true">
    <div class="card border-primary shadow-lg" style="width: 450px; height: 300px;">
        <div class="card-header rounded-0 p-1">
            Custom Attributes
            <span class="badge badge-secondary add-action-button" ng-click="oxyPowerPack.attributes.addAttribute()" style="cursor:pointer;">+</span>
            <span class="badge badge-secondary add-action-button" ng-click="oxyPowerPack.attributes.migrateAttributes()" style="cursor:pointer;" ng-show="!!addCustomAttribute && !oxyPowerPack.attributes.currentAttribute">move attributes</span>
            <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.attributes.attributesModal=false;oxyPowerPack.attributes.stopEditingAttribute();">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body helper-area p-0">
            <div class="list-group border-0 post-list admin-menu-list" ng-if="oxyPowerPack.attributes.currentAttribute==null">
                <div ng-repeat="customAttribute in iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackAttributes']" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-click="oxyPowerPack.attributes.editAttribute(customAttribute)">
                    {{customAttribute.name}} <small>{{customAttribute.value | oppBase64}}</small>
                    <span class="badge badge-danger badge-pill pull-right delete-action" ng-click="oxyPowerPack.attributes.removeAttributeAtIndex($index);">Delete</span>
                </div>
            </div>
            <div class="helper-area p-2" ng-show="!!oxyPowerPack.attributes.currentAttribute">
                <button type="button" ng-click="oxyPowerPack.attributes.stopEditingAttribute()" class="btn btn-link btn-sm">&lt; Back</button>
                <h4>Attribute</h4>
                <div class="oxygen-file-input">
                    Name:
                    <input type="text" id="oppCurrentAttributeName" class="form-control-sm" />
                </div>
                <div class="oxygen-file-input">
                    Value:
                    <input type="text" id="oppCurrentAttributeValue" class="form-control-sm" />
                </div>
            </div>
        </div>
    </div>
</div>
