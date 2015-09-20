<?php
/**
* AliPay Return URL handler (inner processing routines).
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
	exit("Do not access this file directly.");

if (!class_exists ("c_ws_plugin__s2member_pro_alipay_return_in"))
	{
		/**
		* AliPay Return URL handler (inner processing routines).
		*
		* @package s2Member\AliPay
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_alipay_return_in
			{
				/**
				* Handles AliPay Return URL processing.
				*
				* @package s2Member\AliPay
				* @since 1.5
				*
				* @attaches-to ``add_action("init");``
				*
				* @return null Or exits script execution after redirection.
				*/
				public static function alipay_return ()
					{
						global /* For Multisite support. */ $current_site, $current_blog;

						if (!empty($_GET["s2member_pro_alipay_return"]) && $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_seller_email"])
							{
								if (is_array($alipay = c_ws_plugin__s2member_pro_alipay_utilities::alipay_postvars ()) && ($_alipay = $alipay))
									{
										$alipay["s2member_log"][] = "Return-Data received on: " . date ("D M j, Y g:i:s a T");
										$alipay["s2member_log"][] = "s2Member POST vars verified through a POST back to AliPay.";

										if (preg_match ("/^(TRADE_FINISHED|TRADE_SUCCESS)$/i", $alipay["trade_status"]) && !$alipay["refund_status"])
											{
												$alipay["s2member_log"][] = "AliPay transaction identified as ( `TRADE_FINISHED|TRADE_SUCCESS` ).";
												$alipay["s2member_log"][] = "Return-Data reformulated. Piping through s2Member's core/standard PayPal processor as `txn_type` ( `web_accept` ).";
												$alipay["s2member_log"][] = "Please check PayPal RTN logs for further processing details.";

												list ($alipay["invoice"], $alipay["item_number"], $alipay["referencing"], $alipay["customer_ip"]) = preg_split ("/~/", $alipay["out_trade_no"]);
												list ($alipay["first_name"], $alipay["last_name"]) = preg_split ("/@/", $alipay["buyer_email"], 2);

												$rtn = /* Reset. */ array();

												$rtn["txn_type"] = "web_accept";
												$rtn["txn_id"] = $alipay["trade_no"];
												$rtn["custom"] = $alipay["extra_common_param"];

												$rtn["mc_gross"] = number_format ($alipay["total_fee"], 2, ".", "");
												$rtn["mc_currency"] = "CNY"; // Yuan.
												$rtn["tax"] = "0"; // No tax.

												$rtn["payer_email"] = $alipay["buyer_email"];
												$rtn["first_name"] = $alipay["first_name"];
												$rtn["last_name"] = $alipay["last_name"];

												$rtn["option_name1"] = ($alipay["referencing"]) ? "Referencing Customer ID" : "Originating Domain";
												$rtn["option_selection1"] = ($alipay["referencing"]) ? $alipay["referencing"] : $_SERVER["HTTP_HOST"];

												$rtn["option_name2"] = "Customer IP Address";
												$rtn["option_selection2"] = $alipay["customer_ip"];

												$rtn["item_number"] = $alipay["item_number"];
												$rtn["item_name"] = $alipay["body"];

												$rtn_q = "&s2member_paypal_proxy=alipay&s2member_paypal_proxy_use=standard-emails";
												if (!empty /* Using a custom Return URL on success? */ ($_GET["s2member_pro_alipay_return_success"]))
													$rtn_q .= "&s2member_paypal_return_success=" . rawurlencode (trim (stripslashes ($_GET["s2member_pro_alipay_return_success"])));

												$rtn_r = add_query_arg (urlencode_deep ($rtn), home_url ("/?s2member_pro_alipay_return&s2member_paypal_return=1" . $rtn_q));
												$rtn_r = c_ws_plugin__s2member_utils_urls::add_s2member_sig ($rtn_r, "s2member_paypal_proxy_verification");

												$alipay["s2member_log"][] = /* Log the full Return redirection URL here. */ $rtn_r;

												wp_redirect /* Proxy this through s2Member's core PayPal processor. */($rtn_r);
											}
										else // Else we need to fail here. The AliPay status was completely unexpected.
											{
												$alipay["s2member_log"][] = "Unexpected status. The AliPay status did not match a required action.";

												$alipay["s2member_log"][] = "Redirecting Customer to the Home Page, due to an error that occurred.";

												echo '<script type="text/javascript">' . "\n";
												echo "alert('" . c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("ERROR: Unexpected status. Please contact Support for assistance.\n\nThe AliPay status did NOT match a required action.", "s2member-front", "s2member")) . "');" . "\n";
												echo "window.location = '" . c_ws_plugin__s2member_utils_strings::esc_js_sq (home_url ("/")) . "';";
												echo '</script>' . "\n";
											}
									}
								else // Extensive log reporting here. This is an area where many site owners find trouble. Depending on server configuration; remote HTTPS connections may fail.
									{
										$alipay["s2member_log"][] = "Unable to verify POST vars. This is most likely related to an invalid AliPay configuration. Please check: s2Member → AliPay Options.";
										$alipay["s2member_log"][] = "If you're absolutely SURE that your AliPay configuration is valid, you may want to run some tests on your server, just to be sure \$_POST variables are populated, and that your server is able to connect to AliPay over an HTTPS connection.";
										$alipay["s2member_log"][] = "s2Member uses the WP_Http class for remote connections; which will try to use cURL first, and then fall back on the FOPEN method when cURL is not available. On a Windows server, you may have to disable your cURL extension. Instead, set allow_url_fopen = yes in your php.ini file. The cURL extension (usually) does NOT support SSL connections on a Windows server.";
										$alipay["s2member_log"][] = var_export ($_REQUEST, true); // Recording _POST + _GET vars for analysis and debugging.

										$alipay["s2member_log"][] = "Redirecting Customer to the Home Page, due to an error that occurred.";

										echo '<script type="text/javascript">' . "\n";
										echo "alert('" . c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("ERROR: Unable to verify POST vars. Please contact Support for assistance.\n\nThis is most likely related to an invalid AliPay configuration. If you are the site owner, please check: s2Member → AliPay Options.", "s2member-front", "s2member")) . "');" . "\n";
										echo "window.location = '" . c_ws_plugin__s2member_utils_strings::esc_js_sq (home_url ("/")) . "';";
										echo '</script>' . "\n";
									}

								$logt = c_ws_plugin__s2member_utilities::time_details ();
								$logv = c_ws_plugin__s2member_utilities::ver_details ();
								$logm = c_ws_plugin__s2member_utilities::mem_details ();
								$log4 = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . "\nUser-Agent: " . @$_SERVER["HTTP_USER_AGENT"];
								$log4 = (is_multisite () && !is_main_site ()) ? ($_log4 = $current_blog->domain . $current_blog->path) . "\n" . $log4 : $log4;
								$log2 = (is_multisite () && !is_main_site ()) ? "alipay-rtn-4-" . trim (preg_replace ("/[^a-z0-9]/i", "-", $_log4), "-") . ".log" : "alipay-rtn.log";

								if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["gateway_debug_logs"])
									if (is_dir ($logs_dir = $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["logs_dir"]))
										if (is_writable ($logs_dir) && c_ws_plugin__s2member_utils_logs::archive_oversize_log_files ())
											file_put_contents ($logs_dir . "/" . $log2,
											                   "LOG ENTRY: ".$logt . "\n" . $logv . "\n" . $logm . "\n" . $log4 . "\n" .
											                                            c_ws_plugin__s2member_utils_logs::conceal_private_info(var_export ($alipay, true)) . "\n\n",
											                   FILE_APPEND);

								exit (); // Exit now.
							}
					}
			}
	}
