<?php
/*
Plugin Name: Facebook stream
Plugin URI: http://wp-resources.com/
Description: Facebook stream will show your facebook page or group wall in fully responsive format on your website. Plugin is created to show facebook feed in pinterest style (responsive boxes)
Version: 1.6
Author: Ivan M
*/

/*
 *  include style and javascript
 */

// require class
require_once 'includes/cache.class.inc';
require_once 'includes/FacebookStream.class.inc';


function facebook_stream_scripts_with_jquery_and_css()
{
    wp_register_script( 'facebook-stream-custom-script', plugins_url( '/js/facebook-stream.js', __FILE__ ), array( 'jquery' ) );
    wp_register_script( 'facebook-stream-custom-script-colorbox', plugins_url( '/js/jquery.colorbox.js', __FILE__ ), array( 'jquery' ) );
    wp_register_style( 'facebook-stream-custom-style', plugins_url('/css/facebook_stream.css', __FILE__) );
    wp_register_style( 'facebook-stream-custom-style-colorbox', plugins_url('/css/colorbox.css', __FILE__) );

    wp_enqueue_script( 'facebook-stream-custom-script' );
    wp_enqueue_script( 'facebook-stream-custom-script-colorbox' );
    wp_enqueue_style( 'facebook-stream-custom-style' );
    wp_enqueue_style( 'facebook-stream-custom-style-colorbox' );
}
add_action( 'wp_enqueue_scripts', 'facebook_stream_scripts_with_jquery_and_css' );


/*
 *  plugin shortcode
 */
function facebook_stream_shortcode_function( $atts ) {
   
    // create new cache object, and delete all expired caches
    $cache = new Cache(array('path'=> plugin_dir_path( __FILE__ ).'cache/'));
    
    // create social stream object
    $SocialStream = new FacebookStream();
    
    // configuration
    $limit = (isset($atts['limit'])) ? (int)$atts['limit'] : 10;
    $hide_no_media = (isset($atts['hide_no_media'])) ? $atts['hide_no_media'] : "1";
    $cols = (isset($atts['cols'])) ? (int)$atts['cols'] : 4; 
    $padding = (isset($atts['padding'])) ? (int)$atts['padding'] : 10; 
    $bottom_margin = (isset($atts['margin_bottom'])) ? (int)$atts['margin_bottom'] : 35; 
    $theme = (isset($atts['theme'])) ? $atts['theme'] : "white";
    $unique_hash = md5(time().rand(1,100).rand(100,10000));
    $fb_page_id = (isset($atts['fb_page_id'])) ? $atts['fb_page_id'] : get_option('facebook_stream_fbPageID');
    $only_owners_posts = (isset($atts['only_owners_posts'])) ? $atts['only_owners_posts'] : "1";
    
    // generate unique cache key
    $unique_cache_key = md5($limit.$hide_no_media.$cols.$padding.$bottom_margin.$theme.$fb_page_id.$only_owners_posts);
    $cache->setCache('facebook_stream_cache');
    // check is cache expired, and delete it
    $cache->deleteIfExipired($unique_cache_key);
    
    // check is data cached
    if($cache->isCached($unique_cache_key)){
        $GetAvailablePosts = $cache->retrieve($unique_cache_key);
    } else {
        $GenerateToken = $SocialStream->GenerateAccessToken();
        $SocialStream->setFBPageID($fb_page_id);
        $GetAvailablePosts = $SocialStream->GetListOfFBPosts($only_owners_posts,$limit, $GenerateToken);
        $cache->store($unique_cache_key, $GetAvailablePosts, 360);
    }
        //var_dump($GetAvailablePosts);
    // show template
    ob_start();
    if($theme === "white"){
        include("templates/white_stream_tpl.php");
    } else if($theme === "black"){
        include("templates/black_stream_tpl.php");
    } else {
        include("templates/white_stream_tpl.php");
    }
    return ob_get_clean();
    
}
add_shortcode( 'facebook-stream', 'facebook_stream_shortcode_function' );


/*
 * Plugin admin page
 */
add_action( 'admin_menu', 'facebook_stream_plugin_menu' );
function facebook_stream_plugin_menu() {
    add_menu_page("Facebook Stream", "Facebook Stream", 'manage_options', "facebook-stream", "facebook_stream_main_function");
    if(filter_input(INPUT_GET,'page') === "facebook-stream"){
        wp_register_script( 'FacebookStreamDataTablePlugin', plugins_url('js/jquery.dataTables.min.js', __FILE__) );
        wp_enqueue_script( 'FacebookStreamDataTablePlugin' );
        
        wp_register_script( 'FacebookStreamShortcodeGenerator', plugins_url('js/shortcode_generator.js', __FILE__) );
        wp_enqueue_script( 'FacebookStreamShortcodeGenerator' );
        
        wp_register_style( 'FacebookStreamPluginStylesheet', plugins_url('css/style.css?v=1', __FILE__) );
        wp_enqueue_style( 'FacebookStreamPluginStylesheet' );
    }
}

function facebook_stream_main_function(){
    
    $app_id = get_option('facebook_stream_appID');
    $app_secret = get_option('facebook_stream_appSecret');
    $fb_page_id = get_option('facebook_stream_fbPageID');
    
    include("admin_templates/header_tpl.php");
    
    // save data
    if(filter_input(INPUT_GET, 'action') == "save_data"){

        $appID = filter_input(INPUT_POST, 'appID');
        $appSecret = filter_input(INPUT_POST,'appSecret');
        $FBPageID = filter_input(INPUT_POST,'FBpageID');

        update_option('facebook_stream_appID', $appID);
        update_option('facebook_stream_appSecret', $appSecret);
        update_option('facebook_stream_fbPageID', $FBPageID);


        ?><meta http-equiv="REFRESH" content="0;url=?page=facebook-stream"><?php
    } else {

        
        // check is app configured
        if($app_id && $app_secret && $fb_page_id){
            /*
            $SocialStream = new FacebookStream();
            $GenerateToken = $SocialStream->GenerateAccessToken();
            $GetAvailablePosts = $SocialStream->GetListOfFBPosts(50, $GenerateToken);
            echo "<pre>";
            print_r($GetAvailablePosts); 
            echo "</pre>";
            */
            
            $cache = new Cache(array('path'=> plugin_dir_path( __FILE__ ).'cache/'));
            $deletedFromCache = $cache->eraseExpired();
            if(!$deletedFromCache){
                $deletedFromCache = "0";
            }
            
            include("admin_templates/connected_tpl.php");

        }else {
            include("admin_templates/instruction_tpl.php");
        }
        
    }
}

// add ajaxurl to frontend header
add_action('wp_head', 'facebook_stream_free_frontend_header');
function facebook_stream_free_frontend_header() {
    ?>
    <script type="text/javascript">
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    </script>            
    <?php
}