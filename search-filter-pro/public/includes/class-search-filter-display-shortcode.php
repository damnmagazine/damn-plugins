<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Display_Shortcode
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2014 Designs & Code
 */

//form prefix
if (!defined('SF_FPRE'))
{
    define('SF_FPRE', '_sf_'); 
}
if (!defined('SF_TAX_PRE'))
{
    define('SF_TAX_PRE', '_sft_'); 
}
if (!defined('SF_META_PRE'))
{
    define('SF_META_PRE', '_sfm_'); 
}
if (!defined('SF_CLASS_PRE'))
{
    define('SF_CLASS_PRE', 'sf-'); 
}
if (!defined('SF_FIELD_CLASS_PRE'))
{
    define('SF_FIELD_CLASS_PRE', SF_CLASS_PRE."field-"); 
}
if (!defined('SF_ITEM_CLASS_PRE'))
{
    define('SF_ITEM_CLASS_PRE', SF_CLASS_PRE."item-"); 
}
class Search_Filter_Display_Shortcode {
	
	
	public function __construct($plugin_slug)
	{
		/*
		 * Call $plugin_slug from public plugin class.
		 */
		
		//$plugin = Search_Filter::get_instance();
		$this->plugin_slug = $plugin_slug;

		// Add shortcode support for widgets
		add_shortcode('searchandfilter', array($this, 'display_shortcode'));
		add_filter('widget_text', 'do_shortcode');
		
		//add query vars
		add_filter('query_vars', array($this,'add_queryvars') );
		
		$this->is_form_using_template = false; //if the user has selected to use a template with this form
		
		//if the current page is using the defined template - the search form can display anywhere on the site so sometimes where it is displayed may not be a results page
		$this->is_template_loaded = false; 
		
		$this->create_input = new Search_Filter_Generate_Input($plugin_slug);
		$this->display_results = new Search_Filter_Display_Results($plugin_slug);
	}
	
	public function set_is_template($is_template)
	{
		$this->is_template_loaded = $is_template;
	}
	
	public function set_defaults()
	{
		global $sf_form_data;
		global $wp_query;
		
		$this->frmqreserved = array(SF_FPRE."category_name", SF_FPRE."s", SF_FPRE."tag", SF_FPRE."submitted", SF_FPRE."post_date", SF_FPRE."post_types", SF_FPRE."sort_order", SF_FPRE."author"); //same as reserved
		
		$categories = array();
		
		if(isset($wp_query->query['category_name']))
		{
			$category_params = (preg_split("/[,\+ ]/", esc_attr($wp_query->query['category_name']))); //explode with 2 delims
							
			//$category_params = explode("+",esc_attr($wp_query->query['category_name']));
			
			foreach($category_params as $category_param)
			{
				$category = get_category_by_slug( $category_param );
				if(isset($category->cat_ID))
				{
					$categories[] = $category->cat_ID;
				}
			}
		}
		
		$this->defaults[SF_FPRE.'category'] = $categories;
		
		//grab search term for prefilling search input
		if(isset($wp_query->query['_sf_s']))
		{
			$this->searchterm = trim($wp_query->query['_sf_s']);
		}
		else if(isset($wp_query->query['s']))
		{			
			$this->searchterm = trim(get_search_query());
		}

		//check to see if tag is set

		$tags = array();
		
		if(isset($wp_query->query['tag']))
		{
			$tag_params = (preg_split("/[,\+ ]/", esc_attr($wp_query->query['tag']))); //explode with 2 delims
			
			foreach($tag_params as $tag_param)
			{
				$tag = get_term_by("slug",$tag_param, "post_tag");
				if(isset($tag->term_id))
				{
					$tags[] = $tag->term_id;
				}
			}
		}
		
		$this->defaults[SF_FPRE.'post_tag'] = $tags;
		
		$taxonomies_list = get_taxonomies('','names');
		
		
		$taxs = array();
		
		//loop through all the query vars
		if(isset($wp_query->query))
		{
			foreach($wp_query->query as $key=>$val)
			{
				
				if (strpos($key, SF_TAX_PRE) === 0)
				{
					$key = substr($key, strlen(SF_TAX_PRE));
					
					$taxslug = ($val);
					//$tax_params = explode("+",esc_attr($taxslug));
					
					$tax_params = (preg_split("/[,\+ ]/", esc_attr($taxslug))); //explode with 2 delims

					foreach($tax_params as $tax_param)
					{
						$tax = get_term_by("slug",$tax_param, $key);

						if(isset($tax->term_id))
						{
							$taxs[] = $tax->term_id;
						}
					}

					$this->defaults[SF_TAX_PRE.$key] = $taxs;
					
					
				}
				else if (strpos($key, SF_META_PRE) === 0)
				{
					$key = substr($key, strlen(SF_META_PRE));
					
					$meta_data = array("","");
					
					if(isset($wp_query->query[SF_META_PRE.$key]))
					{
						//get meta field options
						$meta_field_data = $sf_form_data->get_field_by_key(SF_META_PRE.$key);
						
						if($meta_field_data['meta_type']=="number")
						{
							$meta_data = array("","");
							if(isset($wp_query->query[SF_META_PRE.$key]))
							{
								$meta_data = (preg_split("/[,\+ ]/", esc_attr(($wp_query->query[SF_META_PRE.$key])))); //explode with 2 delims
								
								if(count($meta_data)==1)
								{
									$meta_data[1] = "";
								}
							}
							
							$this->defaults[SF_FPRE.$key] = $meta_data;	
						}
						else if($meta_field_data['meta_type']=="choice")
						{
							/*if (strpos(esc_attr($wp_query->query[SF_META_PRE.$key]),'-,-') !== false) {
								$ochar = "-,-";
								$meta_data = explode($ochar, esc_attr($wp_query->query[SF_META_PRE.$key]));
								
								
							}
							else
							{
								$ochar = "-+-";
								$meta_data = explode($ochar, esc_attr(urlencode($wp_query->query[SF_META_PRE.$key])));
								$meta_data = array_map( 'urldecode', ($meta_data) );
							}*/
							
							if($meta_field_data["operator"]=="or")
							{
								$ochar = "-,-";
								$meta_data = explode($ochar, esc_attr($wp_query->query[SF_META_PRE.$key]));
							}
							else
							{
								$ochar = "-+-";
								$meta_data = explode($ochar, esc_attr(urlencode($wp_query->query[SF_META_PRE.$key])));
								$meta_data = array_map( 'urldecode', ($meta_data) );
							}
							
							if(count($meta_data)==1)
							{
								$meta_data[1] = "";
							}
						}
						else if($meta_field_data['meta_type']=="date")
						{
							$meta_data = array("","");
							if(isset($wp_query->query[SF_META_PRE.$key]))
							{
								$meta_data = array_map('urldecode', explode("+", esc_attr(urlencode($wp_query->query[SF_META_PRE.$key]))));
								if(count($meta_data)==1)
								{
									$meta_data[1] = "";
								}
							}
						}
					}
					
					$this->defaults[SF_META_PRE.$key] = $meta_data;					
					
				}
			}
		}
		
		$post_date = array("","");
		if(isset($wp_query->query['post_date']))
		{
			$post_date = array_map('urldecode', explode("+", esc_attr(urlencode($wp_query->query['post_date']))));
			if(count($post_date)==1)
			{
				$post_date[1] = "";
			}
		}
		$this->defaults[SF_FPRE.'post_date'] = $post_date;
		
		
		$post_types = array();
		if(isset($wp_query->query['post_types']))
		{
			$post_types = explode(",",esc_attr($wp_query->query['post_types']));
		}
		$this->defaults[SF_FPRE.'post_type'] = $post_types;
		
		
		$sort_order = array();
		if(isset($wp_query->query['sort_order']))
		{
			$sort_order = explode(",",esc_attr(urlencode($wp_query->query['sort_order'])));
		}
		$this->defaults[SF_FPRE.'sort_order'] = $sort_order;
		
		$authors = array();
		if(isset($wp_query->query['authors']))
		{
			$authors = explode(",",esc_attr($wp_query->query['authors']));
		}
		
		$this->defaults[SF_FPRE.'author'] = $authors;
		
		/*echo "<pre>";
		//var_dump($this->defaults);
		global $sf_form_data;
		var_dump($sf_form_data->get_count_table());
		echo "</pre>";*/
	}
	
