<?php
/**
 * PayPal API Functions *(for site owners)*.
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
 * @since 1.5
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit ("Do not access this file directly.");

/**
 * Verifies `s2p-v` in a given query string argument; from a custom URL for success.
 *
 * This can be used to verify the integrity of variables in a success query string.
 * Example usage: ``if(s2member_pro_paypal_s2p_v_query_ok($_SERVER["QUERY_STRING"])){ }``
 *
 * @package s2Member\API_Functions
 * @since 1.5
 *
 * @param string     $url_uri_query A full URL, a partial URI, or just the query string.
 * @param bool       $ignore_time Optional. Defaults to false. If true, timestamp is ignored.
 * @param string|int $exp_secs Optional. Defaults to (int)10. If ``$ignore_time`` is false, s2Member will check the signature timestamp.
 *   By default, the signature timestamp cannot be older than 10 seconds, but you can modify this if you prefer.
 *
 * @return bool True if the query string is OK/verified, else false.
 */
if(!function_exists("s2member_pro_paypal_s2p_v_query_ok"))
{
	function s2member_pro_paypal_s2p_v_query_ok($url_uri_query = FALSE, $ignore_time = FALSE, $exp_secs = FALSE)
	{
		$check_time = ($ignore_time) ? FALSE : TRUE; // Make this compatible with ``$check_time``.

		return c_ws_plugin__s2member_utils_urls::s2member_sig_ok($url_uri_query, $check_time, $exp_secs, "s2p-v");
	}
}
/**
 * Pulls an array of details from the PayPal Pro API; related to a customer's Recurring Billing Profile.
 *
 * This function will return an array of data (as described below); else an empty array if no Recurring Billing Profile exists.
 *   Example usage: ``<!php print_r(s2member_pro_paypal_rbp_for_user(123)); !>``
 *
 * Array elements returned by this function correlate with the PayPal Pro API call method: `GetRecurringPaymentsProfileDetails`.
 *   Please see {@link https://www.x.com/developers/paypal/documentation-tools/api/getrecurringpaymentsprofiledetails-api-operation-nvp}.
 *
 * @package s2Member\API_Functions
 * @since 130405
 *
 * @param string|int $user_id Optional. A specific User ID. Defaults to the current User ID that is logged into the site.
 *
 * @return array An array of data (from the PayPal Pro API); else an empty array if no Recurring Billing Profile exists.
 *
 *   Array elements returned by this function correlate with the PayPal Pro API call method: `GetRecurringPaymentsProfileDetails`.
 *   Please see {@link https://www.x.com/developers/paypal/documentation-tools/api/getrecurringpaymentsprofiledetails-api-operation-nvp}.
 *
 * @note If your PayPal Pro account uses the Payflow™ Edition API, please use {@link s2member_pro_payflow_rbp_for_user()} instead.
 */
if(!function_exists('s2member_pro_paypal_rbp_for_user'))
{
	function s2member_pro_paypal_rbp_for_user($user_id = FALSE)
	{
		$user_id = (integer)$user_id;
		$user_id = ($user_id) ? $user_id : get_current_user_id();
		if(!$user_id) return array();

		$user_subscr_id = get_user_option('s2member_subscr_id', $user_id);
		if(!$user_subscr_id) return array();

		$paypal['METHOD']    = 'GetRecurringPaymentsProfileDetails';
		$paypal['PROFILEID'] = $user_subscr_id;

		if(is_array($paypal = c_ws_plugin__s2member_paypal_utilities::paypal_api_response($paypal)) && empty($paypal['__error']))
			return $paypal;

		return array();
	}
}
/**
 * Pulls last/next billing times from the PayPal Pro API; associated with a customer's Recurring Billing Profile.
 *
 * This function will return an array of data (as described below); else an empty array if no Recurring Billing Profile exists.
 *   Example usage: ``<!php print_r(s2member_pro_paypal_rbp_times_for_user(123)); !>``
 *
 * Array elements returned by this function include: `last_billing_time`, `next_billing_time` (both as UTC Unix timestamps).
 *
 * @package s2Member\API_Functions
 * @since 130405
 *
 * @param string|int $user_id Optional. A specific User ID. Defaults to the current User ID that is logged into the site.
 *
 * @return array Array elements: `last_billing_time`, `next_billing_time` (both as UTC Unix timestamps);
 *   else an empty array if no Recurring Billing Profile exists.
 *
 * If one or more times (e.g., `last_billing_time`, `next_billing_time`) are irrelevant (i.e., there was no payment received yet; or there are no future payments to receive);
 *   that time will default to a value of `0` indicating it's irrelevant and/or not applicable.
 *
 * @note If your PayPal Pro account uses the Payflow™ Edition API, please use {@link s2member_pro_payflow_rbp_times_for_user()} instead.
 */
