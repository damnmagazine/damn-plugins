<?php
/**
* s2Member Pro upgrader.
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
* @package s2Member\Upgrader
* @since 1.5
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");

if(!class_exists("c_ws_plugin__s2member_pro_upgrader"))
	{
		/**
		* s2Member Pro upgrader.
		*
		* @package s2Member\Upgrader
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_upgrader
			{
				/**
				* Upgrade error.
				*
				* @package s2Member\Upgrader
				* @since 111027
				*
				* @var str
				*/
				public static $error = "";
				/**
				* Filesystem credentials.
				*
				* @package s2Member\Upgrader
				* @since 111027
				*
				* @var array
				*/
				public static $credentials = array();
				/**
				* Upgrade wizard.
				*
				* @package s2Member\Upgrader
				* @since 1.5
				*
				* @return string Upgrade wizard, HTML markup.
				*/
				public static function wizard()
					{
						$_p = !empty($_POST) ? c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep((array)$_POST)) : array();
						$error = (!empty(c_ws_plugin__s2member_pro_upgrader::$error)) ? (string)c_ws_plugin__s2member_pro_upgrader::$error : "";
						$stored = (array)get_transient(md5("ws_plugin__s2member_pro_upgrade_credentials"));

						$username = !empty($_p["ws_plugin__s2member_pro_upgrade_username"]) ? (string)$_p["ws_plugin__s2member_pro_upgrade_username"] : "";
						$username = (!$username && !empty($stored["username"])) ? (string)$stored["username"] : $username;
						$username = (!$username) ? "Username" : $username;

						$password = !empty($_p["ws_plugin__s2member_pro_upgrade_password"]) ? (string)$_p["ws_plugin__s2member_pro_upgrade_password"] : "";
						$password = (!$password && !empty($stored["password"])) ? (string)$stored["password"] : $password;
						$password = (!$password) ? "Password" : $password;

						$credentials = c_ws_plugin__s2member_pro_upgrader::$credentials = /* Initialize/set these to an empty array. */ array();

						ob_start /* Buffer output here from the ``request_filesystem_credentials()`` function, which generates a form to collect filesystem credentials. */();
						if(is_array($credentials = request_filesystem_credentials($_SERVER["REQUEST_URI"], false, (($error) ? new WP_Error("s2member_pro_upgrade_error", $error) : false), dirname(dirname(dirname(dirname(__FILE__)))), array("ws_plugin__s2member_pro_upgrade", "ws_plugin__s2member_pro_upgrade_username", "ws_plugin__s2member_pro_upgrade_password"))))
							c_ws_plugin__s2member_pro_upgrader::$credentials = /* Set static ``$credentials`` var. */ $credentials;
						$credentials_form = /* Get form to collect credentials. Also used by this wizard. */ ob_get_clean();

						if(!empty($_p["ws_plugin__s2member_pro_upgrade"]) && $error && strpos($error, "#0004") !== false /* ``WP_Filesystem()`` failed? */ && $credentials_form)
							{
								$wizard = '<div class="error fade">'."\n";
								$wizard .= '<p>Your <a href="'.esc_attr(c_ws_plugin__s2member_readmes::parse_readme_value("Pro Add-on / Home Page")).'" target="_blank">s2Member Pro Add-on</a> must be updated to v'.WS_PLUGIN__S2MEMBER_MIN_PRO_VERSION.'+.<br />Please log in at <a href="'.esc_attr(c_ws_plugin__s2member_readmes::parse_readme_value("Pro Add-on / Home Page")).'" target="_blank" rel="external">s2Member.com</a> for access to the latest version.</p>'."\n";
								$wizard .= '</div>';

								$wizard .= /* Form to collect credentials. */ $credentials_form."\n";
							}
						else // Otherwise, we just need to collect their s2Member.com Username/Password combination.
							{
								$wizard = '<div class="error fade">'."\n";
								$wizard .= '<p>Your <a href="'.esc_attr(c_ws_plugin__s2member_readmes::parse_readme_value("Pro Add-on / Home Page")).'" target="_blank">s2Member Pro Add-on</a> must be updated to v'.WS_PLUGIN__S2MEMBER_MIN_PRO_VERSION.'+.<br />Please log in at <a href="'.esc_attr(c_ws_plugin__s2member_readmes::parse_readme_value("Pro Add-on / Home Page")).'" target="_blank" rel="external">s2Member.com</a> for access to the latest version.</p>'."\n";
								$wizard .= '<form method="post" action="'.esc_attr($_SERVER["REQUEST_URI"]).'" style="margin: 5px 0 5px 0;" onsubmit="jQuery (\'input#ws-plugin--s2member-pro-upgrade-submit\', this).attr (\'disabled\', \'disabled\').val (\'Upgrading... (please wait)\');" autocomplete="off">'."\n";
								$wizard .= '<input type="hidden" name="ws_plugin__s2member_pro_upgrade" id="ws-plugin--s2member-pro-upgrade" value="'.esc_attr(wp_create_nonce("ws-plugin--s2member-pro-upgrade")).'" />'."\n";

								$wizard .= apply_filters("ws_plugin__s2member_pro_upgrade_wizard_instructions", '<p><strong>Or upgrade automatically. Provide your login details for s2Member.com</strong>.</p>'."\n", get_defined_vars());

								$wizard .= '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_upgrade_username" id="ws-plugin--s2member-pro-upgrade-username" value="'.format_to_edit($username).'"'.(($username === "Username") ? ' style="color:#999999;"' : '').' onfocus="if(this.value === \'Username\'){ this.value = \'\'; } this.style.color = \'\';" onblur="if(!this.value){ this.value = \'Username\'; this.style.color = \'#999999\'; }" />'."\n";
								$wizard .= '<input type="'.esc_attr((($password === "Password") ? "text" : "password")).'" autocomplete="off" name="ws_plugin__s2member_pro_upgrade_password" id="ws-plugin--s2member-pro-upgrade-password" value="'.format_to_edit($password).'"'.(($password === "Password") ? ' style="color:#999999;"' : '').' onfocus="if(this.value === \'Password\'){ this.value = \'\'; } this.style.color = \'\'; this.type = \'Password\';" onblur="if(!this.value){ this.value = \'Password\'; this.style.color = \'#999999\'; this.type = \'text\'; }" />'."\n";
								$wizard .= '<input type="submit" id="ws-plugin--s2member-pro-upgrade-submit" value="Upgrade s2Member Pro Automatically" />'."\n";

								$wizard .= ($error) ? '<p><em>'.$error.'</em></p>'."\n" : '';

								$wizard .= '</form>'."\n";
								$wizard .= '</div>';
							}
						return /* Return HTML markup. */ $wizard;
					}
				/**
				* Upgrade processor.
				*
				* @package s2Member\Upgrader
				* @since 1.5
				*
				* @attaches-to ``add_action("admin_init");``
				*
				* @return null Upgrader does NOT return anything.
				*/
				public static function upgrade /* Pro Upgrader. */()
					{
						global /* Need this global object reference. */ $wp_filesystem;

						if(!empty($_POST["ws_plugin__s2member_pro_upgrade"]) && ($nonce = (string)$_POST["ws_plugin__s2member_pro_upgrade"]) && wp_verify_nonce($nonce, "ws-plugin--s2member-pro-upgrade") && ($_p = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_POST))))
							{
								if(@set_time_limit(0) !== "nill" && @ini_set("memory_limit", apply_filters("admin_memory_limit", WP_MAX_MEMORY_LIMIT)) !== "nill" && c_ws_plugin__s2member_pro_upgrader::abbr_bytes(@ini_get("memory_limit")) >= c_ws_plugin__s2member_pro_upgrader::abbr_bytes(apply_filters("admin_memory_limit", WP_MAX_MEMORY_LIMIT)))
									{
										if(!empty($_p["ws_plugin__s2member_pro_upgrade_username"]) && !empty($_p["ws_plugin__s2member_pro_upgrade_password"]) && is_array($s2_pro_upgrade = maybe_unserialize(c_ws_plugin__s2member_utils_urls::remote(add_query_arg(urlencode_deep(array("s2_pro_upgrade" => array("username" => (string)$_p["ws_plugin__s2member_pro_upgrade_username"], "password" => (string)$_p["ws_plugin__s2member_pro_upgrade_password"], "version" => WS_PLUGIN__S2MEMBER_PRO_VERSION))), c_ws_plugin__s2member_readmes::parse_readme_value("Pro Add-on / Auto-Update URL", dirname(dirname(dirname(__FILE__)))."/readme.txt"))))) && !empty($s2_pro_upgrade["zip"]) && !empty($s2_pro_upgrade["ver"]))
											{
												set_transient(md5("ws_plugin__s2member_pro_upgrade_credentials"), array("username" => (string)$_p["ws_plugin__s2member_pro_upgrade_username"], "password" => (string)$_p["ws_plugin__s2member_pro_upgrade_password"]), 5184000);

												ob_start /* We collect credentials here too, in case data is posted. Buffer output from the ``request_filesystem_credentials()`` function, which generates a form to collect filesystem credentials. */();
												if(is_array($credentials = /* Does this function return us an array? */ request_filesystem_credentials($_SERVER["REQUEST_URI"], false, false, dirname(dirname(dirname(dirname(__FILE__)))))))
													c_ws_plugin__s2member_pro_upgrader::$credentials = /* Set static ``$credentials`` var. We have credentials now, possibly via data posted by site owner. */ $credentials;
												$credentials_form = /* Get form to collect credentials, although NOT needed by this routine. */ ob_get_clean();

												c_ws_plugin__s2member_pro_upgrader::maintenance /* Create the `.maintenance` file now. We don't want anything loading up on the site during this process. */(true);

												if(WP_Filesystem(c_ws_plugin__s2member_pro_upgrader::$credentials, ($plugins_dir = $_plugins_dir = dirname(dirname(dirname(dirname(__FILE__)))))) && ($plugins_dir = rtrim($wp_filesystem->find_folder($plugins_dir), "/")) && ($plugin_dir = rtrim($wp_filesystem->find_folder($_plugin_dir = dirname(dirname(dirname(__FILE__)))), "/")))
													{
														if(($tmp_zip = wp_unique_filename($_plugins_dir, basename($plugin_dir).".zip")) && ($_tmp_zip = $_plugins_dir."/".$tmp_zip) && ($tmp_zip = $plugins_dir."/".$tmp_zip) && $wp_filesystem->put_contents($tmp_zip, c_ws_plugin__s2member_utils_urls::remote($s2_pro_upgrade["zip"], false, array("timeout" => 120)), FS_CHMOD_FILE))
															{
																if((!$wp_filesystem->is_dir($plugin_dir."-new") || $wp_filesystem->delete($plugin_dir."-new", true)) && $wp_filesystem->mkdir($plugin_dir."-new", FS_CHMOD_DIR))
																	{
																		if(!is_wp_error($unzip = unzip_file /* Unzip into the `/s2member-pro-new` directory. */($_tmp_zip, $plugin_dir."-new")))
																			{
																				if(!$wp_filesystem->is_dir($plugin_dir) || $wp_filesystem->delete($plugin_dir, true) /* Point of no return. */)
																					{
																						if($wp_filesystem->move /* Live in this directory. */($plugin_dir."-new/s2member-pro", $plugin_dir))
																							{
																								$wp_filesystem->delete($plugin_dir."-new", true).$wp_filesystem->delete($tmp_zip);

																								$notice = 's2Member Pro successfully updated to v'.esc_html($s2_pro_upgrade["ver"]).'.';

																								do_action("ws_plugin__s2member_pro_during_successfull_upgrade", get_defined_vars());

																								c_ws_plugin__s2member_admin_notices::enqueue_admin_notice($notice, "blog|network:*");

																								c_ws_plugin__s2member_pro_upgrader::maintenance(false);

																								wp_redirect(self_admin_url("/plugins.php")).exit();
																							}
																						else // Bummer. OK, now we'll deal with cleanup & error reporting.
																							{
																								$wp_filesystem->delete($plugin_dir."-new", true).$wp_filesystem->delete($tmp_zip);

																								c_ws_plugin__s2member_pro_upgrader::$error = "Upgrade failed. Error #0009. Please upgrade via FTP.";
																							}
																					}
																				else // Bummer. OK, now we'll deal with cleanup & error reporting.
																					{
																						$wp_filesystem->delete($plugin_dir."-new", true).$wp_filesystem->delete($tmp_zip);

																						c_ws_plugin__s2member_pro_upgrader::$error = "Upgrade failed. Error #0008. Please upgrade via FTP.";
																					}
																			}
																		else // Bummer. OK, now we'll deal with cleanup & error reporting.
																			{
																				$wp_filesystem->delete($plugin_dir."-new", true).$wp_filesystem->delete($tmp_zip);

																				c_ws_plugin__s2member_pro_upgrader::$error = "Upgrade failed. Error #0007. ".$unzip->get_error_message()." ~ Please upgrade via FTP. ";
																			}
																	}
																else // Bummer. OK, now we'll deal with cleanup & error reporting.
																	{
																		$wp_filesystem->delete($plugin_dir."-new", true).$wp_filesystem->delete($tmp_zip);

																		c_ws_plugin__s2member_pro_upgrader::$error = "Upgrade failed. Error #0006. Please upgrade via FTP.";
																	}
															}
														else // Bummer. OK, now we'll deal with cleanup & error reporting.
															{
																$wp_filesystem->delete($plugin_dir."-new", true).$wp_filesystem->delete($tmp_zip);

																c_ws_plugin__s2member_pro_upgrader::$error = "Upgrade failed. Error #0005. Please upgrade via FTP.";
															}
													}
												else // Bummer. OK, error reporting (no cleanup). Wizard handles `#0004`. Use `#0004` in ``::$error``.
													{
														c_ws_plugin__s2member_pro_upgrader::$error = // Wizard handles. Use `#0004` in ``::$error``.
														"Upgrade failed. Error #0004. Please upgrade via FTP, or supply valid Filesystem Credentials.";
													}
												c_ws_plugin__s2member_pro_upgrader::maintenance /* Remove the `.maintenance` file now. */(false);
											}
										else if(!empty($s2_pro_upgrade) && $s2_pro_upgrade === /* Forbidden? */ "403 Forbidden")
											{
												c_ws_plugin__s2member_pro_upgrader::$error = "Upgrade failed. Invalid Username/Password (or License Key); please try again.";
											}
										else if(!empty($s2_pro_upgrade) && $s2_pro_upgrade === "503 Service Unavailable")
											{
												c_ws_plugin__s2member_pro_upgrader::$error = "Upgrade failed. Service currently unavailable (please try again).";
											}
										else // Else, display a default error message (server unavailable). Possible connectivity issues.
											{
												c_ws_plugin__s2member_pro_upgrader::$error = "Upgrade failed. Connection failed (please try again).";
											}
									}
								else // Insufficient memory. This requires some special attention. Unzipping large files requires memory.
									{
										c_ws_plugin__s2member_pro_upgrader::$error = "Not enough memory.".
											" Unzipping s2Member Pro via WordPress requires ".WP_MAX_MEMORY_LIMIT." of RAM.".
											" Please upgrade via FTP instead.</code>.";
									}
							}
						return /* Return for uniformity. */;
					}
				/**
				* Maintenance mode.
				*
				* @package s2Member\Upgrader
				* @since 1.5
				*
				* @param bool $enable If true, enable maintenance mode. If false, disable maintenance mode.
				* @return bool This function always returns true.
				*/
				public static function maintenance($enable = NULL)
					{
						global /* Need this global object reference. */ $wp_filesystem;

						if(is_bool($enable) && apply_filters("ws_plugin__s2member_pro_upgrade_maintenance", ($_SERVER["HTTP_HOST"] !== "www.s2member.com"), get_defined_vars()))
							{
								if($enable === true && WP_Filesystem(c_ws_plugin__s2member_pro_upgrader::$credentials, ABSPATH) && ($maintenance = $wp_filesystem->abspath().".maintenance"))
									$wp_filesystem->delete($maintenance).$wp_filesystem->put_contents($maintenance, '<?php $upgrading = '.time().'; ?>', FS_CHMOD_FILE);

								else if($enable === false && WP_Filesystem(c_ws_plugin__s2member_pro_upgrader::$credentials, ABSPATH) && ($maintenance = $wp_filesystem->abspath().".maintenance"))
									$wp_filesystem->delete($maintenance);
							}
						return /* Always return true. */ true;
					}

				/**
				 * Converts an abbreviated byte notation into bytes.
				 *
				 * @package s2Member\Upgrader
				 * @since 130819
				 *
				 * @param string $string A string value in byte notation.
				 *
				 * @return float A float indicating the number of bytes.
				 */
				public static function abbr_bytes($string)
					{
						$string = (string)$string;

						$notation = '/^(?P<value>[0-9\.]+)\s*(?P<modifier>bytes|byte|kbs|kb|k|mb|m|gb|g|tb|t)$/i';

						if(!preg_match($notation, $string, $_op))
							return (float)0;

						$value    = (float)$_op['value'];
						$modifier = strtolower($_op['modifier']);
						unset($_op); // Housekeeping.

						switch($modifier) // Fall through based on modifier.
						{
							case 't': // Multiplied four times.
							case 'tb':
									$value *= 1024;
							case 'g': // Multiplied three times.
							case 'gb':
									$value *= 1024;
							case 'm': // Multiple two times.
							case 'mb':
									$value *= 1024;
							case 'k': // One time only.
							case 'kb':
							case 'kbs':
									$value *= 1024;
						}
						return (float)$value;
					}
			}
	}
