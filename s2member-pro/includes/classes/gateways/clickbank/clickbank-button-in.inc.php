<?php
/**
* Shortcode `[s2Member-Pro-ClickBank-Button /]` (inner processing routines).
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
* @package s2Member\ClickBank
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");

if(!class_exists("c_ws_plugin__s2member_pro_clickbank_button_in"))
	{
		/**
		* Shortcode `[s2Member-Pro-ClickBank-Button /]` (inner processing routines).
		*
		* @package s2Member\ClickBank
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_clickbank_button_in
			{
				/**
				* Shortcode `[s2Member-Pro-ClickBank-Button /]`.
				*
				* @package s2Member\ClickBank
				* @since 1.5
				*
				* @attaches-to ``add_shortcode("s2Member-Pro-ClickBank-Button");``
				*
				* @param array $attr An array of Attributes.
				* @param string $content Content inside the Shortcode.
				* @param string $shortcode The actual Shortcode name itself.
				* @return string The resulting ClickBank Button Code, HTML markup.
				*/
				public static function sc_clickbank_button($attr = FALSE, $content = FALSE, $shortcode = FALSE)
					{
						c_ws_plugin__s2member_no_cache::no_cache_constants /* No caching on pages that contain this Payment Button. */(true);

						$attr = /* Force array. Trim quote entities. */ c_ws_plugin__s2member_utils_strings::trim_qts_deep((array)$attr);

						$attr = shortcode_atts(array("cbp" => "0", "cbskin" => "", "cbfid" => "", "cbur" => "", "cbf" => "auto", "tid" => "", "vtid" => "", "ids" => "0", "exp" => "72", "level" => "1", "ccaps" => "", "desc" => "", "custom" => $_SERVER["HTTP_HOST"], "tp" => "0", "tt" => "D", "rp" => "1", "rt" => "M", "rr" => "1", "modify" => "0", "cancel" => "0", "sp" => "0", "image" => "default", "output" => "anchor"), $attr);

						$attr["tt"] = /* Term lengths absolutely must be provided in upper-case format. Only after running shortcode_atts(). */ strtoupper($attr["tt"]);
						$attr["rt"] = /* Term lengths absolutely must be provided in upper-case format. Only after running shortcode_atts(). */ strtoupper($attr["rt"]);
						$attr["ccaps"] = /* Custom Capabilities must be typed in lower-case format. Only after running shortcode_atts(). */ strtolower($attr["ccaps"]);
						$attr["ccaps"] = /* Custom Capabilities should not have spaces. */ str_replace(" ", "", $attr["ccaps"]);
						$attr["rr"] = /* Lifetime Subscriptions do NOT recur. Only after running shortcode_atts(). */ ($attr["rt"] === "L") ? "0" : $attr["rr"];
						$attr["rr"] = /* Independent Ccaps do NOT recur. Only after running shortcode_atts(). */ ($attr["level"] === "*") ? "0" : $attr["rr"];

						$attr["desc"] = str_replace("+", "plus", $attr["desc"]); // Workaround for a known bug @ ClickBank.
						// ClickBank will NOT properly parse `+` signs in URLs leading to (and returning from) ClickBank checkout forms.

						$attr["desc"] = str_replace(array("&amp;", "&"), "and", $attr["desc"]); // Workaround for a known bug @ ClickBank.
						// ClickBank will NOT properly parse `&` signs in URLs leading to (and returning from) ClickBank checkout forms.

						if($attr["cbur"] && $attr["cbf"] === "auto" && !empty($_REQUEST["cbf"]))
							$attr["cbf"] = esc_html((string)$_REQUEST["cbf"]);
						else if(!$attr["cbur"] || $attr["cbf"] === "auto") $attr["cbf"] = "";

						if /* Modifications/Cancellations. */($attr["modify"] || $attr["cancel"])
							{
								$default_image = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"]."/images/clickbank-edit-button.png";

								$code = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(dirname(dirname(__FILE__))))."/templates/buttons/clickbank-cancellation-button.php")));
								$code = preg_replace("/%%images%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"]."/images")), $code);
								$code = preg_replace("/%%wpurl%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(home_url())), $code);

								$code = $_code = ($attr["image"] && $attr["image"] !== "default") ? preg_replace('/ src\="(.*?)"/', ' src="'.c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($attr["image"])).'"', $code) : preg_replace('/ src\="(.*?)"/', ' src="'.c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($default_image)).'"', $code);

								$code = ($attr["output"] === "anchor") ? /* Buttons already in anchor format. */ $code : $code;
								if($attr["output"] === "url" && preg_match('/ href\="(.*?)"/', $code, $m) && ($href = $m[1]))
									$code = ($url = c_ws_plugin__s2member_utils_urls::n_amps($href));

								unset /* Just a little housekeeping */($href, $url, $m);
							}
						else if /* Specific Post/Page Buttons. */($attr["sp"])
							{
								$default_image = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"]."/images/clickbank-button.png";

								$attr["sp_ids_exp"] = /* Combined "sp:ids:expiration hours". */ "sp:".$attr["ids"].":".$attr["exp"];

								$code = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(dirname(dirname(__FILE__))))."/templates/buttons/clickbank-sp-checkout-button.php")));
								$code = preg_replace("/%%images%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"]."/images")), $code);
								$code = preg_replace("/%%wpurl%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(home_url())), $code);

								$code = preg_replace("/%%item%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($attr["cbp"])), $code);
								$code = preg_replace("/%%vendor%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_clickbank_username"])), $code);
								$code = preg_replace("/%%invoice%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["sp_ids_exp"])), $code);
								$code = preg_replace("/%%desc%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["desc"])), $code);
								$code = preg_replace("/%%custom%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["custom"])), $code);

								$code = preg_replace("/%%cbskin%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["cbskin"])), $code);
								$code = preg_replace("/%%cbfid%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["cbfid"])), $code);
								$code = preg_replace("/%%cbur%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["cbur"])), $code);
								$code = preg_replace("/%%cbf%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["cbf"])), $code);

								$code = preg_replace("/%%tid%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["tid"])), $code);
								$code = preg_replace("/%%vtid%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["vtid"])), $code);

								$code = str_replace(array("&amp;cbskin=&amp;", "&amp;cbfid=&amp;", "&amp;cbur=&amp;", "&amp;cbf=&amp;"), "&amp;", $code);
								$code = str_replace(array("&amp;tid=&amp;", "&amp;vtid=&amp;"), "&amp;", $code);

								$code = preg_replace("/\<\?php echo S2MEMBER_CURRENT_USER_IP; \?\>/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($_SERVER["REMOTE_ADDR"])), $code);

								$code = preg_replace("/%%referencing%%/", (($referencing = c_ws_plugin__s2member_utils_users::get_user_subscr_or_wp_id()) ? c_ws_plugin__s2member_utils_strings::esc_refs("&amp;s2_referencing=".urlencode($referencing)) : ""), $code);

								if(preg_match('/ href\="(.*?)"/', $code, $m) && ($url = c_ws_plugin__s2member_utils_urls::n_amps($m[1])))
									$code = preg_replace('/ href\=".*?"/', ' href="'.c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(c_ws_plugin__s2member_utils_urls::add_s2member_sig($url))).'"', $code);

								$code = $_code = ($attr["image"] && $attr["image"] !== "default") ? preg_replace('/ src\="(.*?)"/', ' src="'.c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($attr["image"])).'"', $code) : preg_replace('/ src\="(.*?)"/', ' src="'.c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($default_image)).'"', $code);

								$code = ($attr["output"] === "anchor") ? /* Buttons already in anchor format. */ $code : $code;
								if($attr["output"] === "url" && preg_match('/ href\="(.*?)"/', $code, $m) && ($href = $m[1]))
									$code = ($url = c_ws_plugin__s2member_utils_urls::n_amps($href));

								unset /* Just a little housekeeping */($href, $url, $m);
							}
						else if /* Independent Custom Capabilities. */($attr["level"] === "*")
							{
								$default_image = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"]."/images/clickbank-button.png";

								$attr["level_ccaps_eotper"] = (!$attr["rr"] && $attr["rt"] !== "L") ? $attr["level"].":".$attr["ccaps"].":".$attr["rp"]." ".$attr["rt"] : $attr["level"].":".$attr["ccaps"];
								$attr["level_ccaps_eotper"] = /* Clean any trailing separators from this string. */ rtrim($attr["level_ccaps_eotper"], ":");

								$code = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(dirname(dirname(__FILE__))))."/templates/buttons/clickbank-ccaps-checkout-button.php")));
								$code = preg_replace("/%%images%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"]."/images")), $code);
								$code = preg_replace("/%%wpurl%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(home_url())), $code);

								$code = preg_replace("/%%item%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($attr["cbp"])), $code);
								$code = preg_replace("/%%vendor%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_clickbank_username"])), $code);
								$code = preg_replace("/%%invoice%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["level_ccaps_eotper"])), $code);
								$code = preg_replace("/%%desc%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["desc"])), $code);
								$code = preg_replace("/%%custom%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["custom"])), $code);

								$code = preg_replace("/%%cbskin%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["cbskin"])), $code);
								$code = preg_replace("/%%cbfid%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["cbfid"])), $code);
								$code = preg_replace("/%%cbur%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["cbur"])), $code);
								$code = preg_replace("/%%cbf%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["cbf"])), $code);

								$code = preg_replace("/%%tid%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["tid"])), $code);
								$code = preg_replace("/%%vtid%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["vtid"])), $code);

								$code = str_replace(array("&amp;cbskin=&amp;", "&amp;cbfid=&amp;", "&amp;cbur=&amp;", "&amp;cbf=&amp;"), "&amp;", $code);
								$code = str_replace(array("&amp;tid=&amp;", "&amp;vtid=&amp;"), "&amp;", $code);

								$code = (!$attr["rr"]) ? preg_replace("/&amp;s2_subscr_id\=s2-\<\?php echo uniqid\(\); \?\>/", "", $code) : preg_replace("/\<\?php echo uniqid\(\); \?\>/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode(uniqid())), $code);

								$code = preg_replace("/\<\?php echo S2MEMBER_CURRENT_USER_IP; \?\>/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($_SERVER["REMOTE_ADDR"])), $code);

								$code = preg_replace("/%%referencing%%/", (($referencing = c_ws_plugin__s2member_utils_users::get_user_subscr_or_wp_id()) ? c_ws_plugin__s2member_utils_strings::esc_refs("&amp;s2_referencing=".urlencode($referencing)) : ""), $code);

								if(preg_match('/ href\="(.*?)"/', $code, $m) && ($url = c_ws_plugin__s2member_utils_urls::n_amps($m[1])))
									$code = preg_replace('/ href\=".*?"/', ' href="'.c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(c_ws_plugin__s2member_utils_urls::add_s2member_sig($url))).'"', $code);

								$code = $_code = ($attr["image"] && $attr["image"] !== "default") ? preg_replace('/ src\="(.*?)"/', ' src="'.c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($attr["image"])).'"', $code) : preg_replace('/ src\="(.*?)"/', ' src="'.c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($default_image)).'"', $code);

								$code = ($attr["output"] === "anchor") ? /* Buttons already in anchor format. */ $code : $code;
								if($attr["output"] === "url" && preg_match('/ href\="(.*?)"/', $code, $m) && ($href = $m[1]))
									$code = ($url = c_ws_plugin__s2member_utils_urls::n_amps($href));

								unset /* Just a little housekeeping */($href, $url, $m);
							}
						else // Otherwise, we'll process this Button normally, using Membership routines.
							{
								$default_image = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"]."/images/clickbank-button.png";

								$attr["level_ccaps_eotper"] = (!$attr["rr"] && $attr["rt"] !== "L") ? $attr["level"].":".$attr["ccaps"].":".$attr["rp"]." ".$attr["rt"] : $attr["level"].":".$attr["ccaps"];
								$attr["level_ccaps_eotper"] = /* Clean any trailing separators from this string. */ rtrim($attr["level_ccaps_eotper"], ":");

								$code = trim(c_ws_plugin__s2member_utilities::evl(file_get_contents(dirname(dirname(dirname(dirname(__FILE__))))."/templates/buttons/clickbank-checkout-button.php")));
								$code = preg_replace("/%%images%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"]."/images")), $code);
								$code = preg_replace("/%%wpurl%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(home_url())), $code);

								$code = preg_replace("/%%item%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($attr["cbp"])), $code);
								$code = preg_replace("/%%vendor%%/", c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_clickbank_username"])), $code);
								$code = preg_replace("/%%invoice%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["level_ccaps_eotper"])), $code);
								$code = preg_replace("/%%desc%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["desc"])), $code);
								$code = preg_replace("/%%p1%%/", (($attr["rr"]) ? c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["tp"]." ".$attr["tt"])) : ""), $code);
								$code = preg_replace("/%%p3%%/", (($attr["rr"]) ? c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["rp"]." ".$attr["rt"])) : ""), $code);
								$code = preg_replace("/%%custom%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["custom"])), $code);

								$code = preg_replace("/%%cbskin%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["cbskin"])), $code);
								$code = preg_replace("/%%cbfid%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["cbfid"])), $code);
								$code = preg_replace("/%%cbur%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["cbur"])), $code);
								$code = preg_replace("/%%cbf%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["cbf"])), $code);

								$code = preg_replace("/%%tid%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["tid"])), $code);
								$code = preg_replace("/%%vtid%%/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($attr["vtid"])), $code);

								$code = str_replace(array("&amp;cbskin=&amp;", "&amp;cbfid=&amp;", "&amp;cbur=&amp;", "&amp;cbf=&amp;"), "&amp;", $code);
								$code = str_replace(array("&amp;tid=&amp;", "&amp;vtid=&amp;"), "&amp;", $code);

								$code = (!$attr["rr"]) ? preg_replace("/&amp;s2_subscr_id\=s2-\<\?php echo uniqid\(\); \?\>/", "", $code) : preg_replace("/\<\?php echo uniqid\(\); \?\>/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode(uniqid())), $code);

								$code = preg_replace("/\<\?php echo S2MEMBER_CURRENT_USER_IP; \?\>/", c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($_SERVER["REMOTE_ADDR"])), $code);

								$code = preg_replace("/%%referencing%%/", (($referencing = c_ws_plugin__s2member_utils_users::get_user_subscr_or_wp_id()) ? c_ws_plugin__s2member_utils_strings::esc_refs("&amp;s2_referencing=".urlencode($referencing)) : ""), $code);

								if(preg_match('/ href\="(.*?)"/', $code, $m) && ($url = c_ws_plugin__s2member_utils_urls::n_amps($m[1])))
									$code = preg_replace('/ href\=".*?"/', ' href="'.c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr(c_ws_plugin__s2member_utils_urls::add_s2member_sig($url))).'"', $code);

								$code = $_code = ($attr["image"] && $attr["image"] !== "default") ? preg_replace('/ src\="(.*?)"/', ' src="'.c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($attr["image"])).'"', $code) : preg_replace('/ src\="(.*?)"/', ' src="'.c_ws_plugin__s2member_utils_strings::esc_refs(esc_attr($default_image)).'"', $code);

								$code = ($attr["output"] === "anchor") ? /* Buttons already in anchor format. */ $code : $code;
								if($attr["output"] === "url" && preg_match('/ href\="(.*?)"/', $code, $m) && ($href = $m[1]))
									$code = ($url = c_ws_plugin__s2member_utils_urls::n_amps($href));

								unset /* Just a little housekeeping */($href, $url, $m);
							}

						return /* Button. */ $code;
					}
			}
	}
