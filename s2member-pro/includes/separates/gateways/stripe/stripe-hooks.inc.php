<?php
/**
 * Primary Hooks/Filters for Stripe.
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
/*
Add the plugin Actions/Filters here.
*/
add_action('init', 'c_ws_plugin__s2member_pro_stripe_notify::stripe_notify', 4);

add_action('init', 'c_ws_plugin__s2member_pro_stripe_update::stripe_update');
add_action('init', 'c_ws_plugin__s2member_pro_stripe_checkout::stripe_checkout');
add_action('init', 'c_ws_plugin__s2member_pro_stripe_sp_checkout::stripe_sp_checkout');
add_action('init', 'c_ws_plugin__s2member_pro_stripe_registration::stripe_registration');
add_action('init', 'c_ws_plugin__s2member_pro_stripe_cancellation::stripe_cancellation');

add_filter('ws_plugin__s2member_during_constants_c', 'c_ws_plugin__s2member_pro_stripe_constants::stripe_constants', 10, 2);

add_action('wp_ajax_ws_plugin__s2member_pro_stripe_ajax_tax', 'c_ws_plugin__s2member_pro_stripe_utilities::stripe_ajax_tax');
add_action('wp_ajax_nopriv_ws_plugin__s2member_pro_stripe_ajax_tax', 'c_ws_plugin__s2member_pro_stripe_utilities::stripe_ajax_tax');

add_action('ws_plugin__s2member_during_css', 'c_ws_plugin__s2member_pro_stripe_css_js::stripe_css');
add_action('ws_plugin__s2member_during_js_w_globals', 'c_ws_plugin__s2member_pro_stripe_css_js::stripe_js_w_globals');
add_action('ws_plugin__s2member_during_menu_pages_js', 'c_ws_plugin__s2member_pro_stripe_admin_css_js::stripe_menu_pages_js');

add_filter('ws_plugin__s2member_during_add_admin_options_add_divider_3', 'c_ws_plugin__s2member_pro_stripe_menu_pages::stripe_admin_options', 10, 2);

add_action('ws_plugin__s2member_during_scripting_page_during_left_sections_during_list_of_api_constants', 'c_ws_plugin__s2member_pro_stripe_menu_pages::stripe_scripting_page_api_constants');
add_action('ws_plugin__s2member_during_scripting_page_during_left_sections_during_list_of_api_constants_farm', 'c_ws_plugin__s2member_pro_stripe_menu_pages::stripe_scripting_page_api_constants');

add_filter('ws_plugin__s2member_check_force_ssl', 'c_ws_plugin__s2member_pro_stripe_ssl::sc_stripe_form_auto_force_ssl', 10, 2);