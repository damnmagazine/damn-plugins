<?php
/**
 * Menu page for s2Member Pro (Login Welcome Page / One-Time-Offers).
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
	exit ("Do not access this file directly.");

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_gen_ops_lwp_otos"))
{
	/**
	 * Menu page for s2Member Pro (Login Welcome Page / One-Time-Offers).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110720
	 */
	class c_ws_plugin__s2member_pro_menu_page_gen_ops_lwp_otos
	{
		public function __construct()
		{
			echo '<div class="ws-menu-page-group" title="One-Time-Offers (Upon Login)">'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-one-time-offers-section">'."\n";
			echo '<h3>Optional One-Time-Offers (Upon Login)</h3>'."\n";
			echo '<p>This is enabled by s2Member Pro. One-Time-Offers allow you to override your default Login Welcome Page, based on the number of times a User/Member has logged in previously. s2Member Pro gives you the ability to write your own configuration file for One-Time-Offers. Please follow the instructions below. It is also possible for advanced site owners to use these <a href="#" onclick="alert(\'Replacement Codes:\\n\\n%%current_user_login%% = The current User\\\'s Username, lowercase (deprecated, please use %%current_user_nicename%%).\\n\\n%%current_user_nicename%% = The current User\\\'s Nicename in lowercase format (i.e., a cleaner version of the username for URLs; recommended for best compatibility).\\n\\n%%current_user_id%% = The current User\\\'s ID.\\n\\n%%current_user_level%% = The current User\\\'s s2Member Level.\\n\\n%%current_user_role%% = The current User\\\'s WordPress Role.'.((!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? '\\n\\n%%current_user_ccaps%% = The current User\\\'s Custom Capabilities.' : '').'\\n\\n%%current_user_logins%% = Number of times the current User has logged in.\\n\\nFor example, if you\\\'re using BuddyPress, and you want to redirect Members to their BuddyPress Profile page after logging in, you would use: '.home_url("/members/%%current_user_nicename%%/profile/").'\\n\\nOr, using %%current_user_level%%, you could have a separate One-Time-Offer page for each Membership Level that you plan to offer.\'); return false;">Replacement Codes</a> in their One-Time-Offer URLs.</p>'."\n";
			echo (c_ws_plugin__s2member_utils_conds::bp_is_installed()) ? '<p><em><strong>BuddyPress:</strong> s2Member integrates with BuddyPress. This configuration affects BuddyPress too.</em></p>'."\n" : '';

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<th>'."\n";
			echo '<label for="ws-plugin--s2member-pro-login-welcome-page-otos">'."\n";
			echo 'One-Time-Offer Configuration File:'."\n";
			echo '</label>'."\n";
			echo '</th>'."\n";

			echo '</tr>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<textarea name="ws_plugin__s2member_pro_login_welcome_page_otos" id="ws-plugin--s2member-pro-login-welcome-page-otos" rows="8" wrap="off" spellcheck="false">'.format_to_edit($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_login_welcome_page_otos"]).'</textarea><br />'."\n";
			echo 'This is a line-delimited list of URLs <em>(in a special format, as seen below)</em>.<br /><br />'."\n";
			echo 'Special format (explained):<br />'."\n";
			echo '<code>[Logins]</code>:<code>[Access Level]</code>:<code>[One-Time-Offer URL]</code><br /><br />'."\n";
			echo '<em><code>[Logins]</code> (this triggers your One-Time-Offer page, upon X number of logins)</em><br />'."\n";
			echo '<em><code>[Access Level]</code> (optional, this triggers your One-Time-Offer, based on Level# as well)</em><br />'."\n";
			echo '<em><code>[One-Time-Offer URL]</code> (where User is redirected, upon login)</em><br /><br />'."\n";
			echo '<strong>Example Configuration File:</strong><br />'."\n";
			echo '<code>1:http://example.com/your-first-login/</code> <em>(displayed on 1st login, to all Users/Members)</em><br />'."\n";
			echo '<code>25:http://example.com/customer-loyalty-reward/</code> <em>(displayed on 25th login, to all Users/Members)</em><br />'."\n";
			echo '<code>3:1:http://example.com/upgrade-to-level-2/</code> <em>(displayed on 3rd login, to Level #1 Members only)</em><br />'."\n";
			echo '<code>1:0:http://example.com/upgrade-to-level-1/</code> <em>(displayed on 1st login, to Free Subscribers only)</em>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";
		}
	}
}

new c_ws_plugin__s2member_pro_menu_page_gen_ops_lwp_otos ();