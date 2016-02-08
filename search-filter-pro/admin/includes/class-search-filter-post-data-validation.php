<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Post_Data_Validation
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
class Search_Filter_Post_Data_Validation {
	
	public function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		 
		$plugin = Search_Filter::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
				
	}
	
	public function get_clean_widget_data($widget_data)
	{
		$clean_widget = array();
				
		if($widget_data['type']=="search")
		{
			$clean_widget = $this->clean_search_widget($widget_data);
		}
		else if(($widget_data['type']=="tag")||($widget_data['type']=="category")||($widget_data['type']=="taxonomy"))
		{
			$clean_widget = $this->clean_taxonomy_widget($widget_data);
		}
		else if($widget_data['type']=="post_type")
		{
			$clean_widget = $this->clean_post_type_widget($widget_data);
		}
		else if($widget_data['type']=="post_date")
		{
			$clean_widget = $this->clean_post_date_widget($widget_data);
		}
		else if($widget_data['type']=="author")
		{
			$clean_widget = $this->clean_author_widget($widget_data);
		}
		else if($widget_data['type']=="post_meta")
		{
			$clean_widget = $this->clean_post_meta_widget($widget_data);
		}
		else if($widget_data['type']=="sort_order")
		{
			$clean_widget = $this->clean_sort_order_widget($widget_data);
		}
		else if($widget_data['type']=="submit")
		{
			$clean_widget = $this->clean_submit_widget($widget_data);
		}
		else if($widget_data['type']=="reset")
		{
			$clean_widget = $this->clean_reset_widget($widget_data);
		}
		
		return $clean_widget;
		
	}
	
	private function clean_search_widget($widget_data)
	{
		$clean_widget = array();
		$clean_widget['type'] = sanitize_text_field($widget_data['type']);
		$clean_widget['heading'] = sanitize_text_field($widget_data['heading']);
		$clean_widget['placeholder'] = sanitize_text_field($widget_data['placeholder']);
		
		return $clean_widget;
	}
	private function clean_taxonomy_widget($widget_data)
	{
		$clean_widget = array();
		
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
			'sync_include_exclude'	=> '',
			'combo_box'				=> ''
		);
		
		
		$widget_data = array_replace($defaults, $widget_data);
		
		$clean_widget['type'] = sanitize_text_field($widget_data['type']);
		
		$clean_widget['input_type'] = sanitize_key($widget_data['input_type']);
		
		$clean_widget['heading'] = sanitize_text_field($widget_data['heading']);
		$clean_widget['all_items_label'] = sanitize_text_field($widget_data['all_items_label']);
		
		$clean_widget['show_count'] = $this->sanitize_checkbox($widget_data['show_count']);
		$clean_widget['hide_empty'] = $this->sanitize_checkbox($widget_data['hide_empty']);
		$clean_widget['hierarchical'] = $this->sanitize_checkbox($widget_data['hierarchical']);
		$clean_widget['include_children'] = $this->sanitize_checkbox($widget_data['include_children']);
		$clean_widget['drill_down'] = $this->sanitize_checkbox($widget_data['drill_down']);
		$clean_widget['sync_include_exclude'] = $this->sanitize_checkbox($widget_data['sync_include_exclude']);
		
		$clean_widget['combo_box'] = $this->sanitize_checkbox($widget_data['combo_box']);
		
		$clean_widget['operator'] = sanitize_key($widget_data['operator']);
		$clean_widget['order_by'] = sanitize_key($widget_data['order_by']);
		$clean_widget['order_dir'] = sanitize_key($widget_data['order_dir']);
		
		$clean_widget['exclude_ids'] = $this->clean_exclude_ids($widget_data['exclude_ids']);
		
		
		//if($widget_data['type']=="taxonomy")
		//{
			
			$clean_widget['taxonomy_name'] = sanitize_text_field($widget_data['taxonomy_name']);
			
		//}
		
		return $clean_widget;
	}
	
	private function clean_post_type_widget($widget_data)
	{
		$clean_widget = array();
		
		$defaults = array(
			'post_types'			=> array(),
			'input_type'			=> '',
			'heading'				=> '',
			'all_items_label'		=> '',
			'show_count'			=> '',
			'hide_empty'			=> '',
			'order_by'				=> '',
			'order_dir'				=> '',
			'combo_box'				=> ''
		);		
		
		$widget_data = array_replace($defaults, $widget_data);
		
		foreach($widget_data['post_types'] as $key => $val)
		{
			$clean_widget['post_types'][$key] = $this->sanitize_checkbox($val);
		}
		
		$clean_widget['type'] = sanitize_text_field($widget_data['type']);
		
		$clean_widget['input_type'] = sanitize_key($widget_data['input_type']);
		
		$clean_widget['heading'] = sanitize_text_field($widget_data['heading']);
		$clean_widget['all_items_label'] = sanitize_text_field($widget_data['all_items_label']);
		
		$clean_widget['combo_box'] = $this->sanitize_checkbox($widget_data['combo_box']);
		
		//$clean_widget['show_count'] = $this->sanitize_checkbox($widget_data['show_count']);
		//$clean_widget['hide_empty'] = $this->sanitize_checkbox($widget_data['hide_empty']);
		
		
		//$clean_widget['order_by'] = sanitize_key($widget_data['order_by']);
		//$clean_widget['order_dir'] = sanitize_key($widget_data['order_dir']);
		
		
		return $clean_widget;
	}
	
	private function clean_post_date_widget($widget_data)
	{
		$clean_widget = array();
		
		$defaults = array(
			'input_type'			=> '',
			'heading'				=> '',
			'date_format'			=> ''
			
		);

		$widget_data = array_replace($defaults, $widget_data);
		
		$clean_widget['type'] = sanitize_text_field($widget_data['type']);
		
		$clean_widget['input_type'] = sanitize_key($widget_data['input_type']);
		$clean_widget['heading'] = sanitize_text_field($widget_data['heading']);
		$clean_widget['date_format'] = sanitize_text_field($widget_data['date_format']);
		
		return $clean_widget;
	}
	
	private function clean_author_widget($widget_data)
	{
		$clean_widget = array();
		
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
			'combo_box'					=> ''
		);
				
		$widget_data = array_replace($defaults, $widget_data);
		
		$clean_widget['type'] = sanitize_text_field($widget_data['type']);
		
		$clean_widget['input_type'] = sanitize_key($widget_data['input_type']);
		
		$clean_widget['heading'] = sanitize_text_field($widget_data['heading']);
		$clean_widget['all_items_label'] = sanitize_text_field($widget_data['all_items_label']);
		
		$clean_widget['optioncount'] = $this->sanitize_checkbox($widget_data['optioncount']);
		$clean_widget['exclude_admin'] = $this->sanitize_checkbox($widget_data['exclude_admin']);
		$clean_widget['show_fullname'] = $this->sanitize_checkbox($widget_data['show_fullname']);
		$clean_widget['hide_empty'] = $this->sanitize_checkbox($widget_data['hide_empty']);
		$clean_widget['combo_box'] = $this->sanitize_checkbox($widget_data['combo_box']);
		
		$clean_widget['operator'] = sanitize_key($widget_data['operator']);
		$clean_widget['order_by'] = sanitize_key($widget_data['order_by']);
		$clean_widget['order_dir'] = sanitize_key($widget_data['order_dir']);
		
		$clean_widget['exclude'] = $this->clean_exclude_ids($widget_data['exclude']);
		
		return $clean_widget;
	}
	
	private function clean_post_meta_widget($widget_data)
	{
		$clean_widget = array();
		
		$defaults = array(
		
			'heading'					=> '',
			'input_type'				=> '',
			
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
			
			'operator'					=> '',
			'all_items_label'			=> '',
			
			'meta_options'				=> array()
		);
		
		$widget_data = array_replace($defaults, $widget_data);
		
		$clean_widget['type'] = sanitize_text_field($widget_data['type']);
		
		$clean_widget['meta_type'] = sanitize_key($widget_data['meta_type']);
		
		$clean_widget['number_input_type'] = sanitize_key($widget_data['number_input_type']);
		$clean_widget['choice_input_type'] = sanitize_key($widget_data['choice_input_type']);
		$clean_widget['date_input_type'] = sanitize_key($widget_data['date_input_type']);
		
		$clean_widget['meta_key'] = sanitize_text_field($widget_data['meta_key']);
		$clean_widget['meta_key_manual'] = sanitize_text_field($widget_data['meta_key_manual']);
		
		$clean_widget['meta_key_manual_toggle'] = $this->sanitize_checkbox($widget_data['meta_key_manual_toggle']);
		$clean_widget['combo_box'] = $this->sanitize_checkbox($widget_data['combo_box']);
		
		$clean_widget['input_type'] = sanitize_key($widget_data['input_type']);
		
		$clean_widget['heading'] = sanitize_text_field($widget_data['heading']);
		
		$clean_widget['range_min'] = (int)$widget_data['range_min'];
		$clean_widget['range_max'] = (int)$widget_data['range_max'];
		$clean_widget['range_step'] = (int)$widget_data['range_step'];
		
		$clean_widget['range_value_prefix'] = sanitize_text_field($widget_data['range_value_prefix']);
		$clean_widget['range_value_postfix'] = sanitize_text_field($widget_data['range_value_postfix']);
		
		$clean_widget['date_input_format'] = sanitize_text_field($widget_data['date_input_format']);
		$clean_widget['date_output_format'] = sanitize_text_field($widget_data['date_output_format']);
		
		$clean_widget['all_items_label'] = sanitize_text_field($widget_data['all_items_label']);
		$clean_widget['operator'] = sanitize_key($widget_data['operator']);
		
		$clean_widget['meta_options'] = array();
		$so_count = 0;
		
		if(isset($widget_data['meta_options']))
		{
			foreach($widget_data['meta_options'] as $meta_option)
			{
				
				$clean_widget['meta_options'][$so_count] = array();
				
				foreach($meta_option as $key=>$val)
				{
					
					if($key=='option_value')
					{
						$clean_widget['meta_options'][$so_count][$key] = sanitize_text_field($val);
					}
					else if($key=='option_label')
					{
						$clean_widget['meta_options'][$so_count][$key] = sanitize_text_field($val);
					}
					
				}
				
				$so_count++;
			}
		}
		
		return $clean_widget;
	}
	
	private function clean_sort_order_widget($widget_data)
	{
		$clean_widget = array();
		
		$defaults = array(
			/*'meta_key'					=> '',
			'meta_key_manual'			=> '',
			'meta_key_manual_toggle'	=> '',*/
			/*'sort_by'					=> '',
			'sort_dir'					=> '',
			'sort_label'				=> '',*/
			'input_type'				=> '',
			'heading'					=> '',
			'all_items_label'			=> '',
			'sort_options'				=> array()
		);
		
		$widget_data = array_replace($defaults, $widget_data);
		
		$clean_widget['type'] = sanitize_text_field($widget_data['type']);
		
		//$clean_widget['meta_key'] = sanitize_key($widget_data['meta_key']);
		//$clean_widget['meta_key_manual'] = sanitize_key($widget_data['meta_key_manual']);
		//$clean_widget['meta_key_manual_toggle'] = $this->sanitize_checkbox($widget_data['meta_key_manual_toggle']);
		
		$clean_widget['input_type'] = sanitize_key($widget_data['input_type']);
		
		$clean_widget['heading'] = sanitize_text_field($widget_data['heading']);
		$clean_widget['all_items_label'] = sanitize_text_field($widget_data['all_items_label']);
		
		$clean_widget['sort_options'] = array();
		$so_count = 0;
		
		if(isset($widget_data['sort_options']))
		{
			foreach($widget_data['sort_options'] as $sort_option)
			{				
				$clean_widget['sort_options'][$so_count] = array();
				
				foreach($sort_option as $key=>$val)
				{
					
					if($key=='meta_key')
					{
						$clean_widget['sort_options'][$so_count][$key] = sanitize_text_field($val);
					}
					else if($key=='name')
					{
						$clean_widget['sort_options'][$so_count][$key] = sanitize_text_field($val);
					}
					else if($key=='sort_type')
					{
						$clean_widget['sort_options'][$so_count][$key] = sanitize_key($val);
					}
					else if($key=='sort_by')
					{
						$clean_widget['sort_options'][$so_count][$key] = sanitize_key($val);
					}
					else if($key=='sort_dir')
					{
						$clean_widget['sort_options'][$so_count][$key] = sanitize_key($val);
					}
					else if($key=='sort_label')
					{
						$clean_widget['sort_options'][$so_count][$key] = sanitize_text_field($val);
					}
				}
				
				$so_count++;
			}
			
		}
		
		return $clean_widget;
	}
	
	private function clean_submit_widget($widget_data)
	{
		$clean_widget = array();
		
		$defaults = array(
			'heading'					=> '',
			'label'						=> 'Submit'
		);
		
		$widget_data = array_replace($defaults, $widget_data);
		
		
		$clean_widget['type'] = sanitize_text_field($widget_data['type']);
		$clean_widget['heading'] = sanitize_text_field($widget_data['heading']);
		
		$clean_widget['label'] = sanitize_text_field($widget_data['label']);
		
		return $clean_widget;
	}
	
	private function clean_reset_widget($widget_data)
	{
		$clean_widget = array();
		
		$defaults = array(
			'heading'					=> '',
			'label'						=> 'Reset',
			'input_type'				=> 'link'
		);
		
		$widget_data = array_replace($defaults, $widget_data);
		
		
		$clean_widget['type'] = sanitize_text_field($widget_data['type']);
		$clean_widget['heading'] = sanitize_text_field($widget_data['heading']);
		$clean_widget['input_type'] = sanitize_key($widget_data['input_type']);
		$clean_widget['label'] = sanitize_text_field($widget_data['label']);
		
		return $clean_widget;
	}
	
	
	//utility functions
	
	public function sanitize_checkbox($value)
	{
		if($value!="")
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	public function clean_exclude_ids($exclude_ids)
	{
		return implode(',', $this->arrayToInt(array_map('trim',explode(",", $exclude_ids))));
	}
	
	
	private function arrayToInt(array $arr)
	{
		foreach ($arr as &$a) {
			
			$a = trim($a);
			if($a!="")
			{
				$a = (int)$a;
			}
		}
		
		return array_filter($arr);
	}
	
}

