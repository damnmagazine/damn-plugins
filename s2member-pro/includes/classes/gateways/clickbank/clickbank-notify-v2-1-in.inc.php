<?php
/**
 * ClickBank IPN v2.1 Handler (inner processing routines).
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
	exit ('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_clickbank_notify_v2_1_in'))
{
	/**
	 * ClickBank IPN Handler (inner processing routines).
	 *
	 * @package s2Member\ClickBank
	 * @since 1.5
	 */
	class c_ws_plugin__s2member_pro_clickbank_notify_v2_1_in
	{
		/**
		 * Handles ClickBank IPN URL processing.
		 *
		 * @package s2Member\ClickBank
		 * @since 1.5
		 *
		 * @attaches-to ``add_action('init');``
		 */
		public static function clickbank_notify()
		{
			global $current_site, $current_blog; // For Multisite support.

			if(!empty($_GET['s2member_pro_clickbank_notify']) && $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_clickbank_username'])
			{
				@ignore_user_abort(TRUE); // Continue processing even if/when connection is broken by the sender.

				if(is_array($clickbank = c_ws_plugin__s2member_pro_clickbank_utilities::clickbank_postvars_v2_1()) && ($_clickbank = $clickbank))
				{
					$clickbank['s2member_log'][] = 'IPN received on: '.date('D M j, Y g:i:s a T');
					$clickbank['s2member_log'][] = 's2Member POST vars verified with ClickBank.';

					$s2vars = c_ws_plugin__s2member_pro_clickbank_utilities::clickbank_parse_s2vars_v2_1($clickbank['cvendthru'], $clickbank['ctransaction']);

					if(isset ($s2vars['s2_p1'], $s2vars['s2_p3']) && $s2vars['s2_p1'] === '0 D') // No Trial defaults to Regular Period.
						$s2vars['s2_p1'] = $s2vars['s2_p3']; // Initial Period. No Trial defaults to Regular Period.

					$clickbank['s2vars'] = $s2vars; // So they appear in the log entry for this Notification.

					if(strcasecmp($clickbank['ccustfirstname'].' '.$clickbank['ccustlastname'], $clickbank['ccustfullname']) !== 0 && preg_match('/(?:[^ ]+)(?: +)(?:[^ ]+)/', $clickbank['ccustfullname']))
						list ($clickbank['ccustfirstname'], $clickbank['ccustlastname']) = preg_split('/ +/', $clickbank['ccustfullname'], 2);

					if(preg_match('/^(?:TEST_)?SALE$/i', $clickbank['ctransaction']) && preg_match('/^STANDARD$/i', $clickbank['cprodtype']))
					{
						$clickbank['s2member_log'][] = 'ClickBank transaction identified as ( `SALE/STANDARD` ).';
						$clickbank['s2member_log'][] = 'IPN reformulated. Piping through s2Member\'s core/standard PayPal processor as `txn_type` ( `web_accept` ).';
						$clickbank['s2member_log'][] = 'Please check PayPal IPN logs for further processing details.';

						$processing = $processed = TRUE;
						$ipn        = array(); // Reset.

						$ipn['txn_type'] = 'web_accept';

						$ipn['txn_id'] = $clickbank['ctransreceipt'];

						$ipn['custom'] = $s2vars['s2_custom'];

						$ipn['mc_gross']    = number_format($clickbank['corderamount'] / 100, 2, '.', '');
						$ipn['mc_currency'] = strtoupper($clickbank['ccurrency']);
						$ipn['tax']         = number_format('0.00', 2, '.', '');

						$ipn['payer_email'] = $clickbank['ccustemail'];
						$ipn['first_name']  = ucwords(strtolower($clickbank['ccustfirstname']));
						$ipn['last_name']   = ucwords(strtolower($clickbank['ccustlastname']));

						$ipn['option_name1']      = ($s2vars['s2_referencing']) ? 'Referencing Customer ID' : 'Originating Domain';
						$ipn['option_selection1'] = ($s2vars['s2_referencing']) ? $s2vars['s2_referencing'] : $_SERVER['HTTP_HOST'];

						$ipn['option_name2']      = 'Customer IP Address';
						$ipn['option_selection2'] = $s2vars['s2_customer_ip'];

						$ipn['item_number'] = $s2vars['s2_invoice'];
						$ipn['item_name']   = $s2vars['s2_desc'];

						$ipn['s2member_paypal_proxy']              = 'clickbank';
						$ipn['s2member_paypal_proxy_use']          = 'standard-emails';
						$ipn['s2member_paypal_proxy_verification'] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

						c_ws_plugin__s2member_utils_urls::remote(home_url('/?s2member_paypal_notify=1'), $ipn, array('timeout' => 20));
					}
					else if(preg_match('/^(?:TEST_)?SALE$/i', $clickbank['ctransaction']) && preg_match('/^RECURRING$/i', $clickbank['cprodtype']))
					{
						$clickbank['s2member_log'][] = 'ClickBank transaction identified as ( `SALE/RECURRING` ).';
						$clickbank['s2member_log'][] = 'IPN reformulated. Piping through s2Member\'s core/standard PayPal processor as `txn_type` ( `subscr_signup` ).';
						$clickbank['s2member_log'][] = 'Please check PayPal IPN logs for further processing details.';

						$processing = $processed = TRUE;
						$ipn        = array(); // Reset.

						$ipn['txn_type']  = 'subscr_signup';
						$ipn['subscr_id'] = $s2vars['s2_subscr_id'];
						$ipn['recurring'] = ($clickbank['cfuturepayments'] > 1) ? '1' : '0';

						$ipn['txn_id'] = $clickbank['ctransreceipt'];

						$ipn['custom'] = $s2vars['s2_custom'];

						$ipn['period1'] = $s2vars['s2_p1'];
						$ipn['period3'] = $s2vars['s2_p3'];

						$ipn['mc_amount1'] = number_format($clickbank['corderamount'] / 100, 2, '.', '');
						$ipn['mc_amount3'] = number_format($clickbank['crebillamnt'] / 100, 2, '.', '');

						$ipn['mc_gross'] = (preg_match('/^[1-9]/', $ipn['period1'])) ? $ipn['mc_amount1'] : $ipn['mc_amount3'];

						$ipn['mc_currency'] = strtoupper($clickbank['ccurrency']);
						$ipn['tax']         = number_format('0.00', 2, '.', '');

						$ipn['payer_email'] = $clickbank['ccustemail'];
						$ipn['first_name']  = ucwords(strtolower($clickbank['ccustfirstname']));
						$ipn['last_name']   = ucwords(strtolower($clickbank['ccustlastname']));

						$ipn['option_name1']      = ($s2vars['s2_referencing']) ? 'Referencing Customer ID' : 'Originating Domain';
						$ipn['option_selection1'] = ($s2vars['s2_referencing']) ? $s2vars['s2_referencing'] : $_SERVER['HTTP_HOST'];

						$ipn['option_name2']      = 'Customer IP Address';
						$ipn['option_selection2'] = $s2vars['s2_customer_ip'];

						$ipn['item_number'] = $s2vars['s2_invoice'];
						$ipn['item_name']   = $s2vars['s2_desc'];

						$ipn['s2member_paypal_proxy']     = 'clickbank';
						$ipn['s2member_paypal_proxy_use'] = 'standard-emails';
						$ipn['s2member_paypal_proxy_use'] .= ($ipn['mc_gross'] > 0) ? ',subscr-signup-as-subscr-payment' : '';
						$ipn['s2member_paypal_proxy_verification'] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

						c_ws_plugin__s2member_utils_urls::remote(home_url('/?s2member_paypal_notify=1'), $ipn, array('timeout' => 20));
					}
					else if(preg_match('/^(?:TEST_)?BILL$/i', $clickbank['ctransaction']) && preg_match('/^RECURRING$/i', $clickbank['cprodtype']))
					{
						$clickbank['s2member_log'][] = 'ClickBank transaction identified as ( `BILL/RECURRING` ).';
						$clickbank['s2member_log'][] = 'IPN reformulated. Piping through s2Member\'s core/standard PayPal processor as `txn_type` ( `subscr_payment` ).';
						$clickbank['s2member_log'][] = 'Please check PayPal IPN logs for further processing details.';

						$processing = $processed = TRUE;
						$ipn        = array(); // Reset.

						$ipn['txn_type']  = 'subscr_payment';
						$ipn['subscr_id'] = $s2vars['s2_subscr_id'];

						$ipn['txn_id'] = $clickbank['ctransreceipt'];

						$ipn['custom'] = $s2vars['s2_custom'];

						$ipn['mc_gross']    = number_format($clickbank['corderamount'] / 100, 2, '.', '');
						$ipn['mc_currency'] = strtoupper($clickbank['ccurrency']);
						$ipn['tax']         = number_format('0.00', 2, '.', '');

						$ipn['payer_email'] = $clickbank['ccustemail'];
						$ipn['first_name']  = ucwords(strtolower($clickbank['ccustfirstname']));
						$ipn['last_name']   = ucwords(strtolower($clickbank['ccustlastname']));

						$ipn['option_name1']      = ($s2vars['s2_referencing']) ? 'Referencing Customer ID' : 'Originating Domain';
						$ipn['option_selection1'] = ($s2vars['s2_referencing']) ? $s2vars['s2_referencing'] : $_SERVER['HTTP_HOST'];

						$ipn['option_name2']      = 'Customer IP Address';
						$ipn['option_selection2'] = $s2vars['s2_customer_ip'];

						$ipn['item_number'] = $s2vars['s2_invoice'];
						$ipn['item_name']   = $s2vars['s2_desc'];

						$ipn['s2member_paypal_proxy']              = 'clickbank';
						$ipn['s2member_paypal_proxy_use']          = 'standard-emails';
						$ipn['s2member_paypal_proxy_verification'] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

						c_ws_plugin__s2member_utils_urls::remote(home_url('/?s2member_paypal_notify=1'), $ipn, array('timeout' => 20));
					}
					else if(preg_match('/^(?:TEST_)?(?:RFND|CGBK|INSF)$/i', $clickbank['ctransaction'])) // Product Type irrelevant here; checked below.
					{
						$clickbank['s2member_log'][] = 'ClickBank transaction identified as ( `RFND|CGBK|INSF` ).';
						$clickbank['s2member_log'][] = 'IPN reformulated. Piping through s2Member\'s core/standard PayPal processor as `payment_status` ( `refunded|reversed` ).';
						$clickbank['s2member_log'][] = 'Please check PayPal IPN logs for further processing details.';

						$processing = $processed = TRUE;
						$ipn        = array(); // Reset.

						$ipn['payment_status'] = (preg_match('/^(?:TEST_)?RFND$/', $clickbank['ctransaction'])) ? 'refunded' : 'reversed';

						$ipn['parent_txn_id'] = (preg_match('/^RECURRING$/i', $clickbank['cprodtype']) && $s2vars['s2_subscr_id']) ? $s2vars['s2_subscr_id'] : $clickbank['ctransreceipt'];

						$ipn['custom'] = $s2vars['s2_custom'];

						$ipn['mc_fee']      = '-'.number_format('0.00', 2, '.', '');
						$ipn['mc_gross']    = '-'.number_format(abs($clickbank['corderamount']) / 100, 2, '.', '');
						$ipn['mc_currency'] = strtoupper($clickbank['ccurrency']);
						$ipn['tax']         = '-'.number_format('0.00', 2, '.', '');

						$ipn['payer_email'] = $clickbank['ccustemail'];
						$ipn['first_name']  = ucwords(strtolower($clickbank['ccustfirstname']));
						$ipn['last_name']   = ucwords(strtolower($clickbank['ccustlastname']));

						$ipn['option_name1']      = ($s2vars['s2_referencing']) ? 'Referencing Customer ID' : 'Originating Domain';
						$ipn['option_selection1'] = ($s2vars['s2_referencing']) ? $s2vars['s2_referencing'] : $_SERVER['HTTP_HOST'];

						$ipn['option_name2']      = 'Customer IP Address';
						$ipn['option_selection2'] = $s2vars['s2_customer_ip'];

						$ipn['item_number'] = $s2vars['s2_invoice'];
						$ipn['item_name']   = $s2vars['s2_desc'];

						$ipn['s2member_paypal_proxy']              = 'clickbank';
						$ipn['s2member_paypal_proxy_use']          = 'standard-emails';
						$ipn['s2member_paypal_proxy_verification'] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

						c_ws_plugin__s2member_utils_urls::remote(home_url('/?s2member_paypal_notify=1'), $ipn, array('timeout' => 20));
					}
					if( // Here we handle Recurring cancellations, and/or EOT (End Of Term) through $clickbank['crebillstatus'].
						(preg_match('/^(?:TEST_)?(?:SALE|BILL)$/i', $clickbank['ctransaction']) && preg_match('/^RECURRING$/i', $clickbank['cprodtype']) && (preg_match('/^COMPLETED$/i', $clickbank['crebillstatus']) || $clickbank['cfuturepayments'] <= 0) && apply_filters('c_ws_plugin__s2member_pro_clickbank_notify_handles_completions', TRUE, get_defined_vars()))
						|| (preg_match('/^(?:TEST_)?CANCEL-REBILL$/i', $clickbank['ctransaction']) && preg_match('/^RECURRING$/i', $clickbank['cprodtype']))
					)
					{
						$clickbank['s2member_log'][] = 'ClickBank transaction identified as ( `RECURRING/COMPLETED` or `CANCEL-REBILL` ).';
						$clickbank['s2member_log'][] = 'IPN reformulated. Piping through s2Member\'s core/standard PayPal processor as `txn_type` ( `subscr_cancel` ).';
						$clickbank['s2member_log'][] = 'Please check PayPal IPN logs for further processing details.';

						$processing = $processed = TRUE;
						$ipn        = array(); // Reset.

						$ipn['txn_type']  = 'subscr_cancel';
						$ipn['subscr_id'] = $s2vars['s2_subscr_id'];

						$ipn['custom'] = $s2vars['s2_custom'];

						$ipn['period1'] = $s2vars['s2_p1'];
						$ipn['period3'] = $s2vars['s2_p3'];

						$ipn['payer_email'] = $clickbank['ccustemail'];
						$ipn['first_name']  = ucwords(strtolower($clickbank['ccustfirstname']));
						$ipn['last_name']   = ucwords(strtolower($clickbank['ccustlastname']));

						$ipn['option_name1']      = ($s2vars['s2_referencing']) ? 'Referencing Customer ID' : 'Originating Domain';
						$ipn['option_selection1'] = ($s2vars['s2_referencing']) ? $s2vars['s2_referencing'] : $_SERVER['HTTP_HOST'];

						$ipn['option_name2']      = 'Customer IP Address';
						$ipn['option_selection2'] = $s2vars['s2_customer_ip'];

						$ipn['item_number'] = $s2vars['s2_invoice'];
						$ipn['item_name']   = $s2vars['s2_desc'];

						$ipn['s2member_paypal_proxy']              = 'clickbank';
						$ipn['s2member_paypal_proxy_use']          = 'standard-emails';
						$ipn['s2member_paypal_proxy_verification'] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

						c_ws_plugin__s2member_utils_urls::remote(home_url('/?s2member_paypal_notify=1'), $ipn, array('timeout' => 20));
					}
					if(empty($processed)) // If nothing was processed, here we add a message to the logs indicating the IPN was ignored.
						$clickbank['s2member_log'][] = 'Ignoring this IPN request. The transaction does NOT require any action on the part of s2Member.';
				}
				else // Extensive log reporting here. This is an area where many site owners find trouble. Depending on server configuration; remote HTTPS connections may fail.
				{
					$clickbank['s2member_log'][] = 'Unable to verify POST vars. This is most likely related to an invalid ClickBank configuration. Please check: s2Member → ClickBank Options.';
					$clickbank['s2member_log'][] = 'If you\'re absolutely SURE that your ClickBank configuration is valid, you may want to run some tests on your server, just to be sure $_POST variables are populated, and that your server is able to connect to ClickBank over an HTTPS connection.';
					$clickbank['s2member_log'][] = 's2Member uses the WP_Http class for remote connections; which will try to use cURL first, and then fall back on the FOPEN method when cURL is not available. On a Windows server, you may have to disable your cURL extension. Instead, set allow_url_fopen = yes in your php.ini file. The cURL extension (usually) does NOT support SSL connections on a Windows server.';
					$clickbank['s2member_log'][] = var_export($_REQUEST, TRUE); // Recording _POST + _GET vars for analysis and debugging.
				}
				/*
				If debugging/logging is enabled; we need to append $clickbank to the log file.
					Logging now supports Multisite Networking as well.
				*/
				$logt = c_ws_plugin__s2member_utilities::time_details();
				$logv = c_ws_plugin__s2member_utilities::ver_details();
				$logm = c_ws_plugin__s2member_utilities::mem_details();
				$log4 = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\n".'User-Agent: '.@$_SERVER['HTTP_USER_AGENT'];
				$log4 = (is_multisite() && !is_main_site()) ? ($_log4 = $current_blog->domain.$current_blog->path)."\n".$log4 : $log4;
				$log2 = (is_multisite() && !is_main_site()) ? 'clickbank-ipn-4-'.trim(preg_replace('/[^a-z0-9]/i', '-', !empty($_log4) ? $_log4 : ''), '-').'.log' : 'clickbank-ipn.log';

				if($GLOBALS['WS_PLUGIN__']['s2member']['o']['gateway_debug_logs'])
					if(is_dir($logs_dir = $GLOBALS['WS_PLUGIN__']['s2member']['c']['logs_dir']))
						if(is_writable($logs_dir) && c_ws_plugin__s2member_utils_logs::archive_oversize_log_files())
							file_put_contents($logs_dir.'/'.$log2,
							                  'LOG ENTRY: '.$logt."\n".$logv."\n".$logm."\n".$log4."\n".
							                  c_ws_plugin__s2member_utils_logs::conceal_private_info(var_export($clickbank, TRUE))."\n\n",
							                  FILE_APPEND);

				status_header(200); // Send a 200 OK status header.
				header('Content-Type: text/plain; charset=UTF-8'); // Content-Type text/plain with UTF-8.
				while(@ob_end_clean()) ; // Clean any existing output buffers.

				exit (); // Exit now.
			}
		}
	}
}