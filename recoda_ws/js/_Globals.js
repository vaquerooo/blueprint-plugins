angular.element(function () {
/*
* HELPER FUNCTIONS
*/
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

/**
 * Throttle function
 * Source: https://gist.github.com/prevostc/dcb395fc8ae4cf094e42d262e77746bd
 * Stackoverflow: https://stackoverflow.com/questions/27078285/simple-throttle-in-javascript
 * @param {Function} func
 * @param {number} wait
 */

 function throttle (callback, limit) {
    var waiting = false;                      // Initially, we're not waiting
    var latest_call_this = null;
    var latest_call_args = null;
    var throttled_function = function () {                      
        if (waiting) {        
          // save latest call infos
          latest_call_this = this;
          latest_call_args = arguments;
        } else {
            callback.apply(this, arguments);   
            waiting = true;                    
            setTimeout(function () {           
                waiting = false;
                // check for latest call infos
                if (latest_call_this !== null) {
                    throttled_function.apply(latest_call_this, latest_call_args);
                    latest_call_this = null;
                    latest_call_this = null;
                }
            }, limit);
        }
    }
    return throttled_function
}
/**
 * Array move
 * Takes array, old and new index and swap two members of an array
 * Used to move array of stylesheets, selectors
 */
function array_move(arr, old_index, new_index) {
    if (new_index >= arr.length) {
        var k = new_index - arr.length + 1;
        while (k--) {
            arr.push(undefined);
        }
    }
    arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
    return arr; // for testing
}
  
  /*-----------------------------------------------------------------------------------------------------------*/
    
        const queryCheck = s => document.createDocumentFragment().querySelector(s)
        const ws_isValidSelector = selector => {
        try { queryCheck(selector) } catch { return false }
         return true
        }
    

    window.recoda = {
    /*
    * VALUES: dev | production | 
    */
    MODE: "prod",
    settingsFlag: "1",
    /*__GLOBAL: Scope variables     _______________________*/
    /*__GLOBAL: Workspace variables _______________________*/
    workspaceWidth : jQuery("#ct-controller-ui").width(),
    workspaceHeight : jQuery("#ct-controller-ui").height(), 

    /*__GLOBAL: Resizer ___________________________________*/
    //Left
    leftResizerMinW : 1,
    leftResizerMaxW_factor : 0.4,
    leftResizerMaxW : 600,
    leftResizerDefW : 350,
    //right
    rightResizerMinW : 1,
    rightResizerMaxW_factor : 0.6,
    rightResizerMaxW : 600,
    rightResizerDefW : 350,
    copyID:'',
    //common
    resizerMagneticTrigger : 250,
    isZoomRequested : false,
    liveServerLocation :  '',
    childWin : '',
    isSafari:   navigator.vendor && navigator.vendor.indexOf('Apple') > -1 &&
                navigator.userAgent &&
                navigator.userAgent.indexOf('CriOS') == -1 &&
                navigator.userAgent.indexOf('FxiOS') == -1,
    };
    $scope.recodaReference = recoda;
    recoda.setBGPosition = function(top,left){
        const   Oxy = $scope.iframeScope,
                options = Oxy.component.options,
                {name, id} = $scope.iframeScope.component.active;
        Oxy.setOptionUnit('background-position-top', '%');
        Oxy.setOptionUnit('background-position-left', '%');
        
        options[id]['model']['background-position-left'] = left.toString();
        Oxy.setOption(id, name,'background-position-left');
        Oxy.checkResizeBoxOptions('background-position-left'); 

        options[id]['model']['background-position-top'] = top.toString();
        Oxy.setOption(id, name,'background-position-top');
        Oxy.checkResizeBoxOptions('background-position-top'); 
          

    }
    /* Events */
    const eReloadLS = new Event('wsReloadLS');
    recoda.runTest = function(fn, n){    console.time("Test");    for(let i =0 ; i < n; i++) {        fn();     }     console.timeEnd("Test");}
    recoda.wslog = function (message, color='black') {
        switch (color) {
            case 'success':  
                 color = 'Green'
                 break
            case 'info':     
                    color = 'Blue'  
                 break;
            case 'error':   
                 color = 'Red'   
                 break;
            case 'warning':  
                 color = 'Orange' 
                 break;
            default: 
                 color = color
        }
   
        console.log(`%c${message}`, `color:${color}`)
   }
  
    recoda.log = function (txt){
        if( recoda.MODE === "dev"){
            Array.prototype.unshift.call(
                arguments,
                '['+new Date().toISOString().slice(11,-5)+']'
            );
            return console.trace.apply(console, arguments);
        }
    
    }
    var wsErrBuffer = "";
    window.onerror = function myErrorHandler(err, url, line) {  
        //Do some  stuff 
        let pushError = "\n" + err + "\t|  URL:" + url + "\t|  Line:" + line ;
        wsErrBuffer +=  pushError;
        //recoda.toast(pushError);
        return false;   // so you still log errors into console 
    }
    /**-------------| HELPER END |------------------ */

    window.onbeforeunload = closingOxygenAction;
    function closingOxygenAction(){
    /* Close Live server */
    if(recoda.childWin) recoda.childWin.close();
    /* Close detached editor */
    if(recoda.wsStudioWindow) recoda.wsStudioWindow.close();
    }
    /* Codesense Global */
    recoda.returnIntelliChars = function(){
        var excludedKeys =
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
            return excludedKeys;
        }
    function customSnippet(objArray, ref) {
        CodeMirror.showHint(ref, function () {
            var snippets = objArray;
            var cursor = ref.getCursor();
            var token = ref.getTokenAt(cursor);
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
    recoda.generateBreakpointsArray = function(){
        var m = $scope.iframeScope.mediaList;
        var controls = ["max", "min"];
        var output = [];
        controls.forEach((c) =>{
            var minMax = c;
            var txt1 = ""
            var txt2 = "\t | => (and below)";
            if(c == 'min'){
                txt1 = "(and above) <= | ";
                txt2 = "";
            }
            var strPageW = `@media screen and (`+minMax+`-width:`+ m['page-width']['maxSize'] +`) { }`;
            var strTablet = `@media screen and (`+minMax+`-width:`+ m['tablet']['maxSize'] +`) { }`;
            var strPhoneLandscape = `@media screen and (`+minMax+`-width:`+ m['phone-landscape']['maxSize'] +`) { }`;
            var strPhonPortrait = `@media screen and (`+minMax+`-width:`+ m['phone-portrait']['maxSize'] +`) { }`;
            var breakpointsArray = [
                {  
                    "text": strPageW,
                    "displayText": txt1 + "Page" + txt2
                },
                {
                    "text": strTablet,
                    "displayText": txt1 + "Tablet" + txt2
                },
                {
                    "text": strPhoneLandscape,
                    "displayText": txt1 + "Phone-L" + txt2
                },
                {
                    "text": strPhonPortrait,
                    "displayText": txt1 + "Phone-P" + txt2
                },
            ];
            output = [...output, ...breakpointsArray]
        })
        return output;
    }

    recoda.hintingFunction = function(cm, options) {
        const anyhint = CodeMirror.hint.anyword(cm, options)
        const jshint = CodeMirror.hint.javascript(cm, options)
        if(anyhint == undefined) return;
        if(jshint == undefined) return;
        const words = new Set([...anyhint.list, ...jshint.list])
        if (words.size > 0) {
          return {
            list: Array.from(words), 
            from: jshint.from, 
            to: jshint.to
          }
        }
      }
      const ExcludedIntelliSenseTriggerKeys =
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
    recoda.toggleFullscreen = function(){
        var builder = document.getElementById('ct-controller-ui');

        var closed = recoda.togglePanels();

        if (!closed) {
                document.getElementById("rews-tg-fs").classList.remove('active');
                builder.classList.remove("rews-fullscreen");
                recoda.toast('Exited from Fullscreen Mode');
        }
        else {
            document.getElementById("rews-tg-fs").classList.add('active');
            builder.classList.add("rews-fullscreen");
            recoda.toast('Entered in Fullscreen Mode');
        } 
    }

    recoda.codeSenseAutocompleteJS = function(_SELECTOR_){
        jQuery(document).on("keydown", _SELECTOR_, (function(e, t) {
            var editor = document.querySelector(_SELECTOR_).CodeMirror;
            var __Cursor = editor.getDoc().getCursor();
            var token = editor.getTokenAt(__Cursor);

            if (!editor.state.completionActive &&
                !ExcludedIntelliSenseTriggerKeys[(e.keyCode || eveent.which).toString()]
                && !(token.string === '(' || token.string === ')')
                && !(token.string === '{' || token.string === '}'))
            {
                CodeMirror.commands.autocomplete(editor, null, {hint: recoda.hintingFunction});
            }
        }))  
    }
    recoda.codeSenseAutocompleteCSS = function(_SELECTOR_, valid){
        if(valid){
            jQuery(document).on("focus", _SELECTOR_ , (function() {
                recoda.linkedStylesheet = $scope.iframeScope.stylesheetToEdit;
                var editor = document.querySelector(_SELECTOR_).CodeMirror;
                var keyMap = {
                    "Ctrl-B": function(){   customSnippet(recoda.generateBreakpointsArray(), editor);  }
                }
                editor.addKeyMap(keyMap);
                editor.setOption("matchBrackets", !0); 
                editor.setOption("autoCloseBrackets", !0); 
                editor.setOption("recodaInvalidCSS", "false");
                editor.setOption("recodaFlag", "suggestion");
            }));
        }
        

        jQuery(document).on("keydown",_SELECTOR_, (function(e, t) {
            var editor = document.querySelector(_SELECTOR_).CodeMirror;
            var __Cursor = editor.getDoc().getCursor();
            var token = editor.getTokenAt(__Cursor);
    
            if (!editor.state.completionActive &&
                !ExcludedIntelliSenseTriggerKeys[(e.keyCode || e.which).toString()]
                && !( token.string  === '#')
                && !(token.string   === '~' || token.string === '+')
                && !(token.string   === '*' || token.string === '>')
                && !(token.string   === '(' || token.string === ')')
                && !(token.string   === '{' || token.string === '}'))
            {
                CodeMirror.commands.autocomplete(editor, null);
            }
        }))  
    }
    recoda.openWorkspaceStudio = function(){

        if(recoda.wsStudioWindow) {try {recoda.wsStudioWindow.focus()} catch(err){ recoda.toast("Stylesheets are opened in detached mode")}}
        else{
            var fontVar = '--powersheets-editor-font-size';
            var fontValue = getComputedStyle(document.querySelector('html')).getPropertyValue(fontVar);
            const winHtml = `<!DOCTYPE html>
                <html style="${fontVar}: ${fontValue};">
                    <head>
                        <title>Window with Blob</title>
                    </head>
                    <body style="display: none;">
                        <h1>Hello from the new window!</h1>
                    </body>
                </html>`;

            const winUrl = URL.createObjectURL(
                new Blob([winHtml], { type: "text/html" })
            );
            var styleSheets = $scope.iframeScope.styleSheets,
                folders = [{ id: 0, name: "Uncategorized", status: 0, folder: 1 }] , sheets = [];
            styleSheets.forEach((maybeFolder) => { 
                if("folder" in maybeFolder)   folders = [...folders, maybeFolder];
            })
            styleSheets.forEach((maybeSheet) => { 
                if("css" in maybeSheet)   sheets = [...sheets, maybeSheet];
            })
            
            var scripts = document.querySelectorAll("script"),
                links   = document.querySelectorAll("link"),
                folderName, folderSheets,
                MAINtemplate = 
                `<div id="ws-de" data-wsapp="workspace-detached-editor">
                    <div class="ws-de-side">
                        <div class="ws-de-side-header"><div class="ws-de-experimental">WS Editor</div></div>
                        <div class="ws-de-side-stylesheets"></div>
                    </div>
                    <div class="ws-de-workarea">
                        <div class="ws-de-header"></div>
                        <div class="ws-de-code">
                            <textarea id="ws-de-codeArea">Initialized...</textarea>
                        </div>
                    </div>
                    <div class="ws-de-foot"></div>
                </div>`;
                function generateFolder(folderName, folderSheets,n, childCount){
                    return `
                    <div class="ws-de-folder">
                        <input type="checkbox" id="toggle${n}" class="toggle">
                        <label class="ws-de-folder-title" data-count="${childCount}" for="toggle${n}">${folderName}</label>
                        <div class="content">
                            ${folderSheets}
                        </div>
                    </div>`;
                }
                function generateSidebar (){
                    var folderBuff = '', n =0;
                    folders.forEach((folder) =>{
                        var id = folder.id,
                            folderName = folder.name,
                            sheetBuff = '',
                            childCount = 0;
                        sheets.forEach((sheet) =>{
                            if(sheet.parent === id){
                                sheetBuff += `<div class="ws-de-stylesheet" data-oxy-sid="${sheet.id}">${sheet.name}</div>`
                                childCount++;
                            }
                        });
                        folderBuff += generateFolder(folderName,sheetBuff,n,childCount);
                        n++;
                    });
                    recoda.wsStudioWindow.document.querySelector(".ws-de-side-stylesheets").innerHTML = folderBuff;
                }

            recoda.wsStudioWindow = window.open(winUrl, "_blank");
            const   x = recoda.wsStudioWindow;
            
            x.window.onload = function() {
                x.window.onunload = function(e) { 
                    if(document.body.classList.contains("ws-detached-editor")) {
                        document.body.classList.remove("ws-detached-editor");
                        recoda.wsStudioWindow = false;
                    }
                };
                document.body.classList.add("ws-detached-editor");
                const doc = x.document;
                x.document.title = "Stylesheets";
                x.document.body.innerHTML = MAINtemplate;
                let h = x.document.querySelector("head"),
                    textarea = x.document.getElementById("ws-de-codeArea");

                scripts.forEach((s) => {    var clone = s.cloneNode(true);  h.append(clone)     });
                links.forEach(  (l) => {    var clone = l.cloneNode(true);  h.append(clone)     });
                generateSidebar();
                recoda.detachedCodeMirror = CodeMirror.fromTextArea(textarea,{   
                        value: textarea.value,
                        mode: "css", 
                        lineWrapping: true,
                        lineNumbers: true,
                        autoCloseBrackets: true,
                        autoRefresh:true,
                        matchBrackets: true,
                        recodaFlag: true,
                        cursorBlinkRate: 0,
                        theme: "default",
                        extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); },
                                    "Ctrl-Space": "autocomplete",
                                    "Cmd-Q": function(cm){ cm.foldCode(cm.getCursor()); },
                                    "Cmd-Space": "autocomplete",  
                    },
                    foldGutter: true,
                    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"] 
                    
                    }); 
                recoda.detachedCodeMirror.refresh()
                x.document.body.style.display = "block";
                const wsEDITOR = recoda.wsStudioWindow.document.querySelector(".ws-de-code .CodeMirror").CodeMirror;
                const wsEditorSave = debounce(saveDetahedSheetToOxygen,1500);
                const wsEditorAutocompleteCSS = debounce(autocompleteDetachedEditorCSS,5);

                function autocompleteDetachedEditorCSS(e){
                    
                    var editor = wsEDITOR;
                    var __Cursor = editor.getDoc().getCursor();
                    var token = editor.getTokenAt(__Cursor);
                    //!editor.state.completionActive
                    if ( 
                        !ExcludedIntelliSenseTriggerKeys[(e.keyCode || e.which).toString()]
                        && !( token.string  === '#')
                        && !(token.string   === '~' || token.string === '+')
                        && !(token.string   === '*' || token.string === '>')
                        && !(token.string   === '(' || token.string === ')')
                        && !(token.string   === '{' || token.string === '}'))   {
                            CodeMirror.commands.autocomplete(editor, null);
                            
                        }
                    
                } 
                
                function saveDetahedSheetToOxygen(e){
                    const Oxy = $scope.iframeScope;
                    let   detach = recoda.wsStudioWindow,
                            active = detach.document.querySelector(".ws-de-header-instance.active"),
                            sheets = $scope.iframeScope.styleSheets;
                    if (active == null) return;
                    if(detach.document.querySelectorAll(".ws-de-header-instance.active").lenght < 1) return
                    let id = active.children['0'].getAttribute("data-oxy-sid");
                    sheets.forEach((sheet) =>{
                        if(sheet.id.toString() === id.toString()){
                            sheet.css = recoda.detachedCodeMirror.getValue();
                        }
                    });

                }
                function removeActive(){
                    let header = x.document.querySelector(".ws-de-header"),
                    instances = header.querySelectorAll(".ws-de-header-instance");
                    instances.forEach((i) => {
                        if(i.classList.contains("active")) i.classList.remove("active");
                    });
                };
                
                function changeActiveStylesheet(e){
                    var target = e.target,
                        header = x.document.querySelector(".ws-de-header"),
                        id = target.getAttribute("data-oxy-sid"),
                        sheets = $scope.iframeScope.styleSheets;
                    if(target.classList.contains("CodeMirror-line")) return;
                    if(target.classList.contains("ws-de-stylesheet")){
                        if(header.querySelectorAll(`[data-oxy-sid="${id}"]`).length < 1){
                            removeActive();
                            var headerInstance  = document.createElement('div');
                            headerInstance.classList.add('ws-de-header-instance', "active");
                            headerInstance.innerHTML = `${target.outerHTML}<span class="ws-de-remove-instance"></span>`;
                            header.appendChild(headerInstance);
                        } else{
                            removeActive();
                            let active = header.querySelector(`[data-oxy-sid="${id}"]`).parentElement;
                            active.classList.add("active");
                        } 
                    }
                
                    if(target.classList.contains("ws-de-header-instance")){ 
                        removeActive(); 
                        target.classList.add("active");
                        id = target.children['0'].getAttribute("data-oxy-sid"); 
                        
                    }
                
                    if(target.classList.contains("ws-de-remove-instance")){ 
                        target.parentElement.remove(); 
                        if(x.document.querySelectorAll(".ws-de-header .ws-de-header-instance").lenght < 1) recoda.detachedCodeMirror.setValue("Select Stylesheet to edit");
                    }

                    sheets.forEach((s) => {
                        if("css" in s) {
                            if(id == s.id)  {
                                recoda.detachedCodeMirror.setValue(s.css)
                            }
                        }
                    });
                }
                x.document.querySelector(".ws-de-side").addEventListener("click", changeActiveStylesheet);
                x.document.querySelector(".ws-de-header").addEventListener("click", changeActiveStylesheet);

                jQuery(recoda.wsStudioWindow).on("keydown", (function(e) {
                    //if(!(e.target.nodeName ==="TEXTAREA")) return;
                        wsEditorAutocompleteCSS(e)
                        wsEditorSave(e);        
                }))  
                
                
                recoda.wsStudioWindow.onbeforeunload = function(){
                    document.body.classList.remove("ws-detached-editor");
                    recoda.wsStudioWindow = false;
                }
            

                /////
            }; 
        } 
        
    }
    recoda.generateComponentAttributeCommand= function(){
        var attributeArray = $scope.iframeScope.component.options[$scope.iframeScope.component.active.id]['model']['custom-attributes']
        var command = '';
        if (undefined === attributeArray){ recoda.toast("Element does not contain any attributes"); return}
        attributeArray.forEach( att => {
            var name = att.name;
            var value = att.value;
            command = command + "[" + name +"="+ value +"]";
        });
        recoda.cmdCodeMirror.setValue(command);
    }
    recoda.checkLocalStorageOption = function(storekey, option){
        var lsCheck = false;
        if (Object.prototype.hasOwnProperty.call(localStorage, 'recoda-settings')){
            var localStore = JSON.parse(localStorage.getItem('recoda-settings'));
        }else lsCheck = true;
        if (!lsCheck){
            if(localStore.hasOwnProperty(storekey)){
                if(localStore[storekey] === option){
                    return true
                }else return false;
            } else return false;
        } else return false;
    }
    recoda.commandHistoryItems = [];
    recoda.addCommandHistoryItem = function (item){
        var select = document.getElementById("selectHistoryCommand"); 
        var el = document.createElement("li");
        el.textContent = item;
        el.value = item;
        el.onchange = `recoda.replaceCommand();`
        select.appendChild(el);
    }
    function getAutomaticCSSVars(){
        var css ='', iframe, iframeLinks, oxyLinks;
        iframe = document.getElementById("ct-artificial-viewport")
        iframeLinks = iframe.contentWindow.document.querySelectorAll("link");
        oxyLinks = document.querySelectorAll("link");
        
        iframeLinks.forEach( (s) => {
            var id =s.id;
            
            if (id.includes('automatic') ) {
                var rules = s.sheet.cssRules;
                for (var i = 0; i < rules.length; i++) {
                    css += rules[i].cssText;
                  }
            }
        });
        oxyLinks.forEach( (s) => {
            var id =s.id;
            if (id.includes('automatic') ) {
                var rules = s.sheet.cssRules;
                for (var i = 0; i < rules.length; i++) {
                    css += rules[i].cssText;
                  }
            }
        });
        return css;
    }
    recoda.stylsheetsCSSVars = [];
    recoda.updateStylesheetsCSSVars = function (){
        //if(recoda.isSafari) return console.log("Safari");
        let styleSheets = $scope.iframeScope.styleSheets,
        regex = /(?<=\--)(.[^;)@}]*?)(?=\:)+/g,
        css ='', c1, c2, c3, c4, u, result;
        css += getAutomaticCSSVars().toString();
       //get all sheets as single string
        styleSheets.forEach((sheet) => { 
           if("css" in sheet)   css += sheet.css;
       })
       // remove all inside brackets ({variable}) instance and  false instances
        c1 = css.replace(/\([^()]*\)/g, '');
        //c3 = c1.replace(/([\\.#][_A-Za-z0-9\\-]+)[^}][*{]/g, '');//
        //result = c3.match(regex); //returns array
        result = c1.match(regex); //returns array
    
       //get css props in array from sheet
      
       u = [...new Set(result)];
       recoda.stylsheetsCSSVars = u;
    }
    recoda.isActiveElementNestable = function (){
        const   Oxy         =   $scope.iframeScope,
                {id, name}  =   $scope.iframeScope.component.active;
        let nestable = ["ct_div_block", "ct_link", "ct_section"],
            result = false;

        nestable.forEach((e) => { 
            if (name === e){
                result= true;
            }
            } )
        return result;
    }
    recoda.injectContent = function (contentToInject){
        const   Oxy         =   $scope.iframeScope,
                options     =   $scope.iframeScope.component.options,
                {id, name}  =   $scope.iframeScope.component.active;

        options[id]['model']['ct_content'] = contentToInject; 
        /* Trigger content save*/
        Oxy.setOption(id, name,'ct_content')
    }
    recoda.alert = function (alertText){
        jQuery("#rews-alert_body").text(alertText); 
        jQuery("#rews-alert").fadeIn(50).delay(3500).fadeOut(50);
    }
    recoda.toast = function (messageStr, typeClass){
        const   FADE_DUR = 700,
                MIN_DUR = 2000;
        let toastContain = document.querySelector(".ws-toastContainer");

        function toast(str, addClass) {

            let duration = Math.max(MIN_DUR, str.length * 80);
            
            if (!toastContain) {
                toastContain = document.createElement('div');
                toastContain.classList.add('ws-toastContainer');
                document.body.appendChild(toastContain);
              }
            
            const EL = document.createElement('div');
            EL.classList.add('ws-toast', addClass);
            EL.innerText = str;
            toastContain.prepend(EL);
            
            setTimeout(() => EL.classList.add('open'));
            setTimeout(
              () => EL.classList.remove('open'),
              duration
            );
            setTimeout(
              () => toastContain.removeChild(EL),
              duration + FADE_DUR
            );
            
          }
        toast(messageStr, typeClass);
        
    }
    recoda.loadStyleSheetsInEditor = function (){
        jQuery("#recoda-var-import").remove();
        let cssChunks = jQuery("#ct-artificial-viewport").contents().find("style.ct-css-location.test"),
            cssBuffer = "",
            length = cssChunks.length,
            control = jQuery("#recoda-var-import").length;
        for (var i = 0 ; i < length; i++ ){
            if (cssChunks[i].innerHTML.trim().startsWith('/*#IMPORT*/')) {cssBuffer = cssBuffer + cssChunks[i].innerHTML; }
        }
        if (control === 0){
            let css = `<style id="recoda-var-import">${cssBuffer}</style>`;
            jQuery("#ct-controller-ui").append(css) 
        }
        else{
            jQuery("#recoda-var-import").innerHTML = cssBuffer;
        }
        
    };
    recoda.toggleCodeView = function (){
        let r = document.documentElement.style,
            dw = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--def-sidebar-width"),10),
            width =  parseInt(getComputedStyle(document.documentElement).getPropertyValue("--sidebar-width"),10),
            workspaceWidth = window.innerWidth,
            ratioL = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--ratio"),10) - 1,
            mw = ratioL * (workspaceWidth/100),
            max_w = mw - 100;
        if(width < max_w){
            r.setProperty("--sidebar-width", mw +"px");
        }
        else{
            r.setProperty("--sidebar-width", dw +"px");
        }
      
    }
    recoda.changeElementID = function (value){
        const   Oxy         =   $scope.iframeScope,
                {id, name}  =   $scope.iframeScope.component.active;

        if (name === "ct_template" || name === "ct_inner_content" || name === "root" ) return;  
        if(!ws_isValidSelector(value)){ return;}
        
        Oxy.component.options[id]['selector'] = value;
        Oxy.setOption(id, name, 'selector');

        if(recoda.checkLocalStorageOption("ws-cli-autorename","id-change")){
            recoda.renameElement(value)
        }
    }
    recoda.renameElement = function (newName){
        const   Oxy         =   $scope.iframeScope,
                {id, name}  =   $scope.iframeScope.component.active;
        let     options     =   Oxy.component.options[id];
        options.nicename = newName;
        Oxy.updateFriendlyName(id);
    }
   
    recoda.minimizeBlockpad = function (){
        let def_w;
        recoda.blockpadSaveToCodeBlock();
        document.querySelector("#ct-controller-ui").classList.remove('blockpad-active');
        document.querySelector(".rews-parent-box.recoda-lpane").classList.remove('recoda-active');
        def_w = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--def-sidebar-width"),10);
        recoda.setPropertyPX("--sidebar-width", def_w);
        recoda.blockpadSaveToCodeBlock();
    }

    recoda.maximizeBlockpad = function (){
        recoda.blockpadImportCodeBlock();
        document.querySelector("#ct-controller-ui").classList.add('blockpad-active');
        document.querySelector(".rews-parent-box.recoda-lpane").classList.add('recoda-active');
    }

    recoda.adjustTopOffset = function (){
        let topOffset = jQuery("#oxygen-ui").offset().top;
        document.documentElement.style.setProperty('--top-offset', (topOffset + "px"))
    }
   
    /* Call via debounce function to improve performance. reduce repaints and reflwos*/
    recoda.outputCanvavsSettings = function () {
        if(recoda.isZoomRequested) return recoda.isZoomRequested = false;
        var cm = $scope.iframeScope.currentMedia, 
            mq_PW, zoom, viewportWidth, zoomScale, sidebarW, paneSize;
        
        viewportWidth = jQuery("#ct-artificial-viewport").width();
        zoomScale = getComputedStyle(document.documentElement).getPropertyValue('--zoom-scale');
        jQuery("#rewsTrueCanvasWidth").val(parseInt(viewportWidth, 10));
        jQuery("#rewsCanvasZoom").val(parseInt(zoomScale * 100, 10));
        sidebarW = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--sidebar-width'),10);
        paneSize = (sidebarW > 299) ? "normal" : "small";
        document.getElementById("oxygen-sidebar").setAttribute("data-width", paneSize);

        /*---------------------------------------------------*/
        /* AUTOZOOM PART */
        /*If it is manual requested zoom, return */
        if(recoda.isZoomRequested) {recoda.isZoomRequested = false; return; }
        if($scope.iframeScope.mediaList === undefined) return;

        if(cm === "default"){
            mq_PW = parseInt($scope.iframeScope.mediaList['page-width']['maxSize'], 10);
        } else if(cm === "page-width"){
            mq_PW = parseInt($scope.iframeScope.mediaList['tablet']['maxSize'], 10);
        }

        autoZoomThreshold = mq_PW + 50;
        
            if(recoda.checkLocalStorageOption('_x_ws-auto-zoom', 'on')){
                if(document.querySelector("#ct-artificial-viewport").classList.contains("rews-responsive-mode")) return;
                //old value
                if($scope.iframeScope.currentMedia != "default") {recoda.callSetCanvasZoom(100);return;}
                if(viewportWidth < autoZoomThreshold){
                    recoda.callSetCanvasPX(autoZoomThreshold);
                } else{
                    recoda.callSetCanvasZoom(100);    
                } 
            }

    }

    recoda.roCanvas = new ResizeObserver(debounce(recoda.outputCanvavsSettings, 250));
    recoda.clipSelector = function(type, parent, e){
        if(parent.querySelectorAll(".ws-data-clip").lenght < 1) return;

        var selector = parent.querySelector(".ws-data-clip").innerHTML;
        var txt = false;

        if(type == "id"){
            if(e.ctrlKey || e.metaKey) txt = `#${selector}`
            else txt = `${selector}`
        }
        if(type = "class"){
            if(e.ctrlKey || e.metaKey) txt = `.${selector}`
            else txt = `${selector}`
        }
        if (txt){
            recoda.textToClipboard(txt);
            recoda.toast(txt);
        }
    }
    function fallbackCopyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        
        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";
      
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
      
        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Fallback: Copying text command was ' + msg);
        } catch (err) {
          console.error('Fallback: Oops, unable to copy', err);
        }
      
        document.body.removeChild(textArea);
     }
      
    recoda.textToClipboard = function  (text) {
        if (!navigator.clipboard) {
            fallbackCopyTextToClipboard(text);
            return;
          }
          navigator.clipboard.writeText(text).then(function() {
            console.log('Async: Copying to clipboard was successful!');
          }, function(err) {
            console.error('Async: Could not copy text: ', err);
          });
    }
    recoda.overviewPrintCSSforID = function (mirrorReference){
        const Oxy = $scope.iframeScope;
        let id      = Oxy.component.active.id,
		    option  = Oxy.component.options[id],
		    string      = Oxy.getSingleComponentCSS(option),
		    css = string.replace(/;/g, ";\n").replace(/{/g, "{\n").replace(/}/g, "}\n").replace(/:/g, ": ");

		mirrorReference.doc.setValue(css);
    }
    recoda.overviewPrintCSSforClass = function (mirrorReference){
        const Oxy = $scope.iframeScope;
        let rawString = Oxy.getSingleClassCSS(Oxy.currentClass),
            css = rawString.replace(/;/g, ";\n").replace(/{/g, "{\n").replace(/}/g, "}\n").replace(/:/g, ": ");

        mirrorReference.doc.setValue(css);
    }
    
    recoda.launchLiveServer = function (){
        const Oxy = $scope.iframeScope;
        let t = window.location.toString();
        let a = "http:";
        //if moved up
        if(t.includes("https")) a = "https:";
        let permalink = Oxy.ajaxVar.permalink;

        if($scope.iframeScope.ajaxVar.oxyTemplate == "1" && !$scope.iframeScope.ajaxVar.oxyReusable){
            recoda.liveServerLocation = Oxy.template.postData.frontendURL;
        }
        
        else if($scope.iframeScope.ajaxVar.oxyReusable == "1"){
            return recoda.toast("Live Server does not support reusable parts preview!");
        }
        else {
            //if(t.includes("https")) a = "https:";
            recoda.liveServerLocation = a.toString() + permalink.toString();
        }
        
        recoda.childWin = window.open(recoda.liveServerLocation, "_blank", "");
        onLiveServerReload(e);
       
        document.addEventListener('wsReloadLS', (e) => {
            onLiveServerReload(e);
        });
        
       
        /*(async() => {
            while(!recoda.childWin.document.getElementById("wpadminbar")) await new Promise(resolve => setTimeout(resolve, 250));
            recoda.childWin.document.getElementById("wpadminbar").style.display="none";  console.log("hhashdhas");
        })();  */
       
    }
    recoda.reloadLiveServer = function () {
        if(!recoda.childWin.closed && recoda.childWin) {
            document.dispatchEvent(eReloadLS);
           recoda.childWin.location.reload(); 
           
       }
         
   }
   function onLiveServerReload(e){
       (async() => { for(let m=0; (m<20 && !recoda.childWin.document.getElementById("wpadminbar")) ;m++){ await new Promise(resolve => setTimeout(resolve, 250)); } 
            if (recoda.childWin.document.getElementById("wpadminbar")){
                var html = recoda.childWin.document.getElementsByTagName('HTML')['0'];
                recoda.childWin.document.getElementById("wpadminbar").remove();
                html.style= 'margin-top:0px !important';
            } 
       })(); //asnyc wait end ev2  
   }
    recoda.launchBugeReportCentre = function(){
        recoda.bugCenterLocation = "https://recoda.me/bug-reporting-center/";
        recoda.childWinBug = window.open(recoda.bugCenterLocation, "_blank", "width=900,height=600");

        let targetOrigin = recoda.bugCenterLocation,
            info = `Oxygen Version: ${rewsLocalVars.oxyVersion["Version"]} \nRecoda WS Version:  ${rewsLocalVars.wsVersion["Version"]}`, 
            dataObj =  {
            "url"       :   window.location,
            'system'    :   navigator.userAgent,
            "options"   :   "dsd",   
            "info"      :   info,   
            "console"   :   wsErrBuffer,   
        }
        const dataString = JSON.stringify(dataObj);
        function handleMessage (event) {
            if (event.data === 'openedReady') {
              recoda.childWinBug.postMessage( dataString, targetOrigin);
            }
          }
    
          window.addEventListener('message', handleMessage, false);

    }
    
    recoda.animateBlockpadCollapse = function(){
        jQuery('.rews-parent-box ').addClass('blockpad-animate').delay(2000).queue(function( next ){
            jQuery(this).removeClass('blockpad-animate'); 
            next();
        });
    }
    recoda.minimizePHP = function(){
        recoda.animateBlockpadCollapse();
        const   c1 = document.querySelector(".rews-code-container-1"),
                c2 = document.querySelector(".rews-code-container-2"),
                c3 = document.querySelector(".rews-code-container-3");
        let h1 = c1.offsetHeight,
            h2 = c2.offsetHeight,
            h3 = c3.offsetHeight,
            h = h1 + h2 + h3,
            h_diff = h - 30,
            h_2 = h_diff / 2,
            he_2 =  h_2,
            he_3 =  h_2;
        c1.setAttribute('style', 'flex-basis:' + 30 + 'px;');
        c2.setAttribute('style', 'flex-basis:' + he_2 + 'px;');
        c3.setAttribute('style', 'flex-basis:' + he_3 + 'px;');
    }
    recoda.minimizeCSS = function(){
        recoda.animateBlockpadCollapse();
        const   c1 = document.querySelector(".rews-code-container-1"),
                c2 = document.querySelector(".rews-code-container-2"),
                c3 = document.querySelector(".rews-code-container-3");
        let h1 = c1.offsetHeight,
            h2 = c2.offsetHeight,
            h3 = c3.offsetHeight,
            h = h1 + h2 + h3,
            h_diff = h - 30,
            h_2 = h_diff / 2,
            he_1 =  h_2,
            he_3 =  h_2;
        c1.setAttribute('style', 'flex-basis:' + he_1 + 'px;');
        c2.setAttribute('style', 'flex-basis:' + 30 + 'px;');
        c3.setAttribute('style', 'flex-basis:' + he_3 + 'px;');
    }
    recoda.minimizeJS = function(){
        recoda.animateBlockpadCollapse();
        const   c1 = document.querySelector(".rews-code-container-1"),
                c2 = document.querySelector(".rews-code-container-2"),
                c3 = document.querySelector(".rews-code-container-3");
        let h1 = c1.offsetHeight,
            h2 = c2.offsetHeight,
            h3 = c3.offsetHeight,
            h = h1 + h2 + h3,
            h_diff = h - 30,
            h_2 = h_diff / 2,
            he_1 =  h_2,
            he_2 =  h_2;
        c1.setAttribute('style', 'flex-basis:' + he_1 + 'px;');
        c2.setAttribute('style', 'flex-basis:' + he_2 + 'px;');
        c3.setAttribute('style', 'flex-basis:' + 30 + 'px;');
    }
    recoda.blockpadImportCodeBlock = function (){
        const   options     =   $scope.iframeScope.component.options,
                {id, name}  =   $scope.iframeScope.component.active;

        document.getElementById("rews-blockpad-linking-id").innerText = id;
        document.getElementById("rews-blockpad-linking-name").innerText = options[id].nicename;
        if($scope.isActiveName("ct_code_block")){
            recoda.myCodeMirrorPHP.setValue(options[id]['model']['code-php']);
            recoda.myCodeMirrorCSS.setValue(options[id]['model']['code-css']);
            recoda.myCodeMirrorJS.setValue(options[id]['model']['code-js']);
        }
        else{
            alert("Warning: Please select Code Block from which you want to import code!")
        }
    }
    recoda.blockpadSaveToCodeBlock = function (){
        const   Oxy     =   $scope.iframeScope,
                options =   $scope.iframeScope.component.options;
        let id = document.getElementById("rews-blockpad-linking-id").innerText,
            ngAtt = `[ng-attr-tree-id="${id}"]`;
        
        jQuery(ngAtt).children().click();
        options[id]['model']['code-php'] = recoda.myCodeMirrorPHP.getValue();
        Oxy.applyCodeBlockPHP();
        options[id]['model']['code-css'] = recoda.myCodeMirrorCSS.getValue();
        Oxy.applyCodeBlockCSS();
        options[id]['model']['code-js']  = recoda.myCodeMirrorJS.getValue();
        Oxy.applyCodeBlockJS();
    }
    recoda.maxBlockpad= function (varName){
        let wsWidth = document.querySelector("#ct-controller-ui").clientWidth,
            width = wsWidth * 0.38;
        document.documentElement.style.setProperty('--sidebar-width', width + "px");

    }

    recoda.linkedStylesheet = false;
    recoda.panelator = function (newOption){
        let node = document.getElementById("oxygen-ui"),
            currentOption = node.getAttribute("data-panelator");
        /* Helper function to hide Oxygen panels while custom are active */
        function hideOtherPanels(){
            switch( currentOption ) {
                case "dom":
                    $scope.switchTab('sidePanel','DOMTree');
                    break;
                case "selectors":
                    $scope.switchTab('sidePanel','selectors');
                    break;
                case "stylesheets":
                    $scope.switchTab('sidePanel','styleSheets');
                    break;
                case "settings":
                    $scope.toggleSettingsPanel();
                    break;
                case "history":
                    $scope.switchTab('sidePanel','History');
                    break;
            }
        }
        
        switch( newOption ) {
            case "dom":
                if(currentOption != newOption){ $scope.switchTab('sidePanel','DOMTree'); node.setAttribute("data-panelator", newOption); }
                break;
            case "selectors":
                if(currentOption != newOption){ $scope.switchTab('sidePanel','selectors'); node.setAttribute("data-panelator", newOption); } 
                break;
            case "stylesheets":
                if(recoda.wsStudioWindow){ try {recoda.wsStudioWindow.focus()} catch(err){ recoda.toast("Stylesheets are opened in detached mode");}  
                } else {
                    if(currentOption != newOption){ $scope.switchTab('sidePanel','styleSheets'); node.setAttribute("data-panelator", newOption); }  
                    if(recoda.linkedStylesheet != false){ 
                        var linkedStylesheetExist = false;
                        var stylesheets = $scope.iframeScope.styleSheets;
                        var link = recoda.linkedStylesheet;
                        for (var i = 0; i < stylesheets.length; i++) {
                            if (stylesheets[i].name === link.name){
                                $scope.iframeScope.setStyleSheetToEdit(link);
                                linkedStylesheetExist = true;
                            } 
                        }
                        if(!linkedStylesheetExist){recoda.linkedStylesheet=false;}
                    }
                }
                
                break;
            case "settings":
                if(currentOption != newOption){ $scope.toggleSettingsPanel(); node.setAttribute("data-panelator", newOption); }  
                break;
            case "inspector":
                node.setAttribute("data-panelator", newOption);
                hideOtherPanels();
                if ($scope.iframeScope.isEditing("id") === true) {	recoda.overviewPrintCSSforID(recoda.myCodeMirror);	}
                else if ($scope.iframeScope.isEditing("class") === true) {	recoda.overviewPrintCSSforClass(recoda.myCodeMirror) };
                break;
            case "history":
                if(currentOption === newOption){ $scope.switchTab('sidePanel','History');  }  
                node.setAttribute("data-panelator", newOption);
                break;
        }

    }
    recoda.readCSSvarAsNumber = function (varName){
        parseInt(getComputedStyle(document.documentElement).getPropertyValue(varName),10)
    }
    recoda.createBackgroundPositionPrests = function(){
        if(! recoda.checkLocalStorageOption("_x_background_position_prests","enabled")) return;
        var n = 0;
        (async() => { while(n < 5) { 
            if(jQuery(".oxygen-measure-box.oxygen-measure-box-option-background-position-top").closest(".oxygen-control-row").lenght > 0) n = 5;
            n += 1;
            await new Promise(resolve => setTimeout(resolve, 20));}  
        if(document.getElementById("ws-position-control-helper") != null) return;
        let options = [ [0,0],[0,50],[0,100],    
                        [50,0],[50,50],[50,100], 
                        [100,0],[100,50],[100,100]];
        let innerTemplate = ``, element; 
        options.forEach((o) => {
            let a = o['0'], b = o['1'];
            innerTemplate += `<div class="ws-position-option" onclick="recoda.setBGPosition(${a},${b})"><span class="ws-dot"></span></div>`;
        })
        element = `<div id="ws-position-control-helper">${innerTemplate}</div>`
        jQuery(".oxygen-measure-box.oxygen-measure-box-option-background-position-top").closest(".oxygen-control-row").prepend(element).addClass('ws-position-prests-layout');
        })(); //asnyc wait end   
        
    }
    recoda.selectAdvanced = function(a, b) {
        $scope.showAllStylesFunc();
        $scope.styleTabAdvance=true;
        $scope.switchTab(a, b);
        switch( b ) {
            case "background":
                recoda.createBackgroundPositionPrests();
                break;
               
           
        }
    }
    recoda.getGistContentViaUrl = function(gistUrl){
        let createStylesheet = true,
            createFolder = true,
            hasFolder = false,
            folderStr,
            fileStr,
            urlTemplate = 'https://api.github.com/gists/',
            tempArr = gistUrl.match(/[^\/]+|\//g),
            gist = tempArr.slice(-1)[0],
            url = urlTemplate + gist;
        function handleDone(data) {
        // this assumes the gist has a single file
            let filename = Object.keys(data.files)[0],
                fnArr = filename.match(/[^\.]+|\./g),
                extension = fnArr.slice(-1)[0],
                folederAndFile =  fnArr.slice(0)[0],
                folederAndFileArray = folederAndFile.split("\\");
            // only file string found, no need for folder
            if(folederAndFileArray.length === 1){
                fileStr = folederAndFile;
            }
            // else you need create folder and break name string to folder and file string parts
            else{ 
                hasFolder = true;
                folderStr = folederAndFileArray[0];
                fileStr = folederAndFileArray[1];
            }
            let code = data.files[filename].content;
            if(extension === "css"){
                
                if(hasFolder){
                    var styleArray = $scope.iframeScope.styleSheets;
                    for (var i = 0; i < styleArray.length; i++) {
                        if (styleArray[i].name === folderStr){
                            createFolder = false;
                            var id = styleArray[i].id;
                            $scope.iframeScope.setActiveCSSFolder(id); $scope.iframeScope.setSelectedCSSFolder(id);
                        } 
                        else if(styleArray[i].name === fileStr){ createStylesheet = false; }
                    }
                    if(createFolder)    {   recoda.addStyleSheet(folderStr, true);  }
                    
                }
                if(createStylesheet){
                    recoda.addStyleSheet(fileStr);
                    $scope.iframeScope.stylesheetToEdit.css = code;
                }else{
                    if (confirm('Are you sure you want to override existing stylesheet "'+ fileStr.toLocaleUpperCase() +'"?')) {
                        // Save it!
                        recoda.addStyleSheet(fileStr);
                        $scope.iframeScope.stylesheetToEdit.css = code;
                        recoda.toast('Stylesheet '+fileStr+' succesfully overriden!');
                      } else {
                        // Do nothing!
                        recoda.toast("Override Stylesheet action aborted!");
                      }
                }
                
            }
            else if(extension === "js"){
                recoda.addComponentWithoutTag("ct_code_block");
                var activeId = $scope.iframeScope.component.active.id;
                if($scope.isActiveName("ct_code_block")){
                    $scope.iframeScope.component.options[activeId]['model']['code-js']  = code;
                    $scope.iframeScope.applyCodeBlockJS();
                } else{ recoda.toast("Warning: Code Block not selected, action aborted!") }

            }else if(extension === "php"){
                recoda.addComponentWithoutTag("ct_code_block");
                var activeId = $scope.iframeScope.component.active.id;
                if($scope.isActiveName("ct_code_block")){
                    $scope.iframeScope.component.options[activeId]['model']['code-php'] = code;
                    $scope.iframeScope.applyCodeBlockPHP();
                } else{ recoda.toast("Warning: Code Block not selected, action aborted!") }
                
            }else{
                recoda.toast("Your file does not contain proper extension (.css, .js, .php)");
            }
       
        }
    
        function handleFail() {
        recoda.toast('Something went wrong. :(');
        }
    
        jQuery.get(url).done(handleDone).fail(handleFail);
    }
    
    /* Oxygen modified function $scope.addStyleSheet (controller.css.js, line: 622)  */
    /* Added direct name argument and data argument call via API */
    recoda.addStyleSheet = function(stylesheet, isFolder) {

		$scope.iframeScope.cancelDeleteUndo();
		if(!stylesheet) {  return;		}


		stylesheet = stylesheet.trim();
	    // check for validity of the name
    	var re = /^[a-z_-][a-z\d_-]*$/gi;
	    //var re = /-?[_a-zA-Z]+[_a-zA-Z0-9-]*$/i;
        stylesheet = stylesheet.replace(/[^a-z0-9]+/gi, '-');
	    if(!re.test(stylesheet)) {
	    	recoda.toast("Bad stylesheet name. Special characters and spaces are not allowed");
	    	return;
	    }
		
	    // check for repeat
	    var filtered = $scope.iframeScope.styleSheets.filter(function(item){ return item.name === stylesheet; });

	    if(filtered.length > 0) {
			recoda.toast("'" + stylesheet + "' already exist. Please choose another name.");
			return;
	    }
		
		// find the highest available ID;
		var newId = 1;

		if($scope.iframeScope.styleSheets.length > 0) {
			newId = _.max($scope.iframeScope.styleSheets, function(item) {
				return item.id;
			}).id + 1;
		}

		//$scope.styleSheets[stylesheet] = "";
		if(isFolder) {
			$scope.iframeScope.styleSheets.push({
				id: newId,
				name: stylesheet,
				status: 1,
				folder: 1
			});
		}
		else {
			var parent = $scope.iframeScope.selectedCSSFolder === -1 || $scope.iframeScope.selectedCSSFolder === null ? 0 : $scope.iframeScope.selectedCSSFolder;
			var newSheet = {
				id: newId,
				name: stylesheet,
				css: '',
				parent: parent,
			};

			$scope.iframeScope.styleSheets.push(newSheet);

			// expand the parent folder
			for(key in $scope.iframeScope.expandedFolder) { $scope.iframeScope.expandedFolder[key] = false };
			$scope.iframeScope.expandedFolder[parent] = true;
			$scope.iframeScope.setStyleSheetToEdit(newSheet);
		}	
        if(isFolder){
            $scope.iframeScope.setActiveCSSFolder(newId); $scope.iframeScope.setSelectedCSSFolder(newId)
        }
	}

    recoda.moveStylesheet = function(direction){
        var moveFlag = false;
        if(!$scope.iframeScope.stylesheetToEdit){
            return;
        }else{
            var resArr = $scope.iframeScope.styleSheets;
            var resId = $scope.iframeScope.stylesheetToEdit.id;
            var currentIndex = resArr.findIndex( (element) => element.id === resId);
            var newIndex = 0;
            if(direction === "down"){
                newIndex = 9999999999;
            }
            var parentId = $scope.iframeScope.stylesheetToEdit.parent;
            if(currentIndex < 1) return;
        }
        if (direction === "up"){
            resArr.forEach(function(obj) {
                if(obj.parent === parentId){
                    var tempId = obj.id;
                    var tempIndex = resArr.findIndex( (element) => element.id === tempId);
                    if(tempIndex > newIndex && tempIndex < currentIndex ){
                        newIndex = tempIndex;
                        moveFlag = true;
                    }
                }
              })
        }
        if(direction === "down"){
            resArr.forEach(function(obj) {
                if(obj.parent === parentId){
                    var tempId = obj.id;
                    var tempIndex = resArr.findIndex( (element) => element.id === tempId);
                    if(tempIndex < newIndex && tempIndex > currentIndex ){
                        newIndex = tempIndex;
                        moveFlag = true;
                    }
                }
              })
        }

        if (moveFlag){
            array_move($scope.iframeScope.styleSheets, currentIndex, newIndex); 
        }
    }
    recoda.moveSelector = function(direction){
        var moveFlag = false;
        if(!$scope.iframeScope.selectorToEdit){
            return;
        }else{
            //selArr = array of selector, used for temp data manipulation, selKey = key of current selector, currentIndex = index of current selector in selArray (temp array)
            var selArr = $scope.iframeScope.objectToArrayObject($scope.iframeScope.classes);
            var selKey = $scope.iframeScope.selectorToEdit.replace(".", "");
            var currentIndex = selArr.findIndex( (element) => element.key === selKey);
            
            var newIndex = -1;
            if(direction === "down"){
                newIndex = 99999999999999;
            }
            var parentId = $scope.iframeScope.classes[selKey].parent;
        }
        if (direction === "up"){
            selArr.forEach(function(obj) {
                if(obj.parent === parentId){
                    var tempKey = obj.key;
                    var tempIndex = selArr.findIndex( (element) => element.key === tempKey);
                    if(tempIndex > newIndex && tempIndex < currentIndex ){
                        newIndex = tempIndex;
                        moveFlag = true;
                    }
                }
              })
        }
        if(direction === "down"){
            selArr.forEach(function(obj) {
                if(obj.parent === parentId){
                    var tempKey = obj.key;
                    var tempIndex = selArr.findIndex( (element) => element.key === tempKey);
                    if(tempIndex < newIndex && tempIndex > currentIndex ){
                        newIndex = tempIndex;
                        moveFlag = true;
                    }
                }
              })
        }

        if (moveFlag){
            //
            array_move(selArr, currentIndex, newIndex);
            var selectorArrayToObject = Object.assign({}, ...(selArr.map(item => ({ [item.key]: item }) ))); 
            $scope.iframeScope.classes = selectorArrayToObject;
        }
    }

    recoda.addClassToActiveID = function (newClassName){
        const   Oxy         =   $scope.iframeScope,
                options     =   $scope.iframeScope.component.options,
                {id, name}  =   $scope.iframeScope.component.active;
        
        if( recoda.checkLocalStorageOption("ws-cli-autorename","first-class-name") && Oxy.componentsClasses[id] === undefined) { 
            recoda.renameElement(newClassName); 
        }
		Oxy.newcomponentclass.name = newClassName;
		Oxy.tryAddClassToComponent(id);
    }
    recoda.addComponentWithTagAndRename = function(component, mytag) {
        const   Oxy         =   $scope.iframeScope;
        Oxy.addComponent(component);
        Oxy.setOptionModel("tag", mytag);
        Oxy.changeTag();
    }
  
    recoda.changeTag = function(mytag) {
        const   Oxy         =   $scope.iframeScope;
        let     {name}      =   $scope.iframeScope.component.active;

        switch( name ) {
            case "ct_text_block":case "ct_section":case "ct_code_block":case "ct_div_block":case "ct_headline":
                Oxy.setOptionModel("tag", mytag);
                Oxy.changeTag();
            break;
            default:
                recoda.toast("Not supported tag change for active element!");
            break;
        }

    }

    recoda.addAttribute = function(attCommand) {
        const   Oxy      =   $scope.iframeScope,
                options  =   $scope.iframeScope.component.options,
                {id}       =   $scope.iframeScope.component.active;

        $scope.addCustomAttribute();
        var attLenght = options[id].model["custom-attributes"]["length"]
        var index = attLenght - 1;
        var att= attCommand.match(/[^\=]+|\=/g);
       
        var attName = att[0];
        if(att[0] != "="){
            options[id]['model']['custom-attributes'][index]['name'] = attName;
            var attValue;
            if (att[2] != undefined ) { 
                    attValue = att[2];
                    options[id]['model']['custom-attributes'][index]['value'] = attValue;
            }   
        }
        else {  
            if (att[1] != undefined ) { 
            attValue = att[1];
            options[id]['model']['custom-attributes'][index]['value'] = attValue;    
            }
        }
        Oxy.rebuildDOM(id);
   }

   
    recoda.doOneUp = function(){
        const   Oxy         =   $scope.iframeScope,
                {id,name}   =   $scope.iframeScope.component.active.parent;
        Oxy.activateComponent( id, name );
    }


    recoda.doTwoUp = function(){
        const   Oxy         =   $scope.iframeScope,
                {id,name}   =   $scope.iframeScope.component.active;
        
        Oxy.activateComponent( id, name );
        Oxy.activateComponent( id, name );
     }

    recoda.openCloseModal= function(id){
        let selector =`[data-ws-modal="${id}"]`;
        var target = document.querySelectorAll(selector);
       
        
        target = document.querySelector(selector);
        if (!target.classList.contains("ws-HIDE")){
            target.classList.add("ws-HIDE");
        } 
        else{
            target.classList.remove("ws-HIDE");
        } 
    }
   
    recoda.addComponentWithoutTag = function(component) {
        const   Oxy =  $scope.iframeScope;
        
        Oxy.addComponent(component);	
    }
   
    recoda.afterCommandReload = function(){
        const   Oxy =  $scope.iframeScope;

        Oxy.undo();
        Oxy.redo();
    }

    recoda.setPropertyPX_sidebabar = function(a,b){
        document.getElementById("oxygen-sidebar").style.setProperty(a, b + "px");
    }
    recoda.setPropertyPX = function(a,b){
        document.documentElement.style.setProperty(a, b + "px");
    }
    recoda.setCanvasPX = function(value){
        if($scope.iframeScope.currentMedia != "default") return recoda.toast("This feature is only supported for default media query");
        recoda.isZoomRequested = true;
        let elW = document.getElementById("ct-viewport-container").offsetWidth - 12,
            val = elW / value,
            z = val * 100,
            zoomPercentage = parseInt(z,10);
    
        document.documentElement.style.setProperty('--zoom-scale', val );
        jQuery("#wsInputCanvasWidth").val(value);
        document.getElementById("rewsTrueCanvasWidth").value = value;
        jQuery("#wsInputCanvasScale").val(zoomPercentage);
        document.getElementById("rewsCanvasZoom").value = zoomPercentage;
        
    }
    recoda.callSetCanvasPX = function(value){
        let elW = document.getElementById("ct-viewport-container").offsetWidth - 12,
            val = elW / value,
            z = val * 100,
            zoomPercentage = parseInt(z,10);
    
        document.documentElement.style.setProperty('--zoom-scale', val );
        jQuery("#wsInputCanvasWidth").val(value);
        document.getElementById("rewsTrueCanvasWidth").value = value;
        jQuery("#wsInputCanvasScale").val(zoomPercentage);
        document.getElementById("rewsCanvasZoom").value = zoomPercentage;
        
    }
    recoda.setDevice = function(width, height){      
        const   Oxy     = $scope.iframeScope,
                media   = $scope.iframeScope.mediaList;
        let option= document.getElementById("rews-resp-1"),
            mq_PW = parseInt(media['page-width']['maxSize'], 10),
            mq_TB = parseInt(media['tablet']['maxSize'], 10),
            mq_PL = parseInt(media['phone-landscape']['maxSize'], 10),
            mq_PP = parseInt(media['phone-portrait']['maxSize'], 10);

        if(!option.checked){ 
            option.checked=true; 
            jQuery("#ct-artificial-viewport").toggleClass("rews-responsive-mode");
            jQuery("#ct-artificial-viewport").toggleClass("rews-device-body");
            jQuery("#ct-viewport-container").toggleClass("rews-scrollY");
        }
        document.documentElement.style.setProperty('--responsive-w', width + "px");
        document.documentElement.style.setProperty('--responsive-h', height + "px");
        jQuery("#wsReponsiveInputWidth").val(width);
        jQuery("#wsReponsiveInputHeight").val(height);

        if     (width < mq_PP)      { Oxy.setCurrentMedia('phone-portrait',true,Oxy.isEditing('class')); }
        else if (width < mq_PL)      { Oxy.setCurrentMedia('phone-landscape',true,Oxy.isEditing('class'));   }
        else if (width < mq_TB)      { Oxy.setCurrentMedia('tablet',true,Oxy.isEditing('class'));    }
        else if (width < mq_PW)      { Oxy.setCurrentMedia('page-width',true,Oxy.isEditing('class'));   }

        /* SCALING PART: Check if needed scale down to fit device */
        let target = document.documentElement.style,
            canvas = document.getElementById("ct-viewport-container"), 
            canvasH = canvas.offsetHeight, 
            canvasW = canvas.offsetWidth,
            h = parseInt(height,10) + 80,
            w = parseInt(width,10) + 52,
            scaleH =  canvasH / h,
            scaleW = canvasW / w;
       
        /* if any scale value less then 1 take smaller value to fit*/
        if (scaleH < 1 || scaleW < 1){
            if (scaleH < scaleW){
                target.setProperty('--zoom-scale', scaleH );
            }
            else{
                target.setProperty('--zoom-scale', scaleW );
            }
        }
        /* If everything fine, reset zoom to 1 because it could be some previous setted value*/
        else{
            target.setProperty('--zoom-scale', 1 );
        }
    }

    ///
    recoda.setDeviceW = function(width){
        document.documentElement.style.setProperty('--responsive-w', width + "px");
    }
    recoda.setDeviceH = function(height){
        document.documentElement.style.setProperty('--responsive-h', height + "px");
    }    
    recoda.setCanvasZoom = function(canvasZoom, e){
        if($scope.iframeScope.currentMedia != "default") return recoda.toast("This feature is only supported for default media query");
        recoda.isZoomRequested = true;
        // set zoom interval <20%,150%>
        if(val < 0.2)       {  val=0.2; jQuery(this).val("20");   }
        if(val > 1.5)       {  val=1.5; jQuery(this).val("150");   }
        var val= canvasZoom * 0.01;
      
        document.documentElement.style.setProperty('--zoom-scale', val );
        var canvasWidth = parseInt(jQuery("#ct-artificial-viewport").width(),10);
        jQuery("#wsInputCanvasScale").val(canvasZoom);
        jQuery("#wsInputCanvasWidth").val(canvasWidth);

        document.getElementById("rewsTrueCanvasWidth").value = canvasWidth;
        document.getElementById("rewsCanvasZoom").value = canvasZoom;
    }
    recoda.callSetCanvasZoom = function(canvasZoom, e){
        // set zoom interval <20%,150%>
        if(val < 0.2)       {  val=0.2; jQuery(this).val("20");   }
        if(val > 1.5)       {  val=1.5; jQuery(this).val("150");   }
        var val= canvasZoom * 0.01;
      
        document.documentElement.style.setProperty('--zoom-scale', val );
        var canvasWidth = parseInt(jQuery("#ct-artificial-viewport").width(),10);
        jQuery("#wsInputCanvasScale").val(canvasZoom);
        jQuery("#wsInputCanvasWidth").val(canvasWidth);

        document.getElementById("rewsTrueCanvasWidth").value = canvasWidth;
        document.getElementById("rewsCanvasZoom").value = canvasZoom;
    }
    recoda.openSmartPanel =  function(targetElm, property){
        var min_w;
        var def_w;
        var a;  
        min_w = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--min-sidebar-width"),10);
        def_w = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--def-sidebar-width"),10);
        a= targetElm.css("left");
        var b = parseInt(a, 10);
        if (b < 100) {  recoda.setPropertyPX(property, def_w);   } 

    }
    recoda.setSmartPanel = function(targetElm, property, control){
        var min_w;
        var def_w;
        var a; 
        var b; 
        targetElm.on('dblclick',function () {        
            if(property === "--sidepanel-width") { 
                min_w = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--min-sidepanel-width"),10);
                def_w = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--def-sidepanel-width"),10);
                b = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--sidepanel-width"),10);
            }
            else if(property === "--sidebar-width") { 
                min_w = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--min-sidebar-width"),10);
                def_w = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--def-sidebar-width"),10);
                b = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--sidebar-width"),10);
            }
            
            if (b > (def_w + 100)) {
                    recoda.setPropertyPX(property, def_w);
                }
            else {         
                if (b < 100) {  recoda.setPropertyPX(property, def_w);
                                $scope.doShowLeftSidebar(true);              
                } 
                else {   
                    if(property === "--sidebar-width" && angular.element("#ct-controller-ui").scope().showLeftSidebar === true) { $scope.doHideLeftSidebar(true); 
                        setTimeout(function(){
                        recoda.setPropertyPX(property, min_w );
                        }, 50); 
                    }  
                    else {
                        recoda.setPropertyPX(property, min_w );
                    }
                }
            }
      
        var builder= document.getElementById('ct-controller-ui');
        if (builder.classList.contains("rews-fullscreen")) {
            document.getElementById("rews-tg-fs").classList.remove('active');
            builder.classList.remove("rews-fullscreen");
            recoda.toast('Exited from Fullscreen Mode');   
        }
           
        });
    }
    recoda.togglePanels = function(){
        var panel1, panel2, default1, default2, v1, v2;
        v1 = '--sidepanel-width';
        v2 = '--sidebar-width';
        panel1 = parseInt(getComputedStyle(document.documentElement).getPropertyValue(v1),10);
        panel2 = parseInt(getComputedStyle(document.documentElement).getPropertyValue(v2),10);
        default1 = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--def-sidepanel-width'),10);
        default2 = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--def-sidebar-width'),10);

        if (panel1 > 200 || panel2 > 200){
            $scope.doHideLeftSidebar(true);
            recoda.setPropertyPX(v1, '0');
            recoda.setPropertyPX(v2, '0');
            return true;
            
        }
        else{
            $scope.doShowLeftSidebar(true);
            recoda.setPropertyPX(v1, default1);
            recoda.setPropertyPX(v2, default2);
            return false;
        }
    }
    // function used for commander, takes desired tag command and adds desired element
    recoda.selectCommand = function( tag ) {

        switch( tag ) {
           
            case "section":
                recoda.addComponentWithoutTag("ct_section");
            break;
            case "h1":case "h2":case "h3":case "h4":case "h5":case "h6":
                recoda.addComponentWithTagAndRename("ct_headline", tag);
            break;
            case "p":case "textarea":
                recoda.addComponentWithTagAndRename("ct_text_block", tag)
            break;
            case "button":
                recoda.addComponentWithTagAndRename("ct_text_block", tag)
            break;
            case "b":
                recoda.addComponentWithTagAndRename("ct_text_block", "button")
            break;
            case "text":case "txt":
                recoda.addComponentWithoutTag("ct_text_block");
            break;
            case "a":
                recoda.addComponentWithoutTag("ct_link_text");
            break;
            case "awrap":case "aw":
                recoda.addComponentWithoutTag("ct_link");
                break;
            case "abutton":case "ab":
                recoda.addComponentWithoutTag("ct_link_button");
                break;
            case "img":
                recoda.addComponentWithoutTag("ct_image");
                break;
            case "video":case "v":
                recoda.addComponentWithoutTag("ct_video");
                break;
            case "icon":case "ic":
                recoda.addComponentWithoutTag("ct_fancy_icon");
                break;
            case "cb":
                recoda.addComponentWithoutTag("ct_code_block");
                break;
            case "sicons":
                recoda.addComponentWithoutTag("oxy_social_icons");
                break;
            case "testimonial":case "tt":
                recoda.addComponentWithoutTag("oxy_testimonial");
                break;
            case "ibox":
                recoda.addComponentWithoutTag("oxy_icon_box");
                break;
            case "pbox":
                recoda.addComponentWithoutTag("oxy_pricing_box");
                break;
            case "pbar":
                recoda.addComponentWithoutTag("oxy_progress_bar");
                break;
            case "gallery":
                recoda.addComponentWithoutTag("oxy_gallery");
                break;
            case "slider":
                recoda.addComponentWithoutTag("ct_slider");
                break;
            case "tabs":
                recoda.addComponentWithoutTag("oxy_tabs");
                break;
            case "superbox":
                recoda.addComponentWithoutTag("oxy_superbox");
                break;
            case "toggle":case "gg":
                recoda.addComponentWithoutTag("oxy_toggle");
                break;
            case "modal":
                recoda.addComponentWithoutTag("ct_modal");
                break;
            case "map":
                recoda.addComponentWithoutTag("oxy_map");
                break;
                
            default:
                recoda.addComponentWithTagAndRename("ct_div_block", tag);
        }
    }
    recoda.addSelectorToCLI = function(){
        const   Oxy         = $scope.iframeScope,
                options     = $scope.iframeScope.component.options,
                {id}  = $scope.iframeScope.component.active;
        let val = Oxy.currentClass,
            preVal= recoda.cmdCodeMirror.getValue(),
            newVal;

            if (!val){val = "#" + options[id]['selector'];}
            else { val = "." + val}
        newVal = preVal + val; 
        recoda.cmdCodeMirror.setValue(newVal)
    }
    recoda.addAttributeToCLI = function(){
        let scope = jQuery(document.activeElement).parent().closest(".ng-scope").find(".oxygen-control-row"),
            preVal= recoda.cmdCodeMirror.getValue(),
            val,
            newVal,
            attName = scope.eq(1).children().find("input").val(),
            attValue = scope.eq(2).children().find("input").val();
        val = "[" + attName + "=" + attValue + "]";
        newVal = preVal + val;
        recoda.cmdCodeMirror.setValue(newVal);
    }
    //function used for Left/Right Resizers, takes 1.minimal width panel, 2.max panel width, 3.width which trigger magnetic behaviour, 4.desired drag prop, 5. safe area prop, 6. "left" or "right"
    
    recoda.dragElement = function (	
        elmnt, 
        item, 
        safe, 						
        control	) {
        /* otherwise, move the DIV from anywhere inside the DIV:*/
        let pos3, pos1, setVal, widthFromRight, min_w, max_w,
            docking = false,
            workspaceWidth = window.innerWidth,
            magneticTrigger =  parseInt(getComputedStyle(document.documentElement).getPropertyValue("--magnetic-trigger"),10);

        elmnt.onmousedown = dragMouseDown;
        if(document.querySelector("body").classList.contains("ws-docking_swap")){
            control = (control === 'right') ? 'left' :'right';
            docking = "swap";
        }
        function dragMouseDown(e) {
            if(document.querySelector("body").classList.contains("ws-docking_left")){
                control = "left";
                docking = true;
            }
            if(document.querySelector("body").classList.contains("ws-docking_left-swap")){
                control = "left-swap";
                docking = true;
            }
            if(document.querySelector("body").classList.contains("ws-docking_right")){
                control = "right";
                docking = true;
            }
            
            magneticTrigger =  parseInt(getComputedStyle(document.documentElement).getPropertyValue("--magnetic-trigger"),10);
            workspaceWidth = window.innerWidth;
            var ratioL = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--ratio"),10) - 1;
            var ratioR = 98 - ratioL; 
            if(control === "right") { 
                min_w = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--min-sidepanel-width"),10);
                max_w = ratioR * (workspaceWidth/100);
            }
            else { 
                min_w = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--min-sidebar-width"),10);
                max_w = ratioL * (workspaceWidth/100);
            }
            //inser here
            e = e || wnd.event;
            // get the mouse cursor position at startup:
            pos3 = e.clientX;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
            jQuery(" #resizer-lp-drawer ").toggleClass("tbf-drag-overaly");
            if( item === "--sidepanel-width"){
                jQuery(" #resizer-rp-d ").toggleClass("ws_DISPLAY");
            }
            else if (item === "--sidebar-width"){
                jQuery(" #resizer-lp-d ").toggleClass("ws_DISPLAY");
            }
        }
        function elementDrag(e) {
                
                // actual callback
                e = e || window.event;
                // calculate the new cursor position:
                pos1 = pos3 - e.clientX;
                pos3 = e.clientX;
                widthFromRight = workspaceWidth - e.clientX;
                setVal= e.clientX;
               
                
                if(control === "right") { 
                   setVal = widthFromRight; 
                }; 
                /* Adapt mechanism when panel docked to left*/
                if (docking && control === "left" && item === "--sidebar-width"){
                    var b= parseInt(document.documentElement.style.getPropertyValue('--sidepanel-width'),10);
                    setVal = setVal - b;
                }
                if (docking && control === "left-swap" && item === "--sidepanel-width"){
                    var b= parseInt(document.documentElement.style.getPropertyValue('--sidebar-width'),10);
                    setVal = setVal - b;
                }
                //++ MAGNETIC GESTURE> HIDE: sidebar, SET big safe-area
                if (setVal < magneticTrigger) {
                    //if magnetic behaviour is triggered and it is LEFT Panel, do Hide Left Sidebar
                    if(item === "--sidebar-width") { angular.element("#ct-controller-ui").scope().doHideLeftSidebar(true);}
                    recoda.setPropertyPX( item, min_w );
                    recoda.setPropertyPX( safe, 300 );
                    
                return;
                }
                else if (setVal < 220){
                    recoda.setPropertyPX( item, 220 );
                }
    
                //++ LOCK dragging to max-w logic algortihm
                else if (setVal > max_w) {
                    recoda.setPropertyPX(item, max_w );
                    return;
                } 
                else {
                    // NORMAL MODE
                    recoda.setPropertyPX(item, setVal );
                // SHOW left sidebar, SET smaller safer area
                
                if (setVal > magneticTrigger) {
                    recoda.setPropertyPX(safe, 100);
                }

                }
            
        }

        function closeDragElement() {
            /* stop moving when mouse button is released:*/
            document.onmouseup = null;
            document.onmousemove = null;
            jQuery(" #resizer-lp-drawer ").toggleClass("tbf-drag-overaly");
            if( item === "--sidepanel-width"){
                jQuery(" #resizer-rp-d ").toggleClass("ws_DISPLAY");
            }
            else if (item === "--sidebar-width"){
                var pane = document.getElementById("oxygen-sidebar");
                jQuery(" #resizer-lp-d ").toggleClass("ws_DISPLAY");
                if(setVal > 199){
                    $scope.doShowLeftSidebar(true);
                }
            }
            if(setVal > 199){
                var builder= document.getElementById('ct-controller-ui');
                if (builder.classList.contains("rews-fullscreen")) {
                    document.getElementById("rews-tg-fs").classList.remove('active');
                    builder.classList.remove("rews-fullscreen");
                    recoda.toast('Exited from Fullscreen Mode');   
                }
            }
        }
    }

   
    recoda.executeCommand = function(rawCommand){ 
       
        if(rawCommand == undefined) return recoda.toast("Command undefined!");
        var cmdN = recoda.commandHistoryItems.length;
        var obj = {};
        obj["text"] = rawCommand;
        obj["displayText"] = "command["+ cmdN +"]:      " +rawCommand;
        recoda.commandHistoryItems.unshift(obj);
        
        var N__content  = 0;
        var N__snippets = 0;
        var escapedContent =  [];
        var escapedSnippets =  [];

        var cTemp = rawCommand.split('{');
        var sTemp = rawCommand.split('(');

        for (var i = 1; i < cTemp.length; i++) {
            escapedContent.push(cTemp[i].split('}')[0]);
        }
        for (var i = 1; i < sTemp.length; i++) {
            escapedSnippets.push(sTemp[i].split(')')[0]);
        }
        /* escaping command , e1,e2,e3 = escaping iteration steps to make easier regex */
        var e1 = rawCommand.replace(/(\{).+?(\})/g, "{☀}");
        var escCommand = e1.replace(/ *\([^)]*\) */g, "(💩)");
        //after escape
        var command = escCommand.replaceAll(' .', '.');
       
        if (command[0] === "|" || command[0] === "/" || command[0] === "'" || command[0] === ">" || command[0] === "@" || command[0] === "." || command[0] === "!" || command[0] === "+" || command[0] === "^" || command[0] === "#" || command[0] === "*" || command[0] === "(" || command[0] === "{" || command[0] === "[" || /[a-z]/.test(command[0])) {
            var s = command
                .match(/[^\|]+|\|/g)
                .join(",")
                .match(/[^\/]+|\//g)
                .join(",")
                .match(/[^\^]+|\^/g)
                .join(",")
                .match(/[^\']+|\'/g)
                .join(",")
                .match(/[^\:]+|\:/g)
                .join(",")
                .match(/[^\*]+|\*/g)
                .join(",")
                .match(/[^\@]+|\@/g)
                .join(",")
                .match(/[^>]+|>/g)
                .join(",")
                .match(/[^\+]+|\+/g)
                .join(",")
                .match(/[^\.]+|\./g)
                .join(",")
                .match(/[^\!]+|\!/g)
                .join(",")
                .match(/[^\#]+|\#/g)
                .join(",")
                .match(/[^\(]+|\(/g)
                .join(",")
                .match(/[^\)]+|\)/g)
                .join(",")
                .match(/[^\[]+|\[/g)
                .join(",")
                .match(/[^\]]+|\]/g)
                .join(",")
                .match(/[^\{}]+|\{/g)
                .join(",")
                .match(/[^\}]+|\}/g)
                .join(",")
                .split(",");
              
                
                    //commdand example: div>p{MyTxt}.class1.class2.class3.class4 
                    
            var commandArray = s.filter(function (el) {	return el;  });
         
            var c = commandArray;
            var g = 0;
            var passed = 1;
  
        
        for (var i = 0; i < c.length; i++) {
                    
            if (c[i] === c[c.lenght - 1]) {
                            s = c[i];
                            
                if (/[a-z]/.test(s)) {
                            } else return 0;
                        }

            /** */
             //Rename element with
             if (c[i] === ":") {recoda.renameElement(c[i-1])}
             //duplicate command 
             /* Case 1: duplicate command is first char */     
             if (c[0] === "*" && c[i] === "*") {	
                 /* Case 1.1: duplicate command is first char and  followed by number */ 
                if(c[1] != null){
                    var n = c[1];
                    if(!isNaN(n)){
                        for (var di = 0; di < n; di++) {
                            recoda.log(n);
                            recoda.log(di);
                            $scope.iframeScope.duplicateComponent();
                            recoda.log("duplicate #1");
                        }
                    }
                }
                /* Case 1.2: duplicate command is first char and NOT followed by number */ 
                else{
                    $scope.iframeScope.duplicateComponent();
                    recoda.log("duplicate #2");
                }
            /* Case 2: duplicate command is not first command, it is somewher in command sequence */ 
            } else if ((c[i - 1] === "*")) {
                
                //bugfix condition: prevents duplication and multiplications to happen on same command
                if (i>1){
                    if(c[i] != null){
                        var n = c[i];
                        if(!isNaN(n)){
                            for (var di = 1; di < n; di++) {
                                $scope.iframeScope.duplicateComponent();
                                recoda.log("duplicate #3");
                            }
                        }
                    }
                }	
            }
            
            if         (c[i - 1] === "#") {	recoda.changeElementID(c[i]); recoda.log("ID"); 		       }
            else if     (c[i - 1] === ".") { recoda.addClassToActiveID(c[i]); recoda.log("CLASS");
                                            if(c[i+1] != null){
                                                if(c[i+1] === "!"){
                                                    if ($scope.iframeScope.component.options[$scope.iframeScope.component.active.id]['model']['selector-locked'] != "true"){
                                                        $scope.iframeScope.component.options[$scope.iframeScope.component.active.id]['model']['selector-locked'] = "true";
                                                        $scope.iframeScope.setOption($scope.iframeScope.component.active.id,$scope.iframeScope.component.active.name,'selector-locked');
                                                    }
                                                } 
                                            }
     		} 
            // Get escaped content and change content command   
            else if (c[i - 1] === "{") {	   

                if(c[i] === "☀")  {
                    recoda.injectContent(escapedContent[N__content]);
                    N__content = N__content + 1;
                } else{ recoda.injectContent(c[i]); }
                 recoda.log("CONTENT " + escapedContent[N__content]);   

            }
            // Get escaped snippets url and execute snippets creation from url   
            else if (c[i - 1] === "(") {	   

                if(c[i] === "💩")  {
                    recoda.getGistContentViaUrl(escapedSnippets[N__snippets]);
                    N__snippets = N__snippets + 1;
                } else{ console.log("WRONG") }
               

            }
            //add here change tag
            else if     (c[i - 1] === "'") {	recoda.changeTag(c[i]); recoda.log("TAG");               }
            else if     (c[i - 1] === "|") {	recoda.addStyleSheet(c[i], true); recoda.log("FOLDER");               }
            else if     (c[i - 1] === "/") {	recoda.addStyleSheet(c[i]); recoda.log("STYLESHEET");               }
            else if     (c[i - 1] === "[") {	recoda.addAttribute(c[i]); recoda.log("ATT");               }
            else if     (c[i - 1] === "@") {	recoda.renameElement(c[i]); recoda.log("RENAME");               }
            else if     (c[i - 1] === ">") {
                            s = c[i];
                            if (/[a-z]/.test(s)) {
                                recoda.selectCommand(s);
                                recoda.log("add >");
                            }
            } else if (c[i - 1] === "+") {
                            s = c[i];
                            
                if (/[a-z]/.test(s)) {
                                if(recoda.isActiveElementNestable()){
                                    recoda.doOneUp();
                                }
                                recoda.selectCommand(s);
                                recoda.log("add +");
                                
                            }
            } else if (c[i - 1] === "^") {
                                var n = c[i];
                                if(!isNaN(n)){
                                    for (var di = 0; di < n; di++) {
                                        recoda.doOneUp();
                                    }
                                }
                                else{
                                    recoda.doOneUp();
                                    recoda.selectCommand(s);
                                }
                    recoda.log("add ^");
                                
                          
            } else if (c[i - 1] === "(") {
                            s = c[i];
                            
                if (/[a-z]/.test(s)) {
                                recoda.selectCommand(s);
                                recoda.log("group");
                            }	
            }else if (/[a-z]/.test(c[i])){
                if (/[a-z]/.test(c[0])){
                recoda.selectCommand(c[i]);
                }
            }
                        
            } /* end of for-loop */
        
            }

    }

}); //doc ready
/* Create custom events to be sure you trigger */


(async() => { for(let n=0;(n<10 && !window.hasOwnProperty('recoda')) ;n++){ await new Promise(resolve => setTimeout(resolve, 250));} 
    var iframe = document.getElementById('ct-artificial-viewport');
    var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
    const event1 = new Event('wsInit');
    document.dispatchEvent(event1); 
    innerDoc.dispatchEvent(event1); 
    (async() => { for(let m=0;(m<10 && !$scope.pageLoaded) ;m++){ await new Promise(resolve => setTimeout(resolve, 250)); } 
        const event2 = new Event('wsLoad');
        document.dispatchEvent(event2); 
        innerDoc.dispatchEvent(event2); 
    })(); //asnyc wait end ev2  
})(); //asnyc wait end  ev1 
