<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Handle_Posts
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2014 Designs & Code
 */

class Search_Filter_Handle_Posts
{
	private $has_form_posted = false;
	private $hasqmark = false;
	private $hassearchquery = false;
	private $urlparams = "/";
	
	private $frmreserved = array(); // a list of all field types that are reserved - good for deducing the field type, from the field name, by subtracting known types in this list...
	//private $frmqreserved = array();
	
	public function __construct($plugin_slug) {

		$this->plugin_slug = $plugin_slug;
		$this->form_settings = "";
		$this->using_custom_template = false;
		$this->using_new_ajax = false;
		$this->page_slug = "";
		
		$this->handle_posted();
	}
	
	/*
	 * Display various inputs
	*/
	//use wp array walker to enable hierarchical display
	public function handle_posted()
	{
		
		$fields = array();
		
		if(isset($_POST[SF_FPRE.'submitted']))
		{
			if($_POST[SF_FPRE.'submitted']==="1")
			{
				//set var to confirm the form was posted
				$this->has_form_posted = true;
				
				//get form id, and check if this form is using a template
				if(isset($_POST[SF_FPRE.'form_id']))
				{
					$lookup_form_id = (int)esc_attr($_POST[SF_FPRE.'form_id']);
					
					$this->form_settings = get_post_meta( $lookup_form_id , '_search-filter-settings' , true );
					
					if((isset($this->form_settings['use_results_shortcode']))&&(isset($this->form_settings['use_ajax_toggle'])))
					{
						if(($this->form_settings['use_results_shortcode']==1)&&($this->form_settings['use_ajax_toggle']==1))
						{
							$this->using_new_ajax = true;
							$this->urlparams = "?action=get_results";
							
							if(isset($_GET['paged']))
							{
								$this->urlparams .= "&paged=".(int)$_GET['paged'];
							}
							
							$this->hasqmark = true;
						}
					}
					else
					{
						$this->form_settings['use_results_shortcode'] = 0;
					}
					
					if(isset($this->form_settings['use_template_manual_toggle']))
					{
						if($this->form_settings['use_template_manual_toggle']==1)
						{
							$this->using_custom_template = true;
						}
					}
					
					if(isset($this->form_settings['use_results_shortcode']))
					{
						if($this->form_settings['use_results_shortcode']==1)
						{//if using shortcode force enable this - so that the righturl is formed
							$this->using_custom_template = true;
						}
					}
					
					if(isset($this->form_settings['page_slug']))
					{
						$this->page_slug = $this->form_settings['page_slug'];
					}
					else
					{
						$this->page_slug = "";
					}
					
					
				}
				
				$fields = get_post_meta( $lookup_form_id , '_search-filter-fields' , true );
				
				$fieldswkeys = array();
				
				foreach ($fields as $field)
				{
					if($field['type']=="post_meta")
					{
						$meta_key = $field['meta_key'];
						if(isset($field['meta_key_manual_toggle']))
						{
							if($field['meta_key_manual_toggle']==1)
							{
								$meta_key = $field['meta_key_manual'];
							}
						}
						$fieldswkeys[SF_META_PRE.$meta_key] = $field; //make fields accessible by key
					}
					else if($field['type']=="taxonomy")
					{
						$taxonomy_name = $field['taxonomy_name'];
						
						$fieldswkeys[SF_TAX_PRE.$taxonomy_name] = $field; //make fields accessible by key
					}
					else if(($field['type']=="tag")||($field['type']=="category"))
					{
						if($this->using_custom_template)
						{//if we're using a custom template, treat tag and cat as normal taxonomies
							$taxonomy_name = $field['taxonomy_name'];
							
							$fieldswkeys[SF_TAX_PRE.$taxonomy_name] = $field; //make fields accessible by key
						}
						else
						{//else make them special ;)
							$fieldswkeys[$field['type']] = $field; //make fields accessible by key
						}
					}
					else
					{
						$fieldswkeys[$field['type']] = $field; //make fields accessible by key
					}
				}
				
				
				$fields = $fieldswkeys;
				
			}
		}
		
		
		
		if(!$this->using_custom_template)
		{
			$this->frmreserved = array(SF_FPRE."category", SF_FPRE."search", SF_FPRE."post_tag", SF_FPRE."submitted", SF_FPRE."post_date", SF_FPRE."post_types", SF_FPRE."sort_order", SF_FPRE."author");
		}
		else
		{//if using custom template tags/categories are treated as taxonomies so don't reserve them
			$this->frmreserved = array(SF_FPRE."search", SF_FPRE."submitted", SF_FPRE."post_date", SF_FPRE."post_types", SF_FPRE."sort_order", SF_FPRE."author");
		}
		
		/* CATEGORIES */
		if(!$this->using_custom_template)
		{
			if((isset($_POST[SF_FPRE.'category']))&&($this->has_form_posted))
			{
				$tfield = "";
				if(isset($fields['category']))
				{
					$tfield = $fields['category'];
				}
				$this->get_category_params($tfield);
			}
		}

		/* SEARCH BOX */
		if((isset($_POST[SF_FPRE.'search']))&&($this->has_form_posted))
		{
			$this->get_search_params();
			
		}
		if(!$this->hassearchquery)
		{
			if((isset($_POST[SF_FPRE.'add_search_param']))&&($this->has_form_posted))
			{//this is only set when a search box is displayed - it tells S&F to append a blank search to the URL to indicate a search has been submitted with no terms, however, still load the search template
				
				if(!$this->hasqmark)
				{
					$this->urlparams .= "?";
					$this->hasqmark = true;
				}
				else
				{
					$this->urlparams .= "&";
				}
				$this->urlparams .= "s=";
			}
		}
		
		/* TAGS */
		if(!$this->using_custom_template)
		{
			if((isset($_POST[SF_FPRE.'post_tag']))&&($this->has_form_posted))
			{
				$tfield = "";
				if(isset($fields['tag']))
				{
					$tfield = $fields['tag'];
				}
				$this->get_tag_params($tfield);
			}
		}
		
		/* POST TYPES */
		if((isset($_POST[SF_FPRE.'post_type']))&&($this->has_form_posted))
		{
			$tfield = "";
			if(isset($fields['post_type']))
			{
				$tfield = $fields['post_type'];
			}
			
			$this->get_post_type_params($tfield);
		}
		
		
		/* POST DATE */
		if((isset($_POST[SF_FPRE.'post_date']))&&($this->has_form_posted))
		{
			$tfield = "";
			if(isset($fields['post_date']))
			{
				$tfield = $fields['post_date'];
			}
			
			$this->get_post_date_params($tfield);
		}
		
		
		/* SORT BY */
		if((isset($_POST[SF_FPRE.'sort_order']))&&($this->has_form_posted))
		{
			$tfield = "";
			if(isset($fields['sort_order']))
			{
				$tfield = $fields['sort_order'];
			}
			
			$this->get_sort_order_params($tfield);
			
		}
		
		/* AUTHOR */
		if((isset($_POST[SF_FPRE.'author']))&&($this->has_form_posted))
		{
			$tfield = "";
			if(isset($fields['author']))
			{
				$tfield = $fields['author'];
			}
			
			$this->get_author_params($tfield);
			
		}
		
		
		//now we have dealt with the all the special case fields - search, tags, categories, post_types, post_date , sort_order, 

		//loop through the $_posts - double check that it is the search form that has been posted, otherwise we could be looping through the posts submitted from an entirely unrelated form
		if($this->has_form_posted)
		{
			
			foreach($_POST as $key=>$val)
			{
			
				if (strpos($key, SF_TAX_PRE) === 0)
				{
					$key = substr($key, strlen(SF_TAX_PRE));
					
					$tfield = $fields[SF_TAX_PRE.$key];
					
					$this->get_taxonomy_params($tfield, $key, $val);
					
				}
				else if (strpos($key, SF_META_PRE) === 0)
				{
					$key = substr($key, strlen(SF_META_PRE));
					
					$tfield = $fields[SF_META_PRE.$key];
					
					$this->get_meta_params($tfield, $key);
				}
			}
		}
		
		
		if((isset($_POST[SF_FPRE.'form_id']))&&($this->has_form_posted))
		{
			if(((!$this->using_custom_template)||($this->page_slug=="")||(!get_option('permalink_structure')))||($this->using_new_ajax==true))
			{
				$form_id = esc_attr($_POST[SF_FPRE.'form_id']);
			
				if(!$this->hasqmark)
				{
					$this->urlparams .= "?";
					$this->hasqmark = true;
				}
				else
				{
					$this->urlparams .= "&";
				}
				$this->urlparams .=  "sfid=".$form_id;
			}
		}
		
		if(isset($_POST[SF_FPRE.'ajax_timestamp']))
		{
			$timestamp = '';
			
			if(is_numeric($_POST[SF_FPRE.'ajax_timestamp']))
			{
				$timestamp = $_POST[SF_FPRE.'ajax_timestamp'];
			}
			
			if($timestamp!="")
			{
				if(!$this->hasqmark)
				{
					$this->urlparams .= "?";
					$this->hasqmark = true;
				}
				else
				{
					$this->urlparams .= "&";
				}
				
				$this->urlparams .=  SF_FPRE."ajax_timestamp=".$timestamp;
				
			}
			
		}
		
		if($this->has_form_posted)
		{//if the search has been posted, redirect to the newly formed url with all the right params
			
			if($this->using_new_ajax==false)
			{
				$home_url = home_url();
			}
			else
			{
				$home_url = admin_url( 'admin-ajax.php' );
			}
			
			if($this->urlparams=="/")
			{//check to see if url params are set, if not ("/") then add "?s=" to force load search results, without this it would redirect to the homepage, which may be a custom page with no blog items/results
				
				$this->urlparams .= "?s=";
			}
			
			if(($this->using_custom_template)&&($this->page_slug!="")&&(get_option('permalink_structure'))&&($this->using_new_ajax==false))
			{
				$redirect_url = (trailingslashit($home_url).$this->page_slug.$this->urlparams);
			}
			else
			{
				$redirect_url = ($home_url.$this->urlparams);
			}
			
			if ( function_exists('icl_get_home_url') )
			{
				global $sitepress;
				$redirect_url = str_replace('&amp;', '&', $sitepress->convert_url( $redirect_url, $sitepress->get_current_language()) );
			}
			
			wp_redirect( $redirect_url ); exit;
		}
	}
	
