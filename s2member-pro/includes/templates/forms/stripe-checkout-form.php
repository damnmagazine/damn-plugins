<?php
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");
?>

<div id="s2p-form"></div><!-- This is for hash anchors; do NOT remove please. -->

<form id="s2member-pro-stripe-checkout-form" class="s2member-pro-stripe-form s2member-pro-stripe-checkout-form" method="post" action="%%action%%" autocomplete="off">

	<!-- Response Section (this is auto-filled after form submission). -->
	<div id="s2member-pro-stripe-checkout-form-response-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-checkout-form-section s2member-pro-stripe-form-response-section s2member-pro-stripe-checkout-form-response-section">
		<div id="s2member-pro-stripe-checkout-form-response-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-response-div s2member-pro-stripe-checkout-form-response-div">
			%%response%%
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Options Section (this is filled by Shortcode options; when/if specified). -->
	<div id="s2member-pro-stripe-checkout-form-options-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-checkout-form-section s2member-pro-stripe-form-options-section s2member-pro-stripe-checkout-form-options-section">
		<div id="s2member-pro-stripe-checkout-form-options-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-checkout-form-section-title s2member-pro-stripe-form-options-section-title s2member-pro-stripe-checkout-form-options-section-title">
			<?php echo _x("Checkout Options", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-stripe-checkout-form-options-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-options-div s2member-pro-stripe-checkout-form-options-div">
			<select name="s2p-option" id="s2member-pro-stripe-checkout-options" class="s2member-pro-stripe-options s2member-pro-stripe-checkout-options form-control" tabindex="-1">
				%%options%%
			</select>
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Checkout Description (this is the desc="" attribute from your Shortcode). -->
	<div id="s2member-pro-stripe-checkout-form-description-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-checkout-form-section s2member-pro-stripe-form-description-section s2member-pro-stripe-checkout-form-description-section">
		<div id="s2member-pro-stripe-checkout-form-description-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-description-div s2member-pro-stripe-checkout-form-description-div">
			%%description%%
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Coupon Code ( this will ONLY be displayed if your Shortcode has this attribute: accept_coupons="1" ). -->
	<div id="s2member-pro-stripe-checkout-form-coupon-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-checkout-form-section s2member-pro-stripe-form-coupon-section s2member-pro-stripe-checkout-form-coupon-section">
		<div id="s2member-pro-stripe-checkout-form-coupon-response-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-coupon-response-div s2member-pro-stripe-checkout-form-coupon-response-div">
			%%coupon_response%% <!-- A Coupon response (w/Discounts) will be displayed here; based on the Coupon Code that was entered. -->
		</div>
		<div id="s2member-pro-stripe-checkout-form-coupon-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-coupon-div s2member-pro-stripe-checkout-form-coupon-div">
			<label for="s2member-pro-stripe-checkout-coupon" id="s2member-pro-stripe-checkout-form-coupon-label" class="s2member-pro-stripe-form-coupon-label s2member-pro-stripe-checkout-form-coupon-label">
				<span><?php echo _x("Gift, Coupon, or Redemption Code?", "s2member-front", "s2member"); ?></span><br />
				<input type="text" maxlength="100" autocomplete="off" name="s2member_pro_stripe_checkout[coupon]" id="s2member-pro-stripe-checkout-coupon" class="s2member-pro-stripe-coupon s2member-pro-stripe-checkout-coupon form-control" value="%%coupon_value%%" tabindex="1" />
			</label>
			<input type="button" id="s2member-pro-stripe-checkout-coupon-apply" class="s2member-pro-stripe-coupon-apply s2member-pro-stripe-checkout-coupon-apply btn btn-default" value="<?php echo esc_attr(_x("Apply", "s2member-front", "s2member")); ?>" tabindex="-1" />
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Registration Details (Name, Email, Username, Password). -->
	<!-- Some of this information will be prefilled automatically when/if a User/Member is already logged-in. -->
	<!-- Name fields will NOT be hidden automatically here; even if your Registration/Profile Field options dictate this behavior. -->
	<div id="s2member-pro-stripe-checkout-form-registration-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-checkout-form-section s2member-pro-stripe-form-registration-section s2member-pro-stripe-checkout-form-registration-section">
		<div id="s2member-pro-stripe-checkout-form-registration-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-checkout-form-section-title s2member-pro-stripe-form-registration-section-title s2member-pro-stripe-checkout-form-registration-section-title">
			<?php echo _x("Create Profile", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-stripe-checkout-form-first-name-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-first-name-div s2member-pro-stripe-checkout-form-first-name-div">
			<label for="s2member-pro-stripe-checkout-first-name" id="s2member-pro-stripe-checkout-form-first-name-label" class="s2member-pro-stripe-form-first-name-label s2member-pro-stripe-checkout-form-first-name-label">
				<span><?php echo _x("First Name", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="50" autocomplete="off" name="s2member_pro_stripe_checkout[first_name]" id="s2member-pro-stripe-checkout-first-name" class="s2member-pro-stripe-first-name s2member-pro-stripe-checkout-first-name form-control" value="%%first_name_value%%" tabindex="10" />
			</label>
		</div>
		<div id="s2member-pro-stripe-checkout-form-last-name-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-last-name-div s2member-pro-stripe-checkout-form-last-name-div">
			<label for="s2member-pro-stripe-checkout-last-name" id="s2member-pro-stripe-checkout-form-last-name-label" class="s2member-pro-stripe-form-last-name-label s2member-pro-stripe-checkout-form-last-name-label">
				<span><?php echo _x("Last Name", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="50" autocomplete="off" name="s2member_pro_stripe_checkout[last_name]" id="s2member-pro-stripe-checkout-last-name" class="s2member-pro-stripe-last-name s2member-pro-stripe-checkout-last-name form-control" value="%%last_name_value%%" tabindex="20" />
			</label>
		</div>
		<div id="s2member-pro-stripe-checkout-form-email-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-email-div s2member-pro-stripe-checkout-form-email-div">
			<label for="s2member-pro-stripe-checkout-email" id="s2member-pro-stripe-checkout-form-email-label" class="s2member-pro-stripe-form-email-label s2member-pro-stripe-checkout-form-email-label">
				<span><?php echo _x("Email Address", "s2member-front", "s2member"); ?> *</span><br />
				<input type="email" aria-required="true" data-expected="email" maxlength="100" autocomplete="off" name="s2member_pro_stripe_checkout[email]" id="s2member-pro-stripe-checkout-email" class="s2member-pro-stripe-email s2member-pro-stripe-checkout-email form-control" value="%%email_value%%" tabindex="30" />
			</label>
		</div>
		<div id="s2member-pro-stripe-checkout-form-username-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-username-div s2member-pro-stripe-checkout-form-username-div">
			<label for="s2member-pro-stripe-checkout-username" id="s2member-pro-stripe-checkout-form-username-label" class="s2member-pro-stripe-form-username-label s2member-pro-stripe-checkout-form-username-label">
				<span><?php echo _x("Username (lowercase alphanumeric)", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="60" autocomplete="off" name="s2member_pro_stripe_checkout[username]" id="s2member-pro-stripe-checkout-username" class="s2member-pro-stripe-username s2member-pro-stripe-checkout-username form-control" value="%%username_value%%" tabindex="40" />
			</label>
		</div>
		<div id="s2member-pro-stripe-checkout-form-password-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-password-div s2member-pro-stripe-checkout-form-password-div">
			<label for="s2member-pro-stripe-checkout-password1" id="s2member-pro-stripe-checkout-form-password-label" class="s2member-pro-stripe-form-password-label s2member-pro-stripe-checkout-form-password-label">
				<span><?php echo _x("Password (type this twice please)", "s2member-front", "s2member"); ?> *</span><br />
				<input type="password" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_stripe_checkout[password1]" id="s2member-pro-stripe-checkout-password1" class="s2member-pro-stripe-password1 s2member-pro-stripe-checkout-password1 form-control" value="%%password1_value%%" tabindex="50" />
			</label>
			<input type="password" maxlength="100" autocomplete="off" name="s2member_pro_stripe_checkout[password2]" id="s2member-pro-stripe-checkout-password2" class="s2member-pro-stripe-password2 s2member-pro-stripe-checkout-password2 form-control" value="%%password2_value%%" tabindex="60" />
			<div id="s2member-pro-stripe-checkout-form-password-strength" class="ws-plugin--s2member-password-strength s2member-pro-stripe-form-password-strength s2member-pro-stripe-checkout-form-password-strength"><em><?php echo _x("password strength indicator", "s2member-front", "s2member"); ?></em></div>
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Custom Fields (Custom Registration/Profile Fields will appear here, when/if they've been configured). -->
	<!-- Custom Fields will NOT be displayed to existing Users/Members that are already logged-in. s2Member assumes this information has already been collected in that case. -->
	%%custom_fields%%

	<!-- Billing Method (powered by Stripe). -->
	<div id="s2member-pro-stripe-checkout-form-billing-method-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-checkout-form-section s2member-pro-stripe-form-billing-method-section s2member-pro-stripe-checkout-form-billing-method-section">
		<div id="s2member-pro-stripe-checkout-form-billing-method-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-checkout-form-section-title s2member-pro-stripe-form-billing-method-section-title s2member-pro-stripe-checkout-form-billing-method-section-title">
			<?php echo _x("Billing Method", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-stripe-checkout-form-source-token-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-source-token-div s2member-pro-stripe-checkout-form-source-token-div">
			<button id="s2member-pro-stripe-checkout-form-source-token-button" class="s2member-pro-stripe-form-source-token-button s2member-pro-stripe-checkout-form-source-token-button" type="button">
				<i><?php echo _x("[+]", "s2member-front", "s2member"); ?></i> <span><?php echo _x("Add Billing Method", "s2member-front", "s2member"); ?></span>
			</button>
			<div id="s2member-pro-stripe-checkout-form-source-token-summary" class="s2member-pro-stripe-form-source-token-summary s2member-pro-stripe-checkout-form-source-token-summary">
				%%source_token_summary%%
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Billing Address (hidden dynamically when/if no tax details are necessary; and/or when no billing info has been provided yet). -->
	<div id="s2member-pro-stripe-checkout-form-billing-address-section" class="s2member-pro-stripe-form-section  s2member-pro-stripe-checkout-form-section s2member-pro-stripe-form-billing-address-section s2member-pro-stripe-checkout-form-billing-address-section">
		<div id="s2member-pro-stripe-checkout-form-billing-address-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-checkout-form-section-title s2member-pro-stripe-form-billing-address-section-title s2member-pro-stripe-checkout-form-billing-address-section-title">
			<?php echo _x("Tax Location", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-stripe-checkout-form-state-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-state-div s2member-pro-stripe-checkout-form-state-div">
			<label for="s2member-pro-stripe-checkout-state" id="s2member-pro-stripe-checkout-form-state-label" class="s2member-pro-stripe-form-state-label s2member-pro-stripe-checkout-form-state-label">
				<span><?php echo _x("State / Province", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="40" autocomplete="off" name="s2member_pro_stripe_checkout[state]" id="s2member-pro-stripe-checkout-state" class="s2member-pro-stripe-state s2member-pro-stripe-checkout-state form-control" value="%%state_value%%" tabindex="320" />
			</label>
		</div>
		<div id="s2member-pro-stripe-checkout-form-zip-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-zip-div s2member-pro-stripe-checkout-form-zip-div">
			<label for="s2member-pro-stripe-checkout-zip" id="s2member-pro-stripe-checkout-form-zip-label" class="s2member-pro-stripe-form-zip-label s2member-pro-stripe-checkout-form-zip-label">
				<span><?php echo _x("Postal / Zip Code", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="20" autocomplete="off" name="s2member_pro_stripe_checkout[zip]" id="s2member-pro-stripe-checkout-zip" class="s2member-pro-stripe-zip s2member-pro-stripe-checkout-zip form-control" value="%%zip_value%%" tabindex="330" />
			</label>
		</div>
		<div id="s2member-pro-stripe-checkout-form-country-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-country-div s2member-pro-stripe-checkout-form-country-div">
			<label for="s2member-pro-stripe-checkout-country" id="s2member-pro-stripe-checkout-form-country-label" class="s2member-pro-stripe-form-country-label s2member-pro-stripe-checkout-form-country-label">
				<span><?php echo _x("Country", "s2member-front", "s2member"); ?> *</span><br />
				<select aria-required="true" name="s2member_pro_stripe_checkout[country]" id="s2member-pro-stripe-checkout-country" class="s2member-pro-stripe-country s2member-pro-stripe-checkout-country form-control" tabindex="340">
					%%country_options%%
				</select>
			</label>
		</div>
		<div id="s2member-pro-stripe-checkout-form-ajax-tax-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-ajax-tax-div s2member-pro-stripe-checkout-form-ajax-tax-div">
			<!-- Sales Tax will be displayed here via Ajax; based on state, country, and/or zip code range. -->
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Captcha ( A reCaptcha section, with a required security code will appear here; if captcha="1" ). -->
	%%captcha%%

	<!-- Checkout Now (this holds the submit button, and also some dynamic hidden input variables). -->
	<div id="s2member-pro-stripe-checkout-form-submission-section" class="s2member-pro-stripe-form-section s2member-pro-stripe-checkout-form-section s2member-pro-stripe-form-submission-section s2member-pro-stripe-checkout-form-submission-section">
		<div id="s2member-pro-stripe-checkout-form-submission-section-title" class="s2member-pro-stripe-form-section-title s2member-pro-stripe-checkout-form-section-title s2member-pro-stripe-checkout-form-submission-section-title">
			<?php echo _x("Checkout Now", "s2member-front", "s2member"); ?>
		</div>
		%%opt_in%% <!-- s2Member will fill this when/if there are list servers integrated, and the Opt-In Box is turned on. -->
		<div id="s2member-pro-stripe-checkout-form-submit-div" class="s2member-pro-stripe-form-div s2member-pro-stripe-checkout-form-div s2member-pro-stripe-form-submit-div s2member-pro-stripe-checkout-form-submit-div">
			%%hidden_inputs%% <!-- Auto-filled by the s2Member software. Do NOT remove this under any circumstance. -->
			<button type="submit" id="s2member-pro-stripe-checkout-submit" class="s2member-pro-stripe-submit s2member-pro-stripe-checkout-submit btn btn-primary" tabindex="600"><?php echo esc_html(_x("Submit Form", "s2member-front", "s2member")); ?></button>
		</div>
		<div style="clear:both;"></div>
	</div>
</form>