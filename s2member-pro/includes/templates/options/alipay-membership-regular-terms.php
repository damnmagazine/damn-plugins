<?php
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");
?>

<optgroup label="<?php echo esc_attr (_x ("AliPay (Buy Now)", "s2member-admin", "s2member")); ?>">
<option value="1-D"><?php echo esc_html (_x ("One Time (for 1 day access)", "s2member-admin", "s2member")); ?></option>
<option value="2-D"><?php echo esc_html (_x ("One Time (for 2 day access)", "s2member-admin", "s2member")); ?></option>
<option value="3-D"><?php echo esc_html (_x ("One Time (for 3 day access)", "s2member-admin", "s2member")); ?></option>
<option value="4-D"><?php echo esc_html (_x ("One Time (for 4 day access)", "s2member-admin", "s2member")); ?></option>
<option value="5-D"><?php echo esc_html (_x ("One Time (for 5 day access)", "s2member-admin", "s2member")); ?></option>
<option value="6-D"><?php echo esc_html (_x ("One Time (for 6 day access)", "s2member-admin", "s2member")); ?></option>

<option value="1-W"><?php echo esc_html (_x ("One Time (for 1 week access)", "s2member-admin", "s2member")); ?></option>
<option value="2-W"><?php echo esc_html (_x ("One Time (for 2 week access)", "s2member-admin", "s2member")); ?></option>
<option value="3-W"><?php echo esc_html (_x ("One Time (for 3 week access)", "s2member-admin", "s2member")); ?></option>

<option value="1-M" selected="selected"><?php echo esc_html (_x ("One Time (for 1 month access)", "s2member-admin", "s2member")); ?></option>
<option value="2-M"><?php echo esc_html (_x ("One Time (for 2 month access)", "s2member-admin", "s2member")); ?></option>
<option value="3-M"><?php echo esc_html (_x ("One Time (for 3 month access)", "s2member-admin", "s2member")); ?></option>
<option value="4-M"><?php echo esc_html (_x ("One Time (for 4 month access)", "s2member-admin", "s2member")); ?></option>
<option value="5-M"><?php echo esc_html (_x ("One Time (for 5 month access)", "s2member-admin", "s2member")); ?></option>
<option value="6-M"><?php echo esc_html (_x ("One Time (for 6 month access)", "s2member-admin", "s2member")); ?></option>

<option value="1-Y"><?php echo esc_html (_x ("One Time (for 1 year access)", "s2member-admin", "s2member")); ?></option>
<option value="2-Y"><?php echo esc_html (_x ("One Time (for 2 year access)", "s2member-admin", "s2member")); ?></option>
<option value="3-Y"><?php echo esc_html (_x ("One Time (for 3 year access)", "s2member-admin", "s2member")); ?></option>
<option value="4-Y"><?php echo esc_html (_x ("One Time (for 4 year access)", "s2member-admin", "s2member")); ?></option>
<option value="5-Y"><?php echo esc_html (_x ("One Time (for 5 year access)", "s2member-admin", "s2member")); ?></option>

<option value="1-L"><?php echo esc_html (_x ("One Time (for lifetime access)", "s2member-admin", "s2member")); ?></option>
</optgroup>
