<?php
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");

/** @var $attr array */
/** @var $s_var string */
/** @var $p_var string */

if(!empty($_REQUEST[$s_var]))
	$s_val = trim(stripslashes($_REQUEST[$s_var]));
else $s_val = ""; // No query yet.
?>
<div class="ws-plugin--s2member-list-search-box-wrapper">
	<div class="ws-plugin--s2member-list-search-box-container">
		<div class="ws-plugin--s2member-list-search-box">

			<form action="<?php echo esc_attr($attr["action"]); ?>" method="get" autocomplete="off">
				%%hidden_inputs%% <!-- Replaced dynamically by s2Member. Please leave this here. -->
				<table>
					<tbody>
					<tr>
						<td>
							<input type="text" name="<?php echo esc_attr($s_var); ?>" value="<?php echo esc_attr($s_val); ?>"
							       placeholder="<?php echo esc_attr($attr["placeholder"]); ?>" class="form-control" tabindex="-1" />
						</td>
						<td>
							<button type="submit" class="btn btn-default" tabindex="-1"><?php echo _x("Search", "s2member-front", "s2member"); ?></button>
						</td>
					</tr>
					</tbody>
				</table>
			</form>

		</div>
	</div>
</div>