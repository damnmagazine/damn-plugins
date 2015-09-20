<?php
/**
* Shortcode `[s2Member-Pro-Google-Button /]` (inner processing routines).
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

if (!class_exists ("c_ws_plugin__s2member_pro_google_button_in"))
	{
		/**
		* Shortcode `[s2Member-Pro-Google-Button /]` (inner processing routines).
		*
		* @package s2Member\Google
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_google_button_in
			{
				/**
				* Shortcode `[s2Member-Pro-Google-Button /]`.
				*
				* @package s2Member\Google
				* @since 1.5
				*
				* @attaches-to ``add_shortcode("s2Member-Pro-Google-Button");``
				*
				* @param array $attr An array of Attributes.
				* @param string $content Content inside the Shortcode.
				* @param string $shortcode The actual Shortcode name itself.
				* @return string The resulting Google Button Code; HTML markup.
				*/
				public static function sc_google_button ($attr = FALSE, $content = FALSE, $shortcode = FALSE)
					{
						c_ws_plugin__s2member_no_cache::no_cache_constants(true);

						$attr = c_ws_plugin__s2member_utils_strings::trim_qts_deep ((array)$attr); $jwt_attr = c_ws_plugin__s2member_utils_encryption::encrypt(serialize($attr));
						$attr = shortcode_atts(array("ids" => "0", "exp" => "72", "level" => "1", "ccaps" => "", "desc" => "", "cc" => "USD", "custom" => $_SERVER["HTTP_HOST"], "ta" => "0", "tp" => "0", "tt" => "D", "ra" => "0.01", "rp" => "1", "rt" => "M", "rr" => "1", "rrt" => "", "modify" => "0", "cancel" => "0", "sp" => "0", "image" => "default", "output" => "anchor", "success" => "", "failure" => ""), $attr);

						if /* Modifications/Cancellations. */ ($attr["modify"] || $attr["cancel"])
							{
								$default_image = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images/google-edit-button.png";

								$code = trim (c_ws_plugin__s2member_utilities::evl (file_get_contents (dirname (dirname (dirname (dirname (__FILE__)))) . "/templates/buttons/google-cancellation-button.php")));
								$code = preg_replace ("/%%images%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images")), $code);
								$code = preg_replace ("/%%wpurl%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr (home_url ())), $code);

								$code = $_code = ($attr["image"] && $attr["image"] !== "default")
									? preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($attr["image"])) . '"', $code)
									: preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($default_image)) . '"', $code);

								$code = ($attr["output"] === "anchor") ? /* Buttons already in anchor format. */ $code : $code;
								if ($attr["output"] === "url" && preg_match ('/ href\="(.*?)"/', $code, $m) && ($href = $m[1]))
									$code = ($url = c_ws_plugin__s2member_utils_urls::n_amps ($href));

								unset /* Just a little housekeeping */ ($href, $url, $m);
							}
						else if /* Specific Post/Page Buttons. */ ($attr["sp"])
							{
								$default_image = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images/google-wallet-co.png";

								$code = trim (c_ws_plugin__s2member_utilities::evl (file_get_contents (dirname (dirname (dirname (dirname (__FILE__)))) . "/templates/buttons/google-sp-checkout-button.php")));
								$code = preg_replace ("/%%images%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images")), $code);
								$code = preg_replace ("/%%wpurl%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr (home_url ())), $code);

								$code = preg_replace ("/%%jwt_attr%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($jwt_attr)), $code);
								$code = preg_replace (array("/%%success%%/", "/%%failure%%/"), array($attr["success"], $attr["failure"]), $code);

								$code = $_code = ($attr["image"] && $attr["image"] !== "default")
									? preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($attr["image"])) . '"', $code)
									: preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($default_image)) . '"', $code);
							}
						else if /* Independent Custom Capabilities. */ ($attr["level"] === "*")
							{
								$default_image = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images/google-wallet-co.png";

								$code = trim (c_ws_plugin__s2member_utilities::evl (file_get_contents (dirname (dirname (dirname (dirname (__FILE__)))) . "/templates/buttons/google-ccaps-checkout-button.php")));
								$code = preg_replace ("/%%images%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images")), $code);
								$code = preg_replace ("/%%wpurl%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr (home_url ())), $code);

								$code = preg_replace ("/%%jwt_attr%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($jwt_attr)), $code);
								$code = preg_replace (array("/%%success%%/", "/%%failure%%/"), array($attr["success"], $attr["failure"]), $code);

								$code = $_code = ($attr["image"] && $attr["image"] !== "default")
									? preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($attr["image"])) . '"', $code)
									: preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($default_image)) . '"', $code);
							}
						else // Otherwise, we'll process this Button normally; nothing special in this case.
							{
								$default_image = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images/google-wallet-co.png";

								$code = trim (c_ws_plugin__s2member_utilities::evl (file_get_contents (dirname (dirname (dirname (dirname (__FILE__)))) . "/templates/buttons/google-checkout-button.php")));
								$code = preg_replace ("/%%images%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images")), $code);
								$code = preg_replace ("/%%wpurl%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr (home_url ())), $code);

								$code = preg_replace ("/%%jwt_attr%%/", c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($jwt_attr)), $code);
								$code = preg_replace (array("/%%success%%/", "/%%failure%%/"), array($attr["success"], $attr["failure"]), $code);

								$code = $_code = ($attr["image"] && $attr["image"] !== "default")
									? preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($attr["image"])) . '"', $code)
									: preg_replace ('/ src\="(.*?)"/', ' src="' . c_ws_plugin__s2member_utils_strings::esc_refs (esc_attr ($default_image)) . '"', $code);
							}
						return $code;
					}
			}
	}