	// get current URL
	function get_current_URL() 
	{
		 $current_url  = 'http';
		 $server_https = isset($_SERVER["HTTPS"]) ? $_SERVER["HTTPS"] : '';
		 $server_name  = isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : '';
		 $server_port  = isset($_SERVER["SERVER_PORT"]) ? $_SERVER["SERVER_PORT"] : '';
		 $request_uri  = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '';
		 
		 if ($server_https == "on") $current_url .= "s";
		 $current_url .= "://";
		 if ($server_port != "80") $current_url .= $server_name . ":" . $server_port . $request_uri;
		 else $current_url .= $server_name . $request_uri;
		 return $current_url;
	}
	
	public function display_shortcode($atts, $content = null)
	{
		
		//load scripts on this page where the shortcode is called
		wp_enqueue_script( $this->plugin_slug . '-plugin-build' );
		wp_enqueue_script( $this->plugin_slug . '-chosen-script' );
		wp_enqueue_script( 'jquery-ui-datepicker' ); 
		
		// extract the attributes into variables
		extract(shortcode_atts(array(
		
			'id' => '',
			'show' => 'form'
			
		), $atts));
		
		$returnvar = "";
		
		//make sure its set
		if($id!="")
		{
			
			if ( function_exists('icl_object_id') )
			{
				$base_form_id = icl_object_id($id, 'search-filter-widget', true, ICL_LANGUAGE_CODE);
			}
			else
			{				
				$base_form_id = $id;
			}
					
			
			$fields = get_post_meta( $base_form_id , '_search-filter-fields' , true );
			$settings = get_post_meta( $base_form_id , '_search-filter-settings' , true );
			$addclass = "";
			
			global $sf_form_data;
			
			$sf_form_data->init($base_form_id);
			$this->set_defaults();
			
			if($show=="form")
			{
				
				/* TODO  set auto count somewhere else */
				global $sf_form_data;
				if(isset($settings["enable_auto_count"]))
				{
					if($settings["enable_auto_count"]==1)
					{
						$term_relationships = new Search_Filter_Relationships($this->plugin_slug);
						$term_relationships->init_relationships();
						$sf_form_data->set_count_table($term_relationships->get_count_table());
					}
				}
				
				//make sure there are results
				if(isset($fields))
				{
					//make sure results are in array format as expected
					if(is_array($fields))
					{
						
						$use_ajax = isset($settings['use_ajax_toggle']) ? (bool)$settings['use_ajax_toggle'] : false;
						$use_history_api = true;
						$ajax_target = isset($settings['ajax_target']) ? esc_attr($settings['ajax_target']) : '';
						$results_url = isset($settings['results_url']) ? esc_attr($settings['results_url']) : '';
						$page_slug = isset($settings['page_slug']) ? esc_attr($settings['page_slug']) : '';
						$ajax_links_selector = isset($settings['ajax_links_selector']) ? esc_attr($settings['ajax_links_selector']) : '';
						$ajax_auto_submit = isset($settings['auto_submit']) ? (int)$settings['auto_submit'] : '';
						$auto_count = isset($settings['enable_auto_count']) ? (int)$settings['enable_auto_count'] : '';
						$use_results_shortcode = isset($settings['use_results_shortcode']) ? (int)$settings['use_results_shortcode'] : ''; /* legacy */
						$display_results_as = isset($settings['display_results_as']) ? esc_attr($settings['display_results_as']) : 'shortcode';
						$update_ajax_url = isset($settings['update_ajax_url']) ? (int)$settings['update_ajax_url'] : '';
						$scroll_to_pos = isset($settings['scroll_to_pos']) ? esc_attr($settings['scroll_to_pos']) : '';
						$scroll_on_action = isset($settings['scroll_on_action']) ? esc_attr($settings['scroll_on_action']) : '';
						$custom_scroll_to = isset($settings['custom_scroll_to']) ? esc_html($settings['custom_scroll_to']) : '';
						
						/* legacy */
						if(isset($settings['use_results_shortcode']))
						{
							if($settings['use_results_shortcode']==1)
							{
								$display_results_as = "shortcode";
							}
							else
							{
								$display_results_as = "archive";
							}
						}
						/* end legacy */
						
						if(!isset($settings['update_ajax_url']))
						{
							$update_ajax_url = 1;
						}
						
						if($display_results_as=="shortcode")
						{//if we're using a shortcode, grab the selector automatically from the id
							$ajax_target = "#search-filter-results-".$base_form_id;
						}
						
						$post_types = isset($settings['post_types']) ? $settings['post_types'] : '';
						
						
						//url
						/*$ajax_url = "";
						$start_url = home_url();
						$full_url = $this->get_current_URL();
						if(substr($full_url, 0, strlen($start_url)) == $start_url)
						{
							$ajax_url = substr($full_url, strlen($start_url));
						}*/
						$ajax_url = "";
						if($display_results_as=="archive")
						{
							$ajax_url = $this->get_current_URL();
						}
						
						$form_attr = ' data-sf-form-id="'.$base_form_id.'"';
						$form_attr .= ' data-use-history-api="'.(int)$use_history_api.'"';
						$form_attr .= ' data-template-loaded="'.(int)$this->is_template_loaded.'"';
						
						$lang_code = "";
						if ( function_exists('icl_object_id') )
						{
							$lang_code = ICL_LANGUAGE_CODE;
						}
						$form_attr .= ' data-lang-code="'.$lang_code.'"';
						
						if($use_ajax)
						{
							$form_attr.=' data-ajax="'.(int)$use_ajax.'"';
							
							if($ajax_target!="")
							{
								$form_attr.=' data-ajax-target="'.$ajax_target.'"';
							}
							
							if($ajax_links_selector!="")
							{
								$form_attr.=' data-ajax-links-selector="'.$ajax_links_selector.'"';
							}
							
							if($ajax_url!="")
							{
								$form_attr.=' data-ajax-url="'.esc_attr($ajax_url).'"';
							}
							
							/*if($use_results_shortcode!="")
							{
								$form_attr.=' data-ajax-shortcode="'.$use_results_shortcode.'"';
							}*/
							
							if($update_ajax_url!="")
							{
								$form_attr.=' data-update-ajax-url="'.$update_ajax_url.'"';
							}
							
							if($scroll_to_pos!="")
							{
								$form_attr.=' data-scroll-to-pos="'.$scroll_to_pos.'"';
								
								if($scroll_to_pos=="custom")
								{
									if($custom_scroll_to!="")
									{
										$form_attr.=' data-custom-scroll-to="'.$custom_scroll_to.'"';
									}
								}
							}
							
							if($scroll_on_action!="")
							{
								$form_attr.=' data-scroll-on-action="'.$scroll_on_action.'"';
							}
							
							
						}
						
						if($results_url!="")
						{
							$form_attr.=' data-results-url="'.$results_url.'"';
						}
						
						if($display_results_as!="")
						{
							$form_attr.=' data-display-results="'.$display_results_as.'"';
						}
						
						$form_attr.=' data-auto-update="'.$ajax_auto_submit.'"';
							
						if((($use_ajax)&&($display_results_as=="archive"))||(!$use_ajax))
						{
							if(get_option('permalink_structure'))
							{
								if($page_slug!="")
								{
									$form_attr.=' data-page-slug="'.$page_slug.'"';
								}
							}
						}
						
						
						if($auto_count==1)
						{
							$form_attr.=' data-auto-count="'.esc_attr($auto_count).'"';
						}
						$returnvar .= '<form action="" method="post" class="searchandfilter'.$addclass.'"'.$form_attr.' id="search-filter-form-'.$base_form_id.'">';
						$returnvar .= "<ul>";
						
						//loop through each field and grab html
						foreach ($fields as $field)
						{
							$returnvar .= $this->get_field($field, $post_types, $base_form_id);
						}
						
						$returnvar .= '<input type="hidden" name="'.SF_FPRE.'submitted" value="1" />';
						$returnvar .= '<input type="hidden" name="'.SF_FPRE.'form_id" value="'.esc_attr($base_form_id).'" class="sf_form_id" />';
						
						if(isset($_GET[SF_FPRE.'ajax_timestamp']))
						{
							if(is_numeric($_GET[SF_FPRE.'ajax_timestamp']))
							{
								$timestamp = $_GET[SF_FPRE.'ajax_timestamp'];
								$returnvar .= '<input type="hidden" name="'.SF_FPRE.'ajax_timestamp" value="'.esc_attr($timestamp).'" class="sf_ajax_timestamp" />';
							}
						}
						
						$returnvar .= "</ul>";
						$returnvar .= "</form>";
						
						
					}
				}
			}
			else if($show=="results")
			{
				/* legacy */
				if($sf_form_data->settings('use_results_shortcode')==1)
				{
					$display_results_as = "shortcode";
				}
				else
				{
					$display_results_as = "archive";
				}
				/* end legacy */
				
				if($sf_form_data->settings('display_results_as')!="")
				{
					$display_results_as = $sf_form_data->settings('display_results_as');
				}
				
				
				if($display_results_as=="shortcode")
				{
					$returnvar = $this->display_results->output_results($base_form_id, $settings);
				}
				else
				{
					
					
					if (current_user_can('edit_posts')) {
						$returnvar = __("<p><strong>Notice:</strong> This Search Form has not been configured to use a shortcode. <a href='".get_edit_post_link($base_form_id)."'>Edit settings</a>.</p>", $this->plugin_slug);
					}
				}
			}
			
		}
		
		return $returnvar;
	}
	
