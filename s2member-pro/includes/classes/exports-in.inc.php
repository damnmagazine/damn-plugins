<?php
/**
 * Handles various exportations (innner processing routines).
 *
 * Copyright: © 2009-2011
 * {@link http://www.websharks-inc.com/ WebSharks, Inc.}
 * (coded in the USA)
 *
 * This WordPress plugin (s2Member Pro) is comprised of two parts:
 *
 * o (1) Its PHP code is licensed under the GPL license, as is WordPress.
 *   You should have received a copy of the GNU General Public License,
 *   along with this software. In the main directory, see: /licensing/
 *   If not, see: {@link http://www.gnu.org/licenses/}.
 *
 * o (2) All other parts of (s2Member Pro); including, but not limited to:
 *   the CSS code, some JavaScript code, images, and design;
 *   are licensed according to the license purchased.
 *   See: {@link http://www.s2member.com/prices/}
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
 * @package s2Member\Exports
 * @since 140724
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_exports_in'))
{
	/**
	 * Handles various exportations (innner processing routines).
	 *
	 * @package s2Member\Exports
	 * @since 140724
	 */
	class c_ws_plugin__s2member_pro_exports_in
	{
		/**
		 * Handles the exportation of Users/Members.
		 *
		 * @package s2Member\Exports
		 * @since 140724
		 *
		 * @return null Or exits script execution after issuing file download prompt with CSV file.
		 */
		public static function export_users()
		{
			if(!empty($_POST['ws_plugin__s2member_pro_export_users'])
			   && ($nonce = $_POST['ws_plugin__s2member_pro_export_users'])
			   && wp_verify_nonce($nonce, 'ws-plugin--s2member-pro-export-users')
			   && current_user_can('create_users')
			)
			{
				global $wpdb; // Global database object reference.
				/** @var \wpdb $wpdb This line for IDEs that need a reference. */
				global $current_site, $current_blog; // Multisite Networking.

				@set_time_limit(0);
				@ini_set('memory_limit', apply_filters('admin_memory_limit', WP_MAX_MEMORY_LIMIT));

				@ini_set('zlib.output_compression', 0);
				if(function_exists('apache_setenv'))
					@apache_setenv('no-gzip', '1');

				while(@ob_end_clean()) ;

				$format   = !empty($_POST['ws_plugin__s2member_pro_export_users_format']) ? $_POST['ws_plugin__s2member_pro_export_users_format'] : '';
				$utf8_bom = isset($_POST['ws_plugin__s2member_pro_export_users_utf8_bom']) ? (int)$_POST['ws_plugin__s2member_pro_export_users_utf8_bom'] : 0;
				$start    = !empty($_POST['ws_plugin__s2member_pro_export_users_start']) ? (int)$_POST['ws_plugin__s2member_pro_export_users_start'] : 1;
				$limit    = !empty($_POST['ws_plugin__s2member_pro_export_users_limit']) ? (int)$_POST['ws_plugin__s2member_pro_export_users_limit']
					: apply_filters('ws_plugin__s2member_pro_export_users_limit', 1000); // Back compatibility; and for blog farms.

				$start  = ($start >= 1) ? $start : 1; // Must be 1 or higher.
				$sql_s  = ($start === 1) ? 0 : $start; // 1 should be 0.
				$export = ''; // Initialize the export file variable.

				$user_keys = array(); // Initialize array of user keys.
				if(is_object($_user_row = $wpdb->get_row("SELECT * FROM `".$wpdb->users."` LIMIT 1")))
					foreach(array_keys((array)$_user_row) as $_user_key)
					{
						if(!in_array($_user_key, array('user_pass'), TRUE))
							$user_keys[] = $_user_key;
					}
				unset($_user_row, $_user_key); // Housekeeping.
				$user_keys = array_unique($user_keys); // Only unique keys please.

				$user_permission_keys = array('role', 'ccaps');

				$user_meta_keys = $wpdb->get_col("SELECT DISTINCT `meta_key` FROM `".$wpdb->usermeta."` WHERE (`".$wpdb->usermeta."`.`meta_key` NOT LIKE '".esc_sql(c_ws_plugin__s2member_utils_strings::like_escape($wpdb->base_prefix))."%' OR `".$wpdb->usermeta."`.`meta_key` LIKE '".esc_sql(c_ws_plugin__s2member_utils_strings::like_escape($wpdb->prefix))."%')");
				$user_meta_keys = is_array($user_meta_keys) ? $user_meta_keys : array();

				foreach($user_meta_keys as $_index => $_meta_key)
				{
					if($_meta_key === $wpdb->prefix.'s2member_custom_fields')
						unset($user_meta_keys[$_index]);
				}
				if(is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site())
					foreach($user_meta_keys as $_index => $_meta_key)
					{
						if(strpos($_meta_key, $wpdb->prefix) !== 0)
							if(!in_array($_meta_key, array('first_name', 'last_name', 'nickname', 'description'), TRUE))
								unset($user_meta_keys[$_index]);
					}
				unset($_index, $_meta_key); // Housekeeping.
				$user_meta_keys = array_unique($user_meta_keys); // Only unique keys please.

				$user_custom_field_keys = array(); // Initialize this array.

				if($GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_fields'])
					foreach(json_decode($GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_fields'], TRUE) as $_field)
					{
						$custom_field_var         = preg_replace('/[^a-z0-9]/i', '_', strtolower($_field['id']));
						$user_custom_field_keys[] = $custom_field_var;
					}
				unset($_field); // Housekeeping.
				$user_custom_field_keys = array_unique($user_custom_field_keys); // Only unique keys please.
				sort($user_custom_field_keys, SORT_STRING); // Sort these also; just to give them some order.

				$export_headers = ''; // Initialize export headers.

				foreach($user_keys as $_user_key) // Include all of the user fields first.
					$export_headers .= ',"'.c_ws_plugin__s2member_utils_strings::esc_dq($_user_key, 1, '"').'"';
				unset($_user_key); // Housekeeping.

				foreach($user_permission_keys as $_user_permission_key) // Include permission keys now.
					$export_headers .= ',"'.c_ws_plugin__s2member_utils_strings::esc_dq($_user_permission_key, 1, '"').'"';
				unset($_user_permission_key); // Housekeeping.

				foreach($user_meta_keys as $_user_meta_key) // Next comes all of the user meta fields.
					$export_headers .= ',"meta_key__'.c_ws_plugin__s2member_utils_strings::esc_dq($_user_meta_key, 1, '"').'"';
				unset($_user_meta_key); // Housekeeping.

				foreach($user_custom_field_keys as $_user_custom_field_key) // Now the s2Member custom fields separately.
					$export_headers .= ',"custom_field_key__'.c_ws_plugin__s2member_utils_strings::esc_dq($_user_custom_field_key, 1, '"').'"';
				unset($_user_custom_field_key); // Housekeeping.

				$export_headers = trim($export_headers, ','); // Trim away leading/trailing delimiters.
				$export         = $export_headers."\n"; // First line of the export file is always the export headers.

				$users = $wpdb->get_results("SELECT `".$wpdb->users."`.`ID` FROM `".$wpdb->users."`, `".$wpdb->usermeta."` WHERE `".$wpdb->users."`.`ID` = `".$wpdb->usermeta."`.`user_id` AND `".$wpdb->usermeta."`.`meta_key` = '".esc_sql($wpdb->prefix.'capabilities')."' ORDER BY `".$wpdb->users."`.`ID` ASC LIMIT ".$sql_s.", ".$limit);
				$users = is_array($users) ? $users : array(); // List of the users on this blog.

				foreach($users as $_user) // Go through each user in this result set.
				{
					$_user_line = ''; // Initialize the export line for this user.

					if(!is_object($_user = new WP_User($_user->ID)) || !$_user->ID)
						continue; // Nothing to export for this user.

					foreach($user_keys as $_user_key)
					{
						$_value = ''; // Intialize value.

						switch($_user_key)
						{
							default: // Default handler.
								$_value = $_user->{$_user_key};
								break;
						}
						$_user_line .= ',"'.c_ws_plugin__s2member_utils_strings::esc_dq((string)$_value, 1, '"').'"';
					}
					unset($_user_key, $_value); // Housekeeping.

					foreach($user_permission_keys as $_user_permission_key)
					{
						$_value = ''; // Intialize value.

						switch($_user_permission_key)
						{
							case 'role': // The user's role.
								$_value = c_ws_plugin__s2member_user_access::user_access_role($_user);
								break;

							case 'ccaps': // s2 custom capabilities.
								$_value = implode(',', c_ws_plugin__s2member_user_access::user_access_ccaps($_user));
								break;
						}
						$_user_line .= ',"'.c_ws_plugin__s2member_utils_strings::esc_dq($_value, 1, '"').'"';
					}
					unset($_user_permission_key, $_value); // Housekeeping.

					$_user_meta_values = $wpdb->get_results("SELECT `meta_key`, `meta_value` FROM `".$wpdb->usermeta."` WHERE `user_id` = '".esc_sql($_user->ID)."'", OBJECT_K);

					foreach($user_meta_keys as $_user_meta_key)
					{
						$_value = ''; // Intialize value.

						switch($_user_meta_key)
						{
							case $wpdb->prefix.'capabilities':
							case $wpdb->prefix.'s2member_sp_references':
							case $wpdb->prefix.'s2member_ipn_signup_vars':
							case $wpdb->prefix.'s2member_access_cap_times':
							case $wpdb->prefix.'s2member_paid_registration_times':
							case $wpdb->prefix.'s2member_file_download_access_arc':
							case $wpdb->prefix.'s2member_file_download_access_log':
								// This handles JSON-encoding for known array values.
								if(isset($_user_meta_values[$_user_meta_key]->meta_value[0]))
									$_value = json_encode(maybe_unserialize($_user_meta_values[$_user_meta_key]->meta_value));
								break;

							default: // Default handler.
								if(isset($_user_meta_values[$_user_meta_key]->meta_value[0]))
									if($format === 'readable' && strpos($_user_meta_values[$_user_meta_key]->meta_value, '{'))
										$_value = json_encode(maybe_unserialize($_user_meta_values[$_user_meta_key]->meta_value));
									else $_value = $_user_meta_values[$_user_meta_key]->meta_value;
								break;
						}
						$_user_line .= ',"'.c_ws_plugin__s2member_utils_strings::esc_dq($_value, 1, '"').'"';
					}
					unset($_user_meta_values, $_user_meta_key, $_value); // Housekeeping.

					$_user_custom_fields = get_user_option('s2member_custom_fields', $_user->ID);

					foreach($user_custom_field_keys as $_user_custom_field_key)
					{
						$_value = ''; // Intialize value.

						switch($_user_custom_field_key)
						{
							default: // Default handler.
								if(isset($_user_custom_fields[$_user_custom_field_key]))
									$_value = $_user_custom_fields[$_user_custom_field_key];
								break;
						}
						if($format === 'readable' && is_array($_value))
							$_value = implode('|', $_value); // A little easier.
						else if(is_array($_value)) $_value = json_encode($_value);

						$_user_line .= ',"'.c_ws_plugin__s2member_utils_strings::esc_dq((string)$_value, 1, '"').'"';
					}
					unset($_user_custom_fields, $_user_custom_field_key, $_value); // Housekeeping.

					$export .= trim($_user_line, " \r\n\t\0\x0B,")."\n";
				}
				unset($_user, $_user_line); // Housekeeping.

				status_header(200); // 200 OK status header.

				if($utf8_bom) // Add UTF-8 BOM (Byte Order Marker)?
					$export = "\xEF\xBB\xBF".$export;

				header('Content-Encoding: none');
				header('Accept-Ranges: none');
				header('Content-Type: text/csv; charset=UTF-8');
				header('Content-Length: '.strlen($export));
				header('Expires: '.gmdate('D, d M Y H:i:s', strtotime('-1 week')).' GMT');
				header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
				header('Cache-Control: no-cache, must-revalidate, max-age=0');
				header('Cache-Control: post-check=0, pre-check=0', FALSE);
				header('Pragma: no-cache');

				header('Content-Disposition: attachment; filename="export-'.$start.'-'.($start + $limit - 1).'.csv"');

				exit($export); // Exportation file.
			}
		}

		/**
		 * Handles the exportation of options.
		 *
		 * @package s2Member\Exports
		 * @since 110815
		 */
		public static function export_ops()
		{
			if(!empty($_GET['ws_plugin__s2member_pro_export_ops']) && ($nonce = $_GET['ws_plugin__s2member_pro_export_ops']) && wp_verify_nonce($nonce, 'ws-plugin--s2member-pro-export-ops') && current_user_can('create_users'))
			{
				$export = serialize(c_ws_plugin__s2member_pro_utils_ops::op_replace($GLOBALS['WS_PLUGIN__']['s2member']['o']));

				@set_time_limit(0);
				@ini_set('memory_limit', apply_filters('admin_memory_limit', WP_MAX_MEMORY_LIMIT));

				@ini_set('zlib.output_compression', 0);
				if(function_exists('apache_setenv'))
					@apache_setenv('no-gzip', '1');

				while(@ob_end_clean()) ;

				status_header(200); // 200 OK status header.

				header('Content-Encoding: none');
				header('Accept-Ranges: none');
				header('Content-Type: text/plain; charset=UTF-8');
				header('Content-Length: '.strlen($export));
				header('Expires: '.gmdate('D, d M Y H:i:s', strtotime('-1 week')).' GMT');
				header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
				header('Cache-Control: no-cache, must-revalidate, max-age=0');
				header('Cache-Control: post-check=0, pre-check=0', FALSE);
				header('Pragma: no-cache');

				header('Content-Disposition: attachment; filename="export.s2e"');

				exit($export); // Exportation file.
			}
		}
	}
}