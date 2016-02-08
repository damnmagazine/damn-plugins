<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Setup_Query
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2014 Designs & Code
 */

class Search_Filter_Get_Results
{
	private $has_form_posted = false;
	private $hasqmark = false;
	private $hassearchquery = false;
	private $urlparams = "/";
	private $meta_query_var = array();
	private $rel_query_args = array();
	//private $has_joined = false;
	
	private $frmreserved = array(); // a list of all field types that are reserved - good for deducing the field type, from the field name, by subtracting known types in this list...
	//private $frmqreserved = array();
	
	public function __construct($plugin_slug)
	{
		$this->plugin_slug = $plugin_slug;
		
		/*add_action( 'posts_where', 'startswithaction');
		function startswithaction( $sql ){
			global $wpdb;
			global $wp_query;
			
			
			$startswith = "s";
			echo "HERE";
			if( $startswith ){
				$sql .= $wpdb->prepare( " AND $wpdb->posts.post_title LIKE %s ", $startswith.'%' );
				echo("<li>".$startswith);
			}

			return $sql;
		}*/
		//add_filter('pre_get_posts', array($this, 'filter_query'), 20);
		//add_action( 'pre_get_posts', array($this, 'wpse52480_pre_get_posts'));
		
		
		$this->rel_query_args['post_types'] = array();
		$this->rel_query_args['authors'] = array();
		$this->rel_query_args['taxonomies'] = array();
		$this->rel_query_args['post_date'] = array();
		$this->rel_query_args['post_meta'] = array();
		
		add_filter( 'query_vars', array($this, 'wpse52480_query_vars') );
	}
	
	
	function maintain_search_settings($url) {
		
		$tGET = $_GET;
		unset($tGET['action']);
		unset($tGET['paged']);
		unset($tGET['sfid']);
		
		foreach($tGET as &$get)
		{
			$get = str_replace(" ", "+", $get); //force + signs back in - otherwise WP seems to strip just " "
		}
		
		return add_query_arg($tGET, $url);
	}
	

