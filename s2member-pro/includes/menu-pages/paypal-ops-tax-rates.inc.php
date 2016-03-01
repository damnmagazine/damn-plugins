<?php
/**
 * Menu page for s2Member Pro (PayPal options, Tax Rates).
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
	exit ("Do not access this file directly.");

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_paypal_ops_tax_rates"))
{
	/**
	 * Menu page for s2Member Pro (PayPal options, Tax Rates).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110531
	 */
	class c_ws_plugin__s2member_pro_menu_page_paypal_ops_tax_rates
	{
		public function __construct()
		{
			echo '<div class="ws-menu-page-group" title="Tax Rate Calculations (Pro-Form)">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-tax-rates-section">'."\n";
			echo '<h3>Tax Rate Calculations for PayPal Pro-Forms (optional)<br />— specifically for s2Member Pro-Form integrations</h3>'."\n";
			echo '<p>With PayPal Pro, your software (s2Member Pro) is solely responsible for calculating Tax Rates. In the fields below, you can set a Global Default Tax Rate, and/or a "Custom Tax Configuration File"; which can be applied to specific countries, specific states, provinces, and even to specific zip code ranges. * Tax Rate calculations are fully compatible with international currencies and locations.</p>'."\n";
			echo '<p>When you create a PayPal Pro-Form with s2Member, you\'ll be asked to supply a <em>Charge Amount</em>. Then, during checkout... s2Member calculates Tax. The calculated Tax Rate is added to the <em>Charge Amount</em> in your PayPal Pro Shortcode. The Tax Rate will be displayed to a Customer during checkout, <strong>after</strong> they\'ve supplied a Billing Address. For example, if you create a PayPal Pro-Form that charges a Customer <strong>$24.95</strong>, and the Tax Rate is configured as 7.0%; s2Member will automatically calculate the Tax as $1.75. A Customer will pay the Total Amount (<em>Charge</em> + Tax = <strong>$26.70</strong>).</p>'."\n";
			echo '<p><em><strong>Quick Tip:</strong> If you configure Tax, it\'s good to include a note somewhere in the <code>desc=""</code> attribute of your Shortcode. Something like <code>desc="$x.xx (plus tax)"</code>.</em></p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-default-tax">'."\n";
			echo 'Global Default Tax Rate:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_default_tax" id="ws-plugin--s2member-pro-default-tax" value="'.format_to_edit($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_default_tax"]).'" /><br />'."\n";
			echo 'This can be a flat tax <code>(1.75)</code>, or a percentage <code>(7.0%)</code>.'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-tax-rates">'."\n";
			echo 'Custom Tax Configuration File (one rate per line)<br />'."\n";
			echo 'Apply different Tax Rates by country, state/province, or zip code range:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<textarea name="ws_plugin__s2member_pro_tax_rates" id="ws-plugin--s2member-pro-tax-rates" rows="10" wrap="off" spellcheck="false">'.format_to_edit($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_tax_rates"]).'</textarea><br />'."\n";
			echo 'Please use one of the following formats (<a href="#" onclick="alert(\'US=7.0%\nCA=12.0%\nHK=0.0%\nFLORIDA/US=7.5%\nIDAHO/US=6.0%\nALBERTA/CA=5.0%\nBRITISH COLUMBIA/CA=12.0%\n32000-34999/US=7.5%\n83200-83999/US=6.0%\n32601/US=6.5%\'); return false;">click for examples</a>)<br /><br />'."\n";
			echo '<code>2-CHARACTER COUNTRY CODE = Flat rate or percentage.</code>—low precedence<br />'."\n";
			echo '<code>STATE OR PROVINCE/2-CHARACTER COUNTRY CODE = Flat rate or percentage.</code>—higher precedence<br />'."\n";
			echo '<code>ZIP CODE-ZIP CODE/2-CHARACTER COUNTRY CODE = Flat rate or percentage.</code>—higher precedence (zip code range)<br />'."\n";
			echo '<code>ZIP CODE/2-CHARACTER COUNTRY CODE = Flat rate or percentage.</code>—highest precedence (specific zip code)'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";
		}
	}
}

new c_ws_plugin__s2member_pro_menu_page_paypal_ops_tax_rates ();