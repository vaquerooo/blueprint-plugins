(function () {
    "use strict";
    function arrowShortcuts(o){  
        if(recoda.checkLocalStorageOption("_x_wscuts-arrow", "off")) return;
        let a;
        let b;
        if (angular.element("#ct-controller-ui").scope().isActiveActionTab('contentEditing')) {
            return;
        }
        if (o.altKey || o.shiftKey) {
            return;
        }
        if (document.activeElement.tagName === "INPUT"){
            return;
        }
        if (document.activeElement.tagName === "TEXTAREA"){return;}
        if (document.activeElement.hasAttribute("contenteditable", "true") == true){return;}
        
        if (!o.ctrlKey && !o.metaKey){
            //ARROW L
            if(o.keyCode == 37){
                o.preventDefault(); 
                $scope.iframeScope.activateComponent($scope.iframeScope.component.active.parent.id, $scope.iframeScope.component.active.parent.name);
            }
            //ARROW UP
            if(o.keyCode == 38){
                o.preventDefault(); 
                if(document.querySelectorAll("#ct-dom-tree-2 .dom-tree-node.active").length < 1) return;
                var prevNode = document.querySelector("#ct-dom-tree-2 .dom-tree-node.active").previousElementSibling;
                if (prevNode && prevNode.classList.contains("dom-tree-node")) prevNode.querySelector(".dom-tree-node-label").click();
                
             
            }
            //ARROW R
            if(o.keyCode == 39){
                o.preventDefault(); 
                if(document.querySelectorAll("#ct-dom-tree-2 .dom-tree-node.active .sub-tree .dom-tree-node").length < 1) return;
                document.querySelector("#ct-dom-tree-2 .dom-tree-node.active .sub-tree .dom-tree-node .dom-tree-node-label").click();
                //if(childNode.querySelectorAll(".dom-tree-node-label").length > 0) childNode.querySelector(".dom-tree-node-label").click();
            }
            //ARROW DOWN
            if(o.keyCode == 40){
                o.preventDefault(); 
                if(document.querySelectorAll("#ct-dom-tree-2 .dom-tree-node.active").length < 1) return;
                var nexteNode = document.querySelector("#ct-dom-tree-2 .dom-tree-node.active").nextElementSibling;
                if (nexteNode && nexteNode.classList.contains("dom-tree-node")) nexteNode.querySelector(".dom-tree-node-label").click();
            }
        
        }

        if (o.ctrlKey || o.metaKey){
            //ARROW L
            if(o.keyCode == 37){
                o.preventDefault(); 
                var expand = '#ct-dom-tree-2 .dom-tree-node.active .ct-expand-butt'
                if(document.querySelectorAll(expand).length > 0){
                    document.querySelector(expand).click()
                }
            }
            //ARROW UP
            if(o.keyCode == 38){
                o.preventDefault(); 
                $scope.$broadcast('treeCollapse');
            }
            //ARROW R
            if(o.keyCode == 39){
                o.preventDefault(); 
                var expand = '#ct-dom-tree-2 .dom-tree-node.active .ct-expand-butt'
                if(document.querySelectorAll(expand).length > 0){
                    document.querySelector(expand).click()
                }
            
            }
            //ARROW DOWN
            if(o.keyCode == 40){
                o.preventDefault(); 
                $scope.$broadcast('treeExpand');
            }

        }

    }
    function functionSingleKeyShortcuts(o) {
        if (recoda.checkLocalStorageOption("_x_wscuts-single", "off")) return;
        const Oxy = $scope.iframeScope;
        // Stop event processing if Control or Command, Shift and Alt keys are active
        
        if (o.ctrlKey || o.metaKey || o.altKey || o.shiftKey) {
            return;
        }

        if (document.activeElement.tagName === "INPUT") {
            return;
        }
        if (document.activeElement.tagName === "TEXTAREA") {
            return;
        }
        if (document.activeElement.tagName === "SELECT") {
            return;
        }

        if (document.activeElement.hasAttribute("contenteditable", "true") == true) {
            return;
        }

        // Stop event processing if content editor is active

        if ($scope.isActiveActionTab('contentEditing')) {
            return;
        }

        let processed = false;
        switch (o.code) {

            case "Delete":
                o.preventDefault();
                Oxy.removeActiveComponent()
                processed = true;
                break;
            case "Digit1":
                o.preventDefault();
                Oxy.setCurrentMedia('default', true, Oxy.isEditing('class'));
                processed = true;
                break;
            case "Digit2":
                o.preventDefault();
                Oxy.setCurrentMedia('page-width', true, Oxy.isEditing('class'));
                processed = true;
                break;
            case "Digit3":
                o.preventDefault();
                Oxy.setCurrentMedia('tablet', true, Oxy.isEditing('class'));
                processed = true;
                break;
            case "Digit4":
                o.preventDefault();
                Oxy.setCurrentMedia('phone-landscape', true, Oxy.isEditing('class'));
                processed = true;
                break;
            case "Digit5":
                o.preventDefault();
                Oxy.setCurrentMedia('phone-portrait', true, Oxy.isEditing('class'));
                processed = true;
                break;
                // panelator controls START :: q = structure panel, w = selectors panel, e = stylesheet panel, r = settings panel          
            case "KeyQ":
                recoda.panelator('dom');
                processed = true;
                break;
            case "KeyW":
                recoda.panelator('selectors');
                processed = true;
                break;
            case "KeyE":
                recoda.panelator('stylesheets');
                processed = true;
                break;
            case "KeyR":
                recoda.panelator('settings');
                processed = true;
                break;
            case "KeyT":
                recoda.panelator('inspector');
                processed = true;
                break;
                // advanced option 1-4 shortcuts a=background, s = position, d= layout, f = typography              
            case "KeyA":
                o.preventDefault();
                $scope.showAllStylesFunc();
                $scope.styleTabAdvance = true;
                $scope.switchTab('advanced', 'background');
                break;
            case "KeyS":
                o.preventDefault();
                $scope.showAllStylesFunc();
                $scope.styleTabAdvance = true;
                $scope.switchTab('advanced', 'position');
                break;
            case "KeyD":
                o.preventDefault();
                $scope.showAllStylesFunc();
                $scope.styleTabAdvance = true;
                $scope.switchTab('advanced', 'layout');
                break;
            case "KeyF":
                o.preventDefault();
                $scope.showAllStylesFunc();
                $scope.styleTabAdvance = true;
                $scope.switchTab('advanced', 'typography');
                break;

                // advanced option 5-9 shortcuts, y = borders x = effects, c = css, v = js, b = attributes              
            case "KeyZ":
                o.preventDefault();
                $scope.showAllStylesFunc();
                $scope.styleTabAdvance = true;
                $scope.switchTab('advanced', 'borders');
                break;
            case "KeyX":
                o.preventDefault();
                $scope.showAllStylesFunc();
                $scope.styleTabAdvance = true;
                $scope.switchTab('advanced', 'effects');
                break;
            case "KeyC":
                o.preventDefault();
                $scope.showAllStylesFunc();
                $scope.styleTabAdvance = true;
                $scope.switchTab('advanced', 'custom-css');
                break;
            case "KeyV":
                o.preventDefault();
                $scope.showAllStylesFunc();
                $scope.styleTabAdvance = true;
                $scope.switchTab('advanced', 'custom-js');
                break;
            case "KeyB":
                o.preventDefault();
                $scope.showAllStylesFunc();
                $scope.styleTabAdvance = true;
                $scope.switchTab('advanced', 'custom-attributes');
                break;

                //command focus
            case "KeyG":
                o.preventDefault();
                document.querySelector("#tbf-cl textarea").focus();
                processed = true;
                break;
                // 
            case "Space":
                o.preventDefault();
                recoda.toggleFullscreen();
                processed = true;
                break;
                // 
        }

        if (processed) {
            o.stopImmediatePropagation();
        }
    }




    function functionCtrlShortcuts(o) {

        if (recoda.checkLocalStorageOption("_x_wscuts-ctrl", "off")) return;
        let activeTag = document.activeElement.tagName;
        // Stop event processing if Control or Command keys are inactive
        if (!o.ctrlKey && !o.metaKey) {
            return;
        }
        if (o.altKey) {
            return;
        }
        if (o.shiftKey) {
            return;
        }
        // Stop event processing if active is textarea or input
        if (activeTag === "TEXTAREA" || activeTag === "INPUT") {
            return;
        }
        // Stop event processing if content editor is active
        if (document.activeElement.hasAttribute("contenteditable", "true") == true) {
            return;
        }
        if ($scope.isActiveActionTab('contentEditing')) {
            return;
        }
        const Oxy = $scope.iframeScope;
        let processed = false;
        switch (String.fromCharCode(o.which).toLowerCase()) {
            case "h":
                o.preventDefault();
                let node = document.getElementById("oxygen-ui");
                let currentOption = node.getAttribute("data-panelator");
                if (currentOption != "history") {
                    $scope.switchTab('sidePanel', 'History');
                    node.setAttribute("data-panelator", "history");
                }
                processed = true;
                break;
            case "l":
        
            o.preventDefault();
            if(Oxy.isSelectorLocked(Oxy.activeSelectors[Oxy.component.active.id])){
                recoda.toast('Selector unlocked!');
                Oxy.component.options[Oxy.component.active.id]['model']['selector-locked'] = 'false';
                Oxy.setOption(Oxy.component.active.id, Oxy.component.active.name,'selector-locked');
                
            }else if(!Oxy.styleSetActive 
                && Oxy.selectedNodeType!=='selectorfolder'
                && Oxy.selectedNodeType!=='cssfolder'
                && Oxy.component.active.name 
                && Oxy.component.active.name != 'root' 
                && Oxy.component.active.name != 'ct_inner_content' 
                && !Oxy.isEditing('style-sheet') 
                && !$scope.isActiveName('ct_reusable') 
                && !$scope.isActiveName('ct_template') 
                && !$scope.isActiveActionTab('componentBrowser')){

                recoda.toast('Selector locked!');    
                Oxy.component.options[Oxy.component.active.id]['model']['selector-locked'] = 'true';
                Oxy.setOption(Oxy.component.active.id, Oxy.component.active.name,'selector-locked');
            }
            processed = true;
            break;
        }

        if (processed) {
            o.stopImmediatePropagation();
        }

    }


    function functionShiftShortcuts(o) {
        if (recoda.checkLocalStorageOption("_x_wscuts-shift", "off")) return;
        const Oxy = $scope.iframeScope;
        // Stop event processing if Control or Command keys are inactive
        if (o.ctrlKey) {
            return;
        }
        if (o.metaKey) {
            return;
        }
        if (o.altKey) {
            return;
        }

        // Stop event processing if it's target is not the body element
        if (document.activeElement.tagName === "TEXTAREA") {
            return;
        }
        if (document.activeElement.tagName === "INPUT") {
            return;
        }
        // Stop event processing if content editor is active
        if (document.activeElement.hasAttribute("contenteditable", "true") == true) {
            return;
        }
        if ($scope.isActiveActionTab('contentEditing')) {
            return;
        }

        let component_id = $scope.iframeScope.component.active.id;
        let parent_id = $scope.iframeScope.component.active.parent.id;
        let processed = false;

        switch (String.fromCharCode(o.which).toLowerCase()) {

            case "a":
                o.preventDefault();
                if ($scope.isActiveActionTab('componentBrowser') == false) {
                    $scope.switchActionTab('componentBrowser');
                    window.setTimeout(function(){document.querySelector('#oxygen-add-sidebar input.oxygen-add-searchbar').focus()},100) 
                }
                processed = true;
                break;
            case "w":
                o.preventDefault();
                if ($scope.isActiveActionTab('componentBrowser') == false) {
                    $scope.switchActionTab('componentBrowser');
                }
                $scope.switchTab('components', 'wordpress-');
                processed = true;
                break;
            case "s":
                o.preventDefault();
                if ($scope.isActiveActionTab('componentBrowser') == false) {
                    $scope.switchActionTab('componentBrowser');
                }
                $scope.switchTab('components', 'smart');
                processed = true;
                break;
            case "d":
                o.preventDefault();
                if ($scope.isActiveActionTab('componentBrowser') == false) {
                    $scope.switchActionTab('componentBrowser');
                }
                $scope.switchTab('components', 'library-');
                processed = true;
                break;
            case "r":
                o.preventDefault();
                if ($scope.isActiveActionTab('componentBrowser') == false) {
                    $scope.switchActionTab('componentBrowser');
                }
                $scope.switchTab('components', 'reusable_parts');
                processed = true;
                break;
            case "t":
                o.preventDefault();
                Oxy.saveReusable(component_id);
                processed = true;
                break;
            case "q":
                o.preventDefault();
                Oxy.wrapComponentWith('ct_div_block', component_id, parent_id);
                processed = true;
                break;

            case "e":
                o.preventDefault();
                $scope.processLink();
                processed = true;
                break;

            case "x":
                o.preventDefault();
                document.getElementById("tbf-s4").click();
                processed = true;
                break;
            case "n":
                o.preventDefault();
                document.getElementById("tbf-s1").click();
                processed = true;
                break;
            case "g":
                o.preventDefault();
                document.getElementById("tbf-s5").click();
                processed = true;
                break;

        }

        if (processed) {
            o.stopImmediatePropagation();
        }


    }
    function shortcutsController(e){
        if (e.shiftKey) {
            functionShiftShortcuts(e);
        } else if (e.ctrlKey || e.metaKey) {
            functionCtrlShortcuts(e);
            arrowShortcuts(e);
        } else if (e.altKey) { //do nothing
        } else if (e.shiftKey && (e.ctrlKey || e.metaKey)) { //do nothing
        } else {
            functionSingleKeyShortcuts(e);
            arrowShortcuts(e);

        }
    }

    function initEditorShortcuts() {
        document.addEventListener("wsInit", () => {
            document.addEventListener("keydown", shortcutsController);
        });
    }

    function initIframeShortcuts() {
        const iframe = document.getElementById("ct-artificial-viewport").contentWindow;
        document.addEventListener("wsLoad", () => {
            iframe.document.addEventListener("keydown", shortcutsController);
        });

    }


        initEditorShortcuts();
        initIframeShortcuts();

    

})();

/*
! Logic load all code after wsIntit evetn (Recoda object ready)
? Helper Functions
1. functionShiftShortcuts() => all shortcuts Shift + Key pattern
2. functionCtrlShortcuts() => all shortcuts Ctrl + Key pattern
3. functionSingleKeyShortcuts() => all shortcuts with single key pattern
? Main calls:
1. initEditorShortcuts() => listen keystrokes inside Oxygen EDITOR
2. initIframeShortcuts() => listen keystrokes inside Oxygen CANVAS (iframe) after iframe is loaded
*/