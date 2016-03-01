<?php
/**
 * Shortcodes for s2Member Pro.
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
Add WordPress Editor Shortcodes.
*/
add_shortcode('s2Drip', 'c_ws_plugin__s2member_pro_sc_drip::shortcode');

add_shortcode('s2MOP', 'c_ws_plugin__s2member_pro_sc_mop_vars_notice::shortcode');
add_shortcode('s2MOPNotice', 'c_ws_plugin__s2member_pro_sc_mop_vars_notice::shortcode');

add_shortcode('s2Member-Login', 'c_ws_plugin__s2member_pro_sc_login::shortcode');
add_shortcode('s2Member-Summary', 'c_ws_plugin__s2member_pro_sc_summary::shortcode');

add_shortcode('s2Member-Gift-Codes', 'c_ws_plugin__s2member_pro_sc_gift_codes::shortcode');

add_shortcode('s2Member-List', 'c_ws_plugin__s2member_pro_sc_member_list::shortcode');
add_shortcode('s2Member-List-Search-Box', 'c_ws_plugin__s2member_pro_sc_member_list::s_box_shortcode');
