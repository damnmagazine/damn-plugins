<?php
/**
 * Payflow Poll.
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
 * @package s2Member\PayPal
 * @since 120514
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_paypal_payflow_poll'))
{
	/**
	 * Payflow Poll.
	 *
	 * @package s2Member\PayPal
	 * @since 120514
	 */
	class c_ws_plugin__s2member_pro_paypal_payflow_poll
	{
		/**
		 * Connect to and process cancellations/refunds/chargebacks/etc via Payflow.
		 *
		 * s2Member's Auto EOT System must be enabled for this to work properly.
		 *
		 * If you have a HUGE userbase, increase the max IPNs per process.
		 * But NOTE, this runs ``$per_process`` *(per Blog)* on a Multisite Network.
		 * To increase, use: ``add_filter ('ws_plugin__s2member_pro_payflow_ipns_per_process');``.
		 *
		 * @package s2Member\PayPal
		 * @since 120514
		 *
		 * @attaches-to ``add_action('ws_plugin__s2member_after_auto_eot_system');``
		 *
		 * @param array $vars Expects an array of defined variables to be passed in by the Action Hook.
		 */
		public static function payflow_service($vars)
		{
			global $wpdb;
			/** @var $wpdb \wpdb */
			global $current_site, $current_blog;

			$counter = 0; // Initialize counter at zero.

			if($GLOBALS['WS_PLUGIN__']['s2member']['o']['paypal_payflow_api_username'])
			{
				$scan_time   = apply_filters('ws_plugin__s2member_pro_payflow_status_scan_time', strtotime('-1 day'), get_defined_vars());
				$per_process = apply_filters('ws_plugin__s2member_pro_payflow_ipns_per_process', $vars['per_process'], get_defined_vars());

				if(is_array($objs = $wpdb->get_results("SELECT `user_id` AS `ID` FROM `".$wpdb->usermeta."` WHERE `meta_key` = '".$wpdb->prefix."s2member_subscr_gateway' AND `meta_value` = 'paypal' AND `user_id` NOT IN(SELECT `user_id` FROM `".$wpdb->usermeta."` WHERE `meta_key` = '".$wpdb->prefix."s2member_last_status_scan' AND `meta_value` > '".esc_sql($scan_time)."')")))
				{
					foreach($objs as $obj /* Run through all of the Paid Member IDs that originated their Subscription through the PayPal gateway. */)
					{
						if(($user_id = $obj->ID) && ($counter = (int)$counter + 1)) // Update counter. Only run through X records; given by $per_process.
						{
							$processed = FALSE; // Initialize and/or reset all of these variables.
							unset($paypal, $subscr_id, $processing, $ipn, $log4, $_log4, $log2, $logs_dir);

							if(($subscr_id = get_user_option('s2member_subscr_id', $user_id)) && !get_user_option('s2member_auto_eot_time', $user_id))
							{
								if(($paypal = c_ws_plugin__s2member_pro_paypal_utilities::payflow_get_profile($subscr_id)))
								{
									$paypal['ipn_signup_vars'] = c_ws_plugin__s2member_utils_users::get_user_ipn_signup_vars(FALSE, $subscr_id);

									if($paypal['ipn_signup_vars'] && preg_match('/expired|too many failures/i', $paypal['STATUS']))
									{
										$paypal['s2member_log'][] = 'Payflow IPN via polling, processed on: '.date('D M j, Y g:i:s a T');

										$paypal['s2member_log'][] = 'Payflow transaction identified as ( `SUBSCRIPTION EXPIRATION` ).';
										$paypal['s2member_log'][] = 'IPN reformulated. Piping through s2Member\'s core/standard PayPal processor as `txn_type` ( `subscr_eot` ).';
										$paypal['s2member_log'][] = 'Please check PayPal IPN logs for further processing details.';

										$processing = $processed = TRUE;
										$ipn        = array(); // Reset.

										$ipn['txn_type']  = 'subscr_eot';
										$ipn['subscr_id'] = $paypal['ipn_signup_vars']['subscr_id'];

										$ipn['custom'] = $paypal['ipn_signup_vars']['custom'];

										$ipn['period1'] = $paypal['ipn_signup_vars']['period1'];
										$ipn['period3'] = $paypal['ipn_signup_vars']['period3'];

										$ipn['payer_email'] = $paypal['ipn_signup_vars']['payer_email'];
										$ipn['first_name']  = $paypal['ipn_signup_vars']['first_name'];
										$ipn['last_name']   = $paypal['ipn_signup_vars']['last_name'];

										$ipn['option_name1']      = $paypal['ipn_signup_vars']['option_name1'];
										$ipn['option_selection1'] = $paypal['ipn_signup_vars']['option_selection1'];

										$ipn['option_name2']      = $paypal['ipn_signup_vars']['option_name2'];
										$ipn['option_selection2'] = $paypal['ipn_signup_vars']['option_selection2'];

										$ipn['item_number'] = $paypal['ipn_signup_vars']['item_number'];
										$ipn['item_name']   = $paypal['ipn_signup_vars']['item_name'];

										$ipn['s2member_paypal_proxy']              = 'paypal';
										$ipn['s2member_paypal_proxy_use']          = 'pro-emails';
										$ipn['s2member_paypal_proxy_verification'] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

										c_ws_plugin__s2member_utils_urls::remote(home_url('/?s2member_paypal_notify=1'), $ipn, array('timeout' => 20));
									}
									else if($paypal['ipn_signup_vars'] && preg_match('/(suspended|canceled|terminated|deactivated)/i', $paypal['STATUS']))
									{
										$paypal['s2member_log'][] = 'Payflow IPN via polling, processed on: '.date('D M j, Y g:i:s a T');

										$paypal['s2member_log'][] = 'Payflow transaction identified as ( `SUBSCRIPTION '.strtoupper($paypal['STATUS']).'` ).';
										$paypal['s2member_log'][] = 'IPN reformulated. Piping through s2Member\'s core/standard PayPal processor as `txn_type` ( `subscr_cancel` ).';
										$paypal['s2member_log'][] = 'Please check PayPal IPN logs for further processing details.';

										$processing = $processed = TRUE;
										$ipn        = array(); // Reset.

										$ipn['txn_type']  = 'subscr_cancel';
										$ipn['subscr_id'] = $paypal['ipn_signup_vars']['subscr_id'];

										$ipn['custom'] = $paypal['ipn_signup_vars']['custom'];

										$ipn['period1'] = $paypal['ipn_signup_vars']['period1'];
										$ipn['period3'] = $paypal['ipn_signup_vars']['period3'];

										$ipn['payer_email'] = $paypal['ipn_signup_vars']['payer_email'];
										$ipn['first_name']  = $paypal['ipn_signup_vars']['first_name'];
										$ipn['last_name']   = $paypal['ipn_signup_vars']['last_name'];

										$ipn['option_name1']      = $paypal['ipn_signup_vars']['option_name1'];
										$ipn['option_selection1'] = $paypal['ipn_signup_vars']['option_selection1'];

										$ipn['option_name2']      = $paypal['ipn_signup_vars']['option_name2'];
										$ipn['option_selection2'] = $paypal['ipn_signup_vars']['option_selection2'];

										$ipn['item_number'] = $paypal['ipn_signup_vars']['item_number'];
										$ipn['item_name']   = $paypal['ipn_signup_vars']['item_name'];

										$ipn['s2member_paypal_proxy']              = 'paypal';
										$ipn['s2member_paypal_proxy_use']          = 'pro-emails';
										$ipn['s2member_paypal_proxy_verification'] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

										c_ws_plugin__s2member_utils_urls::remote(home_url('/?s2member_paypal_notify=1'), $ipn, array('timeout' => 20));
									}
									else if(!$processed && !$paypal['ipn_signup_vars'])
										$paypal['s2member_log'][] = 'Ignoring status (`'.$paypal['STATUS'].'`).'.
										                            ' The user has no IPN Signup Vars recorded on-site by s2Member.';

									else if(!$processed) $paypal['s2member_log'][] = 'Ignoring status (`'.$paypal['STATUS'].'`).'.
									                                                 ' It does NOT require any action on the part of s2Member.';

									$logt = c_ws_plugin__s2member_utilities::time_details();
									$logv = c_ws_plugin__s2member_utilities::ver_details();
									$logm = c_ws_plugin__s2member_utilities::mem_details();
									$log4 = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\n".'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
									$log4 = (is_multisite() && !is_main_site()) ? ($_log4 = $current_blog->domain.$current_blog->path)."\n".$log4 : $log4;
									$log2 = (is_multisite() && !is_main_site()) ? 'paypal-payflow-ipn-4-'.trim(preg_replace('/[^a-z0-9]/i', '-', (!empty($_log4) ? $_log4 : '')), '-').'.log' : 'paypal-payflow-ipn.log';

									if($GLOBALS['WS_PLUGIN__']['s2member']['o']['gateway_debug_logs'])
										if(is_dir($logs_dir = $GLOBALS['WS_PLUGIN__']['s2member']['c']['logs_dir']))
											if(is_writable($logs_dir) && c_ws_plugin__s2member_utils_logs::archive_oversize_log_files())
												file_put_contents($logs_dir.'/'.$log2,
												                  'LOG ENTRY: '.$logt."\n".$logv."\n".$logm."\n".$log4."\n".
												                  c_ws_plugin__s2member_utils_logs::conceal_private_info(var_export($paypal, TRUE))."\n\n",
												                  FILE_APPEND);
								}
							}
							update_user_option($user_id, 's2member_last_status_scan', time());

							if($counter >= $per_process) // Only this many.
								break; // Break the loop now.
						}
					}
				}
			}
		}
	}
}