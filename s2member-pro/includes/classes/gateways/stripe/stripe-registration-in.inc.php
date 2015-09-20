<?php
/**
 * Stripe Registration Form (inner processing routines).
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
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_stripe_registration_in'))
{
	/**
	 * Stripe Registration Form (inner processing routines).
	 *
	 * @package s2Member\Stripe
	 * @since 140617
	 */
	class c_ws_plugin__s2member_pro_stripe_registration_in
	{
		/**
		 * Handles processing of Pro-Form registrations.
		 *
		 * @package s2Member\Stripe
		 * @since 140617
		 *
		 * @attaches-to ``add_action('init');``
		 */
		public static function stripe_registration()
		{
			if(!empty($_POST['s2member_pro_stripe_registration']['nonce'])
			   && ($nonce = $_POST['s2member_pro_stripe_registration']['nonce'])
			   && wp_verify_nonce($nonce, 's2member-pro-stripe-registration')
			)
			{
				$GLOBALS['ws_plugin__s2member_pro_stripe_registration_response'] = array(); // This holds the global response details.
				$global_response                                                 = &$GLOBALS['ws_plugin__s2member_pro_stripe_registration_response'];

				$post_vars         = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST['s2member_pro_stripe_registration']));
				$post_vars['attr'] = (!empty($post_vars['attr'])) ? (array)unserialize(c_ws_plugin__s2member_utils_encryption::decrypt($post_vars['attr'])) : array();
				$post_vars['attr'] = apply_filters('ws_plugin__s2member_pro_stripe_registration_post_attr', $post_vars['attr'], get_defined_vars());

				$post_vars['name']     = trim($post_vars['first_name'].' '.$post_vars['last_name']);
				$post_vars['email']    = apply_filters('user_registration_email', sanitize_email($post_vars['email']), get_defined_vars());
				$post_vars['username'] = (is_multisite()) ? strtolower($post_vars['username']) : $post_vars['username']; // Force lowercase.
				$post_vars['username'] = sanitize_user(($post_vars['_o_username'] = $post_vars['username']), is_multisite());

				$post_vars = c_ws_plugin__s2member_utils_captchas::recaptcha_post_vars($post_vars); // Collect reCAPTCHA™ post vars.

				if(!c_ws_plugin__s2member_pro_stripe_responses::stripe_form_attr_validation_errors($post_vars['attr'])) // Must NOT have any attr errors.
				{
					if(!($form_submission_validation_errors // Validate registration input form fields.
						= c_ws_plugin__s2member_pro_stripe_responses::stripe_form_submission_validation_errors('registration', $post_vars))
					) // If this fails the global response is set to the error(s) returned during form field validation.
					{
						$GLOBALS['ws_plugin__s2member_registration_vars']['ws_plugin__s2member_custom_reg_field_user_pass1'] = $post_vars['password1'];
						$GLOBALS['ws_plugin__s2member_registration_vars']['ws_plugin__s2member_custom_reg_field_first_name'] = $post_vars['first_name'];
						$GLOBALS['ws_plugin__s2member_registration_vars']['ws_plugin__s2member_custom_reg_field_last_name']  = $post_vars['last_name'];
						$GLOBALS['ws_plugin__s2member_registration_vars']['ws_plugin__s2member_custom_reg_field_opt_in']     = @$post_vars['custom_fields']['opt_in'];

						if($GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_fields'])
							foreach(json_decode($GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_fields'], TRUE) as $field)
							{
								$field_var      = preg_replace('/[^a-z0-9]/i', '_', strtolower($field['id']));
								$field_id_class = preg_replace('/_/', '-', $field_var);

								if(isset ($post_vars['custom_fields'][$field_var]))
									$GLOBALS['ws_plugin__s2member_registration_vars']['ws_plugin__s2member_custom_reg_field_'.$field_var]
										= $post_vars['custom_fields'][$field_var];
							}
						$GLOBALS['ws_plugin__s2member_registration_vars']['ws_plugin__s2member_custom_reg_field_s2member_level']         = $post_vars['attr']['level'];
						$GLOBALS['ws_plugin__s2member_registration_vars']['ws_plugin__s2member_custom_reg_field_s2member_ccaps']         = $post_vars['attr']['ccaps'];
						$GLOBALS['ws_plugin__s2member_registration_vars']['ws_plugin__s2member_custom_reg_field_s2member_custom']        = $post_vars['attr']['custom'];
						$GLOBALS['ws_plugin__s2member_registration_vars']['ws_plugin__s2member_custom_reg_field_s2member_auto_eot_time'] = ($post_vars['attr']['tp'] && $post_vars['attr']['tt'])
							? date('Y-m-d H:i:s', c_ws_plugin__s2member_utils_time::auto_eot_time('', '', '', $post_vars['attr']['tp'].' '.$post_vars['attr']['tt'])) : '';
						$GLOBALS['ws_plugin__s2member_registration_return_url']                                                          = $post_vars['attr']['success'];

						$create_user['user_email'] = $post_vars['email']; // Copy this into a separate array for `wp_create_user()`.
						$create_user['user_login'] = $post_vars['username']; // Copy this into a separate array for `wp_create_user()`.
						$create_user['user_pass']  = c_ws_plugin__s2member_registrations::maybe_custom_pass($post_vars["password1"]);
						$has_custom_password       = $post_vars['password1'] && $post_vars['password1'] === $create_user['user_pass'];

						if(((is_multisite() && ($new__user_id = c_ws_plugin__s2member_registrations::ms_create_existing_user($create_user['user_login'], $create_user['user_email'], $create_user['user_pass'])))
						    || ($new__user_id = wp_create_user($create_user['user_login'], $create_user['user_pass'], $create_user['user_email'])))
						   && !is_wp_error($new__user_id)
						)
						{
							update_user_option($new__user_id, 'default_password_nag', $has_custom_password ? FALSE : TRUE, TRUE);

							if (version_compare(get_bloginfo("version"), "4.3", ">="))
								wp_new_user_notification($new__user_id, $has_custom_password ? "admin" : "both", $create_user['user_pass']);
							else wp_new_user_notification($new__user_id, $create_user['user_pass']);

							if($has_custom_password)
								$global_response = array('response' => sprintf(_x('<strong>Thank you.</strong> Please <a href="%s" rel="nofollow">log in</a>.', 's2member-front', 's2member'), esc_attr(wp_login_url())));
							else $global_response = array('response' => _x('<strong>Thank you.</strong> You\'ll receive an email momentarily.', 's2member-front', 's2member'));

							if($post_vars['attr']['success'] && substr($GLOBALS['ws_plugin__s2member_registration_return_url'], 0, 2) === substr($post_vars['attr']['success'], 0, 2)
							   && ($custom_success_url = str_ireplace(array('%%s_response%%', '%%response%%'), array(urlencode(c_ws_plugin__s2member_utils_encryption::encrypt($global_response['response'])), urlencode($global_response['response'])), $GLOBALS['ws_plugin__s2member_registration_return_url']))
							   && ($custom_success_url = trim(preg_replace('/%%(.+?)%%/i', '', $custom_success_url)))
							) wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig($custom_success_url, 's2p-v')).exit ();
						}
						else $global_response = array('response' => _x('<strong>Oops.</strong> A slight problem. Please contact Support for assistance.', 's2member-front', 's2member'), 'error' => TRUE);
					}
					else // Input form field validation errors.
						$global_response = $form_submission_validation_errors;
				}
			}
		}
	}
}
