<?php 



add_action( 'oxygen_before_toolbar_close', 'recoda_ws_class_switcher' );
/**
 * Outputs Oxygen's controls.
 */
function recoda_ws_class_switcher() { ?>

<div id="workspace-class-switcher" class="workspsace-class-switcher"> 
    <ul class="ws-selector-node-container"
        ng-if="!iframeScope.isEditing('custom-selector')">
        <li class="ws-selector-node ws-id-node"
            ondblclick="recoda.clipSelector('id', this, event)"
            ng-click="iframeScope.switchEditToId(true)">
            <div class="ws-active-selector-box-classname ws-data-clip">{{iframeScope.getComponentSelector()}}</div>
        </li>
        <li class="ws-selector-node ws-class-node"
            ondblclick="recoda.clipSelector('class', this, event)"
            ng-class="{'ws-class-locked':iframeScope.isSelectorLocked(className)}"
            ng-repeat="(key,className) in iframeScope.componentsClasses[iframeScope.component.active.id]"
            ng-click="iframeScope.setCurrentClass(className)">
                <div class='ws-active-selector-box-classname ws-data-clip'>{{className}}</div>
                
                <div class="ws-delete-class" title="<?php _e("Remove class from component", "oxygen"); ?>" ng-click="iframeScope.removeComponentClass(className)"/> X
                </div>
               
        </li>
    </ul>

</div> 
<style>
    .workspsace-class-switcher{ margin-top: 12px}
    .ws-selector-node{
        background: var(--theme-click);
        border-radius: 4px;
        max-height: 20px;
        padding-left: 8px;
        padding-top: 4px;
        padding-bottom: 4px;
        list-style: none;
        border-left: 5px solid transparent;
        display: flex !important;
        flex-direction; row;
        flex-basis: 50px;
        min-width: fit-content;
        max-width: var(--sidebar-width);
        font-size: 12px;
        flex-grow: 1;
        position: relative;
    }
    .ws-selector-options-more .ws-class-node{
    padding-right: 8px;
    }
    .ws-selector-node-container{
        padding: 0;
        margin: 0;
        display: flex; 
        flex-direction: row;
        flex-wrap: wrap;
        gap: 8px;
    }
    .ws-class-switcher-selector-options{ display: none}
    .ws-selector-options-more .ws-class-switcher-selector-options{display: block}
    .ws-selector-options-more .ws-delete-class{ display: none}
    .ws-id-node{
        border-left-color: #266ecb;
        padding-right: 8px;
    }
    .ws-class-node{
        border-left-color: #009688;
    }
    
    .ws-selector-node:hover{
        outline: 1px solid rgba(255,255,255,.25)
    }
    .ws-active-selector-box-classname {
        margin-right: auto;
        margin-left: auto;
        max-width: var(--sidebar-width);
        padding-right: 6px;
    }
    .ws-delete-class{
        padding: 4px;
        margin-top: -4px;
        margin-bottom: -4px;
        opacity: .4;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    .ws-delete-class:hover{
        opacity: 1;
        background: #ae4747ba;
    }
    .ws-class-locked{
        border-left-color: red;
        opacity: .7;
    }
    .ws-classSwitcher_turn-off .workspace-class-switcher{
        dispay: none !important;
    }
    .ws-class-switcher-selector-options img:not(.ct-link-button-highlight){
        padding-left: 2px;
        padding-right: 2px;
    }
    .ws-class-switcher-selector-options img:not(.ct-link-button-highlight){
        opacity: 0.7;
    }
</style>

 <?php }

?>
