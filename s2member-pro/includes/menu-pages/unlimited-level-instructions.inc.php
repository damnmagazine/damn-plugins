<?php
/**
 * Menu page for s2Member Pro (Unlimited Level Instructions).
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

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_unlimited_level_instructions"))
{
	/**
	 * Menu page for s2Member Pro (Unlimited Level Instructions).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110706
	 */
	class c_ws_plugin__s2member_pro_menu_page_unlimited_level_instructions
	{
		public function __construct()
		{
			if(!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site())
			{
				echo '<div class="ws-menu-page-hr"></div>'."\n";

				echo '<p><strong>Unlimited Membership Levels (via <code>/wp-config.php</code>)</strong><br />'."\n";
				echo 'With s2Member Pro installed, you may configure an unlimited number of Membership Levels. You can set the number of Membership Levels by adding this line to the top of your <a href="http://codex.wordpress.org/Editing_wp-config.php" target="_blank" rel="external">/wp-config.php</a> file: <code><span style="color:#000000"><span style="color:#0000BB">define</span><span style="color:#007700">(</span><span style="color:#DD0000">"MEMBERSHIP_LEVELS"</span><span style="color:#007700">,&nbsp;</span><span style="color:#0000BB">4</span><span style="color:#007700">);</span></span></code>. This line should be inserted at the top of your <code>/wp-config.php</code> file, right after the <code>&lt;?php</code> tag. Feel free to change the default value of <code>4</code> to whatever you need. The minimum allowed value is <code>'.esc_html($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["min_levels"]).'</code>. The recommended maximum is <code>'.esc_html($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["max_levels"]).'</code> <em>(when/if needed)</em>. If you intend to exceed the recommended maximum, you will also need to add a <a href="http://www.s2member.com/kb/hacking-s2member/" target="_blank" rel="external">WordPress Filter</a> like this: <code>add_filter("ws_plugin__s2member_max_levels", function(){ return PHP_INT_MAX; });</code></p>'."\n";

				echo '<div class="ws-menu-page-hr" style="margin-bottom:0;"></div>'."\n";
			}
		}
	}
}

new c_ws_plugin__s2member_pro_menu_page_unlimited_level_instructions ();