	//switch for different field types
	private function get_field($field_data, $post_types, $search_form_id)
	{
		$returnvar = "";
		
		$field_class = "";
		$field_name = "";
		if($field_data['type'] == "taxonomy")
		{
			$field_class = SF_FIELD_CLASS_PRE.$field_data['type']."-".($field_data['taxonomy_name']);
			$field_name = SF_TAX_PRE.$field_data['taxonomy_name'];
		}
		else if($field_data['type'] == "post_meta")
		{
			$field_class = SF_FIELD_CLASS_PRE.'post-meta'."-".($field_data['meta_key']);
			$field_name = SF_META_PRE.$field_data['meta_key'];
		}
		else
		{
			$field_class = SF_FIELD_CLASS_PRE.$field_data['type'];
			$field_name = $field_data['type'];
		}
		
		$field_class = sanitize_html_class($field_class);
		
		$input_type = "";
		if(isset($field_data['input_type']))
		{
			$input_type = $field_data['input_type'];
		}
		
		$addAttributes = "";
		if($field_data['type']=="post_meta")
		{
			$addAttributes = ' data-sf-meta-type="'.$field_data['meta_type'].'"';
			if($field_data['meta_type']=="number")
			{
				$input_type = $field_data['number_input_type'];
			}
			else if($field_data['meta_type']=="choice")
			{
				$input_type = $field_data['choice_input_type'];
			}
			else if($field_data['meta_type']=="date")
			{
				$input_type = $field_data['date_input_type'];
			}
		}
		
		$returnvar .= "<li class=\"$field_class\" data-sf-field-name=\"$field_name\" data-sf-field-type=\"".$field_data['type']."\" data-sf-field-input-type=\"".$input_type."\"".$addAttributes.">";
		
		//display a heading? (available to all field types)
		if(isset($field_data['heading']))
		{
			if($field_data['heading']!="")
			{
				$returnvar .= "<h4>".esc_html($field_data['heading'])."</h4>";
			}
		}
		
		if($field_data['type']=="search")
		{
			$returnvar .= $this->get_search_field($field_data);
		}
		else if(($field_data['type']=="tag")||($field_data['type']=="category")||($field_data['type']=="taxonomy"))
		{
			$returnvar .= $this->get_taxonomy_field($field_data);
		}
		else if($field_data['type']=="post_type")
		{
			$returnvar .= $this->get_post_type_field($field_data);
		}
		else if($field_data['type']=="post_date")
		{
			$returnvar .= $this->get_post_date_field($field_data);
		}
		else if($field_data['type']=="post_meta")
		{
			$returnvar .= $this->get_post_meta_field($field_data);
		}
		else if($field_data['type']=="sort_order")
		{
			$returnvar .= $this->get_sort_order_field($field_data);
		}
		else if($field_data['type']=="author")
		{
			$returnvar .= $this->get_author_field($field_data, $post_types);
		}
		else if($field_data['type']=="submit")
		{
			$returnvar .= $this->get_submit_field($field_data);
		}
		else if($field_data['type']=="reset")
		{
			$returnvar .= $this->get_reset_field($field_data, $search_form_id);
		}
		
		$returnvar .= "</li>";
		
		return $returnvar;
	}
	
