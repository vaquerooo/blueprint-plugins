<div class="oxypowerpack-floating-buttons">
    <img class="gotoleft" src="<?=plugin_dir_url(__FILE__)?>../img/left-arrow.png" height="28" style="height:28px;">
    <img class="toggle" src="<?=plugin_dir_url(__FILE__)?>../img/logo.png" height="28" style="height:28px;">
    <img class="gotoright" src="<?=plugin_dir_url(__FILE__)?>../img/right-arrow.png" height="28" style="height:28px;">
</div>
<div class="oxypowerpack closed" ng-class="{'closed': oxyPowerPack.drawerOpen == false && oxyPowerPack.drawerDocked == false, 'docked': oxyPowerPack.drawerDocked }"  style="background-image:url(<?=plugin_dir_url(__FILE__).'../img/drawer-background.png'?>); background-size: contain;background-position: center center;background-repeat: no-repeat;">
    <div class="container-fluid opp-maincontainer">

        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <span class="navbar-brand py-0">
                <img src="<?=plugin_dir_url(__FILE__)?>../img/logo.png" height="20" style="height:20px; margin-right: 5px;" alt="OxyPowerPack Logo">
                <small>OxyPowerPack</small>
            </span>

            <ul class="navbar-nav">
                <li class="nav-item" ng-class="{'active': oxyPowerPack.currentTab=='start'}" ng-click="oxyPowerPack.currentTab='start'" ng-show="oxyPowerPack.BEData.events_enabled=='true'">
                    <span class="nav-link py-2">Interactivity</span>
                </li>
                <li class="nav-item" ng-class="{'active': oxyPowerPack.currentTab=='wordpress'}" ng-click="oxyPowerPack.currentTab='wordpress'">
                    <span class="nav-link py-2">WordPress</span>
                </li>
                <li class="nav-item" ng-click="oxyPowerPack.maintenanceModal=true">
                    <span class="nav-link py-2">Maintenance Mode</span>
                </li>
            </ul>

            <span class="navbar-text mr-auto ml-4 text-muted">
                <span ng-show="iframeScope.component.active.name != 'root' && iframeScope.component.active.id != 0">
                    {{iframeScope.component.options[iframeScope.component.active.id].nicename}}&nbsp
                    ({{iframeScope.component.options[iframeScope.component.active.id].name}})
                    <span class="badge badge-secondary" ng-click="oxyPowerPack.attributes.attributesModal=true" style="cursor:pointer;" ng-show="oxyPowerPack.BEData.attributes_enabled=='true'">{{!iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackAttributes'].length ? 'no' : iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackAttributes'].length}} custom attributes</span>
                    <span class="badge badge-secondary" ng-click="oxyPowerPack.parallaxModal=true" style="cursor:pointer;" ng-show="oxyPowerPack.BEData.parallax_enabled=='true'">parallax speed ({{!iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackParallax'] || iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackParallax'] == "0"  ? 'normal' : iframeScope.component.options[iframeScope.component.active.id].model['oxyPowerPackParallax'] }})</span>
                </span>
            </span>

            <span class="navbar-text version-info" ng-click="oxyPowerPack.aboutModal=true" ng-class="{'text-warning': oxyPowerPack.BEData.license_status!='valid'}">
                <small>v<?=OXYPOWERPACK_VERSION?><span class="version-label"><?=OXYPOWERPACK_PREVIEW_LABEL?></span><span ng-if="oxyPowerPack.BEData.license_status!='valid'">!</span></small>
            </span>
            <button id="oxypowerpack-docked-toggle" class="btn btn-sm btn-outline-secondary" ng-class="{'active': oxyPowerPack.drawerDocked == true }" ng-click="oxyPowerPack.drawerDocked = !oxyPowerPack.drawerDocked" type="button">{{oxyPowerPack.drawerDocked ? 'Undock' : 'Dock' }}</button>
        </nav>

        <?php include_once 'drawer-wordpress.template.php'; ?>

        <?php include_once 'drawer-maintenance.template.php'; ?>

        <?php include_once 'drawer-start.template.php'; ?>

        <?php include_once 'drawer-about.template.php'; ?>

        <?php include_once 'drawer-attributes.template.php'; ?>

	    <?php include_once 'drawer-formoptions.template.php'; ?>

	    <?php include_once 'drawer-richtext.template.php'; ?>

        <?php include_once 'drawer-parallax.template.php'; ?>

	    <?php include_once 'drawer-textrotator.template.php'; ?>

        <?php include_once 'drawer-tooltip.template.php'; ?>

        <?php include_once 'drawer-mapbox.template.php'; ?>

        <?php include_once 'drawer-powermaplocation.template.php'; ?>

        <?php include_once 'drawer-powermapfeatures.template.php'; ?>

        <?php include_once 'drawer-premadeforms.template.php'; ?>

    </div>
</div>
<template id="opp-login-template">
    <div id="opp-login">
        <iframe id="opp-floating-login-iframe" src="IFRAMESRC" frameBorder="0"></iframe>
    </div>
</template>

