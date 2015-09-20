<?php
/**
 * Primary Hooks/Filters used by s2Member Pro.
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
 * @package s2Member
 * @since 1.0
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');
/*
Add the plugin Actions/Filters here.
*/
add_action('init', 'c_ws_plugin__s2member_pro_rsf_files::serve', 3);
add_action('init', 'c_ws_plugin__s2member_pro_exports::export', 3);
add_action('init', 'c_ws_plugin__s2member_pro_exports_simple::export', 3);
add_action('init', 'c_ws_plugin__s2member_pro_remote_ops::remote_ops');

add_action('admin_init', 'c_ws_plugin__s2member_pro_stats_pinger::maybe_ping');
add_action('admin_init', 'c_ws_plugin__s2member_pro_lock_icons::configure_lock_icons');

add_action('widgets_init', 'c_ws_plugin__s2member_pro_widgets::login_widget_register');

add_filter('ws_plugin__s2member_during_constants_c', 'c_ws_plugin__s2member_pro_constants::constants', 9, 2);

add_action('ws_plugin__s2member_during_css', 'c_ws_plugin__s2member_pro_css_js::css');
add_action('ws_plugin__s2member_during_js_w_globals', 'c_ws_plugin__s2member_pro_css_js::js_w_globals');
add_action('ws_plugin__s2member_during_menu_pages_js', 'c_ws_plugin__s2member_pro_admin_css_js::menu_pages_js');

add_filter('ws_plugin__s2member_during_add_admin_options_add_divider_2', 'c_ws_plugin__s2member_pro_menu_pages::add_coupon_codes_page', 10, 2);
add_filter('ws_plugin__s2member_during_add_admin_options_add_divider_2', 'c_ws_plugin__s2member_pro_menu_pages::add_import_export_page', 10, 2);
add_filter('ws_plugin__s2member_during_add_admin_options_add_divider_3', 'c_ws_plugin__s2member_pro_menu_pages::add_other_gateways_page', 10, 2);

add_action('ws_plugin__s2member_during_gen_ops_page_during_left_sections_during_membership_levels', 'c_ws_plugin__s2member_pro_menu_pages::add_level_instructions');
add_action('ws_plugin__s2member_during_gen_ops_page_during_left_sections_after_login_welcome_page', 'c_ws_plugin__s2member_pro_menu_pages::gen_ops_lwp_otos');
add_action('ws_plugin__s2member_during_gen_ops_page_during_left_sections_after_url_shortening', 'c_ws_plugin__s2member_pro_menu_pages::gen_ops_captcha_ops');

add_filter('ws_plugin__s2member_sc_paypal_button_default_attrs', 'c_ws_plugin__s2member_pro_paypal_extras::paypal_button_default_attrs', 10, 2);
add_action('ws_plugin__s2member_before_sc_paypal_button_after_shortcode_atts', 'c_ws_plugin__s2member_pro_paypal_extras::paypal_button_after_attrs');
add_filter('ws_plugin__s2member_during_sc_paypal_button_success_return_url', 'c_ws_plugin__s2member_pro_paypal_extras::paypal_button_success_return_url', 10, 2);
add_action('ws_plugin__s2member_during_paypal_buttons_page_during_left_sections_during_shortcode_attrs_lis', 'c_ws_plugin__s2member_pro_menu_pages::paypal_button_attrs');
add_action('ws_plugin__s2member_during_paypal_ops_page_during_left_sections_during_paypal_pdt_after_more_info', 'c_ws_plugin__s2member_pro_menu_pages::paypal_return_template');

add_filter('ws_plugin__s2member_during_scripting_page_during_left_sections_display_api_hooks', 'c_ws_plugin__s2member_pro_menu_pages::scripting_page_login_widget_api');
add_filter('ws_plugin__s2member_during_scripting_page_during_left_sections_display_api_hooks', 'c_ws_plugin__s2member_pro_menu_pages::scripting_page_remote_ops_api');
add_action('ws_plugin__s2member_during_scripting_page_during_left_sections_during_list_of_api_constants', 'c_ws_plugin__s2member_pro_menu_pages::scripting_page_api_constants');
add_action('ws_plugin__s2member_during_scripting_page_during_left_sections_during_list_of_api_constants_farm', 'c_ws_plugin__s2member_pro_menu_pages::scripting_page_api_constants');

add_action('ws_plugin__s2member_after_update_all_options', 'c_ws_plugin__s2member_pro_coupons::after_update_all_options');

add_filter('ws_plugin__s2member_recaptcha_version', 'c_ws_plugin__s2member_pro_utils_captchas::recaptcha_version', 10, 2);
add_filter('ws_plugin__s2member_recaptcha_keys', 'c_ws_plugin__s2member_pro_utils_captchas::recaptcha_keys', 10, 2);

add_filter('ws_plugin__s2member_login_redirect', 'c_ws_plugin__s2member_pro_login_redirects::login_redirect', 11, 2);

add_filter('ws_plugin__s2member_return_template_header', 'c_ws_plugin__s2member_pro_return_templates::return_template_header', 10, 2);

add_filter('ws_plugin__s2member_profile_s2member_subscr_gateways', 'c_ws_plugin__s2member_pro_gateways::profile_subscr_gateways');
add_action('ws_plugin__s2member_after_loaded', 'c_ws_plugin__s2member_pro_gateways::load_gateways', 1);

add_filter('plugin_row_meta', 'c_ws_plugin__s2member_pro_menu_pages::module_identifier', 10, 2);
