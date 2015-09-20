<?php
/**
 * ClickBank utilities.
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
 * @package s2Member\ClickBank
 * @since 1.5
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_clickbank_utilities'))
{
	/**
	 * ClickBank utilities.
	 *
	 * @package s2Member\ClickBank
	 * @since 1.5
	 */
	class c_ws_plugin__s2member_pro_clickbank_utilities
	{
		/**
		 * Get ``$_POST`` or ``$_REQUEST`` vars from ClickBank.
		 *
		 * @package s2Member\ClickBank
		 * @since 140806
		 *
		 * @return array|bool An array of verified ``$_POST`` or ``$_REQUEST`` variables, else false.
		 */
		public static function clickbank_postvars()
		{
			if(!empty($_REQUEST['s2member_pro_clickbank_return']) && !empty($_REQUEST['cbpop']))
			{
				$postvars = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_REQUEST));

				foreach($postvars as $var => $value)
					if(preg_match('/^s2member_/', $var))
						unset($postvars[$var]);

				$cbpop = $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_clickbank_secret_key'];
				$cbpop .= '|'.$postvars['cbreceipt'].'|'.$postvars['time'].'|'.$postvars['item'];

				$mb    = function_exists('mb_convert_encoding') ? @mb_convert_encoding($cbpop, 'UTF-8', $GLOBALS['WS_PLUGIN__']['s2member']['c']['mb_detection_order']) : $cbpop;
				$cbpop = ($mb) ? $mb : $cbpop; // Double check this, just in case conversion fails.
				$cbpop = strtoupper(substr(sha1($cbpop), 0, 8));

				if($postvars['cbpop'] === $cbpop)
					return $postvars;
			}
			else if(!empty($_REQUEST['s2member_pro_clickbank_notify']) && is_object($input = json_decode(file_get_contents('php://input'))))
			{
				$encryption_iv          = base64_decode($input->iv);
				$encrypted_notification = base64_decode($input->notification);
				$key                    = substr(sha1($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_clickbank_secret_key']), 0, 32);
				$decrypted_notification = trim(trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encrypted_notification, MCRYPT_MODE_CBC, $encryption_iv), "\0..\32"));
				if(function_exists('mb_convert_encoding')) $decrypted_notification = mb_convert_encoding($decrypted_notification, 'UTF-8', $GLOBALS['WS_PLUGIN__']['s2member']['c']['mb_detection_order']);

				if($decrypted_notification && ($postvars = (array)json_decode($decrypted_notification)))
					return $postvars;
			}
			return FALSE;
		}

		/**
		 * Get ``$_POST`` or ``$_REQUEST`` vars from ClickBank.
		 *
		 * @package s2Member\ClickBank
		 * @since 1.5
		 *
		 * @return array|bool An array of verified ``$_POST`` or ``$_REQUEST`` variables, else false.
		 */
		public static function clickbank_postvars_v2_1()
		{
			if(!empty($_REQUEST['s2member_pro_clickbank_return']) && !empty($_REQUEST['cbpop']))
			{
				$postvars = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_REQUEST));

				foreach($postvars as $var => $value)
					if(preg_match('/^s2member_/', $var))
						unset($postvars[$var]);

				$cbpop = $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_clickbank_secret_key'];
				$cbpop .= '|'.$postvars['cbreceipt'].'|'.$postvars['time'].'|'.$postvars['item'];

				$mb    = function_exists('mb_convert_encoding') ? @mb_convert_encoding($cbpop, 'UTF-8', $GLOBALS['WS_PLUGIN__']['s2member']['c']['mb_detection_order']) : $cbpop;
				$cbpop = ($mb) ? $mb : $cbpop; // Double check this, just in case conversion fails.
				$cbpop = strtoupper(substr(sha1($cbpop), 0, 8));

				if($postvars['cbpop'] === $cbpop)
					return $postvars;
			}
			else if(!empty($_REQUEST['s2member_pro_clickbank_notify']) && !empty($_REQUEST['cverify']))
			{
				$postvars = c_ws_plugin__s2member_utils_strings::trim_deep(stripslashes_deep($_REQUEST));

				foreach($postvars as $var => $value)
					if(preg_match('/^s2member_/', $var))
						unset($postvars[$var]);

				$cverify = ''; // Initialize verification.

				($keys = array_keys($postvars)).sort($keys);
				foreach($keys as $key) // Go through keys.
					if($key && !preg_match('/^(cverify)$/', $key))
						$cverify .= $postvars[$key].'|';

				$cverify .= $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_clickbank_secret_key'];

				$mb      = function_exists('mb_convert_encoding') ? @mb_convert_encoding($cverify, 'UTF-8', $GLOBALS['WS_PLUGIN__']['s2member']['c']['mb_detection_order']) : $cverify;
				$cverify = ($mb) ? $mb : $cverify; // Double check this, just in case conversion fails.
				$cverify = strtoupper(substr(sha1($cverify), 0, 8));

				if($postvars['cverify'] === $cverify)
					return $postvars;
			}
			return FALSE;
		}

		/**
		 * Parses s2Vars passed through by ClickBank.
		 *
		 * @package s2Member\ClickBank
		 * @since 111205
		 *
		 * @param string $query Expects the URL-encoded query string of s2Vars located in the `downloadUrl` property, including `_s2member_sig`.
		 * @param string $type Optional. The type of ClickBank transaction. This deals with backward compatibility.
		 *   For SALE transactions, do NOT accept the older format. For others, remain backward compatible.
		 *
		 * @return array Array of s2Vars. Possibly an empty array.
		 */
		public static function clickbank_parse_s2vars($query = '', $type = '')
		{
			if(strpos($query, '?') !== FALSE) // A full URL or URI?
				$query = ltrim((string)strstr($query, '?'), '?');

			wp_parse_str((string)$query, $s2vars);
			$s2vars = c_ws_plugin__s2member_utils_strings::trim_deep($s2vars);

			foreach($s2vars as $var => $value /* Pulls out `s2_|_s2member_sig` vars. */)
				if(!in_array($var, array('cbskin', 'cbfid', 'cbur', 'cbf', 'tid', 'vtid'), TRUE)) // These may be included in a signature too.
					if(!preg_match('/^(?:s2_|_s2member_sig)/', $var)) // These will always be included in a signature.
						unset($s2vars[$var]);

			$is_sale = preg_match('/^(?:TEST_)?SALE$/i', (string)$type);
			if(!$is_sale || c_ws_plugin__s2member_utils_urls::s2member_sig_ok(http_build_query($s2vars, NULL, '&')))
				return $s2vars; // Looks good. Return ``$s2vars``.

			return array(); // Default empty array.
		}

		/**
		 * Parses s2Vars passed through by ClickBank.
		 *
		 * @package s2Member\ClickBank
		 * @since 111205
		 *
		 * @param string $cvendthru Expects the URL-encoded query string of s2Vars, including `_s2member_sig`.
		 * @param string $type Optional. The type of ClickBank transaction. This deals with backward compatibility.
		 *   For SALE transactions, do NOT accept the older format. For others, remain backward compatible.
		 *
		 * @return array Array of s2Vars. Possibly an empty array.
		 */
		public static function clickbank_parse_s2vars_v2_1($cvendthru = '', $type = '')
		{
			wp_parse_str((string)$cvendthru, $s2vars);
			$s2vars = c_ws_plugin__s2member_utils_strings::trim_deep($s2vars);

			foreach($s2vars as $var => $value /* Pulls out `s2_|_s2member_sig` vars. */)
				if(!in_array($var, array('cbskin', 'cbfid', 'cbur', 'cbf', 'tid', 'vtid'), TRUE)) // These may be included in a signature too.
					if(!preg_match('/^(?:s2_|_s2member_sig)/', $var)) // These will always be included in a signature.
						unset($s2vars[$var]);

			$is_sale = preg_match('/^(?:TEST_)?SALE$/i', (string)$type);
			if(!$is_sale || c_ws_plugin__s2member_utils_urls::s2member_sig_ok(http_build_query($s2vars, NULL, '&')))
				return $s2vars; // Looks good. Return ``$s2vars``.

			return array(); // Default empty array.
		}

		/**
		 * Formulates request Authorization headers.
		 *
		 * @package s2Member\ClickBank
		 * @since 1.5
		 *
		 * @return array Request Authorization headers for ClickBank API communication.
		 */
		public static function clickbank_api_headers()
		{
			$req['headers']['Accept']        = 'application/json';
			$req['headers']['Authorization'] = $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_clickbank_developer_key'].':'.$GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_clickbank_clerk_key'];

			return $req; // Return array with headers.
		}

		/**
		 * Credit card reminder on Return Page templates.
		 *
		 * @package s2Member\ClickBank
		 * @since 110720
		 *
		 * @attaches-to ``add_filter('ws_plugin__s2member_return_template_support');``
		 *
		 * @param string $support The current value for the `%%support%%` Replacement Code, passed through by the Filter.
		 * @param array  $vars An array of defined variables, passed through by the Filter.
		 *
		 * @return string The ``$support`` value, after possible modification.
		 */
		public static function clickbank_cc_reminder($support = '', $vars = array())
		{
			if(!empty($vars['template']) && $vars['template'] === 'clickbank')
				return $support. // Now add the reminder below this. ClickBank requires site owners to display this.
				       '<div class="cc-reminder">'._x('<strong>Reminder:</strong> Purchases at this site will appear on your credit card or bank statement as: <code>ClickBank</code> or <code>CLKBANK*COM</code>.', 's2member-front', 's2member').'</div>';

			return $support;
		}

		/**
		 * ClickBank order data via API access.
		 *
		 * @package s2Member\ClickBank
		 * @since 150714
		 *
		 * @param string $cbreceipt The ClickBank receipt ID.
		 *
		 * @return array Order data, else an empty array on failure.
		 */
		public static function clickbank_api_order($cbreceipt)
		{
			if(!($cbreceipt = trim((string)$cbreceipt)))
				return array();

			$headers  = self::clickbank_api_headers();
			$endpoint = 'https://api.clickbank.com/rest/1.3/orders/'.$cbreceipt;
			$response = c_ws_plugin__s2member_utils_urls::remote($endpoint, FALSE, array_merge($headers, array('timeout' => 20)));

			if(is_array($order = json_decode($response, TRUE)) && !empty($order['orderData']))
			{
				$order = $order['orderData']; // Assume one by default.
				if(isset($order[0]) && is_array($order[0]))
					$order = $order[0]; // First one.

				foreach($order as $_k => &$_v)
					if(is_array($_v) && isset($_v['@nil']))
						$_v = NULL; // Nullify properly.
				unset($_k, $_v); // Housekeeping.

				return $order;
			}
			return array();
		}
	}
}
