<?php
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");
?>

<div id="s2p-form"></div><!-- This is for hash anchors; do NOT remove please. -->

<form id="s2member-pro-stripe-cancellation-form" class="s2member-pro-stripe-form s2member-pro-stripe-cancellation-form" method="post" action="%%action%%" autocomplete="off">

	<!-- Response Section (this is auto-filled after form submission). -->
	<div id="s2member-pro-stripe-cancellation-form-response-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-cancellation-form-section s2member-pro-stripe-form-response-section s2member-pro-stripe-cancellation-form-response-section">
		<div id="s2member-pro-stripe-cancellation-form-response-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-cancellation-form-div s2member-pro-stripe-form-response-div s2member-pro-stripe-cancellation-form-response-div">
			%%response%%
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Cancellation Description (this will display details about what they're cancelling). -->
	<div id="s2member-pro-stripe-cancellation-form-description-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-cancellation-form-section s2member-pro-stripe-form-description-section s2member-pro-stripe-cancellation-form-description-section">
		<div id="s2member-pro-stripe-cancellation-form-description-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-cancellation-form-div s2member-pro-stripe-form-description-div s2member-pro-stripe-cancellation-form-description-div">
			%%description%%
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Captcha ( A reCaptcha section, with a required security code will appear here; if captcha="1" ). -->
	%%captcha%%

	<!-- Confirm Cancellation (this holds the submit button, and also some dynamic hidden input variables). -->
	<div id="s2member-pro-stripe-cancellation-form-submission-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-cancellation-form-section s2member-pro-stripe-form-submission-section s2member-pro-stripe-cancellation-form-submission-section">
		<div id="s2member-pro-stripe-cancellation-form-submission-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-cancellation-form-section-title s2member-pro-stripe-form-submission-section-title s2member-pro-stripe-cancellation-form-submission-section-title">
			<?php echo _x ("Confirm Cancellation", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-stripe-cancellation-form-submit-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-cancellation-form-div s2member-pro-stripe-form-submit-div s2member-pro-stripe-cancellation-form-submit-div">
			%%hidden_inputs%% <!-- Auto-filled by the s2Member software. Do NOT remove this under any circumstance. -->
			<button type="submit" id="s2member-pro-stripe-cancellation-submit" class="s2member-pro-stripe-submit s2member-pro-stripe-cancellation-submit btn btn-warning" tabindex="100"><?php echo esc_html (_x ("Submit Form", "s2member-front", "s2member")); ?></button>
		</div>
		<div style="clear:both;"></div>
	</div>
</form>