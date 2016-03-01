<?php
/**
* Google IPN Handler (inner processing routines).
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
* @package s2Member\Google
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit ("Do not access this file directly.");

if (!class_exists ("c_ws_plugin__s2member_pro_google_notify_in"))
	{
		/**
		* Google IPN Handler (inner processing routines).
		*
		* @package s2Member\Google
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_google_notify_in
			{
				/**
				* Handles Google IPN URL processing.
				*
				* @package s2Member\Google
				* @since 1.5
				*
				* @attaches-to ``add_action("init");``
				*
				* @return null Or exits script execution after handling the Notification.
				*/
				public static function google_notify ()
					{
						global /* For Multisite support. */ $current_site, $current_blog;

						if (!empty($_GET["s2member_pro_google_notify"]) && $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_google_merchant_id"])
							{
								@ignore_user_abort (true); // Continue processing even if/when connection is broken by the sender.

								if (is_array($google = c_ws_plugin__s2member_pro_google_utilities::google_postvars ()) && ($_google = $google))
									{
										$google["s2member_log"][] = "IPN received on: " . date ("D M j, Y g:i:s a T");
										$google["s2member_log"][] = "s2Member POST vars verified with Google.";

										if (!empty($google["typ"]) && preg_match ('/^google\/payments\/inapp\/item\/v[0-9]+\/postback\/buy$/i', $google["typ"])
										&& is_array($s2vars = c_ws_plugin__s2member_pro_google_utilities::google_parse_s2vars ($google))
										 && !empty($google["response"]["orderId"]))
											{
												$google["s2member_log"][] = "Google transaction identified as ( `SALE/BUY-NOW` ).";
												$google["s2member_log"][] = "IPN reformulated. Piping through s2Member's core/standard PayPal processor as `txn_type` ( `web_accept` ).";
												$google["s2member_log"][] = "Please check PayPal IPN logs for further processing details.";

												$processing = $processed = true;
												$ipn = array(); // Reset.

												$ipn["txn_type"] = "web_accept";
												$ipn["txn_id"] = $google["response"]["orderId"];
												$ipn["custom"] = $s2vars["cs"];

												$ipn["mc_gross"] = number_format ($google["request"]["price"], 2, ".", "");
												$ipn["mc_currency"] = strtoupper ($google["request"]["currencyCode"]);
												$ipn["tax"] = number_format ((float)@$google["request"]["tax"], 2, ".", "");

												$ipn["payer_email"] = $s2vars["em"];
												$ipn["first_name"] = $s2vars["fn"];
												$ipn["last_name"] = $s2vars["ln"];

												$ipn["option_name1"] = ($s2vars["rf"]) ? "Referencing Customer ID" : "Originating Domain";
												$ipn["option_selection1"] = ($s2vars["rf"]) ? $s2vars["rf"] : $_SERVER["HTTP_HOST"];

												$ipn["option_name2"] = "Customer IP Address"; // IP Address.
												$ipn["option_selection2"] = $s2vars["ip"];

												$ipn["item_number"] = $s2vars["in"];
												$ipn["item_name"] = $google["request"]["description"];

												$ipn["s2member_paypal_proxy"] = "google";
												$ipn["s2member_paypal_proxy_use"] = "standard-emails";
												$ipn["s2member_paypal_proxy_verification"] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

												c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20));
											}

										else if (!empty($google["typ"]) && preg_match ('/^google\/payments\/inapp\/subscription\/v[0-9]+\/postback\/buy$/i', $google["typ"])
										&& is_array($s2vars = c_ws_plugin__s2member_pro_google_utilities::google_parse_s2vars ($google))
										 && !empty($google["response"]["orderId"]))
											{
												$google["s2member_log"][] = "Google transaction identified as ( `SALE/SUBSCRIPTION` ).";
												$google["s2member_log"][] = "IPN reformulated. Piping through s2Member's core/standard PayPal processor as `txn_type` ( `subscr_signup` ).";
												$google["s2member_log"][] = "Please check PayPal IPN logs for further processing details.";

												$processing = $processed = true;
												$ipn = array(); // Reset.

												$ipn["txn_type"] = "subscr_signup";
												$ipn["subscr_id"] = $google["response"]["orderId"];
												$ipn["txn_id"] = $google["response"]["orderId"];
												$ipn["recurring"] = $s2vars["rr"];
												$ipn["custom"] = $s2vars["cs"];

												$ipn["period1"] = $s2vars["p1"];
												$ipn["period3"] = $s2vars["p3"];

												$ipn["mc_amount1"] = $ipn["mc_gross"] = number_format ($google["request"]["initialPayment"]["price"], 2, ".", "");
												$ipn["mc_currency"] = strtoupper ($google["request"]["initialPayment"]["currencyCode"]);
												$ipn["tax"] = number_format ((float)@$google["request"]["initialPayment"]["tax"], 2, ".", "");
												$ipn["mc_amount3"] = number_format ($google["request"]["recurrence"]["price"], 2, ".", "");

												$ipn["payer_email"] = $s2vars["em"];
												$ipn["first_name"] = $s2vars["fn"];
												$ipn["last_name"] = $s2vars["ln"];

												$ipn["option_name1"] = ($s2vars["rf"]) ? "Referencing Customer ID" : "Originating Domain";
												$ipn["option_selection1"] = ($s2vars["rf"]) ? $s2vars["rf"] : $_SERVER["HTTP_HOST"];

												$ipn["option_name2"] = "Customer IP Address"; // IP Address.
												$ipn["option_selection2"] = $s2vars["ip"];

												$ipn["item_number"] = $s2vars["in"];
												$ipn["item_name"] = $google["request"]["description"];

												$ipn["s2member_paypal_proxy"] = "google";
												$ipn["s2member_paypal_proxy_use"] = "standard-emails";
												$ipn["s2member_paypal_proxy_use"] .= ($ipn["mc_gross"] > 0) ? ",subscr-signup-as-subscr-payment" : "";
												$ipn["s2member_paypal_proxy_verification"] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

												c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20));
											}

										else if (!empty($google["typ"]) && preg_match ('/^google\/payments\/inapp\/subscription\/v[0-9]+\/canceled$/i', $google["typ"])
										&& !empty($google["response"]["statusCode"]) && preg_match ("/^SUBSCRIPTION_CANCELED$/i", $google["response"]["statusCode"])
										 && !empty($google["response"]["orderId"]) && ($ipn_signup_vars = c_ws_plugin__s2member_utils_users::get_user_ipn_signup_vars (false, $google["response"]["orderId"])))
											{
												$google["s2member_log"][] = "Google transaction identified as ( `SUBSCRIPTION_CANCELED` ).";
												$google["s2member_log"][] = "IPN reformulated. Piping through s2Member's core/standard PayPal processor as `txn_type` ( `subscr_cancel` ).";
												$google["s2member_log"][] = "Please check PayPal IPN logs for further processing details.";

												$processing = $processed = true;
												$ipn = array(); // Reset.

												$ipn["txn_type"] = "subscr_cancel";
												$ipn["subscr_id"] = $google["response"]["orderId"];

												$ipn["custom"] = $ipn_signup_vars["custom"];

												$ipn["period1"] = $ipn_signup_vars["period1"];
												$ipn["period3"] = $ipn_signup_vars["period3"];

												$ipn["payer_email"] = $ipn_signup_vars["payer_email"];
												$ipn["first_name"] = $ipn_signup_vars["first_name"];
												$ipn["last_name"] = $ipn_signup_vars["last_name"];

												$ipn["option_name1"] = $ipn_signup_vars["option_name1"];
												$ipn["option_selection1"] = $ipn_signup_vars["option_selection1"];

												$ipn["option_name2"] = $ipn_signup_vars["option_name2"];
												$ipn["option_selection2"] = $ipn_signup_vars["option_selection2"];

												$ipn["item_number"] = $ipn_signup_vars["item_number"];
												$ipn["item_name"] = $ipn_signup_vars["item_name"];

												$ipn["s2member_paypal_proxy"] = "google";
												$ipn["s2member_paypal_proxy_use"] = "standard-emails";
												$ipn["s2member_paypal_proxy_verification"] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

												c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20));
											}

										else if (!$processed) // If nothing was processed, here we add a message to the logs indicating the IPN was ignored.
											$google["s2member_log"][] = "Ignoring this IPN request. The transaction does NOT require any action on the part of s2Member.";
									}
								else // Extensive log reporting here. This is an area where many site owners find trouble. Depending on server configuration; remote HTTPS connections may fail.
									{
										$google["s2member_log"][] = "Unable to verify POST vars. This is most likely related to an invalid Google configuration. Please check: s2Member → Google Options.";
										$google["s2member_log"][] = "If you're absolutely SURE that your Google configuration is valid, you may want to run some tests on your server, just to be sure \$_POST variables are populated, and that your server is able to connect to Google over an HTTPS connection.";
										$google["s2member_log"][] = "s2Member uses the WP_Http class for remote connections; which will try to use cURL first, and then fall back on the FOPEN method when cURL is not available. On a Windows server, you may have to disable your cURL extension. Instead, set allow_url_fopen = yes in your php.ini file. The cURL extension (usually) does NOT support SSL connections on a Windows server.";
										$google["s2member_log"][] = var_export ($_REQUEST, true); // Recording _POST + _GET vars for analysis and debugging.
									}
								/*
								We need to log this final event before it occurs.
								*/
								$google["s2member_log"][] = "Sending Google an acknowlegment w/ order ID.";
								/*
								If debugging/logging is enabled; we need to append $google to the log file.
									Logging now supports Multisite Networking as well.
								*/
								$logt = c_ws_plugin__s2member_utilities::time_details ();
								$logv = c_ws_plugin__s2member_utilities::ver_details ();
								$logm = c_ws_plugin__s2member_utilities::mem_details ();
								$log4 = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . "\nUser-Agent: " . @$_SERVER["HTTP_USER_AGENT"];
								$log4 = (is_multisite () && !is_main_site ()) ? ($_log4 = $current_blog->domain . $current_blog->path) . "\n" . $log4 : $log4;
								$log2 = (is_multisite () && !is_main_site ()) ? "google-ipn-4-" . trim (preg_replace ("/[^a-z0-9]/i", "-", $_log4), "-") . ".log" : "google-ipn.log";

								if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["gateway_debug_logs"])
									if (is_dir ($logs_dir = $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["logs_dir"]))
										if (is_writable ($logs_dir) && c_ws_plugin__s2member_utils_logs::archive_oversize_log_files ())
											file_put_contents ($logs_dir . "/" . $log2,
											                   "LOG ENTRY: ".$logt . "\n" . $logv . "\n" . $logm . "\n" . $log4 . "\n" .
											                                            c_ws_plugin__s2member_utils_logs::conceal_private_info(var_export ($google, true)) . "\n\n",
											                   FILE_APPEND);

								status_header (200); // Send a 200 OK status header.
								header ("Content-Type: text/plain"); // Google expects text/plain here.
								while (@ob_end_clean ()); // Clean any existing output buffers.

								exit ((!empty($google["response"]["orderId"])) ? $google["response"]["orderId"] : "");
							}
					}
			}
	}
