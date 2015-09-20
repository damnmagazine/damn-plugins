<?php
/**
 * s2Member Pro Gateways.
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
 * @package s2Member\Gateways
 * @since 1.5
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_gateways'))
{
	/**
	 * s2Member Pro Gateways.
	 *
	 * @package s2Member\Gateways
	 * @since 1.5
	 */
	class c_ws_plugin__s2member_pro_gateways
	{
		/**
		 * Array of available Payment Gateways supported by s2Member Pro.
		 *
		 * @package s2Member\Gateways
		 * @since 1.5
		 *
		 * @return array Array of available Payment Gateways.
		 */
		public static function available_gateways() // Payment Gateways available.
		{
			$gateways = array('alipay' => '<span style="opacity:0.5;"><strong>AliPay</strong> <em>(w/ Buttons)</em><br /><span style="font-size:80%;">&uarr; <a href="http://s2member.com/r/alipay-deprecation-kb-article/" target="_blank" rel="external">deprecated</a> June 15th, 2015.</span></span>', 'stripe' => '<strong>Stripe + Bitcoin</strong> <em>(w/ Pro-Forms)</em><br /><span style="font-size:80%;">&uarr; supports Buy Now &amp; Recurring Products.</span>', 'authnet' => '<strong>Authorize.Net</strong> <em>(w/ Pro-Forms)</em><br /><span style="font-size:80%;">&uarr; supports Buy Now &amp; Recurring Products.</span>', 'ccbill' => '<span style="opacity:0.5;"><strong>ccBill</strong> <em>(w/ Buttons)</em><br /><span style="font-size:80%;">&uarr; <a href="http://s2member.com/r/ccbill-deprecation-kb-article/" target="_blank" rel="external">deprecated</a> June 15th, 2015.</span></span>', 'clickbank' => '<strong>ClickBank</strong> <em>(w/ Buttons)</em><br /><span style="font-size:80%;">&uarr; supports Buy Now &amp; Recurring Products.</span>', 'google' => '<span style="opacity:0.5;"><strong>Google Wallet</strong> <em>(w/ Buttons)</em><br /><span style="font-size:80%;">&uarr; <a href="http://www.s2member.com/r/google-wallet-retirement/" target="_blank" rel="external">retired by Google</a> March 2nd, 2015.</span></span>', 'paypal' => '<strong>PayPal Website Payments Pro</strong> <em>(w/ Pro-Forms)</em><br /><span style="font-size:80%;">&uarr; supports Buy Now &amp; Recurring Products.</span>');

			return apply_filters('ws_plugin__s2member_pro_available_gateways', $gateways, get_defined_vars());
		}

		/**
		 * Adds to the list of Payment Gateways in User Profile management panels.
		 *
		 * @package s2Member\Gateways
		 * @since 1.5
		 *
		 * @attaches-to ``add_filter('ws_plugin__s2member_profile_s2member_subscr_gateways');``
		 *
		 * @param array $gateways Expects an array of Payment Gateways, passed through by the Filter.
		 *
		 * @return array Array of Payment Gateways to appear in Profile editing panels.
		 */
		public static function profile_subscr_gateways($gateways)
		{
			$available_gateways = array_keys(c_ws_plugin__s2member_pro_gateways::available_gateways());

			foreach(($others = array('alipay' => 'AliPay (code: alipay)', 'stripe' => 'Stripe (code: stripe)', 'authnet' => 'Authorize.Net (code: authnet)', 'ccbill' => 'ccBill (code: ccbill)', 'clickbank' => 'ClickBank (code: clickbank)', 'google' => 'Google Wallet (code: google)')) as $other => $gateway)
				if(!in_array($other, $available_gateways))
					unset($others[$other]);

			return apply_filters('ws_plugin__s2member_pro_profile_subscr_gateways', array_unique(array_merge((array)$gateways, $others)), get_defined_vars());
		}

		/**
		 * Loads Hooks/Functions/Codes for other Payment Gateways.
		 *
		 * @package s2Member\Gateways
		 * @since 1.5
		 *
		 * @attaches-to ``add_action('ws_plugin__s2member_after_loaded');``
		 */
		public static function load_gateways() // Load Hooks/Functions/Codes for other Gateways.
		{
			foreach(array_keys(c_ws_plugin__s2member_pro_gateways::available_gateways()) as $gateway)
				if(in_array($gateway, $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_gateways_enabled']))
				{
					include_once dirname(dirname(__FILE__)).'/separates/gateways/'.$gateway.'/'.$gateway.'-hooks.inc.php';
					include_once dirname(dirname(__FILE__)).'/separates/gateways/'.$gateway.'/'.$gateway.'-funcs.inc.php';
					include_once dirname(dirname(__FILE__)).'/separates/gateways/'.$gateway.'/'.$gateway.'-codes.inc.php';
				}
			if(!$GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_gateways_seen'])
				add_action('admin_init', 'c_ws_plugin__s2member_pro_gateways::maybe_draw_attention_to_gateways');
		}

		/**
		 * Draw attention to other payment gateways.
		 *
		 * @package s2Member\Gateways
		 * @since 150717
		 *
		 * @attaches-to ``add_action('admin_init');``
		 */
		public static function maybe_draw_attention_to_gateways()
		{
			if(is_network_admin()) return; // Not applicable.

			if($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_gateways_seen'])
				return; // Not applicable. Seen already.

			$GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_gateways_seen'] = '1';
			update_option('ws_plugin__s2member_options', $GLOBALS['WS_PLUGIN__']['s2member']['o']);
			if(is_multisite() && is_main_site()) update_site_option('ws_plugin__s2member_options', $GLOBALS['WS_PLUGIN__']['s2member']['o']);

			// If `unconfigured` is not in the array of gateways they have already been configured in that scenario.
			// 	Or, perhaps this is a site that was setup prior to 150717; i.e., this notice is not applicable.
			if(!in_array('unconfigured', $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_gateways_enabled'], TRUE))
				return; // Already configured these; i.e., back compatibility.

			$page   = admin_url('/admin.php?page=ws-plugin--s2member-pro-other-gateways');
			$notice = '<strong>s2Member® Pro says...</strong> Please configure <a href="'.esc_attr($page).'" style="text-decoration:underline;">Other Payment Gateways</a>; i.e., choose which payment gateways you would like to use.';

			c_ws_plugin__s2member_admin_notices::enqueue_admin_notice($notice, 'blog:*');
		}
	}
}