	private function get_search_field($field_data)
	{
		$returnvar = "";
		
		//set defaults so no chance of any php errors when accessing un init vars
		$defaults = array(
			'placeholder'			=> __("Search &hellip;", $this->plugin_slug)
		);
		$values = array_replace($defaults, $field_data);
		
		$searchterm = "";
		if(isset($this->searchterm))
		{
			$searchterm = esc_attr($this->searchterm);
		}
		
		
		$returnvar .=  '<input type="text" name="'.SF_FPRE.'search" placeholder="'.$values['placeholder'].'" value="'.$searchterm.'">';
		
		return $returnvar;
	}
	
	private function get_taxonomy_field($field_data)
	{
		$returnvar = "";
		
		//set defaults so no chance of any php errors when accessing un init vars
		$defaults = array(
			'taxonomy_name'			=> '',
			'input_type'			=> '',
			'heading'				=> '',
			'all_items_label'		=> '',
			'operator'				=> '',
			'show_count'			=> '',
			'hide_empty'			=> '',
			'hierarchical'			=> '',
			'drill_down'			=> '',
			'order_by'				=> '',
			'order_dir'				=> '',
			'exclude_ids'			=> '',
			'sync_include_exclude'	=> '',
			'combo_box'				=> ''
		);
		
		$values = array_replace($defaults, $field_data);
				
		$taxonomydata = get_taxonomy($values['taxonomy_name']);
		
		if(($values['all_items_label']=="")&&(isset($taxonomydata->labels->all_items)))
		{
			$values['all_items_label'] = $taxonomydata->labels->all_items;
		}
		
		
		
		//check the taxonomy exists
		if($taxonomydata)
		{
			$args = array(
				'sf_name' => SF_TAX_PRE . $values['taxonomy_name'],
				'taxonomy' => $values['taxonomy_name'],
				'hierarchical' => (bool)$values['hierarchical'],
				'child_of' => 0,
				'echo' => false,
				'hide_if_empty' => false,
				'hide_empty' => (bool)$values['hide_empty'],
				'show_option_none' => '',
				'show_count' => (bool)$values['show_count'],
				'show_option_all' => '',
				'show_option_all_sf' => esc_attr($values['all_items_label']),
				'elem_attr' => ""
			);
			
			if(($values['order_by']!="default")&&($values['order_by']!=""))
			{
				$args['orderby'] = $values['order_by'];
				$args['order'] = $values['order_dir'];
			}
			
			
			//setup include/exclude
			
			if($values['sync_include_exclude']==1)
			{
				
				global $sf_form_data;
				
				if($sf_form_data->settings('taxonomies_settings')!="")
				{
					
					if(is_array($sf_form_data->settings('taxonomies_settings')))
					{
						$taxonomies_settings = $sf_form_data->settings('taxonomies_settings');
						
						if($field_data['type']=="category")
						{
							if(isset($taxonomies_settings['category']))
							{
								if(isset($taxonomies_settings['category']['include_exclude']))
								{
									if($taxonomies_settings['category']['include_exclude']=="include")
									{
										$args['include'] = $taxonomies_settings['category']['ids'];
									}
									else
									{
										
										
										if($values['input_type']=="select")
										{
											if(!(bool)$values['hierarchical'])
											{//if not hierearchical exclude categories as normal
												$args['exclude'] = $taxonomies_settings['category']['ids'];
											}
											else
											{//else exclude category and its children when using hierarchical
												$args['exclude_tree'] = $taxonomies_settings['category']['ids'];
											}
										}
										else
										{
											$args['exclude'] = $taxonomies_settings['category']['ids'];
										}
									}
								
								}
							}
							
						}
						else if($field_data['type']=="tag")
						{
							if(isset($taxonomies_settings['post_tag']))
							{
								if(isset($taxonomies_settings['post_tag']['include_exclude']))
								{
									if($taxonomies_settings['post_tag']['include_exclude']=="include")
									{
										$args['include'] = $taxonomies_settings['post_tag']['ids'];
									}
									else
									{
										
										
										if($values['input_type']=="select")
										{
											if(!(bool)$values['hierarchical'])
											{//if not hierearchical exclude categories as normal
												$args['exclude'] = $taxonomies_settings['post_tag']['ids'];
											}
											else
											{//else exclude post_tag and its children when using hierarchical
												$args['exclude_tree'] = $taxonomies_settings['post_tag']['ids'];
											}
										}
										else
										{
											$args['exclude'] = $taxonomies_settings['post_tag']['ids'];
										}
									}
								
								}
							}
							
						}
						else if($field_data['type']=="taxonomy")
						{
							if(isset($taxonomies_settings[$values['taxonomy_name']]))
							{
								if(isset($taxonomies_settings[$values['taxonomy_name']]['include_exclude']))
								{
									if($taxonomies_settings[$values['taxonomy_name']]['include_exclude']=="include")
									{
										$args['include'] = $taxonomies_settings[$values['taxonomy_name']]['ids'];
									}
									else
									{
										
										
										if($values['input_type']=="select")
										{
											if(!(bool)$values['hierarchical'])
											{//if not hierearchical exclude categories as normal
												$args['exclude'] = $taxonomies_settings[$values['taxonomy_name']]['ids'];
											}
											else
											{//else exclude taxonomy and its children when using hierarchical
												$args['exclude_tree'] = $taxonomies_settings[$values['taxonomy_name']]['ids'];
											}
										}
										else
										{
											$args['exclude'] = $taxonomies_settings[$values['taxonomy_name']]['ids'];
										}
									}
								
								}
							}
							
						}
						
					}
				}
					
			}
			else
			{
				
				if($values['input_type']=="select")
				{
					if(!(bool)$values['hierarchical'])
					{//if not hierearchical exclude categories as normal
						$args['exclude'] = $values['exclude_ids'];
					}
					else
					{//else exclude category and its children when using hierarchical
						$args['exclude_tree'] = $values['exclude_ids'];
					}
				}
				else
				{
					$args['exclude'] = $values['exclude_ids'];
				}
			}
			
			
			/* setup defaults */
			$args['title_li'] = '';
			$args['defaults'] = "";
			
			if(isset($this->defaults[$args['sf_name']]))
			{
				$defaults_count = count($this->defaults[$args['sf_name']]);
				
				if($defaults_count>0)
				{
					$args['defaults'] = $this->defaults[$args['sf_name']];
				}
			}
			
			if($values['input_type']=="select")
			{
				if($values['combo_box']==1)
				{
					$args['elem_attr'] = ' data-combobox="1"';
				}			
				
				$returnvar .= $this->create_input->generate_wp_dropdown($args, $values['taxonomy_name'], $taxonomydata->labels);
			}
			else if($values['input_type']=="checkbox")
			{
				$args['elem_attr'] .= ' data-operator="'.esc_attr($values['operator']).'"';
				
				$returnvar .= $this->create_input->generate_wp_checkbox($args, $values['taxonomy_name'], $taxonomydata->labels);
			}
			else if($values['input_type']=="radio")
			{
				$returnvar .= $this->create_input->generate_wp_radio($args, $values['taxonomy_name'], $taxonomydata->labels);
			}
			else if($values['input_type']=="multiselect")
			{
				
				$args['elem_attr'] = "";
				if($values['combo_box']==1)
				{
					$args['elem_attr'] .= ' data-combobox="1" data-placeholder="'.esc_attr($values['all_items_label']).'"';
				}
				$args['elem_attr'] .= ' data-operator="'.esc_attr($values['operator']).'"';
				
			
				$returnvar .= $this->create_input->generate_wp_multiselect($args, $values['taxonomy_name'], $taxonomydata->labels);
			}
		}
		
		return $returnvar;
	}
	
