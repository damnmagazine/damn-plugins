<?php
/**
* Authorize.Net CSS/JS for theme integration.
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

if (!class_exists ("c_ws_plugin__s2member_pro_authnet_css_js"))
	{
		/**
		* Authorize.Net CSS for theme integration.
		*
		* @package s2Member\CSS_JS
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_authnet_css_js
			{
				/**
				* Adds the CSS for this Payment Gateway.
				*
				* @package s2Member\CSS_JS
				* @since 1.5
				*
				* @attaches-to ``add_action("ws_plugin__s2member_during_css");``
				*
				* @param array $vars Expects an array of defined vars to be passed in by the Action Hook.
				* @return null
				*/
				public static function authnet_css ($vars = FALSE)
					{
						$u = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"];
						$i = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images";

						if (!apply_filters("ws_plugin__s2member_pro_css_affects_gateways", true) // Does it affect this?
						|| has_action ("ws_plugin__s2member_during_css", "c_ws_plugin__s2member_pro_css_js::css")) // Only if CSS loads.
							// This check allows a site owner to disable all CSS by removing the main CSS Hook in one shot.
							{
								echo "\n"; // Add a line break before inclusion.

								include_once dirname (dirname (dirname (dirname (__FILE__)))) . "/separates/gateways/authnet/authnet.css";
							}

						return /* Return for uniformity. */;
					}
				/**
				* Adds the JavaScript for this Payment Gateway.
				*
				* @package s2Member\CSS_JS
				* @since 1.5
				*
				* @attaches-to ``add_action("ws_plugin__s2member_during_js_w_globals");``
				*
				* @param array $vars Expects an array of defined vars to be passed in by the Action Hook.
				* @return null
				*/
				public static function authnet_js_w_globals ($vars = FALSE)
					{
						$g = "var S2MEMBER_PRO_AUTHNET_GATEWAY = true,";

						$g = trim ($g, " ,") . ";"; // Trim & add semicolon.

						$u = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"];
						$i = $GLOBALS["WS_PLUGIN__"]["s2member_pro"]["c"]["dir_url"] . "/images";

						echo "\n" . $g . "\n"; // Add a line break before inclusion.

						include_once dirname (dirname (dirname (dirname (__FILE__)))) . "/separates/gateways/authnet/authnet-min.js";

						return /* Return for uniformity. */;
					}
			}
	}
