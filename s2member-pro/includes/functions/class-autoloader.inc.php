<?php
/**
 * s2Member Pro class autoloader.
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
 * @since 1.5
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit('Do not access this file directly.');
/*
The __autoload function for s2Member Pro classes.
*/
if(!function_exists('ws_plugin__s2member_pro_classes'))
{
	/**
	 * s2Member Pro class autoloader.
	 *
	 * The __autoload function for s2Member Pro classes.
	 * This highly optimizes s2Member Pro. Giving it a much smaller footprint.
	 * See: {@link http://www.php.net/manual/en/function.spl-autoload-register.php}
	 *
	 * @package s2Member
	 * @since 1.5
	 *
	 * @param string $class The class that needs to be loaded. Passed in by PHP itself.
	 */
	function ws_plugin__s2member_pro_classes($class = '')
	{
		static $c; //  Holds the classes directory location (location is optimized with a static var).
		static $c_class_dirs; //  All possible dir & sub-directory locations (with a static var).

		if(strpos($class, 'c_ws_plugin__s2member_pro_') === 0) //  Make sure this is an s2Member Pro class.
		{
			$c            = (!isset ($c)) ? dirname(dirname(__FILE__)).'/classes' : $c; //  Configures location of classes.
			$c_class_dirs = (!isset ($c_class_dirs)) ? array_merge(array($c), _ws_plugin__s2member_pro_classes_scan_dirs_r($c)) : $c_class_dirs;

			$class = str_replace('_', '-', str_replace('c_ws_plugin__s2member_pro_', '', $class));

			foreach($c_class_dirs as $class_dir) //  Start looking for the class.
				if($class_dir === $c || strpos($class, basename($class_dir)) === 0)
					if(file_exists($class_dir.'/'.$class.'.inc.php'))
					{
						include_once $class_dir.'/'.$class.'.inc.php';
						break; //  Now stop looking.
					}
		}
	}

	/**
	 * Scans recursively for class sub-directories.
	 *
	 * Used by the s2Member Pro autoloader.
	 *
	 * @package s2Member
	 * @since 1.5
	 *
	 * @param string $starting_dir The directory to start scanning from.
	 *
	 * @return string[] An array of class directories.
	 */
	function _ws_plugin__s2member_pro_classes_scan_dirs_r($starting_dir = '')
	{
		$dirs = array(); //  Initialize dirs array.

		foreach(func_get_args() as $starting_dir)
			if(is_dir($starting_dir)) //  Does this directory exist?
				foreach(scandir($starting_dir) as $dir) //  Scan this directory.
					if($dir !== '.' && $dir !== '..' && is_dir($dir = $starting_dir.'/'.$dir))
						$dirs = array_merge($dirs, array($dir), _ws_plugin__s2member_pro_classes_scan_dirs_r($dir));

		return $dirs; //  Return array of all directories.
	}

	spl_autoload_register('ws_plugin__s2member_pro_classes'); //  Register __autoload.
}