	private function get_post_type_field($field_data)
	{
		$returnvar = "";
		
		$field_name = SF_FPRE."post_type";
		
		//set defaults so no chance of any php errors when accessing un init vars
		$defaults = array(
			'post_types'			=> array(),
			'input_type'			=> '',
			'heading'				=> '',
			'show_count'			=> '',
			'hide_empty'			=> '',
			'order_by'				=> '',
			'order_dir'				=> '',
			'combo_box'				=> ''
		);
		
		$values = array_replace($defaults, $field_data);
		
		if($field_data['all_items_label']=="")
		{
			$values['all_items_label'] = __('All Post Types', $this->plugin_slug);
		}
		
		$post_types_data = array();
		
		if(is_array($values['post_types']))
		{
			foreach($values['post_types'] as $post_type => $val)
			{
				$post_type_object = get_post_type_object( $post_type );
				
				if($post_type_object)
				{
					$new_post_type_object = array();
					$new_post_type_object['term_id'] = $post_type;
					$new_post_type_object['cat_name'] = $post_type_object->labels->name;

					$post_types_data[] = (object)$new_post_type_object;
				}
			}
		}
		
		
		$post_types_data = (object)$post_types_data;
		
		$defaults = "";
		
		if(isset($this->defaults[$field_name]))
		{
			$defaults_count = count($this->defaults[$field_name]);
			
			if($defaults_count>0)
			{
				$defaults = $this->defaults[$field_name];
			}
		}
		$elem_attr = "";
		
		if($values['input_type']=="select")
		{
			if($values['combo_box']==1)
			{
				$elem_attr = ' data-combobox="1"';
			}
			
			$returnvar .= $this->create_input->generate_select($post_types_data, $field_name, $defaults, $values['all_items_label'], $elem_attr);
		}
		else if($values['input_type']=="checkbox")
		{			
			$returnvar .= $this->create_input->generate_checkbox($post_types_data, $field_name, $defaults);
		}
		else if($values['input_type']=="radio")
		{
			$returnvar .= $this->create_input->generate_radio($post_types_data, $field_name, $defaults, $values['all_items_label']);
		}
		else if($values['input_type']=="multiselect")
		{
			if($values['combo_box']==1)
			{
				$elem_attr = ' data-combobox="1" data-placeholder="'.esc_attr($values['all_items_label']).'"';
			}
			$returnvar .= $this->create_input->generate_multiselect($post_types_data, $field_name, $defaults, $elem_attr);
		}
		
		return $returnvar;
	}
	
