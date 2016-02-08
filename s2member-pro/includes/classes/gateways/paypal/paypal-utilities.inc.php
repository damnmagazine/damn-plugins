<?php
/**
 * PayPal utilities.
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
 * @package s2Member\PayPal
 * @since 1.5
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_paypal_utilities'))
{
	/**
	 * PayPal utilities.
	 *
	 * @package s2Member\PayPal
	 * @since 1.5
	 */
	class c_ws_plugin__s2member_pro_paypal_utilities
	{
		/**
		 * Calculates start date for a Recurring Payment Profile.
		 *
		 * @package s2Member\PayPal
		 * @since 1.5
		 *
		 * @param string $period1 Optional. A 'Period Term' combination. Defaults to `0 D`.
		 * @param string $period3 Optional. A 'Period Term' combination. Defaults to `0 D`.
		 *
		 * @return int The start time, a Unix timestamp.
		 */
		public static function paypal_start_time($period1 = '', $period3 = '')
		{
			if(!($p1_time = 0) && ($period1 = trim(strtoupper($period1))))
			{
				list($num, $span) = preg_split('/ /', $period1, 2);

				$days = 0; // Days start at 0.

				if(is_numeric($num) && !is_numeric($span))
				{
					$days = ($span === 'D') ? 1 : $days;
					$days = ($span === 'W') ? 7 : $days;
					$days = ($span === 'M') ? 30 : $days;
					$days = ($span === 'Y') ? 365 : $days;
				}
				$p1_days = (int)$num * (int)$days;
				$p1_time = $p1_days * 86400;
			}
			if(!($p3_time = 0) && ($period3 = trim(strtoupper($period3))))
			{
				list($num, $span) = preg_split('/ /', $period3, 2);

				$days = 0; // Days start at 0.

				if(is_numeric($num) && !is_numeric($span))
				{
					$days = ($span === 'D') ? 1 : $days;
					$days = ($span === 'W') ? 7 : $days;
					$days = ($span === 'M') ? 30 : $days;
					$days = ($span === 'Y') ? 365 : $days;
				}
				$p3_days = (int)$num * (int)$days;
				$p3_time = $p3_days * 86400;
			}
			$start_time = strtotime('now') + $p1_time + $p3_time;
			$start_time = ($start_time <= 0) ? strtotime('now') : $start_time;
			$start_time = $start_time + 43200; // + 12 hours.

			return $start_time;
		}

		/**
		 * Determines whether or not Tax may apply.
		 *
		 * @package s2Member\PayPal
		 * @since 1.5
		 *
		 * @return bool True if Tax may apply, else false.
		 */
		public static function paypal_tax_may_apply()
		{
			if((float)$GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_default_tax'] > 0)
				return TRUE;

			if($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_tax_rates'])
				return TRUE;

			return FALSE;
		}

		/**
		 * Gets a Payflow recurring profile.
		 *
		 * @package s2Member\PayPal
		 * @since 110531
		 *
		 * @param string $subscr_id A paid subscription ID (aka: Recurring Profile ID).
		 *
		 * @return array|false Array of profile details, else false.
		 */
		public static function payflow_get_profile($subscr_id = '')
		{
			$payflow['TRXTYPE']       = 'R';
			$payflow['ACTION']        = 'I';
			$payflow['TENDER']        = 'C';
			$payflow['ORIGPROFILEID'] = $subscr_id;

			if(($profile = c_ws_plugin__s2member_paypal_utilities::paypal_payflow_api_response($payflow)) && empty($profile['__error']))
				return $profile;

			$payflow['TENDER'] = 'P';
			if(($profile = c_ws_plugin__s2member_paypal_utilities::paypal_payflow_api_response($payflow)) && empty($profile['__error']))
				return $profile;

			return FALSE;
		}

		/**
		 * Cancels a Payflow recurring profile.
		 *
		 * @package s2Member\PayPal
		 * @since 110531
		 *
		 * @param string $subscr_id A paid subscription ID (aka: Recurring Profile ID).
		 * @param string $baid A Billing Agreement ID (aka: BAID).
		 *
		 * @return boolean True if the profile was cancelled, else false.
		 */
		public static function payflow_cancel_profile($subscr_id = '', $baid = '')
		{
			$payflow['TRXTYPE']       = 'R';
			$payflow['ACTION']        = 'C';
			$payflow['TENDER']        = 'C';
			$payflow['ORIGPROFILEID'] = $subscr_id;

			if(($cancellation = c_ws_plugin__s2member_paypal_utilities::paypal_payflow_api_response($payflow)) && empty($cancellation['__error']))
				if(!$baid || c_ws_plugin__s2member_pro_paypal_utilities::payflow_cancel_billing_agreement($baid))
					return TRUE;

			$payflow['TENDER'] = 'P';
			if(($cancellation = c_ws_plugin__s2member_paypal_utilities::paypal_payflow_api_response($payflow)) && empty($cancellation['__error']))
				if(!$baid || c_ws_plugin__s2member_pro_paypal_utilities::payflow_cancel_billing_agreement($baid))
					return TRUE;

			return FALSE;
		}

		/**
		 * Cancels a Payflow Billing Agreement.
		 *
		 * @package s2Member\PayPal
		 * @since 130510
		 *
		 * @param string $baid A Billing Agreement ID (aka: BAID).
		 *
		 * @return boolean True if the agreement was cancelled, else false.
		 */
		public static function payflow_cancel_billing_agreement($baid = '')
		{
			$payflow['ACTION']    = 'U';
			$payflow['TENDER']    = 'P';
			$payflow['BAID']      = $baid;
			$payflow['BA_STATUS'] = 'cancel';

			if(($cancellation = c_ws_plugin__s2member_paypal_utilities::paypal_payflow_api_response($payflow)) && empty($cancellation['__error']))
				return TRUE;

			return FALSE;
		}

		/**
		 * Handles currency conversions for Maestro/Solo cards.
		 *
		 * PayPal requires Maestro/Solo to be charged in GBP. So if a site owner is using
		 * another currency *(i.e., something NOT in GBP)*, we have to convert all of the charge amounts dynamically.
		 *
		 * Coupon Codes should always be applied before this conversion takes place.
		 * That way a site owner's configuration remains adequate.
		 *
		 * Tax rates should be applied after this conversion takes place.
		 *
		 * @package s2Member\PayPal
		 * @since 110531
		 *
		 * @param array  $attr An array of PayPal Pro-Form Attributes.
		 * @param string $card_type The Card Type *(i.e., Billing Method)* selected.
		 *
		 * @return array The same array of Pro-Form Attributes, with possible currency conversions.
		 */
		public static function paypal_maestro_solo_2gbp($attr = array(), $card_type = '')
		{
			if(is_array($attr) && is_string($card_type) && in_array($card_type, array('Maestro', 'Solo')))
				if(!empty($attr['cc']) && strcasecmp($attr['cc'], 'GBP') !== 0 && is_numeric($attr['ta']) && is_numeric($attr['ra']))
					if(($attr['ta'] <= 0 && is_numeric($c_ta = '0')) || is_numeric($c_ta = c_ws_plugin__s2member_utils_cur::convert($attr['ta'], $attr['cc'], 'GBP')))
						if(($attr['ra'] <= 0 && is_numeric($c_ra = '0')) || is_numeric($c_ra = c_ws_plugin__s2member_utils_cur::convert($attr['ra'], $attr['cc'], 'GBP')))
							$attr = array_merge($attr, array('cc' => 'GBP', 'ta' => $c_ta, 'ra' => $c_ra));

			return $attr; // Return array of Attributes.
		}

		/**
		 * Handles the return of Tax for Pro-Forms, via AJAX; through a JSON object.
		 *
		 * @package s2Member\PayPal
		 * @since 1.5
		 *
		 * @return null Or exits script execution after returning data for AJAX caller.
		 *
		 * @todo Check the use of ``strip_tags()`` in this routine?
		 * @todo Continue optimizing this routine with ``empty()`` and ``isset()``.
		 * @todo Candidate for the use of ``ifsetor()``?
		 */
		public static function paypal_ajax_tax()
		{
			if(!empty($_POST['ws_plugin__s2member_pro_paypal_ajax_tax']) && ($nonce = $_POST['ws_plugin__s2member_pro_paypal_ajax_tax']) && (wp_verify_nonce($nonce, 'ws-plugin--s2member-pro-paypal-ajax-tax') || c_ws_plugin__s2member_utils_encryption::decrypt($nonce) === 'ws-plugin--s2member-pro-paypal-ajax-tax'))
				/* A wp_verify_nonce() won't always work here, because s2member-pro-min.js must be cacheable. The output from wp_create_nonce() would go stale.
						So instead, s2member-pro-min.js should use c_ws_plugin__s2member_utils_encryption::encrypt() as an alternate form of nonce. */
			{
				status_header(200); // Send a 200 OK status header.
				header('Content-Type: text/plain; charset=UTF-8'); // Content-Type text/plain with UTF-8.
				while(@ob_end_clean()) ; // Clean any existing output buffers.

				if(!empty($_POST['ws_plugin__s2member_pro_paypal_ajax_tax_vars']) && is_array($_p_tax_vars = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST['ws_plugin__s2member_pro_paypal_ajax_tax_vars']))))
				{
					if(is_array($attr = (!empty($_p_tax_vars['attr'])) ? unserialize(c_ws_plugin__s2member_utils_encryption::decrypt($_p_tax_vars['attr'])) : FALSE))
					{
						$attr = (!empty($attr['coupon'])) ? c_ws_plugin__s2member_pro_paypal_utilities::paypal_apply_coupon($attr, $attr['coupon']) : $attr;

						$trial           = ($attr['rr'] !== 'BN' && $attr['tp']) ? TRUE : FALSE; // Is there a trial?
						$sub_total_today = ($trial) ? $attr['ta'] : $attr['ra']; // What is the sub-total today?

						$state    = strip_tags($_p_tax_vars['state']);
						$country  = strip_tags($_p_tax_vars['country']);
						$zip      = strip_tags($_p_tax_vars['zip']);
						$currency = $attr['cc']; // Currency.
						$desc     = $attr['desc']; // Description.

						/* Trial is `null` in this function call. We only need to return what it costs today.
						However, we do tag on a 'trial' element in the array so the ajax routine will know about this. */
						$a = c_ws_plugin__s2member_pro_paypal_utilities::paypal_cost(NULL, $sub_total_today, $state, $country, $zip, $currency, $desc);

						echo json_encode(array('trial'      => $trial,
						                       'sub_total'  => $a['sub_total'],

						                       'tax'        => $a['tax'],
						                       'tax_per'    => $a['tax_per'],

						                       'total'      => $a['total'],

						                       'cur'        => $a['cur'],
						                       'cur_symbol' => $a['cur_symbol'],

						                       'desc'       => $a['desc']));
					}
				}
				exit(); // Clean exit.
			}
		}

		/**
		 * Handles all cost calculations for PayPal.
		 *
		 * Returns an associative array with a possible Percentage Rate, along with the calculated Tax Amount.
		 * Tax calculations are based on State/Province, Country, and/or Zip Code.
		 * Updated to support multiple data fields in it's return value.
		 *
		 * @package s2Member\PayPal
		 * @since 1.5
		 *
		 * @param int|string $trial_sub_total Optional. A numeric Amount/cost of a possible Initial/Trial being offered.
		 * @param int|string $sub_total Optional. A numeric Amount/cost of the purchase and/or Regular Period.
		 * @param string     $state Optional. The State/Province where the Customer is billed.
		 * @param string     $country Optional. The Country where the Customer is billed.
		 * @param int|string $zip Optional. The Postal/Zip Code where the Customer is billed.
		 * @param string     $currency Optional. Expects a 3 character Currency Code.
		 * @param string     $desc Optional. Description of the sale.
		 *
		 * @return array Array of calculations.
		 */
		public static function paypal_cost($trial_sub_total = 0, $sub_total = 0, $state = '', $country = '', $zip = '', $currency = '', $desc = '')
		{
			$state   = strtoupper(c_ws_plugin__s2member_pro_utilities::full_state($state, ($country = strtoupper($country))));
			$rates   = apply_filters('ws_plugin__s2member_pro_tax_rates_before_cost_calculation', strtoupper($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_tax_rates']), get_defined_vars());
			$default = $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_default_tax'];
			$ps      = _x('%', 's2member-front percentage-symbol', 's2member');

			$trial_tax = $tax = $trial_tax_per = $tax_per = $trial_total = $total = NULL; // Initialize.
			foreach(array('trial_sub_total' => $trial_sub_total, 'sub_total' => $sub_total) as $this_key => $this_sub_total)
			{
				$_default = $this_tax = $this_tax_per = $this_total = $configured_rates = $configured_rate = $location = $rate = $m = NULL;

				if(is_numeric($this_sub_total) && $this_sub_total > 0) // Must have a valid Sub-Total.
				{
					if(preg_match('/%$/', $default)) // Percentage-based.
					{
						if(($_default = (float)$default) > 0)
						{
							$this_tax     = round(($this_sub_total / 100) * $_default, 2);
							$this_tax_per = $_default.$ps;
						}
						else // Else the Tax is 0.00.
						{
							$this_tax     = 0.00;
							$this_tax_per = $_default.$ps;
						}
					}
					else if(($_default = (float)$default) > 0)
					{
						$this_tax     = round($_default, 2);
						$this_tax_per = ''; // Flat.
					}
					else // Else the Tax is 0.00.
					{
						$this_tax     = 0.00; // No Tax.
						$this_tax_per = ''; // Flat rate.
					}
					if(strlen($country) === 2) // Must have a valid country.
					{
						foreach(preg_split('/['."\r\n\t".']+/', $rates) as $rate)
						{
							if($rate = trim($rate)) // Do NOT process empty lines.
							{
								list($location, $rate) = preg_split('/\=/', $rate, 2);
								$location = trim($location);
								$rate     = trim($rate);

								if($location === $country)
									$configured_rates[1] = $rate;

								else if($state && $location === $state.'/'.$country)
									$configured_rates[2] = $rate;

								else if($state && preg_match('/^([A-Z]{2})\/('.preg_quote($country, '/').')$/', $location, $m) && strtoupper(c_ws_plugin__s2member_pro_utilities::full_state($m[1], $m[2])).'/'.$m[2] === $state.'/'.$country)
									$configured_rates[2] = $rate;

								else if($zip && preg_match('/^([0-9]+)-([0-9]+)\/('.preg_quote($country, '/').')$/', $location, $m) && $zip >= $m[1] && $zip <= $m[2] && $country === $m[3])
									$configured_rates[3] = $rate;

								else if($zip && $location === $zip.'/'.$country)
									$configured_rates[4] = $rate;
							}
						}
						if(is_array($configured_rates) && !empty($configured_rates))
						{
							krsort($configured_rates);
							$configured_rate = array_shift($configured_rates);

							if(preg_match('/%$/', $configured_rate)) // Percentage.
							{
								if(($configured_rate = (float)$configured_rate) > 0)
								{
									$this_tax     = round(($this_sub_total / 100) * $configured_rate, 2);
									$this_tax_per = $configured_rate.$ps;
								}
								else // Else the Tax is 0.00.
								{
									$this_tax     = 0.00; // No Tax.
									$this_tax_per = $configured_rate.$ps;
								}
							}
							else if(($configured_rate = (float)$configured_rate) > 0)
							{
								$this_tax     = round($configured_rate, 2);
								$this_tax_per = ''; // Flat rate.
							}
							else // Else the Tax is 0.00.
							{
								$this_tax     = 0.00; // No Tax.
								$this_tax_per = ''; // Flat rate.
							}
						}
					}
					$this_total = $this_sub_total + $this_tax;
				}
				else // Else the Tax is 0.00.
				{
					$this_tax       = 0.00; // No Tax.
					$this_tax_per   = ''; // Flat rate.
					$this_sub_total = 0.00; // 0.00.
					$this_total     = 0.00; // 0.00.
				}
				if($this_key === 'trial_sub_total')
				{
					$trial_tax       = $this_tax;
					$trial_tax_per   = $this_tax_per;
					$trial_sub_total = $this_sub_total;
					$trial_total     = $this_total;
				}
				else if($this_key === 'sub_total')
				{
					$tax       = $this_tax;
					$tax_per   = $this_tax_per;
					$sub_total = $this_sub_total;
					$total     = $this_total;
				}
			}
			return array('trial_sub_total' => number_format($trial_sub_total, 2, '.', ''),
			             'sub_total'       => number_format($sub_total, 2, '.', ''),

			             'trial_tax'       => number_format($trial_tax, 2, '.', ''),
			             'tax'             => number_format($tax, 2, '.', ''),

			             'trial_tax_per'   => $trial_tax_per,
			             'tax_per'         => $tax_per,

			             'trial_total'     => number_format($trial_total, 2, '.', ''),
			             'total'           => number_format($total, 2, '.', ''),

			             'cur'             => $currency,
			             'cur_symbol'      => c_ws_plugin__s2member_utils_cur::symbol($currency),

			             'desc'            => $desc);
		}

		/**
		 * Checks to see if a Coupon Code was supplied, and if so; what does it provide?
		 *
		 * @package s2Member\PayPal
		 * @since 1.5
		 *
		 * @param array  $attr An array of Pro-Form Attributes.
		 * @param string $coupon_code Optional. A possible Coupon Code supplied by the Customer.
		 * @param string $return Optional. Return type. One of `response|attr`. Defaults to `attr`.
		 * @param array  $process Optional. An array of additional processing routines to run here.
		 *   One or more of these values: `affiliates-1px-response|affiliates-silent-post|notifications`.
		 *
		 * @return array|string Original array, with prices and description modified when/if a Coupon Code is accepted.
		 *   Or, if ``$return === 'response'``, return a string response, indicating status.
		 */
		public static function paypal_apply_coupon($attr = array(), $coupon_code = '', $return = '', $process = array())
		{
			$coupons = new c_ws_plugin__s2member_pro_coupons();
			return $coupons->apply($attr, $coupon_code, $return, $process);
		}
	}
}