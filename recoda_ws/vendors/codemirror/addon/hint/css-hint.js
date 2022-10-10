// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: https://codemirror.net/LICENSE
// Customized CSS CodeSense by Renato Corluka
(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
    mod(require("../../lib/codemirror"), require("../../mode/css/css"));
  else if (typeof define == "function" && define.amd) // AMD
    define(["../../lib/codemirror", "../../mode/css/css"], mod);
  else // Plain browser env
    mod(CodeMirror);
})(function(CodeMirror) {
  "use strict";

  var pseudoClasses = {"active":1, "after":1, "before":1, "checked":1, "default":1,
    "disabled":1, "empty":1, "enabled":1, "first-child":1, "first-letter":1,
    "first-line":1, "first-of-type":1, "focus":1, "hover":1, "in-range":1,
    "indeterminate":1, "invalid":1, "lang":1, "last-child":1, "last-of-type":1,
    "link":1, "not":1, "nth-child":1, "nth-last-child":1, "nth-last-of-type":1,
    "nth-of-type":1, "only-of-type":1, "only-child":1, "optional":1, "out-of-range":1,
    "placeholder":1, "read-only":1, "read-write":1, "required":1, "root":1,
    "selection":1, "target":1, "valid":1, "visited":1
  };

  var recodaCodeSenseTags = {"a":1, "article":1, "aside":1, "button":1, "blockquote":1,
    "br":1, "caption":1, "div":1, "dd":1, "dl":1,
    "dt":1, "em":1, "fieldset":1, "figcaption":1, "figure":1,
    "footer":1, "h2":1, "h3":1, "h4":1, "h5":1,
    "h6":1, "h1":1, "head":1, "header":1, "hgroup":1,
    "hr":1, "img":1, "input":1, "optional":1, "label":1,
    "li":1, "main":1, "nav":1, "ol":1, "p":1,
    "section":1, "summary":1, "table":1, "tbody":1,
    "td":1, "th":1, "textarea":1, "tfoot":1,
    "thead":1, "ul":1
  };
  var recodaCodeSenseProps = {"align-content: ":1, "align-items: ":1, "align-self: ":1, "animation: ":1, "animation-delay: ":1,
  "animation-direction: ":1, "animation-duration: ":1, "animation-fill-mode: ":1, "animation-iteration-count: ":1, "animation-name: ":1,
  "animation-play-state: ":1, "animation-timing-function: ":1, "aspect-ratio: ":1, 
  "background: ":1, "background-color: ":1, "background-attachment: ":1, "background-blend-mode: ":1, "background-clip: ":1,
  "background-color: ":1, "background-image: ":1, "background-origin: ":1, "background-position: ":1, "background-repeat: ":1,"background-size: ":1, 
  "border: ":1, "border-radius: ":1, "border-top: ":1, "border-bottom: ":1, "border-left: ":1, "border-right: ":1, "border-bottom-left-radius: ":1, "border-bottom-right-radius: ":1,    
  "border-spacing: ":1,"border-style: ":1, "border-width: ":1, "border-color: ":1,  "border-top-left-radius: ":1,  
  "border-top-right-radius: ":1, "border-collapse: ":1,
  "bottom: ":1, "box-shadow: ":1, "box-sizing: ":1,
  "color: ":1, "clip: ":1, "clip-path: ":1, "clear: ":1, "content: ":1, "columns: ":1, "counter-increment: ":1, "counter-reset: ":1, "cursor: ":1, 
  "display: ":1, "direction: ":1, "empty-cells: ":1, "filter: ":1,
  "flex: ":1, "flex-basis: ":1, "flex-direction: ":1, "flex-flow: ":1, "flex-grow: ":1, "flex-shrink: ":1, "flex-wrap: ":1, "float: ":1, "font-size: ":1,"font-weight: ":1, "font: ":1, 
  "font-family: ":1, "font-size-adjust: ":1, "font-stretch: ":1, "font-style: ":1, "font-variant: ":1, "font-kerning: ":1, 
  "grid-template: ":1, "grid-template-areas: ":1, "grid-template-columns: ":1, "grid-template-rows: ":1, "grid-gap: ":1, "grid-area :":1, "grid-column: ":1,"grid-row: ":1, "grid-column-gap: ":1, "grid-row-gap: ":1,
  "height: ":1,  "justify-content: ":1, "justify-items: ":1, "justify-self: ":1, "left: ":1,  "letter-spacing: ":1, "line-height: ":1,  "list-style: ":1, "list-style-image: ":1, "list-style-position: ":1, "list-style-type: ":1,
  "margin: ":1, "margin-bottom: ":1, "margin-left: ":1, "margin-right: ":1, "margin-top: ":1, "max-height: ":1, "max-width: ":1, "min-height: ":1, "min-width: ":1,
  "object-fit: ":1,"object-position: ":1,"opacity: ":1, "order: ":1, "outline: ":1, "outline-color: ":1, "outline-offset: ":1, "outline-style: ":1, "outline-width: ":1, "overflow: ":1, "overflow-x: ":1, "overflow-y: ":1, 
  "padding: ":1, "padding-bottom: ":1, "padding-left: ":1, "padding-right: ":1, "padding-top: ":1, 
  "perspective: ":1, "perspective-origin: ":1, "position: ":1, "resize: ":1, "right: ":1, "tab-size: ":1, "table-layout: ":1, 
  "text-align: ":1, "text-align-last: ":1, "text-decoration: ":1, "text-decoration-color: ":1, "text-decoration-line: ":1, "text-decoration-style: ":1, "text-indent: ":1,
  "text-justify: ":1, "text-overflow: ":1, "text-shadow : ":1, "text-transform: ":1, "top: ":1, "transform: ":1, "transform-origin: ":1, "transform-style: ":1, 
  "transition: ":1, "transition-delay: ":1, "transition-duration: ":1, "transition-property: ":1, "transition-timing-function: ":1, 
  "vertical-align: ":1, "visibility: ":1, "white-space: ":1, "width: ":1, "word-break: ":1, "word-spacing: ":1, "word-wrap: ":1, "z-index: ":1, 
};
var _transitionProps = { "all ":1,
"background ":1, "background-color ":1, "backdrop-filter ":1, 
"background-color ":1, "background-image ":1, "background-origin ":1, "background-position ":1, "background-repeat ":1,"background-size ":1, 
"border ":1, "border-bottom ":1, "border-bottom-left-radius ":1, "border-bottom-right-radius ":1, "border-collapse ":1, "border-left ":1, "border-radius ":1, 
"border-right ":1, "border-spacing ":1,"border-style ":1, "border-width ":1, "border-color ":1, "border-top ":1, "border-top-left-radius ":1, 
"border-top-right-radius ":1, 
"bottom ":1, "box-shadow ":1, 
"color ":1, "clip ":1, "clip-path ":1, "clear ":1, "content ":1, "columns ":1, 
"filter ":1,
"flex ":1, "flex-basis ":1,  "flex-grow ":1, "flex-shrink ":1,  "float ":1, "font-size ":1,"font-weight ":1, "font ":1,
"grid-template ":1, "grid-template-areas ":1, "grid-template-columns ":1, "grid-template-rows ":1, "grid-gap ":1, "grid-area :":1, "grid-column ":1,"grid-row ":1, "grid-column-gap ":1, "grid-row-gap ":1,
"height ":1, "left ":1,  "letter-spacing ":1, "line-height ":1,  "margin ":1, "margin-bottom ":1, "margin-left ":1, "margin-right ":1, "margin-top ":1, "max-height ":1, "max-width ":1, "min-height ":1, "min-width ":1,
"opacity ":1, "order ":1, "outline ":1, "outline-color ":1, "outline-offset ":1,  "outline-width ":1,  
"padding ":1, "padding-bottom ":1, "padding-left ":1, "padding-right ":1, "padding-top ":1, 
"perspective ":1, "perspective-origin ":1,  "right ":1, "tab-size ":1,  
"text-decoration ":1, "text-decoration-color ":1, "text-decoration-line ":1, "text-decoration-style ":1, "text-indent ":1,
"text-justify ":1, "text-overflow ":1, "text-shadow  ":1, "text-transform ":1, "top ":1, "transform ":1, "transform-origin ":1, "transform-style ":1, 
"vertical-align ":1, "visibility ":1,  "width ":1, "word-break ":1, "word-spacing ":1, "z-index ":1, 
};

var _align_content = {"flex-start":1, "flex-end":1, "center":1, "space-between":1, "space-around ":1, "stretch":1,
};
var _align_items = {"flex-start":1, "flex-end":1, "center":1, "baseline":1, "stretch":1,
};
var _align_self = {"flex-start":1, "flex-end":1, "center":1, "baseline":1, "stretch":1, "auto":1,
};
var _animation_direction =  {"normal":1, "reverse":1, "alternate":1, "alternate-reverse":1, "initial":1, "inherit":1,
};
var _animation_fill_mode =  {"none":1, "forwards":1, "backwards":1, "both":1, "initial":1, "inherit":1,
};
var _animation_iteration_count =  {"infinite":1, "initial":1, "inherit":1, 
};
var _animation_play_state =  {"paused":1, "running":1, "initial":1, "inherit":1, 
};
var _animation_timing_function =  {"linear":1, "ease":1, "ease-in":1, "ease-out":1, "ease-in-out":1, "step-star":1, "ease-in":1, "step-end":1, 
"steps(int,start|end)":1, "step-star":1, "cubic-bezier(n,n,n,n)":1, "initial":1, "inherit":1, 
};
var _global = {"transparent":1, "initial":1, "inherit":1, 
};
var _global_k = {"initial":1, "inherit":1, 
};
var _background_attachment = {"scroll":1, "fixed":1, "local":1, "initial":1, "inherit":1, 
};
var _background_blend_mode = {"normal":1, "multiply":1, "screen":1, "overlay":1, "darken":1, "lighten":1, "color-dodge":1, "saturation":1, "color":1, "luminosity":1, 
};
var _background_clip = {"border-box":1, "padding-box":1, "content-box":1, "overlay":1, "initial":1, "inherit":1,  
};
var _background_image = {'url(" ")':1,"none":1, "initial":1, "inherit":1,   
};
var _background_origin = {"padding-box":1, "border-box":1, "content-box":1, "initial":1, "inherit":1,   
};
var _background_position = {"left":1, "top":1, "right":1, "bottom":1, "center":1,   
};
var _background_repeat = {"repeat":1, "repeat-x":1, "no-repeat":1, "initial":1, "inherit":1,   
};
var _background_size = {"auto":1, "cover":1, "contain":1, "initial":1, "inherit":1,   
};
var _border_style = {"none":1, "hidden":1, "dotted":1, "dashed":1, "solid":1, "double":1, "groove":1, "ridge":1, "inset":1, "outset":1, "initial":1, "inherit":1,      
};
var _border_image_repeat = {"stretch":1, "repeat":1, "round":1, "space":1, "initial":1, "inherit":1,   
};
var _border_image_slice = {"fill":1, "initial":1, "inherit":1,   
};
var _border_image_source = {"none":1, "initial":1, "inherit":1,   
};

var _border_image_collapse = {"separate":1, "collapse":1, "initial":1, "inherit":1,   
};
var _box_sizing = {"content-box":1, "border-box":1, "initial":1, "inherit":1,   
};
var _clip = {"auto":1, "rect(0px,0px,0px,0px)":1, "initial":1, "inherit":1,   
};
var _css_colors = CodeMirror.resolveMode("text/css").colorKeywords;      
var _clear = {"both":1, "none":1, "inline-end":1, "inline-start":1, "left":1, "revert":1, "right":1, "initial":1, "inherit":1, "unset":1,  
};
var _content = {"-moz-alt-content":1, "attr":1, "close-quote":1, "counter":1, "counters":1, "image-set":1, "revert":1, "initial":1, "inherit":1, "unset":1,
"none":1, "normal":1, "open-quote":1,  
};
var _columns = { "auto":1, "initial":1, "inherit":1, "revert":1, "unset":1,
};
var _counter_increment__reset = { "none":1, "initial":1, "inherit":1, "revert":1, "unset":1,
};
var _cursor = { "alias":1, "all-scroll":1, "auto":1, "cell":1, "context-menu":1, "col-resize":1, "copy":1, "crosshair":1, "default":1, "e-resize":1,
"ew-resize":1, "grab":1, "help":1, "move":1, "n-resize":1, "ne-resize":1, "nesw-resize":1, "ns-resize":1, "nw-resize":1, 
"nwse-resize":1, "no-drop":1, "none":1, "not-allowed":1, "pointer":1, "progress":1, "row-resize":1, "s-resize":1,"se-resize":1, "sw-resize":1, "text":1,"vertical-text":1, "hew-resize":1, "wait":1,
"zoom-in":1, "zoom-out":1, "initial":1, "inherit":1, 
};
var _direction = { "ltr":1, "rtl":1, "initial":1, "inherit":1,
};
var _display = { "flex":1, "grid":1, "block":1, "none":1, "inline":1, "inline-block":1,  "inline list-item":1, "inline list-item":1, "flow-root":1,
 "contents":1, "initial":1, "inherit":1,
};
var _empty_cells = { "show":1, "hide":1, "initial":1, "inherit":1,
};
var _filter = { "none ":1, "blur()":1, "brightness()":1, "contrast()":1, "drop-shadow()":1, "grayscale()":1, "hue-rotate()":1, "inherit":1,
"invert()":1, "opacity()":1, "saturate()":1, "sepia()":1, "url()":1,
};
var _flex_direction = { "column":1, "column-reverse":1,"row":1, "row-reverse":1,  "initial":1, "inherit":1,
};
var _flex_wrap = { "nowrap":1, "wrap":1, "wrap-reverse":1, "initial":1, "inherit":1,
};
var _float = { "none":1, "left":1, "right":1, "initial":1, "inherit":1,
};
var _font_kerning ={ "auto":1, "normal":1, "none":1, 
};
var _grid_area ={ "grid-row-start / grid-column-start / grid-row-end / grid-column-end | itemname":1, 
};
var _auto = { "auto":1, 
};
var _height = { "auto":1, "fit-content":1, "max-content":1, "min-content":1, "initial":1, "inherit":1, "revert":1, "unset":1,
};
var _justify_content = { "flex-start":1, "flex-end":1, "center":1, "space-between":1, "space-around":1, "space-evenly":1, "initial":1, "inherit":1,
};
var _justify_items = { "flex-start":1, "flex-end":1, "center":1, "baseline":1, "last baseline":1, "end":1, "normal":1, "left":1,"right":1, "revert":1, "safe":1, "initial":1, "inherit":1,
};
var _justify_self = { "auto":1, "flex-start":1, "flex-end":1, "center":1, "baseline":1, "last baseline":1,"end":1, "normal":1, "left":1,"right":1, "revert":1, "safe":1, "initial":1, "inherit":1,
};
var _auto_global =  { "auto":1, "inherit":1, "initial":1, "revert":1, "unset":1,
};
/* letter spacing, line height */
var _common_one =  { "auto":1, "inherit":1, "initial":1, "normal":1, "revert":1, "unset":1,
};
var _global_values =  { "inherit":1, "initial":1, "revert":1, "unset":1,
};

var _list_style_type = { "disc":1, "circle":1, "square":1, "decimal":1, "georgian":1, "trad-chinese-informal":1, "kannada":1, "'-'":1, "none":1,
};
var _list_style_image = { "none":1, "url('":1, "linear-gradient(":1, 
};
var _list_style_position = { "inside":1, "outside":1, 
};
var _outline_width = { "medium":1, "thick":1, "thin":1, 
};
var _overflow = { "auto":1, "clip":1, "hidden":1, "scroll":1, "visible":1,  
};
var _none = { "none":1,
};
var _l_t_r_b = { "left":1, "right":1, "top":1, "bottom":1,  
};
var _position= { "absolute":1, "fixed":1, "relative":1, "static":1, "sticky":1,   
};
var _resize = { "block":1, "both":1, "horizontal":1, "vertical":1, "none":1, "inline":1,   
};
var _table_layout = { "auto":1, "fixed":1,  
};
var _text_align = { "left":1,"right":1,"center":1, "start":1, "end":1, "justify":1, "match-parent":1, "unset":1,    
};
var _text_align_last = { "left":1,"right":1,"center":1, "start":1, "end":1, "justify":1, "unset":1,    
};
var _text_decoration_line = { "none":1,"underline":1,"overline":1, "line-through":1, "blink":1,   
};
var _text_decoration_style = { "solid":1,"double":1,"dotted":1, "dashed":1, "wavy":1,   
};
var _text_justify = { "none":1,"auto":1,"inter-word":1, "inter-character":1, "distribute":1,   
};
var _text_overflow = { "clip":1,"ellipsis":1,   
};
var _text_transform = { "none":1,"capitalize":1, "uppercase":1,"lowercase":1, "full-width":1,"full-size-kana":1,   
};
var _transform = { "none":1,"matrix(":1, "matrix3d(":1,"perspective(":1, 
"rotate(":1,"rotate3d(":1, "rotateX(":1,"rotateY(":1, "rotateZ(":1,
"translate(":1, "translate3d(":1,"translateX(":1, "translateY(":1,"translateZ(":1, 
"scale(":1,"scale3d(":1, "scaleX(":1,"scaleY(":1, "scaleZ(":1,
"skew(":1, "skewX(":1,"skewY(":1,      
};
var _vertical_align = { "baseline":1,"sub":1,"super":1, "text-top":1, "text-bottom":1, "middle":1,"top":1,"bottom":1,  
};
var _transform_style = { "flat":1,"preserve-3d":1,   
};
var _visibility = { "visible":1,"hidden":1,"collapse":1,   
};
var _white_space = { "normal":1,"nowrap":1,"pre":1, "pre-wrap":1,"pre-line":1,"break-spaces":1,      
};
var _word_break = { "normal":1,"break-all":1,"pre":1, "keep-all":1,"break-word":1,      
};
var _word_spacing = { "normal":1,     
};
var _word_wrap = { "normal":1, "break-word":1, "anywhere":1,     
};
var _object_fit = { "contain":1, "cover":1, "fill":1, "none":1, "scale-down":1,      
};
var _object_position = { "left":1, "top":1, "right":1, "bottom":1,     
};
var _clip_path = { "none":1, "url(":1, "margin-box":1, "border-box":1, "padding-box":1,  "content-box":1, "fill-box":1, "stroke-box":1, "view-box":1, "inset(":1, "circle(":1, "ellipse(":1, "polygon":1, "path(":1,     
};


/**
 * 
  
 
  
 "word-wrap: ":1, "z-index: ":1, 
 */



  CodeMirror.registerHelper("hint", "css", function(cm) {
    
    var cur = cm.getCursor(), token = cm.getTokenAt(cur);
    var inner = CodeMirror.innerMode(cm.getMode(), token.state);
    var tokenLine = cm.getLineTokens(cur.line);
    if (inner.mode.name != "css") return;

    if (token.type == "keyword" && "!important".indexOf(token.string) == 0)
      return {list: ["!important"], from: CodeMirror.Pos(cur.line, token.start),
              to: CodeMirror.Pos(cur.line, token.end)};

    var start = token.start, end = cur.ch, word = token.string.slice(0, end - start);
    if (/[^\w$_-]/.test(word)) {
      word = ""; start = end = cur.ch;
    }

    var spec = CodeMirror.resolveMode("text/css");

    var result = [];
    function add(keywords) {
      for (var name in keywords)
        if (!word || name.lastIndexOf(word, 0) == 0)
          result.push(name);
    }
    function addVars(keywords) {
     for (var name in keywords)
         result.push(name);
   }
    var st = inner.state.state;
    /*
    console.log(token.type);
    console.log(token);
    console.log(tokenLine);
    console.log(st);
    console.log(cm.options.recodaInvalidCSS);
    */
    if (st == "pseudo" || token.type == "variable-3") {
          if(cm.options.recodaFlag != undefined){
               add(pseudoClasses);
          } else{
               smartSuggestion("tag"); 
          }
      
      
    }
   /* Custom variables */
    else if (st == "prop" && token.type == "variable-2"){
     if(cm.options.recodaFlag == "NO_suggestion")  return; 
     recoda.updateStylesheetsCSSVars(); 
     let p = recoda.stylsheetsCSSVars;
          let str = token.string;
          //str = str.substring(1);
          let matchedProps={};

          p.forEach( key => {
               var key_ = `var(--${key})`;
               if(key_.includes(str)){ matchedProps[key_] = 1; }
               
          });
          addVars(matchedProps);
    } else if (st == "maybeprop" && token.type == "property error"){
     if(cm.options.recodaFlag == "NO_suggestion")  return;    
      add(recodaCodeSenseProps);
    } else if (st == "top" && token.type == "tag"){
         if(cm.options.recodaFlag != undefined){
          add(recodaCodeSenseTags);
         }
         else{
          add(recodaCodeSenseProps);  
         }
     
   } 
     /* Oxygen class suggestion*/
    else if (st == "top" && token.type == "qualifier"){
          if(cm.options.recodaFlag != undefined){
               let p = $scope.iframeScope.classes;
               let str = token.string;
               str = str.substring(1);
               let pushArr={};

               for (var key in p) {
                    if (p.hasOwnProperty(key)) {
                    var key_ = "." + key;
                    if(key_.includes(str)){ pushArr[key_] = 1; }
                    
                    }
               }
               add(pushArr); 
          }    
     } else if (st == "prop" && token.type == "variable"){
          smartSuggestion("property");
     } else if (st == "top" && token.type == "tag"){
          if(cm.options.recodaFlag == undefined){
               add(recodaCodeSenseProps);  
          }
     } else if (st == "top" && token.type == "variable-3"){
          if(cm.options.recodaFlag == undefined){
               smartSuggestion("tag"); 
          }
     }
     else if (st == "top" && token.type == "variable-2"){
          if(cm.options.recodaFlag == undefined){
               let p = recoda.stylsheetsCSSVars;
               let str = token.string;
               //str = str.substring(1);
               let matchedProps={};
     
               p.forEach( key => {
                    var key_ = `var(--${key})`;
                    if(key_.includes(str)){ matchedProps[key_] = 1; }
                    
               });
               addVars(matchedProps);
          }
     }

     function smartSuggestion(anchor) {  
      if(cm.options.recodaFlag == "NO_suggestion")  return;  
      var boolFound = true;
      var tStart = token.start;
      var tokenIndex = tokenLine.findIndex(x => x.start === tStart);
      var CodeSenseProp;
      // prop need to be on lower index trhan current and needs one more index down because of TOKEN{ : }  
      // find maybeprop
      while(boolFound){
           //error: 
           //--var: sadk
           // left side is undefined
        if(tokenLine[tokenIndex] == undefined) return;
        if(tokenLine[tokenIndex].type == anchor){
          boolFound = false;
          CodeSenseProp = tokenLine[tokenIndex].string;
        }
        tokenIndex--;
      }
      switch (CodeSenseProp) {
        case "align-content":
             add(_align_content);
             break;
        case "align-items":    
             add(_align_items); 
             break;
        case "align-self":  
             add(_align_self);    
             break;
        case "animation-direction":
              add(_animation_direction);
              break;
        case "animation-fill-mode":    
              add(_animation_fill_mode); 
              break;
        case "animation-iteration-count":  
              add(_animation_iteration_count);    
              break;           
        case "animation-play-state":
             add(_animation_play_state);
             break;
        case "animation-timing-function": case"transition-timing-function":   
             add(_animation_timing_function); 
             break;
        case "background":case "background-color":case "border-color":case "border-top-color":case "border-left-color":case "border-right-color":case "border-bottom-color":case "color":case "text-decoration-color":       
             add(_global);  
             add(_css_colors);  
             break;
        case "background-attachment":
             add(_background_attachment);
             break;
        case "background-blend-mode":    
             add(_background_blend_mode); 
             break;
        case "background-clip":  
             add(_background_clip);    
             break;
        case "background-image":
             add(_background_image);
             break;
        case "background-origin":    
             add(_background_origin); 
             break;
        case "background-position":  
             add(_background_position);    
             break;
        case "background-repeat":
             add(_background_repeat);
             break;
        case "background-size":    
             add(_background_size); 
             break;
        case "border-style":case "border-left-style":case "border-top-style": case "border-right-style":case "border-bottom-style":case "outline-style":    
             add(_border_style);    
             break;
        case "border-image-repeat":
             add(_border_image_repeat);
             break;
        case "border-image-slice":    
             add(_border_image_slice); 
             break;
        case "border-image-source":  
             add(_border_image_source);    
             break;
       case "border-image-collapse":  
             add(_border_image_collapse);    
             break;
        case "box-sizing":
             add( _box_sizing);
             break;
        case "clip":    
             add(_clip); 
             break;
        case "clip-path":    
             add(_clip_path);
             add(_global_values); 
             break;
        case "clear":  
             add(_clear);    
             break;
        case "content":
             add(_content);
             break;
        case "columns":    
             add(_columns); 
             break;
        case "counter-increment":case "counter-reset":  
             add(_counter_increment__reset);    
             break;
        case "cursor":
             add(_cursor);
             break;
        case "direction":    
             add(_direction); 
             break;
        case "display":  
             add(_display);    
             break;
        case "empty-cells":
             add(_empty_cells);
             break;
        case "filter":    
             add(_filter); 
             break;
        case "flex-direction":  
             add(_flex_direction);    
             break;
       case "flex-wrap":  
             add(_flex_wrap);    
             break;
        case "float":
             add(_float);
             break;
        case "font-kerning":    
             add(_font_kerning); 
             break;
        case "grid-area":  
             add(_grid_area);    
             break;
        case "height":case "min-height":case "width":case "min-width":
             add(_auto);
             add(_height);
             break;
        case "max-height":case "max-width":
             add(_height);
             break;
        case "justify-content":    
             add(_justify_content); 
             break;
        case "justify-items":  
             add(_justify_items);    
             break;
        case "justify-self":
             add(_justify_self);
             break;
        case "left":case "right": case "top": case "bottom":     
             add(_auto_global); 
             break;
        case "list-style":
             add(_list_style_type);
             add(_list_style_image);
             add(_list_style_position); 
             add(_global_values);     
             break;
        case "list-style-type":
             add(_list_style_type); 
             add(_global_values);  
             break;
        case "list-style-image":    
             add(_list_style_image);
             add(_global_values);  
             break;
        case "list-style-position":  
             add(_list_style_position);
             add(_global_values);    
             break;
       case "object-fit":  
             add(_object_fit);
             add(_global_values);    
             break;
       case "object-position":  
             add(_object_position);
             add(_global_values);    
             break;     
        case "outline":case "outline-color":   
             add(_css_colors);   
             break;
        case "outline-width":  
             add(_outline_width);
             add(_global_values);    
             break;
        case "overflow":case "overflow-x":case "overflow-y":  
             add(_overflow);
             add(_global_values);     
             break;
        case "perspective":  
             add(_global_values);
             add(_none);    
             break;
        case "perspective-origin":  
             add(_l_t_r_b);
             add(_global_values);     
             break;
        case "position":  
             add(_position);
             add(_global_values);     
             break;
        case "resize":  
             add(_resize);
             add(_global_values);     
             break;
        case "table-layout":  
             add(_table_layout);
             add(_global_values);    
             break;
        case "text-align":  
             add(_text_align);
             add(_global_values);     
             break;
        case "text-align-last":  
             add(_text_align_last);
             add(_global_values);    
             break;
        case "text-decoration":
             add(_text_decoration_line);
             add(_text_decoration_style);    
             add(_global_values);
             add(_css_colors);    
             break;
        case "text-decoration-line":  
             add(_text_decoration_line);
             add(_global_values);    
             break;
        case "text-decoration-style":  
             add(_text_decoration_style);
             add(_global_values);      
             break;
        case "text-justify":
             add(_text_justify);  
             add(_global_values);     
             break;
        case "text-overflow":
             add(_text_overflow);  
             add(_global_values);     
             break;
        case "text-transform":  
             add(_global_values);
             add(_text_transform);     
             break;
        case "transform": 
             add(_transform); 
             add(_global_values);     
             break;
        case "transform-origin":
             add(_l_t_r_b);  
             add(_global_values);     
             break;
        case "transform-style": 
             add(_transform_style);  
             add(_global_values);     
             break;
        case "transition":  
             add(_transitionProps);
             add(_animation_timing_function);
             add(_global_values);     
             break;
        case "transition-property":
             add(_transitionProps);  
             add(_global_values);     
             break;
        case "vertical-align":
             add(_vertical_align);  
             add(_global_values);     
             break;
        case "visibility":  
             add(_visibility);  
             add(_global_values);      
             break;
        case "white-space":  
             add(_white_space);  
             add(_global_values);      
             break;
        case "word-break":  
             add(_word_break);  
             add(_global_values);      
             break;
        case "word-spacing":  
             add(_word_spacing);  
             add(_global_values);      
             break;
        case "word-wrap":  
             add(_word_wrap);  
             add(_global_values);     
             break;
        case "z-index":  
             add(_auto_global);     
             break;

        default:
             add(_global_values);    
             break;
      }
    }  
    /*
    else if (st == "prop" || st == "parens" || st == "at" || st == "params") {
      add(spec.valueKeywords);
      add(spec.colorKeywords);
      
    } else if (st == "media" || st == "media_parens") {
      add(spec.mediaTypes);
      add(spec.mediaFeatures);
     
    }
    
*/
    if (result.length) return {
      list: result,
      from: CodeMirror.Pos(cur.line, token.start),
      to: CodeMirror.Pos(cur.line, token.end)
    };
  });
});
