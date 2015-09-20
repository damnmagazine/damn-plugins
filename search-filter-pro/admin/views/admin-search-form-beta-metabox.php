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

<div id="available-fields" class="widgets-search-filter-draggables ui-search-filter-sortable setup" data-allow-expand="0">
	<?php
		global $post;
	?>
	<br /><strong><?php _e("Auto Count <em>*beta*</em>", $this->plugin_slug ); ?></strong>
	<p class="description-inline">
		<label for="{0}[{1}][enable_auto_count]">
			<input class="checkbox enable_auto_count" type="checkbox" id="{0}[{1}][enable_auto_count]" name="enable_auto_count"<?php $this->set_checked($values['enable_auto_count']); ?>> 
			<?php _e("Enable Auto Count", $this->plugin_slug); ?><span class="hint--top hint--info" data-hint="<?php _e("this is a beta feature and still being tested - your feedback is most welcome especially if you have a large numbers of posts or using a lot of fields.", $this->plugin_slug); ?>"><i class="dashicons dashicons-info"></i></span><br />
			<br /><?php _e("<strong>Update 1.3.0 breaks this feature (more).  Expect to see it back in version 2.0.0. </strong> <br /><br />Disabling this is recommended.", $this->plugin_slug); ?><br />
			<!--<br /><em><?php _e("Currently only works for taxonomies, other fields are ignored.", $this->plugin_slug); ?></em><br />
			<br /><em><?php _e("Automatically update the search form based on user selection, hiding or disabling any unavaliable choices (eg, hiding all Tags that are not present in the current selected Category).", $this->plugin_slug); ?></em>-->
		</label>
	</p>
	<br />
</div>


