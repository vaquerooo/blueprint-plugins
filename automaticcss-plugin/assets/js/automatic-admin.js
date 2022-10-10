window.onload = function () {
	if (!automatic_css_settings) {
		console.error(
			"automatic-css-plugin: automatic_css_settings is not defined"
		);
		return;
	}
	loading_animation("on");
	setup_color_picker();
	handle_tab_switching();
	setup_accordions();
	setup_variables();
	handle_reset_buttons();
	setup_form_saving();
	handle_click_to_copy();
	loading_animation("off");
	styleInfoTooltip();
	setUnitWidth();
	styleDropdownOptions();
};

function styleDropdownOptions() {
	const options = document.querySelectorAll(".acss-value__input--option");
	for (const option of options) {
		option.style.fontFamily = '"DM sans", sans-serif';
	}
}

function styleInfoTooltip() {
	const tooltipInfo = document.getElementsByClassName(
		"acss-var__info__tooltip"
	);

	for (let i = 0; i < tooltipInfo.length; i++) {
		const infoTooltipLength = tooltipInfo[i].textContent.length;
		if (infoTooltipLength > 26) {
			tooltipInfo[i].style.width = "26ch";
		} else {
			tooltipInfo[i].style.width = infoTooltipLength - 10 + "ch";
		}
	}
}

function setup_form_saving() {
	var acss_settings_form = document.getElementById("acss-settings-form");
	if (null === acss_settings_form) {
		console.error(
			"Missing #acss_settings_form element, can't hook submit event listener"
		);
		return;
	}
	acss_settings_form.addEventListener("submit", function (e) {
		e.preventDefault();
		loading_animation("on");
		let data = new FormData();
		data.append("action", "automaticcss_save_settings");
		data.append("nonce", automatic_css_settings.nonce);
		let form_variables = {};
		[...e.currentTarget.elements].forEach((item) => {
			if ("submit" === item.type || "button" === item.type) {
				return;
			}
			let name = item.getAttribute("name");
			let value = item.value;
			// Good for most fields. Special fields below.
			if ("radio" === item.type) {
				/*
				 * With on/off radio inputs, we get two inputs with the same name.
				 * item.value does not tell us which value was submitted: it will be 'on' for the first input and 'off' for the second one.
				 * So the second input would always overwrite the first.
				 * Solution:
				 * 1) first iteration: get the :checked value manually and save that one in form_variables[name]
				 * 2) second iteration: skip because you already have the value from the first iteration.
				 */
				if (form_variables.hasOwnProperty(name)) {
					return;
				}
				var checked_item = document.querySelector(
					"input[name='" + name + "']:checked"
				);
				if (checked_item && checked_item.value) {
					value = checked_item.value;
				}
			}
			form_variables[name] = value;
		});
		let form_variables_string = JSON.stringify(form_variables);
		data.append("variables", form_variables_string);
		let response_div = document.getElementById(
			"acss-settings__response-message"
		);
		console.log("Submitting form to Automatic.css backend");
		fetch(automatic_css_settings.ajax_url, {
			method: "POST",
			credentials: "same-origin",
			body: data,
		})
			.then((response) => response.json())
			.then((response) => {
				reset_errors();
				console.log(
					"Received response from Automatic.css backend",
					response
				);
				if (!response.hasOwnProperty("success")) {
					let error_message = `Expecting a success status from the AJAX call, but got this instead: ${response.success}`;
					console.error(error_message, response);
					slide_message(response_div, error_message, "error", 0);
					loading_animation("off");
					return;
				}
				let message_text,
					message_class = "";
				let message_duration = null;
				if (true === response.success) {
					message_class = "success";
					message_text = response.data;
				} else {
					message_class = "error";
					message_text = response.data.hasOwnProperty("message")
						? response.data.message
						: response.data;
					message_duration = 10000;
					let errors = response.data.hasOwnProperty("errors")
						? response.data.errors
						: null;
					if (errors && Object.keys(errors).length > 0) {
						for (const error_id in errors) {
							let error_message = errors[error_id];
							mark_error(error_id, error_message);
						}
					}
				}
				slide_message(
					response_div,
					message_text,
					message_class,
					message_duration
				);
				loading_animation("off");
			})
			.catch((error) => {
				let error_message = `Received an error from Automatic.css backend: ${error.message}`;
				console.error(error_message, error);
				slide_message(response_div, error_message, "error", 0);
				loading_animation("off");
			});
	});
}

