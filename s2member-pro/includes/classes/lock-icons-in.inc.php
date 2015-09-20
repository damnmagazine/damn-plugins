<?php
/**
* Pro Lock Icons (inner processing routines).
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
* @package s2Member\Lock_Icons
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");

if (!class_exists ("c_ws_plugin__s2member_pro_lock_icons_in"))
	{
		/**
		* Pro Lock Icons (inner processing routines).
		*
		* @package s2Member\Lock_Icons
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_lock_icons_in
			{
				/**
				* The "(s2)" column header, attaching an ID to it as well.
				*
				* @package s2Member\Lock_Icons
				* @since 1.5
				*
				* @param array $cols Array of columns passed through by a Filter.
				* @return array Merged array of columns, including the one for `(s2)`.
				*/
				public static function _lock_icons_return_column ($cols = FALSE)
					{
						return array_merge ($cols, array("ws_plugin__s2member_pro_lock_icons" => "(s2)"));
					}
				/**
				* Status for Tags.
				*
				* @package s2Member\Lock_Icons
				* @since 1.5
				*
				* @param string $value Existing value for this data column.
				* @param string $column_name Column ID/Name. We need to look at this to fill the `(s2)` column.
				* @param int|string $id Expecting a numeric Tag ID to be passed through by the Filter.
				* @return string If `(s2)` column, return status. Else, existing ``$value``.
				*/
				public static function _lock_icons_return_value_tags ($value = FALSE, $column_name = FALSE, $id = FALSE)
					{
						return ($column_name === "ws_plugin__s2member_pro_lock_icons") ? c_ws_plugin__s2member_pro_lock_icons_in::_return_lock_icons_description (c_ws_plugin__s2member_ptags_sp::check_specific_ptag_level_access ($id, false)) : $value;
					}
				/**
				* Status for Categories.
				*
				* @package s2Member\Lock_Icons
				* @since 1.5
				*
				* @param string $value Existing value for this data column.
				* @param string $column_name Column ID/Name. We need to look at this to fill the `(s2)` column.
				* @param int|string $id Expecting a numeric Category ID to be passed through by the Filter.
				* @return string If `(s2)` column, return status. Else, existing ``$value``.
				*/
				public static function _lock_icons_return_value_categories ($value = FALSE, $column_name = FALSE, $id = FALSE)
					{
						return ($column_name === "ws_plugin__s2member_pro_lock_icons") ? c_ws_plugin__s2member_pro_lock_icons_in::_return_lock_icons_description (c_ws_plugin__s2member_catgs_sp::check_specific_catg_level_access ($id, false)) : $value;
					}
				/**
				* Status for Pages, these are handled separately.
				*
				* @package s2Member\Lock_Icons
				* @since 1.5
				*
				* @param string $column_name Column ID/Name. We need to look at this to fill the `(s2)` column.
				* @param int|string $id Expecting a numeric Page ID to be passed by the Action Hook.
				* @return null
				*/
				public static function _lock_icons_echo_value_pages ($column_name = FALSE, $id = FALSE)
					{
						echo ($column_name === "ws_plugin__s2member_pro_lock_icons") ? c_ws_plugin__s2member_pro_lock_icons_in::_return_lock_icons_description (c_ws_plugin__s2member_pages_sp::check_specific_page_level_access ($id, false)) : "";
					}
				/**
				* Status for all other Post Types, including Custom Post Types; but excluding Pages.
				*
				* @package s2Member\Lock_Icons
				* @since 1.5
				*
				* @param string $column_name Column ID/Name. We need to look at this to fill the `(s2)` column.
				* @param int|string $id Expecting a numeric Post ID to be passed by the Action Hook.
				* @return null
				*/
				public static function _lock_icons_echo_value_post_types ($column_name = FALSE, $id = FALSE)
					{
						echo ($column_name === "ws_plugin__s2member_pro_lock_icons") ? c_ws_plugin__s2member_pro_lock_icons_in::_return_lock_icons_description (c_ws_plugin__s2member_posts_sp::check_specific_post_level_access ($id, false)) : "";
					}
				/**
				* CSS styles for Lock Icon columns.
				*
				* @package s2Member\Lock_Icons
				* @since 1.5
				*
				* @return null
				*/
				public static function _lock_icons_echo_css ()
					{
						$css = '<style type="text/css">';
						$css .= 'th.column-ws_plugin__s2member_pro_lock_icons, td.column-ws_plugin__s2member_pro_lock_icons { width: 45px; text-align:center; }';
						$css .= '</style>';

						echo apply_filters("_ws_plugin__s2member_pro_lock_icons_echo_css", $css, get_defined_vars ());
					}
				/**
				* Translates a return array into a string description.
				*
				* @package s2Member\Lock_Icons
				* @since 1.5
				*
				* @param array $array Expects an array returned by one of s2Member's security routines.
				* @return string A verbose string representation of the return array details.
				*/
				public static function _return_lock_icons_description ($array = FALSE)
					{
						$dir_url = $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["dir_url"];

						if (isset ($array["s2member_level_req"]))
							$req = 'Requires Membership Level #' . $array["s2member_level_req"];

						else if (isset ($array["s2member_ccap_req"]))
							$req = 'Requires Custom Capabilities';

						else if (isset ($array["s2member_sp_req"]))
							$req = 'Requires Specific Post/Page Access';

						$desc = (!empty($req)) ? '<img src="' . esc_attr ($dir_url) . '/images/lock-icon.png" style="cursor:help; width:16px; border:0;" title="' . esc_attr ($req) . '" />' : '<span style="cursor:help;" title="Publicly Available">&mdash;</span>';

						return apply_filters("_ws_plugin__s2member_pro_return_lock_icons_description", $desc, get_defined_vars ());
					}
			}
	}
