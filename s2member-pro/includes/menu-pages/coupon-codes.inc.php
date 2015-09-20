<?php
/**
 * Menu page for s2Member Pro (Coupon Codes page).
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

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_coupon_codes"))
{
	/**
	 * Menu page for s2Member Pro (Coupon Codes page).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110531
	 */
	class c_ws_plugin__s2member_pro_menu_page_coupon_codes
	{
		public function __construct()
		{
			echo '<div class="wrap ws-menu-page">'."\n";

			echo '<div class="ws-menu-page-toolbox">'."\n";
			c_ws_plugin__s2member_menu_pages_tb::display();
			echo '</div>'."\n";

			echo '<h2>Coupon Codes</h2>'."\n";

			echo '<table class="ws-menu-page-table">'."\n";
			echo '<tbody class="ws-menu-page-table-tbody">'."\n";
			echo '<tr class="ws-menu-page-table-tr">'."\n";
			echo '<td class="ws-menu-page-table-l">'."\n";

			echo '<form method="post" name="ws_plugin__s2member_pro_options_form" id="ws-plugin--s2member-pro-options-form" autocomplete="off">'."\n";
			echo '<input type="hidden" name="ws_plugin__s2member_options_save" id="ws-plugin--s2member-options-save" value="'.esc_attr(wp_create_nonce("ws-plugin--s2member-options-save")).'" />'."\n";

			echo '<div class="ws-menu-page-group" title="Pro-Form Coupon Code Configuration">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-coupon-codes-section">'."\n";
			echo '<h3>Coupon Code Configuration File (optional, to provide discounts)</h3>'."\n";
			echo '<p>Coupons are compatible with Pro-Forms for Stripe, PayPal Pro and Authorize.Net. Coupons allow you to provide discounts through a special promotion. A Customer may enter a Coupon Code at checkout, and depending on the Code they enter, a discount may be applied <em>(based on your configuration below)</em>.</p>'."\n";
			echo '<p>You can have an unlimited number of Coupon Codes. Coupon Codes can be configured to provide a flat-rate discount, or a percentage-based discount. It is possible to force specific Coupon Codes to expire automatically, on a particular date in the future. It is possible to specify which charge(s) a specific Coupon Code applies to <em>(e.g., the Initial/Trial Amount only, the Regular Amount only, or both; including all Recurring fees)</em>. In addition, it is also possible to limit the use of a particular Coupon Code to a particular Post or Page ID; where a particular Pro-Form Shortcode is made available to Customers. You\'ll find several configuration examples below.</p>'."\n";

			if(in_array('stripe', $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_gateways_enabled']))
			{
				echo '<div class="info">'."\n";
				echo '<p><strong>* Stripe:</strong> Coupons <em>must</em> be configured with s2Member and <strong>not via the Stripe Dashboard</strong>. s2Member Pro-Forms currently do <em>not</em> support Stripe-generated coupon codes; only those configured here with s2Member.</p>'."\n";
				echo '</div>'."\n";
			}
			echo '<p><strong>Required Shortcode Attribute:</strong> In order to display a "Coupon Code" field on your Checkout Form you <em>must</em> add this special Shortcode Attribute to your s2Member Pro-Form Shortcode(s): <code>accept_coupons="1"</code>. If you would like to force-feed a default Coupon Code <em>(optional)</em>, you can add this special Shortcode attribute: <code>coupon="[your default code]"</code>. Or, instead of <code>coupon="[your default code]"</code>, you could pass <code>?s2p-coupon=[your default code]</code> in the query string of a URL leading to a Checkout Form.</p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<table class="coupons-table">'."\n";
			echo '<thead>'."\n";
			echo '<tr>'."\n";
			echo '<th class="-code"><i class="fa fa-barcode"></i> Code</td>'."\n";
			echo '<th class="-discount"><i class="fa fa-cart-arrow-down"></i> Discount</td>'."\n";
			echo '<th class="-active_time"><i class="fa fa-calendar"></i> Active</td>'."\n";
			echo '<th class="-expires_time"><i class="fa fa-clock-o"></i> Expires</td>'."\n";
			echo '<th class="-directive"><i class="fa fa-cog"></i> Directive</td>'."\n";
			echo '<th class="-singulars"><i class="fa fa-sitemap"></i> Post IDs</td>'."\n";
			echo '<th class="-users"><i class="fa fa-users"></i> User IDs</td>'."\n";
			echo '<th class="-max_uses"><i class="fa fa-gavel"></i> Max Uses</td>'."\n";
			echo '<th class="-uses"><i class="fa fa-line-chart"></i> Uses</td>'."\n";
			echo '<th class="-actions">&nbsp;</td>'."\n";
			echo '</tr>'."\n";
			echo '</thead>'."\n";
			echo '<tbody>'."\n";
			$_coupons = new c_ws_plugin__s2member_pro_coupons();
			foreach($_coupons->coupons as $_coupon)
			{
				echo '<tr>'."\n";
				echo '<td class="-code"><input type="text" spellcheck="false" value="'.esc_attr($_coupon['code']).'" /></td>'."\n";
				echo '<td class="-discount"><input type="text" spellcheck="false" value="'.esc_attr($_coupon['discount'] ? $_coupon['discount'] : '').'" /></td>'."\n";
				echo '<td class="-active_time"><input type="text" spellcheck="false" value="'.esc_attr($_coupon['active_time'] ? date('m/d/Y', $_coupon['active_time']) : '').'" /></td>'."\n";
				echo '<td class="-expires_time"><input type="text" spellcheck="false" value="'.esc_attr($_coupon['expires_time'] ? date('m/d/Y', $_coupon['expires_time']) : '').'" /></td>'."\n";
				echo '<td class="-directive"><input type="text" spellcheck="false" value="'.esc_attr($_coupon['directive'] ? $_coupon['directive'] : '').'" /></td>'."\n";
				echo '<td class="-singulars"><input type="text" spellcheck="false" value="'.esc_attr($_coupon['singulars'] ? implode(',', $_coupon['singulars']) : '').'" /></td>'."\n";
				echo '<td class="-users"><input type="text" spellcheck="false" value="'.esc_attr($_coupon['users'] ? implode(',', $_coupon['users']) : '').'" /></td>'."\n";
				echo '<td class="-max_uses"><input type="text" spellcheck="false" value="'.esc_attr($_coupon['max_uses'] ? $_coupon['max_uses'] : '').'" /></td>'."\n";
				echo '<td class="-uses"><input type="text" spellcheck="false" value="'.esc_attr($_coupons->get_uses($_coupon['code'])).'" /></td>'."\n";
				echo '<td class="-actions"><a href="#" class="-up" title="Move Up" tabindex="-1"><i class="fa fa-chevron-circle-up"></i></a><a href="#" class="-down" title="Move Down" tabindex="-1"><i class="fa fa-chevron-circle-down"></i></a><a href="#" class="-delete" title="Delete" tabindex="-1"><i class="fa fa-times-circle"></i></a></td>'."\n";
				echo '</tr>'."\n";
			}
			unset($_coupons, $_coupon); // Housekeeping.
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div><input type="hidden" name="ws_plugin__s2member_pro_coupon_codes" id="ws-plugin--s2member-pro-coupon-codes" value="" />'."\n";
			echo '<div class="coupon-add"><a href="#" title="Add Coupon"><i class="fa fa-plus-square"></i> Add Coupon</a></div>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<h3>Legend for the Above Configuration Fields</h3>'."\n";

			echo '<ul style="list-style:none; margin:0; padding:0;">'."\n"; // Explaining Coupon Codes by example.
			echo '<li style="list-style:none;"><strong><i class="fa fa-barcode"></i> Code</strong>&nbsp;&nbsp;&nbsp; e.g., <code>SAVE-100</code>, <code>$100OFF</code>, <code>XMAS!!</code> <em>This is what a customer will enter to acquire a discount. It may contain any sequence of characters (or symbols) that you like. Coupon Codes are not caSe-sensitive here, and they are not caSe-sensitive when a customer enter them either. <strong>Tip:</strong> it is recommended that you refrain from using complex Coupon Codes that are difficult for a customer to enter during checkout.</em></li>'."\n";
			echo '<li style="list-style:none;margin-top:1em;"><strong><i class="fa fa-cart-arrow-down"></i> Discount</strong>&nbsp;&nbsp;&nbsp; e.g., <code>100.00</code> (flat-rate) or <code>100%</code> (percentage-based) <em>How much of a discount will you offer?</em></li>'."\n";
			echo '<li style="list-style:none;margin-top:1em;"><strong><i class="fa fa-calendar"></i> Active</strong>&nbsp;&nbsp;&nbsp; e.g., <code>12/01/2020</code> <em>When will the coupon become active? If empty, it becomes active immediately. If you fill this in, please use <code>MM/DD/YYYY</code> format.</em></li>'."\n";
			echo '<li style="list-style:none;margin-top:1em;"><strong><i class="fa fa-clock-o"></i> Expires</strong>&nbsp;&nbsp;&nbsp; e.g., <code>12/31/2020</code> <em>When will the coupon expire? If empty, it never expires. If you fill this in, please use <code>MM/DD/YYYY</code> format.</em></li>'."\n";
			echo '<li style="list-style:none;margin-top:1em;"><strong><i class="fa fa-cog"></i> Directive</strong>&nbsp;&nbsp;&nbsp; e.g., <code>ta-only</code> or <code>ra-only</code> <em>By default (i.e., if this is empty), s2Member will apply the discount to all amounts, including any Regular/Recurring fees. However, you may configure Coupon Codes that will only apply to (ta) Trial Amounts, or (ra) Regular Amounts. If this is empty, the discount applies to all amounts, including any Regular/Recurring fees.</em></li>'."\n";
			echo '<li style="list-style:none;margin-top:1em;"><strong><i class="fa fa-sitemap"></i> Post IDs</strong>&nbsp;&nbsp;&nbsp; e.g., <code>123</code> or <code>1,9983,223</code> <em>By default (i.e., if this is empty), s2Member accepts Coupon Codes on any Pro-Form with Shortcode Attribute: <code>accept_coupons="1"</code>. However, you may configure Coupon Codes that only work on specific Post or Page IDs. This can be entered as a single Post/Page ID, or as a comma-delimited list of multiple IDs.</em></li>'."\n";
			echo '<li style="list-style:none;margin-top:1em;"><strong><i class="fa fa-users"></i> User IDs</strong>&nbsp;&nbsp;&nbsp; e.g., <code>456</code> or <code>8,673,93</code> <em>By default (i.e., if this is empty), s2Member accepts Coupon Codes from any User, whether they are logged-in or not. However, you may configure Coupon Codes that only work for specific WordPress User IDs. This can be entered as a single WordPress User ID, or as a comma-delimited list of multiple IDs. <strong>Note:</strong> adding this limitation requires that a user be logged-in when they use the Coupon Code.</em></li>'."\n";
			echo '<li style="list-style:none;margin-top:1em;"><strong><i class="fa fa-gavel"></i> Max Uses</strong>&nbsp;&nbsp;&nbsp; e.g., <code>1000</code> <em>By default (i.e., if this is empty), a Coupon Code can be used any number of times, or until it expires. However, you can set a limit on the number of times that a Coupon Code can be used to complete checkout by any number of customers overall. If you fill this in, when the limit is reached, because X number of customers used the Coupon Code to complete checkout, the Coupon will automatically expire. <strong>Note:</strong> setting this to <code>0</code> is the same as leaving it empty; i.e., if you set this to <code>0</code>, the Coupon Code can be used any number of times, or until it expires. No difference.</em></li>'."\n";
			echo '<li style="list-style:none;margin-top:1em;"><strong><i class="fa fa-line-chart"></i> Uses (Counter)</strong>&nbsp;&nbsp;&nbsp; e.g., <code>0</code> <em>s2Member updates this field automatically, incrementing it by <code>1</code> each time the Coupon Code is used to complete checkout. However, you can reset this to <code>0</code> (or any other number) if you so desire.</em></li>'."\n";
			echo '</ul>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<p>'."\n";
			echo '<em><strong>REMINDER:</strong> you need a Pro-Form with Shortcode Attribute: <code>accept_coupons="1"</code></em><br />'."\n";
			echo '<em>* s2Member ONLY accepts Coupons on Pro-Forms with Shortcode Attribute: <code>accept_coupons="1"</code></em>'."\n";
			echo '</p>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<p>'."\n";
			echo '<em><strong>TIP:</strong> your changes to the configuration above (including deletions) will not take affect until you click <strong>Save All Changes</strong> down below.</em>'."\n";
			echo '</p>'."\n";

			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Pro-Form Affiliate Coupon Codes">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-affiliate-coupon-codes-section">'."\n";
			echo '<h3>Affiliate Coupon Codes (optional, for affiliate tracking systems)</h3>'."\n";
			echo '<p>Coupons are compatible with Pro-Forms for Stripe, PayPal Pro and Authorize.Net. Coupon Codes allow you to provide discounts through a special promotion. <strong>Affiliate Coupon Codes</strong> make it possible for your affiliates to receive credit for sales they refer, using one of your Coupon Codes <em>(which you may have configured in the section above)</em>.</p>'."\n";
			echo '<p>Here\'s how it works. You tell your affiliates about one or more of the Coupon Codes that you accept <em>(which you may have configured in the section above)</em>. Each of your affiliates can add their affiliate ID onto the end of any valid Coupon Code, like this: <code>COUPON-CODE'.esc_html($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_affiliate_coupon_code_suffix_chars"]).'123</code>; where <code>COUPON-CODE</code> is the valid Coupon Code that you\'ve configured, and <code>123</code> is the affiliate\'s ID <em>(see also, Suffix Chars below)</em>. If a Customer comes to your site, and they use a Coupon Code with an affiliate ID on the end of it; your affiliate will be <strong>tracked</strong> automatically by s2Member. If this Customer actually completes the sale, the referring affiliate will be credited with whatever commission your affiliate program offers.</p>'."\n";
			echo '<p><strong>Prerequisites.</strong> Your affiliate tracking system MUST provide you with a Tracking URL that s2Member can connect to silently behind-the-scenes <em>(sometimes referred to as a cURL Tracking Logger)</em>. The Standard edition of <a href="http://www.s2member.com/idev-affiliate" target="_blank" rel="external">iDevAffiliate</a> meets this requirement, and it\'s our recommendation to use the cURL Tracking Logger provided by iDevAffiliate. Or, if you\'re using another affiliate system that offers a URL s2Member can load as a 1px IMG in the Customer\'s browser after a Coupon Code is applied, that\'s fine too. In either case, this URL should ONLY <strong>track</strong> a potential Customer upon entering a Coupon Code, and NOT credit an affiliate with a commission. Credit is given to an affiliate through other forms of integration which you may or may not have configured yet. Please see: <strong>s2Member → API Tracking</strong>.</p>'."\n";
			echo '<p><strong><a href="http://www.s2member.com/idev-affiliate" target="_blank" rel="external">iDevAffiliate</a> <em>(recommended)</em>:</strong> You can obtain an Affiliate Tracking URL inside your iDevAffiliate dashboard. See: <strong>iDevAffiliate → Setup &amp; Tools → Advanced Developer Tools → Custom Functions → cURL Tracking Log</strong>. s2Member only needs the Tracking URL itself <em>(and NOT all of the PHP code that iDevAffiliate provides)</em>. Your iDevAffiliate Tracking URL <em>( including the <code>SILENT-PHP|</code> prefix described below)</em> should contain s2Member\'s <code><em class="ws-menu-page-hilite">%%</em></code> Replacement Codes, like this: <code>SILENT-PHP|http://example.com/idevaffiliate.php?ip_address=<em class="ws-menu-page-hilite">%%user_ip%%</em>&id=<em class="ws-menu-page-hilite">%%coupon_affiliate_id%%</em></code></p>'."\n";
			echo '<p><strong><a href="http://www.s2member.com/shareasale" target="_blank" rel="external">ShareASale</a>:</strong> Use this ShareASale URL <em>(including the <code>IMG-1PX|</code> prefix described below)</em>, and modify it just a bit to match your product: <code>IMG-1PX|https://www.shareasale.com/r.cfm?u=<em class="ws-menu-page-hilite">%%coupon_affiliate_id%%</em>&b=<em class="ws-menu-page-hilite">BBBBBB</em>&m=<em class="ws-menu-page-hilite">MMMMMM</em>&urllink=about%3Ablank&afftrack=<em class="ws-menu-page-hilite">%%full_coupon_code%%</em></code>. Be sure to replace <code><em class="ws-menu-page-hilite">BBBBBB</em></code> with a specific ShareASale Banner/Creative ID that you make available to your affiliates. Replace <code><em class="ws-menu-page-hilite">MMMMMM</em></code> with your ShareASale Merchant ID. The other <code><em class="ws-menu-page-hilite">%%</em></code> Replacement Codes should remain as they are, these are documented below.</p>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-affiliate-coupon-code-suffix-chars">'."\n";
			echo 'Affiliate Suffix Chars (indicating an Affiliate ID):'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_affiliate_coupon_code_suffix_chars" id="ws-plugin--s2member-pro-affiliate-coupon-code-suffix-chars" value="'.format_to_edit($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_affiliate_coupon_code_suffix_chars"]).'" /><br />'."\n";
			echo 'Characters that s2Member will use to identify an Affiliate\'s ID in Coupon Codes.<br />'."\n";
			echo '<em>Example: <code>COUPON-CODE'.esc_html($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_affiliate_coupon_code_suffix_chars"]).'123</code>. Coupon Code is <code>COUPON-CODE</code>. Affiliate ID is <code>123</code>.</em>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-affiliate-coupon-code-tracking-urls">'."\n";
			echo 'Affiliate Tracking URLs (one per line):'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo 'You can input multiple Tracking URLs by inserting one per line.<br />'."\n";
			echo 'Each line must include a prefix. One of: <code>SILENT-PHP|</code> or <code>IMG-1PX|</code> (details below)<br />'."\n";
			echo '<textarea name="ws_plugin__s2member_pro_affiliate_coupon_code_tracking_urls" id="ws-plugin--s2member-pro-affiliate-coupon-code-tracking-urls" rows="3" wrap="off">'.format_to_edit($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_affiliate_coupon_code_tracking_urls"]).'</textarea><br />'."\n";
			echo '<em>These are ONLY for <strong>tracking</strong> a potential Customer via Affiliate Coupon Codes. You\'re NOT crediting affiliates.</em><br />'."\n";
			echo '<em>To actually credit your affiliates, please check your Dashboard here: <strong>s2Member → API / Tracking (or API / Notifications)</strong>.</em><br /><br />'."\n";
			echo '<strong>You can use these special Replacement Codes in your Tracking URL(s), as needed.</strong>'."\n";
			echo '<ul>'."\n";
			echo '<li><code>%%full_coupon_code%%</code> = The full Affiliate Coupon Code accepted by s2Member.</li>'."\n";
			echo '<li><code>%%coupon_code%%</code> = The actual Coupon Code accepted by your configuration of s2Member.</li>'."\n";
			echo '<li><code>%%coupon_affiliate_id%%</code> = This is the end of the Affiliate Coupon Code <em>(i.e., the referring affiliate\'s ID)</em>.</li>'."\n";
			echo '<li><code>%%affiliate_id%%</code> = Deprecated. This will be removed in a future release. Please use <code>%%coupon_affiliate_id%%</code> instead.</li>'."\n";
			echo '<li><code>%%user_ip%%</code> = The Customer\'s IP Address, detected during checkout via <code>$_SERVER["REMOTE_ADDR"]</code>.</li>'."\n";
			echo '</ul>'."\n";
			echo '<strong>Each Tracking URL must include a prefix. One of: <code>SILENT-PHP|</code> or <code>IMG-1PX|</code></strong>'."\n";
			echo '<ul>'."\n";
			echo '<li><code>SILENT-PHP|URL goes here</code> = Coupon Code Tracking for a potential Customer takes place silently behind-the-scenes via PHP, using an HTTP connection. This method is the most reliable. This method requires an affiliate tracking system like <a href="http://www.s2member.com/idev-affiliate" target="_blank" rel="external">iDevAffiliate</a>, that can track by IP address only, when it needs to. If you\'re running iDevAffiliate, please see the example above.</li>'."\n";
			echo '<li><code>IMG-1PX|URL goes here</code> = Coupon Code Tracking takes place in a potential Customer\'s browser, through a 1px IMG tag, usually with Cookies set by your affiliate tracking system. This is the most compatible across various affiliate software applications. You give s2Member the Tracking URL, and s2Member will load the 1px IMG tag at the appropriate time.</li>'."\n";
			echo '</ul>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '<div class="ws-menu-page-group" title="Pro-Form Gift/Redemption Codes">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-gift-codes-section">'."\n";
			echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/T3N_vygowbM" frameborder="0" allowscriptaccess="always" allowfullscreen="true" style="float:right; margin:0 0 20px 20px; width:300px; height:200px;"></iframe>'."\n";
			echo '<h3>Gift/Redemption Codes (optional, for dynamic coupon code generation)</h3>'."\n";
			echo '<p>Coupons are compatible with Pro-Forms for Stripe, PayPal Pro and Authorize.Net. s2Member Pro also comes with a shortcode: <code>[s2Member-Gift-Codes /]</code>. This can be used to auto-generate Coupon Codes (aka: Gift Codes, aka: Redemption Codes). Auto-generated Gift/Redemption Codes are unique for each Post/Page ID in WordPress (i.e., the Post/Page that you insert <code>[s2Member-Gift-Codes /]</code> into). They are also unique for each user that gains access to that Post/Page. This makes it very easy for you to insert something like <code>[s2Member-Gift-Codes discount="100%" quantity="10" /]</code> into a Post or Page, and then sell access to that Post/Page with s2Member. In this way, you are selling a customer a set of 10 Gift/Redemption Codes that they can share with others in their group, or with a specific gift recipient they have in mind <i class="fa fa-smile-o"></i> For a full tutorial on using the Gift/Redemption Codes, please see the <a href="http://s2member.com/r/giftredemption-codes-video/" target="_blank">Video Tutorial</a> embedded to the right.</p>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<h3 style="margin-top:1em;"><code style="font-weight:400;">[s2Member-Gift-Codes /]</code> (Copy/Paste Examples)</h3>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<p style="margin:0;">Generates 10 Gift/Redemption Codes that provide a 100% discount:</p>'."\n";
			echo '<code class="highlight-shortcodes">[s2Member-Gift-Codes discount="100%" quantity="10" /]</code>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<p style="margin:0;">Generates 100 Gift/Redemption Codes that provide a $50 discount. Can only be redeemed from a Pro-Form on Page ID <code>334</code>:</p>'."\n";
			echo '<code class="highlight-shortcodes">[s2Member-Gift-Codes discount="50.00" quantity="100" singulars="334" /]</code>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<p style="margin:0;">Generates 1 Gift/Redemption Code that provides a $75 discount on an initial/trial period only.</p>'."\n";
			echo '<code class="highlight-shortcodes">[s2Member-Gift-Codes discount="75.00" quantity="1" directive="ta-only" /]</code>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<h3 style="margin-top:1em;"><code style="font-weight:400;">[s2Member-Gift-Codes /]</code> (Shortcode Attributes Explained)</h3>'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<ul style="list-style:none; margin:0; padding:0;">'."\n";
			echo '<li style="list-style:none;"><i class="fa fa-list-ol"></i> <code>quantity="1"</code> e.g., <code>quantity="10"</code>, this can be up to <code>1000</code>. <em>This is the number of Gift/Redemption Codes that should be generated and then listed on the Post/Page where the shortcode is being used.</em></li>'."\n";
			echo '<li style="list-style:none;margin-top:1em;"><i class="fa fa-cart-arrow-down"></i> <code>discount="100%"</code> e.g., <code>100.00</code> (flat-rate) or <code>100%</code> (percentage-based) <em>How much of a discount will each Gift/Redemption Code provide? This defaults to <code>100%</code> (most common use case).</em></li>'."\n";
			echo '<li style="list-style:none;margin-top:1em;"><i class="fa fa-cog"></i> <code>directive=""</code> e.g., <code>ta-only</code> or <code>ra-only</code> <em>By default (i.e., if this is empty), s2Member will apply the discount to all amounts, including any Regular/Recurring fees. However, you may configure Gift/Redemption Codes that will only apply to (ta) Trial Amounts, or (ra) Regular Amounts. If this is empty, the discount applies to all amounts, including any Regular/Recurring fees.</em></li>'."\n";
			echo '<li style="list-style:none;margin-top:1em;"><i class="fa fa-sitemap"></i> <code>singulars=""</code> e.g., <code>123</code> or <code>1,9983,223</code> <em>By default (i.e., if this is empty), s2Member accepts Gift/Redemption Codes on any Pro-Form with Shortcode Attribute: <code>accept_coupons="1"</code>. However, you may configure Gift/Redemption Codes that only work on specific Post or Page IDs. This can be entered as a single Post/Page ID, or as a comma-delimited list of multiple IDs.</em></li>'."\n";
			echo '<li style="list-style:none;margin-top:1em;"><i class="fa fa-link"></i> <code>one_click=""</code> e.g., <code>/redeem/</code> <em>By default (i.e., if this is empty), s2Member simply lists each Gift/Redemption Code for the customer. The customer can copy/paste each code and send it to a recipient of their choosing. However, if you provide a full URL, or a relative path in this attribute, each Gift/Redemption Code will be clickable. Making it possible for the customer to copy a preformatted link which leads to a Post/Page where each code can be redeemed. <strong>Note:</strong> this URL (or relative path) should lead to a Post/Page where a Pro-Form has been implemented, and where the Pro-Form has Shortcode Attribute: <code>accept_coupons="1"</code>. <strong>Tip:</strong> if you fill this in, it\'s reasonable to assume that you have a specific Post/Page set up where Gift/Redemption Codes should be used. You may want to consider adding the ID for that Post/Page to the <code>singulars=""</code>, thereby forcing all Gift/Redemption Coupons to be used in the location you specify here.</em></li>'."\n";
			echo '</ul>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<p><strong>SECURITY NOTE:</strong> s2Member does not guard access to the Gift/Redemption codes unless you tell it to; i.e., the Post/Page that contains this Shortcode should be protected using s2Member Restriction Options of your choosing.</p>'."\n";
			echo '<p><strong>CHECKOUT COMPATIBILITY NOTE:</strong> Customers that purchase access to Gift/Redemption Codes do NOT need to have an account. This means that you can sell access using <strong>s2Member\'s Specific Post/Page Access</strong> functionality <strong>(easiest way to sell Gift/Redemption Codes)</strong>. You can also sell access to Gift/Redemption Codes using any of s2Member\'s Membership Level Access functionality, or by selling Custom Capabilities to new and/or existing users. You simply generate Pro-Forms w/ s2Member and then lead a Customer to the Post/Page where you have <code>[s2Member-Gift-Codes /]</code> for them.</p>'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<p class="ws-menu-page-warning-hilite" style="padding:1em;"><strong>WARNING:</strong> Gift/Redemption Codes are unique for each Post/Page that contains the shortcode, and they are also unique for each User that gains access to it. However, changing the Shortcode Attributes will cause a new set of Gift/Redemption Codes to be generated. This is to ensure that all of the Gift/Redemption Codes meet the specifications that you\'ve configured with the Shortcode. So please take note; you should NOT change the Shortcode Attributes once you have already started selling access to the Post/Page that contains them. During your own tests it is fine to tweak things here and there, just be careful about leaving a Post/Page several months, and then suddenly deciding to change some of the <code>[s2Member-Gift-Codes /]</code> Attributes. If you need to generate a new list, please create a new Post/Page and retire the old one.</p>'."\n";

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

new c_ws_plugin__s2member_pro_menu_page_coupon_codes ();