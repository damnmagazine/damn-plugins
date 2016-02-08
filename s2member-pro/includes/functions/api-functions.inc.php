<?php
/**
 * Core API Functions *(for site owners)*.
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
 * @package s2Member\API_Functions
 * @since 1.0
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit ('Do not access this file directly.');

/**
 * Allows for the inclusion of the Pro Login Widget directly into a theme/plugin file.
 *
 * This function will return the HTML output from the widget function call.
 *   Example usage: ``<!php echo s2member_pro_login_widget(); !>``
 *
 * The ``$options`` parameter (array) is completely optional *(i.e., NOT required)*.
 * It can be passed in as an array of options; overriding some or all of these defaults:
 *
 *   o ``'title' => 'Membership Login'``
 *   Title when NOT logged in, or leave this blank if you'd prefer not to show a title.
 *
 *   o ``'signup_url' => '%%automatic%%'``
 *   Full Signup URL, or use `%%automatic%%` for the Membership Options Page. If you leave this blank, it will not be shown.
 *
 *   o ``'login_redirect' => ''``
 *   Empty ( i.e., `''` ) = Login Welcome Page, `%%previous%%` = Previous Page, `%%home%%` = Home Page, or use a full URL of your own.
 *
 *   o ``'logged_out_code' => ''``
 *   HTML/PHP code to display when logged out. May also contain WP Shortcodes if you like.
 *
 *   o ``'profile_title' => 'My Profile Summary'``
 *   Title when a User is logged in. Or you can leave this blank if you'd prefer not to show a title.
 *
 *   o ``'display_gravatar' => '1'``
 *   Display Gravatar image when logged in? `1` = yes, `0` = no. Gravatars are based on email address.
 *
 *   o ``'link_gravatar' => '1'``
 *   Link Gravatar image to Gravatar.com? `1` = yes, `0` = no. Allows Users to setup a Gravatar.
 *
 *   o ``'display_name' => '1'``
 *   Display the current User's WordPress 'Display Name' when logged in? `1` = yes, `0` = no.
 *
 *   o ``'logged_in_code' => ''``
 *   HTML/PHP code to display when logged in. May also contain WP Shortcodes if you like.
 *
 *   o ``'logout_redirect' => '%%home%%'``
 *   Empty ( i.e., `''` ) = Login Screen, `%%previous%%` = Previous Page, `%%home%%` = Home Page, or use a full URL of your own.
 *
 *   o ``'my_account_url' => '%%automatic%%'``
 *   Full URL of your own, or use `%%automatic%%` for the Login Welcome Page. Leave empty to not show this at all.
 *
 *   o ``'my_profile_url' => '%%automatic%%'``
 *   Full URL of your own, or use `%%automatic%%` for a JavaScript popup. Leave empty to not show this at all.
 *
 * The ``$args`` parameter (array) is also completely optional *(i.e., NOT required)*.
 * It can be passed in as an array of options: overriding some or all of these defaults:
 *
 *   o ``'before_widget' => ''``
 *   HTML code to display before the widget.
 *
 *   o ``'before_title' => '<h3>'``
 *   HTML code to display before the title.
 *
 *   o ``'after_title' => '</h3>'``
 *   HTML code to display after the title.
 *
 *   o ``'after_widget' => ''``
 *   HTML code to display after the widget.
 *
 * @package s2Member\API_Functions
 * @since 1.5
 *
 * @param array $options Optional. See function description for details.
 * @param array $args Optional. See function description for details.
 *
 * @return string The Pro Login Widget, HTML markup.
 */
if(!function_exists('s2member_pro_login_widget'))
{
	function s2member_pro_login_widget($options = array(), $args = array())
	{
		ob_start(); // Begin output buffering.

		$options = (array)$options; // Force array.
		$args    = array_merge(array('before_widget' => '', 'before_title' => '<h3>', 'after_title' => '</h3>', 'after_widget' => ''), (array)$args);

		c_ws_plugin__s2member_pro_login_widget::___static_widget___($args, $options);

		return ob_get_clean();
	}
}
