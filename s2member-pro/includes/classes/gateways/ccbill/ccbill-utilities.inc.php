<?php
/**
* ccBill utilities.
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
* @package s2Member\ccBill
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit ("Do not access this file directly.");

if (!class_exists ("c_ws_plugin__s2member_pro_ccbill_utilities"))
	{
		/**
		* ccBill utilities.
		*
		* @package s2Member\ccBill
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_ccbill_utilities
			{
				/**
				* Generates a ccBill link.
				*
				* @package s2Member\ccBill
				* @since 1.5
				*
				* @param array $vars An array of variables to include in the ccBill link.
				* @return string A full URL to the ccBill Payment Gateway.
				*
				* @todo Optimize this routine with ``empty()`` and ``isset()``.
				* @todo Candidate for the use of ``ifsetor()``?
				*/
				public static function ccbill_link_gen ($vars = FALSE)
					{
						$gateway = "https://bill.ccbill.com/jpost/signup.cgi";

						$digest_vars = $vars["formPrice"] . $vars["formPeriod"]; // These are always required.
						$digest_vars .= $vars["formRecurringPrice"] . $vars["formRecurringPeriod"] . $vars["formRebills"];
						$digest_vars .= $vars["currencyCode"]; // Add the currency code to this too (always req).

						$vars["formDigest"] = md5 ($digest_vars . $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_ccbill_salt_key"]);

						return add_query_arg (urlencode_deep ($vars), $gateway); // ccBill link.
					}
				/**
				* Converts currency code to a numeric code for ccBill.
				*
				* @package s2Member\ccBill
				* @since 1.5
				*
				* @param string $currency_code Expects a 3 character Currency Code.
				* @return int|str A numeric string with a ccBill Currency Number. Defaults to `840` *( i.e., `USD` )*.
				*/
				public static function ccbill_currency_numr ($currency_code = FALSE)
					{
						$currency_code = strtoupper ($currency_code); // Force uppercase.

						$currencies = array("USD" => "840", "EUR" => "978", "AUD" => "036", "CAD" => "124", "GBP" => "826", "JPY" => "392");

						return (!empty($currencies[$currency_code])) ? $currencies[$currency_code] : $currencies["USD"];
					}
				/**
				* Converts Currency Number for ccBill forms into a valid Currency Code.
				*
				* @package s2Member\ccBill
				* @since 1.5
				*
				* @param int|string $currency_numr Expects a valid ccBill Currency Number, numeric.
				* @return string A 3 character Currency Code, for use with s2Member. Defaults to `USD` *( i.e., `840` )*.
				*/
				public static function ccbill_currency_code ($currency_numr = FALSE)
					{
						$currencies = array("840" => "USD", "978" => "EUR", "036" => "AUD", "124" => "CAD", "826" => "GBP", "392" => "JPY");

						return (!empty($currencies[$currency_numr])) ? $currencies[$currency_numr] : $currencies["840"];
					}
				/**
				* Calculates period in days for ccBill forms.
				*
				* @package s2Member\ccBill
				* @since 1.5
				*
				* @param int|string $period Optional. A numeric Period that coincides with ``$term``.
				* @param string $term Optional. A Term that coincides with ``$period``.
				* @return int A "Period Term", in days. Defaults to `0`.
				*/
				public static function ccbill_per_term_2_days ($period = FALSE, $term = FALSE)
					{
						if (is_numeric ($period) && !is_numeric ($term) && ($term = strtoupper ($term)))
							{
								$days = ($term === "D") ? 1 : $days;
								$days = ($term === "W") ? 7 : $days;
								$days = ($term === "M") ? 30 : $days;
								$days = ($term === "Y") ? 365 : $days;
								$days = ($term === "L") ? 365 : $days;

								return (int)$period * (int)$days;
							}
						else
							return 0;
					}
				/**
				* Get ``$_POST`` or ``$_REQUEST`` vars from ccBill.
				*
				* @package s2Member\ccBill
				* @since 1.5
				*
				* @return array|bool An array of verified ``$_POST`` or ``$_REQUEST`` variables, else false.
				*
				* @todo Continue optimizing this routine with ``empty()`` and ``isset()``.
				* @todo Candidate for the use of ``ifsetor()``?
				* @todo Update to use ``strcasecmp()``.
				*/
				public static function ccbill_postvars ()
					{
						if ((isset ($_REQUEST["s2member_pro_ccbill_return"]) && strlen ($_REQUEST["s2member_pro_ccbill_return"])) || (isset ($_REQUEST["s2member_pro_ccbill_notify"]) && strlen ($_REQUEST["s2member_pro_ccbill_notify"])))
							{
								$postvars = c_ws_plugin__s2member_utils_strings::trim_deep (stripslashes_deep ($_REQUEST));

								foreach ($postvars as $var => $value)
									if (preg_match ("/^s2member_/", $var))
										unset($postvars[$var]);

								$denial_digest_vars = $postvars["denialId"] . "0";
								$approval_digest_vars = $postvars["subscription_id"] . "1";

								if ($postvars["responseDigest"] === md5 ($approval_digest_vars . $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_ccbill_salt_key"]))
									return $postvars;

								else if ($postvars["responseDigest"] === md5 ($denial_digest_vars . $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_ccbill_salt_key"]))
									return $postvars;

								else // Nope.
									return false;
							}
						else // Nope.
							return false;
					}
			}
	}
