<?php
/**
 * Menu page for s2Member Pro (PayPal options, Sandbox tip).
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

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_paypal_ops_sandbox_tip"))
{
	/**
	 * Menu page for s2Member Pro (PayPal options, Sandbox tip).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110531
	 */
	class c_ws_plugin__s2member_pro_menu_page_paypal_ops_sandbox_tip
	{
		public function __construct()
		{
			echo '<p><em><strong>PayPal Pro Sandbox Tip:</strong> If you\'d like to run test transactions against a PayPal Pro configuration, get yourself a <a href="http://s2member.com/r/paypal-developers/" target="_blank" rel="external">PayPal Developer account</a>. Then, create a new PayPal Pro Seller account inside the Sandbox; with PayPal Pro enabled. This requires a special, yet "fake" application. Whenever you fill out the fake Pro-Application, be sure to start your Social Security # with <code>111</code>, and then use whatever random numbers you prefer. You\'ll need to configure s2Member with your Sandbox API Credentials, and supply a Sandbox email address that is tied to a PayPal Pro Sandbox account. Once you have all of that, you can add a fake credit card to any Personal Sandbox account (that is, a Buyer account). Log out of your WordPress Dashboard before testing. You can use your fake card number &amp; expiration date for running test transactions as a would-be Customer.</em></p>'."\n";
		}
	}
}

new c_ws_plugin__s2member_pro_menu_page_paypal_ops_sandbox_tip ();