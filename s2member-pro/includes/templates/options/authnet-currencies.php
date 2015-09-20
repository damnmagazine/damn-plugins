<?php
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");
?>

<optgroup label="<?php echo esc_attr (_x ("Currency", "s2member-admin", "s2member")); ?>">
<option value="USD" selected="selected">USD</option>
<option value="AUD">AUD</option>
<option value="CAD">CAD</option>
<option value="EUR">EUR</option>
<option value="GBP">GBP</option>
</optgroup>