	function the_results($sfid)
	{
		global $sf_form_data;
				
		$args = "";
		$args = $this->get_query_args($sfid);
		
		$returnvar = "";
		
		add_action('posts_where', array($this, 'filter_meta_query_where'));
		add_action('posts_join' , array($this, 'filter_meta_join'));
		
		// Attach hook to filter WHERE clause.
		//add_filter('posts_where', array($this,'limit_date_range_query'));
		// Remove the filter after it is executed.
		//add_action('posts_selection', array($this,'remove_limit_date_range_query'));
		
		if($sf_form_data->settings("maintain_state")==1)
		{
			add_filter('the_permalink', array($this, 'maintain_search_settings'));
		}
				
		$query = new WP_Query($args);
		
		if($sf_form_data->settings("force_is_search")==1)
		{
			$query->set('is_search', true);
			$query->is_search = true;
		}
		
		//if S is set, use Relevanssi
		//if relevanssi exists & enabled
		//if user has also ticked to enable relevanssi
		
		if($sf_form_data->settings("use_relevanssi")==1)
		{
			if($query->query_vars['s']!="")
			{			
				if (function_exists('relevanssi_do_query'))
				{				
					relevanssi_do_query($query);
				}
			}
		}
		
		
		if(!function_exists('sf_pagination_prev_next'))
		{
			function sf_pagination_prev_next($currentPage, $lastPage, $delim = " | ", $containertag = "", $childtag = "")
			{
				if($lastPage>1)
				{
					$returnHtml = "";
					if($containertag!="")
					{
						$returnHtml .= "<".$containertag.">";
					}
					
					$returnHtml = get_previous_link($currentPage, $lastPage, $childtag).$delim.get_next_link($currentPage, $lastPage, $childtag);
					
					if($containertag!="")
					{
						$returnHtml .= "</".$containertag.">";
					}
					
					echo $returnHtml;
				}
			}
		}
		if(!function_exists('sf_pagination_numbers'))
		{
			function sf_pagination_numbers($currentPage, $totalPage, $delim = " | ", $containertag = "", $childtag = "", $distance = 4)
			{
				if($totalPage>1)
				{
					$returnHtml = "";
					
					$linksArray = Array();
									
					$startPage = ($currentPage < 5)? 1 : $currentPage - 4;
					$endPage = 8 + $startPage;
					$endPage = ($totalPage < $endPage) ? $totalPage : $endPage;
					$diff = $startPage - $endPage + 8;
					$startPage -= ($startPage - $diff > 0) ? $diff : 0;

					if ($startPage > 1)
					{
						array_push ( $linksArray , '<a href="#" class="pagi-first" data-page-number="1">1</a> ...' );
					}
					for($i=$startPage; $i<=$endPage; $i++)
					{
						$addClass = '';
						if($i==$currentPage)
						{
							$addClass = ' sf-active';
						}
						
						array_push ( $linksArray , '<a href="#" class="pagi-num'.$addClass.'" data-page-number="'.$i.'">'.$i.'</a>' );
					}
					
					if ($endPage < $totalPage)
					{
						array_push ( $linksArray , '... <a href="#" class="pagi-last" data-page-number="'.$totalPage.'">'.$totalPage.'</a>' );
					}
					
					$returnHtml .= implode($linksArray, $delim);
					
					echo $returnHtml;
				}
			}
		}
		
		if(!function_exists('get_previous_link'))
		{
			function get_previous_link($currentPage, $lastPage, $containertag = "")
			{
				$returnHtml = "";
				if($containertag!="")
				{
					$returnHtml .= "<".$containertag.">";
				}
				
				$prevLink = 1;
				$addClass = "";
				if($currentPage==1)
				{
					$addClass = " sf-disabled";
				}
				else
				{
					if($currentPage-1>=1)
					{
						$prevLink = $currentPage - 1;
					}
				}
				
				$returnHtml .= '<a href="#" class="pagi-prev'.$addClass.'" data-page-number="'.$prevLink.'">'.__( "Previous", 'search-filter' ).'</a>';
				
				if($containertag!="")
				{
					$returnHtml .= "</".$containertag.">";
				}
				
				return $returnHtml;
			}
		}

		if(!function_exists('get_next_link'))
		{
			function get_next_link($currentPage, $lastPage, $containertag)
			{
				$returnHtml = "";
				if($containertag!="")
				{
					$returnHtml .= "<".$containertag.">";
				}
				
				$addClass = "";
				
				$nextLink = $lastPage;
				$addClass = "";
				if($currentPage==$lastPage)
				{
					$addClass = " sf-disabled";
				}
				else
				{
					if($currentPage+1<=$lastPage)
					{
						$nextLink = $currentPage + 1;
					}
				}
				
				$returnHtml .= '<a href="#" class="pagi-next'.$addClass.'" data-page-number="'.$nextLink.'" >'.__( "Next", 'search-filter' ).'</a>';
				
				if($containertag!="")
				{
					$returnHtml .= "</".$containertag.">";
				}
				
				return $returnHtml;
			}
		}
		
		
		remove_filter( 'posts_where', array($this, 'filter_meta_query_where' ) );
		remove_filter( 'posts_join', array($this, 'filter_meta_join' ) );
		
		//print_r($query->request);
		
		ob_start();
		
		//first check to see if there is a search form that matches the ID of this form
		if ( $overridden_template = locate_template( 'search-filter/'.$sfid.'.php' ) )
		{
			// locate_template() returns path to file
			// if either the child theme or the parent theme have overridden the template
			include($overridden_template);
			
		}
		else
		{
			
			//the check for the default template (results.php)
			
			if ( $overridden_template = locate_template( 'search-filter/results.php' ) )
			{
				// locate_template() returns path to file
				// if either the child theme or the parent theme have overridden the template
				include($overridden_template);
				
			}
			else
			{
				// If neither the child nor parent theme have overridden the template,
				// we load the template from the 'templates' sub-directory of the directory this file is in
				include(plugin_dir_path( SEARCH_FILTER_PRO_BASE_PATH ) . '/templates/results.php');
			}
		}
		
		$returnvar .= ob_get_clean();
		
		wp_reset_postdata();
		
		return $returnvar;
		
		
		
	}
	
