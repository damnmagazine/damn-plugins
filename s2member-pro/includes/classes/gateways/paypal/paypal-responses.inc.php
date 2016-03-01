<?php
/**
* PayPal Pro-Form responses.
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

if(!class_exists("c_ws_plugin__s2member_pro_paypal_responses"))
	{
		/**
		* PayPal Pro-Form responses.
		*
		* @package s2Member\PayPal
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_paypal_responses
			{
				/**
				* Creates response divs after Cancellation processing.
				*
				* @package s2Member\PayPal
				* @since 1.5
				*
				* @param array $attr An array of Pro-Form Attributes.
				* @return array An array of response details.
				*/
				public static function paypal_cancellation_response($attr = FALSE)
					{
						$_response = @$GLOBALS["ws_plugin__s2member_pro_paypal_cancellation_response"];
						$_response = (!$_response) ? c_ws_plugin__s2member_pro_paypal_responses::paypal_form_attr_validation_errors($attr) : $_response;
						$response = $error = NULL; // Initialize.

						if($_response && !empty($_response["error"]) && !empty($_response["response"]) && ($error = $_response["error"]))
							{
								$response = '<div id="s2member-pro-paypal-form-response" class="s2member-pro-paypal-form-response-error s2member-pro-paypal-cancellation-form-response-error">';
								$response .= $_response["response"];
								$response .= '</div>';
							}
						else if($_response && !empty($_response["response"]))
							{
								$response = '<div id="s2member-pro-paypal-form-response" class="s2member-pro-paypal-form-response-info s2member-pro-paypal-cancellation-form-response-info">';
								$response .= $_response["response"];
								$response .= '</div>';
							}
						return array("response" => $response, "error" => $error);
					}
				/**
				* Creates response divs after Update processing.
				*
				* @package s2Member\PayPal
				* @since 1.5
				*
				* @param array $attr An array of Pro-Form Attributes.
				* @return array An array of response details.
				*/
				public static function paypal_update_response($attr = FALSE)
					{
						$_response = @$GLOBALS["ws_plugin__s2member_pro_paypal_update_response"];
						$_response = (!$_response) ? c_ws_plugin__s2member_pro_paypal_responses::paypal_form_attr_validation_errors($attr) : $_response;
						$response = $error = NULL; // Initialize.

						if($_response && !empty($_response["error"]) && !empty($_response["response"]) && ($error = $_response["error"]))
							{
								$response = '<div id="s2member-pro-paypal-form-response" class="s2member-pro-paypal-form-response-error s2member-pro-paypal-update-form-response-error">';
								$response .= $_response["response"];
								$response .= '</div>';
							}
						else if($_response && !empty($_response["response"]))
							{
								$response = '<div id="s2member-pro-paypal-form-response" class="s2member-pro-paypal-form-response-info s2member-pro-paypal-update-form-response-info">';
								$response .= $_response["response"];
								$response .= '</div>';
							}
						return array("response" => $response, "error" => $error);
					}
				/**
				* Creates response divs after Registration processing.
				*
				* @package s2Member\PayPal
				* @since 1.5
				*
				* @param array $attr An array of Pro-Form Attributes.
				* @return array An array of response details.
				*/
				public static function paypal_registration_response($attr = FALSE)
					{
						$_response = @$GLOBALS["ws_plugin__s2member_pro_paypal_registration_response"];
						$_response = (!$_response) ? c_ws_plugin__s2member_pro_paypal_responses::paypal_form_attr_validation_errors($attr) : $_response;
						$response = $error = NULL; // Initialize.

						if($_response && !empty($_response["error"]) && !empty($_response["response"]) && ($error = $_response["error"]))
							{
								$response = '<div id="s2member-pro-paypal-form-response" class="s2member-pro-paypal-form-response-error s2member-pro-paypal-registration-form-response-error">';
								$response .= $_response["response"];
								$response .= '</div>';
							}
						else if($_response && !empty($_response["response"]))
							{
								$response = '<div id="s2member-pro-paypal-form-response" class="s2member-pro-paypal-form-response-info s2member-pro-paypal-registration-form-response-info">';
								$response .= $_response["response"];
								$response .= '</div>';
							}
						return array("response" => $response, "error" => $error);
					}
				/**
				* Creates response divs after Specific Post/Page checkout processing.
				*
				* @package s2Member\PayPal
				* @since 1.5
				*
				* @param array $attr An array of Pro-Form Attributes.
				* @return array An array of response details.
				*/
				public static function paypal_sp_checkout_response($attr = FALSE)
					{
						$_response = @$GLOBALS["ws_plugin__s2member_pro_paypal_sp_checkout_response"];
						$_response = (!$_response) ? c_ws_plugin__s2member_pro_paypal_responses::paypal_form_attr_validation_errors($attr) : $_response;
						$response = $error = NULL; // Initialize.

						if($_response && !empty($_response["error"]) && !empty($_response["response"]) && ($error = $_response["error"]))
							{
								$response = '<div id="s2member-pro-paypal-form-response" class="s2member-pro-paypal-form-response-error s2member-pro-paypal-sp-checkout-form-response-error">';
								$response .= $_response["response"];
								$response .= '</div>';
							}
						else if($_response && !empty($_response["response"]))
							{
								$response = '<div id="s2member-pro-paypal-form-response" class="s2member-pro-paypal-form-response-info s2member-pro-paypal-sp-checkout-form-response-info">';
								$response .= $_response["response"];
								$response .= '</div>';
							}
						return array("response" => $response, "error" => $error);
					}
				/**
				* Creates response divs after Membership checkout processing.
				*
				* @package s2Member\PayPal
				* @since 1.5
				*
				* @param array $attr An array of Pro-Form Attributes.
				* @return array An array of response details.
				*/
				public static function paypal_checkout_response($attr = FALSE)
					{
						$_response = @$GLOBALS["ws_plugin__s2member_pro_paypal_checkout_response"];
						$_response = (!$_response) ? c_ws_plugin__s2member_pro_paypal_responses::paypal_form_attr_validation_errors($attr) : $_response;
						$response = $error = NULL; // Initialize.

						if($_response && !empty($_response["error"]) && !empty($_response["response"]) && ($error = $_response["error"]))
							{
								$response = '<div id="s2member-pro-paypal-form-response" class="s2member-pro-paypal-form-response-error s2member-pro-paypal-checkout-form-response-error">';
								$response .= $_response["response"];
								$response .= '</div>';
							}
						else if($_response && !empty($_response["response"]))
							{
								$response = '<div id="s2member-pro-paypal-form-response" class="s2member-pro-paypal-form-response-info s2member-pro-paypal-checkout-form-response-info">';
								$response .= $_response["response"];
								$response .= '</div>';
							}
						return array("response" => $response, "error" => $error);
					}
				/**
				* Validates the configuration of API Credentials.
				*
				* Free Registration Forms do NOT require API Credentials.
				*
				* @package s2Member\PayPal
				* @since 1.5
				*
				* @param array $attr An array of Pro-Form Attributes.
				* @return null|array Null if there are no errors, else a response array.
				*/
				public static function paypal_form_api_validation_errors($attr = FALSE)
					{
						if(!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_business"])
							$response = array("response" => _x('PayPal configuration error. Please configure your PayPal Email Address.', "s2member-admin", "s2member"), "error" => true);

						else if(!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_api_username"])
							$response = array("response" => _x('PayPal configuration error. Your PayPal API Username is not yet configured.', "s2member-admin", "s2member"), "error" => true);

						else if(!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_api_password"])
							$response = array("response" => _x('PayPal configuration error. Your PayPal API Password is not yet configured.', "s2member-admin", "s2member"), "error" => true);

						else if(!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_api_signature"])
							$response = array("response" => _x('PayPal configuration error. Your PayPal API Signature is not yet configured.', "s2member-admin", "s2member"), "error" => true);

						return (empty($response) || !empty($attr["register"])) ? null : $response;
					}
				/**
				* Validates the configuration of the current form.
				*
				* Free Registration Forms do NOT require API Credentials.
				*
				* @package s2Member\PayPal
				* @since 1.5
				*
				* @param array $attr An array of Pro-Form Attributes.
				* @return null|array Null if there are no errors, else a response array.
				*/
				public static function paypal_form_attr_validation_errors($attr = FALSE)
					{
						if(!($response = c_ws_plugin__s2member_pro_paypal_responses::paypal_form_api_validation_errors($attr)) || !empty($attr["register"]))
							{
								if /* Special form for Cancellations. User/Member must be logged in. */($attr["cancel"])
									{
										if(!is_user_logged_in())
											$response = array("response" => sprintf(_x('You must <a href="%s" rel="nofollow">log in</a> to cancel your account.', "s2member-front", "s2member"), esc_attr(wp_login_url($_SERVER["REQUEST_URI"]))), "error" => true);

										else if(!is_object($user = wp_get_current_user()) || !($user_id = $user->ID) || !($subscr_id = get_user_option("s2member_subscr_id", $user_id)))
											$response = array("response" => _x('Nothing to cancel. You\'re NOT a paid Member.', "s2member-front", "s2member"), "error" => true);

										else if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_payflow_api_username"])
											{
												if(!($paypal = c_ws_plugin__s2member_pro_paypal_utilities::payflow_get_profile($subscr_id)))
													$response = array("response" => _x('Nothing to cancel. You have NO recurring fees.', "s2member-front", "s2member"), "error" => true);

												else if(!empty($paypal["STATUS"]) && preg_match("/^(Pending|PendingProfile)$/i", $paypal["STATUS"]))
													$response = array("response" => _x('<strong>Unable to cancel at this time.</strong> Your account is pending other changes. Please try again in 15 minutes.', "s2member-front", "s2member"), "error" => true);

												else if(empty($paypal["STATUS"]) || !preg_match("/^(Active|ActiveProfile|Suspended|SuspendedProfile)$/i", $paypal["STATUS"]))
													$response = array("response" => _x('Nothing to cancel. You have NO recurring fees.', "s2member-front", "s2member"), "error" => true);
											}
										else if(is_array($paypal = array("PROFILEID" => $subscr_id, "METHOD" => "GetRecurringPaymentsProfileDetails")))
											{
												if(!($paypal = c_ws_plugin__s2member_paypal_utilities::paypal_api_response($paypal)) || !empty($paypal["__error"]))
													{
														if($paypal && !empty($paypal["__error"]) && !empty($paypal["L_ERRORCODE0"]) && $paypal["L_ERRORCODE0"] === "11592")
															$response = array("response" => sprintf(_x('Please <a href="%s" rel="nofollow">log in at PayPal</a> to cancel your Subscription.', "s2member-front", "s2member"), esc_attr("https://".(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_sandbox"]) ? "www.sandbox.paypal.com" : "www.paypal.com")."/cgi-bin/webscr?cmd=_subscr-find&amp;alias=".urlencode($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_merchant_id"]))), "error" => true);

														else $response = array("response" => _x('Nothing to cancel. You have NO recurring fees.', "s2member-front", "s2member"), "error" => true);
													}
												else if(!empty($paypal["STATUS"]) && preg_match("/^(Pending|PendingProfile)$/i", $paypal["STATUS"]))
													$response = array("response" => _x('<strong>Unable to cancel at this time.</strong> Your account is pending other changes. Please try again in 15 minutes.', "s2member-front", "s2member"), "error" => true);

												else if(empty($paypal["STATUS"]) || !preg_match("/^(Active|ActiveProfile|Suspended|SuspendedProfile)$/i", $paypal["STATUS"]))
													$response = array("response" => _x('Nothing to cancel. You have NO recurring fees.', "s2member-front", "s2member"), "error" => true);
											}
									}
								else if /* Special form for Updates. User/Member must be logged in. */($attr["update"])
									{
										if(!is_user_logged_in())
											$response = array("response" => sprintf(_x('You must <a href="%s" rel="nofollow">log in</a> to update your billing information.', "s2member-front", "s2member"), esc_attr(wp_login_url($_SERVER["REQUEST_URI"]))), "error" => true);

										else if(!is_object($user = wp_get_current_user()) || !($user_id = $user->ID) || !($subscr_id = get_user_option("s2member_subscr_id", $user_id)))
											$response = array("response" => _x('Nothing to update. You\'re NOT a paid Member.', "s2member-front", "s2member"), "error" => true);

										else if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_payflow_api_username"])
											{
												if(!($paypal = c_ws_plugin__s2member_pro_paypal_utilities::payflow_get_profile($subscr_id)))
													$response = array("response" => _x('Nothing to update. You have NO recurring fees. Or, your billing profile is no longer active. Please contact Support if you need assistance.', "s2member-front", "s2member"), "error" => true);

												else if(!empty($paypal["TENDER"]) && strtoupper($paypal["TENDER"]) === "P")
													$response = array("response" => sprintf(_x('Please <a href="%s" rel="nofollow">log in at PayPal</a> to update your billing information.', "s2member-front", "s2member"), esc_attr("https://".(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_sandbox"]) ? "www.sandbox.paypal.com" : "www.paypal.com")."/")), "error" => true);

												else if(!empty($paypal["STATUS"]) && preg_match("/^(Pending|PendingProfile)$/i", $paypal["STATUS"]))
													$response = array("response" => _x('<strong>Unable to update at this time.</strong> Your account is pending other changes. Please try again in 15 minutes.', "s2member-front", "s2member"), "error" => true);

												else if(empty($paypal["STATUS"]) || !preg_match("/^(Active|ActiveProfile|Suspended|SuspendedProfile)$/i", $paypal["STATUS"]))
													$response = array("response" => _x('Nothing to update. You have NO recurring fees. Or, your billing profile is no longer active. Please contact Support if you need assistance.', "s2member-front", "s2member"), "error" => true);
											}
										else if(is_array($paypal = array("PROFILEID" => $subscr_id, "METHOD" => "GetRecurringPaymentsProfileDetails")))
											{
												if(!($paypal = c_ws_plugin__s2member_paypal_utilities::paypal_api_response($paypal)) || !empty($paypal["__error"]) || empty($paypal["ACCT"]) || strlen($paypal["ACCT"]) !== 4)
													{
														if($paypal && empty($paypal["__error"]) && (empty($paypal["ACCT"]) || strlen($paypal["ACCT"]) !== 4))
															$response = array("response" => sprintf(_x('Please <a href="%s" rel="nofollow">log in at PayPal</a> to update your billing information.', "s2member-front", "s2member"), esc_attr("https://".(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_sandbox"]) ? "www.sandbox.paypal.com" : "www.paypal.com")."/")), "error" => true);

														else if($paypal && !empty($paypal["__error"]) && !empty($paypal["L_ERRORCODE0"]) && $paypal["L_ERRORCODE0"] === "11592")
															$response = array("response" => sprintf(_x('Please <a href="%s" rel="nofollow">log in at PayPal</a> to update your billing information.', "s2member-front", "s2member"), esc_attr("https://".(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_sandbox"]) ? "www.sandbox.paypal.com" : "www.paypal.com")."/")), "error" => true);

														else $response = array("response" => _x('Nothing to update. You have NO recurring fees. Or, your billing profile is no longer active. Please contact Support if you need assistance.', "s2member-front", "s2member"), "error" => true);
													}
												else if(!empty($paypal["STATUS"]) && preg_match("/^(Pending|PendingProfile)$/i", $paypal["STATUS"]))
													$response = array("response" => _x('<strong>Unable to update at this time.</strong> Your account is pending other changes. Please try again in 15 minutes.', "s2member-front", "s2member"), "error" => true);

												else if(empty($paypal["STATUS"]) || !preg_match("/^(Active|ActiveProfile|Suspended|SuspendedProfile)$/i", $paypal["STATUS"]))
													$response = array("response" => _x('Nothing to update. You have NO recurring fees. Or, your billing profile is no longer active. Please contact Support if you need assistance.', "s2member-front", "s2member"), "error" => true);
											}
									}
								else if /* Free Registration does not require attr validation. */($attr["register"])
									{
										if(!is_string($attr["level"]) || !is_numeric($attr["level"]))
											$response = array("response" => sprintf(_x('Invalid form configuration. Missing "level" attribute. Membership Level. Must be numeric [0-%s].', "s2member-admin", "s2member"), esc_html($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"])), "error" => true);

										else if($attr["level"] < 0 || $attr["level"] > $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"])
											$response = array("response" => sprintf(_x('Invalid form configuration. Invalid "level" attribute. Membership Level. Must be numeric [0-%s].', "s2member-admin", "s2member"), esc_html($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"])), "error" => true);

										else if($attr["ccaps"] && (!is_string($attr["ccaps"]) || (preg_replace("/^-all[\r\n\t\s;,]*/", "", str_replace("+", "", $attr["ccaps"])) && !preg_match("/^([a-z_0-9,]+)$/", preg_replace("/^-all[\r\n\t\s;,]*/", "", str_replace("+", "", $attr["ccaps"]))))))
											$response = array("response" => _x('Invalid form configuration. Invalid "ccaps" attribute. Custom Capabilities. When provided, must be all lowercase [a-z_0-9,]. A preceding `-all,` directive is also acceptable.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["tp"] && (!is_string($attr["tp"]) || !is_numeric($attr["tp"])))
											$response = array("response" => _x('Invalid form configuration. Invalid "tp" attribute. The Trial Period. When provided, must be numeric.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["tp"] && $attr["tp"] < 1)
											$response = array("response" => _x('Invalid form configuration. Invalid "tp" attribute. The Trial Period. When provided, must be >= 1.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["tp"] && (!$attr["tt"] || !is_string($attr["tt"])))
											$response = array("response" => _x('Invalid form configuration. Missing "tt" attribute. The Trial Term. When "tp" is provided, "tt" (Trial Term) must be one of D,W,M,Y.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["tp"] && !preg_match("/[DWMY]/", $attr["tt"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "tt" attribute. The Trial Term. When "tp" is provided, "tt" (Trial Term) must be one of D,W,M,Y.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["custom"] && (!is_string($attr["custom"]) || !preg_match("/^".preg_quote(preg_replace("/\:([0-9]+)$/", "", $_SERVER["HTTP_HOST"]), "/")."/i", $attr["custom"])))
											$response = array("response" => _x('Invalid form configuration. Invalid "custom" attribute. When provided, must start with your domain name.', "s2member-admin", "s2member-admin"), "error" => true);
									}
								else if /* Validation routines for Specific Post/Page checkout forms. */($attr["sp"])
									{
										if(!$attr["ids"] || !is_string($attr["ids"]))
											$response = array("response" => _x('Invalid form configuration. Missing "ids" attribute. Must contain comma-delimited Post/Page IDs.', "s2member-admin", "s2member"), "error" => true);

										else if(!preg_match("/^([0-9,]+)$/", $attr["ids"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "ids" attribute. Must contain comma-delimited Post/Page IDs. Must contain [0-9,] only.', "s2member-admin", "s2member"), "error" => true);

										else if(!$attr["exp"] || !is_string($attr["exp"]))
											$response = array("response" => _x('Invalid form configuration. Missing "exp" attribute. Specific Post/Page Expiration (in hours). Must be numeric.', "s2member-admin", "s2member"), "error" => true);

										else if(!is_numeric($attr["exp"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "exp" attribute. Specific Post/Page Expiration (in hours). Must be numeric.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["exp"] < 1)
											$response = array("response" => _x('Invalid form configuration. Invalid "exp" attribute. Specific Post/Page Expiration (in hours). Must be >= 1.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["exp"] > 438291)
											$response = array("response" => _x('Invalid form configuration. Invalid "exp" attribute. Specific Post/Page Expiration (in hours). Must be <= 438291.', "s2member-admin", "s2member"), "error" => true);

										else if(!$attr["sp_ids_exp"] || !is_string($attr["sp_ids_exp"]))
											$response = array("response" => _x('Invalid form configuration. Missing "sp_ids_exp" internal attribute. Please check Shortcode Attributes.', "s2member-admin", "s2member"), "error" => true);

										else if(!preg_match($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["sp_access_item_number_regex"], $attr["sp_ids_exp"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "sp_ids_exp" internal attribute. Please check Shortcode Attributes.', "s2member-admin", "s2member"), "error" => true);

										else if(!$attr["desc"] || !is_string($attr["desc"]))
											$response = array("response" => _x('Invalid form configuration. Missing "desc" attribute. Please provide a Description for this form.', "s2member-admin", "s2member"), "error" => true);

										else if(strlen($attr["desc"]) > 100 /* Actually, this can be 127 chars; but we need plenty of room for s2Member's coupon info. */)
											$response = array("response" => _x('Invalid form configuration. Your "desc" (Description) attribute must be <= 100 characters long.', "s2member-admin", "s2member"), "error" => true);

										else if(!$attr["custom"] || !is_string($attr["custom"]))
											$response = array("response" => _x('Invalid form configuration. Missing "custom" attribute. Must start with your domain name.', "s2member-admin", "s2member"), "error" => true);

										else if(!preg_match("/^".preg_quote(preg_replace("/\:([0-9]+)$/", "", $_SERVER["HTTP_HOST"]), "/")."/i", $attr["custom"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "custom" attribute. Must start with your domain name.', "s2member-admin", "s2member"), "error" => true);

										else if(!$attr["cc"] || !is_string($attr["cc"]))
											$response = array("response" => _x('Invalid form configuration. Missing "cc" attribute. Must be a 3 character Currency Code.', "s2member-admin", "s2member"), "error" => true);

										else if(strlen($attr["cc"]) !== 3)
											$response = array("response" => _x('Invalid form configuration. Invalid "cc" attribute. Must be a 3 character Currency Code.', "s2member-admin", "s2member"), "error" => true);

										else if(!strlen($attr["dg"]) || !is_string($attr["dg"]))
											$response = array("response" => _x('Invalid form configuration. Missing "dg" attribute. Digital indicator. Must be numeric [0-1].', "s2member-admin", "s2member"), "error" => true);

										else if($attr["dg"] < 0 || $attr["dg"] > 1)
											$response = array("response" => _x('Invalid form configuration. Invalid "dg" attribute. Digital indicator. Must be numeric [0-1].', "s2member-admin", "s2member"), "error" => true);

										else if(!strlen($attr["ns"]) || !is_string($attr["ns"]))
											$response = array("response" => _x('Invalid form configuration. Missing "ns" attribute. Shipping configuration. Must be numeric [0-2].', "s2member-admin", "s2member"), "error" => true);

										else if($attr["ns"] < 0 || $attr["ns"] > 2)
											$response = array("response" => _x('Invalid form configuration. Invalid "ns" attribute. Shipping configuration. Must be numeric [0-2].', "s2member-admin", "s2member"), "error" => true);

										else if($attr["dg"] && $attr["ns"] !== "1")
											$response = array("response" => _x('Invalid form configuration. Invalid "ns" attribute. Shipping configuration. Must be 1 with "dg" (digital) items.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["lc"] && strlen($attr["lc"]) !== 2)
											$response = array("response" => _x('Invalid form configuration. Invalid "lc" attribute. Locale Code. When provided, must be a 2 character country code.', "s2member-admin", "s2member"), "error" => true);

										else if(!strlen($attr["ra"]) || !is_string($attr["ra"]))
											$response = array("response" => _x('Invalid form configuration. Missing "ra" attribute. The Regular Amount. Must be >= 0.00.', "s2member-admin", "s2member"), "error" => true);

										else if(!is_numeric($attr["ra"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "ra" attribute. The Regular Amount. Must be numeric.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["ra"] < 0.00)
											$response = array("response" => _x('Invalid form configuration. Invalid "ra" attribute. The Regular Amount. Must be >= 0.00.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["ra"] > 10000.00 && strtoupper($attr["cc"]) === "USD")
											$response = array("response" => _x('Invalid form configuration. Invalid "ra" attribute. The Regular Amount. Must be <= 10000.00.', "s2member-admin", "s2member"), "error" => true);
									}
								else // Validation routines for Member Level checkout forms. This is the default functionality.
									{
										if /* Must be logged in before a modification can take place. */($attr["modify"] && !is_user_logged_in())
											$response = array("response" => sprintf(_x('You must <a href="%s" rel="nofollow">login</a> to update your billing plan.', "s2member-front", "s2member"), esc_attr(wp_login_url($_SERVER["REQUEST_URI"]))), "error" => true);

										else if /* Must be logged in before purchasing. */($attr["level"] === "*" && !is_user_logged_in())
											$response = array("response" => sprintf(_x('You must <a href="%s" rel="nofollow">login</a> before making this purchase.', "s2member-front", "s2member"), esc_attr(wp_login_url($_SERVER["REQUEST_URI"]))), "error" => true);

										else if((!$attr["level"] || !is_string($attr["level"]) || !is_numeric($attr["level"])) && $attr["level"] !== "*")
											$response = array("response" => sprintf(_x('Invalid form configuration. Missing "level" attribute. Membership Level. Must be numeric [1-%s], or an asterisk (*).', "s2member-admin", "s2member"), esc_html($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"])), "error" => true);

										else if(($attr["level"] < 1 || $attr["level"] > $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"]) && $attr["level"] !== "*")
											$response = array("response" => sprintf(_x('Invalid form configuration. Invalid "level" attribute. Membership Level. Must be numeric [1-%s], or an asterisk (*).', "s2member-admin", "s2member"), esc_html($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"])), "error" => true);

										else if($attr["ccaps"] && (!is_string($attr["ccaps"]) || (preg_replace("/^-all[\r\n\t\s;,]*/", "", str_replace("+", "", $attr["ccaps"])) && !preg_match("/^([a-z_0-9,]+)$/", preg_replace("/^-all[\r\n\t\s;,]*/", "", str_replace("+", "", $attr["ccaps"]))))))
											$response = array("response" => _x('Invalid form configuration. Invalid "ccaps" attribute. Custom Capabilities. When provided, must be all lowercase [a-z_0-9,]. A preceding `-all,` directive is also acceptable.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["level"] === "*" && (!is_string($attr["ccaps"]) || !preg_replace("/^-all[\r\n\t\s;,]*/", "", str_replace("+", "", $attr["ccaps"])) || !preg_match("/^([a-z_0-9,]+)$/", preg_replace("/^-all[\r\n\t\s;,]*/", "", str_replace("+", "", $attr["ccaps"])))))
											$response = array("response" => _x('Invalid form configuration. Missing or invalid "ccaps" attribute. When "level" is "*" for (Independent Custom Capabilities), "ccaps" is required. All lowercase [a-z_0-9,]. A preceding `-all,` directive is also acceptable.', "s2member-admin", "s2member"), "error" => true);

										else if(!$attr["desc"] || !is_string($attr["desc"]))
											$response = array("response" => _x('Invalid form configuration. Missing "desc" attribute. Please provide a Description for this form.', "s2member-admin", "s2member"), "error" => true);

										else if(strlen($attr["desc"]) > 100 /* Actually, this can be 127 chars; but we need plenty of room for s2Member's coupon info. */)
											$response = array("response" => _x('Invalid form configuration. Your "desc" (Description) attribute must be <= 100 characters long.', "s2member-admin", "s2member"), "error" => true);

										else if(!$attr["custom"] || !is_string($attr["custom"]))
											$response = array("response" => _x('Invalid form configuration. Missing "custom" attribute. Must start with your domain name.', "s2member-admin", "s2member"), "error" => true);

										else if(!preg_match("/^".preg_quote(preg_replace("/\:([0-9]+)$/", "", $_SERVER["HTTP_HOST"]), "/")."/i", $attr["custom"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "custom" attribute. Must start with matching domain.', "s2member-admin", "s2member"), "error" => true);

										else if(!$attr["cc"] || !is_string($attr["cc"]))
											$response = array("response" => _x('Invalid form configuration. Missing "cc" attribute. Must be a 3 character Currency Code.', "s2member-admin", "s2member"), "error" => true);

										else if(strlen($attr["cc"]) !== 3)
											$response = array("response" => _x('Invalid form configuration. Invalid "cc" attribute. Must be a 3 character Currency Code.', "s2member-admin", "s2member"), "error" => true);

										else if(!strlen($attr["dg"]) || !is_string($attr["dg"]))
											$response = array("response" => _x('Invalid form configuration. Missing "dg" attribute. Digital indicator. Must be numeric [0-1].', "s2member-admin", "s2member"), "error" => true);

										else if($attr["dg"] < 0 || $attr["dg"] > 1)
											$response = array("response" => _x('Invalid form configuration. Invalid "dg" attribute. Digital indicator. Must be numeric [0-1].', "s2member-admin", "s2member"), "error" => true);

										else if(!strlen($attr["ns"]) || !is_string($attr["ns"]))
											$response = array("response" => _x('Invalid form configuration. Missing "ns" attribute. Shipping configuration. Must be numeric [0-2].', "s2member-admin", "s2member"), "error" => true);

										else if($attr["ns"] < 0 || $attr["ns"] > 2)
											$response = array("response" => _x('Invalid form configuration. Invalid "ns" attribute. Shipping configuration. Must be numeric [0-2].', "s2member-admin", "s2member"), "error" => true);

										else if($attr["dg"] && $attr["ns"] !== "1")
											$response = array("response" => _x('Invalid form configuration. Invalid "ns" attribute. Shipping configuration. Must be 1 with "dg" (digital) items.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["lc"] && strlen($attr["lc"]) !== 2)
											$response = array("response" => _x('Invalid form configuration. Invalid "lc" attribute. Locale Code. When provided, must be a 2 character country code.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["tp"] && (!is_string($attr["tp"]) || !is_numeric($attr["tp"])))
											$response = array("response" => _x('Invalid form configuration. Invalid "tp" attribute. The Trial Period. When provided, must be numeric.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["tp"] && $attr["tp"] < 1)
											$response = array("response" => _x('Invalid form configuration. Invalid "tp" attribute. The Trial Period. When provided, must be >= 1.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["tp"] && (!$attr["tt"] || !is_string($attr["tt"])))
											$response = array("response" => _x('Invalid form configuration. Missing "tt" attribute. The Trial Term. When "tp" is provided, "tt" (Trial Term) must be one of D,W,M,Y.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["tp"] && !preg_match("/[DWMY]/", $attr["tt"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "tt" attribute. The Trial Term. When "tp" is provided, "tt" (Trial Term) must be one of D,W,M,Y.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["tp"] && $attr["ta"] && !is_numeric($attr["ta"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "ta" attribute. The Trial Amount. When provided, must be numeric.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["tp"] && $attr["ta"] && $attr["ta"] < 0.00)
											$response = array("response" => _x('Invalid form configuration. Invalid "ta" attribute. The Trial Amount. When provided, must be >= 0.00.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["tp"] && $attr["ta"] && $attr["ta"] > 10000.00 && strtoupper($attr["cc"]) === "USD")
											$response = array("response" => _x('Invalid form configuration. Invalid "ta" attribute. The Trial Amount. When provided, must be <= 10000.00.', "s2member-admin", "s2member"), "error" => true);

										else if(!$attr["rp"] || !is_string($attr["rp"]))
											$response = array("response" => _x('Invalid form configuration. Missing "rp" attribute. The Regular Period. Must be >= 1.', "s2member-admin", "s2member"), "error" => true);

										else if(!is_numeric($attr["rp"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "rp" attribute. The Regular Period. Must be numeric.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rp"] < 1)
											$response = array("response" => _x('Invalid form configuration. Invalid "rp" attribute. The Regular Period. Must be >= 1.', "s2member-admin", "s2member"), "error" => true);

										else if(!$attr["rt"] || !is_string($attr["rt"]))
											$response = array("response" => _x('Invalid form configuration. Missing "rt" attribute. The Regular Term. Must be one of D,W,M,Y,L.', "s2member-admin", "s2member"), "error" => true);

										else if(!preg_match("/[DWMYL]/", $attr["rt"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "rt" attribute. The Regular Term. Must be one of D,W,M,Y,L.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rt"] === "D" && $attr["rp"] > 365 && $attr["rr"] !== "BN")
											$response = array("response" => _x('Invalid form configuration. Invalid "rt, rp, rr" attributes. The "rt" (Regular Term) attribute is "D", "rp" (Regular Period) > 365, and "rr" is not "BN" (Buy Now).', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rt"] === "W" && $attr["rp"] > 52 && $attr["rr"] !== "BN")
											$response = array("response" => _x('Invalid form configuration. Invalid "rt, rp, rr" attributes. The "rt" (Regular Term) attribute is "W", "rp" (Regular Period) > 52, and "rr" is not "BN" (Buy Now).', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rt"] === "M" && $attr["rp"] > 12 && $attr["rr"] !== "BN")
											$response = array("response" => _x('Invalid form configuration. Invalid "rt, rp, rr" attributes. The "rt" (Regular Term) attribute is "M", "rp" (Regular Period) > 12, and "rr" is not "BN" (Buy Now).', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rr"] !== "BN" && $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_payflow_api_username"] && !in_array($attr["rp"]."-".$attr["rt"], array("1-D", "1-W", "2-W", "1-M", "3-M", "6-M", "1-Y"), TRUE)) // We allow daily here in case Payflow begins to support this in the future.
											$response = array("response" => _x('Invalid Payflow form configuration. Invalid "rt, rp, rr" attributes. Payflow supports a specific set of recurring intervals. Pro-Forms can be configured to charge: weekly, bi-weekly, monthly, quarterly, semi-yearly or yearly. Any other combination results in this error. This is a Payflow limitation.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rt"] === "Y" && $attr["rp"] > 5 && $attr["rr"] !== "BN")
											$response = array("response" => _x('Invalid form configuration. Invalid "rt, rp, rr" attributes. The "rt" (Regular Term) attribute is "Y", "rp" (Regular Period) > 5, and "rr" is not "BN" (Buy Now).', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rt"] === "Y" && $attr["rp"] > 1 && $attr["rr"] !== "BN")
											$response = array("response" => _x('Invalid form configuration. Invalid "rt, rp, rr" attributes. The "rt" (Regular Term) attribute is "Y", "rp" (Regular Period) > 1, and "rr" is not "BN" (Buy Now).', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rt"] === "L" && $attr["rp"] > 1)
											$response = array("response" => _x('Invalid form configuration. Invalid "rp, rt" attributes. The "rt" (Regular Term) attribute is "L" (Lifetime), and "rp" (Regular Period) > 1.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rt"] === "L" && $attr["rr"] !== "BN")
											$response = array("response" => _x('Invalid form configuration. Invalid "rt, rr" attributes. The "rt" (Regular Term) attribute is "L" (Lifetime), and "rr" is not "BN" (Buy Now).', "s2member-admin", "s2member"), "error" => true);

										else if(!$attr["level_ccaps_eotper"] || !is_string($attr["level_ccaps_eotper"]))
											$response = array("response" => _x('Invalid form configuration. Missing "level_ccaps_eotper" attribute. Please check Shortcode Attributes.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["level"] !== "*" && !preg_match($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["membership_item_number_w_level_regex"], $attr["level_ccaps_eotper"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "level_ccaps_eotper" attribute. Please check Shortcode Attributes.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["level"] === "*" && !preg_match($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["membership_item_number_wo_level_regex"], $attr["level_ccaps_eotper"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "level_ccaps_eotper" attribute. Please check Shortcode Attributes.', "s2member-admin", "s2member"), "error" => true);

										else if(!strlen($attr["ra"]) || !is_string($attr["ra"]))
											$response = array("response" => _x('Invalid form configuration. Missing "ra" attribute. The Regular Amount. Must be >= 0.00.', "s2member-admin", "s2member"), "error" => true);

										else if(!is_numeric($attr["ra"]))
											$response = array("response" => _x('Invalid form configuration. Invalid "ra" attribute. The Regular Amount. Must be numeric.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["ra"] < 0.00)
											$response = array("response" => _x('Invalid form configuration. Invalid "ra" attribute. The Regular Amount. Must be >= 0.00.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["ra"] > 10000.00 && strtoupper($attr["cc"]) === "USD")
											$response = array("response" => _x('Invalid form configuration. Invalid "ra" attribute. The Regular Amount. Must be <= 10000.00.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rr"] && (!is_string($attr["rr"]) || !preg_match("/^([0-1]|BN)$/", $attr["rr"])))
											$response = array("response" => _x('Invalid form configuration. Invalid "rr" attribute. Regular Recurring. When provided, must be 0, 1, or BN.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rr"] === "BN" && $attr["tp"])
											$response = array("response" => _x('Invalid form configuration. Invalid "rr, tp" attributes. The "rr" (Regular Recurring) attribute is "BN" (Buy Now), and "tp" (Trial Period) is not "0".', "s2member-admin", "s2member"), "error" => true);

										else if($attr["level"] === "*" && $attr["rr"] !== "BN")
											$response = array("response" => _x('Invalid form configuration. Invalid "level, rr" attributes. The "level" (Level) attribute is "*" for (Independent Custom Capabilities), and "rr" is not "BN" (Buy Now).', "s2member-admin", "s2member"), "error" => true);

										else if($attr["ra"] && $attr["ta"] === $attr["ra"] && $attr["tp"] === $attr["rp"] && $attr["tt"] === $attr["rt"])
											$response = array("response" => _x('Invalid form configuration. Invalid "ta, tp, tt" attributes. Trial Period. When provided, these cannot be exactly the same as your "ra, rp, rt" attributes.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rrt"] && (!is_string($attr["rrt"]) || !is_numeric($attr["rrt"])))
											$response = array("response" => _x('Invalid form configuration. Invalid "rrt" attribute. Recurring Times (fixed). When provided, must be numeric.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rrt"] && $attr["rrt"] < 1)
											$response = array("response" => _x('Invalid form configuration. Invalid "rrt" attribute. Recurring Times (fixed). When provided, must be >= 1.', "s2member-admin", "s2member"), "error" => true);

										else if($attr["rrt"] && $attr["rr"] !== "1")
											$response = array("response" => _x('Invalid form configuration. Invalid "rr, rrt" attributes. When "rrt" (Recurring Times) is provided, "rr" (Regular Recurring) must be 1.', "s2member-admin", "s2member"), "error" => true);

										else if(($attr["rr"] === "0" || $attr["rr"] === "1") && (!is_string($attr["rra"]) || !is_numeric($attr["rra"])))
											$response = array("response" => _x('Invalid form configuration. Invalid "rr, rra" attributes. When "rr" (Regular Recurring) is 0 or 1, "rra" (Recurring Retry Attempts) must be numeric.', "s2member-admin", "s2member"), "error" => true);

										else if(($attr["rr"] === "0" || $attr["rr"] === "1") && $attr["rra"] < 0)
											$response = array("response" => _x('Invalid form configuration. Invalid "rr, rra" attributes. When "rr" (Regular Recurring) is 0 or 1, "rra" (Recurring Retry Attempts) must be >= 0.', "s2member-admin", "s2member"), "error" => true);
									}
							}
						return (empty($response)) ? null : $response;
					}
				/**
				* Validates different kinds of form submissions.
				*
				* Free Registration Forms do NOT require API Credentials.
				*
				* @package s2Member\PayPal
				* @since 1.5
				*
				* @param string $form The type of Pro-Form being submitted.
				* @param array $s An array of data submitted through the Pro-Form.
				* @return null|array Null if there are no errors, else a response array.
				*/
				public static function paypal_form_submission_validation_errors($form = FALSE, $s = FALSE)
					{
						if($form === "registration" || !($response = c_ws_plugin__s2member_pro_paypal_responses::paypal_form_api_validation_errors()))
							{
								if /* Special form for Cancellations. User/Member must be logged in. */($form === "cancellation")
									{
										if(!is_user_logged_in())
											$response = array("response" => sprintf(_x('You must <a href="%s" rel="nofollow">log in</a> to cancel your account.', "s2member-front", "s2member"), esc_attr(wp_login_url($_SERVER["REQUEST_URI"]))), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if($s["attr"]["captcha"] && (empty($s["recaptcha_challenge_field"]) || empty($s["recaptcha_response_field"]) || !c_ws_plugin__s2member_utils_captchas::recaptcha_code_validates($s["recaptcha_challenge_field"], $s["recaptcha_response_field"])))
											$response = array("response" => _x('Missing or invalid Security Verification. Please try again.', "s2member-front", "s2member"), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if(is_object($user = wp_get_current_user()) && $user->ID && $user->has_cap("administrator")) // NOT for Administrators.
											$response = array("response" => _x('Unable to process. You are an Administrator. Stopping here for security. Otherwise, an Administrator could lose access.', "s2member-admin", "s2member"), "error" => true);
									}
								else if /* Special form for Updates. User/Member must be logged in. */($form === "update")
									{
										if(!is_user_logged_in())
											$response = array("response" => sprintf(_x('You must <a href="%s" rel="nofollow">log in</a> to update your billing information.', "s2member-front", "s2member"), esc_attr(wp_login_url($_SERVER["REQUEST_URI"]))), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if(is_object($user = wp_get_current_user()) && $user->ID && /* NOT for Administrators. */ $user->has_cap("administrator"))
											$response = array("response" => _x('Unable to process. You are an Administrator. Stopping here for security. Otherwise, an Administrator could lose access.', "s2member-admin", "s2member"), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if(empty($s["card_type"]) || !is_string($s["card_type"]))
											$response = array("response" => _x('Missing Card Type (Billing Method). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(!in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo", "PayPal")) || !is_array($s["attr"]["accept"]) || !in_array(strtolower($s["card_type"]), $s["attr"]["accept"]))
											$response = array("response" => _x('Invalid Card Type (Billing Method). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["card_number"]) || !is_string($s["card_number"])))
											$response = array("response" => _x('Missing Card Number. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["card_expiration"]) || !is_string($s["card_expiration"])))
											$response = array("response" => _x('Missing Card Expiration Date (mm/yyyy). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && !preg_match("/^[0-9]{2}\/[0-9]{4}$/", $s["card_expiration"]))
											$response = array("response" => _x('Invalid Card Expiration Date. Must be in this format (mm/yyyy). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["card_verification"]) || !is_string($s["card_verification"])))
											$response = array("response" => _x('Missing Card Verification Code. It\'s on the back of your Card. 3-4 digits. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Maestro", "Solo")) && (empty($s["card_start_date_issue_number"]) || !is_string($s["card_start_date_issue_number"])))
											$response = array("response" => _x('Missing Card Start Date, or Issue #. Required for Maestro/Solo. Please try again.', "s2member-front", "s2member"), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["street"]) || !is_string($s["street"])))
											$response = array("response" => _x('Missing Street Address. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["city"]) || !is_string($s["city"])))
											$response = array("response" => _x('Missing City/Town. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["state"]) || !is_string($s["state"])))
											$response = array("response" => _x('Missing State/Province. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["country"]) || !is_string($s["country"])))
											$response = array("response" => _x('Missing Country. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["zip"]) || !is_string($s["zip"])))
											$response = array("response" => _x('Missing Postal/Zip Code. Please try again.', "s2member-front", "s2member"), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if($s["attr"]["captcha"] && (empty($s["recaptcha_challenge_field"]) || empty($s["recaptcha_response_field"]) || !c_ws_plugin__s2member_utils_captchas::recaptcha_code_validates($s["recaptcha_challenge_field"], $s["recaptcha_response_field"])))
											$response = array("response" => _x('Missing or invalid Security Verification. Please try again.', "s2member-front", "s2member"), "error" => true);
									}
								else if /* Validation routines for free Registration forms. */($form === "registration")
									{
										if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_names"] && (empty($s["first_name"]) || !is_string($s["first_name"])))
											$response = array("response" => _x('Missing First Name. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_names"] && (empty($s["last_name"]) || !is_string($s["last_name"])))
											$response = array("response" => _x('Missing Last Name. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(empty($s["email"]) || !is_string($s["email"]))
											$response = array("response" => _x('Missing or invalid Email Address. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(!is_email($s["email"]))
											$response = array("response" => _x('Invalid Email Address. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(email_exists($s["email"]) && (!is_multisite() || !c_ws_plugin__s2member_utils_users::ms_user_login_email_can_join_blog(@$s["username"], $s["email"])))
											$response = array("response" => _x('That Email Address is already in use. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(empty($s["username"]) || !is_string($s["username"]) || empty($s["_o_username"]) || !is_string($s["_o_username"]))
											$response = array("response" => _x('Missing or invalid Username. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(!validate_username($s["username"]) || !validate_username($s["_o_username"]))
											$response = array("response" => _x('Invalid Username. Please try again. Use ONLY lowercase alphanumerics.', "s2member-front", "s2member"), "error" => true);

										else if(username_exists($s["username"]) && (!is_multisite() || !c_ws_plugin__s2member_utils_users::ms_user_login_email_can_join_blog($s["username"], $s["email"])))
											$response = array("response" => _x('That Username is already in use. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(is_multisite() && !c_ws_plugin__s2member_utils_users::ms_user_login_email_can_join_blog($s["username"], $s["email"]) && ($_response = wpmu_validate_user_signup($s["username"], $s["email"])) && is_wp_error($_errors = $_response["errors"]) && $_errors->get_error_message())
											$response = array("response" => $_errors->get_error_message(), "error" => true);

										else if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_password"] && (empty($s["password1"]) || !is_string($s["password1"])))
											$response = array("response" => _x('Missing Password. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_password"] && strlen($s["password1"]) < c_ws_plugin__s2member_user_securities::min_password_length())
											$response = array("response" => sprintf(_x('Invalid Password. Must be at least %1$s characters. Please try again.', "s2member-front", "s2member"), c_ws_plugin__s2member_user_securities::min_password_length()), "error" => true);

										else if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_password"] && strlen($s["password1"]) > 64)
											$response = array("response" => _x('Invalid Password. Max length is 64 characters. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_password"] && (empty($s["password2"]) || $s["password2"] !== $s["password1"]))
											$response = array("response" => _x('Password fields do NOT match. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(($custom_field_validation_errors = c_ws_plugin__s2member_custom_reg_fields::validation_errors(isset($s["custom_fields"]) ? $s["custom_fields"] : array(), c_ws_plugin__s2member_custom_reg_fields::custom_fields_configured_at_level($s["attr"]["level"], "registration", TRUE))))
											$response = array("response" => array_shift($custom_field_validation_errors), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if($s["attr"]["captcha"] && (empty($s["recaptcha_challenge_field"]) || empty($s["recaptcha_response_field"]) || !c_ws_plugin__s2member_utils_captchas::recaptcha_code_validates($s["recaptcha_challenge_field"], $s["recaptcha_response_field"])))
											$response = array("response" => _x('Missing or invalid Security Verification. Please try again.', "s2member-front", "s2member"), "error" => true);
									}
								else if /* Validation routines for Specific Post/Page checkout forms. */($form === "sp-checkout")
									{
										if(empty($s["first_name"]) || !is_string($s["first_name"]))
											$response = array("response" => _x('Missing First Name. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(empty($s["last_name"]) || !is_string($s["last_name"]))
											$response = array("response" => _x('Missing Last Name. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(empty($s["email"]) || !is_string($s["email"]))
											$response = array("response" => _x('Missing or invalid Email Address. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(!is_email($s["email"]))
											$response = array("response" => _x('Invalid Email Address. Please try again.', "s2member-front", "s2member"), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if(empty($s["card_type"]) || !is_string($s["card_type"]))
											$response = array("response" => _x('Missing Card Type (Billing Method). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(!in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo", "PayPal", "Free")))
											$response = array("response" => _x('Invalid Card Type (Billing Method). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo", "PayPal")) && (!is_array($s["attr"]["accept"]) || !in_array(strtolower($s["card_type"]), $s["attr"]["accept"])))
											$response = array("response" => _x('Invalid Card Type (Billing Method). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["card_number"]) || !is_string($s["card_number"])))
											$response = array("response" => _x('Missing Card Number. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["card_expiration"]) || !is_string($s["card_expiration"])))
											$response = array("response" => _x('Missing Card Expiration Date (mm/yyyy). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && !preg_match("/^[0-9]{2}\/[0-9]{4}$/", $s["card_expiration"]))
											$response = array("response" => _x('Invalid Card Expiration Date. Must be in this format (mm/yyyy). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["card_verification"]) || !is_string($s["card_verification"])))
											$response = array("response" => _x('Missing Card Verification Code. It\'s on the back of your Card. 3-4 digits. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Maestro", "Solo")) && (empty($s["card_start_date_issue_number"]) || !is_string($s["card_start_date_issue_number"])))
											$response = array("response" => _x('Missing Card Start Date, or Issue #. Required for Maestro/Solo. Please try again.', "s2member-front", "s2member"), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["street"]) || !is_string($s["street"])))
											$response = array("response" => _x('Missing Street Address. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["city"]) || !is_string($s["city"])))
											$response = array("response" => _x('Missing City/Town. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["state"]) || !is_string($s["state"])))
											$response = array("response" => _x('Missing State/Province. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["country"]) || !is_string($s["country"])))
											$response = array("response" => _x('Missing Country. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["zip"]) || !is_string($s["zip"])))
											$response = array("response" => _x('Missing Postal/Zip Code. Please try again.', "s2member-front", "s2member"), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if($s["attr"]["captcha"] && (empty($s["recaptcha_challenge_field"]) || empty($s["recaptcha_response_field"]) || !c_ws_plugin__s2member_utils_captchas::recaptcha_code_validates($s["recaptcha_challenge_field"], $s["recaptcha_response_field"])))
											$response = array("response" => _x('Missing or invalid Security Verification. Please try again.', "s2member-front", "s2member"), "error" => true);
									}
								else if /* Validation routines for Member Level checkout forms. This is the default functionality. */($form === "checkout")
									{
										if($s["attr"]["modify"] && !is_user_logged_in())
											$response = array("response" => sprintf(_x('You must <a href="%s" rel="nofollow">log in</a> to modify your billing plan.', "s2member-front", "s2member"), esc_attr(wp_login_url($_SERVER["REQUEST_URI"]))), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if($s["attr"]["level"] === "*" && !is_user_logged_in())
											$response = array("response" => sprintf(_x('You must <a href="%s" rel="nofollow">log in</a> before making this purchase.', "s2member-front", "s2member"), esc_attr(wp_login_url($_SERVER["REQUEST_URI"]))), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if(is_user_logged_in() && is_object($user = wp_get_current_user()) && $user->ID && $user->has_cap("administrator")) // NOT for Administrators.
											$response = array("response" => _x('Unable to process. You are an Administrator. Stopping here for security. Otherwise, an Administrator could lose access.', "s2member-admin", "s2member"), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if(empty($s["first_name"]) || !is_string($s["first_name"]))
											$response = array("response" => _x('Missing First Name. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(empty($s["last_name"]) || !is_string($s["last_name"]))
											$response = array("response" => _x('Missing Last Name. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(!is_user_logged_in() && (empty($s["email"]) || !is_string($s["email"])))
											$response = array("response" => _x('Missing or invalid Email Address. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(!is_user_logged_in() && !is_email($s["email"]))
											$response = array("response" => _x('Invalid Email Address. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(!is_user_logged_in() && email_exists($s["email"]) && (!is_multisite() || !c_ws_plugin__s2member_utils_users::ms_user_login_email_can_join_blog(@$s["username"], $s["email"])))
											$response = array("response" => _x('That Email Address is already in use. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(!is_user_logged_in() && (empty($s["username"]) || !is_string($s["username"]) || empty($s["_o_username"]) || !is_string($s["_o_username"])))
											$response = array("response" => _x('Missing or invalid Username. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(!is_user_logged_in() && (!validate_username($s["username"]) || !validate_username($s["_o_username"])))
											$response = array("response" => _x('Invalid Username. Please try again. Use ONLY lowercase alphanumerics.', "s2member-front", "s2member"), "error" => true);

										else if(!is_user_logged_in() && username_exists($s["username"]) && (!is_multisite() || !c_ws_plugin__s2member_utils_users::ms_user_login_email_can_join_blog($s["username"], $s["email"])))
											$response = array("response" => _x('That Username is already in use. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(!is_user_logged_in() && is_multisite() && !c_ws_plugin__s2member_utils_users::ms_user_login_email_can_join_blog($s["username"], $s["email"]) && ($_response = wpmu_validate_user_signup($s["username"], $s["email"])) && is_wp_error($_errors = $_response["errors"]) && $_errors->get_error_message())
											$response = array("response" => $_errors->get_error_message(), "error" => true);

										else if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_password"] && !is_user_logged_in() && (empty($s["password1"]) || !is_string($s["password1"])))
											$response = array("response" => _x('Missing Password. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_password"] && !is_user_logged_in() && strlen($s["password1"]) < c_ws_plugin__s2member_user_securities::min_password_length())
											$response = array("response" => sprintf(_x('Invalid Password. Must be at least %1$s characters. Please try again.', "s2member-front", "s2member"), c_ws_plugin__s2member_user_securities::min_password_length()), "error" => true);

										else if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_password"] && !is_user_logged_in() && strlen($s["password1"]) > 64)
											$response = array("response" => _x('Invalid Password. Max length is 64 characters. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_password"] && !is_user_logged_in() && (empty($s["password2"]) || $s["password2"] !== $s["password1"]))
											$response = array("response" => _x('Password fields do NOT match. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(!is_user_logged_in() && ($custom_field_validation_errors = c_ws_plugin__s2member_custom_reg_fields::validation_errors(isset($s["custom_fields"]) ? $s["custom_fields"] : array(), c_ws_plugin__s2member_custom_reg_fields::custom_fields_configured_at_level($s["attr"]["level"] === "*" ? "auto-detection" : $s["attr"]["level"], "registration", TRUE))))
											$response = array("response" => array_shift($custom_field_validation_errors), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if(empty($s["card_type"]) || !is_string($s["card_type"]))
											$response = array("response" => _x('Missing Card Type (Billing Method). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(!in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo", "PayPal", "Free")))
											$response = array("response" => _x('Invalid Card Type (Billing Method). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo", "PayPal")) && (!is_array($s["attr"]["accept"]) || !in_array(strtolower($s["card_type"]), $s["attr"]["accept"])))
											$response = array("response" => _x('Invalid Card Type (Billing Method). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["card_number"]) || !is_string($s["card_number"])))
											$response = array("response" => _x('Missing Card Number. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["card_expiration"]) || !is_string($s["card_expiration"])))
											$response = array("response" => _x('Missing Card Expiration Date (mm/yyyy). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && !preg_match("/^[0-9]{2}\/[0-9]{4}$/", $s["card_expiration"]))
											$response = array("response" => _x('Invalid Card Expiration Date. Must be in this format (mm/yyyy). Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["card_verification"]) || !is_string($s["card_verification"])))
											$response = array("response" => _x('Missing Card Verification Code. It\'s on the back of your Card. 3-4 digits. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Maestro", "Solo")) && (empty($s["card_start_date_issue_number"]) || !is_string($s["card_start_date_issue_number"])))
											$response = array("response" => _x('Missing Card Start Date, or Issue #. Required for Maestro/Solo. Please try again.', "s2member-front", "s2member"), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["street"]) || !is_string($s["street"])))
											$response = array("response" => _x('Missing Street Address. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["city"]) || !is_string($s["city"])))
											$response = array("response" => _x('Missing City/Town. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["state"]) || !is_string($s["state"])))
											$response = array("response" => _x('Missing State/Province. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["country"]) || !is_string($s["country"])))
											$response = array("response" => _x('Missing Country. Please try again.', "s2member-front", "s2member"), "error" => true);

										else if(in_array($s["card_type"], array("Visa", "MasterCard", "Discover", "Amex", "Maestro", "Solo")) && (empty($s["zip"]) || !is_string($s["zip"])))
											$response = array("response" => _x('Missing Postal/Zip Code. Please try again.', "s2member-front", "s2member"), "error" => true);
										// -----------------------------------------------------------------------------------------------------------------
										else if($s["attr"]["captcha"] && (empty($s["recaptcha_challenge_field"]) || empty($s["recaptcha_response_field"]) || !c_ws_plugin__s2member_utils_captchas::recaptcha_code_validates($s["recaptcha_challenge_field"], $s["recaptcha_response_field"])))
											$response = array("response" => _x('Missing or invalid Security Verification. Please try again.', "s2member-front", "s2member"), "error" => true);
									}
								else // Else we are dealing with an unknown form submission type.
								$response = array("response" => _x('Unknown form submission type. Please contact Support.', "s2member-front", "s2member"), "error" => true);
							}
						return apply_filters("ws_plugin__s2member_pro_paypal_form_submission_validation_response", ((empty($response)) ? null : $response), $form, $s);
					}
			}
	}
