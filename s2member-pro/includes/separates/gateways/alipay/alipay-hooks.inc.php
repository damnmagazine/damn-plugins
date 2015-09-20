<?php
/**
 * Primary Hooks/Filters for AliPay.
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
 * @package s2Member\AliPay
 * @since 1.5
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');
/*
Add the plugin Actions/Filters here.
*/
add_action('init', 'c_ws_plugin__s2member_pro_alipay_return::alipay_return', 4);
add_action('init', 'c_ws_plugin__s2member_pro_alipay_notify::alipay_notify', 4);

add_filter('ws_plugin__s2member_during_constants_c', 'c_ws_plugin__s2member_pro_alipay_constants::alipay_constants', 10, 2);

add_action('ws_plugin__s2member_during_css', 'c_ws_plugin__s2member_pro_alipay_css_js::alipay_css');
add_action('ws_plugin__s2member_during_js_w_globals', 'c_ws_plugin__s2member_pro_alipay_css_js::alipay_js_w_globals');
add_action('ws_plugin__s2member_during_menu_pages_js', 'c_ws_plugin__s2member_pro_alipay_admin_css_js::alipay_menu_pages_js');

add_filter('ws_plugin__s2member_during_add_admin_options_add_divider_3', 'c_ws_plugin__s2member_pro_alipay_menu_pages::alipay_admin_options', 10, 2);

add_action('ws_plugin__s2member_during_scripting_page_during_left_sections_during_list_of_api_constants', 'c_ws_plugin__s2member_pro_alipay_menu_pages::alipay_scripting_page_api_constants');
add_action('ws_plugin__s2member_during_scripting_page_during_left_sections_during_list_of_api_constants_farm', 'c_ws_plugin__s2member_pro_alipay_menu_pages::alipay_scripting_page_api_constants');