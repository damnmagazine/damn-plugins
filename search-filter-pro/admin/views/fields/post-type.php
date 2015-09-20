<?php
/**
 * Represents the view for the administration settings dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

?>
<div class="widget" data-field-type="<?php echo $values['type']; ?>">
	<div class="widget-top">
		<div class="widget-title-action">
			<a class="widget-action hide-if-no-js" href="#"></a>
		</div>
		<div class="widget-title-action-move">
			<a class="widget-action hide-if-no-js" href="#"></a>
		</div>
		<div class="widget-title">
			<h4><?php _e("Post Type", $this->plugin_slug); ?><span class="in-widget-title"></span></h4>
		</div>
	</div>

	<div class="widget-inside">
		<!--<form action="" method="post">-->
			<div class="widget-content" style="position:relative;">

				<!--<a class="widget-control-advanced" href="#remove"><?php _e("Advanced settings", $this->plugin_slug); ?></a>-->
				
				<fieldset class="">
					<p class="sf_post_types">
						<?php _e("Post Types:", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("post types available to this field", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
						<?php _e("Any post types made available here must also be made available to the search form under <strong>Settings</strong> before a user will see them as options.", $this->plugin_slug); ?>
						<br /><br />
						<?php
							
							$args = array(
							   'public'   => true
							);
							
							$post_types = get_post_types( $args, 'objects' ); 
							
							$is_default = false;
							if(!is_array($values['post_types']))
							{
								$is_default = true;
								$values['post_types'] = array();
							}
							
							
							foreach ( $post_types as $post_type )
							{
								//if($post_type->name!="attachment")
								//{
									if($is_default)
									{
										$values['post_types'][$post_type->name] = "1";
									}
									else if(!isset($values['post_types'][$post_type->name]))
									{
										$values['post_types'][$post_type->name] = "";
									}
									
									?>
									
									<input class="checkbox" type="checkbox" id="{0}[{1}][post_types][<?php echo $post_type->name; ?>]" name="{0}[{1}][post_types][<?php echo $post_type->name; ?>]"<?php $this->set_checked($values['post_types'][$post_type->name]); ?>>
									<label for="{0}[{1}][post_types][<?php echo $post_type->name; ?>]"><?php _e($post_type->labels->name, $this->plugin_slug); ?></label>
									
									<?php
								//}
							}
						?>
						
					</p>
				</fieldset>
				<br class="clear" />
				<hr />
				<fieldset class="item-container">
					<p class="sf_input_type">
						<label for="{0}[{1}][input_type]"><?php _e("Input type: ", $this->plugin_slug); ?><br />
							<select name="{0}[{1}][input_type]" class="" id="{0}[{1}][input_type]">
								<option value="select"<?php $this->set_selected($values['input_type'], "select"); ?>><?php _e("Dropdown", $this->plugin_slug); ?></option>
								<option value="checkbox"<?php $this->set_selected($values['input_type'], "checkbox"); ?>><?php _e("Checkbox", $this->plugin_slug); ?></option>
								<option value="radio"<?php $this->set_selected($values['input_type'], "radio"); ?>><?php _e("Radio", $this->plugin_slug); ?></option>
								<option value="multiselect"<?php $this->set_selected($values['input_type'], "multiselect"); ?>><?php _e("Multi-select", $this->plugin_slug); ?></option>
							</select>
						</label>
					</p>
					<p class="sf_make_combobox">
						<input class="checkbox" type="checkbox" id="{0}[{1}][combo_box]" name="{0}[{1}][combo_box]"<?php $this->set_checked($values['combo_box']); ?>>
						<label for="{0}[{1}][combo_box]"><?php _e("Make Combobox?", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("Allow for text input to find values, with autocomplete and dropdown suggest", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span></label>
					</p>
					<p>
						<label for="{0}[{1}][heading]"><?php _e("Add a heading?", $this->plugin_slug); ?><br /><input class="" id="{0}[{1}][heading]" name="{0}[{1}][heading]" type="text" value="<?php echo esc_attr($values['heading']); ?>"></label>
					</p>
					<p class="sf_all_items_label">
						<label for="{0}[{1}][all_items_label]"><?php _e("Change All Items Label?", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("override the default - e.g. &quot;All Post Types&quot;", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
						<input class="" id="{0}[{1}][all_items_label]" name="{0}[{1}][all_items_label]" type="text" value="<?php echo esc_attr($values['all_items_label']); ?>"></label>
					</p>
					
					<p class="sf_operator">
					<!--	<label for="{0}[{1}][operator]"><?php _e("Search Operator", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("AND - posts must be in each tag, OR - posts must be in any tag", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
							<select name="{0}[{1}][operator]" id="{0}[{1}][operator]">
								<option value="and"<?php $this->set_selected($values['operator'], "and"); ?>><?php _e("AND", $this->plugin_slug); ?></option>
								<option value="or"<?php $this->set_selected($values['operator'], "or"); ?>><?php _e("OR", $this->plugin_slug); ?></option>
							</select>
						</label>-->
					</p>
					
				</fieldset>
				
				
				<!--<fieldset class="item-container">
					<br />
					<p>
						<input class="checkbox" type="checkbox" id="{0}[{1}][show_count]" name="{0}[{1}][show_count]"<?php $this->set_checked($values['show_count']); ?>>
						<label for="{0}[{1}][show_count]"><?php _e("Display count?", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("display the number of posts in each category", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span></label>
					</p>
				</fieldset>-->
				
				<div class="clear"></div>
				
				<!--<fieldset class="advanced-settings">
					<hr />
					
					<p class="item-container">
						<label for="{0}[{1}][order_by]"><?php _e("Order terms by: ", $this->plugin_slug); ?><br />
							<select name="{0}[{1}][order_by]" id="{0}[{1}][order_by]">
								<option value="default"<?php $this->set_selected($values['order_by'], "default"); ?>><?php _e("Default", $this->plugin_slug); ?></option>
								<option value="id"<?php $this->set_selected($values['order_by'], "id"); ?>><?php _e("ID", $this->plugin_slug); ?></option>
								<option value="name"<?php $this->set_selected($values['order_by'], "name"); ?>><?php _e("Name", $this->plugin_slug); ?></option>
								<option value="slug"<?php $this->set_selected($values['order_by'], "slug"); ?>><?php _e("Slug", $this->plugin_slug); ?></option>
								<option value="count"<?php $this->set_selected($values['order_by'], "count"); ?>><?php _e("Count", $this->plugin_slug); ?></option>
								<option value="term_group"<?php $this->set_selected($values['order_by'], "term_group"); ?>><?php _e("Term Group", $this->plugin_slug); ?></option>
							</select>
							
							<select name="{0}[{1}][order_dir]" id="{0}[{1}][order_dir]">
								<option value="asc"<?php $this->set_selected($values['order_dir'], "asc"); ?>><?php _e("ASC", $this->plugin_slug); ?></option>
								<option value="desc"<?php $this->set_selected($values['order_dir'], "desc"); ?>><?php _e("DESC", $this->plugin_slug); ?></option>
							</select>
							
						</label>
					</p>
										
				</fieldset>
				
				<div class="clear"></div>-->
				
			</div>
			<br class="clear" />
			
			<input type="hidden" name="{0}[{1}][type]" class="widget-id" id="hidden_type" value="<?php echo esc_attr($values['type']); ?>" />
			
			<div class="widget-control-actions">
				<div class="alignleft">
					<a class="widget-control-remove" href="#remove"><?php _e("Delete", $this->plugin_slug); ?></a> |
					<a class="widget-control-close" href="#close"><?php _e("Close", $this->plugin_slug); ?></a>
				</div>
				<br class="clear">
			</div>
		<!--</form>-->
	</div>
	<div class="widget-description">
		<?php _e("Add a Post Type Field your form", $this->plugin_slug); ?>
	</div>
</div>