	private function get_search_params($field = "")
	{
		$this->searchterm = trim(stripslashes($_POST[SF_FPRE.'search']));

		if($this->searchterm!="")
		{
			if(!$this->hasqmark)
			{
				$this->urlparams .= "?";
				$this->hasqmark = true;
			}
			else
			{
				$this->urlparams .= "&";
			}
			$this->urlparams .= "s=".urlencode($this->searchterm);
			$this->hassearchquery = true;
		}
	}
	
	private function get_category_params($field)
	{
		$the_post_cat = ($_POST[SF_FPRE.'category']);

		//make the post an array for easy looping
		if(!is_array($_POST[SF_FPRE.'category']))
		{
			$post_cat[] = $the_post_cat;
		}
		else
		{
			$post_cat = $the_post_cat;
		}
		
		//sanitize
		$post_cat = array_map( 'esc_attr', $post_cat );
		
		$catarr = array();

		foreach ($post_cat as $cat)
		{
			$catobj = get_category($cat);
			
			if(isset($catobj->slug))
			{
				$catarr[] = $catobj->slug;
			}
		}

		if(count($catarr)>0)
		{
			$operator = "+"; //default behaviour
			
			if(isset($field['operator']))
			{
				if($field['operator'] == "or")
				{
					$operator = ","; //default behaviour
				}
			}
			
			$categories = implode($operator,$catarr);
			
			//check to see if permalinks are enabled
			if((get_option('permalink_structure'))&&($this->using_new_ajax==false))
			{//grab the base
				$category_base = (get_option( 'category_base' )=="") ? "category" : get_option( 'category_base' );
				$category_path = $category_base."/".$categories."/";
				$this->urlparams .= $category_path;
			}
			else
			{
				if(!$this->hasqmark)
				{
					$this->urlparams .= "?";
					$this->hasqmark = true;
				}
				else
				{
					$this->urlparams .= "&";
				}
				$this->urlparams .= "category_name=".$categories;
			}
		}
	}
	
	
	private function get_tag_params($field)
	{
		$the_post_tag = ($_POST[SF_FPRE.'post_tag']);

		//make the post an array for easy looping
		if(!is_array($_POST[SF_FPRE.'post_tag']))
		{
			$post_tag[] = $the_post_tag;
		}
		else
		{
			$post_tag = $the_post_tag;
		}
		
		//sanitize
		$post_tag = array_map( 'esc_attr', $post_tag );
		
		$tagarr = array();

		foreach ($post_tag as $tag)
		{
			$tagobj = get_tag($tag);

			if(isset($tagobj->slug))
			{
				$tagarr[] = $tagobj->slug;
			}
		}
		
		if(count($tagarr)>0)
		{
			$operator = "+"; //default behaviour
			
			if(isset($field['operator']))
			{
				if($field['operator'] == "or")
				{
					$operator = ","; //default behaviour
				}
			}
						
			$tags = implode($operator,$tagarr);

			if(!$this->hasqmark)
			{
				$this->urlparams .= "?";
				$this->hasqmark = true;
			}
			else
			{
				$this->urlparams .= "&";
			}
			$this->urlparams .= "tag=".$tags;

		}
	}
	
