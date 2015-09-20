<?php
/**
* ClickBank Menu Pages.
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
* @package s2Member\Menu_Pages
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit ("Do not access this file directly.");

if (!class_exists ("c_ws_plugin__s2member_pro_clickbank_menu_pages"))
	{
		/**
		* ClickBank Menu Pages.
		*
		* @package s2Member\Menu_Pages
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_clickbank_menu_pages
			{
				/**
				* Add the pages for this Payment Gateway.
				*
				* @package s2Member\Menu_Pages
				* @since 1.5
				*
				* @attaches-to ``add_filter("ws_plugin__s2member_during_add_admin_options_add_divider_3");``
				*
				* @param bool $add_divider Expects a boolean value, passed through by the Filter.
				* @param array $vars Expects an array of defined variables passed through by the Filter.
				* @return bool Passes back the original value of ``$add_divider``.
				*/
				public static function clickbank_admin_options ($add_divider = TRUE, $vars = FALSE)
					{
						add_submenu_page ($vars["menu"], "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:1px; line-height:1px; background:#CCCCCC;"></span>', "create_users", "#");
						add_submenu_page ($vars["menu"], "s2Member Pro / ClickBank Options", "ClickBank Options", "create_users", "ws-plugin--s2member-pro-clickbank-ops", "c_ws_plugin__s2member_pro_clickbank_menu_pages::clickbank_ops_page");
						add_submenu_page ($vars["menu"], "s2Member Pro / ClickBank Buttons", "ClickBank Buttons", "create_users", "ws-plugin--s2member-pro-clickbank-buttons", "c_ws_plugin__s2member_pro_clickbank_menu_pages::clickbank_buttons_page");

						return $add_divider; // Now add the divider.
					}
				/**
				* Builds the documentation for Scripting / API Constants related to this Payment Gateway.
				*
				* @package s2Member\Menu_Pages
				* @since 1.5
				*
				* @attaches-to ``add_action("ws_plugin__s2member_during_scripting_page_during_left_sections_during_list_of_api_constants");``
				*
				* @param array $vars Expects an array of defined variables passed through by the Action Hook.
				* @return null
				*/
				public static function clickbank_scripting_page_api_constants ($vars = FALSE)
					{
						include_once dirname (dirname (dirname (dirname (__FILE__)))) . "/menu-pages/clickbank-s-api-c.inc.php";

						return /* Return for uniformity. */;
					}
				/**
				* Builds the options panel for this Payment Gateway.
				*
				* @package s2Member\Menu_Pages
				* @since 1.5
				*
				* @return null
				*/
				public static function clickbank_ops_page ()
					{
						c_ws_plugin__s2member_menu_pages::update_all_options ();

						$logs_dir = $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["logs_dir"];

						if (!is_dir ($logs_dir) && is_writable (dirname (c_ws_plugin__s2member_utils_dirs::strip_dir_app_data ($logs_dir))))
							mkdir ($logs_dir, 0777, true) . clearstatcache ();

						$htaccess = $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["logs_dir"] . "/.htaccess";
						$htaccess_contents = trim (c_ws_plugin__s2member_utilities::evl (file_get_contents ($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["logs_dir_htaccess"])));

						if (is_dir ($logs_dir) && is_writable ($logs_dir) && !file_exists ($htaccess))
							file_put_contents ($htaccess, $htaccess_contents) . clearstatcache ();

						if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["gateway_debug_logs"]) // Logging enabled?
							{
								if (!is_dir ($logs_dir)) // If the security-enabled logs directory does not exist yet.
									c_ws_plugin__s2member_admin_notices::display_admin_notice ('The security-enabled logs directory (<code>' . esc_html (c_ws_plugin__s2member_utils_dirs::doc_root_path ($logs_dir)) . '</code>) does not exist. Please create this directory manually &amp; make it writable (chmod 777).', true);

								else if (!is_writable ($logs_dir)) // If the logs directory is not writable yet.
									c_ws_plugin__s2member_admin_notices::display_admin_notice ('Permissions error. The security-enabled logs directory (<code>' . esc_html (c_ws_plugin__s2member_utils_dirs::doc_root_path ($logs_dir)) . '</code>) is not writable. Please make this directory writable (chmod 777).', true);

								if (!file_exists ($htaccess)) // If the .htaccess file has not been created yet.
									c_ws_plugin__s2member_admin_notices::display_admin_notice ('The .htaccess protection file (<code>' . esc_html (c_ws_plugin__s2member_utils_dirs::doc_root_path ($htaccess)) . '</code>) does not exist. Please create this file manually. Inside your .htaccess file, add this:<br /><pre>' . esc_html ($htaccess_contents) . '</pre>', true);

								else if (!preg_match ("/deny from all/i", file_get_contents ($htaccess))) // Else if the .htaccess file does not offer the required protection.
									c_ws_plugin__s2member_admin_notices::display_admin_notice ('Unprotected. The .htaccess protection file (<code>' . esc_html (c_ws_plugin__s2member_utils_dirs::doc_root_path ($htaccess)) . '</code>) does not contain <code>deny from all</code>. Inside your .htaccess file, add this:<br /><pre>' . esc_html ($htaccess_contents) . '</pre>', true);
							}

						include_once dirname (dirname (dirname (dirname (__FILE__)))) . "/menu-pages/clickbank-ops.inc.php";

						return /* Return for uniformity. */;
					}
				/**
				* Builds the Buttons panel for this Payment Gateway.
				*
				* @package s2Member\Menu_Pages
				* @since 1.5
				*
				* @return null
				*/
				public static function clickbank_buttons_page ()
					{
						if (!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_clickbank_username"] || !$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_clickbank_clerk_key"] || !$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_clickbank_developer_key"] || !$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_clickbank_secret_key"])
							c_ws_plugin__s2member_admin_notices::display_admin_notice ('Please configure <strong>s2Member → ClickBank Options</strong> first. Once all of your ClickBank Options have been configured, return to this page &amp; generate your ClickBank Button(s).', true);

						include_once dirname (dirname (dirname (dirname (__FILE__)))) . "/menu-pages/clickbank-buttons.inc.php";

						return /* Return for uniformity. */;
					}
			}
	}
