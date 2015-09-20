<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Generate_Input
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2014 Designs & Code
 */

class Search_Filter_Generate_Input {
	
	public function __construct($plugin_slug) {

		$this->plugin_slug = $plugin_slug;
	}
		
	/*
	 * Display various inputs
	*/
	//use wp array walker to enable hierarchical display and other options
	public function generate_wp_dropdown($args, $name, $labels = null)
	{
		$elem_attr = "";
		$returnvar = "";
		
		if(isset($args['elem_attr']))
		{
			$elem_attr .= $args['elem_attr'];
		}
		
		$returnvar .= '<select name="'.$args['sf_name'].'[]" class="postform"'.$elem_attr.'>';
		$returnvar .= $this->walk_taxonomy('select', $args);
		$returnvar .= "</select>";
		
		return $returnvar;
	}
	
	public function generate_wp_multiselect($args, $name, $labels = null)
	{
		$elem_attr = "";
		if(isset($args['elem_attr']))
		{
			$elem_attr .= $args['elem_attr'];
		}
		
		$returnvar = '<select multiple="multiple" name="'.$args['sf_name'].'[]" class="postform"'.$elem_attr.'>';
		$returnvar .= $this->walk_taxonomy('multiselect', $args);
		$returnvar .= "</select>";
		
		return $returnvar;
	}
	
	public function generate_wp_checkbox($args, $name, $labels = null)
	{
		$elem_attr = "";
		if(isset($args['elem_attr']))
		{
			$elem_attr .= $args['elem_attr'];
		}
		
		$returnvar = '<ul'.$elem_attr.'>';
		$returnvar .= $this->walk_taxonomy('checkbox', $args);
		$returnvar .= "</ul>";
		
		return $returnvar;
	}
	
	public function generate_wp_radio($args, $name, $labels = null)
	{
		$returnvar = '<ul>';
		$returnvar .= $this->walk_taxonomy('radio', $args);
		$returnvar .= "</ul>";
		
		return $returnvar;
	}
	
	//generate generic form inputs for use elsewhere, such as post types and non taxonomy fields
	public function generate_select($dropdata, $name, $defaults, $all_items_label = null, $elem_attr = "")
	{
		$returnvar = "";
		
		$returnvar .= '<select class="postform" name="'.$name.'[]"'.$elem_attr.'>';
		if(isset($all_items_label))
		{
			if($all_items_label!="")
			{//check to see if all items has been registered in field then use this label
				$returnvar .= '<option class="level-0" value="">'.esc_html($all_items_label).'</option>';
			}
		}

		foreach($dropdata as $dropdown)
		{
			$selected = "";
			
			if(isset($defaults))
			{
				if(is_array($defaults))
				{
					foreach($defaults as $defaultid)
					{
						if($defaultid==$dropdown->term_id)
						{
							$selected = ' selected="selected"';
						}
					}
				}
			}
			
			$returnvar .= '<option class="level-0" value="'.esc_attr($dropdown->term_id).'"'.$selected.'>'.esc_html($dropdown->cat_name).'</option>';

		}
		$returnvar .= "</select>";

		return $returnvar;
	}
	public function generate_multiselect($dropdata, $name, $defaults, $elem_attr = "")
	{
		$returnvar = "";

		$returnvar .= '<select multiple="multiple" class="postform" name="'.$name.'[]"'.$elem_attr.'>';
		
		foreach($dropdata as $dropdown)
		{
			$selected = "";

			if(isset($defaults))
			{
				if(is_array($defaults)) //there should never be more than 1 default in a select, if there are then don't set any, user is obviously searching multiple values, in the case of a select this must be "all"
				{
					foreach($defaults as $defaultid)
					{
						if($defaultid==$dropdown->term_id)
						{
							$selected = ' selected="selected"';
						}
					}
				}
			}
			$returnvar .= '<option class="level-0" value="'.esc_attr($dropdown->term_id).'"'.$selected.'>'.esc_html($dropdown->cat_name).'</option>';

		}
		$returnvar .= "</select>";

		return $returnvar;
	}
	
	public function generate_checkbox($dropdata, $name, $defaults, $elem_attr = "")
	{
		$returnvar = '<ul'.$elem_attr.'>';
		
		foreach($dropdata as $dropdown)
		{
			$checked = "";
			
			//check a default has been set
			if(isset($defaults))
			{
				if(is_array($defaults))
				{
					foreach($defaults as $defaultid)
					{
						if($defaultid==$dropdown->term_id)
						{
							$checked = ' checked="checked"';
						}
					}
				}				
			}
			$returnvar .= '<li class="cat-item"><label><input class="postform cat-item" type="checkbox" name="'.$name.'[]" value="'.esc_attr($dropdown->term_id).'"'.$checked.'> '.esc_html($dropdown->cat_name).'</label></li>';
		
		}
		
		$returnvar .= '</ul>';
		
		return $returnvar;
	}
	
