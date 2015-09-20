<?php
/**
* Authorize.Net Specific Post/Page Forms (inner processing routines).
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
* @package s2Member\AuthNet
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");

if (!class_exists ("c_ws_plugin__s2member_pro_authnet_sp_checkout_in"))
	{
		/**
		* Authorize.Net Specific Post/Page Forms (inner processing routines).
		*
		* @package s2Member\AuthNet
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_authnet_sp_checkout_in
			{
				/**
				* Handles processing of Pro-Forms for Specific Post/Page checkout.
				*
				* @package s2Member\AuthNet
				* @since 1.5
				*
				* @attaches-to ``add_action("init");``
				*
				* @return null Or exits script execution after a custom URL redirection.
				*/
				public static function authnet_sp_checkout ()
					{
						if (!empty($_POST["s2member_pro_authnet_sp_checkout"]["nonce"]) && ($nonce = $_POST["s2member_pro_authnet_sp_checkout"]["nonce"]) && wp_verify_nonce ($nonce, "s2member-pro-authnet-sp-checkout"))
							{
								$GLOBALS["ws_plugin__s2member_pro_authnet_sp_checkout_response"] = array(); // This holds the global response details.
								$global_response = &$GLOBALS["ws_plugin__s2member_pro_authnet_sp_checkout_response"]; // This is a shorter reference.

								$post_vars = c_ws_plugin__s2member_utils_strings::trim_deep (stripslashes_deep ($_POST["s2member_pro_authnet_sp_checkout"]));
								$post_vars["attr"]   = (!empty($post_vars["attr"])) ? (array)unserialize(c_ws_plugin__s2member_utils_encryption::decrypt($post_vars["attr"])) : array();
								$post_vars["attr"] = apply_filters("ws_plugin__s2member_pro_authnet_sp_checkout_post_attr", $post_vars["attr"], get_defined_vars ());

								$post_vars["name"] = trim ($post_vars["first_name"] . " " . $post_vars["last_name"]);
								$post_vars["email"] = apply_filters("user_registration_email", sanitize_email ($post_vars["email"]), get_defined_vars ());

								if(empty($post_vars["card_expiration"]) && isset($post_vars["card_expiration_month"], $post_vars["card_expiration_year"]))
									$post_vars["card_expiration"] = $post_vars["card_expiration_month"]."/".$post_vars["card_expiration_year"];

								$post_vars = c_ws_plugin__s2member_utils_captchas::recaptcha_post_vars($post_vars); // Collect reCAPTCHA™ post vars.

								if (!c_ws_plugin__s2member_pro_authnet_responses::authnet_form_attr_validation_errors ($post_vars["attr"])) // Attr errors?
									{
										if (!($error = c_ws_plugin__s2member_pro_authnet_responses::authnet_form_submission_validation_errors ("sp-checkout", $post_vars)))
											{
												$cp_attr = c_ws_plugin__s2member_pro_authnet_utilities::authnet_apply_coupon ($post_vars["attr"], $post_vars["coupon"], "attr", array("affiliates-silent-post"));
												$cost_calculations = c_ws_plugin__s2member_pro_authnet_utilities::authnet_cost (null, $cp_attr["ra"], $post_vars["state"], $post_vars["country"], $post_vars["zip"], $cp_attr["cc"], $cp_attr["desc"]);

												if (!($authnet = array())) // Direct payments.
													{
														$authnet["x_type"] = "AUTH_CAPTURE";
														$authnet["x_method"] = "CC";

														$authnet["x_email"] = $post_vars["email"];
														$authnet["x_first_name"] = $post_vars["first_name"];
														$authnet["x_last_name"] = $post_vars["last_name"];
														$authnet["x_customer_ip"] = $_SERVER["REMOTE_ADDR"];

														$authnet["x_invoice_num"] = "s2-" . uniqid ();
														$authnet["x_description"] = $cost_calculations["desc"];

														$authnet["s2_invoice"] = $post_vars["attr"]["sp_ids_exp"];
														$authnet["s2_custom"] = $post_vars["attr"]["custom"];

														$authnet["x_tax"] = $cost_calculations["tax"];
														$authnet["x_amount"] = $cost_calculations["total"];
														$authnet["x_currency_code"] = $cost_calculations["cur"];

														$authnet["x_card_num"] = preg_replace ("/[^0-9]/", "", $post_vars["card_number"]);
														$authnet["x_exp_date"] = c_ws_plugin__s2member_pro_authnet_utilities::authnet_exp_date ($post_vars["card_expiration"], 'aim');
														$authnet["x_card_code"] = $post_vars["card_verification"];

														#if (in_array($post_vars["card_type"], array("Maestro", "Solo")))
														#	if (preg_match ("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
														#		$authnet["x_card_start_date"] = preg_replace ("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
														#	else // Otherwise, we assume they provided an issue number instead.
														#		$authnet["x_card_issue_number"] = $post_vars["card_start_date_issue_number"];

														$authnet["x_address"] = $post_vars["street"];
														$authnet["x_city"] = $post_vars["city"];
														$authnet["x_state"] = $post_vars["state"];
														$authnet["x_country"] = $post_vars["country"];
														$authnet["x_zip"] = $post_vars["zip"];
													}
												if ($cost_calculations["total"] <= 0 || (($authnet = c_ws_plugin__s2member_pro_authnet_utilities::authnet_aim_response ($authnet)) && empty($authnet["__error"])))
													{
														if($cost_calculations["total"] <= 0)
															$new__txn_id = strtoupper('free-'.uniqid()); // Auto-generated value in this case.
														else $new__txn_id = $authnet["transaction_id"];

														if (!($ipn = array())) // Simulated PayPal IPN.
															{
																$ipn["txn_type"] = "web_accept";
																$ipn["txn_id"] = $new__txn_id;
																$ipn["custom"] = $post_vars["attr"]["custom"];

																$ipn["mc_gross"] = $cost_calculations["total"];
																$ipn["mc_currency"] = $cost_calculations["cur"];
																$ipn["tax"] = $cost_calculations["tax"];

																$ipn["payer_email"] = $post_vars["email"];
																$ipn["first_name"] = $post_vars["first_name"];
																$ipn["last_name"] = $post_vars["last_name"];

																if (is_user_logged_in () && // Reference a User/Member?
																($referencing = c_ws_plugin__s2member_utils_users::get_user_subscr_or_wp_id ()))
																	{
																		$ipn["option_name1"] = "Referencing Customer ID";
																		$ipn["option_selection1"] = $referencing;
																	}
																else // Otherwise, default to the originating domain.
																	{
																		$ipn["option_name1"] = "Originating Domain";
																		$ipn["option_selection1"] = $_SERVER["HTTP_HOST"];
																	}

																$ipn["option_name2"] = "Customer IP Address";
																$ipn["option_selection2"] = $_SERVER["REMOTE_ADDR"];

																$ipn["item_name"] = $cost_calculations["desc"];
																$ipn["item_number"] = $post_vars["attr"]["sp_ids_exp"];

																$ipn["s2member_paypal_proxy"] = "authnet";
																$ipn["s2member_paypal_proxy_use"] = "pro-emails";
																$ipn["s2member_paypal_proxy_coupon"] = array("coupon_code" => $cp_attr["_coupon_code"], "full_coupon_code" => $cp_attr["_full_coupon_code"], "affiliate_id" => $cp_attr["_coupon_affiliate_id"]);
																$ipn["s2member_paypal_proxy_verification"] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();
																$ipn["s2member_paypal_proxy_return_url"] = $post_vars["attr"]["success"];

																$ipn["s2member_authnet_proxy_return_url"] = trim (c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20)));
															}
														if (($sp_access_url = c_ws_plugin__s2member_sp_access::sp_access_link_gen ($post_vars["attr"]["ids"], $post_vars["attr"]["exp"])))
															{
																setcookie ("s2member_sp_tracking", ($s2member_sp_tracking = c_ws_plugin__s2member_utils_encryption::encrypt ($new__txn_id)), time () + 31556926, COOKIEPATH, COOKIE_DOMAIN) . setcookie ("s2member_sp_tracking", $s2member_sp_tracking, time () + 31556926, SITECOOKIEPATH, COOKIE_DOMAIN) . ($_COOKIE["s2member_sp_tracking"] = $s2member_sp_tracking);

																$global_response = array("response" => sprintf (_x ('<strong>Thank you.</strong> Your purchase has been approved.<br />&mdash; Please <a href="%s" rel="nofollow">click here</a> to proceed.', "s2member-front", "s2member"), esc_attr ($sp_access_url)));

																if ($post_vars["attr"]["success"] && substr ($ipn["s2member_authnet_proxy_return_url"], 0, 2) === substr ($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace (array("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array(urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $ipn["s2member_authnet_proxy_return_url"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																	wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
															}
														else // Else, unable to generate Access Link.
															{
																$global_response = array("response" => _x ('<strong>Oops.</strong> Unable to generate Access Link. Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
															}
													}
												else // Else, an error.
													{
														$global_response = array("response" => $authnet["__error"], "error" => true);
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
