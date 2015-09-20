<?php
/**
* ccBill IPN Handler (inner processing routines).
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
* @package s2Member\ccBill
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit ("Do not access this file directly.");

if (!class_exists ("c_ws_plugin__s2member_pro_ccbill_notify_in"))
	{
		/**
		* ccBill IPN Handler (inner processing routines).
		*
		* @package s2Member\ccBill
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_ccbill_notify_in
			{
				/**
				* Handles ccBill IPN URL processing.
				*
				* @package s2Member\ccBill
				* @since 1.5
				*
				* @attaches-to ``add_action("init");``
				*
				* @return null Or exits script execution after handling the Notification.
				*/
				public static function ccbill_notify ()
					{
						global /* For Multisite support. */ $current_site, $current_blog;

						if (isset ($_GET["s2member_pro_ccbill_notify"]) && strlen ($_GET["s2member_pro_ccbill_notify"]) && $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_ccbill_client_id"])
							{
								@ignore_user_abort (true); // Continue processing even if/when connection is broken by the sender.

								if (is_array($ccbill = c_ws_plugin__s2member_pro_ccbill_utilities::ccbill_postvars ()) && ($_ccbill = $ccbill))
									{
										$ccbill["s2member_log"][] = "IPN received on: " . date ("D M j, Y g:i:s a T");
										$ccbill["s2member_log"][] = "s2Member POST vars verified with ccBill.";

										if (!$ccbill["denialId"] && $ccbill["subscription_id"] && !$ccbill["recurringPeriod"])
											{
												$ccbill["s2member_log"][] = "ccBill transaction identified as ( `NON-RECURRING/BUY-NOW` ).";
												$ccbill["s2member_log"][] = "IPN reformulated. Piping through s2Member's core/standard PayPal processor as `txn_type` ( `web_accept` ).";
												$ccbill["s2member_log"][] = "Please check PayPal IPN logs for further processing details.";

												$processing = $processed = true;
												$ipn = array(); // Reset.

												$ipn["txn_type"] = "web_accept";

												$ipn["txn_id"] = $ccbill["subscription_id"];

												$ipn["custom"] = $ccbill["s2_custom"];

												$ipn["mc_gross"] = number_format ($ccbill["initialPrice"], 2, ".", "");
												$ipn["mc_currency"] = c_ws_plugin__s2member_pro_ccbill_utilities::ccbill_currency_code ($ccbill["currencyCode"]);
												$ipn["tax"] = number_format ("0.00", 2, ".", "");

												$ipn["payer_email"] = $ccbill["email"];
												$ipn["first_name"] = $ccbill["customer_fname"];
												$ipn["last_name"] = $ccbill["customer_lname"];

												$ipn["option_name1"] = ($ccbill["s2_referencing"]) ? "Referencing Customer ID" : "Originating Domain";
												$ipn["option_selection1"] = ($ccbill["s2_referencing"]) ? $ccbill["s2_referencing"] : $_SERVER["HTTP_HOST"];

												$ipn["option_name2"] = "Customer IP Address";
												$ipn["option_selection2"] = $ccbill["s2_customer_ip"];

												$ipn["item_number"] = $ccbill["s2_invoice"];
												$ipn["item_name"] = $ccbill["s2_desc"];

												$ipn["s2member_paypal_proxy"] = "ccbill";
												$ipn["s2member_paypal_proxy_use"] = "standard-emails";
												$ipn["s2member_paypal_proxy_verification"] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

												c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20));
											}

										else if (!$ccbill["denialId"] && $ccbill["subscription_id"] && $ccbill["recurringPeriod"])
											{
												$ccbill["s2member_log"][] = "ccBill transaction identified as ( `RECURRING/SUBSCRIPTION` ).";
												$ccbill["s2member_log"][] = "IPN reformulated. Piping through s2Member's core/standard PayPal processor as `txn_type` ( `subscr_signup` ).";
												$ccbill["s2member_log"][] = "Please check PayPal IPN logs for further processing details.";

												$processing = $processed = true;
												$ipn = array(); // Reset.

												$ipn["txn_type"] = "subscr_signup";
												$ipn["subscr_id"] = $ccbill["subscription_id"];
												$ipn["recurring"] = "1"; // Yes, recurring.

												$ipn["txn_id"] = $ccbill["subscription_id"];

												$ipn["custom"] = $ccbill["s2_custom"];

												$ipn["period1"] = $ccbill["s2_p1"];
												$ipn["period3"] = $ccbill["s2_p3"];

												$ipn["mc_amount1"] = number_format ($ccbill["initialPrice"], 2, ".", "");
												$ipn["mc_amount3"] = number_format ($ccbill["recurringPrice"], 2, ".", "");

												$ipn["mc_gross"] = (preg_match ("/^[1-9]/", $ipn["period1"])) ? $ipn["mc_amount1"] : $ipn["mc_amount3"];

												$ipn["mc_currency"] = c_ws_plugin__s2member_pro_ccbill_utilities::ccbill_currency_code ($ccbill["currencyCode"]);
												$ipn["tax"] = number_format ("0.00", 2, ".", "");

												$ipn["payer_email"] = $ccbill["email"];
												$ipn["first_name"] = $ccbill["customer_fname"];
												$ipn["last_name"] = $ccbill["customer_lname"];

												$ipn["option_name1"] = ($ccbill["s2_referencing"]) ? "Referencing Customer ID" : "Originating Domain";
												$ipn["option_selection1"] = ($ccbill["s2_referencing"]) ? $ccbill["s2_referencing"] : $_SERVER["HTTP_HOST"];

												$ipn["option_name2"] = "Customer IP Address";
												$ipn["option_selection2"] = $ccbill["s2_customer_ip"];

												$ipn["item_number"] = $ccbill["s2_invoice"];
												$ipn["item_name"] = $ccbill["s2_desc"];

												$ipn["s2member_paypal_proxy"] = "ccbill";
												$ipn["s2member_paypal_proxy_use"] = "standard-emails";
												$ipn["s2member_paypal_proxy_use"] .= ($ipn["mc_gross"] > 0) ? ",subscr-signup-as-subscr-payment" : "";
												$ipn["s2member_paypal_proxy_verification"] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

												c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20));
											}

										else if (!$processed) // If nothing was processed, here we add a message to the logs indicating the IPN was ignored.
											$ccbill["s2member_log"][] = "Ignoring this IPN request. The transaction does NOT require any action on the part of s2Member.";
									}
								else // Extensive log reporting here. This is an area where many site owners find trouble. Depending on server configuration; remote HTTPS connections may fail.
									{
										$ccbill["s2member_log"][] = "Unable to verify POST vars. This is most likely related to an invalid ccBill configuration. Please check: s2Member → ccBill Options.";
										$ccbill["s2member_log"][] = "If you're absolutely SURE that your ccBill configuration is valid, you may want to run some tests on your server, just to be sure \$_POST variables are populated, and that your server is able to connect to ccBill over an HTTPS connection.";
										$ccbill["s2member_log"][] = "s2Member uses the WP_Http class for remote connections; which will try to use cURL first, and then fall back on the FOPEN method when cURL is not available. On a Windows server, you may have to disable your cURL extension. Instead, set allow_url_fopen = yes in your php.ini file. The cURL extension (usually) does NOT support SSL connections on a Windows server.";
										$ccbill["s2member_log"][] = var_export ($_REQUEST, true); // Recording _POST + _GET vars for analysis and debugging.
									}
								/*
								If debugging/logging is enabled; we need to append $ccbill to the log file.
									Logging now supports Multisite Networking as well.
								*/
								$logt = c_ws_plugin__s2member_utilities::time_details ();
								$logv = c_ws_plugin__s2member_utilities::ver_details ();
								$logm = c_ws_plugin__s2member_utilities::mem_details ();
								$log4 = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . "\nUser-Agent: " . @$_SERVER["HTTP_USER_AGENT"];
								$log4 = (is_multisite () && !is_main_site ()) ? ($_log4 = $current_blog->domain . $current_blog->path) . "\n" . $log4 : $log4;
								$log2 = (is_multisite () && !is_main_site ()) ? "ccbill-ipn-4-" . trim (preg_replace ("/[^a-z0-9]/i", "-", $_log4), "-") . ".log" : "ccbill-ipn.log";

								if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["gateway_debug_logs"])
									if (is_dir ($logs_dir = $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["logs_dir"]))
										if (is_writable ($logs_dir) && c_ws_plugin__s2member_utils_logs::archive_oversize_log_files ())
											file_put_contents ($logs_dir . "/" . $log2,
											                   "LOG ENTRY: ".$logt . "\n" . $logv . "\n" . $logm . "\n" . $log4 . "\n" .
											                                            c_ws_plugin__s2member_utils_logs::conceal_private_info(var_export ($ccbill, true)) . "\n\n",
											                   FILE_APPEND);

								status_header (200); // Send a 200 OK status header.
								header ("Content-Type: text/plain; charset=UTF-8"); // Content-Type text/plain with UTF-8.
								while (@ob_end_clean ()); // Clean any existing output buffers.

								exit (); // Exit now.
							}
					}
			}
	}
