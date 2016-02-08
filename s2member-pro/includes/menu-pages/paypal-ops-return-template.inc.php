<?php
/**
 * Menu page for s2Member Pro (PayPal Return Template).
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
 * @since 110720
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_paypal_return_template"))
{
	/**
	 * Menu page for s2Member Pro (PayPal Return Template).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110720
	 */
	class c_ws_plugin__s2member_pro_menu_page_paypal_return_template
	{
		public function __construct()
		{
			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<h3 style="margin:0;">Auto-Return Page Template (<a href="#" onclick="jQuery(\'div#ws-plugin--s2member-pro-paypal-return-page-template\').toggle(); return false;" class="ws-dotted-link">optional customizations</a>)</h3>'."\n";
			echo '<div id="ws-plugin--s2member-pro-paypal-return-page-template" style="margin-top:10px; display:none;">'."\n";
			echo '<p>With s2Member Pro installed, you have the ability to customize your <a href="'.esc_attr(home_url("/?s2member_paypal_return=1&s2member_paypal_proxy=paypal&s2member_paypal_proxy_use=x-preview")).'" target="_blank" rel="external">Auto-Return Page Template</a>. If you are using PayPal Standard integration <em>(i.e., PayPal Buttons)</em>, each of your Customers are returned back to your site immediately after they complete checkout at PayPal. Your Auto-Return Page displays a message and instructions for the Customer. s2Member may change the message and instructions dynamically, based on what the Customer is actually doing <em>(i.e., based on the type of transaction that is taking place)</em>. So, although we do not recommend that you attempt to change the message and instructions presented dynamically by s2Member, you can certainly control the Header, and/or the overall appearance of s2Member\'s Auto-Return Page Template.</p>'."\n";
			echo '<p>The quickest/easiest way, is to simply add some HTML code in the box below. For instance, you might include an <code>&lt;img&gt;</code> tag with your logo. The box below allows you to customize the Header section <em>(i.e., the top)</em> of s2Member\'s default Auto-Return Page Template. Everything else, including the textual response and other important details that each Customer needs to know about, are already handled dynamically by s2Member <em>(based on the type of transaction that is taking place)</em>. All you need to do is customize the Header with your logo and anything else you feel is important. Although this Header customization is completely optional, we recommend an <a href="http://s2member.com/r/image-tag-reference/" target="_blank" rel="external"><code>&lt;img&gt;</code> tag</a> with a logo that is around 300px wide. After you "Save All Changes" below, you may <a href="'.esc_attr(home_url("/?s2member_paypal_return=1&s2member_paypal_proxy=paypal&s2member_paypal_proxy_use=x-preview")).'" target="_blank" rel="external">click this link to see what your Header looks like</a>.</p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-paypal-return-template-header">'."\n";
			echo 'Auto-Return Page Template Header:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<textarea name="ws_plugin__s2member_pro_paypal_return_template_header" id="ws-plugin--s2member-pro-paypal-return-template-header" rows="5" wrap="off" spellcheck="false">'.format_to_edit($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_paypal_return_template_header"]).'</textarea><br />'."\n";
			echo 'Any valid XHTML / JavaScript'.((is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) ? '' : ' (or even PHP)').' code will work just fine here.'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			if(!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site())
				echo '<p>It is also possible to build your own Auto-Return Page Template, if you prefer. If you feel the need to create your own Auto-Return Page Template, please make a copy of s2Member\'s default template: <code>'.esc_html(c_ws_plugin__s2member_utils_dirs::doc_root_path($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["dir"]."/includes/templates/returns/default-template.php")).'</code>. Place your copy of this default template, inside your active WordPress theme directory, and name the file: <code>/paypal-return.php</code>. s2Member will automatically find your Auto-Return Page Template in this location, and s2Member will use your template instead of the default. Further details are provided inside s2Member\'s default template file. Once your custom template file is in place, you may <a href="'.esc_attr(home_url("/?s2member_paypal_return=1&s2member_paypal_proxy=paypal&s2member_paypal_proxy_use=x-preview")).'" target="_blank" rel="external">click this link to see what it looks like</a>.</p>'."\n";

			echo '<p>It is also possible to bypass s2Member\'s Auto-Return system altogether, if you prefer. For further details, please read more about the <code>success=""</code> Shortcode Attribute for PayPal Buttons generated by s2Member. You will find details on this inside your Dashboard, under: <strong>s2Member → PayPal Buttons → Shortcode Attributes (Explained)</strong>. Please note: you will still need to configure your PayPal account for Auto-Return/PDT <em>(as instructed above)</em>. Then, you may use the <code>success=""</code> Attribute in your Shortcode, when/if you need it. In other words, if you use the <code>success=""</code> Attribute in your Shortcode, the initial redirection back to s2Member\'s default Auto-Return/PDT handler must still occur. However, instead of s2Member displaying an Auto-Return Template to the Customer, s2Member will silently redirect the Customer to the URL that you specified in the <code>success="http://..."</code> Attribute of your Shortcode, allowing you to take complete control over what happens next.</p>'."\n";
			echo '</div>'."\n";
		}
	}
}

new c_ws_plugin__s2member_pro_menu_page_paypal_return_template ();