function slide_message(
	response_div,
	message_text,
	message_class,
	duration = 5000
) {
	duration = duration || 5000;
	let message = `<p class="${message_class}">${message_text}</p>`;
	response_div.innerHTML = message;
	response_div.style.bottom = 0;
	if (duration > 0) {
		let timeout = setTimeout(function () {
			response_div.style.bottom = "-1000px";
		}, duration);
		/*
				TODO: allow the user to stop the timer by hovering over the div.
				response_div.addEventListener("onmouseover", function(e) {

				});
				*/
	}
}

function setup_variables() {
	const vars = automatic_css_settings.variables;
	for (const var_id in vars) {
		const var_type = vars[var_id].type;
		const no_reset = vars[var_id].no_reset || false;
		const variable = document.getElementById(var_id);
		// Setup reset buttons.
		if (!no_reset) {
			setup_reset_button(var_id, var_type);
		}
		// Setup conditionals.
		if (vars[var_id].hasOwnProperty("condition")) {
			setup_conditional_field(var_id, vars);
		}
	}
}

function setup_color_picker() {
	Coloris({
		el: ".acss-value__input--color",
		alpha: false,
	});
	// Update the saturations when a color is changed.
	const color_pickers = document.querySelectorAll(
		".acss-value__input--color"
	);
	for (const color_picker of color_pickers) {
		color_picker.addEventListener("change", function (e) {
			update_saturation_fields(e.target);
		});
	}
}

function setup_reset_button(var_id, type) {
	const toggle_elements = document.getElementsByName(var_id); // used by toggles.
	const variable_element = document.getElementById(var_id); // used by all other variable types.
	var wrapper; // filled out later depending on variable type.
	var default_value; // filled out later depending on variable type.
	if ("toggle" === type) {
		if (null === toggle_elements || toggle_elements.length === 0) {
			console.warn("toggle_elements is null or empty");
			return;
		}
		wrapper = toggle_elements[0].closest(".acss-value__input-wrapper");
		default_value = wrapper.querySelector(
			".acss-value__input-wrapper--toggle"
		).dataset.default;
	} else {
		if (null === variable_element) {
			console.warn("variable_element is null");
			return;
		}
		wrapper = variable_element.closest(".acss-value__input-wrapper");
		default_value = variable_element.dataset.default;
	}
	const reset_button = wrapper.querySelector(".acss-reset-button");
	reset_button.addEventListener("click", function (e) {
		e.preventDefault(); // otherwise the form will submit.
		this.classList.toggle("tooltip-active");
	});
	// This function allows us to click wherever to hide tooltip when its open.
	const appWrapper = reset_button.closest(".acss-wrapper");

	appWrapper.addEventListener("click", function (e) {
		if (
			e.target !== reset_button &&
			reset_button.classList.contains("tooltip-active")
		) {
			reset_button.classList.toggle("tooltip-active");
		}
	});

	const tooltip_accept_button = wrapper.querySelector(
		".acss-tooltip__accept"
	);
	tooltip_accept_button.addEventListener("click", function (e) {
		e.preventDefault(); // otherwise the form will submit.
		var has_changed = false;
		var changed_element = null;
		if ("toggle" === type) {
			for (const toggle of toggle_elements) {
				var checked_value = false;
				if (default_value === toggle.value) {
					// this is the default
					checked_value = true;
					has_changed = true;
					changed_element = toggle;
				}
				toggle.checked = checked_value;
			}
		} else {
			var wrapper_element = e.target.closest(
				".acss-value__input-wrapper"
			);
			var input_element =
				wrapper_element.querySelector(".acss-value__input");
			const current_value = get_current_value_from_input(input_element);
			const default_value = get_default_value_from_input(input_element);
			if (current_value !== default_value) {
				input_element.value = default_value;
				has_changed = true;
				changed_element = input_element;
				if ("color" === type) {
					fix_color_picker_background(input_element);
				}
			}
		}
		if (has_changed) {
			changed_element.focus();
			reset_button.classList.remove("tooltip-active");
			setTimeout(function () {
				reset_button.classList.remove("rotate-reset");
			}, 400);
			changed_element.dispatchEvent(new Event("change")); // trigger change - for conditional fields.
		}
	});
}

