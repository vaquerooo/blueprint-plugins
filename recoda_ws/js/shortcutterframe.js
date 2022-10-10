document.addEventListener("wsLoad", () => {
	"use strict";
	jQuery(document.getElementById("ct-artificial-viewport").contentWindow.document).on( "keydown", function (event) {
		if (angular.element("#ct-controller-ui").scope().isActiveActionTab("contentEditing")) {
			return;
		}

		if (event.keyCode == 71) {
			event.preventDefault();
			recoda.cmdCodeMirror.display.input.focus();
		}
	});
	
	jQuery("body").on('click', document.getElementById("ct-artificial-viewport").contentWindow.document, function() {

		setTimeout((() => {
			let el = document.querySelector('div.dom-tree-node.active');
			if(el){
				el.scrollIntoView({behavior: "smooth", block: "center", inline: "nearest"});
			}
		}), 50);	
	});
		
});
