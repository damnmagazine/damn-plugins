<?php
/**
* Shortcode `[s2Member-Pro-PayPal-Form /]` (inner processing routines).
*
* Copyright: © 2009-2011
* {@link http://www.websharks-inc.com/ WebSharks, Inc.}
* (coded in the USA)
*
* This WordPress plugin (s2Member Pro) is comprised of two parts:
*
* o (1) Its PHP code is licensed under the GPL license, as is WordPress.
* 	You should have received a copy of the GNU General Public License,
* 	along with this software. In the main directory, see: /licensing/
* 	If not, see: {@link http://www.gnu.org/licenses/}.
*
* o (2) All other parts of (s2Member Pro); including, but not limited to:
* 	the CSS code, some JavaScript code, images, and design;
* 	are licensed according to the license purchased.
* 	See: {@link http://www.s2member.com/prices/}
*
* Unless you have our prior written consent, you must NOT directly or indirectly license,
* sub-license, sell, resell, or provide for free; part (2) of the s2Member Pro Add-on;
* or make an offer to do any of these things. All of these things are strictly
* prohibited with part (2) of the s2Member Pro Add-on.
*
* Your purchase of s2Member Pro includes free lifetime upgrades via s2Member.com
* (i.e., new features, bug fixes, updates, improvements); along with full access
* to our video tutorial library: {@link http://www.s2member.com/videos/}
*
* @package s2Member\PayPal
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");

if(!class_exists("c_ws_plugin__s2member_pro_paypal_form_in"))
	{
		/**
		* Shortcode `[s2Member-Pro-PayPal-Form /]` (inner processing routines).
		*
		* @package s2Member\PayPal
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_paypal_form_in
			{
				/**
				* Shortcode `[s2Member-Pro-PayPal-xFormOption /]`.
				*
				* @package s2Member\PayPal
				* @since 130728
				*
				* @attaches-to ``add_shortcode("s2Member-Pro-PayPal-xFormOption");``
				*
				* @param array $attr An array of Attributes.
				* @param string $content Content inside the Shortcode.
				* @param string $shortcode The actual Shortcode name itself.
				* @return string The resulting Form Code, HTML markup.
				*/
				public static function sc_paypal_form_option($attr = FALSE, $content = FALSE, $shortcode = FALSE)
					{
						foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;
						do_action("ws_plugin__s2member_pro_before_sc_paypal_form", get_defined_vars());
						unset($__refs, $__v);

						return serialize(c_ws_plugin__s2member_utils_strings::trim_qts_deep((array)$attr)).'|::|';
					}
				/**
				* Shortcode `[s2Member-Pro-PayPal-Form /]`.
				*
				* @package s2Member\PayPal
				* @since 1.5
				*
				* @attaches-to ``add_shortcode("s2Member-Pro-PayPal-Form");``
				*
				* @param array $attr An array of Attributes.
				* @param string $content Content inside the Shortcode.
				* @param string $shortcode The actual Shortcode name itself.
				* @return string The resulting Form Code, HTML markup.
				*/
				public static function sc_paypal_form($attr = FALSE, $content = FALSE, $shortcode = FALSE)
					{
						foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;
						do_action("ws_plugin__s2member_pro_before_sc_paypal_form", get_defined_vars());
						unset($__refs, $__v);

						c_ws_plugin__s2member_no_cache::no_cache_constants /* No caching on pages that contain a Pro-Form. */(true);

						$attr = /* Force array. Trim quote entities. */ c_ws_plugin__s2member_utils_strings::trim_qts_deep((array)$attr);
						$options = array(); // Initialize options to an empty array.
						$option_selections = ''; // Initialize w/ no options.

						if($content && ($content = strip_tags($content))) // This allows for nested Pro-Form Shortcodes as options.
							$content = str_replace('s2Member-Pro-PayPal-Form ', 's2Member-Pro-PayPal-xFormOption ', $content);

						if($content && ($content_options = do_shortcode($content)))
							{
								foreach(preg_split('/\s*\|\:\:\|\s*/', $content_options, NULL, PREG_SPLIT_NO_EMPTY) as $_content_option_key => $_content_option)
									{
										$_content_option_id = $_content_option_key + 1;
										$options[$_content_option_id] = maybe_unserialize(trim($_content_option));
										if(!is_array($options[$_content_option_id])){ unset($options[$_content_option_id]); continue; }
										if(!empty($_REQUEST['s2p-option']) && (integer)$_REQUEST['s2p-option'] === $_content_option_id)
											$options[$_content_option_id]['selected'] = TRUE;
									}
								unset($_content_option_key, $_content_option, $_content_option_id); // Housekeeping.

								foreach($options as $_option_id => $_option) if(!empty($_option['selected']))
									{ $attr = array_merge($attr, $_option); $_selected_option_id = $_option_id; }
								unset($_option_id, $_option); // Housekeeping.

								if(empty($_selected_option_id)) foreach($options as $_option_id => $_option)
									{ $attr = array_merge($attr, $_option); break; } // Force a selected option (default).
								unset($_option_id, $_option, $_selected_option_id); // Housekeeping.

								foreach($options as $_option_id => $_option) // Build option selections.
									$option_selections .= '<option value="'.esc_attr($_option_id).'"'.((!empty($_option['selected'])) ? ' selected="selected"' : '').'>'.esc_html($_option['desc']).'</option>';
								unset($_option_id, $_option); // Housekeeping.
							}
						$attr = shortcode_atts(array("ids" => "0", "exp" => "72", "level" => ((@$attr["register"]) ? "0" : "1"), "ccaps" => "", "desc" => "", "ps" => "paypal", "lc" => "", "lang" => "", "cc" => "USD", "dg" => "0", "ns" => "1", "custom" => $_SERVER["HTTP_HOST"], "ta" => "0", "tp" => "0", "tt" => "D", "ra" => "0.01", "rp" => "1", "rt" => "M", "rr" => "1", "rrt" => "", "rra" => "2", "modify" => "0", "cancel" => "0", "unsub" => "0", "sp" => "0", "register" => "0", "update" => "0", "accept" => "paypal,visa,mastercard,amex,discover,maestro,solo", "accept_via_paypal" => "paypal", "coupon" => "", "accept_coupons" => "0", "default_country_code" => "", "captcha" => "", "template" => "", "success" => ""), $attr);

						$attr["lc"] = /* Locale code absolutely must be provided in upper-case format. Only after running shortcode_atts(). */ strtoupper($attr["lc"]);
						$attr["tt"] = /* Term lengths absolutely must be provided in upper-case format. Only after running shortcode_atts(). */ strtoupper($attr["tt"]);
						$attr["rt"] = /* Term lengths absolutely must be provided in upper-case format. Only after running shortcode_atts(). */ strtoupper($attr["rt"]);
						$attr["rr"] = /* Must be provided in upper-case format. Numerical, or BN value. Only after running shortcode_atts(). */ strtoupper($attr["rr"]);
						$attr["ccaps"] = /* Custom Capabilities must be typed in lower-case format. Only after running shortcode_atts(). */ strtolower($attr["ccaps"]);
						$attr["ccaps"] = /* Custom Capabilities should not have spaces. */ str_replace(" ", "", $attr["ccaps"]);
						$attr["rr"] = /* Lifetime Subscriptions require Buy Now. Only after running shortcode_atts(). */ ($attr["rt"] === "L") ? "BN" : $attr["rr"];
						$attr["rr"] = /* Independent Ccaps require Buy Now. Only after running shortcode_atts(). */ ($attr["level"] === "*") ? "BN" : $attr["rr"];
						$attr["rr"] = /* No Trial / non-recurring. Only after running shortcode_atts(). */ (!$attr["tp"] && !$attr["rr"]) ? "BN" : $attr["rr"];
						$attr["ns"] = /* No shipping directive must be 1 for digital items. After shortcode_atts(). */ ($attr["dg"] === "1") ? "1" : $attr["ns"];
						$attr["default_country_code"] = /* This MUST be in uppercase format. */ strtoupper($attr["default_country_code"]);
						$attr["success"] = /* Normalize ampersands. */ c_ws_plugin__s2member_utils_urls::n_amps($attr["success"]);

						$attr['accept'] = trim($attr['accept']) ? preg_split('/[;,]+/', preg_replace('/['."\r\n\t".'\s]+/', '', trim(strtolower($attr['accept'])))) : array();
						$attr['accept'] = !in_array('paypal', $attr['accept']) ? array_merge($attr['accept'], array('paypal')) : $attr['accept'];

						$attr['accept_via_paypal'] = trim($attr['accept_via_paypal']) ? preg_split('/[;,]+/', preg_replace('/['."\r\n\t".'\s]+/', '', trim(strtolower($attr['accept_via_paypal'])))) : array();
						$attr['accept_via_paypal'] = !in_array('paypal', $attr['accept_via_paypal']) ? array_merge($attr['accept_via_paypal'], array('paypal')) : $attr['accept_via_paypal'];

						$attr["coupon"] = (!empty($_GET["s2p-coupon"])) ? trim(strip_tags(stripslashes($_GET["s2p-coupon"]))) : $attr["coupon"];

						$attr["singular"] = /* Collect the Singular ID for this Post/Page. */ get_the_ID();

						foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;
						do_action("ws_plugin__s2member_pro_before_sc_paypal_form_after_shortcode_atts", get_defined_vars());
						unset($__refs, $__v);

						if /* Cancellations. */($attr["cancel"])
							{
								$_p = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST));
								/*
								Obtain a possible response and/or validation error.
								*/
								$response = c_ws_plugin__s2member_pro_paypal_responses::paypal_cancellation_response($attr);
								/*
								Empty post vars on successful response.
								*/
								$_p = ($response["response"] && !$response["error"]) ? array(): $_p;
								/*
								Build the reCaptcha box via JavaScript.
								*/
								if($attr["captcha"]) // Is a captcha being used on this form?
									{
										$captcha = '<div id="s2member-pro-paypal-cancellation-form-captcha-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-cancellation-form-section s2member-pro-paypal-form-captcha-section s2member-pro-paypal-cancellation-form-captcha-section">'."\n";

										$captcha .= '<div id="s2member-pro-paypal-cancellation-form-captcha-section-title" class="s2member-pro-paypal-form-section-title s2member-pro-paypal-cancellation-form-section-title s2member-pro-paypal-form-captcha-section-title s2member-pro-paypal-cancellation-form-captcha-section-title">'."\n";
										$captcha .= _x("Security Verification", "s2member-front", "s2member")."\n";
										$captcha .= '</div>'."\n";

										$captcha .= '<div id="s2member-pro-paypal-cancellation-form-captcha-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-cancellation-form-div s2member-pro-paypal-form-captcha-div s2member-pro-paypal-cancellation-form-captcha-div">'."\n";

										$captcha .= '<label id="s2member-pro-paypal-cancellation-form-captcha-label" class="s2member-pro-paypal-form-captcha-label s2member-pro-paypal-cancellation-form-captcha-label">'."\n";
										$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag($attr["captcha"], 10)."\n";
										$captcha .= '</label>'."\n";

										$captcha .= '</div>'."\n";

										$captcha .= '</div>'."\n";
									}
								/*
								Build the hidden input variables.
								*/
								$hidden_inputs = '<input type="hidden" name="s2member_pro_paypal_cancellation[nonce]" id="s2member-pro-paypal-cancellation-nonce" value="'.esc_attr(wp_create_nonce("s2member-pro-paypal-cancellation")).'" />';
								$hidden_inputs .= '<input type="hidden" name="s2member_pro_paypal_cancellation[attr]" id="s2member-pro-paypal-cancellation-attr" value="'.esc_attr(c_ws_plugin__s2member_utils_encryption::encrypt(serialize($attr))).'" />';
								$hidden_inputs .= '<input type="hidden" id="s2member-pro-paypal-lang-attr" value="'.esc_attr($attr["lang"]).'" />';
								$hidden_inputs .= '<input type="hidden" name="s2p-option" value="'.esc_attr((string)@$_REQUEST['s2p-option']).'" />';
								/*
								Get the form template.
								*/
								$custom_template = (is_file(TEMPLATEPATH."/paypal-cancellation-form.php")) ? TEMPLATEPATH."/paypal-cancellation-form.php" : '';
								$custom_template = (is_file(get_stylesheet_directory()."/paypal-cancellation-form.php")) ? get_stylesheet_directory()."/paypal-cancellation-form.php" : $custom_template;

								$custom_template = ($attr["template"] && is_file(TEMPLATEPATH."/".$attr["template"])) ? TEMPLATEPATH."/".$attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && is_file(get_stylesheet_directory()."/".$attr["template"])) ? get_stylesheet_directory()."/".$attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && is_file(WP_CONTENT_DIR."/".$attr["template"])) ? WP_CONTENT_DIR."/".$attr["template"] : $custom_template;

								$code = trim(file_get_contents((($custom_template) ? $custom_template : dirname(dirname(dirname(dirname(__FILE__))))."/templates/forms/paypal-cancellation-form.php")));
								$code = trim(((!$custom_template || !is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? c_ws_plugin__s2member_utilities::evl($code) : $code));
								/*
								Fill in the action.
								*/
								$code = preg_replace("/%%action%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(remove_query_arg(array("s2member_paypal_xco", "token", "PayerID"), $_SERVER["REQUEST_URI"]))), $code);
								/*
								Fill in the response.
								*/
								$code = preg_replace("/%%response%%/", c_ws_plugin__s2member_utils_strings::esc_refs($response["response"]), $code);
								/*
								Fill in the description.
								*/
								$code = preg_replace("/%%description%%/", c_ws_plugin__s2member_utils_strings::esc_refs($attr["desc"]), $code);
								/*
								Fill the captcha section.
								*/
								$code = preg_replace("/%%captcha%%/", c_ws_plugin__s2member_utils_strings::esc_refs(@$captcha), $code);
								/*
								Fill hidden inputs.
								*/
								$code = preg_replace("/%%hidden_inputs%%/", c_ws_plugin__s2member_utils_strings::esc_refs($hidden_inputs), $code);

								foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;
								do_action("ws_plugin__s2member_pro_during_sc_paypal_cancellation_form", get_defined_vars());
								unset($__refs, $__v);
							}
						else if /* Free registrations. */($attr["register"])
							{
								$_p = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST));
								/*
								Obtain a possible response and/or validation error.
								*/
								$response = c_ws_plugin__s2member_pro_paypal_responses::paypal_registration_response($attr);
								/*
								Empty post vars on successful response.
								*/
								$_p = ($response["response"] && !$response["error"]) ? array(): $_p;
								/*
								Build all of the custom fields.
								*/
								if /* Only display Custom Fields if configured. */($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"])
									if(($fields_applicable = c_ws_plugin__s2member_custom_reg_fields::custom_fields_configured_at_level($attr["level"], "registration")))
										{
											$tabindex = /* Start tabindex at 99 ( +1 below = 100 ). */ 99;

											$custom_fields = '<div id="s2member-pro-paypal-registration-form-custom-fields-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-registration-form-section s2member-pro-paypal-form-custom-fields-section s2member-pro-paypal-registration-form-custom-fields-section">'."\n";

											$custom_fields .= '<div id="s2member-pro-paypal-registration-form-custom-fields-section-title" class="s2member-pro-paypal-form-section-title s2member-pro-paypal-registration-form-section-title s2member-pro-paypal-form-custom-fields-section-title s2member-pro-paypal-registration-form-custom-fields-section-title">'."\n";
											$custom_fields .= _x("Additional Info", "s2member-front", "s2member")."\n";
											$custom_fields .= '</div>'."\n";

											foreach(json_decode($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"], true) as $field)
												{
													if /* Field is applicable to Level 0? */(in_array($field["id"], $fields_applicable))
														{
															$field_var = preg_replace("/[^a-z0-9]/i", "_", strtolower($field["id"]));
															$field_id_class = preg_replace("/_/", "-", $field_var);

															if /* Starts a new section? */(!empty($field["section"]) && $field["section"] === "yes")
																$custom_fields .= '<div id="s2member-pro-paypal-registration-form-custom-reg-field-'.$field_id_class.'-divider-section" class="s2member-pro-paypal-form-div s2member-pro-paypal-registration-form-div s2member-pro-paypal-form-custom-reg-field-divider-section'.((!empty($field["sectitle"])) ? '-title' : '').' s2member-pro-paypal-form-custom-reg-field-'.$field_id_class.'-divider-section'.((!empty($field["sectitle"])) ? '-title' : '').' s2member-pro-paypal-registration-form-custom-reg-field-'.$field_id_class.'-divider-section'.((!empty($field["sectitle"])) ? '-title' : '').'">'.((!empty($field["sectitle"])) ? $field["sectitle"] : '').'</div>';

															$custom_fields .= '<div id="s2member-pro-paypal-registration-form-custom-reg-field-'.$field_id_class.'-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-registration-form-div s2member-pro-paypal-form-custom-reg-field-'.$field_id_class.'-div s2member-pro-paypal-registration-form-custom-reg-field-'.$field_id_class.'-div">'."\n";

															$custom_fields .= '<label for="s2member-pro-paypal-registration-custom-reg-field-'.esc_attr($field_id_class).'" id="s2member-pro-paypal-registration-form-custom-reg-field-'.$field_id_class.'-label" class="s2member-pro-paypal-form-custom-reg-field-'.$field_id_class.'-label s2member-pro-paypal-registration-form-custom-reg-field-'.$field_id_class.'-label">'."\n";
															$custom_fields .= '<span'.((preg_match("/^(checkbox|pre_checkbox)$/", $field["type"])) ? ' style="display:none;"' : '').'>'.$field["label"].(($field["required"] === "yes") ? ' *' : '').'</span></label>'.((preg_match("/^(checkbox|pre_checkbox)$/", $field["type"])) ? '' : '<br />')."\n";
															$custom_fields .= c_ws_plugin__s2member_custom_reg_fields::custom_field_gen(__FUNCTION__, $field, "s2member_pro_paypal_registration[custom_fields][", "s2member-pro-paypal-registration-custom-reg-field-", "s2member-pro-paypal-custom-reg-field-".$field_id_class." s2member-pro-paypal-registration-custom-reg-field-".$field_id_class, "", ($tabindex = $tabindex + 1), "", @$_p["s2member_pro_paypal_registration"], @$_p["s2member_pro_paypal_registration"]["custom_fields"][$field_var], "registration");

															$custom_fields .= '</div>'."\n";
														}
												}
											$custom_fields .= '</div>'."\n";
										}
								/*
								Build the reCaptcha box via JavaScript.
								*/
								if /* Is a captcha being used on this form? */($attr["captcha"])
									{
										$captcha = '<div id="s2member-pro-paypal-registration-form-captcha-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-registration-form-section s2member-pro-paypal-form-captcha-section s2member-pro-paypal-registration-form-captcha-section">'."\n";

										$captcha .= '<div id="s2member-pro-paypal-registration-form-captcha-section-title" class="s2member-pro-paypal-form-section-title s2member-pro-paypal-registration-form-section-title s2member-pro-paypal-form-captcha-section-title s2member-pro-paypal-registration-form-captcha-section-title">'."\n";
										$captcha .= _x("Security Verification", "s2member-front", "s2member")."\n";
										$captcha .= '</div>'."\n";

										$captcha .= '<div id="s2member-pro-paypal-registration-form-captcha-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-registration-form-div s2member-pro-paypal-form-captcha-div s2member-pro-paypal-registration-form-captcha-div">'."\n";

										$captcha .= '<label id="s2member-pro-paypal-registration-form-captcha-label" class="s2member-pro-paypal-form-captcha-label s2member-pro-paypal-registration-form-captcha-label">'."\n";
										$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag($attr["captcha"], 200)."\n";
										$captcha .= '</label>'."\n";

										$captcha .= '</div>'."\n";

										$captcha .= '</div>'."\n";
									}
								/*
								Build the opt-in checkbox.
								*/
								if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in"] && c_ws_plugin__s2member_list_servers::list_servers_integrated())
									{
										$opt_in = '<div id="s2member-pro-paypal-registration-form-custom-reg-field-opt-in-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-registration-form-div s2member-pro-paypal-form-custom-reg-field-opt-in-div s2member-pro-paypal-registration-form-custom-reg-field-opt-in-div">'."\n";

										$opt_in .= '<label for="s2member-pro-paypal-registration-form-custom-reg-field-opt-in" id="s2member-pro-paypal-registration-form-custom-reg-field-opt-in-label" class="s2member-pro-paypal-form-custom-reg-field-opt-in-label s2member-pro-paypal-registration-form-custom-reg-field-opt-in-label">'."\n";
										$opt_in .= '<input type="checkbox" name="s2member_pro_paypal_registration[custom_fields][opt_in]" id="s2member-pro-paypal-registration-form-custom-reg-field-opt-in" class="s2member-pro-paypal-form-custom-reg-field-opt-in s2member-pro-paypal-registration-form-custom-reg-field-opt-in" value="1"'.(((empty($_p["s2member_pro_paypal_registration"]) && $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in"] == 1) || @$_p["s2member_pro_paypal_registration"]["custom_fields"]["opt_in"]) ? ' checked="checked"' : '').' tabindex="300" />'."\n";
										$opt_in .= $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in_label"]."\n";
										$opt_in .= '</label>'."\n";

										$opt_in .= '</div>'."\n";
									}
								/*
								Build the hidden input variables.
								*/
								$hidden_inputs = '<input type="hidden" name="s2member_pro_paypal_registration[nonce]" id="s2member-pro-paypal-registration-nonce" value="'.esc_attr(wp_create_nonce("s2member-pro-paypal-registration")).'" />';
								$hidden_inputs .= (!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_names"]) ? '<input type="hidden" id="s2member-pro-paypal-registration-names-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= (!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_password"]) ? '<input type="hidden" id="s2member-pro-paypal-registration-password-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= '<input type="hidden" name="s2member_pro_paypal_registration[attr]" id="s2member-pro-paypal-registration-attr" value="'.esc_attr(c_ws_plugin__s2member_utils_encryption::encrypt(serialize($attr))).'" />';
								$hidden_inputs .= '<input type="hidden" id="s2member-pro-paypal-lang-attr" value="'.esc_attr($attr["lang"]).'" />';
								/*
								Get the form template.
								*/
								$custom_template = (is_file(TEMPLATEPATH."/paypal-registration-form.php")) ? TEMPLATEPATH."/paypal-registration-form.php" : '';
								$custom_template = (is_file(get_stylesheet_directory()."/paypal-registration-form.php")) ? get_stylesheet_directory()."/paypal-registration-form.php" : $custom_template;

								$custom_template = ($attr["template"] && is_file(TEMPLATEPATH."/".$attr["template"])) ? TEMPLATEPATH."/".$attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && is_file(get_stylesheet_directory()."/".$attr["template"])) ? get_stylesheet_directory()."/".$attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && is_file(WP_CONTENT_DIR."/".$attr["template"])) ? WP_CONTENT_DIR."/".$attr["template"] : $custom_template;

								$code = trim(file_get_contents((($custom_template) ? $custom_template : dirname(dirname(dirname(dirname(__FILE__))))."/templates/forms/paypal-registration-form.php")));
								$code = trim(((!$custom_template || !is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? c_ws_plugin__s2member_utilities::evl($code) : $code));
								/*
								Fill in the action.
								*/
								$code = preg_replace("/%%action%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(remove_query_arg(array("s2member_paypal_xco", "token", "PayerID"), $_SERVER["REQUEST_URI"]))), $code);
								/*
								Fill in the response.
								*/
								$code = preg_replace("/%%response%%/", c_ws_plugin__s2member_utils_strings::esc_refs($response["response"]), $code);
								/*
								Fill in the option selections.
								*/
								$code = preg_replace("/%%options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($option_selections), $code);
								/*
								Fill in the description.
								*/
								$code = preg_replace("/%%description%%/", c_ws_plugin__s2member_utils_strings::esc_refs($attr["desc"]), $code);
								/*
								Fill in the registration section.
								*/
								$code = preg_replace("/%%first_name_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(@$_p["s2member_pro_paypal_registration"]["first_name"])), $code);
								$code = preg_replace("/%%last_name_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(@$_p["s2member_pro_paypal_registration"]["last_name"])), $code);
								$code = preg_replace("/%%email_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_registration"]["email"])), $code);
								$code = preg_replace("/%%username_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_registration"]["username"])), $code);
								$code = preg_replace("/%%password1_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_registration"]["password1"])), $code);
								$code = preg_replace("/%%password2_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_registration"]["password2"])), $code);
								/*
								Fill in the custom fields section.
								*/
								$code = preg_replace("/%%custom_fields%%/", c_ws_plugin__s2member_utils_strings::esc_refs(@$custom_fields), $code);
								/*
								Fill the captcha section.
								*/
								$code = preg_replace("/%%captcha%%/", c_ws_plugin__s2member_utils_strings::esc_refs(@$captcha), $code);
								/*
								Fill the opt-in box.
								*/
								$code = preg_replace("/%%opt_in%%/", c_ws_plugin__s2member_utils_strings::esc_refs(@$opt_in), $code);
								/*
								Fill hidden inputs.
								*/
								$code = preg_replace("/%%hidden_inputs%%/", c_ws_plugin__s2member_utils_strings::esc_refs($hidden_inputs), $code);

								foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;
								do_action("ws_plugin__s2member_pro_during_sc_paypal_registration_form", get_defined_vars());
								unset($__refs, $__v);
							}
						else if /* Billing information updates. */($attr["update"])
							{
								$_p = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST));
								/*
								Obtain a possible response and/or validation error.
								*/
								$response = c_ws_plugin__s2member_pro_paypal_responses::paypal_update_response($attr);
								/*
								Empty post vars on successful response.
								*/
								$_p = ($response["response"] && !$response["error"]) ? array(): $_p;
								/*
								Build the list of card type options.
								*/
								$card_type_options = ''; // Initialize.
								foreach(array("Visa" => _x("Visa", "s2member-front", "s2member"), "MasterCard" => _x("MasterCard", "s2member-front", "s2member"), "Discover" => _x("Discover", "s2member-front", "s2member"), "Amex" => _x("American Express", "s2member-front", "s2member"), "Maestro" => _x("Maestro", "s2member-front", "s2member"), "Solo" => _x("Solo", "s2member-front", "s2member")) as $card_type_v => $card_type_l)
									$card_type_options .= '<label for="s2member-pro-paypal-update-card-type-'.esc_attr(strtolower($card_type_v)).'" id="s2member-pro-paypal-update-form-card-type-'.esc_attr(strtolower($card_type_v)).'-label" class="s2member-pro-paypal-form-card-type-label s2member-pro-paypal-update-form-card-type-label s2member-pro-paypal-form-card-type-'.esc_attr(strtolower($card_type_v)).'-label s2member-pro-paypal-update-form-card-type-'.esc_attr(strtolower($card_type_v)).'-label'.((!in_array(strtolower($card_type_v), $attr["accept"])) ? ' disabled' : '').'">'."\n".
									'<input type="radio" aria-required="true" name="s2member_pro_paypal_update[card_type]" id="s2member-pro-paypal-update-card-type-'.esc_attr(strtolower($card_type_v)).'" class="s2member-pro-paypal-card-type-'.esc_attr(strtolower($card_type_v)).' s2member-pro-paypal-update-card-type-'.esc_attr(strtolower($card_type_v)).'" value="'.esc_attr($card_type_v).'"'.((!empty($_p["s2member_pro_paypal_update"]["card_type"]) && in_array(strtolower($_p["s2member_pro_paypal_update"]["card_type"]), $attr["accept"]) && $_p["s2member_pro_paypal_update"]["card_type"] === $card_type_v) ? ' checked="checked"' : '').((!in_array(strtolower($card_type_v), $attr["accept"])) ? ' disabled="disabled"' : '').' tabindex="10" />'."\n".
										'</label>';
								/*
								Build the list of expiration date options.
								*/
								$card_expiration_month_options = '<option value=""></option>'; // Start with an empty option value.
								$card_expiration_year_options = '<option value=""></option>'; // Start with an empty option value.

								foreach(array("01" => _x ("01 January", "s2member-front", "s2member"), "02" => _x ("02 February", "s2member-front", "s2member"), "03" => _x ("03 March", "s2member-front", "s2member"), "04" => _x ("04 April", "s2member-front", "s2member"), "05" => _x ("05 May", "s2member-front", "s2member"), "06" => _x ("06 June", "s2member-front", "s2member"), "07" => _x ("07 July", "s2member-front", "s2member"), "08" => _x ("08 August", "s2member-front", "s2member"), "09" => _x ("09 September", "s2member-front", "s2member"), "10" => _x ("10 October", "s2member-front", "s2member"), "11" => _x ("11 November", "s2member-front", "s2member"), "12" => _x ("12 December", "s2member-front", "s2member")) as $month => $month_label)
									$card_expiration_month_options .= '<option value="'.esc_attr($month).'"'.((@$_p["s2member_pro_paypal_update"]["card_expiration_month"] === (string)$month) ? ' selected="selected"' : '').'>'.esc_html($month_label).'</option>';
								unset($month, $month_label); // Housekeeping.

								for($i = 0, $year = date("Y"); $i < 50; $i++) // Current year; and then go 50 years into the future.
									$card_expiration_year_options .= '<option value="'.esc_attr($year+$i).'"'.((@$_p["s2member_pro_paypal_update"]["card_expiration_year"] === (string)($year+$i)) ? ' selected="selected"' : '').'>'.esc_html($year+$i).'</option>';
								unset($i, $year); // Housekeeping.

								/*
								Build the list of country code options.
								*/
								$country_default_by_currency = (!@$_p["s2member_pro_paypal_update"]["country"] && $attr["cc"] === "USD") ? "US" : "";
								$country_default_by_currency = (!@$_p["s2member_pro_paypal_update"]["country"] && $attr["cc"] === "CAD") ? "CA" : $country_default_by_currency;
								$country_default_by_currency = (!@$_p["s2member_pro_paypal_update"]["country"] && $attr["cc"] === "GBP") ? "GB" : $country_default_by_currency;
								$country_default_by_currency = apply_filters("ws_plugin__s2member_pro_paypal_default_country", $country_default_by_currency, get_defined_vars());

								$default_country_v = ($attr["default_country_code"]) ? $attr["default_country_code"] : $country_default_by_currency;

								$country_options = /* Start with an empty option value. */ '<option value=""></option>';

								foreach(preg_split("/[\r\n]+/", file_get_contents(dirname(dirname(dirname(dirname(__FILE__))))."/iso-3166-1.txt")) as $country)
									{
										list($country_l, $country_v) = preg_split("/;/", $country, 2);

										if /* Here we also check on the default pre-selected country; as determined above; based on currency. */($country_l && $country_v)
											$country_options .= '<option value="'.esc_attr(strtoupper($country_v)).'"'.((@$_p["s2member_pro_paypal_update"]["country"] === $country_v || $default_country_v === $country_v) ? ' selected="selected"' : '').'>'.esc_html(ucwords(strtolower($country_l))).'</option>';
									}
								/*
								Build the reCaptcha box via JavaScript.
								*/
								if /* Is a captcha being used on this form? */($attr["captcha"])
									{
										$captcha = '<div id="s2member-pro-paypal-update-form-captcha-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-update-form-section s2member-pro-paypal-form-captcha-section s2member-pro-paypal-update-form-captcha-section">'."\n";

										$captcha .= '<div id="s2member-pro-paypal-update-form-captcha-section-title" class="s2member-pro-paypal-form-section-title s2member-pro-paypal-update-form-section-title s2member-pro-paypal-form-captcha-section-title s2member-pro-paypal-update-form-captcha-section-title">'."\n";
										$captcha .= _x("Security Verification", "s2member-front", "s2member")."\n";
										$captcha .= '</div>'."\n";

										$captcha .= '<div id="s2member-pro-paypal-update-form-captcha-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-update-form-div s2member-pro-paypal-form-captcha-div s2member-pro-paypal-update-form-captcha-div">'."\n";

										$captcha .= '<label id="s2member-pro-paypal-update-form-captcha-label" class="s2member-pro-paypal-form-captcha-label s2member-pro-paypal-update-form-captcha-label">'."\n";
										$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag($attr["captcha"], 200)."\n";
										$captcha .= '</label>'."\n";

										$captcha .= '</div>'."\n";

										$captcha .= '</div>'."\n";
									}
								/*
								Build the hidden input variables.
								*/
								$hidden_inputs = '<input type="hidden" name="s2member_pro_paypal_update[nonce]" id="s2member-pro-paypal-update-nonce" value="'.esc_attr(wp_create_nonce("s2member-pro-paypal-update")).'" />';
								$hidden_inputs .= '<input type="hidden" name="s2member_pro_paypal_update[attr]" id="s2member-pro-paypal-update-attr" value="'.esc_attr(c_ws_plugin__s2member_utils_encryption::encrypt(serialize($attr))).'" />';
								$hidden_inputs .= '<input type="hidden" id="s2member-pro-paypal-lang-attr" value="'.esc_attr($attr["lang"]).'" />';
								$hidden_inputs .= '<input type="hidden" name="s2p-option" value="'.esc_attr((string)@$_REQUEST['s2p-option']).'" />';
								/*
								Get the form template.
								*/
								$custom_template = (is_file(TEMPLATEPATH."/paypal-update-form.php")) ? TEMPLATEPATH."/paypal-update-form.php" : '';
								$custom_template = (is_file(get_stylesheet_directory()."/paypal-update-form.php")) ? get_stylesheet_directory()."/paypal-update-form.php" : $custom_template;

								$custom_template = ($attr["template"] && is_file(TEMPLATEPATH."/".$attr["template"])) ? TEMPLATEPATH."/".$attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && is_file(get_stylesheet_directory()."/".$attr["template"])) ? get_stylesheet_directory()."/".$attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && is_file(WP_CONTENT_DIR."/".$attr["template"])) ? WP_CONTENT_DIR."/".$attr["template"] : $custom_template;

								$code = trim(file_get_contents((($custom_template) ? $custom_template : dirname(dirname(dirname(dirname(__FILE__))))."/templates/forms/paypal-update-form.php")));
								$code = trim(((!$custom_template || !is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? c_ws_plugin__s2member_utilities::evl($code) : $code));
								/*
								Fill in the action.
								*/
								$code = preg_replace("/%%action%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(remove_query_arg(array("s2member_paypal_xco", "token", "PayerID"), $_SERVER["REQUEST_URI"]))), $code);
								/*
								Fill in the response.
								*/
								$code = preg_replace("/%%response%%/", c_ws_plugin__s2member_utils_strings::esc_refs($response["response"]), $code);
								/*
								Fill in the description.
								*/
								$code = preg_replace("/%%description%%/", c_ws_plugin__s2member_utils_strings::esc_refs($attr["desc"]), $code);
								/*
								Fill in the billing method section.
								*/
								$code = preg_replace("/%%card_type_options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($card_type_options), $code);
								$code = preg_replace("/%%card_number_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_update"]["card_number"])), $code);
								$code = preg_replace("/%%card_expiration_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_update"]["card_expiration"])), $code);
								$code = preg_replace("/%%card_expiration_month_options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($card_expiration_month_options), $code);
								$code = preg_replace("/%%card_expiration_year_options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($card_expiration_year_options), $code);
								$code = preg_replace("/%%card_verification_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_update"]["card_verification"])), $code);
								$code = preg_replace("/%%card_start_date_issue_number_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_update"]["card_start_date_issue_number"])), $code);
								/*
								Fill in the billing address section.
								*/
								$code = preg_replace("/%%street_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_update"]["street"])), $code);
								$code = preg_replace("/%%city_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_update"]["city"])), $code);
								$code = preg_replace("/%%state_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_update"]["state"])), $code);
								$code = preg_replace("/%%country_options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($country_options), $code);
								$code = preg_replace("/%%zip_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_update"]["zip"])), $code);
								/*
								Fill the captcha section.
								*/
								$code = preg_replace("/%%captcha%%/", c_ws_plugin__s2member_utils_strings::esc_refs(@$captcha), $code);
								/*
								Fill hidden inputs.
								*/
								$code = preg_replace("/%%hidden_inputs%%/", c_ws_plugin__s2member_utils_strings::esc_refs($hidden_inputs), $code);

								foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;
								do_action("ws_plugin__s2member_pro_during_sc_paypal_update_form", get_defined_vars());
								unset($__refs, $__v);
							}
						else if /* Specific Post/Page Access. */($attr["sp"])
							{
								$_p = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST));
								/*
								Configure internal attributes.
								*/
								$attr["sp_ids_exp"] = /* Combined "sp:ids:expiration hours". */ "sp:".$attr["ids"].":".$attr["exp"];
								$attr["coupon"] = (@$_p["s2member_pro_paypal_sp_checkout"]["coupon"]) ? $_p["s2member_pro_paypal_sp_checkout"]["coupon"] : $attr["coupon"];
								/*
								Obtain a possible response and/or validation error.
								*/
								$response = c_ws_plugin__s2member_pro_paypal_responses::paypal_sp_checkout_response($attr);
								/*
								Empty post vars on successful response.
								*/
								$_p = ($response["response"] && !$response["error"]) ? array(): $_p;
								/*
								Build the list of card type options.
								*/
								$card_type_options = '<input type="radio" name="s2member_pro_paypal_sp_checkout[card_type]" id="s2member-pro-paypal-sp-checkout-card-type-free" class="s2member-pro-paypal-card-type-free s2member-pro-paypal-sp-checkout-card-type-free" value="Free" tabindex="-1" style="display:none;" />'."\n";
								foreach(array("PayPal" => _x("PayPal", "s2member-front", "s2member"), "Visa" => _x("Visa", "s2member-front", "s2member"), "MasterCard" => _x("MasterCard", "s2member-front", "s2member"), "Discover" => _x("Discover", "s2member-front", "s2member"), "Amex" => _x("American Express", "s2member-front", "s2member"), "Maestro" => _x("Maestro", "s2member-front", "s2member"), "Solo" => _x("Solo", "s2member-front", "s2member")) as $card_type_v => $card_type_l)
									$card_type_options .= '<label for="s2member-pro-paypal-sp-checkout-card-type-'.esc_attr(strtolower($card_type_v)).'" id="s2member-pro-paypal-sp-checkout-form-card-type-'.esc_attr(strtolower($card_type_v)).'-label" class="s2member-pro-paypal-form-card-type-label s2member-pro-paypal-sp-checkout-form-card-type-label s2member-pro-paypal-form-card-type-'.esc_attr(strtolower($card_type_v)).'-label s2member-pro-paypal-sp-checkout-form-card-type-'.esc_attr(strtolower($card_type_v)).'-label'.((!in_array(strtolower($card_type_v), $attr["accept"])) ? ' disabled' : '').'">'."\n".
									'<input type="radio" aria-required="true" name="s2member_pro_paypal_sp_checkout[card_type]" id="s2member-pro-paypal-sp-checkout-card-type-'.esc_attr(strtolower($card_type_v)).'" class="s2member-pro-paypal-card-type-'.esc_attr(strtolower($card_type_v)).' s2member-pro-paypal-sp-checkout-card-type-'.esc_attr(strtolower($card_type_v)).'" value="'.((in_array(strtolower($card_type_v), $attr["accept_via_paypal"])) ? "PayPal" : esc_attr($card_type_v)).'"'.((!empty($_p["s2member_pro_paypal_sp_checkout"]["card_type"]) && in_array(strtolower($_p["s2member_pro_paypal_sp_checkout"]["card_type"]), $attr["accept"]) && $_p["s2member_pro_paypal_sp_checkout"]["card_type"] === $card_type_v || ($attr["accept"] === array("paypal")&& $card_type_v === "PayPal")) ? ' checked="checked"' : '').((!in_array(strtolower($card_type_v), $attr["accept"])) ? ' disabled="disabled"' : '').' tabindex="100" />'."\n".
										'</label>';
								/*
								Build the list of expiration date options.
								*/
								$card_expiration_month_options = '<option value=""></option>'; // Start with an empty option value.
								$card_expiration_year_options = '<option value=""></option>'; // Start with an empty option value.

								foreach(array("01" => _x ("01 January", "s2member-front", "s2member"), "02" => _x ("02 February", "s2member-front", "s2member"), "03" => _x ("03 March", "s2member-front", "s2member"), "04" => _x ("04 April", "s2member-front", "s2member"), "05" => _x ("05 May", "s2member-front", "s2member"), "06" => _x ("06 June", "s2member-front", "s2member"), "07" => _x ("07 July", "s2member-front", "s2member"), "08" => _x ("08 August", "s2member-front", "s2member"), "09" => _x ("09 September", "s2member-front", "s2member"), "10" => _x ("10 October", "s2member-front", "s2member"), "11" => _x ("11 November", "s2member-front", "s2member"), "12" => _x ("12 December", "s2member-front", "s2member")) as $month => $month_label)
									$card_expiration_month_options .= '<option value="'.esc_attr($month).'"'.((@$_p["s2member_pro_paypal_sp_checkout"]["card_expiration_month"] === (string)$month) ? ' selected="selected"' : '').'>'.esc_html($month_label).'</option>';
								unset($month, $month_label); // Housekeeping.

								for($i = 0, $year = date("Y"); $i < 50; $i++) // Current year; and then go 50 years into the future.
									$card_expiration_year_options .= '<option value="'.esc_attr($year+$i).'"'.((@$_p["s2member_pro_paypal_sp_checkout"]["card_expiration_year"] === (string)($year+$i)) ? ' selected="selected"' : '').'>'.esc_html($year+$i).'</option>';
								unset($i, $year); // Housekeeping.

								/*
								Build the list of country code options.
								*/
								$country_default_by_currency = (!@$_p["s2member_pro_paypal_sp_checkout"]["country"] && $attr["cc"] === "USD") ? "US" : "";
								$country_default_by_currency = (!@$_p["s2member_pro_paypal_sp_checkout"]["country"] && $attr["cc"] === "CAD") ? "CA" : $country_default_by_currency;
								$country_default_by_currency = (!@$_p["s2member_pro_paypal_sp_checkout"]["country"] && $attr["cc"] === "GBP") ? "GB" : $country_default_by_currency;
								$country_default_by_currency = apply_filters("ws_plugin__s2member_pro_paypal_default_country", $country_default_by_currency, get_defined_vars());

								$default_country_v = ($attr["default_country_code"]) ? $attr["default_country_code"] : $country_default_by_currency;

								$country_options = /* Start with an empty option value. */ '<option value=""></option>';

								foreach(preg_split("/[\r\n]+/", file_get_contents(dirname(dirname(dirname(dirname(__FILE__))))."/iso-3166-1.txt")) as $country)
									{
										list($country_l, $country_v) = preg_split("/;/", $country, 2);

										if($country_l && $country_v) // Here we also check on the default pre-selected country; as determined above; based on currency.
											$country_options .= '<option value="'.esc_attr(strtoupper($country_v)).'"'.((@$_p["s2member_pro_paypal_sp_checkout"]["country"] === $country_v || $default_country_v === $country_v) ? ' selected="selected"' : '').'>'.esc_html(ucwords(strtolower($country_l))).'</option>';
									}
								/*
								Build the reCaptcha box via JavaScript.
								*/
								if /* Is a captcha being used on this form? */($attr["captcha"])
									{
										$captcha = '<div id="s2member-pro-paypal-sp-checkout-form-captcha-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-sp-checkout-form-section s2member-pro-paypal-form-captcha-section s2member-pro-paypal-sp-checkout-form-captcha-section">'."\n";

										$captcha .= '<div id="s2member-pro-paypal-sp-checkout-form-captcha-section-title" class="s2member-pro-paypal-form-section-title s2member-pro-paypal-sp-checkout-form-section-title s2member-pro-paypal-form-captcha-section-title s2member-pro-paypal-sp-checkout-form-captcha-section-title">'."\n";
										$captcha .= _x("Security Verification", "s2member-front", "s2member")."\n";
										$captcha .= '</div>'."\n";

										$captcha .= '<div id="s2member-pro-paypal-sp-checkout-form-captcha-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-captcha-div s2member-pro-paypal-sp-checkout-form-captcha-div">'."\n";

										$captcha .= '<label id="s2member-pro-paypal-sp-checkout-form-captcha-label" class="s2member-pro-paypal-form-captcha-label s2member-pro-paypal-sp-checkout-form-captcha-label">'."\n";
										$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag($attr["captcha"], 300)."\n";
										$captcha .= '</label>'."\n";

										$captcha .= '</div>'."\n";

										$captcha .= '</div>'."\n";
									}
								/*
								Build the opt-in checkbox.
								*/
								if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in"] && c_ws_plugin__s2member_list_servers::list_servers_integrated())
									{
										$opt_in = '<div id="s2member-pro-paypal-sp-checkout-form-custom-reg-field-opt-in-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-custom-reg-field-opt-in-div s2member-pro-paypal-sp-checkout-form-custom-reg-field-opt-in-div">'."\n";

										$opt_in .= '<label for="s2member-pro-paypal-sp-checkout-form-custom-reg-field-opt-in" id="s2member-pro-paypal-sp-checkout-form-custom-reg-field-opt-in-label" class="s2member-pro-paypal-form-custom-reg-field-opt-in-label s2member-pro-paypal-sp-checkout-form-custom-reg-field-opt-in-label">'."\n";
										$opt_in .= '<input type="checkbox" name="s2member_pro_paypal_sp_checkout[custom_fields][opt_in]" id="s2member-pro-paypal-sp-checkout-form-custom-reg-field-opt-in" class="s2member-pro-paypal-form-custom-reg-field-opt-in s2member-pro-paypal-sp-checkout-form-custom-reg-field-opt-in" value="1"'.(((empty($_p["s2member_pro_paypal_sp_checkout"]) && $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in"] == 1) || @$_p["s2member_pro_paypal_sp_checkout"]["custom_fields"]["opt_in"]) ? ' checked="checked"' : '').' tabindex="400" />'."\n";
										$opt_in .= $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in_label"]."\n";
										$opt_in .= '</label>'."\n";

										$opt_in .= '</div>'."\n";
									}
								/*
								Build the hidden input variables.
								*/
								$hidden_inputs = '<input type="hidden" name="s2member_pro_paypal_sp_checkout[nonce]" id="s2member-pro-paypal-sp-checkout-nonce" value="'.esc_attr(wp_create_nonce("s2member-pro-paypal-sp-checkout")).'" />';
								$hidden_inputs .= (!$attr["accept_coupons"]) ? '<input type="hidden" id="s2member-pro-paypal-sp-checkout-coupons-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= (!c_ws_plugin__s2member_pro_paypal_utilities::paypal_tax_may_apply()) ? '<input type="hidden" id="s2member-pro-paypal-sp-checkout-tax-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= (($cp_attr = c_ws_plugin__s2member_pro_paypal_utilities::paypal_apply_coupon($attr, $attr["coupon"])) && $cp_attr["ta"] <= 0.00 && $cp_attr["ra"] <= 0.00) ? '<input type="hidden" id="s2member-pro-paypal-sp-checkout-payment-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= '<input type="hidden" name="s2member_pro_paypal_sp_checkout[attr]" id="s2member-pro-paypal-sp-checkout-attr" value="'.esc_attr(c_ws_plugin__s2member_utils_encryption::encrypt(serialize($attr))).'" />';
								$hidden_inputs .= '<input type="hidden" id="s2member-pro-paypal-lang-attr" value="'.esc_attr($attr["lang"]).'" />';
								/*
								Get the form template.
								*/
								$custom_template = (is_file(TEMPLATEPATH."/paypal-sp-checkout-form.php")) ? TEMPLATEPATH."/paypal-sp-checkout-form.php" : '';
								$custom_template = (is_file(get_stylesheet_directory()."/paypal-sp-checkout-form.php")) ? get_stylesheet_directory()."/paypal-sp-checkout-form.php" : $custom_template;

								$custom_template = ($attr["template"] && is_file(TEMPLATEPATH."/".$attr["template"])) ? TEMPLATEPATH."/".$attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && is_file(get_stylesheet_directory()."/".$attr["template"])) ? get_stylesheet_directory()."/".$attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && is_file(WP_CONTENT_DIR."/".$attr["template"])) ? WP_CONTENT_DIR."/".$attr["template"] : $custom_template;

								$code = trim(file_get_contents((($custom_template) ? $custom_template : dirname(dirname(dirname(dirname(__FILE__))))."/templates/forms/paypal-sp-checkout-form.php")));
								$code = ($attr["accept"] === array("paypal")) ? preg_replace("/ s2member-pro-paypal-sp-checkout-form-billing-method-section\"\>/", ' s2member-pro-paypal-sp-checkout-form-billing-method-section" data-paypal-only="true">', $code) : $code;
								$code = ($attr["accept"] === array("paypal")) ? preg_replace("/Billing Method/", _x("We Accept PayPal", "s2member-front", "s2member"), $code) : $code;
								$code = trim(((!$custom_template || !is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? c_ws_plugin__s2member_utilities::evl($code) : $code));
								/*
								Fill in the action.
								*/
								$code = preg_replace("/%%action%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(remove_query_arg(array("s2member_paypal_xco", "token", "PayerID"), $_SERVER["REQUEST_URI"]))), $code);
								/*
								Fill in the response.
								*/
								$code = preg_replace("/%%response%%/", c_ws_plugin__s2member_utils_strings::esc_refs($response["response"]), $code);
								/*
								Fill in the option selections.
								*/
								$code = preg_replace("/%%options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($option_selections), $code);
								/*
								Fill in the description.
								*/
								$code = preg_replace("/%%description%%/", c_ws_plugin__s2member_utils_strings::esc_refs($attr["desc"]), $code);
								/*
								Fill in the coupon value.
								*/
								$code = preg_replace("/%%coupon_response%%/", c_ws_plugin__s2member_utils_strings::esc_refs(c_ws_plugin__s2member_pro_paypal_utilities::paypal_apply_coupon($attr, $attr["coupon"], "response", array("affiliates-1px-response"))), $code);
								$code = preg_replace("/%%coupon_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit($attr["coupon"])), $code);
								/*
								Fill in the registration section.
								*/
								$code = preg_replace("/%%first_name_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(@$_p["s2member_pro_paypal_sp_checkout"]["first_name"])), $code);
								$code = preg_replace("/%%last_name_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(@$_p["s2member_pro_paypal_sp_checkout"]["last_name"])), $code);
								$code = preg_replace("/%%email_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_sp_checkout"]["email"])), $code);
								/*
								Fill in the billing method section.
								*/
								$code = preg_replace("/%%card_type_options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($card_type_options), $code);
								$code = preg_replace("/%%card_number_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_sp_checkout"]["card_number"])), $code);
								$code = preg_replace("/%%card_expiration_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_sp_checkout"]["card_expiration"])), $code);
								$code = preg_replace("/%%card_expiration_month_options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($card_expiration_month_options), $code);
								$code = preg_replace("/%%card_expiration_year_options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($card_expiration_year_options), $code);
								$code = preg_replace("/%%card_verification_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_sp_checkout"]["card_verification"])), $code);
								$code = preg_replace("/%%card_start_date_issue_number_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_sp_checkout"]["card_start_date_issue_number"])), $code);
								/*
								Fill in the billing address section.
								*/
								$code = preg_replace("/%%street_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_sp_checkout"]["street"])), $code);
								$code = preg_replace("/%%city_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_sp_checkout"]["city"])), $code);
								$code = preg_replace("/%%state_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_sp_checkout"]["state"])), $code);
								$code = preg_replace("/%%country_options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($country_options), $code);
								$code = preg_replace("/%%zip_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_sp_checkout"]["zip"])), $code);
								/*
								Fill the captcha section.
								*/
								$code = preg_replace("/%%captcha%%/", c_ws_plugin__s2member_utils_strings::esc_refs(@$captcha), $code);
								/*
								Fill the opt-in box.
								*/
								$code = preg_replace("/%%opt_in%%/", c_ws_plugin__s2member_utils_strings::esc_refs(@$opt_in), $code);
								/*
								Fill hidden inputs.
								*/
								$code = preg_replace("/%%hidden_inputs%%/", c_ws_plugin__s2member_utils_strings::esc_refs($hidden_inputs), $code);

								foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;
								do_action("ws_plugin__s2member_pro_during_sc_paypal_sp_form", get_defined_vars());
								unset($__refs, $__v);
							}
						else // Signups and Modifications.
							{
								$_p = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST));
								/*
								Configure internal attributes.
								*/
								$attr["level_ccaps_eotper"] = ($attr["rr"] === "BN" && $attr["rt"] !== "L") ? $attr["level"].":".$attr["ccaps"].":".$attr["rp"]." ".$attr["rt"] : $attr["level"].":".$attr["ccaps"];
								$attr["level_ccaps_eotper"] = /* Clean any trailing separators from this string. */ rtrim($attr["level_ccaps_eotper"], ":");
								$attr["coupon"] = (@$_p["s2member_pro_paypal_checkout"]["coupon"]) ? $_p["s2member_pro_paypal_checkout"]["coupon"] : $attr["coupon"];
								/*
								Obtain a possible response and/or validation error.
								*/
								$response = c_ws_plugin__s2member_pro_paypal_responses::paypal_checkout_response($attr);
								/*
								Empty post vars on successful response.
								*/
								$_p = ($response["response"] && !$response["error"]) ? array(): $_p;
								/*
								Build the list of card type options.
								*/
								$card_type_options = '<input type="radio" name="s2member_pro_paypal_checkout[card_type]" id="s2member-pro-paypal-checkout-card-type-free" class="s2member-pro-paypal-card-type-free s2member-pro-paypal-checkout-card-type-free" value="Free" tabindex="-1" style="display:none;" />'."\n";
								foreach(array("PayPal" => _x("PayPal", "s2member-front", "s2member"), "Visa" => _x("Visa", "s2member-front", "s2member"), "MasterCard" => _x("MasterCard", "s2member-front", "s2member"), "Discover" => _x("Discover", "s2member-front", "s2member"), "Amex" => _x("American Express", "s2member-front", "s2member"), "Maestro" => _x("Maestro", "s2member-front", "s2member"), "Solo" => _x("Solo", "s2member-front", "s2member")) as $card_type_v => $card_type_l)
									$card_type_options .= '<label for="s2member-pro-paypal-checkout-card-type-'.esc_attr(strtolower($card_type_v)).'" id="s2member-pro-paypal-checkout-form-card-type-'.esc_attr(strtolower($card_type_v)).'-label" class="s2member-pro-paypal-form-card-type-label s2member-pro-paypal-checkout-form-card-type-label s2member-pro-paypal-form-card-type-'.esc_attr(strtolower($card_type_v)).'-label s2member-pro-paypal-checkout-form-card-type-'.esc_attr(strtolower($card_type_v)).'-label'.((!in_array(strtolower($card_type_v), $attr["accept"])) ? ' disabled' : '').'">'."\n".
									'<input type="radio" aria-required="true" name="s2member_pro_paypal_checkout[card_type]" id="s2member-pro-paypal-checkout-card-type-'.esc_attr(strtolower($card_type_v)).'" class="s2member-pro-paypal-card-type-'.esc_attr(strtolower($card_type_v)).' s2member-pro-paypal-checkout-card-type-'.esc_attr(strtolower($card_type_v)).'" value="'.((in_array(strtolower($card_type_v), $attr["accept_via_paypal"])) ? "PayPal" : esc_attr($card_type_v)).'"'.((!empty($_p["s2member_pro_paypal_checkout"]["card_type"]) && in_array(strtolower($_p["s2member_pro_paypal_checkout"]["card_type"]), $attr["accept"]) && $_p["s2member_pro_paypal_checkout"]["card_type"] === $card_type_v || ($attr["accept"] === array("paypal")&& $card_type_v === "PayPal")) ? ' checked="checked"' : '').((!in_array(strtolower($card_type_v), $attr["accept"])) ? ' disabled="disabled"' : '').' tabindex="200" />'."\n".
										'</label>';
								/*
								Build the list of expiration date options.
								*/
								$card_expiration_month_options = '<option value=""></option>'; // Start with an empty option value.
								$card_expiration_year_options = '<option value=""></option>'; // Start with an empty option value.

								foreach(array("01" => _x ("01 January", "s2member-front", "s2member"), "02" => _x ("02 February", "s2member-front", "s2member"), "03" => _x ("03 March", "s2member-front", "s2member"), "04" => _x ("04 April", "s2member-front", "s2member"), "05" => _x ("05 May", "s2member-front", "s2member"), "06" => _x ("06 June", "s2member-front", "s2member"), "07" => _x ("07 July", "s2member-front", "s2member"), "08" => _x ("08 August", "s2member-front", "s2member"), "09" => _x ("09 September", "s2member-front", "s2member"), "10" => _x ("10 October", "s2member-front", "s2member"), "11" => _x ("11 November", "s2member-front", "s2member"), "12" => _x ("12 December", "s2member-front", "s2member")) as $month => $month_label)
									$card_expiration_month_options .= '<option value="'.esc_attr($month).'"'.((@$_p["s2member_pro_paypal_checkout"]["card_expiration_month"] === (string)$month) ? ' selected="selected"' : '').'>'.esc_html($month_label).'</option>';
								unset($month, $month_label); // Housekeeping.

								for($i = 0, $year = date("Y"); $i < 50; $i++) // Current year; and then go 50 years into the future.
									$card_expiration_year_options .= '<option value="'.esc_attr($year+$i).'"'.((@$_p["s2member_pro_paypal_checkout"]["card_expiration_year"] === (string)($year+$i)) ? ' selected="selected"' : '').'>'.esc_html($year+$i).'</option>';
								unset($i, $year); // Housekeeping.

								/*
								Build the list of country code options.
								*/
								$country_default_by_currency = (!@$_p["s2member_pro_paypal_checkout"]["country"] && $attr["cc"] === "USD") ? "US" : "";
								$country_default_by_currency = (!@$_p["s2member_pro_paypal_checkout"]["country"] && $attr["cc"] === "CAD") ? "CA" : $country_default_by_currency;
								$country_default_by_currency = (!@$_p["s2member_pro_paypal_checkout"]["country"] && $attr["cc"] === "GBP") ? "GB" : $country_default_by_currency;
								$country_default_by_currency = apply_filters("ws_plugin__s2member_pro_paypal_default_country", $country_default_by_currency, get_defined_vars());

								$default_country_v = ($attr["default_country_code"]) ? $attr["default_country_code"] : $country_default_by_currency;

								$country_options = /* Start with an empty option value. */ '<option value=""></option>';

								foreach(preg_split("/[\r\n]+/", file_get_contents(dirname(dirname(dirname(dirname(__FILE__))))."/iso-3166-1.txt")) as $country)
									{
										list($country_l, $country_v) = preg_split("/;/", $country, 2);

										if /* Here we also check on the default pre-selected country; as determined above; based on currency. */($country_l && $country_v)
											$country_options .= '<option value="'.esc_attr(strtoupper($country_v)).'"'.((@$_p["s2member_pro_paypal_checkout"]["country"] === $country_v || $default_country_v === $country_v) ? ' selected="selected"' : '').'>'.esc_html(ucwords(strtolower($country_l))).'</option>';
									}
								/*
								Build all of the custom fields.
								*/
								if /* Only display Custom Fields if configured. */($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"])
									if(($fields_applicable = c_ws_plugin__s2member_custom_reg_fields::custom_fields_configured_at_level((($attr["level"] === "*") ? "auto-detection" : $attr["level"]), "registration")))
										{
											$tabindex = /* Start tabindex at 99 ( +1 below = 100 ). */ 99;

											$custom_fields = '<div id="s2member-pro-paypal-checkout-form-custom-fields-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-checkout-form-section s2member-pro-paypal-form-custom-fields-section s2member-pro-paypal-checkout-form-custom-fields-section">'."\n";

											$custom_fields .= '<div id="s2member-pro-paypal-checkout-form-custom-fields-section-title" class="s2member-pro-paypal-form-section-title s2member-pro-paypal-checkout-form-section-title s2member-pro-paypal-form-custom-fields-section-title s2member-pro-paypal-checkout-form-custom-fields-section-title">'."\n";
											$custom_fields .= _x("Additional Info", "s2member-front", "s2member")."\n";
											$custom_fields .= '</div>'."\n";

											foreach(json_decode($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"], true) as $field)
												{
													if /* Field is applicable to this Level? */(in_array($field["id"], $fields_applicable))
														{
															$field_var = preg_replace("/[^a-z0-9]/i", "_", strtolower($field["id"]));
															$field_id_class = preg_replace("/_/", "-", $field_var);

															if /* Starts a new section? */(!empty($field["section"]) && $field["section"] === "yes")
																$custom_fields .= '<div id="s2member-pro-paypal-checkout-form-custom-reg-field-'.$field_id_class.'-divider-section" class="s2member-pro-paypal-form-div s2member-pro-paypal-checkout-form-div s2member-pro-paypal-form-custom-reg-field-divider-section'.((!empty($field["sectitle"])) ? '-title' : '').' s2member-pro-paypal-form-custom-reg-field-'.$field_id_class.'-divider-section'.((!empty($field["sectitle"])) ? '-title' : '').' s2member-pro-paypal-checkout-form-custom-reg-field-'.$field_id_class.'-divider-section'.((!empty($field["sectitle"])) ? '-title' : '').'">'.((!empty($field["sectitle"])) ? $field["sectitle"] : '').'</div>';

															$custom_fields .= '<div id="s2member-pro-paypal-checkout-form-custom-reg-field-'.$field_id_class.'-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-checkout-form-div s2member-pro-paypal-form-custom-reg-field-'.$field_id_class.'-div s2member-pro-paypal-checkout-form-custom-reg-field-'.$field_id_class.'-div">'."\n";

															$custom_fields .= '<label for="s2member-pro-paypal-checkout-custom-reg-field-'.esc_attr($field_id_class).'" id="s2member-pro-paypal-checkout-form-custom-reg-field-'.$field_id_class.'-label" class="s2member-pro-paypal-form-custom-reg-field-'.$field_id_class.'-label s2member-pro-paypal-checkout-form-custom-reg-field-'.$field_id_class.'-label">'."\n";
															$custom_fields .= '<span'.((preg_match("/^(checkbox|pre_checkbox)$/", $field["type"])) ? ' style="display:none;"' : '').'>'.$field["label"].(($field["required"] === "yes") ? ' *' : '').'</span></label>'.((preg_match("/^(checkbox|pre_checkbox)$/", $field["type"])) ? '' : '<br />')."\n";
															$custom_fields .= c_ws_plugin__s2member_custom_reg_fields::custom_field_gen(__FUNCTION__, $field, "s2member_pro_paypal_checkout[custom_fields][", "s2member-pro-paypal-checkout-custom-reg-field-", "s2member-pro-paypal-custom-reg-field-".$field_id_class." s2member-pro-paypal-checkout-custom-reg-field-".$field_id_class, "", ($tabindex = $tabindex + 1), "", @$_p["s2member_pro_paypal_checkout"], @$_p["s2member_pro_paypal_checkout"]["custom_fields"][$field_var], "registration");

															$custom_fields .= '</div>'."\n";
														}
												}
											$custom_fields .= '</div>'."\n";
										}
								/*
								Build the reCaptcha box via JavaScript.
								*/
								if /* Is a captcha being used on this form? */($attr["captcha"])
									{
										$captcha = '<div id="s2member-pro-paypal-checkout-form-captcha-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-checkout-form-section s2member-pro-paypal-form-captcha-section s2member-pro-paypal-checkout-form-captcha-section">'."\n";

										$captcha .= '<div id="s2member-pro-paypal-checkout-form-captcha-section-title" class="s2member-pro-paypal-form-section-title s2member-pro-paypal-checkout-form-section-title s2member-pro-paypal-form-captcha-section-title s2member-pro-paypal-checkout-form-captcha-section-title">'."\n";
										$captcha .= _x("Security Verification", "s2member-front", "s2member")."\n";
										$captcha .= '</div>'."\n";

										$captcha .= '<div id="s2member-pro-paypal-checkout-form-captcha-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-checkout-form-div s2member-pro-paypal-form-captcha-div s2member-pro-paypal-checkout-form-captcha-div">'."\n";

										$captcha .= '<label id="s2member-pro-paypal-checkout-form-captcha-label" class="s2member-pro-paypal-form-captcha-label s2member-pro-paypal-checkout-form-captcha-label">'."\n";
										$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag($attr["captcha"], 400)."\n";
										$captcha .= '</label>'."\n";

										$captcha .= '</div>'."\n";

										$captcha .= '</div>'."\n";
									}
								/*
								Build the opt-in checkbox.
								*/
								if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in"] && c_ws_plugin__s2member_list_servers::list_servers_integrated())
									{
										$opt_in = '<div id="s2member-pro-paypal-checkout-form-custom-reg-field-opt-in-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-checkout-form-div s2member-pro-paypal-form-custom-reg-field-opt-in-div s2member-pro-paypal-checkout-form-custom-reg-field-opt-in-div">'."\n";

										$opt_in .= '<label for="s2member-pro-paypal-checkout-form-custom-reg-field-opt-in" id="s2member-pro-paypal-checkout-form-custom-reg-field-opt-in-label" class="s2member-pro-paypal-form-custom-reg-field-opt-in-label s2member-pro-paypal-checkout-form-custom-reg-field-opt-in-label">'."\n";
										$opt_in .= '<input type="checkbox" name="s2member_pro_paypal_checkout[custom_fields][opt_in]" id="s2member-pro-paypal-checkout-form-custom-reg-field-opt-in" class="s2member-pro-paypal-form-custom-reg-field-opt-in s2member-pro-paypal-checkout-form-custom-reg-field-opt-in" value="1"'.(((empty($_p["s2member_pro_paypal_checkout"]) && $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in"] == 1) || @$_p["s2member_pro_paypal_checkout"]["custom_fields"]["opt_in"]) ? ' checked="checked"' : '').' tabindex="500" />'."\n";
										$opt_in .= $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in_label"]."\n";
										$opt_in .= '</label>'."\n";

										$opt_in .= '</div>'."\n";
									}
								/*
								Build the hidden input variables.
								*/
								$hidden_inputs = '<input type="hidden" name="s2member_pro_paypal_checkout[nonce]" id="s2member-pro-paypal-checkout-nonce" value="'.esc_attr(wp_create_nonce("s2member-pro-paypal-checkout")).'" />';
								$hidden_inputs .= (!$attr["accept_coupons"]) ? '<input type="hidden" id="s2member-pro-paypal-checkout-coupons-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= (!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_password"]) ? '<input type="hidden" id="s2member-pro-paypal-checkout-password-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= (!c_ws_plugin__s2member_pro_paypal_utilities::paypal_tax_may_apply()) ? '<input type="hidden" id="s2member-pro-paypal-checkout-tax-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= (($cp_attr = c_ws_plugin__s2member_pro_paypal_utilities::paypal_apply_coupon($attr, $attr["coupon"])) && $cp_attr["ta"] <= 0.00 && $cp_attr["ra"] <= 0.00) ? '<input type="hidden" id="s2member-pro-paypal-checkout-payment-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= '<input type="hidden" name="s2member_pro_paypal_checkout[attr]" id="s2member-pro-paypal-checkout-attr" value="'.esc_attr(c_ws_plugin__s2member_utils_encryption::encrypt(serialize($attr))).'" />';
								$hidden_inputs .= '<input type="hidden" id="s2member-pro-paypal-lang-attr" value="'.esc_attr($attr["lang"]).'" />';
								/*
								Get the form template.
								*/
								$custom_template = (is_file(TEMPLATEPATH."/paypal-checkout-form.php")) ? TEMPLATEPATH."/paypal-checkout-form.php" : '';
								$custom_template = (is_file(get_stylesheet_directory()."/paypal-checkout-form.php")) ? get_stylesheet_directory()."/paypal-checkout-form.php" : $custom_template;

								$custom_template = ($attr["template"] && is_file(TEMPLATEPATH."/".$attr["template"])) ? TEMPLATEPATH."/".$attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && is_file(get_stylesheet_directory()."/".$attr["template"])) ? get_stylesheet_directory()."/".$attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && is_file(WP_CONTENT_DIR."/".$attr["template"])) ? WP_CONTENT_DIR."/".$attr["template"] : $custom_template;

								$code = trim(file_get_contents((($custom_template) ? $custom_template : dirname(dirname(dirname(dirname(__FILE__))))."/templates/forms/paypal-checkout-form.php")));
								$code = ($attr["accept"] === array("paypal")) ? preg_replace("/ s2member-pro-paypal-checkout-form-billing-method-section\"\>/", ' s2member-pro-paypal-checkout-form-billing-method-section" data-paypal-only="true">', $code) : $code;
								$code = ($attr["accept"] === array("paypal")) ? preg_replace("/Billing Method/", _x("We Accept PayPal", "s2member-front", "s2member"), $code) : $code;
								$code = trim(((!$custom_template || !is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? c_ws_plugin__s2member_utilities::evl($code) : $code));
								/*
								Fill in the action.
								*/
								$code = preg_replace("/%%action%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(remove_query_arg(array("s2member_paypal_xco", "token", "PayerID"), $_SERVER["REQUEST_URI"]))), $code);
								/*
								Fill in the response.
								*/
								$code = preg_replace("/%%response%%/", c_ws_plugin__s2member_utils_strings::esc_refs($response["response"]), $code);
								/*
								Fill in the option selections.
								*/
								$code = preg_replace("/%%options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($option_selections), $code);
								/*
								Fill in the description.
								*/
								$code = preg_replace("/%%description%%/", c_ws_plugin__s2member_utils_strings::esc_refs($attr["desc"]), $code);
								/*
								Fill in the coupon value.
								*/
								$code = preg_replace("/%%coupon_response%%/", c_ws_plugin__s2member_utils_strings::esc_refs(c_ws_plugin__s2member_pro_paypal_utilities::paypal_apply_coupon($attr, $attr["coupon"], "response", array("affiliates-1px-response"))), $code);
								$code = preg_replace("/%%coupon_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit($attr["coupon"])), $code);
								/*
								Fill in the registration section.
								*/
								$code = preg_replace("/%%first_name_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(@$_p["s2member_pro_paypal_checkout"]["first_name"])), $code);
								$code = preg_replace("/%%last_name_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(@$_p["s2member_pro_paypal_checkout"]["last_name"])), $code);
								$code = preg_replace("/%%email_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_checkout"]["email"])), $code);
								$code = preg_replace("/%%username_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_checkout"]["username"])), $code);
								$code = preg_replace("/%%password1_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_checkout"]["password1"])), $code);
								$code = preg_replace("/%%password2_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_checkout"]["password2"])), $code);
								/*
								Fill in the custom fields section.
								*/
								$code = preg_replace("/%%custom_fields%%/", c_ws_plugin__s2member_utils_strings::esc_refs(@$custom_fields), $code);
								/*
								Fill in the billing method section.
								*/
								$code = preg_replace("/%%card_type_options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($card_type_options), $code);
								$code = preg_replace("/%%card_number_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_checkout"]["card_number"])), $code);
								$code = preg_replace("/%%card_expiration_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_checkout"]["card_expiration"])), $code);
								$code = preg_replace("/%%card_expiration_month_options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($card_expiration_month_options), $code);
								$code = preg_replace("/%%card_expiration_year_options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($card_expiration_year_options), $code);
								$code = preg_replace("/%%card_verification_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_checkout"]["card_verification"])), $code);
								$code = preg_replace("/%%card_start_date_issue_number_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_checkout"]["card_start_date_issue_number"])), $code);
								/*
								Fill in the billing address section.
								*/
								$code = preg_replace("/%%street_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_checkout"]["street"])), $code);
								$code = preg_replace("/%%city_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_checkout"]["city"])), $code);
								$code = preg_replace("/%%state_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_checkout"]["state"])), $code);
								$code = preg_replace("/%%country_options%%/", c_ws_plugin__s2member_utils_strings::esc_refs($country_options), $code);
								$code = preg_replace("/%%zip_value%%/", c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p["s2member_pro_paypal_checkout"]["zip"])), $code);
								/*
								Fill the captcha section.
								*/
								$code = preg_replace("/%%captcha%%/", c_ws_plugin__s2member_utils_strings::esc_refs(@$captcha), $code);
								/*
								Fill the opt-in box.
								*/
								$code = preg_replace("/%%opt_in%%/", c_ws_plugin__s2member_utils_strings::esc_refs(@$opt_in), $code);
								/*
								Fill hidden inputs.
								*/
								$code = preg_replace("/%%hidden_inputs%%/", c_ws_plugin__s2member_utils_strings::esc_refs($hidden_inputs), $code);

								foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;
								($attr["modify"]) ? do_action("ws_plugin__s2member_pro_during_sc_paypal_modification_form", get_defined_vars()) : do_action("ws_plugin__s2member_pro_during_sc_paypal_form", get_defined_vars());
								unset($__refs, $__v);
							}
						return apply_filters("ws_plugin__s2member_pro_sc_paypal_form", $code, get_defined_vars());
					}
			}
	}
