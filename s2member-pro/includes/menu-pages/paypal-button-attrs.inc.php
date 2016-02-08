<?php
/**
 * Menu page for s2Member Pro (PayPal Button Attributes).
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
 * @since 110604
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_paypal_button_attrs"))
{
	/**
	 * Menu page for s2Member Pro (PayPal Button Attributes).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110604
	 */
	class c_ws_plugin__s2member_pro_menu_page_paypal_button_attrs
	{
		public function __construct()
		{
			echo '</ul>'."\n";

			echo '<h3>Additional Shortcode Attributes (enabled by s2Member Pro)</h3>'."\n";

			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>success=""</code> Success Return URL <em>(optional)</em>. s2Member handles this automatically for you. However, if you would prefer to take control over the landing page after checkout <em>(i.e., your own custom Thank-You Page)</em>, you can. If supplied, this must be a full URL, starting with <code>http://</code>. Note, s2Member will NOT use this value if an existing account holder is being modified. s2Member handles account updates <em>(i.e., billing modification)</em> in a more dynamic way. Your Success Return URL is only applied to (new) Customers.</li>'."\n";
		}
	}
}

new c_ws_plugin__s2member_pro_menu_page_paypal_button_attrs ();