	private function get_post_date_field($field_data)
	{
		$returnvar = "";
		
		$field_name = SF_FPRE."post_date";
		
		$defaults = array(
			'input_type'			=> '',
			'date_format'			=> '',
			'heading'				=> ''
		);
				
		$values = array_replace($defaults, $field_data);
		
		if($values['date_format']=="")
		{
			$values['date_format'] = 'm/d/Y';
		}
		
		$defaults = "";
		$placeholder = "";
		$jqueryformat = "";
		
		if($values['date_format']=="m/d/Y")
		{
			$placeholder = __("mm/dd/yyyy", $this->plugin_slug);
			$jqueryformat = __("mm/dd/yy", $this->plugin_slug);
		}
		else if($values['date_format']=="d/m/Y")
		{
			$placeholder = __("dd/mm/yyyy", $this->plugin_slug);
			$jqueryformat = __("dd/mm/yy", $this->plugin_slug);
		}
		else if($values['date_format']=="Y/m/d")
		{
			$placeholder = __("yyyy/mm/dd", $this->plugin_slug);
			$jqueryformat = __("yy/mm/dd", $this->plugin_slug);
		}
		
		if(isset($this->defaults[$field_name]))
		{
			foreach($this->defaults[$field_name] as &$a_default)
			{
				if(strlen($a_default)==8)
				{
					if($values['date_format']=="m/d/Y")
					{
						$month = substr($a_default, 0, 2);
						$day = substr($a_default, 2, 2);
						$year = substr($a_default, 4, 4);
						
						$a_default = $month."/".$day."/".$year;
						
					}
					else if($values['date_format']=="d/m/Y")
					{
						$month = substr($a_default, 2, 2);
						$day = substr($a_default, 0, 2);
						$year = substr($a_default, 4, 4);
						
						$a_default = $day."/".$month."/".$year;
						
					}
					else if($values['date_format']=="Y/m/d")
					{
						$month = substr($a_default, 4, 2);
						$day = substr($a_default, 6, 2);
						$year = substr($a_default, 0, 4);
						
						$a_default = $year."/".$month."/".$day;
						
					}
				}
				else
				{
					$a_default = "";
				}
			}
			
			$defaults = $this->defaults[$field_name];
		}
		
		$returnvar .= "<ul class=\"sf_date_field\" data-date-format=\"".$jqueryformat."\">";
		
		if($values['input_type']=="date")
		{
			$returnvar .= "<li>";
			$returnvar .= $this->create_input->generate_date($field_name, $defaults, $placeholder, 0);
			$returnvar .= "</li>";
		}
		if($values['input_type']=="daterange")
		{
			$returnvar .= "<li>";
			$returnvar .= $this->create_input->generate_date($field_name, $defaults, $placeholder, 0);
			$returnvar .= "</li><li>";
			$returnvar .= $this->create_input->generate_date($field_name, $defaults, $placeholder, 1);
			$returnvar .= "</li>";
		}
		
		$returnvar .= "</ul>";
		
		return $returnvar;
	}
	private function get_meta_date_field($field_data, $field_name)
	{
		$returnvar = "";
		
		$defaults = array(
			'date_input_type'			=> '',
			'date_output_format'		=> '',
			'heading'					=> ''
		);
				
		$values = array_replace($defaults, $field_data);
		
		if($values['date_output_format']=="")
		{
			$values['date_output_format'] = 'm/d/Y';
		}
		
		$defaults = "";
		$placeholder = "";
		$jqueryformat = "";
		
		if($values['date_output_format']=="m/d/Y")
		{
			$placeholder = __("mm/dd/yyyy", $this->plugin_slug);
			$jqueryformat = __("mm/dd/yy", $this->plugin_slug);
		}
		else if($values['date_output_format']=="d/m/Y")
		{
			$placeholder = __("dd/mm/yyyy", $this->plugin_slug);
			$jqueryformat = __("dd/mm/yy", $this->plugin_slug);
		}
		else if($values['date_output_format']=="Y/m/d")
		{
			$placeholder = __("yyyy/mm/dd", $this->plugin_slug);
			$jqueryformat = __("yy/mm/dd", $this->plugin_slug);
		}
		
		if(isset($this->defaults[$field_name]))
		{
			foreach($this->defaults[$field_name] as &$a_default)
			{
				if(strlen($a_default)==8)
				{
					if($values['date_output_format']=="m/d/Y")
					{
						$month = substr($a_default, 0, 2);
						$day = substr($a_default, 2, 2);
						$year = substr($a_default, 4, 4);
						
						$a_default = $month."/".$day."/".$year;
						
					}
					else if($values['date_output_format']=="d/m/Y")
					{
						$month = substr($a_default, 2, 2);
						$day = substr($a_default, 0, 2);
						$year = substr($a_default, 4, 4);
						
						$a_default = $day."/".$month."/".$year;
						
					}
					else if($values['date_output_format']=="Y/m/d")
					{
						$month = substr($a_default, 4, 2);
						$day = substr($a_default, 6, 2);
						$year = substr($a_default, 0, 4);
						
						$a_default = $year."/".$month."/".$day;
						
					}
				}
				else
				{
					$a_default = "";
				}
			}
			
			$defaults = $this->defaults[$field_name];
		}
		
		$returnvar .= "<ul class=\"sf_date_field\" data-date-format=\"".$jqueryformat."\">";
		
		if($values['date_input_type']=="date")
		{
			$returnvar .= "<li>";
			$returnvar .= $this->create_input->generate_date($field_name, $defaults, $placeholder, 0);
			$returnvar .= "</li>";
		}
		if($values['date_input_type']=="daterange")
		{
			$returnvar .= "<li>";
			$returnvar .= $this->create_input->generate_date($field_name, $defaults, $placeholder, 0);
			$returnvar .= "</li><li>";
			$returnvar .= $this->create_input->generate_date($field_name, $defaults, $placeholder, 1);
			$returnvar .= "</li>";
		}
		
		$returnvar .= "</ul>";
		
		return $returnvar;
	}
	