function setup_conditional_field(var_id, vars) {
	const condition = vars[var_id].condition;
	const condition_var_type = vars[condition.field].type;
	const condition_field = document.getElementById(condition.field);
	const field = document.getElementById(var_id);
	const field_wrapper =
		document.getElementById(condition.field_wrapper_id) ||
		field.closest(".acss-var");
	if ("toggle" === condition_var_type) {
		const buttons = document.getElementsByName(condition.field);
		const actual_value = document.querySelector(
			"input[name='" + condition.field + "']:checked"
		).value;
		update_conditional_field_visibility(
			field,
			field_wrapper,
			condition,
			actual_value
		);
		buttons.forEach((button) => {
			button.addEventListener("change", function (e) {
				const actual_value = String(e.target.value);
				update_conditional_field_visibility(
					field,
					field_wrapper,
					condition,
					actual_value
				);
			});
		});
	} else {
		const actual_value = String(condition_field.value);
		update_conditional_field_visibility(
			field,
			field_wrapper,
			condition,
			actual_value
		);
		condition_field.addEventListener("change", (e) => {
			const actual_value = String(e.target.value);
			update_conditional_field_visibility(
				field,
				field_wrapper,
				condition,
				actual_value
			);
		});
	}
}

function update_conditional_field_visibility(
	field,
	field_wrapper,
	condition,
	actual_value
) {
	const condition_type = condition.type || "show_only_if";
	const condition_value = String(condition.value);
	if ("show_only_if" === condition_type) {
		if (actual_value === condition_value) {
			const condition_required = condition.required || true;
			// show field.
			field_wrapper.classList.remove("hidden");
			if (condition_required) {
				field.setAttribute("required", "required");
			}
		} else {
			field_wrapper.classList.add("hidden");
			field.removeAttribute("required");
		}
		let wrapper_accordion = field_wrapper.closest(
			".acss-accordion__content-wrapper"
		);
		if (wrapper_accordion) {
			wrapper_accordion.style.maxHeight =
				wrapper_accordion.scrollHeight + "px";
		}
	}
}

function setup_accordions() {
	const accordions = document.querySelectorAll(".acss-accordion");
	for (const accordion of accordions) {
		accordion
			.querySelector(".acss-accordion__header")
			.addEventListener("click", function (e) {
				// Looping because there's multiple DOM elements involved.
				for (const loop_accordion of accordions) {
					const loop_accordion_header = loop_accordion.querySelector(
						".acss-accordion__header"
					);
					const loop_accordion_content = loop_accordion.querySelector(
						".acss-accordion__content-wrapper"
					);
					if (
						this === loop_accordion_header &&
						!loop_accordion.classList.contains(
							"acss-accordion--open"
						)
					) {
						// This accordion was clicked and is not open, so open it.
						loop_accordion.classList.add("acss-accordion--open");
						setTimeout(function () {
							loop_accordion_content.classList.add(
								"acss-accordion--visible"
							);
						}, 400);
						loop_accordion_content.style.maxHeight =
							loop_accordion_content.scrollHeight + "px";
					} else {
						// This accordion was NOT clicked, so close it.
						loop_accordion.classList.remove("acss-accordion--open");
						loop_accordion_content.classList.remove(
							"acss-accordion--visible"
						);
						loop_accordion_content.style.maxHeight = "0px";
					}
				}
			});
	}
}

function setUnitWidth() {
	const unitContainer = document.querySelectorAll(".acss-value__unit");
	for (const unit of unitContainer) {
		const unitLength = unit.textContent.length;
		unit.style.width = unitLength + "ch";
	}
}

//I used event listener for DOMLoaded instead of function because I couldn't reach __unit classes with function run on windowload
document.addEventListener("DOMContentLoaded", function (event) {
	const inputs = document.querySelectorAll(".acss-value__input");
	for (const input of inputs) {
		const relatedUnit =
			input.nextElementSibling.classList.contains("acss-value__unit");
		const inputLength = input.value.length;
		const dropdownInput = input.classList.contains(
			"acss-value__input--dropdown"
		);
		const colorInput = input.classList.contains("acss-value__input--color");

		if (relatedUnit && !dropdownInput && !colorInput) {
			input.style.width = `${inputLength + 2}ch`;
		} else if (!relatedUnit && !dropdownInput && !colorInput) {
			input.style.width = `${inputLength + 6}ch`;
		} else if (dropdownInput) {
			input.style.paddingRight = "6ch";
		}

		input.addEventListener("input", () => {
			const inputLength = input.value.length;
			if (relatedUnit && !dropdownInput && !colorInput) {
				input.style.width = `${inputLength + 2}ch`;
			} else if (!relatedUnit && !dropdownInput && !colorInput) {
				input.style.width = `${inputLength + 6}ch`;
			} else if (dropdownInput) {
				input.style.paddingRight = "6ch";
			}
		});
	}
});

