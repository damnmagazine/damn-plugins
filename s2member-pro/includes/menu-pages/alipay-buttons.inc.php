<?php
/**
 * Menu page for s2Member Pro (AliPay Buttons page).
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

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_alipay_buttons"))
{
	/**
	 * Menu page for s2Member Pro (AliPay Buttons page).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110531
	 */
	class c_ws_plugin__s2member_pro_menu_page_alipay_buttons
	{
		public function __construct()
		{
			echo '<div class="wrap ws-menu-page">'."\n";

			echo '<div class="ws-menu-page-toolbox">'."\n";
			c_ws_plugin__s2member_menu_pages_tb::display();
			echo '</div>'."\n";

			echo '<h2>AliPay Buttons</h2>'."\n";

			echo '<table class="ws-menu-page-table">'."\n";
			echo '<tbody class="ws-menu-page-table-tbody">'."\n";
			echo '<tr class="ws-menu-page-table-tr">'."\n";
			echo '<td class="ws-menu-page-table-l">'."\n";

			for($n = 1; $n <= $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"]; $n++)
			{
				echo '<div class="ws-menu-page-group" title="Buttons For Level #'.$n.' Access">'."\n";

				echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-level'.$n.'-buttons-section">'."\n";
				echo '<h3>Button Code Generator For Level #'.$n.' Access</h3>'."\n";
				echo '<p>Very simple. All you do is customize the form fields provided, for each Membership Level that you plan to offer. Then press (Generate Button Code). These special AliPay Buttons are customized to work with s2Member seamlessly. Member accounts will be activated instantly, in an automated fashion. s2Member makes extensive use of the AliPay IPN service. s2Member receives updates from AliPay behind-the-scene. <em><strong>Please note:</strong> buttons are NOT saved here. This is only a Button Generator. Once you\'ve generated your Button, copy/paste it into your Membership Options Page. If you lose your Button Code, you\'ll need to come back &amp; re-generate a new one.</em></p>'."\n";

				echo '<table class="form-table">'."\n";
				echo '<tbody>'."\n";
				echo '<tr>'."\n";

				echo '<th class="ws-menu-page-th-side">'."\n";
				echo '<label for="ws-plugin--s2member-pro-level'.$n.'-shortcode">'."\n";
				echo 'Button Code<br />For Level #'.$n.':<br /><br />'."\n";
				echo '</label>'."\n";
				echo '</th>'."\n";

				echo '<td>'."\n";
				echo '<form onsubmit="return false;" autocomplete="off">'."\n";
				echo '<p>I want to charge: $<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-level'.$n.'-amount" value="0.01" size="4" /> / <select id="ws-plugin--s2member-pro-level'.$n.'-term">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/alipay-membership-regular-terms.php"))).'</select></p>'."\n";
				echo '<p>Description: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-level'.$n.'-desc" value="'.format_to_edit($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["level".$n."_label"]).' / description and pricing details here." size="73" /></p>'."\n";
				echo '<p'.((is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) ? ' style="display:none;"' : '').'>Custom Capabilities (comma-delimited) <a href="#" onclick="alert(\'Optional. This is VERY advanced.\\nSee: s2Member → API Scripting → Custom Capabilities.\\n\\n*ADVANCED TIP: You can specifiy a list of Custom Capabilities that will be (Added) with this purchase. Or, you could tell s2Member to (Remove All) Custom Capabilities that may or may not already exist for a particular Member, and (Add) only the new ones that you specify. To do this, just start your list of Custom Capabilities with `-all`.\\n\\nSo instead of just (Adding) Custom Capabilities:\\nmusic,videos,archives,gifts\\n\\nYou could (Remove All) that may already exist, and then (Add) new ones:\\n-all,calendar,forums,tools\\n\\nOr to just (Remove All) and (Add) nothing:\\n-all\'); return false;" tabindex="-1">[?]</a> <input type="text" maxlength="125" autocomplete="off" id="ws-plugin--s2member-pro-level'.$n.'-ccaps" size="40" /></p>'."\n";
				echo '<p><input type="button" value="Generate Button Code" onclick="ws_plugin__s2member_pro_alipayButtonGenerate(\'level'.$n.'\');" /></p>'."\n";
				echo '</form>'."\n";
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<td colspan="2">'."\n";
				echo '<form onsubmit="return false;" autocomplete="off">'."\n";
				echo '<strong>WordPress Shortcode:</strong> (recommended for both the WordPress Visual &amp; HTML Editors)<br />'."\n";
				$ws_plugin__s2member_pro_temp_s = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/shortcodes/alipay-checkout-button-shortcode.php")));
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

			if(!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site())
			{
				echo '<div class="ws-menu-page-group" title="Capability (Buy Now) Buttons">'."\n";

				echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-ccap-buttons-section">'."\n";
				echo '<h3>Button Code Generator For Independent Custom Capabilities</h3>'."\n";
				echo '<p>This is VERY advanced. For further details, please check your Dashboard: <strong>s2Member → API Scripting → Custom Capabiities</strong>.</p>'."\n";
				echo '<p>With s2Member, you can sell one or more Custom Capabilities using Buy Now functionality, to "existing" Users/Members, regardless of which Membership Level they have on your site <em>(i.e., you could even sell Independent Custom Capabilities to Users at Membership Level #0, normally referred to as Free Subscribers, if you like)</em>. So this is quite flexible. Independent Custom Capabilities do NOT rely on any specific Membership Level. That\'s why s2Member refers to these as `Independent` Custom Capabilities, because you can sell Capabilities this way, through Buy Now functionality, and the Customer\'s Membership Level Access, along with any existing paid access they may already have with you, will remain completely unaffected.</p>'."\n";
				echo '<p>Independent Custom Capabilities are added to a Customer\'s account immediately after checkout, and the Customer will have the Custom Capabilities for as long as their Membership lasts, based on their primary Subscription with your site, and/or forever, if they have a Lifetime account with you. In other words, Independent Custom Capabilities will exist on the Customer\'s account forever, or until an EOT <em>(End Of Term)</em> occurs on their primary Subscription with you; in which case s2Member would demote or delete the Customer\'s account <em>(based on your EOT configuration)</em>, and all Custom Capabilities are removed as well.</p>'."\n";
				echo '<p>Very simple. All you do is customize the form fields provided, for each set of Custom Capabilities that you plan to sell. Then press (Generate Button Code). These special AliPay Buttons are customized to work with s2Member seamlessly. The Customer will be granted additional access to one or more Custom Capabilities that you specify; while the Customer\'s Membership Level Access and any existing paid Subscription they may already have with you, will remain completely unaffected.</p>'."\n";
				echo '<p><em><strong>Important Note:</strong> Independent Custom Capability Buttons should ONLY be displayed to existing Users/Members, and they MUST be logged-in, BEFORE clicking this Button. Otherwise, post-processing of their transaction will fail to recognize the Customer\'s existing account within WordPress. Please display this Button only to Users/Members that are already logged into their account (perhaps in your Login Welcome Page for s2Member), or in another location where you can be absolutely sure that a User/Member is logged in. s2Member\'s Simple Conditionals could also be used to ensure a User/Member is logged in, by wrapping your Shortcode within a Conditional test. For further details, please see: <strong>s2Member → API Scripting → Simple Conditionals</strong>.</em></p>'."\n";
				echo '<p><em><strong>Please note:</strong> buttons are NOT saved here. This is only a Button Generator. If you lose your Button Code, you\'ll need to come back &amp; re-generate a new one.</em></p>'."\n";

				echo '<table class="form-table">'."\n";
				echo '<tbody>'."\n";
				echo '<tr>'."\n";

				echo '<th class="ws-menu-page-th-side">'."\n";
				echo '<label for="ws-plugin--s2member-pro-ccap-shortcode">'."\n";
				echo 'Button Code<br />For Capabilities:<br /><br />'."\n";
				echo '</label>'."\n";
				echo '</th>'."\n";

				echo '<td>'."\n";
				echo '<form onsubmit="return false;" autocomplete="off">'."\n";
				echo '<p>I want to charge: $<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-ccap-amount" value="0.01" size="4" /> / <select id="ws-plugin--s2member-pro-ccap-term">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/alipay-membership-ccap-terms.php"))).'</select></p>'."\n";
				echo '<p>Description: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-ccap-desc" value="Description and pricing details here." size="73" /></p>'."\n";
				echo '<p>Custom Capabilities (comma-delimited) <a href="#" onclick="alert(\'Optional. This is VERY advanced.\\nSee: s2Member → API Scripting → Custom Capabilities.\\n\\n*ADVANCED TIP: You can specifiy a list of Custom Capabilities that will be (Added) with this purchase. Or, you could tell s2Member to (Remove All) Custom Capabilities that may or may not already exist for a particular Member, and (Add) only the new ones that you specify. To do this, just start your list of Custom Capabilities with `-all`.\\n\\nSo instead of just (Adding) Custom Capabilities:\\nmusic,videos,archives,gifts\\n\\nYou could (Remove All) that may already exist, and then (Add) new ones:\\n-all,calendar,forums,tools\\n\\nOr to just (Remove All) and (Add) nothing:\\n-all\'); return false;" tabindex="-1">[?]</a> <input type="text" maxlength="125" autocomplete="off" id="ws-plugin--s2member-pro-ccap-ccaps" size="40" /></p>'."\n";
				echo '<p><input type="button" value="Generate Button Code" onclick="ws_plugin__s2member_pro_alipayCcapButtonGenerate();" /></p>'."\n";
				echo '</form>'."\n";
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<td colspan="2">'."\n";
				echo '<form onsubmit="return false;" autocomplete="off">'."\n";
				echo '<strong>WordPress Shortcode:</strong> (recommended for both the WordPress Visual &amp; HTML Editors)<br />'."\n";
				$ws_plugin__s2member_pro_temp_s = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/shortcodes/alipay-ccaps-checkout-button-shortcode.php")));
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

			echo '<div class="ws-menu-page-group" title="Member Registration Access Links">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-reg-links-section">'."\n";
			echo '<h3>Registration Access Link Generator (for Customer Service)</h3>'."\n";
			echo '<p>s2Member automatically generates Registration Access Links for your Customers after checkout, and also sends them a link in a Confirmation Email. However, if you ever need to deal with a Customer Service issue that requires a new Registration Access Link to be created manually, you can use this tool for that. Alternatively, you can create their account yourself/manually by going to <strong>Users → Add New</strong>. Either of these methods will work fine.</p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<form onsubmit="return false;" autocomplete="off">'."\n";
			echo '<p>Paid Membership Level#: <select id="ws-plugin--s2member-pro-reg-link-level">'."\n";
			for($n = 1; $n <= $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"]; $n++)
				echo '<option value="'.$n.'">s2Member Level #'.$n.'</option>'."\n";
			echo '</select></p>'."\n";
			echo '<p>Paid Subscr. ID: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-reg-link-subscr-id" value="" size="50" /> <a href="#" onclick="alert(\'The Customer\\\'s Paid Subscr. ID ( aka: AliPay Trade No. ) must be unique. This value can be obtained from inside your AliPay account. Each paying Customer MUST be associated with a unique Paid Subscr. ID. If the Customer is NOT associated with a Paid Subscr. ID, you will need to generate a unique value for this field on your own. But keep in mind, s2Member will be unable to maintain future communication with the AliPay IPN (i.e., Notification) service if this value does not reflect a real Paid Subscr. ID that exists in your AliPay transaction log.\'); return false;" tabindex="-1">[?]</a></p>'."\n";
			echo '<p>Custom String Value: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-reg-link-custom" value="'.esc_attr($_SERVER["HTTP_HOST"]).'" size="30" /> <a href="#" onclick="alert(\'A Paid Subscription is always associated with a Custom String that is passed through the custom=\\\'\\\''.c_ws_plugin__s2member_utils_strings::esc_js_sq(esc_attr($_SERVER["HTTP_HOST"]), 3).'\\\'\\\' attribute of your Shortcode. This Custom Value, MUST always start with your domain name. However, you can also pipe delimit additional values after your domain, if you need to.\\n\\nFor example:\n'.c_ws_plugin__s2member_utils_strings::esc_js_sq(esc_attr($_SERVER["HTTP_HOST"]), 3).'|cv1|cv2|cv3\'); return false;" tabindex="-1">[?]</a> <input type="button" value="Generate Access Link" onclick="ws_plugin__s2member_pro_alipayRegLinkGenerate();" /> <img id="ws-plugin--s2member-pro-reg-link-loading" src="'.esc_attr($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["dir_url"]).'/images/ajax-loader.gif" alt="" style="display:none;" /></p>'."\n";
			echo '<p'.((is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) ? ' style="display:none;"' : '').'>Custom Capabilities (comma-delimited) <a href="#" onclick="alert(\'Optional. This is VERY advanced.\\nSee: s2Member → API Scripting → Custom Capabilities.\'); return false;" tabindex="-1">[?]</a> <input type="text" maxlength="125" autocomplete="off" id="ws-plugin--s2member-pro-reg-link-ccaps" size="40" onkeyup="if(this.value.match(/[^a-z_0-9,]/)) this.value = jQuery.trim (jQuery.trim (this.value).replace (/[ \-]/g, \'_\').replace (/[^a-z_0-9,]/gi, \'\').toLowerCase ());" /></p>'."\n";
			echo '<p>Fixed Term Length (for Buy Now transactions): <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-reg-link-fixed-term" value="" size="10" /> <a href="#" onclick="alert(\'You may configure a Fixed Term Length in this field. This way the Customer\\\'s Membership Access is automatically revoked by s2Member at the appropriate time. This will be a numeric value, followed by a space, then a single letter.\\n\\nHere are some examples:\\n\\n1 D (this means 1 Day)\\n1 W (this means 1 Week)\\n1 M (this means 1 Month)\\n1 Y (this means 1 Year)\\n1 L (this means 1 Lifetime)\'); return false;">[?]</a></p>'."\n";
			echo '<p id="ws-plugin--s2member-pro-reg-link" class="monospace" style="display:none;"></p>'."\n";
			echo '</form>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Specific Post/Page (Buy Now) Buttons">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-sp-buttons-section">'."\n";
			echo '<h3>Button Code Generator For Specific Post/Page Buttons</h3>'."\n";
			echo '<p>s2Member now supports an additional layer of functionality (very powerful), which allows you to sell access to specific Posts/Pages that you\'ve created in WordPress. Specific Post/Page Access works independently from Member Level Access. That is, you can sell an unlimited number of Posts/Pages using "Buy Now" Buttons, and your Customers will NOT be required to have a Membership Account with your site in order to receive access. If they are already a Member, that\'s fine, but they won\'t need to be.</p>'."\n";
			echo '<p>In other words, Customers will NOT need to login, just to receive access to the Specific Post/Page they purchased access to. s2Member will immediately redirect the Customer to the Specific Post/Page after checkout is completed successfully. An email is also sent to the Customer with a link (see: <strong>s2Member → AliPay Options → Specific Post/Page Email</strong>). Authentication is handled automatically through self-expiring links, good for 72 hours by default.</p>'."\n";
			echo '<p>Specific Post/Page Access, is sort of like selling a product. Only, instead of shipping anything to the Customer, you just give them access to a specific Post/Page on your site; one that you created in WordPress. A Specific Post/Page that is protected by s2Member, might contain a download link for your eBook, access to file &amp; music downloads, access to additional support services, and the list goes on and on. The possibilities with this are endless; as long as your digital product can be delivered through access to a WordPress Post/Page that you\'ve created. To protect Specific Posts/Pages, please see: <strong>s2Member → Restriction Options → Specific Post/Page Access</strong>. Once you\'ve configured your Specific Post/Page Restrictions, those Posts/Pages will be available in the menus below.</p>'."\n";
			echo '<p>Very simple. All you do is customize the form fields provided, for each Post/Page that you plan to sell. Then press (Generate Button Code). These special AliPay Buttons are customized to work with s2Member seamlessly. You can even Package Additional Posts/Pages together into one transaction. <em><strong>Please note:</strong> buttons are NOT saved here. This is only a Button Generator. Once you\'ve generated your Button, copy/paste it into your WordPress Editor, wherever you feel it would be most appropriate. If you lose your Button Code, you\'ll need to come back &amp; re-generate a new one.</em></p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th class="ws-menu-page-th-side">'."\n";
			echo '<label for="ws-plugin--s2member-pro-sp-shortcode">'."\n";
			echo 'Button Code<br />Specific Posts/Pages:<br /><br />'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

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

			echo '<p>I want to charge: $<input type="text" autocomplete="off" id="ws-plugin--s2member-pro-sp-amount" value="0.01" size="4" /> / <select id="ws-plugin--s2member-pro-sp-hours">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/alipay-sp-hours.php"))).'</select></p>'."\n";
			echo '<p>Description: <input type="text" autocomplete="off" id="ws-plugin--s2member-pro-sp-desc" value="Description and pricing details here." size="67" /></p>'."\n";
			echo '<p><input type="button" value="Generate Button Code" onclick="ws_plugin__s2member_pro_alipaySpButtonGenerate();" /></p>'."\n";
			echo '</form>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td colspan="2">'."\n";
			echo '<form onsubmit="return false;" autocomplete="off">'."\n";
			echo '<strong>WordPress Shortcode:</strong> (recommended for both the WordPress Visual &amp; HTML Editors)<br />'."\n";
			$ws_plugin__s2member_pro_temp_s = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/shortcodes/alipay-sp-checkout-button-shortcode.php")));
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

			echo '<p><select id="ws-plugin--s2member-pro-sp-link-hours">'.trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(__FILE__))."/templates/options/alipay-sp-hours.php"))).'</select> <input type="button" value="Generate Access Link" onclick="ws_plugin__s2member_pro_alipaySpLinkGenerate();" /> <img id="ws-plugin--s2member-pro-sp-link-loading" src="'.esc_attr($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["dir_url"]).'/images/ajax-loader.gif" alt="" style="display:none;" /></p>'."\n";
			echo '<p id="ws-plugin--s2member-pro-sp-link" class="monospace" style="display:none;"></p>'."\n";
			echo '</form>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Shortcode Attributes (Explained)">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-shortcode-attrs-section">'."\n";
			echo '<h3>Shortcode Attributes (Explained In Full Detail)</h3>'."\n";
			echo '<p>When you generate a Button Code, s2Member will make a <a href="http://s2member.com/r/shortcode-reference/" target="_blank" rel="external">Shortcode</a> available to you. Like most Shortcodes for WordPress, s2Member reads Attributes in your Shortcode. These Attributes will be pre-configured by one of s2Member\'s Button Generators automatically; so there really is nothing more you need to do. However, many site owners like to know exactly how these Shortcode Attributes work. Below, is a brief overview of each possible Shortcode Attribute.</p>'."\n";

			echo '<table class="form-table" style="margin-top:0;">'."\n";
			echo '<tbody>'."\n";
			echo '<tr style="padding-top:0;">'."\n";

			echo '<td style="padding-top:0;">'."\n";
			echo '<ul class="ws-menu-page-li-margins">'."\n";
			echo (!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? '<li><code>ccaps="music,videos"</code> A comma-delimited list of Custom Capabilities. Only valid w/ Membership Level Access and/or Independent Custom Capabilities.</li>'."\n" : '';
			echo '<li><code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'"</code> must start with your domain. Additional values can be piped in (ex: <code>custom="'.esc_html($_SERVER["HTTP_HOST"]).'|cv1|cv2|cv3|etc"</code>).</li>'."\n";
			echo '<li><code>desc="Gold Membership"</code> A brief purchase Description; which may also include pricing details.</li>'."\n";
			echo '<li><code>exp="72"</code> Access Expires (in hours). Only valid when <code>sp="1"</code> for Specific Post/Page Access.</li>'."\n";
			echo '<li><code>ids="14"</code> A Post/Page ID#, or a comma-delimited list of IDs. Only valid when <code>sp="1"</code> for Specific Post/Page Access.</li>'."\n";
			echo '<li><code>image="default"</code> Button Image Location. Possible values: <code>default</code> = use the default AliPay Button, <code>http://...</code> = location of your custom Image.</li>'."\n";
			echo '<li><code>level="1"</code> Membership Level [1-4] <em>(or, up to the number of configured Levels)</em>. Only valid for Buttons providing paid Membership Level Access.'.((is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) ? '' : ' Or, with Independent Custom Capabilities this MUST be set to <code>level="*"</code>, and <code>ccaps=""</code> must NOT be empty <em>(i.e., <code>level="*" ccaps="music,videos"</code>)</em>.').'</li>'."\n";
			echo '<li><code>output="anchor"</code> Output Type. Possible values: <code>anchor</code> = AliPay Button (  &lt;a&gt; anchor tag ) URL w/ ?query string, <code>url</code> = raw URL w/ ?query string.</li>'."\n";
			echo '<li><code>ra="0.01"</code> Regular / Buy Now Amount. Must be &gt;= <code>0.01</code>.</li>'."\n";
			echo '<li><code>rp="1"</code> Regular Period. Only valid w/ Membership Level Access'.((is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) ? '' : ' and/or Independent Custom Capabilities').'. Must be &gt;= <code>1</code> (ex: <code>1</code> Week, <code>2</code> Months, <code>1</code> Month, <code>3</code> Days).</li>'."\n";
			echo '<li><code>rt="M"</code> Regular Term. Only valid w/ Membership Level Access'.((is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) ? '' : ' and/or Independent Custom Capabilities').'. Possible values: <code>D</code> = Days, <code>W</code> = Weeks, <code>M</code> = Months, <code>Y</code> = Years, <code>L</code> = Lifetime.</li>'."\n";
			echo '<li><code>sp="0"</code> Specific Post/Page Button. Possible values: <code>0</code> = this is NOT a Specific Post/Page Access Button, <code>1</code> = this IS a Specific Post/Page Access Button.</li>'."\n";
			echo '<li><code>success=""</code> Success Return URL <em>(optional)</em>. s2Member handles this automatically for you. However, if you would prefer to take control over the landing page after checkout <em>(i.e., your own custom Thank-You Page)</em>, you can. If supplied, this must be a full URL, starting with <code>http://</code>. Note, s2Member will NOT use this value if an existing account holder is being modified. s2Member handles account updates <em>(i.e., billing modification)</em> in a more dynamic way. Your Success Return URL is only applied to (new) Customers.</li>'."\n";
			echo '</ul>'."\n";
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

new c_ws_plugin__s2member_pro_menu_page_alipay_buttons ();
