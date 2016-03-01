<?php
/**
* PayPal Update Forms (inner processing routines).
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

if(!class_exists("c_ws_plugin__s2member_pro_paypal_update_pf_in"))
	{
		/**
		* PayPal Update Forms (inner processing routines).
		*
		* @package s2Member\PayPal
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_paypal_update_pf_in
			{
				/**
				* Handles processing of Pro-Form billing updates.
				*
				* @package s2Member\PayPal
				* @since 1.5
				*
				* @attaches-to ``add_action("init");``
				*
				* @return null Or exits script execution after a custom URL redirection.
				*/
				public static function paypal_update()
					{
						if(!empty($_POST["s2member_pro_paypal_update"]["nonce"]) && ($nonce = $_POST["s2member_pro_paypal_update"]["nonce"]) && wp_verify_nonce($nonce, "s2member-pro-paypal-update"))
							{
								$GLOBALS["ws_plugin__s2member_pro_paypal_update_response"] = array(); // This holds the global response details.
								$global_response = &$GLOBALS["ws_plugin__s2member_pro_paypal_update_response"]; // This is a shorter reference.

								$post_vars = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST["s2member_pro_paypal_update"]));
								$post_vars["attr"]   = (!empty($post_vars["attr"])) ? (array)unserialize(c_ws_plugin__s2member_utils_encryption::decrypt($post_vars["attr"])) : array();
								$post_vars["attr"] = apply_filters("ws_plugin__s2member_pro_paypal_update_post_attr", $post_vars["attr"], get_defined_vars());

								$post_vars = c_ws_plugin__s2member_utils_captchas::recaptcha_post_vars($post_vars); // Collect reCAPTCHA™ post vars.

								if(empty($post_vars["card_expiration"]) && isset($post_vars["card_expiration_month"], $post_vars["card_expiration_year"]))
									$post_vars["card_expiration"] = $post_vars["card_expiration_month"]."/".$post_vars["card_expiration_year"];

								if(!c_ws_plugin__s2member_pro_paypal_responses::paypal_form_attr_validation_errors($post_vars["attr"])) // Must NOT have any attr errors.
									{
										if(!($error = c_ws_plugin__s2member_pro_paypal_responses::paypal_form_submission_validation_errors("update", $post_vars)))
											{
												if($post_vars["card_type"] === "PayPal") // A Customer must log into their PayPal account to update billing info.
													{
														$global_response = array("response" => sprintf(_x('Please <a href="%s" rel="nofollow">log in at PayPal</a> to update your billing information.', "s2member-front", "s2member"), esc_attr("https://".(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_sandbox"]) ? "www.sandbox.paypal.com" : "www.paypal.com")."/")), "error" => true);
													}
												else if(is_user_logged_in() && ($user = wp_get_current_user()) && ($user_id = $user->ID)) // Logged in?
													{
														if(($cur__subscr_id = get_user_option("s2member_subscr_id"))) // Does the customer have a Billing Profile?
															{
																if(($paypal = c_ws_plugin__s2member_pro_paypal_utilities::payflow_get_profile($cur__subscr_id)) && $paypal["TENDER"] !== "P" && preg_match("/^(Active|ActiveProfile)$/i", $paypal["STATUS"]))
																	{
																		$paypal = array(); // Reset the PayPal array.

																		$paypal["TRXTYPE"] = "R";
																		$paypal["ACTION"] = "M";
																		$paypal["ORIGPROFILEID"] = $cur__subscr_id;

																		$paypal["EMAIL"] = $user->user_email;
																		$paypal["FIRSTNAME"] = $user->first_name;
																		$paypal["LASTNAME"] = $user->last_name;

																		$paypal["TENDER"] = "C";
																		$paypal["ACCT"] = preg_replace("/[^0-9]/", "", $post_vars["card_number"]);
																		if(preg_match("/^(?P<month>[0-9]{2})\/[0-9]{2}(?P<year_suffix>[0-9]{2})$/", $post_vars["card_expiration"], $_m))
																			$paypal["EXPDATE"] = $_m["month"].$_m["year_suffix"];
																		$paypal["CVV2"] = $post_vars["card_verification"];

																		if(in_array($post_vars["card_type"], array("Maestro", "Solo")))
																			{
																				if(preg_match("/^(?P<month>[0-9]{2})\/[0-9]{2}(?P<year>[0-9]{2})$/", $post_vars["card_start_date_issue_number"], $_m))
																					$paypal["CARDSTART"] = $_m["month"].$_m["year"];
																				else $paypal["CARDISSUE"] = $post_vars["card_start_date_issue_number"];
																				unset /* A little housekeeping. */($_m);
																			}
																		$paypal["STREET"] = $post_vars["street"];
																		$paypal["CITY"] = $post_vars["city"];
																		$paypal["STATE"] = $post_vars["state"];
																		$paypal["COUNTRY"] = $post_vars["country"];
																		$paypal["ZIP"] = $post_vars["zip"];

																		if(($paypal = c_ws_plugin__s2member_paypal_utilities::paypal_payflow_api_response($paypal)) && empty($paypal["__error"]))
																			{
																				$global_response = array("response" => _x('<strong>Confirmed.</strong> Your billing information has been updated.', "s2member-front", "s2member"));

																				if($post_vars["attr"]["success"] && ($custom_success_url = str_ireplace(array("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array(urlencode(c_ws_plugin__s2member_utils_encryption::encrypt($global_response["response"])), urlencode($global_response["response"])), $post_vars["attr"]["success"])) && ($custom_success_url = trim(preg_replace("/%%(.+?)%%/i", "", $custom_success_url))))
																					wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig($custom_success_url, "s2p-v")).exit();
																			}
																		else // Else, an error.
																			{
																				$global_response = array("response" => $paypal["__error"], "error" => true);
																			}
																	}
																else if($paypal && $paypal["TENDER"] !== "P" && !preg_match("/^(Active|ActiveProfile)$/i", $paypal["STATUS"]))
																	{
																		$global_response = array("response" => _x('<strong>Unable to update.</strong> You have NO recurring fees. Or, your billing profile is no longer active. Please contact Support if you need assistance.', "s2member-front", "s2member"), "error" => true);
																	}
																else if($paypal && $paypal["TENDER"] === "P") // They used a PayPal account?
																	{
																		$global_response = array("response" => sprintf(_x('Please <a href="%s" rel="nofollow">log in at PayPal</a> to update your billing information.', "s2member-front", "s2member"), esc_attr("https://".(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_sandbox"]) ? "www.sandbox.paypal.com" : "www.paypal.com")."/")), "error" => true);
																	}
																else $global_response = array("response" => _x('<strong>Unknown error.</strong> Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
															}
														else // Else, an error.
															{
																$global_response = array("response" => _x('<strong>No Subscr. ID.</strong> Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
															}
													}
												else // Else, an error.
													{
														$global_response = array("response" => _x('You\'re <strong>NOT</strong> logged in.', "s2member-front", "s2member"), "error" => true);
													}
											}
										else // Else, an error.
											{
												$global_response = $error;
											}
									}
							}
					}
			}
	}
