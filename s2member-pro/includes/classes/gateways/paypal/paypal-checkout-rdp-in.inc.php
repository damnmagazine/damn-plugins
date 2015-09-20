<?php
/**
* PayPal Checkout Forms (inner processing routines).
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

if (!class_exists ("c_ws_plugin__s2member_pro_paypal_checkout_rdp_in"))
	{
		/**
		* PayPal Checkout Forms (inner processing routines).
		*
		* This version handles Real-Time Direct Pay *(mission critical)*, when:
		* ``$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_paypal_checkout_rdp"] == true``.
		*
		* @package s2Member\PayPal
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_paypal_checkout_rdp_in
			{
				/**
				* Handles processing of Pro-Form checkouts.
				*
				* @package s2Member\PayPal
				* @since 1.5
				*
				* @attaches-to ``add_action("init");``
				*
				* @return null Or exits script execution after a custom URL redirection; or upon Express Checkout redirection.
				*/
				public static function paypal_checkout ()
					{
						if ((!empty($_POST["s2member_pro_paypal_checkout"]["nonce"]) && ($nonce = $_POST["s2member_pro_paypal_checkout"]["nonce"]) && wp_verify_nonce ($nonce, "s2member-pro-paypal-checkout"))
						|| (!empty($_GET["s2member_paypal_xco"]) && $_GET["s2member_paypal_xco"] === "s2member_pro_paypal_checkout_return" //  PayPal Express Checkout with $_GET["token"] & $_GET["PayerID"]?
						&& !empty($_GET["token"]) && ($_GET["token"] = esc_html ($_GET["token"])) && (empty($_GET["PayerID"]) || ($_GET["PayerID"] = esc_html ($_GET["PayerID"]))) // PayerID is not required.
						&& ($xco_post_vars = get_transient ("s2m_" . md5 ("s2member_transient_express_checkout_" . $_GET["token"])))))
							{
								$GLOBALS["ws_plugin__s2member_pro_paypal_checkout_response"] = array(); // This holds the global response details.
								$global_response = &$GLOBALS["ws_plugin__s2member_pro_paypal_checkout_response"]; // This is a shorter reference.

								if(!empty($xco_post_vars)) // A customer is returning from Express Checkout @ PayPal?
									$_POST = $xco_post_vars; // POST vars from submission prior to Express Checkout.

								$post_vars           = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST["s2member_pro_paypal_checkout"]));
								$post_vars["attr"]   = (!empty($post_vars["attr"])) ? (array)unserialize(c_ws_plugin__s2member_utils_encryption::decrypt($post_vars["attr"])) : array();
								$post_vars["attr"]   = apply_filters("ws_plugin__s2member_pro_paypal_checkout_post_attr", $post_vars["attr"], get_defined_vars());
								if(!empty($xco_post_vars)) $post_vars["attr"]["captcha"] = "0"; // No need to revalidate captcha in this case.

								$post_vars["name"] = trim ($post_vars["first_name"] . " " . $post_vars["last_name"]);
								$post_vars["email"] = apply_filters("user_registration_email", sanitize_email (@$post_vars["email"]), get_defined_vars ());
								$post_vars["username"] = (is_multisite()) ? strtolower(@$post_vars["username"]) : @$post_vars["username"]; // Force lowercase.
								$post_vars["username"] = sanitize_user (($post_vars["_o_username"] = $post_vars["username"]), is_multisite ());

								if(empty($post_vars["card_expiration"]) && isset($post_vars["card_expiration_month"], $post_vars["card_expiration_year"]))
									$post_vars["card_expiration"] = $post_vars["card_expiration_month"]."/".$post_vars["card_expiration_year"];

								$post_vars = c_ws_plugin__s2member_utils_captchas::recaptcha_post_vars($post_vars); // Collect reCAPTCHA™ post vars.

								if(!empty($_GET["token"])) delete_transient("s2m_" . md5 ("s2member_transient_express_checkout_" . $_GET["token"]));

								if (!c_ws_plugin__s2member_pro_paypal_responses::paypal_form_attr_validation_errors ($post_vars["attr"])) // Attr errors?
									{
										if (!($error = c_ws_plugin__s2member_pro_paypal_responses::paypal_form_submission_validation_errors ("checkout", $post_vars)))
											{
												$cp_attr = c_ws_plugin__s2member_pro_paypal_utilities::paypal_apply_coupon ($post_vars["attr"], $post_vars["coupon"], "attr", array("affiliates-silent-post"));
												$cp_2gbp_attr = c_ws_plugin__s2member_pro_paypal_utilities::paypal_maestro_solo_2gbp ( /* Now we use the new array of ``$cp_attr``. */$cp_attr, $post_vars["card_type"]);
												$cost_calculations = c_ws_plugin__s2member_pro_paypal_utilities::paypal_cost ($cp_2gbp_attr["ta"], $cp_2gbp_attr["ra"], $post_vars["state"], $post_vars["country"], $post_vars["zip"], $cp_2gbp_attr["cc"], $cp_2gbp_attr["desc"]);

												if($cost_calculations["total"] <= 0 && $post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0)
													{
														$post_vars["attr"]["tp"] = "0"; // Ditch the trial period completely.
														$cost_calculations["sub_total"] = $cost_calculations["trial_sub_total"]; // Use as regular sub-total (ditch trial sub-total).
														$cost_calculations["tax"] = $cost_calculations["trial_tax"]; // Use as regular tax (ditch trial tax).
														$cost_calculations["tax_per"] = $cost_calculations["trial_tax_per"]; // Use as regular tax (ditch trial tax).
														$cost_calculations["total"] = $cost_calculations["trial_total"]; // Use as regular total (ditch trial).
														$cost_calculations["trial_sub_total"] = "0.00"; // Ditch the initial total (using as grand total).
														$cost_calculations["trial_tax"] = "0.00"; // Ditch this calculation now also.
														$cost_calculations["trial_tax_per"] = ""; // Ditch this calculation now also.
														$cost_calculations["trial_total"] = "0.00"; // Ditch this calculation now also.
													}
												$use_recurring_profile = ($post_vars["attr"]["rr"] === "BN" || (!$post_vars["attr"]["tp"] && !$post_vars["attr"]["rr"])) ? false : true;
												$is_independent_ccaps_sale = ($post_vars["attr"]["level"] === "*") ? true : false; // Selling Independent Custom Capabilities?

												if($use_recurring_profile && $cost_calculations["trial_total"] <= 0 && $cost_calculations["total"] <= 0)
													{
														if(!$post_vars["attr"]["rr"] && $post_vars["attr"]["rt"] !== "L")
															{
																if(substr_count($post_vars["attr"]["level_ccaps_eotper"], ":") === 1)
																	$post_vars["attr"]["level_ccaps_eotper"] .= ":".$post_vars["attr"]["rp"]." ".$post_vars["attr"]["rt"];
																else if(substr_count($post_vars["attr"]["level_ccaps_eotper"], ":") === 0)
																	$post_vars["attr"]["level_ccaps_eotper"] .= "::".$post_vars["attr"]["rp"]." ".$post_vars["attr"]["rt"];
															}
														else if($post_vars["attr"]["rr"] && $post_vars["attr"]["rrt"] && $post_vars["attr"]["rt"] !== "L")
															{
																if(substr_count($post_vars["attr"]["level_ccaps_eotper"], ":") === 1)
																	$post_vars["attr"]["level_ccaps_eotper"] .= ":".($post_vars["attr"]["rp"] * $post_vars["attr"]["rrt"])." ".$post_vars["attr"]["rt"];
																else if(substr_count($post_vars["attr"]["level_ccaps_eotper"], ":") === 0)
																	$post_vars["attr"]["level_ccaps_eotper"] .= "::".($post_vars["attr"]["rp"] * $post_vars["attr"]["rrt"])." ".$post_vars["attr"]["rt"];
															}
													}
												if (empty($_GET["s2member_paypal_xco"]) && $post_vars["card_type"] === "PayPal" && ($cost_calculations["trial_total"] > 0 || $cost_calculations["total"] > 0))
													{
														$return_url = $cancel_url = (is_ssl ()) ? "https://" : "http://";
														$return_url = $cancel_url = ($return_url = $cancel_url) . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
														$return_url = $cancel_url = /* Ditch. */ remove_query_arg (array("token", "PayerID", "s2p-option"), ($return_url = $cancel_url));

														$return_url = add_query_arg("s2p-option", urlencode((string)@$_REQUEST["s2p-option"]), $return_url);
														$return_url = add_query_arg("s2member_paypal_xco", urlencode("s2member_pro_paypal_checkout_return"), $return_url);

														$cancel_url = add_query_arg("s2p-option", urlencode((string)@$_REQUEST["s2p-option"]), $cancel_url);
														$cancel_url = add_query_arg("s2member_paypal_xco", urlencode("s2member_pro_paypal_checkout_cancel"), $cancel_url);

														$user = (is_user_logged_in () && is_object ($user = wp_get_current_user ()) && ($user_id = $user->ID)) ? $user : false;

														if (!($paypal_set_xco = array())) // PayPal Express Checkout.
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

																if ($use_recurring_profile) // Recurring Profile?
																	{
																		$paypal_set_xco["L_BILLINGTYPE0"] = "RecurringPayments";
																		$paypal_set_xco["L_BILLINGAGREEMENTDESCRIPTION0"] = $cost_calculations["desc"];

																		if($post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0)
																			$paypal_set_xco["MAXAMT"] = $cost_calculations["trial_total"];
																		else $paypal_set_xco["MAXAMT"] = $cost_calculations["total"];

																		$paypal_set_xco["PAYMENTREQUEST_0_CURRENCYCODE"] = $cost_calculations["cur"];

																		if (!$post_vars["attr"]["tp"] || ($post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0))
																			{
																				$paypal_set_xco["PAYMENTREQUEST_0_PAYMENTACTION"] = "Sale";

																				$paypal_set_xco["PAYMENTREQUEST_0_DESC"] = $cost_calculations["desc"];
																				$paypal_set_xco["PAYMENTREQUEST_0_CUSTOM"] = $post_vars["attr"]["custom"];

																				if ($post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0)
																					{
																						$paypal_set_xco["PAYMENTREQUEST_0_CURRENCYCODE"] = $cost_calculations["cur"];
																						$paypal_set_xco["PAYMENTREQUEST_0_ITEMAMT"] = $cost_calculations["trial_sub_total"];
																						$paypal_set_xco["PAYMENTREQUEST_0_TAXAMT"] = $cost_calculations["trial_tax"];
																						$paypal_set_xco["PAYMENTREQUEST_0_AMT"] = $cost_calculations["trial_total"];

																						$paypal_set_xco["L_PAYMENTREQUEST_0_QTY0"] = "1"; // Always (1).
																						$paypal_set_xco["L_PAYMENTREQUEST_0_NAME0"] = $cost_calculations["desc"];
																						$paypal_set_xco["L_PAYMENTREQUEST_0_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																						$paypal_set_xco["L_PAYMENTREQUEST_0_AMT0"] = $cost_calculations["trial_sub_total"];
																					}
																				else // Else we need to charge the first Regular payment in the billing cycle.
																					{
																						$paypal_set_xco["PAYMENTREQUEST_0_CURRENCYCODE"] = $cost_calculations["cur"];
																						$paypal_set_xco["PAYMENTREQUEST_0_ITEMAMT"] = $cost_calculations["sub_total"];
																						$paypal_set_xco["PAYMENTREQUEST_0_TAXAMT"] = $cost_calculations["tax"];
																						$paypal_set_xco["PAYMENTREQUEST_0_AMT"] = $cost_calculations["total"];

																						$paypal_set_xco["L_PAYMENTREQUEST_0_QTY0"] = "1"; // Always (1).
																						$paypal_set_xco["L_PAYMENTREQUEST_0_NAME0"] = $cost_calculations["desc"];
																						$paypal_set_xco["L_PAYMENTREQUEST_0_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																						$paypal_set_xco["L_PAYMENTREQUEST_0_AMT0"] = $cost_calculations["sub_total"];
																					}
																			}
																	}
																else // Otherwise, we use fields for a "Buy Now" purchase.
																	{
																		$paypal_set_xco["PAYMENTREQUEST_0_PAYMENTACTION"] = "Sale";

																		$paypal_set_xco["MAXAMT"] = $cost_calculations["total"];

																		$paypal_set_xco["PAYMENTREQUEST_0_DESC"] = $cost_calculations["desc"];
																		$paypal_set_xco["PAYMENTREQUEST_0_CUSTOM"] = $post_vars["attr"]["custom"];

																		$paypal_set_xco["PAYMENTREQUEST_0_CURRENCYCODE"] = $cost_calculations["cur"];
																		$paypal_set_xco["PAYMENTREQUEST_0_ITEMAMT"] = $cost_calculations["sub_total"];
																		$paypal_set_xco["PAYMENTREQUEST_0_TAXAMT"] = $cost_calculations["tax"];
																		$paypal_set_xco["PAYMENTREQUEST_0_AMT"] = $cost_calculations["total"];

																		$paypal_set_xco["L_PAYMENTREQUEST_0_QTY0"] = "1"; // Always (1).
																		$paypal_set_xco["L_PAYMENTREQUEST_0_NAME0"] = $cost_calculations["desc"];
																		$paypal_set_xco["L_PAYMENTREQUEST_0_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																		$paypal_set_xco["L_PAYMENTREQUEST_0_AMT0"] = $cost_calculations["sub_total"];
																	}
																$paypal_set_xco["PAYMENTREQUEST_0_SHIPTONAME"] = $post_vars["name"];
																$paypal_set_xco["PAYMENTREQUEST_0_SHIPTOSTREET"] = $post_vars["street"];
																$paypal_set_xco["PAYMENTREQUEST_0_SHIPTOCITY"] = $post_vars["city"];
																$paypal_set_xco["PAYMENTREQUEST_0_SHIPTOSTATE"] = $post_vars["state"];
																$paypal_set_xco["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"] = $post_vars["country"];
																$paypal_set_xco["PAYMENTREQUEST_0_SHIPTOZIP"] = $post_vars["zip"];

																$paypal_set_xco["EMAIL"] = ($user) ? $user->user_email : $post_vars["email"];
															}
														if (($paypal_set_xco = c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($paypal_set_xco)) && empty($paypal_set_xco["__error"]))
															{
																set_transient ("s2m_" . md5 ("s2member_transient_express_checkout_" . $paypal_set_xco["TOKEN"]), $_POST, 10800);

																$endpoint = ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_sandbox"]) ? "www.sandbox.paypal.com" : "www.paypal.com";

																wp_redirect(add_query_arg ("token", urlencode ($paypal_set_xco["TOKEN"]), "https://" . $endpoint . "/cgi-bin/webscr?cmd=_express-checkout"));

																exit /* Clean exit. */ ();
															}
														else // Else, an error.
															{
																$global_response = array("response" => $paypal_set_xco["__error"], "error" => true);
															}
													}
												else if ($use_recurring_profile && is_user_logged_in () && is_object ($user = wp_get_current_user ()) && ($user_id = $user->ID))
													{
														if (!($cur__subscr_id = get_user_option ("s2member_subscr_id")) || !($paypal = c_ws_plugin__s2member_paypal_utilities::paypal_api_response (array("METHOD" => "GetRecurringPaymentsProfileDetails", "PROFILEID" => $cur__subscr_id))) || !empty($paypal["__error"]) || !preg_match ("/^(Pending|PendingProfile)$/i", $paypal["STATUS"]))
															{
																$period1 = c_ws_plugin__s2member_paypal_utilities::paypal_pro_period1 ($post_vars["attr"]["tp"] . " " . $post_vars["attr"]["tt"]);
																$period3 = c_ws_plugin__s2member_paypal_utilities::paypal_pro_period3 ($post_vars["attr"]["rp"] . " " . $post_vars["attr"]["rt"]);

																$start_time = ($post_vars["attr"]["tp"]) ? // If there's an Initial/Trial Period; start when it's over.
																c_ws_plugin__s2member_pro_paypal_utilities::paypal_start_time ($period1) : // After Trial is over.
																c_ws_plugin__s2member_pro_paypal_utilities::paypal_start_time ($period3); // Or next billing cycle.

																$reference = $start_time . ":" . $period1 . ":" . $period3 . "~" . $_SERVER["HTTP_HOST"] . "~" . $post_vars["attr"]["level_ccaps_eotper"];

																update_user_meta ($user_id, "first_name", $post_vars["first_name"]) . update_user_meta ($user_id, "last_name", $post_vars["last_name"]);

																if (!($_paypal = array()) // A first Initial/Trial payment?
																&& (!$post_vars["attr"]["tp"] || ($post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0)))
																	{
																		if (!empty($_GET["s2member_paypal_xco"]) && $_GET["s2member_paypal_xco"] === "s2member_pro_paypal_checkout_return" && !empty($_GET["token"]) && ($_paypal_xco_details = array("METHOD" => "GetExpressCheckoutDetails", "TOKEN" => $_GET["token"])) && ($_paypal_xco_details = c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($_paypal_xco_details)) && empty($_paypal_xco_details["__error"]))
																			{
																				$_paypal["METHOD"] = "DoExpressCheckoutPayment";

																				$_paypal["TOKEN"] = $_paypal_xco_details["TOKEN"];
																				$_paypal["PAYERID"] = $_paypal_xco_details["PAYERID"];

																				$_paypal["PAYMENTREQUEST_0_PAYMENTACTION"] = "Sale";

																				$_paypal["PAYMENTREQUEST_0_DESC"] = $cost_calculations["desc"];
																				$_paypal["PAYMENTREQUEST_0_CUSTOM"] = $post_vars["attr"]["custom"];

																				if ($post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0)
																					{
																						$_paypal["PAYMENTREQUEST_0_CURRENCYCODE"] = $cost_calculations["cur"];
																						$_paypal["PAYMENTREQUEST_0_ITEMAMT"] = $cost_calculations["trial_sub_total"];
																						$_paypal["PAYMENTREQUEST_0_TAXAMT"] = $cost_calculations["trial_tax"];
																						$_paypal["PAYMENTREQUEST_0_AMT"] = $cost_calculations["trial_total"];

																						$_paypal["L_PAYMENTREQUEST_0_QTY0"] = "1"; // Always (1).
																						$_paypal["L_PAYMENTREQUEST_0_NAME0"] = $cost_calculations["desc"];
																						$_paypal["L_PAYMENTREQUEST_0_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																						$_paypal["L_PAYMENTREQUEST_0_AMT0"] = $cost_calculations["trial_sub_total"];
																					}
																				else // Otherwise, charge for the first Regular payment.
																					{
																						$_paypal["PAYMENTREQUEST_0_CURRENCYCODE"] = $cost_calculations["cur"];
																						$_paypal["PAYMENTREQUEST_0_ITEMAMT"] = $cost_calculations["sub_total"];
																						$_paypal["PAYMENTREQUEST_0_TAXAMT"] = $cost_calculations["tax"];
																						$_paypal["PAYMENTREQUEST_0_AMT"] = $cost_calculations["total"];

																						$_paypal["L_PAYMENTREQUEST_0_QTY0"] = "1"; // Always (1).
																						$_paypal["L_PAYMENTREQUEST_0_NAME0"] = $cost_calculations["desc"];
																						$_paypal["L_PAYMENTREQUEST_0_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																						$_paypal["L_PAYMENTREQUEST_0_AMT0"] = $cost_calculations["sub_total"];
																					}
																			}
																		else // NOT using PayPal Express Checkout.
																			{
																				$_paypal["METHOD"] = "DoDirectPayment";
																				$_paypal["PAYMENTACTION"] = "Sale";

																				$_paypal["EMAIL"] = $user->user_email;
																				$_paypal["FIRSTNAME"] = $post_vars["first_name"];
																				$_paypal["LASTNAME"] = $post_vars["last_name"];
																				$_paypal["IPADDRESS"] = $_SERVER["REMOTE_ADDR"];

																				$_paypal["DESC"] = $cost_calculations["desc"];
																				$_paypal["CUSTOM"] = $post_vars["attr"]["custom"];

																				if ($post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0)
																					{
																						$_paypal["CURRENCYCODE"] = $cost_calculations["cur"];
																						$_paypal["ITEMAMT"] = $cost_calculations["trial_sub_total"];
																						$_paypal["TAXAMT"] = $cost_calculations["trial_tax"];
																						$_paypal["AMT"] = $cost_calculations["trial_total"];

																						$_paypal["L_QTY0"] = "1"; // Always (1).
																						$_paypal["L_NAME0"] = $cost_calculations["desc"];
																						$_paypal["L_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																						$_paypal["L_AMT0"] = $cost_calculations["trial_sub_total"];
																					}
																				else // Otherwise, charge for the first Regular payment.
																					{
																						$_paypal["CURRENCYCODE"] = $cost_calculations["cur"];
																						$_paypal["ITEMAMT"] = $cost_calculations["sub_total"];
																						$_paypal["TAXAMT"] = $cost_calculations["tax"];
																						$_paypal["AMT"] = $cost_calculations["total"];

																						$_paypal["L_QTY0"] = "1"; // Always (1).
																						$_paypal["L_NAME0"] = $cost_calculations["desc"];
																						$_paypal["L_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																						$_paypal["L_AMT0"] = $cost_calculations["sub_total"];
																					}

																				$_paypal["CREDITCARDTYPE"] = $post_vars["card_type"];
																				$_paypal["ACCT"] = preg_replace ("/[^0-9]/", "", $post_vars["card_number"]);
																				$_paypal["EXPDATE"] = preg_replace ("/[^0-9]/", "", $post_vars["card_expiration"]);
																				$_paypal["CVV2"] = $post_vars["card_verification"];

																				if (in_array($post_vars["card_type"], array("Maestro", "Solo")))
																					if (preg_match ("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
																						$_paypal["STARTDATE"] = preg_replace ("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
																					else // Otherwise, we assume they provided an Issue Number instead.
																						$_paypal["ISSUENUMBER"] = $post_vars["card_start_date_issue_number"];

																				$_paypal["STREET"] = $post_vars["street"];
																				$_paypal["CITY"] = $post_vars["city"];
																				$_paypal["STATE"] = $post_vars["state"];
																				$_paypal["COUNTRYCODE"] = $post_vars["country"];
																				$_paypal["ZIP"] = $post_vars["zip"];
																			}
																	}
																if (!($paypal = array())) // Recurring Profile.
																	{
																		$paypal["METHOD"] = "CreateRecurringPaymentsProfile";

																		$paypal["EMAIL"] = $user->user_email;
																		$paypal["FIRSTNAME"] = $post_vars["first_name"];
																		$paypal["LASTNAME"] = $post_vars["last_name"];
																		$paypal["SUBSCRIBERNAME"] = $post_vars["name"];

																		$paypal["DESC"] = $cost_calculations["desc"];
																		$paypal["PROFILEREFERENCE"] = $reference;

																		$paypal["CURRENCYCODE"] = $cost_calculations["cur"];
																		$paypal["AMT"] = $cost_calculations["sub_total"];
																		$paypal["TAXAMT"] = $cost_calculations["tax"];

																		$paypal["MAXFAILEDPAYMENTS"] = $post_vars["attr"]["rra"];
																		$paypal["AUTOBILLOUTAMT"] = apply_filters("ws_plugin__s2member_pro_paypal_auto_bill_op", "AddToNextBilling", get_defined_vars ());

																		$paypal["PROFILESTARTDATE"] = date ("Y-m-d", $start_time) . "T00:00:00Z";

																		$paypal["BILLINGPERIOD"] = c_ws_plugin__s2member_paypal_utilities::paypal_pro_term ($post_vars["attr"]["rt"]);
																		$paypal["TOTALBILLINGCYCLES"] = ($post_vars["attr"]["rr"]) ? (($post_vars["attr"]["rrt"]) ? $post_vars["attr"]["rrt"] : "0") : "1";
																		$paypal["BILLINGFREQUENCY"] = $post_vars["attr"]["rp"];

																		if (!empty($_GET["s2member_paypal_xco"]) && $_GET["s2member_paypal_xco"] === "s2member_pro_paypal_checkout_return" && !empty($_GET["token"]) && ((!empty($_paypal_xco_details) && empty($_paypal_xco_details["__error"]) && ($paypal_xco_details = $_paypal_xco_details)) || (($paypal_xco_details = array("METHOD" => "GetExpressCheckoutDetails", "TOKEN" => $_GET["token"])) && ($paypal_xco_details = c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($paypal_xco_details)) && empty($paypal_xco_details["__error"]))))
																			{
																				$paypal["TOKEN"] = $paypal_xco_details["TOKEN"];
																			}
																		else // Otherwise, they're using a major credit card.
																			{
																				$paypal["CREDITCARDTYPE"] = $post_vars["card_type"];
																				$paypal["ACCT"] = preg_replace ("/[^0-9]/", "", $post_vars["card_number"]);
																				$paypal["EXPDATE"] = preg_replace ("/[^0-9]/", "", $post_vars["card_expiration"]);
																				$paypal["CVV2"] = $post_vars["card_verification"];

																				if (in_array($post_vars["card_type"], array("Maestro", "Solo")))
																					if (preg_match ("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
																						$paypal["STARTDATE"] = preg_replace ("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
																					else // Otherwise, we assume they provided an Issue Number instead.
																						$paypal["ISSUENUMBER"] = $post_vars["card_start_date_issue_number"];

																				$paypal["STREET"] = $post_vars["street"];
																				$paypal["CITY"] = $post_vars["city"];
																				$paypal["STATE"] = $post_vars["state"];
																				$paypal["COUNTRYCODE"] = $post_vars["country"];
																				$paypal["ZIP"] = $post_vars["zip"];
																			}
																	}
																if (($cost_calculations["trial_total"] <= 0 && $cost_calculations["total"] <= 0) || !$_paypal || (($_paypal = c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($_paypal)) && empty($_paypal["__error"])))
																	{
																		if (($cost_calculations["trial_total"] <= 0 && $cost_calculations["total"] <= 0) || (($paypal = c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($paypal)) && empty($paypal["__error"])))
																			{
																				if($cost_calculations["trial_total"] <= 0 && $cost_calculations["total"] <= 0)
																					$new__txn_id = strtoupper('free-'.uniqid()); // Auto-generated value in this case.

																				else // We handle this normally. The transaction ID comes from PayPal as it always does.
																					{
																						$new__txn_id = ($_paypal && !empty($_paypal["PAYMENTINFO_0_TRANSACTIONID"])) ? $_paypal["PAYMENTINFO_0_TRANSACTIONID"] : false;
																						$new__txn_id = (!$new__txn_id && $_paypal && !empty($_paypal["TRANSACTIONID"])) ? $_paypal["TRANSACTIONID"] : $new__txn_id;
																					}
																				$old__subscr_or_wp_id = c_ws_plugin__s2member_utils_users::get_user_subscr_or_wp_id ();
																				$old__subscr_id = get_user_option ("s2member_subscr_id");

																				if($cost_calculations["trial_total"] <= 0 && $cost_calculations["total"] <= 0)
																					$new__subscr_id = strtoupper('free-'.uniqid()); // Auto-generated value in this case.
																				else $new__subscr_id = $paypal["PROFILEID"];

																				if (!($ipn = array())) // Simulated PayPal IPN.
																					{
																						$ipn["txn_type"] = "subscr_signup";
																						$ipn["subscr_id"] = $new__subscr_id;
																						$ipn["custom"] = $post_vars["attr"]["custom"];

																						$ipn["txn_id"] = ($new__txn_id) ? $new__txn_id : $new__subscr_id;

																						$ipn["period1"] = $period1;
																						$ipn["period3"] = $period3;

																						$ipn["mc_amount1"] = $cost_calculations["trial_total"];
																						$ipn["mc_amount3"] = $cost_calculations["total"];

																						$ipn["mc_gross"] = (preg_match ("/^[1-9]/", $ipn["period1"])) ? $ipn["mc_amount1"] : $ipn["mc_amount3"];

																						$ipn["mc_currency"] = $cost_calculations["cur"];
																						$ipn["tax"] = $cost_calculations["tax"];

																						$ipn["recurring"] = ($post_vars["attr"]["rr"]) ? "1" : "";

																						$ipn["payer_email"] = $user->user_email;
																						$ipn["first_name"] = $post_vars["first_name"];
																						$ipn["last_name"] = $post_vars["last_name"];

																						$ipn["option_name1"] = "Referencing Customer ID";
																						$ipn["option_selection1"] = $old__subscr_or_wp_id;

																						$ipn["option_name2"] = "Customer IP Address";
																						$ipn["option_selection2"] = $_SERVER["REMOTE_ADDR"];

																						$ipn["item_name"] = $cost_calculations["desc"];
																						$ipn["item_number"] = $post_vars["attr"]["level_ccaps_eotper"];

																						$ipn["s2member_paypal_proxy"] = "paypal";
																						$ipn["s2member_paypal_proxy_use"] = "pro-emails";
																						$ipn["s2member_paypal_proxy_use"] .= ($ipn["mc_gross"] > 0) ? ",subscr-signup-as-subscr-payment" : "";
																						$ipn["s2member_paypal_proxy_coupon"] = array("coupon_code" => $cp_attr["_coupon_code"], "full_coupon_code" => $cp_attr["_full_coupon_code"], "affiliate_id" => $cp_attr["_coupon_affiliate_id"]);
																						$ipn["s2member_paypal_proxy_verification"] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();
																						$ipn["s2member_paypal_proxy_return_url"] = $post_vars["attr"]["success"];

																						$ipn["s2member_paypal_proxy_return_url"] = trim (c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20)));
																					}
																				if (!($paypal = array()) && ($paypal["PROFILEID"] = $old__subscr_id) && apply_filters("s2member_pro_cancels_old_rp_before_new_rp", TRUE, get_defined_vars()))
																					{
																						$paypal["METHOD"] = "ManageRecurringPaymentsProfileStatus";
																						$paypal["ACTION"] = "Cancel";

																						c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($paypal);
																					}
																				c_ws_plugin__s2member_list_servers::process_list_servers_against_current_user((boolean)@$post_vars["custom_fields"]["opt_in"], TRUE, TRUE);

																				setcookie("s2member_tracking", ($s2member_tracking = c_ws_plugin__s2member_utils_encryption::encrypt($new__subscr_id)), time() + 31556926, COOKIEPATH, COOKIE_DOMAIN).setcookie("s2member_tracking", $s2member_tracking, time() + 31556926, SITECOOKIEPATH, COOKIE_DOMAIN).($_COOKIE["s2member_tracking"] = $s2member_tracking);

																				$global_response = array("response" => _x ('<strong>Thank you.</strong> Your account has been updated.', "s2member-front", "s2member"));

																				if ($post_vars["attr"]["success"] && substr ($ipn["s2member_paypal_proxy_return_url"], 0, 2) === substr ($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace (array("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array(urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $ipn["s2member_paypal_proxy_return_url"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																					wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																			}
																		else // Else, an error.
																			{
																				$global_response = array("response" => $paypal["__error"], "error" => true);
																			}
																	}
																else // Else, an error.
																	{
																		$global_response = array("response" => $_paypal["__error"], "error" => true);
																	}
															}
														else // Else, an error. The existing Billing Profile is still in a pending state.
															{
																$global_response = array("response" => _x ('<strong>Sorry.</strong> Your account is pending other changes. Please try again in 15 minutes.', "s2member-front", "s2member"), "error" => true);
															}
													}

												else if ($use_recurring_profile && !is_user_logged_in ()) // Create a new account.
													{
														$period1 = c_ws_plugin__s2member_paypal_utilities::paypal_pro_period1 ($post_vars["attr"]["tp"] . " " . $post_vars["attr"]["tt"]);
														$period3 = c_ws_plugin__s2member_paypal_utilities::paypal_pro_period3 ($post_vars["attr"]["rp"] . " " . $post_vars["attr"]["rt"]);

														$start_time = ($post_vars["attr"]["tp"]) ? // If there's an Initial/Trial Period; start when it's over.
														c_ws_plugin__s2member_pro_paypal_utilities::paypal_start_time ($period1) : // After Trial is over.
														c_ws_plugin__s2member_pro_paypal_utilities::paypal_start_time ($period3); // Or next billing cycle.

														$reference = $start_time . ":" . $period1 . ":" . $period3 . "~" . $_SERVER["HTTP_HOST"] . "~" . $post_vars["attr"]["level_ccaps_eotper"];

														if (!($_paypal = array()) // A first Initial/Trial payment?
														&& (!$post_vars["attr"]["tp"] || ($post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0)))
															{
																if (!empty($_GET["s2member_paypal_xco"]) && $_GET["s2member_paypal_xco"] === "s2member_pro_paypal_checkout_return" && !empty($_GET["token"]) && ($_paypal_xco_details = array("METHOD" => "GetExpressCheckoutDetails", "TOKEN" => $_GET["token"])) && ($_paypal_xco_details = c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($_paypal_xco_details)) && empty($_paypal_xco_details["__error"]))
																	{
																		$_paypal["METHOD"] = "DoExpressCheckoutPayment";

																		$_paypal["TOKEN"] = $_paypal_xco_details["TOKEN"];
																		$_paypal["PAYERID"] = $_paypal_xco_details["PAYERID"];

																		$_paypal["PAYMENTREQUEST_0_PAYMENTACTION"] = "Sale";

																		$_paypal["PAYMENTREQUEST_0_DESC"] = $cost_calculations["desc"];
																		$_paypal["PAYMENTREQUEST_0_CUSTOM"] = $post_vars["attr"]["custom"];

																		if ($post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0)
																			{
																				$_paypal["PAYMENTREQUEST_0_CURRENCYCODE"] = $cost_calculations["cur"];
																				$_paypal["PAYMENTREQUEST_0_ITEMAMT"] = $cost_calculations["trial_sub_total"];
																				$_paypal["PAYMENTREQUEST_0_TAXAMT"] = $cost_calculations["trial_tax"];
																				$_paypal["PAYMENTREQUEST_0_AMT"] = $cost_calculations["trial_total"];

																				$_paypal["L_PAYMENTREQUEST_0_QTY0"] = "1"; // Always (1).
																				$_paypal["L_PAYMENTREQUEST_0_NAME0"] = $cost_calculations["desc"];
																				$_paypal["L_PAYMENTREQUEST_0_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																				$_paypal["L_PAYMENTREQUEST_0_AMT0"] = $cost_calculations["trial_sub_total"];
																			}
																		else // Otherwise, charge for the first Regular payment.
																			{
																				$_paypal["PAYMENTREQUEST_0_CURRENCYCODE"] = $cost_calculations["cur"];
																				$_paypal["PAYMENTREQUEST_0_ITEMAMT"] = $cost_calculations["sub_total"];
																				$_paypal["PAYMENTREQUEST_0_TAXAMT"] = $cost_calculations["tax"];
																				$_paypal["PAYMENTREQUEST_0_AMT"] = $cost_calculations["total"];

																				$_paypal["L_PAYMENTREQUEST_0_QTY0"] = "1"; // Always (1).
																				$_paypal["L_PAYMENTREQUEST_0_NAME0"] = $cost_calculations["desc"];
																				$_paypal["L_PAYMENTREQUEST_0_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																				$_paypal["L_PAYMENTREQUEST_0_AMT0"] = $cost_calculations["sub_total"];
																			}
																	}
																else // NOT using PayPal Express Checkout.
																	{
																		$_paypal["METHOD"] = "DoDirectPayment";
																		$_paypal["PAYMENTACTION"] = "Sale";

																		$_paypal["EMAIL"] = $post_vars["email"];
																		$_paypal["FIRSTNAME"] = $post_vars["first_name"];
																		$_paypal["LASTNAME"] = $post_vars["last_name"];
																		$_paypal["IPADDRESS"] = $_SERVER["REMOTE_ADDR"];

																		$_paypal["DESC"] = $cost_calculations["desc"];
																		$_paypal["CUSTOM"] = $post_vars["attr"]["custom"];

																		if ($post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0)
																			{
																				$_paypal["CURRENCYCODE"] = $cost_calculations["cur"];
																				$_paypal["ITEMAMT"] = $cost_calculations["trial_sub_total"];
																				$_paypal["TAXAMT"] = $cost_calculations["trial_tax"];
																				$_paypal["AMT"] = $cost_calculations["trial_total"];

																				$_paypal["L_QTY0"] = "1"; // Always (1).
																				$_paypal["L_NAME0"] = $cost_calculations["desc"];
																				$_paypal["L_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																				$_paypal["L_AMT0"] = $cost_calculations["trial_sub_total"];
																			}
																		else // Otherwise, charge for the first Regular payment.
																			{
																				$_paypal["CURRENCYCODE"] = $cost_calculations["cur"];
																				$_paypal["ITEMAMT"] = $cost_calculations["sub_total"];
																				$_paypal["TAXAMT"] = $cost_calculations["tax"];
																				$_paypal["AMT"] = $cost_calculations["total"];

																				$_paypal["L_QTY0"] = "1"; // Always (1).
																				$_paypal["L_NAME0"] = $cost_calculations["desc"];
																				$_paypal["L_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																				$_paypal["L_AMT0"] = $cost_calculations["sub_total"];
																			}

																		$_paypal["CREDITCARDTYPE"] = $post_vars["card_type"];
																		$_paypal["ACCT"] = preg_replace ("/[^0-9]/", "", $post_vars["card_number"]);
																		$_paypal["EXPDATE"] = preg_replace ("/[^0-9]/", "", $post_vars["card_expiration"]);
																		$_paypal["CVV2"] = $post_vars["card_verification"];

																		if (in_array($post_vars["card_type"], array("Maestro", "Solo")))
																			if (preg_match ("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
																				$_paypal["STARTDATE"] = preg_replace ("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
																			else // Otherwise, we assume they provided an Issue Number instead.
																				$_paypal["ISSUENUMBER"] = $post_vars["card_start_date_issue_number"];

																		$_paypal["STREET"] = $post_vars["street"];
																		$_paypal["CITY"] = $post_vars["city"];
																		$_paypal["STATE"] = $post_vars["state"];
																		$_paypal["COUNTRYCODE"] = $post_vars["country"];
																		$_paypal["ZIP"] = $post_vars["zip"];
																	}
															}
														if (!($paypal = array())) // Recurring Profile.
															{
																$paypal["METHOD"] = "CreateRecurringPaymentsProfile";

																$paypal["EMAIL"] = $post_vars["email"];
																$paypal["FIRSTNAME"] = $post_vars["first_name"];
																$paypal["LASTNAME"] = $post_vars["last_name"];
																$paypal["SUBSCRIBERNAME"] = $post_vars["name"];

																$paypal["DESC"] = $cost_calculations["desc"];
																$paypal["PROFILEREFERENCE"] = $reference;

																$paypal["CURRENCYCODE"] = $cost_calculations["cur"];
																$paypal["AMT"] = $cost_calculations["sub_total"];
																$paypal["TAXAMT"] = $cost_calculations["tax"];

																$paypal["MAXFAILEDPAYMENTS"] = $post_vars["attr"]["rra"];
																$paypal["AUTOBILLOUTAMT"] = apply_filters("ws_plugin__s2member_pro_paypal_auto_bill_op", "AddToNextBilling", get_defined_vars ());

																$paypal["PROFILESTARTDATE"] = date ("Y-m-d", $start_time) . "T00:00:00Z";

																$paypal["BILLINGPERIOD"] = c_ws_plugin__s2member_paypal_utilities::paypal_pro_term ($post_vars["attr"]["rt"]);
																$paypal["TOTALBILLINGCYCLES"] = ($post_vars["attr"]["rr"]) ? (($post_vars["attr"]["rrt"]) ? $post_vars["attr"]["rrt"] : "0") : "1";
																$paypal["BILLINGFREQUENCY"] = $post_vars["attr"]["rp"];

																if (!empty($_GET["s2member_paypal_xco"]) && $_GET["s2member_paypal_xco"] === "s2member_pro_paypal_checkout_return" && !empty($_GET["token"]) && ((!empty($_paypal_xco_details) && empty($_paypal_xco_details["__error"]) && ($paypal_xco_details = $_paypal_xco_details)) || (($paypal_xco_details = array("METHOD" => "GetExpressCheckoutDetails", "TOKEN" => $_GET["token"])) && ($paypal_xco_details = c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($paypal_xco_details)) && empty($paypal_xco_details["__error"]))))
																	{
																		$paypal["TOKEN"] = $paypal_xco_details["TOKEN"];
																	}
																else // Otherwise, they're using a major credit card.
																	{
																		$paypal["CREDITCARDTYPE"] = $post_vars["card_type"];
																		$paypal["ACCT"] = preg_replace ("/[^0-9]/", "", $post_vars["card_number"]);
																		$paypal["EXPDATE"] = preg_replace ("/[^0-9]/", "", $post_vars["card_expiration"]);
																		$paypal["CVV2"] = $post_vars["card_verification"];

																		if (in_array($post_vars["card_type"], array("Maestro", "Solo")))
																			if (preg_match ("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
																				$paypal["STARTDATE"] = preg_replace ("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
																			else // Otherwise, we assume they provided an Issue Number instead.
																				$paypal["ISSUENUMBER"] = $post_vars["card_start_date_issue_number"];

																		$paypal["STREET"] = $post_vars["street"];
																		$paypal["CITY"] = $post_vars["city"];
																		$paypal["STATE"] = $post_vars["state"];
																		$paypal["COUNTRYCODE"] = $post_vars["country"];
																		$paypal["ZIP"] = $post_vars["zip"];
																	}
															}
														if (($cost_calculations["trial_total"] <= 0 && $cost_calculations["total"] <= 0) || !$_paypal || (($_paypal = c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($_paypal)) && empty($_paypal["__error"])))
															{
																if (($cost_calculations["trial_total"] <= 0 && $cost_calculations["total"] <= 0) || (($paypal = c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($paypal)) && empty($paypal["__error"])))
																	{
																		if($cost_calculations["trial_total"] <= 0 && $cost_calculations["total"] <= 0)
																			$new__txn_id = strtoupper('free-'.uniqid()); // Auto-generated value in this case.

																		else // We handle this normally. The transaction ID comes from PayPal as it always does.
																			{
																				$new__txn_id = ($_paypal && !empty($_paypal["PAYMENTINFO_0_TRANSACTIONID"])) ? $_paypal["PAYMENTINFO_0_TRANSACTIONID"] : false;
																				$new__txn_id = (!$new__txn_id && $_paypal && !empty($_paypal["TRANSACTIONID"])) ? $_paypal["TRANSACTIONID"] : $new__txn_id;
																			}
																		if($cost_calculations["trial_total"] <= 0 && $cost_calculations["total"] <= 0)
																			$new__subscr_id = strtoupper('free-'.uniqid()); // Auto-generated value in this case.
																		else $new__subscr_id = $paypal["PROFILEID"];

																		if (!($ipn = array())) // Simulated PayPal IPN.
																			{
																				$ipn["txn_type"] = "subscr_signup";
																				$ipn["subscr_id"] = $new__subscr_id;
																				$ipn["custom"] = $post_vars["attr"]["custom"];

																				$ipn["txn_id"] = ($new__txn_id) ? $new__txn_id : $new__subscr_id;

																				$ipn["period1"] = $period1;
																				$ipn["period3"] = $period3;

																				$ipn["mc_amount1"] = $cost_calculations["trial_total"];
																				$ipn["mc_amount3"] = $cost_calculations["total"];

																				$ipn["mc_gross"] = (preg_match ("/^[1-9]/", $ipn["period1"])) ? $ipn["mc_amount1"] : $ipn["mc_amount3"];

																				$ipn["mc_currency"] = $cost_calculations["cur"];
																				$ipn["tax"] = $cost_calculations["tax"];

																				$ipn["recurring"] = ($post_vars["attr"]["rr"]) ? "1" : "";

																				$ipn["payer_email"] = $post_vars["email"];
																				$ipn["first_name"] = $post_vars["first_name"];
																				$ipn["last_name"] = $post_vars["last_name"];

																				$ipn["option_name1"] = "Originating Domain";
																				$ipn["option_selection1"] = $_SERVER["HTTP_HOST"];

																				$ipn["option_name2"] = "Customer IP Address";
																				$ipn["option_selection2"] = $_SERVER["REMOTE_ADDR"];

																				$ipn["item_name"] = $cost_calculations["desc"];
																				$ipn["item_number"] = $post_vars["attr"]["level_ccaps_eotper"];

																				$ipn["s2member_paypal_proxy"] = "paypal";
																				$ipn["s2member_paypal_proxy_use"] = "pro-emails";
																				$ipn["s2member_paypal_proxy_use"] .= ($ipn["mc_gross"] > 0) ? ",subscr-signup-as-subscr-payment" : "";
																				$ipn["s2member_paypal_proxy_coupon"] = array("coupon_code" => $cp_attr["_coupon_code"], "full_coupon_code" => $cp_attr["_full_coupon_code"], "affiliate_id" => $cp_attr["_coupon_affiliate_id"]);
																				$ipn["s2member_paypal_proxy_verification"] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();
																				$ipn["s2member_paypal_proxy_return_url"] = $post_vars["attr"]["success"];
																			}
																		if (!($create_user = array())) // Build post fields for registration configuration, and then the creation array.
																			{
																				$_POST["ws_plugin__s2member_custom_reg_field_user_pass1"] = @$post_vars["password1"]; // Fake this for registration configuration.
																				$_POST["ws_plugin__s2member_custom_reg_field_first_name"] = $post_vars["first_name"]; // Fake this for registration configuration.
																				$_POST["ws_plugin__s2member_custom_reg_field_last_name"] = $post_vars["last_name"]; // Fake this for registration configuration.
																				$_POST["ws_plugin__s2member_custom_reg_field_opt_in"] = @$post_vars["custom_fields"]["opt_in"]; // Fake this too.

																				if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"])
																					foreach (json_decode ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"], true) as $field)
																						{
																							$field_var = preg_replace ("/[^a-z0-9]/i", "_", strtolower ($field["id"]));
																							$field_id_class = preg_replace ("/_/", "-", $field_var);

																							if (isset ($post_vars["custom_fields"][$field_var]))
																								$_POST["ws_plugin__s2member_custom_reg_field_" . $field_var] = $post_vars["custom_fields"][$field_var];
																						}

																				$_COOKIE["s2member_subscr_gateway"] = c_ws_plugin__s2member_utils_encryption::encrypt ("paypal"); // Fake this for registration configuration.
																				$_COOKIE["s2member_subscr_id"] = c_ws_plugin__s2member_utils_encryption::encrypt ($new__subscr_id); // Fake this for registration configuration.
																				$_COOKIE["s2member_custom"] = c_ws_plugin__s2member_utils_encryption::encrypt ($post_vars["attr"]["custom"]); // Fake this for registration configuration.
																				$_COOKIE["s2member_item_number"] = c_ws_plugin__s2member_utils_encryption::encrypt ($post_vars["attr"]["level_ccaps_eotper"]); // Fake this too.

																				$create_user["user_login"] = $post_vars["username"]; // Copy this into a separate array for `wp_create_user()`.
																				$create_user["user_pass"] = c_ws_plugin__s2member_registrations::maybe_custom_pass($post_vars["password1"]);
																				$create_user["user_email"] = $post_vars["email"]; // Copy this into a separate array for `wp_create_user()`.
																			}
																		if (!empty($post_vars["password1"]) && $post_vars["password1"] === $create_user["user_pass"]) // A custom Password is being used?
																			{
																				if (((is_multisite () && ($new__user_id = c_ws_plugin__s2member_registrations::ms_create_existing_user ($create_user["user_login"], $create_user["user_email"], $create_user["user_pass"]))) || ($new__user_id = wp_create_user ($create_user["user_login"], $create_user["user_pass"], $create_user["user_email"]))) && !is_wp_error ($new__user_id))
																					{
																						if (version_compare(get_bloginfo("version"), "4.3", ">="))
																							wp_new_user_notification ($new__user_id, "admin", $create_user["user_pass"]);
																						else wp_new_user_notification ($new__user_id, $create_user["user_pass"]);

																						$ipn["s2member_paypal_proxy_return_url"] = trim (c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20)));

																						$global_response = array("response" => sprintf (_x ('<strong>Thank you.</strong> Your account has been approved.<br />&mdash; Please <a href="%s" rel="nofollow">log in</a>.', "s2member-front", "s2member"), esc_attr (wp_login_url ())));

																						if ($post_vars["attr"]["success"] && substr ($ipn["s2member_paypal_proxy_return_url"], 0, 2) === substr ($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace (array("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array(urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $ipn["s2member_paypal_proxy_return_url"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																							wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																					}
																				else // Else, an error reponse should be given.
																					{
																						c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20));

																						$global_response = array("response" => _x ('<strong>Oops.</strong> A slight problem. Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
																					}
																			}
																		else // Otherwise, they'll need to check their email for the auto-generated Password.
																			{
																				if (((is_multisite () && ($new__user_id = c_ws_plugin__s2member_registrations::ms_create_existing_user ($create_user["user_login"], $create_user["user_email"], $create_user["user_pass"]))) || ($new__user_id = wp_create_user ($create_user["user_login"], $create_user["user_pass"], $create_user["user_email"]))) && !is_wp_error ($new__user_id))
																					{
																						update_user_option ($new__user_id, "default_password_nag", true, true); // Password nag.

																						if (version_compare(get_bloginfo("version"), "4.3", ">="))
																							wp_new_user_notification ($new__user_id, "both", $create_user["user_pass"]);
																						else wp_new_user_notification ($new__user_id, $create_user["user_pass"]);

																						$ipn["s2member_paypal_proxy_return_url"] = trim (c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20)));

																						$global_response = array("response" => _x ('<strong>Thank you.</strong> Your account has been approved.<br />&mdash; You\'ll receive an email momentarily.', "s2member-front", "s2member"));

																						if ($post_vars["attr"]["success"] && substr ($ipn["s2member_paypal_proxy_return_url"], 0, 2) === substr ($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace (array("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array(urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $ipn["s2member_paypal_proxy_return_url"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																							wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																					}
																				else // Else, an error reponse should be given.
																					{
																						c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20));

																						$global_response = array("response" => _x ('<strong>Oops.</strong> A slight problem. Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
																					}
																			}
																	}
																else // Else, an error.
																	{
																		$global_response = array("response" => $paypal["__error"], "error" => true);
																	}
															}
														else // Else, an error.
															{
																$global_response = array("response" => $_paypal["__error"], "error" => true);
															}
													}

												else if (!$use_recurring_profile && is_user_logged_in () && is_object ($user = wp_get_current_user ()) && ($user_id = $user->ID))
													{
														if ($is_independent_ccaps_sale || !($cur__subscr_id = get_user_option ("s2member_subscr_id")) || !($paypal = c_ws_plugin__s2member_paypal_utilities::paypal_api_response (array("METHOD" => "GetRecurringPaymentsProfileDetails", "PROFILEID" => $cur__subscr_id))) || !empty($paypal["__error"]) || !preg_match ("/^(Pending|PendingProfile)$/i", $paypal["STATUS"]))
															{
																update_user_meta ($user_id, "first_name", $post_vars["first_name"]) . update_user_meta ($user_id, "last_name", $post_vars["last_name"]);

																if (!($paypal = array())) // Prepare a "Buy Now" transaction.
																	{
																		if (!empty($_GET["s2member_paypal_xco"]) && $_GET["s2member_paypal_xco"] === "s2member_pro_paypal_checkout_return" && !empty($_GET["token"]) && ($paypal_xco_details = array("METHOD" => "GetExpressCheckoutDetails", "TOKEN" => $_GET["token"])) && ($paypal_xco_details = c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($paypal_xco_details)) && empty($paypal_xco_details["__error"]))
																			{
																				$paypal["METHOD"] = "DoExpressCheckoutPayment";

																				$paypal["TOKEN"] = $paypal_xco_details["TOKEN"];
																				$paypal["PAYERID"] = $paypal_xco_details["PAYERID"];

																				$paypal["PAYMENTREQUEST_0_PAYMENTACTION"] = "Sale";

																				$paypal["PAYMENTREQUEST_0_DESC"] = $cost_calculations["desc"];
																				$paypal["PAYMENTREQUEST_0_CUSTOM"] = $post_vars["attr"]["custom"];

																				$paypal["PAYMENTREQUEST_0_CURRENCYCODE"] = $cost_calculations["cur"];
																				$paypal["PAYMENTREQUEST_0_ITEMAMT"] = $cost_calculations["sub_total"];
																				$paypal["PAYMENTREQUEST_0_TAXAMT"] = $cost_calculations["tax"];
																				$paypal["PAYMENTREQUEST_0_AMT"] = $cost_calculations["total"];

																				$paypal["L_PAYMENTREQUEST_0_QTY0"] = "1"; // Always (1).
																				$paypal["L_PAYMENTREQUEST_0_NAME0"] = $cost_calculations["desc"];
																				$paypal["L_PAYMENTREQUEST_0_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																				$paypal["L_PAYMENTREQUEST_0_AMT0"] = $cost_calculations["sub_total"];
																			}
																		else // NOT using PayPal Express Checkout.
																			{
																				$paypal["METHOD"] = "DoDirectPayment";
																				$paypal["PAYMENTACTION"] = "Sale";

																				$paypal["EMAIL"] = $user->user_email;
																				$paypal["FIRSTNAME"] = $post_vars["first_name"];
																				$paypal["LASTNAME"] = $post_vars["last_name"];
																				$paypal["IPADDRESS"] = $_SERVER["REMOTE_ADDR"];

																				$paypal["DESC"] = $cost_calculations["desc"];
																				$paypal["CUSTOM"] = $post_vars["attr"]["custom"];

																				$paypal["CURRENCYCODE"] = $cost_calculations["cur"];
																				$paypal["ITEMAMT"] = $cost_calculations["sub_total"];
																				$paypal["TAXAMT"] = $cost_calculations["tax"];
																				$paypal["AMT"] = $cost_calculations["total"];

																				$paypal["L_QTY0"] = "1"; // Always (1).
																				$paypal["L_NAME0"] = $cost_calculations["desc"];
																				$paypal["L_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																				$paypal["L_AMT0"] = $cost_calculations["sub_total"];

																				$paypal["CREDITCARDTYPE"] = $post_vars["card_type"];
																				$paypal["ACCT"] = preg_replace ("/[^0-9]/", "", $post_vars["card_number"]);
																				$paypal["EXPDATE"] = preg_replace ("/[^0-9]/", "", $post_vars["card_expiration"]);
																				$paypal["CVV2"] = $post_vars["card_verification"];

																				if (in_array($post_vars["card_type"], array("Maestro", "Solo")))
																					if (preg_match ("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
																						$paypal["STARTDATE"] = preg_replace ("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
																					else // Otherwise, we assume they provided an Issue Number instead.
																						$paypal["ISSUENUMBER"] = $post_vars["card_start_date_issue_number"];

																				$paypal["STREET"] = $post_vars["street"];
																				$paypal["CITY"] = $post_vars["city"];
																				$paypal["STATE"] = $post_vars["state"];
																				$paypal["COUNTRYCODE"] = $post_vars["country"];
																				$paypal["ZIP"] = $post_vars["zip"];
																			}
																	}
																if ($cost_calculations["total"] <= 0 || (($paypal = c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($paypal)) && empty($paypal["__error"])))
																	{
																		$old__subscr_id = get_user_option ("s2member_subscr_id");
																		$old__subscr_or_wp_id = c_ws_plugin__s2member_utils_users::get_user_subscr_or_wp_id ();

																		if($cost_calculations["total"] <= 0) $new__subscr_id = $new__txn_id = strtoupper('free-'.uniqid()); // Auto-generated value in this case.

																		else // Handle this normally. The transaction ID comes from PayPal as it always does.
																			{
																				$new__subscr_id = $new__txn_id = (!empty($paypal["PAYMENTINFO_0_TRANSACTIONID"])) ? $paypal["PAYMENTINFO_0_TRANSACTIONID"] : false;
																				$new__subscr_id = $new__txn_id = (!$new__subscr_id && !empty($paypal["TRANSACTIONID"])) ? $paypal["TRANSACTIONID"] : $new__subscr_id;
																			}
																		if (!($ipn = array())) // Simulated PayPal IPN.
																			{
																				$ipn["txn_type"] = "web_accept";
																				$ipn["txn_id"] = $new__subscr_id;
																				$ipn["custom"] = $post_vars["attr"]["custom"];

																				$ipn["mc_gross"] = $cost_calculations["total"];
																				$ipn["mc_currency"] = $cost_calculations["cur"];
																				$ipn["tax"] = $cost_calculations["tax"];

																				$ipn["payer_email"] = $user->user_email;
																				$ipn["first_name"] = $post_vars["first_name"];
																				$ipn["last_name"] = $post_vars["last_name"];

																				$ipn["option_name1"] = "Referencing Customer ID";
																				$ipn["option_selection1"] = $old__subscr_or_wp_id;

																				$ipn["option_name2"] = "Customer IP Address";
																				$ipn["option_selection2"] = $_SERVER["REMOTE_ADDR"];

																				$ipn["item_name"] = $cost_calculations["desc"];
																				$ipn["item_number"] = $post_vars["attr"]["level_ccaps_eotper"];

																				$ipn["s2member_paypal_proxy"] = "paypal";
																				$ipn["s2member_paypal_proxy_use"] = "pro-emails";
																				$ipn["s2member_paypal_proxy_coupon"] = array("coupon_code" => $cp_attr["_coupon_code"], "full_coupon_code" => $cp_attr["_full_coupon_code"], "affiliate_id" => $cp_attr["_coupon_affiliate_id"]);
																				$ipn["s2member_paypal_proxy_verification"] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();
																				$ipn["s2member_paypal_proxy_return_url"] = $post_vars["attr"]["success"];

																				$ipn["s2member_paypal_proxy_return_url"] = trim (c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20)));
																			}
																		if (!$is_independent_ccaps_sale) // Independent?
																			if (!($paypal = array()) && ($paypal["PROFILEID"] = $old__subscr_id) && apply_filters("s2member_pro_cancels_old_rp_before_new_rp", TRUE, get_defined_vars()))
																				{
																					$paypal["METHOD"] = "ManageRecurringPaymentsProfileStatus";
																					$paypal["ACTION"] = "Cancel";

																					c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($paypal);
																				}
																		c_ws_plugin__s2member_list_servers::process_list_servers_against_current_user((boolean)@$post_vars["custom_fields"]["opt_in"], TRUE, TRUE);

																		setcookie("s2member_tracking", ($s2member_tracking = c_ws_plugin__s2member_utils_encryption::encrypt($new__subscr_id)), time() + 31556926, COOKIEPATH, COOKIE_DOMAIN).setcookie("s2member_tracking", $s2member_tracking, time() + 31556926, SITECOOKIEPATH, COOKIE_DOMAIN).($_COOKIE["s2member_tracking"] = $s2member_tracking);

																		$global_response = array("response" => _x ('<strong>Thank you.</strong> Your account has been updated.', "s2member-front", "s2member"));

																		if ($post_vars["attr"]["success"] && substr ($ipn["s2member_paypal_proxy_return_url"], 0, 2) === substr ($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace (array("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array(urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $ipn["s2member_paypal_proxy_return_url"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																			wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																	}
																else // Else, an error.
																	{
																		$global_response = array("response" => $paypal["__error"], "error" => true);
																	}
															}
														else // Else, an error. The existing Billing Profile is still in a pending state.
															{
																$global_response = array("response" => _x ('<strong>Sorry.</strong> Your account is pending other changes. Please try again in 15 minutes.', "s2member-front", "s2member"), "error" => true);
															}
													}
												else if (!$use_recurring_profile && !is_user_logged_in ())
													{
														if (!($paypal = array())) // Prepare a "Buy Now" transaction.
															{
																if (!empty($_GET["s2member_paypal_xco"]) && $_GET["s2member_paypal_xco"] === "s2member_pro_paypal_checkout_return" && !empty($_GET["token"]) && ($paypal_xco_details = array("METHOD" => "GetExpressCheckoutDetails", "TOKEN" => $_GET["token"])) && ($paypal_xco_details = c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($paypal_xco_details)) && empty($paypal_xco_details["__error"]))
																	{
																		$paypal["METHOD"] = "DoExpressCheckoutPayment";

																		$paypal["TOKEN"] = $paypal_xco_details["TOKEN"];
																		$paypal["PAYERID"] = $paypal_xco_details["PAYERID"];

																		$paypal["PAYMENTREQUEST_0_PAYMENTACTION"] = "Sale";

																		$paypal["PAYMENTREQUEST_0_DESC"] = $cost_calculations["desc"];
																		$paypal["PAYMENTREQUEST_0_CUSTOM"] = $post_vars["attr"]["custom"];

																		$paypal["PAYMENTREQUEST_0_CURRENCYCODE"] = $cost_calculations["cur"];
																		$paypal["PAYMENTREQUEST_0_ITEMAMT"] = $cost_calculations["sub_total"];
																		$paypal["PAYMENTREQUEST_0_TAXAMT"] = $cost_calculations["tax"];
																		$paypal["PAYMENTREQUEST_0_AMT"] = $cost_calculations["total"];

																		$paypal["L_PAYMENTREQUEST_0_QTY0"] = "1"; // Always (1).
																		$paypal["L_PAYMENTREQUEST_0_NAME0"] = $cost_calculations["desc"];
																		$paypal["L_PAYMENTREQUEST_0_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
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

																		$paypal["CURRENCYCODE"] = $cost_calculations["cur"];
																		$paypal["ITEMAMT"] = $cost_calculations["sub_total"];
																		$paypal["TAXAMT"] = $cost_calculations["tax"];
																		$paypal["AMT"] = $cost_calculations["total"];

																		$paypal["L_QTY0"] = "1"; // Always (1).
																		$paypal["L_NAME0"] = $cost_calculations["desc"];
																		$paypal["L_NUMBER0"] = $post_vars["attr"]["level_ccaps_eotper"];
																		$paypal["L_AMT0"] = $cost_calculations["sub_total"];

																		$paypal["CREDITCARDTYPE"] = $post_vars["card_type"];
																		$paypal["ACCT"] = preg_replace ("/[^0-9]/", "", $post_vars["card_number"]);
																		$paypal["EXPDATE"] = preg_replace ("/[^0-9]/", "", $post_vars["card_expiration"]);
																		$paypal["CVV2"] = $post_vars["card_verification"];

																		if (in_array($post_vars["card_type"], array("Maestro", "Solo")))
																			if (preg_match ("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
																				$paypal["STARTDATE"] = preg_replace ("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
																			else // Otherwise, we assume they provided an Issue Number instead.
																				$paypal["ISSUENUMBER"] = $post_vars["card_start_date_issue_number"];

																		$paypal["STREET"] = $post_vars["street"];
																		$paypal["CITY"] = $post_vars["city"];
																		$paypal["STATE"] = $post_vars["state"];
																		$paypal["COUNTRYCODE"] = $post_vars["country"];
																		$paypal["ZIP"] = $post_vars["zip"];
																	}
															}
														if ($cost_calculations["total"] <= 0 || (($paypal = c_ws_plugin__s2member_paypal_utilities::paypal_api_response ($paypal)) && empty($paypal["__error"])))
															{
																if($cost_calculations["total"] <= 0) $new__subscr_id = $new__txn_id = strtoupper('free-'.uniqid()); // Auto-generated value in this case.

																else // Handle this normally. The transaction ID comes from PayPal as it always does.
																	{
																		$new__subscr_id = $new__txn_id = (!empty($paypal["PAYMENTINFO_0_TRANSACTIONID"])) ? $paypal["PAYMENTINFO_0_TRANSACTIONID"] : false;
																		$new__subscr_id = $new__txn_id = (!$new__subscr_id && !empty($paypal["TRANSACTIONID"])) ? $paypal["TRANSACTIONID"] : $new__subscr_id;
																	}
																if (!($ipn = array())) // Simulated PayPal IPN.
																	{
																		$ipn["txn_type"] = "web_accept";
																		$ipn["txn_id"] = $new__subscr_id;
																		$ipn["custom"] = $post_vars["attr"]["custom"];

																		$ipn["mc_gross"] = $cost_calculations["total"];
																		$ipn["mc_currency"] = $cost_calculations["cur"];
																		$ipn["tax"] = $cost_calculations["tax"];

																		$ipn["payer_email"] = $post_vars["email"];
																		$ipn["first_name"] = $post_vars["first_name"];
																		$ipn["last_name"] = $post_vars["last_name"];

																		$ipn["option_name1"] = "Originating Domain";
																		$ipn["option_selection1"] = $_SERVER["HTTP_HOST"];

																		$ipn["option_name2"] = "Customer IP Address";
																		$ipn["option_selection2"] = $_SERVER["REMOTE_ADDR"];

																		$ipn["item_name"] = $cost_calculations["desc"];
																		$ipn["item_number"] = $post_vars["attr"]["level_ccaps_eotper"];

																		$ipn["s2member_paypal_proxy"] = "paypal";
																		$ipn["s2member_paypal_proxy_use"] = "pro-emails";
																		$ipn["s2member_paypal_proxy_coupon"] = array("coupon_code" => $cp_attr["_coupon_code"], "full_coupon_code" => $cp_attr["_full_coupon_code"], "affiliate_id" => $cp_attr["_coupon_affiliate_id"]);
																		$ipn["s2member_paypal_proxy_verification"] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();
																		$ipn["s2member_paypal_proxy_return_url"] = $post_vars["attr"]["success"];
																	}
																if (!($create_user = array())) // Build post fields for registration configuration, and then the creation array.
																	{
																		$_POST["ws_plugin__s2member_custom_reg_field_user_pass1"] = @$post_vars["password1"]; // Fake this for registration configuration.
																		$_POST["ws_plugin__s2member_custom_reg_field_first_name"] = $post_vars["first_name"]; // Fake this for registration configuration.
																		$_POST["ws_plugin__s2member_custom_reg_field_last_name"] = $post_vars["last_name"]; // Fake this for registration configuration.
																		$_POST["ws_plugin__s2member_custom_reg_field_opt_in"] = @$post_vars["custom_fields"]["opt_in"]; // Fake this too.

																		if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"])
																			foreach (json_decode ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"], true) as $field)
																				{
																					$field_var = preg_replace ("/[^a-z0-9]/i", "_", strtolower ($field["id"]));
																					$field_id_class = preg_replace ("/_/", "-", $field_var);

																					if (isset ($post_vars["custom_fields"][$field_var]))
																						$_POST["ws_plugin__s2member_custom_reg_field_" . $field_var] = $post_vars["custom_fields"][$field_var];
																				}

																		$_COOKIE["s2member_subscr_gateway"] = c_ws_plugin__s2member_utils_encryption::encrypt ("paypal"); // Fake this for registration configuration.
																		$_COOKIE["s2member_subscr_id"] = c_ws_plugin__s2member_utils_encryption::encrypt ($new__subscr_id); // Fake this for registration configuration.
																		$_COOKIE["s2member_custom"] = c_ws_plugin__s2member_utils_encryption::encrypt ($post_vars["attr"]["custom"]); // Fake this for registration configuration.
																		$_COOKIE["s2member_item_number"] = c_ws_plugin__s2member_utils_encryption::encrypt ($post_vars["attr"]["level_ccaps_eotper"]); // Fake this too.

																		$create_user["user_login"] = $post_vars["username"]; // Copy this into a separate array for `wp_create_user()`.
																		$create_user["user_pass"] = c_ws_plugin__s2member_registrations::maybe_custom_pass($post_vars["password1"]);
																		$create_user["user_email"] = $post_vars["email"]; // Copy this into a separate array for `wp_create_user()`.
																	}
																if (!empty($post_vars["password1"]) && $post_vars["password1"] === $create_user["user_pass"]) // A custom Password is being used?
																	{
																		if (((is_multisite () && ($new__user_id = c_ws_plugin__s2member_registrations::ms_create_existing_user ($create_user["user_login"], $create_user["user_email"], $create_user["user_pass"]))) || ($new__user_id = wp_create_user ($create_user["user_login"], $create_user["user_pass"], $create_user["user_email"]))) && !is_wp_error ($new__user_id))
																			{
																				if (version_compare(get_bloginfo("version"), "4.3", ">="))
																					wp_new_user_notification ($new__user_id, "admin", $create_user["user_pass"]);
																				else wp_new_user_notification ($new__user_id, $create_user["user_pass"]);

																				$ipn["s2member_paypal_proxy_return_url"] = trim (c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20)));

																				$global_response = array("response" => sprintf (_x ('<strong>Thank you.</strong> Your account has been approved.<br />&mdash; Please <a href="%s" rel="nofollow">log in</a>.', "s2member-front", "s2member"), esc_attr (wp_login_url ())));

																				if ($post_vars["attr"]["success"] && substr ($ipn["s2member_paypal_proxy_return_url"], 0, 2) === substr ($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace (array("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array(urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $ipn["s2member_paypal_proxy_return_url"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																					wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																			}
																		else // Else, an error reponse should be given.
																			{
																				c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20));

																				$global_response = array("response" => _x ('<strong>Oops.</strong> A slight problem. Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
																			}
																	}
																else // Otherwise, they'll need to check their email for the auto-generated Password.
																	{
																		if (((is_multisite () && ($new__user_id = c_ws_plugin__s2member_registrations::ms_create_existing_user ($create_user["user_login"], $create_user["user_email"], $create_user["user_pass"]))) || ($new__user_id = wp_create_user ($create_user["user_login"], $create_user["user_pass"], $create_user["user_email"]))) && !is_wp_error ($new__user_id))
																			{
																				update_user_option ($new__user_id, "default_password_nag", true, true); // Password nag.

																				if (version_compare(get_bloginfo("version"), "4.3", ">="))
																					wp_new_user_notification ($new__user_id, "both", $create_user["user_pass"]);
																				else wp_new_user_notification ($new__user_id, $create_user["user_pass"]);

																				$ipn["s2member_paypal_proxy_return_url"] = trim (c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20)));

																				$global_response = array("response" => _x ('<strong>Thank you.</strong> Your account has been approved.<br />&mdash; You\'ll receive an email momentarily.', "s2member-front", "s2member"));

																				if ($post_vars["attr"]["success"] && substr ($ipn["s2member_paypal_proxy_return_url"], 0, 2) === substr ($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace (array("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array(urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $ipn["s2member_paypal_proxy_return_url"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																					wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																			}
																		else // Else, an error reponse should be given.
																			{
																				c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20));

																				$global_response = array("response" => _x ('<strong>Oops.</strong> A slight problem. Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
																			}
																	}
															}
														else // Else, an error.
															{
																$global_response = array("response" => $paypal["__error"], "error" => true);
															}
													}
												else // Else, we have an unknown scenario.
													{
														$global_response = array("response" => _x ('<strong>Unknown error.</strong> Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
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
