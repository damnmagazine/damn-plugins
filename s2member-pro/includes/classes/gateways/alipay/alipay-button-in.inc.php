<?php
/**
* Shortcode `[s2Member-Pro-AliPay-Button /]` (inner processing routines).
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

if (!class_exists ("c_ws_plugin__s2member_pro_alipay_button_in"))
	{
		/**
		* Shortcode `[s2Member-Pro-AliPay-Button /]` (inner processing routines).
		*
		* @package s2Member\AliPay
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_alipay_button_in
			{
				/**
				* Shortcode `[s2Member-Pro-AliPay-Button /]`.
				*
				* @package s2Member\AliPay
				* @since 1.5
				*
				* @attaches-to ``add_shortcode("s2Member-Pro-AliPay-Button");``
				*
				* @param array $attr An array of Attributes.
				* @param string $content Content inside the Shortcode.
				* @param string $shortcode The actual Shortcode name itself.
				* @return string The resulting AliPay Button Code, HTML markup.
				*/
				public static function sc_alipay_button ($attr = FALSE, $content = FALSE, $shortcode = FALSE)
					{
						c_ws_plugin__s2member_no_cache::no_cache_constants /* No caching on pages that contain this Payment Button. */ (true);

						$attr = /* Force array. Trim quote entities. */ c_ws_plugin__s2member_utils_strings::trim_qts_deep ((array)$attr);

						$attr = shortcode_atts (array("ids" => "0", "exp" => "72", "level" => "1", "ccaps" => "", "desc" => "", "custom" => $_SERVER["HTTP_HOST"], "ra" => "0.01", "rp" => "1", "rt" => "M", "sp" => "0", "success" => "", "image" => "default", "output" => "anchor"), $attr);

						$attr["rt"] = /* Term lengths absolutely must be provided in upper-case format. Only after running shortcode_atts(). */ strtoupper ($attr["rt"]);
						$attr["ccaps"] = /* Custom Capabilities must be typed in lower-case format. Only after running shortcode_atts(). */ strtolower ($attr["ccaps"]);
						$attr["ccaps"] = /* Custom Capabilities should not have spaces. */ str_replace(" ", "", $attr["ccaps"]);
						$attr["success"] = /* Convert ampersands. */ str_ireplace (array("&#038;", "&amp;"), "&", $attr["success"]);

						if /* Specific Post/Page Buttons. */ ($attr["sp"])
							{
								$default_image = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images/alipay-button.gif";

								$attr["sp_ids_exp"] = /* Combined "sp:ids:expiration hours". */ "sp:" . $attr["ids"] . ":" . $attr["exp"];

								$code = trim (c_ws_plugin__s2member_utilities::evl (file_get_contents (dirname (dirname (dirname (dirname (__FILE__)))) . "/templates/buttons/alipay-sp-checkout-button.php")));
								$code = preg_replace ("/%%images%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images")), $code);
								$code = preg_replace ("/%%wpurl%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr (home_url ())), $code);

								$vars = array("service" => "create_direct_pay_by_user", "payment_type" => 1, "partner" => $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_partner_id"], "seller_email" => $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_seller_email"], "subject" => $_SERVER["HTTP_HOST"], "body" => $attr["desc"], "out_trade_no" => uniqid () . "~" . $attr["sp_ids_exp"] . (($referencing = c_ws_plugin__s2member_utils_users::get_user_subscr_or_wp_id ()) ? "~" . $referencing : "~") . "~" . $_SERVER["REMOTE_ADDR"], "extra_common_param" => $attr["custom"], "total_fee" => $attr["ra"], "paymethod" => "directPay", "show_url" => home_url ("/"), "return_url" => (($attr["success"]) ? $attr["success"] : home_url ("/?s2member_pro_alipay_return=1")), "notify_url" => home_url ("/"));

								$code = preg_replace ("/%%url%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($url = c_ws_plugin__s2member_pro_alipay_utilities::alipay_link_gen ($vars))), $code);

								$code = $_code = ($attr["image"] && $attr["image"] !== "default") ? preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($attr["image"])) . '"', $code) : preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($default_image)) . '"', $code);

								$code = ($attr["output"] === "anchor") ? /* Buttons already in anchor format. */ $code : $code;
								$code = ($attr["output"] === "url") ? /* From the routine above. */ $url : $code;

								unset /* Just a little housekeeping */ ($href, $url, $m);
							}
						else if /* Independent Custom Capabilities. */ ($attr["level"] === "*")
							{
								$default_image = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images/alipay-button.gif";

								$attr["level_ccaps_eotper"] = ($attr["rt"] !== "L") ? $attr["level"] . ":" . $attr["ccaps"] . ":" . $attr["rp"] . " " . $attr["rt"] : $attr["level"] . ":" . $attr["ccaps"];
								$attr["level_ccaps_eotper"] = /* Clean any trailing separators from this string. */ rtrim ($attr["level_ccaps_eotper"], ":");

								$code = trim (c_ws_plugin__s2member_utilities::evl (file_get_contents (dirname (dirname (dirname (dirname (__FILE__)))) . "/templates/buttons/alipay-ccaps-checkout-button.php")));
								$code = preg_replace ("/%%images%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images")), $code);
								$code = preg_replace ("/%%wpurl%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr (home_url ())), $code);

								$vars = array("service" => "create_direct_pay_by_user", "payment_type" => 1, "partner" => $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_partner_id"], "seller_email" => $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_seller_email"], "subject" => $_SERVER["HTTP_HOST"], "body" => $attr["desc"], "out_trade_no" => uniqid () . "~" . $attr["level_ccaps_eotper"] . (($referencing = c_ws_plugin__s2member_utils_users::get_user_subscr_or_wp_id ()) ? "~" . $referencing : "~") . "~" . $_SERVER["REMOTE_ADDR"], "extra_common_param" => $attr["custom"], "total_fee" => $attr["ra"], "paymethod" => "directPay", "show_url" => home_url ("/"), "return_url" => (($attr["success"] && !$referencing) ? $attr["success"] : home_url ("/?s2member_pro_alipay_return=1")), "notify_url" => home_url ("/"));

								$code = preg_replace ("/%%url%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($url = c_ws_plugin__s2member_pro_alipay_utilities::alipay_link_gen ($vars))), $code);

								$code = $_code = ($attr["image"] && $attr["image"] !== "default") ? preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($attr["image"])) . '"', $code) : preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($default_image)) . '"', $code);

								$code = ($attr["output"] === "anchor") ? /* Buttons already in anchor format. */ $code : $code;
								$code = ($attr["output"] === "url") ? /* From the routine above. */ $url : $code;

								unset /* Just a little housekeeping */ ($href, $url, $m);
							}
						else // Otherwise, we'll process this Button normally, using Membership routines.
							{
								$default_image = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images/alipay-button.gif";

								$attr["desc"] = (!$attr["desc"]) ? $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["level" . $attr["level"] . "_label"] : $attr["desc"];

								$attr["level_ccaps_eotper"] = ($attr["rt"] !== "L") ? $attr["level"] . ":" . $attr["ccaps"] . ":" . $attr["rp"] . " " . $attr["rt"] : $attr["level"] . ":" . $attr["ccaps"];
								$attr["level_ccaps_eotper"] = /* Clean any trailing separators from this string. */ rtrim ($attr["level_ccaps_eotper"], ":");

								$code = trim (c_ws_plugin__s2member_utilities::evl (file_get_contents (dirname (dirname (dirname (dirname (__FILE__)))) . "/templates/buttons/alipay-checkout-button.php")));
								$code = preg_replace ("/%%images%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images")), $code);
								$code = preg_replace ("/%%wpurl%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr (home_url ())), $code);

								$vars = array("service" => "create_direct_pay_by_user", "payment_type" => 1, "partner" => $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_partner_id"], "seller_email" => $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_seller_email"], "subject" => $_SERVER["HTTP_HOST"], "body" => $attr["desc"], "out_trade_no" => uniqid () . "~" . $attr["level_ccaps_eotper"] . (($referencing = c_ws_plugin__s2member_utils_users::get_user_subscr_or_wp_id ()) ? "~" . $referencing : "~") . "~" . $_SERVER["REMOTE_ADDR"], "extra_common_param" => $attr["custom"], "total_fee" => $attr["ra"], "paymethod" => "directPay", "show_url" => home_url ("/"), "return_url" => (($attr["success"] && !$referencing) ? $attr["success"] : home_url ("/?s2member_pro_alipay_return=1")), "notify_url" => home_url ("/"));

								$code = preg_replace ("/%%url%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($url = c_ws_plugin__s2member_pro_alipay_utilities::alipay_link_gen ($vars))), $code);

								$code = $_code = ($attr["image"] && $attr["image"] !== "default") ? preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($attr["image"])) . '"', $code) : preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($default_image)) . '"', $code);

								$code = ($attr["output"] === "anchor") ? /* Buttons already in anchor format. */ $code : $code;
								$code = ($attr["output"] === "url") ? /* From the routine above. */ $url : $code;

								unset /* Just a little housekeeping */ ($href, $url, $m);
							}
						return /* Button. */ $code;
					}
			}
	}
