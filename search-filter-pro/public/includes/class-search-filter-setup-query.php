<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Setup_Query
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2014 Designs & Code
 */

class Search_Filter_Setup_Query
{
	private $has_form_posted = false;
	private $hasqmark = false;
	private $hassearchquery = false;
	private $urlparams = "/";
	private $meta_query_var = array();
	private $rel_query_args = array();
	private $tax_query = array();
	private $meta_query = array();
	//private $has_joined = false;
	
	private $frmreserved = array(); // a list of all field types that are reserved - good for deducing the field type, from the field name, by subtracting known types in this list...
	//private $frmqreserved = array();
	
	public function __construct($plugin_slug)
	{
		$this->plugin_slug = $plugin_slug;
		
		add_filter('pre_get_posts', array($this, 'filter_query'), 20);
		
		$this->rel_query_args['post_types'] = array();
		$this->rel_query_args['authors'] = array();
		$this->rel_query_args['taxonomies'] = array();
		$this->rel_query_args['post_date'] = array();
		$this->rel_query_args['post_meta'] = array();
		
	}
	
	function maintain_search_settings($url) {
		
		$tGET = $_GET;
		unset($tGET['action']);
		unset($tGET['paged']);
		unset($tGET['sfid']);
		unset($tGET['lang']);
		
		if(isset($tGET['s']))
		{
			$tGET['_sf_s'] = $tGET['s'];
			unset($tGET['s']);
		}
		foreach($tGET as &$get)
		{
			$get = str_replace(" ", "+", $get); //force + signs back in - otherwise WP seems to strip just " "
		}
		
		return add_query_arg($tGET, $url);
	}
	
	function filter_query($query)
	{
		global $sf_form_data;
		global $wp_query;
		
		if(($query->is_main_query())&&(!is_admin()))
		{
			if(isset($wp_query->query['sfid']))
			{
				$sf_form_data->init($wp_query->query['sfid']);
			}
			
			if($sf_form_data->is_valid_form())
			{
				if($sf_form_data->settings("maintain_state")==1)
				{
					add_filter('the_permalink', array($this, 'maintain_search_settings'));
				}
				
				$query = $this->filter_settings($query);
				$query = $this->filter_query_post_types($query);
				$query = $this->filter_query_author($query);
				$query = $this->filter_query_tax_meta($query);
				$query = $this->filter_query_sort_order($query);
				$query = $this->filter_query_post_date($query);
				
				$query->set('meta_query', $this->meta_query);
				
				
				if($sf_form_data->settings("enable_auto_count")==1)
				{
					$this->term_relationships = new Search_Filter_Relationships($this->plugin_slug);
					$this->term_relationships->init_relationships($this->rel_query_args);
					$sf_form_data->set_count_table($this->term_relationships->get_count_table());
				}
				
				if($sf_form_data->settings("force_is_search")==1)
				{
					$query->set('is_search', true);
					$query->is_search = true;
				}
				
				if($sf_form_data->settings("use_relevanssi")!=1)
				{//removes relevanssi if use has left this option unticked
					remove_filter('the_posts', 'relevanssi_query');
					remove_filter('posts_request', 'relevanssi_prevent_default_request' );
				}
			}
		}
		
		//$query->set('is_search', true);
		
		return $query;
	}
	
