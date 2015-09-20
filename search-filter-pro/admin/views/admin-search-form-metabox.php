<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2014 Designs & Code
 */
?>
<?php wp_nonce_field( 'search_form_nonce', $this->plugin_slug.'_nonce', true, true ); ?>

<div id="search-form">
	
	<p class="description"><?php _e( 'Build your Search Form here by dragging Available Fields in to this area.', $this->plugin_slug ); ?></p>
		
	<?php
		$widgets = (get_post_meta( $object->ID, '_search-filter-fields', true ));
		
		if(isset($widgets))
		{
			if($widgets!="")
			{
				foreach ($widgets as $widget)
				{
					if(isset($widget['type']))
					{
						$this->display_meta_box_field($widget['type'], $widget);
					}
				}
			}
		}
	?>
</div>
