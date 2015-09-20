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
			<h4><?php _e("Post Meta", $this->plugin_slug); ?><span class="in-widget-title"></span></h4>
		</div>
	</div>

	<div class="widget-inside">
	
		<div class="widget-content" style="position:relative;">
			
			<fieldset>
				<p>
					<?php _e("Search &amp; Filter has found the following Meta Keys used across all of your post types.", $this->plugin_slug); ?>
				</p>
				
				<p class="item-container sf_meta_keys">
					<label for="{0}[{1}][meta_key]">
						<?php _e("Meta Key", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("choose a meta key for this field", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
						<?php
							$all_meta_keys = $this->get_all_post_meta_keys();
							echo '<select name="{0}[{1}][meta_key]" class="meta_key" id="{0}[{1}][meta_key]">';
							
							foreach($all_meta_keys as $v){
								//$data[] = $v->meta_key;
								
								echo '<option value="'.$v.'"'.$this->set_selected($values['meta_key'], $v, false).'>'.$v."</option>";
							}
							echo '</select>';
							
						?>
						<input type="hidden"  name="{0}[{1}][meta_key]" id="{0}[{1}][meta_key]" class="meta_key_hidden"  value="<?php echo $values['meta_key']; ?>" disabled="disabled" />
					</label>
				</p>
				<p class="item-container">
					<label for="{0}[{1}][meta_key_manual_toggle]">
						<input class="checkbox meta_key_manual_toggle" type="checkbox" id="{0}[{1}][meta_key_manual_toggle]" name="{0}[{1}][meta_key_manual_toggle]"<?php $this->set_checked($values['meta_key_manual_toggle']); ?>> 
						<?php _e("Enter manually?", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("if your meta key is not listed or not yet created enter here", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
						<input class="meta_key_manual" id="{0}[{1}][meta_key_manual]" name="{0}[{1}][meta_key_manual]" type="text" value="<?php echo esc_attr($values['meta_key_manual']); ?>">
						<input type="hidden"  name="{0}[{1}][meta_key_manual]" id="{0}[{1}][meta_key_manual]" class="meta_key_manual_hidden"  value="<?php echo $values['meta_key_manual']; ?>" disabled="disabled" />
					</label>
				</p>
				<p class="item-container">
			
					<label for="{0}[{1}][heading]"><?php _e("Add a heading?", $this->plugin_slug); ?><br /><input class="" id="{0}[{1}][heading]" name="{0}[{1}][heading]" type="text" value="<?php echo esc_attr($values['heading']); ?>"></label>
				</p>
				
				<br />
			</fieldset>
			<br class="clear" />
			<hr />
			
			<p><strong><?php _e("Display your Meta Field", $this->plugin_slug); ?></strong></p>
			<p><?php _e("Choose from displaying the field as numerical data, choice input or date picker.", $this->plugin_slug); ?><br /></p>
			
			<div class="tab-header sf_meta_type">
				<label for="{0}[{1}][meta_type][0]" class="active"><input data-radio-checked="<?php echo ($values['meta_type']=="number") ? 1 : 0 ?>" class="meta_type_radio" id="{0}[{1}][meta_type][0]" name="{0}[{1}][meta_type]" type="radio" value="number"<?php $this->set_radio($values['meta_type'], 'number'); ?>><?php _e("Number", $this->plugin_slug); ?></label> 
				<label for="{0}[{1}][meta_type][1]"><input data-radio-checked="<?php echo ($values['meta_type']=="choice") ? 1 : 0 ?>" class="meta_type_radio" id="{0}[{1}][meta_type][1]" name="{0}[{1}][meta_type]" type="radio" value="choice"<?php $this->set_radio($values['meta_type'], 'choice'); ?>><?php _e("Choice", $this->plugin_slug); ?></label>
				<label for="{0}[{1}][meta_type][2]"><input data-radio-checked="<?php echo ($values['meta_type']=="date") ? 1 : 0 ?>" class="meta_type_radio" id="{0}[{1}][meta_type][2]" name="{0}[{1}][meta_type]" type="radio" value="date"<?php $this->set_radio($values['meta_type'], 'date'); ?>><?php _e("Date", $this->plugin_slug); ?></label>
			</div>
			<br class="clear">
			<div class="sf_field_data sf_number">
				<p class="item-container">
					<label for="{0}[{1}][number_input_type]"><?php _e("Input type: ", $this->plugin_slug); ?><br />
						<select name="{0}[{1}][number_input_type]" class="" id="{0}[{1}][number_input_type]">
							<option value="range-slider"<?php $this->set_selected($values['number_input_type'], "range-slider"); ?>><?php _e("Range - Slider", $this->plugin_slug); ?></option>
							<option value="range-number"<?php $this->set_selected($values['number_input_type'], "range-number"); ?>><?php _e("Range - Number", $this->plugin_slug); ?></option>
							<option value="range-radio"<?php $this->set_selected($values['number_input_type'], "range-radio"); ?>><?php _e("Range - Radio", $this->plugin_slug); ?></option>
							<option value="range-checkbox"<?php $this->set_selected($values['number_input_type'], "range-checkbox"); ?>><?php _e("Range - Checkbox", $this->plugin_slug); ?></option>
							<option value="range-select"<?php $this->set_selected($values['number_input_type'], "range-select"); ?>><?php _e("Range - Dropdown", $this->plugin_slug); ?></option>
						</select>
					</label>
				</p><div class="clear"></div>
				<fieldset class="item-container child-columns">
					
					<p class="sf_range_min">
						<label for="{0}[{1}][range_min]">
							<?php _e("Min Value", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("the lowest value that a user can select", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
							<input class="" id="{0}[{1}][range_min]" name="{0}[{1}][range_min]" type="text" size="7" value="<?php echo $values['range_min']; ?>">
						</label>
					</p>
					<p class="sf_range_max">
						<label for="{0}[{1}][range_max]">
							<?php _e("Max Value", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("the highest value that a user can select", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
							<input class="" id="{0}[{1}][range_max]" name="{0}[{1}][range_max]" type="text" size="7" value="<?php echo $values['range_max']; ?>">
						</label>
					</p>
					<p class="sf_range_step">
						<label for="{0}[{1}][range_step]">
							<?php _e("Step", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("the increment amount", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
							<input class="" id="{0}[{1}][range_step]" name="{0}[{1}][range_step]" type="text" size="7" value="<?php echo $values['range_step']; ?>">
						</label>
					</p>
				</fieldset>
				
				<fieldset class="item-container child-columns">
					<p class="sf_range_value_prefix">
						<label for="{0}[{1}][range_value_prefix]">
							<?php _e("Value Prefix", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("text to appear before a value  - such as a currency symbol - &dollar;", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
							<input class="" id="{0}[{1}][range_value_prefix]" name="{0}[{1}][range_value_prefix]" type="text" size="7" value="<?php echo $values['range_value_prefix']; ?>">
						</label>
					</p>
					
					<p class="sf_range_value_postfix">
						<label for="{0}[{1}][range_value_postfix]">
							<?php _e("Value Postfix", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("text to appear after a value  - such as a currency symbol - &euro;", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
							<input class="" id="{0}[{1}][range_value_postfix]" name="{0}[{1}][range_value_postfix]" type="text" size="7" value="<?php echo $values['range_value_postfix']; ?>">
						</label>
					</p>
				</fieldset>
				<br class="clear" />
			</div>
			
			<div class="sf_field_data sf_choice">
				
				<p class="item-container sf_input_type slim">
					<label for="{0}[{1}][choice_input_type]"><?php _e("Input type: ", $this->plugin_slug); ?><br />
						<select name="{0}[{1}][choice_input_type]" class="" id="{0}[{1}][choice_input_type]">
							<option value="select"<?php $this->set_selected($values['choice_input_type'], "select"); ?>><?php _e("Dropdown", $this->plugin_slug); ?></option>
							<option value="checkbox"<?php $this->set_selected($values['choice_input_type'], "checkbox"); ?>><?php _e("Checkbox", $this->plugin_slug); ?></option>
							<option value="radio"<?php $this->set_selected($values['choice_input_type'], "radio"); ?>><?php _e("Radio", $this->plugin_slug); ?></option>
							<option value="multiselect"<?php $this->set_selected($values['choice_input_type'], "multiselect"); ?>><?php _e("Multi-select", $this->plugin_slug); ?></option>
						</select>
					</label>
				</p>
				
				<p class="sf_all_items_label item-container child-columns">
					<label for="{0}[{1}][all_items_label]"><?php _e("Change All Items Label?", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("override the default - e.g. &quot;All Items&quot;", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
					<input class="" id="{0}[{1}][all_items_label]" name="{0}[{1}][all_items_label]" type="text" value="<?php echo esc_attr($values['all_items_label']); ?>"></label>
				</p>
				
				<p class="sf_operator item-container child-columns">
					<label for="{0}[{1}][operator]"><?php _e("Search Operator", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("AND - posts must have each option selected, OR - posts must have any of the options selected", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
						<select name="{0}[{1}][operator]" id="{0}[{1}][operator]">
							<option value="and"<?php $this->set_selected($values['operator'], "and"); ?>><?php _e("AND", $this->plugin_slug); ?></option>
							<option value="or"<?php $this->set_selected($values['operator'], "or"); ?>><?php _e("OR", $this->plugin_slug); ?></option>
						</select>
					</label>
				</p>
				<p class="sf_make_combobox clear">
					<input class="checkbox" type="checkbox" id="{0}[{1}][combo_box]" name="{0}[{1}][combo_box]"<?php $this->set_checked($values['combo_box']); ?>>
					<label for="{0}[{1}][combo_box]"><?php _e("Make Combobox?", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("Allow for text input to find values, with autocomplete and dropdown suggest", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span></label>
				</p>
				<br class="clear" />
				<hr class="clear" />
				<p><strong><?php _e("Options", $this->plugin_slug); ?></strong></p>
				<p>
					<?php _e("Add the options that will be available to this field, each option must have a value and a label.", $this->plugin_slug); ?>
				</p>
				
				<p class="item-container slimheadings1">
					<strong><?php _e("Value", $this->plugin_slug); ?> <span class="hint--top hint--info" data-hint="<?php _e("the internal meta value - this value will be used to search your meta data", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span></strong>
				</p>
				<p class="item-container slimheadings2">
					<strong><?php _e("Label", $this->plugin_slug); ?> <span class="hint--top hint--info" data-hint="<?php _e("text that is visible to a user when selecting this option in the Search Form", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span></strong>
				</p>
				
				<!--<p>
					
					<a href="#" class="button-secondary sort-options-button"><?php _e("Sort By Value", $this->plugin_slug); ?></a>
					<a href="#" class="button-secondary sort-options-button"><?php _e("Sort By Label", $this->plugin_slug); ?></a>
				</p>-->
				<br class="clear"></span>
				
				<p class="no_sort_label"><?php _e("<strong>There are no options</strong>.", $this->plugin_slug); ?></p>
				
				<ul class="meta_options_list">
					<?php
					
					$i = 0;
					$this->display_meta_option( array(), ' meta-option-template ignore-template-init');
					
					if(isset($values['meta_options']))
					{
						foreach ($values['meta_options'] as $sort_option)
						{
							$this->display_meta_option($sort_option);
							
							$i++;
						}
					}
					
					?>
				</ul>
				
				<p>
					<a href="#" class="dashicons-plus add-option-button button-secondary"><?php _e("Add Option", $this->plugin_slug); ?></a>
					<a  href="#" class="dashicons-search detect-option-button button-secondary sfmodal"><?php _e("Auto Suggest", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("*experimental - suggest possible values based on a search of existing values for the meta key", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span></a>
					<a href="#" class="clear-option-button button-secondary"><?php _e("Clear All Options", $this->plugin_slug); ?></a>
				</p>
				
				
			</div>
			<div class="sf_field_data sf_date">
				<p class="item-container">
					<label for="{0}[{1}][date_input_type]"><?php _e("Input type: ", $this->plugin_slug); ?><br />
						<select name="{0}[{1}][date_input_type]" class="" id="{0}[{1}][date_input_type]">
							<option value="date"<?php $this->set_selected($values['date_input_type'], "date"); ?>><?php _e("Date", $this->plugin_slug); ?></option>
							<option value="daterange"<?php $this->set_selected($values['date_input_type'], "daterange"); ?>><?php _e("Date Range", $this->plugin_slug); ?></option>
						</select>
					</label>
				</p>
				<p class="item-container">
					
						<label for="{0}[{1}][date_input_format]"><?php _e("Date Input Format ", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("the format the date is saved in the database", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
							<select name="{0}[{1}][date_input_format]" class="" id="{0}[{1}][date_input_format]">
								<option value="timestamp"<?php $this->set_selected($values['date_input_format'], "timestamp"); ?>><?php _e("Timestamp", $this->plugin_slug); ?></option>
								<option value="yyyymmdd"<?php $this->set_selected($values['date_input_format'], "yyyymmdd"); ?>><?php _e("YYYYMMDD (ACF)", $this->plugin_slug); ?></option>
							</select>
						</label>
				</p>
				
				<p class="item-container">
					<p>
						<?php _e("Date Display Format", $this->plugin_slug); ?>
					</p>
					<p>
					<?php
						$format = array();
						$format[0] = "d/m/Y";
						$format[1] = "m/d/Y";
						$format[2] = "Y/m/d";
						
						$formati = 0;
						
						
					?>
						<label for="{0}[{1}][date_output_format][0]"><input data-radio-checked="<?php echo ($values['date_output_format']==$format[0]) ? 1 : 0 ?>" class="date_format_radio" id="{0}[{1}][date_output_format][0]" name="{0}[{1}][date_output_format]" type="radio" value="<?php echo $format[0] ?>"<?php echo $this->set_radio($values['date_output_format'], $format[0]); ?>><?php echo date($format[0]) ?></label><br />
						<label for="{0}[{1}][date_output_format][1]"><input data-radio-checked="<?php echo ($values['date_output_format']==$format[1]) ? 1 : 0 ?>" class="date_format_radio" id="{0}[{1}][date_output_format][1]" name="{0}[{1}][date_output_format]" type="radio" value="<?php echo $format[1] ?>"<?php echo $this->set_radio($values['date_output_format'], $format[1]); ?>><?php echo date($format[1]) ?></label><br />
						<label for="{0}[{1}][date_output_format][2]"><input data-radio-checked="<?php echo ($values['date_output_format']==$format[2]) ? 1 : 0 ?>" class="date_format_radio" id="{0}[{1}][date_output_format][2]" name="{0}[{1}][date_output_format]" type="radio" value="<?php echo $format[2] ?>"<?php echo $this->set_radio($values['date_output_format'], $format[2]); ?>><?php echo date($format[2]) ?></label><br />
						<!--<label for="{0}[{1}][date_output_format]"><input class="" id="{0}[{1}][date_output_format]" name="{0}[{1}][date_output_format]" type="radio"> Custom: <input type="text" size="10" /></label>-->
					</p>
				</fieldset>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			
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

	</div>
	<div class="widget-description">
		<?php _e("Add a Post Meta Field to your form", $this->plugin_slug); ?>
	</div>
</div>