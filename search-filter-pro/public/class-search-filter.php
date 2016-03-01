<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2014 Designs & Code
 */
global $sf_form_data;

class Search_Filter {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.4.3';
	
	/**
	 * @TODO - Rename "plugin-name" to the name your your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'search-filter';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct()
	{		
		global $sf_form_data;
		$sf_form_data = new Search_Filter_Form_Data();
		
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		add_action( 'wp_ajax_get_counts', array($this, 'get_counts') );
		add_action( 'wp_ajax_nopriv_get_counts', array($this, 'get_counts') );
		
		add_action( 'wp_ajax_get_results', array($this, 'get_results') );
		add_action( 'wp_ajax_nopriv_get_results', array($this, 'get_results') );
		
		add_action( 'init', array( $this, 'create_custom_post_types' ) );
		
		if(!is_admin())
		{
			//add_action( 'init', array( $this, 'set_search_form_vars' ) );
			
			$this->display_shortcode = new Search_Filter_Display_Shortcode($this->plugin_slug);
			$this->setup_query = new Search_Filter_Setup_Query($this->plugin_slug);
			
			
			// Check the header to see if the form has been submitted
			//add_action( 'template_redirect', array( $this, 'check_posted' ) );
			//add_action( 'registered_taxonomy', array( $this, 'check_posted' ) );
			add_action( 'init', array( $this, 'check_posted' ), 20 );
			
			
			//load SF Template - set high priority to override other plugins...
			add_action('template_include', array($this, 'handle_template'), 100, 3);
			
			//add_filter('the_title', array($this, 'update_page_title'), 10, 2);
		}
		
		
		add_action('widgets_init', array($this, 'init_widget'));
		
		add_filter('rewrite_rules_array', array($this, 'sf_rewrite_rules'));
		
	}
	
	
	function get_results()
	{
		//handle posts from ajax request and redirect
		$check_posts_class = new Search_Filter_Handle_Posts($this->plugin_slug);
		
		//if no redirect, get results based on URL
		$this->get_results_obj = new Search_Filter_Get_Results($this->plugin_slug);
		
		echo $this->get_results_obj->the_results(esc_attr($_GET['sfid']));
		exit;
	}
	
	function sf_rewrite_rules( $rules )
	{
		global $sf_form_data;
		$newrules = array();
		
		$args = array(
			 'posts_per_page' => 200,
			 'post_type' => $this->plugin_slug."-widget",
			 'post_status' => 'publish'
		);
		
		$all_search_forms = get_posts( $args );
		foreach ($all_search_forms as $search_form)
		{
			$settings = get_post_meta( $search_form->ID , '_search-filter-settings' , true );
			
			if(isset($settings['page_slug']))
			{
				if($settings['page_slug']!="")
				{
					$base_id = $search_form->ID;
					
					//$newrules[$settings['page_slug'].'/page/([0-9]+)/([0-9]+)$'] = 'index.php?&sfid='.$base_id.'&paged=$matches[2]&lang=$matches[1]'; //pagination & lang rule
					$newrules[$settings['page_slug'].'/page/([0-9]+)$'] = 'index.php?&sfid='.$base_id.'&paged=$matches[1]'; //pagination rule
					$newrules[$settings['page_slug'].'/page/([0-9]+)$'] = 'index.php?&sfid='.$base_id.'&paged=$matches[1]'; //pagination rule
					$newrules[$settings['page_slug'].'$'] = 'index.php?&sfid='.$base_id; //regular plain slug
					
				}
			}			
		}
		
		return $newrules + $rules;
	}
	
	
	function check_posted()
	{
		$check_posts_class = new Search_Filter_Handle_Posts($this->plugin_slug);
	}
	
	function init_widget()
	{
		register_widget( 'Search_Filter_Register_Widget' );
	}
	
	public function handle_template($original_template)
	{
		global $sf_form_data;
		
		if($sf_form_data->is_valid_form())
		{//then we are doing a search
			
			$template_file_name = $sf_form_data->get_template_name();
			
			if($template_file_name)
			{
				$located = locate_template( $template_file_name );
				
				if ( !empty( $located ) )
				{
					// 'home.php' found in Theme, do something
					$this->display_shortcode->set_is_template(true);
					return ($located);
				}
			}		
		}
		
		return $original_template;
	}
	
	function update_page_title($data)
	{
	
		global $post;
		global $sf_form_data;
		
		if($sf_form_data->is_valid_form())
		{//then we are doing a search
			
			// where $data would be string(#) "current title"
			// Example:
			// (you would want to change $post->ID to however you are getting the book order #,
			// but you can see how it works this way with global $post;)
			
			return 'Book Order #' . $post->ID.' ';
			
			//$template_file_name = $sf_form_data->get_template_name();
		}
		else
		{
			return $data;
		}
	}
	
	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}
	
	

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style( $this->plugin_slug . '-chosen-styles', plugins_url( 'assets/css/chosen.min.css', __FILE__ ), array(), self::VERSION );
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/search-filter.min.css', __FILE__ ), array(), self::VERSION );
	}
	
	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		global $sf_form_data;
		
		//if($sf_form_data->is_valid_form())
		//{
			wp_register_script( $this->plugin_slug . '-plugin-build', plugins_url( 'assets/js/search-filter-build.js', __FILE__ ), array('jquery'), self::VERSION );
			wp_localize_script($this->plugin_slug . '-plugin-build', 'SF_LDATA', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'home_url' => (home_url('/')), 'sfid' => $sf_form_data->get_active_form_id() ));
			
			wp_register_script( $this->plugin_slug . '-chosen-script', plugins_url( 'assets/js/chosen.jquery.min.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		//}
	}
	
	function get_counts()
	{
		global $wpdb;
		
		//var_dump(($_POST));
		$taxterms = array();
		foreach ($_POST as $key => $val)
		{
			if (strpos($key, SF_TAX_PRE) === 0)
			{
				$taxonomy_name = sanitize_key(substr($key, strlen(SF_TAX_PRE)));
				
				foreach ($val as $tt)
				{
					if($tt!=0)
					{
						$term_id = intval($tt);
						$term_obj = get_term( $term_id, $taxonomy_name );
						$taxterms[$taxonomy_name][] = $term_obj->slug;
					}
				}
			}
			else if (strpos($key, SF_META_PRE) === 0)
			{
				$key = substr($key, strlen(SF_META_PRE));
				
				
			}
		}
		
		$rel_query_args = array();
		$rel_query_args['taxonomies'] = $taxterms;
		
		$term_relationships = new Search_Filter_Relationships($this->plugin_slug);
		//var_dump($rel_query_args);
		$term_relationships->init_relationships($rel_query_args);
		
		
		
		echo json_encode($term_relationships->get_count_table());
		exit;
		
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function create_custom_post_types() {
		// @TODO: Define your action hook callback here
		// Create ACF post type
		$labels = array(
		    'name'					=>	__( 'Search &amp; Filter', $this->plugin_slug ),
			'singular_name'			=>	__( 'Search Form', $this->plugin_slug ),
		    'add_new'				=>	__( 'Add New Search Form', $this->plugin_slug ),
		    'add_new_item'			=>	__( 'Add New Search Form', $this->plugin_slug ),
		    'edit_item'				=>	__( 'Edit Search Form', $this->plugin_slug ),
		    'new_item'				=>	__( 'New Search Form', $this->plugin_slug ),
		    'view_item'				=>	__( 'View Search Form', $this->plugin_slug ),
		    'search_items'			=>	__( 'Search \'Search Forms\'', $this->plugin_slug ),
		    'not_found'				=>	__( 'No Search Forms found', $this->plugin_slug ),
		    'not_found_in_trash'	=>	__( 'No Search Forms found in Trash', $this->plugin_slug ),
		);
		
		register_post_type($this->plugin_slug.'-widget' , array(
			'labels'			=> $labels,
			'public'			=> false,
			'show_ui'			=> true,
			'_builtin'			=> false,
			'capability_type'	=> 'page',
			'hierarchical'		=> true,
			'rewrite'			=> false,
			'supports'			=> array('title'),
			'show_in_menu'	=> false,
			/*'has_archive' => true,*/
		));
	}
}


if ( ! class_exists( 'Search_Filter_Display_Shortcode' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-search-filter-display-shortcode.php' );
}

if ( ! class_exists( 'Search_Filter_Handle_Posts' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-search-filter-handle-posts.php' );
}

if ( ! class_exists( 'Search_Filter_Setup_Query' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-search-filter-setup-query.php' );
}
if ( ! class_exists( 'Search_Filter_Get_Results' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-search-filter-get-results.php' );
}

if ( ! class_exists( 'Search_Filter_Form_Data' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-search-filter-form-data.php' );
}

if ( ! class_exists( 'Search_Filter_Relationships' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-search-filter-relationships.php' );
}

