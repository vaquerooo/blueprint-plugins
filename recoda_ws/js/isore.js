document.addEventListener("wsInit", () => {
  "use strict";
        (function ()  {
          let e = document.getElementById("ct-page-overlay");
//! better commenzt 
                            (async() => {
                                while(!e.classList.contains("transparent")){
                                  await new Promise(resolve => setTimeout(resolve, 500));
                                } // define the condition as you like
                            let t = document.getElementById("ct-sidepanel");
                            jQuery("#collaboration-app").prependTo(".rews-cmd-right-wrap");
                            jQuery(".oxypowerpack-floating-buttons").prependTo(".rews-cmd-right-wrap");
                            jQuery('#advanced-editor-buttons').css('display', 'none');
                            setTimeout(function(){
                                recoda.adjustTopOffset();
                                    }, 50); 
                            if(t.classList.contains("ng-hide")){
                                try {
                                    $scope.switchTab('sidePanel','DOMTree')
                                    let panelator =  document.createAttribute("data-panelator");
                                    panelator.value = "dom";
                                    const node = document.getElementById("oxygen-ui").setAttributeNode(panelator);
                                  }
                                  catch(err) {
                                    recoda.toast(err);
                                  }
                            }
                            else return;
                        })();
        })();
});  