	public function generate_radio($dropdata, $name, $defaults, $all_items_label = null)
	{
		$returnvar = '<ul>';
		
		if(isset($all_items_label))
		{
			if($all_items_label!="")
			{
				$checked = "";
				
				if(isset($defaults))
				{
					if(!is_array($defaults))
					{
						if($defaults=="")
						{
							$checked = ' checked="checked"';
						}
					}
				}
				
				$returnvar .= '<li class="cat-item"><label><input class="postform" type="radio" name="'.$name.'[]" value=""'.$checked.'> '.esc_html($all_items_label).'</label></li>';
			}
		}
		
		foreach($dropdata as $dropdown)
		{
			$checked = "";
			
			//check a default has been set
			if(isset($defaults))
			{
				if(is_array($defaults))
				{
					foreach($defaults as $defaultid)
					{
						if($defaultid==$dropdown->term_id)
						{
							$checked = ' checked="checked"';
						}
					}
				}
			}
			$returnvar .= '<li class="cat-item"><label><input class="postform" type="radio" name="'.$name.'[]" value="'.esc_attr($dropdown->term_id).'"'.$checked.'> '.esc_html($dropdown->cat_name).'</label></li>';
		}
		
		$returnvar .= '</ul>';
		
		return $returnvar;
	}
	
	public function generate_date($name, $defaults, $placeholder = "mm/dd/yyyy", $currentid = 0)
	{
		$returnvar = '';
		$current_date = '';
		//check a default has been set - upto two possible vars for array 
		
		if(isset($defaults))
		{
			$noselected = count($defaults);
			
			if(($noselected>0)&&(is_array($defaults)))
			{
				$current_date = $defaults[$currentid];
			}
		}
		$returnvar .= '<input class="postform datepicker" type="text" name="'.$name.'[]" value="' . esc_attr($current_date) . '" placeholder="'.$placeholder.'" />';
		
		return $returnvar;
	}
	
	public function walk_taxonomy( $type = "checkbox", $args = array() )
	{
		$args['walker'] = new Search_Filter_Taxonomy_Walker($type, $args['sf_name']);
		
		$output = wp_list_categories($args);
		if ( $output )
			return $output;
	}
	
	public function walk_author( $type = "checkbox", $args = array() ) {

		$walker = new Search_Filter_Author_Walker($type, $args['name']);
		$args['echo'] = false;
		$output = $walker->wp_authors($type, $args);
		
		if ( $output )
			return $output;
	}
	
	public function generate_range_slider($field, $min, $max, $step, $smin, $smax, $value_prefix = "", $value_postfix = "")
	{
		$returnvar = "";
		
		if($value_prefix!="")
		{
			$value_prefix = $value_prefix." ";
		}
		if($value_postfix!="")
		{
			$value_postfix = " ".$value_postfix;
		}		
		
		if((int)$smax<(int)$smin)
		{
			$smax = $smin;
		}
		
		$smin = (int)$smin;
		if((int)$smax<(int)$smin)
		{
			$smax = $smin;
		}
		$smax = (int)$smax;
		
		$returnvar .= '<div class="meta-range" data-start-min="'.esc_attr($smin).'" data-start-max="'.esc_attr($smax).'" data-min="'.esc_attr($min).'" data-max="'.esc_attr($max).'" data-step="'.esc_attr($step).'">';
		$returnvar .= $value_prefix.'<input name="'.$field.'[]" type="number" min="'.esc_attr($min).'" max="'.esc_attr($max).'" step="'.esc_attr($step).'" class="range-min" value="'.(int)$smin.'" />'.$value_postfix;
		$returnvar .= ' - ';
		$returnvar .= $value_prefix.'<input name="'.$field.'[]" type="number" min="'.esc_attr($min).'" max="'.esc_attr($max).'" step="'.esc_attr($step).'" class="range-max" value="'.(int)$smax.'" />'.$value_postfix;
		$returnvar .= '<div class="meta-slider"></div>';
		$returnvar .= '</div>';
		
		return $returnvar;
	}
	
