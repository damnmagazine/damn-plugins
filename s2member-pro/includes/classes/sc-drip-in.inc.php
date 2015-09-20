<?php
/**
 * [s2Drip] Shortcode.
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
 * @since 140328
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_sc_drip_in'))
{
	/**
	 * [s2Drip] Shortcode.
	 *
	 * @package s2Member\Shortcodes
	 * @since 140328
	 */
	class c_ws_plugin__s2member_pro_sc_drip_in
	{
		/**
		 * [s2Drip] Shortcode.
		 *
		 * @package s2Member\Shortcodes
		 * @since 140328
		 *
		 * @attaches-to ``add_shortcode('s2Drip');``
		 *
		 * @param array  $attr An array of Attributes.
		 * @param string $content Content inside the Shortcode.
		 * @param string $shortcode The actual Shortcode name itself.
		 *
		 * @return string Content if dripping is allowable, else an empty string.
		 */
		public static function shortcode($attr = array(), $content = '', $shortcode = '')
		{
			foreach(array_keys(get_defined_vars()) as $__v) $__refs[$__v] =& $$__v;
			do_action('ws_plugin__s2member_pro_before_sc_drip', get_defined_vars());
			unset($__refs, $__v);

			c_ws_plugin__s2member_no_cache::no_cache_constants(true);

			$attr = shortcode_atts(
				array(
					'level'    => '0',
					'access'   => '',
					'from_day' => '0',
					'to_day'   => '0',
				), $attr, $shortcode);

			if(!$attr['access'])
				$attr['access'] = 'level'.$attr['level'];

			$drip = c_ws_plugin__s2member_pro_user_drip_access::user_can_access_drip($attr['access'], $attr['from_day'], $attr['to_day']);

			return apply_filters('ws_plugin__s2member_pro_sc_drip_content', $drip ? do_shortcode($content) : '', get_defined_vars());
		}
	}
}
