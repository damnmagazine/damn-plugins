<?php
/**
 * [s2Member-Gift-Codes] Shortcode.
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
 * @since 150203
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_sc_gift_codes_in'))
{
	/**
	 * [s2Member-Gift-Codes] Shortcode.
	 *
	 * @package s2Member\Shortcodes
	 * @since 150203
	 */
	class c_ws_plugin__s2member_pro_sc_gift_codes_in
	{
		/**
		 * [s2Member-Gift-Codes] Shortcode.
		 *
		 * @package s2Member\Shortcodes
		 * @since 150203
		 *
		 * @attaches-to ``add_shortcode('s2Member-Gift-Codes');``
		 *
		 * @param array  $attr An array of Attributes.
		 * @param string $content Content inside the Shortcode.
		 * @param string $shortcode The actual Shortcode name itself.
		 *
		 * @return string List of Gift Codes.
		 */
		public static function shortcode($attr = array(), $content = '', $shortcode = '')
		{
			global $wpdb; // Global DB object reference.
			/** @var $wpdb wpdb Reference for IDEs. */

			foreach(array_keys(get_defined_vars()) as $__v) $__refs[$__v] =& $$__v;
			do_action('c_ws_plugin__s2member_pro_before_sc_gift_codes', get_defined_vars());
			unset($__refs, $__v);

			c_ws_plugin__s2member_no_cache::no_cache_constants(true);

			$default_attr = array(
				'quantity'  => '1',
				'discount'  => '100%',
				'directive' => '',
				'singulars' => '',
				'one_click' => '',
			);
			if(isset($attr['singular']) && !isset($attr['singulars']))
				$attr['singulars'] = $attr['singular'];
			$attr             = shortcode_atts($default_attr, $attr, $shortcode);
			$attr['quantity'] = (string)min($attr['quantity'], apply_filters('ws_plugin__s2member_pro_gifts_max_quantity', 1000));

			$hashable_attr = $attr;
			unset($hashable_attr['one_click']);

			$post_id         = is_singular() ? get_the_ID() : 0;
			$user            = wp_get_current_user(); // Current user.
			$sp_access_value = $post_id ? c_ws_plugin__s2member_sp_access::sp_access($post_id, 'read-only') : '';

			if($post_id && (($sp_access_value && is_string($sp_access_value)) || $user->ID))
			{
				$gifts         = array(); // Initialize.
				$coupons_class = new c_ws_plugin__s2member_pro_coupons();

				if($sp_access_value && is_string($sp_access_value))
				{
					$sp_hash              = md5($sp_access_value);
					$attr_hash            = hash('crc32b', serialize($hashable_attr));
					$option_key_for_gifts = 's2m_gcs_'.$post_id.'_'.$sp_hash.'_'.$attr_hash;

					if(!is_array($gifts = get_option($option_key_for_gifts)))
					{
						$gifts = $coupons_class->generate_gifts($attr); // Generate new gifts.
						$wpdb->query("DELETE FROM `".$wpdb->options."` WHERE `option_name` LIKE '%".esc_sql(c_ws_plugin__s2member_utils_strings::like_escape('s2m_gcs_'.$post_id.'_'.$sp_hash.'_'))."%'");
						add_option($option_key_for_gifts, $gifts, '', 'no'); // Store the new gifts.
					}
				}
				else if($user->ID) // Do we have a user ID?
				{
					$attr_hash                 = md5(serialize($hashable_attr));
					$user_option_key_for_gifts = 's2m_gcs_'.$post_id.'_'.$attr_hash;

					if(!is_array($gifts = get_user_option($user_option_key_for_gifts, $user->ID)))
					{
						$gifts = $coupons_class->generate_gifts($attr); // Generate new gifts.
						$wpdb->query("DELETE FROM `".$wpdb->usermeta."` WHERE `user_id` = '".esc_sql($user->ID)."' AND `meta_key` LIKE '%".esc_sql(c_ws_plugin__s2member_utils_strings::like_escape('s2m_gcs_'.$post_id.'_'))."%'");
						update_user_option($user->ID, $user_option_key_for_gifts, $gifts); // Store the new gifts.
					}
				}
				if($gifts) // Do we have gifts to display?
				{
					$content = '<table class="ws-plugin--s2member-gift-codes table table-condensed table-striped table-hover">'."\n";

					$content .= '<thead>'."\n";
					$content .= '<tr>'.
					            '<th class="-code">'._x('Redemption Code', 's2member-front', 's2member').'</th>'.
					            '<th class="-status">'._x('Status', 's2member-front', 's2member').'</th>'.
					            '</tr>'."\n";
					$content .= '</thead>'."\n";

					$content .= '<tbody>'."\n";

					foreach($gifts as $_gift)
					{
						if($coupons_class->get_uses($_gift['code']))
						{
							$content .= '<tr class="-status-used">'.
							            '<td class="-code"><s>'.esc_html($_gift['code']).'</s></td>'.
							            '<td class="-status">'._x('used', 's2member-front', 's2member').'</td>'.
							            '</tr>'."\n";
						}
						else // It's available for use; i.e., status = unused in this case.
						{
							$_one_click_url = $attr['one_click'] ? add_query_arg('s2p-coupon', urlencode($_gift['code']), $attr['one_click']) : '';

							$content .= '<tr class="-status-unused">'.
							            '<td class="-code">'.
							            ($attr['one_click'] && $_one_click_url // A click URL has been provided?
								            ? '<a href="'.esc_attr($_one_click_url).'" target="_blank" title="'._x('Click to Redeem', 's2member-front', 's2member').'" data-toggle="tooltip">'.esc_html($_gift['code']).'</a>'
								            : esc_html($_gift['code'])).'</td>'.
							            '<td class="-status">'._x('unused', 's2member-front', 's2member').'</td>'.
							            '</tr>'."\n";
						}
					}
					unset($_gift, $_one_click_url); // Housekeeping.

					$content .= '<tbody>'."\n";

					$content .= '</table>';
				}
			}
			return apply_filters('c_ws_plugin__s2member_pro_sc_gift_codes_content', $content, get_defined_vars());
		}
	}
}
