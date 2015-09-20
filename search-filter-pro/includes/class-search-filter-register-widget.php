<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Register_Widget
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
class Search_Filter_Register_Widget extends WP_Widget
{
	
	/*public function __construct()
	{

		$plugin = Search_Filter::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		
		//register_widget('search_filter_widget');
	}*/
	
	function Search_Filter_Register_Widget() {
		// Instantiate the parent object
		parent::__construct( false, 'Search & Filter Form' );
		
		$plugin = Search_Filter::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
	}
	function widget( $args, $instance )
	{
		extract($args);
		
		$title = apply_filters('widget_title', $instance['title']);
		
		echo $before_widget; //Widget starts to print information
		
		// Check if title is set
		if ( $title )
		{
			echo $before_title . $title . $after_title;
		}
		
		$formid = apply_filters( 'widget_title', $instance['formid'] );
		
		echo do_shortcode('[searchandfilter id="'.$formid.'"]');
				
		echo $after_widget; //Widget ends printing information
		//do_shortcode('[searchandfilter id="11"]');
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance = $old_instance;
		 
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['formid'] = ( ! empty( $new_instance['formid'] ) ) ? strip_tags( $new_instance['formid'] ) : '';
		
		return $instance;
		 
	}

	function form( $instance )
	{
		
		
		if (( isset( $instance[ 'title' ]) ) && ( isset( $instance[ 'formid' ]) ))
		{
			$title = __(esc_attr($instance['title']), $this->plugin_slug);
			$formid = esc_attr($instance[ 'formid' ]);
		}
		else
		{
			$title = __( '', $this->plugin_slug);
			$formid = __( '', $this->plugin_slug);
		}
		
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'formid' ); ?>">Choose a Search Form: 
				<select class="widefat" name="<?php echo $this->get_field_name( 'formid' ); ?>" id="<?php echo $this->get_field_id( 'formid' ); ?>">
					<option value="0"><?php _e('Please choose'); ?></option>
					<?php //
						$custom_posts = new WP_Query('post_type=search-filter-widget&post_status=publish&posts_per_page=-1');
						
						if ( function_exists('icl_object_id') )
						{
							$formid = icl_object_id($formid, 'search-filter-widget', true, ICL_LANGUAGE_CODE);
						}
						
						//var_dump($custom_posts);
						while ($custom_posts->have_posts()) : $custom_posts->the_post();
					?>
						<option value="<?php the_ID(); ?>" <?php if($formid==get_the_ID()){ echo ' selected="selected"'; } ?>><?php the_title(); ?></option>
					<?php endwhile; ?>

				</select>
			</label>
		</p>
		<p>
			<?php _e('Don\'t see a Search Form you want to use? <a href="'.admin_url( 'post-new.php?post_type=search-filter-widget' ).'">Create a new Search Form</a>.'); ?>
			
		</p>
		<?php
	}
}