function handle_reset_buttons() {
	const inputs = document.querySelectorAll(".acss-value__input");
	for (const input of inputs) {
		const unit = input.parentNode.querySelector(".acss-value__unit");
		if (input.nextElementSibling == unit) {
			input.style.borderRadius =
				"var(--acss-border-radius) 0 0 var(--acss-border-radius)";
		}
		input.addEventListener("change", function (e) {
			const reset_button = e.target
				.closest(".acss-value__input-wrapper")
				.querySelector(".acss-reset-button");

			if (reset_button) {
				const default_value = get_default_value_from_input(e.target);
				const current_value = get_current_value_from_input(e.target);
				const var_wrapper =
					e.target.closest(".acss-group") ||
					e.target.closest(".acss-var");
				const var_wrapper_class = var_wrapper.classList.contains(
					"acss-group"
				)
					? "acss-group--changed"
					: "acss-var--changed";
				if (default_value !== current_value) {
					reset_button.removeAttribute("disabled");
					var_wrapper.classList.add(var_wrapper_class);
					//HERE ANIMATION
				} else {
					var_wrapper.classList.remove(var_wrapper_class);
					reset_button.setAttribute("disabled", "disabled");
					reset_button.classList.add("rotate-reset"); //Reset Arrow Animation Class Toggle
					setTimeout(function () {
						reset_button.classList.remove("rotate-reset");
					}, 400);
				}
			}

			const is_color_input = e.target.classList.contains(
				"acss-value__input--color"
			);
			if (is_color_input) {
				update_saturation_fields(e.target);
			}
		});
	}
}

function update_saturation_fields(color_field) {
	// get the new saturation and set it as both the value and the default.
	let data = new FormData();
	data.append("action", "automaticcss_get_saturation");
	data.append("nonce", automatic_css_settings.nonce);
	data.append("input_color", color_field.value);
	fetch(automatic_css_settings.ajax_url, {
		method: "POST",
		credentials: "same-origin",
		body: data,
	})
		.then((response) => response.json())
		.then((response) => {
			console.log(
				"Received response from Automatic.css backend",
				response
			);
			if (!response.hasOwnProperty("success")) {
				let error_message = `Expecting a success status from the AJAX call, but got this instead: ${response.success}`;
				console.error(error_message, response);
				return;
			}
			const new_saturation = response.data;
			const panel = color_field.closest(".acss-panel");
			if (!panel) {
				console.error("Could not find panel for color input");
				return;
			}
			const saturation_inputs = panel.querySelectorAll(
				".acss-value__input[name$='-s']"
			);
			if (!saturation_inputs || saturation_inputs.length === 0) {
				console.error("Could not find saturation inputs");
				return;
			}
			for (const saturation_input of saturation_inputs) {
				saturation_input.value = new_saturation;
				saturation_input.dataset.default = new_saturation;
				saturation_input.dispatchEvent(new Event("change"));
			}
		})
		.catch((error) => {
			let error_message = `Received an error from Automatic.css backend: ${error.message}`;
			console.error(error_message, error);
			loading_animation("off");
		});
}