	private function get_post_type_params($field = "")
	{
		$the_post_types = ($_POST[SF_FPRE.'post_type']);
		
		//make the post an array for easy looping
		if(!is_array($the_post_types))
		{
			$post_types_arr[] = $the_post_types;
		}
		else
		{
			$post_types_arr = $the_post_types;
		}
		
		//sanitize
		$post_types_arr = array_map( 'esc_attr', $post_types_arr );
		
		$num_post_types = count($post_types_arr);
		
		$return_post_type = true;
		if($num_post_types==1)
		{
			if(($post_types_arr[0]=="0")||($post_types_arr[0]==""))
			{
				$return_post_type = false;
			}
		}
		else if($num_post_types==0)
		{
			$return_post_type = false;
		}
		
		if(($num_post_types>0) && ($return_post_type))
		{
			$operator = ","; //default behaviour
			
			$post_types = implode($operator,$post_types_arr);
			
			if(!$this->hasqmark)
			{
				$this->urlparams .= "?";
				$this->hasqmark = true;
			}
			else
			{
				$this->urlparams .= "&";
			}
			
			$this->urlparams .= "post_types=".$post_types;

		}
	}
	
	private function get_post_date_params($field = "")
	{
		
		$the_post_date = ($_POST[SF_FPRE.'post_date']);

		//make the post an array for easy looping
		if(!is_array($the_post_date))
		{
			$post_date_arr[] = $the_post_date;
		}
		else
		{
			$post_date_arr = $the_post_date;
		}
		
		//sanitize
		$post_date_arr = array_map( 'esc_attr', $post_date_arr );

		$post_date_count = count($post_date_arr);
		
		if(is_array($post_date_arr))
		{
			foreach ($post_date_arr as &$a_post_date)
			{
				$a_post_date = (preg_replace("/[^0-9]/","",($a_post_date)));
			}
		}

		if($post_date_count>0)
		{
			$post_date = "";
			
			if($post_date_count==2)
			{//see if there are 2 elements in arr (second date range selector)
			
				if(($post_date_arr[0]!="")&&($post_date_arr[1]==""))
				{
					$post_date = $post_date_arr[0];
				}
				else if($post_date_arr[1]=="")
				{//if second date range is blank then remove the array element - this remove the addition of a '+' by implode below and only use first element
					unset($post_date_arr[1]);
				}
				else if($post_date_arr[0]=="")
				{
					$post_date = "+".$post_date_arr[1];
				}
				else
				{
					$post_date = implode("+",array_filter($post_date_arr));
				}
			}
			else
			{
				$post_date = $post_date_arr[0];
			}
			
			if($post_date!="")
			{
				if(!$this->hasqmark)
				{
					$this->urlparams .= "?";
					$this->hasqmark = true;
				}
				else
				{
					$this->urlparams .= "&";
				}
				$this->urlparams .= "post_date=".$post_date;
			}
		}
	}
	
	
	private function get_taxonomy_params($field = "", $key, $val)
	{
		$the_post_tax = $val;

		//make the post an array for easy looping
		if(!is_array($val))
		{
			$post_tax[] = $the_post_tax;
		}
		else
		{
			$post_tax = $the_post_tax;
		}
		$taxarr = array();

		foreach ($post_tax as $tax)
		{
			$tax = esc_attr($tax);
			$taxobj = get_term_by('id',$tax,$key);

			if(isset($taxobj->slug))
			{
				$taxarr[] = $taxobj->slug;
			}
		}
		
		if(count($taxarr)>0)
		{
			$operator = "+"; //default behaviour
	
			//check to see if an operator has been specified - only applies with fields that use multiple selects such as checkboxes or multi selects
			$operator = "+"; //default behaviour
			
			if(isset($field['operator']))
			{
				if($field['operator'] == "or")
				{
					$operator = ","; //default behaviour
				}
			}
			
			
			$tags = implode($operator,$taxarr);

			if(!$this->hasqmark)
			{
				$this->urlparams .= "?";
				$this->hasqmark = true;
			}
			else
			{
				$this->urlparams .= "&";
			}
			
			if($this->using_custom_template)
			{
				$this->urlparams .=  SF_TAX_PRE.$key."=".$tags;
			}
			else
			{
				$this->urlparams .=  $key."=".$tags;
			}
		}
		
	}
		
