<?php
/**
 * Stripe Update Forms (inner processing routines).
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
 * @package s2Member\Stripe
 * @since 140617
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit ('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_stripe_update_in'))
{
	/**
	 * Stripe Update Forms (inner processing routines).
	 *
	 * @package s2Member\Stripe
	 * @since 140617
	 */
	class c_ws_plugin__s2member_pro_stripe_update_in
	{
		/**
		 * Handles processing of Pro-Form billing updates.
		 *
		 * @package s2Member\Stripe
		 * @since 140617
		 *
		 * @attaches-to ``add_action('init');``
		 */
		public static function stripe_update()
		{
			if(!empty($_POST['s2member_pro_stripe_update']['nonce'])
			   && ($nonce = $_POST['s2member_pro_stripe_update']['nonce'])
			   && wp_verify_nonce($nonce, 's2member-pro-stripe-update')
			)
			{
				$GLOBALS['ws_plugin__s2member_pro_stripe_update_response'] = array(); // This holds the global response details.
				$global_response                                           = & $GLOBALS['ws_plugin__s2member_pro_stripe_update_response'];

				$post_vars         = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST['s2member_pro_stripe_update']));
				$post_vars['attr'] = (!empty($post_vars['attr'])) ? (array)unserialize(c_ws_plugin__s2member_utils_encryption::decrypt($post_vars['attr'])) : array();
				$post_vars['attr'] = apply_filters('ws_plugin__s2member_pro_stripe_update_post_attr', $post_vars['attr'], get_defined_vars());

				$post_vars = c_ws_plugin__s2member_utils_captchas::recaptcha_post_vars($post_vars); // Collect reCAPTCHA™ post vars.

				if(!c_ws_plugin__s2member_pro_stripe_responses::stripe_form_attr_validation_errors($post_vars['attr']))
				{
					if(!($form_submission_validation_errors // Validate update input form fields.
						= c_ws_plugin__s2member_pro_stripe_responses::stripe_form_submission_validation_errors('update', $post_vars))
					) // If this fails the global response is set to the error(s) returned during form field validation.
					{
						if(is_user_logged_in() && ($user = wp_get_current_user()) && ($user_id = $user->ID)) // Are they logged in?
						{
							if(($cur__subscr_cid = get_user_option('s2member_subscr_cid')) && ($cur__subscr_id = get_user_option('s2member_subscr_id')))
							{
								if(is_object($stripe_subscription = c_ws_plugin__s2member_pro_stripe_utilities::get_customer_subscription($cur__subscr_cid, $cur__subscr_id)) && !preg_match('/^canceled$/i', $stripe_subscription->status) && !$stripe_subscription->cancel_at_period_end)
								{
									unset($_POST['s2member_pro_stripe_update']['source_token']); // These are good one-time only.
									unset($_POST['s2member_pro_stripe_update']['source_token_summary']);

									if(is_object($set_customer_source = c_ws_plugin__s2member_pro_stripe_utilities::set_customer_source($cur__subscr_cid, $post_vars['source_token'], $post_vars, $post_vars['attr']['reject_prepaid'])))
									{
										$global_response = array('response' => _x('<strong>Confirmed.</strong> Your billing information has been updated.', 's2member-front', 's2member'));

										if($post_vars['attr']['success']
										   && ($custom_success_url = str_ireplace(array('%%s_response%%', '%%response%%'), array(urlencode(c_ws_plugin__s2member_utils_encryption::encrypt($global_response['response'])), urlencode($global_response['response'])), $post_vars['attr']['success']))
										   && ($custom_success_url = trim(preg_replace('/%%(.+?)%%/i', '', $custom_success_url)))
										) wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig($custom_success_url, 's2p-v')).exit ();
									}
									else $global_response = array('response' => $set_customer_source, 'error' => TRUE);
								}
								else $global_response = array('response' => _x('<strong>Unable to update.</strong> You have NO recurring fees. Or, your billing profile is no longer active. Please contact Support if you need assistance.', 's2member-front', 's2member'), 'error' => TRUE);
							}
							else $global_response = array('response' => _x('<strong>Oops.</strong> No Customer|Subscr. ID. Please contact Support for assistance.', 's2member-front', 's2member'), 'error' => TRUE);
						}
						else $global_response = array('response' => _x('You\'re <strong>NOT</strong> logged in.', 's2member-front', 's2member'), 'error' => TRUE);
					}
					else // Input form field validation errors.
						$global_response = $form_submission_validation_errors;
				}
			}
		}
	}
}
