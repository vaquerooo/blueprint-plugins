<div class="oxypowerpack-action-modal-backdrop" style="background-color: rgba(0,0,0, 0.1); position: fixed; top:0; left: 0; width: 100%; height: 100%; z-index: 10000; display:flex; justify-content:center; align-items:center;" ng-if="oxyPowerPack.textRotator.textRotatorModal==true">
	<div class="card border-primary shadow-lg" style="width: 450px; height: 300px;">
		<div class="card-header rounded-0 p-1">
			Text Rotator
			<span class="badge badge-secondary add-action-button" ng-click="oxyPowerPack.textRotator.addText()" style="cursor:pointer;">+</span>
			<button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.textRotator.textRotatorModal=false;oxyPowerPack.textRotator.stopEditingText();">
				<span aria-hidden="true">×</span>
			</button>
		</div>
		<div class="card-body helper-area p-0">
			<div class="list-group border-0 post-list admin-menu-list" ng-if="oxyPowerPack.textRotator.currentText==null">
				<div ng-repeat="customText in iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackTextRotator']" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-click="oxyPowerPack.textRotator.currentText=customText">
					{{customText.text}}
					<span class="badge badge-danger badge-pill pull-right delete-action" ng-click="oxyPowerPack.textRotator.removeTextAtIndex($index);">Delete</span>
				</div>
			</div>
			<div class="helper-area p-2" ng-if="!!oxyPowerPack.textRotator.currentText">
				<button type="button" ng-click="oxyPowerPack.textRotator.stopEditingText()" class="btn btn-link btn-sm">&lt; Back</button>
				<div class="oxygen-file-input">
					Text
					<input type="text" ng-model="oxyPowerPack.textRotator.currentText.text" class="form-control-sm" />
				</div>
			</div>
		</div>
	</div>
</div>