	function get_query_args($sfid)
	{
		global $sf_form_data;
		global $wp_query;
		
		$args = array();
		
		$sf_form_data->init($sfid);
		
		//ajax paged value
		$sfpaged = 1;
		if(isset($_GET['paged']))
		{
			$sfpaged = (int)$_GET['paged'];
		}
		
		//regular paged value - normally found when loading the page (non ajax)
		$args['paged'] = get_query_var( 'paged' ) 
		? get_query_var( 'paged' ) 
		: $sfpaged;
		
		//set_query_var( 'paged', $args['paged'] );
		if(is_admin()) //is admin is a great way to test if this is in an ajax call
		{
			global $paged;
			$paged = $sfpaged;
		}
		
		
		$args = $this->filter_settings($args);
		$args = $this->filter_query_search_term($args);
		$args = $this->filter_query_post_types($args);
		$args = $this->filter_query_author($args);
		$args = $this->filter_query_tax_meta($args);
		$args = $this->filter_query_sort_order($args);
		$args = $this->filter_query_post_date($args);
		
		return $args;
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
	
	function filter_settings($args)
	{
		global $sf_form_data;
		
		//posts per page
		$args['posts_per_page'] = $sf_form_data->settings('results_per_page') == "" ? get_option('posts_per_page') : $sf_form_data->settings('results_per_page');
		
		//post status
		if($sf_form_data->settings('post_status')!="")
		{
			$post_status = $sf_form_data->settings('post_status');
			$args['post_status'] = array_map("esc_attr", array_keys($post_status));
			
			$post_types = $sf_form_data->settings('post_types');
			if($post_types!="")
			{
				if(array_key_exists('attachment', $post_types))
				{
					array_push($args['post_status'], "inherit");
				}
			}
			
		}
		
		//exclude post ids
		if($sf_form_data->settings('exclude_post_ids')!="")
		{
			$exclude_post_ids = $sf_form_data->settings('exclude_post_ids');
			$args['post__not_in'] = array_map("intval" , explode(",", $exclude_post_ids));
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
									$args['category__in'] = $this->lang_object_ids(array_map("intval" , explode(",", $val['ids'])), $key);
								}
								else
								{
									$args['category__not_in'] = $this->lang_object_ids(array_map("intval" , explode(",", $val['ids'])), $key);
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
									$args['tag__in'] = $this->lang_object_ids(array_map("intval" , explode(",", $val['ids'])), $key);
								}
								else
								{
									$args['tag__not_in'] = $this->lang_object_ids(array_map("intval" , explode(",", $val['ids'])), $key);
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
								$args['tax_query']['relation'] = "AND";
								
								if($val["include_exclude"]=="include")
								{
									$operator = "IN";
								}
								else
								{
									$operator = 'NOT IN';
								}
								
								$args['tax_query'][] = array(
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
		if(!isset($args['meta_query']))
		{
			$args['meta_query'] = array();
		}
		
		
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
					
					array_push($args['meta_query'], $meta_query);
					
				}
			}
		}
		
		
		return $args;
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
		
		//if(!is_admin())
		//{
			
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
							array_push($meta_val_arr, "sf_meta_$meta_iter.meta_value LIKE '%$value%'");
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
							array_push($meta_val_arr, "sf_meta_$meta_iter.meta_value = '$value'");
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
		//}
		
		return $where;
	}
	
	
	function filter_query_search_term($args)
	{
		global $wp_query;
		global $sf_form_data;
		
		
		if(isset($_GET['_sf_s']))
		{
			$search_term = esc_attr($_GET['_sf_s']);
			$args['s'] = $search_term;	
		}
		
		return $args;
	}
	
