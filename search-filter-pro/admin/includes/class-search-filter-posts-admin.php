<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2014 Designs & Code
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package Plugin_Name_Admin
 * @author  Your Name <email@example.com>
 */
class Search_Filter_Posts_Admin {
	
	private $post_meta_keys = array();
	
	
	public function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		
		$plugin = Search_Filter::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load widgets admin style she	et and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		
		add_action( 'add_meta_boxes', array( $this, 'add_posts_meta_boxes' ) );
		
		/* Save post meta on the 'save_post' hook. */
		add_action( 'save_post', array( $this, 'save_search_form_meta'), 10, 2  );
		
		/* AJAX */
		/*
		add_action( 'wp_ajax_meta_prefs_set', array($this, 'meta_prefs_set') ); //if logged in
		add_action( 'wp_ajax_nopriv_meta_prefs_set', array($this, 'meta_prefs_set') ); //if not logged in
		*/
		
	}
	
	public function enqueue_admin_styles()
	{
		//wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css' ), array(), Search_Filter::VERSION );
	}
	public function enqueue_admin_scripts()
	{
		wp_enqueue_script( $this->plugin_slug . '-admin-posts-script', plugins_url( '/assets/js/admin-posts.js',dirname(__FILE__) ), array( 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery' ), Search_Filter::VERSION );
		wp_enqueue_script( $this->plugin_slug . '-admin-posts-qtip-script', plugins_url( '/assets/js/jquery.qtip.min.js',dirname(__FILE__) ), array( 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery' ), Search_Filter::VERSION );
	}
	
	function save_search_form_meta($post_id, $post)
	{
		//init post data validatin class
		$this->post_data_validation = new Search_Filter_Post_Data_Validation();
		
		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST[$this->plugin_slug.'_nonce'] ) || !wp_verify_nonce( $_POST[$this->plugin_slug.'_nonce'], 'search_form_nonce' ) )
			return $post_id;
			
		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
			return $post_id;
		
		$this->process_search_form_post($post_id);
		$this->process_settings_post($post_id);
	
	}
	
	private function process_search_form_post($post_id)
	{
		
		/* Get the posted data and sanitize it for use as an HTML class. */
		$new_meta_value = ( isset( $_POST['smashing-post-class'] ) ? sanitize_html_class( $_POST['smashing-post-class'] ) : '' );
		$new_meta_value = "";
		
		/* Start processing all the fields of the form */
		if(isset($_POST['widget-field']))
		{
			
			$clean_widgets = array();
			$wc = 0;
			
			if(is_array($_POST['widget-field']))
			{
				foreach($_POST['widget-field'] as $widget)
				{
					if(isset($widget['type']))
					{
						$clean_widgets[$wc] = $this->post_data_validation->get_clean_widget_data($widget);
					}
					
					$wc++;
					
				}
			}
			
			$new_meta_value = $clean_widgets;
		}
		
		/* Get the meta key. */
		$meta_key = '_search-filter-fields';

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If a new meta value was added and there was no previous value, add it. */
		if ( $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );
	}
	
	private function process_settings_post($post_id)
	{
		
		$settings = array();
		$settings['post_types'] = array();
		$settings['post_status'] = array();
		$settings['use_template_manual_toggle'] = "";
		$settings['enable_auto_count'] = "";
		$settings['template_name_manual'] = "";
		$settings['page_slug'] = "";
		$settings['use_ajax_toggle'] = "";
		$settings['maintain_state'] = "";
		$settings['use_relevanssi'] = "";
		$settings['force_is_search'] = "";
		$settings['results_url'] = "";
		$settings['ajax_target'] = "";
		$settings['ajax_links_selector'] = "";
		$settings['auto_submit'] = "";
		//$settings['use_results_shortcode'] = "";
		$settings['display_results_as'] = "";
		$settings['update_ajax_url'] = "";
		$settings['results_per_page'] = "";
		$settings['exclude_post_ids'] = "";
		$settings['taxonomy_relation'] = "";
		$settings['default_sort_by'] = "";
		$settings['default_sort_dir'] = "";
		$settings['settings_post_meta'] = "";
		
			
		$settings = array();
		
		if(isset($_POST['use_template_manual_toggle']))
		{
			$settings['use_template_manual_toggle'] = $this->post_data_validation->sanitize_checkbox($_POST['use_template_manual_toggle']);
		}
		else
		{
			$settings['use_template_manual_toggle'] = 0;
		}
		if(isset($_POST['enable_auto_count']))
		{
			$settings['enable_auto_count'] = $this->post_data_validation->sanitize_checkbox($_POST['enable_auto_count']);
		}
		else
		{
			$settings['enable_auto_count'] = 0;
		}
		
		
		if(isset($_POST['template_name_manual']))
		{
			$settings['template_name_manual'] = $this->sanitize_template_path($_POST['template_name_manual']);
		}
		
		if(isset($_POST['page_slug']))
		{
			$settings['page_slug'] = sanitize_key($_POST['page_slug']);
		}
		
		if(isset($_POST['settings_post_types']))
		{
			if(is_array($_POST['settings_post_types']))
			{
				foreach($_POST['settings_post_types'] as $key => $val)
				{
					$settings['post_types'][$key] = $this->post_data_validation->sanitize_checkbox($val);
				}
			}
		}
		
		if(isset($_POST['settings_post_status']))
		{
			if(is_array($_POST['settings_post_status']))
			{
				foreach($_POST['settings_post_status'] as $key => $val)
				{
					$settings['post_status'][$key] = $this->post_data_validation->sanitize_checkbox($val);
				}
			}
		}
		
		if(isset($_POST['settings_post_meta']))
		{
			
			if(is_array($_POST['settings_post_meta']))
			{
				$meta_count = 0;
				
				if(isset($_POST['settings_post_meta']["{0}"]))
				{
					unset($_POST['settings_post_meta']["{0}"]);
				}
				
				foreach($_POST['settings_post_meta'] as $post_meta_setting)
				{
					
					foreach($post_meta_setting as $key => $val)
					{
						
						if($key=='meta_key')
						{
							$settings['settings_post_meta'][$meta_count][$key] = sanitize_text_field($val);
						}
						else if($key=='meta_compare')
						{
							$settings['settings_post_meta'][$meta_count][$key] = sanitize_text_field($val);
						}
						else if($key=='meta_value')
						{
							$settings['settings_post_meta'][$meta_count][$key] = sanitize_text_field($val);
						}
						else if($key=='meta_type')
						{
							$settings['settings_post_meta'][$meta_count][$key] = sanitize_text_field($val);
						}
						else if($key=='meta_date_value_day')
						{
							$settings['settings_post_meta'][$meta_count][$key] = sanitize_text_field($val);
						}
						else if($key=='meta_date_value_month')
						{
							$settings['settings_post_meta'][$meta_count][$key] = sanitize_text_field($val);
						}
						else if($key=='meta_date_value_year')
						{
							$settings['settings_post_meta'][$meta_count][$key] = sanitize_text_field($val);
						}
						else if($key=='meta_date_value_date')
						{
							$settings['settings_post_meta'][$meta_count][$key] = sanitize_text_field($val);
						}
						else if($key=='meta_date_value_timestamp')
						{
							$settings['settings_post_meta'][$meta_count][$key] = sanitize_text_field($val);
						}
						else if($key=='meta_date_value_current_date')
						{
							$settings['settings_post_meta'][$meta_count][$key] = $this->post_data_validation->sanitize_checkbox($val);
						}
						else if($key=='meta_date_value_current_timestamp')
						{
							$settings['settings_post_meta'][$meta_count][$key] = $this->post_data_validation->sanitize_checkbox($val);
						}
					}
					$meta_count++;
				}
			}
		}
		
		if(isset($_POST['use_ajax_toggle']))
		{
			$settings['use_ajax_toggle'] = $this->post_data_validation->sanitize_checkbox($_POST['use_ajax_toggle']);
		}
		
		if(isset($_POST['scroll_to_pos']))
		{
			$settings['scroll_to_pos'] = sanitize_key($_POST['scroll_to_pos']);
		}
		
		if(isset($_POST['custom_scroll_to']))
		{
			$settings['custom_scroll_to'] = sanitize_text_field($_POST['custom_scroll_to']);
		}
		if(isset($_POST['scroll_on_action']))
		{
			$settings['scroll_on_action'] = sanitize_text_field($_POST['scroll_on_action']);
		}
		
		if(isset($_POST['maintain_state']))
		{
			$settings['maintain_state'] = $this->post_data_validation->sanitize_checkbox($_POST['maintain_state']);
		}
		
		if(isset($_POST['use_relevanssi']))
		{
			$settings['use_relevanssi'] = $this->post_data_validation->sanitize_checkbox($_POST['use_relevanssi']);
		}
		
		if(isset($_POST['force_is_search']))
		{
			$settings['force_is_search'] = $this->post_data_validation->sanitize_checkbox($_POST['force_is_search']);
		}
		
		
		if(isset($_POST['auto_submit']))
		{
			
			$settings['auto_submit'] = $this->post_data_validation->sanitize_checkbox($_POST['auto_submit']);
		}
		/*if(isset($_POST['use_results_shortcode']))
		{
			$settings['use_results_shortcode'] = $this->post_data_validation->sanitize_checkbox($_POST['use_results_shortcode']);
		}
		else
		{
			$settings['use_results_shortcode'] = "";
		}*/
		if(isset($_POST['display_results_as']))
		{
			$settings['display_results_as'] = sanitize_key($_POST['display_results_as']);
		}
		else
		{
			$settings['display_results_as'] = "";
		}
		
		if(isset($_POST['update_ajax_url']))
		{
			$settings['update_ajax_url'] = $this->post_data_validation->sanitize_checkbox($_POST['update_ajax_url']);
		}
		else
		{
			$settings['update_ajax_url'] = "";
		}
		
		if(isset($_POST['ajax_target']))
		{
			$settings['ajax_target'] = sanitize_text_field($_POST['ajax_target']);
		}
		
		if(isset($_POST['results_url']))
		{
			$settings['results_url'] = sanitize_text_field($_POST['results_url']);
		}
		
		
		if(isset($_POST['ajax_links_selector']))
		{
			$settings['ajax_links_selector'] = sanitize_text_field($_POST['ajax_links_selector']);
		}
		
		if(isset($_POST['results_per_page']))
		{
			$settings['results_per_page'] = (int)$_POST['results_per_page'];
		}
		
		if(isset($_POST['exclude_post_ids']))
		{
			$settings['exclude_post_ids'] = $this->post_data_validation->clean_exclude_ids($_POST['exclude_post_ids']);
		}
		
		
		if(isset($_POST['taxonomy_relation']))
		{
			$settings['taxonomy_relation'] = sanitize_key($_POST['taxonomy_relation']);
		}
		
		
		if(isset($_POST['default_sort_by']))
		{
			$settings['default_sort_by'] = sanitize_text_field($_POST['default_sort_by']);
		}
		
		if(isset($_POST['default_sort_dir']))
		{
			$settings['default_sort_dir'] = sanitize_text_field($_POST['default_sort_dir']);
		}
		if(isset($_POST['default_meta_key']))
		{
			$settings['default_meta_key'] = sanitize_text_field($_POST['default_meta_key']);
		}
		
		if(isset($_POST['default_sort_type']))
		{
			$settings['default_sort_type'] = sanitize_text_field($_POST['default_sort_type']);
		}
		
		if(isset($_POST['settings_taxonomies']))
		{
			if(is_array($_POST['settings_taxonomies']))
			{
				$settings_taxonomies = array();
				
				foreach($_POST['settings_taxonomies'] as $key => $val)
				{
					$nval = array();
					$nval['include_exclude'] = sanitize_key($val['include_exclude']);
					$nval['ids'] = $this->post_data_validation->clean_exclude_ids($val['ids']);
					
					$settings_taxonomies[$key] = $nval;
				}
				
				$settings['taxonomies_settings'] = $settings_taxonomies;
			}
			
			
		}
		
		
		/* Get the meta key. */
		$meta_key = '_search-filter-settings';

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );
		
		if(isset($meta_value['page_slug']))
		{
			$old_page_slug = $meta_value['page_slug'];
		}
		
		/* If a new meta value was added and there was no previous value, add it. */
		if ( $settings && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $settings, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( $settings && $settings != $meta_value )
			update_post_meta( $post_id, $meta_key, $settings );

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( '' == $settings && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );
			
		/* check to see if the slug has been updated, if so flush the rewrite rules */
		if(isset($meta_value['page_slug']))
		{
			if($meta_value['page_slug']!=$settings['page_slug'])
			{
				flush_rewrite_rules();
			}
		}
		
	}
	
	function sanitize_template_path($template_path)
	{
		$located = locate_template( $template_path );
		if ( !empty( $located ) ) {
			return $template_path;
		}
		else
		{
			return "";
		}
	}
	
	function add_posts_meta_boxes()
	{
		$screens = array( 'search-filter-widget' );

		foreach ( $screens as $screen )
		{
			add_meta_box(
				'search-filter-shortcodes',
				__( 'Shortcodes', $this->plugin_slug ),
				array($this,'load_search_form_shortcode_metabox'),
				$screen,
				'side'
			);
			
			add_meta_box(
				'search-filter-settings',
				__( 'Beta', $this->plugin_slug ),
				array($this,'load_search_form_setup_metabox'),
				$screen,
				'side',
				'low'
			);
			
			
			add_meta_box(
				'search-filter-settings-box',
				__( 'Settings & Defaults', $this->plugin_slug ),
				array($this,'load_search_form_settings_metabox'),
				$screen,
				'advanced',
				'high'
			);
			
			add_meta_box(
				'search-filter-available-fields',
				__( 'Available Fields', $this->plugin_slug ),
				array($this,'load_post_available_fields_metabox'),
				$screen,
				'advanced',
				'high'
				
			);
			
			add_meta_box(
				'search-filter-search-form',
				__( 'Search Form UI', $this->plugin_slug ),
				array($this,'load_post_search_form_metabox'),
				$screen,
				'advanced',
				'low'
			);
			
		}
	}
	
	function load_search_form_setup_metabox($object, $box)
	{
		$settings = (get_post_meta( $object->ID, '_search-filter-settings', true ));
		
		$defaults = array(
			'post_types'					=> '',
			'use_template_manual_toggle'	=> '1',
			'enable_auto_count'				=> '',
			'template_name_manual'			=> 'search.php',
			'page_slug'						=> ''
		);
		
		if(is_array($settings))
		{
			$values = array_replace($defaults, $settings);
		}
		else
		{
			$values = $defaults;
		}
		
		include_once( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/admin-search-form-beta-metabox.php' );
	}
	function load_search_form_shortcode_metabox($object, $box)
	{
		$settings = (get_post_meta( $object->ID, '_search-filter-shortcode', true ));
		
		$defaults = array(
			'post_types'					=> '',
			'use_template_manual_toggle'	=> '1',
			'enable_auto_count'				=> '',
			'template_name_manual'			=> 'search.php',
			'page_slug'						=> ''
		);
		
		if(is_array($settings))
		{
			$values = array_replace($defaults, $settings);
		}
		else
		{
			$values = $defaults;
		}
		
		include_once( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/admin-search-form-shortcode-metabox.php' );
	}
	
	function load_search_form_settings_metabox($object, $box)
	{
		$settings = (get_post_meta( $object->ID, '_search-filter-settings', true ));
		
		$defaults = array(
			'category_ids'					=> '',
			'post_tag_ids'					=> '',
			
			
			'use_ajax_toggle'				=> '',
			'display_results_as'			=> 'shortcode',
			'scroll_to_pos'					=> '',
			'custom_scroll_to'				=> '',
			'scroll_on_action'				=> '',
			'maintain_state'				=> '',
			'use_relevanssi'				=> '',
			'force_is_search'				=> '',
			'ajax_target'					=> '#content',
			'results_url'					=> '',
			'ajax_links_selector'			=> '.pagination a',
			'auto_submit'					=> '',
			//'use_results_shortcode'			=> '',
			'update_ajax_url'				=> '',
			
			'post_types'					=> '',
			'use_template_manual_toggle'	=> '1',
			'enable_auto_count'				=> '',
			'template_name_manual'			=> 'search.php',
			'page_slug'						=> '',
			
			'results_per_page'				=> get_option('posts_per_page'),
			'exclude_post_ids'				=> '',
			'taxonomy_relation'				=> '',
			'default_sort_by'				=> '',
			'default_sort_dir'				=> '',
			'default_meta_key'				=> '',
			'default_sort_type'				=> '',
			'post_status' 					=> array(
												"publish" => "",
												"pending" => "",
												"draft" => "",
												"future" => "",
												"private" => "",
											),
			'settings_post_meta' 		=> array()
											
		);
		
		//set default for this based on other user selections - shortcode users will be used to this being disabled so do not force it
		//non shortcode users will be used to this feature, so enable by default
		if(!isset($settings['update_ajax_url']))
		{
			$defaults['update_ajax_url'] = 1;
		}
		
		if((isset($settings['use_ajax_toggle']))&&(isset($settings['use_results_shortcode'])))
		{
			if(($settings['use_ajax_toggle']==1)&&($settings['use_results_shortcode']==1))
			{
				$defaults['display_results_as'] = "shortcode";
			}
			else
			{
				$defaults['display_results_as'] = "archive";
			}
		}
		else
		{
			$defaults['display_results_as'] = "archive";
		}
		
		if(get_post_status($object->ID)=="auto-draft")
		{
			$defaults['use_ajax_toggle'] = 1;
			//$defaults['use_results_shortcode'] = 1;
			$defaults['update_ajax_url'] = 1;
			$defaults['auto_submit'] = 1;
			
		}
		
		//now add a default for published if the form has not been saved before
		if(!isset($settings['post_status']))
		{
			$defaults['post_status']['publish'] = "publish";
		}
		
		
		if(is_array($settings))
		{
			
			$values = array_replace_recursive ($defaults, $settings);
		}
		else
		{
			$values = $defaults;
		}
		
		include_once( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/admin-search-form-settings-metabox.php' );
	}
	
	
	function load_post_search_form_metabox($object, $box)
	{
		include_once( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/admin-search-form-metabox.php' );
	}
	
	function load_post_available_fields_metabox($object, $box)
	{
		include_once( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/admin-available-fields-metabox.php' );
		
	}
	
	function set_selected($desired_value, $current_value, $echo = true)
	{
		if($desired_value==$current_value)
		{
			if($echo==true)
			{
				echo ' selected="selected"';
			}
			else
			{
				return ' selected="selected"';
			}
		}
	}
	
	function set_radio($desired_value, $current_value, $echo = true)
	{
		if($desired_value==$current_value)
		{
			if($echo==true)
			{
				echo ' checked="checked"';
			}
			else
			{
				return ' checked="checked"';
			}
		}
	}
	
	function set_checked($current_value)
	{
		if($current_value!="")
		{
			echo ' checked="checked"';
		}
	}
	
	function get_post_meta_all($post_id)
	{
		global $wpdb;
		$data   =   array();
		$wpdb->query("
			SELECT `meta_key`, `meta_value`
			FROM $wpdb->postmeta
			WHERE `post_id` = $post_id
		");
		foreach($wpdb->last_result as $k => $v){
			$data[$v->meta_key] =   $v->meta_value;
		};
		return $data;
	}
	
	function get_all_post_meta_keys()
	{
		if(is_array($this->post_meta_keys))
		{
			$num_meta_keys = count($this->post_meta_keys);
			if($num_meta_keys==0)
			{
				$ignore_list = array(
					'_wp_page_template', '_edit_lock', '_edit_last', '_menu_item_type', '_menu_item_menu_item_parent', '_menu_item_object_id', '_menu_item_object', '_menu_item_target', '_menu_item_classes', '_menu_item_xfn', '_menu_item_url', '_search-filter-fields'
				);
				global $wpdb;
				$data   =   array();
				$wpdb->query("
					SELECT DISTINCT `meta_key`
					FROM $wpdb->postmeta ORDER BY `meta_key` ASC
				");
				
				foreach($wpdb->last_result as $k => $v){
					//$data[$v->meta_key] =   $v->meta_value;
					$data[] = $v->meta_key;
				};
				
				$this->post_meta_keys = $data;
			}
		}
		
		return $this->post_meta_keys;
	}
	
	function display_meta_box_field($type, $widget_data = array())
	{
		if($type=="search")
		{
			$defaults = array(
				'heading'				=> '',
				'placeholder'			=> __("Search &hellip;", $this->plugin_slug),
				'type'					=> $type
			);
			
			$values = array_replace($defaults, $widget_data);
			
			include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/fields/search.php' );
		}
		else if($type=="tag")
		{
			$defaults = array(
				'taxonomy_name'			=> '',
				'input_type'			=> '',
				'heading'				=> '',
				'all_items_label'		=> '',
				'operator'				=> '',
				'show_count'			=> '',
				'hide_empty'			=> '',
				'hierarchical'			=> '',
				'include_children'		=> '',
				'drill_down'			=> '',
				'order_by'				=> '',
				'order_dir'				=> '',
				'exclude_ids'			=> '',
				'sync_include_exclude'	=> '1',
				'combo_box'				=> '',
				'type'					=> $type
			);
			
			$values = array_replace($defaults, $widget_data);
			
			include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/fields/tag.php' );
		}
		else if($type=="category")
		{
			$defaults = array(
				'taxonomy_name'			=> '',
				'input_type'			=> '',
				'heading'				=> '',
				'all_items_label'		=> '',
				'operator'				=> '',
				'show_count'			=> '',
				'hide_empty'			=> '',
				'hierarchical'			=> '',
				'include_children'		=> '',
				'drill_down'			=> '',
				'order_by'				=> '',
				'order_dir'				=> '',
				'exclude_ids'			=> '',
				'sync_include_exclude'	=> '1',
				'combo_box'				=> '',
				'type'					=> $type
			);
			
			$values = array_replace($defaults, $widget_data);
			
			include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/fields/category.php' );
		}
		else if($type=="taxonomy")
		{
			$defaults = array(
				'taxonomy_name'			=> '',
				'input_type'			=> '',
				'heading'				=> '',
				'all_items_label'		=> '',
				'operator'				=> '',
				'show_count'			=> '',
				'hide_empty'			=> '',
				'hierarchical'			=> '',
				'include_children'		=> '',
				'drill_down'			=> '',
				'order_by'				=> '',
				'order_dir'				=> '',
				'exclude_ids'			=> '',
				'sync_include_exclude'	=> '1',
				'combo_box'				=> '',
				'type'					=> $type
			);
			
			$values = array_replace($defaults, $widget_data);
			
			include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/fields/taxonomy.php' );
		}
		else if($type=="post_type")
		{
			$defaults = array(
				'post_types'			=> '',
				'input_type'			=> '',
				'heading'				=> '',
				'all_items_label'		=> '',
				'show_count'			=> '',
				'hide_empty'			=> '',
				'order_by'				=> '',
				'order_dir'				=> '',
				'combo_box'				=> '',
				'type'					=> $type
			);
			
			$values = array_replace($defaults, $widget_data);
			
			include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/fields/post-type.php' );
		}
		else if($type=="post_date")
		{
			$defaults = array(
				'input_type'			=> '',
				'heading'				=> '',
				'date_format'			=> 'd/m/Y',
				'type'					=> $type
			);
			
			$values = array_replace($defaults, $widget_data);
			
			include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/fields/post-date.php' );
		}
		else if($type=="post_meta")
		{
			$defaults = array(
				'heading'					=> '',
				
				'meta_type'					=> '',
				'meta_key'					=> '',
				'meta_key_manual'			=> '',
				'meta_key_manual_toggle'	=> '',
				
				'number_input_type'			=> '',
				'choice_input_type'			=> '',
				'combo_box'					=> '',
				'date_input_type'			=> '',
				
				'range_min'					=> '0',
				'range_max'					=> '1000',
				'range_step'				=> '10',
				'range_value_prefix'		=> '',
				'range_value_postfix'		=> '',
				
				'date_output_format'		=> 'd/m/Y',
				'date_input_format'			=> 'timestamp',
				
				'all_items_label'			=> '',
				'operator'					=> '',
				
				'meta_options'				=> array(),
				
				'type'						=> $type
			);
			
			$values = array_replace($defaults, $widget_data);
			
			include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/fields/post-meta.php' );
		}
		else if($type=="author")
		{
			$defaults = array(
				'input_type'				=> '',
				'heading'					=> '',
				'optioncount'				=> '',
				'exclude_admin'				=> '',
				'show_fullname'				=> '',
				'order_by'					=> '',
				'order_dir'					=> '',
				'hide_empty'				=> '',
				'operator'					=> '',
				'all_items_label'			=> '',
				'exclude'					=> '',
				'combo_box'					=> '',
				'type'						=> $type
			);
			
			$values = array_replace($defaults, $widget_data);
			
			include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/fields/author.php' );
		}
		else if($type=="submit")
		{
			$defaults = array(
				'label'						=> 'Submit',
				'heading'					=> '',
				'type'						=> $type
			);
			
			$values = array_replace($defaults, $widget_data);
			
			include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/fields/submit.php' );
		}
		else if($type=="reset")
		{
			$defaults = array(
				'label'						=> 'Reset',
				'input_type'				=> 'link',
				'heading'					=> '',
				'type'						=> $type
			);
			
			$values = array_replace($defaults, $widget_data);
			
			include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/fields/reset.php' );
		}
		else if($type=="sort_order")
		{
			$defaults = array(
				/*'meta_key'					=> '',
				'meta_key_manual'			=> '',
				'meta_key_manual_toggle'	=> '',*/
				'input_type'				=> '',
				'heading'					=> '',
				'all_items_label'			=> '',
				'sort_options'				=> array(),
				'type'						=> $type
			);
			
			$values = array_replace($defaults, $widget_data);
			
			include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/fields/sort-order.php' );
		}
		
	}
	
	function display_settings_meta_option($option_data = array(), $class = "")
	{
		$defaults = array(
			'meta_key'							=> '',
			'meta_compare'						=> '',
			'meta_value'						=> '',
			'meta_type'							=> '',
			'meta_date_value_day'				=> '',
			'meta_date_value_month'				=> '',
			'meta_date_value_year'				=> '',
			'meta_date_value_date'				=> '',
			'meta_date_value_timestamp'			=> '',
			'meta_date_value_current_date'		=> '',
			'meta_date_value_current_timestamp'	=> ''
			
		);
		
		$values = array_replace($defaults, $option_data);
		
		include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/settings-metabox/meta-option.php' );		
	}
	function display_sort_option($option_data = array(), $class = "")
	{
		$defaults = array(
			'meta_key'					=> '',
			'sort_by'					=> '',
			'sort_label'				=> '',
			'sort_dir'					=> '',
			'sort_label'				=> '',
			'sort_type'					=> 'numeric'
		);
		
		$values = array_replace($defaults, $option_data);
		
		include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/fields/sort-order-option.php' );		
	}
	function display_meta_option($option_data = array(), $class = "")
	{
		$defaults = array(
			'option_value'				=> '',
			'option_label'				=> ''
		);
		
		$values = array_replace($defaults, $option_data);
		
		include( ( plugin_dir_path( dirname( __FILE__ ) ) ) . 'views/fields/meta-option.php' );		
	}
	
}


if ( ! class_exists( 'Search_Filter_Post_Data_Validation' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'class-search-filter-post-data-validation.php' );
}
