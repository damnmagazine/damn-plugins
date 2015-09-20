<?php
if(!defined('WPINC')) // MUST have WordPress.
	exit("Do not access this file directly.");
?>

<div id="s2p-form"></div><!-- This is for hash anchors; do NOT remove please. -->

<form id="s2member-pro-paypal-sp-checkout-form" class="s2member-pro-paypal-form s2member-pro-paypal-sp-checkout-form" method="post" action="%%action%%" autocomplete="off">

	<!-- Response Section (this is auto-filled after form submission). -->
	<div id="s2member-pro-paypal-sp-checkout-form-response-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-sp-checkout-form-section s2member-pro-paypal-form-response-section s2member-pro-paypal-sp-checkout-form-response-section">
		<div id="s2member-pro-paypal-sp-checkout-form-response-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-response-div s2member-pro-paypal-sp-checkout-form-response-div">
			%%response%%
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Options Section (this is filled by Shortcode options; when/if specified). -->
	<div id="s2member-pro-paypal-sp-checkout-form-options-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-sp-checkout-form-section s2member-pro-paypal-form-options-section s2member-pro-paypal-sp-checkout-form-options-section">
		<div id="s2member-pro-paypal-sp-checkout-form-options-section-title" class="s2member-pro-paypal-form-section-title s2member-pro-paypal-sp-checkout-form-section-title s2member-pro-paypal-form-options-section-title s2member-pro-paypal-sp-checkout-form-options-section-title">
			<?php echo _x("Checkout Options", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-options-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-options-div s2member-pro-paypal-sp-checkout-form-options-div">
			<select name="s2p-option" id="s2member-pro-paypal-sp-checkout-options" class="s2member-pro-paypal-options s2member-pro-paypal-sp-checkout-options form-control" tabindex="-1">
				%%options%%
			</select>
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Checkout Description (this is the desc="" attribute from your Shortcode). -->
	<div id="s2member-pro-paypal-sp-checkout-form-description-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-sp-checkout-form-section s2member-pro-paypal-form-description-section s2member-pro-paypal-sp-checkout-form-description-section">
		<div id="s2member-pro-paypal-sp-checkout-form-description-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-description-div s2member-pro-paypal-sp-checkout-form-description-div">
			%%description%%
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Coupon Code ( this will ONLY be displayed if your Shortcode has this attribute: accept_coupons="1" ). -->
	<div id="s2member-pro-paypal-sp-checkout-form-coupon-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-sp-checkout-form-section s2member-pro-paypal-form-coupon-section s2member-pro-paypal-sp-checkout-form-coupon-section">
		<div id="s2member-pro-paypal-sp-checkout-form-coupon-response-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-coupon-response-div s2member-pro-paypal-sp-checkout-form-coupon-response-div">
			%%coupon_response%% <!-- A Coupon response (w/Discounts) will be displayed here; based on the Coupon Code that was entered. -->
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-coupon-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-coupon-div s2member-pro-paypal-sp-checkout-form-coupon-div">
			<label for="s2member-pro-paypal-sp-checkout-coupon" id="s2member-pro-paypal-sp-checkout-form-coupon-label" class="s2member-pro-paypal-form-coupon-label s2member-pro-paypal-sp-checkout-form-coupon-label">
				<span><?php echo _x("Gift, Coupon, or Redemption Code?", "s2member-front", "s2member"); ?></span><br />
				<input type="text" maxlength="100" autocomplete="off" name="s2member_pro_paypal_sp_checkout[coupon]" id="s2member-pro-paypal-sp-checkout-coupon" class="s2member-pro-paypal-coupon s2member-pro-paypal-sp-checkout-coupon form-control" value="%%coupon_value%%" tabindex="1" />
			</label>
			<input type="button" id="s2member-pro-paypal-sp-checkout-coupon-apply" class="s2member-pro-paypal-coupon-apply s2member-pro-paypal-sp-checkout-coupon-apply btn btn-default" value="<?php echo esc_attr(_x("Apply", "s2member-front", "s2member")); ?>" tabindex="-1" />
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Contact Info/Details (Name, Email). -->
	<!-- Some of this information will be prefilled automatically when/if a User/Member is already logged-in. -->
	<!-- Name fields will NOT be hidden automatically here; even if your Registration/Profile Field options dictate this behavior. -->
	<div id="s2member-pro-paypal-sp-checkout-form-registration-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-sp-checkout-form-section s2member-pro-paypal-form-registration-section s2member-pro-paypal-sp-checkout-form-registration-section">
		<div id="s2member-pro-paypal-sp-checkout-form-registration-section-title" class="s2member-pro-paypal-form-section-title s2member-pro-paypal-sp-checkout-form-section-title s2member-pro-paypal-form-registration-section-title s2member-pro-paypal-sp-checkout-form-registration-section-title">
			<?php echo _x("Contact Info", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-first-name-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-first-name-div s2member-pro-paypal-sp-checkout-form-first-name-div">
			<label for="s2member-pro-paypal-sp-checkout-first-name" id="s2member-pro-paypal-sp-checkout-form-first-name-label" class="s2member-pro-paypal-form-first-name-label s2member-pro-paypal-sp-checkout-form-first-name-label">
				<span><?php echo _x("First Name", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_paypal_sp_checkout[first_name]" id="s2member-pro-paypal-sp-checkout-first-name" class="s2member-pro-paypal-first-name s2member-pro-paypal-sp-checkout-first-name form-control" value="%%first_name_value%%" tabindex="10" />
			</label>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-last-name-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-last-name-div s2member-pro-paypal-sp-checkout-form-last-name-div">
			<label for="s2member-pro-paypal-sp-checkout-last-name" id="s2member-pro-paypal-sp-checkout-form-last-name-label" class="s2member-pro-paypal-form-last-name-label s2member-pro-paypal-sp-checkout-form-last-name-label">
				<span><?php echo _x("Last Name", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_paypal_sp_checkout[last_name]" id="s2member-pro-paypal-sp-checkout-last-name" class="s2member-pro-paypal-last-name s2member-pro-paypal-sp-checkout-last-name form-control" value="%%last_name_value%%" tabindex="20" />
			</label>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-email-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-email-div s2member-pro-paypal-sp-checkout-form-email-div">
			<label for="s2member-pro-paypal-sp-checkout-email" id="s2member-pro-paypal-sp-checkout-form-email-label" class="s2member-pro-paypal-form-email-label s2member-pro-paypal-sp-checkout-form-email-label">
				<span><?php echo _x("Email Address", "s2member-front", "s2member"); ?> *</span><br />
				<input type="email" aria-required="true" data-expected="email" maxlength="100" autocomplete="off" name="s2member_pro_paypal_sp_checkout[email]" id="s2member-pro-paypal-sp-checkout-email" class="s2member-pro-paypal-email s2member-pro-paypal-sp-checkout-email form-control" value="%%email_value%%" tabindex="30" />
			</label>
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Billing Method (Customers can use a Credit/Debit card, or PayPal w/Express Checkout). -->
	<div id="s2member-pro-paypal-sp-checkout-form-billing-method-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-sp-checkout-form-section s2member-pro-paypal-form-billing-method-section s2member-pro-paypal-sp-checkout-form-billing-method-section">
		<div id="s2member-pro-paypal-sp-checkout-form-billing-method-section-title" class="s2member-pro-paypal-form-section-title s2member-pro-paypal-sp-checkout-form-section-title s2member-pro-paypal-form-billing-method-section-title s2member-pro-paypal-sp-checkout-form-billing-method-section-title">
			<?php echo _x("Billing Method", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-card-type-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-card-type-div s2member-pro-paypal-sp-checkout-form-card-type-div">
			%%card_type_options%%
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-card-number-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-card-number-div s2member-pro-paypal-sp-checkout-form-card-number-div">
			<label for="s2member-pro-paypal-sp-checkout-card-number" id="s2member-pro-paypal-sp-checkout-form-card-number-label" class="s2member-pro-paypal-form-card-number-label s2member-pro-paypal-sp-checkout-form-card-number-label">
				<span><?php echo _x("Card Number (no dashes or spaces)", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_paypal_sp_checkout[card_number]" id="s2member-pro-paypal-sp-checkout-card-number" class="s2member-pro-paypal-card-number s2member-pro-paypal-sp-checkout-card-number form-control" value="%%card_number_value%%" tabindex="110" />
			</label>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-card-expiration-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-card-expiration-div s2member-pro-paypal-sp-checkout-form-card-expiration-div">
			<label for="s2member-pro-paypal-sp-checkout-card-expiration" id="s2member-pro-paypal-sp-checkout-form-card-expiration-label" class="s2member-pro-paypal-form-card-expiration-label s2member-pro-paypal-sp-checkout-form-card-expiration-label">
				<span><?php echo _x("Card Expiration Date (mm/yyyy)", "s2member-front", "s2member"); ?> *</span><br />
				<select aria-required="true" autocomplete="off" name="s2member_pro_paypal_sp_checkout[card_expiration_month]" id="s2member-pro-paypal-sp-checkout-card-expiration-month" class="s2member-pro-paypal-card-expiration-month s2member-pro-paypal-sp-checkout-card-expiration-month form-control" tabindex="120">
					%%card_expiration_month_options%%
				</select>
				<select aria-required="true" autocomplete="off" name="s2member_pro_paypal_sp_checkout[card_expiration_year]" id="s2member-pro-paypal-sp-checkout-card-expiration-year" class="s2member-pro-paypal-card-expiration-year s2member-pro-paypal-sp-checkout-card-expiration-year form-control" tabindex="121">
					%%card_expiration_year_options%%
				</select>
			</label>
			<div style="clear:both;"></div>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-card-verification-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-card-verification-div s2member-pro-paypal-sp-checkout-form-card-verification-div">
			<label for="s2member-pro-paypal-sp-checkout-card-verification" id="s2member-pro-paypal-sp-checkout-form-card-verification-label" class="s2member-pro-paypal-form-card-verification-label s2member-pro-paypal-sp-checkout-form-card-verification-label">
				<span><?php echo _x("Card Verification Code", "s2member-front", "s2member"); ?> * <a href="https://en.wikipedia.org/wiki/Card_security_code" target="_blank" tabindex="-1" rel="external nofollow"><?php echo _x("need help?", "s2member-front", "s2member"); ?></a></span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_paypal_sp_checkout[card_verification]" id="s2member-pro-paypal-sp-checkout-card-verification" class="s2member-pro-paypal-card-verification s2member-pro-paypal-sp-checkout-card-verification form-control" value="%%card_verification_value%%" tabindex="130" />
			</label>
		</div>
		<!-- This is displayed only when Maestro/Solo cards are selected as the Payment Method. -->
		<div id="s2member-pro-paypal-sp-checkout-form-card-start-date-issue-number-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-card-start-date-issue-number-div s2member-pro-paypal-sp-checkout-form-card-start-date-issue-number-div">
			<label for="s2member-pro-paypal-sp-checkout-card-start-date-issue-number" id="s2member-pro-paypal-sp-checkout-form-card-start-date-issue-number-label" class="s2member-pro-paypal-form-card-start-date-issue-number-label s2member-pro-paypal-sp-checkout-form-card-start-date-issue-number-label">
				<span><?php echo _x("Card Start Date (mm/yyyy), or Issue Number", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_paypal_sp_checkout[card_start_date_issue_number]" id="s2member-pro-paypal-sp-checkout-card-start-date-issue-number" class="s2member-pro-paypal-card-start-date-issue-number s2member-pro-paypal-sp-checkout-card-start-date-issue-number form-control" value="%%card_start_date_issue_number_value%%" tabindex="140" />
			</label>
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Billing Address (hidden dynamically when/if PayPal is selected as the Payment Method). -->
	<div id="s2member-pro-paypal-sp-checkout-form-billing-address-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-sp-checkout-form-section s2member-pro-paypal-form-billing-address-section s2member-pro-paypal-sp-checkout-form-billing-address-section">
		<div id="s2member-pro-paypal-sp-checkout-form-billing-address-section-title" class="s2member-pro-paypal-form-section-title s2member-pro-paypal-sp-checkout-form-section-title s2member-pro-paypal-form-billing-address-section-title s2member-pro-paypal-sp-checkout-form-billing-address-section-title">
			<?php echo _x("Billing Address", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-street-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-street-div s2member-pro-paypal-sp-checkout-form-street-div">
			<label for="s2member-pro-paypal-sp-checkout-street" id="s2member-pro-paypal-sp-checkout-form-street-label" class="s2member-pro-paypal-form-street-label s2member-pro-paypal-sp-checkout-form-street-label">
				<span><?php echo _x("Street Address", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_paypal_sp_checkout[street]" id="s2member-pro-paypal-sp-checkout-street" class="s2member-pro-paypal-street s2member-pro-paypal-sp-checkout-street form-control" value="%%street_value%%" tabindex="200" />
			</label>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-city-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-city-div s2member-pro-paypal-sp-checkout-form-city-div">
			<label for="s2member-pro-paypal-sp-checkout-city" id="s2member-pro-paypal-sp-checkout-form-city-label" class="s2member-pro-paypal-form-city-label s2member-pro-paypal-sp-checkout-form-city-label">
				<span><?php echo _x("City / Town", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_paypal_sp_checkout[city]" id="s2member-pro-paypal-sp-checkout-city" class="s2member-pro-paypal-city s2member-pro-paypal-sp-checkout-city form-control" value="%%city_value%%" tabindex="210" />
			</label>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-state-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-state-div s2member-pro-paypal-sp-checkout-form-state-div">
			<label for="s2member-pro-paypal-sp-checkout-state" id="s2member-pro-paypal-sp-checkout-form-state-label" class="s2member-pro-paypal-form-state-label s2member-pro-paypal-sp-checkout-form-state-label">
				<span><?php echo _x("State / Province", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_paypal_sp_checkout[state]" id="s2member-pro-paypal-sp-checkout-state" class="s2member-pro-paypal-state s2member-pro-paypal-sp-checkout-state form-control" value="%%state_value%%" tabindex="220" />
			</label>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-zip-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-zip-div s2member-pro-paypal-sp-checkout-form-zip-div">
			<label for="s2member-pro-paypal-sp-checkout-zip" id="s2member-pro-paypal-sp-checkout-form-zip-label" class="s2member-pro-paypal-form-zip-label s2member-pro-paypal-sp-checkout-form-zip-label">
				<span><?php echo _x("Postal / Zip Code", "s2member-front", "s2member"); ?> *</span><br />
				<input type="text" aria-required="true" maxlength="100" autocomplete="off" name="s2member_pro_paypal_sp_checkout[zip]" id="s2member-pro-paypal-sp-checkout-zip" class="s2member-pro-paypal-zip s2member-pro-paypal-sp-checkout-zip form-control" value="%%zip_value%%" tabindex="230" />
			</label>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-country-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-country-div s2member-pro-paypal-sp-checkout-form-country-div">
			<label for="s2member-pro-paypal-sp-checkout-country" id="s2member-pro-paypal-sp-checkout-form-country-label" class="s2member-pro-paypal-form-country-label s2member-pro-paypal-sp-checkout-form-country-label">
				<span><?php echo _x("Country", "s2member-front", "s2member"); ?> *</span><br />
				<select aria-required="true" name="s2member_pro_paypal_sp_checkout[country]" id="s2member-pro-paypal-sp-checkout-country" class="s2member-pro-paypal-country s2member-pro-paypal-sp-checkout-country form-control" tabindex="240">
					%%country_options%%
				</select>
			</label>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-ajax-tax-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-ajax-tax-div s2member-pro-paypal-sp-checkout-form-ajax-tax-div">
			<!-- Sales Tax will be displayed here via Ajax; based on state, country, and/or zip code range. -->
		</div>
		<div style="clear:both;"></div>
	</div>

	<!-- Captcha ( A reCaptcha section, with a required security code will appear here; if captcha="1" ). -->
	%%captcha%%

	<!-- Checkout Now (this holds the submit button, and also some dynamic hidden input variables). -->
	<div id="s2member-pro-paypal-sp-checkout-form-submission-section" class="s2member-pro-paypal-form-section s2member-pro-paypal-sp-checkout-form-section s2member-pro-paypal-form-submission-section s2member-pro-paypal-sp-checkout-form-submission-section">
		<div id="s2member-pro-paypal-sp-checkout-form-submission-section-title" class="s2member-pro-paypal-form-section-title s2member-pro-paypal-sp-checkout-form-section-title s2member-pro-paypal-form-submission-section-title s2member-pro-paypal-sp-checkout-form-submission-section-title">
			<?php echo _x("Checkout Now", "s2member-front", "s2member"); ?>
		</div>
		<div id="s2member-pro-paypal-sp-checkout-form-submit-div" class="s2member-pro-paypal-form-div s2member-pro-paypal-sp-checkout-form-div s2member-pro-paypal-form-submit-div s2member-pro-paypal-sp-checkout-form-submit-div">
			%%hidden_inputs%% <!-- Auto-filled by the s2Member software. Do NOT remove this under any circumstance. -->
			<button type="submit" id="s2member-pro-paypal-sp-checkout-submit" class="s2member-pro-paypal-submit s2member-pro-paypal-sp-checkout-submit btn btn-primary" tabindex="500"><?php echo esc_html(_x("Submit Form", "s2member-front", "s2member")); ?></button>
		</div>
		<div style="clear:both;"></div>
	</div>
</form>
