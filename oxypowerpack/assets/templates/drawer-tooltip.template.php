<div class="oxypowerpack-action-modal-backdrop" style="background-color: rgba(0,0,0, 0.1); position: fixed; top:0; left: 0; width: 100%; height: 100%; z-index: 10000; display:flex; justify-content:center; align-items:center;" ng-if="oxyPowerPack.tooltips.tooltipsModal==true">
    <div class="card border-primary shadow-lg" style="width: 450px; height: 300px;">
        <div class="card-header rounded-0 p-1">
            Popover for {{iframeScope.component.options[iframeScope.component.active.id].nicename}}
            <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.tooltips.tooltipsModal=false;oxyPowerPack.tooltips.stopEditingTooltip();">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body p-0 container">

            <div class="row m-0">

                <div class="col-sm-12 helper-area px-0 py-2 container">
                    <div class="row m-0">
                        <div class="col-sm-5">
                            Content Type
                        </div>
                        <div class="col-sm-7">
                            <select class="form-control form-control-sm" ng-model="oxyPowerPack.tooltips.currentTooltip.type" ng-change="oxyPowerPack.tooltips.currentTooltip.content=''">
                                <option value="text">Text</option>
                                <option value="element">HTML Element</option>
                            </select>
                        </div>
                    </div>

                    <div class="row m-0" ng-show="oxyPowerPack.tooltips.currentTooltip.type == 'text'">
                        <div class="col-sm-5">
                            Text
                        </div>
                        <div class="col-sm-7">
                            <textarea ng-model="oxyPowerPack.tooltips.currentTooltip.content" style="width:100%;"></textarea>
                        </div>
                    </div>

                    <div class="row m-0" ng-show="oxyPowerPack.tooltips.currentTooltip.type == 'element'">
                        <div class="col-sm-5">
                            Element
                        </div>
                        <div class="col-sm-7">
                            <div class="oxygen-file-input">
                                <input type="text" ng-model="oxyPowerPack.tooltips.currentTooltip.content" ng-blur="oxyPowerPack.tooltips.applyCurrentTooltip()" class="form-control-sm" />
                                <div class="oxygen-selector-browse" data-option="{{'oxyPowerPackTempSelector'}}">
                                    <?php _e("choose","oxygen"); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row m-0" ng-show="oxyPowerPack.tooltips.currentTooltip.type == 'element'">
                        <div class="col-sm-5">
                            Content Behavior
                        </div>
                        <div class="col-sm-7">
                            <div>
                                <input type="radio" ng-model="oxyPowerPack.tooltips.currentTooltip.contentCopy" name="currentTooltipContentCopy" id="currentTooltipContentCopyFalse" ng-value="false"/><label for="currentTooltipContentCopyFalse" style="display: inline; margin-left:5px;">Move the selected element into the popover</label>
                            </div>
                            <div>
                                <input type="radio" ng-model="oxyPowerPack.tooltips.currentTooltip.contentCopy" name="currentTooltipContentCopy" id="currentTooltipContentCopyTrue" ng-value="true"/><label for="currentTooltipContentCopyTrue" style="display: inline; margin-left:5px;">Copy the selected element into the popover</label>
                            </div>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-sm-5">
                            Animation
                        </div>
                        <div class="col-sm-7">
                            <select class="form-control form-control-sm" ng-model="oxyPowerPack.tooltips.currentTooltip.animation">
                                <option value="fade">Fade</option>
                                <option value="shift-away">Shift Away</option>
                                <option value="shift-toward">Shift Toward</option>
                                <option value="scale">Scale</option>
                                <option value="perspective">Perspective</option>
                            </select>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-sm-5">
                            Arrow
                        </div>
                        <div class="col-sm-7">
                            <div>
                                <input type="radio" ng-model="oxyPowerPack.tooltips.currentTooltip.arrow" name="currentTooltipArrow" id="currentTooltipArrowTrue" ng-value="true"/><label for="currentTooltipArrowTrue" style="display: inline; margin-left:5px;">Enabled</label>
                            </div>
                            <div>
                                <input type="radio" ng-model="oxyPowerPack.tooltips.currentTooltip.arrow" name="currentTooltipArrow" id="currentTooltipArrowFalse" ng-value="false"/><label for="currentTooltipArrowFalse" style="display: inline; margin-left:5px;">Disabled</label>
                            </div>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-sm-5">
                            Placement
                        </div>
                        <div class="col-sm-7">
                            <select class="form-control form-control-sm" ng-model="oxyPowerPack.tooltips.currentTooltip.placement">
                                <option value="top">Top</option>
                                <option value="top-start">Top-Start</option>
                                <option value="top-end">Top-End</option>
                                <option value="right">Right</option>
                                <option value="right-start">Right-Start</option>
                                <option value="right-end">Right-End</option>
                                <option value="bottom">Bottom</option>
                                <option value="bottom-start">Bottom-Start</option>
                                <option value="bottom-end">Bottom-End</option>
                                <option value="left">Left</option>
                                <option value="left-start">Left-Start</option>
                                <option value="left-end">Left-End</option>
                            </select>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-sm-5">
                            Max Width
                        </div>
                        <div class="col-sm-7">
                            <input type="text" class="form-control form-control-sm" ng-model="oxyPowerPack.tooltips.currentTooltip.maxWidth" />
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-sm-5">
                            Trigger
                        </div>
                        <div class="col-sm-7">
                            <select class="form-control form-control-sm" ng-model="oxyPowerPack.tooltips.currentTooltip.trigger">
                                <option value="mouseenter focus">Mouse Hover</option>
                                <option value="click focus">Click</option>
                            </select>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-sm-5">
                            Theme
                        </div>
                        <div class="col-sm-7">
                            <select class="form-control form-control-sm" ng-model="oxyPowerPack.tooltips.currentTooltip.theme">
                                <option value="">None</option>
                                <option value="light">Light</option>
                                <option value="light-border">Light Border</option>
                                <option value="material">Material</option>
                                <option value="translucent">Translucent</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mx-0 my-2">
                        <div class="col-sm-6">
                            <button class="btn btn-sm btn-block" ng-click="oxyPowerPack.tooltips.tooltipsModal=false;oxyPowerPack.tooltips.stopEditingTooltip()" type="button">Save & Close</button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-sm btn-danger btn-block" ng-click="oxyPowerPack.tooltips.tooltipsModal=false;oxyPowerPack.tooltips.deleteTooltip();" type="button">Delete Popover</button>
                        </div>
                    </div>
                </div>

                <!--div class="col-sm-6 p-0 container">
                    TODO: PREVIEW BOX
                </div-->
            </div>

        </div>
    </div>
</div>
