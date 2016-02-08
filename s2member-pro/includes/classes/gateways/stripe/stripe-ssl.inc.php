<?php
/**
 * Stripe SSL.
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
 * @package s2Member\Stripe
 * @since 140617
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_stripe_ssl'))
{
	/**
	 * Stripe SSL.
	 *
	 * @package s2Member\Stripe
	 * @since 140617
	 */
	class c_ws_plugin__s2member_pro_stripe_ssl
	{
		/**
		 * Auto-detects Pro-Forms when Auto SSL is enabled.
		 *
		 * @package s2Member\Stripe
		 * @since 140617
		 *
		 * @attaches-to ``add_filter('ws_plugin__s2member_check_force_ssl');``
		 *
		 * @param bool  $force Expects a boolean value passed through by the Filter.
		 * @param array $vars Expects an array of defined variables, passed through by the Filter.
		 *
		 * @return bool True if SSL is not currently being forced, but this routine detected that it should be; else the existing value of ``$force``.
		 */
		public static function sc_stripe_form_auto_force_ssl($force, $vars)
		{
			global $post; // Need this global object reference.

			if(!$force && defined('S2MEMBER_PRO_AUTO_FORCE_SSL') && S2MEMBER_PRO_AUTO_FORCE_SSL)
				if(is_object($post) && strpos($post->post_content, '[s2Member-Pro-Stripe-Form') !== FALSE)
					return ($force = TRUE);

			return $force; // Keep current value.
		}
	}
}