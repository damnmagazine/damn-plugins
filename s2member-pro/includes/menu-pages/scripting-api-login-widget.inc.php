<?php
/**
 * Menu page for s2Member Pro (Scripting, Pro Login Widget).
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

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_scripting_api_login_widget"))
{
	/**
	 * Menu page for s2Member Pro (Scripting, Pro Login Widget).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110531
	 */
	class c_ws_plugin__s2member_pro_menu_page_scripting_api_login_widget
	{
		public function __construct()
		{
			if(!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site())
			{
				echo '<div class="ws-menu-page-group" title="Pro Login Widget via PHP">'."\n";

				echo '<div class="ws-menu-page-section ws-plugin--s2member-api-login-widget-section">'."\n";
				echo '<h3>Pro Login Widget via PHP Tag (some scripting required)</h3>'."\n";
				echo '<p>With s2Member Pro installed, you have access to the s2Member Pro Login Widget. This is made available in your Dashboard under: <strong>Appearance → Widgets</strong>. Very simple to use; just drag &amp; drop (that\'s it). For developers though, sometimes it is necessary to include the Pro Login Widget in non-widgetized sections of a WordPress theme; or even into another plugin that you run in concert with s2Member. You can use this PHP tag to build the Pro Login Widget dynamically: '.c_ws_plugin__s2member_utils_strings::highlight_php('<?php echo s2member_pro_login_widget(); ?>').'</p>'."\n";
				echo '<p>The Pro Login Widget can also be configured with an <em>optional</em> array of <a href="http://www.s2member.com/codex/stable/s2member/api_functions/package-functions/#src_doc_s2member_pro_login_widget()" target="_blank" rel="external">configuration parameters</a>.</p>'."\n";

				echo '<div class="ws-menu-page-hr"></div>'."\n";

				echo '<p><strong>TIP:</strong> In addition to this documentation, you may also want to have a look at the <a href="http://www.s2member.com/codex/" target="_blank" rel="external">s2Member Codex</a>.<br />'."\n";
				echo '<strong>See Also:</strong> <a href="http://www.s2member.com/codex/stable/s2member/api_constants/package-summary/" target="_blank" rel="external">s2Member Codex → API Constants</a>, and <a href="http://www.s2member.com/codex/stable/s2member/api_functions/package-summary/" target="_blank" rel="external">s2Member Codex → API Functions</a>.</p>'."\n";
				echo '</div>'."\n";

				echo '</div>'."\n";
			}
		}
	}
}

new c_ws_plugin__s2member_pro_menu_page_scripting_api_login_widget ();