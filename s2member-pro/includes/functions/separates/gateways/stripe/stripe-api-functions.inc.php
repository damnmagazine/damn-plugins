<?php
/**
 * Stripe API Functions *(for site owners)*.
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
 * @package s2Member\API_Functions
 * @since 140617
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

/**
 * Verifies `s2p-v` in a given query string argument; from a custom URL for success.
 *
 * This can be used to verify the integrity of variables in a success query string.
 * Example usage: ``if(s2member_pro_stripe_s2p_v_query_ok($_SERVER['QUERY_STRING'])){ }``
 *
 * @package s2Member\API_Functions
 * @since 140617
 *
 * @param string     $url_uri_query A full URL, a partial URI, or just the query string.
 * @param bool       $ignore_time Optional. Defaults to false. If true, timestamp is ignored.
 * @param string|int $exp_secs Optional. Defaults to (int)10. If ``$ignore_time`` is false, s2Member will check the signature timestamp.
 *   By default, the signature timestamp cannot be older than 10 seconds, but you can modify this if you prefer.
 *
 * @return bool True if the query string is OK/verified, else false.
 */
if(!function_exists('s2member_pro_stripe_s2p_v_query_ok'))
{
	function s2member_pro_stripe_s2p_v_query_ok($url_uri_query = FALSE, $ignore_time = FALSE, $exp_secs = FALSE)
	{
		$check_time = ($ignore_time) ? FALSE : TRUE; // Make this compatible with `$check_time`.

		return c_ws_plugin__s2member_utils_urls::s2member_sig_ok($url_uri_query, $check_time, $exp_secs, 's2p-v');
	}
}
/**
 * Get a Stripe customer object instance.
 *
 * @param null|integer $user_id If it's for an existing user; pass the user's ID (optional).
 * @param string       $email Customer's email address (optional).
 * @param string       $fname Customer's first name (optional).
 * @param string       $lname Customer's last name (optional).
 * @param array        $metadata Any metadata (optional).
 *
 * @return Stripe_Customer|string Customer object; else error message.
 */
if(!function_exists('s2member_pro_stripe_customer'))
{
	function s2member_pro_stripe_customer()
	{
		if (!($args = func_get_args())) {
			$args[0] = get_current_user_id();
		}
		if(!$args[0] // Must be a paying Stripe customer.
			|| !get_user_option('s2member_subscr_id', $args[0])
			|| !get_user_option('s2member_subscr_cid', $args[0])
			|| get_user_option('s2member_subscr_gateway', $args[0]) !== 'stripe') {
			return ''; // Empty (default behavior).
		}
		return call_user_func_array('c_ws_plugin__s2member_pro_stripe_utilities::get_customer', $args);
	}
}
/**
 * Get a Stripe customer subscription object instance.
 *
 * @param string $customer_id Customer ID in Stripe.
 * @param string $subscription_id Subscription ID in Stripe.
 *
 * @return Stripe_Subscription|string Subscription object; else error message.
 */
if(!function_exists('s2member_pro_stripe_customer_subscription'))
{
	function s2member_pro_stripe_customer_subscription()
	{
		if (!($args = func_get_args())) {
			$args[0] = get_user_option('s2member_subscr_cid');
			$args[1] = get_user_option('s2member_subscr_id');
		}
		return call_user_func_array('c_ws_plugin__s2member_pro_stripe_utilities::get_customer_subscription', $args);
	}
}

// @TODO List invoices (i.e., payment history).
