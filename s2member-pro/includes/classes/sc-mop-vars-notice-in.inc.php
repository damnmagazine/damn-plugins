<?php
/**
 * Shortcode for `[s2MOP /]`.
 *
 * Copyright: © 2009-2011
 * {@link http://www.websharks-inc.com/ WebSharks, Inc.}
 * (coded in the USA)
 *
 * Released under the terms of the GNU General Public License.
 * You should have received a copy of the GNU General Public License,
 * along with this software. In the main directory, see: /licensing/
 * If not, see: {@link http://www.gnu.org/licenses/}.
 *
 * @package s2Member\Shortcodes
 * @since 140331
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit ('Do not access this file directly.');

/*
 * Working shortcode examples (as of Raam's changes on 2014-09-12 21:27:40 EDT):
 *
 * [s2MOP]You were trying to access a protected: %%SEEKING_TYPE%%[/s2MOP]
 * [s2MOP]You were trying to access content that requires a %%REQUIRED_TYPE%% that you don't have.[/s2MOP]
 * [s2MOP]You were trying to access content protected via s2Member: %%RESTRICTION_TYPE%% restrictions[/s2MOP]
 * [s2MOP seeking_type="post" required_type="level" restriction_type="post"]You were trying to access a restricted post with Post ID #%%SEEKING_POST_ID%% which requires that you be a Member at Level #%%REQUIRED_LEVEL%%[/s2MOP]
 * [s2MOP required_type="level"]"%%POST_TITLE%%" is a protected %%SEEKING_TYPE%% that requires Membership Level #%%REQUIRED_LEVEL%%[/s2MOP]
 * [s2MOP required_type="ccap" required_value="free_gift"]This content is restricted to Custom Capability <code>free_gift</code>[/s2MOP]
 */