	private function get_meta_params($field = "", $key)
	{
		$the_meta_data = ($_POST[SF_META_PRE.$key]);
		
		//make the post an array for easy looping
		if(!is_array($the_meta_data))
		{
			$meta_data_arr = array($the_meta_data);
		}
		else
		{
			$meta_data_arr = $the_meta_data;
		}
		
		//sanitize
		$meta_data_arr = array_map( 'esc_attr', $meta_data_arr );
		
		$num_meta_data = count($meta_data_arr);
		
		if($num_meta_data>0)
		{
			$meta_data = "";
			
			if($field['meta_type']=="number")
			{
				//check to see if any of the meta data have a '+' in, this will signify that it is either a checkbox or a radio that has been submitted
				if ((strpos($meta_data_arr[0],'+') !== false)&&($num_meta_data)>1) {
					//echo 'true';
					//ths is a checkbox with multiple selections
				}
				else
				{//then we are dealing with a straighforward array
					
					if($num_meta_data==2)
					{//see if there are 2 elements in arr (so we have a range)
						
						if(($meta_data_arr[0]!="")&&($meta_data_arr[1]==""))
						{
							$meta_data = $meta_data_arr[0];
						}
						else if($meta_data_arr[1]=="")
						{//if second date range is blank then remove the array element - this remove the addition of a '+' by implode below and only use first element
							
							unset($meta_data_arr[1]);
						}
						else if($meta_data_arr[0]=="")
						{
							
							$meta_data = "0+".$meta_data_arr[1];
						}
						else
						{
							$meta_data = implode("+",($meta_data_arr));
						}
					}
					else
					{
						if (strpos($meta_data_arr[0],'+'))
						{//then we are dealing with a radio or checkbox when one value has been selected, so explode
							$meta_data = $meta_data_arr[0];
						}
						else
						{
							$meta_data = $meta_data_arr[0]."+";
						}
					}
				}
				
			}
			else if($field['meta_type']=="choice")
			{
				$operator = "-+-"; //default behaviour
			
				if(isset($field['operator']))
				{
					if($field['operator'] == "or")
					{
						$operator = "-,-"; //default behaviour
					}
				}
				
				$meta_data_arr = array_map( 'urlencode', $meta_data_arr );
				
				
				$meta_data = implode($operator, $meta_data_arr);
			}
			else if($field['meta_type']=="date")
			{
				
				if(is_array($meta_data_arr))
				{
					foreach ($meta_data_arr as &$a_meta_date)
					{
						$a_meta_date = (preg_replace("/[^0-9]/","",($a_meta_date)));
					}
				}
				
				if($num_meta_data==2)
				{//see if there are 2 elements in arr (second date range selector)
				
					if(($meta_data_arr[0]!="")&&($meta_data_arr[1]==""))
					{
						$meta_data = $meta_data_arr[0];
					}
					else if($meta_data_arr[1]=="")
					{//if second date range is blank then remove the array element - this remove the addition of a '+' by implode below and only use first element
						unset($meta_data_arr[1]);
					}
					else if($meta_data_arr[0]=="")
					{
						$meta_data = "+".$meta_data_arr[1];
					}
					else
					{
						$meta_data = implode("+",array_filter($meta_data_arr));
					}
				}
				else
				{
					$meta_data = $meta_data_arr[0];
				}
				
				
			}
			
			if($meta_data!="")
			{
				if(!$this->hasqmark)
				{
					$this->urlparams .= "?";
					$this->hasqmark = true;
				}
				else
				{
					$this->urlparams .= "&";
				}
				
				$this->urlparams .= SF_META_PRE.$key."=".$meta_data;
			}
		}
		
	}
	private function get_sort_order_params($field = "")
	{
		$sort_order = ($_POST[SF_FPRE.'sort_order']);
		
		
		if(!is_array($sort_order))
		{
			$sort_order = array(esc_attr($sort_order));
		}
		else
		{
			$sort_order = array_map('esc_attr', $sort_order);
		}
		
		
		foreach($sort_order as $a_sort_order)
		{
			if(($a_sort_order!="")&&($a_sort_order!="0"))
			{
				if(!$this->hasqmark)
				{
					$this->urlparams .= "?";
					$this->hasqmark = true;
				}
				else
				{
					$this->urlparams .= "&";
				}
				$this->urlparams .= "sort_order=".($a_sort_order);
			}
		}
		
	}
	
	private function get_author_params($field)
	{
		$author = ($_POST[SF_FPRE.'author']);

		//make the post an array for easy looping
		if(!is_array($_POST[SF_FPRE.'author']))
		{
			$author = array($author);
		}
		
		//sanitize
		$author = array_map( 'esc_attr', $author );
		
		$num_authors = count($author);
		
		$return_author = true;
		if($num_authors==1)
		{
			if($author[0]=="0")
			{
				$return_author = false;
			}
		}
		
		if((count($author)>0) && ($return_author))
		{
			$operator = ","; //default behaviour
									
			$authors = implode($operator,$author);
			
			if(!$this->hasqmark)
			{
				$this->urlparams .= "?";
				$this->hasqmark = true;
			}
			else
			{
				$this->urlparams .= "&";
			}
			$this->urlparams .= "authors=".$authors;

		}
	}
	
	public function is_meta_value($key)
	{
		if(substr( $key, 0, 5 )===SF_META_PRE)
		{
			return true;
		}
		return false;
	}
	
}