if(!function_exists('s2member_pro_paypal_rbp_times_for_user'))
{
	function s2member_pro_paypal_rbp_times_for_user($user_id = FALSE)
	{
		if(!($paypal = s2member_pro_paypal_rbp_for_user($user_id)))
			return array();

		$array = array('last_billing_time' => 0, 'next_billing_time' => 0);

		if($paypal['LASTPAYMENTDATE'] && strtotime($paypal['LASTPAYMENTDATE']) <= time())
			$array['last_billing_time'] = strtotime($paypal['LASTPAYMENTDATE']);

		if(($paypal['TOTALBILLINGCYCLES'] <= 0 || $paypal['NUMCYCLESREMAINING'] > 0) && preg_match('/^(Active|ActiveProfile)$/i', $paypal['STATUS']))
			if($paypal['NEXTBILLINGDATE'] && strtotime($paypal['NEXTBILLINGDATE']) > time())
				$array['next_billing_time'] = strtotime($paypal['NEXTBILLINGDATE']);

		return $array;
	}
}
/**
 * Pulls an array of details from PayPal Pro (Payflow™ Edition) API; related to a customer's Recurring Billing Profile.
 *
 * This function will return an array of data (as described below); else an empty array if no Recurring Billing Profile exists.
 *   Example usage: ``<!php print_r(s2member_pro_payflow_rbp_for_user(123)); !>``
 *
 * Array elements returned by this function correlate with the PayPal Pro (Payflow™ Edition) API call method: `ACTION=I`.
 *   Please see {@link https://www.paypalobjects.com/webstatic/en_US/developer/docs/pdf/wpppe_rp_guide.pdf#page=54}.
 *
 * @package s2Member\API_Functions
 * @since 130405
 *
 * @param string|int $user_id Optional. A specific User ID. Defaults to the current User ID that is logged into the site.
 *
 * @return array An array of data from the PayPal Pro (Payflow™ Edition) API; else an empty array if no Recurring Billing Profile exists.
 *
 *   Array elements returned by this function correlate with the PayPal Pro (Payflow™ Edition) API call method: `ACTION=I`.
 *   Please see {@link https://www.paypalobjects.com/webstatic/en_US/developer/docs/pdf/wpppe_rp_guide.pdf#page=54}.
 */
if(!function_exists('s2member_pro_payflow_rbp_for_user'))
{
	function s2member_pro_payflow_rbp_for_user($user_id = FALSE)
	{
		$user_id = (integer)$user_id;
		$user_id = ($user_id) ? $user_id : get_current_user_id();
		if(!$user_id) return array();

		$user_subscr_id = get_user_option('s2member_subscr_id', $user_id);
		if(!$user_subscr_id) return array();

		if(!class_exists('c_ws_plugin__s2member_pro_paypal_utilities'))
			return array();

		if(is_array($payflow = c_ws_plugin__s2member_pro_paypal_utilities::payflow_get_profile($user_subscr_id)))
			return $payflow;

		return array();
	}
}
/**
 * Pulls last/next billing times from the PayPal Pro (Payflow™ Edition) API; associated with a customer's Recurring Billing Profile.
 *
 * This function will return an array of data (as described below); else an empty array if no Recurring Billing Profile exists.
 *   Example usage: ``<!php print_r(s2member_pro_payflow_rbp_times_for_user(123)); !>``
 *
 * Array elements returned by this function include: `last_billing_time`, `next_billing_time` (both as UTC Unix timestamps).
 *
 * @package s2Member\API_Functions
 * @since 130405
 *
 * @param string|int $user_id Optional. A specific User ID. Defaults to the current User ID that is logged into the site.
 *
 * @return array Array elements: `last_billing_time`, `next_billing_time` (both as UTC Unix timestamps);
 *   else an empty array if no Recurring Billing Profile exists.
 *
 * If one or more times (e.g., `last_billing_time`, `next_billing_time`) are irrelevant (i.e., there was no payment received yet; or there are no future payments to receive);
 *   that time will default to a value of `0` indicating it's irrelevant and/or not applicable.
 */
if(!function_exists('s2member_pro_payflow_rbp_times_for_user'))
{
	function s2member_pro_payflow_rbp_times_for_user($user_id = FALSE)
	{
		if(!($payflow = s2member_pro_payflow_rbp_for_user($user_id)))
			return array();

		$array = array('last_billing_time' => 0, 'next_billing_time' => 0);

		if(($last_billing_time = get_user_option('s2member_last_payment_time', $user_id)))
			$array['last_billing_time'] = $last_billing_time; // Only choice.

		if(($payflow['TERM'] <= 0 || $payflow['PAYMENTSLEFT'] > 0) && preg_match('/^(Active|ActiveProfile)$/i', $payflow['STATUS']))
			if($payflow['NEXTPAYMENT'] && strlen($payflow['NEXTPAYMENT']) === 8) // MMDDYYYY format is not `strtotime()` compatible.
				$array['next_billing_time'] = strtotime(substr($payflow['NEXTPAYMENT'], -4).'-'.substr($payflow['NEXTPAYMENT'], 0, 2).'-'.substr($payflow['NEXTPAYMENT'], 2, 2));

		return $array;
	}
}
