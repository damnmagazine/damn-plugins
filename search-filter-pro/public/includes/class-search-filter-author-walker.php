<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Author_Walker
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2014 Designs & Code
 */
 
class Search_Filter_Author_Walker {
	
	private $type;
	
	function wp_authors($type, $args = '') {
		global $wpdb;
		
		$this->type = $type;
		
		$defaults = array(
			'orderby' => 'name', 'order' => 'ASC', 'number' => '',
			'optioncount' => false, 'exclude_admin' => true,
			'show_fullname' => false, 'hide_empty' => true,
			'feed' => '', 'feed_image' => '', 'feed_type' => '', 'echo' => true,
			'style' => 'list', 'html' => true, 'exclude' => '', 'include' => '',
			'post_types' => array('post'), 'combo_box' => ''
			
		);
		
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );
				
		$return = '';

		$query_args = wp_array_slice_assoc( $args, array( 'orderby', 'order', 'number', 'exclude', 'include' ) );
		$query_args['fields'] = 'ids';
		$authors = get_users( $query_args );
		
		//build where conditions for post types...
		
		$where_conditions = array();
		$post_type_count = count($post_types);
		$where_query = '';
		if($post_type_count>0)
		{
			foreach($post_types as $post_type)
			{
				if(post_type_exists($post_type))
				{
					$post_type = esc_attr($post_type);
					$where_conditions[] = " (post_type = '$post_type' AND " . get_private_posts_cap_sql( $post_type ) . ")";
				}
			}
			$where_query = implode(" OR", $where_conditions);
		}
		
		$author_count = array();
		foreach ( (array) $wpdb->get_results("SELECT DISTINCT post_author, COUNT(ID) AS count FROM $wpdb->posts WHERE$where_query GROUP BY post_author") as $row )
			$author_count[$row->post_author] = $row->count;
		
		$elem_attr = "";
		if($this->type=="select")
		{
			if($combo_box==1)
			{
				$elem_attr = ' data-combobox="1"';
			}
			$return .= '<select name="'.$args['name'].'"'.$elem_attr.'>';
			$return .= '<option class="level-0 '.SF_ITEM_CLASS_PRE.'0" value="0">'.$args['all_items_label'].'</option>';
		}
		else if($this->type=="radio")
		{
			$return .= '<ul>';
		}
		else if($this->type=="checkbox")
		{
			$return .= '<ul>';
		}
		else if($this->type=="multiselect")
		{
			if($combo_box==1)
			{
				$elem_attr = ' data-combobox="1"';
			}
			$return .= '<select multiple="multiple" name="'.$args['name'].'[]"'.$elem_attr.'>';
		}
		
		foreach ( $authors as $author_id ) {
			$author = get_userdata( $author_id );

			if ( $exclude_admin && 'admin' == $author->display_name )
				continue;

			$posts = isset( $author_count[$author->ID] ) ? $author_count[$author->ID] : 0;

			if ( !$posts && $hide_empty )
				continue;

			$link = '';

			if ( $show_fullname && $author->first_name && $author->last_name )
				$name = "$author->first_name $author->last_name";
			else
				$name = $author->display_name;

			if ( !$html ) {
				$return .= $name . ', ';

				continue; // No need to go further to process HTML.
			}
			
			/*if ( 'list' == $style ) {
				$return .= '<li>';
			}*/
			
			$selected = "";
			$checked = "";
			
			
			if(isset($args['defaults']))
			{
				$noselected = count($args['defaults']);
				
				if(($noselected>0)&&(is_array($args['defaults'])))
				{
					if(in_array($author->ID, $args['defaults']))
					{
						$selected = ' selected="selected"';
						$checked = ' checked="checked"';
					}
				}
			}
			
			if(($this->type=="select")||($this->type=="multiselect"))
			{
				$return .= '<option class="level-0 '.SF_ITEM_CLASS_PRE.esc_attr($author->ID).'" value="'.esc_attr($author->ID).'"'.$selected.'>';
			}
			else if(($this->type=="radio")||($this->type=="checkbox"))
			{
				$return .= '<li class="'.SF_ITEM_CLASS_PRE.esc_attr($author->ID).'">';
			}
			
			//$link = '<a href="' . get_author_posts_url( $author->ID, $author->user_nicename ) . '" title="' . esc_attr( sprintf(__("Posts by %s"), $author->display_name) ) . '">' . $name . '</a>';
			
			if(($this->type=="select")||($this->type=="multiselect"))
			{
				$link = $name;
			}
			else if($this->type=="radio")
			{
				$link = '<label><input type="radio" name="'.$args['name'].'[]" value="'.esc_attr($author->ID).'"'.$checked.'> '.esc_html($name);
			}
			else if($this->type=="checkbox")
			{
				$link = '<label><input type="checkbox" name="'.$args['name'].'[]" value="'.esc_attr($author->ID).'"'.$checked.'> '.esc_html($name);
			}

			if ( !empty( $feed_image ) || !empty( $feed ) ) {
				$link .= ' ';
				if ( empty( $feed_image ) ) {
					$link .= '(';
				}

				$link .= '<a href="' . get_author_feed_link( $author->ID ) . '"';

				$alt = $title = '';
				if ( !empty( $feed ) ) {
					$title = ' title="' . esc_attr( $feed ) . '"';
					$alt = ' alt="' . esc_attr( $feed ) . '"';
					$name = $feed;
					$link .= $title;
				}

				$link .= '>';

				if ( !empty( $feed_image ) )
					$link .= '<img src="' . esc_url( $feed_image ) . '" style="border: none;"' . $alt . $title . ' />';
				else
					$link .= $name;

				$link .= '</a>';

				if ( empty( $feed_image ) )
					$link .= ')';
			}

			if ( $optioncount )
			{
				if(($this->type=="checkbox")||($this->type=="radio"))
				{
					$link .= ' <span class="'.SF_CLASS_PRE.'count">('. $posts . ')</span>';
				}
				else
				{
					$link .= ' ('. $posts . ')';
				}
			}

			$return .= $link;
			
			//$return .= ( 'list' == $style ) ? '</li>' : ', ';
			
			if(($this->type=="select")||($this->type=="multiselect"))
			{
				$return .= '</option>';
			}
			else if(($this->type=="radio")||($this->type=="checkbox"))
			{
				$return .= '</label></li>';
			}
		}
		
		if($this->type=="select")
		{
			$return .= '</select>';
		}
		else if($this->type=="radio")
		{
			$return .= '</ul>';
		}
		else if($this->type=="checkbox")
		{
			$return .= '</ul>';
		}
		else if($this->type=="multiselect")
		{
			$return .= '</select>';
		}
		
		
		$return = rtrim($return, ', ');

		if ( !$echo )
			return $return;

		echo $return;
	}

}

?>