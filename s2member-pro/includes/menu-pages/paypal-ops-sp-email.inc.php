<?php
/**
 * Menu page for s2Member Pro (PayPal options, Specific Post/Page Email).
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

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_paypal_ops_sp_email"))
{
	/**
	 * Menu page for s2Member Pro (PayPal options, Specific Post/Page Email).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110531
	 */
	class c_ws_plugin__s2member_pro_menu_page_paypal_ops_sp_email
	{
		public function __construct()
		{
			echo '<div class="ws-menu-page-group" title="Specific Post/Page Confirmation Email (Pro-Form)">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-sp-confirmation-email-section">'."\n";
			echo '<h3>Specific Post/Page Confirmation Email (required, but the default works fine)<br />— specifically for s2Member Pro-Form integrations</h3>'."\n";
			echo '<p>This email is sent to new Customers after they successfully complete a PayPal "Pro-Form" submission on your site, for Specific Post/Page Access. (see: <strong>s2Member → Restriction Options → Specific Post/Page Access</strong>). This is NOT used for Membership sales, only for Specific Post/Page Access. The <strong>primary</strong> purpose of this email is to provide the Customer with a receipt, along with a link to access the Specific Post/Page they\'ve purchased access to. If you\'ve created a Specific Post/Page Package (with multiple Posts/Pages bundled together into one transaction), this ONE link (<code>%%sp_access_url%%</code>) will automatically authenticate them for access to ALL of the Posts/Pages included in their transaction. You may customize this email further by providing details that are specifically geared to your site.</p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-sp-email-recipients">'."\n";
			echo 'Specific Post/Page Confirmation Recipients:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_sp_email_recipients" id="ws-plugin--s2member-pro-sp-email-recipients" value="'.format_to_edit($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_sp_email_recipients"]).'" /><br />'."\n";
			echo 'This is a semicolon ( ; ) delimited list of Recipients. Here is an example:<br />'."\n";
			echo '<code>"%%full_name%%" &lt;%%payer_email%%&gt;; admin@example.com; "Webmaster" &lt;webmaster@example.com&gt;</code>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-sp-email-subject">'."\n";
			echo 'Specific Post/Page Confirmation Email Subject:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_sp_email_subject" id="ws-plugin--s2member-pro-sp-email-subject" value="'.format_to_edit($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_sp_email_subject"]).'" /><br />'."\n";
			echo 'Subject Line used in the email sent to a Customer after a successful purchase has occurred through a PayPal Pro-Form, for Specific Post/Page Access.'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-sp-email-message">'."\n";
			echo 'Specific Post/Page Confirmation Email Message:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<textarea name="ws_plugin__s2member_pro_sp_email_message" id="ws-plugin--s2member-pro-sp-email-message" rows="10">'.format_to_edit($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_sp_email_message"]).'</textarea><br />'."\n";
			echo 'Message Body used in the email sent to a Customer after a successful purchase has occurred through a PayPal Pro-Form, for Specific Post/Page Access.<br /><br />'."\n";
			echo '<strong>You can also use these special Replacement Codes if you need them:</strong>'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>%%sp_access_url%%</code> = The full URL (generated by s2Member) where the Customer can gain access.</li>'."\n";
			echo '<li><code>%%sp_access_exp%%</code> = Human readable expiration for <code>%%sp_access_url%%</code>. Ex: <em>(link expires in <code>%%sp_access_exp%%</code>)</em>.</li>'."\n";
			echo '<li><code>%%txn_id%%</code> = The PayPal Transaction ID. PayPal assigns a unique identifier for every purchase.</li>'."\n";
			echo '<li><code>%%currency%%</code> = Three-character currency code (uppercase); e.g., <code>USD</code></li>'."\n";
			echo '<li><code>%%currency_symbol%%</code> = Currency code symbol; e.g., <code>$</code></li>'."\n";
			echo '<li><code>%%amount%%</code> = The full Amount that you charged for Specific Post/Page Access. This value will <code>always be > 0</code>.</li>'."\n";
			echo '<li><code>%%first_name%%</code> = The First Name of the Customer who purchased Specific Post/Page Access.</li>'."\n";
			echo '<li><code>%%last_name%%</code> = The Last Name of the Customer who purchased Specific Post/Page Access.</li>'."\n";
			echo '<li><code>%%full_name%%</code> = The Full Name (First &amp; Last) of the Customer who purchased Specific Post/Page Access.</li>'."\n";
			echo '<li><code>%%payer_email%%</code> = The Email Address of the Customer who purchased Specific Post/Page Access.</li>'."\n";
			echo '<li><code>%%user_ip%%</code> = The Customer\'s IP Address, detected during checkout via <code>$_SERVER["REMOTE_ADDR"]</code>.</li>'."\n";
			echo '<li><code>%%item_number%%</code> = The Item Number. Ex: <code><em>sp:13,24,36:72</em></code> (translates to: <code><em>sp:comma-delimited IDs:expiration hours</em></code>).</li>'."\n";
			echo '<li><code>%%item_name%%</code> = The Item Name (as provided by the <code>desc=""</code> attribute in your Shortcode, which briefly describes the Item Number).</li>'."\n";
			echo '</ul>'."\n";

			echo '<strong>Coupon Replacement Codes:</strong>'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>%%full_coupon_code%%</code> = A full Coupon Code—if one is accepted by your configuration of s2Member. This may indicate an Affiliate Coupon Code, which will include your Affiliate Suffix Chars too (i.e., the full Coupon Code).</li>'."\n";
			echo '<li><code>%%coupon_code%%</code> = A Coupon Code—if one is accepted by your configuration of s2Member. This will NOT include any Affiliate Suffix Chars. This indicates the actual Coupon Code accepted by your configuration of s2Member (excluding any Affiliate ID).</li>'."\n";
			echo '<li><code>%%coupon_affiliate_id%%</code> = This is the end of an Affiliate Coupon Code <em>(i.e., the referring affiliate\'s ID)</em>. This is only applicable if an Affiliate Coupon Code is accepted by your configuration of s2Member.</li>'."\n";
			echo '</ul>'."\n";

			echo '<strong>Custom Replacement Codes can also be inserted using these instructions:</strong>'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>%%cv0%%</code> = The domain of your site, which is passed through the `custom` attribute in your Shortcode.</li>'."\n";
			echo '<li><code>%%cv1%%</code> = If you need to track additional custom variables, you can pipe delimit them into the `custom` attribute; inside your Shortcode, like this: <code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'|cv1|cv2|cv3"</code>. You can have an unlimited number of custom variables. Obviously, this is for advanced webmasters; but the functionality has been made available for those who need it.</li>'."\n";
			echo '</ul>'."\n";
			echo '<strong>This example uses cv1 to record a special marketing campaign:</strong><br />'."\n";
			echo '<em>(The campaign (i.e., christmas-promo) could be referenced using <code>%%cv1%%</code>)</em><br />'."\n";
			echo '<code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'|christmas-promo"</code>'."\n";

			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ?
				'<div class="ws-menu-page-hr"></div>'."\n".
				'<p style="margin:0;"><strong>PHP Code:</strong> It is also possible to use PHP tags—optional (for developers). If you use PHP tags, please run a test email with <code>&lt;?php print_r(get_defined_vars()); ?&gt;</code>. This will give you a full list of all PHP variables available to you in this email. The <code>$paypal</code> variable is the most important one. It contains all of the <code>$_POST</code> variables received from PayPal\'s IPN service—or from an s2Member Pro-Form integration (e.g., <code>$paypal["item_number"]</code>, <code>$paypal["item_name"]</code>, etc). Please note that all Replacement Codes will be parsed first, and then any PHP tags that you\'ve included. Also, please remember that emails are sent in plain text format.</p>'."\n"
				: '';
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";
		}
	}
}

new c_ws_plugin__s2member_pro_menu_page_paypal_ops_sp_email ();