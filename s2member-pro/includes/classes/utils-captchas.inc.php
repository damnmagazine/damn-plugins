<?php
/**
* Captcha utilities (introduced by s2Member Pro).
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
* @package s2Member\Utilities
* @since 111203
*/
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if (!class_exists ('c_ws_plugin__s2member_pro_utils_captchas'))
	{
		/**
		* Captcha utilities (introduced by s2Member Pro).
		*
		* @package s2Member\Utilities
		* @since 111203
		*/
		class c_ws_plugin__s2member_pro_utils_captchas
			{
				/**
				* reCAPTCHA™ version filter.
				*
				* @package s2Member\Utilities
				* @since 150717
				*
				* @attaches-to ``add_filter('ws_plugin__s2member_recaptcha_version');``
				*
				* @param string The version passed in by the filter.
				* @param array $vars Variables passed in by the filter.
				*
				* @return string The version number.
				*/
				public static function recaptcha_version($version = '', $vars = array())
					{
						if($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_recaptcha2_public_key'])
							if($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_recaptcha2_private_key'])
								return '2'; // Version 2 API.

						return $version; // No change.
					}

				/**
				* Public/private keys to use for reCAPTCHA™.
				*
				* @package s2Member\Utilities
				* @since 111203
				*
				* @attaches-to ``add_filter('ws_plugin__s2member_recaptcha_keys');``
				*
				* @param array $keys An array with elements: `public` and `private`; passed through by the Filter.
				* @param array $vars An array of defined variables, passed through by the Filter.
				* @return array The array of ``$keys``, after possible modification.
				*/
				public static function recaptcha_keys($keys = array(), $vars = array())
					{
						if(self::recaptcha_version() === '2')
						{
							if(($public = $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_recaptcha2_public_key']) && ($private = $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_recaptcha2_private_key']))
								$keys = array_merge ((array)$keys, array('public' => $public, 'private' => $private));

							return $keys; // Not possible.
						}
						if(($public = $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_recaptcha_public_key']) && ($private = $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_recaptcha_private_key']))
							$keys = array_merge ((array)$keys, array('public' => $public, 'private' => $private));

						return $keys; // Not possible.
					}
			}
	}
