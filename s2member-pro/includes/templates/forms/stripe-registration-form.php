<?php
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");
?>

<div id="s2p-form"></div><!-- This is for hash anchors; do NOT remove please. -->

<form id="s2member-pro-stripe-registration-form" class="s2member-pro-stripe-form s2member-pro-stripe-registration-form" method="post" action="%%action%%" autocomplete="off">

	<!-- Response Section (this is auto-filled after form submission). -->
	<div id="s2member-pro-stripe-registration-form-response-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-registration-form-section s2member-pro-stripe-form-response-section s2member-pro-stripe-registration-form-response-section">
		<div id="s2member-pro-stripe-registration-form-response-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-registration-form-div s2member-pro-stripe-form-response-div s2member-pro-stripe-registration-form-response-div">
			%%response%%
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Options Section (this is filled by Shortcode options; when/if specified). -->
	<div id="s2member-pro-stripe-registration-form-options-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-registration-form-section s2member-pro-stripe-form-options-section s2member-pro-stripe-registration-form-options-section">
		<div id="s2member-pro-stripe-registration-form-options-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-registration-form-section-title s2member-pro-stripe-form-options-section-title s2member-pro-stripe-registration-form-options-section-title">
			<?php echo _x("Registration Options", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-stripe-registration-form-options-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-registration-form-div s2member-pro-stripe-form-options-div s2member-pro-stripe-registration-form-options-div">
			<select name="s2p-option" id="s2member-pro-stripe-registration-options" class="s2member-pro-stripe-options s2member-pro-stripe-registration-options form-control" tabindex="-1">
				%%options%%
			</select>
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Registration Description (this is the desc="" attribute from your Shortcode). -->
	<div id="s2member-pro-stripe-registration-form-description-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-registration-form-section s2member-pro-stripe-form-description-section s2member-pro-stripe-registration-form-description-section">
		<div id="s2member-pro-stripe-registration-form-description-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-registration-form-div s2member-pro-stripe-form-description-div s2member-pro-stripe-registration-form-description-div">
			%%description%%
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Registration Details (Name, Email, Username, Password). -->
	<!-- Name fields will be hidden automatically when/if your Registration/Profile Field options dictate this behavior. -->
	<div id="s2member-pro-stripe-registration-form-registration-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-registration-form-section s2member-pro-stripe-form-registration-section s2member-pro-stripe-registration-form-registration-section">
		<div id="s2member-pro-stripe-registration-form-registration-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-registration-form-section-title s2member-pro-stripe-form-registration-section-title s2member-pro-stripe-registration-form-registration-section-title">
			<?php echo _x ("Create Profile", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-stripe-registration-form-first-name-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-registration-form-div s2member-pro-stripe-form-first-name-div s2member-pro-stripe-registration-form-first-name-div">
			<label for="s2member-pro-stripe-registration-first-name" id="s2member-pro-stripe-registration-form-first-name-label" class="s2member-pro-stripe-form-first-name-label s2member-pro-stripe-registration-form-first-name-label">
				<span><?php echo _x ("First Name", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_stripe_registration[first_name]" id="s2member-pro-stripe-registration-first-name" class="s2member-pro-stripe-first-name s2member-pro-stripe-registration-first-name form-control" value="%%first_name_value%%" tabindex="10" />
			</label>
		</div>
		<div id="s2member-pro-stripe-registration-form-last-name-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-registration-form-div s2member-pro-stripe-form-last-name-div s2member-pro-stripe-registration-form-last-name-div">
			<label for="s2member-pro-stripe-registration-last-name" id="s2member-pro-stripe-registration-form-last-name-label" class="s2member-pro-stripe-form-last-name-label s2member-pro-stripe-registration-form-last-name-label">
				<span><?php echo _x ("Last Name", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_stripe_registration[last_name]" id="s2member-pro-stripe-registration-last-name" class="s2member-pro-stripe-last-name s2member-pro-stripe-registration-last-name form-control" value="%%last_name_value%%" tabindex="20" />
			</label>
		</div>
		<div id="s2member-pro-stripe-registration-form-email-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-registration-form-div s2member-pro-stripe-form-email-div s2member-pro-stripe-registration-form-email-div">
			<label for="s2member-pro-stripe-registration-email" id="s2member-pro-stripe-registration-form-email-label" class="s2member-pro-stripe-form-email-label s2member-pro-stripe-registration-form-email-label">
				<span><?php echo _x ("Email Address", "s2member-front", "s2member"); ?> *</span><br />
				<input type="email" aria-required="true" data-expected="email" maxlength="100" autocomplete="off" name="s2member_pro_stripe_registration[email]" id="s2member-pro-stripe-registration-email" class="s2member-pro-stripe-email s2member-pro-stripe-registration-email form-control" value="%%email_value%%" tabindex="30" />
			</label>
		</div>
		<div id="s2member-pro-stripe-registration-form-username-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-registration-form-div s2member-pro-stripe-form-username-div s2member-pro-stripe-registration-form-username-div">
			<label for="s2member-pro-stripe-registration-username" id="s2member-pro-stripe-registration-form-username-label" class="s2member-pro-stripe-form-username-label s2member-pro-stripe-registration-form-username-label">
				<span><?php echo _x ("Username (lowercase alphanumeric)", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="60" autocomplete="off" name="s2member_pro_stripe_registration[username]" id="s2member-pro-stripe-registration-username" class="s2member-pro-stripe-username s2member-pro-stripe-registration-username form-control" value="%%username_value%%" tabindex="40" />
			</label>
		</div>
		<div id="s2member-pro-stripe-registration-form-password-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-registration-form-div s2member-pro-stripe-form-password-div s2member-pro-stripe-registration-form-password-div">
			<label for="s2member-pro-stripe-registration-password1" id="s2member-pro-stripe-registration-form-password-label" class="s2member-pro-stripe-form-password-label s2member-pro-stripe-registration-form-password-label">
				<span><?php echo _x ("Password (type this twice please)", "s2member-front", "s2member"); ?> *</span><br />
				<input type="password" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_stripe_registration[password1]" id="s2member-pro-stripe-registration-password1" class="s2member-pro-stripe-password1 s2member-pro-stripe-registration-password1 form-control" value="%%password1_value%%" tabindex="50" />
			</label>
			<input type="password" maxlength="100" autocomplete="off" name="s2member_pro_stripe_registration[password2]" id="s2member-pro-stripe-registration-password2" class="s2member-pro-stripe-password2 s2member-pro-stripe-registration-password2 form-control" value="%%password2_value%%" tabindex="60" />
			<div id="s2member-pro-stripe-registration-form-password-strength" class="ws-plugin--s2member-password-strength s2member-pro-stripe-form-password-strength s2member-pro-stripe-registration-form-password-strength"><em><?php echo _x ("password strength indicator", "s2member-front", "s2member"); ?></em></div>
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Custom Fields (Custom Registration/Profile Fields will appear here, when/if they've been configured). -->
	%%custom_fields%%

	<!-- Captcha ( A reCaptcha section, with a required security code will appear here; if captcha="1" ). -->
	%%captcha%%

	<!-- Complete Registration (this holds the submit button, and also some dynamic hidden input variables). -->
	<div id="s2member-pro-stripe-registration-form-submission-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-registration-form-section s2member-pro-stripe-form-submission-section s2member-pro-stripe-registration-form-submission-section">
		<div id="s2member-pro-stripe-registration-form-submission-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-registration-form-section-title s2member-pro-stripe-form-submission-section-title s2member-pro-stripe-registration-form-submission-section-title">
			<?php echo _x ("Complete Registration", "s2member-front", "s2member"); ?>
		</div>
		%%opt_in%% <!-- s2Member will fill this when/if there are list servers integrated, and the Opt-In Box is turned on. -->
		<div id="s2member-pro-stripe-registration-form-submit-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-registration-form-div s2member-pro-stripe-form-submit-div s2member-pro-stripe-registration-form-submit-div">
			%%hidden_inputs%% <!-- Auto-filled by the s2Member software. Do NOT remove this under any circumstance. -->
			<button type="submit" id="s2member-pro-stripe-registration-submit" class="s2member-pro-stripe-submit s2member-pro-stripe-registration-submit btn btn-primary" tabindex="400"><?php echo esc_html (_x ("Submit Form", "s2member-front", "s2member")); ?></button>
		</div>
		<div style="clear:both;"></div>
	</div>
</form>