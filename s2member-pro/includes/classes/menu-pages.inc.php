<?php
/**
 * Administrative Menu Pages.
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

if(!class_exists("c_ws_plugin__s2member_pro_menu_pages"))
{
	/**
	 * Administrative Menu Pages.
	 *
	 * @package s2Member\Menu_Pages
	 * @since 1.5
	 */
	class c_ws_plugin__s2member_pro_menu_pages
	{
		/**
		 * Identifies the s2Member Pro Add-on.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 1.5
		 *
		 * @attaches-to ``add_filter("plugin_row_meta");``
		 *
		 * @param array  $plugin_meta Expects an array of meta details for the ``$plugin_file``.
		 * @param string $plugin_file Expects a string holding the basename of the plugin.
		 *   We need to look for a match to the s2Member Pro Add-on in this variable.
		 *
		 * @return array Array of meta details for the plugin.
		 */
		public static function module_identifier($plugin_meta = array(), $plugin_file = '')
		{
			if($plugin_file === $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["plugin_basename"])
			{
				$plugin_meta[0] .= ' + <strong>s2Member Pro v'.esc_html(WS_PLUGIN__S2MEMBER_PRO_VERSION).'</strong>';
			}
			return $plugin_meta; // Now return all plugin meta; imploded by WordPress.
		}

		/**
		 * Add the Coupon Codes page.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 1.5
		 *
		 * @attaches-to ``add_filter("ws_plugin__s2member_during_add_admin_options_add_divider_2");``
		 *
		 * @param bool  $add_divider Expects a boolean value, passed through by the Filter.
		 * @param array $vars Expects an array of defined variables passed through by the Filter.
		 *
		 * @return bool Passes back the original value of ``$add_divider``.
		 */
		public static function add_coupon_codes_page($add_divider = TRUE, $vars = array())
		{
			add_submenu_page($vars["menu"], "s2Member Pro / Coupon Codes", "Pro Coupon Codes", "create_users", "ws-plugin--s2member-pro-coupon-codes", "c_ws_plugin__s2member_pro_menu_pages::coupon_codes_page");

			return $add_divider; // Now add the divider.
		}

		/**
		 * Add the Pro Add-on Import/Export page here.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 1.5
		 *
		 * @attaches-to ``add_filter("ws_plugin__s2member_during_add_admin_options_add_divider_2");``
		 *
		 * @param bool  $add_divider Expects a boolean value, passed through by the Filter.
		 * @param array $vars Expects an array of defined variables passed through by the Filter.
		 *
		 * @return bool Passes back the original value of ``$add_divider``.
		 */
		public static function add_import_export_page($add_divider = TRUE, $vars = array())
		{
			add_submenu_page($vars["menu"], "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:1px; line-height:1px; background:#CCCCCC;"></span>', "create_users", "#");
			add_submenu_page($vars["menu"], "s2Member Pro (User Import/Export)", "Import / Export", "create_users", "ws-plugin--s2member-pro-import-export", "c_ws_plugin__s2member_pro_menu_pages::import_export_page");

			return $add_divider; // Now add the divider.
		}

		/**
		 * Add the page for configuration of other Payment Gateways.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 1.5
		 *
		 * @attaches-to ``add_filter("ws_plugin__s2member_during_add_admin_options_add_divider_3");``
		 *
		 * @param bool  $add_divider Expects a boolean value, passed through by the Filter.
		 * @param array $vars Expects an array of defined variables passed through by the Filter.
		 *
		 * @return bool Passes back the original value of ``$add_divider``.
		 */
		public static function add_other_gateways_page($add_divider = TRUE, $vars = array())
		{
			add_submenu_page($vars["menu"], "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:1px; line-height:1px; background:#CCCCCC;"></span>', "create_users", "#");
			add_submenu_page($vars["menu"], "s2Member Pro / Other Payment Gateways", "Other Gateways", "create_users", "ws-plugin--s2member-pro-other-gateways", "c_ws_plugin__s2member_pro_menu_pages::other_gateways_page");

			return $add_divider; // Now add the divider.
		}

		/**
		 * Add options for Login Welcome Page / One-Time-Offers.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 110720
		 *
		 * @attaches-to ``add_action("ws_plugin__s2member_during_gen_ops_page_during_left_sections_after_login_welcome_page");``
		 *
		 * @param array $vars Expects an array of defined variables passed through by the Action Hook.
		 */
		public static function gen_ops_lwp_otos($vars = array())
		{
			include_once dirname(dirname(__FILE__))."/menu-pages/gen-ops-lwp-otos.inc.php";
		}

		/**
		 * Add options for captcha anti-spam preferences.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 111203
		 *
		 * @attaches-to ``add_action("ws_plugin__s2member_during_gen_ops_page_during_left_sections_after_url_shortening");``
		 *
		 * @param array $vars Expects an array of defined variables passed through by the Action Hook.
		 */
		public static function gen_ops_captcha_ops($vars = array())
		{
			include_once dirname(dirname(__FILE__))."/menu-pages/gen-ops-captcha-ops.inc.php";
		}

		/**
		 * Add instructions to configure Membership Levels.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 1.5
		 *
		 * @attaches-to ``add_action("ws_plugin__s2member_during_gen_ops_page_during_left_sections_during_membership_levels");``
		 *
		 * @param array $vars Expects an array of defined variables passed through by the Action Hook.
		 */
		public static function add_level_instructions($vars = array())
		{
			if(!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site())
				include_once dirname(dirname(__FILE__))."/menu-pages/unlimited-level-instructions.inc.php";
		}

		/**
		 * Builds the documentation for the Remote Operations API.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 110713
		 *
		 * @attaches-to ``add_filter("ws_plugin__s2member_during_scripting_page_during_left_sections_display_api_hooks");``
		 *
		 * @param bool $display_api_hooks Expects a boolean value passed through by the Filter.
		 *
		 * @return bool The same value of ``$display_api_hooks`` passes through.
		 */
		public static function scripting_page_remote_ops_api($display_api_hooks = FALSE)
		{
			include_once dirname(dirname(__FILE__))."/menu-pages/scripting-api-remote-ops.inc.php";

			return $display_api_hooks; // Now display API Hooks.
		}

		/**
		 * Builds the documentation for the Pro Login Widget.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 1.5
		 *
		 * @attaches-to ``add_filter("ws_plugin__s2member_during_scripting_page_during_left_sections_display_api_hooks");``
		 *
		 * @param bool $display_api_hooks Expects a boolean value passed through by the Filter.
		 *
		 * @return bool The same value of ``$display_api_hooks`` passes through.
		 */
		public static function scripting_page_login_widget_api($display_api_hooks = FALSE)
		{
			include_once dirname(dirname(__FILE__))."/menu-pages/scripting-api-login-widget.inc.php";

			return $display_api_hooks; // Now display API Hooks.
		}

		/**
		 * Builds the documentation for Scripting / API Constants.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 1.5
		 *
		 * @attaches-to ``add_action("ws_plugin__s2member_during_scripting_page_during_left_sections_during_list_of_api_constants");``
		 *
		 * @param array $vars Expects an array of defined variables passed through by the Action Hook.
		 */
		public static function scripting_page_api_constants($vars = array())
		{
			include_once dirname(dirname(__FILE__))."/menu-pages/scripting-api-constants.inc.php";
		}

		/**
		 * Builds documentation for additional Shortcode Attributes integrated w/ PayPal Buttons.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 1.5
		 *
		 * @attaches-to ``add_action("ws_plugin__s2member_during_paypal_buttons_page_during_left_sections_during_shortcode_attrs_lis");``
		 *
		 * @param array $vars Expects an array of defined variables passed through by the Action Hook.
		 */
		public static function paypal_button_attrs($vars = array())
		{
			include_once dirname(dirname(__FILE__))."/menu-pages/paypal-button-attrs.inc.php";
		}

		/**
		 * Builds options to customize the PayPal Return-Page template.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 110720
		 *
		 * @attaches-to ``add_action("ws_plugin__s2member_during_paypal_ops_page_during_left_sections_during_paypal_pdt_after_more_info");``
		 *
		 * @param array $vars Expects an array of defined variables passed through by the Action Hook.
		 */
		public static function paypal_return_template($vars = array())
		{
			include_once dirname(dirname(__FILE__))."/menu-pages/paypal-ops-return-template.inc.php";
		}

		/**
		 * Builds the other Payment Gateways page.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 1.5
		 */
		public static function other_gateways_page()
		{
			c_ws_plugin__s2member_menu_pages::update_all_options(FALSE, FALSE, TRUE, TRUE, FALSE, TRUE);

			include_once dirname(dirname(__FILE__))."/menu-pages/other-gateways.inc.php";
		}

		/**
		 * Builds the User Import / Export page.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 1.5
		 */
		public static function import_export_page()
		{
			c_ws_plugin__s2member_pro_imports::import(); // Handles imports.
			c_ws_plugin__s2member_pro_imports_simple::import(); // Handles imports.

			include_once dirname(dirname(__FILE__))."/menu-pages/import-export.inc.php";
		}

		/**
		 * Builds the Coupon Codes page.
		 *
		 * @package s2Member\Menu_Pages
		 * @since 1.5
		 */
		public static function coupon_codes_page()
		{
			c_ws_plugin__s2member_menu_pages::update_all_options(); // Updates options.

			include_once dirname(dirname(__FILE__))."/menu-pages/coupon-codes.inc.php";
		}
	}
}