
document.addEventListener("wsInit", () => {
	"use strict";
/*Start: Jquery safe call wrap, wait document to execute code*/ 
/*
?? HELPER FUNCTIONS 
*/
	function isJSON(str) {
		try {
			return (JSON.parse(str) && !!str);
		} catch (e) {
			return false;
		}
	}
	function numToString(num){
		num = num.replaceAll(/0/g, "a");
		num = num.replaceAll(/1/g, "b");
		num = num.replaceAll(/2/g, "c");
		num = num.replaceAll(/3/g, "d");
		num = num.replaceAll(/4/g, "e");
		num = num.replaceAll(/5/g, "f");
		num = num.replaceAll(/6/g, "g");
		num = num.replaceAll(/7/g, "h");
		num = num.replaceAll(/8/g, "i");
		num = num.replaceAll(/9/g, "j");

		return num;	
	}

	function loadCustomProfileFromDB(){
		var custom = document.querySelector('.ws-profile-custom');
		var def = document.querySelector('.ws-profile-default');
		var db = rewsLocalVars.userPreference;
		var id = numToString(rewsLocalVars.userId);
		var active = document.querySelector('.ws-profile.active');
		var flag = true;		

		if(db.hasOwnProperty(id) ){
			flag = false;
			active.classList.remove('active');
			window.localStorage.setItem('recoda-settings', JSON.stringify(db[id]));
			custom.classList.add('active');
		}else custom.classList.add('ws-profile-empty');	

		if(db.hasOwnProperty('default')) 	{
			if(flag) window.localStorage.setItem('recoda-settings', JSON.stringify(db['default']));
		}else def.classList.add('ws-profile-empty');	
		
		if(!db.hasOwnProperty('default') && !db.hasOwnProperty(id)) return recoda.toast('No user profile found in Database!');

		recodaLoadCurrentUserPref(document.querySelectorAll(".recoda-local-store-pull"));
	}
	function changeSelectedUserProfile(){
		document.getElementById('ws-user-profile').addEventListener('click', (e)=>{
			var t = e.target;

			if(!t.classList.contains('ws-profile')) return;
			if(t.classList.contains('active')) return;

			document.querySelector('.ws-profile.active').classList.remove('active');
			t.classList.add('active');
			switchLocalStorage();
		});	
	}
	function switchLocalStorage(){
		var db = rewsLocalVars.userPreference;
		var id = numToString(rewsLocalVars.userId);
		var o;
		if(document.querySelector('.ws-profile.active').innerHTML.toString().toLocaleLowerCase() == 'default') o = 'default';
		else o = 'custom';
		
		if(db.hasOwnProperty(id) && o == 'custom') 				{window.localStorage.setItem('recoda-settings', JSON.stringify(db[id]))}	
		else if(db.hasOwnProperty('default') && o == 'default' ) 	{window.localStorage.setItem('recoda-settings', JSON.stringify(db[o]))}
		else return;
		recoda.toast('Reload required!');
		//load settings in APP, this won't change view, this changes variables, body classes etc.
		recodaGetLocalStorage();
		//change view part
		recodaLoadCurrentUserPref(document.querySelectorAll(".recoda-local-store-pull"));
		
		}

	function initSettingsToDB(){
		document.getElementById("ws-save-profile").addEventListener('click', (e)=>{
			var ls = JSON.parse(window.localStorage.getItem('recoda-settings'));
			var db = rewsLocalVars.userPreference;
			var id = numToString(rewsLocalVars.userId);
			var o;
			if(document.querySelector('.ws-profile.active').innerHTML.toString().toLocaleLowerCase() == 'default') o = 'default';
			else o = 'custom';
			if(o == 'custom') 			 db[id] = ls; 
			else if( o == 'default' ) 	 db[o] = ls;
			else return;
			saveToDB(db);
		});
	}
	function saveToDB(data){
		var send = {
			action: 'recoda_workspace_action_save_user_preference',
			user_pref_load: data
		};
		jQuery.post(ajaxurl, send)
			.done(function() {
			recoda.toast("User preference saved to the database")
		});
	}


	function Tabs() {
		let bindAll = function() {
		let menuElements = document.querySelectorAll('.ws-settings-tag');
		for(var i = 0; i < menuElements.length ; i++) {
			menuElements[i].addEventListener('click', change, false);
		}
		}
	
		let clear = function() {
		let menuElements = document.querySelectorAll('.ws-settings-tag.active');
		for(var i = 0; i < menuElements.length ; i++) {
			menuElements[i].classList.remove('active');
			let filter = menuElements[i].getAttribute('data-filter');
			let tag = `.recoda-option-row[data-tag=`+ filter +`]`;
			let filteredItems = document.querySelectorAll(tag);
			for(var i = 0; i < filteredItems.length ; i++) {
				filteredItems[i].classList.remove('active');
			}
		}
		}
	
		let change = function(e) {
			clear();

			e.target.classList.add('active');
			let filter = e.currentTarget.getAttribute('data-filter');
			let tag = `.recoda-option-row[data-tag=`+ filter +`]`;
			let filteredItems = document.querySelectorAll(tag);

			for(var i = 0; i < filteredItems.length ; i++) {
				filteredItems[i].classList.add('active');
				}
			document.getElementById("ws-user-pref-tag-controlls").setAttribute("data-filtering", "on");
		}
  		bindAll();
  	}
/**--END OF HELPER FUNCTIONS */

/*
?? GLOBAL FUNCTIONS
*/
	recoda.localSaveUserPref = function(){
		let li = document.querySelectorAll(".recoda-local-store-pull");
		recodaSetLocalStorage(li);
	}

	recoda.exportUserPref = function(){
		if (localStorage.getItem("recoda-settings") !== null)  {
			let localStore = window.localStorage.getItem('recoda-settings');
			let copyTextarea = document.querySelector('.rews-up-js-copytextarea');
			copyTextarea.value = localStore;
			copyTextarea.focus();
			copyTextarea.select();
		
			try {
				let successful = document.execCommand('copy');
				let msg = successful ? 'successful' : 'unsuccessful';
				recoda.toast('User preference export went ' + msg);
			} catch (err) {
				recoda.toast('Oops, unable to copy');
			}

		}
		else alert("There is no User prefs in Local Storage");
	}

	recoda.importUserPref = function(data){
		setTimeout(function(){ 
			let t = document.querySelector(".rews-up-js-copytextarea").value;
			let pasteText = isJSON(t);
			if (pasteText == false) { 
				document.querySelector(".rews-up-js-copytextarea").value="NOTICE: NOT VALID INPUT";
				alert("Not valid JSON format, JSON test faild. Please check settings!");
				document.querySelector(".rews-up-js-copytextarea").value=""; 
				return;}
			else{
				let txt = JSON.parse(t);
				if (txt["recoda"] === "active"){
					recoda.toast("Recoda Workspace: Successfully imported your User Preferences!");
					window.localStorage.setItem('recoda-settings', JSON.stringify(txt));
					recoda.showImportWizard();
					recoda.toast("Successfully imported your User Preferences!");

				}
				else { alert("Please import valid User Preferences!"); } 
			}
		
		}, 100);	
		
	}

/*Helper common options*/
	const onOff = ['ON','OFF'];
	const offOn = ['OFF','ON'];
	const userPrefsObj = {
	"General" : [
		{	"select":{
			"label": "Theme",
			"options":['Respect API', 'Recoda Sleek','Recoda 4 2W', 'Recoda Designer', 'Recoda Developer','One Dark 3W','One Dark 2W', 'Dracula 3W', 'Dracula 2W', 'Classic Light', 'Classic Darker', 'Vodka Legacy',  'Custom'],
			"storekey": "ws-theme",
			"special": "",
			"docs": "https://docs.recoda.me/getting-started/user-preference#theme-preference",
			}		
		},
		{	"select":{
				"label": "Keyboard Layout:",
				"options":['QWERTY','QWERTZ','AZERTY', 'COLEMAK DH', 'DVORAK'],
				"storekey": "ws-kbLayout",
				"special": "",
				"docs": "",
			}	
		},
		{	"select":{
			"label": "Auto Zoom:",
			"options": offOn,
			"storekey": "_x_ws-auto-zoom",
			"special": "",
			"docs": "https://docs.recoda.me/getting-started/user-preference#auto-zoom",
			}
		},
		
	],
	"Interface" : [
		{	"select":{
				"label": "Structure Panel Style:",
				"options":['Classic','Sleek','Turn OFF'],
				"storekey": "ws-dom",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#structure-panel-style",
				}
					
		},
		{	"select":{
				"label": "Panelator position:",
				"options":['Topbar','Sidepanel'],
				"storekey": "ws-panelator-position",
				"special": "ws-req-reload",
				"docs": "https://docs.recoda.me/getting-started/user-preference#panelator-position",
				}
			
		},
		{	"select":{
			"label": "Add Elements Layout:",
			"options":['Compact', 'Classic'],
			"storekey": "ws-addelement",
			"special": "",
			"docs": "",
			}
				
		},
		{	"select":{
				"label": "Install UX Pack:",
				"options": ['Houdini','None'],
				"storekey": "data-wspack-install",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#panel-ui-pack",
				}
		},
		{	"select":{
				"label": "Canvas scroll style:",
				"options": ['Normal','Tiny'],
				"storekey": "ws-canvas-scroll-style",
				"special": "ws-req-reload",
				"docs": "https://docs.recoda.me/getting-started/user-preference#canvas-scroll-style",
				}		
		},
		{	"rangeSlider":{
				"label": "Panel Resizer Handle Width:",
				"min": "5",
				"max": "25",
				"step": "1",
				"storekey": "--resizer-handle-width",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#resizer-handle-width",
			}
		},	
		{	"select":{
				"label": "Panel Docking:",
				"options":['OFF','Left','Left-swap',"Swap"],
				"storekey": "ws-docking",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#panel-docking",
			}		
		},
		{	"select":{
				"label": "Magnetic trigger width:",
				"options":['250','200','150', '100', '50'],
				"storekey": "--magnetic-trigger",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#magnetic-trigger",
			}		
		},
		{	"select":{
				"label": "Margin/Padding controls style:",
				"options":['Classic', 'Column', 'Default'],
				"storekey": "ws-mpc",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#margin-padding-control-style",
			}		
		},
	],
	"QuickFlow" : [
		
		{	"select":{
				"label": "Class switcher",
				"options":['Default','Turn OFF'],
				"storekey": "ws-classSwitcher",
				"special": "",
				"docs": "https://app.gitbook.com/s/-MlEqeNDeKMlnHAVP2LQ/getting-started/interface-overview#class-switcher",
			}
				
		},
		{	"select":{
			"label": "Expand units:",
			"options": offOn,
			"storekey": "ws-expand-units",
			"special": "",
			"docs": "https://docs.recoda.me/getting-started/user-preference#expand-units",
		}		
	},
	],
	"Panel Sizing" : [
		{	"rangeSlider":{
				"label": "Left Panel Default Width:",
				"min": "250",
				"max": "500",
				"step": "25",
				"storekey": "--def-sidebar-width",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#left-right-panel-default-width",
			}		
		},
		{	"rangeSlider":{
				"label": "Right Panel Default Width:",
				"min": "250",
				"max": "500",
				"step": "25",
				"storekey": "--def-sidepanel-width",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#left-right-panel-default-width",
			}		
		},
		{	"rangeSlider":{
				"label": "Structure indentation:",
				"min": "0.2",
				"max": "2.5",
				"step": "0.1",
				"storekey": "--ws-dom-indentation",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#structure-indentation",
			}		
		},
		{	"rangeDivider":{
				"label": "Left/Right Panel Max. ratio:",
				"min": "30",
				"max": "70",
				"step": "1",
				"storekey": "--ratio",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#left-right-panel-maximal-ratio",
			}		
		},
		
	],
	"Advanced Tabs" : [
		{	"select":{
			"label": "Advantor style:",
			"options": ['Classic','Vertical','Horizontal','Hover','None'],
			"storekey": "ws-advantor-style",
			"special": "",
			"docs": "https://docs.recoda.me/getting-started/user-preference#advantor-style",
			}	
		},
		{	"select":{
			"label": "Background position presets :",
			"options": ['Disabled','Enabled'],
			"storekey": "_x_background_position_presets",
			"special": "",
			"docs": "https://docs.recoda.me/getting-started/user-preference#background-position-presets",
			}	
		},
	],
	"CodeSense" : [
		{	"select":{
				"label": "Code Editor font-size:",
				"options":['13','14','15', '16', '17', '18'],
				"storekey": "--code-font-size",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#code-editor-font-size",
			}
				
		},
		{	"select":{
				"label": "Powersheets font-size:",
				"options":['13','14','15', '16', '17', '18'],
				"storekey": "--powersheets-editor-font-size",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#code-editor-font-size",
			}
			
		},
		{	"select":{
			"label": "Code Editor font:",
			"options":['default','Jet Brains Mono'],
			"storekey": "wsp-cef",
			"special": "",
			"docs": "",
		}
			
	},
	],
	"Command-line" : [
		{	"select":{
				"label": "Command-line class highlighter:",
				"options": offOn,
				"storekey": "ws-class-highlighter",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#cl-class-highlighter",
			}
				
		},
		{	"select":{
				"label": "Command-line autorename",
				"options": ['None', 'ID change', 'First class name'],
				"storekey": "ws-cli-autorename",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#command-line-autorename",
		}
			
	},
	],
	"Shortcuts" :  [
		{	"select":{
			"label": "Shortcuts Guide:",
			"options": offOn,
			"storekey": "ws-hot-hint",
			"special": "",
			"docs": "https://docs.recoda.me/getting-started/user-preference#shortcuts-hint",
			}
		
		},
		{	"select":{
				"label": "Single Key Shortcuts:",
				"options": onOff,
				"storekey": "_x_wscuts-single",
				"special": "ws-req-reload",
				"docs": "https://docs.recoda.me/getting-started/shortcuts#keyboard-positional-single-key",
			}
				
		},
		{	"select":{
				"label": "Arrow DOM Shortcuts:",
				"options": onOff,
				"storekey": "_x_wscuts-arrow",
				"special": "ws-req-reload",
				"docs": "https://docs.recoda.me/getting-started/shortcuts#arrow-shortcuts",
			}
				
		},
		{	"select":{
				"label": "Ctrl + Key Shortcuts:",
				"options": onOff,
				"storekey": "_x_wscuts-ctrl",
				"special": "ws-req-reload",
				"docs": "https://docs.recoda.me/getting-started/shortcuts#ctrl-+-key-shortcuts",
			}
				
		},
			{	"select":{
				"label": "Shift + Key Shortcuts:",
				"options": onOff,
				"storekey": "_x_wscuts-shift",
				"special": "ws-req-reload",
				"docs": "https://docs.recoda.me/getting-started/shortcuts#shift-+-key-shortcuts",
			}
				
		},
	],
	"Units" :  [
		{	"select":{
			"label": "CSS Variable Suggestion:",
			"options": ['Enabled', 'Disabled'],
			"storekey": "_x_var_suggestion",
			"special": "ws-beta",
			"docs": "https://docs.recoda.me/getting-started/user-preference#css-variables-suggestion-video",
			}
		
		},
		{	"select":{
			"label": "Auto Unit Detection:",
			"options": ['Enabled', 'Disabled'],
			"storekey": "_x_unit_detection",
			"special": "ws-beta",
			"docs": "https://docs.recoda.me/getting-started/user-preference#css-variables-suggestion-video",
			}
		
		},
		{	"select":{
			"label": "Global:",
			"options": ['default', 'rem', 'em',],
			"storekey": "_x_-unit_Global",
			"special": "ws-beta",
			"docs": "https://docs.recoda.me/getting-started/user-preference#default-units-video",
			}
		
		},
		{	"select":{
			"label": "Margin:",
			"options": ['default','px', '%', 'rem', 'em'],
			"storekey": "_x_-unit_Margin",
			"special": "ws-beta",
			"docs": "https://docs.recoda.me/getting-started/user-preference#default-units-video",
			}
		
		},
		{	"select":{
				"label": "Padding:",
				"options": ['default','px', '%', 'rem', 'em'],
				"storekey": "_x_-unit_Padding",
				"special": "ws-beta",
				"docs": "https://docs.recoda.me/getting-started/user-preference#default-units-video",
			}
				
		},
		{	"select":{
				"label": "Width:",
				"options": ['default','px', '%', 'rem', 'em'],
				"storekey": "_x_-unit_Width",
				"special": "ws-beta",
				"docs": "https://docs.recoda.me/getting-started/user-preference#default-units-video",
			}
				
		},
		{	"select":{
				"label": "Max-width:",
				"options": ['default','px', '%', 'rem', 'em'],
				"storekey": "_x_-unit_MaxW",
				"special": "ws-beta",
				"docs": "https://docs.recoda.me/getting-started/user-preference#default-units-video",
			}
				
		},
			{	"select":{
				"label": "Font-size:",
				"options": ['default','px', 'rem', 'em'],
				"storekey": "_x_-unit_FontSize",
				"special": "ws-beta",
				"docs": "https://docs.recoda.me/getting-started/user-preference#default-units-video",
			}
				
		},
	],
	"Misc" : [
		{	"select":{
				"label": "Hide Settings Dropdown:",
				"options": offOn,
				"storekey": "ws-hide-sett-drop",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#hide-settings-dropdown",
			}
				
		},
		{	"select":{
				"label": "Classic Subtabs Style:",
				"options": onOff,
				"storekey": "ws-subtabs",
				"special": "",
				"docs": "",
			}
				
		},
		{	"select":{
				"label": "Hide Sidebar Breadcrumb:",
				"options": offOn,
				"storekey": "ws-hide_breadcumbs",
				"special": "",
				"docs": "https://docs.recoda.me/getting-started/user-preference#hide-sidebar-breadcrumb",
			}
				
		},
		{	"select":{
			"label": "Disable Padding/Margin Drag:",
			"options": ['default','disabled'],
			"storekey": "_x_ws-pmcd",
			"special": "ws-req-reload",
			"docs": "https://docs.recoda.me/getting-started/user-preference#disable-padding-margin-drag",
		}
			
	},
	],
}

	

	/* ::FUNCTION CALL:: Set Prefs on Load */
	
	//Function HTML Template to generate user preference tab
	function recodaTabMenu(o, special){
		let options='';
		if(undefined==special) special='';
		o.forEach((innerText) => { 
			let b = innerText.toLowerCase().split(/[ ,]+/).join('-');
			options += `<div class="ws-settings-tag" data-filter="${b}">${innerText}</div>`;
			} )
		return options;
	}
	//Function HTML Template to generate Select option row
	function recodaSelectOptions(category, o, option, label, special, href){
		let el= '';
		let options='';
		let docs = '';
		if(href == "") {docs = '';} 
		else{	docs = `<a class="ws-docs-link" href="${href}" target="_blank">Docs</a>`;}
		category = category.toLowerCase().split(/[ ,]+/).join('-');
		if(undefined==special) special='';
		o.forEach((innerText) => { 
			let val = innerText.toLowerCase().split(/[ ,]+/).join('-');
			options += `<option value="${val}">${innerText}</option>`;
			} )

		el = `<div class="recoda-option-row ${special}" data-tag="${category}"><p class="recoda-option">${label}</p>${docs}<select data-storekey="${option}" class="recoda-local-store-pull ">${options}</select></div>`;	
		return el;
	}
	//Function HTML Template to generate Range slider option row
	function recodaRangeOptions(category, option, label, minVal, maxVal, step, special, href){
		let docs;
		let def_w = parseFloat(getComputedStyle(document.documentElement).getPropertyValue(option),10);
		category = category.toLowerCase().split(/[ ,]+/).join('-');
		if(undefined == special || special == ""){special = "";}
		if(undefined == href || href == ""){docs = "";}
		else{	docs = `<a class="ws-docs-link" href="${href}" target="_blank">Docs</a>`;}
		let input_template = `<input id="a" type="range" step="${step}" data-storekey="${option}" class="recoda-local-store-pull" min="${minVal}" max="${maxVal}" value=" ${def_w}">`;
		let el = `<div class="recoda-option-row pref-range`+ special +`" data-tag="`+ category +`"><p class="recoda-option">`+label+`</p>`+ docs +`<form onload="result.value=a.value" oninput="result.value=a.value"><output name="result" for="a" >`+def_w+`</output>` + input_template + `</form></div>`;	
		return el;
	}
	//Function HTML Template to generate Range slider option row
	function recodaRangeDivideOptions(category, option, label, minVal, maxVal, step,  special, href){
		category = category.toLowerCase().split(/[ ,]+/).join('-');
		let docs;
		let def_w = parseInt(getComputedStyle(document.documentElement).getPropertyValue(option),10);
		let def_val_r = 100 - def_w;
		if(undefined == special || special == ""){special = "";}
		if(undefined == href || href == ""){docs = "";}
		else{	docs = `<a class="ws-docs-link" href="${href}" target="_blank">Docs</a>`;}
		let input_template = `<input id="a" type="range" step="${step}" data-storekey="${option}" class="recoda-local-store-pull" min="${minVal}" max="${maxVal}"`+ `value="${def_w}">`;
		let el = `<div class="recoda-option-row pref-range-divider ${special}" data-tag="${category}"><p class="recoda-option">${label}</p><form oninput="result1.value=parseInt(a.value); result2.value =100-parseInt(a.value) "><output name="result1" for="a">${def_w}</output>${input_template}<output name="result2" for="a">${def_val_r}</output></form> </div>`;	
		return el;
	}

	recoda.showImportWizard = function(){
		let target = document.getElementById("rews-up-clip");
		let o = document.getElementById("recodaImportWizard");
		let copyTextarea = document.querySelector('.rews-up-js-copytextarea');
			
		if (!target.classList.contains("recoda-show-import-clip")){
			target.classList.add("recoda-show-import-clip");
			o.classList.add("active");
			copyTextarea.value = "";
			copyTextarea.focus();
			copyTextarea.select();
		} 
		else{
			target.classList.remove("recoda-show-import-clip");
			o.classList.remove("active");
		} 
	}

/*---------------------------------------------------------------------------------------------------------------------------*/
/* RECODA | ELEMEN | USER PREFERENCE | MODAL*/
jQuery(`<div class="ws-modal-wrap"><div class="rews-backdrop-modal-close ws-up ws-HIDE " data-ws-modal="02" onclick="recoda.openCloseModal('02')"></div><div id="ws-user-pref-tag-controlls" class="recoda-settings-container ws-popup" data-filtering="none"> <div class="ws-row" id="recodaUserPrefHeader"> <div id="ws-user-profile"><h3 class="ws-modal-title" >Profile:</h3><button class="ws-profile ws-profile-default active">Default</button><button class="ws-profile ws-profile-custom">Custom</button></div><button class="ws-icon-btn ws-mL-auto" onclick="recoda.openCloseModal('02')" ><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"></path></svg></button> </div> <div id="recodaSettingsTagContainer" style="position: absolute;top: 45px;width: 200px;"></div><div id="recodaSettingsContainer"></div> <div id="recodaUserPrefFooter"><button id="ws-save-profile">Save</button><button onclick="recoda.exportUserPref()">Export</button><button id="recodaImportWizard" onclick="recoda.showImportWizard()">Import</button><div class="rews-divider"></div><a href="https://feedback.recoda.me/" target="_blank">Feedback</a><a onclick="recoda.launchBugeReportCentre()">Record Bug</a><a href="https://docs.recoda.me/" target="_blank">Docs</a><a href="https://workspace.recoda.me/u/27c27215" target="_blank">Support</a><a class="rews-disabled">How to</a> <div id= "rews-up-clip" style="clip: rect(0,0,0,0);position: absolute;"><div class="recoda-import-wizdard-container"><p>Welcome to Import Wizard</p><p><span>STEP 1</span>Click on Textarea</p><p><span>STEP 2</span>Paste</p></div> <textarea placeholder="I'm textarea, Paste me here..&#10;Mac users: ⌘ + V  &#10;PC users: Ctrl + V " onpaste="recoda.importUserPref();" class="rews-up-js-copytextarea"></textarea></div>   </div></div> </div>`).prependTo("#oxygen-ui")

/*---------------------------------------------------------------------------------------------------------------------------*/
/* HTML TEMPLATE CALLL----  RECODA | ELEMENT | USER PREFERENCE | MODAL OPTIONS 
** add created option elements
*/
	const settingsTabsTags = recodaTabMenu(Object.keys(userPrefsObj));
	let optionRowBuffer = "";
	for (const [tag, element] of Object.entries(userPrefsObj)) {
		let category = tag;
		for (const [num, obj] of Object.entries(element)) {
			for (const [k, v] of Object.entries(obj)) {
				if(k == "select")					{	optionRowBuffer = optionRowBuffer + recodaSelectOptions(category, v["options"], v["storekey"], v["label"], v["special"], v["docs"])}
				else if(k == "rangeSlider")	   		{ 	optionRowBuffer = optionRowBuffer + recodaRangeOptions(category,  v["storekey"], v["label"], v["min"], v["max"], v["step"], v["special"], v["docs"])}
				else if(k == "rangeDivider")		{ 	optionRowBuffer = optionRowBuffer + recodaRangeDivideOptions(category,  v["storekey"], v["label"], v["min"], v["max"], v["step"], v["special"], v["docs"])}
				
			}
		}
	}
	function generateView(){
		document.querySelector('#recodaSettingsContainer').innerHTML = '';
		document.querySelector('#recodaSettingsTagContainer').innerHTML = '';
		jQuery(optionRowBuffer).prependTo("#recodaSettingsContainer");
		// prepend tab menu to settings container
		jQuery(	settingsTabsTags ).prependTo("#recodaSettingsTagContainer");
		Tabs();
	}
	generateView();
	changeSelectedUserProfile();
	initSettingsToDB();
/*---------------------------------------------------------------------------------------------------------------------------*/
/* SET ITEMS IN LS*/
/* get data from all elements with class ".recoda-local-store-pull" (option value are saved in data-storekey)
** add created option elements to modal
*/

	let iVal = document.querySelectorAll(".recoda-local-store-pull");
	iVal.forEach(function(elem) {
		elem.addEventListener('change', (event) => {
			recodaSetLocalStorage(iVal);
			recodaGetLocalStorage();
			//setRecodaUI();
		});
	});

/*---------------------------------------------------------------------------------------------------------------------------*/
/* Load */
/* get data from all elements with class ".recoda-local-store-pull" (option value are saved in data-storekey)
** add created option elements to modal
** data direction: 	LS ==> APP
*/
	function recodaLoadCurrentUserPref(list){
		let i = list;
		let b = JSON.parse(localStorage.getItem('recoda-settings'));
		i.forEach((item) => { 
			let key = item.getAttribute("data-storekey");
			for (var k in b) {
				if (b.hasOwnProperty(k)) {
					if(key == k){
						item.value = b[k];
					}
				}
			}
		})			
	}


/*---------------------------------------------------------------------------------------------------------------------------*/
/* on loading Oxygen load data from Local storage to Recoda */
recodaLoadCurrentUserPref(iVal);

/*---------------------------------------------------------------------------------------------------------------------------*/
/* Set /  Write */
/* write all data from app to Local STorage
** data direction: 	APP ==> LS
*/
	function recodaSetLocalStorage(list){
		let i = list;
		let recoda_settings = {
			recoda: 'active',
		};
		i.forEach((item) => { 
			let key = item.getAttribute("data-storekey");
			let keyValue = item.value;
			recoda_settings[key] = keyValue; 
			} )
			localStorage.setItem('recoda-settings', JSON.stringify(recoda_settings));
	}

/*---------------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------------------------------*/
/* Get / Read 
	1:	read all data from local storage
	2. set all options in App needed to manipulate with UI
	! BAD: there are things hardcoded, rethink, make patterns and change to universal solution
** data direction: 	LS ==> APP
*/		

	function recodaGetLocalStorage(){
		let localStore = JSON.parse(localStorage.getItem('recoda-settings'));
		let r = document.querySelector(':root');
		let d = document.querySelector("body");

		for (var key in localStore) {
			
			switch( key ) {
				case "ws-docking":
					let bodyClass = key+ "_" +localStore[key];
					let c = d.classList;
					for (i=0; i < c["length"]; i++){
						let s = c[i];
						if (s.includes(key)){
							d.classList.remove(s);
								}	
					}
					if(localStore[key] != "off") d.classList.add(bodyClass);
				
					
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
				break;
				case "_x_ws-pmcd":
					if(localStore[key] != "default"){
						document.addEventListener("wsLoad", () => {
							var iframe = document.getElementById('ct-artificial-viewport');
							var innerDoc = (iframe.contentDocument) ? iframe.contentDocument : iframe.contentWindow.document;
							innerDoc.querySelector('html').classList.add('ws_pm_disabled');

						});
					} 
				break;
				case "--powersheets-editor-font-size":
					r.style.setProperty(key, localStore[key] +'px');
					r.style.setProperty("--powersheets-editor-font-size", localStore[key] +'px');
				break;
				case "--ws-dom-indentation":
					r.style.setProperty(key, localStore[key] +'rem');
					r.style.setProperty("--ws-dom-indentation", localStore[key] +'rem');
				break;
				case "--magnetic-trigger":
					r.style.setProperty(key, localStore[key] +'px');
					r.style.setProperty("--magnetic-trigger", localStore[key] +'px');
				break;
				case "--def-sidebar-width":
					r.style.setProperty(key, localStore[key] +'px');
					r.style.setProperty("--sidebar-width", localStore[key] +'px');
				break;
				case "--def-sidepanel-width":
					r.style.setProperty(key, localStore[key] +'px');
					r.style.setProperty("--sidepanel-width", localStore[key] +'px');
				break;
				case "--code-font-size":
					r.style.setProperty(key, localStore[key] +'px');
					r.style.setProperty("--code-font-size", localStore[key] +'px');
				break;
				case "--resizer-handle-width":
					r.style.setProperty(key, localStore[key] +'px');
					r.style.setProperty("--resizer-handle-width", localStore[key] +'px');
				break;
				case "--ws-add-element-grid-template":
					r.style.setProperty(key, localStore[key] +'px');
					r.style.setProperty("--ws-add-element-grid-template", localStore[key] +'px');
				break;
				case "--ratio":
					r.style.setProperty(key, localStore[key] );
					r.style.setProperty("--ratio", localStore[key]);
				break;
/* NOTE ## WARNING	START			-------------------------------*/	
/**
 * b = html (root)
 * d = body 
 * correct this nonsense
 * remove html atts, move them to body
 * add type or tag mechanism,
 * rename functions element
 * ! bad: comments explain things which they should not explan, rethink and make more obvious code 
 */
/* NOTE ## WARNING	END			-------------------------------*/	
				case "ws-theme": case "ws-dom":
					r.setAttribute(key, localStore[key])				
				break;
				case "data-wspack-install":case"data-wsoptimze-screen":
					d.setAttribute(key, localStore[key])
				break;
				
				default:
					if(!key.includes("_x_")){
						let bodyClass = key+ "_" +localStore[key];
						let c = d.classList;
						for (var i=0; i < c["length"]; i++){
							let s = c[i];
							if (s.includes(key)){
								d.classList.remove(s);
									}	
						}
						if(localStore[key] != "off" && localStore[key] != "default") {d.classList.add(bodyClass);  };
					}
					break;
			}
		}
	
	};
	loadCustomProfileFromDB();
	recoda.localSaveUserPref();
	recodaGetLocalStorage();
});
