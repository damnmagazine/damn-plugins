<?php
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");
?>

<optgroup label="<?php echo esc_attr (_x ("ccBill Recurring Plans", "s2member-admin", "s2member")); ?>">
<option value="1-M-1" selected="selected"><?php echo esc_html (_x ("Monthly (recurring charge, for ongoing access)", "s2member-admin", "s2member")); ?></option>
<option value="2-M-1"><?php echo esc_html (_x ("Bi-Monthly (recurring charge, for ongoing access)", "s2member-admin", "s2member")); ?></option>
<option value="3-M-1"><?php echo esc_html (_x ("Quarterly (recurring charge, for ongoing access)", "s2member-admin", "s2member")); ?></option>
</optgroup>

<option disabled="disabled"></option>

<optgroup label="<?php echo esc_attr (_x ("ccBill Non-Recurring / Buy Now", "s2member-admin", "s2member")); ?>">
<option value="2-D-0"><?php echo esc_html (_x ("One Time (for 2 day access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>
<option value="3-D-0"><?php echo esc_html (_x ("One Time (for 3 day access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>
<option value="4-D-0"><?php echo esc_html (_x ("One Time (for 4 day access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>
<option value="5-D-0"><?php echo esc_html (_x ("One Time (for 5 day access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>
<option value="6-D-0"><?php echo esc_html (_x ("One Time (for 6 day access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>

<option value="1-W-0"><?php echo esc_html (_x ("One Time (for 1 week access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>
<option value="2-W-0"><?php echo esc_html (_x ("One Time (for 2 week access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>
<option value="3-W-0"><?php echo esc_html (_x ("One Time (for 3 week access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>

<option value="1-M-0"><?php echo esc_html (_x ("One Time (for 1 month access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>
<option value="2-M-0"><?php echo esc_html (_x ("One Time (for 2 month access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>
<option value="3-M-0"><?php echo esc_html (_x ("One Time (for 3 month access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>
<option value="4-M-0"><?php echo esc_html (_x ("One Time (for 4 month access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>
<option value="5-M-0"><?php echo esc_html (_x ("One Time (for 5 month access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>
<option value="6-M-0"><?php echo esc_html (_x ("One Time (for 6 month access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>

<option value="1-Y-0"><?php echo esc_html (_x ("One Time (for 1 year access, non-recurring, no trial)", "s2member-admin", "s2member")); ?></option>
</optgroup>