<?php
/**
* CSS/JS integrations with theme.
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
* @package s2Member\CSS_JS
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");

if (!class_exists ("c_ws_plugin__s2member_pro_css_js"))
	{
		/**
		* CSS/JS integrations with theme.
		*
		* @package s2Member\CSS_JS
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_css_js
			{
				/**
				* Adds Pro Add-on CSS.
				*
				* @package s2Member\CSS_JS
				* @since 1.5
				*
				* @attaches-to ``add_action("ws_plugin__s2member_during_css");``
				*
				* @param array $vars Expects array of defined variables, passed in by the Action Hook.
				* @return null
				*/
				public static function css ($vars = FALSE)
					{
						$u = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"];
						$i = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images";

						echo "\n"; // Add a line break before inclusion.

						include_once dirname (dirname (__FILE__)) . "/s2member-pro.css";

						return; // Return unformity.
					}
				/**
				* Adds Pro Add-on JavaScript.
				*
				* @package s2Member\CSS_JS
				* @since 1.5
				*
				* @attaches-to ``add_action("ws_plugin__s2member_during_js_w_globals");``
				*
				* @param array $vars Expects array of defined variables, passed in by the Action Hook.
				* @return null
				*/
				public static function js_w_globals ($vars = FALSE)
					{
						$g = "var S2MEMBER_PRO_VERSION = '" . c_ws_plugin__s2member_utils_strings::esc_js_sq (S2MEMBER_PRO_VERSION) . "',";

						$g = trim ($g, " ,") . ";"; // Trim & add semicolon.

						$u = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"];
						$i = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images";

						echo "\n" . $g . "\n"; // Add a line break before inclusion.

						include_once dirname (dirname (__FILE__)) . "/s2member-pro-min.js";

						return; // Return unformity.
					}
			}
	}
