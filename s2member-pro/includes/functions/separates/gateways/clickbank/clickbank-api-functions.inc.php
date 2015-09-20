<?php
/**
* ClickBank API Functions *(for site owners)*.
*
* Copyright: © 2009-2011
* {@link http://www.websharks-inc.com/ WebSharks, Inc.}
* (coded in the USA)
*
* This WordPress plugin (s2Member Pro) is comprised of two parts:
*
* o (1) Its PHP code is licensed under the GPL license, as is WordPress.
* 	You should have received a copy of the GNU General Public License,
* 	along with this software. In the main directory, see: /licensing/
* 	If not, see: {@link http://www.gnu.org/licenses/}.
*
* o (2) All other parts of (s2Member Pro); including, but not limited to:
* 	the CSS code, some JavaScript code, images, and design;
* 	are licensed according to the license purchased.
* 	See: {@link http://www.s2member.com/prices/}
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
* @package s2Member\API_Functions
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit ("Do not access this file directly.");

/**
 * ClickBank order info.
 *
 * @param int|string $user_id Input user ID. Defaults to current user ID.
 *
 * @return array An array of order data; else an empty array.
 */
if(!function_exists('s2member_pro_clickbank_order'))
{
	function s2member_pro_clickbank_order($user_id = 0)
	{
		if(!$user_id) $user_id = get_current_user_id();
		if(!$user_id) return array(); // Not possible.

		$subscr_id       = get_user_option('s2member_subscr_id', $user_id);
		$subscr_gateway  = get_user_option('s2member_subscr_gateway', $user_id);
		$ipn_signup_vars = c_ws_plugin__s2member_utils_users::get_user_ipn_signup_vars($user_id);

		if(!$subscr_id || $subscr_gateway !== 'clickbank')
			return array(); // Not applicable.

		if(!$ipn_signup_vars || empty($ipn_signup_vars['txn_id']))
			return array(); // Not possible.

		return c_ws_plugin__s2member_pro_clickbank_utilities::clickbank_api_order($ipn_signup_vars['txn_id']);
	}
}
