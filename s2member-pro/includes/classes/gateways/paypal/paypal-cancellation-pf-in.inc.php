<?php
/**
 * PayPal Cancellation Forms (inner processing routines).
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
 * @since 1.5
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_paypal_cancellation_pf_in'))
{
	/**
	 * PayPal Cancellation Forms (inner processing routines).
	 *
	 * @package s2Member\PayPal
	 * @since 1.5
	 */
	class c_ws_plugin__s2member_pro_paypal_cancellation_pf_in
	{
		/**
		 * Handles processing of Pro-Form cancellations.
		 *
		 * @package s2Member\PayPal
		 * @since 1.5
		 *
		 * @attaches-to ``add_action('init');``
		 *
		 * @return null Or exits script execution after a custom URL redirection.
		 */
		public static function paypal_cancellation()
		{
			if(!empty($_POST['s2member_pro_paypal_cancellation']['nonce'])
			   && ($nonce = $_POST['s2member_pro_paypal_cancellation']['nonce'])
			   && wp_verify_nonce($nonce, 's2member-pro-paypal-cancellation')
			)
			{
				$GLOBALS['ws_plugin__s2member_pro_paypal_cancellation_response'] = array(); // This holds the global response details.
				$global_response                                                 = & $GLOBALS['ws_plugin__s2member_pro_paypal_cancellation_response'];

				$post_vars         = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST['s2member_pro_paypal_cancellation']));
				$post_vars['attr'] = (!empty($post_vars['attr'])) ? (array)unserialize(c_ws_plugin__s2member_utils_encryption::decrypt($post_vars['attr'])) : array();
				$post_vars['attr'] = apply_filters('ws_plugin__s2member_pro_paypal_cancellation_post_attr', $post_vars['attr'], get_defined_vars());

				$post_vars = c_ws_plugin__s2member_utils_captchas::recaptcha_post_vars($post_vars); // Collect reCAPTCHA™ post vars.

				if(!c_ws_plugin__s2member_pro_paypal_responses::paypal_form_attr_validation_errors($post_vars['attr'])) // Must NOT have any attr errors.
				{
					if(!($error = c_ws_plugin__s2member_pro_paypal_responses::paypal_form_submission_validation_errors('cancellation', $post_vars)))
					{
						if(is_user_logged_in() && is_object($user = wp_get_current_user()) && ($user_id = $user->ID)) // Are they logged in?
						{
							if(($cur__subscr_id = get_user_option('s2member_subscr_id'))) // Does the customer have a Billing Profile?
							{
								if(($paypal = c_ws_plugin__s2member_pro_paypal_utilities::payflow_get_profile($cur__subscr_id)) && @$paypal['TENDER'] !== 'P')
								{
									if(preg_match('/^(Active|ActiveProfile)$/i', $paypal['STATUS'])) // Possible?
									{
										if(!($ipn = array())) // Build a simulated PayPal IPN response.
										{
											$ipn['txn_type']  = 'subscr_cancel';
											$ipn['subscr_id'] = $paypal['PROFILEID'];
											$ipn['custom']    = get_user_option('s2member_custom');

											$ipn['period1'] = c_ws_plugin__s2member_paypal_utilities::paypal_pro_period1($paypal);
											$ipn['period3'] = c_ws_plugin__s2member_paypal_utilities::paypal_pro_period3($paypal);

											$ipn['payer_email'] = $paypal['EMAIL'];
											$ipn['first_name']  = $paypal['NAME'];
											$ipn['last_name']   = $paypal['LASTNAME'];

											$ipn['option_name1']      = 'Referencing Customer ID';
											$ipn['option_selection1'] = $paypal['PROFILEID'];

											$ipn['option_name2']      = 'Customer IP Address'; // IP Address.
											$ipn['option_selection2'] = get_user_option('s2member_registration_ip');

											$ipn['item_name']   = !empty($paypal['DESC']) ? $paypal['DESC'] : $paypal['PROFILENAME'];
											$ipn['item_number'] = c_ws_plugin__s2member_paypal_utilities::paypal_pro_item_number($paypal);

											$ipn['s2member_paypal_proxy']              = 'paypal';
											$ipn['s2member_paypal_proxy_use']          = 'pro-emails';
											$ipn['s2member_paypal_proxy_verification'] = c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen();

											c_ws_plugin__s2member_utils_urls::remote(home_url('/?s2member_paypal_notify=1'), $ipn, array('timeout' => 20));
										}
										c_ws_plugin__s2member_pro_paypal_utilities::payflow_cancel_profile($paypal['PROFILEID'], !empty($paypal['BAID']) ? $paypal['BAID'] : '');

										$global_response = array('response' => _x('<strong>Billing termination confirmed.</strong> Your account has been cancelled.', 's2member-front', 's2member'));

										if($post_vars['attr']['success']
										   && ($custom_success_url = str_ireplace(array('%%s_response%%', '%%response%%'),
										                                          array(urlencode(c_ws_plugin__s2member_utils_encryption::encrypt($global_response['response'])),
										                                                urlencode($global_response['response'])), $post_vars['attr']['success']))
										   && ($custom_success_url = trim(preg_replace('/%%(.+?)%%/i', '', $custom_success_url)))
										)
											wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig($custom_success_url, 's2p-v')).exit();
									}
									else // Account already terminated in one way or another. Perhaps inactive.
									{
										$global_response = array('response' => _x('<strong>Billing terminated.</strong> Your account has been cancelled.', 's2member-front', 's2member'));

										if($post_vars['attr']['success']
										   && ($custom_success_url = str_ireplace(array('%%s_response%%', '%%response%%'),
										                                          array(urlencode(c_ws_plugin__s2member_utils_encryption::encrypt($global_response['response'])),
										                                                urlencode($global_response['response'])), $post_vars['attr']['success']))
										   && ($custom_success_url = trim(preg_replace('/%%(.+?)%%/i', '', $custom_success_url)))
										)
											wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig($custom_success_url, 's2p-v')).exit();
									}
								}
								else if($paypal && $paypal['TENDER'] === 'P')
								{
									$global_response = array('response' => sprintf(_x('Please <a href="%s" rel="nofollow">log in at PayPal</a> to cancel your Subscription.', 's2member-front', 's2member'), esc_attr('https://'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['paypal_sandbox']) ? 'www.sandbox.paypal.com' : 'www.paypal.com').'/cgi-bin/webscr?cmd=_subscr-find&amp;alias='.urlencode($GLOBALS['WS_PLUGIN__']['s2member']['o']['paypal_merchant_id']))), 'error' => TRUE);
								}
								else // Else, there is no Billing Profile.
								{
									$global_response = array('response' => _x('<strong>Billing terminated.</strong> Your account has been cancelled.', 's2member-front', 's2member'));

									if($post_vars['attr']['success']
									   && ($custom_success_url = str_ireplace(array('%%s_response%%', '%%response%%'),
									                                          array(urlencode(c_ws_plugin__s2member_utils_encryption::encrypt($global_response['response'])),
									                                                urlencode($global_response['response'])), $post_vars['attr']['success']))
									   && ($custom_success_url = trim(preg_replace('/%%(.+?)%%/i', '', $custom_success_url)))
									)
										wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig($custom_success_url, 's2p-v')).exit();
								}
							}
							else // Else, there is no Billing Profile.
							{
								$global_response = array('response' => _x('<strong>Billing terminated.</strong> Your account has been cancelled.', 's2member-front', 's2member'));

								if($post_vars['attr']['success']
								   && ($custom_success_url = str_ireplace(array('%%s_response%%', '%%response%%'),
								                                          array(urlencode(c_ws_plugin__s2member_utils_encryption::encrypt($global_response['response'])),
								                                                urlencode($global_response['response'])), $post_vars['attr']['success']))
								   && ($custom_success_url = trim(preg_replace('/%%(.+?)%%/i', '', $custom_success_url)))
								)
									wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig($custom_success_url, 's2p-v')).exit();
							}
							if($post_vars['attr']['unsub']) c_ws_plugin__s2member_list_servers::process_list_server_removals_against_current_user(TRUE);
						}
						else $global_response = array('response' => _x('You\'re <strong>NOT</strong> logged in.', 's2member-front', 's2member'), 'error' => TRUE);
					}
					else $global_response = $error;
				}
			}
		}
	}
}
