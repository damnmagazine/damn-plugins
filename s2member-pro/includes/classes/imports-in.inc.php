<?php
/**
 * Handles various importations (inner processing routines).
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
 * @package s2Member\Imports
 * @since 1.5
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_imports_in'))
{
	/**
	 * Handles various importations (inner processing routines).
	 *
	 * @package s2Member\Imports
	 * @since 1.5
	 */
	class c_ws_plugin__s2member_pro_imports_in
	{
		/**
		 * Handles the importation of Users/Members.
		 *
		 * @package s2Member\Imports
		 * @since 110815
		 */
		public static function import_users()
		{
			if(!empty($_POST['ws_plugin__s2member_pro_import_users'])
			   && ($nonce = $_POST['ws_plugin__s2member_pro_import_users'])
			   && wp_verify_nonce($nonce, 'ws-plugin--s2member-pro-import-users')
			   && current_user_can('create_users')
			)
			{
				global $wpdb; // Global database object reference.
				/** @var \wpdb $wpdb This line for IDEs that need a reference. */
				global $current_site, $current_blog; // Multisite Networking.

				@set_time_limit(0); // Make time for processing large import files.
				@ini_set('memory_limit', apply_filters('admin_memory_limit', WP_MAX_MEMORY_LIMIT));

				remove_all_actions('profile_update').remove_all_actions('user_register');
				remove_all_actions('added_existing_user').remove_all_actions('add_user_to_blog');

				if(!empty($_FILES['ws_plugin__s2member_pro_import_users_file']) && empty($_FILES['ws_plugin__s2member_pro_import_users_file']['error']))
					$file = fopen($_FILES['ws_plugin__s2member_pro_import_users_file']['tmp_name'], 'r');

				else if(!empty($_POST['ws_plugin__s2member_pro_import_users_direct_input']))
					fwrite(($file = tmpfile()), trim(stripslashes($_POST['ws_plugin__s2member_pro_import_users_direct_input']))).fseek($file, 0);

				$imported = $line = $line_index = 0; // Initialize these counters.
				$headers  = array(); // Initialize the array of CSV import file headers.

				$user_keys = array(); // Initialize array of user keys.
				if(is_object($_user_row = $wpdb->get_row("SELECT * FROM `".$wpdb->users."` LIMIT 1")))
					foreach(array_keys((array)$_user_row) as $_user_key)
						$user_keys[] = $_user_key;
				unset($_user_row, $_user_key); // Housekeeping.
				$user_keys = array_unique($user_keys); // Only unique keys please.

				if(isset($file) && is_resource($file)) // Only process if we have a resource.
				{
					while(($_csv_data = ((version_compare(PHP_VERSION, '5.3', '>=')) ? fgetcsv($file, 0, ',', '"', '"') : fgetcsv($file, 0, ',', '"'))) !== FALSE)
					{
						$line_index = (int)$line_index + 1; // CSV lines.
						$line       = (int)$line + 1; // CSV lines.

						$_csv_data = c_ws_plugin__s2member_utils_strings::trim_deep($_csv_data);

						if($line_index === 1 && isset($_csv_data[0]))
						{
							$line = $line - 1;
							foreach($_csv_data as $_header)
								$headers[] = $_header;
							unset($_header); // Housekeeping.

							continue; // We've got the headers now; let's move to the next line.
						}
						if($line_index >= 1 && (!$headers || (!in_array('ID', $headers, TRUE) && !in_array('user_login', $headers, TRUE))))
						{
							$errors[] = 'Line #'.$line.'. Missing first-line CSV headers; please try again.'.
							            ' Please note that your CSV headers MUST contain (at a minimum), one of: "ID", or "user_login"';
							break; // Stop here; we have no headers in this importation.
						}
						$_user_ID_key = array_search('ID', $headers);
						$_user_id     = $_user_ID_key !== FALSE && !empty($_csv_data[$_user_ID_key]) ? (integer)$_csv_data[$_user_ID_key] : 0;
						unset($_user_ID_key); // Housekeeping.

						$_user_login_key = array_search('user_login', $headers);
						$_user_login     = $_user_login_key !== FALSE && !empty($_csv_data[$_user_login_key]) ? $_csv_data[$_user_login_key] : '';
						unset($_user_login_key); // Housekeeping.

						$_user_pass_key = array_search('user_pass', $headers);
						$_user_pass     = $_user_pass_key !== FALSE && !empty($_csv_data[$_user_pass_key]) ? $_csv_data[$_user_pass_key] : '';
						unset($_user_pass_key); // Housekeeping.

						$_user_email_key = array_search('user_email', $headers);
						$_user_email     = $_user_email_key !== FALSE && !empty($_csv_data[$_user_email_key]) ? $_csv_data[$_user_email_key] : '';
						unset($_user_email_key); // Housekeeping.

						$_user_role_key = array_search('role', $headers);
						$_user_role     = $_user_role_key !== FALSE && !empty($_csv_data[$_user_role_key]) ? $_csv_data[$_user_role_key] : '';
						$_user_role     = is_numeric($_user_role) ? ($_user_role == 0 ? 'subscriber' : 's2member_level'.$_user_role) : $_user_role;
						unset($_user_role_key); // Housekeeping.

						$_user_ccaps_key = array_search('ccaps', $headers);
						$_user_ccaps     = $_user_ccaps_key !== FALSE && !empty($_csv_data[$_user_ccaps_key]) ? $_csv_data[$_user_ccaps_key] : '';
						unset($_user_ccaps_key); // Housekeeping.

						if($_user_login) // Sanitize the username.
						{
							if(is_multisite()) $_user_login = strtolower($_user_login);
							$_user_login = sanitize_user($_user_login, is_multisite());
						}
						if($_user_email) $_user_email = sanitize_email($_user_email);

						$_user_id_exists_but_not_on_blog = 0; // Initialize.
						if(!$_user_id && $_user_login && $_user_email && is_multisite())
							$_user_id = $_user_id_exists_but_not_on_blog = c_ws_plugin__s2member_utils_users::ms_user_login_email_exists_but_not_on_blog($_user_login, $_user_email);

						if(strcasecmp($_user_role, 'administrator') === 0)
						{
							$errors[] = 'Line #'.$line.'. Users cannot be updated to an Administrator. Bypassing this line for security.';
							continue; // Skip this line.
						}
						if($_user_email && !is_email($_user_email)) // Is the email address valid?
						{
							$errors[] = 'Line #'.$line.'. Invalid email address (<code>'.esc_html($_user_email).'</code>); please try again.';
							continue; // Skip this line.
						}
						if($_user_login && !validate_username($_user_login)) // Is the username valid?
						{
							$errors[] = 'Line #'.$line.'. Invalid username (<code>'.esc_html($_user_login).'</code>).';
							continue; // Skip this line.
						}
						if($_user_id) // Updating an existing user based on their ID in the database?
						{
							if(!is_object($_user = new WP_User($_user_id)) || !$_user->ID)
							{
								$errors[] = 'Line #'.$line.'. User ID# <code>'.esc_html($_user_id).'</code> does NOT belong to an existing User.';
								continue; // Skip this line.
							}
							if(is_super_admin($_user_id) || $_user->has_cap('administrator'))
							{
								$errors[] = 'Line #'.$line.'. User ID# <code>'.esc_html($_user_id).'</code> belongs to an Administrator. Bypassing this line for security.';
								continue; // Skip this line.
							}
							if(is_multisite() && $_user_id_exists_but_not_on_blog && add_existing_user_to_blog(array('user_id' => $_user_id, 'role' => 'subscriber')) !== TRUE)
							{
								$errors[] = 'Line #'.$line.'. Unknown user/site addition error, please try again.';
								continue; // Skip this line.
							}
							if(is_multisite() && !is_user_member_of_blog($_user_id)) // Must be a Member of this Blog.
							{
								$errors[] = 'Line #'.$line.'. User ID# <code>'.esc_html($_user_id).'</code> does NOT belong to an existing User on this site.';
								continue; // Skip this line.
							}
							if($_user_email && strcasecmp($_user_email, $_user->user_email) !== 0 && email_exists($_user_email))
							{
								$errors[] = 'Line #'.$line.'. Conflicting; the email address (<code>'.esc_html($_user_email).'</code>), already exists.';
								continue; // Skip this line.
							}
							if($_user_login && strcasecmp($_user_login, $_user->user_login) !== 0 && username_exists($_user_login))
							{
								$errors[] = 'Line #'.$line.'. Conflicting; the username (<code>'.esc_html($_user_login).'</code>), already exists.';
								continue; // Skip this line.
							}
							/** @var WP_Error $_email_login_validation */
							if(is_multisite() && strcasecmp($_user_email, $_user->user_email) !== 0 && strcasecmp($_user_login, $_user->user_login) !== 0)
								if(is_wp_error($_email_login_validation = wpmu_validate_user_signup($_user_login, $_user_email)))
									if($_email_login_validation->get_error_code())
									{
										$errors[] = 'Line #'.$line.'. Network. The email and/or username (<code>'.esc_html($_user_email).'</code> / <code>'.esc_html($_user_login).'</code>) are in conflict w/ network rules.';
										continue; // Skip this line.
									}
							unset($_email_login_validation); // Housekeeping.

							$_wp_update_user = array();
							foreach($user_keys as $_user_key)
								if(($_user_data_key = array_search($_user_key, $headers)) !== FALSE && isset($_csv_data[$_user_data_key]))
									$_wp_update_user[$_user_key] = $_csv_data[$_user_data_key];
							unset($_user_key, $_user_data_key); // Housekeeping.

							if(is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site())
								unset($_wp_update_user['user_login'], $_wp_update_user['user_pass']);

							if(!wp_update_user(wp_slash($_wp_update_user)))
							{
								$errors[] = 'Line #'.$line.'. User ID# <code>'.esc_html($_user_id).'</code> could NOT be updated. Unknown error, please try again.';
								continue; // Skip this line.
							}
							unset($_wp_update_user); // Housekeeping.

							clean_user_cache($_user_id);
							wp_cache_delete($_user_id, 'user_meta');
							$_user = new WP_User($_user_id);

							$imported = $imported + 1;
						}
						else // Creating a new user on this blog.
						{
							if(!$_user_email)
							{
								$errors[] = 'Line #'.$line.'. Missing email address.';
								continue; // Skip this line.
							}
							if(email_exists($_user_email))
							{
								$errors[] = 'Line #'.$line.'. Conflicting; the email address (<code>'.esc_html($_user_email).'</code>), already exists.';
								continue; // Skip this line.
							}
							if(!$_user_login)
							{
								$errors[] = 'Line #'.$line.'. Missing user login (i.e., username).';
								continue; // Skip this line.
							}
							if(username_exists($_user_login))
							{
								$errors[] = 'Line #'.$line.'. Conflicting; the username (<code>'.esc_html($_user_login).'</code>), already exists.';
								continue; // Skip this line.
							}
							/** @var WP_Error $_email_login_validation */
							if(is_multisite() && is_wp_error($_email_login_validation = wpmu_validate_user_signup($_user_login, $_user_email)))
								if($_email_login_validation->get_error_code())
								{
									$errors[] = 'Line #'.$line.'. Network. The email and/or username (<code>'.esc_html($_user_email).'</code> / <code>'.esc_html($_user_login).'</code>) are in conflict w/ network rules.';
									continue; // Skip this line.
								}
							unset($_email_login_validation); // Housekeeping.

							if(!($_user_id = wp_insert_user(wp_slash(array('user_login' => $_user_login, 'user_pass' => $_user_pass ? $_user_pass : wp_generate_password(12, FALSE), 'user_email' => $_user_email)))) || is_wp_error($_user_id))
							{
								$errors[] = 'Line #'.$line.'. Unknown insertion error, please try again.';
								continue; // Skip this line.
							}
							$_wp_update_user = array('ID' => $_user_id);
							foreach($user_keys as $_user_key)
								if(($_user_data_key = array_search($_user_key, $headers)) !== FALSE && isset($_csv_data[$_user_data_key]))
									$_wp_update_user[$_user_key] = $_csv_data[$_user_data_key];
							unset($_user_key, $_user_data_key); // Housekeeping.

							if(!wp_update_user(wp_slash($_wp_update_user)))
							{
								$errors[] = 'Line #'.$line.'. Post insertion update failed on User ID# <code>'.esc_html($_user_id).'</code>. Unknown error, please try again.';
								continue; // Skip this line.
							}
							unset($_wp_update_user); // Housekeeping.

							if(is_multisite()) // New Users on a Multisite Network need this too.
								update_user_meta($_user_id, 's2member_originating_blog', $current_blog->blog_id);

							clean_user_cache($_user_id);
							wp_cache_delete($_user_id, 'user_meta');
							$_user = new WP_User($_user_id);

							$imported = $imported + 1;
						}
						if($_user_role) $_user->set_role($_user_role);

						if($_user_ccaps) // Deal with custom capabilities.
						{
							foreach($_user->allcaps as $_cap => $_cap_enabled)
								if(preg_match('/^access_s2member_ccap_/', $_cap))
									$_user->remove_cap($_cap);
							unset($_cap, $_cap_enabled); // Housekeeping.

							if(preg_replace('/^-all['."\r\n\t".'\s;,]*/', '', str_replace('+', '', $_user_ccaps)))
								foreach(preg_split('/['."\r\n\t".'\s;,]+/', preg_replace('/^-all['."\r\n\t".'\s;,]*/', '', str_replace('+', '', $_user_ccaps))) as $_ccap)
									if(strlen($_ccap = trim(strtolower(preg_replace('/[^a-z_0-9]/i', '', $_ccap)))))
										$_user->add_cap('access_s2member_ccap_'.$_ccap);
						}
						$_user_custom_fields = get_user_option('s2member_custom_fields', $_user_id);
						$_user_custom_fields = is_array($_user_custom_fields) ? $_user_custom_fields : array();

						foreach($headers as $_index => $_header)
						{
							if(strpos($_header, 'meta_key__') === 0)
							{
								if(isset($_csv_data[$_index]))
								{
									$_new_meta_value = $_csv_data[$_index];
									$_user_meta_key  = substr($_header, strlen('meta_key__'));

									if($_user_meta_key === $wpdb->prefix.'capabilities' && ($_user_role || $_user_ccaps))
										continue; // Already handled via `role` and `ccaps`.

									if($_user_meta_key === $wpdb->prefix.'capabilities' && stripos($_new_meta_value, 'administrator') !== FALSE)
										continue; // Do not allow this for security purposes.

									if(is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site())
										if(strpos($_user_meta_key, $wpdb->prefix) !== 0 && !in_array($_user_meta_key, array('first_name', 'last_name', 'nickname', 'description'), TRUE))
											continue; // Child sites may NOT update meta data for other child blogs.

									switch($_user_meta_key)
									{
										case $wpdb->prefix.'capabilities':
										case $wpdb->prefix.'s2member_sp_references':
										case $wpdb->prefix.'s2member_ipn_signup_vars':
										case $wpdb->prefix.'s2member_access_cap_times':
										case $wpdb->prefix.'s2member_paid_registration_times':
										case $wpdb->prefix.'s2member_file_download_access_arc':
										case $wpdb->prefix.'s2member_file_download_access_log':
											if(isset($_new_meta_value[0])) // This handles JSON-decoding for known array values.
												if(!is_null($_new_meta_value_decoded = json_decode($_new_meta_value, TRUE)))
													$_new_meta_value = maybe_serialize($_new_meta_value_decoded);
											break;
									}
									$_existing_meta_row = $wpdb->get_row("SELECT * FROM `".$wpdb->usermeta."` WHERE `user_id` = '".esc_sql($_user_id)."' AND `meta_key` = '".esc_sql($_user_meta_key)."' AND `meta_value` = '".esc_sql($_new_meta_value)."' LIMIT 1");
									if(is_object($_existing_meta_row)) continue; // No need to update this; it is still the same value.

									$_existing_meta_rows = $wpdb->get_results("SELECT * FROM `".$wpdb->usermeta."` WHERE `user_id` = '".esc_sql($_user_id)."' AND `meta_key` = '".esc_sql($_user_meta_key)."' LIMIT 2");
									if($_existing_meta_rows && count($_existing_meta_rows) > 1) continue; // We don't update multivalue keys. This can cause database corruption via CSV import files.

									$_existing_meta_row = $_existing_meta_rows ? $_existing_meta_rows[0] : NULL;
									/** @var object $_existing_meta_row This line is for IDEs; so they don't choke. */

									if(is_object($_existing_meta_row) && $_new_meta_value !== $_existing_meta_row->meta_value)
										$wpdb->update($wpdb->usermeta, array('meta_value' => $_new_meta_value), array('umeta_id' => $_existing_meta_row->umeta_id));

									else if(!is_object($_existing_meta_row))
										$wpdb->insert($wpdb->usermeta, array('user_id' => $_user_id, 'meta_key' => $_user_meta_key, 'meta_value' => $_new_meta_value));
								}
							}
							else if(strpos($_header, 'custom_field_key__') === 0)
							{
								if(isset($_csv_data[$_index]))
								{
									$_new_custom_field_value = $_csv_data[$_index];
									if(!is_null($_new_custom_field_value_decoded = json_decode($_new_custom_field_value, TRUE)))
										$_new_custom_field_value = $_new_custom_field_value_decoded;
									$_user_custom_field_key = substr($_header, strlen('custom_field_key__'));

									$_user_custom_fields[$_user_custom_field_key] = $_new_custom_field_value;
								}
							}
						}
						update_user_option($_user_id, 's2member_custom_fields', $_user_custom_fields);

						unset($_user_custom_fields, $_index, $_header); // Housekeeping.
						unset($_new_meta_value, $_new_meta_value_decoded, $_user_meta_key, $_existing_meta_rows, $_existing_meta_row);
						unset($_new_custom_field_value, $_new_custom_field_value_decoded, $_user_custom_field_key);
					}
					fclose($file); // Close the file resource handle now.
					unset($_csv_data, $_user, $_user_id, $_user_login, $_user_email);
					unset($_user_id_exists_but_not_on_blog, $_user_role, $_user_ccaps);
				}
				else $errors[] = 'No data was received. Please try again.'; // The upload failed, or it was empty.

				c_ws_plugin__s2member_admin_notices::display_admin_notice('Operation complete. Users/Members imported: <code>'.(int)$imported.'</code>.');

				if(!empty($errors)) // Here is where a detailed error log will be returned to the Site Owner; as a way of clarifying what just happened during importation.
					c_ws_plugin__s2member_admin_notices::display_admin_notice('<strong>The following errors were encountered during importation:</strong><ul style="font-size:80%; list-style:disc outside; margin-left:25px;"><li>'.implode('</li><li>', $errors).'</li></ul>', TRUE);
			}
		}

		/**
		 * Handles the importation of options.
		 *
		 * @package s2Member\Imports
		 * @since 110815
		 */
		public static function import_ops()
		{
			if(!empty($_POST['ws_plugin__s2member_pro_import_ops']) && ($nonce = $_POST['ws_plugin__s2member_pro_import_ops']) && wp_verify_nonce($nonce, 'ws-plugin--s2member-pro-import-ops') && current_user_can('create_users'))
			{
				@set_time_limit(0); // Make time for processing large import files.
				@ini_set('memory_limit', apply_filters('admin_memory_limit', WP_MAX_MEMORY_LIMIT));

				if(!empty($_FILES['ws_plugin__s2member_pro_import_ops_file']) && empty($_FILES['ws_plugin__s2member_pro_import_ops_file']['error']))
					$file = file_get_contents($_FILES['ws_plugin__s2member_pro_import_ops_file']['tmp_name'], 'r');

				if(!empty($file)) // Only process if we have an importation file.
				{
					if(is_array($import = c_ws_plugin__s2member_pro_utils_ops::op_replace(@unserialize($file), TRUE)) && !empty($import) && ($import['configured'] = '1'))
					{
						unset($import['options_checksum'], $import['options_version']);

						foreach($import as $key => $value) // Add prefixes now.
						{
							(is_array($value)) ? array_unshift($value, 'update-signal') : NULL;
							$import['ws_plugin__s2member_'.$key] = $value;
							unset($import[$key]);
						}
						c_ws_plugin__s2member_menu_pages::update_all_options($import, TRUE, TRUE, FALSE, FALSE, FALSE);
					}
					else $errors[] = 'Invalid data received. Please try again.'; // Unserialization failed?
				}
				else $errors[] = 'No data was received. Please try again.'; // The upload failed, or it was empty.

				if(!empty($errors)) // Here is where a detailed error log will be returned to the Site Owner; as a way of clarifying what just happened during importation.
					c_ws_plugin__s2member_admin_notices::display_admin_notice('<strong>The following errors were encountered during importation:</strong><ul style="font-size:80%; list-style:disc outside; margin-left:25px;"><li>'.implode('</li><li>', $errors).'</li></ul>', TRUE);
				else c_ws_plugin__s2member_admin_notices::display_admin_notice('Operation complete. Options imported.');
			}
		}
	}
}
