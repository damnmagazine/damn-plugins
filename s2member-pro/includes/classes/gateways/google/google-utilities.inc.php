<?php
/**
* Google utilities.
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
* @package s2Member\Google
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit ("Do not access this file directly.");

if (!class_exists ("c_ws_plugin__s2member_pro_google_utilities"))
	{
		/**
		* Google utilities.
		*
		* @package s2Member\Google
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_google_utilities
			{
				/**
				* Converts a "Period Term" into a Google periodicity.
				*
				* @package s2Member\Google
				* @since 1.5
				*
				* @param string $period_term A "Period Term" combination.
				* @return string The Google Wallet equivalent for ``$period_term``.
				* 	One of `daily`, `weekly`, `semi_monthly`, `monthly`, `every_two_months`, `quarterly`, or `yearly`.
				* 	Defaults to `monthly` if ``$period_term`` is not configured properly.
				*/
				public static function google_periodicity ($period_term = FALSE)
					{
						list ($num, $span) = preg_split ("/ /", strtoupper ($period_term), 2);
						$num = (int)$num; // Force this to an integer.

						if ($num === 1 && $span === "D")
							return "daily";

						else if ($num === 1 && $span === "W")
							return "weekly";

						else if ($num === 2 && $span === "W")
							return "semi_monthly";

						else if ($num === 1 && $span === "M")
							return "monthly";

						else if ($num === 2 && $span === "M")
							return "every_two_months";

						else if ($num === 3 && $span === "M")
							return "quarterly";

						else if ($num === 1 && $span === "Y")
							return "yearly";

						return "monthly";
					}
				/**
				* Parses s2Vars from Google IPN Notifications.
				*
				* @package s2Member\Google
				* @since 1.5
				*
				* @param array $jwt The JWT from Google.
				* @return array|bool An array of s2Vars, else false on failure.
				*/
				public static function google_parse_s2vars ($jwt = FALSE)
					{
						if (!empty($jwt["request"]["sellerData"]))
							return json_decode($jwt["request"]["sellerData"], TRUE);
						return false;
					}
				/**
				* Get ``$_POST`` or ``$_REQUEST`` vars from Google.
				*
				* @package s2Member\Google
				* @since 1.5
				*
				* @return array|bool An array of verified ``$_POST`` or ``$_REQUEST`` variables, else false.
				*
				* @todo Continue optimizing this routine with ``empty()`` and ``isset()``.
				* @todo Candidate for the use of ``ifsetor()``?
				*/
				public static function google_postvars ()
					{
						include_once dirname(dirname(dirname(dirname(__FILE__)))).'/_xtnls/JWT.php';

						if (!empty($_REQUEST["s2member_pro_google_notify"]) && !empty($_REQUEST["jwt"]))
							if(is_object($jwt = JWT::decode(stripslashes((string)$_REQUEST["jwt"]), $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_google_merchant_key"])))
								{
									$jwt = (array)$jwt;

									if(!empty($jwt["request"]))
										$jwt["request"] = (array)$jwt["request"];

									if(!empty($jwt["request"]["initialPayment"]))
										$jwt["request"]["initialPayment"] = (array)$jwt["request"]["initialPayment"];

									if(!empty($jwt["request"]["recurrence"]))
										$jwt["request"]["recurrence"] = (array)$jwt["request"]["recurrence"];

									if(!empty($jwt["response"]))
										$jwt["response"] = (array)$jwt["response"];

									return $jwt;
								}
						return false;
					}
				/**
				* Calculates start date for a Recurring Payment Profile.
				*
				* @package s2Member\Google
				* @since 1.5
				*
				* @param string $period1 Optional. A "Period Term" combination. Defaults to `0 D`.
				* @param string $period3 Optional. A "Period Term" combination. Defaults to `0 D`.
				* @return int The start time, a Unix timestamp.
				*/
				public static function google_start_time ($period1 = FALSE, $period3 = FALSE)
					{
						if (!($p1_time = 0) && ($period1 = trim (strtoupper ($period1))))
							{
								list ($num, $span) = preg_split ("/ /", $period1, 2);

								$days = 0; // Days start at 0.

								if (is_numeric ($num) && !is_numeric ($span))
									{
										$days = ($span === "D") ? 1 : $days;
										$days = ($span === "W") ? 7 : $days;
										$days = ($span === "M") ? 30 : $days;
										$days = ($span === "Y") ? 365 : $days;
									}

								$p1_days = (int)$num * (int)$days;
								$p1_time = $p1_days * 86400;
							}

						if (!($p3_time = 0) && ($period3 = trim (strtoupper ($period3))))
							{
								list ($num, $span) = preg_split ("/ /", $period3, 2);

								$days = 0; // Days start at 0.

								if (is_numeric ($num) && !is_numeric ($span))
									{
										$days = ($span === "D") ? 1 : $days;
										$days = ($span === "W") ? 7 : $days;
										$days = ($span === "M") ? 30 : $days;
										$days = ($span === "Y") ? 365 : $days;
									}

								$p3_days = (int)$num * (int)$days;
								$p3_time = $p3_days * 86400;
							}

						$start_time = strtotime ("now") + $p1_time + $p3_time;

						$start_time = ($start_time <= 0) ? strtotime ("now") : $start_time;

						$start_time = $start_time + 43200; // + 12 hours.
						// This prevents date clashes with Google's API server.

						return $start_time;
					}
			}
	}
