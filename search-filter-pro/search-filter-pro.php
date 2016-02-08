<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2014 Designs & Code
 * 
 * @wordpress-plugin
 * Plugin Name:       Search & Filter Pro
 * Plugin URI:        http://www.designsandcode.com/wordpress-plugins/search-filter-pro/
 * Description:       Search & Filtering for posts, products and custom posts. Allow your users to Search & Filter by categories, tags, taxonomies, custom fields, post meta, post dates, post types and authors.
 * Version:           1.4.3
 * Author:            Designs & Code
 * Author URI:        http://www.designsandcode.com/
 * Text Domain:       search-filter
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'SEARCH_FILTER_PRO_BASE_PATH' ) ) {
	
	define('SEARCH_FILTER_PRO_BASE_PATH', __FILE__);
}

if (!function_exists('array_replace'))
{
	function array_replace()
	{ 
		$array=array();    
		$n=func_num_args(); 
		while ($n-- >0)
		{ 
			$array+=func_get_arg($n); 
		}
		return $array; 
	}
}
if (!function_exists('array_replace_recursive'))
{
  function array_replace_recursive($array, $array1)
  {
    function recurse($array, $array1)
    {
      foreach ($array1 as $key => $value)
      {
        // create new key in $array, if it is empty or not an array
        if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key])))
        {
          $array[$key] = array();
        }

        // overwrite the value in the base array
        if (is_array($value))
        {
          $value = recurse($array[$key], $value);
        }
        $array[$key] = $value;
      }
      return $array;
    }

    // handle the arguments, merge one by one
    $args = func_get_args();
    $array = $args[0];
    if (!is_array($array))
    {
      return $array;
    }
    for ($i = 1; $i < count($args); $i++)
    {
      if (is_array($args[$i]))
      {
        $array = recurse($array, $args[$i]);
      }
    }
    return $array;
  }
}
/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-search-filter.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Search_Filter', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Search_Filter', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Search_Filter', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
//if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-search-filter-admin.php' );
	add_action( 'plugins_loaded', array( 'Search_Filter_Admin', 'get_instance' ) );

}

if ( ! class_exists( 'Search_Filter_Register_Widget' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-search-filter-register-widget.php' );
}
