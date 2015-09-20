<?php
/**
 * Stats Pinger.
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
 * @package s2Member\Stats
 * @since 150708
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit ('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_stats_pinger'))
{
	/**
	 * Stats Pinger.
	 *
	 * @package s2Member\Stats
	 * @since 150708
	 */
	class c_ws_plugin__s2member_pro_stats_pinger
	{
		public static function maybe_ping()
		{
			if (!apply_filters('c_ws_plugin__s2member_pro_stats_pinger_enable', TRUE))
				return; // Stats collection off.

			if ($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_last_stats_log'] >= strtotime('-1 week'))
				return; // No reason to keep pinging.

			$GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_last_stats_log'] = (string)time();

			update_option('ws_plugin__s2member_options', $GLOBALS['WS_PLUGIN__']['s2member']['o']);

			if(is_multisite() && is_main_site()) // Update site options on a multisite network.
				update_site_option('ws_plugin__s2member_options', $GLOBALS['WS_PLUGIN__']['s2member']['o']);

			$stats_api_url      = 'https://www.websharks-inc.com/products/stats-log.php';
			$stats_api_url_args = array(
				'os'              => PHP_OS,
				'php_version'     => PHP_VERSION,
				'mysql_version'   => $GLOBALS['wpdb']->db_version(),
				'wp_version'      => get_bloginfo('version'),
				'product_version' => WS_PLUGIN__S2MEMBER_PRO_VERSION,
				'product'         => 's2member-pro',
			);
			$stats_api_url = add_query_arg(urlencode_deep($stats_api_url_args), $stats_api_url);

			wp_remote_get ($stats_api_url, array(
					'blocking'  => false,
					'sslverify' => false,
				)
			);
		}
	}
}
