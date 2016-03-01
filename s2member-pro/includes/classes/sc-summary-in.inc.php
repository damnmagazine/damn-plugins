<?php
/**
 * [s2Member-Summary] Shortcode.
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
 * @since 150712
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_sc_summary_in'))
{
	/**
	 * [s2Member-Summary] Shortcode.
	 *
	 * @package s2Member\Shortcodes
	 * @since 150712
	 */
	class c_ws_plugin__s2member_pro_sc_summary_in
	{
		/**
		 * [s2Member-Summary] Shortcode.
		 *
		 * @package s2Member\Shortcodes
		 * @since 150712
		 *
		 * @attaches-to ``add_shortcode('s2Member-Summary');``
		 *
		 * @param array  $attr An array of Attributes.
		 * @param string $content Content inside the Shortcode.
		 * @param string $shortcode The actual Shortcode name itself.
		 *
		 * @return string Summary widget.
		 */
		public static function shortcode($attr_args_options = array(), $content = '', $shortcode = '')
		{
			foreach(array_keys(get_defined_vars()) as $__v) $__refs[$__v] =& $$__v;
			do_action('c_ws_plugin__s2member_pro_before_sc_summary', get_defined_vars());
			unset($__refs, $__v); // Housekeeping.

			$attr_args_options = (array)$attr_args_options;

			$default_attr = array(
				'show_login_if_not_logged_in' => '0',
			);
			$attr    = array_merge($default_attr, $attr_args_options);
			$attr    = array_intersect_key($attr, $default_attr);

			$default_args = array(
				'before_widget' => '',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
				'after_widget'  => '',
			);
			$args    = array_merge($default_args, $attr_args_options);
			$args    = array_intersect_key($args, $default_args);

			$options = array_diff_key($attr_args_options, $attr, $args);

			if(!is_user_logged_in() && !filter_var($attr['show_login_if_not_logged_in'], FILTER_VALIDATE_BOOLEAN))
				$summary = ''; // Empty. Logged in already.

			else // Login widget if not logged in, else profile summary.
			{
				ob_start(); // Begin output buffering.
				c_ws_plugin__s2member_pro_login_widget::___static_widget___($args, $options);
				$summary = ob_get_clean();
			}
			if($summary) // Wrapper for CSS styling.
				$summary = '<div class="ws-plugin--s2member-sc-summary">'.$summary.'</div>';

			return apply_filters('c_ws_plugin__s2member_pro_sc_summary', $summary, get_defined_vars());
		}
	}
}
