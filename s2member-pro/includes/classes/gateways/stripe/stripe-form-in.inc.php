<?php
/**
 * Shortcode `[s2Member-Pro-Stripe-Form /]` (inner processing routines).
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

if(!class_exists('c_ws_plugin__s2member_pro_stripe_form_in'))
{
	/**
	 * Shortcode `[s2Member-Pro-Stripe-Form /]` (inner processing routines).
	 *
	 * @package s2Member\Stripe
	 * @since 140617
	 */
	class c_ws_plugin__s2member_pro_stripe_form_in
	{
		/**
		 * Shortcode `[s2Member-Pro-Stripe-xFormOption /]`.
		 *
		 * @package s2Member\Stripe
		 * @since 140617
		 *
		 * @attaches-to ``add_shortcode('s2Member-Pro-Stripe-xFormOption');``
		 *
		 * @param array  $attr An array of Attributes.
		 * @param string $content Content inside the Shortcode.
		 * @param string $shortcode The actual Shortcode name itself.
		 *
		 * @return string The resulting Form Code, HTML markup.
		 */
		public static function sc_stripe_form_option($attr, $content = '', $shortcode = '')
		{
			foreach(array_keys(get_defined_vars()) as $__v) $__refs[$__v] =& $$__v;
			do_action('ws_plugin__s2member_pro_before_sc_stripe_form', get_defined_vars());
			unset($__refs, $__v); // Ditch these temporary vars.

			return serialize(c_ws_plugin__s2member_utils_strings::trim_qts_deep((array)$attr)).'|::|';
		}

		/**
		 * Shortcode `[s2Member-Pro-Stripe-Form /]`.
		 *
		 * @package s2Member\Stripe
		 * @since 140617
		 *
		 * @attaches-to ``add_shortcode('s2Member-Pro-Stripe-Form');``
		 *
		 * @param array  $attr An array of Attributes.
		 * @param string $content Content inside the Shortcode.
		 * @param string $shortcode The actual Shortcode name itself.
		 *
		 * @return string The resulting Form Code, HTML markup.
		 */
		public static function sc_stripe_form($attr, $content = '', $shortcode = '')
		{
			foreach(array_keys(get_defined_vars()) as $__v) $__refs[$__v] =& $$__v;
			do_action('ws_plugin__s2member_pro_before_sc_stripe_form', get_defined_vars());
			unset($__refs, $__v); // Ditch these temporary vars.

			c_ws_plugin__s2member_no_cache::no_cache_constants(TRUE);

			$attr              = c_ws_plugin__s2member_utils_strings::trim_qts_deep((array)$attr);
			$options           = array(); // Initialize options to an empty array.
			$option_selections = ''; // Initialize w/ no options.

			if($content && ($content = strip_tags($content))) // This allows for nested Pro-Form Shortcodes as options.
				$content = str_replace('s2Member-Pro-Stripe-Form ', 's2Member-Pro-Stripe-xFormOption ', $content);

			if($content && ($content_options = do_shortcode($content)))
			{
				foreach(preg_split('/\s*\|\:\:\|\s*/', $content_options, NULL, PREG_SPLIT_NO_EMPTY) as $_content_option_key => $_content_option)
				{
					$_content_option_id           = $_content_option_key + 1;
					$options[$_content_option_id] = maybe_unserialize(trim($_content_option));
					if(!is_array($options[$_content_option_id]))
					{
						unset($options[$_content_option_id]);
						continue; // Invalid option.
					}
					if(!empty($_REQUEST['s2p-option']) && (integer)$_REQUEST['s2p-option'] === $_content_option_id)
						$options[$_content_option_id]['selected'] = TRUE;
				}
				unset($_content_option_key, $_content_option, $_content_option_id); // Housekeeping.

				foreach($options as $_option_id => $_option) if(!empty($_option['selected']))
				{
					$attr                = array_merge($attr, $_option);
					$_selected_option_id = $_option_id;
				}
				unset($_option_id, $_option); // Housekeeping.

				if(empty($_selected_option_id)) foreach($options as $_option_id => $_option)
				{
					$attr = array_merge($attr, $_option);
					break; // Force a selected option (default).
				}
				unset($_option_id, $_option, $_selected_option_id); // Housekeeping.

				foreach($options as $_option_id => $_option) // Build option selections.
					$option_selections .= '<option value="'.esc_attr($_option_id).'"'.(!empty($_option['selected']) ? ' selected="selected"' : '').'>'.esc_html($_option['desc']).'</option>';
				unset($_option_id, $_option); // Housekeeping.
			}
			$attr = shortcode_atts(array('ids' => '0', 'exp' => '72', 'level' => (@$attr['register'] ? '0' : '1'), 'ccaps' => '', 'desc' => '', 'cc' => 'USD', 'custom' => $_SERVER['HTTP_HOST'], 'ta' => '0', 'tp' => '0', 'tt' => 'D', 'ra' => '0.50', 'rp' => '1', 'rt' => 'M', 'rr' => '1', 'rrt' => '', 'modify' => '0', 'cancel' => '0', 'unsub' => '0', 'sp' => '0', 'register' => '0', 'update' => '0', 'accept' => $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_stripe_api_accept_bitcoin'] ? 'bitcoin' : '', 'coupon' => '', 'accept_coupons' => '0', 'default_country_code' => 'US', 'captcha' => '', 'template' => '', 'success' => '', 'reject_prepaid' => ''), $attr);

			$attr['tt']                   = strtoupper($attr['tt']); // Term lengths absolutely must be provided in upper-case format. Only after running shortcode_atts().
			$attr['rt']                   = strtoupper($attr['rt']); // Term lengths absolutely must be provided in upper-case format. Only after running shortcode_atts().
			$attr['rr']                   = strtoupper($attr['rr']); // Must be provided in upper-case format. Numerical, or BN value. Only after running shortcode_atts().
			$attr['cc']                   = strtoupper($attr['cc']); // Must be provided in upper-case format. Only after running shortcode_atts().
			$attr['ccaps']                = strtolower($attr['ccaps']); // Custom Capabilities must be typed in lower-case format. Only after running shortcode_atts().
			$attr['ccaps']                = str_replace(' ', '', $attr['ccaps']); // Custom Capabilities should not have spaces.
			$attr['rr']                   = $attr['rt'] === 'L' ? 'BN' : $attr['rr']; // Lifetime Subscriptions require Buy Now. Only after running shortcode_atts().
			$attr['rr']                   = $attr['level'] === '*' ? 'BN' : $attr['rr']; // Independent Ccaps require Buy Now. Only after running shortcode_atts().
			$attr['rr']                   = !$attr['tp'] && !$attr['rr'] ? 'BN' : $attr['rr']; // No Trial / non-recurring. Only after running shortcode_atts().
			$attr['default_country_code'] = strtoupper($attr['default_country_code']); // This MUST be in uppercase format.
			$attr['success']              = c_ws_plugin__s2member_utils_urls::n_amps($attr['success']); // Normalize ampersands.
			$attr['coupon']               = !empty($_GET['s2p-coupon']) ? trim(strip_tags(stripslashes($_GET['s2p-coupon']))) : $attr['coupon'];
			$attr['singular']             = get_the_ID(); // Collect the Singular ID for this Post/Page.
			$attr['accept']               = trim($attr['accept']) ? preg_split('/[;,]+/', preg_replace('/['."\r\n\t".'\s]+/', '', trim(strtolower($attr['accept'])))) : array();

			foreach(array_keys(get_defined_vars()) as $__v) $__refs[$__v] =& $$__v;
			do_action('ws_plugin__s2member_pro_before_sc_stripe_form_after_shortcode_atts', get_defined_vars());
			unset($__refs, $__v); // Ditch these temporary vars.

			if($attr['cancel']) // Cancellations.
			{
				$_p       = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST));
				$response = c_ws_plugin__s2member_pro_stripe_responses::stripe_cancellation_response($attr);
				$_p       = ($response['response'] && !$response['error']) ? array() : $_p;

				if($attr['captcha']) // Is a captcha being used on this form?
				{
					$captcha = '<div id="s2member-pro-stripe-cancellation-form-captcha-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-cancellation-form-section s2member-pro-stripe-form-captcha-section s2member-pro-stripe-cancellation-form-captcha-section">'."\n";

					$captcha .= '<div id="s2member-pro-stripe-cancellation-form-captcha-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-cancellation-form-section-title s2member-pro-stripe-form-captcha-section-title s2member-pro-stripe-cancellation-form-captcha-section-title">'."\n";
					$captcha .= _x('Security Verification', 's2member-front', 's2member')."\n";
					$captcha .= '</div>'."\n";

					$captcha .= '<div id="s2member-pro-stripe-cancellation-form-captcha-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-cancellation-form-div s2member-pro-stripe-form-captcha-div s2member-pro-stripe-cancellation-form-captcha-div">'."\n";

					$captcha .= '<label id="s2member-pro-stripe-cancellation-form-captcha-label" class="s2member-pro-stripe-form-captcha-label s2member-pro-stripe-cancellation-form-captcha-label">'."\n";
					$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag($attr['captcha'], 10)."\n";
					$captcha .= '</label>'."\n";

					$captcha .= '</div>'."\n";

					$captcha .= '</div>'."\n";
				}
				else $captcha = ''; // Not applicable.

				$hidden_inputs = '<input type="hidden" name="s2member_pro_stripe_cancellation[nonce]" id="s2member-pro-stripe-cancellation-nonce" value="'.esc_attr(wp_create_nonce('s2member-pro-stripe-cancellation')).'" />';
				$hidden_inputs .= '<input type="hidden" name="s2member_pro_stripe_cancellation[attr]" id="s2member-pro-stripe-cancellation-attr" value="'.esc_attr(c_ws_plugin__s2member_utils_encryption::encrypt(serialize($attr))).'" />';
				$hidden_inputs .= '<input type="hidden" name="s2p-option" value="'.esc_attr((string)@$_REQUEST['s2p-option']).'" />';

				$custom_template = (is_file(TEMPLATEPATH.'/stripe-cancellation-form.php')) ? TEMPLATEPATH.'/stripe-cancellation-form.php' : '';
				$custom_template = (is_file(get_stylesheet_directory().'/stripe-cancellation-form.php')) ? get_stylesheet_directory().'/stripe-cancellation-form.php' : $custom_template;

				$custom_template = ($attr['template'] && is_file(TEMPLATEPATH.'/'.$attr['template'])) ? TEMPLATEPATH.'/'.$attr['template'] : $custom_template;
				$custom_template = ($attr['template'] && is_file(get_stylesheet_directory().'/'.$attr['template'])) ? get_stylesheet_directory().'/'.$attr['template'] : $custom_template;
				$custom_template = ($attr['template'] && is_file(WP_CONTENT_DIR.'/'.$attr['template'])) ? WP_CONTENT_DIR.'/'.$attr['template'] : $custom_template;

				$code = trim(file_get_contents($custom_template ? $custom_template : dirname(dirname(dirname(dirname(__FILE__)))).'/templates/forms/stripe-cancellation-form.php'));
				$code = trim(!$custom_template || !is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site() ? c_ws_plugin__s2member_utilities::evl($code) : $code);

				$code = preg_replace('/%%action%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($_SERVER['REQUEST_URI'])), $code);
				$code = preg_replace('/%%response%%/', c_ws_plugin__s2member_utils_strings::esc_refs($response['response']), $code);
				$code = preg_replace('/%%description%%/', c_ws_plugin__s2member_utils_strings::esc_refs($attr['desc']), $code);
				$code = preg_replace('/%%captcha%%/', c_ws_plugin__s2member_utils_strings::esc_refs($captcha), $code);
				$code = preg_replace('/%%hidden_inputs%%/', c_ws_plugin__s2member_utils_strings::esc_refs($hidden_inputs), $code);

				foreach(array_keys(get_defined_vars()) as $__v) $__refs[$__v] =& $$__v;
				do_action('ws_plugin__s2member_pro_during_sc_stripe_cancellation_form', get_defined_vars());
				unset($__refs, $__v); // Ditch these temporary vars.
			}
			else if($attr['register']) // Free registrations.
			{
				$_p       = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST));
				$response = c_ws_plugin__s2member_pro_stripe_responses::stripe_registration_response($attr);
				$_p       = ($response['response'] && !$response['error']) ? array() : $_p;

				$custom_fields = ''; // Initialize custom fields.
				if($GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_fields']) // Only display Custom Fields if configured.
					if(($fields_applicable = c_ws_plugin__s2member_custom_reg_fields::custom_fields_configured_at_level($attr['level'], 'registration')))
					{
						$tabindex = 99; // Start tabindex at 99 ( +1 below = 100 ).

						$custom_fields = '<div id="s2member-pro-stripe-registration-form-custom-fields-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-registration-form-section s2member-pro-stripe-form-custom-fields-section s2member-pro-stripe-registration-form-custom-fields-section">'."\n";

						$custom_fields .= '<div id="s2member-pro-stripe-registration-form-custom-fields-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-registration-form-section-title s2member-pro-stripe-form-custom-fields-section-title s2member-pro-stripe-registration-form-custom-fields-section-title">'."\n";
						$custom_fields .= _x('Additional Info', 's2member-front', 's2member')."\n";
						$custom_fields .= '</div>'."\n";

						foreach(json_decode($GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_fields'], TRUE) as $field)
							if(in_array($field['id'], $fields_applicable)) // Field is applicable to Level 0?
							{
								$field_var      = preg_replace('/[^a-z0-9]/i', '_', strtolower($field['id']));
								$field_id_class = preg_replace('/_/', '-', $field_var);

								if(!empty($field['section']) && $field['section'] === 'yes') // Starts a new section?
									$custom_fields .= '<div id="s2member-pro-stripe-registration-form-custom-reg-field-'.$field_id_class.'-divider-section" class="s2member-pro-stripe-form-div s2member-pro-stripe-registration-form-div s2member-pro-stripe-form-custom-reg-field-divider-section'.((!empty($field['sectitle'])) ? '-title' : '').' s2member-pro-stripe-form-custom-reg-field-'.$field_id_class.'-divider-section'.((!empty($field['sectitle'])) ? '-title' : '').' s2member-pro-stripe-registration-form-custom-reg-field-'.$field_id_class.'-divider-section'.((!empty($field['sectitle'])) ? '-title' : '').'">'.((!empty($field['sectitle'])) ? $field['sectitle'] : '').'</div>';

								$custom_fields .= '<div id="s2member-pro-stripe-registration-form-custom-reg-field-'.$field_id_class.'-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-registration-form-div s2member-pro-stripe-form-custom-reg-field-'.$field_id_class.'-div s2member-pro-stripe-registration-form-custom-reg-field-'.$field_id_class.'-div">'."\n";

								$custom_fields .= '<label for="s2member-pro-stripe-registration-custom-reg-field-'.esc_attr($field_id_class).'" id="s2member-pro-stripe-registration-form-custom-reg-field-'.$field_id_class.'-label" class="s2member-pro-stripe-form-custom-reg-field-'.$field_id_class.'-label s2member-pro-stripe-registration-form-custom-reg-field-'.$field_id_class.'-label">'."\n";
								$custom_fields .= '<span'.((preg_match('/^(checkbox|pre_checkbox)$/', $field['type'])) ? ' style="display:none;"' : '').'>'.$field['label'].(($field['required'] === 'yes') ? ' *' : '').'</span></label>'.((preg_match('/^(checkbox|pre_checkbox)$/', $field['type'])) ? '' : '<br />')."\n";
								$custom_fields .= c_ws_plugin__s2member_custom_reg_fields::custom_field_gen(__FUNCTION__, $field, 's2member_pro_stripe_registration[custom_fields][', 's2member-pro-stripe-registration-custom-reg-field-', 's2member-pro-stripe-custom-reg-field-'.$field_id_class.' s2member-pro-stripe-registration-custom-reg-field-'.$field_id_class, '', ($tabindex = $tabindex + 1), '', @$_p['s2member_pro_stripe_registration'], @$_p['s2member_pro_stripe_registration']['custom_fields'][$field_var], 'registration');

								$custom_fields .= '</div>'."\n";
							}
						$custom_fields .= '</div>'."\n";
					}
				if($attr['captcha']) // Is a captcha being used on this form?
				{
					$captcha = '<div id="s2member-pro-stripe-registration-form-captcha-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-registration-form-section s2member-pro-stripe-form-captcha-section s2member-pro-stripe-registration-form-captcha-section">'."\n";

					$captcha .= '<div id="s2member-pro-stripe-registration-form-captcha-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-registration-form-section-title s2member-pro-stripe-form-captcha-section-title s2member-pro-stripe-registration-form-captcha-section-title">'."\n";
					$captcha .= _x('Security Verification', 's2member-front', 's2member')."\n";
					$captcha .= '</div>'."\n";

					$captcha .= '<div id="s2member-pro-stripe-registration-form-captcha-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-registration-form-div s2member-pro-stripe-form-captcha-div s2member-pro-stripe-registration-form-captcha-div">'."\n";

					$captcha .= '<label id="s2member-pro-stripe-registration-form-captcha-label" class="s2member-pro-stripe-form-captcha-label s2member-pro-stripe-registration-form-captcha-label">'."\n";
					$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag($attr['captcha'], 200)."\n";
					$captcha .= '</label>'."\n";

					$captcha .= '</div>'."\n";

					$captcha .= '</div>'."\n";
				}
				else $captcha = ''; // Not applicable.

				if($GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_opt_in'] && c_ws_plugin__s2member_list_servers::list_servers_integrated())
				{
					$opt_in = '<div id="s2member-pro-stripe-registration-form-custom-reg-field-opt-in-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-registration-form-div s2member-pro-stripe-form-custom-reg-field-opt-in-div s2member-pro-stripe-registration-form-custom-reg-field-opt-in-div">'."\n";

					$opt_in .= '<label for="s2member-pro-stripe-registration-form-custom-reg-field-opt-in" id="s2member-pro-stripe-registration-form-custom-reg-field-opt-in-label" class="s2member-pro-stripe-form-custom-reg-field-opt-in-label s2member-pro-stripe-registration-form-custom-reg-field-opt-in-label">'."\n";
					$opt_in .= '<input type="checkbox" name="s2member_pro_stripe_registration[custom_fields][opt_in]" id="s2member-pro-stripe-registration-form-custom-reg-field-opt-in" class="s2member-pro-stripe-form-custom-reg-field-opt-in s2member-pro-stripe-registration-form-custom-reg-field-opt-in" value="1"'.(((empty($_p['s2member_pro_stripe_registration']) && $GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_opt_in'] == 1) || @$_p['s2member_pro_stripe_registration']['custom_fields']['opt_in']) ? ' checked="checked"' : '').' tabindex="300" />'."\n";
					$opt_in .= $GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_opt_in_label']."\n";
					$opt_in .= '</label>'."\n";

					$opt_in .= '</div>'."\n";
				}
				else $opt_in = ''; // Not applicable.

				$hidden_inputs = '<input type="hidden" name="s2member_pro_stripe_registration[nonce]" id="s2member-pro-stripe-registration-nonce" value="'.esc_attr(wp_create_nonce('s2member-pro-stripe-registration')).'" />';
				$hidden_inputs .= !$GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_names'] ? '<input type="hidden" id="s2member-pro-stripe-registration-names-not-required-or-not-possible" value="1" />' : '';
				$hidden_inputs .= !$GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_password'] ? '<input type="hidden" id="s2member-pro-stripe-registration-password-not-required-or-not-possible" value="1" />' : '';
				$hidden_inputs .= '<input type="hidden" name="s2member_pro_stripe_registration[attr]" id="s2member-pro-stripe-registration-attr" value="'.esc_attr(c_ws_plugin__s2member_utils_encryption::encrypt(serialize($attr))).'" />';

				$custom_template = (is_file(TEMPLATEPATH.'/stripe-registration-form.php')) ? TEMPLATEPATH.'/stripe-registration-form.php' : '';
				$custom_template = (is_file(get_stylesheet_directory().'/stripe-registration-form.php')) ? get_stylesheet_directory().'/stripe-registration-form.php' : $custom_template;

				$custom_template = ($attr['template'] && is_file(TEMPLATEPATH.'/'.$attr['template'])) ? TEMPLATEPATH.'/'.$attr['template'] : $custom_template;
				$custom_template = ($attr['template'] && is_file(get_stylesheet_directory().'/'.$attr['template'])) ? get_stylesheet_directory().'/'.$attr['template'] : $custom_template;
				$custom_template = ($attr['template'] && is_file(WP_CONTENT_DIR.'/'.$attr['template'])) ? WP_CONTENT_DIR.'/'.$attr['template'] : $custom_template;

				$code = trim(file_get_contents($custom_template ? $custom_template : dirname(dirname(dirname(dirname(__FILE__)))).'/templates/forms/stripe-registration-form.php'));
				$code = trim(!$custom_template || !is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site() ? c_ws_plugin__s2member_utilities::evl($code) : $code);

				$code = preg_replace('/%%action%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($_SERVER['REQUEST_URI'])), $code);
				$code = preg_replace('/%%response%%/', c_ws_plugin__s2member_utils_strings::esc_refs($response['response']), $code);
				$code = preg_replace('/%%options%%/', c_ws_plugin__s2member_utils_strings::esc_refs($option_selections), $code);
				$code = preg_replace('/%%description%%/', c_ws_plugin__s2member_utils_strings::esc_refs($attr['desc']), $code);
				$code = preg_replace('/%%first_name_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(@$_p['s2member_pro_stripe_registration']['first_name'])), $code);
				$code = preg_replace('/%%last_name_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(@$_p['s2member_pro_stripe_registration']['last_name'])), $code);
				$code = preg_replace('/%%email_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p['s2member_pro_stripe_registration']['email'])), $code);
				$code = preg_replace('/%%username_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p['s2member_pro_stripe_registration']['username'])), $code);
				$code = preg_replace('/%%password1_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p['s2member_pro_stripe_registration']['password1'])), $code);
				$code = preg_replace('/%%password2_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p['s2member_pro_stripe_registration']['password2'])), $code);
				$code = preg_replace('/%%custom_fields%%/', c_ws_plugin__s2member_utils_strings::esc_refs($custom_fields), $code);
				$code = preg_replace('/%%captcha%%/', c_ws_plugin__s2member_utils_strings::esc_refs($captcha), $code);
				$code = preg_replace('/%%opt_in%%/', c_ws_plugin__s2member_utils_strings::esc_refs($opt_in), $code);
				$code = preg_replace('/%%hidden_inputs%%/', c_ws_plugin__s2member_utils_strings::esc_refs($hidden_inputs), $code);

				foreach(array_keys(get_defined_vars()) as $__v) $__refs[$__v] =& $$__v;
				do_action('ws_plugin__s2member_pro_during_sc_stripe_registration_form', get_defined_vars());
				unset($__refs, $__v); // Ditch these temporary vars.
			}
			else if($attr['update']) // Billing information updates.
			{
				$_p       = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST));
				$response = c_ws_plugin__s2member_pro_stripe_responses::stripe_update_response($attr);
				$_p       = ($response['response'] && !$response['error']) ? array() : $_p;

				if($attr['captcha']) // Is a captcha being used on this form?
				{
					$captcha = '<div id="s2member-pro-stripe-update-form-captcha-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-update-form-section s2member-pro-stripe-form-captcha-section s2member-pro-stripe-update-form-captcha-section">'."\n";

					$captcha .= '<div id="s2member-pro-stripe-update-form-captcha-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-update-form-section-title s2member-pro-stripe-form-captcha-section-title s2member-pro-stripe-update-form-captcha-section-title">'."\n";
					$captcha .= _x('Security Verification', 's2member-front', 's2member')."\n";
					$captcha .= '</div>'."\n";

					$captcha .= '<div id="s2member-pro-stripe-update-form-captcha-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-update-form-div s2member-pro-stripe-form-captcha-div s2member-pro-stripe-update-form-captcha-div">'."\n";

					$captcha .= '<label id="s2member-pro-stripe-update-form-captcha-label" class="s2member-pro-stripe-form-captcha-label s2member-pro-stripe-update-form-captcha-label">'."\n";
					$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag($attr['captcha'], 200)."\n";
					$captcha .= '</label>'."\n";

					$captcha .= '</div>'."\n";

					$captcha .= '</div>'."\n";
				}
				else $captcha = ''; // Not applicable.

				$hidden_inputs = '<input type="hidden" name="s2member_pro_stripe_update[nonce]" id="s2member-pro-stripe-update-nonce" value="'.esc_attr(wp_create_nonce('s2member-pro-stripe-update')).'" />';
				$hidden_inputs .= '<input type="hidden" name="s2member_pro_stripe_update[source_token]" id="s2member-pro-stripe-update-source-token" value="'.esc_attr(@$_p['s2member_pro_stripe_update']['source_token']).'" />';
				$hidden_inputs .= '<input type="hidden" name="s2member_pro_stripe_update[source_token_summary]" id="s2member-pro-stripe-update-source-token-summary" value="'.esc_attr(@$_p['s2member_pro_stripe_update']['source_token_summary']).'" />';
				$hidden_inputs .= '<input type="hidden" name="s2member_pro_stripe_update[attr]" id="s2member-pro-stripe-update-attr" value="'.esc_attr(c_ws_plugin__s2member_utils_encryption::encrypt(serialize($attr))).'" />';
				$hidden_inputs .= '<input type="hidden" name="s2p-option" value="'.esc_attr((string)@$_REQUEST['s2p-option']).'" />';

				$custom_template = (is_file(TEMPLATEPATH.'/stripe-update-form.php')) ? TEMPLATEPATH.'/stripe-update-form.php' : '';
				$custom_template = (is_file(get_stylesheet_directory().'/stripe-update-form.php')) ? get_stylesheet_directory().'/stripe-update-form.php' : $custom_template;

				$custom_template = ($attr['template'] && is_file(TEMPLATEPATH.'/'.$attr['template'])) ? TEMPLATEPATH.'/'.$attr['template'] : $custom_template;
				$custom_template = ($attr['template'] && is_file(get_stylesheet_directory().'/'.$attr['template'])) ? get_stylesheet_directory().'/'.$attr['template'] : $custom_template;
				$custom_template = ($attr['template'] && is_file(WP_CONTENT_DIR.'/'.$attr['template'])) ? WP_CONTENT_DIR.'/'.$attr['template'] : $custom_template;

				$custom_template_contents = $custom_template ? trim(file_get_contents($custom_template)) : '';
				if($custom_template_contents && stripos($custom_template_contents, '%%source_token_summary%%') === FALSE)
					$custom_template_contents = ''; // Custom template must be up-to-date.

				$code = $custom_template_contents ? $custom_template_contents : trim(file_get_contents(dirname(dirname(dirname(dirname(__FILE__)))).'/templates/forms/stripe-update-form.php'));
				$code = trim(!$custom_template_contents || !is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site() ? c_ws_plugin__s2member_utilities::evl($code) : $code);

				$code = preg_replace('/%%action%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($_SERVER['REQUEST_URI'])), $code);
				$code = preg_replace('/%%response%%/', c_ws_plugin__s2member_utils_strings::esc_refs($response['response']), $code);
				$code = preg_replace('/%%description%%/', c_ws_plugin__s2member_utils_strings::esc_refs($attr['desc']), $code);
				$code = preg_replace('/%%source_token%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_html(@$_p['s2member_pro_stripe_update']['source_token'])), $code);
				$code = preg_replace('/%%source_token_summary%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_html(@$_p['s2member_pro_stripe_update']['source_token_summary'])), $code);
				$code = preg_replace('/%%captcha%%/', c_ws_plugin__s2member_utils_strings::esc_refs($captcha), $code);
				$code = preg_replace('/%%hidden_inputs%%/', c_ws_plugin__s2member_utils_strings::esc_refs($hidden_inputs), $code);

				foreach(array_keys(get_defined_vars()) as $__v) $__refs[$__v] =& $$__v;
				do_action('ws_plugin__s2member_pro_during_sc_stripe_update_form', get_defined_vars());
				unset($__refs, $__v); // Ditch these temporary vars.
			}
			else if($attr['sp']) // Specific Post/Page Access.
			{
				$_p                 = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST));
				$attr['sp_ids_exp'] = 'sp:'.$attr['ids'].':'.$attr['exp']; // Combined `sp:ids:expiration hours`.
				$attr['coupon']     = !empty($_p['s2member_pro_stripe_sp_checkout']['coupon']) ? $_p['s2member_pro_stripe_sp_checkout']['coupon'] : $attr['coupon'];
				$response           = c_ws_plugin__s2member_pro_stripe_responses::stripe_sp_checkout_response($attr);
				$_p                 = $response['response'] && !$response['error'] ? array() : $_p;

				$tax_may_apply = c_ws_plugin__s2member_pro_stripe_utilities::tax_may_apply(); // Tax may apply?
				$cp_attr       = $cp_buy_now_attr = c_ws_plugin__s2member_pro_stripe_utilities::apply_coupon($attr, $attr['coupon']);
				// ↑ The discounted amounts, but before any tax calculations occur during checkout.

				$is_buy_now                  = TRUE; // Always true for Specific Post/Page Access transactions.
				$is_buy_now_amount           = $is_buy_now && $cp_buy_now_attr['ra'] > 0 ? number_format($cp_buy_now_attr['ra'], 2, '.', '') : '0.00';
				$is_buy_now_currency         = $is_buy_now ? $cp_buy_now_attr['cc'] : ''; // Note that Bitcoin can only be charged in USD at the present time.
				$is_buy_now_amount_in_cents  = $is_buy_now && $is_buy_now_amount > 0 ? (string)c_ws_plugin__s2member_pro_stripe_utilities::dollar_amount_to_cents($is_buy_now_amount, $is_buy_now_currency) : '0';
				$is_buy_now_desc             = $is_buy_now ? $cp_buy_now_attr['desc'] : ''; // This description is used for Bitcoin transaction; description for receiver.
				$is_buy_now_bitcoin_accepted = $is_buy_now && $is_buy_now_amount_in_cents > 0 && $is_buy_now_currency === 'USD' && in_array('bitcoin', $cp_buy_now_attr['accept'], TRUE);

				$country_default_by_currency = !@$_p['s2member_pro_stripe_sp_checkout']['country'] && $attr['cc'] === 'USD' ? 'US' : '';
				$country_default_by_currency = !@$_p['s2member_pro_stripe_sp_checkout']['country'] && $attr['cc'] === 'CAD' ? 'CA' : $country_default_by_currency;
				$country_default_by_currency = !@$_p['s2member_pro_stripe_sp_checkout']['country'] && $attr['cc'] === 'GBP' ? 'GB' : $country_default_by_currency;
				$country_default_by_currency = apply_filters('ws_plugin__s2member_pro_stripe_default_country', $country_default_by_currency, get_defined_vars());
				$default_country_v           = $attr['default_country_code'] ? $attr['default_country_code'] : $country_default_by_currency;

				$country_options = '<option value=""></option>'; // Start with an empty option value.
				foreach(preg_split('/['."\r\n".']+/', file_get_contents(dirname(dirname(dirname(dirname(__FILE__)))).'/iso-3166-1.txt')) as $country)
				{
					list ($country_l, $country_v) = preg_split('/;/', $country, 2);
					if($country_l && $country_v) // Here we also check on the default pre-selected country; as determined above; based on currency.
						$country_options .= '<option value="'.esc_attr(strtoupper($country_v)).'"'.((@$_p['s2member_pro_stripe_sp_checkout']['country'] === $country_v || $default_country_v === $country_v) ? ' selected="selected"' : '').'>'.esc_html(ucwords(strtolower($country_l))).'</option>';
				}
				if($attr['captcha']) // Is a captcha being used on this form?
				{
					$captcha = '<div id="s2member-pro-stripe-sp-checkout-form-captcha-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-sp-checkout-form-section s2member-pro-stripe-form-captcha-section s2member-pro-stripe-sp-checkout-form-captcha-section">'."\n";

					$captcha .= '<div id="s2member-pro-stripe-sp-checkout-form-captcha-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-sp-checkout-form-section-title s2member-pro-stripe-form-captcha-section-title s2member-pro-stripe-sp-checkout-form-captcha-section-title">'."\n";
					$captcha .= _x('Security Verification', 's2member-front', 's2member')."\n";
					$captcha .= '</div>'."\n";

					$captcha .= '<div id="s2member-pro-stripe-sp-checkout-form-captcha-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-sp-checkout-form-div s2member-pro-stripe-form-captcha-div s2member-pro-stripe-sp-checkout-form-captcha-div">'."\n";

					$captcha .= '<label id="s2member-pro-stripe-sp-checkout-form-captcha-label" class="s2member-pro-stripe-form-captcha-label s2member-pro-stripe-sp-checkout-form-captcha-label">'."\n";
					$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag($attr['captcha'], 300)."\n";
					$captcha .= '</label>'."\n";

					$captcha .= '</div>'."\n";

					$captcha .= '</div>'."\n";
				}
				else $captcha = ''; // Not applicable.
				/*
				Build the opt-in checkbox.
				*/
				if($GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_opt_in'] && c_ws_plugin__s2member_list_servers::list_servers_integrated())
				{
					$opt_in = '<div id="s2member-pro-stripe-sp-checkout-form-custom-reg-field-opt-in-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-sp-checkout-form-div s2member-pro-stripe-form-custom-reg-field-opt-in-div s2member-pro-stripe-sp-checkout-form-custom-reg-field-opt-in-div">'."\n";

					$opt_in .= '<label for="s2member-pro-stripe-sp-checkout-form-custom-reg-field-opt-in" id="s2member-pro-stripe-sp-checkout-form-custom-reg-field-opt-in-label" class="s2member-pro-stripe-form-custom-reg-field-opt-in-label s2member-pro-stripe-sp-checkout-form-custom-reg-field-opt-in-label">'."\n";
					$opt_in .= '<input type="checkbox" name="s2member_pro_stripe_sp_checkout[custom_fields][opt_in]" id="s2member-pro-stripe-sp-checkout-form-custom-reg-field-opt-in" class="s2member-pro-stripe-form-custom-reg-field-opt-in s2member-pro-stripe-sp-checkout-form-custom-reg-field-opt-in" value="1"'.(((empty($_p['s2member_pro_stripe_sp_checkout']) && $GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_opt_in'] == 1) || @$_p['s2member_pro_stripe_sp_checkout']['custom_fields']['opt_in']) ? ' checked="checked"' : '').' tabindex="400" />'."\n";
					$opt_in .= $GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_opt_in_label']."\n";
					$opt_in .= '</label>'."\n";

					$opt_in .= '</div>'."\n";
				}
				else $opt_in = ''; // Not applicable.

				$hidden_inputs = '<input type="hidden" name="s2member_pro_stripe_sp_checkout[nonce]" id="s2member-pro-stripe-sp-checkout-nonce" value="'.esc_attr(wp_create_nonce('s2member-pro-stripe-sp-checkout')).'" />';
				$hidden_inputs .= '<input type="hidden" name="s2member_pro_stripe_sp_checkout[source_token]" id="s2member-pro-stripe-sp-checkout-source-token" value="'.esc_attr($is_buy_now_amount <= 0 || @$_p['s2member_pro_stripe_sp_checkout']['source_token'] !== 'free' ? @$_p['s2member_pro_stripe_sp_checkout']['source_token'] : '').'" />';
				$hidden_inputs .= '<input type="hidden" name="s2member_pro_stripe_sp_checkout[source_token_summary]" id="s2member-pro-stripe-sp-checkout-source-token-summary" value="'.esc_attr($is_buy_now_amount <= 0 || @$_p['s2member_pro_stripe_sp_checkout']['source_token'] !== 'free' ? @$_p['s2member_pro_stripe_sp_checkout']['source_token_summary'] : '').'" />';
				$hidden_inputs .= !$attr['accept_coupons'] ? '<input type="hidden" id="s2member-pro-stripe-sp-checkout-coupons-not-required-or-not-possible" value="1" />' : '';
				$hidden_inputs .= !$tax_may_apply ? '<input type="hidden" id="s2member-pro-stripe-sp-checkout-tax-not-required-or-not-possible" value="1" />' : '';
				$hidden_inputs .= $is_buy_now_amount <= 0 ? '<input type="hidden" id="s2member-pro-stripe-sp-checkout-payment-not-required-or-not-possible" value="1" />' : '';
				$hidden_inputs .= $is_buy_now_amount > 0 ? '<input type="hidden" id="s2member-pro-stripe-sp-checkout-is-buy-now-amount" value="'.esc_attr($is_buy_now_amount).'" />' : '';
				$hidden_inputs .= $is_buy_now_amount_in_cents > 0 ? '<input type="hidden" id="s2member-pro-stripe-sp-checkout-is-buy-now-amount-in-cents" value="'.esc_attr($is_buy_now_amount_in_cents).'" />' : '';
				$hidden_inputs .= $is_buy_now_currency ? '<input type="hidden" id="s2member-pro-stripe-sp-checkout-is-buy-now-currency" value="'.esc_attr($is_buy_now_currency).'" />' : '';
				$hidden_inputs .= $is_buy_now_desc ? '<input type="hidden" id="s2member-pro-stripe-sp-checkout-is-buy-now-desc" value="'.esc_attr($is_buy_now_desc).'" />' : '';
				$hidden_inputs .= $is_buy_now_bitcoin_accepted ? '<input type="hidden" id="s2member-pro-stripe-sp-checkout-is-buy-now-bitcoin-accepted" value="1" />' : '';
				$hidden_inputs .= '<input type="hidden" name="s2member_pro_stripe_sp_checkout[attr]" id="s2member-pro-stripe-sp-checkout-attr" value="'.esc_attr(c_ws_plugin__s2member_utils_encryption::encrypt(serialize($attr))).'" />';

				$custom_template = (is_file(TEMPLATEPATH.'/stripe-sp-checkout-form.php')) ? TEMPLATEPATH.'/stripe-sp-checkout-form.php' : '';
				$custom_template = (is_file(get_stylesheet_directory().'/stripe-sp-checkout-form.php')) ? get_stylesheet_directory().'/stripe-sp-checkout-form.php' : $custom_template;

				$custom_template = ($attr['template'] && is_file(TEMPLATEPATH.'/'.$attr['template'])) ? TEMPLATEPATH.'/'.$attr['template'] : $custom_template;
				$custom_template = ($attr['template'] && is_file(get_stylesheet_directory().'/'.$attr['template'])) ? get_stylesheet_directory().'/'.$attr['template'] : $custom_template;
				$custom_template = ($attr['template'] && is_file(WP_CONTENT_DIR.'/'.$attr['template'])) ? WP_CONTENT_DIR.'/'.$attr['template'] : $custom_template;

				$custom_template_contents = $custom_template ? trim(file_get_contents($custom_template)) : '';
				if($custom_template_contents && stripos($custom_template_contents, '%%source_token_summary%%') === FALSE)
					$custom_template_contents = ''; // Custom template must be up-to-date.

				$code = $custom_template_contents ? $custom_template_contents : trim(file_get_contents(dirname(dirname(dirname(dirname(__FILE__)))).'/templates/forms/stripe-sp-checkout-form.php'));
				$code = trim(!$custom_template_contents || !is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site() ? c_ws_plugin__s2member_utilities::evl($code) : $code);

				$code = preg_replace('/%%action%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($_SERVER['REQUEST_URI'])), $code);
				$code = preg_replace('/%%response%%/', c_ws_plugin__s2member_utils_strings::esc_refs($response['response']), $code);
				$code = preg_replace('/%%options%%/', c_ws_plugin__s2member_utils_strings::esc_refs($option_selections), $code);
				$code = preg_replace('/%%description%%/', c_ws_plugin__s2member_utils_strings::esc_refs($attr['desc']), $code);
				$code = preg_replace('/%%coupon_response%%/', c_ws_plugin__s2member_utils_strings::esc_refs(c_ws_plugin__s2member_pro_stripe_utilities::apply_coupon($attr, $attr['coupon'], 'response', array('affiliates-1px-response'))), $code);
				$code = preg_replace('/%%coupon_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit($attr['coupon'])), $code);
				$code = preg_replace('/%%first_name_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(@$_p['s2member_pro_stripe_sp_checkout']['first_name'])), $code);
				$code = preg_replace('/%%last_name_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(@$_p['s2member_pro_stripe_sp_checkout']['last_name'])), $code);
				$code = preg_replace('/%%email_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p['s2member_pro_stripe_sp_checkout']['email'])), $code);
				$code = preg_replace('/%%source_token%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_html(@$_p['s2member_pro_stripe_sp_checkout']['source_token'])), $code);
				$code = preg_replace('/%%source_token_summary%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_html(@$_p['s2member_pro_stripe_sp_checkout']['source_token_summary'])), $code);
				$code = preg_replace('/%%state_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p['s2member_pro_stripe_sp_checkout']['state'])), $code);
				$code = preg_replace('/%%country_options%%/', c_ws_plugin__s2member_utils_strings::esc_refs($country_options), $code);
				$code = preg_replace('/%%zip_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p['s2member_pro_stripe_sp_checkout']['zip'])), $code);
				$code = preg_replace('/%%captcha%%/', c_ws_plugin__s2member_utils_strings::esc_refs($captcha), $code);
				$code = preg_replace('/%%opt_in%%/', c_ws_plugin__s2member_utils_strings::esc_refs($opt_in), $code);
				$code = preg_replace('/%%hidden_inputs%%/', c_ws_plugin__s2member_utils_strings::esc_refs($hidden_inputs), $code);

				foreach(array_keys(get_defined_vars()) as $__v) $__refs[$__v] =& $$__v;
				do_action('ws_plugin__s2member_pro_during_sc_stripe_sp_form', get_defined_vars());
				unset($__refs, $__v); // Ditch these temporary vars.
			}
			else // Signups and Modifications.
			{
				$_p                         = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST));
				$attr['level_ccaps_eotper'] = $attr['rr'] === 'BN' && $attr['rt'] !== 'L' ? $attr['level'].':'.$attr['ccaps'].':'.$attr['rp'].' '.$attr['rt'] : $attr['level'].':'.$attr['ccaps'];
				$attr['level_ccaps_eotper'] = rtrim($attr['level_ccaps_eotper'], ':'); // Clean any trailing separators from this string.
				$attr['coupon']             = !empty($_p['s2member_pro_stripe_checkout']['coupon']) ? $_p['s2member_pro_stripe_checkout']['coupon'] : $attr['coupon'];
				$response                   = c_ws_plugin__s2member_pro_stripe_responses::stripe_checkout_response($attr);
				$_p                         = $response['response'] && !$response['error'] ? array() : $_p;

				$tax_may_apply = c_ws_plugin__s2member_pro_stripe_utilities::tax_may_apply(); // Tax may apply?
				$cp_attr       = $cp_buy_now_attr = c_ws_plugin__s2member_pro_stripe_utilities::apply_coupon($attr, $attr['coupon']);
				// ↑ The discounted amounts, but before any tax calculations occur during checkout.

				if($cp_buy_now_attr['ra'] <= 0 && $cp_buy_now_attr['tp'] && $cp_buy_now_attr['ta'] > 0)
				{
					$cp_buy_now_attr['tp'] = '0'; // Ditch the trial period completely.
					$cp_buy_now_attr['ra'] = $cp_buy_now_attr['ta']; // Use as regular amount.
					$cp_buy_now_attr['ta'] = '0.00'; // Ditch this calculation now.
				}
				$is_buy_now                  = $cp_buy_now_attr['rr'] === 'BN' || (!$cp_buy_now_attr['tp'] && !$cp_buy_now_attr['rr']);
				$is_buy_now_amount           = $is_buy_now && $cp_buy_now_attr['ra'] > 0 ? number_format($cp_buy_now_attr['ra'], 2, '.', '') : '0.00';
				$is_buy_now_currency         = $is_buy_now ? $cp_buy_now_attr['cc'] : ''; // Note that Bitcoin can only be charged in USD at the present time.
				$is_buy_now_amount_in_cents  = $is_buy_now && $is_buy_now_amount > 0 ? (string)c_ws_plugin__s2member_pro_stripe_utilities::dollar_amount_to_cents($is_buy_now_amount, $is_buy_now_currency) : '0';
				$is_buy_now_desc             = $is_buy_now ? $cp_buy_now_attr['desc'] : ''; // This description is used for Bitcoin transaction; description for receiver.
				$is_buy_now_bitcoin_accepted = $is_buy_now && $is_buy_now_amount_in_cents > 0 && $is_buy_now_currency === 'USD' && in_array('bitcoin', $cp_buy_now_attr['accept'], TRUE);

				$country_default_by_currency = !@$_p['s2member_pro_stripe_checkout']['country'] && $attr['cc'] === 'USD' ? 'US' : '';
				$country_default_by_currency = !@$_p['s2member_pro_stripe_checkout']['country'] && $attr['cc'] === 'CAD' ? 'CA' : $country_default_by_currency;
				$country_default_by_currency = !@$_p['s2member_pro_stripe_checkout']['country'] && $attr['cc'] === 'GBP' ? 'GB' : $country_default_by_currency;
				$country_default_by_currency = apply_filters('ws_plugin__s2member_pro_stripe_default_country', $country_default_by_currency, get_defined_vars());
				$default_country_v           = $attr['default_country_code'] ? $attr['default_country_code'] : $country_default_by_currency;

				$country_options = '<option value=""></option>'; // Start with an empty option value.
				foreach(preg_split('/['."\r\n".']+/', file_get_contents(dirname(dirname(dirname(dirname(__FILE__)))).'/iso-3166-1.txt')) as $country)
				{
					list ($country_l, $country_v) = preg_split('/;/', $country, 2);
					if($country_l && $country_v) // Here we also check on the default pre-selected country; as determined above; based on currency.
						$country_options .= '<option value="'.esc_attr(strtoupper($country_v)).'"'.((@$_p['s2member_pro_stripe_checkout']['country'] === $country_v || $default_country_v === $country_v) ? ' selected="selected"' : '').'>'.esc_html(ucwords(strtolower($country_l))).'</option>';
				}
				$custom_fields = ''; // Initialize custom fields.
				if($GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_fields']) // Only display Custom Fields if configured.
					if(($fields_applicable = c_ws_plugin__s2member_custom_reg_fields::custom_fields_configured_at_level((($attr['level'] === '*') ? 'auto-detection' : $attr['level']), 'registration')))
					{
						$tabindex = 99; // Start tabindex at 99 (+1 below = 100).

						$custom_fields = '<div id="s2member-pro-stripe-checkout-form-custom-fields-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-checkout-form-section s2member-pro-stripe-form-custom-fields-section s2member-pro-stripe-checkout-form-custom-fields-section">'."\n";

						$custom_fields .= '<div id="s2member-pro-stripe-checkout-form-custom-fields-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-checkout-form-section-title s2member-pro-stripe-form-custom-fields-section-title s2member-pro-stripe-checkout-form-custom-fields-section-title">'."\n";
						$custom_fields .= _x('Additional Info', 's2member-front', 's2member')."\n";
						$custom_fields .= '</div>'."\n";

						foreach(json_decode($GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_fields'], TRUE) as $field)
							if(in_array($field['id'], $fields_applicable)) // Field is applicable to this Level?
							{
								$field_var      = preg_replace('/[^a-z0-9]/i', '_', strtolower($field['id']));
								$field_id_class = preg_replace('/_/', '-', $field_var);

								if(!empty($field['section']) && $field['section'] === 'yes') // Starts a new section?
									$custom_fields .= '<div id="s2member-pro-stripe-checkout-form-custom-reg-field-'.$field_id_class.'-divider-section" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-custom-reg-field-divider-section'.((!empty($field['sectitle'])) ? '-title' : '').' s2member-pro-stripe-form-custom-reg-field-'.$field_id_class.'-divider-section'.((!empty($field['sectitle'])) ? '-title' : '').' s2member-pro-stripe-checkout-form-custom-reg-field-'.$field_id_class.'-divider-section'.((!empty($field['sectitle'])) ? '-title' : '').'">'.((!empty($field['sectitle'])) ? $field['sectitle'] : '').'</div>';

								$custom_fields .= '<div id="s2member-pro-stripe-checkout-form-custom-reg-field-'.$field_id_class.'-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-custom-reg-field-'.$field_id_class.'-div s2member-pro-stripe-checkout-form-custom-reg-field-'.$field_id_class.'-div">'."\n";

								$custom_fields .= '<label for="s2member-pro-stripe-checkout-custom-reg-field-'.esc_attr($field_id_class).'" id="s2member-pro-stripe-checkout-form-custom-reg-field-'.$field_id_class.'-label" class="s2member-pro-stripe-form-custom-reg-field-'.$field_id_class.'-label s2member-pro-stripe-checkout-form-custom-reg-field-'.$field_id_class.'-label">'."\n";
								$custom_fields .= '<span'.((preg_match('/^(checkbox|pre_checkbox)$/', $field['type'])) ? ' style="display:none;"' : '').'>'.$field['label'].(($field['required'] === 'yes') ? ' *' : '').'</span></label>'.((preg_match('/^(checkbox|pre_checkbox)$/', $field['type'])) ? '' : '<br />')."\n";
								$custom_fields .= c_ws_plugin__s2member_custom_reg_fields::custom_field_gen(__FUNCTION__, $field, 's2member_pro_stripe_checkout[custom_fields][', 's2member-pro-stripe-checkout-custom-reg-field-', 's2member-pro-stripe-custom-reg-field-'.$field_id_class.' s2member-pro-stripe-checkout-custom-reg-field-'.$field_id_class, '', ($tabindex = $tabindex + 1), '', @$_p['s2member_pro_stripe_checkout'], @$_p['s2member_pro_stripe_checkout']['custom_fields'][$field_var], 'registration');

								$custom_fields .= '</div>'."\n";
							}
						$custom_fields .= '</div>'."\n";
					}
				if($attr['captcha']) // Is a captcha being used on this form?
				{
					$captcha = '<div id="s2member-pro-stripe-checkout-form-captcha-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-checkout-form-section s2member-pro-stripe-form-captcha-section s2member-pro-stripe-checkout-form-captcha-section">'."\n";

					$captcha .= '<div id="s2member-pro-stripe-checkout-form-captcha-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-checkout-form-section-title s2member-pro-stripe-form-captcha-section-title s2member-pro-stripe-checkout-form-captcha-section-title">'."\n";
					$captcha .= _x('Security Verification', 's2member-front', 's2member')."\n";
					$captcha .= '</div>'."\n";

					$captcha .= '<div id="s2member-pro-stripe-checkout-form-captcha-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-captcha-div s2member-pro-stripe-checkout-form-captcha-div">'."\n";

					$captcha .= '<label id="s2member-pro-stripe-checkout-form-captcha-label" class="s2member-pro-stripe-form-captcha-label s2member-pro-stripe-checkout-form-captcha-label">'."\n";
					$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag($attr['captcha'], 400)."\n";
					$captcha .= '</label>'."\n";

					$captcha .= '</div>'."\n";

					$captcha .= '</div>'."\n";
				}
				else $captcha = ''; // Not applicable.

				if($GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_opt_in'] && c_ws_plugin__s2member_list_servers::list_servers_integrated())
				{
					$opt_in = '<div id="s2member-pro-stripe-checkout-form-custom-reg-field-opt-in-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-custom-reg-field-opt-in-div s2member-pro-stripe-checkout-form-custom-reg-field-opt-in-div">'."\n";

					$opt_in .= '<label for="s2member-pro-stripe-checkout-form-custom-reg-field-opt-in" id="s2member-pro-stripe-checkout-form-custom-reg-field-opt-in-label" class="s2member-pro-stripe-form-custom-reg-field-opt-in-label s2member-pro-stripe-checkout-form-custom-reg-field-opt-in-label">'."\n";
					$opt_in .= '<input type="checkbox" name="s2member_pro_stripe_checkout[custom_fields][opt_in]" id="s2member-pro-stripe-checkout-form-custom-reg-field-opt-in" class="s2member-pro-stripe-form-custom-reg-field-opt-in s2member-pro-stripe-checkout-form-custom-reg-field-opt-in" value="1"'.(((empty($_p['s2member_pro_stripe_checkout']) && $GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_opt_in'] == 1) || @$_p['s2member_pro_stripe_checkout']['custom_fields']['opt_in']) ? ' checked="checked"' : '').' tabindex="500" />'."\n";
					$opt_in .= $GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_opt_in_label']."\n";
					$opt_in .= '</label>'."\n";

					$opt_in .= '</div>'."\n";
				}
				else $opt_in = ''; // Not applicable.

				$hidden_inputs = '<input type="hidden" name="s2member_pro_stripe_checkout[nonce]" id="s2member-pro-stripe-checkout-nonce" value="'.esc_attr(wp_create_nonce('s2member-pro-stripe-checkout')).'" />';
				$hidden_inputs .= '<input type="hidden" name="s2member_pro_stripe_checkout[source_token]" id="s2member-pro-stripe-checkout-source-token" value="'.esc_attr(($cp_attr['ta'] <= 0 && $cp_attr['ra'] <= 0) || @$_p['s2member_pro_stripe_checkout']['source_token'] !== 'free' ? @$_p['s2member_pro_stripe_checkout']['source_token'] : '').'" />';
				$hidden_inputs .= '<input type="hidden" name="s2member_pro_stripe_checkout[source_token_summary]" id="s2member-pro-stripe-checkout-source-token-summary" value="'.esc_attr(($cp_attr['ta'] <= 0 && $cp_attr['ra'] <= 0) || @$_p['s2member_pro_stripe_checkout']['source_token'] !== 'free' ? @$_p['s2member_pro_stripe_checkout']['source_token_summary'] : '').'" />';
				$hidden_inputs .= !$attr['accept_coupons'] ? '<input type="hidden" id="s2member-pro-stripe-checkout-coupons-not-required-or-not-possible" value="1" />' : '';
				$hidden_inputs .= !$GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_password'] ? '<input type="hidden" id="s2member-pro-stripe-checkout-password-not-required-or-not-possible" value="1" />' : '';
				$hidden_inputs .= !$tax_may_apply ? '<input type="hidden" id="s2member-pro-stripe-checkout-tax-not-required-or-not-possible" value="1" />' : '';
				$hidden_inputs .= $cp_attr['ta'] <= 0 && $cp_attr['ra'] <= 0 ? '<input type="hidden" id="s2member-pro-stripe-checkout-payment-not-required-or-not-possible" value="1" />' : '';
				$hidden_inputs .= $is_buy_now_amount > 0 ? '<input type="hidden" id="s2member-pro-stripe-checkout-is-buy-now-amount" value="'.esc_attr($is_buy_now_amount).'" />' : '';
				$hidden_inputs .= $is_buy_now_amount_in_cents > 0 ? '<input type="hidden" id="s2member-pro-stripe-checkout-is-buy-now-amount-in-cents" value="'.esc_attr($is_buy_now_amount_in_cents).'" />' : '';
				$hidden_inputs .= $is_buy_now_currency ? '<input type="hidden" id="s2member-pro-stripe-checkout-is-buy-now-currency" value="'.esc_attr($is_buy_now_currency).'" />' : '';
				$hidden_inputs .= $is_buy_now_desc ? '<input type="hidden" id="s2member-pro-stripe-checkout-is-buy-now-desc" value="'.esc_attr($is_buy_now_desc).'" />' : '';
				$hidden_inputs .= $is_buy_now_bitcoin_accepted ? '<input type="hidden" id="s2member-pro-stripe-checkout-is-buy-now-bitcoin-accepted" value="1" />' : '';
				$hidden_inputs .= '<input type="hidden" name="s2member_pro_stripe_checkout[attr]" id="s2member-pro-stripe-checkout-attr" value="'.esc_attr(c_ws_plugin__s2member_utils_encryption::encrypt(serialize($attr))).'" />';

				$custom_template = (is_file(TEMPLATEPATH.'/stripe-checkout-form.php')) ? TEMPLATEPATH.'/stripe-checkout-form.php' : '';
				$custom_template = (is_file(get_stylesheet_directory().'/stripe-checkout-form.php')) ? get_stylesheet_directory().'/stripe-checkout-form.php' : $custom_template;

				$custom_template = ($attr['template'] && is_file(TEMPLATEPATH.'/'.$attr['template'])) ? TEMPLATEPATH.'/'.$attr['template'] : $custom_template;
				$custom_template = ($attr['template'] && is_file(get_stylesheet_directory().'/'.$attr['template'])) ? get_stylesheet_directory().'/'.$attr['template'] : $custom_template;
				$custom_template = ($attr['template'] && is_file(WP_CONTENT_DIR.'/'.$attr['template'])) ? WP_CONTENT_DIR.'/'.$attr['template'] : $custom_template;

				$custom_template_contents = $custom_template ? trim(file_get_contents($custom_template)) : '';
				if($custom_template_contents && stripos($custom_template_contents, '%%source_token_summary%%') === FALSE)
					$custom_template_contents = ''; // Custom template must be up-to-date.

				$code = $custom_template_contents ? $custom_template_contents : trim(file_get_contents(dirname(dirname(dirname(dirname(__FILE__)))).'/templates/forms/stripe-checkout-form.php'));
				$code = trim(!$custom_template_contents || !is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site() ? c_ws_plugin__s2member_utilities::evl($code) : $code);

				$code = preg_replace('/%%action%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($_SERVER['REQUEST_URI'])), $code);
				$code = preg_replace('/%%response%%/', c_ws_plugin__s2member_utils_strings::esc_refs($response['response']), $code);
				$code = preg_replace('/%%options%%/', c_ws_plugin__s2member_utils_strings::esc_refs($option_selections), $code);
				$code = preg_replace('/%%description%%/', c_ws_plugin__s2member_utils_strings::esc_refs($attr['desc']), $code);
				$code = preg_replace('/%%coupon_response%%/', c_ws_plugin__s2member_utils_strings::esc_refs(c_ws_plugin__s2member_pro_stripe_utilities::apply_coupon($attr, $attr['coupon'], 'response', array('affiliates-1px-response'))), $code);
				$code = preg_replace('/%%coupon_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit($attr['coupon'])), $code);
				$code = preg_replace('/%%first_name_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(@$_p['s2member_pro_stripe_checkout']['first_name'])), $code);
				$code = preg_replace('/%%last_name_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(@$_p['s2member_pro_stripe_checkout']['last_name'])), $code);
				$code = preg_replace('/%%email_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p['s2member_pro_stripe_checkout']['email'])), $code);
				$code = preg_replace('/%%username_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p['s2member_pro_stripe_checkout']['username'])), $code);
				$code = preg_replace('/%%password1_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p['s2member_pro_stripe_checkout']['password1'])), $code);
				$code = preg_replace('/%%password2_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p['s2member_pro_stripe_checkout']['password2'])), $code);
				$code = preg_replace('/%%custom_fields%%/', c_ws_plugin__s2member_utils_strings::esc_refs($custom_fields), $code);
				$code = preg_replace('/%%source_token%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_html(@$_p['s2member_pro_stripe_checkout']['source_token'])), $code);
				$code = preg_replace('/%%source_token_summary%%/', c_ws_plugin__s2member_utils_strings::esc_refs(esc_html(@$_p['s2member_pro_stripe_checkout']['source_token_summary'])), $code);
				$code = preg_replace('/%%state_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p['s2member_pro_stripe_checkout']['state'])), $code);
				$code = preg_replace('/%%country_options%%/', c_ws_plugin__s2member_utils_strings::esc_refs($country_options), $code);
				$code = preg_replace('/%%zip_value%%/', c_ws_plugin__s2member_utils_strings::esc_refs(format_to_edit(@$_p['s2member_pro_stripe_checkout']['zip'])), $code);
				$code = preg_replace('/%%captcha%%/', c_ws_plugin__s2member_utils_strings::esc_refs($captcha), $code);
				$code = preg_replace('/%%opt_in%%/', c_ws_plugin__s2member_utils_strings::esc_refs($opt_in), $code);
				$code = preg_replace('/%%hidden_inputs%%/', c_ws_plugin__s2member_utils_strings::esc_refs($hidden_inputs), $code);

				foreach(array_keys(get_defined_vars()) as $__v) $__refs[$__v] =& $$__v;
				($attr['modify']) ? do_action('ws_plugin__s2member_pro_during_sc_stripe_modification_form', get_defined_vars()) : do_action('ws_plugin__s2member_pro_during_sc_stripe_form', get_defined_vars());
				unset($__refs, $__v); // Ditch these temporary vars.
			}
			return apply_filters('ws_plugin__s2member_pro_sc_stripe_form', $code, get_defined_vars());
		}
	}
}
