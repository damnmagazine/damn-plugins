<?php
/**
 * [s2Member-List /] Shortcode.
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
 * @since 140504
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_sc_member_list_in'))
{
	/**
	 * Shortcode for `[s2Member-List /]`.
	 *
	 * @package s2Member\Shortcodes
	 * @since 140504
	 */
	class c_ws_plugin__s2member_pro_sc_member_list_in
	{
		/**
		 * `[s2Member-List /]` Shortcode.
		 *
		 * @package s2Member\Shortcodes
		 * @since 140504
		 *
		 * @attaches-to ``add_shortcode('s2Member-List');``
		 *
		 * @param array  $attr An array of Attributes.
		 * @param string $content Content inside the Shortcode.
		 * @param string $shortcode The actual Shortcode name itself.
		 *
		 * @return mixed Template file output for this shortcode.
		 */
		public static function shortcode($attr = array(), $content = '', $shortcode = '')
		{
			$wpdb = $GLOBALS['wpdb'];
			/** @var $wpdb \wpdb For IDEs. */

			$defaults = array(
				'args'              => '',

				'blog'              => $GLOBALS['blog_id'],

				'rlc_satisfy'       => 'ALL', // `ALL` or `ANY`
				'role'              => '', 'level' => '', 'ccap' => '',
				'roles'             => '', 'levels' => '', 'ccaps' => '',
				'search'            => '', 'search_columns' => '', 'enable_list_search' => '',
				'include'           => '', 'exclude' => '',

				'order'             => 'DESC',
				'orderby'           => 'registered',
				'limit'             => 25,

				'template'          => '',

				'avatar_size'       => 48,
				'show_avatar'       => 'yes',
				'link_avatar'       => '', // http://www.gravatar.com/%%md5.email%%

				'show_display_name' => 'yes',
				'link_display_name' => '', // /members/%%nicename%%/

				'show_fields'       => '',
			);
			if(!empty($attr['orderby']) && in_array($attr['orderby'], array('login', 'nicename', 'email', 'url', 'display_name'), TRUE))
				$defaults['order'] = 'ASC'; // A more logical default when dealing with alphabetic ordering.

			$attr = shortcode_atts($defaults, $attr);

			if(!$attr['roles'] && $attr['role']) $attr['roles'] = $attr['role'];
			if(!isset($attr['levels'][0]) && isset($attr['level'][0])) $attr['levels'] = $attr['level'];
			if(!$attr['ccaps'] && $attr['ccap']) $attr['ccaps'] = $attr['ccap'];

			$attr['order']              = strtoupper($attr['order']);
			$attr['rlc_satisfy']        = strtoupper($attr['rlc_satisfy']);
			$attr['show_avatar']        = filter_var($attr['show_avatar'], FILTER_VALIDATE_BOOLEAN);
			$attr['show_display_name']  = filter_var($attr['show_display_name'], FILTER_VALIDATE_BOOLEAN);
			$attr['enable_list_search'] = filter_var($attr['enable_list_search'], FILTER_VALIDATE_BOOLEAN);

			if($attr['args']) // Custom args?
				$args = wp_parse_args($attr['args']);

			else // Convert shortcode attributes to args.
			{
				$args = array(
					'blog_id'        => (integer)$attr['blog'],

					'meta_query'     => array(),
					'search'         => $attr['search'],
					'search_columns' => preg_split('/[;,\s]+/', $attr['search_columns'], NULL, PREG_SPLIT_NO_EMPTY),
					'include'        => preg_split('/[;,\s]+/', $attr['include'], NULL, PREG_SPLIT_NO_EMPTY),
					'exclude'        => preg_split('/[;,\s]+/', $attr['exclude'], NULL, PREG_SPLIT_NO_EMPTY),

					'order'          => $attr['order'],
					'orderby'        => $attr['orderby'],
					'number'         => (integer)$attr['limit'],
				);
				if($attr['roles']) // Must satisfy all Roles in the list (default behavior).
				{
					foreach(preg_split('/[;,\s]+/', $attr['roles'], NULL, PREG_SPLIT_NO_EMPTY) as $_role)
						$args['meta_query'][] = array(
							'key'     => $wpdb->get_blog_prefix().'capabilities',
							'value'   => '"'.$_role.'"',
							'compare' => 'LIKE',
						);
					if($attr['rlc_satisfy'] === 'ANY') // Default is `ALL` (i.e., `AND`).
						$args['meta_query']['relation'] = 'OR';

					unset($_role); // Housekeeping.
				}
				if(isset($attr['levels'][0])) // Must satisfy all Levels in the list (default behavior).
				{
					foreach(preg_split('/[;,\s]+/', $attr['levels'], NULL, PREG_SPLIT_NO_EMPTY) as $_level)
						$args['meta_query'][] = array(
							'key'     => $wpdb->get_blog_prefix().'capabilities',
							'value'   => (int)$_level === 0 ? '"subscriber"' : '"s2member_level'.$_level.'"',
							'compare' => 'LIKE',
						);
					if($attr['rlc_satisfy'] === 'ANY') // Default is `ALL` (i.e., `AND`).
						$args['meta_query']['relation'] = 'OR';

					unset($_level); // Housekeeping.
				}
				if($attr['ccaps']) // Must satisfy all CCAPs in the list (default behavior).
				{
					foreach(preg_split('/[;,\s]+/', $attr['ccaps'], NULL, PREG_SPLIT_NO_EMPTY) as $_ccap)
						$args['meta_query'][] = array(
							'key'     => $wpdb->get_blog_prefix().'capabilities',
							'value'   => '"access_s2member_ccap_'.$_ccap.'"',
							'compare' => 'LIKE',
						);
					if($attr['rlc_satisfy'] === 'ANY') // Default is `ALL` (i.e., `AND`).
						$args['meta_query']['relation'] = 'OR';

					unset($_ccap); // Housekeeping.
				}
			}
			if(is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site())
				$args['blog_id'] = $GLOBALS['blog_id']; // Disallow for security reasons.

			$s_var = self::s_var();
			$p_var = self::p_var();
			if($attr['enable_list_search'] && !empty($_REQUEST[$s_var]))
				$args['search'] = trim(stripslashes($_REQUEST[$s_var]));

			$member_list_query = c_ws_plugin__s2member_pro_member_list::query($args);

			$custom_template = (is_file(TEMPLATEPATH.'/member-list.php')) ? TEMPLATEPATH.'/member-list.php' : '';
			$custom_template = (is_file(get_stylesheet_directory().'/member-list.php')) ? get_stylesheet_directory().'/member-list.php' : $custom_template;

			$custom_template = ($attr['template'] && is_file(TEMPLATEPATH.'/'.$attr['template'])) ? TEMPLATEPATH.'/'.$attr['template'] : $custom_template;
			$custom_template = ($attr['template'] && is_file(get_stylesheet_directory().'/'.$attr['template'])) ? get_stylesheet_directory().'/'.$attr['template'] : $custom_template;
			$custom_template = ($attr['template'] && is_file(WP_CONTENT_DIR.'/'.$attr['template'])) ? WP_CONTENT_DIR.'/'.$attr['template'] : $custom_template;

			if($attr['template'] && !$custom_template) // Unable to locate the template file?
				trigger_error(sprintf('Invalid `template=""` attribute. Could not find: `%1$s`.', esc_html($attr['template'])), E_USER_ERROR);

			$code = trim(file_get_contents((($custom_template) ? $custom_template : dirname(dirname(__FILE__)).'/templates/members/member-list.php')));
			$code = trim(((!$custom_template || !is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? c_ws_plugin__s2member_utilities::evl($code, get_defined_vars()) : $code));

			return apply_filters('ws_plugin__s2member_pro_sc_member_list', $code, get_defined_vars());
		}

		/**
		 * `[s2Member-List-Search-Box /]` Shortcode.
		 *
		 * @package s2Member\Shortcodes
		 * @since 140504
		 *
		 * @attaches-to ``add_shortcode('s2Member-List-Search-Box');``
		 *
		 * @param array  $attr An array of Attributes.
		 * @param string $content Content inside the Shortcode.
		 * @param string $shortcode The actual Shortcode name itself.
		 *
		 * @return mixed Template file output for this shortcode.
		 */
		public static function s_box_shortcode($attr = array(), $content = '', $shortcode = '')
		{
			$defaults = array(
				'template'    => '',
				'action'      => '',
				'placeholder' => _x('Search users...', 's2member-front', 's2member'),
			);
			$attr     = shortcode_atts($defaults, $attr);
			$s_var    = self::s_var();
			$p_var    = self::p_var();

			$custom_template = (is_file(TEMPLATEPATH.'/member-list-search-box.php')) ? TEMPLATEPATH.'/member-list-search-box.php' : '';
			$custom_template = (is_file(get_stylesheet_directory().'/member-list-search-box.php')) ? get_stylesheet_directory().'/member-list-search-box.php' : $custom_template;

			$custom_template = ($attr['template'] && is_file(TEMPLATEPATH.'/'.$attr['template'])) ? TEMPLATEPATH.'/'.$attr['template'] : $custom_template;
			$custom_template = ($attr['template'] && is_file(get_stylesheet_directory().'/'.$attr['template'])) ? get_stylesheet_directory().'/'.$attr['template'] : $custom_template;
			$custom_template = ($attr['template'] && is_file(WP_CONTENT_DIR.'/'.$attr['template'])) ? WP_CONTENT_DIR.'/'.$attr['template'] : $custom_template;

			if($attr['template'] && !$custom_template) // Unable to locate the template file?
				trigger_error(sprintf('Invalid `template=""` attribute. Could not find: `%1$s`.', esc_html($attr['template'])), E_USER_ERROR);

			$code = trim(file_get_contents((($custom_template) ? $custom_template : dirname(dirname(__FILE__)).'/templates/members/member-list-search-box.php')));
			$code = trim(((!$custom_template || !is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site()) ? c_ws_plugin__s2member_utilities::evl($code, get_defined_vars()) : $code));

			$hidden_inputs = ''; // Initialize.
			foreach(stripslashes_deep($_GET) as $_key => $_value) if($_key !== $s_var && $_key !== $p_var && is_scalar($_value))
				$hidden_inputs .= '<input type="hidden" name="'.esc_attr((string)$_key).'" value="'.esc_attr((string)$_value).'" />';
			$code = str_ireplace('%%hidden_inputs%%', $hidden_inputs, $code);
			unset($_key, $_value); // Housekeeping.

			return apply_filters('ws_plugin__s2member_pro_sc_member_list_search_box', $code, get_defined_vars());
		}

		/**
		 * Allows for customization over the search variable.
		 *
		 * @return string Search variable name; e.g., `s2-s` (default value).
		 */
		public static function s_var()
		{
			return apply_filters('ws_plugin__s2member_pro_sc_member_list_search_var', 's2-s');
		}

		/**
		 * Allows for customization over the page variable.
		 *
		 * @return string Page variable name; e.g., `s2-p` (default value).
		 */
		public static function p_var()
		{
			return apply_filters('ws_plugin__s2member_pro_member_list_page_var', 's2-p');
		}

		/**
		 * Parses user replacement codes.
		 *
		 * @package s2Member\Shortcodes
		 * @since 140504
		 *
		 * @param string  $string The string to parse.
		 * @param WP_User $user A WordPress `WP_User` object instance.
		 *
		 * @return string Parsed `$string` value.
		 *
		 * @note This is used by shortcode template files.
		 */
		public static function parse_replacement_codes($string, $user)
		{
			if(($string = (string)$string) && $user instanceof WP_User && $user->exists())
			{
				$string = str_ireplace('%%ID%%', c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($user->ID)), $string);
				$string = str_ireplace('%%username%%', c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($user->user_login)), $string);
				$string = str_ireplace('%%nicename%%', c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($user->user_nicename)), $string);
				$string = str_ireplace('%%display_name%%', c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($user->display_name)), $string);
				$string = str_ireplace('%%email%%', c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($user->user_email)), $string);
				$string = str_ireplace('%%md5.email%%', urlencode(md5(trim(strtolower($user->user_email)))), $string);
			}
			return preg_replace('/%%(.+?)%%/', '', $string);
		}

		/**
		 * Construct `<a>` tag attributes for a given `$link`.
		 *
		 * @package s2Member\Shortcodes
		 * @since 140504
		 *
		 * @param string $link Input link to check.
		 *
		 * @return string Link `<a>` tag attributes, if applicable.
		 *
		 * @note This is used by shortcode template files.
		 */
		public static function link_attributes($link)
		{
			if(($link = (string)$link))
			{
				if(strpos($link, '//') !== FALSE)
					if(stripos($link, $_SERVER['HTTP_HOST']) === FALSE)
						$attr = ' target="_blank" rel="external nofollow"';
			}
			return !empty($attr) ? $attr : '';
		}
	}
}
