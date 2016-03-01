<?php
/**
 * The main plugin file.
 *
 * This file loads the plugin after checking
 * PHP, WordPress and other compatibility requirements.
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
 * @package s2Member
 * @since 1.0
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');
/**
 * The installed version of s2Member Pro.
 *
 * @package s2Member
 * @since 1.0
 *
 * @var string
 */
if(!defined('WS_PLUGIN__S2MEMBER_PRO_VERSION'))
	define('WS_PLUGIN__S2MEMBER_PRO_VERSION', '150827' /* !#distro-version#! */);
/**
 * Minimum PHP version required to run s2Member Pro.
 *
 * @package s2Member
 * @since 1.0
 *
 * @var string
 */
if(!defined('WS_PLUGIN__S2MEMBER_PRO_MIN_PHP_VERSION'))
	define('WS_PLUGIN__S2MEMBER_PRO_MIN_PHP_VERSION', '5.2' /* !#php-requires-at-least-version#! */);
/**
 * Minimum WordPress version required to run s2Member Pro.
 *
 * @package s2Member
 * @since 1.0
 *
 * @var string
 */
if(!defined('WS_PLUGIN__S2MEMBER_PRO_MIN_WP_VERSION'))
	define('WS_PLUGIN__S2MEMBER_PRO_MIN_WP_VERSION', '4.2' /* !#wp-requires-at-least-version#! */);
/**
 * Minimum Framework version required by s2Member Pro.
 *
 * @package s2Member
 * @since 1.0
 *
 * @var string
 */
if(!defined('WS_PLUGIN__S2MEMBER_PRO_MIN_FRAMEWORK_VERSION'))
	define('WS_PLUGIN__S2MEMBER_PRO_MIN_FRAMEWORK_VERSION', '150827' /* !#distro-version#! */);
/*
Several compatibility checks.
If all pass, load the s2Member plugin.
*/
if(version_compare(PHP_VERSION, WS_PLUGIN__S2MEMBER_PRO_MIN_PHP_VERSION, '>=') && version_compare(get_bloginfo('version'), WS_PLUGIN__S2MEMBER_PRO_MIN_WP_VERSION, '>=') && defined('WS_PLUGIN__S2MEMBER_VERSION') && defined('WS_PLUGIN__S2MEMBER_MIN_PRO_VERSION') && version_compare(WS_PLUGIN__S2MEMBER_VERSION, WS_PLUGIN__S2MEMBER_PRO_MIN_FRAMEWORK_VERSION, '>=') && version_compare(WS_PLUGIN__S2MEMBER_PRO_VERSION, WS_PLUGIN__S2MEMBER_MIN_PRO_VERSION, '>=') && !isset($GLOBALS['WS_PLUGIN__']['s2member_pro']))
{
	$GLOBALS['WS_PLUGIN__']['s2member_pro']['l'] = __FILE__;
	/*
	Hook before loaded.
	*/
	do_action('ws_plugin__s2member_pro_before_loaded');
	/*
	System configuraton.
	*/
	include_once dirname(__FILE__).'/includes/syscon.inc.php';
	/*
	Hooks and Filters.
	*/
	include_once dirname(__FILE__).'/includes/hooks.inc.php';
	/*
	Hook after system config & Hooks are loaded.
	*/
	do_action('ws_plugin__s2member_pro_config_hooks_loaded');
	/*
	Function includes.
	*/
	include_once dirname(__FILE__).'/includes/funcs.inc.php';
	/*
	Include Shortcodes.
	*/
	include_once dirname(__FILE__).'/includes/codes.inc.php';
	/*
	Hooks after loaded.
	*/
	do_action('ws_plugin__s2member_pro_loaded');
	do_action('ws_plugin__s2member_pro_after_loaded');
}
/*
Else NOT compatible. Do we need admin compatibility errors now?
*/
else if(is_admin()) //  Admin compatibility errors.
{
	if(!version_compare(PHP_VERSION, WS_PLUGIN__S2MEMBER_PRO_MIN_PHP_VERSION, '>='))
	{
		add_action('all_admin_notices', create_function('', 'echo \'<div class="error fade"><p>You need PHP v\'.WS_PLUGIN__S2MEMBER_PRO_MIN_PHP_VERSION.\'+ to use the s2Member Pro Add-on.</p></div>\';'));
	}
	else if(!version_compare(get_bloginfo('version'), WS_PLUGIN__S2MEMBER_PRO_MIN_WP_VERSION, '>='))
	{
		add_action('all_admin_notices', create_function('', 'echo \'<div class="error fade"><p>You need WordPress v\'.WS_PLUGIN__S2MEMBER_PRO_MIN_WP_VERSION.\'+ to use the s2Member Pro Add-on.</p></div>\';'));
	}
	else if(!defined('WS_PLUGIN__S2MEMBER_VERSION') || !defined('WS_PLUGIN__S2MEMBER_MIN_PRO_VERSION') || !version_compare(WS_PLUGIN__S2MEMBER_VERSION, WS_PLUGIN__S2MEMBER_PRO_MIN_FRAMEWORK_VERSION, '>='))
	{
		add_action('all_admin_notices', create_function('', 'echo \'<div class="error fade"><p>In order to load the s2Member Pro Add-on, you need the <a href="\'.c_ws_plugin__s2member_readmes::parse_readme_value(\'Plugin URI\').\'" target="_blank">s2Member Framework</a>, v\'.WS_PLUGIN__S2MEMBER_PRO_MIN_FRAMEWORK_VERSION.\'+. It\\\'s free.</p></div>\';'));
	}
	else if(!version_compare(WS_PLUGIN__S2MEMBER_PRO_VERSION, WS_PLUGIN__S2MEMBER_MIN_PRO_VERSION, '>=') && file_exists(dirname(__FILE__).'/includes/classes/upgrader.inc.php'))
	{
		include_once dirname(__FILE__).'/includes/classes/upgrader.inc.php'; // Include upgrader class. s2Member Pro autoload functionality will NOT be available in this scenario. Using ``include_once()``.
		add_action('admin_init', 'c_ws_plugin__s2member_pro_upgrader::upgrade').add_action('all_admin_notices', create_function('', 'echo c_ws_plugin__s2member_pro_upgrader::wizard();'));
	}
}
