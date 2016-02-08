<?php
/**
 * Menu page for s2Member Pro (PayPal Forms page).
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

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_paypal_forms"))
{
	/**
	 * Menu page for s2Member Pro (PayPal Forms page).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110531
	 */
	class c_ws_plugin__s2member_pro_menu_page_paypal_forms
	{
		public function __construct()
		{
			echo '<div class="wrap ws-menu-page">'."\n";

			echo '<div class="ws-menu-page-toolbox">'."\n";
			c_ws_plugin__s2member_menu_pages_tb::display();
			echo '</div>'."\n";

			echo '<h2>PayPal Pro-Forms</h2>'."\n";

			echo '<table class="ws-menu-page-table">'."\n";
			echo '<tbody class="ws-menu-page-table-tbody">'."\n";
			echo '<tr class="ws-menu-page-table-tr">'."\n";
			echo '<td class="ws-menu-page-table-l">'."\n";

			echo '<div class="ws-menu-page-group" title="PayPal Pro Requirements">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-requirements-section">'."\n";

			echo '<h3>Is "PayPal Payments Pro" required for me to use Pro-Forms?</h3>'."\n";
			echo '<p>Yes, PayPal Payments Pro is required for Pro-Forms. However, there are some exceptions to that rule.</p>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<h4>PayPal Payments Pro is Absolutely Required:</h4>'."\n";
			echo '<ul>'."\n";
			echo '	<li>For you to accept on-site credit card payments via Pro-Forms; i.e., not just PayPal Express Checkout.</li>'."\n";
			echo '	<li>For Cancelation, Billing Update, and Billing Modification Pro-Forms to work as expected in all cases.</li>'."\n";
			echo '	<li>Generally speaking, for you to take full advantage of everything that Pro-Forms can do; and to have them all work as originally intended.</li>'."\n";
			echo '</ul>'."\n";

			echo '<p><em><strong>See also:</strong> <a href="https://s2member.com/kb-article/supported-paypal-account-types/" target="_blank" rel="external">Supported PayPal Account Types</a></em></p>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<h4>PayPal Payments Pro is NOT Required (Exceptions):</h4>'."\n";
			echo '<ul>'."\n";
			echo '	<li>For you to introduce Free Registration Pro-Forms. In fact, you can use Free Registration Pro-Forms w/o even configuring PayPal API credentials.</li>'."\n";
			echo '	<li>For you to accept PayPal Express Checkout only (not ideal). See: <a href="https://s2member.com/kb-article/do-s2member-pro-forms-work-with-paypal-standard-i-e-without-paypal-pro/" target="_blank" rel="external">this KB article</a> for details and important limitations.</li>'."\n";
			echo '	<li style="font-style:italic;">If you integrate with Stripe™ instead (free, most popular). See: <a href="https://s2member.com/kb-article/does-s2member-integrate-w-stripe-bitcoin/" target="_blank" rel="external">Does s2Member Pro integrate w/ Stripe? Bitcoin?</a></li>'."\n";
			echo '</ul>'."\n";

			echo '</div>'."\n";
			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Free Registration Forms">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-registration-forms-section">'."\n";
			echo '<h3>One Form Does It All For Free Registrations (copy/paste)</h3>'."\n";
			echo '<p>Whenever a visitor registers without paying, they\'ll automatically become a Free Subscriber, at Level #0.</p>'."\n";
			echo '<p><em><strong>Note:</strong> the use of this particular Form will override your Open Registration configuration. In other words, making this Form available is the same as turning Open Registration <code>(on)</code>. One of the benefits to this functionality, is that it makes it possible for you to integrate this Free Registration Form in creative ways (i.e., making it available ONLY under certain circumstances); while still leaving Open Registration <code>(off)</code> throughout the rest of the site.</em></p>'."\n";
			echo '<p><em><strong>Tip (optional):</strong> It is also possible to change the <code>level="0"</code> Attribute to something other than the default Level #0 (Free Subscriber). For example, if you need to, you can change it to <code>level="1"</code>, attach Custom Capabilities with the <code>ccaps=""</code> Attribute, and even limit this access to a certain timeframe with <code>tp="30" tt="D"</code> (i.e., 30 Days). So this Form is very flexible. It can be used to allow free access to just about any aspect of your service. For more information on Attributes, please see the section below: Shortcode Attributes (Explained).</em></p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<form onsubmit="return false;" autocomplete="off">'."\n";
			echo '<strong>WordPress Shortcode:</strong> (recommended for both the WordPress Visual &amp; HTML Editors)<br />'."\n";
			$ws_plugin__s2member_pro_temp_s = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/shortcodes/paypal-registration-form-shortcode.php")));
			$ws_plugin__s2member_pro_temp_s = preg_replace("/%%level%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr("0")), $ws_plugin__s2member_pro_temp_s);
			$ws_plugin__s2member_pro_temp_s = preg_replace("/%%level_label%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["level0_label"])), $ws_plugin__s2member_pro_temp_s);
			$ws_plugin__s2member_pro_temp_s = preg_replace("/%%custom%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($_SERVER["HTTP_HOST"])), $ws_plugin__s2member_pro_temp_s);
			echo '<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-registration-shortcode" value="'.format_to_edit($ws_plugin__s2member_pro_temp_s).'" onclick="this.select ();" class="monospace" />'."\n";
			echo '</form>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			for($n = 1; $n <= $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"]; $n++)
			{
				echo '<div class="ws-menu-page-group" title="Forms For Level #'.$n.' Access">'."\n";

				echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-level'.$n.'-forms-section">'."\n";
				echo '<h3>Pro-Form Generator For Level #'.$n.' Access</h3>'."\n";
				echo '<p>Very simple. All you do is customize the form fields provided, for each Membership Level that you plan to offer. Then press (Generate Form Code). These special PayPal Forms are customized to work with s2Member seamlessly. Member accounts will be activated instantly, in an automated fashion. When you, or a Member, cancels their Membership, or fails to make payments on time, s2Member will automatically terminate their Membership privileges. s2Member makes extensive use of the PayPal IPN service. s2Member receives updates from PayPal behind-the-scene.</p>'."\n";
				echo '<p><em><strong>Please note:</strong> forms are NOT saved here. This is only a Form Generator. Once you\'ve generated your Form, copy/paste it into any Post/Page you like. You\'ll want to provide your visitors with a link to the Post/Page where this Form is located. We suggest placing a link to this Form on your Membership Options Page. That way your visitors can get registered &amp; checkout!</em></p>'."\n";

				echo '<table class="form-table">'."\n";
				echo '<tbody>'."\n";
				echo '<tr>'."\n";

				echo '<td>'."\n";
				echo '<form onsubmit="return false;" autocomplete="off">'."\n";
				echo '<p id="ws-plugin--s2member-pro-level'.$n.'-trial-line">I\'ll offer the first <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-level'.$n.'-trial-period" value="0" size="6" /> <select id="ws-plugin--s2member-pro-level'.$n.'-trial-term">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/paypal-membership-trial-terms.php"))).'</select> @ $<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-level'.$n.'-trial-amount" value="0.00" size="4" /></p>'."\n";
				echo '<p><span id="ws-plugin--s2member-pro-level'.$n.'-trial-then">Then, </span>I want to charge: $<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-level'.$n.'-amount" value="0.01" size="4" /> / <select id="ws-plugin--s2member-pro-level'.$n.'-term">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/".(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_payflow_api_username"]) ? "payflow" : "paypal")."-membership-regular-terms.php"))).'</select></p>'."\n";
				echo '<p>Description: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-level'.$n.'-desc" value="'.format_to_edit($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["level".$n."_label"]).' / description and pricing details here." size="68" /></p>'."\n";
				echo '<p>Checkout Page Style <a href="#" onclick="alert(\'Optional. This can be configured inside your PayPal account. PayPal allows you to create Custom Page Styles, and assign a unique name to them. You can add your own header image and color selection to the checkout form. Once you\\\'ve created a Custom Page Style at PayPal, you can enter that Page Style here.\\n\\nWith PayPal Pro integration, this is only applied to PayPal Express Checkout pages; when/if a Customer chooses PayPal as the Payment Method.\'); return false;" tabindex="-1">[?]</a>: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-level'.$n.'-page-style" value="paypal" size="18" /> <select id="ws-plugin--s2member-pro-level'.$n.'-currency">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/paypal-currencies.php"))).'</select> <input type="button" value="Generate Form Code" onclick="ws_plugin__s2member_pro_paypalFormGenerate(\'level'.$n.'\');" /></p>'."\n";
				echo '<p'.((is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) ? ' style="display:none;"' : '').'>Custom Capabilities (comma-delimited) <a href="#" onclick="alert(\'Optional. This is VERY advanced.\\nSee: s2Member → API Scripting → Custom Capabilities.\\n\\n*ADVANCED TIP: You can specifiy a list of Custom Capabilities that will be (Added) with this purchase. Or, you could tell s2Member to (Remove All) Custom Capabilities that may or may not already exist for a particular Member, and (Add) only the new ones that you specify. To do this, just start your list of Custom Capabilities with `-all`.\\n\\nSo instead of just (Adding) Custom Capabilities:\\nmusic,videos,archives,gifts\\n\\nYou could (Remove All) that may already exist, and then (Add) new ones:\\n-all,calendar,forums,tools\\n\\nOr to just (Remove All) and (Add) nothing:\\n-all\'); return false;" tabindex="-1">[?]</a> <input type="text" maxlength="125" autocomplete="off" id="ws-plugin--s2member-pro-level'.$n.'-ccaps" size="40" /></p>'."\n";
				echo '</form>'."\n";
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<td>'."\n";
				echo '<form onsubmit="return false;" autocomplete="off">'."\n";
				echo '<strong>WordPress Shortcode:</strong> (recommended for both the WordPress Visual &amp; HTML Editors)<br />'."\n";
				$ws_plugin__s2member_pro_temp_s = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/shortcodes/paypal-checkout-form-shortcode.php")));
				$ws_plugin__s2member_pro_temp_s = preg_replace("/%%level%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($n)), $ws_plugin__s2member_pro_temp_s);
				$ws_plugin__s2member_pro_temp_s = preg_replace("/%%level_label%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["level".$n."_label"])), $ws_plugin__s2member_pro_temp_s);
				$ws_plugin__s2member_pro_temp_s = preg_replace("/%%custom%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($_SERVER["HTTP_HOST"])), $ws_plugin__s2member_pro_temp_s);
				echo '<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-level'.$n.'-shortcode" value="'.format_to_edit($ws_plugin__s2member_pro_temp_s).'" onclick="this.select ();" class="monospace" />'."\n";
				echo '</form>'."\n";
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '</tbody>'."\n";
				echo '</table>'."\n";
				echo '</div>'."\n";

				echo '</div>'."\n";
			}

			echo '<div class="ws-menu-page-group" title="Billing Modification Forms">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-modification-forms-section">'."\n";
			echo '<h3>Pro-Form Generator For Billing Modifications</h3>'."\n";
			echo '<p>If you\'d like to give your Members (and/or your Free Subscribers) the ability to change (modify) their billing plan; you can generate a new PayPal Modification Form here. Configure the updated Level, pricing, terms, etc. Then, make that new Modification Form available to Members who are logged into their existing account with you. For example, you might want to insert a "Level #2" Upgrade link into your Login Welcome Page, which would up-sell existing Level #1 Members to a more expensive plan that you offer.</p>'."\n";
			echo '<p><em><strong>Modification Process:</strong> Very simple. A Member clicks a link to a special Post/Page, which contains a Modification Form you\'ve generated. The Member fills in their billing information. After a successful form submission, s2Member will update the status of their account to the Level, pricing, and terms that you configure below. If the Member already has an existing paid Subscription with you, that paid Subscription will be cancelled automatically behind-the-scenes, and a new paid Subscription will be created to replace the old one. Again, the new paid Subscription is based on the Level, pricing, and terms that you specify below. If you need to give Customers some sort of grace period when/if they upgrade to a more expensive plan, please feel free to handle this through the application of free days, or with special pricing configured below.</em></p>'."\n";
			echo '<p><em><strong>Integrating Conditionals:</strong> Since each Modification Form is configured for a specific Level, you may want to create multiple Modification Forms, one for each combination you intend to make available. s2Member\'s API Conditionals can help you display the proper Form to each Customer, based on the status of their existing account. For further details, see: <strong>s2Member → API Scripting</strong>.</em></p>'."\n";
			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? '<p><em><strong>Independent Custom Capabilities:</strong> If you just want to sell an existing Member new Custom Capabilities, without affecting their paid Subscription in any way, please see the next Form Generator: <code>Capability (Buy Now) Forms</code>. Independent Capability Forms facilitate Buy Now functionality, specifically for Custom Capabilities, without affecting the Customer\'s primary Subscription and Membership Level Access.</em></p>'."\n" : '';

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<form onsubmit="return false;" autocomplete="off">'."\n";

			echo '<p>Modification: <select id="ws-plugin--s2member-pro-modification-level">'."\n";

			for($n = 1; $n <= $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"]; $n++)
			{
				echo '<optgroup label="Level #'.$n.'">'."\n";
				echo '<option value="upgrade:'.$n.'">&uarr; Upgrade To Level #'.$n.'</option>'."\n";
				echo ($n < $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"]) ? '<option value="downgrade:'.$n.'">&darr; Downgrade To Level #'.$n.'</option>'."\n" : '';
				echo '</optgroup>'."\n";

				echo ($n < $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"]) ? '<option disabled="disabled"></option>'."\n" : '';
			}

			echo '</select></p>'."\n";

			echo '<p id="ws-plugin--s2member-pro-modification-trial-line">I\'ll offer the first <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-modification-trial-period" value="0" size="6" /> <select id="ws-plugin--s2member-pro-modification-trial-term">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/paypal-membership-trial-terms.php"))).'</select> @ $<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-modification-trial-amount" value="0.00" size="4" /></p>'."\n";
			echo '<p><span id="ws-plugin--s2member-pro-modification-trial-then">Then, </span>I want to charge: $<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-modification-amount" value="0.01" size="4" /> / <select id="ws-plugin--s2member-pro-modification-term">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/".(($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["paypal_payflow_api_username"]) ? "payflow" : "paypal")."-membership-regular-terms.php"))).'</select></p>'."\n";
			echo '<p>Description: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-modification-desc" value="Description and pricing details here." size="68" /></p>'."\n";
			echo '<p>Checkout Page Style <a href="#" onclick="alert(\'Optional. This can be configured inside your PayPal account. PayPal allows you to create Custom Page Styles, and assign a unique name to them. You can add your own header image and color selection to the checkout form. Once you\\\'ve created a Custom Page Style at PayPal, you can enter that Page Style here.\\n\\nWith PayPal Pro integration, this is only applied to PayPal Express Checkout pages; when/if a Customer chooses PayPal as the Payment Method.\'); return false;" tabindex="-1">[?]</a>: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-modification-page-style" value="paypal" size="18" /> <select id="ws-plugin--s2member-pro-modification-currency">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/paypal-currencies.php"))).'</select> <input type="button" value="Generate Form Code" onclick="ws_plugin__s2member_pro_paypalFormGenerate(\'modification\');" /></p>'."\n";
			echo '<p'.((is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) ? ' style="display:none;"' : '').'>Custom Capabilities (comma-delimited) <a href="#" onclick="alert(\'Optional. This is VERY advanced.\\nSee: s2Member → API Scripting → Custom Capabilities.\\n\\n*ADVANCED TIP: You can specifiy a list of Custom Capabilities that will be (Added) with this purchase. Or, you could tell s2Member to (Remove All) Custom Capabilities that may or may not already exist for a particular Member, and (Add) only the new ones that you specify. To do this, just start your list of Custom Capabilities with `-all`.\\n\\nSo instead of just (Adding) Custom Capabilities:\\nmusic,videos,archives,gifts\\n\\nYou could (Remove All) that may already exist, and then (Add) new ones:\\n-all,calendar,forums,tools\\n\\nOr to just (Remove All) and (Add) nothing:\\n-all\'); return false;" tabindex="-1">[?]</a> <input type="text" maxlength="125" autocomplete="off" id="ws-plugin--s2member-pro-modification-ccaps" size="40" /></p>'."\n";
			echo '</form>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<form onsubmit="return false;" autocomplete="off">'."\n";
			echo '<strong>WordPress Shortcode:</strong> (recommended for both the WordPress Visual &amp; HTML Editors)<br />'."\n";
			$ws_plugin__s2member_pro_temp_s = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/shortcodes/paypal-checkout-form-shortcode.php")));
			$ws_plugin__s2member_pro_temp_s = preg_replace("/%%level%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr("1")), $ws_plugin__s2member_pro_temp_s);
			$ws_plugin__s2member_pro_temp_s = preg_replace("/%%level_label%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["level1_label"])), $ws_plugin__s2member_pro_temp_s);
			$ws_plugin__s2member_pro_temp_s = preg_replace("/%%custom%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($_SERVER["HTTP_HOST"])), $ws_plugin__s2member_pro_temp_s);
			$ws_plugin__s2member_pro_temp_s = preg_replace("/\/]$/", 'modify="1" /]', $ws_plugin__s2member_pro_temp_s); // Adds modify="1" to the end of the Shortcode.
			echo '<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-modification-shortcode" value="'.format_to_edit($ws_plugin__s2member_pro_temp_s).'" onclick="this.select ();" class="monospace" />'."\n";
			echo '</form>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			if(!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site())
			{
				echo '<div class="ws-menu-page-group" title="Capability (Buy Now) Forms">'."\n";

				echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-ccap-forms-section">'."\n";
				echo '<h3>Pro-Form Generator For Independent Custom Capabilities</h3>'."\n";
				echo '<p>This is VERY advanced. For further details, please check your Dashboard: <strong>s2Member → API Scripting → Custom Capabiities</strong>.</p>'."\n";
				echo '<p>With s2Member, you can sell one or more Custom Capabilities using Buy Now functionality, to "existing" Users/Members, regardless of which Membership Level they have on your site <em>(i.e., you could even sell Independent Custom Capabilities to Users at Membership Level #0, normally referred to as Free Subscribers, if you like)</em>. So this is quite flexible. Independent Custom Capabilities do NOT rely on any specific Membership Level. That\'s why s2Member refers to these as `Independent` Custom Capabilities, because you can sell Capabilities this way, through Buy Now functionality, and the Customer\'s Membership Level Access, along with any existing paid Subscription they may already have with you, will remain completely unaffected. That being said, if you intend to charge a recurring fee for Custom Capabilities, please use a <code>Billing Modification Form</code> instead; because Independent Custom Capabilities can only be sold through Buy Now functionality.</p>'."\n";
				echo '<p>Independent Custom Capabilities are added to a Customer\'s account immediately after checkout, and the Customer will have the Custom Capabilities for as long as their Membership lasts, based on their primary Subscription with your site, and/or forever, if they have a Lifetime account with you. In other words, Independent Custom Capabilities will exist on the Customer\'s account forever, or until an EOT <em>(End Of Term)</em> occurs on their primary Subscription with you; in which case s2Member would demote or delete the Customer\'s account <em>(based on your EOT configuration)</em>, and all Custom Capabilities are removed as well.</p>'."\n";
				echo '<p>Very simple. All you do is customize the form fields provided, for each set of Custom Capabilities that you plan to sell. Then press (Generate Form Code). These special PayPal Forms are customized to work with s2Member seamlessly. The Customer will be granted additional access to one or more Custom Capabilities that you specify; while the Customer\'s Membership Level Access and any existing paid Subscription they may already have with you, will remain completely unaffected.</p>'."\n";

				echo '<table class="form-table">'."\n";
				echo '<tbody>'."\n";
				echo '<tr>'."\n";

				echo '<td>'."\n";
				echo '<form onsubmit="return false;" autocomplete="off">'."\n";

				echo '<p>I want to charge: $<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-ccap-amount" value="0.01" size="4" /> / <select id="ws-plugin--s2member-pro-ccap-term">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/paypal-membership-ccap-terms.php"))).'</select></p>'."\n";
				echo '<p>Description: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-ccap-desc" value="Description and pricing details here." size="68" /></p>'."\n";
				echo '<p>Checkout Page Style <a href="#" onclick="alert(\'Optional. This can be configured inside your PayPal account. PayPal allows you to create Custom Page Styles, and assign a unique name to them. You can add your own header image and color selection to the checkout form. Once you\\\'ve created a Custom Page Style at PayPal, you can enter that Page Style here.\\n\\nWith PayPal Pro integration, this is only applied to PayPal Express Checkout pages; when/if a Customer chooses PayPal as the Payment Method.\'); return false;" tabindex="-1">[?]</a>: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-ccap-page-style" value="paypal" size="18" /> <select id="ws-plugin--s2member-pro-ccap-currency">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/paypal-currencies.php"))).'</select> <input type="button" value="Generate Form Code" onclick="ws_plugin__s2member_pro_paypalCcapFormGenerate();" /></p>'."\n";
				echo '<p>Custom Capabilities (comma-delimited) <a href="#" onclick="alert(\'Optional. This is VERY advanced.\\nSee: s2Member → API Scripting → Custom Capabilities.\\n\\n*ADVANCED TIP: You can specifiy a list of Custom Capabilities that will be (Added) with this purchase. Or, you could tell s2Member to (Remove All) Custom Capabilities that may or may not already exist for a particular Member, and (Add) only the new ones that you specify. To do this, just start your list of Custom Capabilities with `-all`.\\n\\nSo instead of just (Adding) Custom Capabilities:\\nmusic,videos,archives,gifts\\n\\nYou could (Remove All) that may already exist, and then (Add) new ones:\\n-all,calendar,forums,tools\'); return false;" tabindex="-1">[?]</a> <input type="text" maxlength="125" autocomplete="off" id="ws-plugin--s2member-pro-ccap-ccaps" size="40" /></p>'."\n";
				echo '</form>'."\n";
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<td>'."\n";
				echo '<form onsubmit="return false;" autocomplete="off">'."\n";
				echo '<strong>WordPress Shortcode:</strong> (recommended for both the WordPress Visual &amp; HTML Editors)<br />'."\n";
				$ws_plugin__s2member_pro_temp_s = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/shortcodes/paypal-ccaps-checkout-form-shortcode.php")));
				$ws_plugin__s2member_pro_temp_s = preg_replace("/%%custom%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($_SERVER["HTTP_HOST"])), $ws_plugin__s2member_pro_temp_s);
				echo '<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-ccap-shortcode" value="'.format_to_edit($ws_plugin__s2member_pro_temp_s).'" onclick="this.select ();" class="monospace" />'."\n";
				echo '</form>'."\n";
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '</tbody>'."\n";
				echo '</table>'."\n";
				echo '</div>'."\n";

				echo '</div>'."\n";
			}

			echo '<div class="ws-menu-page-group" title="Billing Update Forms">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-update-forms-section">'."\n";
			echo '<h3>One Form Does It All For Billing Updates (copy/paste)</h3>'."\n";
			echo '<p>An Update Form can be provided to existing Members, as a way for them to update their billing information; without modifying their existing paid Subscription in any way. For instance, a Customer may need to update their billing information, because their credit card is expiring, or because they moved their bank account.</p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<form onsubmit="return false;" autocomplete="off">'."\n";
			echo '<strong>WordPress Shortcode:</strong> (recommended for both the WordPress Visual &amp; HTML Editors)<br />'."\n";
			$ws_plugin__s2member_pro_temp_s = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/shortcodes/paypal-update-form-shortcode.php")));
			echo '<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-update-shortcode" value="'.format_to_edit($ws_plugin__s2member_pro_temp_s).'" onclick="this.select ();" class="monospace" />'."\n";
			echo '</form>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Billing Cancellation Forms">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-cancellation-forms-section">'."\n";
			echo '<h3>One Form Does It All For Cancellations (copy/paste)</h3>'."\n";
			echo '<p>According to PayPal\'s policy on recurring billing, you MUST provide each and every Customer with an easy to way to cancel future charges. Generating a Cancellation Form here, and making that Form available to all Customers is our recommendation. For further details and legalities, please visit the <a href="http://s2member.com/r/paypal-developer-network/" target="_blank" rel="external">PayPal Developer Network</a>.</p>'."\n";
			echo '<p><em><strong>Cancellation Process:</strong> Very simple. A Member clicks a link to a Post/Page that contains a Cancellation Form you\'ve generated. The Member clicks the Submit button to confirm the cancellation. s2Member is notified silently behind-the-scenes, and will immediately cancel all future billing. s2Member will later terminate their account access, at the correct point in time. This works in conjunction with the s2Member Auto-EOT System. For further details, see: <strong>s2Member → PayPal Options → EOT Behavior</strong>.</em></p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<form onsubmit="return false;" autocomplete="off">'."\n";
			echo '<strong>WordPress Shortcode:</strong> (recommended for both the WordPress Visual &amp; HTML Editors)<br />'."\n";
			$ws_plugin__s2member_pro_temp_s = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/shortcodes/paypal-cancellation-form-shortcode.php")));
			echo '<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-cancellation-shortcode" value="'.format_to_edit($ws_plugin__s2member_pro_temp_s).'" onclick="this.select ();" class="monospace" />'."\n";
			echo '</form>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Member Registration Access Links">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-reg-links-section">'."\n";
			echo '<h3>Registration Access Link Generator (for Customer Service)</h3>'."\n";
			echo '<p>s2Member Pro-Forms consolidate the Registration/Checkout process into a single-step solution, so it is unlikely that you will ever need this tool. That being said, if you DO need to deal with a Customer Service issue that requires a simple paid Registration Access Link to be created manually, you can use this tool for that. Alternatively, you can create their account yourself/manually by going to <strong>Users → Add New</strong>. Either of these methods will work fine.</p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<form onsubmit="return false;" autocomplete="off">'."\n";
			echo '<p>Paid Membership Level#: <select id="ws-plugin--s2member-pro-reg-link-level">'."\n";
			for($n = 1; $n <= $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"]; $n++)
				echo '<option value="'.$n.'">s2Member Level #'.$n.'</option>'."\n";
			echo '</select></p>'."\n";
			echo '<p>Paid Subscr. ID: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-reg-link-subscr-id" value="" size="50" /> <a href="#" onclick="alert(\'The Customer\\\'s Paid Subscr. ID (aka: Recurring Profile ID, Transaction ID) must be unique. This value can be obtained from inside your PayPal account in the History tab. Each paying Customer MUST be associated with a unique Paid Subscr. ID. If the Customer is NOT associated with a Paid Subscr. ID, you will need to generate a unique value for this field on your own. But keep in mind, s2Member will be unable to maintain future communication with the PayPal IPN (i.e., Notification) service if this value does not reflect a real Paid Subscr. ID that exists in your PayPal History log.\'); return false;" tabindex="-1">[?]</a></p>'."\n";
			echo '<p>Custom String Value: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-reg-link-custom" value="'.esc_attr($_SERVER["HTTP_HOST"]).'" size="30" /> <a href="#" onclick="alert(\'A Paid Subscription is always associated with a Custom String that is passed through the custom=\\\'\\\''.c_ws_plugin__s2member_utils_strings::esc_js_sq(esc_attr($_SERVER["HTTP_HOST"]), 3).'\\\'\\\' attribute of your Shortcode. This Custom Value, MUST always start with your domain name. However, you can also pipe delimit additional values after your domain, if you need to.\\n\\nFor example:\n'.c_ws_plugin__s2member_utils_strings::esc_js_sq(esc_attr($_SERVER["HTTP_HOST"]), 3).'|cv1|cv2|cv3\'); return false;" tabindex="-1">[?]</a> <input type="button" value="Generate Access Link" onclick="ws_plugin__s2member_pro_paypalRegLinkGenerate();" /> <img id="ws-plugin--s2member-pro-reg-link-loading" src="'.esc_attr($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["dir_url"]).'/images/ajax-loader.gif" alt="" style="display:none;" /></p>'."\n";
			echo '<p'.((is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) ? ' style="display:none;"' : '').'>Custom Capabilities (comma-delimited) <a href="#" onclick="alert(\'Optional. This is VERY advanced.\\nSee: s2Member → API Scripting → Custom Capabilities.\'); return false;" tabindex="-1">[?]</a> <input type="text" maxlength="125" autocomplete="off" id="ws-plugin--s2member-pro-reg-link-ccaps" size="40" onkeyup="if(this.value.match(/[^a-z_0-9,]/)) this.value = jQuery.trim (jQuery.trim (this.value).replace (/[ \-]/g, \'_\').replace (/[^a-z_0-9,]/gi, \'\').toLowerCase ());" /></p>'."\n";
			echo '<p>Fixed Term Length (for Buy Now transactions): <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-reg-link-fixed-term" value="" size="10" /> <a href="#" onclick="alert(\'If the Customer purchased Membership through a Buy Now transaction (i.e., there is no Initial/Trial Period and no recurring charges for ongoing access), you may configure a Fixed Term Length in this field. This way the Customer\\\'s Membership Access is revoked by s2Member at the appropriate time. This will be a numeric value, followed by a space, then a single letter.\\n\\nHere are some examples:\\n\\n1 D (this means 1 Day)\\n1 W (this means 1 Week)\\n1 M (this means 1 Month)\\n1 Y (this means 1 Year)\\n1 L (this means 1 Lifetime)\'); return false;">[?]</a></p>'."\n";
			echo '<p id="ws-plugin--s2member-pro-reg-link" class="monospace" style="display:none;"></p>'."\n";
			echo '</form>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Specific Post/Page (Buy Now) Forms">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-sp-forms-section">'."\n";
			echo '<h3>Pro-Form Generator For Specific Post/Page Forms</h3>'."\n";
			echo '<p>s2Member now supports an additional layer of functionality (very powerful), which allows you to sell access to specific Posts/Pages that you\'ve created in WordPress. Specific Post/Page Access works independently from Member Level Access. That is, you can sell an unlimited number of Posts/Pages using "Buy Now" functionality. Your Customers will NOT be required to have a Membership Account with your site in order to receive access. If they are already a Member, that\'s fine, but they won\'t need to be.</p>'."\n";
			echo '<p>In other words, Customers will NOT need to login, just to receive access to the Specific Post/Page they purchased access to. s2Member will immediately redirect the Customer to the Specific Post/Page after checkout is completed successfully. An email is also sent to the Customer with a link (see: <strong>s2Member → PayPal Options → Specific Post/Page Email</strong>). Authentication is handled automatically through self-expiring links, good for 72 hours by default.</p>'."\n";
			echo '<p>Specific Post/Page Access, is sort of like selling a product. Only, instead of shipping anything to the Customer, you just give them access to a specific Post/Page on your site; one that you created in WordPress. A Specific Post/Page that is protected by s2Member, might contain a download link for your eBook, access to file &amp; music downloads, access to additional support services, and the list goes on and on. The possibilities with this are endless; as long as your digital product can be delivered through access to a WordPress Post/Page that you\'ve created. To protect Specific Posts/Pages, please see: <strong>s2Member → Restriction Options → Specific Post/Page Access</strong>. Once you\'ve configured your Specific Post/Page Restrictions, those Posts/Pages will be available in the menus below.</p>'."\n";
			echo '<p>Very simple. All you do is customize the form fields provided, for each Post/Page that you plan to sell. Then press (Generate Form Code). These special PayPal Forms are customized to work with s2Member seamlessly. You can even Package Additional Posts/Pages together into one transaction.</p>'."\n";
			echo '<p><em><strong>Please note:</strong> forms are NOT saved here. This is only a Form Generator. Once you\'ve generated your Form, copy/paste it into any Post/Page you like. You\'ll want to provide your visitors with a link to the Post/Page where this Form is located.</em></p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<form onsubmit="return false;" autocomplete="off">'."\n";

			echo '<p><select id="ws-plugin--s2member-pro-sp-leading-id">'."\n";
			echo '<option value="">&mdash; Select a Leading Post/Page that you\'ve protected &mdash;</option>'."\n";

			$ws_plugin__s2member_pro_temp_a_singulars = c_ws_plugin__s2member_utils_gets::get_all_singulars_with_sp("exclude-conflicts");

			foreach($ws_plugin__s2member_pro_temp_a_singulars as $ws_plugin__s2member_pro_temp_o)
				echo '<option value="'.esc_attr($ws_plugin__s2member_pro_temp_o->ID).'">'.esc_html($ws_plugin__s2member_pro_temp_o->post_title).'</option>'."\n";

			echo '</select> <a href="#" onclick="alert(\'Required. The Leading Post/Page, is what your Customers will land on after checkout.\n\n*Tip* If there are no Posts/Pages in the menu, it\\\'s because you\\\'ve not configured s2Member for Specific Post/Page Access yet. See: s2Member → Restriction Options → Specific Post/Page Access.\'); return false;" tabindex="-1">[?]</a></p>'."\n";

			echo '<p><select id="ws-plugin--s2member-pro-sp-additional-ids" multiple="multiple" style="height:100px;">'."\n";
			echo '<optgroup label="&mdash; Package Additional Posts/Pages that you\'ve protected &mdash;">'."\n";

			foreach($ws_plugin__s2member_pro_temp_a_singulars as $ws_plugin__s2member_pro_temp_o)
				echo '<option value="'.esc_attr($ws_plugin__s2member_pro_temp_o->ID).'">'.esc_html($ws_plugin__s2member_pro_temp_o->post_title).'</option>'."\n";

			echo '</optgroup></select> <a href="#" onclick="alert(\'Hold down your `Ctrl` key to select multiples.\\n\\nOptional. If you include Additional Posts/Pages, Customers will still land on your Leading Post/Page; BUT, they\\\'ll ALSO have access to some Additional Posts/Pages that you\\\'ve protected. This gives you the ability to create Post/Page Packages.\\n\\nIn other words, a Customer is sold a Specific Post/Page (they\\\'ll land on your Leading Post/Page after checkout), which might contain links to some other Posts/Pages that you\\\'ve packaged together under one transaction.\\n\\nBundling Additional Posts/Pages into one Package, authenticates the Customer for access to the Additional Posts/Pages automatically (i.e., only one Access Link is needed, and s2Member generates this automatically). However, you will STILL need to design your Leading Post/Page (which is what a Customer will actually land on), with links pointing to the other Posts/Pages. This way your Customers will have clickable links to everything they\\\'ve paid for.\\n\\n*Quick Summary* s2Member sends Customers to your Leading Post/Page, and also authenticates them for access to any Additional Posts/Pages automatically. You handle it from there.\\n\\n*Tip* If there are no Posts/Pages in this menu, it\\\'s because you\\\'ve not configured s2Member for Specific Post/Page Access yet. See: s2Member → Restriction Options → Specific Post/Page Access.\'); return false;" tabindex="-1">[?]</a></p>'."\n";

			echo '<p>I want to charge: $<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-sp-amount" value="0.01" size="4" /> / <select id="ws-plugin--s2member-pro-sp-hours">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/paypal-sp-hours.php"))).'</select></p>'."\n";
			echo '<p>Description: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-sp-desc" value="Description and pricing details here." size="68" /></p>'."\n";
			echo '<p>Checkout Page Style <a href="#" onclick="alert(\'Optional. This can be configured inside your PayPal account. PayPal allows you to create Custom Page Styles, and assign a unique name to them. You can add your own header image and color selection to the checkout form. Once you\\\'ve created a Custom Page Style at PayPal, you can enter that Page Style here.\\n\\nWith PayPal Pro integration, this is only applied to PayPal Express Checkout pages; when/if a Customer chooses PayPal as the Payment Method.\'); return false;" tabindex="-1">[?]</a>: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-sp-page-style" value="paypal" size="18" /> <select id="ws-plugin--s2member-pro-sp-currency">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/paypal-currencies.php"))).'</select> <input type="button" value="Generate Form Code" onclick="ws_plugin__s2member_pro_paypalSpFormGenerate();" /></p>'."\n";
			echo '</form>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<form onsubmit="return false;" autocomplete="off">'."\n";
			echo '<strong>WordPress Shortcode:</strong> (recommended for both the WordPress Visual &amp; HTML Editors)<br />'."\n";
			$ws_plugin__s2member_pro_temp_s = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/shortcodes/paypal-sp-checkout-form-shortcode.php")));
			$ws_plugin__s2member_pro_temp_s = preg_replace("/%%custom%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($_SERVER["HTTP_HOST"])), $ws_plugin__s2member_pro_temp_s);
			echo '<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-sp-shortcode" value="'.format_to_edit($ws_plugin__s2member_pro_temp_s).'" onclick="this.select ();" class="monospace" />'."\n";
			echo '</form>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Specific Post/Page Access Links">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-sp-links-section">'."\n";
			echo '<h3>Specific Post/Page Access Link Generator (for Customer Service)</h3>'."\n";
			echo '<p>s2Member automatically generates Specific Post/Page Access Links for your Customers after checkout, and also sends them a link in a Confirmation Email. However, if you ever need to deal with a Customer Service issue that requires a new Specific Post/Page Access Link to be created manually, you can use this tool for that.</p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<form onsubmit="return false;" autocomplete="off">'."\n";

			echo '<p><select id="ws-plugin--s2member-pro-sp-link-leading-id">'."\n";
			echo '<option value="">&mdash; Select a Leading Post/Page that you\'ve protected &mdash;</option>'."\n";

			$ws_plugin__s2member_pro_temp_a_singulars = c_ws_plugin__s2member_utils_gets::get_all_singulars_with_sp("exclude-conflicts");

			foreach($ws_plugin__s2member_pro_temp_a_singulars as $ws_plugin__s2member_pro_temp_o)
				echo '<option value="'.esc_attr($ws_plugin__s2member_pro_temp_o->ID).'">'.esc_html($ws_plugin__s2member_pro_temp_o->post_title).'</option>'."\n";

			echo '</select> <a href="#" onclick="alert(\'Required. The Leading Post/Page, is what your Customers will land on after checkout.\n\n*Tip* If there are no Posts/Pages in the menu, it\\\'s because you\\\'ve not configured s2Member for Specific Post/Page Access yet. See: s2Member → Restriction Options → Specific Post/Page Access.\'); return false;" tabindex="-1">[?]</a></p>'."\n";

			echo '<p><select id="ws-plugin--s2member-pro-sp-link-additional-ids" multiple="multiple" style="height:100px; min-width:450px;">'."\n";
			echo '<optgroup label="&mdash; Package Additional Posts/Pages that you\'ve protected &mdash;">'."\n";

			foreach($ws_plugin__s2member_pro_temp_a_singulars as $ws_plugin__s2member_pro_temp_o)
				echo '<option value="'.esc_attr($ws_plugin__s2member_pro_temp_o->ID).'">'.esc_html($ws_plugin__s2member_pro_temp_o->post_title).'</option>'."\n";

			echo '</optgroup></select> <a href="#" onclick="alert(\'Hold down your `Ctrl` key to select multiples.\\n\\nOptional. If you include Additional Posts/Pages, Customers will still land on your Leading Post/Page; BUT, they\\\'ll ALSO have access to some Additional Posts/Pages that you\\\'ve protected. This gives you the ability to create Post/Page Packages.\\n\\nIn other words, a Customer is sold a Specific Post/Page (they\\\'ll land on your Leading Post/Page after checkout), which might contain links to some other Posts/Pages that you\\\'ve packaged together under one transaction.\\n\\nBundling Additional Posts/Pages into one Package, authenticates the Customer for access to the Additional Posts/Pages automatically (i.e., only one Access Link is needed, and s2Member generates this automatically). However, you will STILL need to design your Leading Post/Page (which is what a Customer will actually land on), with links pointing to the other Posts/Pages. This way your Customers will have clickable links to everything they\\\'ve paid for.\\n\\n*Quick Summary* s2Member sends Customers to your Leading Post/Page, and also authenticates them for access to any Additional Posts/Pages automatically. You handle it from there.\\n\\n*Tip* If there are no Posts/Pages in this menu, it\\\'s because you\\\'ve not configured s2Member for Specific Post/Page Access yet. See: s2Member → Restriction Options → Specific Post/Page Access.\'); return false;" tabindex="-1">[?]</a></p>'."\n";

			echo '<p><select id="ws-plugin--s2member-pro-sp-link-hours">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/paypal-sp-hours.php"))).'</select> <input type="button" value="Generate Access Link" onclick="ws_plugin__s2member_pro_paypalSpLinkGenerate();" /> <img id="ws-plugin--s2member-pro-sp-link-loading" src="'.esc_attr($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["dir_url"]).'/images/ajax-loader.gif" alt="" style="display:none;" /></p>'."\n";
			echo '<p id="ws-plugin--s2member-pro-sp-link" class="monospace" style="display:none;"></p>'."\n";
			echo '</form>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			if(!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site())
			{
				echo '<div class="ws-menu-page-group" title="Custom Return URLs Upon Success">'."\n";

				echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-forms-success-section">'."\n";
				echo '<h3>Custom Return URLs Upon Success (optional, for developers)</h3>'."\n";
				echo '<p>s2Member Pro opens the door for Custom Return URLs upon success. You can add a special attribute to any Form Shortcode (<code>success="/my-thank-you-page/"</code>). This makes it possible to integrate PayPal Pro-Forms in very creative ways; and even receive/verify Replacement Code variables, as needed. For example, (<code>success="/my-thank-you-page/?subscr_id=%%subscr_id%%"</code>).</p>'."\n";
				echo '<p><em>A Custom Return URL is 100% optional. In fact, if you only need to obtain details for the purpose of tracking sales, you should just use the simpler API Tracking methods provided by s2Member, under: <strong>s2Member → API / Tracking</strong>. In other words, if you don\'t use the <code>success=""</code> attribute in your Shortcode, s2Member will handle things gracefully, all on its own. So using a Custom Return URL is only necessary when you need advanced customization for one reason or another.</em></p>'."\n";

				echo '<div class="ws-menu-page-hr"></div>'."\n";

				echo '<h3>Free Registration Forms (<a href="#" onclick="jQuery(\'div#ws-plugin--s2member-pro-forms-success-free-registration\').toggle(); return false;" class="ws-dotted-link">open/close</a>)</h3>'."\n";
				echo '<div id="ws-plugin--s2member-pro-forms-success-free-registration" style="display:none;">'."\n";
				echo '<ul style="margin: 10px 0 10px 20px; list-style: disc outside;" class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%role%%</code> = The Role ID <code>(subscriber, s2member_level[0-9]+, administrator, editor, author, contributor)</code>.</li>'."\n";
				echo '<li><code>%%level%%</code> = The Level number <code>(0, 1, 2, 3, 4)</code>. (<em>deprecated, no longer recommended; use <code>%%role%%</code></em>)</li>'."\n";
				echo '<li><code>%%ccaps%%</code> = Custom Capabilities. Ex: <code>music,videos,free_gift</code> (<em>in comma-delimited format</em>).</li>'."\n";
				echo '<li><code>%%auto_eot_time%%</code> = Auto-EOT Time (if applicable). Ex: <code>1299925670</code> (<em>unix timestamp</em>).</li>'."\n";
				echo '<li><code>%%user_first_name%%</code> = The First Name of the Member who registered their Username.</li>'."\n";
				echo '<li><code>%%user_last_name%%</code> = The Last Name of the Member who registered their Username.</li>'."\n";
				echo '<li><code>%%user_full_name%%</code> = The Full Name (First &amp; Last) of the Member who registered their Username.</li>'."\n";
				echo '<li><code>%%user_email%%</code> = The Email Address of the Member who registered their Username.</li>'."\n";
				echo '<li><code>%%user_login%%</code> = The Username the Member selected during registration.</li>'."\n";
				echo '<li><code>%%user_pass%%</code> = The Password selected or generated during registration.</li>'."\n";
				echo '<li><code>%%user_ip%%</code> = The User\'s IP Address, via <code>$_SERVER["REMOTE_ADDR"]</code>.</li>'."\n";
				echo '<li><code>%%user_id%%</code> = A unique WordPress User ID generated during registration.</li>'."\n";
				echo '<li><code>%%response%%</code> = Deprecated. Use <code>%%s_response%%</code>. A successful response message that *would* have been displayed to the Customer, had they NOT been redirected to your Custom Return URL upon success. This may contain some basic HTML. For instance, it might contain a link to the login page. You don\'t have to use this. You can generate your own response if you like.</li>'."\n";
				echo '<li><code>%%s_response%%</code> = A successful response message that *would* have been displayed to the Customer, had they NOT been redirected to your Custom Return URL upon success. This may contain some basic HTML. For instance, it might contain a link to the login page. You don\'t have to use this. You can generate your own response if you like. Value is encrypted. Use <a href="http://www.s2member.com/codex/stable/s2member/api_functions/package-functions/#src_doc_s2member_decrypt()" target="_blank" rel="external">s2member_decrypt()</a>.</li>'."\n";
				echo '</ul>'."\n";

				echo '<strong>Custom Registration/Profile Fields are also supported here:</strong>'."\n";
				echo '<ul style="margin: 10px 0 10px 20px; list-style: disc outside;" class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%date_of_birth%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>date_of_birth</code>.</li>'."\n";
				echo '<li><code>%%street_address%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>street_address</code>.</li>'."\n";
				echo '<li><code>%%country%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>country</code>.</li>'."\n";
				echo '<li><em><code>%%etc, etc...%%</code> <strong>see:</strong> s2Member → General Options → Registration/Profile Fields</em>.</li>'."\n";
				echo '</ul>'."\n";

				echo '<strong>Custom Replacement Codes can also be inserted using these instructions:</strong>'."\n";
				echo '<ul style="margin: 10px 0 10px 20px; list-style: disc outside;" class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%cv0%%</code> = The domain of your site, which is passed through the `custom` attribute in your Shortcode.</li>'."\n";
				echo '<li><code>%%cv1%%</code> = If you need to track additional custom variables, you can pipe delimit them into the `custom` attribute; inside your Shortcode, like this: <code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'|cv1|cv2|cv3"</code>. You can have an unlimited number of custom variables. Obviously, this is for advanced webmasters; but the functionality has been made available for those who need it.</li>'."\n";
				echo '</ul>'."\n";
				echo '<strong>This example uses cv1 to record a special marketing campaign:</strong><br />'."\n";
				echo '<em>(The campaign (i.e., christmas-promo) could be referenced using <code>%%cv1%%</code>)</em><br />'."\n";
				echo '<code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'|christmas-promo"</code>'."\n";
				echo '</div>'."\n";

				echo '<div class="ws-menu-page-hr"></div>'."\n";

				echo '<h3>Membership Sales / Signups &amp; Modifications (<a href="#" onclick="jQuery(\'div#ws-plugin--s2member-pro-forms-success-sales\').toggle(); return false;" class="ws-dotted-link">open/close</a>)</h3>'."\n";
				echo '<div id="ws-plugin--s2member-pro-forms-success-sales" style="display:none;">'."\n";
				echo '<ul style="margin: 10px 0 10px 20px; list-style: disc outside;" class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%subscr_id%%</code> = The PayPal Subscription ID, which remains constant throughout any &amp; all future payments. [ <a href="#" onclick="alert(\'There is one exception. If you are selling Lifetime or Fixed-Term (non-recurring) access, using Buy Now functionality; the %%subscr_id%% is actually set to the Transaction ID for the purchase. PayPal does not provide a specific Subscription ID for Buy Now purchases. Since Lifetime &amp; Fixed-Term Subscriptions are NOT recurring (i.e., there is only ONE payment), using the Transaction ID as the Subscription ID is a graceful way to deal with this minor conflict.\'); return false;">?</a> ]</li>'."\n";
				echo '<li><code>%%subscr_baid%%</code> = Applicable only with PayPal Pro (Payflow Edition); and only for Express Checkout transactions that require a Billing Agreement. This is the Subscription\'s Billing Agreement ID, which remains constant throughout any &amp; all future payments. [ <a href="#" onclick="alert(\'Applicable only with PayPal Pro (Payflow Edition); and only for Express Checkout transactions that require a Billing Agreement. In all other cases, the %%subscr_baid%% is simply set to the %%subscr_id%% value; i.e., it is a duplicate of %%subscr_id%% in most cases.\'); return false;">?</a> ]</li>'."\n";
				echo '<li><code>%%currency%%</code> = Three-character currency code (uppercase); e.g., <code>USD</code></li>'."\n";
				echo '<li><code>%%currency_symbol%%</code> = Currency code symbol; e.g., <code>$</code></li>'."\n";
				echo '<li><code>%%initial%%</code> = The Initial Fee charged during signup. If you offered a 100% Free Trial, this will be <code>0</code>. [ <a href="#" onclick="alert(\'This will always represent the amount of money the Customer spent, whenever they initially signed up, no matter what. Even if that amount is 0.\\n\\nIf a Customer signs up, under the terms of a 100% Free Trial Period, this will be 0. So be careful using %%initial%% when you offer a 100% Free Trial Period, because a $0.00 sale amount could cause havoc with affiliate programs.\\n\\nIf you\\\'re offering a 100% Free Trial Period, and you need to track sales through affiliate programs, you can either hard-code an amount; or use `s2Member → API Notifications → Payment Notifications` instead.\'); return false;">?</a> ]</li>'."\n";
				echo '<li><code>%%regular%%</code> = The Regular Amount of the Subscription. If you offer something 100% free, this will be <code>0</code>. [ <a href="#" onclick="alert(\'This is how much the Subscription costs after an Initial Period expires. If you did NOT offer an Initial Period at a different price, %%initial%% and %%regular%% will be equal to the same thing.\'); return false;">?</a> ]</li>'."\n";
				echo '<li><code>%%recurring%%</code> = This is the amount that will be charged on a recurring basis, or <code>0</code> if non-recurring. [ <a href="#" onclick="alert(\'If Recurring Payments have not been required, this will be equal to 0. That being said, %%regular%% &amp; %%recurring%% are usually the same value. This variable can be used in two different ways. You can use it to determine what the Regular Recurring Rate is, or to determine whether the Subscription will recur or not. If it is going to recur, %%recurring%% will be > 0.\'); return false;">?</a> ]</li>'."\n";
				echo '<li><code>%%first_name%%</code> = The First Name of the Customer who purchased the Membership Subscription.</li>'."\n";
				echo '<li><code>%%last_name%%</code> = The Last Name of the Customer who purchased the Membership Subscription.</li>'."\n";
				echo '<li><code>%%full_name%%</code> = The Full Name (First &amp; Last) of the Customer who purchased the Membership Subscription.</li>'."\n";
				echo '<li><code>%%payer_email%%</code> = The Email Address of the Customer who purchased the Membership Subscription.</li>'."\n";
				echo '<li><code>%%item_number%%</code> = The Item Number (colon separated <code><em>level:custom_capabilities:fixed term</em></code>) for the Membership Subscription.</li>'."\n";
				echo '<li><code>%%item_name%%</code> = The Item Name (as provided by the <code>desc=""</code> attribute in your Shortcode, which briefly describes the Item Number).</li>'."\n";
				echo '<li><code>%%initial_term%%</code> = This is the term length of the Initial Period. This will be a numeric value, followed by a space, then a single letter. [ <a href="#" onclick="alert(\'Here are some examples:\\n\\n%%initial_term%% = 1 D (this means 1 Day)\\n%%initial_term%% = 1 W (this means 1 Week)\\n%%initial_term%% = 1 M (this means 1 Month)\\n%%initial_term%% = 1 Y (this means 1 Year)\\n\\nThe Initial Period never recurs, so this only lasts for the term length specified, then it is over.\'); return false;">?</a> ]</li>'."\n";
				echo '<li><code>%%regular_term%%</code> = This is the term length of the Regular Period. This will be a numeric value, followed by a space, then a single letter. [ <a href="#" onclick="alert(\'Here are some examples:\\n\\n%%regular_term%% = 1 D (this means 1 Day)\\n%%regular_term%% = 1 W (this means 1 Week)\\n%%regular_term%% = 1 M (this means 1 Month)\\n%%regular_term%% = 1 Y (this means 1 Year)\\n%%regular_term%% = 1 L (this means 1 Lifetime)\\n\\nThe Regular Term is usually recurring. So the Regular Term value represents the period (or duration) of each recurring period. If %%recurring%% = 0, then the Regular Term only applies once, because it is not recurring. So if it is not recurring, the value of %%regular_term%% simply represents how long their Membership privileges are going to last after the %%initial_term%% has expired, if there was an Initial Term. The value of this variable ( %%regular_term%% ) will never be empty, it will always be at least: 1 D, meaning 1 day. No exceptions.\'); return false;">?</a> ]</li>'."\n";
				echo '<li><code>%%modification%%</code> = <code>1</code> if/when a Billing Modification has just taken place; otherwise <code>0</code> indicates a new Customer.</li>'."\n";
				echo '<li><code>%%user_first_name%%</code> = The First Name listed on their User account. This might be different than what is on file with your Payment Gateway.</li>'."\n";
				echo '<li><code>%%user_last_name%%</code> = The Last Name listed on their User account. This might be different than what is on file with your Payment Gateway.</li>'."\n";
				echo '<li><code>%%user_full_name%%</code> = The Full Name listed on their User account. This might be different than what is on file with your Payment Gateway.</li>'."\n";
				echo '<li><code>%%user_email%%</code> = The Email Address associated with their User account. This might be different than what is on file with your Payment Gateway.</li>'."\n";
				echo '<li><code>%%user_login%%</code> = The Username associated with their account. The Customer created this during registration.</li>'."\n";
				echo '<li><code>%%user_ip%%</code> = The Customer\'s original IP Address, during checkout/registration via <code>$_SERVER["REMOTE_ADDR"]</code>.</li>'."\n";
				echo '<li><code>%%user_id%%</code> = A unique WordPress User ID that references this account in the WordPress database.</li>'."\n";
				echo '<li><code>%%response%%</code> = Deprecated. Use <code>%%s_response%%</code>. A successful response message that *would* have been displayed to the Customer, had they NOT been redirected to your Custom Return URL upon success. This may contain some basic HTML. For instance, it might contain a link to the login page. You don\'t have to use this. You can generate your own response if you like.</li>'."\n";
				echo '<li><code>%%s_response%%</code> = A successful response message that *would* have been displayed to the Customer, had they NOT been redirected to your Custom Return URL upon success. This may contain some basic HTML. For instance, it might contain a link to the login page. You don\'t have to use this. You can generate your own response if you like. Value is encrypted. Use <a href="http://www.s2member.com/codex/stable/s2member/api_functions/package-functions/#src_doc_s2member_decrypt()" target="_blank" rel="external">s2member_decrypt()</a>.</li>'."\n";
				echo '</ul>'."\n";

				echo '<strong>Coupon Replacement Codes:</strong>'."\n";
				echo '<ul class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%full_coupon_code%%</code> = A full Coupon Code—if one is accepted by your configuration of s2Member. This may indicate an Affiliate Coupon Code, which will include your Affiliate Suffix Chars too (i.e., the full Coupon Code).</li>'."\n";
				echo '<li><code>%%coupon_code%%</code> = A Coupon Code—if one is accepted by your configuration of s2Member. This will NOT include any Affiliate Suffix Chars. This indicates the actual Coupon Code accepted by your configuration of s2Member (excluding any Affiliate ID).</li>'."\n";
				echo '<li><code>%%coupon_affiliate_id%%</code> = This is the end of an Affiliate Coupon Code <em>(i.e., the referring affiliate\'s ID)</em>. This is only applicable if an Affiliate Coupon Code is accepted by your configuration of s2Member.</li>'."\n";
				echo '</ul>'."\n";

				echo '<strong>Custom Registration/Profile Fields are also supported here:</strong>'."\n";
				echo '<ul style="margin: 10px 0 10px 20px; list-style: disc outside;" class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%date_of_birth%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>date_of_birth</code>.</li>'."\n";
				echo '<li><code>%%street_address%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>street_address</code>.</li>'."\n";
				echo '<li><code>%%country%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>country</code>.</li>'."\n";
				echo '<li><em><code>%%etc, etc...%%</code> <strong>see:</strong> s2Member → General Options → Registration/Profile Fields</em>.</li>'."\n";
				echo '</ul>'."\n";

				echo '<strong>Custom Replacement Codes can also be inserted using these instructions:</strong>'."\n";
				echo '<ul style="margin: 10px 0 10px 20px; list-style: disc outside;" class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%cv0%%</code> = The domain of your site, which is passed through the `custom` attribute in your Shortcode.</li>'."\n";
				echo '<li><code>%%cv1%%</code> = If you need to track additional custom variables, you can pipe delimit them into the `custom` attribute; inside your Shortcode, like this: <code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'|cv1|cv2|cv3"</code>. You can have an unlimited number of custom variables. Obviously, this is for advanced webmasters; but the functionality has been made available for those who need it.</li>'."\n";
				echo '</ul>'."\n";
				echo '<strong>This example uses cv1 to record a special marketing campaign:</strong><br />'."\n";
				echo '<em>(The campaign (i.e., christmas-promo) could be referenced using <code>%%cv1%%</code>)</em><br />'."\n";
				echo '<code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'|christmas-promo"</code>'."\n";
				echo '</div>'."\n";

				echo '<div class="ws-menu-page-hr"></div>'."\n";

				echo '<h3>Independent Custom Capability Sales (<a href="#" onclick="jQuery(\'div#ws-plugin--s2member-pro-forms-success-ccaps\').toggle(); return false;" class="ws-dotted-link">open/close</a>)</h3>'."\n";
				echo '<div id="ws-plugin--s2member-pro-forms-success-ccaps" style="display:none;">'."\n";
				echo '<ul style="margin: 10px 0 10px 20px; list-style: disc outside;" class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%txn_id%%</code> = The Payment Transaction ID, which is always unique for each payment received.</li>'."\n";
				echo '<li><code>%%currency%%</code> = Three-character currency code (uppercase); e.g., <code>USD</code></li>'."\n";
				echo '<li><code>%%currency_symbol%%</code> = Currency code symbol; e.g., <code>$</code></li>'."\n";
				echo '<li><code>%%amount%%</code> = The Amount of the payment. Most affiliate programs calculate commissions from this.</li>'."\n";
				echo '<li><code>%%first_name%%</code> = The First Name of the Customer who purchased the Independent Custom Capabilities.</li>'."\n";
				echo '<li><code>%%last_name%%</code> = The Last Name of the Customer who purchased the Independent Custom Capabilities.</li>'."\n";
				echo '<li><code>%%full_name%%</code> = The Full Name (First &amp; Last) of the Customer who purchased the Independent Custom Capabilities.</li>'."\n";
				echo '<li><code>%%payer_email%%</code> = The Email Address of the Customer who purchased the Independent Custom Capabilities.</li>'."\n";
				echo '<li><code>%%item_number%%</code> = The Item Number (colon separated <code><em>*level:custom_capabilities:fixed term</em></code>) that the payment is for. [ <a href="#" onclick="alert(\'With Independent Custom Capabilities, the `level` portion of this string will be an asterisk ( `*` ), since the Membership Level is irrelevant, and remains `as it was`.\'); return false;">?</a> ]</li>'."\n";
				echo '<li><code>%%item_name%%</code> = The Item Name (as provided by the <code>desc=""</code> attribute in your Shortcode, which briefly describes the Item Number).</li>'."\n";
				echo '<li><code>%%user_first_name%%</code> = The First Name listed on their User account. This might be different than what is on file with your Payment Gateway.</li>'."\n";
				echo '<li><code>%%user_last_name%%</code> = The Last Name listed on their User account. This might be different than what is on file with your Payment Gateway.</li>'."\n";
				echo '<li><code>%%user_full_name%%</code> = The Full Name listed on their User account. This might be different than what is on file with your Payment Gateway.</li>'."\n";
				echo '<li><code>%%user_email%%</code> = The Email Address associated with their User account. This might be different than what is on file with your Payment Gateway.</li>'."\n";
				echo '<li><code>%%user_login%%</code> = The Username associated with their account. The Customer created this during registration.</li>'."\n";
				echo '<li><code>%%user_ip%%</code> = The Customer\'s original IP Address, during checkout/registration via <code>$_SERVER["REMOTE_ADDR"]</code>.</li>'."\n";
				echo '<li><code>%%user_id%%</code> = A unique WordPress User ID that references this account in the WordPress database.</li>'."\n";
				echo '<li><code>%%response%%</code> = Deprecated. Use <code>%%s_response%%</code>. A successful response message that *would* have been displayed to the Customer, had they NOT been redirected to your Custom Return URL upon success. This may contain some basic HTML. For instance, it might contain a link to the login page. You don\'t have to use this. You can generate your own response if you like.</li>'."\n";
				echo '<li><code>%%s_response%%</code> = A successful response message that *would* have been displayed to the Customer, had they NOT been redirected to your Custom Return URL upon success. This may contain some basic HTML. For instance, it might contain a link to the login page. You don\'t have to use this. You can generate your own response if you like. Value is encrypted. Use <a href="http://www.s2member.com/codex/stable/s2member/api_functions/package-functions/#src_doc_s2member_decrypt()" target="_blank" rel="external">s2member_decrypt()</a>.</li>'."\n";
				echo '</ul>'."\n";

				echo '<strong>Coupon Replacement Codes:</strong>'."\n";
				echo '<ul class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%full_coupon_code%%</code> = A full Coupon Code—if one is accepted by your configuration of s2Member. This may indicate an Affiliate Coupon Code, which will include your Affiliate Suffix Chars too (i.e., the full Coupon Code).</li>'."\n";
				echo '<li><code>%%coupon_code%%</code> = A Coupon Code—if one is accepted by your configuration of s2Member. This will NOT include any Affiliate Suffix Chars. This indicates the actual Coupon Code accepted by your configuration of s2Member (excluding any Affiliate ID).</li>'."\n";
				echo '<li><code>%%coupon_affiliate_id%%</code> = This is the end of an Affiliate Coupon Code <em>(i.e., the referring affiliate\'s ID)</em>. This is only applicable if an Affiliate Coupon Code is accepted by your configuration of s2Member.</li>'."\n";
				echo '</ul>'."\n";

				echo '<strong>Custom Registration/Profile Fields are also supported here:</strong>'."\n";
				echo '<ul style="margin: 10px 0 10px 20px; list-style: disc outside;" class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%date_of_birth%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>date_of_birth</code>.</li>'."\n";
				echo '<li><code>%%street_address%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>street_address</code>.</li>'."\n";
				echo '<li><code>%%country%%</code> would be valid; if you have a Custom Registration/Profile Field with the ID <code>country</code>.</li>'."\n";
				echo '<li><em><code>%%etc, etc...%%</code> <strong>see:</strong> s2Member → General Options → Registration/Profile Fields</em>.</li>'."\n";
				echo '</ul>'."\n";

				echo '<strong>Custom Replacement Codes can also be inserted using these instructions:</strong>'."\n";
				echo '<ul style="margin: 10px 0 10px 20px; list-style: disc outside;" class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%cv0%%</code> = The domain of your site, which is passed through the `custom` attribute in your Shortcode.</li>'."\n";
				echo '<li><code>%%cv1%%</code> = If you need to track additional custom variables, you can pipe delimit them into the `custom` attribute; inside your Shortcode, like this: <code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'|cv1|cv2|cv3"</code>. You can have an unlimited number of custom variables. Obviously, this is for advanced webmasters; but the functionality has been made available for those who need it.</li>'."\n";
				echo '</ul>'."\n";
				echo '<strong>This example uses cv1 to record a special marketing campaign:</strong><br />'."\n";
				echo '<em>(The campaign (i.e., christmas-promo) could be referenced using <code>%%cv1%%</code>)</em><br />'."\n";
				echo '<code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'|christmas-promo"</code>'."\n";
				echo '</div>'."\n";

				echo '<div class="ws-menu-page-hr"></div>'."\n";

				echo '<h3>Specific Post/Page Transactions (<a href="#" onclick="jQuery(\'div#ws-plugin--s2member-pro-forms-success-sp-sales\').toggle(); return false;" class="ws-dotted-link">open/close</a>)</h3>'."\n";
				echo '<div id="ws-plugin--s2member-pro-forms-success-sp-sales" style="display:none;">'."\n";
				echo '<ul style="margin: 10px 0 10px 20px; list-style: disc outside;" class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%sp_access_url%%</code> = The full URL (generated by s2Member) where the Customer can gain access.</li>'."\n";
				echo '<li><code>%%sp_access_exp%%</code> = Human readable expiration for <code>%%sp_access_url%%</code>. Ex: <em>(link expires in <code>%%sp_access_exp%%</code>)</em>.</li>'."\n";
				echo '<li><code>%%txn_id%%</code> = The PayPal Transaction ID. PayPal assigns a unique identifier for every purchase.</li>'."\n";
				echo '<li><code>%%currency%%</code> = Three-character currency code (uppercase); e.g., <code>USD</code></li>'."\n";
				echo '<li><code>%%currency_symbol%%</code> = Currency code symbol; e.g., <code>$</code></li>'."\n";
				echo '<li><code>%%amount%%</code> = The full Amount of the sale. Most affiliate programs calculate commissions from this.</li>'."\n";
				echo '<li><code>%%first_name%%</code> = The First Name of the Customer who purchased Specific Post/Page Access.</li>'."\n";
				echo '<li><code>%%last_name%%</code> = The Last Name of the Customer who purchased Specific Post/Page Access.</li>'."\n";
				echo '<li><code>%%full_name%%</code> = The Full Name (First &amp; Last) of the Customer who purchased Specific Post/Page Access.</li>'."\n";
				echo '<li><code>%%payer_email%%</code> = The Email Address of the Customer who purchased Specific Post/Page Access.</li>'."\n";
				echo '<li><code>%%user_ip%%</code> = The Customer\'s IP Address, detected during checkout via <code>$_SERVER["REMOTE_ADDR"]</code>.</li>'."\n";
				echo '<li><code>%%item_number%%</code> = The Item Number. Ex: <code><em>sp:13,24,36:72</em></code> (translates to: <code><em>sp:comma-delimited IDs:expiration hours</em></code>).</li>'."\n";
				echo '<li><code>%%item_name%%</code> = The Item Name (as provided by the <code>desc=""</code> attribute in your Shortcode, which briefly describes the Item Number).</li>'."\n";
				echo '<li><code>%%response%%</code> = Deprecated. Use <code>%%s_response%%</code>. A successful response message that *would* have been displayed to the Customer, had they NOT been redirected to your Custom Return URL upon success. This may contain some basic HTML. For instance, a link to the Specific Post/Page. You don\'t have to use this. You can generate your own response if you like.</li>'."\n";
				echo '<li><code>%%s_response%%</code> = A successful response message that *would* have been displayed to the Customer, had they NOT been redirected to your Custom Return URL upon success. This may contain some basic HTML. For instance, a link to the Specific Post/Page. You don\'t have to use this. You can generate your own response if you like. Value is encrypted. Use <a href="http://www.s2member.com/codex/stable/s2member/api_functions/package-functions/#src_doc_s2member_decrypt()" target="_blank" rel="external">s2member_decrypt()</a>.</li>'."\n";
				echo '</ul>'."\n";

				echo '<strong>Coupon Replacement Codes:</strong>'."\n";
				echo '<ul class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%full_coupon_code%%</code> = A full Coupon Code—if one is accepted by your configuration of s2Member. This may indicate an Affiliate Coupon Code, which will include your Affiliate Suffix Chars too (i.e., the full Coupon Code).</li>'."\n";
				echo '<li><code>%%coupon_code%%</code> = A Coupon Code—if one is accepted by your configuration of s2Member. This will NOT include any Affiliate Suffix Chars. This indicates the actual Coupon Code accepted by your configuration of s2Member (excluding any Affiliate ID).</li>'."\n";
				echo '<li><code>%%coupon_affiliate_id%%</code> = This is the end of an Affiliate Coupon Code <em>(i.e., the referring affiliate\'s ID)</em>. This is only applicable if an Affiliate Coupon Code is accepted by your configuration of s2Member.</li>'."\n";
				echo '</ul>'."\n";

				echo '<strong>Custom Replacement Codes can also be inserted using these instructions:</strong>'."\n";
				echo '<ul style="margin: 10px 0 10px 20px; list-style: disc outside;" class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%cv0%%</code> = The domain of your site, which is passed through the `custom` attribute in your Shortcode.</li>'."\n";
				echo '<li><code>%%cv1%%</code> = If you need to track additional custom variables, you can pipe delimit them into the `custom` attribute; inside your Shortcode, like this: <code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'|cv1|cv2|cv3"</code>. You can have an unlimited number of custom variables. Obviously, this is for advanced webmasters; but the functionality has been made available for those who need it.</li>'."\n";
				echo '</ul>'."\n";
				echo '<strong>This example uses cv1 to record a special marketing campaign:</strong><br />'."\n";
				echo '<em>(The campaign (i.e., christmas-promo) could be referenced using <code>%%cv1%%</code>)</em><br />'."\n";
				echo '<code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'|christmas-promo"</code>'."\n";
				echo '</div>'."\n";

				echo '<div class="ws-menu-page-hr"></div>'."\n";

				echo '<h3>Cancellations &amp; Billing Updates (<a href="#" onclick="jQuery(\'div#ws-plugin--s2member-pro-forms-success-cancellations-updates\').toggle(); return false;" class="ws-dotted-link">open/close</a>)</h3>'."\n";
				echo '<div id="ws-plugin--s2member-pro-forms-success-cancellations-updates" style="display:none;">'."\n";
				echo '<ul style="margin: 10px 0 10px 20px; list-style: disc outside;" class="ws-menu-page-li-margins">'."\n";
				echo '<li><code>%%response%%</code> = Deprecated. Use <code>%%s_response%%</code>. A successful response message that *would* have been displayed to the Customer, had they NOT been redirected to your Custom Return URL upon success. This may contain some basic HTML. For instance, a link back to their account page. You don\'t have to use this. You can generate your own response if you like.</li>'."\n";
				echo '<li><code>%%s_response%%</code> = A successful response message that *would* have been displayed to the Customer, had they NOT been redirected to your Custom Return URL upon success. This may contain some basic HTML. For instance, a link back to their account page. You don\'t have to use this. You can generate your own response if you like. Value is encrypted. Use <a href="http://www.s2member.com/codex/stable/s2member/api_functions/package-functions/#src_doc_s2member_decrypt()" target="_blank" rel="external">s2member_decrypt()</a>.</li>'."\n";
				echo '</ul>'."\n";
				echo '</div>'."\n";

				echo '<div class="ws-menu-page-hr"></div>'."\n";

				echo '<h3>Verify The Integrity Of Replacement Codes (<a href="#" onclick="jQuery(\'div#ws-plugin--s2member-pro-forms-success-verification\').toggle(); return false;" class="ws-dotted-link">open/close</a>)</h3>'."\n";
				echo '<div id="ws-plugin--s2member-pro-forms-success-verification" style="display:none;">'."\n";
				echo '<p>If you know a little PHP, you can verify the integrity of the Replacement Codes returned by s2Member. This is important, because in this particular situation, Replacement Codes are passed publicly in the query string of your Custom Return URL. In other words, a Customer could manually change one of the values; like the dollar amounts. For this reason, you should always verify the integrity of the details being returned to any processing routines that receive this information. In the PHP code for your Custom Return URL, you can use this s2Member API Function: <code>s2member_pro_paypal_s2p_v_query_ok()</code>.</p>'."\n";
				echo '<p>Here are some examples:</p>'."\n";
				echo '<p>1. <strong>Shortcode attribute:</strong> <code>success="/thank-you/?subscr_id=%%subscr_id%%&amp;initial=%%initial%%&amp;regular=%%regular%%"</code></p>'."\n";
				echo '<p>2. <strong>s2Member returns Customer to:</strong> <code>/thank-you/?subscr_id=123&amp;initial=0.00&amp;regular=24.95&amp;s2p-v=234098234-23409sdfs234sd234209sdf</code></p>'."\n";
				echo '<p>3. <strong>Now, in your Custom Return Page, you will need to do this before trusting anything:</strong></p>'."\n";
				echo '<p>'.c_ws_plugin__s2member_utils_strings::highlight_php(file_get_contents(dirname(__FILE__)."/code-samples/paypal-s2p-v-query-ok-1.x-php")).'</p>'."\n";
				echo '<p>s2Member will only verify a query string for up to 10 seconds. After 10 seconds, <code>s2member_pro_paypal_s2p_v_query_ok()</code> will always return <code>false</code>, even if the integrity of the query string is valid. This prevents a Customer from bookmarking your Return URL; thereby causing duplicate commissions; in case you\'re using it for tracking purposes.</p>'."\n";
				echo '<p>Again, if you only need to obtain details for the purpose of tracking sales, you should just use the simpler API Tracking methods provided by s2Member, under: <strong>s2Member → API / Tracking</strong>. The API Tracking methods are specifically designed for tracking sales, exactly ONE time for each Customer.</p>'."\n";

				echo '<div class="ws-menu-page-hr"></div>'."\n";

				echo '<p><em>If it is your intention to allow Customers to bookmark your Custom Return URL, you can still do that. Just be aware that <code>s2member_pro_paypal_s2p_v_query_ok()</code> will return <code>false</code> after the first 10 seconds. If you want to verify after 10 seconds, you can pass a second argument to the function, like this:</em></p>'."\n";
				echo '<p>'.c_ws_plugin__s2member_utils_strings::highlight_php(file_get_contents(dirname(__FILE__)."/code-samples/paypal-s2p-v-query-ok-2.x-php")).'</p>'."\n";

				echo '<div class="ws-menu-page-hr"></div>'."\n";

				echo '<h3>Could a Customer change the timestamp in the URL?</h3>'."\n";
				echo '<p>Based on the structure of the URL, it would appear possible; however, it\'s NOT. s2Member uses an advanced checksum.</p>'."\n";
				echo '<h3>Can I get rid of the <code>s2p-v</code> variable?</h3>'."\n";
				echo '<p>No, this variable is always passed to your Custom Return URL, it\'s for important verification purposes.</p>'."\n";
				echo '</div>'."\n";
				echo '</div>'."\n";

				echo '</div>'."\n";
			}

			echo '<div class="ws-menu-page-group" title="Shortcode Attributes (Explained)">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-shortcode-attrs-section">'."\n";
			echo '<h3>Shortcode Attributes (Explained In Full Detail)</h3>'."\n";
			echo '<p>When you generate a Form, s2Member will make a <a href="http://s2member.com/r/shortcode-reference/" target="_blank" rel="external">Shortcode</a> available to you. Like most Shortcodes for WordPress, s2Member reads Attributes in your Shortcode. These Attributes will be pre-configured by one of s2Member\'s Form Generators automatically; so there really is nothing more you need to do. However, many site owners like to know exactly how these Shortcode Attributes work. Below, is a brief overview of each possible Shortcode Attribute.</p>'."\n";

			echo '<table class="form-table" style="margin-top:0;">'."\n";
			echo '<tbody>'."\n";
			echo '<tr style="padding-top:0;">'."\n";

			echo '<td style="padding-top:0;">'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo '<li><code>accept="paypal,visa,mastercard,amex,discover,maestro,solo"</code> Accepted Billing Methods. A comma-delimited list of Billing Methods you want to accept. Due to a PayPal policy, you may NOT exclude PayPal from this list; s2Member won\'t let you. Not valid when <code>cancel="1"</code>.</li>'."\n";
			echo '<li><code>accept_via_paypal="paypal"</code> Accepted Billing via PayPal. A comma-delimited list of Billing Methods you want to accept through PayPal, as opposed to processing them on-site. Due to a PayPal policy, you may NOT exclude PayPal from this list; s2Member won\'t let you. <strong>Tip:</strong> If you don\'t have a PayPal Pro account, you can set <code>accept="paypal"</code>, or set <code>accept_via_paypal="paypal,visa,mastercard,amex,discover,maestro,solo"</code>. With one or both of these configurations, all you need is a PayPal Standard account with Express Checkout <em>(which is free)</em>. Not valid when <code>cancel="1"</code>.</li>'."\n";
			echo '<li><code>accept_coupons="1"</code> Accept Coupons? Possible values: <code>0</code> = do NOT accept Coupons on this particular Form; <code>1</code> = DO accept Coupon Codes on this particular Form.</li>'."\n";
			echo '<li><code>cancel="0"</code> Cancellation Form. Only valid w/ Membership Level Access. Possible values: <code>0</code> = this is NOT a Cancellation Form, <code>1</code> = this IS a Cancellation Form.</li>'."\n";
			echo '<li><code>captcha=""</code> When you set this Attribute, visitors must prove they\'re human by typing a <a href="http://s2member.com/r/captcha-reference/" target="_blank" rel="external">captcha/security code</a>. This service is powered by Google\'s reCaptcha system. Possible values: <code>0</code> = do NOT require a captcha code on this Form; <code>clean</code> = DO require a captcha code on this Form; using the <code>clean</code> theme style. Possible theme styles include: <code>red</code>, <code>white</code>, <code>clean</code>, and <code>blackglass</code>. Or, if you supplied reCaptcha v2 keys in your s2Member General Options (i.e., you are using reCaptcha v2), this must be <code>light</code> or <code>dark</code>.</li>'."\n";
			echo '<li><code>cc="USD"</code> 3 character Currency Code. Not valid when <code>cancel="1"</code>.</li>'."\n";
			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? '<li><code>ccaps="music,videos"</code> A comma-delimited list of Custom Capabilities. Only valid w/ Membership Level Access and/or Independent Custom Capabilities.</li>'."\n" : '';
			echo '<li><code>coupon="SAVE-10"</code> Default/pre-filled Coupon Code. This is optional, and the Coupon Code (if supplied) must exist in your Coupon Code configuration.</li>'."\n";
			echo '<li><code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'"</code> must start with your domain. Additional values can be piped in (ex: <code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'|cv1|cv2|cv3|etc"</code>). Not valid when <code>cancel="1"</code>.</li>'."\n";
			echo '<li><code>default_country_code="US"</code> If you set this 2-character value, it will pre-configure the default Country that is selected in the drop-down menu for Checkout and Billing Update Forms. This MUST be an uppercase country code, following the <a href="http://s2member.com/r/iso-3166/" target="_blank" rel="external">ISO-3166-1 specification</a>. If this is empty(i.e., <code>""</code>) s2Member will set the default country code automatically; based on currency. Not valid when <code>cancel="1"</code>.</li>'."\n";
			echo '<li><code>desc="Gold Membership @ $29/mo"</code> A brief purchase Description. This can be as long as you like. However, all descriptions passed through PayPal APIs are truncated automatically to 60 characters max (i.e., the maximum allowed length for PayPal descriptions is 60 characters). Not valid when <code>cancel="1"</code>.</li>'."\n";
			echo '<li><code>dg="0"</code> The Digital Goods directive. s2Member will eventually be integrated with <a href="http://s2member.com/r/paypal-express-checkout-digitals/" target="_blank" rel="external">Digital Goods</a> for inline Express Checkout. But for now, this should always be <code>0</code>.</li>'."\n";
			echo '<li><code>exp="72"</code> Access Expires (in hours). Only valid when <code>sp="1"</code> for Specific Post/Page Access.</li>'."\n";
			echo '<li><code>ids="14"</code> A Post/Page ID#, or a comma-delimited list of IDs. Only valid when <code>sp="1"</code> for Specific Post/Page Access.</li>'."\n";
			echo '<li><code>lang=""</code> Optional 5 character Button Language Code <em>(ake: Locale Code—ex: <code>en_US</code>)</em>. This controls the interface language of the PayPal Express Checkout Button itself. If unspecified, the language defaults to English (i.e., <code>en_US</code>; or to the value set by an optional MO translation file; which translates s2Member overall). See <a href="http://s2member.com/r/paypal-locale-codes/" target="_blank" rel="external">this list of possible Locale Codes</a>.</li>'."\n";
			echo '<li><code>lc=""</code> Optional 2 character Country/Locale Code <em>(i.e., Country Code—ex: <code>US</code>)</em>. This controls the interface language used when/if a Customer chooses PayPal Express Checkout as their payment method. If unspecified, the language is determined by PayPal Express Checkout when possible, defaulting to <code>US</code> <em>english</em> when not possible. See <a href="http://s2member.com/r/paypal-locale-codes/" target="_blank" rel="external">this list of possible Country Codes</a>. Not valid when <code>cancel="1"</code>.</li>'."\n";
			echo '<li><code>level="1"</code> Membership Level [1-4] <em>(or, up to the number of configured Levels)</em>. Only valid for Forms providing Membership Level Access, which includes Free Registration Forms too. Free Registration Forms allow a value of <code>level="0"</code> whenever <code>register="1"</code> for Free Registration. In addition, Free Registration Forms will also allow visitors to register for free, even at higher Levels if you wish. Free Registration Forms will accept any value [0-4] <em>(or, up to the number of configured Levels)</em>.'.((is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) ? '' : ' With Independent Custom Capabilities this MUST be set to <code>level="*"</code>, and <code>ccaps=""</code> must NOT be empty <em>(i.e., <code>level="*" ccaps="music,videos"</code>)</em>.').'</li>'."\n";
			echo '<li><code>modify="0"</code> Modification directive. Only valid w/ Membership Level Access. Possible values: <code>0</code> = allows Customers to modify their current Subscription or sign up for a new one, <code>1</code> = allows Customers to only modify their current Subscription. When <code>modify="1"</code>, s2Member will force a Customer to be logged-in before they can fill out the Form (very handy). This is slightly different than PayPal "Button" Codes; there is no "2" option for "Forms"; only <code>0</code> &amp; <code>1</code> are valid values.</li>'."\n";
			echo '<li><code>ns="1"</code> The <em>no_shipping</em> directive. Possible values: <code>0</code> = prompt for an address, but do not require one, <code>1</code> = do not prompt for a shipping address, <code>2</code> = prompt for an address, and require one. Applies only to PayPal Express Checkout; because Pro-Forms do not ask for a Shipping Address. However, you may request a Shipping Address by creating Custom Fields under: <em>s2Member → General Options → Registration/Profile Fields</em>. Not valid when <code>cancel="1"</code>.</li>'."\n";
			echo '<li><code>ps="paypal"</code> PayPal checkout Page Style. Applies only to PayPal Express Checkout. Not valid when <code>cancel="1"</code>.</li>'."\n";
			echo '<li><code>register="0"</code> Free Registration Form. Only valid w/ Membership Level Access. Possible values: <code>0</code> = this is NOT a Free Registration Form, <code>1</code> = this IS a Free Registration Form.</li>'."\n";
			echo '<li><code>ra="0.01"</code> Regular, Buy Now, and/or Recurring Amount. Can also be <code>0.00</code> to provide free access. Not valid when <code>cancel="1"</code>.</li>'."\n";
			echo '<li><code>rp="1"</code> Regular Period. Only valid w/ Membership Level Access'.((is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) ? '' : ' and/or Independent Custom Capabilities').'. Must be &gt;= <code>1</code> (ex: <code>1</code> Week, <code>2</code> Months, <code>1</code> Month, <code>3</code> Days).—<strong>NOTICE (regarding Payflow):</strong> If you are operating a PayPal Pro (Payflow Edition) account, please take note. Payflow supports ONLY a specific set of recurring intervals. Pro-Forms integrated with the Payflow API can be configured to charge: weekly, bi-weekly, monthly, quarterly, semi-yearly or yearly.</li>'."\n";
			echo '<li><code>rt="M"</code> Regular Term. Only valid w/ Membership Level Access'.((is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) ? '' : ' and/or Independent Custom Capabilities').'. Possible values: <code>D</code> = Days, <code>W</code> = Weeks, <code>M</code> = Months, <code>Y</code> = Years, <code>L</code> = Lifetime.—<strong>NOTICE (regarding Payflow):</strong> If you are operating a PayPal Pro (Payflow Edition) account, please take note. Payflow supports ONLY a specific set of recurring intervals. Pro-Forms integrated with the Payflow API can be configured to charge: weekly, bi-weekly, monthly, quarterly, semi-yearly or yearly.</li>'."\n";
			echo '<li><code>rr="1"</code> Recurring directive. Only valid w/ Membership Level Access'.((is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) ? '' : ' and/or Independent Custom Capabilities').'. Possible values: <code>0</code> = non-recurring "Subscription" with possible Trial Period for free, or at a different Trial Amount; <code>1</code> = recurring "Subscription" with possible Trial Period for free, or at a different Trial Amount; <code>BN</code> = non-recurring "Buy Now" functionality, no Trial Period possible.</li>'."\n";
			echo '<li><code>rrt=""</code> Recurring Times <em>(i.e., a fixed number of installments)</em>. Only valid w/ Membership Level Access. When unspecified, any recurring charges will remain ongoing until cancelled, or until payments start failing. If this is set to <code>1 or higher</code> the regular recurring charges will only continue for X billing cycles, depending on what you specify. This is only valid when <code>rr="1"</code> for recurring "Subscriptions". Please note that a fixed number of installments, also means a fixed period of access. If a Customer\'s billing is monthly, and you set <code>rrt="3"</code>, billing will continue for only 3 monthly installments. After that, billing would stop, and their access to the site would be revoked as well <em>(based on your EOT Behavior setting under: s2Member → PayPal Options)</em>. <strong>IMPORTANT NOTE:</strong> If you don\'t offer a trial period; i.e., the first charge occurs when a customer completes checkout, you should set this to the number of <em>additional</em> payments, and NOT to the total number. For instance, if I want to charge the customer a total of 3 times, and one of those charges occurs when they complete checkout, I set should this to <code>rrt="2"</code> for a grand total of three all together.</li>'."\n";
			echo '<li><code>rra="2"</code> Reattempt billing when/if a recurring payment fails; exactly X number of times; and then automatically suspend the customer\'s account (i.e., the customer loses access). By default, PayPal will retry a maximum of 2 times whenever <code>rra="2"</code>; after that, a Subscription would be terminated due to Max Failed Payments having been reached. The value of this attribute configures Max Failed Payments. A setting of <code>rra="2"</code> means that you allow a maximum of 2 failed payments. Setting <code>rra="0"</code> means that you allow an infinite number of failed payments.</li>'."\n";
			echo '<li><code>sp="0"</code> Specific Post/Page Form. Possible values: <code>0</code> = this is NOT a Specific Post/Page Access Form, <code>1</code> = this IS a Specific Post/Page Access Form.</li>'."\n";
			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? '<li><code>success=""</code> 100% optional. This can be used to create a Custom Return URL on success. Please see the sub-section above titled: <em>Custom Return URLs on Success</em>.</li>'."\n" : '';
			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? '<li><code>template=""</code> 100% optional. This can be a custom template file that exists inside your WordPress theme directory. For example: <code>template="checkout.php"</code>. Please see <a href="http://s2member.com/kb-article/can-i-customize-pro-forms/" target="_blank" rel="external">this KB article</a> for further details.</li>'."\n" : '';
			echo '<li><code>ta="0.00"</code> Trial Amount. Only valid w/ Membership Level Access. Must be <code>0</code> when <code>rt="L"</code> or when <code>rr="BN"</code>.</li>'."\n";
			echo '<li><code>tp="0"</code> Trial Period. Only valid w/ Membership Level Access. Must be <code>0</code> when <code>rt="L"</code> or when <code>rr="BN"</code>.</li>'."\n";
			echo '<li><code>tt="D"</code> Trial Term. Only valid w/ Membership Level Access. Possible values: <code>D</code> = Days, <code>W</code> = Weeks, <code>M</code> = Months, <code>Y</code> = Years.</li>'."\n";
			echo '<li><code>unsub="0"</code> Unsubscribe user? Only valid when <code>cancel="1"</code>. Possible values: <code>0</code> = do NOT unsubscribe (wait until an EOT occurs), <code>1</code> = yes, unusbscribe user immediately; i.e., when they submit the cancellation form. <em>NOTE: Just to clarify, this pertains to List Servers that you\'ve configured with s2Member. Setting <code>unsub="1"</code> will cause the user to be unsubscribed from a mailing list (if they are currently subscribed).</em></li>'."\n";
			echo '<li><code>update="0"</code> Billing Update Form. Only valid w/ Membership Level Access. Possible values: <code>0</code> = this is NOT a Billing Update Form, <code>1</code> = this IS a Billing Update Form.</li>'."\n";
			echo '</ul>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Wrapping Multiple Shortcodes as Checkout Options">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-form-options-section">'."\n";
			echo '<h3>Wrapping Multiple Shortcodes as "Checkout Options"</h3>'."\n";
			echo '<p>If you would like to offer a single Pro-Form w/ multiple "Checkout Options", it\'s quite easy. Generate each of your Pro-Form Shortcodes the same as you normally would (using some of the Pro-Form Generators on this page). Then, you can simply wrap them all inside another Pro-Form Shortcode (as seen below). For instance, if you generate two Pro-Form Shortcodes (or you have multiple Pro-Form Shortcodes on-site already); you can simply take those and wrap them inside another Pro-Form Shortcode and it consolidates all the Pro-Form Shortcodes into a single Pro-Form with multiple "Checkout Options" (i.e., it creates a drop-down menu for your customers to choose from). The following is a VERY simple example.</p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<pre class="code">';
			echo '<code>';
			echo '[s2Member-Pro-PayPal-Form]'."\n";
			echo "\t".'[s2Member-Pro-PayPal-Form /]'."\n";
			echo "\t".'[s2Member-Pro-PayPal-Form /]'."\n";
			echo '[/s2Member-Pro-PayPal-Form]';
			echo '</code>';
			echo '</pre>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<h3>How do "Checkout Options" work behind-the-scenes?</h3>'."\n";
			echo '<p>Given this simple example (as seen below); s2Member will first take the primary default Shortcode Attributes (from the top-level parent Shortcode); and then it merges those together with Shortcode Attributes from a particular Checkout Option (i.e., a child). The one s2Member merges with is based on the currently selected Checkout Option (i.e., the Checkout Option selected by your customer).</p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<pre class="code">';
			echo '<code>';
			echo '[s2Member-Pro-PayPal-Form rp="1" rt="M" rr="1" accept_coupons="1"]'."\n";
			echo "\t".'[s2Member-Pro-PayPal-Form level="1" desc="Option 1 ($10.00)" ra="10.00" /]'."\n";
			echo "\t".'[s2Member-Pro-PayPal-Form level="2" desc="Option 2 ($20.00)" ra="20.00" /]'."\n";
			echo "\t".'[s2Member-Pro-PayPal-Form level="3" desc="Option 3 ($30.00)" ra="30.00" /]'."\n";
			echo '[/s2Member-Pro-PayPal-Form]';
			echo '</code>';
			echo '</pre>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<p style="font-style:italic;">In this example, the default checkout Level is 1 (because the default Checkout Option is always the first Checkout Option); but that can change depending on which Checkout Option is selected by the customer. All of these Checkout Options will be associated with different prices; and each Checkout Option will provide access to a different Membership Level. The customer may choose which one they would like to pay for and gain access to.</p>'."\n";
			echo '<p style="font-style:italic;">All of these Checkout Options will allow a Coupon Code; because that\'s an absolute default Shortcode Attribute (in this particular example); which is not overwritten by any of the children. All of these Checkout Options will charge the customer on a recurring basis (once each month); because those are absolute default Shortcode Attributes (in this particular example) i.e., <code>rp="1" rt="M" rr="1"</code>; which are not overwritten by any of the children.</p>'."\n";
			echo '<p style="font-style:italic;">~ You\'ll be happy to know that ANY Shortcode Attribute can be declared (and/or overridden); in any of these tags. There is no special subset of restricted Shortcode Attributes in any of these; you can do whatever you like once you understand how this works. In addition, this works for Specific Post/Page Access, Buy Now Access, Recurring Access; etc. You can even mix these together if you like. Just wrap all of your Pro-Form Shortcodes with another Pro-Form Shortcode :-)</p>'."\n";
			echo '</div>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<h3>Linking To A Pro-Form w/ Multiple "Checkout Options"</h3>'."\n";
			echo '<p>It is also possible to link to a Pro-Form and pre-select a specific Checkout Option that appears in the list. Starting from the first Checkout Option in the list (we call this Checkout Option 1) you can choose which Checkout Option number you want to have selected by default. This is accomplished by linking to any Post/Page on your site which contains a Pro-Form Shortcode; and then adding the <code>?s2p-option=</code> variable onto the end of that URL (as seen below).</p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";

			echo 'This example would pre-select option 2.'."\n";
			echo '<pre class="code">';
			echo '<code>';
			echo 'http://www.example.com/my-checkout-form/?s2p-option=2';
			echo '</code>';
			echo '</pre>'."\n";

			echo 'The absolute default Checkout Option is always the first one (Checkout Option 1).<br />'."\n";
			echo 'This would pre-select option 1 (but this is NOT necessary, because it\'s the default already).'."\n";
			echo '<pre class="code">';
			echo '<code>';
			echo 'http://www.example.com/my-checkout-form/?s2p-option=1';
			echo '</code>';
			echo '</pre>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

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

new c_ws_plugin__s2member_pro_menu_page_paypal_forms ();
