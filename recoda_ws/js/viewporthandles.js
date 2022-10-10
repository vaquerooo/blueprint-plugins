document.addEventListener("wsLoad", () => {
	"use strict";
     /* for: earlier than 3.9 Oxygen  */
     document.querySelector("input#tbf-mrm").addEventListener('change', function() {
      function handler() {
          if(!document.getElementById('tbf-mrm').checked) {return; } 
          recoda.executeCommand(recoda.lastCommand);
        }
      let elm_old=document.querySelectorAll('#ct-dom-tree .ct-dom-tree-node-anchor');
      let elm_new=document.querySelectorAll('#ct-dom-tree-2 .dom-tree-node-label');
      

      if (this.checked) {
          //elm_old.addEventListener("click", handler);
          for (i = 0; i < elm_old.length; i++) {
              elm_old[i].addEventListener("click", handler);
          }
          for (i = 0; i < elm_new.length; i++) {
              elm_new[i].addEventListener("click", handler);
          }
          
      }
      else{
          for (i = 0; i < elm_old.length; i++) {
              elm_old[i].removeEventListener("click", handler);
          }
          for (i = 0; i < elm_new.length; i++) {
              elm_new[i].removeEventListener("click", handler);
          }
          
      }


    });
    
    let tbf_viewport_handles='<div id="tbf-handle-256" class="tbf-viewport-handle" style="left:253px" title="256px: Nintendo Entertainment System (NES)"></div><div id="tbf-handle-320" class="tbf-viewport-handle" style="left:317px" title="320px: iPhone SE/ 5s/ earlier | iPod Touch"></div><div id="tbf-handle-360" class="tbf-viewport-handle" style="left:357px" title="360px: iPhone 12mini | Galaxy S9/ S9+/ S8/ S8+/ S7+/ S7 Edge | Xperia XZ2 | Huawei P20 Lite/ P20 Pro/ P10/ P10 lite | Google Pixel"></div><div id="tbf-handle-375" class="tbf-viewport-handle" style="left:372px" title="375px: iPhone 11 Pro/ XS / X/ 8/ 7 / 6/ 6s"></div><div id="tbf-handle-390" class="tbf-viewport-handle active" style="left:387px" title="390px: iPhone 12/ 12 Pro"></div><div id="tbf-handle-393" class="tbf-viewport-handle" style="left:390px" title="393px: Pixel 3 XL/ 3/ 2 XL/ 2/ XL"></div><div id="tbf-handle-414" class="tbf-viewport-handle" style="left:411px" title="414px: iPhone 11 Pro Max/ 11/ XS Max/ XR/ 8 Plus/ 7 Plus/ 6s Plus/ 6"></div><div id="tbf-handle-428" class="tbf-viewport-handle" style="left:425px" title="428px: iPhone 12 Pro Max"></div><div id="tbf-handle-480" class="tbf-viewport-handle" style="left:477px" title="480px: Kindle Fire HD 7 | Galaxy Note 5 | LG G5/ G6 | One Plus 3"></div><div id="tbf-handle-600" class="tbf-viewport-handle" style="left:597px" title="600px: Galaxy Tab 2 (7inch) | Kindle Fire (non-HD)"></div><div id="tbf-handle-720" class="tbf-viewport-handle" style="left:717px" title="720px: Surface Pro 2/ Pro"></div><div id="tbf-handle-768" class="tbf-viewport-handle" style="left:765px" title="768px: iPad Pro (9.7inch)/ 2017/ 2018/ Air/ Air 2/ 4th Gen and earlier/ mini | Microsoft Surface"></div><div id="tbf-handle-800" class="tbf-viewport-handle" style="left:797px" title="800px: Galaxy Note 10.1 / Tab(10inch) 2/ 3/  Tab (8.9inch) | Kindle Fire HD"></div><div id="tbf-handle-834" class="tbf-viewport-handle" style="left:831px" title="834px: iPad Pro (10.5inch)"></div><div id="tbf-handle-912" class="tbf-viewport-handle" style="left:909px" title="912px: Surface Pro 2017 (Portrait)"></div>';
    jQuery(tbf_viewport_handles).prependTo("#ct-viewport-ruller-wrap");
    
    const oxyRuller = document.getElementById("ct-viewport-ruller-wrap");
    oxyRuller.addEventListener('click', (e) => {
      if (e.target.className === 'tbf-viewport-handle') {
        const canvas = document.getElementById("ct-artificial-viewport");
        let styleLeft =parseInt(e.target.style.left,10);
        let leftVal = styleLeft+3;
        let leftValStr = leftVal + "px";
        canvas.style.width = leftValStr;
      }
    });
    
    jQuery(document.getElementById('ct-artificial-viewport').contentWindow.document).on( "keydown",function(event){
      if (event.altKey ) { 
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

      if((event.shiftKey && event.metaKey) || (event.shiftKey && event.ctrlKey)) {
          if(event.keyCode == 37){
            event.preventDefault(); 
            let current = jQuery(".tbf-viewport-handle.active");
            let prev = jQuery(".tbf-viewport-handle.active").prev(".tbf-viewport-handle");
            let title;
            if(prev.length > 0){
              current.removeClass("active");
              prev.addClass("active");
              prev.click();
              title=prev.attr('title');
              
            }
          
          }

        //ARROW R
          if(event.keyCode == 39){
            event.preventDefault(); 
            let current = jQuery(".tbf-viewport-handle.active");
            let next = jQuery(".tbf-viewport-handle.active").next(".tbf-viewport-handle");
            let title; 
            if(next.length > 0){
              current.removeClass("active");
              next.addClass("active");
              next.click();
              title=next.attr('title');
            }   
          }

      }
         
  });
  
  jQuery(window).on( "keydown",function(event){  
    
    if (event.altKey ) { 
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

      if((event.shiftKey && event.metaKey) || (event.shiftKey && event.ctrlKey)) { 
          if(event.keyCode == 37){
            event.preventDefault(); 
            let current = jQuery(".tbf-viewport-handle.active");
            let prev = jQuery(".tbf-viewport-handle.active").prev(".tbf-viewport-handle");
            let title;
            if(prev.length > 0){
              current.removeClass("active");
              prev.addClass("active");
              prev.click();
              title=prev.attr('title');
              
            }
          
          }

        //ARROW R
          if(event.keyCode == 39){
            event.preventDefault(); 
            let current = jQuery(".tbf-viewport-handle.active");
            let next = jQuery(".tbf-viewport-handle.active").next(".tbf-viewport-handle");
            let title; 
            if(next.length > 0){
              current.removeClass("active");
              next.addClass("active");
              next.click();
              title=next.attr('title');
            }   
          }

      }
     
  
  });

});
