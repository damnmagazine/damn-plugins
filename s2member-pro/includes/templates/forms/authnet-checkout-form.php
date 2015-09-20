<?php
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");
?>

<div id="s2p-form"></div><!-- This is for hash anchors; do NOT remove please. -->

<form id="s2member-pro-authnet-checkout-form" class="s2member-pro-authnet-form s2member-pro-authnet-checkout-form" method="post" action="%%action%%" autocomplete="off">

	<!-- Response Section (this is auto-filled after form submission). -->
	<div id="s2member-pro-authnet-checkout-form-response-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-checkout-form-section s2member-pro-authnet-form-response-section s2member-pro-authnet-checkout-form-response-section">
		<div id="s2member-pro-authnet-checkout-form-response-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-response-div s2member-pro-authnet-checkout-form-response-div">
			%%response%%
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Options Section (this is filled by Shortcode options; when/if specified). -->
	<div id="s2member-pro-authnet-checkout-form-options-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-checkout-form-section s2member-pro-authnet-form-options-section s2member-pro-authnet-checkout-form-options-section">
		<div id="s2member-pro-authnet-checkout-form-options-section-title" class="s2member-pro-authnet-form-section-title s2member-pro-authnet-checkout-form-section-title s2member-pro-authnet-form-options-section-title s2member-pro-authnet-checkout-form-options-section-title">
			<?php echo _x("Checkout Options", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-authnet-checkout-form-options-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-options-div s2member-pro-authnet-checkout-form-options-div">
			<select name="s2p-option" id="s2member-pro-authnet-checkout-options" class="s2member-pro-authnet-options s2member-pro-authnet-checkout-options form-control" tabindex="-1">
				%%options%%
			</select>
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Checkout Description (this is the desc="" attribute from your Shortcode). -->
	<div id="s2member-pro-authnet-checkout-form-description-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-checkout-form-section s2member-pro-authnet-form-description-section s2member-pro-authnet-checkout-form-description-section">
		<div id="s2member-pro-authnet-checkout-form-description-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-description-div s2member-pro-authnet-checkout-form-description-div">
			%%description%%
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Coupon Code ( this will ONLY be displayed if your Shortcode has this attribute: accept_coupons="1" ). -->
	<div id="s2member-pro-authnet-checkout-form-coupon-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-checkout-form-section s2member-pro-authnet-form-coupon-section s2member-pro-authnet-checkout-form-coupon-section">
		<div id="s2member-pro-authnet-checkout-form-coupon-response-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-coupon-response-div s2member-pro-authnet-checkout-form-coupon-response-div">
			%%coupon_response%% <!-- A Coupon response (w/Discounts) will be displayed here; based on the Coupon Code that was entered. -->
		</div>
		<div id="s2member-pro-authnet-checkout-form-coupon-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-coupon-div s2member-pro-authnet-checkout-form-coupon-div">
			<label for="s2member-pro-authnet-checkout-coupon" id="s2member-pro-authnet-checkout-form-coupon-label" class="s2member-pro-authnet-form-coupon-label s2member-pro-authnet-checkout-form-coupon-label">
				<span><?php echo _x("Gift, Coupon, or Redemption Code?", "s2member-front", "s2member"); ?></span><br />
				<input type="text" maxlength="100" autocomplete="off" name="s2member_pro_authnet_checkout[coupon]" id="s2member-pro-authnet-checkout-coupon" class="s2member-pro-authnet-coupon s2member-pro-authnet-checkout-coupon form-control" value="%%coupon_value%%" tabindex="1" />
			</label>
			<input type="button" id="s2member-pro-authnet-checkout-coupon-apply" class="s2member-pro-authnet-coupon-apply s2member-pro-authnet-checkout-coupon-apply btn btn-default" value="<?php echo esc_attr(_x("Apply", "s2member-front", "s2member")); ?>" tabindex="-1" />
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Registration Details (Name, Email, Username, Password). -->
	<!-- Some of this information will be prefilled automatically when/if a User/Member is already logged-in. -->
	<!-- Name fields will NOT be hidden automatically here; even if your Registration/Profile Field options dictate this behavior. -->
	<div id="s2member-pro-authnet-checkout-form-registration-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-checkout-form-section s2member-pro-authnet-form-registration-section s2member-pro-authnet-checkout-form-registration-section">
		<div id="s2member-pro-authnet-checkout-form-registration-section-title" class="s2member-pro-authnet-form-section-title s2member-pro-authnet-checkout-form-section-title s2member-pro-authnet-form-registration-section-title s2member-pro-authnet-checkout-form-registration-section-title">
			<?php echo _x("Create Profile", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-authnet-checkout-form-first-name-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-first-name-div s2member-pro-authnet-checkout-form-first-name-div">
			<label for="s2member-pro-authnet-checkout-first-name" id="s2member-pro-authnet-checkout-form-first-name-label" class="s2member-pro-authnet-form-first-name-label s2member-pro-authnet-checkout-form-first-name-label">
				<span><?php echo _x("First Name", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="50" autocomplete="off" name="s2member_pro_authnet_checkout[first_name]" id="s2member-pro-authnet-checkout-first-name" class="s2member-pro-authnet-first-name s2member-pro-authnet-checkout-first-name form-control" value="%%first_name_value%%" tabindex="10" />
			</label>
		</div>
		<div id="s2member-pro-authnet-checkout-form-last-name-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-last-name-div s2member-pro-authnet-checkout-form-last-name-div">
			<label for="s2member-pro-authnet-checkout-last-name" id="s2member-pro-authnet-checkout-form-last-name-label" class="s2member-pro-authnet-form-last-name-label s2member-pro-authnet-checkout-form-last-name-label">
				<span><?php echo _x("Last Name", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="50" autocomplete="off" name="s2member_pro_authnet_checkout[last_name]" id="s2member-pro-authnet-checkout-last-name" class="s2member-pro-authnet-last-name s2member-pro-authnet-checkout-last-name form-control" value="%%last_name_value%%" tabindex="20" />
			</label>
		</div>
		<div id="s2member-pro-authnet-checkout-form-email-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-email-div s2member-pro-authnet-checkout-form-email-div">
			<label for="s2member-pro-authnet-checkout-email" id="s2member-pro-authnet-checkout-form-email-label" class="s2member-pro-authnet-form-email-label s2member-pro-authnet-checkout-form-email-label">
				<span><?php echo _x("Email Address", "s2member-front", "s2member"); ?> *</span><br />
				<input type="email" aria-required="true" data-expected="email" maxlength="100" autocomplete="off" name="s2member_pro_authnet_checkout[email]" id="s2member-pro-authnet-checkout-email" class="s2member-pro-authnet-email s2member-pro-authnet-checkout-email form-control" value="%%email_value%%" tabindex="30" />
			</label>
		</div>
		<div id="s2member-pro-authnet-checkout-form-username-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-username-div s2member-pro-authnet-checkout-form-username-div">
			<label for="s2member-pro-authnet-checkout-username" id="s2member-pro-authnet-checkout-form-username-label" class="s2member-pro-authnet-form-username-label s2member-pro-authnet-checkout-form-username-label">
				<span><?php echo _x("Username (lowercase alphanumeric)", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="60" autocomplete="off" name="s2member_pro_authnet_checkout[username]" id="s2member-pro-authnet-checkout-username" class="s2member-pro-authnet-username s2member-pro-authnet-checkout-username form-control" value="%%username_value%%" tabindex="40" />
			</label>
		</div>
		<div id="s2member-pro-authnet-checkout-form-password-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-password-div s2member-pro-authnet-checkout-form-password-div">
			<label for="s2member-pro-authnet-checkout-password1" id="s2member-pro-authnet-checkout-form-password-label" class="s2member-pro-authnet-form-password-label s2member-pro-authnet-checkout-form-password-label">
				<span><?php echo _x("Password (type this twice please)", "s2member-front", "s2member"); ?> *</span><br />
				<input type="password" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_authnet_checkout[password1]" id="s2member-pro-authnet-checkout-password1" class="s2member-pro-authnet-password1 s2member-pro-authnet-checkout-password1 form-control" value="%%password1_value%%" tabindex="50" />
			</label>
			<input type="password" maxlength="100" autocomplete="off" name="s2member_pro_authnet_checkout[password2]" id="s2member-pro-authnet-checkout-password2" class="s2member-pro-authnet-password2 s2member-pro-authnet-checkout-password2 form-control" value="%%password2_value%%" tabindex="60" />
			<div id="s2member-pro-authnet-checkout-form-password-strength" class="ws-plugin--s2member-password-strength s2member-pro-authnet-form-password-strength s2member-pro-authnet-checkout-form-password-strength"><em><?php echo _x("password strength indicator", "s2member-front", "s2member"); ?></em></div>
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Custom Fields (Custom Registration/Profile Fields will appear here, when/if they've been configured). -->
	<!-- Custom Fields will NOT be displayed to existing Users/Members that are already logged-in. s2Member assumes this information has already been collected in that case. -->
	%%custom_fields%%

	<!-- Billing Method (Customers can use a Credit/Debit card only). -->
	<div id="s2member-pro-authnet-checkout-form-billing-method-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-checkout-form-section s2member-pro-authnet-form-billing-method-section s2member-pro-authnet-checkout-form-billing-method-section">
		<div id="s2member-pro-authnet-checkout-form-billing-method-section-title" class="s2member-pro-authnet-form-section-title s2member-pro-authnet-checkout-form-section-title s2member-pro-authnet-form-billing-method-section-title s2member-pro-authnet-checkout-form-billing-method-section-title">
			<?php echo _x("Billing Method", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-authnet-checkout-form-card-type-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-card-type-div s2member-pro-authnet-checkout-form-card-type-div">
			%%card_type_options%%
		</div>
		<div id="s2member-pro-authnet-checkout-form-card-number-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-card-number-div s2member-pro-authnet-checkout-form-card-number-div">
			<label for="s2member-pro-authnet-checkout-card-number" id="s2member-pro-authnet-checkout-form-card-number-label" class="s2member-pro-authnet-form-card-number-label s2member-pro-authnet-checkout-form-card-number-label">
				<span><?php echo _x("Card Number (no dashes or spaces)", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_authnet_checkout[card_number]" id="s2member-pro-authnet-checkout-card-number" class="s2member-pro-authnet-card-number s2member-pro-authnet-checkout-card-number form-control" value="%%card_number_value%%" tabindex="210" />
			</label>
		</div>
		<div id="s2member-pro-authnet-checkout-form-card-expiration-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-card-expiration-div s2member-pro-authnet-checkout-form-card-expiration-div">
			<label for="s2member-pro-authnet-checkout-card-expiration" id="s2member-pro-authnet-checkout-form-card-expiration-label" class="s2member-pro-authnet-form-card-expiration-label s2member-pro-authnet-checkout-form-card-expiration-label">
				<span><?php echo _x("Card Expiration Date (mm/yyyy)", "s2member-front", "s2member"); ?> *</span><br />
				<select aria-required="true" autocomplete="off" name="s2member_pro_authnet_checkout[card_expiration_month]" id="s2member-pro-authnet-checkout-card-expiration-month" class="s2member-pro-authnet-card-expiration-month s2member-pro-authnet-checkout-card-expiration-month form-control" tabindex="220">
					%%card_expiration_month_options%%
				</select>
				<select aria-required="true" autocomplete="off" name="s2member_pro_authnet_checkout[card_expiration_year]" id="s2member-pro-authnet-checkout-card-expiration-year" class="s2member-pro-authnet-card-expiration-year s2member-pro-authnet-checkout-card-expiration-year form-control" tabindex="221">
					%%card_expiration_year_options%%
				</select>
			</label>
			<div style="clear:both;"></div>
		</div>
		<div id="s2member-pro-authnet-checkout-form-card-verification-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-card-verification-div s2member-pro-authnet-checkout-form-card-verification-div">
			<label for="s2member-pro-authnet-checkout-card-verification" id="s2member-pro-authnet-checkout-form-card-verification-label" class="s2member-pro-authnet-form-card-verification-label s2member-pro-authnet-checkout-form-card-verification-label">
				<span><?php echo _x("Card Verification Code", "s2member-front", "s2member"); ?> * <a href="https://en.wikipedia.org/wiki/Card_security_code" target="_blank" tabindex="-1" rel="external nofollow"><?php echo _x("need help?", "s2member-front", "s2member"); ?></a></span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_authnet_checkout[card_verification]" id="s2member-pro-authnet-checkout-card-verification" class="s2member-pro-authnet-card-verification s2member-pro-authnet-checkout-card-verification form-control" value="%%card_verification_value%%" tabindex="230" />
			</label>
		</div>
		<!-- This is displayed only when Maestro/Solo cards are selected as the Payment Method. -->
		<div id="s2member-pro-authnet-checkout-form-card-start-date-issue-number-div" class="s2member-pro-authnet-form-div  s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-card-start-date-issue-number-div s2member-pro-authnet-checkout-form-card-start-date-issue-number-div">
			<label for="s2member-pro-authnet-checkout-card-start-date-issue-number" id="s2member-pro-authnet-checkout-form-card-start-date-issue-number-label" class="s2member-pro-authnet-form-card-start-date-issue-number-label s2member-pro-authnet-checkout-form-card-start-date-issue-number-label">
				<span><?php echo _x("Card Start Date (mm/yyyy), or Issue Number", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_authnet_checkout[card_start_date_issue_number]" id="s2member-pro-authnet-checkout-card-start-date-issue-number" class="s2member-pro-authnet-card-start-date-issue-number s2member-pro-authnet-checkout-card-start-date-issue-number form-control" value="%%card_start_date_issue_number_value%%" tabindex="240" />
			</label>
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Billing Address (hidden dynamically when/if no Payment Method is selected yet). -->
	<div id="s2member-pro-authnet-checkout-form-billing-address-section" class="s2member-pro-authnet-form-section  s2member-pro-authnet-checkout-form-section s2member-pro-authnet-form-billing-address-section s2member-pro-authnet-checkout-form-billing-address-section">
		<div id="s2member-pro-authnet-checkout-form-billing-address-section-title" class="s2member-pro-authnet-form-section-title s2member-pro-authnet-checkout-form-section-title s2member-pro-authnet-form-billing-address-section-title s2member-pro-authnet-checkout-form-billing-address-section-title">
			<?php echo _x("Billing Address", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-authnet-checkout-form-street-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-street-div s2member-pro-authnet-checkout-form-street-div">
			<label for="s2member-pro-authnet-checkout-street" id="s2member-pro-authnet-checkout-form-street-label" class="s2member-pro-authnet-form-street-label s2member-pro-authnet-checkout-form-street-label">
				<span><?php echo _x("Street Address", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="60" autocomplete="off" name="s2member_pro_authnet_checkout[street]" id="s2member-pro-authnet-checkout-street" class="s2member-pro-authnet-street s2member-pro-authnet-checkout-street form-control" value="%%street_value%%" tabindex="300" />
			</label>
		</div>
		<div id="s2member-pro-authnet-checkout-form-city-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-city-div s2member-pro-authnet-checkout-form-city-div">
			<label for="s2member-pro-authnet-checkout-city" id="s2member-pro-authnet-checkout-form-city-label" class="s2member-pro-authnet-form-city-label s2member-pro-authnet-checkout-form-city-label">
				<span><?php echo _x("City / Town", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="40" autocomplete="off" name="s2member_pro_authnet_checkout[city]" id="s2member-pro-authnet-checkout-city" class="s2member-pro-authnet-city s2member-pro-authnet-checkout-city form-control" value="%%city_value%%" tabindex="310" />
			</label>
		</div>
		<div id="s2member-pro-authnet-checkout-form-state-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-state-div s2member-pro-authnet-checkout-form-state-div">
			<label for="s2member-pro-authnet-checkout-state" id="s2member-pro-authnet-checkout-form-state-label" class="s2member-pro-authnet-form-state-label s2member-pro-authnet-checkout-form-state-label">
				<span><?php echo _x("State / Province", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="40" autocomplete="off" name="s2member_pro_authnet_checkout[state]" id="s2member-pro-authnet-checkout-state" class="s2member-pro-authnet-state s2member-pro-authnet-checkout-state form-control" value="%%state_value%%" tabindex="320" />
			</label>
		</div>
		<div id="s2member-pro-authnet-checkout-form-zip-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-zip-div s2member-pro-authnet-checkout-form-zip-div">
			<label for="s2member-pro-authnet-checkout-zip" id="s2member-pro-authnet-checkout-form-zip-label" class="s2member-pro-authnet-form-zip-label s2member-pro-authnet-checkout-form-zip-label">
				<span><?php echo _x("Postal / Zip Code", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="20" autocomplete="off" name="s2member_pro_authnet_checkout[zip]" id="s2member-pro-authnet-checkout-zip" class="s2member-pro-authnet-zip s2member-pro-authnet-checkout-zip form-control" value="%%zip_value%%" tabindex="330" />
			</label>
		</div>
		<div id="s2member-pro-authnet-checkout-form-country-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-country-div s2member-pro-authnet-checkout-form-country-div">
			<label for="s2member-pro-authnet-checkout-country" id="s2member-pro-authnet-checkout-form-country-label" class="s2member-pro-authnet-form-country-label s2member-pro-authnet-checkout-form-country-label">
				<span><?php echo _x("Country", "s2member-front", "s2member"); ?> *</span><br />
				<select aria-required="true" name="s2member_pro_authnet_checkout[country]" id="s2member-pro-authnet-checkout-country" class="s2member-pro-authnet-country s2member-pro-authnet-checkout-country form-control" tabindex="340">
					%%country_options%%
				</select>
			</label>
		</div>
		<div id="s2member-pro-authnet-checkout-form-ajax-tax-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-ajax-tax-div s2member-pro-authnet-checkout-form-ajax-tax-div">
			<!-- Sales Tax will be displayed here via Ajax; based on state, country, and/or zip code range. -->
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Captcha ( A reCaptcha section, with a required security code will appear here; if captcha="1" ). -->
	%%captcha%%

	<!-- Checkout Now (this holds the submit button, and also some dynamic hidden input variables). -->
	<div id="s2member-pro-authnet-checkout-form-submission-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-checkout-form-section s2member-pro-authnet-form-submission-section s2member-pro-authnet-checkout-form-submission-section">
		<div id="s2member-pro-authnet-checkout-form-submission-section-title" class="s2member-pro-authnet-form-section-title s2member-pro-authnet-checkout-form-section-title s2member-pro-authnet-checkout-form-submission-section-title">
			<?php echo _x("Checkout Now", "s2member-front", "s2member"); ?>
		</div>
		%%opt_in%% <!-- s2Member will fill this when/if there are list servers integrated, and the Opt-In Box is turned on. -->
		<div id="s2member-pro-authnet-checkout-form-submit-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-submit-div s2member-pro-authnet-checkout-form-submit-div">
			%%hidden_inputs%% <!-- Auto-filled by the s2Member software. Do NOT remove this under any circumstance. -->
			<button type="submit" id="s2member-pro-authnet-checkout-submit" class="s2member-pro-authnet-submit s2member-pro-authnet-checkout-submit btn btn-primary" tabindex="600"><?php echo esc_html(_x("Submit Form", "s2member-front", "s2member")); ?></button>
		</div>
		<div style="clear:both;"></div>
	</div>
</form>
