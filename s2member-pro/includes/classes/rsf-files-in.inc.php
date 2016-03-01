<?php
/**
 * Resolution SMIL Files.
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
 * @package s2Member\Shortcodes
 * @since 140814
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_rsf_files_in'))
{
	/**
	 * Resolution SMIL Files.
	 *
	 * @package s2Member\Shortcodes
	 * @since 140814
	 */
	class c_ws_plugin__s2member_pro_rsf_files_in
	{
		/**
		 * Resolution SMIL Files.
		 *
		 * @package s2Member\Shortcodes
		 * @since 140814
		 *
		 * @see http://tools.ietf.org/html/rfc4536
		 */
		public static function serve()
		{
			if(empty($_GET['s2member_rsf_file']))
				return; // Nothing to do here.

			header('Content-Type: application/smil+xml; charset=UTF-8');
			while(@ob_end_clean()) ; // Clean any existing output buffers.

			$smil_file_id = trim(stripslashes((string)$_GET['s2member_rsf_file']));

			if(empty($_GET['s2member_rsf_file_ip']) // IP address must match up.
			   || trim(stripslashes($_GET['s2member_rsf_file_ip'])) !== $_SERVER['REMOTE_ADDR']
			) exit; // Invalid and/or missing IP address.

			if(!c_ws_plugin__s2member_utils_urls::s2member_sig_ok($_SERVER['REQUEST_URI'], TRUE, 86400))
				exit; // Fail here. Invalid and/or expired SMIL file ID.

			if(!($smil_file = get_transient('s2m_rsf_'.$smil_file_id)))
				exit; // Fail here. Invalid and/or expired SMIL file ID.

			exit($smil_file);
		}
	}
}