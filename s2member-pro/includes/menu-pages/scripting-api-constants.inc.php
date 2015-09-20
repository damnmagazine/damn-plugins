<?php
/**
 * Menu page for s2Member Pro (Scripting, API Constants).
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

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_scripting_api_constants"))
{
	/**
	 * Menu page for s2Member Pro (Scripting, API Constants).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110531
	 */
	class c_ws_plugin__s2member_pro_menu_page_scripting_api_constants
	{
		public function __construct()
		{
			if(is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site()) // Two versions here.
			{
				echo '<p><strong>S2MEMBER_PRO_VERSION</strong><br />This will always be a (string) with the current s2Member Pro version. Available since s2Member Pro v1.0. Dated versions began with s2Member Pro v110604.</p>'."\n";

				echo '<div class="ws-menu-page-hr"></div>'."\n";
			}
			else // Otherwise, we can display the standardized version of this information.
			{
				echo '<p><strong>S2MEMBER_PRO_VERSION</strong><br />This will always be a (string) with the current s2Member Pro version. Available since s2Member Pro v1.0. Dated versions began with s2Member Pro v110604.</p>'."\n";
				echo '<p>'.c_ws_plugin__s2member_utils_strings::highlight_php(file_get_contents(dirname(__FILE__)."/code-samples/pro-version.x-php")).'</p>'."\n";

				echo '<div class="ws-menu-page-hr"></div>'."\n";
			}
		}
	}
}

new c_ws_plugin__s2member_pro_menu_page_scripting_api_constants ();