	function filter_query_post_types($args)
	{
		global $wp_query;
		global $sf_form_data;
		
		if(isset($_GET['post_types']))
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
						array_push($form_post_types, $key);
					}
				}
			}
			
			$user_post_types = explode(",",esc_attr($_GET['post_types']));
			
			if(isset($user_post_types))
			{
				if(is_array($user_post_types))
				{
					//this means the user has submitted some post types
					foreach($user_post_types as $upt)
					{
						if(in_array($upt, $form_post_types))
						{
							array_push($post_types_filter, $upt);
						}
					}
				}					
			}
			
			$args['post_type'] = $post_types_filter; //here we set the post types that we want WP to search
			
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
						array_push($form_post_types, $key);
					}
				}
			}
			
			$args['post_type'] = $form_post_types;
		}
		return $args;
	}
	
	
	function filter_query_author($args)
	{
		global $wp_query;
		
		if(isset($_GET['authors']))
		{
			
			$authors = explode(",",esc_attr($_GET['authors']));
			foreach ($authors as &$author)
			{
				$author = (int)$author;
			}
			
			$args['author'] = implode(",", $authors); //here we set the post types that we want WP to search
			
			$this->rel_query_args['authors'] = $authors;
		}
		
		return $args;
	}
	
	function filter_query_tax_meta($args)
	{

		global $wp_query;
		global $sf_form_data;
		
		if(!isset($args['tax_query']))
		{
			$args['tax_query'] = array();
		}
		
		if(!isset($args['meta_query']))
		{
			$args['meta_query'] = array();
		}
		
		//apply user filters to meta query
		foreach($_GET as $key=>$val)
		{
			$key = sanitize_text_field($key);
			
			if($this->is_meta_value($key))
			{//handle default filtering of query by meta
				
				array_push($args['meta_query'], $this->filter_query_meta($args, $key));
				
			}
			else if($this->is_taxonomy_key($key))
			{//handle default filtering of taxonomy
				
				array_push($args['tax_query'], $this->filter_query_taxonomy($args, $key));
			
			}
			else
			{
			
			}
		}
		
		if(!empty($args['tax_query']))
		{
			if(count($args['tax_query'])>1)
			{
				$taxonomy_relation = $sf_form_data->settings('taxonomy_relation');
				if($taxonomy_relation!="")
				{
					$args['tax_query']['relation'] = strtoupper($taxonomy_relation);
				}
			}
		}
		
		// Remove the filter after it is executed.
		//add_action('posts_selection', array($this,'remove_meta_query'));
		
		return $args;
	}
	
	function filter_query_taxonomy($args, $key)
	{//only do this if using a custom template
		global $wp_query;
		
		// strip off all "meta_" prefix
		if (strpos($key, SF_TAX_PRE) === 0)
		{
			$key = substr($key, strlen(SF_TAX_PRE));
		}
		
		
		if(isset($_GET[SF_TAX_PRE.$key]))
		{
			
			if (strpos(esc_attr($_GET[SF_TAX_PRE.$key]),',') !== false)
			{
				$operator = "IN";
				$ochar = ",";
				$taxterms = explode($ochar, esc_attr(($_GET[SF_TAX_PRE.$key])));
			}
			else
			{
				$operator = "AND";
				$ochar = "+";
				$taxterms = explode($ochar, esc_attr(urlencode($_GET[SF_TAX_PRE.$key])));
			}
			
			global $sf_form_data;
			$tax_field = $sf_form_data->get_field_by_key(SF_TAX_PRE.$key);
			
			$include_children = false;
			if(isset($tax_field['include_children']))
			{
				if($tax_field['include_children']==1)
				{//incl children must always use "IN" or "NOT IN"
					$include_children = true;
					$operator = "IN";
				}
			}
			
						
			$tax_query = array(
				'taxonomy' => $key,
				'field' => 'slug',
				'terms' => $taxterms,
				'operator'=> $operator,
				'include_children'=> $include_children
				
			);
			
			$this->rel_query_args['taxonomies'][$key] = $taxterms;
			//$this->rel_query_args['taxonomies'][] = $val;
			
			return $tax_query;
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
			{//then we are talking a number range
				
				$meta_data = array("","");
				if(isset($_GET[SF_META_PRE.$key]))
				{
					$meta_data = explode("+", esc_attr(urlencode($_GET[SF_META_PRE.$key])));
					if(count($meta_data)==1)
					{
						$meta_data[1] = "";
					}
				}
				
				$meta_query = array();
				
				if(($meta_data[0]!="")&&($meta_data[1]!=""))
				{
					$minval = intval($meta_data[0]);
					$maxval = intval($meta_data[1]);
					
					//$this->meta_query_var[] = array("key" => $key, "values" => array( $minval, $maxval ), "operator" => "OR", "type" => "number_range");
					
					$meta_query = 
						
						array(
							
							'key' => $key,
							'value'   => array( $minval, $maxval ),
							'type'    => 'numeric',
							'compare' => 'BETWEEN',
						);
					
				}
				
				
			}
			else if($meta_field['meta_type']=="choice")
			{
				
				$meta_data = explode("+", esc_attr(urlencode($_GET[SF_META_PRE.$key])));
				
				if (strpos(esc_attr($_GET[SF_META_PRE.$key]),'-,-') !== false)
				{
					$operator = "OR";
					$ochar = "-,-";
					$meta_data = explode($ochar, esc_attr($_GET[SF_META_PRE.$key]));
				}
				else
				{
					$operator = "AND";
					$ochar = "-+-";
					$meta_data = explode($ochar, esc_attr(urlencode($_GET[SF_META_PRE.$key])));
					$meta_data = array_map( 'urldecode', ($meta_data) );
				}
				
				// check if meta key is serialised...
				$meta_query_arr = array();
				$meta_query_arr['relation'] = 'OR';
				
				if($this->is_meta_type_serialized($key))
				{
					array_push($this->meta_query_var, array("key" => $key, "values" => $meta_data, "operator" => $operator, "type" => "serialized"));
				}
				else
				{
					array_push($this->meta_query_var, array("key" => $key, "values" => $meta_data, "operator" => $operator, "type" => "value"));
				}
				
				$meta_query = array();
			}
			else if($meta_field['meta_type']=="date")
			{
				$meta_data = array("","");
				if(isset($_GET[SF_META_PRE.$key]))
				{
					$meta_data = explode("+", esc_attr(urlencode($_GET[SF_META_PRE.$key])));
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
					
					$meta_query = 
						array(
							'key'     => $key,
							'value'   => array( $minval, $maxval ),
							'compare' => 'BETWEEN',
							"type" => "NUMERIC"
						);
					
					
					
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
					
					$meta_query = array(
						
						
							'key'     => $key,
							'value'   => $val,
							'compare' => '=',
							"type" => "NUMERIC"
						
					);
					
					
				}
			}
		}
		
		if(isset($meta_query))
		{
			return $meta_query;
		}
		
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
			'post_type' => $post_types,
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
	
	function filter_query_sort_order($args)
	{
		global $wp_query;
		
	
		if(isset($_GET['sort_order']))
		{
			$search_all = false;
			
			$sort_order_arr = explode("+",esc_attr(urlencode($_GET['sort_order'])));
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
						
						$args['orderby'] = $sort_by;
						$args['order'] = $sort_order_arr[1];
						$args['meta_key'] = $meta_key;
					}
					else
					{
						$sort_by = $sort_order_arr[0];
						if($sort_by=="id")
						{
							$sort_by = "ID";
						}
						
						$args['orderby'] = $sort_by;
						$args['order'] = $sort_order_arr[1];
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
						
						$args['orderby'] = $sort_by;
						$args['order'] = $sort_dir;
						$args['meta_key'] = $meta_key;
						
					}
					else
					{
						$args['orderby'] = $sort_by;
						$args['order'] = $sort_dir;
					}
				}
			}
		}
	
		
		return $args;
	}
	
	
	

	function filter_query_post_date($args)
	{
		global $wp_query;

		
			if(isset($_GET['post_date']))
			{
				//get post dates into array
				$post_date = explode("+", esc_attr(urlencode($_GET['post_date'])));
				
				if(!empty($post_date))
				{
					global $sf_form_data;
					$post_date_field = $sf_form_data->get_field_by_key('post_date');
					$date_format="m/d/Y";
					
					if(isset($post_date_field['date_format']))
					{
						$date_format = $post_date_field['date_format'];
					}
					
					//if there is more than 1 post date and the dates are not the same
					if (count($post_date) > 1 && $post_date[0] != $post_date[1])
					{
						
						if((!empty($post_date[0]))&&(!empty($post_date[1])))
						{
							
							
							$fromDate = $this->getDateDMY($post_date[0],$date_format);
							$toDate = $this->getDateDMY($post_date[1],$date_format);
							
							$args['date_query'] = array(
								'after' => array(
									'day'   	=> $fromDate['day'],
									'month'     => $fromDate['month'],
									'year'      => $fromDate['year'],
									//'compare'   => '>='
								),
								'before' => array(
									'day'   	=> $toDate['day'],
									'month'     => $toDate['month'],
									'year'      => $toDate['year'],
									//'compare'   => '<='
								),
								'inclusive' => true
							);
						}
					}
					else
					{ //else we are dealing with one date or both dates are the same (so need to find posts for a single day)
						
						
						if (!empty($post_date[0]))
						{
							$theDate = $this->getDateDMY($post_date[0], $date_format);
							
							$args['year'] = $theDate['year'];
							$args['monthnum'] = $theDate['month'];
							$args['day'] = $theDate['day'];
						}
					}
				}
			}
		

		return $args;
	}

	function getDateDMY($date, $date_format)
	{
		if($date_format=="m/d/Y")
		{
			$month = substr($date, 0, 2);
			$day = substr($date, 2, 2);
			$year = substr($date, 4, 4);
		}
		else if($date_format=="d/m/Y")
		{
			$month = substr($date, 2, 2);
			$day = substr($date, 0, 2);
			$year = substr($date, 4, 4);
		}
		else if($date_format=="Y/m/d")
		{

			$month = substr($date, 4, 2);
			$day = substr($date, 6, 2);
			$year = substr($date, 0, 4);
			
		}
		
		$rdate["year"] = $year;
		$rdate["month"] = $month;
		$rdate["day"] = $day;
		
		return $rdate;
	}
	
	/*function remove_meta_query()
	{
		remove_filter( 'posts_where', array($this, 'filter_meta_query_where' ) );
		remove_filter( 'posts_join', array($this, 'filter_meta_join' ) );
	}*/
	
	function remove_limit_date_range_query()
	{
		remove_filter( 'posts_where', array($this, 'limit_date_range_query' ) );
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
	
}
