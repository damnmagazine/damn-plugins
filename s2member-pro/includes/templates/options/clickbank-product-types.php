<?php
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");
?>

<option value="standard"><?php echo esc_html (_x ("Standard", "s2member-admin", "s2member")); ?></option>
<option value="recurring" selected="selected"><?php echo esc_html (_x ("Recurring", "s2member-admin", "s2member")); ?></option>