	function filter_meta_join($join)
	{
		global $wpdb;
		
		//check to see if wp_postmeta is already joined
		/*if (strpos($join, $wpdb->postmeta) === false)
		{
			$join .= " INNER JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id) ";
		}*/
		
		$meta_iter = 1;
		if(count($this->meta_query_var)>0)
		{
			foreach($this->meta_query_var as $meta_query)
			{
				$join .= " INNER JOIN $wpdb->postmeta as sf_meta_$meta_iter ON ($wpdb->posts.ID =  sf_meta_$meta_iter.post_id)";
				
				$meta_iter++;
			}
		}
		
		return $join;
	}
	
	
	function filter_meta_query_where($where = '')
	{
		global $wpdb; 
		global $wp_query;
		global $sf_form_data;
				
		if(!is_admin())
		{
			if(count($this->meta_query_var)>0)
			{
				$meta_iter = 1;
				
				foreach($this->meta_query_var as $meta_query)
				{
					
					if($meta_query['type']=="serialized")
					{
						$where .= " AND ((sf_meta_$meta_iter.meta_key = '".$meta_query['key']."' AND (";
						
						$meta_val_arr = array();
						
						foreach($meta_query['values'] as $value)
						{
							$meta_val_arr[] = "sf_meta_$meta_iter.meta_value LIKE '%$value%'";
						}
						
						$where .= implode(" ".$meta_query['operator']." ", $meta_val_arr);
						
						$where .= ")))";
						
					}
					else if($meta_query['type']=="value")
					{
						$where .= " AND ((sf_meta_$meta_iter.meta_key = '".$meta_query['key']."' AND (";
						
						$meta_val_arr = array();
						
						foreach($meta_query['values'] as $value)
						{
							$meta_val_arr[] = "sf_meta_$meta_iter.meta_value = '$value'";
						}
						
						$where .= implode(" ".$meta_query['operator']." ", $meta_val_arr);
						
						$where .= ")))";
					}
					else if($meta_query['type']=="number_range")
					{
						$where .= " AND (sf_meta_$meta_iter.meta_key = '".$meta_query['key']."'";
						
						$where .= " AND CAST(sf_meta_$meta_iter.meta_value AS SIGNED) BETWEEN '".$meta_query['values'][0]."' AND '".$meta_query['values'][1]."')";
					}
					
					$meta_iter++;
				}
			}
		}
		
		return $where;
	}
	
	
	
	function filter_query_post_types($query)
	{
		global $wp_query;
		global $sf_form_data;
		
		if(($query->is_main_query())&&(!is_admin()))
		{
			if(isset($wp_query->query['post_types']))
			{
				$post_types_filter = array();
				$form_post_types = array();
				
				$post_types = $sf_form_data->settings('post_types');
				if($post_types)
				{
					if(is_array($post_types))
					{
						foreach ($post_types as $key => $value)
						{
							$form_post_types[] = $key;
						}
					}
				}
				
				$user_post_types = explode(",",esc_attr($wp_query->query['post_types']));
				
				if(isset($user_post_types))
				{
					if(is_array($user_post_types))
					{
						//this means the user has submitted some post types
						foreach($user_post_types as $upt)
						{
							if(in_array($upt, $form_post_types))
							{
								$post_types_filter[] = $upt;
							}
						}
					}					
				}
				
				$query->set('post_type', $post_types_filter); //here we set the post types that we want WP to search
				
				$this->rel_query_args['post_types'] = $post_types_filter;
				
			}
			else
			{
				$form_post_types = array();
				$post_types = $sf_form_data->settings('post_types');
				
				if($post_types)
				{
					if(is_array($post_types))
					{
						foreach ($post_types as $key => $value)
						{
							$form_post_types[] = $key;
						}
					}
				}
				
				$query->set('post_type', $form_post_types); //here we set the post types that we want WP to search
				
				$this->rel_query_args['post_types'] = $form_post_types;
			}
		}
		
		return $query;
	}
	
	
	function filter_query_author($query)
	{
		global $wp_query;
		
		if(($query->is_main_query())&&(!is_admin()))
		{
			if(isset($wp_query->query['authors']))
			{
				$authors = explode(",",esc_attr($wp_query->query['authors']));
				foreach ($authors as &$author)
				{
					$author = (int)$author;
				}
				
				$query->set('author', implode(",", $authors)); //here we set the post types that we want WP to search
				
				$this->rel_query_args['authors'] = $authors;
			}
		}
		
		return $query;
	}
	
	function filter_query_tax_meta($query)
	{
		global $wp_query;
		global $sf_form_data;
		
		if(($query->is_main_query())&&(!is_admin()))
		{
			foreach($wp_query->query as $key=>$val)
			{
				$key = sanitize_text_field($key);
				
				if($this->is_meta_value($key))
				{//handle default filtering of query by meta
					
					$query = $this->filter_query_meta($query, $key);
				}
				else if($this->is_taxonomy_key($key))
				{//handle default filtering of taxonomy
					
					if($sf_form_data->is_using_custom_template())
					{//only update the query for taxonomies when using a custom template, otherwise WP will handle them automatically...
						
						$query = $this->filter_query_taxonomy($query, $key, $this->tax_query);
						
					}
				}
			}
			
			if(!empty($this->tax_query))
			{
				if(count($this->tax_query)>1)
				{
					$taxonomy_relation = $sf_form_data->settings('taxonomy_relation');
					if($taxonomy_relation!="")
					{
						$this->tax_query['relation'] = strtoupper($taxonomy_relation);
					}
				}
				$query->set( 'tax_query', $this->tax_query );
			}
			
			add_filter('posts_where', array($this, 'filter_meta_query_where'));
			add_filter('posts_join' , array($this, 'filter_meta_join'));
			
			// Remove the filter after it is executed.
			add_action('posts_selection', array($this,'remove_meta_query'));
		}
		
		return $query;
	}
	
