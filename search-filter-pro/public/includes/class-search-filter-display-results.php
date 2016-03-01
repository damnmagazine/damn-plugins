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


class Search_Filter_Display_Results {
	
	
	public function __construct($plugin_slug)
	{
		/*
		 * Call $plugin_slug from public plugin class.
		 */
		
		//$plugin = Search_Filter::get_instance();
		$this->plugin_slug = $plugin_slug;

	}
	
	public function output_results($sfid, $settings)
	{
		global $sf_form_data;
		
		$returnvar = "";
		
		$returnvar .= "<div class=\"search-filter-results\" id=\"search-filter-results-".$sfid."\">";
		$returnvar .= "";
		
		$get_results_obj = new Search_Filter_Get_Results($this->plugin_slug);
		$the_results = $get_results_obj->the_results($sfid);
		$returnvar .= $the_results;
		
		$returnvar .= "</div>";
		
		return $returnvar;
	}
	
	
}