function handle_tab_switching() {
	if (!automatic_css_settings.tab_ids) {
		console.error(
			"Tab information not found in the automatic_css_settings object."
		);
		return;
	}
	var tab_ids = automatic_css_settings.tab_ids; // acceptable tab IDs.
	// this variable stores the last tab the user was on when they saved, so we can restore navigation from there.
	var last_tab = document.getElementById("last-tab");
	/**
	 * Determine what tab to switch to.
	 * Order of preference:
	 * 1) The tab specified in the current URL's hashlink.
	 * 2) The last tab the user was on.
	 * 3) The default tab.
	 */
	var current_url = new URL(document.URL);
	var current_hash = current_url.hash;
	var new_hash = null; // store the new hash here. If null, don't switch.
	var set_default_tab = false; // should I set the default tab?

	if ("" !== current_hash) {
		// There is a hash, so we can use that, unless it's not a valid tab ID.
		var current_hash_id = current_hash.replace("#", "");
		if (!tab_ids.includes(current_hash_id)) {
			// Not a valid hash, so set the default tab.
			set_default_tab = true;
		}
	} else {
		// There is no hash, so check the last tab the user was on.
		if (
			null !== last_tab &&
			"" !== last_tab.value &&
			tab_ids.includes(last_tab.value)
		) {
			// Last tab is a valid tab ID, so use that.
			new_hash = last_tab.value;
		} else {
			// Last tab is not a valid tab ID, so set the default tab.
			set_default_tab = true;
		}
	}
	// If we need to set the default tab, do so.
	if (set_default_tab) {
		new_hash = "acss-tab-viewport";
	}
	// If we have a new hash, switch to it.
	if (new_hash) {
		current_url.hash = new_hash;
		document.location.href = current_url.href;
		fix_url_layout_shift(current_url.href);
	}

	// fix active class on parent li
	var li_selector =
		".acss-nav a[href^='#" + current_url.hash.replace("#", "") + "']";
	var li = document.querySelector(li_selector).parentNode || null;
	if (null !== li) {
		li.classList.add("active");
	}

	// fixes tab links jumping around due to the use of #hashlinks and :target
	var hashLinks = document.querySelectorAll(".acss-nav a[href^='#']");
	[].forEach.call(hashLinks, function (link) {
		link.addEventListener("click", function (event) {
			event.preventDefault();
			// fix active class on parent li
			var active_li = document.querySelectorAll(".acss-nav li.active");
			active_li.forEach(function (li) {
				li.classList.remove("active");
			});
			event.target.parentNode.classList.add("active");
			// fix last tab
			last_tab.value = new URL(link.href).hash.replace("#", "");
			fix_url_layout_shift(link.href);
			const tab_wrapper = document.querySelector(".acss-tabs-wrapper");
			tab_wrapper.scrollTop = 0; // Fix issue where the tab content is not scrolled to the top.
		});
	});
}

/**
 * This function helps with links jumping around due to the use of #hashlinks and :target
 * @see https://medium.com/@pimterry/better-css-only-tabs-with-target-7886c88deb75
 * @param {string} url
 */
function fix_url_layout_shift(url) {
	history.pushState({}, "", url);
	// Update the URL again with the same hash, then go back
	history.pushState({}, "", url);
	history.back();
}

function handle_click_to_copy() {
	const elements = document.querySelectorAll(".acss-copy-to-clipboard");
	for (const element of elements) {
		element.addEventListener("click", async (event) => {
			if (!navigator.clipboard) {
				console.warn("Clipboard API not available");
				return;
			}
			const text = event.target.dataset.content || "";
			if ("" !== text) {
				try {
					await navigator.clipboard.writeText(text);
					event.target.textContent = "Copied to clipboard";
					setTimeout(() => {
						event.target.textContent = text;
					}, 1500);
				} catch (err) {
					console.error("Failed to copy!", err);
				}
			}
		});
	}
}

function fix_color_picker_background(element) {
	console.log("fixing color picker background", element);
	const default_value = element.dataset.default;
	console.log("default value", default_value);
	element
		.closest(".clr-field")
		.setAttribute("style", `color: ${default_value};`);
}

function loading_animation(status) {
	show = "on" === status ? true : false;
	const animation_element = document.querySelector(".acss-loading-wrapper");
	if (show) {
		animation_element.classList.remove("hidden");
	} else {
		animation_element.classList.add("hidden");
	}
}

/**
 * Get the current value of the input, keeping in mind that it might be a radio.
 * @param {Element} input
 */
function get_current_value_from_input(input) {
	switch (input.type) {
		case "radio":
			let name = input.getAttribute("name");
			var checked_item = document.querySelector(
				"input[name='" + name + "']:checked"
			);
			if (checked_item && checked_item.value) {
				return checked_item.value;
			}
		case "submit":
		case "button":
			// no value.
			return;
		default:
			return input.value;
	}
	return null;
}

