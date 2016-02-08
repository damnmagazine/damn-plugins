<?php
/**
 * ClickBank Return URL handler (inner processing routines).
 *
 * Copyright: © 2009-2011
 * {@link http://www.websharks-inc.com/ WebSharks, Inc.}
 * (coded in the USA)
 *
 * This WordPress plugin (s2Member Pro) is comprised of two parts:
 *
 * o (1) Its PHP code is licensed under the GPL license, as is WordPress.
 *   You should have received a copy of the GNU General Public License,
 *   along with this software. In the main directory, see: /licensing/
 *   If not, see: {@link http://www.gnu.org/licenses/}.
 *
 * o (2) All other parts of (s2Member Pro); including, but not limited to:
 *   the CSS code, some JavaScript code, images, and design;
 *   are licensed according to the license purchased.
 *   See: {@link http://www.s2member.com/prices/}
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
 * @package s2Member\ClickBank
 * @since 1.5
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_clickbank_return_in'))
{
	/**
	 * ClickBank Return URL handler (inner processing routines).
	 *
	 * @package s2Member\ClickBank
	 * @since 1.5
	 */
	class c_ws_plugin__s2member_pro_clickbank_return_in
	{
		/**
		 * Handles ClickBank Return URL processing.
		 *
		 * @package s2Member\ClickBank
		 * @since 1.5
		 *
		 * @attaches-to ``add_action('init');``
		 */
		public static function clickbank_return()
		{
			global $current_site, $current_blog; // For Multisite support.

			if(!empty($_GET['s2member_pro_clickbank_return']) && $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_clickbank_username'])
			{
				if(is_array($clickbank = c_ws_plugin__s2member_pro_clickbank_utilities::clickbank_postvars_v2_1()) && ($_clickbank = $clickbank))
				{
					$clickbank['s2member_log'][] = 'Return-Data received on: '.date('D M j, Y g:i:s a T');
					$clickbank['s2member_log'][] = 's2Member POST vars verified with ClickBank.';

					$clickbank['s2member_log'][] = 'Sleeping for 5 seconds. Giving ClickBank a chance to finalize processing.';
					sleep(5); // Sleep here to give ClickBank a chance to finalize processing. Allows the API call to succeed.
					$clickbank['s2member_log'][] = 'Awake. It\'s '.date('D M j, Y g:i:s a T').'. Processing will continue.';

					if(($order = c_ws_plugin__s2member_pro_clickbank_utilities::clickbank_api_order($clickbank['cbreceipt'])) && is_array($order))
					{
						$clickbank['s2member_log'][] = 'Order API variables have been obtained from ClickBank.';

						$s2vars = c_ws_plugin__s2member_pro_clickbank_utilities::clickbank_parse_s2vars_v2_1(http_build_query($clickbank, NULL, '&'), $order['txnType']);

						if(!empty($s2vars['s2_p1']) && !empty($s2vars['s2_p3']) && $s2vars['s2_p1'] === '0 D')
							// Initial Period. No Trial defaults to Regular Period.
							$s2vars['s2_p1'] = $s2vars['s2_p3'];

						$clickbank['s2vars'] = $s2vars; // So they appear in the log entry for this Notification.

						if(strcasecmp($order['firstName'].' '.$order['lastName'], $order['customerDisplayName']) !== 0 && preg_match('/([^ ]+)( +)([^ ]+)/', $order['customerDisplayName']))
							list($order['firstName'], $order['lastName']) = preg_split('/ +/', $order['customerDisplayName'], 2);

						if(preg_match('/^(TEST_)?SALE$/i', $order['txnType']) && empty($s2vars['s2_p1']) && empty($s2vars['s2_p3']))
						{
							$clickbank['s2member_log'][] = 'ClickBank transaction identified as (`SALE/STANDARD`).';
							$clickbank['s2member_log'][] = 'Return-Data reformulated. Piping through s2Member\'s core/standard PayPal processor as `txn_type` (`web_accept`).';
							$clickbank['s2member_log'][] = 'Please check PayPal RTN logs for further processing details.';

							$rtn = array(); // Reset.

							$rtn['txn_type'] = 'web_accept';

							$rtn['txn_id'] = $order['receipt'];
							$rtn['custom'] = $s2vars['s2_custom'];

							$rtn['mc_gross']    = number_format($order['amount'], 2, '.', '');
							$rtn['mc_currency'] = strtoupper($order['currency']);
							$rtn['tax']         = '0.00'; // No tax.

							$rtn['payer_email'] = $order['email'];
							$rtn['first_name']  = ucwords(strtolower($order['firstName']));
							$rtn['last_name']   = ucwords(strtolower($order['lastName']));

							$rtn['option_name1']      = !empty($s2vars['s2_referencing']) ? 'Referencing Customer ID' : 'Originating Domain';
							$rtn['option_selection1'] = !empty($s2vars['s2_referencing']) ? $s2vars['s2_referencing'] : $_SERVER['HTTP_HOST'];

							$rtn['option_name2']      = 'Customer IP Address';
							$rtn['option_selection2'] = $s2vars['s2_customer_ip'];

							$rtn['item_number'] = $s2vars['s2_invoice'];
							$rtn['item_name']   = $s2vars['s2_desc'];

							$rtn_q = '&s2member_paypal_proxy=clickbank&s2member_paypal_proxy_use=standard-emails';
							if(!empty($_GET['s2member_pro_clickbank_return_success'])) // Using a custom Return URL on success?
								$rtn_q .= '&s2member_paypal_return_success='.rawurlencode(trim(stripslashes($_GET['s2member_pro_clickbank_return_success'])));

							$rtn_r = add_query_arg(urlencode_deep($rtn), home_url('/?s2member_pro_clickbank_return&s2member_paypal_return=1'.$rtn_q));
							$rtn_r = c_ws_plugin__s2member_utils_urls::add_s2member_sig($rtn_r, 's2member_paypal_proxy_verification');

							$clickbank['s2member_log'][] = $rtn_r; // Log the full Return redirection URL here.

							wp_redirect($rtn_r); // Proxy this through s2Member's core PayPal processor.
						}
						else if(preg_match('/^(TEST_)?SALE$/i', $order['txnType']) && !empty($s2vars['s2_p1']) && !empty($s2vars['s2_p3']))
						{
							$clickbank['s2member_log'][] = 'ClickBank transaction identified as (`SALE/RECURRING`).';
							$clickbank['s2member_log'][] = 'Return-Data reformulated. Piping through s2Member\'s core/standard PayPal processor as `txn_type` (`subscr_signup`).';
							$clickbank['s2member_log'][] = 'Please check PayPal RTN logs for further processing details.';

							$rtn = array(); // Reset.

							$rtn['txn_type']  = 'subscr_signup';
							$rtn['subscr_id'] = $s2vars['s2_subscr_id'];
							$rtn['recurring'] = ($order['futurePayments'] > 1) ? '1' : '0';

							$rtn['txn_id'] = $order['receipt'];
							$rtn['custom'] = $s2vars['s2_custom'];

							$rtn['period1'] = $s2vars['s2_p1'];
							$rtn['period3'] = $s2vars['s2_p3'];

							$rtn['mc_amount1'] = number_format($order['amount'], 2, '.', '');
							$rtn['mc_amount3'] = @number_format($order['rebillAmount'], 2, '.', '');

							$rtn['mc_currency'] = strtoupper($order['currency']);
							$rtn['tax']         = '0.00'; // No tax.

							$rtn['payer_email'] = $order['email'];
							$rtn['first_name']  = ucwords(strtolower($order['firstName']));
							$rtn['last_name']   = ucwords(strtolower($order['lastName']));

							$rtn['option_name1']      = !empty($s2vars['s2_referencing']) ? 'Referencing Customer ID' : 'Originating Domain';
							$rtn['option_selection1'] = !empty($s2vars['s2_referencing']) ? $s2vars['s2_referencing'] : $_SERVER['HTTP_HOST'];

							$rtn['option_name2']      = 'Customer IP Address';
							$rtn['option_selection2'] = $s2vars['s2_customer_ip'];

							$rtn['item_number'] = $s2vars['s2_invoice'];
							$rtn['item_name']   = $s2vars['s2_desc'];

							$rtn_q = '&s2member_paypal_proxy=clickbank&s2member_paypal_proxy_use=standard-emails';
							if(!empty($_GET['s2member_pro_clickbank_return_success'])) // Using a custom Return URL on success?
								$rtn_q .= '&s2member_paypal_return_success='.rawurlencode(trim(stripslashes($_GET['s2member_pro_clickbank_return_success'])));

							$rtn_r = add_query_arg(urlencode_deep($rtn), home_url('/?s2member_pro_clickbank_return&s2member_paypal_return=1'.$rtn_q));
							$rtn_r = c_ws_plugin__s2member_utils_urls::add_s2member_sig($rtn_r, 's2member_paypal_proxy_verification');

							$clickbank['s2member_log'][] = $rtn_r; // Log the full Return redirection URL here.

							wp_redirect($rtn_r); // Proxy this through s2Member's core PayPal processor.
						}
						else // Else, we were unable to determine the txnType that is being handled here.
						{
							$clickbank['s2member_log'][] = 'Unexpected txnType. The ClickBank txnType did not match a required action.';

							$clickbank['s2member_log'][] = 'Redirecting Customer to the Home Page, due to an error that occurred.';

							echo '<script type="text/javascript">'."\n";
							echo "alert('".c_ws_plugin__s2member_utils_strings::esc_js_sq(_x('ERROR: Unexpected txnType. Please contact Support for assistance.\n\nThe ClickBank txnType did not match a required action.', 's2member-front', 's2member'))."');"."\n";
							echo "window.location = '".c_ws_plugin__s2member_utils_strings::esc_js_sq(home_url('/'))."';";
							echo '</script>'."\n";
						}
					}
					else // Sometimes it takes a few seconds for the ClickBank API to receive data for new orders. This is here in case that happens.
					{
						$clickbank['s2member_log'][] = 'Unable to obtain API vars. The ClickBank API may NOT have data for this order yet. Or, your ClickBank API Keys are NOT configured properly under `s2Member → ClickBank Options`.';
						$clickbank['s2member_log'][] = var_export($_REQUEST, TRUE); // Recording ``$_POST`` + ``$_GET`` vars for analysis and debugging.

						$clickbank['s2member_log'][] = 'Return-Data reformulated. Piping through s2Member\'s core/standard PayPal processor with `proxy_use` (`ty-email`).';
						$clickbank['s2member_log'][] = 'Please check PayPal RTN logs for further processing details.';

						$rtn_q = '&s2member_paypal_proxy=clickbank&s2member_paypal_proxy_use=standard-emails,ty-email';
						if(!empty ($_GET['s2member_pro_clickbank_return_success']) /* Using a custom Return URL on success? */)
							$rtn_q .= '&s2member_paypal_return_success='.rawurlencode(trim(stripslashes($_GET['s2member_pro_clickbank_return_success'])));

						$rtn_r = home_url('/?s2member_pro_clickbank_return&s2member_paypal_return=1'.$rtn_q);
						$rtn_r = c_ws_plugin__s2member_utils_urls::add_s2member_sig($rtn_r, 's2member_paypal_proxy_verification');

						$clickbank['s2member_log'][] = $rtn_r; // Log the full Return redirection URL here.

						wp_redirect($rtn_r); // Proxy this through s2Member's core PayPal processor.
					}
				}
				else // Extensive log reporting here. This is an area where many site owners find trouble. Depending on server configuration; remote HTTPS connections may fail.
				{
					$clickbank['s2member_log'][] = 'Unable to verify POST vars. This is most likely related to an invalid ClickBank configuration. Please check: s2Member → ClickBank Options.';
					$clickbank['s2member_log'][] = 'If you\'re absolutely SURE that your ClickBank configuration is valid, you may want to run some tests on your server, just to be sure \$_POST variables are populated, and that your server is able to connect to ClickBank over an HTTPS connection.';
					$clickbank['s2member_log'][] = 's2Member uses the WP_Http class for remote connections; which will try to use cURL first, and then fall back on the FOPEN method when cURL is not available. On a Windows server, you may have to disable your cURL extension. Instead, set allow_url_fopen = yes in your php.ini file. The cURL extension (usually) does NOT support SSL connections on a Windows server.';
					$clickbank['s2member_log'][] = var_export($_REQUEST, TRUE); // Recording _POST + _GET vars for analysis and debugging.

					$clickbank['s2member_log'][] = 'Redirecting Customer to the Home Page, due to an error that occurred.';

					echo '<script type="text/javascript">'."\n";
					echo "alert('".c_ws_plugin__s2member_utils_strings::esc_js_sq(_x('ERROR: Unable to verify POST vars. Please contact Support for assistance.\n\nThis is most likely related to an invalid ClickBank configuration. If you are the site owner, please check: s2Member → ClickBank Options.', 's2member-front', 's2member'))."');"."\n";
					echo "window.location = '".c_ws_plugin__s2member_utils_strings::esc_js_sq(home_url("/"))."';";
					echo '</script>'."\n";
				}
				$logt = c_ws_plugin__s2member_utilities::time_details();
				$logv = c_ws_plugin__s2member_utilities::ver_details();
				$logm = c_ws_plugin__s2member_utilities::mem_details();
				$log4 = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\n".'User-Agent: '.@$_SERVER['HTTP_USER_AGENT'];
				$log4 = (is_multisite() && !is_main_site()) ? ($_log4 = $current_blog->domain.$current_blog->path)."\n".$log4 : $log4;
				$log2 = (is_multisite() && !is_main_site()) ? 'clickbank-rtn-4-'.trim(preg_replace('/[^a-z0-9]/i', '-', !empty($_log4) ? $_log4 : ''), '-').'.log' : 'clickbank-rtn.log';

				if($GLOBALS['WS_PLUGIN__']['s2member']['o']['gateway_debug_logs'])
					if(is_dir($logs_dir = $GLOBALS['WS_PLUGIN__']['s2member']['c']['logs_dir']))
						if(is_writable($logs_dir) && c_ws_plugin__s2member_utils_logs::archive_oversize_log_files())
							file_put_contents($logs_dir.'/'.$log2,
							                  'LOG ENTRY: '.$logt."\n".$logv."\n".$logm."\n".$log4."\n".
							                  c_ws_plugin__s2member_utils_logs::conceal_private_info(var_export($clickbank, TRUE))."\n\n",
							                  FILE_APPEND);

				exit(); // Clean exit; all done here.
			}
		}
	}
}
