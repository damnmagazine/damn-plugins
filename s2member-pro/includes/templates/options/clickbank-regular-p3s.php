<?php
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");
?>

<option value="1-W"><?php echo esc_html (_x ("Weekly", "s2member-admin", "s2member")); ?></option>
<option value="2-W"><?php echo esc_html (_x ("Bi-Weekly", "s2member-admin", "s2member")); ?></option>
<option value="1-M" selected="selected"><?php echo esc_html (_x ("Monthly", "s2member-admin", "s2member")); ?></option>
<option value="3-M"><?php echo esc_html (_x ("Quarterly", "s2member-admin", "s2member")); ?></option>