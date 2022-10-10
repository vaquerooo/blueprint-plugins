document.addEventListener("wsInit", () => {
    "use strict";
//define function const  
//http://davidwalsh.name/javascript-debounce-function
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };     
                    
         /* DOM manipulation  */
    // move MQ
    
    if(rewsLocalVars.oxyVersion.Version != "Err"){
        var str = rewsLocalVars.oxyVersion.Version;
        var versionBodyClass = "oxy-version-" + str.charAt(0) + str.charAt(2);
        document.querySelector("body").classList.add(versionBodyClass)
    }
    document.querySelector(".oxygen-sidebar-currently-editing").append(document.getElementById("workspace-class-switcher"));
    jQuery(".oxygen-select.oxygen-media-query-box-wrapper").insertAfter(".oxygen-add-button.oxygen-toolbar-button");
    /*Allow Alt-B on Mac to be {*/
    delete CodeMirror.keyMap.emacsy["Alt-B"]; 
    /* canvas observer*/
    recoda.roCanvas.observe(document.getElementById("ct-artificial-viewport")); 

        console.log(`[`+new Date().toISOString().slice(11,-5)+`]` + "Your Workspace is ready to use...");

    
    /* User Preference: Canvas scrollbar thin*/
    var lsCheck = false;
	if (Object.prototype.hasOwnProperty.call(localStorage, 'recoda-settings')){
		var localStore = JSON.parse(localStorage.getItem('recoda-settings'));
	}else lsCheck = true;
    if (!lsCheck){
        if(localStore["ws-canvas-scroll-style"] == "tiny"){
            jQuery("iframe").contents().find("html").toggleClass('ws-tiny-scroll');            
        }
    } 
    
    /* On load actions*/
         /*---------------|When this|--------------------------------------------------------------------|Do this|------------------------------------ */
    /* FILTERS DOM*/
    var element =  document.getElementById('ct-dom-tree-2');
    if (typeof(element) != 'undefined' && element != null)
    {
        document.getElementById("rews-filter-control").addEventListener('click', event => {
            if(event.target.classList.contains("rews-input-main")){
                let id = event.target.id;
                var dom = jQuery("#ct-sidepanel #ct-dom-tree-2")
                switch (id) {
                    case 'rews-f0':
                        jQuery("#ct-sidepanel .ct-tab-panel.ct-dom-tree-tab").toggleClass("rewsFilter--ON"); $scope.$broadcast('treeExpand');
                        break;
                    case 'rews-f1':
                        dom.toggleClass("filterDiv--ON");
                        break;
                    case 'rews-f2':
                        dom.toggleClass("filterCB--ON"); 
                        break;
                    case 'rews-f3':
                        dom.toggleClass("filterImage--ON"); 
                        break;
                    case 'rews-f4':
                        dom.toggleClass("filterHeading--ON"); 
                        break;
                    case 'rews-f5':
                        dom.toggleClass("filterText--ON");
                        break;
                    case 'rews-f6':
                        dom.toggleClass("filterRT--ON");
                        break;
                    case 'rews-f7':
                        dom.toggleClass("filterButton--ON")
                        break;
                    default:
                      console.log(`Sorry, we are out of ${expr}.`);
                  }
            }
            
        });
    }   
    jQuery(`.oxygen-sidebar-control-panel div[ng-click="switchTab('advanced', 'background')"]`).on("click", function(){recoda.createBackgroundPositionPrests()});
  
    let n = rewsLocalVars.adminURL,
    r = '<a class="oxygen-toolbar-button-dropdown-option ws-dp-hide" href="' + n + 'edit.php?post_type=ct_template">Templates</a><a class="oxygen-toolbar-button-dropdown-option ws-dp-hide" href="' + n + 'edit.php">Posts</a><a class="oxygen-toolbar-button-dropdown-option ws-dp-hide" href="' + n + 'edit.php?post_type=page">Pages</a><a class="oxygen-toolbar-button-dropdown-option ws-dp-hide" href="' + n + 'upload.php">Media</a>';
    jQuery("#oxygen-topbar .oxygen-back-to-wp-menu .oxygen-toolbar-button-dropdown").append(r)
    /* Expand/Collapse */
    jQuery(document).on("click",".oxygen-code-editor-expand", function () {
        recoda.toggleCodeView(); 
        });      
    /*  Right and Left Panel RESIZER */
    // attach drag function to Left/Right panels, CSS variables do hard lifting
	// 1) Right panel function call
    
	recoda.dragElement( 
        document.getElementById("resizer-rp"),  
        "--sidepanel-width", 
        "--resizer-r-safe-area", 
        "right" );
    // 2) Left panel function call
    recoda.dragElement( 
        document.getElementById("resizer-lp"), 
        "--sidebar-width", 
        "--resizer-l-safe-area", 
        "left" );
    // Adjust topbar offset if there is any top strip by 3rd party plugins
    recoda.adjustTopOffset();
    // attach smart actions to left and side panel, CSS variables do hard lifting for us, user pref baked to function so we have full control how we want			
    recoda.setSmartPanel(jQuery("#resizer-rp"), "--sidepanel-width", "right");
    recoda.setSmartPanel(jQuery("#resizer-lp"), "--sidebar-width", "left");

    /* COMMANDER BUTTONS*/
    jQuery("#rews-command-container").on("click","#rewsCanvasControll", function (e) { 
        $scope.iframeScope.currentMedia = "default";
        e.stopPropagation();
        let b = document.querySelector("body"),
            width = jQuery("#rewsTrueCanvasWidth").val(),
            zoom = jQuery("#rewsCanvasZoom").val();

        jQuery("#rews-canvas-settings").toggleClass("ws-ON");
        jQuery("#wsInputCanvasWidth").val(width);
        jQuery("#wsInputCanvasScale").val(zoom);
        if (b.classList.contains("ws-auto-zoom_on")){
            b.classList.add("ws-az-disable")
        }
        })
     /* RESONSIVE HELPERS BUTTONS - LEFT CMD WRAP*/
    jQuery("#rews-command-container").on("click","#rews-resp-1", function (e) { 
        e.stopPropagation(); 
        jQuery("#ct-artificial-viewport").toggleClass("rews-responsive-mode");
        jQuery("#ct-artificial-viewport").toggleClass("rews-device-body");
        jQuery("#ct-viewport-container").toggleClass("rews-scrollY");
    });   

    jQuery("#rews-command-container").on("click","#rews-resp-2", function (e) { 
            e.stopPropagation();
            let width = parseInt(jQuery("#ct-artificial-viewport").width(),10),
                height = parseInt(jQuery("#ct-artificial-viewport").height(),10),
                specifiedElement = document.getElementById('rews-responsive-settings');

            jQuery("#rews-responsive-settings").addClass("ws-ON");
            jQuery("#wsReponsiveInputWidth").val(width);
            jQuery("#wsReponsiveInputHeight").val(height);
            
            document.addEventListener('click', function(event) {
                let isClickInside = specifiedElement.contains(event.target);
                if (isClickInside) {
                  //
                }
                else {
                    jQuery("#rews-responsive-settings").removeClass("ws-ON");
                }
            });
        });
    jQuery("#rews-responsive-settings").on("click","#rewsRotateDevice", function (e) { 
        e.stopPropagation();
        let width = jQuery("#wsReponsiveInputWidth").val(),
            height = jQuery("#wsReponsiveInputHeight").val();
        recoda.setDevice(height, width);
    });
    
   //jQuery("#oxygen-sidebar").on("keyup", ".oxygen-control .oxygen-measure-box input",  debounce(function(e) {  
    jQuery("#oxygen-sidebar").on("keyup", ".oxygen-control .oxygen-color-picker input",  debounce(function(e) {  
        let element = document.getElementById("result-ws");
        const Oxy = $scope.iframeScope;
        let input = e.target,
            val = input.value,
            regExp = /[a-zA-Z%]/g,
            doesStringContainsLetters = regExp.test(val.toString());
       
            if( doesStringContainsLetters){
                if ( val !== '' && isNaN(val) && element == null && recoda.checkLocalStorageOption("_x_var_suggestion","enabled")){ recoda.inputHints(input, false);} 
            }
            
    },100));
    jQuery("#oxygen-sidebar").on("keyup", ".oxygen-control .oxygen-measure-box input",  debounce(function(e) {  
        let element = document.getElementById("result-ws");
        const Oxy = $scope.iframeScope;
        /* CHECK THIS console.log(dummy to see)
         !check why currentUnit is hardcoded
         */
        // Create a new 'change' event
        var _EVENT_CHANGE = new Event('change');
        function wsReplaceUnit(u){
            Oxy.setOptionUnit( option, u); 
            input.value = val.replaceAll( u, '');
            input.dispatchEvent(_EVENT_CHANGE);
        }
        let unit,
            c = 'Unit detector: init state',
            
            cssFunctions = ["var(", "calc(","clamp(", "min(", "max("],
            cssImportant = ["!important"],
            input = e.target,
            option = input.getAttribute('data-option'),
            val = input.value,
            regExp = /[a-zA-Z%]/g,
            doesStringContainsLetters = regExp.test(val.toString()),
            doesContainCssFunction = cssFunctions.some(cssFunction => val.toString().includes(cssFunction)),
            doesContainImportant = cssImportant.some(cssI => val.toString().includes(cssI)),
            currentUnit = Oxy.getOptionUnit(option),
            autodetected = false,
            optionFlag = false,
            globalUnitFlag = false;
            

            //! Uncaught TypeError: can't access property "includes", option is null
            if(option == null) { optionFlag = true; }
            /*else if( option.includes("aos")      ||  option.includes("opacity")  || option.includes("transition")    || 
                option.includes("delay")    ||  option.includes("duration") || option.includes("shadow")        ||
                option.includes("transform") || option.includes("filter")   || option.includes(`border-'+currentBorder+'-width `)
                || option.includes("icon")) {
                    return ;
                }
            */
            else if(option.includes('padding') || option.includes('margin') 
            || (option == 'width') || (option == 'min-width') || (option == 'max-width')
            || (option == 'height') || (option == 'min-height') || (option == 'max-height')
            || (option == 'font-size')){
                globalUnitFlag = true;
            }
            if(doesContainCssFunction){

                autodetected = true; 
                return Oxy.setOptionUnit( option, ' ');
            } 
            else if(doesContainImportant){
                console.log("important");
            }
            else if( doesStringContainsLetters){
                if(!recoda.checkLocalStorageOption("_x_unit_detection","enabled")) return;
                autodetected = true;
                if(!optionFlag){
                    if(val.toString().includes("px")) {c = 'px';recoda.log(c);          wsReplaceUnit('px'); return }
                    else if(val.toString().includes("%")) {c = '%';recoda.log(c);       wsReplaceUnit('%');; return }
                    else if(val.toString().includes("rem")) {c = 'rem';recoda.log(c);   wsReplaceUnit('rem'); return }
                    else if(val.toString().includes("em")) {c = 'rem';recoda.log(c);    wsReplaceUnit('em'); return }
                    else if(val.toString().includes("vw") || val.toString().includes("vh") ||
                            val.toString().includes("ch") || val.toString().includes("ex") || 
                            val.toString().includes("vmin") || val.toString().includes("vmax")) {
                                wsReplaceUnit(' '); return; 
                    }
                   
                }
                if ( val !== '' && isNaN(val) && element == null && recoda.checkLocalStorageOption("_x_var_suggestion","enabled")){ 
                    var isnum = /^\d+$/.test(val.toString().charAt(0));
                    
                    if(!isnum)  {            
                            recoda.inputHints(input, true);
                    }
                   
                }
                    c = 'var';recoda.log(c);
                
                
                
            }

            else if(!autodetected){
                if(currentUnit !== "px") return; 
                var localStore;
                if (Object.prototype.hasOwnProperty.call(localStorage, 'recoda-settings')){
                    localStore = JSON.parse(localStorage.getItem('recoda-settings'));
                }
                
                if (option == null) return;
                if(option.includes("margin") && !recoda.checkLocalStorageOption("_x_-unit_Margin","default")) return  Oxy.setOptionUnit( option, localStore["_x_-unit_Margin"]); 
                else if(option.includes("padding") && !recoda.checkLocalStorageOption("_x_-unit_Padding","default")) return  Oxy.setOptionUnit( option, localStore["_x_-unit_Padding"]);
                else if(option === "width" && !recoda.checkLocalStorageOption("_x_-unit_Width","default")) return  Oxy.setOptionUnit( option, localStore["_x_-unit_Width"]);
                else if(option === "max-width" && !recoda.checkLocalStorageOption("_x_-unit_MaxW","default")) return  Oxy.setOptionUnit( option, localStore["_x_-unit_MaxW"]);
                else if(option === "font-size" && !recoda.checkLocalStorageOption("_x_-unit_FontSize","default")) return  Oxy.setOptionUnit( option, localStore["_x_-unit_FontSize"]);
                else if(!recoda.checkLocalStorageOption("_x_-unit_Global","default") && globalUnitFlag) return  Oxy.setOptionUnit( option, localStore["_x_-unit_Global"]);

            }
        //$scope.iframeScope.setOptionUnit(option, 'rem')
    },100));
    // Grid Guide
	jQuery("#rews-command-container").on("click","#tbf-s5", function ()     {     jQuery("iframe").contents().find("html").toggleClass('tf-filled');    })
	// X-Mode
	jQuery("#rews-command-container").on("click","#tbf-s4", function ()     {      jQuery("iframe").contents().find("html").toggleClass('ws-x-on');
			                                                                        jQuery("iframe").contents().find("#ct-builder").toggleClass('ws-x-mode'); })
    /* QUICK FIX - fix common problems*/
    jQuery("#rews-command-container").on("click","#rews-fix", function ()   {      
        jQuery("#recoda-var-import").remove();
        jQuery(".oxypowerpack-floating-buttons").prependTo(".rews-cmd-right-wrap"); 
        jQuery("#collaboration-app").prependTo(".rews-cmd-right-wrap");
        recoda.adjustTopOffset();  
        recoda.toast("Quick Fixes applied!") 
    })
    /* Import StyleSheets inside Ediotr */
    jQuery("#rews-command-container").on("click","#rews-ssimp", function ()     {      recoda.loadStyleSheetsInEditor(); })
    jQuery("#rews-command-container").on("click","#rews-pow", function ()       {     recoda.toast("feature is under development") })
    /* LIVE SERVER */
    jQuery("#rews-command-container").on("click","#tbf-s1", function ()         {      recoda.launchLiveServer();  
        let lsObserver = new MutationObserver(function(mutations) {
            recoda.reloadLiveServer();
          });
        let lsTarget = document.querySelector('#ct-page-overlay');
          lsObserver.observe(lsTarget, {
            attributes: true
          });
    
    })
    jQuery("#rews-command-container").on("click","#ws-detach-ss", function ()         {      recoda.openWorkspaceStudio();
    
    })
    jQuery('.oxygen-history-button').on("click", function ()     {   recoda.panelator("history")    })
    /* FIND WHY THIS */
    jQuery(".oxygen-add-section-accordion").on("click", function ()     {      $scope.iframeScope.designSetSubTab = 0 })
    //Open Left Panel on ADD+
    jQuery(".oxygen-add-button.oxygen-toolbar-button").on("click", function ()     {      recoda.openSmartPanel(jQuery("#resizer-lp"), "--sidebar-width"); })
    /* ALT + CLICK on topbar */
    jQuery('#oxygen-topbar').on('click', function(event){
        if(event.altKey){
        
            var a = jQuery('#oxygen-topbar').css( "height" );
            var h = parseInt(a,10);
            
            if (h > 50)    {document.documentElement.style.setProperty('--topbar-height', (40 + "px"));}
            if (h < 50)    {document.documentElement.style.setProperty('--topbar-height', (58 + "px"));}
        }
    });
   /* ALT + CLICK on selectors */
    jQuery('#workspace-class-switcher').on('click', function(e)   {  if(e.altKey)  { 
    
        requestAnimationFrame(()=>{
            document.querySelector('.oxygen-active-selector-box').click();
        })
    
    }});
   /* Set canvas Zoom*/
   jQuery("#wsInputCanvasWidth").on("keyup", function (e) {
    if (e.key === "Enter" || e.keyCode === 13 || e.keyCode === 32) {
        e.preventDefault();
        var val= jQuery(this).val();
        recoda.setCanvasPX(val)
    }
    });
    jQuery("#wsInputCanvasScale").on("keyup", function (e) {
        if (e.key === "Enter" || e.keyCode === 13 || e.keyCode === 32) {
            e.preventDefault();
            var val= jQuery(this).val();
            recoda.setCanvasZoom(val);
        }
        });
    /* RESPONSIVE HELPERS, Set width and height of canvas */
    jQuery("#wsReponsiveInputHeight").on("keyup", function (e) {
        const Oxy = $scope.iframeScope;
        if (e.key === "Enter" || e.keyCode === 13 || e.keyCode === 32) {
            e.preventDefault(); 
            let dwControl = jQuery("#wsReponsiveInputWidth"),
                deviceWidth = parseInt(dwControl.val(), 10),
                mq_PW = parseInt(Oxy.mediaList['page-width']['maxSize'], 10),
                curVal= jQuery(this).val();
            recoda.setDevice(curVal, deviceWidth);   
            
        }
        });
        jQuery("#wsReponsiveInputWidth").on("keyup", function (e) {
            if (e.key === "Enter" || e.keyCode === 13 || e.keyCode === 32) {
                e.preventDefault();
                let dwControl = jQuery("#wsReponsiveInputHeight"),
                    deviceHeight = parseInt(dwControl.val(), 10),
                    curVal= jQuery(this).val();
                recoda.setDevice(deviceHeight, curVal);
            }
        });
    
        /* Commander start CLI */
	jQuery("#tbf-cli").on("keyup", function (e) {
		if (e.key === "Enter" || e.keyCode === 13 || e.keyCode === 32) {
			e.preventDefault();
			let myCommand = jQuery("#tbf-cli").val();
            recoda.lastCommand = myCommand;
			jQuery("#tbf-cli").val("");
            recoda.executeCommand(myCommand);
            if (myCommand[0] === ">") {   $scope.iframeScope.rebuildDOM();   }			
        }
    }); /* Commander JS end */
    /* RECODA REBIN / JSBIN / CODEPEN */
    document.querySelector(".rews-php-handle").addEventListener('dblclick', function (e) {
        jQuery('.rews-parent-box ').addClass('blockpad-animate').delay(2000).queue(function( next ){
            jQuery(this).removeClass('blockpad-animate'); 
            next();
        });
        var c1 = document.querySelector(".rews-code-container-1");
        var c2 = document.querySelector(".rews-code-container-2");
        var c3 = document.querySelector(".rews-code-container-3");
        var h1 = c1.offsetHeight;
        var h2 = c2.offsetHeight;
        var h3 = c3.offsetHeight;
        var h = h1 + h2 + h3;
        var h_h = h / 2;
        var h_t = h / 3;
        var h_f = h - 60;
        if (h1 < h_h) {
            c1.setAttribute('style', 'flex-basis:' + h_f + 'px;');
            c2.setAttribute('style', 'flex-basis:' + 30 + 'px;');
            c3.setAttribute('style', 'flex-basis:' + 30 + 'px;');
        }
        else {
            c1.setAttribute('style', 'flex-basis:' + h_t + 'px;');
            c2.setAttribute('style', 'flex-basis:' + h_t + 'px;');
            c3.setAttribute('style', 'flex-basis:' + h_t + 'px;');
        }
    });
    document.querySelector(".rews-css-handle").addEventListener('dblclick', function (e) {
        jQuery('.rews-parent-box ').addClass('blockpad-animate').delay(2000).queue(function( next ){
            jQuery(this).removeClass('blockpad-animate'); 
            next();
        });
        var c1 = document.querySelector(".rews-code-container-1");
        var c2 = document.querySelector(".rews-code-container-2");
        var c3 = document.querySelector(".rews-code-container-3");
        var h1 = c1.offsetHeight;
        var h2 = c2.offsetHeight;
        var h3 = c3.offsetHeight;
        var h = h1 + h2 + h3;
        var h_h = h / 2;
        var h_t = h / 3;
        var h_f = h - 60;
        if (h2 < h_h) {
            c1.setAttribute('style', 'flex-basis:' + 30 + 'px;');
            c2.setAttribute('style', 'flex-basis:' + h_f + 'px;');
            c3.setAttribute('style', 'flex-basis:' + 30 + 'px;');
        }
        else {
            c1.setAttribute('style', 'flex-basis:' + h_t + 'px;');
            c2.setAttribute('style', 'flex-basis:' + h_t + 'px;');
            c3.setAttribute('style', 'flex-basis:' + h_t + 'px;');
        }

    });
    document.querySelector(".rews-js-handle").addEventListener('dblclick', function (e) {
        jQuery('.rews-parent-box ').addClass('blockpad-animate').delay(2000).queue(function( next ){
            jQuery(this).removeClass('blockpad-animate'); 
            next();
        });
        var c1 = document.querySelector(".rews-code-container-1");
        var c2 = document.querySelector(".rews-code-container-2");
        var c3 = document.querySelector(".rews-code-container-3");
        var h1 = c1.offsetHeight;
        var h2 = c2.offsetHeight;
        var h3 = c3.offsetHeight;
        var h = h1 + h2 + h3;
        var h_h = h / 2;
        var h_t = h / 3;
        var h_f = h - 60;
        if (h3 < h_h) {
            c1.setAttribute('style', 'flex-basis:' + 30 + 'px;');
            c2.setAttribute('style', 'flex-basis:' + 30 + 'px;');
            c3.setAttribute('style', 'flex-basis:' + h_f + 'px;');
        }
        else {
            c1.setAttribute('style', 'flex-basis:' + h_t + 'px;');
            c2.setAttribute('style', 'flex-basis:' + h_t + 'px;');
            c3.setAttribute('style', 'flex-basis:' + h_t + 'px;');
        }

    });
    /*JS DRAG */
    JSdragElement(document.querySelector(".rews-js-handle"));

    function JSdragElement(elmnt) {
        var he1;
        var he2;
        var he3;
        var diff;
        var he2_ = 0;
        var startY;
        var c1 = document.querySelector(".rews-code-container-1");
        var c2 = document.querySelector(".rews-code-container-2");
        var c3 = document.querySelector(".rews-code-container-3");
        var h1 = c1.offsetHeight;
        var h2 = c2.offsetHeight;
        var h3 = c3.offsetHeight;
        elmnt.onmousedown = dragMouseDown;

        function dragMouseDown(e) {
            h1 = c1.offsetHeight;
            h2 = c2.offsetHeight;
            h3 = c3.offsetHeight;
            e = e || window.event;
            e.preventDefault();
            // get the mouse cursor position at startup:
            startY = e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;

        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            // calculate the new cursor position:
            diff = startY - e.clientY;


            he3 = h3 + diff;

            he2 = h2 - diff;

            if (he3 > 0) {
                c3.setAttribute('style', 'flex-basis:' + he3 + 'px;');
                if (he2 > 28) {
                    c2.setAttribute('style', 'flex-basis:' + he2 + 'px;');
                }
                else {
                    c2.setAttribute('style', 'flex-basis:' + 30 + 'px;');
                }

            }


            if (he2 < 0) {
                he1 = h1 + he2;
                if (he2_ > he2) {
                    c1.setAttribute('style', 'flex-basis:' + he1 + 'px;');
                }

                if (he1 < 30) {
                    h1 = c1.offsetHeight;
                    h2 = c2.offsetHeight;
                    h3 = c3.offsetHeight;
                    startY = e.clientY;
                }
            }
            he2_ = he2;
            if (he2 < -100) {
                h1 = c1.offsetHeight;
                h2 = c2.offsetHeight;
                h3 = c3.offsetHeight;
                startY = e.clientY;
            }
        }

        function closeDragElement() {

            // stop moving when mouse button is released:
            document.onmouseup = null;
            document.onmousemove = null;
            h1 = c1.offsetHeight;
            h2 = c2.offsetHeight;
            h3 = c3.offsetHeight;
            c1.setAttribute('style', 'flex-basis:' + h1 + 'px;');
            c2.setAttribute('style', 'flex-basis:' + h2 + 'px;');
            c3.setAttribute('style', 'flex-basis:' + h3 + 'px;');
        }
    }
    /* CSS DRAG */
    dragElement(document.querySelector(".rews-css-handle"));

    function dragElement(elmnt) {
        var he1;
        var he2;
        var he3;
        var diff;
        var startY;
        elmnt.onmousedown = dragMouseDown;
        var c1 = document.querySelector(".rews-code-container-1");
        var c2 = document.querySelector(".rews-code-container-2");
        var c3 = document.querySelector(".rews-code-container-3");
        var h1 = c1.offsetHeight;
        var h2 = c2.offsetHeight;
        var h3 = c3.offsetHeight;





        function dragMouseDown(e) {
            h1 = c1.offsetHeight;
            h2 = c2.offsetHeight;
            h3 = c3.offsetHeight;
            e = e || window.event;
            e.preventDefault();
            // get the mouse cursor position at startup:
            startY = e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            // calculate the new cursor position:
            diff = startY - e.clientY;

            // set the element's new position:

            he1 = h1 - diff;
            he2 = h2 + diff;

            if (he1 > 25) {
                c1.setAttribute('style', 'flex-basis:' + he1 + 'px;');
                c2.setAttribute('style', 'flex-basis:' + he2 + 'px;');
            }
            if (he2 < 25) {
                he3 = h3 + he2;
                c3.setAttribute('style', 'flex-basis:' + he3 + 'px;');
            }
            if (he3 < 30) {
                h1 = c1.offsetHeight;
                h2 = c2.offsetHeight;
                h3 = c3.offsetHeight;
                startY = e.clientY;
            }
        }
        function closeDragElement() {
            // stop moving when mouse button is released:
            document.onmouseup = null;
            document.onmousemove = null;
            h1 = c1.offsetHeight;
            h2 = c2.offsetHeight;
            h3 = c3.offsetHeight;
            c1.setAttribute('style', 'flex-basis:' + h1 + 'px;');
            c2.setAttribute('style', 'flex-basis:' + h2 + 'px;');
            c3.setAttribute('style', 'flex-basis:' + h3 + 'px;');
        }
    }

    /* PHP/HTML */
    var ExcludedIntelliSenseTriggerKeys =
    {
    "8": "backspace",
    "9": "tab",
    "13": "enter",
    "16": "shift",
    "17": "ctrl",
    "18": "alt",
    "19": "pause",
    "20": "capslock",
    "27": "escape",
    "32": "space",
    "33": "pageup",
    "34": "pagedown",
    "35": "end",
    "36": "home",
    "37": "left",
    "38": "up",
    "39": "right",
    "40": "down",
    "45": "insert",
    "46": "delete",
    "50": "quote",
    "91": "left window key",
    "92": "right window key",
    "93": "select",
    "107": "add",
    "109": "subtract",
    "110": "decimal point",
    "111": "divide",
    "112": "f1",
    "113": "f2",
    "114": "f3",
    "115": "f4",
    "116": "f5",
    "117": "f6",
    "118": "f7",
    "119": "f8",
    "120": "f9",
    "121": "f10",
    "122": "f11",
    "123": "f12",
    "144": "numlock",
    "145": "scrolllock",
    "186": "semicolon",
    "187": "equalsign",
    "188": "comma",
    "189": "dash",
    "190": "period",
    "191": "slash",
    "192": "graveaccent",
    "220": "backslash",
    "222": "quote"
    }

    recoda.myTextAreaPHP = document.querySelector("#rews-cm-php");
    recoda.myCodeMirrorPHP = CodeMirror(
		function (elt)  {    recoda.myTextAreaPHP.parentNode.replaceChild(elt, recoda.myTextAreaPHP);
		},
		{ value: recoda.myTextAreaPHP.value, 
      mode: "application/x-httpd-php",  
      lineWrapping: true,
      lineNumbers: true,
      recodaFlag: true,
      theme: "dracula",
      matchTags: {bothTags: true},
      extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); },
                  "Ctrl-J": "toMatchingTag",
                  "Ctrl-Space": "autocomplete", 
                  "Ctrl-D": function(){recoda.blockpadSaveToCodeBlock();},
                  "Ctrl-S": function(){recoda.blockpadSaveToCodeBlock(); $scope.iframeScope.savePage();},
                  "Cmd-Q": function(cm){ cm.foldCode(cm.getCursor()); },
                  "Cmd-J": "toMatchingTag",
                  "Cmd-Space": "autocomplete", 
                  "Cmd-D": function(){recoda.blockpadSaveToCodeBlock();},
                  "Cmd-S": function(){recoda.blockpadSaveToCodeBlock(); $scope.iframeScope.savePage();},
        },  
      foldGutter: true,
      gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
      
      autoCloseTags: {
        indentTags: [false]
    } }
	);
    recoda.myCodeMirrorPHP.on("keyup", function(editor, event)
    { 
        var __Cursor = editor.getDoc().getCursor();
        var token = editor.getTokenAt(__Cursor);

        if (!editor.state.completionActive &&
            !ExcludedIntelliSenseTriggerKeys[(event.keyCode || event.which).toString()]
            && !(token.string === '.' || token.string === '#')
            && !(token.string === '~' || token.string === '+')
            && !(token.string === '*' )
            && !(token.string === '(' || token.string === ')')
            && !(token.string === '{' || token.string === '}'))
        {
            CodeMirror.commands.autocomplete(editor, null);
        }
    });
    recoda.myCodeMirrorPHP.foldCode(CodeMirror.Pos(0, 0));
    recoda.myCodeMirrorPHP.foldCode(CodeMirror.Pos(34, 0));
    recoda.myCodeMirrorPHP.refresh();
    /* CSS */
    recoda.myTextAreaCSS = document.querySelector("#rews-cm-css");
    recoda.myCodeMirrorCSS = CodeMirror(
		function (elt)  {    recoda.myTextAreaCSS.parentNode.replaceChild(elt, recoda.myTextAreaCSS);
		},
		{ value: recoda.myTextAreaCSS.value, 
      mode: "css", 
      lineWrapping: true,
      lineNumbers: true,
      autoCloseBrackets: true,
      matchBrackets: true,
      recodaFlag: true,
      theme: "dracula",
      extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); },
                  "Ctrl-Space": "autocomplete", 
                  "Ctrl-D": function(){recoda.blockpadSaveToCodeBlock();},
                  "Ctrl-S": function(){recoda.blockpadSaveToCodeBlock(); $scope.iframeScope.savePage();},
                  "Ctrl-B": function(){   customSnippet(recoda.generateBreakpointsArray(), recoda.myCodeMirrorCSS );  },
                  "Cmd-Q": function(cm){ cm.foldCode(cm.getCursor()); },
                  "Cmd-Space": "autocomplete", 
                  "Cmd-D": function(){recoda.blockpadSaveToCodeBlock();},
                  "Cmd-S": function(){recoda.blockpadSaveToCodeBlock(); $scope.iframeScope.savePage();},
                  "Cmd-B": function(){   customSnippet(recoda.generateBreakpointsArray(), recoda.myCodeMirrorCSS);  },
    },
      foldGutter: true,
      gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"] }
	);
    recoda.myCodeMirrorCSS.on("keyup", function(editor, event)
    { 
        var __Cursor = editor.getDoc().getCursor();
        var token = editor.getTokenAt(__Cursor);
        if(token.string === "."){
            //prepare data for suggestion
         
        }
        else if (!editor.state.completionActive &&
            !ExcludedIntelliSenseTriggerKeys[(event.keyCode || event.which).toString()]
            && !( token.string === '#')
            && !(token.string === '~' || token.string === '+')
            && !(token.string === '*' || token.string === '>')
            && !(token.string === '(' || token.string === ')')
            && !(token.string === '{' || token.string === '}'))
        {
            CodeMirror.commands.autocomplete(editor, null);
        }
        
    });
    recoda.myCodeMirrorCSS.foldCode(CodeMirror.Pos(0, 0));
    recoda.myCodeMirrorCSS.refresh();
    /* JS */
    

    
      
    recoda.myTextAreaJS = document.querySelector("#rews-cm-js");
    recoda.myCodeMirrorJS = CodeMirror(
		function (elt)  {    recoda.myTextAreaJS.parentNode.replaceChild(elt, recoda.myTextAreaJS);
		},
		{ value: recoda.myTextAreaJS.value, 
      mode: "javascript", 
      autoCloseBrackets: true,
      matchBrackets: true,
      lineWrapping: true,
      hintOptions: {globalScope: {document}},
      lineNumbers: true,
      extraKeys: {  "Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); },
                    "Ctrl-D": function(){recoda.blockpadSaveToCodeBlock();},
                    "Ctrl-S": function(){recoda.blockpadSaveToCodeBlock(); $scope.iframeScope.savePage();},
                    "Cmd-Q": function(cm){ cm.foldCode(cm.getCursor()); },
                    "Cmd-D": function(){recoda.blockpadSaveToCodeBlock();},
                    "Cmd-S": function(){recoda.blockpadSaveToCodeBlock(); $scope.iframeScope.savePage();},
                    },
      foldGutter: true,
      gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
      theme: "dracula" }
	);
    recoda.myCodeMirrorJS.foldCode(CodeMirror.Pos(0, 0));
    
    recoda.myCodeMirrorJS.on("keyup", function(editor, event)
    { 
        var __Cursor = editor.getDoc().getCursor();
        var token = editor.getTokenAt(__Cursor);

        if (!editor.state.completionActive &&
            !ExcludedIntelliSenseTriggerKeys[(event.keyCode || event.which).toString()]
            && !(token.string === '(' || token.string === ')')
            && !(token.string === '{' || token.string === '}'))
        {
            CodeMirror.commands.autocomplete(editor, null, {hint: recoda.hintingFunction});
        }
    });
    recoda.myCodeMirrorJS.refresh();
    
    
    //---------------------------------------|  STYLE INSPECTOR CODEMIRROR THINGS   |------------------------------------
    /* STYLE INSPECTOR -    CSS overview */
    
    recoda.myTextArea = document.querySelector("#tbf-overview-txt");
	recoda.myCodeMirror = CodeMirror(
		function (elt)  {    recoda.myTextArea.parentNode.replaceChild(elt, recoda.myTextArea);
		},
		{   value: recoda.myTextArea.value, 
            mode: "css", 
            readOnly: true, 
            lineWrapping: true,
            theme: "default" 
        }
	);


     //---------------------------------------|  COMMAND LINE CODEMIRROR THINGS   |------------------------------------
    
    function historySnippet() {
        CodeMirror.showHint(recoda.cmdCodeMirror, function () {
            var snippets = recoda.commandHistoryItems;
            var cursor = recoda.cmdCodeMirror.getCursor();
            var token = recoda.cmdCodeMirror.getTokenAt(cursor);
            var start = token.start;
            var end = cursor.ch;
            var line = cursor.line;
            var currentWord = token.string;
            var list = snippets.filter(function (item) {
                return item.text.indexOf(currentWord) >= 0;
            });
            return {
                list: list.length ? list : snippets,
                from: CodeMirror.Pos(line, start),
                to: CodeMirror.Pos(line, end)
            };
        }, { completeSingle: false });
    }


    //---------------------------------------|  CANVAS CONTROL - Generate breakponts to string  |------------------------------------
    
    document.getElementById("rewsCanvasControll").addEventListener('contextmenu', function(e) {
        e.preventDefault();
        var value, mq, minMax = 'max';

        if(e.ctrlKey || e.metaKey) minMax = 'min';

        value = document.getElementById("rewsTrueCanvasWidth").value,
        mq = `@media screen and (`+minMax+`-width:`+ value +`px) { }`;

        recoda.textToClipboard(mq);
        recoda.toast("CLIPBOARD: " + mq ); 
    }, false);
    

    //---------------------------------------|  BREAKPOINTS (MQ) - Generate breakponts to string  |------------------------------------
    /*! see this part, new events added, after 4.0 stable try to remove async await and load after recoda ready event */

    (async() => { 
        var n = 0;
        while(!document.querySelector(".oxygen-media-query-panel .oxygen-media-queries-repeater") ){
            if(n > 10) return;
            n++;
            await new Promise(resolve => setTimeout(resolve, 1000));
        } 
            
        const mediaQueryNodeArray = document.querySelectorAll(".oxygen-media-queries-repeater");
        for (let i = 0; i < mediaQueryNodeArray.length; i++) {
            mediaQueryNodeArray[i].addEventListener('contextmenu', function(e) {
                e.preventDefault();
                let selector = '';
                if(e.altKey){
                    const Oxy = $scope.iframeScope,
                    {id} = $scope.iframeScope.component.active;
                    let val;
                    val = Oxy.currentClass;
                    if (!Oxy.currentClass)   val ="#" + Oxy.component.options[id].selector;
                    else val = "." + Oxy.currentClass;
                    selector = `${val} {}`;
                }
                let minMax = 'max';
                if(e.ctrlKey || e.metaKey) minMax = 'min';
                let m = $scope.iframeScope.mediaList,
                    mediaQuery = mediaQueryNodeArray[i].firstElementChild.id,
                    clipTxt,
                    flag = true,
                    page = m['page-width']['maxSize'],
                    tablet = m['tablet']['maxSize'],
                    phoneLandscape =  m['phone-landscape']['maxSize'],
                    phonePortrait = m['phone-portrait']['maxSize'];


                switch( mediaQuery ) {
                    case "oxy-media-query-page-width":
                        clipTxt = `@media screen and ( ${minMax}-width: ${page}) { ${selector} }`;
                    break;
                    case "oxy-media-query-tablet":
                        clipTxt = `@media screen and ( ${minMax}-width: ${tablet}) { ${selector} }`;
                    break;
                    case "oxy-media-query-phone-landscape":
                        clipTxt = `@media screen and ( ${minMax}-width: ${phoneLandscape}) { ${selector} }`;
                    break;
                    case "oxy-media-query-phone-portrait":
                        clipTxt = `@media screen and ( ${minMax}-width: ${phoneLandscape}) { ${selector} }`;
                    break; 
                    default:
                        clipTxt = "";
                        flag = false
                        break;
                }
                if(flag){
                    recoda.toast("CLIPBOARD: " + clipTxt ); 
                    clipTxt = clipTxt.replace(/;/g, ";\n").replace(/{/g, "{\n").replace(/}/g, "}\n").replace(/:/g, ": ");
                    recoda.textToClipboard(clipTxt);
                    
                }
                

            }, false);
        }
    })();


    //---------------------------------------|  GENERATE BREAKPOINTS  |------------------------------------

    function generateBreakpointsArray(control){
        var m = $scope.iframeScope.mediaList;
        var minMax = 'max';
        var txt = "Desktop First"
        if(control == 'min'){
            txt = "Mobile First"
            minMax = 'min'}
        var strPageW = `@media screen and (`+minMax+`-width:`+ m['page-width']['maxSize'] +`) { }`;
        var strTablet = `@media screen and (`+minMax+`-width:`+ m['tablet']['maxSize'] +`) { }`;
        var strPhoneLandscape = `@media screen and (`+minMax+`-width:`+ m['phone-landscape']['maxSize'] +`) { }`;
        var strPhonPortrait = `@media screen and (`+minMax+`-width:`+ m['phone-portrait']['maxSize'] +`) { }`;
        var breakpointsArray = [
            {  
                "text": strPageW,
                "displayText": txt + " @media: Page"
            },
            {
                "text": strTablet,
                "displayText": txt + " @media: Tablet"
            },
            {
                "text": strPhoneLandscape,
                "displayText": txt + " @media: Phone-Landscape"
            },
            {
                "text": strPhonPortrait,
                "displayText": txt + " @media: Phone-Portrait"
            },
        ];
        return breakpointsArray;
    }
    //---------------------------------------|  Codemirror custom snippet  |------------------------------------
    //---- EXTENDING Show Hint from Codemirror
    function customSnippet(objArray, ref) {
        CodeMirror.showHint(ref, function () {
            var snippets, cursor, token, start, end, line, currentWord, list; 

            snippets = objArray;
            cursor = ref.getCursor();
            token = ref.getTokenAt(cursor);
            start = token.start;
            end = cursor.ch;
            line = cursor.line;
            currentWord = token.string;
            list = snippets.filter(function (item) {
                return item.text.indexOf(currentWord) >= 0;
            });
            return {
                list: list.length ? list : snippets,
                from: CodeMirror.Pos(line, start),
                to: CodeMirror.Pos(line, end)
            };
        }, { completeSingle: false });
    }
    

    //---------------------------------------|  INIT AND SETUP COMMAND LINE CODEMIRROR |------------------------------------

    recoda.cmdTextArea = document.querySelector("#tbf-cli");
	recoda.cmdCodeMirror = CodeMirror(
		function (elt)  {    recoda.cmdTextArea.parentNode.replaceChild(elt, recoda.cmdTextArea);
		},
		{   value: recoda.cmdTextArea.value,
            mode: "css",
            theme: "dracula",
            recodaInvalidCSS: "false",
            recodaFlag: "NO_suggestion", 
            extraKeys: {
                ".": function(cm) {
                    if (document.querySelector("body").classList.contains("ws-class-highlighter_on")){
                        cm.replaceSelection(" .");
                    }
                    else{
                        cm.replaceSelection(".");
                    }
                    
                  },
                "Enter": function() {
                    let myCommand = recoda.cmdCodeMirror.getValue();
                    recoda.lastCommand = myCommand;
                    recoda.cmdCodeMirror.setValue("")
                    recoda.executeCommand(myCommand);
                },
                "Esc": function() {
                    recoda.cmdCodeMirror.display.input.blur();
                },
                "Ctrl-H": function() {
                    historySnippet();
                },
                "Cmd-H": function() {
                    historySnippet();
                },
                
            },
        }
	); 
    recoda.cmdCodeMirror.on("keyup", function(editor, event)
    { 
        var __Cursor = editor.getDoc().getCursor();
        var token = editor.getTokenAt(__Cursor);
        var str = token.string;
        var cmdChar = str.substring(0, 1);
        if(token.string === "."){
            //prepare data for suggestion
         
        }
        else if (!editor.state.completionActive &&
            !ExcludedIntelliSenseTriggerKeys[(event.keyCode || event.which).toString()]
            && !(token.string === '#'  || token.string === '!')
            && !(token.string === '~'  || token.string === '+')
            && !(token.string === '*'  || token.string === '>')
            && !(token.string === '('  || token.string === ')')
            && !(token.string === '{'  || token.string === '}'))
        {
            CodeMirror.commands.autocomplete(editor, null);
        } 

      
    });
    
    //---------------------------------------|  INIT CODESENSE |------------------------------------
    /*STYLESHEETS Codesense */
    recoda.codeSenseAutocompleteCSS(".oxygen-sidebar-code-editor-wrap.oxygen-sidebar-stylesheet-editor-wrap .CodeMirror", true);
    /* CUSTOM-CSS CodeSense */
    recoda.codeSenseAutocompleteCSS(".custom-css .CodeMirror", false);
    /* CUSTOM-JS Codesense*/ 
    recoda.codeSenseAutocompleteJS(".custom-js .CodeMirror");
      
    //-----------------------------------------------------------------------------------------------------------------

    
    //---------------------------------------|  STYLE INSPECTOR  TRIGGER ON SIDEBAR CLICK (Left Panel) |------------------------------------
    jQuery("#oxygen-sidebar").on('click', function(e)   {      
        if (document.getElementById("oxygen-ui").getAttribute("data-panelator") == "inspector") {          
            if ($scope.iframeScope.isEditing("id") == true) {	recoda.overviewPrintCSSforID(recoda.myCodeMirror);	}
            else if ($scope.iframeScope.isEditing("class") == true) {	recoda.overviewPrintCSSforClass(recoda.myCodeMirror);	}
        }
    });  

    //---------------------------------------|  STYLE INSPECTOR  TRIGGER ON IFRAME CLICK |------------------------------------
    jQuery(document.getElementById("ct-artificial-viewport").contentWindow.document).on('click', function(e)   {
		if (document.getElementById("oxygen-ui").getAttribute("data-panelator") == "inspector") {          
            if ($scope.iframeScope.isEditing("id") == true) {	recoda.overviewPrintCSSforID(recoda.myCodeMirror);	}
            else if ($scope.iframeScope.isEditing("class") == true) {	recoda.overviewPrintCSSforClass(recoda.myCodeMirror);	}
        }
	});
    //---------------------------------------|  LIVE SERVER SCROLL SYNC|------------------------------------
    jQuery("#rews-command-container").on("click","#tbf-s2", function () {
        let timeout,
            checkBox = document.getElementById("tbf-s2");
        if (checkBox.checked === false) return;

            jQuery(document.getElementById('ct-artificial-viewport').contentWindow.document, recoda.childWin.document).on("scroll", function callback() {
                
                clearTimeout(timeout);
                let tch=0,
                    sch=0;
                
                let source = jQuery(this),
                    target = jQuery(source.is(recoda.childWin.document) ? document.getElementById('ct-artificial-viewport').contentWindow.document : recoda.childWin.document);
                    
                    if(target == document.getElementById('ct-artificial-viewport').contentWindow.document){
                        tch = parseInt(document.defaultView.getComputedStyle(document.getElementById('ct-artificial-viewport')).height, 10);
                        sch = recoda.childWin.innerHeight;
                    } else{
                            sch = parseInt(document.defaultView.getComputedStyle(document.getElementById('ct-artificial-viewport')).height, 10);
                            tch = recoda.childWin.innerHeight;
                    }
                    
                let sS = source.scrollTop(),
                    sh_ = source.innerHeight(),
                    sH = sh_ - sch,
                    s = sS / sH,
                    th_ = target.innerHeight(),
                    tH = th_ - tch,
                    value = tH * s;
                
                target.off("scroll").scrollTop(value);
             
                timeout = setTimeout(function() {
                    target.on("scroll", callback);
                }, 250)
            });
       
        });
    
    
    
    }); 