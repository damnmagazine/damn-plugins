<?php
/**
* PayPal Specific Post/Page Forms (inner processing routines).
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

if(!class_exists("c_ws_plugin__s2member_pro_paypal_sp_checkout_in"))
	{
		/**
		* PayPal Specific Post/Page Forms (inner processing routines).
		*
		* @package s2Member\PayPal
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_paypal_sp_checkout_in
			{
				/**
				* Handles processing of Pro-Forms for Specific Post/Page checkout.
				*
				* @package s2Member\PayPal
				* @since 1.5
				*
				* @attaches-to ``add_action("init");``
				*
				* @return null Or exits script execution after a custom URL redirection; or upon Express Checkout redirection.
				*/
				public static function sp_checkout()
					{
						if((!empty($_POST["s2member_pro_paypal_sp_checkout"]["nonce"]) && ($nonce = $_POST["s2member_pro_paypal_sp_checkout"]["nonce"]) && wp_verify_nonce($nonce, "s2member-pro-paypal-sp-checkout"))
						|| (!empty($_GET["s2member_paypal_xco"]) && $_GET["s2member_paypal_xco"] === "s2member_pro_paypal_sp_checkout_return" //  PayPal Express Checkout with $_GET["token"] & $_GET["PayerID"].
						&& !empty($_GET["token"]) && ($_GET["token"] = esc_html($_GET["token"])) && (empty($_GET["PayerID"]) || ($_GET["PayerID"] = esc_html($_GET["PayerID"]))) // PayerID is not required.
						&& ($xco_post_vars = get_transient("s2m_".md5("s2member_transient_express_checkout_".$_GET["token"])))))
							{
								$GLOBALS["ws_plugin__s2member_pro_paypal_sp_checkout_response"] = array(); // This holds the global response details.
								$global_response = &$GLOBALS["ws_plugin__s2member_pro_paypal_sp_checkout_response"]; // This is a shorter reference.

								if(!empty($xco_post_vars)) // A customer is returning from Express Checkout @ PayPal?
									$_POST = $xco_post_vars; // POST vars from submission prior to Express Checkout.

								$post_vars           = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST["s2member_pro_paypal_sp_checkout"]));
								$post_vars["attr"]   = (!empty($post_vars["attr"])) ? (array)unserialize(c_ws_plugin__s2member_utils_encryption::decrypt($post_vars["attr"])) : array();
								$post_vars["attr"]   = apply_filters("ws_plugin__s2member_pro_paypal_sp_checkout_post_attr", $post_vars["attr"], get_defined_vars());
								if(!empty($xco_post_vars)) $post_vars["attr"]["captcha"] = "0"; // No need to revalidate captcha in this case.

								$post_vars["name"] = trim($post_vars["first_name"]." ".$post_vars["last_name"]);
								$post_vars["email"] = apply_filters("user_registration_email", sanitize_email($post_vars["email"]), get_defined_vars());

								if(empty($post_vars["card_expiration"]) && isset($post_vars["card_expiration_month"], $post_vars["card_expiration_year"]))
									$post_vars["card_expiration"] = $post_vars["card_expiration_month"]."/".$post_vars["card_expiration_year"];

								$post_vars = c_ws_plugin__s2member_utils_captchas::recaptcha_post_vars($post_vars); // Collect reCAPTCHA™ post vars.

								(!empty($_GET["token"])) ? delete_transient("s2m_".md5("s2member_transient_express_checkout_".$_GET["token"])) : null;

								if(!c_ws_plugin__s2member_pro_paypal_responses::paypal_form_attr_validation_errors($post_vars["attr"])) // Attr errors?
									{
										if(!($error = c_ws_plugin__s2member_pro_paypal_responses::paypal_form_submission_validation_errors("sp-checkout", $post_vars)))
											{
												$cp_attr = c_ws_plugin__s2member_pro_paypal_utilities::paypal_apply_coupon($post_vars["attr"], $post_vars["coupon"], "attr", array("affiliates-silent-post"));
												$cp_2gbp_attr = c_ws_plugin__s2member_pro_paypal_utilities::paypal_maestro_solo_2gbp( /* Now we use the new array of ``$cp_attr``. */$cp_attr, $post_vars["card_type"]);
												$cost_calculations = c_ws_plugin__s2member_pro_paypal_utilities::paypal_cost(null, $cp_2gbp_attr["ra"], $post_vars["state"], $post_vars["country"], $post_vars["zip"], $cp_2gbp_attr["cc"], $cp_2gbp_attr["desc"]);

												if(empty($_GET["s2member_paypal_xco"]) && $post_vars["card_type"] === "PayPal" && $cost_calculations["total"] > 0)
													{
														$return_url = $cancel_url = (is_ssl()) ? "https://" : "http://";
														$return_url = $cancel_url = ($return_url = $cancel_url).$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
														$return_url = $cancel_url = /* Ditch. */ remove_query_arg(array("token", "PayerID"), ($return_url = $cancel_url));
														$return_url = add_query_arg("s2member_paypal_xco", urlencode("s2member_pro_paypal_sp_checkout_return"), $return_url);
														$cancel_url = add_query_arg("s2member_paypal_xco", urlencode("s2member_pro_paypal_sp_checkout_cancel"), $cancel_url);

														$user = (is_user_logged_in() && is_object($user = wp_get_current_user()) && ($user_id = $user->ID)) ? $user : false;

														$post_vars["attr"]["invoice"] = uniqid()."~".$_SERVER["REMOTE_ADDR"]; // Unique invoice w/ IP address too.

														if(!($paypal_set_xco = array())) // PayPal Express Checkout.
															{
																$paypal_set_xco["METHOD"] = "SetExpressCheckout";

																$paypal_set_xco["RETURNURL"] = $return_url;
																$paypal_set_xco["CANCELURL"] = $cancel_url;

																$paypal_set_xco["PAGESTYLE"] = $post_vars["attr"]["ps"];
																$paypal_set_xco["LOCALECODE"] = $post_vars["attr"]["lc"];
																$paypal_set_xco["NOSHIPPING"] = $post_vars["attr"]["ns"];
																$paypal_set_xco["SOLUTIONTYPE"] = "Sole";
																$paypal_set_xco["LANDINGPAGE"] = "Billing";
																$paypal_set_xco["ALLOWNOTE"] = "0";

																$paypal_set_xco["PAYMENTREQUEST_0_PAYMENTACTION"] = "Sale";

																$paypal_set_xco["MAXAMT"] = $cost_calculations["total"];

																$paypal_set_xco["PAYMENTREQUEST_0_DESC"] = $cost_calculations["desc"];
																$paypal_set_xco["PAYMENTREQUEST_0_CUSTOM"] = $post_vars["attr"]["custom"];
																$paypal_set_xco["PAYMENTREQUEST_0_INVNUM"] = $post_vars["attr"]["invoice"];

																$paypal_set_xco["PAYMENTREQUEST_0_CURRENCYCODE"] = $cost_calculations["cur"];
																$paypal_set_xco["PAYMENTREQUEST_0_ITEMAMT"] = $cost_calculations["sub_total"];
																$paypal_set_xco["PAYMENTREQUEST_0_TAXAMT"] = $cost_calculations["tax"];
																$paypal_set_xco["PAYMENTREQUEST_0_AMT"] = $cost_calculations["total"];

																$paypal_set_xco["L_PAYMENTREQUEST_0_QTY0"] = "1"; // Always (1).
																$paypal_set_xco["L_PAYMENTREQUEST_0_NAME0"] = $cost_calculations["desc"];
																$paypal_set_xco["L_PAYMENTREQUEST_0_NUMBER0"] = $post_vars["attr"]["sp_ids_exp"];
																$paypal_set_xco["L_PAYMENTREQUEST_0_AMT0"] = $cost_calculations["sub_total"];

																$paypal_set_xco["PAYMENTREQUEST_0_SHIPTONAME"] = $post_vars["name"];
																$paypal_set_xco["PAYMENTREQUEST_0_SHIPTOSTREET"] = $post_vars["street"];
																$paypal_set_xco["PAYMENTREQUEST_0_SHIPTOCITY"] = $post_vars["city"];
																$paypal_set_xco["PAYMENTREQUEST_0_SHIPTOSTATE"] = $post_vars["state"];
																$paypal_set_xco["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"] = $post_vars["country"];
																$paypal_set_xco["PAYMENTREQUEST_0_SHIPTOZIP"] = $post_vars["zip"];

																$paypal_set_xco["EMAIL"] = $post_vars["email"];
															}

														if(($paypal_set_xco = c_ws_plugin__s2member_paypal_utilities::paypal_api_response($paypal_set_xco)) && empty($paypal_set_xco["__error"]))
															{
																set_transient("s2m_".md5("s2member_transient_express_checkout_".$paypal_set_xco["TOKEN"]), $_POST, 10800);

																$endpoint = ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_sandbox"]) ? "www.sandbox.paypal.com" : "www.paypal.com";

																wp_redirect(add_query_arg("token", urlencode($paypal_set_xco["TOKEN"]), "https://".$endpoint."/cgi-bin/webscr?cmd=_express-checkout"));

																exit(); // Clean exit.
															}
														else // Else, an error.
															{
																$global_response = array("response" => $paypal_set_xco["__error"], "error" => true);
															}
													}
												else // Else we're good. Now ready to process this "Buy Now" transaction.
													{
														if(empty($post_vars["attr"]["invoice"])) // Only if it's empty.
															$post_vars["attr"]["invoice"] = uniqid()."~".$_SERVER["REMOTE_ADDR"];

														if(!($paypal = array())) // Build a simple "Buy Now" request.
															{
																if(!empty($_GET["s2member_paypal_xco"]) && $_GET["s2member_paypal_xco"] === "s2member_pro_paypal_sp_checkout_return" && !empty($_GET["token"]) && ($paypal_xco_details = array("METHOD" => "GetExpressCheckoutDetails", "TOKEN" => $_GET["token"])) && ($paypal_xco_details = c_ws_plugin__s2member_paypal_utilities::paypal_api_response($paypal_xco_details)) && empty($paypal_xco_details["__error"]))
																	{
																		$paypal["METHOD"] = "DoExpressCheckoutPayment";

																		$paypal["TOKEN"] = $paypal_xco_details["TOKEN"];
																		$paypal["PAYERID"] = $paypal_xco_details["PAYERID"];

																		$paypal["PAYMENTREQUEST_0_PAYMENTACTION"] = "Sale";

																		$paypal["PAYMENTREQUEST_0_DESC"] = $cost_calculations["desc"];
																		$paypal["PAYMENTREQUEST_0_CUSTOM"] = $post_vars["attr"]["custom"];
																		$paypal["PAYMENTREQUEST_0_INVNUM"] = $post_vars["attr"]["invoice"];

																		$paypal["PAYMENTREQUEST_0_CURRENCYCODE"] = $cost_calculations["cur"];
																		$paypal["PAYMENTREQUEST_0_ITEMAMT"] = $cost_calculations["sub_total"];
																		$paypal["PAYMENTREQUEST_0_TAXAMT"] = $cost_calculations["tax"];
																		$paypal["PAYMENTREQUEST_0_AMT"] = $cost_calculations["total"];

																		$paypal["L_PAYMENTREQUEST_0_QTY0"] = "1"; // Always (1).
																		$paypal["L_PAYMENTREQUEST_0_NAME0"] = $cost_calculations["desc"];
																		$paypal["L_PAYMENTREQUEST_0_NUMBER0"] = $post_vars["attr"]["sp_ids_exp"];
																		$paypal["L_PAYMENTREQUEST_0_AMT0"] = $cost_calculations["sub_total"];
																	}
																else // NOT using PayPal Express Checkout.
																	{
																		$paypal["METHOD"] = "DoDirectPayment";
																		$paypal["PAYMENTACTION"] = "Sale";

																		$paypal["EMAIL"] = $post_vars["email"];
																		$paypal["FIRSTNAME"] = $post_vars["first_name"];
																		$paypal["LASTNAME"] = $post_vars["last_name"];
																		$paypal["IPADDRESS"] = $_SERVER["REMOTE_ADDR"];

																		$paypal["DESC"] = $cost_calculations["desc"];
																		$paypal["CUSTOM"] = $post_vars["attr"]["custom"];
																		$paypal["INVNUM"] = $post_vars["attr"]["invoice"];

																		$paypal["CURRENCYCODE"] = $cost_calculations["cur"];
																		$paypal["ITEMAMT"] = $cost_calculations["sub_total"];
																		$paypal["TAXAMT"] = $cost_calculations["tax"];
																		$paypal["AMT"] = $cost_calculations["total"];

																		$paypal["L_QTY0"] = "1"; // Always (1).
																		$paypal["L_NAME0"] = $cost_calculations["desc"];
																		$paypal["L_NUMBER0"] = $post_vars["attr"]["sp_ids_exp"];
																		$paypal["L_AMT0"] = $cost_calculations["sub_total"];

																		$paypal["CREDITCARDTYPE"] = $post_vars["card_type"];
																		$paypal["ACCT"] = preg_replace("/[^0-9]/", "", $post_vars["card_number"]);
																		$paypal["EXPDATE"] = preg_replace("/[^0-9]/", "", $post_vars["card_expiration"]);
																		$paypal["CVV2"] = $post_vars["card_verification"];

																		if(in_array($post_vars["card_type"], array("Maestro", "Solo")))
																			if(preg_match("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
																				$paypal["STARTDATE"] = preg_replace("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
																			else // Otherwise, we assume they provided an Issue Number instead.
																			$paypal["ISSUENUMBER"] = $post_vars["card_start_date_issue_number"];

																		$paypal["STREET"] = $post_vars["street"];
																		$paypal["CITY"] = $post_vars["city"];
																		$paypal["STATE"] = $post_vars["state"];
																		$paypal["COUNTRYCODE"] = $post_vars["country"];
																		$paypal["ZIP"] = $post_vars["zip"];
																	}
															}
														if($cost_calculations["total"] <= 0 || (($paypal = c_ws_plugin__s2member_paypal_utilities::paypal_api_response($paypal)) && empty($paypal["__error"])))
															{
																if($cost_calculations["total"] <= 0) $new__txn_id = strtoupper('free-'.uniqid()); // Auto-generated value in this case.

																else // We handle this normally. The transaction ID comes from PayPal as it always does.
																	{
																		$new__txn_id = (!empty($paypal["PAYMENTINFO_0_TRANSACTIONID"])) ? $paypal["PAYMENTINFO_0_TRANSACTIONID"] : false;
																		$new__txn_id = (!$new__txn_id && !empty($paypal["TRANSACTIONID"])) ? $paypal["TRANSACTIONID"] : $new__txn_id;
																	}
																if(!($ipn = array())) // Simulated PayPal IPN.
																	{
																		$ipn["txn_type"] = "web_accept";
																		$ipn["txn_id"] = $new__txn_id;
																		$ipn["custom"] = $post_vars["attr"]["custom"];
																		$ipn["invoice"] = $post_vars["attr"]["invoice"];

																		$ipn["mc_gross"] = $cost_calculations["total"];
																		$ipn["mc_currency"] = $cost_calculations["cur"];
																		$ipn["tax"] = $cost_calculations["tax"];

																		$ipn["payer_email"] = $post_vars["email"];
																		$ipn["first_name"] = $post_vars["first_name"];
																		$ipn["last_name"] = $post_vars["last_name"];

																		if(is_user_logged_in() && // Reference a User/Member?
																		($referencing = c_ws_plugin__s2member_utils_users::get_user_subscr_or_wp_id()))
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

																		$ipn["s2member_paypal_proxy"] = "paypal";
																		$ipn["s2member_paypal_proxy_use"] = "pro-emails";
																		$ipn["s2member_paypal_proxy_coupon"] = array("coupon_code" => $cp_attr["_coupon_code"], "full_coupon_code" => $cp_attr["_full_coupon_code"], "affiliate_id" => $cp_attr["_coupon_affiliate_id"]);
																		$ipn["s2member_paypal_proxy_verification"] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();
																		$ipn["s2member_paypal_proxy_return_url"] = $post_vars["attr"]["success"];

																		$ipn["s2member_paypal_proxy_return_url"] = trim(c_ws_plugin__s2member_utils_urls::remote(home_url("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20)));
																	}
																if(($sp_access_url = c_ws_plugin__s2member_sp_access::sp_access_link_gen($post_vars["attr"]["ids"], $post_vars["attr"]["exp"])))
																	{
																		setcookie("s2member_sp_tracking", ($s2member_sp_tracking = c_ws_plugin__s2member_utils_encryption::encrypt($new__txn_id)), time() + 31556926, COOKIEPATH, COOKIE_DOMAIN).setcookie("s2member_sp_tracking", $s2member_sp_tracking, time() + 31556926, SITECOOKIEPATH, COOKIE_DOMAIN).($_COOKIE["s2member_sp_tracking"] = $s2member_sp_tracking);

																		$global_response = array("response" => sprintf(_x('<strong>Thank you.</strong> Your purchase has been approved.<br />&mdash; Please <a href="%s" rel="nofollow">click here</a> to proceed.', "s2member-front", "s2member"), esc_attr($sp_access_url)));

																		if($post_vars["attr"]["success"] && substr($ipn["s2member_paypal_proxy_return_url"], 0, 2) === substr($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace(array("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array(urlencode(c_ws_plugin__s2member_utils_encryption::encrypt($global_response["response"])), urlencode($global_response["response"])), $ipn["s2member_paypal_proxy_return_url"])) && ($custom_success_url = trim(preg_replace("/%%(.+?)%%/i", "", $custom_success_url))))
																			wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig($custom_success_url, "s2p-v")).exit();
																	}
																else // Else, unable to generate Access Link.
																	{
																		$global_response = array("response" => _x('<strong>Oops.</strong> Unable to generate Access Link. Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
																	}
															}
														else // Else, an error.
															{
																$global_response = array("response" => $paypal["__error"], "error" => true);
															}
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
