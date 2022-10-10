<div class="row mx-0" ng-show="oxyPowerPack.currentTab=='start'">

    <div class="col-sm-2 rounded-0 border-dark p-0">
        <div class="card border-primary rounded-0 border-top-0 border-bottom-0 border-right-0" ng-show="iframeScope.component.active.name != 'root'">
            <div class="card-header rounded-0 p-2">Events <span class="badge badge-secondary add-action-button" ng-click="oxyPowerPack.events.createMethod()" style="cursor:pointer;">+</span><!--input class="form-control-sm" style="padding:0; font-size: 10px; height: 18px" type="text" ng-model="oxyPowerPack.events.search" placeholder="Search events" /-->
            </div>
            <div class="card-body helper-area p-0">
                <div class="list-group border-0 post-list admin-menu-list">
                    <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0 bg-secondary" style="cursor: default;">Global Events</div>
                    <div ng-repeat="theevent in oxyPowerPack.events.eventList | filter:oxyPowerPack.events.search" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-show="theevent.category=='global'" ng-class="{'active': oxyPowerPack.events.currentEvent == theevent}" ng-click="oxyPowerPack.events.currentEvent = theevent; oxyPowerPack.events.currentAction = null">
                        {{theevent.name}}
                        <span ng-if="iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackEvent-'+theevent.slug].length" class="badge badge-primary badge-pill pull-right">Actions</span>
                    </div>
                    <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0 bg-secondary" style="cursor: default;">Element Events</div>
                    <div ng-repeat="theevent in oxyPowerPack.events.eventList | filter:oxyPowerPack.events.search" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-show="theevent.category=='element'" ng-class="{'active': oxyPowerPack.events.currentEvent == theevent}" ng-click="oxyPowerPack.events.currentEvent = theevent; oxyPowerPack.events.currentAction = null">
                        {{theevent.name}}
                        <span ng-if="iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackEvent-'+theevent.slug].length" class="badge badge-primary badge-pill pull-right">Actions</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-2 rounded-0 border-dark p-0">
        <div class="card border-primary rounded-0 border-top-0 border-bottom-0 border-right-0" ng-show="!!oxyPowerPack.events.currentEvent">
            <div class="card-header rounded-0 p-2">
                Event Details
            </div>
            <div class="card-body helper-area p-2">
                <h5>{{oxyPowerPack.events.currentEvent.name}}</h5>
                <p>{{oxyPowerPack.events.currentEvent.description}}</p>
                <div ng-if="iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackEvent-'+oxyPowerPack.events.currentEvent.slug].length">

                    <div ng-if="oxyPowerPack.objectPropertyExists(iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackEvent-'+oxyPowerPack.events.currentEvent.slug + '-settings'],'percentage')">
                        Page percentage:
                        <select class="form-control-sm" ng-model="iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackEvent-'+oxyPowerPack.events.currentEvent.slug + '-settings'].percentage">
                            <option ng-selected="iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackEvent-'+oxyPowerPack.events.currentEvent.slug + '-settings'].percentage == item" ng-repeat="item in [5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100]" ng-value="item">{{item}}%</option>
                        </select>
                    </div>

                    <div ng-if="oxyPowerPack.objectPropertyExists(iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackEvent-'+oxyPowerPack.events.currentEvent.slug + '-settings'],'selector')">
                        Selector:
                        <div class="oxygen-file-input">
                            <input type="text" ng-blur="oxyPowerPack.events.applyCurrentAction()" ng-model="iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackEvent-'+oxyPowerPack.events.currentEvent.slug + '-settings'].selector" />
                            <div class="oxygen-selector-browse" data-option="{{'oxyPowerPackEvent-'+oxyPowerPack.events.currentEvent.slug + '-settings.selector'}}">
                                <?php _e("choose","oxygen"); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-2 rounded-0 border-dark p-0">
        <div class="card border-primary rounded-0 border-top-0 border-bottom-0 border-right-0 border-left-0" ng-show="!!oxyPowerPack.events.currentEvent">
            <div class="card-header rounded-0 p-2">
                Event Actions
                <span class="badge badge-secondary add-action-button" ng-click="oxyPowerPack.events.actionModal=true" style="cursor:pointer;">+</span>
                <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.events.currentEvent=null;oxyPowerPack.events.currentAction=null">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="card-body helper-area p-0">
                <div class="list-group border-0 post-list admin-menu-list" ui-sortable ng-model="iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackEvent-'+oxyPowerPack.events.currentEvent.slug]">
                    <div ng-repeat="action in iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackEvent-'+oxyPowerPack.events.currentEvent.slug]" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" style="cursor:move;" ng-click="oxyPowerPack.events.currentAction=action" ng-class="{'active': oxyPowerPack.events.currentAction == action}">
                        #{{$index+1}} {{oxyPowerPack.events.actionList[action.slug].name}} <small ng-if="action.comment!=''">- {{action.comment}}</small>
                        <span class="badge badge-danger badge-pill pull-right delete-action" ng-click="oxyPowerPack.events.deleteAction($index);">Delete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-4 rounded-0 border-dark p-0">
        <div class="card border-primary rounded-0 border-top-0 border-bottom-0 border-right-0" ng-if="!!oxyPowerPack.events.currentAction">
            <div class="card-header rounded-0 p-2">
                Action
                <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.events.currentAction=null">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="card-body helper-area">
                <h5>{{oxyPowerPack.events.actionList[oxyPowerPack.events.currentAction.slug].name}}</h5>
                <p>{{oxyPowerPack.events.actionList[oxyPowerPack.events.currentAction.slug].description}}</p>
                <div class="oxygen-file-input">
                    Comment:
                    <input type="text" ng-model="oxyPowerPack.events.currentAction.comment" ng-blur="oxyPowerPack.events.applyCurrentAction()" class="form-control-sm" />
                </div>

                <div class="oxygen-file-input" ng-if="oxyPowerPack.objectPropertyExists(oxyPowerPack.events.currentAction.attributes, 'text')">
                    Text:
                    <input type="text" ng-model="oxyPowerPack.events.currentAction.attributes.text" ng-blur="oxyPowerPack.events.applyCurrentAction()" class="form-control-sm" />
                </div>

                <div class="oxygen-file-input" ng-if="oxyPowerPack.objectPropertyExists(oxyPowerPack.events.currentAction.attributes, 'seconds')">
                    Seconds:
                    <input type="text" ng-model="oxyPowerPack.events.currentAction.attributes.seconds" ng-blur="oxyPowerPack.events.applyCurrentAction()" class="form-control-sm" />
                </div>

                <div class="oxygen-file-input" ng-if="oxyPowerPack.objectPropertyExists(oxyPowerPack.events.currentAction.attributes, 'class')">
                    Class:
                    <input type="text" ng-model="oxyPowerPack.events.currentAction.attributes.class" ng-blur="oxyPowerPack.events.applyCurrentAction()" class="form-control-sm" />
                </div>

                <div class="oxygen-file-input" ng-if="oxyPowerPack.objectPropertyExists(oxyPowerPack.events.currentAction.attributes, 'location')">
                    Location:
                    <input type="text" ng-model="oxyPowerPack.events.currentAction.attributes.location" ng-change="$safeAply()" class="form-control-sm" />
                </div>

                <div class="oxygen-file-input" ng-if="oxyPowerPack.objectPropertyExists(oxyPowerPack.events.currentAction.attributes, 'fading')">
                    Fading:
                    <select class="form-control-sm" ng-model="oxyPowerPack.events.currentAction.attributes.fading" ng-blur="oxyPowerPack.events.applyCurrentAction()">
                        <option ng-selected="oxyPowerPack.events.currentAction.attributes.fading == item" ng-repeat="item in [true,false]" ng-value="item">{{item}}</option>
                    </select>
                </div>

                <div class="oxygen-file-input" ng-if="oxyPowerPack.objectPropertyExists(oxyPowerPack.events.currentAction.attributes, 'selector')">
                    Selector:
                    <input type="text" ng-model="oxyPowerPack.events.currentAction.attributes.selector" ng-blur="oxyPowerPack.events.applyCurrentAction()" class="form-control-sm" />
                    <div class="oxygen-selector-browse" data-option="{{'oxyPowerPackTempSelector'}}">
                        <?php _e("choose","oxygen"); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-2 rounded-0 border-dark p-0">
        <div class="card border-primary rounded-0 border-top-0 border-bottom-0 border-right-0">
            <div class="card-header rounded-0 p-2">
                OxyPowerPack Components
            </div>
            <div class="card-body p-0">
                <div ng-show="!oxyPowerPack.currentComponent">
                    <div class="list-group border-0 post-list admin-menu-list">
                        <span ng-repeat="component in oxyPowerPack.components | filter:'!power-form-field'" ng-click="oxyPowerPack.currentComponent = component" ng-class="{'active': oxyPowerPack.currentComponent == component}" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0">
                            {{component.name}}
                        </span>
                    </div>
                </div>
                <div class="helper-area p-2" ng-show="!!oxyPowerPack.currentComponent">
                    <button type="button" ng-click="oxyPowerPack.currentComponent=null" class="btn btn-link btn-sm">&lt; Back</button>
                    <h4>{{oxyPowerPack.currentComponent.name}}</h4>
                    <p>{{oxyPowerPack.currentComponent.description}}</p>
                    <button type="button" ng-click="iframeScope.addComponent('oxy-' + oxyPowerPack.currentComponent.slug,'','',{});" class="btn btn-secondary btn-sm">Insert Component</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="oxypowerpack-action-modal-backdrop" style="background-color: rgba(0,0,0, 0.1); position: fixed; top:0; left: 0; width: 100%; height: 100%; z-index: 10000; display:flex; justify-content:center; align-items:center;" ng-if="oxyPowerPack.events.actionModal==true">
    <div class="card border-primary shadow-lg" style="width: 300px; height: 300px;">
        <div class="card-header rounded-0 p-1">
            Available Actions
            <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.events.actionModal=false">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body helper-area p-0">
            <div class="list-group border-0 post-list admin-menu-list">
                <div ng-repeat="(actionSlug, actionDetails) in oxyPowerPack.events.actionList | filter:oxyPowerPack.events.searchActions" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-click="oxyPowerPack.events.addAction(actionSlug)">
                    {{actionDetails.name}}
                </div>
            </div>
        </div>
    </div>
</div>