	function filter_query_taxonomy($query, $key, &$tax_query)
	{//only do this if using a custom template
		global $wp_query;
		
		// strip off all "meta_" prefix
		if (strpos($key, SF_TAX_PRE) === 0)
		{
			$key = substr($key, strlen(SF_TAX_PRE));
		}
		
		if(isset($wp_query->query[SF_TAX_PRE.$key]))
		{
			
			if (strpos(esc_attr($wp_query->query[SF_TAX_PRE.$key]),',') !== false)
			{
				$operator = "IN";
				$ochar = ",";
				$taxterms = explode($ochar, esc_attr(($wp_query->query[SF_TAX_PRE.$key])));
			}
			else
			{
				$operator = "AND";
				$ochar = "+";
				$taxterms = explode($ochar, esc_attr(urlencode($wp_query->query[SF_TAX_PRE.$key])));
			}
			
			
			global $sf_form_data;
			$tax_field = $sf_form_data->get_field_by_key(SF_TAX_PRE.$key);
			
			$include_children = false;
			if($tax_field['include_children']==1)
			{//incl children must always use "IN" or "NOT IN"
				$include_children = true;
				$operator = "IN";
			}
			
			$tax_query[] = array(
				'taxonomy' => $key,
				'field' => 'slug',
				'terms' => $taxterms,
				'operator'=> $operator,
				'include_children'=> $include_children				
			);
			
			$this->rel_query_args['taxonomies'][$key] = $taxterms;
			//$this->rel_query_args['taxonomies'][] = $val;
		}
		
		return $query;
	}
	
