<?php
/**
* PayPal Standard extras (introduced by s2Member Pro).
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
* @package s2Member\PayPal
* @since 110604
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit ("Do not access this file directly.");

if (!class_exists ("c_ws_plugin__s2member_pro_paypal_extras"))
	{
		/**
		* PayPal Standard extras (introduced by s2Member Pro).
		*
		* @package s2Member\PayPal
		* @since 110604
		*/
		class c_ws_plugin__s2member_pro_paypal_extras
			{
				/**
				* Adds extra default Attributes for PayPal Button Shortcodes.
				*
				* @package s2Member\PayPal
				* @since 110604
				*
				* @attaches-to ``add_filter("ws_plugin__s2member_sc_paypal_button_default_attrs");``
				*
				* @param array $default_attrs An array of default Attributes, passed through by the Filter.
				* @param array $vars An array of defined variables, passed through by the Filter.
				* @return array The array of ``$default_attrs``, after adding extras.
				*/
				public static function paypal_button_default_attrs ($default_attrs = FALSE, $vars = FALSE)
					{
						return array_merge ((array)$default_attrs, array("success" => ""));
					}
				/**
				* Cleans up extra Attributes in PayPal Button Shortcodes.
				*
				* @package s2Member\PayPal
				* @since 110604
				*
				* @attaches-to ``add_action("ws_plugin__s2member_before_sc_paypal_button_after_shortcode_atts");``
				*
				* @param array $vars An array of defined variables, passed in by the Action Hook.
				* @return null
				*/
				public static function paypal_button_after_attrs ($vars = FALSE)
					{
						$attr = &$vars["__refs"]["attr"]; // By reference.

						$attr["success"] = str_ireplace (array("&#038;", "&amp;"), "&", $attr["success"]);

						return /* Return for uniformity. */;
					}
				/**
				* Handles Success Return URL for PayPal Button Shortcodes.
				*
				* @package s2Member\PayPal
				* @since 110604
				*
				* @attaches-to ``add_filter("ws_plugin__s2member_during_sc_paypal_button_success_return_url");``
				*
				* @param string $success_return_url The current Return URL, passed through by the Filter.
				* @param array $vars An array of defined variables, passed through by the Filter.
				* @return string The ``$success_return_url``, after possible modification.
				*/
				public static function paypal_button_success_return_url ($success_return_url = FALSE, $vars = FALSE)
					{
						$attr = $vars["attr"]; // Shortcode Attributes.

						if ($attr["success"]) // Using a custom success return URL?
							return ($success_return_url = add_query_arg ("s2member_paypal_return_success", rawurlencode ($attr["success"]), $success_return_url));

						else // Else default.
							return $success_return_url;
					}
			}
	}
