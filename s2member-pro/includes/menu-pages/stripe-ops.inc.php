<?php
/**
 * Menu page for s2Member Pro (Stripe Options page).
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
 * @since 140617
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit ('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_menu_page_stripe_ops'))
{
	/**
	 * Menu page for s2Member Pro (Stripe Options page).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 140617
	 */
	class c_ws_plugin__s2member_pro_menu_page_stripe_ops
	{
		public function __construct()
		{
			echo '<div class="wrap ws-menu-page">'."\n";

			echo '<div class="ws-menu-page-toolbox">'."\n";
			c_ws_plugin__s2member_menu_pages_tb::display();
			echo '</div>'."\n";

			echo '<h2>Stripe Options</h2>'."\n";

			echo '<table class="ws-menu-page-table">'."\n";
			echo '<tbody class="ws-menu-page-table-tbody">'."\n";
			echo '<tr class="ws-menu-page-table-tr">'."\n";
			echo '<td class="ws-menu-page-table-l">'."\n";

			echo '<form method="post" name="ws_plugin__s2member_pro_options_form" id="ws-plugin--s2member-pro-options-form" autocomplete="off">'."\n";
			echo '<input type="hidden" name="ws_plugin__s2member_options_save" id="ws-plugin--s2member-options-save" value="'.esc_attr(wp_create_nonce('ws-plugin--s2member-options-save')).'" />'."\n";

			echo '<div class="ws-menu-page-group" title="Stripe Account Details">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-stripe-account-details-section">'."\n";

			echo '<img src="'.esc_attr($GLOBALS['WS_PLUGIN__']['s2member']['c']['dir_url']).'/images/large-icon.png" title="s2Member (a Membership management system for WordPress)" alt="" style="float:right; margin:0 0 0 25px; border:0;" />'."\n";
			echo '<a href="http://www.s2member.com/r/stripe/" target="_blank"><img src="'.esc_attr($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"]).'/images/stripe-logo.png" class="ws-menu-page-right" style="width:250px; height:116px; background:#0D1F2F; border-radius:5px; border:0; margin-bottom:10px;" alt="." /></a>'."\n";
			echo '<a href="http://www.s2member.com/r/stripe-bitcoin-enable/" target="_blank"><img src="'.esc_attr($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"]).'/images/bitcoin-logo.png" class="ws-menu-page-right" style="clear:both; width:225px; height:74px; border:0; margin-left:5px; margin-bottom:10px;" alt="." /></a>'."\n";
			echo '<i class="fa fa-3x fa-plus ws-menu-page-right" style="color:#BECD97;"></i>'."\n";

			echo '<h3>Stripe Account Details (required)</h3>'."\n";
			echo '<p><a href="http://www.s2member.com/r/stripe/" target="_blank" rel="external">Stripe</a> absolutely rocks! It\'s a developer-friendly way to accept payments online and in mobile apps. They process billions of dollars a year for thousands of companies of all sizes. Easy to integrate; and easy for customers to use.</p>'."\n";
			echo '<p>s2Member Pro has been integrated with Stripe for Direct Payments and also for Subscriptions (Automated Recurring Billing). In order to take advantage of this integration, you will need to have a Stripe Merchant Account (free). Once you have an account, all of the details below can be obtained from inside of your Stripe account. If you need assistance, please check their <a href="http://www.s2member.com/r/stripe-help/" target="_blank" rel="external">help section</a>.</p>'."\n";
			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? '<p><em><strong>Secure Server:</strong> In order to comply with Stripe and PCI Compliance policies, as set forth by major credit card companies; you will need to host all of your Stripe Pro-Forms on an SSL enabled site. Please check with your hosting provider to ask about obtaining an SSL certificate for your domain. Please note... when you create Stripe Pro-Forms with s2Member; you\'ll be supplied with WordPress Shortcodes, which you\'ll insert into Posts/Pages of your choosing. These special Posts/Pages will need to be displayed in SSL mode, using links that start with (<code>https://</code>). &mdash; You can skip the SSL certificate during Development/Sandbox testing. SSL is not required until you officially go live. Once you\'re live, you can add the Custom Field <code>s2member_force_ssl = yes</code> to any Post/Page.</em></p>'."\n" : '<p><em><strong>Secure Server:</strong> In order to comply with Stripe and PCI Compliance policies, as set forth by major credit card companies; you will need to host all of your Stripe Pro-Forms on an SSL enabled page. When you create Stripe Pro-Forms with s2Member; you\'ll be supplied with WordPress Shortcodes, which you\'ll insert into Posts/Pages of your choosing. These special Posts/Pages will need to be displayed in SSL mode, using links that start with (<code>https://</code>). You can add the Custom Field <code>s2member_force_ssl = yes</code> to any Post/Page that contains a Pro-Form Shortcode. This tells s2Member to force those special Posts/Pages to be viewed over SSL at all times; no matter what.</em></p>'."\n";
			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? '<p><em><strong>SSL Compatibility:</strong> Most themes available at <a href="http://www.s2member.com/r/themeforest/" target="_blank" rel="external">ThemeForest™</a> include full support for SSL, as does WordPress itself. However, there are many themes/plugins that do NOT support SSL enabled Posts/Pages like they should. For this reason, you should be very careful when choosing a WordPress theme to use with s2Member Pro. Otherwise, your visitors could see the famous "Secure/Insecure" warnings in Internet Explorer browsers. With s2Member installed, you can add the Custom Field <code>s2member_force_ssl = yes</code> to any Post/Page. s2Member will buffer output on those special Posts/Pages, converting everything over to <code>https://</code> for you automatically, and forcing those specific Posts/Pages to be viewed over a secure SSL connection; so long as your server supports the https protocol.</em></p>'."\n" : '';

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-stripe-api-secret-key">'."\n";
			echo 'Stripe Secret API Key:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="password" autocomplete="off" name="ws_plugin__s2member_pro_stripe_api_secret_key" id="ws-plugin--s2member-pro-stripe-api-secret-key" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_stripe_api_secret_key']).'" /><br />'."\n";
			echo 'You\'ll find this in your Stripe Merchant account, under: <strong>Account Settings → API Keys</strong>.'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-stripe-api-publishable-key">'."\n";
			echo 'Stripe Publishable API Key:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_stripe_api_publishable_key" id="ws-plugin--s2member-pro-stripe-api-publishable-key" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_stripe_api_publishable_key']).'" /><br />'."\n";
			echo 'You\'ll find this in your Stripe Merchant account, under: <strong>Account Settings → API Keys</strong>.'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-stripe-api-accept-bitcoin">'."\n";
			echo 'Accept Bitcoin via Stripe?'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<select name="ws_plugin__s2member_pro_stripe_api_accept_bitcoin" id="ws-plugin--s2member-pro-stripe-api-accept-bitcoin">'."\n";
			echo '<option value="0"'.((!$GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_stripe_api_accept_bitcoin']) ? ' selected="selected"' : '').'>No, do not accept Bitcoin (default Stripe behavior)</option>'."\n";
			echo '<option value="1"'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_stripe_api_accept_bitcoin']) ? ' selected="selected"' : '').'>Yes, accept Bitcoin (enabled for "Buy Now" only; i.e., recurring charges not possible at this time)</option>'."\n";
			echo '</select><br />'."\n";
			echo 'Works for "Buy Now" transactions in USD only; i.e., other currency conversions not supported by Stripe at this time.<br />'."\n";
			echo 'Turning this on requires that you <a href="http://www.s2member.com/r/stripe-bitcoin-enable/" target="_blank" rel="external">enable the live Bitcoin API on your account</a>'."\n";
			echo '<p><em><strong>Tip:</strong> This setting for Bitcoin is a global flag that enables Bitcoin for all Pro-Forms that offer a "Buy Now" item. However, you can also enable/disable Bitcoin in a specific Pro-Form (regardless of your configuration here), by adding the Shortcode Attribute <code>accept="bitcoin"</code> (to enable Bitcoin) or <code>accept=""</code> (to disable Bitcoin). For further details, please see: <strong>s2Member → Stripe Pro-Forms → Shortcode Attributes (Explained)</strong></em></p>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-stripe-api-image">'."\n";
			echo 'Stripe Image Branding:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_stripe_api_image" id="ws-plugin--s2member-pro-stripe-api-image" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_stripe_api_image']).'" /><br />'."\n";
			echo 'Minimum size of <code>128px</code> x <code>128px</code> (square). Stripe displays this image above credit card input fields; <code>https://...</code> recommended here.'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-stripe-api-statement-description">'."\n";
			echo 'Stripe Statement Description:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" maxlength="15" name="ws_plugin__s2member_pro_stripe_api_statement_description" id="ws-plugin--s2member-pro-stripe-api-statement-description" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_stripe_api_statement_description']).'" placeholder="MYCOMPANY-INC" /><br />'."\n";
			echo 'An arbitrary string to be displayed alongside your company name. This appears on your customer\'s credit card statement. 15 characters max. The statement description may NOT include these special characters: <code>'.esc_html('<>"\'').'</code>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-stripe-api-validate-zipcode">'."\n";
			echo 'Stripe Should Verify Zipcodes?'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<select name="ws_plugin__s2member_pro_stripe_api_validate_zipcode" id="ws-plugin--s2member-pro-stripe-api-validate-zipcode">'."\n";
			echo '<option value="0"'.((!$GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_stripe_api_validate_zipcode']) ? ' selected="selected"' : '').'>No, do not validate a customer\'s billing zipcode (default Stripe behavior)</option>'."\n";
			echo '<option value="1"'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_stripe_api_validate_zipcode']) ? ' selected="selected"' : '').'>Yes, validate the customer\'s zipcode to be sure it matches the card\'s billing address</option>'."\n";
			echo '</select>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-stripe-api-reject-prepaid">'."\n";
			echo 'Reject or Allow Prepaid Cards?'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<select name="ws_plugin__s2member_pro_stripe_api_reject_prepaid" id="ws-plugin--s2member-pro-stripe-api-reject-prepaid">'."\n";
			echo '<option value="0"'.((!$GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_stripe_api_reject_prepaid']) ? ' selected="selected"' : '').'>Allow; I will accept all types of cards; even prepaid cards (recommended, default)</option>'."\n";
			echo '<option value="1"'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_stripe_api_reject_prepaid']) ? ' selected="selected"' : '').'>Reject; refuse to accept cards that Stripe detects as being "prepaid" funding sources</option>'."\n";
			echo '</select><br />'."\n";
			echo '<em><strong>Note:</strong> You can override this global default in a specific Pro-Form with the <code>reject_prepaid=""</code> attribute. See: <strong>s2Member → Stripe Pro-Forms → Shortcode Attributes (Explained)</strong> for details.</em>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th style="padding-top:0;">'."\n";
			echo '<label for="ws-plugin--s2member-pro-stripe-sandbox">'."\n";
			echo 'Sandbox/Test Mode?'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="radio" name="ws_plugin__s2member_pro_stripe_sandbox" id="ws-plugin--s2member-pro-stripe-sandbox-0" value="0"'.((!$GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_stripe_sandbox']) ? ' checked="checked"' : '').' /> <label for="ws-plugin--s2member-pro-stripe-sandbox-0">No</label> &nbsp;&nbsp;&nbsp; <input type="radio" name="ws_plugin__s2member_pro_stripe_sandbox" id="ws-plugin--s2member-pro-stripe-sandbox-1" value="1"'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_stripe_sandbox']) ? ' checked="checked"' : '').' /> <label for="ws-plugin--s2member-pro-stripe-sandbox-1">Yes, enable support for Sandbox testing.</label><br />'."\n";
			echo '<em>Only enable this if you\'ve provided test credentials above. This puts s2Member\'s Stripe integration into Sandbox/Test mode. See: <a href="http://www.s2member.com/r/stripe-test-accounts/" target="_blank" rel="external">Stripe Test Accounts</a></em>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			if(!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site())
			{
				echo '<div class="ws-menu-page-hr"></div>'."\n";

				echo '<table class="form-table">'."\n";
				echo '<tbody>'."\n";
				echo '<tr>'."\n";

				echo '<th>'."\n";
				echo '<label for="ws-plugin--s2member-gateway-debug-logs">'."\n";
				echo 'Enable Logging Routines?<br />'."\n";
				echo '<small><em class="ws-menu-page-hilite">* This setting applies universally. [ <a href="#" onclick="alert(\'This configuration option may ALSO appear under (s2Member → PayPal Options). Feel free to configure it here; but please remember that this setting is applied universally (i.e., SHARED) among all Payment Gateways integrated with s2Member.\'); return false;">?</a> ]</em></small>'."\n";
				echo '</label>'."\n";
				echo '</th>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<td>'."\n";
				echo '<input type="radio" name="ws_plugin__s2member_gateway_debug_logs" id="ws-plugin--s2member-gateway-debug-logs-0" value="0"'.((!$GLOBALS['WS_PLUGIN__']['s2member']['o']['gateway_debug_logs']) ? ' checked="checked"' : '').' /> <label for="ws-plugin--s2member-gateway-debug-logs-0">No</label> &nbsp;&nbsp;&nbsp; <input type="radio" name="ws_plugin__s2member_gateway_debug_logs" id="ws-plugin--s2member-gateway-debug-logs-1" value="1"'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['gateway_debug_logs']) ? ' checked="checked"' : '').' /> <label for="ws-plugin--s2member-gateway-debug-logs-1">Yes, enable debugging, with API, Webhook/IPN &amp; Return Page logging.</label><br />'."\n";
				echo '<em>This enables API, Webhook/IPN and Return Page logging. The log files are stored here: <code>'.esc_html(c_ws_plugin__s2member_utils_dirs::doc_root_path($GLOBALS['WS_PLUGIN__']['s2member']['c']['logs_dir'])).'</code></em><br />'."\n";
				echo '<em class="ws-menu-page-hilite">If you have any trouble, please review your s2Member log files for problems. See: <a href="'.esc_attr(admin_url('/admin.php?page=ws-plugin--s2member-logs')).'">Log Viewer</a></em>'."\n";
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<td>'."\n";
				echo '<div class="info" style="margin-bottom:0;">'."\n";
				echo '<p style="margin-top:0;"><span>We highly recommend that you enable logging during your initial testing phase. Logs produce lots of useful details that can help in debugging. Logs can help you find issues in your configuration and/or problems during payment processing. See: <a href="'.esc_attr(admin_url('/admin.php?page=ws-plugin--s2member-logs')).'">Log Files (Debug)</a>.</span></p>'."\n";
				echo '<p style="margin-bottom:0;"><span class="ws-menu-page-error">However, it is very important to disable logging once you go live. Log files may contain personally identifiable information, credit card numbers, secret API credentials, passwords and/or other sensitive information. We strongly suggest that logging be disabled on a live site (for security reasons).</span></p>'."\n";
				echo '</div>'."\n";
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '</tbody>'."\n";
				echo '</table>'."\n";
			}
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Stripe Webhook/IPN Integration">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-stripe-ipn-section">'."\n";
			echo '<a href="http://www.s2member.com/r/stripe/" target="_blank"><img src="'.esc_attr($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"]).'/images/stripe-logo.png" class="ws-menu-page-right" style="width:250px; height:116px; background:#0D1F2F; border-radius:5px; border:0; margin-bottom:10px;" alt="." /></a>'."\n";
			echo '<h3>Stripe Webhook/IPN Integration (required)</h3>'."\n";
			echo '<p>Log into your Stripe Merchant account and navigate to this section:<br /><strong>Account Settings → Webhooks</strong></p>'."\n";
			echo '<p>Your Stripe Webhook URL is:<br /><code>'.esc_html(home_url('/?s2member_pro_stripe_notify=1')).'</code></p>'."\n";
			echo '<div class="info" style="margin-bottom:0;">'."\n";
			echo '<p>If you are currently in Test/Sandbox mode (i.e., you gave s2Member Test API Credentials); please choose the <code>Test</code> option when entering the Webhook URL in your Stripe Dashboard. Otherwise, under normal circumstances you will want to choose <code>Live</code>.</p>'."\n";
			echo '</div>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Signup Confirmation Email">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-signup-confirmation-email-section">'."\n";
			echo '<h3>Signup Confirmation Email (required, but the default works fine)<br />— specifically for s2Member Pro-Form integrations</h3>'."\n";
			echo '<p>This email is sent to new Customers after they successfully complete a Stripe "Pro-Form" submission on your site. The <strong>primary</strong> purpose of this email, is to provide the Customer with a receipt, and NOT to send them a <code>%%registration_url%%</code>, because s2Member\'s Stripe Pro-Form integration handles that automatically; based on scenario. You may want to customize this email further, by providing details that are specifically geared to your site.</p>'."\n";

			echo '<p><em class="ws-menu-page-bright-hilite">* This email configuration is universally applied to all Payment Gateway integrations. [ <a href="#" onclick="alert(\'This configuration panel may ALSO appear under (s2Member → PayPal Options). Feel free to configure this email here; but please remember that this configuration is applied universally (i.e., SHARED) among all Payment Gateways integrated with s2Member Pro-Forms.\'); return false;">?</a> ]</em></p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-signup-email-recipients">'."\n";
			echo 'Signup Confirmation Recipients:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_signup_email_recipients" id="ws-plugin--s2member-pro-signup-email-recipients" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_signup_email_recipients']).'" /><br />'."\n";
			echo 'This is a semicolon ( ; ) delimited list of Recipients. Here is an example:<br />'."\n";
			echo '<code>"%%full_name%%" &lt;%%payer_email%%&gt;; admin@example.com; "Webmaster" &lt;webmaster@example.com&gt;</code>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-signup-email-subject">'."\n";
			echo 'Signup Confirmation Email Subject:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_signup_email_subject" id="ws-plugin--s2member-pro-signup-email-subject" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_signup_email_subject']).'" /><br />'."\n";
			echo 'Subject Line used in the email sent to a Customer after a successful signup has occurred through a Stripe Pro-Form.'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-signup-email-message">'."\n";
			echo 'Signup Confirmation Email Message:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<textarea name="ws_plugin__s2member_pro_signup_email_message" id="ws-plugin--s2member-pro-signup-email-message" rows="10">'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_signup_email_message']).'</textarea><br />'."\n";
			echo 'Message Body used in the email sent to a Customer after a successful signup has occurred through a Stripe Pro-Form.<br /><br />'."\n";
			echo '<strong>You can also use these special Replacement Codes if you need them:</strong>'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>%%registration_url%%</code> = Not needed with Stripe Pro-Form integration. Pro-Forms handle this automatically.</li>'."\n";
			echo '<li><code>%%subscr_id%%</code> = The Stripe Subscription ID, which remains constant throughout any &amp; all future payments. [ <a href="#" onclick="alert(\'There is one exception. If you are selling Lifetime or Fixed-Term (non-recurring) access, using Buy Now functionality; the %%subscr_id%% is actually set to the Transaction ID for the purchase. Stripe does not provide a specific Subscription ID for Buy Now purchases. Since Lifetime &amp; Fixed-Term Subscriptions are NOT recurring (i.e., there is only ONE payment), using the Transaction ID as the Subscription ID is a graceful way to deal with this minor conflict.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%subscr_cid%%</code> = Applicable only with Stripe integration. This is the Customer\'s ID in Stripe, which remains constant throughout any &amp; all future payments. Each Stripe Customer has this Customer ID; and also a Subscription and/or Transaction ID [ <a href="#" onclick="alert(\'Applicable only when you integrate s2Member with Stripe. In all other cases, the %%subscr_cid%% is simply set to the %%subscr_id%% value; i.e., it is a duplicate of %%subscr_id%% when running anything other than Stripe.\\n\\nEach Stripe Customer has a Customer ID; and also a Subscription and/or Transaction ID. See %%subscr_id%% for further details.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%currency%%</code> = Three-character currency code (uppercase); e.g., <code>USD</code></li>'."\n";
			echo '<li><code>%%currency_symbol%%</code> = Currency code symbol; e.g., <code>$</code></li>'."\n";
			echo '<li><code>%%initial%%</code> = The Initial Fee charged during signup. If you offered a 100% Free Trial, this will be <code>0</code>. [ <a href="#" onclick="alert(\'This will always represent the amount of money the Customer spent, whenever they initially signed up, no matter what. If a Customer signs up, under the terms of a 100% Free Trial Period, this will be 0.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%regular%%</code> = The Regular Amount of the Subscription. If you offer something 100% free, this will be <code>0</code>. [ <a href="#" onclick="alert(\'This is how much the Subscription costs after an Initial Period expires. If you did NOT offer an Initial Period at a different price, %%initial%% and %%regular%% will be equal to the same thing.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%recurring%%</code> = This is the amount that will be charged on a recurring basis, or <code>0</code> if non-recurring. [ <a href="#" onclick="alert(\'If Recurring Payments have not been required, this will be equal to 0. That being said, %%regular%% &amp; %%recurring%% are usually the same value. This variable can be used in two different ways. You can use it to determine what the Regular Recurring Rate is, or to determine whether the Subscription will recur or not. If it is going to recur, %%recurring%% will be > 0.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%first_name%%</code> = The First Name of the Customer who purchased the Membership Subscription.</li>'."\n";
			echo '<li><code>%%last_name%%</code> = The Last Name of the Customer who purchased the Membership Subscription.</li>'."\n";
			echo '<li><code>%%full_name%%</code> = The Full Name (First &amp; Last) of the Customer who purchased the Membership Subscription.</li>'."\n";
			echo '<li><code>%%payer_email%%</code> = The Email Address of the Customer who purchased the Membership Subscription.</li>'."\n";
			echo '<li><code>%%user_ip%%</code> = The Customer\'s IP Address, detected during checkout via <code>$_SERVER["REMOTE_ADDR"]</code>.</li>'."\n";
			echo '<li><code>%%item_number%%</code> = The Item Number (colon separated <code><em>level:custom_capabilities:fixed term</em></code>) that the Subscription is for.</li>'."\n";
			echo '<li><code>%%item_name%%</code> = The Item Name (as provided by the <code>desc=""</code> attribute in your Shortcode, which briefly describes the Item Number).</li>'."\n";
			echo '<li><code>%%initial_term%%</code> = This is the term length of the Initial Period. This will be a numeric value, followed by a space, then a single letter. [ <a href="#" onclick="alert(\'Here are some examples:\\n\\n%%initial_term%% = 1 D (this means 1 Day)\\n%%initial_term%% = 1 W (this means 1 Week)\\n%%initial_term%% = 1 M (this means 1 Month)\\n%%initial_term%% = 1 Y (this means 1 Year)\\n\\nThe Initial Period never recurs, so this only lasts for the term length specified, then it is over.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%initial_cycle%%</code> = This is the <code>%%initial_term%%</code> from above, converted to a cycle representation of: <code><em>X days/weeks/months/years</em></code>.</li>'."\n";
			echo '<li><code>%%regular_term%%</code> = This is the term length of the Regular Period. This will be a numeric value, followed by a space, then a single letter. [ <a href="#" onclick="alert(\'Here are some examples:\\n\\n%%regular_term%% = 1 D (this means 1 Day)\\n%%regular_term%% = 1 W (this means 1 Week)\\n%%regular_term%% = 1 M (this means 1 Month)\\n%%regular_term%% = 1 Y (this means 1 Year)\\n%%regular_term%% = 1 L (this means 1 Lifetime)\\n\\nThe Regular Term is usually recurring. So the Regular Term value represents the period (or duration) of each recurring period. If %%recurring%% = 0, then the Regular Term only applies once, because it is not recurring. So if it is not recurring, the value of %%regular_term%% simply represents how long their Membership privileges are going to last after the %%initial_term%% has expired, if there was an Initial Term. The value of this variable ( %%regular_term%% ) will never be empty, it will always be at least: 1 D, meaning 1 day. No exceptions.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%regular_cycle%%</code> = This is the <code>%%regular_term%%</code> from above, converted to a cycle representation of: <code><em>[every] X days/weeks/months/years—OR daily, weekly, bi-weekly, monthly, bi-monthly, quarterly, yearly, or lifetime</em></code>. This is a very useful Replacment Code. Its value is dynamic; depending on term length, recurring status, and period/term lengths configured.</li>'."\n";
			echo '<li><code>%%recurring/regular_cycle%%</code> = Example (<code>14.95 / Monthly</code>), or ... (<code>0 / non-recurring</code>); depending on the value of <code>%%recurring%%</code>.</li>'."\n";
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
			echo '<li><code>%%cv1%%</code> = If you need to track additional custom variables, you can pipe delimit them into the `custom` attribute; inside your Shortcode, like this: <code>custom="'.esc_html($_SERVER['HTTP_HOST']).'|cv1|cv2|cv3"</code>. You can have an unlimited number of custom variables. Obviously, this is for advanced webmasters; but the functionality has been made available for those who need it.</li>'."\n";
			echo '</ul>'."\n";
			echo '<strong>This example uses cv1 to record a special marketing campaign:</strong><br />'."\n";
			echo '<em>(The campaign (i.e., christmas-promo) could be referenced using <code>%%cv1%%</code>)</em><br />'."\n";
			echo '<code>custom="'.esc_html($_SERVER['HTTP_HOST']).'|christmas-promo"</code>'."\n";

			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ?
				'<div class="ws-menu-page-hr"></div>'."\n".
				'<p style="margin:0;"><strong>PHP Code:</strong> It is also possible to use PHP tags—optional (for developers). If you use PHP tags, please run a test email with <code>&lt;?php print_r(get_defined_vars()); ?&gt;</code>. This will give you a full list of all PHP variables available to you in this email. The <code>$stripe</code> variable is the most important one. It contains all of the <code>$_POST</code> variables received from your Pro-Form integration (related to the transaction itself); which are then translated into a format that s2Member\'s Core Gateway Processor can understand (e.g., <code>$stripe["item_number"]</code>, <code>$stripe["item_name"]</code>, etc). Please note that all Replacement Codes will be parsed first, and then any PHP tags that you\'ve included. Also, please remember that emails are sent in plain text format.</p>'."\n"
				: '';
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Modification Confirmation Email">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-modification-confirmation-email-section">'."\n";
			echo '<h3>Modification Confirmation Email (required, but the default works fine)</h3>'."\n";
			echo '<p>This email is sent to existing Users after they complete an upgrade/downgrade (if and when you make this possible). For instance, if a Free Subscriber upgrades to a paid Membership Level, s2Member considers this a Modification (NOT a Signup; a Signup is associated only with someone completely new). The <strong>primary</strong> purpose of this email is to provide the Customer with a confirmation that their account was updated. You may also customize this further by providing details that are specifically geared to your site.</p>'."\n";

			echo '<p><em class="ws-menu-page-bright-hilite">* The email configuration below is universally applied to all Payment Gateway integrations. [ <a href="#" onclick="alert(\'This configuration panel may ALSO appear under (s2Member → PayPal Options). Feel free to configure this email here; but please remember that this configuration is applied universally (i.e., SHARED) among all Payment Gateways integrated with s2Member.\'); return false;">?</a> ]</em></p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-modification-email-recipients">'."\n";
			echo 'Modification Confirmation Recipients:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_modification_email_recipients" id="ws-plugin--s2member-modification-email-recipients" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['modification_email_recipients']).'" /><br />'."\n";
			echo 'This is a semicolon ( ; ) delimited list of Recipients. Here is an example:<br />'."\n";
			echo '<code>"%%full_name%%" &lt;%%payer_email%%&gt;; admin@example.com; "Webmaster" &lt;webmaster@example.com&gt;</code>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-modification-email-subject">'."\n";
			echo 'Modification Confirmation Email Subject:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_modification_email_subject" id="ws-plugin--s2member-modification-email-subject" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['modification_email_subject']).'" /><br />'."\n";
			echo 'Subject Line used in the email sent to a Customer after a successful modification has occurred through Stripe.'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-modification-email-message">'."\n";
			echo 'Modification Confirmation Email Message:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<textarea name="ws_plugin__s2member_modification_email_message" id="ws-plugin--s2member-modification-email-message" rows="10">'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['modification_email_message']).'</textarea><br />'."\n";
			echo 'Message Body used in the email sent to a Customer after a successful modification has occurred through Stripe.<br /><br />'."\n";
			echo '<strong>You can also use these special Replacement Codes if you need them:</strong>'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>%%subscr_id%%</code> = The Stripe Subscription ID, which remains constant throughout any &amp; all future payments. [ <a href="#" onclick="alert(\'There is one exception. If you are selling Lifetime or Fixed-Term (non-recurring) access, using Buy Now functionality; the %%subscr_id%% is actually set to the Transaction ID for the purchase. Stripe does not provide a specific Subscription ID for Buy Now purchases. Since Lifetime &amp; Fixed-Term Subscriptions are NOT recurring (i.e., there is only ONE payment), using the Transaction ID as the Subscription ID is a graceful way to deal with this minor conflict.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%subscr_cid%%</code> = Applicable only with Stripe integration. This is the Customer\'s ID in Stripe, which remains constant throughout any &amp; all future payments. Each Stripe Customer has this Customer ID; and also a Subscription and/or Transaction ID [ <a href="#" onclick="alert(\'Applicable only when you integrate s2Member with Stripe. In all other cases, the %%subscr_cid%% is simply set to the %%subscr_id%% value; i.e., it is a duplicate of %%subscr_id%% when running anything other than Stripe.\\n\\nEach Stripe Customer has a Customer ID; and also a Subscription and/or Transaction ID. See %%subscr_id%% for further details.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%currency%%</code> = Three-character currency code (uppercase); e.g., <code>USD</code></li>'."\n";
			echo '<li><code>%%currency_symbol%%</code> = Currency code symbol; e.g., <code>$</code></li>'."\n";
			echo '<li><code>%%initial%%</code> = The Initial Fee. If you offered a 100% Free Trial, this will be <code>0</code>. [ <a href="#" onclick="alert(\'This will always represent the amount of money the Customer spent when they completed checkout, no matter what. Even if that amount is 0. If a Customer upgrades/downgrades under the terms of a 100% Free Trial Period, this will be 0.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%regular%%</code> = The Regular Amount of the Subscription. If you offer something 100% free, this will be <code>0</code>. [ <a href="#" onclick="alert(\'This is how much the Subscription costs after an Initial Period expires. If you did NOT offer an Initial Period at a different price, %%initial%% and %%regular%% will be equal to the same thing.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%recurring%%</code> = This is the amount that will be charged on a recurring basis, or <code>0</code> if non-recurring. [ <a href="#" onclick="alert(\'If Recurring Payments have not been required, this will be equal to 0. That being said, %%regular%% &amp; %%recurring%% are usually the same value. This variable can be used in two different ways. You can use it to determine what the Regular Recurring Rate is, or to determine whether the Subscription will recur or not. If it is going to recur, %%recurring%% will be > 0.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%first_name%%</code> = The First Name of the Customer who purchased the Membership Subscription.</li>'."\n";
			echo '<li><code>%%last_name%%</code> = The Last Name of the Customer who purchased the Membership Subscription.</li>'."\n";
			echo '<li><code>%%full_name%%</code> = The Full Name (First &amp; Last) of the Customer who purchased the Membership Subscription.</li>'."\n";
			echo '<li><code>%%payer_email%%</code> = The Email Address of the Customer who purchased the Membership Subscription.</li>'."\n";
			echo '<li><code>%%item_number%%</code> = The Item Number (colon separated <code><em>level:custom_capabilities:fixed term</em></code>) that the Subscription is for.</li>'."\n";
			echo '<li><code>%%item_name%%</code> = The Item Name (as provided by the <code>desc=""</code> attribute in your Shortcode, which briefly describes the Item Number).</li>'."\n";
			echo '<li><code>%%initial_term%%</code> = This is the term length of the Initial Period. This will be a numeric value, followed by a space, then a single letter. [ <a href="#" onclick="alert(\'Here are some examples:\\n\\n%%initial_term%% = 1 D (this means 1 Day)\\n%%initial_term%% = 1 W (this means 1 Week)\\n%%initial_term%% = 1 M (this means 1 Month)\\n%%initial_term%% = 1 Y (this means 1 Year)\\n\\nThe Initial Period never recurs, so this only lasts for the term length specified, then it is over.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%initial_cycle%%</code> = This is the <code>%%initial_term%%</code> from above, converted to a cycle representation of: <code><em>X days/weeks/months/years</em></code>.</li>'."\n";
			echo '<li><code>%%regular_term%%</code> = This is the term length of the Regular Period. This will be a numeric value, followed by a space, then a single letter. [ <a href="#" onclick="alert(\'Here are some examples:\\n\\n%%regular_term%% = 1 D (this means 1 Day)\\n%%regular_term%% = 1 W (this means 1 Week)\\n%%regular_term%% = 1 M (this means 1 Month)\\n%%regular_term%% = 1 Y (this means 1 Year)\\n%%regular_term%% = 1 L (this means 1 Lifetime)\\n\\nThe Regular Term is usually recurring. So the Regular Term value represents the period (or duration) of each recurring period. If %%recurring%% = 0, then the Regular Term only applies once, because it is not recurring. So if it is not recurring, the value of %%regular_term%% simply represents how long their Membership privileges are going to last after the %%initial_term%% has expired, if there was an Initial Term. The value of this variable ( %%regular_term%% ) will never be empty, it will always be at least: 1 D, meaning 1 day. No exceptions.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%regular_cycle%%</code> = This is the <code>%%regular_term%%</code> from above, converted to a cycle representation of: <code><em>[every] X days/weeks/months/years—OR daily, weekly, bi-weekly, monthly, bi-monthly, quarterly, yearly, or lifetime</em></code>. This is a very useful Replacment Code. Its value is dynamic; depending on term length, recurring status, and period/term lengths configured.</li>'."\n";
			echo '<li><code>%%recurring/regular_cycle%%</code> = Example (<code>14.95 / Monthly</code>), or ... (<code>0 / non-recurring</code>); depending on the value of <code>%%recurring%%</code>.</li>'."\n";
			echo '<li><code>%%user_first_name%%</code> = The First Name listed on their User account. This might be different than what is on file with Stripe.</li>'."\n";
			echo '<li><code>%%user_last_name%%</code> = The Last Name listed on their User account. This might be different than what is on file with Stripe.</li>'."\n";
			echo '<li><code>%%user_full_name%%</code> = The Full Name listed on their User account. This might be different than what is on file with Stripe.</li>'."\n";
			echo '<li><code>%%user_email%%</code> = The Email Address associated with their User account. This might be different than what is on file with Stripe.</li>'."\n";
			echo '<li><code>%%user_login%%</code> = The Username associated with their account. The Customer created this during registration.</li>'."\n";
			echo '<li><code>%%user_ip%%</code> = The Customer\'s original IP Address, during checkout/registration via <code>$_SERVER["REMOTE_ADDR"]</code>.</li>'."\n";
			echo '<li><code>%%user_id%%</code> = A unique WordPress User ID that references this account in the WordPress database.</li>'."\n";
			echo '</ul>'."\n";

			echo '<strong>Coupon Replacement Codes:</strong>'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>%%full_coupon_code%%</code> = A full Coupon Code—if one is accepted by your configuration of s2Member. This may indicate an Affiliate Coupon Code, which will include your Affiliate Suffix Chars too (i.e., the full Coupon Code).</li>'."\n";
			echo '<li><code>%%coupon_code%%</code> = A Coupon Code—if one is accepted by your configuration of s2Member. This will NOT include any Affiliate Suffix Chars. This indicates the actual Coupon Code accepted by your configuration of s2Member (excluding any Affiliate ID).</li>'."\n";
			echo '<li><code>%%coupon_affiliate_id%%</code> = This is the end of an Affiliate Coupon Code <em>(i.e., the referring affiliate\'s ID)</em>. This is only applicable if an Affiliate Coupon Code is accepted by your configuration of s2Member.</li>'."\n";
			echo '</ul>'."\n";

			echo '<strong>Custom Registration/Profile Fields are also supported in this email:</strong>'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>%%date_of_birth%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>date_of_birth</code>.</li>'."\n";
			echo '<li><code>%%street_address%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>street_address</code>.</li>'."\n";
			echo '<li><code>%%country%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>country</code>.</li>'."\n";
			echo '<li><em><code>%%etc, etc...%%</code> <strong>see:</strong> s2Member → General Options → Registration/Profile Fields</em>.</li>'."\n";
			echo '</ul>'."\n";

			echo '<strong>Custom Replacement Codes can also be inserted using these instructions:</strong>'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>%%cv0%%</code> = The domain of your site, which is passed through the `custom` attribute in your Shortcode.</li>'."\n";
			echo '<li><code>%%cv1%%</code> = If you need to track additional custom variables, you can pipe delimit them into the `custom` attribute; inside your Shortcode, like this: <code>custom="'.esc_html($_SERVER['HTTP_HOST']).'|cv1|cv2|cv3"</code>. You can have an unlimited number of custom variables. Obviously, this is for advanced webmasters; but the functionality has been made available for those who need it.</li>'."\n";
			echo '</ul>'."\n";
			echo '<strong>This example uses cv1 to record a special marketing campaign:</strong><br />'."\n";
			echo '<em>(The campaign (i.e., christmas-promo) could be referenced using <code>%%cv1%%</code>)</em><br />'."\n";
			echo '<code>custom="'.esc_html($_SERVER['HTTP_HOST']).'|christmas-promo"</code>'."\n";

			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ?
				'<div class="ws-menu-page-hr"></div>'."\n".
				'<p style="margin:0;"><strong>PHP Code:</strong> It is also possible to use PHP tags—optional (for developers). If you use PHP tags, please run a test email with <code>&lt;?php print_r(get_defined_vars()); ?&gt;</code>. This will give you a full list of all PHP variables available to you in this email. The <code>$stripe</code> variable is the most important one. It contains all of the <code>$_POST</code> variables received from your Pro-Form integration (related to the transaction itself); which are then translated into a format that s2Member\'s Core Gateway Processor can understand (e.g., <code>$stripe["item_number"]</code>, <code>$stripe["item_name"]</code>, etc). Please note that all Replacement Codes will be parsed first, and then any PHP tags that you\'ve included. Also, please remember that emails are sent in plain text format.</p>'."\n"
				: '';
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Capability Confirmation Email">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-ccap-confirmation-email-section">'."\n";
			echo '<h3>Capability Confirmation Email (required, but the default works fine)</h3>'."\n";
			echo '<p>This email is sent to existing Users after they complete a Buy Now purchase for one or more Custom Capabilities (if and when you make this possible); see: <strong>Dashboard → s2Member → Stripe Forms → Capability (Buy Now)</strong>. The <strong>primary</strong> purpose of this email is to provide the Customer with a confirmation that their account was updated. You may also customize this further by providing details that are specifically geared to your site.</p>'."\n";

			echo '<p><em class="ws-menu-page-bright-hilite">* The email configuration below is universally applied to all Payment Gateway integrations. [ <a href="#" onclick="alert(\'This configuration panel may ALSO appear under (s2Member → PayPal Options). Feel free to configure this email here; but please remember that this configuration is applied universally (i.e., SHARED) among all Payment Gateways integrated with s2Member.\'); return false;">?</a> ]</em></p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-ccap-email-recipients">'."\n";
			echo 'Capability Confirmation Recipients:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_ccap_email_recipients" id="ws-plugin--s2member-ccap-email-recipients" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['ccap_email_recipients']).'" /><br />'."\n";
			echo 'This is a semicolon ( ; ) delimited list of Recipients. Here is an example:<br />'."\n";
			echo '<code>"%%full_name%%" &lt;%%payer_email%%&gt;; admin@example.com; "Webmaster" &lt;webmaster@example.com&gt;</code>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-ccap-email-subject">'."\n";
			echo 'Capability Confirmation Email Subject:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_ccap_email_subject" id="ws-plugin--s2member-ccap-email-subject" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['ccap_email_subject']).'" /><br />'."\n";
			echo 'Subject Line used in the email sent to a Customer after a purchase is completed through Stripe.'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-ccap-email-message">'."\n";
			echo 'Capability Confirmation Email Message:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<textarea name="ws_plugin__s2member_ccap_email_message" id="ws-plugin--s2member-ccap-email-message" rows="10">'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['ccap_email_message']).'</textarea><br />'."\n";
			echo 'Message Body used in the email sent to a Customer after a purchase is completed through Stripe.<br /><br />'."\n";
			echo '<strong>You can also use these special Replacement Codes if you need them:</strong>'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>%%txn_id%%</code> = The Stripe Transaction ID. Stripe assigns a unique identifier for every purchase.</li>'."\n";
			echo '<li><code>%%txn_cid%%</code> = Applicable only with Stripe integration. This is the Customer\'s ID in Stripe. Each Stripe Customer has this Customer ID; and also a Transaction ID associated with their purchase of a the Custom Capability [ <a href="#" onclick="alert(\'Applicable only when you integrate s2Member with Stripe. In all other cases, the %%txn_cid%% is simply set to the %%txn_id%% value; i.e., it is a duplicate of %%txn_id%% when running anything other than Stripe.\\n\\nEach Stripe Customer has a Customer ID; and also a Transaction ID. See %%txn_id%% for further details.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%currency%%</code> = Three-character currency code (uppercase); e.g., <code>USD</code></li>'."\n";
			echo '<li><code>%%currency_symbol%%</code> = Currency code symbol; e.g., <code>$</code></li>'."\n";
			echo '<li><code>%%amount%%</code> = The full Amount that you charged for Custom Capability access.</li>'."\n";
			echo '<li><code>%%first_name%%</code> = The First Name of the Customer who completed the purchase.</li>'."\n";
			echo '<li><code>%%last_name%%</code> = The Last Name of the Customer who completed the purchase.</li>'."\n";
			echo '<li><code>%%full_name%%</code> = The Full Name (First &amp; Last) of the Customer who completed the purchase.</li>'."\n";
			echo '<li><code>%%payer_email%%</code> = The Email Address of the Customer who completed the purchase.</li>'."\n";
			echo '<li><code>%%item_number%%</code> = The Item Number (colon separated <code><em>*:custom_capabilities</em></code>); where <code>custom_capabilities</code> is a comma-delimited list of the Custom Capabilities they purchased.</li>'."\n";
			echo '<li><code>%%item_name%%</code> = The Item Name (as provided by the <code>desc=""</code> attribute in your Shortcode, which briefly describes the Item Number).</li>'."\n";
			echo '<li><code>%%user_first_name%%</code> = The First Name listed on their User account. This might be different than what is on file with Stripe.</li>'."\n";
			echo '<li><code>%%user_last_name%%</code> = The Last Name listed on their User account. This might be different than what is on file with Stripe.</li>'."\n";
			echo '<li><code>%%user_full_name%%</code> = The Full Name listed on their User account. This might be different than what is on file with Stripe.</li>'."\n";
			echo '<li><code>%%user_email%%</code> = The Email Address associated with their User account. This might be different than what is on file with Stripe.</li>'."\n";
			echo '<li><code>%%user_login%%</code> = The Username associated with their account. The Customer created this during registration.</li>'."\n";
			echo '<li><code>%%user_ip%%</code> = The Customer\'s original IP Address, during checkout/registration via <code>$_SERVER["REMOTE_ADDR"]</code>.</li>'."\n";
			echo '<li><code>%%user_id%%</code> = A unique WordPress User ID that references this account in the WordPress database.</li>'."\n";
			echo '</ul>'."\n";

			echo '<strong>Coupon Replacement Codes:</strong>'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>%%full_coupon_code%%</code> = A full Coupon Code—if one is accepted by your configuration of s2Member. This may indicate an Affiliate Coupon Code, which will include your Affiliate Suffix Chars too (i.e., the full Coupon Code).</li>'."\n";
			echo '<li><code>%%coupon_code%%</code> = A Coupon Code—if one is accepted by your configuration of s2Member. This will NOT include any Affiliate Suffix Chars. This indicates the actual Coupon Code accepted by your configuration of s2Member (excluding any Affiliate ID).</li>'."\n";
			echo '<li><code>%%coupon_affiliate_id%%</code> = This is the end of an Affiliate Coupon Code <em>(i.e., the referring affiliate\'s ID)</em>. This is only applicable if an Affiliate Coupon Code is accepted by your configuration of s2Member.</li>'."\n";
			echo '</ul>'."\n";

			echo '<strong>Custom Registration/Profile Fields are also supported in this email:</strong>'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>%%date_of_birth%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>date_of_birth</code>.</li>'."\n";
			echo '<li><code>%%street_address%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>street_address</code>.</li>'."\n";
			echo '<li><code>%%country%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>country</code>.</li>'."\n";
			echo '<li><em><code>%%etc, etc...%%</code> <strong>see:</strong> s2Member → General Options → Registration/Profile Fields</em>.</li>'."\n";
			echo '</ul>'."\n";

			echo '<strong>Custom Replacement Codes can also be inserted using these instructions:</strong>'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>%%cv0%%</code> = The domain of your site, which is passed through the `custom` attribute in your Shortcode.</li>'."\n";
			echo '<li><code>%%cv1%%</code> = If you need to track additional custom variables, you can pipe delimit them into the `custom` attribute; inside your Shortcode, like this: <code>custom="'.esc_html($_SERVER['HTTP_HOST']).'|cv1|cv2|cv3"</code>. You can have an unlimited number of custom variables. Obviously, this is for advanced webmasters; but the functionality has been made available for those who need it.</li>'."\n";
			echo '</ul>'."\n";
			echo '<strong>This example uses cv1 to record a special marketing campaign:</strong><br />'."\n";
			echo '<em>(The campaign (i.e., christmas-promo) could be referenced using <code>%%cv1%%</code>)</em><br />'."\n";
			echo '<code>custom="'.esc_html($_SERVER['HTTP_HOST']).'|christmas-promo"</code>'."\n";

			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ?
				'<div class="ws-menu-page-hr"></div>'."\n".
				'<p style="margin:0;"><strong>PHP Code:</strong> It is also possible to use PHP tags—optional (for developers). If you use PHP tags, please run a test email with <code>&lt;?php print_r(get_defined_vars()); ?&gt;</code>. This will give you a full list of all PHP variables available to you in this email. The <code>$stripe</code> variable is the most important one. It contains all of the <code>$_POST</code> variables received from your Pro-Form integration (related to the transaction itself); which are then translated into a format that s2Member\'s Core Gateway Processor can understand (e.g., <code>$stripe["item_number"]</code>, <code>$stripe["item_name"]</code>, etc). Please note that all Replacement Codes will be parsed first, and then any PHP tags that you\'ve included. Also, please remember that emails are sent in plain text format.</p>'."\n"
				: '';
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Specific Post/Page Confirmation Email">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-sp-confirmation-email-section">'."\n";
			echo '<h3>Specific Post/Page Confirmation Email (required, but the default works fine)<br />— specifically for s2Member Pro-Form integrations</h3>'."\n";
			echo '<p>This email is sent to new Customers after they successfully complete a Stripe "Pro-Form" submission on your site, for Specific Post/Page Access. (see: <strong>s2Member → Restriction Options → Specific Post/Page Access</strong>). This is NOT used for Membership sales, only for Specific Post/Page Access. The <strong>primary</strong> purpose of this email, is to provide the Customer with a receipt, along with a link to access the Specific Post/Page they\'ve purchased access to. If you\'ve created a Specific Post/Page Package (with multiple Posts/Pages bundled together into one transaction), this ONE link (<code>%%sp_access_url%%</code>) will automatically authenticate them for access to ALL of the Posts/Pages included in their transaction. You may customize this email further, by providing details that are specifically geared to your site.</p>'."\n";

			echo '<p><em class="ws-menu-page-bright-hilite">* This email configuration is universally applied to all Payment Gateway integrations. [ <a href="#" onclick="alert(\'This configuration panel may ALSO appear under (s2Member → PayPal Options). Feel free to configure this email here; but please remember that this configuration is applied universally (i.e., SHARED) among all Payment Gateways integrated with s2Member Pro-Forms.\'); return false;">?</a> ]</em></p>'."\n";

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
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_sp_email_recipients" id="ws-plugin--s2member-pro-sp-email-recipients" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_sp_email_recipients']).'" /><br />'."\n";
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
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_sp_email_subject" id="ws-plugin--s2member-pro-sp-email-subject" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_sp_email_subject']).'" /><br />'."\n";
			echo 'Subject Line used in the email sent to a Customer after a successful purchase has occurred through a Stripe Pro-Form, for Specific Post/Page Access.'."\n";
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
			echo '<textarea name="ws_plugin__s2member_pro_sp_email_message" id="ws-plugin--s2member-pro-sp-email-message" rows="10">'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_sp_email_message']).'</textarea><br />'."\n";
			echo 'Message Body used in the email sent to a Customer after a successful purchase has occurred through a Stripe Pro-Form, for Specific Post/Page Access.<br /><br />'."\n";
			echo '<strong>You can also use these special Replacement Codes if you need them:</strong>'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>%%sp_access_url%%</code> = The full URL (generated by s2Member) where the Customer can gain access.</li>'."\n";
			echo '<li><code>%%sp_access_exp%%</code> = Human readable expiration for <code>%%sp_access_url%%</code>. Ex: <em>(link expires in <code>%%sp_access_exp%%</code>)</em>.</li>'."\n";
			echo '<li><code>%%txn_id%%</code> = The Stripe Transaction ID. Stripe assigns a unique identifier for every purchase.</li>'."\n";
			echo '<li><code>%%txn_cid%%</code> = Applicable only with Stripe integration. This is the Customer\'s ID in Stripe. Each Stripe Customer has this Customer ID; and also a Transaction ID associated with their purchase of a Specific Post/Page [ <a href="#" onclick="alert(\'Applicable only when you integrate s2Member with Stripe. In all other cases, the %%txn_cid%% is simply set to the %%txn_id%% value; i.e., it is a duplicate of %%txn_id%% when running anything other than Stripe.\\n\\nEach Stripe Customer has a Customer ID; and also a Transaction ID. See %%txn_id%% for further details.\'); return false;">?</a> ]</li>'."\n";
			echo '<li><code>%%currency%%</code> = Three-character currency code (uppercase); e.g., <code>USD</code></li>'."\n";
			echo '<li><code>%%currency_symbol%%</code> = Currency code symbol; e.g., <code>$</code></li>'."\n";
			echo '<li><code>%%amount%%</code> = The full Amount that you charged for Specific Post/Page Access.</li>'."\n";
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
			echo '<li><code>%%cv1%%</code> = If you need to track additional custom variables, you can pipe delimit them into the `custom` attribute; inside your Shortcode, like this: <code>custom="'.esc_html($_SERVER['HTTP_HOST']).'|cv1|cv2|cv3"</code>. You can have an unlimited number of custom variables. Obviously, this is for advanced webmasters; but the functionality has been made available for those who need it.</li>'."\n";
			echo '</ul>'."\n";
			echo '<strong>This example uses cv1 to record a special marketing campaign:</strong><br />'."\n";
			echo '<em>(The campaign (i.e., christmas-promo) could be referenced using <code>%%cv1%%</code>)</em><br />'."\n";
			echo '<code>custom="'.esc_html($_SERVER['HTTP_HOST']).'|christmas-promo"</code>'."\n";

			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ?
				'<div class="ws-menu-page-hr"></div>'."\n".
				'<p style="margin:0;"><strong>PHP Code:</strong> It is also possible to use PHP tags—optional (for developers). If you use PHP tags, please run a test email with <code>&lt;?php print_r(get_defined_vars()); ?&gt;</code>. This will give you a full list of all PHP variables available to you in this email. The <code>$stripe</code> variable is the most important one. It contains all of the <code>$_POST</code> variables received from your Pro-Form integration (related to the transaction itself); which are then translated into a format that s2Member\'s Core Gateway Processor can understand (e.g., <code>$stripe["item_number"]</code>, <code>$stripe["item_name"]</code>, etc). Please note that all Replacement Codes will be parsed first, and then any PHP tags that you\'ve included. Also, please remember that emails are sent in plain text format.</p>'."\n"
				: '';
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";
			echo '<div class="ws-menu-page-group" title="Tax Rate Calculations (Pro-Form)">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-tax-rates-section">'."\n";
			echo '<h3>Tax Rate Calculations for Stripe Pro-Forms (optional)<br />— specifically for s2Member Pro-Form integrations</h3>'."\n";
			echo '<p>With Stripe, your software (s2Member Pro) is solely responsible for calculating Tax Rates. In the fields below, you can set a Global Default Tax Rate, and/or a "Custom Tax Configuration File"; which can be applied to specific countries, specific states, provinces, and even to specific zip code ranges. * Tax Rate calculations are fully compatible with international currencies and locations.</p>'."\n";
			echo '<p>When you create a Stripe Pro-Form with s2Member, you\'ll be asked to supply a <em>Charge Amount</em>. Then, during checkout... s2Member calculates Tax. The calculated Tax Rate is added to the <em>Charge Amount</em> in your Stripe Pro-Form Shortcode. The Tax Rate will be displayed to a Customer during checkout, <strong>after</strong> they\'ve supplied a Billing Address. For example, if you create a Stripe Pro-Form that charges a Customer <strong>$24.95</strong>, and the Tax Rate is configured as 7.0%; s2Member will automatically calculate the Tax as $1.75. A Customer will pay the Total Amount (<em>Charge</em> + Tax = <strong>$26.70</strong>).</p>'."\n";
			echo '<p><em><strong>Quick Tip:</strong> If you configure Tax, it\'s good to include a note somewhere in the <code>desc=""</code> attribute of your Shortcode. Something like <code>desc="$x.xx (plus tax)"</code>.</em></p>'."\n";

			echo '<p><em class="ws-menu-page-bright-hilite">* This tax configuration is universally applied to all Payment Gateway integrations. [ <a href="#" onclick="alert(\'This configuration panel may ALSO appear under (s2Member → PayPal Options). Feel free to configure taxes here; but please remember that this configuration is applied universally (i.e., SHARED) among all Payment Gateways integrated with s2Member Pro-Forms.\'); return false;">?</a> ]</em></p>'."\n";

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
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_default_tax" id="ws-plugin--s2member-pro-default-tax" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_default_tax']).'" /><br />'."\n";
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
			echo '<textarea name="ws_plugin__s2member_pro_tax_rates" id="ws-plugin--s2member-pro-tax-rates" rows="10" wrap="off" spellcheck="false">'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_tax_rates']).'</textarea><br />'."\n";
			echo 'Please use one of the following formats (<a href="#" onclick="alert(\'US=7.0%\nCA=12.0%\nHK=0.0%\nFLORIDA/US=7.5%\nIDAHO/US=6.0%\nALBERTA/CA=5.0%\nBRITISH COLUMBIA/CA=12.0%\n32000-34999/US=7.5%\n83200-83999/US=6.0%\n32601/US=6.5%\'); return false;">click for examples</a>)<br /><br />'."\n";
			echo '<code>2-CHARACTER COUNTRY CODE = Flat rate or percentage.</code>—low precedence<br />'."\n";
			echo '<code>STATE OR PROVINCE/2-CHARACTER COUNTRY CODE = Flat rate or percentage.</code>—higher precedence<br />'."\n";
			echo '<code>ZIP CODE-ZIP CODE/2-CHARACTER COUNTRY CODE = Flat rate or percentage.</code>—higher precedence (zip code range)<br />'."\n";
			echo '<code>ZIP CODE/2-CHARACTER COUNTRY CODE = Flat rate or percentage.</code>—highest precedence (specific zip code)'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<p class="warning"><em><strong>Bitcoin:</strong> At this time, due to technical limitations (and conflicting laws), <strong>taxes you configure here are not applied to payments made in Bitcoin</strong>. If you intend to accept Bitcoin via Stripe, and you want to charge tax, please adjust the final purchase price; i.e., change the overall price so that it includes/covers any applicable tax that you plan to charge. See also: <a href="http://s2member.com/r/bitcoin-tax-faq/" target="_blank" rel="external">http://s2member.com/r/bitcoin-tax-faq/</a></em></p>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Automatic EOT Behavior">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-eot-behavior-section">'."\n";
			echo '<h3>Stripe EOT Behavior (required, please choose)</h3>'."\n";
			echo '<p>EOT = End Of Term. By default, s2Member will demote a paid Member to a Free Subscriber whenever their Subscription term has ended (i.e., expired) or is cancelled. s2Member demotes them to a Free Subscriber, so they will no longer have Member Level Access to your site. However, in some cases, you may prefer to have Customer accounts deleted completely, instead of just being demoted. This is where you choose which method works best for your site. If you don\'t want s2Member to take ANY action at all, you can disable s2Member\'s EOT System temporarily, or even completely. There are also a few other configurable options here, so please read carefully. These options are all very important.</p>'."\n";
			echo '<p>Your Stripe Webhook (aka: IPN) integration will assist in notifying s2Member whenever a Subscription expires. For example, if a Customer cancels their own Subscription; or if you cancel/delete a Customer\'s Subscription through Stripe, s2Member will eventually be notified. The account for that Customer will either be demoted to a Free Subscriber, or deleted automatically (based on your configuration). ~ Otherwise, under normal circumstances, s2Member will not process an EOT until the User has completely used up the time they paid for.</em></p>'."\n";

			echo '<p id="ws-plugin--s2member-auto-eot-system-enabled-via-cron"'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['auto_eot_system_enabled'] == 2 && (!function_exists('wp_cron') || !wp_get_schedule('ws_plugin__s2member_auto_eot_system__schedule'))) ? '' : ' style="display:none;"').'>If you\'d like to run s2Member\'s Auto-EOT System through a more traditional Cron Job; instead of through <code>WP-Cron</code>, you will need to configure a Cron Job through your server control panel; provided by your hosting company. Set the Cron Job to run <code>once about every 10 minutes to an hour</code>. You\'ll want to configure an HTTP Cron Job that loads this URL:<br /><code>'.esc_html(home_url('/?s2member_auto_eot_system_via_cron=1')).'</code></p>'."\n";

			echo '<p><em class="ws-menu-page-bright-hilite">* These options are universally applied to all Payment Gateway integrations. [ <a href="#" onclick="alert(\'These settings may ALSO appear under (s2Member → PayPal Options). Feel free to configure them here; but please remember that these configuration options are applied universally (i.e., they\\\'re SHARED) among all Payment Gateways integrated with s2Member.\'); return false;">?</a> ]</em></p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-auto-eot-system-enabled">'."\n";
			echo 'Enable s2Member\'s Auto-EOT System?'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<select name="ws_plugin__s2member_auto_eot_system_enabled" id="ws-plugin--s2member-auto-eot-system-enabled">'."\n";
			// Very advanced conditionals here. If the Auto-EOT System is NOT running, or NOT fully configured, this will indicate that no option is set - as sort of a built-in acknowledgment/warning in the UI panel.
			echo (($GLOBALS['WS_PLUGIN__']['s2member']['o']['auto_eot_system_enabled'] == 1 && (!function_exists('wp_cron') || !wp_get_schedule("ws_plugin__s2member_auto_eot_system__schedule"))) || ($GLOBALS['WS_PLUGIN__']['s2member']['o']['auto_eot_system_enabled'] == 2 && (function_exists('wp_cron') && wp_get_schedule('ws_plugin__s2member_auto_eot_system__schedule'))) || (!$GLOBALS['WS_PLUGIN__']['s2member']['o']['auto_eot_system_enabled'] && (function_exists("wp_cron") && wp_get_schedule('ws_plugin__s2member_auto_eot_system__schedule')))) ? '<option value=""></option>'."\n" : '';
			echo '<option value="1"'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['auto_eot_system_enabled'] == 1 && function_exists('wp_cron') && wp_get_schedule('ws_plugin__s2member_auto_eot_system__schedule')) ? ' selected="selected"' : '').'>Yes (enable the Auto-EOT System through WP-Cron)</option>'."\n";
			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? '<option value="2"'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['auto_eot_system_enabled'] == 2 && (!function_exists('wp_cron') || !wp_get_schedule('ws_plugin__s2member_auto_eot_system__schedule'))) ? ' selected="selected"' : '').'>Yes (but, I\'ll run it with my own Cron Job)</option>'."\n" : '';
			echo '<option value="0"'.((!$GLOBALS['WS_PLUGIN__']['s2member']['o']['auto_eot_system_enabled'] && (!function_exists('wp_cron') || !wp_get_schedule('ws_plugin__s2member_auto_eot_system__schedule'))) ? ' selected="selected"' : '').'>No (disable the Auto-EOT System)</option>'."\n";
			echo '</select><br />'."\n";
			echo 'Recommended setting: (<code>Yes / enable via WP-Cron</code>)'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-membership-eot-behavior">'."\n";
			echo 'Membership EOT Behavior (Demote or Delete)?'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<select name="ws_plugin__s2member_membership_eot_behavior" id="ws-plugin--s2member-membership-eot-behavior">'."\n";
			echo '<option value="demote"'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['membership_eot_behavior'] === "demote") ? ' selected="selected"' : '').'>Demote (convert them to a Free Subscriber)</option>'."\n";
			echo '<option value="delete"'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['membership_eot_behavior'] === "delete") ? ' selected="selected"' : '').'>Delete (erase their account completely)</option>'."\n";
			echo '</select>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-eots-remove-ccaps">'."\n";
			echo 'Membership EOTs also Remove all Custom Capabilities?'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<select name="ws_plugin__s2member_eots_remove_ccaps" id="ws-plugin--s2member-eots-remove-ccaps">'."\n";
			echo '<option value="1"'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['eots_remove_ccaps']) ? ' selected="selected"' : '').'>Yes (an EOT also results in the loss of any Custom Capabilities a User/Member may have)</option>'."\n";
			echo '<option value="0"'.((!$GLOBALS['WS_PLUGIN__']['s2member']['o']['eots_remove_ccaps']) ? ' selected="selected"' : '').'>No (an EOT has no impact on any Custom Capabilities a User/Member may have)</option>'."\n";
			echo '</select>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-eot-grace-time">'."\n";
			echo 'EOT Grace Time (in seconds):'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_eot_grace_time" id="ws-plugin--s2member-eot-grace-time" value="'.format_to_edit($GLOBALS['WS_PLUGIN__']['s2member']['o']['eot_grace_time']).'" /><br />'."\n";
			echo '<em>This is represented in seconds. For example, a value of: <code>86400</code> = 1 day. Your EOT Grace Time; is the amount of time you will offer as a grace period (if any). Most site owners will give customers an additional 24 hours of access; just to help avoid any negativity that may result from a customer losing access sooner than they might expect. You can disable EOT Grace Time by setting this to: <code>0</code>.</em>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-triggers-immediate-eot">'."\n";
			echo 'Refunds/Partial Refunds/Reversals (trigger Immediate EOT)?'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<select name="ws_plugin__s2member_triggers_immediate_eot" id="ws-plugin--s2member-triggers-immediate-eot">'."\n";
			echo '<option value="none"'.(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["triggers_immediate_eot"] === "none") ? ' selected="selected"' : '').'>None (I\'ll review these events manually)</option>'."\n";
			echo '<option value="refunds"'.(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["triggers_immediate_eot"] === "refunds") ? ' selected="selected"' : '').'>Full Refunds (full refunds only; ALWAYS trigger an Immediate EOT action)</option>'."\n";
			echo '<option value="reversals"'.(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["triggers_immediate_eot"] === "reversals") ? ' selected="selected"' : '').'>Reversals (chargebacks only; ALWAYS trigger an Immediate EOT action)</option>'."\n";
			echo '<option value="refunds,reversals"'.(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["triggers_immediate_eot"] === "refunds,reversals") ? ' selected="selected"' : '').'>Full Refunds, Reversals (these ALWAYS trigger an Immediate EOT action)</option>'."\n";
			echo '<option value="refunds,partial_refunds,reversals"'.(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["triggers_immediate_eot"] === "refunds,partial_refunds,reversals") ? ' selected="selected"' : '').'>Full Refunds, Partial Refunds, Reversals (these ALWAYS trigger an Immediate EOT action)</option>'."\n";
			echo '</select><br />'."\n";
			echo '<em><strong>Note:</strong> s2Member is not equipped to detect partial refunds against multi-payment Subscriptions reliably. Therefore, all refunds processed against Subscriptions (of any kind) are considered <strong>Partial</strong> Refunds. Full refunds (in the eyes of s2Member) occur only against Buy Now transactions where it is easy for s2Member to see that the refund amount is &gt;= the original Buy Now purchase price (i.e., a Full Refund). <strong>Also Note:</strong> This setting (no matter what you choose) will NOT impact s2Member\'s internal API Notifications for Refund/Reversal events. <a href="#" onclick="alert(\'A Full or Partial Refund; and/or a Reversal Notification will ALWAYS be processed internally by s2Member, even if no action is taken by s2Member in accordance with your configuration here.\\n\\nIn this way, you\\\'ll have the full ability to listen for these events on your own (via API Notifications); if you prefer (optional). For more information, check your Dashboard under: `s2Member → API Notifications → Refunds/Reversals`.\'); return false;">Click here for details</a>.</em>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-eot-time-ext-behavior">'."\n";
			echo 'Fixed-Term Extensions (Auto-Extend)?'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<select name="ws_plugin__s2member_eot_time_ext_behavior" id="ws-plugin--s2member-eot-time-ext-behavior">'."\n";
			echo '<option value="extend"'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['eot_time_ext_behavior'] === "extend") ? ' selected="selected"' : '').'>Yes (default, automatically extend any existing EOT Time)</option>'."\n";
			echo '<option value="reset"'.(($GLOBALS['WS_PLUGIN__']['s2member']['o']['eot_time_ext_behavior'] === "reset") ? ' selected="selected"' : '').'>No (do NOT extend; s2Member should reset EOT Time completely)</option>'."\n";
			echo '</select><br />'."\n";
			echo '<em>This setting will only affect Buy Now transactions for fixed-term lengths. By default, s2Member will automatically extend any existing EOT Time that a Customer may have. For example, if I buy one year of access, and then I buy another year of access (before my first year is totally used up); I end up with everything I paid you for (now over 1 year of access) if this is set to <code>Yes</code>. If this was set to <code>No</code>, the EOT Time would be reset when I make the second purchase; leaving me with only 1 year of access, starting the date of my second purchase.</em>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<p class="submit"><input type="submit" value="Save All Changes" /></p>'."\n";

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

new c_ws_plugin__s2member_pro_menu_page_stripe_ops ();
