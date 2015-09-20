<?php
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");

/** @var $attr array */
/** @var $s_var string */
/** @var $p_var string */
/** @var $member_list_query array */
/** @var $query WP_User_Query */
/** @var $pagination array */
$query      = $member_list_query["query"];
$pagination = $member_list_query["pagination"];

if(!empty($_REQUEST[$s_var]))
	$s_val = trim(stripslashes($_REQUEST[$s_var]));
else $s_val = ""; // No query yet.
?>
<div class="ws-plugin--s2member-list-wrapper">
	<div class="ws-plugin--s2member-list-container">
		<div class="ws-plugin--s2member-list">

			<?php if($query->get_total()): ?>

				<ul class="ws-plugin--s2member-list-users">
					<?php foreach($query->get_results() as $_user): /** @var $_user WP_User */ ?>
						<li class="ws-plugin--s2member-list-user ws-plugin--s2member-clearfix">

							<?php if($attr["avatar_size"] && $attr["show_avatar"] && ($_avatar = get_avatar($_user->ID, $attr["avatar_size"]))): ?>
								<div class="ws-plugin--s2member-list-user-avatar">
									<?php if(($_avatar_link = c_ws_plugin__s2member_pro_sc_member_list_in::parse_replacement_codes($attr["link_avatar"], $_user))): ?>
										<a href="<?php echo esc_attr($_avatar_link); ?>"<?php echo c_ws_plugin__s2member_pro_sc_member_list_in::link_attributes($_avatar_link); ?>><?php echo $_avatar; ?></a>
									<?php else: echo $_avatar; endif; ?>
								</div>
							<?php endif; ?>

							<?php if($attr["show_display_name"] && $_user->display_name): ?>
								<div class="ws-plugin--s2member-list-user-display-name">
									<?php if(($_display_name_link = c_ws_plugin__s2member_pro_sc_member_list_in::parse_replacement_codes($attr["link_display_name"], $_user))): ?>
										<a href="<?php echo esc_attr($_display_name_link); ?>"<?php echo c_ws_plugin__s2member_pro_sc_member_list_in::link_attributes($_display_name_link); ?>><?php echo esc_html($_user->display_name); ?></a>
									<?php else: echo esc_html($_user->display_name); endif; ?>
								</div>
							<?php endif; ?>

							<?php if(($_fields = preg_split('/[,]+/', $attr["show_fields"], NULL, PREG_SPLIT_NO_EMPTY))): ?>
								<table class="ws-plugin--s2member-list-user-fields">
									<tbody>
									<?php foreach($_fields as $_field): ?>
										<?php
										if(strpos($_field, ":") !== FALSE)
											list($_field_label, $_field) = explode(":", $_field, 2);
										else $_field_label = ucwords(preg_replace('/[^a-z0-9]+/i', " ", $_field));

										if(!($_field_label = trim($_field_label)))
											continue; // Empty.

										if(!($_field = trim($_field)))
											continue; // Empty.

										$_field_value = get_user_field($_field, $_user->ID);
										if($_field_value && is_array($_field_value))
											$_field_value = implode(", ", $_field_value);
										else $_field_value = (string)$_field_value;

										$_field_label = esc_html($_field_label);
										$_field_value = make_clickable(esc_html($_field_value));
										$_field_value = preg_replace_callback('|<a (.+?)>|i', 'wp_rel_nofollow_callback', $_field_value);
										if(is_numeric($_field_value) && strlen($_field_value) === 10) // Convert timestamps to a date string.
											$_field_value = date_i18n(get_option("date_format")." ".get_option("time_format"), (integer)$_field_value, TRUE);

										$_field_label = apply_filters("ws_plugin__s2member_pro_sc_member_list_field_label", $_field_label, get_defined_vars());
										$_field_value = apply_filters("ws_plugin__s2member_pro_sc_member_list_field_value", $_field_value, get_defined_vars());
										?>
										<?php if($_field_label && $_field_value): ?>
											<tr>
												<td>
													<span title="<?php echo esc_attr(strip_tags($_field_label)); ?>"><?php echo $_field_label; ?></span>
												</td>
												<td>
													<span title="<?php echo esc_attr(strip_tags($_field_value)); ?>"><?php echo $_field_value; ?></span>
												</td>
											</tr>
										<?php endif; ?>
									<?php endforeach; ?>
									</tbody>
								</table>
							<?php endif; ?>

						</li>
					<?php endforeach; ?>
				</ul>

				<?php if(count($pagination) > 1): ?>
					<ul class="ws-plugin--s2member-list-pagination">
						<li><?php echo _x("Page:", "s2member-front", "s2member"); ?></li>
						<?php foreach($pagination as $_page): ?>
							<li><?php echo $_page["link"]; ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

			<?php elseif($attr["enable_list_search"] && $s_val): ?>
				<p><?php echo _x('Sorry, your search returned 0 results.', "s2member-front", "s2member"); ?></p>

			<?php elseif(!$attr["enable_list_search"] && $s_val): ?>
				<p><?php echo _x('Sorry, search is not allowed here. The shortcode attribute `enable_list_search` is not enabled by the site owner.', "s2member-front", "s2member"); ?></p>

			<?php else: /* Generic message in this case. */ ?>
				<p><?php echo _x('Sorry, there are no users to list at this time.', "s2member-front", "s2member"); ?></p>
			<?php endif; ?>

		</div>
	</div>
</div>