	private function get_post_meta_field($field_data)
	{
		$returnvar = "";
		
		//set defaults so no chance of any php errors when accessing un init vars
		$defaults = array(
			
			'heading'					=> '',
			
			'meta_type'					=> 'number',
			'meta_key'					=> '',
			'meta_key_manual'			=> '',
			'meta_key_manual_toggle'	=> '',
			
			'number_input_type'			=> '',
			'choice_input_type'			=> '',
			'date_input_type'			=> '',
			
			'range_min'					=> '0',
			'range_max'					=> '1000',
			'range_step'				=> '10',
			'range_value_prefix'		=> '',
			'range_value_postfix'		=> '',
			
			'meta_options'				=> array(),
			
			'all_items_label'			=> '',
			'combo_box'					=> ''
		);
		
		$values = array_replace($defaults, $field_data);		
		
		if($values['meta_key_manual_toggle']==1)
		{
			$meta_key = $values['meta_key_manual'];
		}
		else
		{
			$meta_key = $values['meta_key'];
		}
		
		if($values['all_items_label']=="")
		{
			$values['all_items_label'] = __("All Items", $this->plugin_slug);
		}
		
		$field_name = SF_META_PRE.$meta_key;
		
			
		$defaults = "";
		if(isset($this->defaults[$field_name]))
		{
			$defaults = $this->defaults[$field_name];
		}
		
		if($values['meta_type']=="number")
		{
			if($values['number_input_type']=="range-slider")
			{
				if(is_array($defaults))
				{
					if(!isset($defaults[0]))
					{
						$defaults[0] = $values['range_min'];
					}
					if(!isset($defaults[1]))
					{
						$defaults[1] = $values['range_max'];
					}				
				}
				else
				{
					$defaults = array($values['range_min'], $values['range_max']);
				}
				
				$returnvar .= $this->create_input->generate_range_slider($field_name, $values['range_min'], $values['range_max'], $values['range_step'], $defaults[0], $defaults[1], $values['range_value_prefix'], $values['range_value_postfix']);
			}
			else if($values['number_input_type']=="range-number")
			{
				if(is_array($defaults))
				{
					if(!isset($defaults[0]))
					{
						$defaults[0] = $values['range_min'];
					}
					else if($defaults[0]=="")
					{
						$defaults[0] = $values['range_min'];
					}
					
					if(!isset($defaults[1]))
					{
						$defaults[1] = $values['range_max'];
					}
					else if($defaults[1]=="")
					{
						$defaults[1] = $values['range_max'];
					}
				}
				else
				{
					$defaults = array($values['range_min'], $values['range_max']);
				}
				
				$returnvar .= $this->create_input->generate_range_number($field_name, $values['range_min'], $values['range_max'], $values['range_step'], $defaults[0], $defaults[1], $values['range_value_prefix'], $values['range_value_postfix']);
			}
			else if($values['number_input_type']=="range-radio")
			{
				if(isset($defaults))
				{
					if(is_array($defaults))
					{
						$defaults = implode($defaults, "+");
					}
				}
				
				$returnvar .= $this->create_input->generate_range_radio($field_name, $values['range_min'], $values['range_max'], $values['range_step'], $defaults, $values['range_value_prefix'], $values['range_value_postfix']);
				
			}
			else if($values['number_input_type']=="range-checkbox")
			{
				$returnvar .= $this->create_input->generate_range_checkbox($field_name, $values['range_min'], $values['range_max'], $values['range_step'], $values['range_min'], $values['range_max'], $values['range_value_prefix'], $values['range_value_postfix']);
			}
			else if($values['number_input_type']=="range-select")
			{
				$returnvar .= $this->create_input->generate_range_select($field_name, $values['range_min'], $values['range_max'], $values['range_step'], $defaults, $values['range_value_prefix'], $values['range_value_postfix']);
			}
		}
		else if($values['meta_type']=="choice")
		{
			if(isset($values['meta_options']))
			{
				if(is_array($values['meta_options']))
				{
					$meta_options = array();
					
					$taxonomychildren = array();
					
					foreach ($values['meta_options'] as $meta_option)
					{
						$tempobject = array();
						$tempobject['term_id'] = $meta_option['option_value'];
						$tempobject['cat_name'] = $meta_option['option_label'];
						
						$taxonomychildren[] = (object)$tempobject;
					}
					$elem_attr = "";
					if($values['choice_input_type']=="select")
					{
						if($values['combo_box']==1)
						{
							$elem_attr = ' data-combobox="1"';
						}
						$returnvar .= $this->create_input->generate_select($taxonomychildren, $field_name, $defaults, $values['all_items_label'], $elem_attr);
					}
					else if($values['choice_input_type']=="checkbox")
					{
						$elem_attr = ' data-operator="'.esc_attr($values['operator']).'"';
						$returnvar .= $this->create_input->generate_checkbox($taxonomychildren, $field_name, $defaults, $elem_attr);
					}
					else if($values['choice_input_type']=="radio")
					{
						$returnvar .= $this->create_input->generate_radio($taxonomychildren, $field_name, $defaults, $values['all_items_label']);
					}
					else if($values['choice_input_type']=="multiselect")
					{
						if($values['combo_box']==1)
						{
							$elem_attr = ' data-combobox="1" data-placeholder="'.esc_attr($values['all_items_label']).'"';
						}
						$elem_attr .= ' data-operator="'.esc_attr($values['operator']).'"';
						
						$returnvar .= $this->create_input->generate_multiselect($taxonomychildren, $field_name, $defaults, $elem_attr);
					}
				}
			}			
		}
		else if($values['meta_type']=="date")
		{
			$returnvar .= $this->get_meta_date_field($field_data, $field_name);
		}
		
		return $returnvar;
	}
	
