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
<div class="widget" data-field-type="<?php echo esc_attr($values['type']); ?>">
	<div class="widget-top">
		<div class="widget-title-action">
			<a class="widget-action hide-if-no-js" href="#"></a>
		</div>
		<div class="widget-title-action-move">
			<a class="widget-action hide-if-no-js" href="#"></a>
		</div>
		<div class="widget-title">
			<h4><?php _e("Post Date", $this->plugin_slug); ?><span class="in-widget-title"></span></h4>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget-content" style="position:relative;">
			
			<fieldset class="item-container">
				<p class="sf_input_type">
					<label for="{0}[{1}][input_type]"><?php _e("Input type: ", $this->plugin_slug); ?><br />
						<select name="{0}[{1}][input_type]" class="" id="{0}[{1}][input_type]">
							<option value="date"<?php $this->set_selected($values['input_type'], "date"); ?>><?php _e("Date", $this->plugin_slug); ?></option>
							<option value="daterange"<?php $this->set_selected($values['input_type'], "daterange"); ?>><?php _e("Date Range", $this->plugin_slug); ?></option>
						</select>
					</label>
				</p>
				<p>
					<label for="{0}[{1}][heading]"><?php _e("Add a heading?", $this->plugin_slug); ?><br /><input class="" id="{0}[{1}][heading]" name="{0}[{1}][heading]" type="text" value="<?php echo esc_attr($values['heading']); ?>"></label>
				</p>
			</fieldset>
			<fieldset class="item-container">
				<p>
					<?php _e("Date Format", $this->plugin_slug); ?>
				</p>
				<p>
				<?php
					$format = array();
					$format[0] = "d/m/Y";
					$format[1] = "m/d/Y";
					$format[2] = "Y/m/d";
					
					$formati = 0;
					foreach($format as $aformat)
					{
						if($values['date_format'] == $aformat)
						{
							echo '<input type="hidden" disabled="disabled" class="date_format_hidden" value="'.$formati.'" id="{0}[{1}][date_format_hidden]" name="{0}[{1}][date_format_hidden]" />';
						}
						
						$formati++;
					}
					
				?>
					
					
					<label for="{0}[{1}][date_format][0]"><input class="date_format_radio" id="{0}[{1}][date_format][0]" name="{0}[{1}][date_format]" type="radio" value="<?php echo $format[0] ?>"<?php echo $this->set_radio($values['date_format'], $format[0]); ?>><?php echo date($format[0]) ?></label><br />
					<label for="{0}[{1}][date_format][1]"><input class="date_format_radio" id="{0}[{1}][date_format][1]" name="{0}[{1}][date_format]" type="radio" value="<?php echo $format[1] ?>"<?php echo $this->set_radio($values['date_format'], $format[1]); ?>><?php echo date($format[1]) ?></label><br />
					<label for="{0}[{1}][date_format][2]"><input class="date_format_radio" id="{0}[{1}][date_format][2]" name="{0}[{1}][date_format]" type="radio" value="<?php echo $format[2] ?>"<?php echo $this->set_radio($values['date_format'], $format[2]); ?>><?php echo date($format[2]) ?></label><br />
					<!--<label for="{0}[{1}][date_format]"><input class="" id="{0}[{1}][date_format]" name="{0}[{1}][date_format]" type="radio"> Custom: <input type="text" size="10" /></label>-->
				</p>
			</fieldset>
			
			
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
		<?php _e("Add a Post Date Field to your form", $this->plugin_slug); ?>
	</div>
</div>