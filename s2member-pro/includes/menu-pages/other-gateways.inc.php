<?php
/**
 * Menu page for s2Member Pro (Other Gateways page).
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
 * @package s2Member\Menu_Pages
 * @since 1.5
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_other_gateways"))
{
	/**
	 * Menu page for s2Member Pro (Other Gateways page).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110531
	 */
	class c_ws_plugin__s2member_pro_menu_page_other_gateways
	{
		public function __construct()
		{
			echo '<div class="wrap ws-menu-page">'."\n";

			echo '<div class="ws-menu-page-toolbox">'."\n";
			c_ws_plugin__s2member_menu_pages_tb::display();
			echo '</div>'."\n";

			echo '<h2>Gateways</h2>'."\n";

			echo '<table class="ws-menu-page-table">'."\n";
			echo '<tbody class="ws-menu-page-table-tbody">'."\n";
			echo '<tr class="ws-menu-page-table-tr">'."\n";
			echo '<td class="ws-menu-page-table-l">'."\n";

			echo '<form method="post" name="ws_plugin__s2member_pro_options_form" id="ws-plugin--s2member-pro-options-form" autocomplete="off">'."\n";
			echo '<input type="hidden" name="ws_plugin__s2member_options_save" id="ws-plugin--s2member-options-save" value="'.esc_attr(wp_create_nonce("ws-plugin--s2member-options-save")).'" />'."\n";

			echo '<div class="ws-menu-page-group" title="Other Payment Gateways" default-state="open">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-other-gateways-section">'."\n";
			echo '<h3>Other Payment Gateways (enable/disable)</h3>'."\n";
			echo '<p>s2Member Pro has been integrated with the additional Payment Gateways listed below. If you wish to take advantage of these additional Payment Gateway integrations, you will need to enable them explicitly from this page. Once enabled, please refresh the page. New options will become available in your s2Member Menu on the left-hand side.</p>'."\n";
			echo '<p>s2Member has the ability to operate with as many Payment Gateway integrations as you like. If you\'d like to use them all, you can! Please remember, for each Payment Gateway that you integrate, you will need to configure the options for that Payment Gateway. You\'ll then use s2Member\'s Pro-Form/Button Generators to create WordPress Shortcodes that go into your Membership Options Page <em>(aka: your Signup Page)</em>.</p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<div class="ws-menu-page-scrollbox" style="height:250px;">'."\n";
			echo '<input type="hidden" name="ws_plugin__s2member_pro_gateways_enabled[]" value="update-signal" />'."\n";
			foreach(c_ws_plugin__s2member_pro_gateways::available_gateways() as $ws_plugin__s2member_temp_s_key => $ws_plugin__s2member_temp_s_val)
				echo '<input type="checkbox" name="ws_plugin__s2member_pro_gateways_enabled[]" id="ws-plugin--s2member-pro-gateways-enabled-'.esc_attr($ws_plugin__s2member_temp_s_key).'" value="'.esc_attr($ws_plugin__s2member_temp_s_key).'"'.((in_array($ws_plugin__s2member_temp_s_key, $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_gateways_enabled"])) ? ' checked="checked"' : '').' /> <label for="ws-plugin--s2member-pro-gateways-enabled-'.esc_attr($ws_plugin__s2member_temp_s_key).'">'.$ws_plugin__s2member_temp_s_val.'</label><br /><br />'."\n";
			echo '<input type="checkbox" checked="checked" disabled="disabled" /> <label><strong>PayPal Website Payments Standard</strong> <em>(w/ Buttons)</em><br /><span style="font-size:80%;">&uarr; supports Buy Now &amp; Recurring. (<strong>core / always on</strong>)</span></label>'."\n";
			echo '</div>'."\n";
			echo 'Enable/disable Payment Gateways integrated with s2Member Pro.'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<p class="submit"><input type="submit" value="Save Changes, (then refresh)" /></p>'."\n";

			echo '</form>'."\n";

			echo '</td>'."\n";

			echo '<td class="ws-menu-page-table-r">'."\n";
			c_ws_plugin__s2member_menu_pages_rs::display();
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '</div>'."\n";
		}
	}
}

new c_ws_plugin__s2member_pro_menu_page_other_gateways ();
