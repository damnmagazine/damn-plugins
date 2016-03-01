<?php
/**
* AliPay utilities.
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
* @package s2Member\AliPay
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");

if (!class_exists ("c_ws_plugin__s2member_pro_alipay_utilities"))
	{
		/**
		* AliPay utilities.
		*
		* @package s2Member\AliPay
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_alipay_utilities
			{
				/**
				* Generates an AliPay link.
				*
				* @package s2Member\AliPay
				* @since 1.5
				*
				* @param array $vars An array of variables to place in the link.
				* @return string Full URL to AliPay checkout.
				*/
				public static function alipay_link_gen ($vars = FALSE)
					{
						$vars["_input_charset"] = "utf-8";
						ksort($vars) . reset ($vars);

						$query = ""; // Initialize query string.
						$_q = ""; // Initialize the unencoded query.

						$gateway = "https://www.alipay.com/cooperate/gateway.do";

						foreach (c_ws_plugin__s2member_utils_strings::trim_deep ($vars) as $var => $value)
							if ($var && strlen ($value) && !preg_match ("/^(sign|sign_type)$/", $var))
								{
									$query .= (($query) ? "&" : "") . $var . "=" . ((preg_match ("/^http(s)?\:\/\//i", $value)) ? rawurlencode ($value) : urlencode ($value));
									$_q .= (($_q) ? "&" : "") . $var . "=" . $value; // This version is used to generate the digital signature.
								}

						return $gateway . "?" . $query . "&sign=" . urlencode (md5 ($_q . $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_security_code"])) . "&sign_type=MD5";
					}
				/**
				* Get ``$_POST`` or ``$_REQUEST`` vars from AliPay.
				*
				* @package s2Member\AliPay
				* @since 1.5
				*
				* @return array|bool An array of verified AliPay ``$_POST`` or ``$_REQUEST`` vars, else false.
				*/
				public static function alipay_postvars ()
					{
						if (!empty($_REQUEST["notify_id"]) && !empty($_REQUEST["notify_type"]) && preg_match ("/^trade_status_sync$/i", $_REQUEST["notify_type"]) && !empty($_REQUEST["sign"]))
							{
								$postvars = c_ws_plugin__s2member_utils_strings::trim_deep (stripslashes_deep ($_REQUEST));

								foreach ($postvars as $var => $value)
									if (preg_match ("/^s2member_/", $var))
										unset($postvars[$var]);

								ksort($postvars) . reset ($postvars);

								$_q = ""; // Initialize unencoded query.

								$gateway = "https://www.alipay.com/cooperate/gateway.do";

								foreach ($postvars as $var => $value)
									if ($var && strlen ($value) && !preg_match ("/^(sign|sign_type)$/", $var))
										$_q .= (($_q) ? "&" : "") . $var . "=" . $value;

								if ($postvars["sign"] === md5 ($_q . $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_security_code"])
								&& preg_match ("/true$/i", trim (c_ws_plugin__s2member_utils_urls::remote ($gateway . "?service=notify_verify&partner=" . urlencode ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_partner_id"]) . "&notify_id=" . urlencode ($postvars["notify_id"]), "", array("timeout" => 20)))))
									return $postvars;

								else // Nope.
									return false;
							}
						else // Nope.
							return false;
					}
			}
	}
