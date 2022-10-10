document.addEventListener("wsLoad", () => {
"use strict";
    if(recoda.checkLocalStorageOption("_x_wscuts-arrow", "off")) return;
    jQuery(document.getElementById('ct-artificial-viewport').contentWindow.document).on( "keydown",function(event){
        let a;
        let b;

        if (event.altKey || event.shiftKey) {
            return;
        }
        if (document.activeElement.tagName === "INPUT"){
            return;
        }
        if (document.activeElement.tagName === "TEXTAREA"){return;}
        if (document.activeElement.hasAttribute("contenteditable", "true") == true){return;}
       
        if (angular.element("#ct-controller-ui").scope().isActiveActionTab('contentEditing')) {
            return;
        }

        if (!event.ctrlKey){
            //ARROW L
            if(event.keyCode == 37){
                event.preventDefault(); 
                angular.element("#ct-controller-ui").scope().iframeScope.activateComponent(angular.element("#ct-controller-ui").scope().iframeScope.component.active.parent.id, angular.element("#ct-controller-ui").scope().iframeScope.component.active.parent.name);
            }
            //ARROW UP
            if(event.keyCode == 38){
                event.preventDefault(); 
                // a = jQuery("#ct-dom-tree-2").contents().find(" .dom-tree-node.active ").prev();
                a = jQuery("#ct-dom-tree-2 .dom-tree-node.active ").prev();
                b = a.children(".dom-tree-node-label").click();
                b.click();
             
            }
            //ARROW R
            if(event.keyCode == 39){
                event.preventDefault(); 
                a = jQuery("#ct-dom-tree-2 .dom-tree-node.active .sub-tree .dom-tree-node");
                b = a.children(".dom-tree-node-label").eq(0).click();
            }
            //ARROW DOWN
            if(event.keyCode == 40){
                event.preventDefault(); 
                a = jQuery("#ct-dom-tree-2 .dom-tree-node.active").next();
                b = a.children(".dom-tree-node-label").click();
                b.click();
            }
        
        }

        if (event.ctrlKey){
            //ARROW L
            if(event.keyCode == 37){
                event.preventDefault(); 
                jQuery("#ct-dom-tree-2 .dom-tree-node.active .ct-expand-butt").click();
            }
            //ARROW UP
            if(event.keyCode == 38){
                event.preventDefault(); 
                $scope.$broadcast('treeCollapse');
            }
            //ARROW R
            if(event.keyCode == 39){
                event.preventDefault(); 
                jQuery("#ct-dom-tree-2 .dom-tree-node.active .ct-expand-butt").click();
            
            }
            //ARROW DOWN
            if(event.keyCode == 40){
                event.preventDefault(); 
                $scope.$broadcast('treeExpand');
            }

        }
    });

    jQuery(window).on( "keydown", function(event){  
        let a;
        let b;
        if (angular.element("#ct-controller-ui").scope().isActiveActionTab('contentEditing')) {
            return;
        }
        if (event.altKey || event.shiftKey) {
            return;
        }
        if (document.activeElement.tagName === "INPUT"){
            return;
        }
        if (document.activeElement.tagName === "TEXTAREA"){return;}
        if (document.activeElement.hasAttribute("contenteditable", "true") == true){return;}
        
        if (!event.ctrlKey){
            //ARROW L
            if(event.keyCode == 37){
                event.preventDefault(); 
                angular.element("#ct-controller-ui").scope().iframeScope.activateComponent(angular.element("#ct-controller-ui").scope().iframeScope.component.active.parent.id, angular.element("#ct-controller-ui").scope().iframeScope.component.active.parent.name);
            }
            //ARROW UP
            if(event.keyCode == 38){
                event.preventDefault(); 
                // a = jQuery("#ct-dom-tree-2").contents().find(" .dom-tree-node.active ").prev();
                a = jQuery("#ct-dom-tree-2 .dom-tree-node.active ").prev();
                b = a.children(".dom-tree-node-label").click();
                b.click();
             
            }
            //ARROW R
            if(event.keyCode == 39){
                event.preventDefault(); 
                a = jQuery("#ct-dom-tree-2 .dom-tree-node.active .sub-tree .dom-tree-node");
                b = a.children(".dom-tree-node-label").eq(0).click();
            }
            //ARROW DOWN
            if(event.keyCode == 40){
                event.preventDefault(); 
                a = jQuery("#ct-dom-tree-2 .dom-tree-node.active").next();
                b = a.children(".dom-tree-node-label").click();
                b.click();
            }
        
        }

        if (event.ctrlKey){
            //ARROW L
            if(event.keyCode == 37){
                event.preventDefault(); 
                jQuery("#ct-dom-tree-2 .dom-tree-node.active .ct-expand-butt").click();
            }
            //ARROW UP
            if(event.keyCode == 38){
                event.preventDefault(); 
                $scope.$broadcast('treeCollapse');
            }
            //ARROW R
            if(event.keyCode == 39){
                event.preventDefault(); 
                jQuery("#ct-dom-tree-2 .dom-tree-node.active .ct-expand-butt").click();
            
            }
            //ARROW DOWN
            if(event.keyCode == 40){
                event.preventDefault(); 
                $scope.$broadcast('treeExpand');
            }

        }

    });

    
});   