/**
 * Get the default value for the specified input, keeping in mind that it might be a radio.
 * @param {Element} input
 */
function get_default_value_from_input(input) {
	switch (input.type) {
		case "radio":
			const wrapper = input.closest(".acss-value__input-wrapper--toggle");
			if (wrapper) {
				return wrapper.dataset.default;
			}
		case "submit":
		case "button":
			// no value.
			return;
		default:
			return input.dataset.default;
	}
	return null;
}

function mark_error(variable_id, error_message) {
	const element = document.getElementById(variable_id);
	let var_element = null;
	if (element) {
		var_element = element.closest(".acss-var");
	} else {
		// likely a toggle.
		var_element = document.querySelector(`#acss-var-${variable_id}`);
	}
	// Set the error message and class on the variable.
	if (var_element) {
		var_element.classList.add("error");
		var_element.classList.remove("hidden");
		const message_element = var_element.querySelector(
			".acss-var__error_message"
		);
		if (message_element) {
			message_element.innerHTML = error_message;
		}
	} else {
		// Try a group instead
		const group_element = element.closest(".acss-group");
		if (group_element) {
			group_element.classList.add("error");
			group_element.classList.remove("hidden");
			const message_element = group_element.querySelector(
				".acss-group__error_message"
			);
			if (message_element) {
				message_element.innerHTML += error_message + "<br/>";
			}
		}
	}
	// Signal the error on the accordion this variable is in, if present.
	const accordion_element = element.closest(".acss-accordion");
	if (accordion_element) {
		accordion_element.classList.add("error");
	}
	// Signal the error on the tab this variable is in.
	const tab = element.closest(".acss-tab");
	if (tab) {
		const tab_id = tab.id;
		const nav_item = document.querySelector(`a[href="#${tab_id}"]`);
		if (nav_item) {
			nav_item.classList.add("error");
		}
	}
}

function reset_errors(variable_id = null) {
	if (null !== variable_id) {
		// reset on this variable and its tab.
		const element = document.getElementById(variable_id);
		if (!element) {
			return;
		}
		const var_element = element.closest(".acss-var");
		if (var_element) {
			var_element.classList.remove("error");
			const message_element = var_element.querySelector(
				".acss-var__error_message"
			);
			if (message_element) {
				message_element.innerHTML = "";
			}
		} else {
			// Try a group instead
			const group_element = element.closest(".acss-group");
			if (group_element) {
				group_element.classList.remove("error");
				const message_element = group_element.querySelector(
					".acss-group__error_message"
				);
				if (message_element) {
					message_element.innerHTML = "";
				}
			}
		}
		const accordion_element = element.closest(".acss-accordion");
		if (accordion_element) {
			accordion_element.classList.remove("error");
		}
		const tab = element.closest(".acss-tab");
		if (tab) {
			const tab_id = tab.id;
			const error_variables = tab.querySelectorAll(".acss-var.error");
			if (!error_variables || 0 === error_variables.length) {
				// Reset the nav item marker only if there are no other errors in this tab.
				const nav_item = document.querySelector(`a[href="#${tab_id}"]`);
				if (nav_item) {
					nav_item.classList.remove("error");
				}
			}
		}
	} else {
		var variables = document.querySelectorAll(".acss-var.error");
		if (variables.length > 0) {
			for (let variable of variables) {
				variable.classList.remove("error");
			}
		}
		var groups = document.querySelectorAll(".acss-group.error");
		if (groups.length > 0) {
			for (let group of groups) {
				group.classList.remove("error");
			}
		}
		var accordions = document.querySelectorAll(".acss-accordion.error");
		if (accordions.length > 0) {
			for (let accordion of accordions) {
				accordion.classList.remove("error");
			}
		}
		var var_messages = document.querySelectorAll(
			".acss-var__error_message"
		);
		if (var_messages.length > 0) {
			for (let message of var_messages) {
				message.innerHTML = "";
			}
		}
		var group_messages = document.querySelectorAll(
			".acss-group__error_message"
		);
		if (group_messages.length > 0) {
			for (let message of group_messages) {
				message.innerHTML = "";
			}
		}
		var nav_items = document.querySelectorAll(".acss-nav a.error");
		if (nav_items.length > 0) {
			for (let nav_item of nav_items) {
				nav_item.classList.remove("error");
			}
		}
	}
}