if(!class_exists('c_ws_plugin__s2member_pro_sc_mop_vars_notice_in'))
{
	/**
	 * Shortcode for `[s2MOP /]`.
	 *
	 * @package s2Member\Shortcodes
	 * @since 140331
	 */
	class c_ws_plugin__s2member_pro_sc_mop_vars_notice_in
	{
		public static function shortcode($attr = array(), $content = '', $shortcode = '')
		{
			$_g = stripslashes_deep($_GET); // Cleanup the query string vars.

			if(!isset($_g['_s2member_seeking']) || !is_array($_g['_s2member_seeking']) || empty($_SERVER['QUERY_STRING'])
			   || !c_ws_plugin__s2member_utils_urls::s2member_sig_ok($_SERVER['QUERY_STRING'])
				// The query string is going to contain the new MOP Vars, whereas this works on the old ones.
				//    Still, that's OK for now. The new query string vars use an s2Member signature too;
				//       and the data we're looking at is derived from the query string vars.
			) return '';

			$valid_required_types    = array('level', 'ccap', 'sp');
			$valid_seeking_types     = array('page', 'post', 'catg', 'ptag', 'file', 'ruri');
			$valid_restriction_types = array('page', 'post', 'catg', 'ptag', 'file', 'ruri', 'ccap', 'sp', 'sys');
			$attr                    = shortcode_atts(array('seeking_type' => '', 'required_type' => '', 'required_value' => '', 'restriction_type' => ''), $attr, $shortcode);

			# ---------------------------------------------------------------------------------------------------

			if($attr['seeking_type'] !== '' || $attr['required_type'] !== '' || $attr['restriction_type'] !== '')
			{
				$attr['seeking_type']     = array_unique(preg_split('/[|;,\s]+/', $attr['seeking_type'], NULL, PREG_SPLIT_NO_EMPTY));
				$attr['required_type']    = array_unique(preg_split('/[|;,\s]+/', $attr['required_type'], NULL, PREG_SPLIT_NO_EMPTY));
				$attr['required_value']   = array_unique(preg_split('/[|;,\s]+/', $attr['required_value'], NULL, PREG_SPLIT_NO_EMPTY));
				$attr['restriction_type'] = array_unique(preg_split('/[|;,\s]+/', $attr['restriction_type'], NULL, PREG_SPLIT_NO_EMPTY));

				if(array_intersect($attr['seeking_type'], $valid_seeking_types))
					if(empty($_g['_s2member_seeking']['type']) || !in_array($_g['_s2member_seeking']['type'], $attr['seeking_type'], TRUE))
						return '';

				if(array_intersect($attr['required_type'], $valid_required_types))
				{
					if(empty($_g['_s2member_req']['type']) || !in_array($_g['_s2member_req']['type'], $attr['required_type'], TRUE))
						return '';

					$required_type = $_g['_s2member_req']['type'];
					if(!empty($attr['required_value']) && ( count($attr['required_type']) !== 1 || !in_array($_g['_s2member_req'][$required_type], $attr['required_value'], TRUE)))
						return '';
				}
				if(array_intersect($attr['restriction_type'], $valid_restriction_types))
					if(empty($_g['_s2member_res']['type']) || !in_array($_g['_s2member_res']['type'], $attr['restriction_type'], TRUE))
						return '';
			}
			# ---------------------------------------------------------------------------------------------------

			if(!empty($_g['_s2member_seeking']['type']) /* One of: page|post|catg|ptag|file|ruri */)
			{
				$seeking_type_tag = ''; // Initialize.

				// Let's give the replacement tags a name that's useful for building messages
				switch(strtolower($_g['_s2member_seeking']['type']))
				{
					case 'page':
						$seeking_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_seeking_type_page', 'Page', get_defined_vars());
						break;

					case 'post':
						$seeking_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_seeking_type_post', 'Post', get_defined_vars());;
						break;

					case 'catg':
						$seeking_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_seeking_type_catg', 'Category', get_defined_vars());;
						break;

					case 'ptag':
						$seeking_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_seeking_type_ptag', 'Tag', get_defined_vars());;
						break;

					case 'file':
						$seeking_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_seeking_type_file', 'File', get_defined_vars());;
						break;

					case 'ruri':
						$seeking_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_seeking_type_ruri', 'URI', get_defined_vars());;
						break;
				}
				$content = str_ireplace('%%SEEKING_TYPE%%', esc_html($seeking_type_tag), $content);
			}
			# ---------------------------------------------------------------------------------------------------

			if(!empty($_g['_s2member_seeking']['page']))
				$content = str_ireplace('%%SEEKING_PAGE_ID%%', esc_html($_g['_s2member_seeking']['page']), $content);

			else if(!empty($_g['_s2member_seeking']['post']))
				$content = str_ireplace('%%SEEKING_POST_ID%%', esc_html($_g['_s2member_seeking']['post']), $content);

			else if(!empty($_g['_s2member_seeking']['catg']))
				$content = str_ireplace('%%SEEKING_CAT_ID%%', esc_html($_g['_s2member_seeking']['catg']), $content);

			else if(!empty($_g['_s2member_seeking']['ptag']))
				$content = str_ireplace('%%SEEKING_TAG_ID%%', esc_html($_g['_s2member_seeking']['ptag']), $content);

			else if(!empty($_g['_s2member_seeking']['file']))
				$content = str_ireplace('%%SEEKING_FILE%%', esc_html($_g['_s2member_seeking']['file']), $content);

			else if(!empty($_g['_s2member_seeking']['ruri']) /* Full URI they were trying to access. */)
				$content = str_ireplace('%%SEEKING_RURI%%', esc_html(base64_decode($_g['_s2member_seeking']['ruri'])), $content);

			# ---------------------------------------------------------------------------------------------------

			if(!empty($_g['_s2member_seeking']['_uri']) /* Full URI they were trying to access. */)
				$content = str_ireplace('%%SEEKING_URI%%', esc_html(home_url(base64_decode($_g['_s2member_seeking']['_uri']))), $content);

			# ---------------------------------------------------------------------------------------------------

			if(!empty($_g['_s2member_req']['type']) /* One of: level|ccap|sp */)
			{
				$required_type_tag = ''; // Initialize.

				// Let's give the replacement tags a name that's useful for building messages
				switch(strtolower($_g['_s2member_req']['type']))
				{
					case 'level':
						$required_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_required_type_level', 'Level', get_defined_vars());
						break;

					case 'ccap':
						$required_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_required_type_ccap', 'Capability', get_defined_vars());;
						break;

					case 'sp':
						$required_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_required_type_sp', 'Specific Post/Page', get_defined_vars());;
						break;
				}
				$content = str_ireplace('%%REQUIRED_TYPE%%', esc_html($required_type_tag), $content);
			}
			# ---------------------------------------------------------------------------------------------------

			if(isset ($_g['_s2member_req']['level']))
			{
				$content = str_ireplace('%%REQUIRED_LEVEL%%', esc_html($_g['_s2member_req']['level']), $content);
				if(!empty($GLOBALS['WS_PLUGIN__']['s2member']['o']['level'.$_g['_s2member_req']['level'].'_label']))
					$content = str_ireplace('%%REQUIRED_LEVEL_LABEL%%', esc_html($GLOBALS['WS_PLUGIN__']['s2member']['o']['level'.$_g['_s2member_req']['level'].'_label']), $content);
			}
			else if(!empty($_g['_s2member_req']['ccap']))
			{
				$content = str_ireplace('%%REQUIRED_CCAP%%', esc_html($_g['_s2member_req']['ccap']), $content);
			}
			else if(!empty($_g['_s2member_req']['sp']))
			{
				$content = str_ireplace('%%REQUIRED_SP%%', esc_html($_g['_s2member_req']['sp']), $content);
			}
			# ---------------------------------------------------------------------------------------------------

			if(!empty($_g['_s2member_res']['type']) /* One of: post|page|catg|ptag|file|ruri|ccap|sp|sys */)
			{
				$restriction_type_tag = ''; // Initialize.

				// Let's give the replacement tags a name that's useful for building messages
				switch(strtolower($_g['_s2member_seeking']['type']))
				{
					case 'page':
						$restriction_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_restriction_type_page', 'Page', get_defined_vars());
						break;

					case 'post':
						$restriction_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_restriction_type_post', 'Post', get_defined_vars());;
						break;

					case 'catg':
						$restriction_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_restriction_type_catg', 'Category', get_defined_vars());;
						break;

					case 'ptag':
						$restriction_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_restriction_type_ptag', 'Tag', get_defined_vars());;
						break;

					case 'file':
						$restriction_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_restriction_type_file', 'File', get_defined_vars());;
						break;

					case 'ruri':
						$restriction_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_restriction_type_ruri', 'URI', get_defined_vars());;
						break;

					case 'ccap':
						$restriction_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_restriction_type_ccap', 'Custom Capability', get_defined_vars());;
						break;

					case 'sp':
						$restriction_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_restriction_type_sp', 'Specific Post/Page', get_defined_vars());;
						break;

					case 'sys':
						$restriction_type_tag = apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_restriction_type_sys', 'Systematic', get_defined_vars());;
						break;
				}
				$content = str_ireplace('%%RESTRICTION_TYPE%%', esc_html($restriction_type_tag), $content);
			}
			# ---------------------------------------------------------------------------------------------------

			if(!empty($_g['_s2member_seeking']['type']) && $_g['_s2member_seeking']['type'] == 'post')
			{
				$content = str_ireplace(array('%%POST_TITLE%%', '%%PAGE_TITLE%%'), get_the_title((integer)$_g['_s2member_seeking']['post']), $content);
				$content = str_ireplace('%%POST_EXCERPT%%', c_ws_plugin__s2member_pro_sc_mop_vars_notice_in::get_excerpt((integer)$_g['_s2member_seeking']['post']), $content);
			}
			# ---------------------------------------------------------------------------------------------------

			if(!empty($_g['_s2member_seeking']['type']) && $_g['_s2member_seeking']['type'] == 'page')
				$content = str_ireplace(array('%%POST_TITLE%%', '%%PAGE_TITLE%%'), get_the_title((integer)$_g['_s2member_seeking']['page']), $content);

			# ---------------------------------------------------------------------------------------------------

			return apply_filters('c_ws_plugin__s2member_pro_sc_mop_vars_notice_content', do_shortcode($content), get_defined_vars());
		}

		private static function get_excerpt($post_id)
		{
			if(!($post = get_post($post_id)))
				return ''; // Not possible.

			if($post->post_excerpt) // A custom excerpt defined by the site owner.
				return apply_filters('the_excerpt', apply_filters('get_the_excerpt', $post->post_excerpt));

			$text = trim(strip_tags(apply_filters('the_content', $post->post_content)));

			// See wp_trim_excerpt() in wp-includes/formatting.php
			$num_words = apply_filters('excerpt_length', 55);
			$more      = apply_filters('excerpt_more', ' '.'[&hellip;]');

			// See wp_trim_words() in wp-includes/formatting.php
			$words_array = preg_split('/['."\n\r\t".' ]+/', $text, $num_words + 1, PREG_SPLIT_NO_EMPTY);
			$sep         = ' ';

			if(count($words_array) > $num_words)
			{
				array_pop($words_array);
				$text = implode($sep, $words_array);
				$text = $text.$more;
			}
			else
			{
				$text = implode($sep, $words_array);
			}
			return $text;
		}
	}
}