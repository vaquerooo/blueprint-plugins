<div class="oxypowerpack-action-modal-backdrop" style="background-color: rgba(0,0,0, 0.1); position: fixed; top:0; left: 0; width: 100%; height: 100%; z-index: 10000; display:flex; justify-content:center; align-items:center;" ng-show="oxyPowerPack.richtext.richTextModal==true">
    <div class="card border-primary shadow-lg" style="width: 700px;">
        <div class="card-header rounded-0 p-1">
            {{oxyPowerPack.richtext.windowTitle}}
            <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.richtext.close()">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body p-0 container" style="position: relative;">
	        <?php wp_editor("", "oxyPowerPackTinyMce", $settings = array(
		        "media_buttons" => false,
		        "editor_height" => 350
	        )); ?>
            <p style="font-size:12px;">Available tags: <span id="oppFormTags"></span></p>
            <div class="row mx-0 my-2">
                <div class="col-sm-7">

                </div>
                <div class="col-sm-5">
                    <button class="btn btn-sm btn-danger w-50 pull-right" ng-click="oxyPowerPack.richtext.close()" type="button">Cancel</button>
                    <button class="btn btn-sm btn-success w-50 pull-right" ng-click="oxyPowerPack.richtext.save()" type="button">Save & Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
