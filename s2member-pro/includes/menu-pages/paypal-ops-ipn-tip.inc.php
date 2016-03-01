<?php
/**
 * Menu page for s2Member Pro (PayPal options, IPN tip).
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

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_paypal_ops_ipn_tip"))
{
	/**
	 * Menu page for s2Member Pro (PayPal options, IPN tip).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110531
	 */
	class c_ws_plugin__s2member_pro_menu_page_paypal_ops_ipn_tip
	{
		public function __construct()
		{
			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site())
				? '<p><em><strong>NOT True w/ PayPal Pro:</strong> With PayPal Pro integration you absolutely must set an IPN URL inside your PayPal account. PayPal Pro integration does not allow the IPN location to be overridden on a per-transaction basis. If you\'re using a single PayPal Pro account for multiple cross-domain installations, and you need to receive IPN notifications for each of your domains; you\'ll want to create a central IPN processing script that scans variables in each IPN response, forking itself out to each of your individual domains. In rare cases when this is necessary, you\'ll find two variables in all IPN responses for s2Member. The originating domain name (i.e., <code>'.esc_html($_SERVER["HTTP_HOST"]).'</code>) will always be included somewhere within, either: <code>custom</code> and/or <code>rp_invoice_id</code>; depending on the type of transaction. These variables can be used to test incoming IPNs, and fork to the proper installation. For your convenience, an example script has been provided inside: <code>/s2m-pro-extras/paypal-central-ipn.php</code>. You can download all Extras here: <a href="http://s2member.com/r/s2m-pro-extras/">s2m-pro-extras.zip</a>.</em></p>'."\n"
				: '<p><em><strong>NOT True w/ PayPal Pro:</strong> With PayPal Pro integration you absolutely must set an IPN URL inside your PayPal account. PayPal Pro integration does NOT allow the IPN location to be overridden on a per-transaction basis. If you\'re using a single PayPal Pro account for multiple cross-site installations, and you need to receive IPN notifications for each of your sites; you\'ll want to create a central IPN processing script that scans variables in each IPN response, forking itself out to each of your individual domains. In rare cases when this is necessary, you\'ll find two variables in all IPN responses for s2Member. The originating domain name (i.e., <code>'.esc_html($_SERVER["HTTP_HOST"]).'</code>) will always be included somewhere within, either: <code>custom</code> and/or <code>rp_invoice_id</code>; depending on the type of transaction. These variables can be used to test incoming IPNs, and fork to the proper installation. For your convenience, an example script has been provided inside: <code>/s2m-pro-extras/paypal-central-ipn.php</code>. You can download all Extras here: <a href="http://s2member.com/r/s2m-pro-extras/">s2m-pro-extras.zip</a>. Please contact Support if you have multiple cross-site installations and you need assistance setting this up.</em></p>'."\n";
		}
	}
}

new c_ws_plugin__s2member_pro_menu_page_paypal_ops_ipn_tip();