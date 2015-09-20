<?php
/**
 * Menu page for s2Member Pro (PayPal option detail rows).
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

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_paypal_ops_detail_rows"))
{
	/**
	 * Menu page for s2Member Pro (PayPal option detail rows).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110531
	 */
	class c_ws_plugin__s2member_pro_menu_page_paypal_ops_detail_rows
	{
		public function __construct()
		{
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-paypal-checkout-rdp">'."\n";
			echo 'PayPal Pro-Forms :: Recurring Profile Behavior:<br />'."\n";
			echo '~ impacts the first payment in Recurring Billing Profiles<br />'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<select name="ws_plugin__s2member_pro_paypal_checkout_rdp" id="ws-plugin--s2member-pro-paypal-checkout-rdp">'."\n";
			echo '<option value="0"'.((!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_paypal_checkout_rdp"]) ? ' selected="selected"' : '').'>Consolidate w/ Recurring Profile (1st payment charged immediately)</option>'."\n";
			echo '<option value="1"'.(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_paypal_checkout_rdp"]) ? ' selected="selected"' : '').'>Real-Time / Direct Pay (1st payment charged immediately, in real-time)</option>'."\n";
			echo '</select><br />'."\n";
			echo 'Applies only to "Subscriptions" (aka: Recurring Billing Profiles). [ <a href="#" onclick="alert(\'If your PayPal Pro-Form is configured to bill on a recurring basis (starting the day of signup), this setting controls the way in which s2Member handles the first payment.\\n\\nWe recommend: [Consolidate w/ Recurring Profile], because this keeps all charges associated with a particular Customer organized in your PayPal account.\\n\\nNo matter which option you choose, a first Initial Payment (when applicable) will always be charged immediately. However, in cases where it is critical that a Customer not gain access until their first payment has been fully captured, pleae choose: [Real-Time / Direct Pay]. This tells s2Member to authorize/capture the first payment in real-time during checkout, instead of consolidating it into the Recurring Profile.\\n\\nHere Is A Breakdown\\n\\n— Consolidate w/ Recurring Profile —\\ns2Member creates a Recurring Profile with an Initial Payment amount, to be charged immediately. PayPal generates the Recurring Profile, returns a successful response to s2Member and the Customer gains access. Moments later (usually within 30 seconds), PayPal will authorize/capture the first payment. If the first payment is declined, s2Member will revoke the Customer\\\'s access immediately.\\n\\n— Real-Time / Direct Pay —\\ns2Member charges the first payment separately (in real-time during checkout), leaving no possibility for the Customer to gain access until the first charge is fully captured. A Recurring Profile is also generated, which handles any future billing. You will have two billing records in your PayPal account. One for the Initial Payment, and another for the Recurring Profile.\'); return false;">full details</a> ]'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
		}
	}
}

new c_ws_plugin__s2member_pro_menu_page_paypal_ops_detail_rows ();