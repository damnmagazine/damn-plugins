<?php
/**
* Google JWT generator.
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
* @package s2Member\Google
* @since 131123
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit ("Do not access this file directly.");

if (!class_exists ("c_ws_plugin__s2member_pro_google_jwt_in"))
	{
		/**
		* Google JWT generator.
		*
		* @package s2Member\Google
		* @since 131123
		*/
		class c_ws_plugin__s2member_pro_google_jwt_in
			{
				/**
				* Google JWT generator.
				*
				* @package s2Member\Google
				* @since 131123
				*
				* @param array $attr An array of Attributes.
				* @param string $content Content inside the Shortcode.
				* @param string $shortcode The actual Shortcode name itself.
				* @return string The resulting Google Button Code, HTML markup.
				*/
				public static function google_jwt ()
					{
						if (empty($_GET["s2member_pro_google_jwt"]))
							return; // Nothing to do.

						status_header (200); // Send a 200 OK status header.
						header ("Content-Type: text/plain"); // Google expects text/plain here.
						while (@ob_end_clean ()); // Clean any existing output buffers.

						$current_user = wp_get_current_user();

						if(!empty($_REQUEST["s2member_pro_google_jwt_vars"]["email"]))
							$em = stripslashes((string)$_REQUEST["s2member_pro_google_jwt_vars"]["email"]);
						else if($current_user && !empty($current_user->user_email))
							$em = $current_user->user_email;
						else exit(); // Not possible.

						if(!empty($_REQUEST["s2member_pro_google_jwt_vars"]["fname"]))
							$fn = stripslashes((string)$_REQUEST["s2member_pro_google_jwt_vars"]["fname"]);
						else if($current_user && !empty($current_user->first_name))
							$fn = $current_user->first_name;
						else if($current_user && !empty($current_user->display_name))
							$fn = $current_user->display_name;
						else $fn = $em;

						if(!empty($_REQUEST["s2member_pro_google_jwt_vars"]["lname"]))
							$ln = stripslashes((string)$_REQUEST["s2member_pro_google_jwt_vars"]["lname"]);
						else if($current_user && !empty($current_user->last_name))
							$ln = $current_user->last_name;
						else $ln = ""; // No last name available.

						if (empty($_REQUEST["s2member_pro_google_jwt_vars"]["attr"])) exit();
						$attr = stripslashes((string)$_REQUEST["s2member_pro_google_jwt_vars"]["attr"]);
						$attr = (array)unserialize(c_ws_plugin__s2member_utils_encryption::decrypt($attr));

						$attr = array_merge(array("ids" => "0", "exp" => "72", "level" => "1", "ccaps" => "", "desc" => "", "cc" => "USD", "custom" => $_SERVER["HTTP_HOST"], "ta" => "0", "tp" => "0", "tt" => "D", "ra" => "0.01", "rp" => "1", "rt" => "M", "rr" => "1", "rrt" => "", "modify" => "0", "cancel" => "0", "sp" => "0", "image" => "default", "output" => "anchor", "success" => "", "failure" => ""), $attr);

						$attr["tt"] = /* Term lengths absolutely must be provided in upper-case format. Only after running shortcode_atts(). */ strtoupper ($attr["tt"]);
						$attr["rt"] = /* Term lengths absolutely must be provided in upper-case format. Only after running shortcode_atts(). */ strtoupper ($attr["rt"]);
						$attr["rr"] = /* Must be provided in upper-case format. Numerical, or BN value. Only after running shortcode_atts(). */ strtoupper ($attr["rr"]);
						$attr["ccaps"] = /* Custom Capabilities must be typed in lower-case format. Only after running shortcode_atts(). */ strtolower ($attr["ccaps"]);
						$attr["ccaps"] = /* Custom Capabilities should not have spaces. */ str_replace(" ", "", $attr["ccaps"]);
						$attr["rr"] = /* Lifetime Subscriptions require Buy Now. Only after running shortcode_atts(). */ ($attr["rt"] === "L") ? "BN" : $attr["rr"];
						$attr["rr"] = /* Independent Ccaps do NOT recur. Only after running shortcode_atts(). */ ($attr["level"] === "*") ? "BN" : $attr["rr"];
						$attr["rr"] = /* No Trial / non-recurring. Only after running shortcode_atts(). */ (!$attr["tp"] && !$attr["rr"]) ? "BN" : $attr["rr"];
						$attr["referencing"] = c_ws_plugin__s2member_utils_users::get_user_subscr_or_wp_id ();

						include_once dirname(dirname(dirname(dirname(__FILE__)))).'/_xtnls/JWT.php';

						if /* Specific Post/Page Buttons. */ ($attr["sp"])
							{
								$attr["sp_ids_exp"] = "sp:" . $attr["ids"] . ":" . $attr["exp"];

								$jwt["iss"] = $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_google_merchant_id"];
								$jwt["aud"] = "Google"; $jwt["typ"] = "google/payments/inapp/item/v1";
								$jwt["exp"] = time() + 3600; $jwt["iat"] = time();
								$jwt["request"] = array("name" => substr($_SERVER["HTTP_HOST"], 0, 50), "description" => substr($attr["desc"], 0, 100),
																"price" => number_format($attr["ra"], 2, ".", ""), "currencyCode" => $attr["cc"],
																"sellerData" => json_encode(
																	array("cs" => $attr["custom"],
																			"in" => $attr["sp_ids_exp"],
																			"ip" => $_SERVER["REMOTE_ADDR"],
																			"rf" => $attr["referencing"],
																			"fn" => $fn, "ln" => $ln, "em" => $em)));

								$jwt = JWT::encode($jwt, $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_google_merchant_key"]);
							}
						else if /* Independent Custom Capabilities. */ ($attr["level"] === "*")
							{
								$attr["level_ccaps_eotper"] = ($attr["rt"] !== "L") ? $attr["level"] . ":" . $attr["ccaps"] . ":" . $attr["rp"] . " " . $attr["rt"] : $attr["level"] . ":" . $attr["ccaps"];
								$attr["level_ccaps_eotper"] = rtrim ($attr["level_ccaps_eotper"], ":"); // Right-trim separators from this string so we don't have trailing colons.

								$jwt["iss"] = $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_google_merchant_id"];
								$jwt["aud"] = "Google"; $jwt["typ"] = "google/payments/inapp/item/v1";
								$jwt["exp"] = time() + 3600; $jwt["iat"] = time();
								$jwt["request"] = array("name" => substr($_SERVER["HTTP_HOST"], 0, 50), "description" => substr($attr["desc"], 0, 100),
																"price" => number_format($attr["ra"], 2, ".", ""), "currencyCode" => $attr["cc"],
																"sellerData" => json_encode(
																	array("cs" => $attr["custom"],
																			"in" => $attr["level_ccaps_eotper"],
																			"ip" => $_SERVER["REMOTE_ADDR"],
																			"rf" => $attr["referencing"],
																			"fn" => $fn, "ln" => $ln, "em" => $em)));

								$jwt = JWT::encode($jwt, $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_google_merchant_key"]);
							}
						else if ($attr["rr"] === "BN" || (!$attr["tp"] && !$attr["rr"])) // Buy Now.
							{
								$attr["desc"] = (!$attr["desc"]) ? $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["level" . $attr["level"] . "_label"] : $attr["desc"];

								$attr["level_ccaps_eotper"] = ($attr["rt"] !== "L") ? $attr["level"] . ":" . $attr["ccaps"] . ":" . $attr["rp"] . " " . $attr["rt"] : $attr["level"] . ":" . $attr["ccaps"];
								$attr["level_ccaps_eotper"] = rtrim ($attr["level_ccaps_eotper"], ":"); // Right-trim separators from this string so we don't have trailing colons.

								$jwt["iss"] = $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_google_merchant_id"];
								$jwt["aud"] = "Google"; $jwt["typ"] = "google/payments/inapp/item/v1";
								$jwt["exp"] = time() + 3600; $jwt["iat"] = time();
								$jwt["request"] = array("name" => substr($_SERVER["HTTP_HOST"], 0, 50), "description" => substr($attr["desc"], 0, 100),
																"price" => number_format($attr["ra"], 2, ".", ""), "currencyCode" => $attr["cc"],
																"sellerData" => json_encode(
																	array("cs" => $attr["custom"],
																			"in" => $attr["level_ccaps_eotper"],
																			"ip" => $_SERVER["REMOTE_ADDR"],
																			"rf" => $attr["referencing"],
																			"fn" => $fn, "ln" => $ln, "em" => $em)));

								$jwt = JWT::encode($jwt, $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_google_merchant_key"]);
							}
						else // Otherwise, we'll process this Button normally, using Membership routines.
							{
								$attr["desc"] = (!$attr["desc"]) ? $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["level" . $attr["level"] . "_label"] : $attr["desc"];

								$attr["level_ccaps_eotper"] = $attr["level"] . ":" . $attr["ccaps"]; // Actual Subscriptions will always end on their own.
								$attr["level_ccaps_eotper"] = rtrim ($attr["level_ccaps_eotper"], ":"); // Clean any trailing separators from this string.

								$attr["periodicity"] = c_ws_plugin__s2member_pro_google_utilities::google_periodicity ($attr["rp"] . " " . $attr["rt"]);

								if ($attr["tp"]) // An actual Subscription that includes a Trial Period; which MIGHT also be recurring.
									{
										$attr["start_time"] = c_ws_plugin__s2member_pro_google_utilities::google_start_time ($attr["tp"] . " " . $attr["tt"]);

										$jwt["iss"] = $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_google_merchant_id"];
										$jwt["aud"] = "Google"; $jwt["typ"] = "google/payments/inapp/subscription/v1";
										$jwt["exp"] = time() + 3600; $jwt["iat"] = time();
										$jwt["request"] = array("name" => substr($_SERVER["HTTP_HOST"], 0, 50),
								                        "description" => substr($attr["desc"], 0, 100),
																"initialPayment" =>
																	array("price" => number_format($attr["ta"], 2, ".", ""), "currencyCode" => $attr["cc"],
																			"paymentType" => (($attr["ta"] > 0) ? "prorated" : "free_trial")),
																"recurrence" =>
																	array("price" => number_format($attr["ra"], 2, ".", ""), "currencyCode" => $attr["cc"],
																			"startTime" => $attr["start_time"], "frequency" => $attr["periodicity"],
																			"numRecurrences" => ((!$attr["rr"]) ? 1 : (($attr["rrt"]) ? $attr["rrt"] :  NULL))),
																"sellerData" => json_encode(
																	array("cs" => $attr["custom"],
																			"in" => $attr["level_ccaps_eotper"],
																			"p1" => $attr["tp"] . " " . $attr["tt"],
																			"p3" => $attr["rp"] . " " . $attr["rt"],
																			"rr" => $attr["rr"],
																			"ip" => $_SERVER["REMOTE_ADDR"],
																			"rf" => $attr["referencing"],
																			"fn" => $fn, "ln" => $ln, "em" => $em)));
									}
								else if (!$attr["tp"] && $attr["rr"]) /* This is a Subscription w/o a Trial Period; and it IS associated with multiple recurring charges.
											This should ALWAYS be associated with recurring charges, because of the "BN" check above that includes (!$attr["tp"] && !$attr["rr"]).
											In other words, we should never have a Subscription w/o a Trial Period, AND no recurring charges. That would make no sense. */
									{
										$attr["start_time"] = c_ws_plugin__s2member_pro_google_utilities::google_start_time ($attr["rp"] . " " . $attr["rt"]);

										$jwt["iss"] = $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_google_merchant_id"];
										$jwt["aud"] = "Google"; $jwt["typ"] = "google/payments/inapp/subscription/v1";
										$jwt["exp"] = time() + 3600; $jwt["iat"] = time();
										$jwt["request"] = array("name" => substr($_SERVER["HTTP_HOST"], 0, 50),
								                        "description" => substr($attr["desc"], 0, 100),
																"initialPayment" =>
																	array("price" => number_format($attr["ra"], 2, ".", ""), "currencyCode" => $attr["cc"],
																			"paymentType" => "prorated" /* No choice in the matter; always prorated by Google. */),
																"recurrence" =>
																	array("price" => number_format($attr["ra"], 2, ".", ""), "currencyCode" => $attr["cc"],
																			"startTime" => $attr["start_time"], "frequency" => $attr["periodicity"],
																			"numRecurrences" => ((!$attr["rr"]) ? 1 : (($attr["rrt"]) ? $attr["rrt"] :  NULL))),
																"sellerData" => json_encode(
																	array("cs" => $attr["custom"],
																			"in" => $attr["level_ccaps_eotper"],
																			"p1" => "0 D", // No trial period in this case.
																			"p3" => $attr["rp"] . " " . $attr["rt"],
																			"rr" => $attr["rr"],
																			"ip" => $_SERVER["REMOTE_ADDR"],
																			"rf" => $attr["referencing"],
																			"fn" => $fn, "ln" => $ln, "em" => $em)));
									}
								$jwt = JWT::encode($jwt, $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_google_merchant_key"]);
							}
						exit((!empty($jwt)) ? $jwt : "");
					}
			}
	}
