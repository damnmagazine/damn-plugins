<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Post_Data
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2014 Designs & Code
 */
 
class Search_Filter_Form_Data
{
	private $form_data = '';
	private $count_table;
	
	function __construct()
	{
		//fetch the list of term ids for the given post
		//$this->term_ids = wp_get_post_terms( $post_id, $taxonomy, 'fields=ids' );
		//var_dump($this->term_ids);
	}
	
	public function get_field_by_key($key)
	{
		if(isset($this->form_data['fields_assoc'][$key]))
		{
			return $this->form_data['fields_assoc'][$key];
		}
		else
		{
			return false;
		}
	}
	
	public function set_count_table($count_table)
	{
		$this->count_table = $count_table;
	}
	
	public function get_count_table()
	{
		return $this->count_table;
	}
	
	public function get_count_var($field_type, $id)
	{
		if($field_type=="taxonomy")
		{
			if(isset($this->count_table[SF_TAX_PRE.$id]))
			{
				return $this->count_table[SF_TAX_PRE.$id];
			}
			else
			{
				return 0;
			}
		}
	}
	
	public function init($postid = '')
	{
		$form_id = $postid;
		
		$this->form_data['settings'] = get_post_meta( $form_id , '_search-filter-settings' , true );
		$this->form_data['fields'] = get_post_meta( $form_id , '_search-filter-fields' , true );
		$this->form_data['fields_assoc'] = array();
				
		if(($this->form_data['settings'])&&($this->form_data['fields']))
		{
			$this->form_data['id'] = $form_id;
			$this->form_data['postid'] = $postid;
			$this->form_data['idref'] = $postid;
			
			//$fieldswkeys = array();
			
			foreach ($this->form_data['fields'] as $field)
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
					$this->form_data['fields_assoc'][SF_META_PRE.$meta_key] = $field; //make fields accessible by key
				}
				else if($field['type']=="taxonomy")
				{
					$taxonomy_name = $field['taxonomy_name'];
					
					$this->form_data['fields_assoc'][SF_TAX_PRE.$taxonomy_name] = $field; //make fields accessible by key
				}
				else if(($field['type']=="tag")||($field['type']=="category"))
				{
					if($this->is_using_custom_template())
					{//if we're using a custom template, treat tag and cat as normal taxonomies
						
						$taxonomy_name = $field['taxonomy_name'];
						
						$this->form_data['fields_assoc'][SF_TAX_PRE.$taxonomy_name] = $field; //make fields accessible by key
					}
					else
					{//else make them special ;)
						$this->form_data['fields_assoc'][$field['type']] = $field; //make fields accessible by key
					}
				}
				else
				{
					$this->form_data['fields_assoc'][$field['type']] = $field; //make fields accessible by key
				}
			}			
		}
	}
	
	function get_active_form_id()
	{
		if(isset($this->form_data['idref']))
		{
			return $this->form_data['idref'];
		}
		else
		{
			return 0;
		}
	}
	
	function data($index = '')
	{
		if(isset($this->form_data[$index]))
		{
			return $this->form_data[$index];
		}
		
		return false;
	}
	
	public function settings($index)
	{
		if(isset($this->form_data['settings']))
		{
			if(isset($this->form_data['settings'][$index]))
			{
				return $this->form_data['settings'][$index];
			}
		}
		
		return false;
	}
	
	public function get_template_name()
	{
		if(isset($this->form_data['settings']))
		{
			if(isset($this->form_data['settings']['use_template_manual_toggle']))
			{
				if($this->form_data['settings']['use_template_manual_toggle']==1)
				{//then a template option has been selected
					
					if(isset($this->form_data['settings']['template_name_manual']))
					{
						return $this->form_data['settings']['template_name_manual'];
					}
				}
			}
		}
		
		return false;
	}
		
	public function is_valid_form()
	{
		if(isset($this->form_data['id']))
		{
			if($this->form_data['id']!=0)
			{
				return true;
			}
		}
		
		return false;
		
		//global $sf_form_data;
		//$sf_form_data->settings('post_types')
	}
	public function form_id()
	{
		if(isset($this->form_data['id']))
		{
			if($this->form_data['id']!=0)
			{
				return $this->form_data['id'];
			}
		}
		
		return false;
		
		//global $sf_form_data;
		//$sf_form_data->settings('post_types')
	}
	
	public function is_using_custom_template()
	{
		if(isset($this->form_data['settings']))
		{
			if(isset($this->form_data['settings']['use_template_manual_toggle']))
			{
				if($this->form_data['settings']['use_template_manual_toggle']==1)
				{
					return true;
				}
			}
		}
		
		return false;
	}
	
	public function return_data()
	{		
		return $this->form_data;
		
		//global $sf_form_data;
		//$sf_form_data->settings('post_types')
	}
	
}

?>