<?php
/**
* Pro Lock Icons.
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

if (!class_exists ("c_ws_plugin__s2member_pro_lock_icons"))
	{
		/**
		* Pro Lock Icons.
		*
		* @package s2Member\Lock_Icons
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_lock_icons
			{
				/**
				* Configure all of the required Hooks/Filters for columns & CSS.
				*
				* @package s2Member\Lock_Icons
				* @since 1.5
				*
				* @attaches-to ``add_action("admin_init");``
				*
				* @return null
				*/
				public static function configure_lock_icons ()
					{
						global $wp_post_types, $wp_taxonomies; // Global references.

						do_action("ws_plugin__s2member_pro_before_configure_lock_icons", get_defined_vars ());

						add_action ("admin_head", "c_ws_plugin__s2member_pro_lock_icons_in::_lock_icons_echo_css");

						add_filter ("manage_edit-post_tag_columns", "c_ws_plugin__s2member_pro_lock_icons_in::_lock_icons_return_column", 11, 1);
						add_filter ("manage_post_tag_custom_column", "c_ws_plugin__s2member_pro_lock_icons_in::_lock_icons_return_value_tags", 11, 3);

						add_filter ("manage_edit-category_columns", "c_ws_plugin__s2member_pro_lock_icons_in::_lock_icons_return_column", 11, 1);
						add_filter ("manage_category_custom_column", "c_ws_plugin__s2member_pro_lock_icons_in::_lock_icons_return_value_categories", 11, 3);

						add_filter ("manage_page_posts_columns", "c_ws_plugin__s2member_pro_lock_icons_in::_lock_icons_return_column", 11, 1);
						add_action ("manage_page_posts_custom_column", "c_ws_plugin__s2member_pro_lock_icons_in::_lock_icons_echo_value_pages", 11, 2);

						if (is_array($wp_post_types) && !empty($wp_post_types)) // All; including Custom Post Types; excluding Pages.
							foreach (array_keys ($wp_post_types) as $type)
								{
									if ($type !== "page") // Always exclude Pages here. They have a separate handler in the lines just above.
										{
											add_filter ("manage_" . $type . "_posts_columns", "c_ws_plugin__s2member_pro_lock_icons_in::_lock_icons_return_column", 11, 1);
											add_action ("manage_" . $type . "_posts_custom_column", "c_ws_plugin__s2member_pro_lock_icons_in::_lock_icons_echo_value_post_types", 11, 2);
										}
								}

						do_action("ws_plugin__s2member_pro_after_configure_lock_icons", get_defined_vars ());

						return /* Return for uniformity. */;
					}
			}
	}
