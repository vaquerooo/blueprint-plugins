document.addEventListener("wsLoad", () => {
    jQuery( ".oxygen-media-query-and-selector-wrapper" ).on("dblclick",function() {
        const Oxy = $scope.iframeScope,
        {id, name} = $scope.iframeScope.component.active;
        var val = Oxy.currentClass;
        if (!val)   val = Oxy.component.options[id].selector;
        recoda.textToClipboard(val); 
        recoda.toast("copied: " + val)
    });  

    jQuery(document).on('dblclick','.ct-css-node-header.ct-node-options-active.ct-css-node-stylesheet', function() {
        $scope.doShowLeftSidebar(true);
        recoda.setPropertyPX("--sidebar-width",450);
    });
    jQuery(document).on('dblclick','.ct-dom-tree-node-anchor.ct-dom-tree-node-type-general.ct-dom-tree-name', function() {
        var def_w = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--def-sidebar-width"),10);
        $scope.doShowLeftSidebar(true);
        recoda.setPropertyPX("--sidebar-width",def_w);
    });


    jQuery(document.getElementById("ct-artificial-viewport").contentWindow.document).on('click', function(event){
        if(event.shiftKey){
            recoda.executeCommand(recoda.lastCommand); 
        }
    });
    jQuery(document.getElementById("ct-artificial-viewport").contentWindow.document).on('click', function(event){
        if(event.altKey){
            recoda.executeCommand(recoda.lastCommand); 
        }
    });
    jQuery(".oxygen-media-query-and-selector-wrapper").on('click', function(event){
        if(event.shiftKey){
            recoda.addSelectorToCLI();
        }
    });
    jQuery(document).on('click',".custom-attributes.ng-scope", function(event){
        if(event.shiftKey){
            recoda.addAttributeToCLI();
        }
    });

    function autocompleteMatch(input) {
      let items = recoda.stylsheetsCSSVars;
      if (input == '') {
        return [];
      }
      var reg = new RegExp(input)
      return items.filter(function(term) {
          if (term.match(reg)) {
            return term;
          }
      });
    }
   
     
    recoda.inputHints = function(target, changeUnit, e) {
        const Oxy = $scope.iframeScope;
        var val = target.value;
        var currentFocus = -1;
        var element = document.getElementById("result-ws");
        if ( element == null){
            recoda.updateStylesheetsCSSVars();
            let a = document.createElement("DIV");
            a.setAttribute("id", "result-ws");
            a.setAttribute("class", "autocomplete-items");
            target.parentNode.appendChild(a);
            updateSuggestion();
            target.addEventListener("keyup", handleInputKeyup );
            target.addEventListener("blur", closeHints );
            let res = document.getElementById("result-ws");
            res.addEventListener("mousedown", handleClick);
        } 
        function updateSuggestion(){
            if(!document.body.classList.contains("ws-mbha")) document.body.classList.add("ws-mbha");
            let res = document.getElementById("result-ws");
            res.innerHTML = '';
            let list = '';
            let terms = autocompleteMatch(val);
            for (i=0; i<terms.length; i++) {
                list += '<li class="ws-variable-hint">' + terms[i] + '</li>';
            }
            res.innerHTML = '<ul>' + list + '</ul>';
        }
          
        function handleClick(e){
            var hintItem = e.target;
            if (hintItem.classList.contains("ws-variable-hint")){
                let variable = hintItem.innerHTML,
                    template = `var(--${variable})`,
                    dataOption = target.getAttribute('data-option');

                target.value = template;
                if(changeUnit) Oxy.setOptionUnit( dataOption, ' ');
                target.dispatchEvent(new Event("input")); 
            }
        }

        function handleInputKeyup(e) {
            var x =[], y = document.getElementById("result-ws");
            if (y) x = y.getElementsByTagName("li");
            val = target.value;

            //if user type '(' he wants CSS functions, not vars
            let cssFunctions = ["calc(", "min(", "max("];
            let doesContainCssFunction = cssFunctions.some(cssFunction => val.toString().includes(cssFunction));
            if(doesContainCssFunction) return;

            if (e.keyCode == 40) {
              /*If the arrow DOWN key is pressed,
              increase the currentFocus variable:*/
              currentFocus++;
              /*and and make the current item more visible:*/
              addActive(x);
            } else if (e.keyCode == 38) { //up
              /*If the arrow UP key is pressed,
              decrease the currentFocus variable:*/
              currentFocus--;
              /*and and make the current item more visible:*/
              addActive(x);
            } else if (e.keyCode == 13) {
              /*If the ENTER key is pressed, prevent the form from being submitted,*/
              e.preventDefault();
              if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (!x) return;
                let variable = x[currentFocus].textContent,
                    template = `var(--${variable})`,
                    dataOption =target.getAttribute('data-option');
                target.value = template;
                // if it measure box, set unit to none
                if(changeUnit) Oxy.setOptionUnit( dataOption, ' ');
                target.dispatchEvent(new Event("input")); 
               // closeHints();

              }
            }else updateSuggestion();
        
            /* Print new suggestions */
            /*append the DIV element as a child of the autocomplete container:*/
            
            
        }
        function closeHints(e){
            /* Clean up DOM*/
            const hintContainer = document.getElementById("result-ws");
            hintContainer.remove();
            /* Clean up event listners */
            hintContainer.removeEventListener("click", handleClick);
            target.removeEventListener("keyup", handleInputKeyup);
            target.removeEventListener("blur", closeHints);
            if(document.body.classList.contains("ws-mbha")) document.body.classList.remove("ws-mbha");
        }
        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
              x[i].classList.remove("autocomplete-active");
            }
          }
        function addActive(x) {
            
            /*a function to classify an item as "active":*/
            if (x.length < 1) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            var activeEl =x[currentFocus]
            activeEl.classList.add("autocomplete-active");
            if (!isElementVisible(activeEl)) activeEl.scrollIntoView();;

        }
        function isElementVisible(el) {
            var rect     = el.getBoundingClientRect(),
                vWidth   = window.innerWidth || doc.documentElement.clientWidth,
                vHeight  = window.innerHeight || doc.documentElement.clientHeight,
                efp      = function (x, y) { return document.elementFromPoint(x, y) };     
        
            // Return false if it's not in the viewport
            if (rect.right < 0 || rect.bottom < 0 
                    || rect.left > vWidth || rect.top > vHeight)
                return false;
        
            // Return true if any of its four corners are visible
            return (
                  el.contains(efp(rect.left,  rect.top))
              ||  el.contains(efp(rect.right, rect.top))
              ||  el.contains(efp(rect.right, rect.bottom))
              ||  el.contains(efp(rect.left,  rect.bottom))
            );
        }
    
    }
    
});