	private function get_sort_order_field($field_data)
	{
		$returnvar = "";
		
		$field_name = SF_FPRE."sort_order";
		
		//set defaults so no chance of any php errors when accessing un init vars
		$defaults = array(
			'input_type'				=> '',
			'all_items_label'			=> '',
			'sort_options'				=> array()
		);
		
		$values = array_replace($defaults, $field_data);
		
		if($values['all_items_label']=="")
		{
			$values['all_items_label'] = __("Sort Results By", $this->plugin_slug);
		}
		
		$taxonomychildren = array();
		
		$no_sort_options = count($values['sort_options']);
		if($no_sort_options>0)
		{
			foreach($values['sort_options'] as $sort_option)
			{
				$sort_by = "";
				$sort_label = "";
				$sort_dir = "";
				
				$meta_key = "";
				$sort_type = "";
				
				if(isset($sort_option['sort_by']))
				{
					$sort_by = $sort_option['sort_by'];
					
					if($sort_by=="meta_value")
					{
						if(isset($sort_option['meta_key']))
						{
							$sort_by = SF_META_PRE.$sort_option['meta_key'];
						}
						
						if(isset($sort_option['sort_type']))
						{
							if($sort_option['sort_type']=="numeric")
							{
								$sort_type = "+num";
							}
							else if($sort_option['sort_type']=="alphabetic")
							{
								$sort_type = "+alpha";
							}
						}
						
					}
				}
				if(isset($sort_option['sort_label']))
				{
					$sort_label = $sort_option['sort_label'];
				}
				if(isset($sort_option['sort_dir']))
				{
					$sort_dir = $sort_option['sort_dir'];
				}
				
				
				
				//add entry for asc
				$tempobject = array();
				$tempobject['term_id'] = $sort_by."+".$sort_dir.$sort_type;
				$tempobject['cat_name'] = $sort_label;
				$taxonomychildren[] = (object)$tempobject;
				
			}
			
			$defaults = "";
			
			if(isset($this->defaults[$field_name]))
			{
				$defaults_count = count($this->defaults[$field_name]);
				
				if($defaults_count>0)
				{
					$defaults = $this->defaults[$field_name];
				}
			}
			
			
			$returnvar .= $this->create_input->generate_select($taxonomychildren, $field_name, $defaults, $values['all_items_label']);
			
		}
		else
		{
			return;
		}
		
				
		return $returnvar;
	}
	
	
	private function get_author_field($field_data, $post_types)
	{
		$returnvar = "";
		
		$field_name = SF_FPRE."author";
		
		//set defaults so no chance of any php errors when accessing un init vars
		$defaults = array(
			'input_type'				=> '',
			'optioncount'				=> '',
			'exclude_admin'				=> '',
			'show_fullname'				=> '',
			'order_by'					=> '',
			'order_dir'					=> '',
			'hide_empty'				=> '',
			'operator'					=> '',
			'all_items_label'			=> '',
			'exclude'					=> '',
			'combo_box'					=> ''
		);
		//set defaults so no chance of any php errors when accessing un init vars
		$values = array_replace($defaults, $field_data);
		
		if(is_array($post_types))
		{//get the post types in to the proper format
			$post_types_array = array();
			
			foreach ($post_types as $key => $val)
			{
				$post_types_array[] = $key;
			}
			
			$values['post_types'] = $post_types_array;
		}
		
		
		if($field_data['all_items_label']=="")
		{
			$values['all_items_label'] = __('All Authors', $this->plugin_slug);
		}
		
		if(($values['order_by']!="default")&&($values['order_by']!=""))
		{
			$values['orderby'] = $values['order_by'];
			$values['order'] = strtoupper($values['order_dir']);
		}
		
		$values['name'] = $field_name; //field name
		
		$defaults = "";
		if(isset($this->defaults[$field_name]))
		{
			$defaults = $this->defaults[$field_name];
		}
		
		$values['defaults'] = $defaults;
		
		$returnvar .= $this->create_input->walk_author($values['input_type'], $values);
		
		return $returnvar;
	}
	
	
	private function get_submit_field($field_data)
	{
		$returnvar = "";
		
		//set defaults so no chance of any php errors when accessing un init vars
		$defaults = array(
			'label'			=> __("Submit", $this->plugin_slug)
		);
		$values = array_replace($defaults, $field_data);
		
		//$searchterm = (esc_attr($this->searchterm));
		$searchterm =  "";
		$returnvar .=  '<input type="submit" name="'.SF_FPRE.'submit" value="'.$values['label'].'">';
		
		return $returnvar;
	}
	
	private function get_reset_field($field_data, $search_form_id)
	{
		$returnvar = "";
		
		//set defaults so no chance of any php errors when accessing un init vars
		$defaults = array(
			'label'			=> __("Reset", $this->plugin_slug),
			'input_type'	=> "link"
		);
		
		$values = array_replace($defaults, $field_data);
		
		$searchterm =  "";
		if($values['input_type']=="link")
		{
			$returnvar .=  '<a href="#" class="search-filter-reset" data-search-form-id="'.$search_form_id.'">'.$values['label'].'</a>';
		}
		else
		{
			$returnvar .=  '<input type="submit" class="search-filter-reset" name="'.SF_FPRE.'reset" value="'.$values['label'].'" data-search-form-id="'.$search_form_id.'">';
		}
		
		return $returnvar;
	}
	
	
	
	
	function add_queryvars( $qvars )
	{
		$qvars[] = 'post_types';
		$qvars[] = 'post_date';
		$qvars[] = 'sort_order';
		$qvars[] = 'authors';
		$qvars[] = '_sf_s';
		$qvars[] = 'sfid'; //search filter template
		
		//we need to add in any meta keys
		foreach($_GET as $key=>$val)
		{
			$key = sanitize_text_field($key);
			
			if(($this->is_meta_value($key))||($this->is_taxonomy_key($key)))
			{
				$qvars[] = $key;
			}
		}
		
		return $qvars;
	}
	
	public function is_meta_value($key)
	{
		if(substr( $key, 0, 5 )===SF_META_PRE)
		{
			return true;
		}
		return false;
	}
	public function is_taxonomy_key($key)
	{
		if(substr( $key, 0, 5 )===SF_TAX_PRE)
		{
			return true;
		}
		return false;
	}
}


if ( ! class_exists( 'Search_Filter_Generate_Input' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'class-search-filter-generate-input.php' );
}

if ( ! class_exists( 'Search_Filter_Display_Results' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'class-search-filter-display-results.php' );
}

