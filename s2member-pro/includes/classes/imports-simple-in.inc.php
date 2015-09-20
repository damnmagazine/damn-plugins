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

if(!class_exists('c_ws_plugin__s2member_pro_imports_simple_in'))
{
	/**
	 * Handles various importations (inner processing routines).
	 *
	 * @package s2Member\Imports
	 * @since 1.5
	 */
	class c_ws_plugin__s2member_pro_imports_simple_in
	{
		/**
		 * Handles the importation of Users/Members.
		 *
		 * @package s2Member\Imports
		 * @since 110815
		 */
		public static function import_users()
		{
			if(!empty($_POST['ws_plugin__s2member_pro_import_simple_users']) && ($nonce = $_POST['ws_plugin__s2member_pro_import_simple_users']) && wp_verify_nonce($nonce, 'ws-plugin--s2member-pro-import-users') && current_user_can('create_users'))
			{
				global $wpdb; // Global database object reference.
				global $current_site, $current_blog; // Multisite Networking.

				@set_time_limit(0); // Make time for processing large import files.
				@ini_set('memory_limit', apply_filters('admin_memory_limit', WP_MAX_MEMORY_LIMIT));

				remove_all_actions('profile_update').remove_all_actions('user_register');
				remove_all_actions('added_existing_user').remove_all_actions('add_user_to_blog');

				if(!empty($_FILES['ws_plugin__s2member_pro_import_users_file']) && empty($_FILES['ws_plugin__s2member_pro_import_users_file']['error']))
					$file = fopen($_FILES['ws_plugin__s2member_pro_import_users_file']['tmp_name'], 'r');

				else if(!empty($_POST['ws_plugin__s2member_pro_import_users_direct_input']))
					fwrite(($file = tmpfile()), trim(stripslashes($_POST['ws_plugin__s2member_pro_import_users_direct_input']))).fseek($file, 0);

				$imported = $line = 0; // Initialize these counters.

				if(isset($file) && is_resource($file)) // Only process if we have a resource.
				{
					$custom_field_vars = array(); // Initialize this array.
					if($GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_fields'])
						foreach(json_decode($GLOBALS['WS_PLUGIN__']['s2member']['o']['custom_reg_fields'], TRUE) as $field)
						{
							$custom_field_var    = preg_replace('/[^a-z0-9]/i', '_', strtolower($field['id']));
							$custom_field_vars[] = $custom_field_var;
						}
					sort($custom_field_vars, SORT_STRING); // Always sort this array.

					while(($data = ((version_compare(PHP_VERSION, '5.3', '>=')) ? fgetcsv($file, 0, ',', '"', '"') : fgetcsv($file, 0, ',', '"'))) !== FALSE)
					{
						$line = (int)$line + 1; // CSV lines.

						$data = c_ws_plugin__s2member_utils_strings::trim_deep($data);
						$data = stripslashes_deep($data);

						if($line === 1 && strtoupper($data[0]) === 'ID') // Skip first line if it contains headers.
						{
							$line = $line - 1;
							continue;
						}
						if(is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site())
						{
							$ID = $data[0];

							$user_login = (is_multisite()) ? strtolower($data[1]) : $data[1];
							$user_login = sanitize_user($user_login, is_multisite());
							$user_pass  = (string)'';

							$first_name   = $data[2];
							$last_name    = $data[3];
							$display_name = $data[4];

							$user_email = sanitize_email($data[5]);
							$user_url   = $data[6];

							$role                = $data[7];
							$custom_capabilities = $data[8];

							$user_registered         = ($data[9]) ? date('Y-m-d H:i:s', strtotime($data[9])) : '';
							$paid_registration_times = ($data[10]) ? maybe_unserialize($data[10]) : '';
							$last_payment_time       = ($data[11]) ? strtotime($data[11]) : '';
							$auto_eot_time           = ($data[12]) ? strtotime($data[12]) : '';

							$custom         = $data[13];
							$subscr_id      = $data[14];
							$subscr_gateway = strtolower($data[15]);

							$custom_fields = array(); // Initialize.
							if(count($data) > 16) // Now loop through Custom Fields.
								for($i = 16, $j = 0; $i < count($data); $i++, $j++)
									if(isset($custom_field_vars[$j])) // A field in this position?
										$custom_fields[$custom_field_vars[$j]] = maybe_unserialize($data[$i]);
						}
						else // Otherwise, we use the standardized format for importation.
						{
							$ID = $data[0];

							$user_login = is_multisite() ? strtolower($data[1]) : $data[1];
							$user_login = sanitize_user($user_login, is_multisite());
							$user_pass  = $data[2];

							$first_name   = $data[3];
							$last_name    = $data[4];
							$display_name = $data[5];

							$user_email = sanitize_email($data[6]);
							$user_url   = $data[7];

							$role                = $data[8];
							$custom_capabilities = $data[9];

							$user_registered         = ($data[10]) ? date('Y-m-d H:i:s', strtotime($data[10])) : '';
							$paid_registration_times = ($data[11]) ? maybe_unserialize($data[11]) : '';
							$last_payment_time       = ($data[12]) ? strtotime($data[12]) : '';
							$auto_eot_time           = ($data[13]) ? strtotime($data[13]) : '';

							$custom         = $data[14];
							$subscr_id      = $data[15];
							$subscr_gateway = strtolower($data[16]);

							$custom_fields = array(); // Initialize.
							if(count($data) > 17) // Now loop through Custom Fields.
								for($i = 17, $j = 0; $i < count($data); $i++, $j++)
									if(isset($custom_field_vars[$j])) // A field in this position?
										$custom_fields[$custom_field_vars[$j]] = maybe_unserialize($data[$i]);
						}
						$role = (is_numeric($role)) ? (($role == 0) ? 'subscriber' : 's2member_level'.$role) : $role;

						if($paid_registration_times && !is_array($paid_registration_times))
							$paid_registration_times = array('level' => strtotime($paid_registration_times));
						$paid_registration_times = (!$paid_registration_times || !is_array($paid_registration_times)) ? array() : $paid_registration_times;

						$user_details = compact('ID', 'user_login', 'user_pass', 'first_name', 'last_name', 'display_name', 'user_email', 'user_url', 'role', 'user_registered');
						if(empty($user_details['user_pass'])) // If there was NO Password given.
							unset($user_details['user_pass']); // Unset the Password array element.

						if($ID) // Are we dealing with an existing User ID?
						{
							if(is_object($user = new WP_User($ID)) && $user->ID) // Is this User in the database?
							{
								if(!is_multisite() || is_user_member_of_blog($ID)) // Must be a Member of this Blog.
								{
									if((!is_multisite() || !is_super_admin($ID)) && !$user->has_cap('administrator'))
									{
										if(strtolower($role) !== 'administrator') // Do NOT update to Administrator.
										{
											if($user_email && is_email($user_email)) // Is the email address valid?
											{
												if($user_login) // Has a Username (aka: user_login) been supplied?
												{
													if(validate_username($user_login)) // Is the Username valid?
													{
														if(($_same_email = (strtolower($user_email) === strtolower($user->user_email))) || !email_exists($user_email))
														{
															if(($_same_login = (strtolower($user_login) === strtolower($user->user_login))) || !username_exists($user_login))
															{
																if(!is_multisite() || ($_same_email && $_same_login) || (($_ = wpmu_validate_user_signup($user_login, $user_email)) && (!is_wp_error($_['errors']) || !$_['errors']->get_error_code())))
																{
																	if(is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site())
																		unset($user_details['user_login'], $user_details['user_pass']);

																	if(($user_id = wp_update_user(wp_slash($user_details))))
																	{
																		$user = new WP_User($ID); // Refresh object value.

																		update_user_option($user_id, 's2member_custom', $custom);
																		update_user_option($user_id, 's2member_subscr_id', $subscr_id);
																		update_user_option($user_id, 's2member_subscr_gateway', $subscr_gateway);
																		update_user_option($user_id, 's2member_auto_eot_time', $auto_eot_time);
																		update_user_option($user_id, 's2member_paid_registration_times', $paid_registration_times);
																		update_user_option($user_id, 's2member_last_payment_time', $last_payment_time);
																		update_user_option($user_id, 's2member_custom_fields', $custom_fields);

																		foreach($user->allcaps as $cap => $cap_enabled)
																			if(preg_match('/^access_s2member_ccap_/', $cap))
																				$user->remove_cap($ccap = $cap);

																		if($custom_capabilities && preg_replace('/^-all['."\r\n\t".'\s;,]*/', '', str_replace('+', '', $custom_capabilities)))
																			foreach(preg_split('/['."\r\n\t".'\s;,]+/', preg_replace('/^-all['."\r\n\t".'\s;,]*/', '', str_replace('+', '', $custom_capabilities))) as $ccap)
																				if(strlen($ccap = trim(strtolower(preg_replace('/[^a-z_0-9]/i', '', $ccap)))))
																					$user->add_cap('access_s2member_ccap_'.$ccap);

																		$imported = $imported + 1;
																	}
																	else
																		$errors[] = 'Line #'.$line.'. User ID# <code>'.$ID.'</code> could NOT be updated. Unknown error, please try again.';
																}
																else
																	$errors[] = 'Line #'.$line.'. Network. The Username and/or Email (<code>'.esc_html($user_login).'</code> / <code>'.esc_html($user_email).'</code>) are in conflict w/ Network rules.';
															}
															else
																$errors[] = 'Line #'.$line.'. Conflicting. The Username (<code>'.esc_html($user_login).'</code>), already exists.';
														}
														else
															$errors[] = 'Line #'.$line.'. Conflicting. The Email address (<code>'.esc_html($user_email).'</code>), already exists.';
													}
													else
														$errors[] = 'Line #'.$line.'. Invalid Username (<code>'.esc_html($user_login).'</code>). Lowercase alphanumerics are required.';
												}
												else
													$errors[] = 'Line #'.$line.'. Missing Username; please try again.'; // We have two separate errors for Usernames. This provides clarity.
											}
											else
												$errors[] = 'Line #'.$line.'. Missing or invalid Email address (<code>'.esc_html($user_email).'</code>); please try again.';
										}
										else
											$errors[] = 'Line #'.$line.'. User ID# <code>'.$ID.'</code> cannot be updated to an Administrator. Bypassing this line for security.';
									}
									else
										$errors[] = 'Line #'.$line.'. User ID# <code>'.$ID.'</code> belongs to an Administrator. Bypassing this line for security.';
								}
								else
									$errors[] = 'Line #'.$line.'. User ID# <code>'.$ID.'</code> does NOT belong to an existing User on this site.';
							}
							else
								$errors[] = 'Line #'.$line.'. User ID# <code>'.$ID.'</code> does NOT belong to an existing User.';
						}
						else if(is_multisite() && ($user_id = c_ws_plugin__s2member_utils_users::ms_user_login_email_exists_but_not_on_blog($user_login, $user_email)) && !is_super_admin($user_id))
						{
							if(strtolower($role) !== 'administrator') // Do NOT add existing Users as Administrators.
							{
								if(add_existing_user_to_blog(array('user_id' => $user_id, 'role' => $role)))
								{
									if(is_object($user = new WP_User($user_id)) && $user->ID)
									{
										update_user_option($user_id, 's2member_custom', $custom);
										update_user_option($user_id, 's2member_subscr_id', $subscr_id);
										update_user_option($user_id, 's2member_subscr_gateway', $subscr_gateway);
										update_user_option($user_id, 's2member_auto_eot_time', $auto_eot_time);
										update_user_option($user_id, 's2member_paid_registration_times', $paid_registration_times);
										update_user_option($user_id, 's2member_last_payment_time', $last_payment_time);
										update_user_option($user_id, 's2member_custom_fields', $custom_fields);

										foreach($user->allcaps as $cap => $cap_enabled)
											if(preg_match('/^access_s2member_ccap_/', $cap))
												$user->remove_cap($ccap = $cap);

										if($custom_capabilities && preg_replace('/^-all['."\r\n\t".'\s;,]*/', '', str_replace('+', '', $custom_capabilities)))
											foreach(preg_split('/['."\r\n\t".'\s;,]+/', preg_replace('/^-all['."\r\n\t".'\s;,]*/', '', str_replace('+', '', $custom_capabilities))) as $ccap)
												if(strlen($ccap = trim(strtolower(preg_replace('/[^a-z_0-9]/i', '', $ccap)))))
													$user->add_cap('access_s2member_ccap_'.$ccap);

										$imported = $imported + 1;
									}
									else
										$errors[] = 'Line #'.$line.'. Unknown object error, please try again.';
								}
								else
									$errors[] = 'Line #'.$line.'. Unknown User/site addition error, please try again.';
							}
							else
								$errors[] = 'Line #'.$line.'. Role cannot be Administrator. Bypassing this line for security.';
						}

						else // Otherwise, we are adding a brand new User.
						{
							if(strtolower($role) !== 'administrator') // Admin?
							{
								if($user_email && is_email($user_email) /* Valid? */)
								{
									if($user_login) // Was a Username even supplied?
									{
										if(validate_username($user_login)) // Is it valid?
										{
											if(!email_exists($user_email) /* Exists already? */)
											{
												if(!username_exists($user_login) /* Exists? */)
												{
													if(!is_multisite() || (($_ = wpmu_validate_user_signup($user_login, $user_email)) && (!is_wp_error($_['errors']) || !$_['errors']->get_error_code())))
													{
														if(($user_id = wp_insert_user(wp_slash(empty($user_details['user_pass']) ? array_merge($user_details, array('user_pass' => wp_generate_password(12, FALSE))) : $user_details))))
														{
															if(is_object($user = new WP_User($user_id)) && $user->ID)
															{
																if($user_pass) // If we are given an 'un-encrypted Password'.
																	wp_update_user(wp_slash(array('ID' => $user_id, 'user_pass' => $user_pass)));

																if(is_multisite()) // New Users on a Multisite Network need this too.
																	update_user_meta($user_id, 's2member_originating_blog', $current_blog->blog_id);

																update_user_option($user_id, 's2member_custom', $custom);
																update_user_option($user_id, 's2member_subscr_id', $subscr_id);
																update_user_option($user_id, 's2member_subscr_gateway', $subscr_gateway);
																update_user_option($user_id, 's2member_auto_eot_time', $auto_eot_time);
																update_user_option($user_id, 's2member_paid_registration_times', $paid_registration_times);
																update_user_option($user_id, 's2member_last_payment_time', $last_payment_time);
																update_user_option($user_id, 's2member_custom_fields', $custom_fields);

																foreach($user->allcaps as $cap => $cap_enabled)
																	if(preg_match('/^access_s2member_ccap_/', $cap))
																		$user->remove_cap($ccap = $cap);

																if($custom_capabilities && preg_replace('/^-all['."\r\n\t".'\s;,]*/', '', str_replace('+', '', $custom_capabilities)))
																	foreach(preg_split('/['."\r\n\t".'\s;,]+/', preg_replace('/^-all['."\r\n\t".'\s;,]*/', '', str_replace('+', '', $custom_capabilities))) as $ccap)
																		if(strlen($ccap = trim(strtolower(preg_replace('/[^a-z_0-9]/i', '', $ccap)))))
																			$user->add_cap('access_s2member_ccap_'.$ccap);

																$imported = $imported + 1;
															}
															else
																$errors[] = 'Line #'.$line.'. Unknown object error, please try again.';
														}
														else
															$errors[] = 'Line #'.$line.'. Unknown insertion error, please try again.';
													}
													else
														$errors[] = 'Line #'.$line.'. Network. The Username and/or Email (<code>'.esc_html($user_login).'</code> / <code>'.esc_html($user_email).'</code>) are in conflict w/ Network rules.';
												}
												else
													$errors[] = 'Line #'.$line.'. Conflicting. The Username (<code>'.esc_html($user_login).'</code>), already exists.';
											}
											else
												$errors[] = 'Line #'.$line.'. Conflicting. The Email address (<code>'.esc_html($user_email).'</code>), already exists.';
										}
										else
											$errors[] = 'Line #'.$line.'. Invalid Username (<code>'.esc_html($user_login).'</code>). Lowercase alphanumerics are required.';
									}
									else
										$errors[] = 'Line #'.$line.'. Missing Username; please try again.'; // We have two separate errors for Usernames. This provides clarity.
								}
								else
									$errors[] = 'Line #'.$line.'. Missing or invalid Email address (<code>'.esc_html($user_email).'</code>); please try again.';
							}
							else
								$errors[] = 'Line #'.$line.'. Role cannot be Administrator. Bypassing this line for security.';
						}
					}
					fclose($file); // Close the file resource handle now.
				}
				else $errors[] = 'No data was received. Please try again.'; // The upload failed, or it was empty.

				c_ws_plugin__s2member_admin_notices::display_admin_notice('Operation complete. Users/Members imported: <code>'.(int)$imported.'</code>.');

				if(!empty($errors)) // Here is where a detailed error log will be returned to the Site Owner; as a way of clarifying what just happened during importation.
					c_ws_plugin__s2member_admin_notices::display_admin_notice('<strong>The following errors were encountered during importation:</strong><ul style="font-size:80%; list-style:disc outside; margin-left:25px;"><li>'.implode('</li><li>', $errors).'</li></ul>', TRUE);
			}
		}
	}
}
