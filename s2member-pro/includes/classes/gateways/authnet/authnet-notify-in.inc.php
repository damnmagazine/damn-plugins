<?php
/**
 * Authorize.Net Silent Post *(aka: IPN)* (inner processing routines).
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
 * @package s2Member\AuthNet
 * @since 1.5
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit ('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_authnet_notify_in'))
{
	/**
	 * Authorize.Net Silent Post *(aka: IPN)* (inner processing routines).
	 *
	 * @package s2Member\AuthNet
	 * @since 1.5
	 */
	class c_ws_plugin__s2member_pro_authnet_notify_in
	{
		/**
		 * Handles Authorize.Net IPN URL processing.
		 *
		 * @package s2Member\AuthNet
		 * @since 1.5
		 *
		 * @attaches-to ``add_action('init');``
		 */
		public static function authnet_notify()
		{
			global $current_site, $current_blog; // For Multisite support.

			if(!empty($_GET['s2member_pro_authnet_notify']) && $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_authnet_api_login_id'])
			{
				@ignore_user_abort(TRUE); // Continue processing even if/when connection is broken by the sender.

				if(is_array($authnet = c_ws_plugin__s2member_pro_authnet_utilities::authnet_postvars()) && ($_authnet = $authnet))
				{
					$processing = $processed = FALSE; // Initialize these flags.

					$authnet['s2member_log'][] = 'IPN received on: '.date('D M j, Y g:i:s a T');
					$authnet['s2member_log'][] = 's2Member POST vars verified with Authorize.Net.';

					if($authnet['x_subscription_id'] && $authnet['x_subscription_paynum'] && $authnet['x_response_code'] === '1')
					{
						if(($_authnet = c_ws_plugin__s2member_pro_authnet_utilities::authnet_parse_arb_desc($authnet)) && ($authnet = $_authnet))
						{
							$authnet['s2member_log'][] = 'Authorize.Net transaction identified as (`ARB / PAYMENT #'.$authnet['x_subscription_paynum'].'`).';

							if(($user_id = c_ws_plugin__s2member_utils_users::get_user_id_with($authnet['x_subscription_id'])))
							{
								delete_user_option($user_id, 's2member_authnet_payment_failures');
								$authnet['s2member_log'][] = 'Successful payment. Resetting payment failures to `0` for this subscription.';
							}
							$authnet['s2member_log'][] = 'IPN reformulated. Piping through s2Member\'s core/standard PayPal processor as `txn_type` (`subscr_payment`).';
							$authnet['s2member_log'][] = 'Please check PayPal IPN logs for further processing details.';

							$processing = $processed = TRUE;
							$ipn        = array(); // Reset.

							$ipn['txn_type']  = 'subscr_payment';
							$ipn['subscr_id'] = $authnet['x_subscription_id'];
							$ipn['txn_id']    = $authnet['x_trans_id'];

							$ipn['custom'] = $authnet['s2_custom'];

							$ipn['mc_gross']    = number_format($authnet['x_amount'], 2, '.', '');
							$ipn['mc_currency'] = strtoupper((!empty($authnet['s2_currency']) ? $authnet['s2_currency'] : 'USD'));
							$ipn['tax']         = number_format($authnet['x_tax'], 2, '.', '');

							$ipn['payer_email'] = $authnet['x_email'];
							$ipn['first_name']  = $authnet['x_first_name'];
							$ipn['last_name']   = $authnet['x_last_name'];

							$ipn['option_name1']      = 'Referencing Customer ID';
							$ipn['option_selection1'] = $authnet['x_subscription_id'];

							$ipn['option_name2']      = 'Customer IP Address';
							$ipn['option_selection2'] = NULL;

							$ipn['item_number'] = $authnet['s2_invoice'];
							$ipn['item_name']   = $authnet['x_description'];

							$ipn['s2member_paypal_proxy']              = 'authnet';
							$ipn['s2member_paypal_proxy_use']          = 'pro-emails';
							$ipn['s2member_paypal_proxy_verification'] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

							c_ws_plugin__s2member_utils_urls::remote(home_url('/?s2member_paypal_notify=1'), $ipn, array('timeout' => 20));
						}
						else // Otherwise, we don't have enough information to reforumalte this IPN response. An error must be generated.
						{
							$authnet['s2member_log'][] = 'Authorize.Net transaction identified as (`ARB / PAYMENT #'.$authnet['x_subscription_paynum'].'`).';
							$authnet['s2member_log'][] = 'Ignoring this IPN. The transaction does NOT contain a valid reference value/desc.';
						}
					}
					else if($authnet['x_subscription_id'] && $authnet['x_subscription_paynum'] && preg_match('/^(2|3)$/', $authnet['x_response_code']))
					{
						if(($_authnet = c_ws_plugin__s2member_pro_authnet_utilities::authnet_parse_arb_desc($authnet)) && ($authnet = $_authnet))
						{
							if(($user_id = c_ws_plugin__s2member_utils_users::get_user_id_with($authnet['x_subscription_id'])))
							{
								if($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_authnet_max_payment_failures'] // Is this functionality enabled at all?
								   && ($current_payment_failures = get_user_option('s2member_authnet_payment_failures', $user_id)) + 1 >= $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_authnet_max_payment_failures']
								) // If a site owner limits payment failures, trigger an EOT when/if the max failed payments threshold is reached for this subscription.
								{
									$authnet['s2member_log'][] = 'Authorize.Net transaction identified as (`ARB / FAILED PAYMENT`).';
									$authnet['s2member_log'][] = 'This subscription has `'.$GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_authnet_max_payment_failures'].'` or more failed payments.';
									$authnet['s2member_log'][] = 'Max failed payments. IPN reformulated. Piping through s2Member\'s core/standard PayPal processor as `txn_type` (`subscr_eot`).';
									$authnet['s2member_log'][] = 'Please check PayPal IPN logs for further processing details.';

									$processing = $processed = TRUE;
									$ipn        = array(); // Reset.

									$ipn['txn_type']  = 'subscr_eot';
									$ipn['subscr_id'] = $authnet['x_subscription_id'];

									$ipn['custom'] = $authnet['s2_custom'];

									$ipn['period1'] = $authnet['s2_p1'];
									$ipn['period3'] = $authnet['s2_p3'];

									$ipn['payer_email'] = $authnet['x_email'];
									$ipn['first_name']  = $authnet['x_first_name'];
									$ipn['last_name']   = $authnet['x_last_name'];

									$ipn['option_name1']      = 'Referencing Customer ID';
									$ipn['option_selection1'] = $authnet['x_subscription_id'];

									$ipn['option_name2']      = 'Customer IP Address';
									$ipn['option_selection2'] = NULL;

									$ipn['item_number'] = $authnet['s2_invoice'];
									$ipn['item_name']   = $authnet['x_description'];

									$ipn['s2member_paypal_proxy']              = 'authnet';
									$ipn['s2member_paypal_proxy_use']          = 'pro-emails';
									$ipn['s2member_paypal_proxy_verification'] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

									c_ws_plugin__s2member_utils_urls::remote(home_url('/?s2member_paypal_notify=1'), $ipn, array('timeout' => 20));

									c_ws_plugin__s2member_pro_authnet_utilities::authnet_arb_response(array('x_method' => 'cancel', 'x_subscription_id' => $authnet['x_subscription_id']));
								}
								else // Bump the total number of failed payments by `1`; not at the limit yet.
								{
									$current_payment_failures = get_user_option('s2member_authnet_payment_failures', $user_id);
									update_user_option($user_id, 's2member_authnet_payment_failures', $current_payment_failures + 1);
									$authnet['s2member_log'][] = 'Bumping payment failures for subscription: `'.$authnet['x_subscription_id'].'`, to: `'.($current_payment_failures + 1).'`';
									$authnet['s2member_log'][] = 'Recording number of payment failures only. This does not require any action (at the moment) on the part of s2Member.';
								}
							}
							else
							{
								$authnet['s2member_log'][] = 'Authorize.Net transaction identified as (`ARB / FAILED PAYMENT`).';
								$authnet['s2member_log'][] = 'Ignoring this IPN. The transaction does NOT contain a valid reference to an existing user/member.';
							}
						}
						else // Otherwise, we don't have enough information to reforumalte this IPN response. An error must be generated.
						{
							$authnet['s2member_log'][] = 'Authorize.Net transaction identified as (`ARB / FAILED PAYMENT`).';
							$authnet['s2member_log'][] = 'Ignoring this IPN. The transaction does NOT contain a valid reference value/desc.';
						}
					}
					else if(!$processed) // If nothing was processed, here we add a message to the logs indicating the IPN was ignored.
						$authnet['s2member_log'][] = 'Ignoring this IPN. The transaction does NOT require any action on the part of s2Member.';
				}
				else // Extensive log reporting here. This is an area where many site owners find trouble. Depending on server configuration; remote HTTPS connections may fail.
				{
					$authnet['s2member_log'][] = 'Unable to verify POST vars. This is most likely related to an invalid Authorize.Net configuration. Please check: s2Member → Authorize.Net Options.';
					$authnet['s2member_log'][] = 'If you\'re absolutely SURE that your Authorize.Net configuration is valid, you may want to run some tests on your server, just to be sure \$_POST variables are populated, and that your server is able to connect to Authorize.Net over an HTTPS connection.';
					$authnet['s2member_log'][] = 's2Member uses the WP_Http class for remote connections; which will try to use cURL first, and then fall back on the FOPEN method when cURL is not available. On a Windows server, you may have to disable your cURL extension. Instead, set allow_url_fopen = yes in your php.ini file. The cURL extension (usually) does NOT support SSL connections on a Windows server.';
					$authnet['s2member_log'][] = var_export($_REQUEST, TRUE); // Recording _POST + _GET vars for analysis and debugging.
				}
				/*
				If debugging/logging is enabled; we need to append $authnet to the log file.
					Logging now supports Multisite Networking as well.
				*/
				$logt = c_ws_plugin__s2member_utilities::time_details();
				$logv = c_ws_plugin__s2member_utilities::ver_details();
				$logm = c_ws_plugin__s2member_utilities::mem_details();
				$log4 = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\n".'User-Agent: '.@$_SERVER['HTTP_USER_AGENT'];
				$log4 = (is_multisite() && !is_main_site()) ? ($_log4 = $current_blog->domain.$current_blog->path)."\n".$log4 : $log4;
				$log2 = (is_multisite() && !is_main_site()) ? 'authnet-ipn-4-'.trim(preg_replace('/[^a-z0-9]/i', '-', !empty($_log4) ? $_log4 : ''), '-').'.log' : 'authnet-ipn.log';

				if($GLOBALS['WS_PLUGIN__']['s2member']['o']['gateway_debug_logs'])
					if(is_dir($logs_dir = $GLOBALS['WS_PLUGIN__']['s2member']['c']['logs_dir']))
						if(is_writable($logs_dir) && c_ws_plugin__s2member_utils_logs::archive_oversize_log_files())
							file_put_contents($logs_dir.'/'.$log2,
							                  'LOG ENTRY: '.$logt."\n".$logv."\n".$logm."\n".$log4."\n".
							                  c_ws_plugin__s2member_utils_logs::conceal_private_info(var_export($authnet, TRUE))."\n\n",
							                  FILE_APPEND);

				status_header(200); // Send a 200 OK status header.
				header('Content-Type: text/plain; charset=UTF-8'); // Content-Type text/plain with UTF-8.
				while(@ob_end_clean()) ; // Clean any existing output buffers.

				exit (); // Exit now.
			}
		}
	}
}