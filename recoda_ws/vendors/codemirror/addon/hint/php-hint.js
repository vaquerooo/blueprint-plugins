// Customized CSS CodeSense by Renato Corluka

  CodeMirror.registerHelper("hint", "html", function(cm) {
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

    var cur = cm.getCursor(), token = cm.getTokenAt(cur);
    var inner = CodeMirror.innerMode(cm.getMode(), token.state);
    var tokenLine = cm.getLineTokens(cur.line);
    if (inner.mode.name != "xml") return;

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
    var st = inner.state.state;
    /*
    console.log(token.type);
    console.log(token);
   
    console.log(st);
    console.log(cm.options.recodaInvalidCSS);
    */
    console.log(tokenLine);
    if (token.type == "tag") {
     add(recodaCodeSenseTags);
      
      
    } 
    //class suggestion
    //else if (token.type == "string"){
    else if (token.type == "Look above"){
         
          var tStart = token.start;
          var tokenIndex = tokenLine.findIndex(x => x.start === tStart);
          //console.log(tokenLine[tokenIndex - 2].type);
          if(tokenLine[tokenIndex - 2].type == undefined) {return; }   

          else {
               var boolFound = true;
               while(boolFound && boolFound != "exit"){
                    var n =0;
                    console.log(`Searching step ${n}:`, tokenIndex);
                    //if(tokenLine[tokenIndex].type == undefined) {return console.log('%c RETURNED! ', 'background: #222; color: #bada55'); }  
                    console.log(tokenLine[tokenIndex].type +" - "+tokenLine[tokenIndex].string );
                    if(tokenLine[tokenIndex].type == "attribute" && tokenLine[tokenIndex].string == "class"){
                         console.log('%c FOUND! ', 'background: #222; color: #bada55');
                      boolFound = false;
                      CodeSenseProp = tokenLine[tokenIndex].string;
                    }
                    if(tokenIndex > 1){
                         tokenIndex--;
                         n++;
                    } 
                    else if(n > 20 || tokenIndex == 0) boolFound="exit";
                  }
     
               if(boolFound == false){
                    console.log("suggestion");
                    var p = $scope.iframeScope.classes;
                    var str = token.string;
                    str = str.substring(1);
                    var pushArr={};
               
                    for (var key_ in p) {
                         if (p.hasOwnProperty(key_)) {
                              if(key_.includes(str)){ pushArr[key_] = 1; }
                         }
                    }
                    add(pushArr); 
                  }
               
          }

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
               var p = rewsBuilder.iframeScope.classes;
               var str = token.string;
               str = str.substring(1);
               var pushArr={};

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

     function smartSuggestion(anchor) {  
      if(cm.options.recodaFlag == "NO_suggestion")  return;  
      var boolFound = true;
      var tStart = token.start;
      var tokenIndex = tokenLine.findIndex(x => x.start === tStart);
      var CodeSenseProp;
      // prop need to be on lower index trhan current and needs one more index down because of TOKEN{ : }  
      // find maybeprop
      while(boolFound){
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