	public function generate_range_number($field, $min, $max, $step, $smin, $smax, $value_prefix = "", $value_postfix = "")
	{
		$returnvar = "";
		
		if($value_prefix!="")
		{
			$value_prefix = $value_prefix." ";
		}
		if($value_postfix!="")
		{
			$value_postfix = " ".$value_postfix;
		}
		
		$smin = (int)$smin;
		if((int)$smax<(int)$smin)
		{
			$smax = $smin;
		}
		$smax = (int)$smax;
		
		$returnvar .= '<div class="meta-range" data-start-min="'.esc_attr($smin).'" data-start-max="'.esc_attr($smax).'" data-min="'.esc_attr($min).'" data-max="'.esc_attr($max).'" data-step="'.esc_attr($step).'">';
		$returnvar .= $value_prefix.'<input name="'.$field.'[]" type="number" min="'.esc_attr($min).'" max="'.esc_attr($max).'" step="'.esc_attr($step).'" class="range-min" value="'.(int)$smin.'" />'.$value_postfix;
		$returnvar .= ' - ';
		$returnvar .= $value_prefix.'<input name="'.$field.'[]" type="number" min="'.esc_attr($min).'" max="'.esc_attr($max).'" step="'.esc_attr($step).'" class="range-max" value="'.(int)$smax.'" />'.$value_postfix;
		$returnvar .= '</div>';
		
		return $returnvar;
	}
	public function generate_range_radio($field, $min, $max, $step, $default, $value_prefix = "", $value_postfix = "")
	{
		$returnvar = '<ul>';
		
		
		$startval = $min;
		$endval = $max;
		$diff = $endval - $startval;
		$istep = ceil($diff/$step);
		
		
		for($i=0; $i<($istep); $i++)
		{
			$radio_value = $startval + ($i * $step);
			$radio_top_value = ($radio_value + $step - 1);
			
			if($radio_top_value>$endval)
			{
				$radio_top_value = $endval;
			}
			
			$radio_label = $value_prefix.$radio_value.$value_postfix." - ".$value_prefix.$radio_top_value.$value_postfix;
			$radio_value = esc_attr($radio_value).'+'.esc_attr($radio_top_value);
			
			$checked = "";
			if($radio_value == $default)
			{
				$checked = ' checked="checked"';
			}
			$returnvar .= '<li class="cat-item"><label><input class="postform" type="radio" name="'.$field.'[]" value="'.$radio_value.'"'.$checked.'> '.esc_html($radio_label).'</label></li>';
		}
		
		
		$returnvar .= '</ul>';
		
		return $returnvar;
	}
	public function generate_range_select($field, $min, $max, $step, $default, $value_prefix = "", $value_postfix = "")
	{
		
		
		$startval = $min;
		$endval = $max;
		$diff = $endval - $startval;
		$istep = ceil($diff/$step);
		
		$returnvar = "";
		
		$returnvar .= '<select class="postform" name="'.$field.'[]">';
		if(isset($all_items_label))
		{
			if($all_items_label!="")
			{//check to see if all items has been registered in field then use this label
				$returnvar .= '<option class="level-0" value="">'.esc_html($all_items_label).'</option>';
			}
		}
		
		
		for($i=0; $i<($istep); $i++)
		{
			$radio_value = $startval + ($i * $step);
			$radio_top_value = ($radio_value + $step - 1);
			
			if($radio_top_value>$endval)
			{
				$radio_top_value = $endval;
			}
			
			$radio_label = $value_prefix.$radio_value.$value_postfix." - ".$value_prefix.$radio_top_value.$value_postfix;
			$radio_value = esc_attr($radio_value).'+'.esc_attr($radio_top_value);
			
			$selected = "";
			if($radio_value == $default)
			{
				$selected = ' selected="selected"';
			}
			$returnvar .= '<option class="level-0" value="'.esc_attr($radio_value).'"'.$selected.'>'.esc_html($radio_label).'</option>';
		}
		
		
		$returnvar .= "</select>";
		
		return $returnvar;
	}
	
	public function generate_range_checkbox($field, $min, $max, $step, $smin, $smax, $value_prefix = "", $value_postfix = "")
	{
		$returnvar = '<ul>';
		
		if(isset($this->defaults[SF_FPRE.'meta_'.$field]))
		{
			$defaults = $this->defaults[SF_FPRE.'meta_'.$field];
		}
		
		if(isset($defaults[0]))
		{
			$smin = intval($defaults[0]);
		}
		
		if(isset($defaults[1]))
		{
			$smax = intval($defaults[1]);
		}
		
		$startval = $min;
		$endval = $max;
		$diff = $endval - $startval;
		$istep = ceil($diff/$step);
		
		
		for($i=0; $i<($istep); $i++)
		{
			$radio_value = $startval + ($i * $step);
			$radio_top_value = ($radio_value + $step - 1);
			
			if($radio_top_value>$endval)
			{
				$radio_top_value = $endval;
			}
			
			$radio_label = $value_prefix.$radio_value.$value_postfix." - ".$value_prefix.$radio_top_value.$value_postfix;
			$returnvar .= '<li class="cat-item"><label><input class="postform" type="checkbox" name="'.SF_FPRE.'meta_'.$field.'[]" value="'.esc_attr($radio_value).'"> '.esc_html($radio_label).'</label></li>';
		}
		
		
		$returnvar .= '</ul>';
		
		return $returnvar;
	}
	
	
}

if ( ! class_exists( 'Search_Filter_Taxonomy_Walker' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'class-search-filter-taxonomy-walker.php' );
}

if ( ! class_exists( 'Search_Filter_Author_Walker' ) )
{
	require_once( plugin_dir_path( __FILE__ ) . 'class-search-filter-author-walker.php' );
}

