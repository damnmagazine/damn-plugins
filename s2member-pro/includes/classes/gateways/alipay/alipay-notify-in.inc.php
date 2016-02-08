<?php
/**
* AliPay IPN Handler (inner processing routines).
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
* @package s2Member\AliPay
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit ("Do not access this file directly.");

if (!class_exists ("c_ws_plugin__s2member_pro_alipay_notify_in"))
	{
		/**
		* AliPay IPN Handler (inner processing routines).
		*
		* @package s2Member\AliPay
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_alipay_notify_in
			{
				/**
				* Handles AliPay IPN URL processing.
				*
				* @package s2Member\AliPay
				* @since 1.5
				*
				* @attaches-to ``add_action("init");``
				*
				* @return null Or exits script execution after handling the Notification.
				*/
				public static function alipay_notify ()
					{
						global /* For Multisite support. */ $current_site, $current_blog;

						if (!empty($_POST["notify_type"]) && preg_match ("/^trade_status_sync$/i", $_POST["notify_type"]) && $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_seller_email"])
							{
								@ignore_user_abort (true); // Continue processing even if/when connection is broken by the sender.

								if (is_array($alipay = c_ws_plugin__s2member_pro_alipay_utilities::alipay_postvars ()) && ($_alipay = $alipay))
									{
										$alipay["s2member_log"][] = "IPN received on: " . date ("D M j, Y g:i:s a T");
										$alipay["s2member_log"][] = "s2Member POST vars verified through a POST back to AliPay.";

										if (!is_array($alipay_already_p = get_transient ("s2m_" . md5 ("s2member_pro_alipay_notify_ids"))) || !in_array($alipay["notify_id"], $alipay_already_p))
											{
												$alipay_already_p = (is_array($alipay_already_p)) ? array_push ($alipay_already_p, $alipay["notify_id"]) : array($alipay["notify_id"]);
												set_transient ("s2m_" . md5 ("s2member_pro_alipay_notify_ids"), array_slice (array_unique ($alipay_already_p), 0, 1000), 31556926);

												if (preg_match ("/^(TRADE_FINISHED|TRADE_SUCCESS)$/i", $alipay["trade_status"]) && !$alipay["refund_status"])
													{
														$alipay["s2member_log"][] = "AliPay transaction identified as ( `TRADE_FINISHED|TRADE_SUCCESS` ).";
														$alipay["s2member_log"][] = "IPN reformulated. Piping through s2Member's core/standard PayPal processor as `txn_type` ( `web_accept` ).";
														$alipay["s2member_log"][] = "Please check PayPal IPN logs for further processing details.";

														list ($alipay["invoice"], $alipay["item_number"], $alipay["referencing"], $alipay["customer_ip"]) = preg_split ("/~/", $alipay["out_trade_no"]);
														list ($alipay["first_name"], $alipay["last_name"]) = preg_split ("/@/", $alipay["buyer_email"], 2);

														$ipn = array(); // Reset.

														$ipn["txn_type"] = "web_accept";

														$ipn["txn_id"] = $alipay["trade_no"];

														$ipn["custom"] = $alipay["extra_common_param"];

														$ipn["mc_gross"] = number_format ($alipay["total_fee"], 2, ".", "");
														$ipn["mc_currency"] = strtoupper ("CNY"); // Yuan.
														$ipn["tax"] = number_format ("0.00", 2, ".", "");

														$ipn["payer_email"] = $alipay["buyer_email"];
														$ipn["first_name"] = $alipay["first_name"];
														$ipn["last_name"] = $alipay["last_name"];

														$ipn["option_name1"] = ($alipay["referencing"]) ? "Referencing Customer ID" : "Originating Domain";
														$ipn["option_selection1"] = ($alipay["referencing"]) ? $alipay["referencing"] : $_SERVER["HTTP_HOST"];

														$ipn["option_name2"] = "Customer IP Address";
														$ipn["option_selection2"] = $alipay["customer_ip"];

														$ipn["item_number"] = $alipay["item_number"];
														$ipn["item_name"] = $alipay["body"];

														$ipn["s2member_paypal_proxy"] = "alipay";
														$ipn["s2member_paypal_proxy_use"] = "standard-emails";
														$ipn["s2member_paypal_proxy_verification"] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

														c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20));
													}
												else if (preg_match ("/^(TRADE_CLOSED|TRADE_SUCCESS)$/i", $alipay["trade_status"]) && $alipay["refund_status"])
													{
														$alipay["s2member_log"][] = "AliPay transaction identified as ( `REFUND_SUCCESS` ).";
														$alipay["s2member_log"][] = "IPN reformulated. Piping through s2Member's core/standard PayPal processor as `payment_status` ( `refunded` ).";
														$alipay["s2member_log"][] = "Please check PayPal IPN logs for further processing details.";

														list ($alipay["invoice"], $alipay["item_number"], $alipay["referencing"], $alipay["customer_ip"]) = preg_split ("/~/", $alipay["out_trade_no"]);
														list ($alipay["first_name"], $alipay["last_name"]) = preg_split ("/@/", $alipay["buyer_email"], 2);

														$ipn = array(); // Reset.

														$ipn["payment_status"] = "refunded";

														$ipn["parent_txn_id"] = $alipay["trade_no"];

														$ipn["custom"] = $alipay["extra_common_param"];

														$ipn["mc_fee"] = "-" . number_format ("0.00", 2, ".", "");
														$ipn["mc_gross"] = "-" . number_format (abs ($alipay["total_fee"]), 2, ".", "");
														$ipn["mc_currency"] = strtoupper ("CNY"); // Yuan.
														$ipn["tax"] = "-" . number_format ("0.00", 2, ".", "");

														$ipn["payer_email"] = $alipay["buyer_email"];
														$ipn["first_name"] = $alipay["first_name"];
														$ipn["last_name"] = $alipay["last_name"];

														$ipn["option_name1"] = ($alipay["referencing"]) ? "Referencing Customer ID" : "Originating Domain";
														$ipn["option_selection1"] = ($alipay["referencing"]) ? $alipay["referencing"] : $_SERVER["HTTP_HOST"];

														$ipn["option_name2"] = "Customer IP Address";
														$ipn["option_selection2"] = $alipay["customer_ip"];

														$ipn["item_number"] = $alipay["item_number"];
														$ipn["item_name"] = $alipay["body"];

														$ipn["s2member_paypal_proxy"] = "alipay";
														$ipn["s2member_paypal_proxy_use"] = "standard-emails";
														$ipn["s2member_paypal_proxy_verification"] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

														c_ws_plugin__s2member_utils_urls::remote (home_url ("/?s2member_paypal_notify=1"), $ipn, array("timeout" => 20));
													}
												else
													$alipay["s2member_log"][] = "Ignoring this IPN request. The status does NOT require any action on the part of s2Member.";
											}
										else
											$alipay["s2member_log"][] = "Ignoring duplicate IPN. s2Member has already processed AliPay Notification ID: " . $alipay["notify_id"] . ".";
									}
								else // Extensive log reporting here. This is an area where many site owners find trouble. Depending on server configuration; remote HTTPS connections may fail.
									{
										$alipay["s2member_log"][] = "Unable to verify POST vars. This is most likely related to an invalid AliPay configuration. Please check: s2Member → AliPay Options.";
										$alipay["s2member_log"][] = "If you're absolutely SURE that your AliPay configuration is valid, you may want to run some tests on your server, just to be sure \$_POST variables are populated, and that your server is able to connect to AliPay over an HTTPS connection.";
										$alipay["s2member_log"][] = "s2Member uses the WP_Http class for remote connections; which will try to use cURL first, and then fall back on the FOPEN method when cURL is not available. On a Windows server, you may have to disable your cURL extension. Instead, set allow_url_fopen = yes in your php.ini file. The cURL extension (usually) does NOT support SSL connections on a Windows server.";
										$alipay["s2member_log"][] = var_export ($_REQUEST, true); // Recording _POST + _GET vars for analysis and debugging.
									}
								/*
								If debugging/logging is enabled; we need to append $alipay to the log file.
									Logging now supports Multisite Networking as well.
								*/
								$logt = c_ws_plugin__s2member_utilities::time_details ();
								$logv = c_ws_plugin__s2member_utilities::ver_details ();
								$logm = c_ws_plugin__s2member_utilities::mem_details ();
								$log4 = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . "\nUser-Agent: " . @$_SERVER["HTTP_USER_AGENT"];
								$log4 = (is_multisite () && !is_main_site ()) ? ($_log4 = $current_blog->domain . $current_blog->path) . "\n" . $log4 : $log4;
								$log2 = (is_multisite () && !is_main_site ()) ? "alipay-ipn-4-" . trim (preg_replace ("/[^a-z0-9]/i", "-", $_log4), "-") . ".log" : "alipay-ipn.log";

								if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["gateway_debug_logs"])
									if (is_dir ($logs_dir = $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["logs_dir"]))
										if (is_writable ($logs_dir) && c_ws_plugin__s2member_utils_logs::archive_oversize_log_files ())
											file_put_contents ($logs_dir . "/" . $log2,
											                   "LOG ENTRY: ".$logt . "\n" . $logv . "\n" . $logm . "\n" . $log4 . "\n" .
											                                            c_ws_plugin__s2member_utils_logs::conceal_private_info(var_export ($alipay, true)) . "\n\n",
											                   FILE_APPEND);

								status_header (200); // 200 OK status header.
								header ("Content-Type: text/plain; charset=UTF-8"); // Content-Type text/plain with UTF-8.
								while (@ob_end_clean ()); // Clean any existing output buffers.

								exit ("success"); // Exit now with "success".
							}
					}
			}
	}