	function filter_query_meta($query, $key)
	{
		global $wp_query;
		
		// strip off all "meta_" prefix
		if (strpos($key, SF_META_PRE) === 0)
		{
			$key = substr($key, strlen(SF_META_PRE));
		}
		
		//ensure the remaining key is not blank
		if($key!="")
		{
			
			global $sf_form_data;
			$meta_field = $sf_form_data->get_field_by_key(SF_META_PRE.$key);
			
			if($meta_field['meta_type']=="number")
			{
				
				$meta_data = array("","");
				if(isset($wp_query->query[SF_META_PRE.$key]))
				{
					$meta_data = explode("+", esc_attr(urlencode($wp_query->query[SF_META_PRE.$key])));
					if(count($meta_data)==1)
					{
						$meta_data[1] = "";
					}
				}
				
				if(($meta_data[0]!="")&&($meta_data[1]!=""))
				{
					$minval = intval($meta_data[0]);
					$maxval = intval($meta_data[1]);
					
					$this->meta_query_var[] = array("key" => $key, "values" => array( $minval, $maxval ), "operator" => "OR", "type" => "number_range");
					
				}
			}
			else if($meta_field['meta_type']=="choice")
			{
				
				$meta_data = explode("+", esc_attr(urlencode($wp_query->query[SF_META_PRE.$key])));
				
				if (strpos(esc_attr($wp_query->query[SF_META_PRE.$key]),'-,-') !== false)
				{
					$operator = "OR";
					$ochar = "-,-";
					$meta_data = explode($ochar, esc_attr($wp_query->query[SF_META_PRE.$key]));
				}
				else
				{
					$operator = "AND";
					$ochar = "-+-";
					$meta_data = explode($ochar, esc_attr(urlencode($wp_query->query[SF_META_PRE.$key])));
					$meta_data = array_map( 'urldecode', ($meta_data) );
				}
				
				// check if meta key is serialised...
				$meta_query_arr = array();
				$meta_query_arr['relation'] = 'OR';
				
				if($this->is_meta_type_serialized($key))
				{
					$this->meta_query_var[] = array("key" => $key, "values" => $meta_data, "operator" => $operator, "type" => "serialized");
				}
				else
				{
					$this->meta_query_var[] = array("key" => $key, "values" => $meta_data, "operator" => $operator, "type" => "value");
				}
			}
			else if($meta_field['meta_type']=="date")
			{
				$meta_data = array("","");
				if(isset($wp_query->query[SF_META_PRE.$key]))
				{
					$meta_data = explode("+", esc_attr(urlencode($wp_query->query[SF_META_PRE.$key])));
					if(count($meta_data)==1)
					{
						$meta_data[1] = "";
					}
				}
				
				
				//prep date to match input format:
				
				$date_output_format="m/d/Y";
				$date_input_format="timestamp";
				
				if(isset($meta_field['date_output_format']))
				{
					$date_output_format = $meta_field['date_output_format'];
				}
				if(isset($meta_field['date_input_format']))
				{
					$date_input_format = $meta_field['date_input_format'];
				}
				
				
				if(($meta_data[0]!="")&&($meta_data[1]!=""))
				{
					if($date_input_format=="timestamp")
					{
						$minval = $this->convert_date_to('timestamp', $meta_data[0], $date_output_format);
						$maxval = $this->convert_date_to('timestamp', $meta_data[1], $date_output_format);
					}
					else if($date_input_format=="yyyymmdd")
					{
						$minval = $this->convert_date_to('yyyymmdd', $meta_data[0], $date_output_format);
						$maxval = $this->convert_date_to('yyyymmdd', $meta_data[1], $date_output_format);
					}
					
					array_push($this->meta_query, array(
						
						
							'key'     => $key,
							'value'   => array( $minval, $maxval ),
							'compare' => 'BETWEEN',
							"type" => "NUMERIC"
						
					));					
				}
				else if($meta_data[0]!="")
				{//then its a single date
					
					if($date_input_format=="timestamp")
					{
						$val = $this->convert_date_to('timestamp', $meta_data[0], $date_output_format);
					}
					else if($date_input_format=="yyyymmdd")
					{
						$val = $this->convert_date_to('yyyymmdd', $meta_data[0], $date_output_format);
					}
					
					array_push($this->meta_query, array(
						
						
							'key'     => $key,
							'value'   => $val,
							'compare' => '=',
							"type" => "NUMERIC"
						
					));
				}				
			}			
		}
		
		return $query;
	}
	function convert_date_to($type, $date, $date_output_format)
	{
		if (!empty($date))
		{
			if($date_output_format=="m/d/Y")
			{
				$month = substr($date, 0, 2);
				$day = substr($date, 2, 2);
				$year = substr($date, 4, 4);
			}
			else if($date_output_format=="d/m/Y")
			{
				$month = substr($date, 2, 2);
				$day = substr($date, 0, 2);
				$year = substr($date, 4, 4);
			}
			else if($date_output_format=="Y/m/d")
			{
				$month = substr($date, 4, 2);
				$day = substr($date, 6, 2);
				$year = substr($date, 0, 4);
			}
			
			if($type=="timestamp")
			{
				$date = strtotime($year."-".$month."-".$day);
			}
			else if($type=="yyyymmdd")
			{
				$date = $year.$month.$day;
			}

			//$date_query['after'] = date('Y-m-d 00:00:00', strtotime($date));
		}
		return $date;
	}
	function is_meta_type_serialized($meta_key)
	{
		$post_types = get_post_types( '', 'names' );
		
		$args = array(			
			'meta_query' => array(
				array(
					'key' => $meta_key
				)
			),
			'posts_per_page' => 2,
			'post_type' => $post_types
		);
		
		$arr_count = 0;
		$postslist = get_posts( $args );
		$postlistcount = count($postslist);
		foreach ( $postslist as $post )
		{
			$post_meta = get_post_meta($post->ID, $meta_key, true);
			
			if(is_array($post_meta))
			{
				$arr_count++;
			}
		}
		
		if($postlistcount==$arr_count)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	function filter_query_sort_order($query)
	{
		global $wp_query;
		
		if(($query->is_main_query())&&(!is_admin()))
		{
			if(isset($wp_query->query['sort_order']))
			{
				$search_all = false;
				
				$sort_order_arr = explode("+",esc_attr(urlencode($wp_query->query['sort_order'])));
				$sort_arr_length = count($sort_order_arr);
				
				
				//check both elems in arr exist - field name [0] and direction [1]
				if($sort_arr_length>=2)
				{
					$sort_order_arr[1] = strtoupper($sort_order_arr[1]);
					if(($sort_order_arr[1]=="ASC")||($sort_order_arr[1]=="DESC"))
					{
						
						if($this->is_meta_value($sort_order_arr[0]))
						{
							$sort_by = "meta_value";
							if(isset($sort_order_arr[2]))
							{
								if($sort_order_arr[2]=="num")
								{
									$sort_by = "meta_value_num";
								}
							}
							$meta_key = substr($sort_order_arr[0], strlen(SF_META_PRE));
							
							$query->set('orderby', $sort_by);
							$query->set('order', $sort_order_arr[1]);
							$query->set('meta_key', $meta_key);
						}
						else
						{
							$sort_by = $sort_order_arr[0];
							if($sort_by=="id")
							{
								$sort_by = "ID";
							}
							
							$query->set('orderby', $sort_by);
							$query->set('order', $sort_order_arr[1]);
						}
					}
				}
			}
			else
			{
				global $sf_form_data;
				$sort_by = $sf_form_data->settings('default_sort_by');
				$sort_dir = $sf_form_data->settings('default_sort_dir');
				$meta_key = $sf_form_data->settings('default_meta_key');
				$sort_type = $sf_form_data->settings('default_sort_type');
				
				if(isset($sort_by))
				{
					if($sort_by!="0")
					{
						if($sort_by=="meta_value")
						{
							if(isset($sort_type))
							{
								if($sort_type=="numeric")
								{
									$sort_by = "meta_value_num";
								}
							}
							
							$query->set('orderby', $sort_by);
							$query->set('order', $sort_dir);
							$query->set('meta_key', $meta_key);
						}
						else
						{
							$query->set('orderby', $sort_by);
							$query->set('order', $sort_dir);
						}
					}
				}
			}
		}
		
		return $query;
	}
	
	
	function limit_date_range_query( $where )
	{
		global $wp_query;
	
		//get post dates into array
		if(isset($wp_query->query['post_date']))
		{
			$post_date = explode("+", esc_attr(urlencode($wp_query->query['post_date'])));
				
			if (count($post_date) > 1 && $post_date[0] != $post_date[1])
			{
				$date_query = array();
				
				global $sf_form_data;
				$post_date_field = $sf_form_data->get_field_by_key('post_date');
				$date_format="m/d/Y";
				
				if(isset($post_date_field['date_format']))
				{
					$date_format = $post_date_field['date_format'];
				}
				
				if (!empty($post_date[0]))
				{
					
					if($date_format=="m/d/Y")
					{
						$month = substr($post_date[0], 0, 2);
						$day = substr($post_date[0], 2, 2);
						$year = substr($post_date[0], 4, 4);
					}
					else if($date_format=="d/m/Y")
					{
						$month = substr($post_date[0], 2, 2);
						$day = substr($post_date[0], 0, 2);
						$year = substr($post_date[0], 4, 4);
					}
					else if($date_format=="Y/m/d")
					{
						$month = substr($post_date[0], 4, 2);
						$day = substr($post_date[0], 6, 2);
						$year = substr($post_date[0], 0, 4);
					}
					
					$date = $year."-".$month."-".$day;
					
					$date_query['after'] = date('Y-m-d 00:00:00', strtotime($date));
					
				}
				
				if (!empty($post_date[1]))
				{
					
					if($date_format=="m/d/Y")
					{
						$month = substr($post_date[1], 0, 2);
						$day = substr($post_date[1], 2, 2);
						$year = substr($post_date[1], 4, 4);
					}
					else if($date_format=="d/m/Y")
					{
						$month = substr($post_date[1], 2, 2);
						$day = substr($post_date[1], 0, 2);
						$year = substr($post_date[1], 4, 4);
					}
					else if($date_format=="Y/m/d")
					{
						$month = substr($post_date[1], 4, 2);
						$day = substr($post_date[1], 6, 2);
						$year = substr($post_date[1], 0, 4);
					}
					
					$date = $year."-".$month."-".$day;
					
					
					$date_query['before'] = date('Y-m-d 23:59:59', strtotime($date));
				}
				
			}
					
			// Append fragment to WHERE clause to select posts newer than the past week.
			$where .= " AND post_date >='" . $date_query['after'] . "' AND post_date <='" . $date_query['before'] . "'";
		}
		return $where;
	}
	

	function filter_query_post_date($query)
	{
		global $wp_query;

		if(($query->is_main_query())&&(!is_admin()))
		{
			if(isset($wp_query->query['post_date']))
			{
				//get post dates into array
				$post_date = explode("+", esc_attr(urlencode($wp_query->query['post_date'])));
				
				if(!empty($post_date))
				{
					//if there is more than 1 post date and the dates are not the same
					if (count($post_date) > 1 && $post_date[0] != $post_date[1])
					{
						if((!empty($post_date[0]))&&(!empty($post_date[1])))
						{
							// Attach hook to filter WHERE clause.
							add_filter('posts_where', array($this,'limit_date_range_query'));
							
							// Remove the filter after it is executed.
							add_action('posts_selection', array($this,'remove_limit_date_range_query'));
						}
					}
					else
					{ //else we are dealing with one date or both dates are the same (so need to find posts for a single day)
						global $sf_form_data;
						$post_date_field = $sf_form_data->get_field_by_key('post_date');
						$date_format="m/d/Y";
						
						if(isset($post_date_field['date_format']))
						{
							$date_format = $post_date_field['date_format'];
						}
						
						if (!empty($post_date[0]))
						{
							if($date_format=="m/d/Y")
							{
								$month = substr($post_date[0], 0, 2);
								$day = substr($post_date[0], 2, 2);
								$year = substr($post_date[0], 4, 4);
							}
							else if($date_format=="d/m/Y")
							{
								$month = substr($post_date[0], 2, 2);
								$day = substr($post_date[0], 0, 2);
								$year = substr($post_date[0], 4, 4);
							}
							else if($date_format=="Y/m/d")
							{
								$month = substr($post_date[0], 4, 2);
								$day = substr($post_date[0], 6, 2);
								$year = substr($post_date[0], 0, 4);
							}
							
							$date = $year."-".$month."-".$day;
							
							$post_time = strtotime($date);
							$query->set('year', date('Y', $post_time));
							$query->set('monthnum', date('m', $post_time));
							$query->set('day', date('d', $post_time));
						}
					}
				}
			}
		}

		return $query;
	}


	/**
	 * Remove the filter limiting posts to the past week.
	 *
	 * Remove the filter after it runs so that it doesn't affect any other
	 * queries that might be performed on the same page (eg. Recent Posts
	 * widget).
	 */
	function remove_limit_date_range_query()
	{
		remove_filter( 'posts_where', array($this, 'limit_date_range_query' ) );
	}
	function remove_meta_query()
	{
		remove_filter( 'posts_where', array($this, 'filter_meta_query_where' ) );
		remove_filter( 'posts_join', array($this, 'filter_meta_join' ) );
	}
	
	/*
	 * Display various inputs
	*/
	//use wp array walker to enable hierarchical display
	public function handle_posted()
	{
		
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
	
	function lang_object_ids($ids_array, $type)
	{
		if(function_exists('icl_object_id'))
		{
			$res = array();
			foreach ($ids_array as $id)
			{
				$xlat = icl_object_id($id,$type,false);
				if(!is_null($xlat)) $res[] = $xlat;
			}
			return $res;
		}
		else
		{
			return $ids_array;
		}
	}
	
	
	
	/**********************************************/
	public function filter_settings($query)
	{
		global $sf_form_data;
		
		//posts per page
		$posts_per_page = $sf_form_data->settings('results_per_page') == "" ? get_option('posts_per_page') : $sf_form_data->settings('results_per_page');
		$query->set('posts_per_page', $posts_per_page);
		
		//post status
		if($sf_form_data->settings('post_status')!="")
		{
			$post_status = $sf_form_data->settings('post_status');
			
			
			$post_types = $sf_form_data->settings('post_types');
			if($post_types!="")
			{
				
				if(array_key_exists('attachment', $post_types))
				{
					$post_status['inherit'] = 1;
				}
			}			
			
			$query->set('post_status', array_map("esc_attr", array_keys($post_status)));
			
		}
		
		//exclude post ids
		if($sf_form_data->settings('exclude_post_ids')!="")
		{
			$exclude_post_ids = $sf_form_data->settings('exclude_post_ids');
			$query->set('post__not_in', array_map("intval" , explode(",", $exclude_post_ids)));
		}
		
		//include/exclude taxonomies
		if($sf_form_data->settings('taxonomies_settings')!="")
		{
			if(is_array($sf_form_data->settings('taxonomies_settings')))
			{
				foreach ($sf_form_data->settings('taxonomies_settings') as $key => $val)
				{
					
					if($key == "category")
					{
						if(isset($val['ids']))
						{
							if($val['ids']!="")
							{
								if($val["include_exclude"]=="include")
								{
									$query->set('category__in', $this->lang_object_ids(array_map("intval" , explode(",", $val['ids'])), $key));
								}
								else
								{
									$query->set('category__not_in', $this->lang_object_ids(array_map("intval" , explode(",", $val['ids'])), $key));
								}
							}
						}
					}
					else if($key=="post_tag")
					{
						if(isset($val['ids']))
						{
							if($val['ids']!="")
							{
								if($val["include_exclude"]=="include")
								{
									$query->set('tag__in', $this->lang_object_ids(array_map("intval" , explode(",", $val['ids'])), $key));
								}
								else
								{
									$query->set('tag__not_in', $this->lang_object_ids(array_map("intval" , explode(",", $val['ids'])), $key));
								}
							}
						}
					}
					else
					{//taxonomy
						if(isset($val['ids']))
						{
							if($val['ids']!="")
							{
								$this->tax_query['relation'] = "AND";
								
								if($val["include_exclude"]=="include")
								{
									$operator = "IN";
								}
								else
								{
									$operator = 'NOT IN';
								}
								
								$this->tax_query[] = array(
									'taxonomy' => $key,
									'field'    => 'id',
									'terms'    => $this->lang_object_ids(array_map("intval" , explode(",", $val['ids'])), $key),
									'operator' => $operator
								);
							}
						}	
					}
					
				}
			}
		}
		
		//meta queries
		/*if(!isset($args['meta_query']))
		{
			$args['meta_query'] = array();
		}*/
		
		
		if($sf_form_data->settings('settings_post_meta')!="")
		{
			//$args['meta_query']
			if(is_array($sf_form_data->settings('settings_post_meta')))
			{
				foreach($sf_form_data->settings('settings_post_meta') as $post_meta)
				{					
					$compare_val = "";
					if($post_meta['meta_compare']=="e")
					{
						$compare_val = "=";
					}
					else if($post_meta['meta_compare']=="ne")
					{
						$compare_val = "!=";
					}
					else if($post_meta['meta_compare']=="lt")
					{
						$compare_val = "<";
					}
					else if($post_meta['meta_compare']=="gt")
					{
						$compare_val = ">";
					}
					else if($post_meta['meta_compare']=="lte")
					{
						$compare_val = "<=";
					}
					else if($post_meta['meta_compare']=="gte")
					{
						$compare_val = ">=";
					}
					else
					{
						$compare_val = $post_meta['meta_compare'];
					}
					
					
					
					if($post_meta['meta_type']=="DATE")
					{
						if($post_meta['meta_date_value_current_date']==1)
						{
							$meta_query = array(
								
								'key'		=> $post_meta['meta_key'],
								'value'		=> date( 'Ymd' ),
								'type'		=> $post_meta['meta_type'],
								'compare'	=> $compare_val
							);
						}
						else
						{
							$meta_query = array(
								
								'key'		=> $post_meta['meta_key'],
								//'value'		=> $post_meta['meta_date_value_year'].$post_meta['meta_date_value_month'].$post_meta['meta_date_value_day'],
								'value'		=> $post_meta['meta_date_value_date'],
								'type'		=> $post_meta['meta_type'],
								'compare'	=> $compare_val
							);
						}
					}
					else if($post_meta['meta_type']=="TIMESTAMP")
					{
						if($post_meta['meta_date_value_current_timestamp']==1)
						{
							$meta_query = array(
								
								'key'		=> $post_meta['meta_key'],
								'value'		=> current_time('timestamp'),
								'type'		=> "NUMERIC",
								'compare'	=> $compare_val
							);
						}
						else
						{
							$meta_query = array(
								
								'key'		=> $post_meta['meta_key'],
								'value'		=> $post_meta['meta_date_value_timestamp'],
								'type'		=> "NUMERIC",
								'compare'	=> $compare_val
							);
						}
					}
					else
					{
						$meta_query = array(
							
							'key'		=> $post_meta['meta_key'],
							'value'		=> $post_meta['meta_value'],
							'type'		=> $post_meta['meta_type'],
							'compare'	=> $compare_val
						);
					}
					
					
					//we don't want to pass the value when checking if a field exists or not
					if(($compare_val=="EXISTS")||($compare_val=="NOT EXISTS"))
					{
						unset($meta_query['value']);
						unset($meta_query['type']);
						
					}
					
					array_push($this->meta_query, $meta_query);
					
				}
			}
		}
		
		return $query;
	}
	
}
