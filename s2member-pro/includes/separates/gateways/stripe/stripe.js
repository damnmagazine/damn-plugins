/**
 * Core JavaScript routines for Stripe.
 *
 * Copyright: © 2009-2011
 * {@link http://www.websharks-inc.com/ WebSharks, Inc.}
 * (coded in the USA)
 *
 * This WordPress plugin (s2Member Pro) is comprised of two parts:
 *
 * o (1) Its PHP code is licensed under the GPL license, as is WordPress.
 *   You should have received a copy of the GNU General Public License,
 *   along with this software. In the main directory, see: /licensing/
 *   If not, see: {@link http://www.gnu.org/licenses/}.
 *
 * o (2) All other parts of (s2Member Pro); including, but not limited to:
 *   the CSS code, some JavaScript code, images, and design;
 *   are licensed according to the license purchased.
 *   See: {@link http://www.s2member.com/prices/}
 *
 * Unless you have our prior written consent, you must NOT directly or indirectly license,
 * sub-license, sell, resell, or provide for free; part (2) of the s2Member Pro Add-on;
 * or make an offer to do any of these things. All of these things are strictly
 * prohibited with part (2) of the s2Member Pro Add-on.
 *
 * Your purchase of s2Member Pro includes free lifetime upgrades via s2Member.com
 * (i.e., new features, bug fixes, updates, improvements); along with full access
 * to our video tutorial library: {@link http://www.s2member.com/videos/}
 *
 * @package s2Member\Stripe
 * @since 140617
 */
jQuery(document).ready( // DOM ready.
	function($) // Depends on Stripe lib.
	{
		var stripeCheck = function()
		{
			if(window.StripeCheckout) // Stripe available?
				clearInterval(stripeCheckInterval), setupProForms();
		}, stripeCheckInterval = setInterval(stripeCheck, 100);

		$.ajax({cache: true, dataType: 'script', url: 'https://checkout.stripe.com/checkout.js'});

		var setupProForms = function()
		{
			/*
			 Initializations.
			 */
			var preloadAjaxLoader, // Loading image.
				$clForm, $upForm, $rgForm, $spForm, $coForm,
				ariaTrue = {'aria-required': 'true'}, ariaFalse = {'aria-required': 'false'},
				ariaFalseDis = {'aria-required': 'false', 'disabled': 'disabled'},
				disabled = {'disabled': 'disabled'},

				taxMayApply = true, calculateTax, cTaxDelay, cTaxTimeout, cTaxReq, cTaxLocation, ajaxTaxDiv,
				optionsSection, optionsSelect, descSection, couponSection, couponApplyButton, registrationSection, customFieldsSection,
				billingMethodSection, handleBillingMethod, sourceTokenButton, sourceTokenSummary, sourceTokenInput, sourceTokenSummaryInput, billingAddressSection, captchaSection,
				submissionSection, submissionButton, submissionNonceVerification;

			preloadAjaxLoader = new Image(), preloadAjaxLoader.src = '<?php echo $vars["i"]; ?>/ajax-loader.gif';

			/*
			 Check for more than a single form on this page.
			 */
			if($('form.s2member-pro-stripe-cancellation-form').length > 1
			   || $('form.s2member-pro-stripe-registration-form').length > 1 || $('form.s2member-pro-stripe-update-form').length > 1
			   || $('form.s2member-pro-stripe-sp-checkout-form').length > 1 || $('form.s2member-pro-stripe-checkout-form').length > 1)
			{
				return alert('Detected more than one s2Member Pro-Form.\n\nPlease use only ONE s2Member Pro-Form Shortcode on each Post/Page.' +
				             ' Attempting to serve more than one Pro-Form on each Post/Page (even w/ DHTML) may result in unexpected/broken functionality.');
			}
			/*
			 Cancellation form handler.
			 */
			if(($clForm = $('form#s2member-pro-stripe-cancellation-form')).length === 1)
			{
				captchaSection = 'div#s2member-pro-stripe-cancellation-form-captcha-section',
					submissionSection = 'div#s2member-pro-stripe-cancellation-form-submission-section',
					submissionButton = submissionSection + ' button#s2member-pro-stripe-cancellation-submit';

				$(submissionButton).removeAttr('disabled'),
					ws_plugin__s2member_animateProcessing($(submissionButton), 'reset');

				$clForm.on('submit', function(/* Form validation. */)
				{
					var context = this, label = '', error = '', errors = '',
						$recaptchaResponse = $(captchaSection + ' input#recaptcha_response_field, '+captchaSection+' #g-recaptcha-response');

					$(':input', context)
						.each(function(/* Go through them all together. */)
						      {
							      var id = $.trim($(this).attr('id')).replace(/---[0-9]+$/g, ''/* Remove numeric suffixes. */);

							      if(id && (label = $.trim($('label[for="' + id + '"]', context).first().children('span').first().text().replace(/[\r\n\t]+/g, ' '))))
							      {
								      if(error = ws_plugin__s2member_validationErrors(label, this, context))
									      errors += error + '\n\n'/* Collect errors. */;
							      }
						      });
					if((errors = $.trim(errors)))
					{
						alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + errors);
						return false; // Error; cannot continue in this scenario.
					}
					else if($recaptchaResponse.length && !$recaptchaResponse.val())
					{
						alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("Security Verification missing. Please try again.", "s2member-front", "s2member")); ?>');
						return false; // Error; cannot continue in this scenario.
					}
					$(submissionButton).attr(disabled),
						ws_plugin__s2member_animateProcessing($(submissionButton));
					return true; // Allow submission.
				});
			}
			/*
			 Registration form handler.
			 */
			else if(($rgForm = $('form#s2member-pro-stripe-registration-form')).length === 1)
			{
				optionsSection = 'div#s2member-pro-stripe-registration-form-options-section',
					optionsSelect = optionsSection + ' select#s2member-pro-stripe-registration-options',

					descSection = 'div#s2member-pro-stripe-registration-form-description-section',

					registrationSection = 'div#s2member-pro-stripe-registration-form-registration-section',
					captchaSection = 'div#s2member-pro-stripe-registration-form-captcha-section',
					submissionSection = 'div#s2member-pro-stripe-registration-form-submission-section',
					submissionButton = submissionSection + ' button#s2member-pro-stripe-registration-submit',
					submissionNonceVerification = submissionSection + ' input#s2member-pro-stripe-registration-nonce';

				$(submissionButton).removeAttr('disabled'),
					ws_plugin__s2member_animateProcessing($(submissionButton), 'reset');

				if(!$(optionsSelect + ' option').length)
					$(optionsSection).hide(), $(descSection).show();

				else $(optionsSection).show(), $(descSection).hide(),
					$(optionsSelect).on('change', function(/* Handle checkout option changes. */)
					{
						$(submissionNonceVerification).val('option'),
							$rgForm.attr('action', $rgForm.attr('action').replace(/#.*$/, '') + '#s2p-form'),
							$rgForm.submit(); // Submit form with a new checkout option.
					});
				if($(submissionSection + ' input#s2member-pro-stripe-registration-names-not-required-or-not-possible').length)
				{
					$(registrationSection + ' > div#s2member-pro-stripe-registration-form-first-name-div').hide(),
						$(registrationSection + ' > div#s2member-pro-stripe-registration-form-first-name-div :input').attr(ariaFalseDis);

					$(registrationSection + ' > div#s2member-pro-stripe-registration-form-last-name-div').hide(),
						$(registrationSection + ' > div#s2member-pro-stripe-registration-form-last-name-div :input').attr(ariaFalseDis);
				}
				if($(submissionSection + ' input#s2member-pro-stripe-registration-password-not-required-or-not-possible').length)
				{
					$(registrationSection + ' > div#s2member-pro-stripe-registration-form-password-div').hide(),
						$(registrationSection + ' > div#s2member-pro-stripe-registration-form-password-div :input').attr(ariaFalseDis);
				}
				$(registrationSection + ' > div#s2member-pro-stripe-registration-form-password-div :input').on('keyup initialize.s2', function()
				{
					ws_plugin__s2member_passwordStrength(
						$(registrationSection + ' input#s2member-pro-stripe-registration-username'),
						$(registrationSection + ' input#s2member-pro-stripe-registration-password1'),
						$(registrationSection + ' input#s2member-pro-stripe-registration-password2'),
						$(registrationSection + ' div#s2member-pro-stripe-registration-form-password-strength')
					);
				}).trigger('initialize.s2');
				$rgForm.on('submit', function(/* Form validation. */)
				{
					if($.inArray($(submissionNonceVerification).val(), ['option']) === -1)
					{
						var context = this, label = '', error = '', errors = '',
							$recaptchaResponse = $(captchaSection + ' input#recaptcha_response_field, '+captchaSection+' #g-recaptcha-response'),
							$password1 = $(registrationSection + ' input#s2member-pro-stripe-registration-password1[aria-required="true"]'),
							$password2 = $(registrationSection + ' input#s2member-pro-stripe-registration-password2');

						$(':input', context)
							.each(function(/* Go through them all together. */)
							      {
								      var id = $.trim($(this).attr('id')).replace(/---[0-9]+$/g, ''/* Remove numeric suffixes. */);

								      if(id && (label = $.trim($('label[for="' + id + '"]', context).first().children('span').first().text().replace(/[\r\n\t]+/g, ' '))))
								      {
									      if(error = ws_plugin__s2member_validationErrors(label, this, context))
										      errors += error + '\n\n'/* Collect errors. */;
								      }
							      });
						if((errors = $.trim(errors)))
						{
							alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + errors);
							return false; // Error; cannot continue in this scenario.
						}
						else if($password1.length && $.trim($password1.val()) !== $.trim($password2.val()))
						{
							alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("Passwords do not match up. Please try again.", "s2member-front", "s2member")); ?>');
							return false; // Error; cannot continue in this scenario.
						}
						else if($password1.length && $.trim($password1.val()).length < ws_plugin__s2member_passwordMinLength())
						{
							alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(sprintf(_x("Password MUST be at least %s characters. Please try again.", "s2member-front", "s2member"), c_ws_plugin__s2member_user_securities::min_password_length())); ?>');
							return false;
						}
						else if($password1.length && ws_plugin__s2member_passwordStrengthMeter($.trim($password1.val()), $.trim($password2.val()), true) < ws_plugin__s2member_passwordMinStrengthScore())
						{
							alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(sprintf(_x("Password strength MUST be %s. Please try again.", "s2member-front", "s2member"), c_ws_plugin__s2member_user_securities::min_password_strength_label())); ?>');
							return false;
						}
						else if($recaptchaResponse.length && !$recaptchaResponse.val())
						{
							alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("Security Verification missing. Please try again.", "s2member-front", "s2member")); ?>');
							return false; // Error; cannot continue in this scenario.
						}
						// $(optionsSelect).attr(disabled); // Not an option selection.
						// Bug fix. Don't disable, because that prevents it from being submitted.
					}
					$(submissionButton).attr(disabled),
						ws_plugin__s2member_animateProcessing($(submissionButton));
					return true; // Allow submission.
				});
			}
			/*
			 Update form handler.
			 */
			else if(($upForm = $('form#s2member-pro-stripe-update-form')).length === 1)
			{
				billingMethodSection = 'div#s2member-pro-stripe-update-form-billing-method-section',
					sourceTokenButton = billingMethodSection + ' button#s2member-pro-stripe-update-form-source-token-button',
					sourceTokenSummary = billingMethodSection + ' div#s2member-pro-stripe-update-form-source-token-summary',

					billingAddressSection = 'div#s2member-pro-stripe-update-form-billing-address-section',

					captchaSection = 'div#s2member-pro-stripe-update-form-captcha-section',

					submissionSection = 'div#s2member-pro-stripe-update-form-submission-section',
					sourceTokenInput = submissionSection + ' input[name="' + ws_plugin__s2member_escjQAttr('s2member_pro_stripe_update[source_token]') + '"]',
					sourceTokenSummaryInput = submissionSection + ' input[name="' + ws_plugin__s2member_escjQAttr('s2member_pro_stripe_update[source_token_summary]') + '"]',
					submissionButton = submissionSection + ' button#s2member-pro-stripe-update-submit';

				$(submissionButton).removeAttr('disabled'),
					ws_plugin__s2member_animateProcessing($(submissionButton), 'reset');

				handleBillingMethod = function(eventTrigger /* eventTrigger is passed by jQuery for DOM events. */)
				{
					var sourceToken = $(sourceTokenInput).val(/* Source token from Stripe. */);

					if(sourceToken/* They have now supplied a source token? */)
					{
						$(billingMethodSection).show(), // Show billing method section.
							$(billingMethodSection + ' > div.s2member-pro-stripe-update-form-div').show(),
							$(billingMethodSection + ' > div.s2member-pro-stripe-update-form-div :input').attr(ariaTrue);

						if(taxMayApply/* If tax may apply, we need to collect a tax location. */)
						{
							$(billingAddressSection).show(), // Show billing address section.
								$(billingAddressSection + ' > div.s2member-pro-stripe-update-form-div').show(),
								$(billingAddressSection + ' > div.s2member-pro-stripe-update-form-div :input').attr(ariaTrue);
						}
						else // There is no reason to collect tax information in this case.
						{
							$(billingAddressSection).hide(), // Hide billing address section.
								$(billingAddressSection + ' > div.s2member-pro-stripe-update-form-div').hide(),
								$(billingAddressSection + ' > div.s2member-pro-stripe-update-form-div :input').attr(ariaFalse);
						}
						if(eventTrigger) $(submissionSection + ' button#s2member-pro-stripe-update-submit').focus();
					}
					else if(!sourceToken/* Else there is no Billing Method supplied. */)
					{
						$(billingMethodSection).show(), // Show billing method section.
							$(billingMethodSection + ' > div.s2member-pro-stripe-update-form-div').show(),
							$(billingMethodSection + ' > div.s2member-pro-stripe-update-form-div :input').attr(ariaTrue);

						$(billingAddressSection).hide(), // Hide billing address section.
							$(billingAddressSection + ' > div.s2member-pro-stripe-update-form-div').hide(),
							$(billingAddressSection + ' > div.s2member-pro-stripe-update-form-div :input').attr(ariaFalse);
					}
				};
				handleBillingMethod(); // Handle billing method immediately to deal with fields already filled in.

				$(sourceTokenButton).on('click', function() // Stripe integration.
				{
					var getSourceToken = StripeCheckout.configure
					({
						 bitcoin: false, // Accept Bitcoin as a funding source in this instance?

						 key            : '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_stripe_api_publishable_key"]); ?>',
						 zipCode        : '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_stripe_api_validate_zipcode"]); ?>' == '1',
						 image          : '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_stripe_api_image"]); ?>',
						 panelLabel     : '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("Add", "s2member-front", "s2member")); ?>',
						 email          : typeof S2MEMBER_CURRENT_USER_EMAIL === 'string' ? S2MEMBER_CURRENT_USER_EMAIL : '',
						 allowRememberMe: true, // Allow Stripe to remember the customer.

						 token: function(token) // Callback handler.
						 {
							 $(sourceTokenInput).val(token.id), $(sourceTokenSummaryInput).val(buildSourceTokenTextSummary(token)),
								 $(sourceTokenSummary).html(ws_plugin__s2member_escHtml(buildSourceTokenTextSummary(token))),
								 handleBillingMethod(); // Adjust billing methods fields now also.
						 }
					 });
					getSourceToken.open(); // Open Stripe overlay.
				});
				$upForm.on('submit', function(/* Form validation. */)
				{
					var context = this, label = '', error = '', errors = '',
						$recaptchaResponse = $(captchaSection + ' input#recaptcha_response_field, '+captchaSection+' #g-recaptcha-response');

					if(!$(sourceTokenInput).val())
					{
						alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("No Billing Method; please try again.", "s2member-front", "s2member")); ?>');
						return false; // Error; cannot continue in this scenario.
					}
					$(':input', context)
						.each(function(/* Go through them all together. */)
						      {
							      var id = $.trim($(this).attr('id')).replace(/---[0-9]+$/g, ''/* Remove numeric suffixes. */);

							      if(id && (label = $.trim($('label[for="' + id.replace(/-(month|year)/, '') + '"]', context).first().children('span').first().text().replace(/[\r\n\t]+/g, ' '))))
							      {
								      if(error = ws_plugin__s2member_validationErrors(label, this, context))
									      errors += error + '\n\n'/* Collect errors. */;
							      }
						      });
					if((errors = $.trim(errors)))
					{
						alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + errors);
						return false; // Error; cannot continue in this scenario.
					}
					else if($recaptchaResponse.length && !$recaptchaResponse.val())
					{
						alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("Security Verification missing. Please try again.", "s2member-front", "s2member")); ?>');
						return false; // Error; cannot continue in this scenario.
					}
					$(submissionButton).attr(disabled),
						ws_plugin__s2member_animateProcessing($(submissionButton));
					return true; // Allow submission.
				});
			}
			/*
			 Handles both types of checkout forms.
			 */
			else if(($coForm = $('form#s2member-pro-stripe-sp-checkout-form')).length === 1 || ($coForm = $('form#s2member-pro-stripe-checkout-form')).length === 1)
			{
				(function($coForm)// Handles both types of checkout forms; i.e., Specific Post/Page and also Checkout/Modification forms.
				{
					var coTypeWithDashes = $coForm[0].id.replace(/^s2member\-pro\-stripe\-/, '').replace(/\-form$/, ''),
						coTypeWithUnderscores = coTypeWithDashes.replace(/[^a-z0-9]/gi, '_'); // e.g., `sp_checkout`.

					optionsSection = 'div#s2member-pro-stripe-' + coTypeWithDashes + '-form-options-section',
						optionsSelect = optionsSection + ' select#s2member-pro-stripe-' + coTypeWithDashes + '-options',

						descSection = 'div#s2member-pro-stripe-' + coTypeWithDashes + '-form-description-section',

						couponSection = 'div#s2member-pro-stripe-' + coTypeWithDashes + '-form-coupon-section',
						couponApplyButton = couponSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-coupon-apply',

						registrationSection = 'div#s2member-pro-stripe-' + coTypeWithDashes + '-form-registration-section',
						customFieldsSection = 'div#s2member-pro-stripe-' + coTypeWithDashes + '-form-custom-fields-section',

						billingMethodSection = 'div#s2member-pro-stripe-' + coTypeWithDashes + '-form-billing-method-section',
						sourceTokenButton = billingMethodSection + ' button#s2member-pro-stripe-' + coTypeWithDashes + '-form-source-token-button',
						sourceTokenSummary = billingMethodSection + ' div#s2member-pro-stripe-' + coTypeWithDashes + '-form-source-token-summary',

						billingAddressSection = 'div#s2member-pro-stripe-' + coTypeWithDashes + '-form-billing-address-section',
						ajaxTaxDiv = billingAddressSection + ' > div#s2member-pro-stripe-' + coTypeWithDashes + '-form-ajax-tax-div',

						captchaSection = 'div#s2member-pro-stripe-' + coTypeWithDashes + '-form-captcha-section',

						submissionSection = 'div#s2member-pro-stripe-' + coTypeWithDashes + '-form-submission-section',
						sourceTokenInput = submissionSection + ' input[name="' + ws_plugin__s2member_escjQAttr('s2member_pro_stripe_' + coTypeWithUnderscores + '[source_token]') + '"]',
						sourceTokenSummaryInput = submissionSection + ' input[name="' + ws_plugin__s2member_escjQAttr('s2member_pro_stripe_' + coTypeWithUnderscores + '[source_token_summary]') + '"]',
						submissionNonceVerification = submissionSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-nonce',
						submissionButton = submissionSection + ' button#s2member-pro-stripe-' + coTypeWithDashes + '-submit';
					/*
					 Reset button states; in case of a back button.
					 */
					$(optionsSelect).removeAttr('disabled'), $(couponApplyButton).removeAttr('disabled'),
						$(submissionButton).removeAttr('disabled'), ws_plugin__s2member_animateProcessing($(submissionButton), 'reset');
					/*
					 Handle checkout options. Does this form have checkout options?
					 */
					if(!$(optionsSelect + ' option').length)
						$(optionsSection).hide(), $(descSection).show();

					else $(optionsSection).show(), $(descSection).hide(),
						$(optionsSelect).on('change', function(/* Handle checkout option changes. */)
						{
							$(submissionNonceVerification).val('option'),
								$coForm.attr('action', $coForm.attr('action').replace(/#.*$/, '') + '#s2p-form'),
								$coForm.submit(); // Submit form with a new checkout option.
						});
					/*
					 Handle the coupon code section. Enabled on this form?
					 */
					if($(submissionSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-coupons-not-required-or-not-possible').length)
						$(couponSection).hide(); // Not accepting coupons on this particular form.

					else $(couponSection).show(), $(couponApplyButton).on('click', function(/* Submit coupon code upon clicking apply button. */)
					{
						$(submissionNonceVerification).val('apply-coupon'), $coForm.submit();
					});
					/*
					 Handle a user that is already logged into their account.
					 */
					if(S2MEMBER_CURRENT_USER_IS_LOGGED_IN/* User is already logged in? */)
					{
						$(registrationSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-first-name')
							.each(function()
							      {
								      var $this = $(this), val = $this.val();
								      if(!val) $this.val(S2MEMBER_CURRENT_USER_FIRST_NAME);
							      });
						$(registrationSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-last-name')
							.each(function()
							      {
								      var $this = $(this), val = $this.val();
								      if(!val) $this.val(S2MEMBER_CURRENT_USER_LAST_NAME);
							      });
						$(registrationSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-email').val(S2MEMBER_CURRENT_USER_EMAIL).attr(ariaFalseDis),
							$(registrationSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-username').val(S2MEMBER_CURRENT_USER_LOGIN).attr(ariaFalseDis);

						if(coTypeWithDashes === 'sp-checkout') // Specific Post/Page Access requires an email address.
							$(registrationSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-email').attr(ariaTrue).removeAttr('disabled');

						$(registrationSection + ' > div#s2member-pro-stripe-' + coTypeWithDashes + '-form-password-div').hide(),
							$(registrationSection + ' > div#s2member-pro-stripe-' + coTypeWithDashes + '-form-password-div :input').attr(ariaFalseDis);

						if($.trim($(registrationSection + ' > div#s2member-pro-stripe-' + coTypeWithDashes + '-form-registration-section-title').html()) === '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("Create Profile", "s2member-front", "s2member")); ?>')
							$(registrationSection + ' > div#s2member-pro-stripe-' + coTypeWithDashes + '-form-registration-section-title').html('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("Your Profile", "s2member-front", "s2member")); ?>');

						$(customFieldsSection).hide(), $(customFieldsSection + ' :input').attr(ariaFalseDis);
					}
					/*
					 Handle the password input field in various scenarios.
					 */
					if($(submissionSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-password-not-required-or-not-possible').length)
					{
						$(registrationSection + ' > div#s2member-pro-stripe-' + coTypeWithDashes + '-form-password-div').hide(),
							$(registrationSection + ' > div#s2member-pro-stripe-' + coTypeWithDashes + '-form-password-div :input').attr(ariaFalseDis);
					}
					else $(registrationSection + ' > div#s2member-pro-stripe-' + coTypeWithDashes + '-form-password-div :input').on('keyup initialize.s2', function()
					{
						ws_plugin__s2member_passwordStrength(
							$(registrationSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-username'),
							$(registrationSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-password1'),
							$(registrationSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-password2'),
							$(registrationSection + ' div#s2member-pro-stripe-' + coTypeWithDashes + '-form-password-strength')
						);
					}).trigger('initialize.s2');
					/*
					 Handle tax calulations via tax-related input fields.
					 */
					if($(submissionSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-tax-not-required-or-not-possible').length)
						$(ajaxTaxDiv).hide(), taxMayApply = false; // Tax does NOT even apply.

					else // We need to setup a few handlers.
					{
						cTaxDelay = function(eventTrigger)
						{
							setTimeout(function(){ calculateTax(eventTrigger); }, 10);
						};
						calculateTax = function(eventTrigger) // Calculates tax.
						{
							if(!taxMayApply) return; // Not applicable.

							if(eventTrigger && eventTrigger.interval && document.activeElement
							   && document.activeElement.id === 's2member-pro-stripe-' + coTypeWithDashes + '-country')
								return; // Nothing to do in this special case.

							var attr = $(submissionSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-attr').val(),
								state = $.trim($(billingAddressSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-state').val()),
								country = $.trim($(billingAddressSection + ' select#s2member-pro-stripe-' + coTypeWithDashes + '-country').val()),
								zip = $.trim($(billingAddressSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-zip').val()),
								thisTaxLocation = state + '|' + country + '|' + zip, // Three part location.
								isBitcoin = $.trim($(sourceTokenInput).val()).indexOf('btcrcv_') === 0;

							if(state && country && zip && thisTaxLocation && !isBitcoin && (!cTaxLocation || cTaxLocation !== thisTaxLocation))
							{
								clearTimeout(cTaxTimeout), cTaxTimeout = 0,
									cTaxLocation = thisTaxLocation; // Set current location.
								if(cTaxReq) cTaxReq.abort(); // Abort any existing connections.

								var verifier = '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(c_ws_plugin__s2member_utils_encryption::encrypt("ws-plugin--s2member-pro-stripe-ajax-tax")); ?>',
									calculating = '<div><img src="' + ws_plugin__s2member_escAttr(preloadAjaxLoader.src) + '" alt="" /> <?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("calculating sales tax...", "s2member-front", "s2member")); ?></div>',
									ajaxTaxHandler = function(/* Create a new cTaxTimeout with a one second delay. */)
									{
										cTaxReq = $.post('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(admin_url("/admin-ajax.php")); ?>',
										                 {
											                 'action'                                               : 'ws_plugin__s2member_pro_stripe_ajax_tax',
											                 'ws_plugin__s2member_pro_stripe_ajax_tax'              : verifier,
											                 'ws_plugin__s2member_pro_stripe_ajax_tax_vars[attr]'   : attr,
											                 'ws_plugin__s2member_pro_stripe_ajax_tax_vars[state]'  : state,
											                 'ws_plugin__s2member_pro_stripe_ajax_tax_vars[country]': country,
											                 'ws_plugin__s2member_pro_stripe_ajax_tax_vars[zip]'    : zip
										                 },
										                 function(response, textStatus)
										                 {
											                 clearTimeout(cTaxTimeout), cTaxTimeout = 0;
											                 if(typeof response === 'object' && response.hasOwnProperty('tax'))
											                 /* translators: `Sales Tax (Today)` and `Total (Today)`. The word `Today` is displayed when/if a trial period is offered. The word `Today` is translated elsewhere. */
												                 $(ajaxTaxDiv).html('<div>' + $.sprintf('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("<strong>Sales Tax%s:</strong> %s<br /><strong>— Total%s:</strong> %s", "s2member-front", "s2member")); ?>', ((response.trial) ? ' ' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("Today", "s2member-front", "s2member")); ?>' : ''), ((response.tax_per) ? '<em>' + response.tax_per + '</em> ( ' + response.cur_symbol + '' + response.tax + ' )' : response.cur_symbol + '' + response.tax), ((response.trial) ? ' ' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("Today", "s2member-front", "s2member")); ?>' : ''), response.cur_symbol + '' + response.total) + '</div>');
										                 }, 'json');
									};
								$(ajaxTaxDiv).html(calculating), cTaxTimeout = setTimeout(ajaxTaxHandler, ((eventTrigger && eventTrigger.keyCode) ? 1000 : 100));
							}
							else if(!state || !country || !zip || !thisTaxLocation || isBitcoin)
							{
								clearTimeout(cTaxTimeout), cTaxTimeout = 0,
									cTaxLocation = ''; // Reset the current location.
								if(cTaxReq) cTaxReq.abort(); // Abort any existing connections.
								$(ajaxTaxDiv).html(''); // Empty the tax calculation div here also.
							}
						};
						setInterval(function(){ calculateTax({interval: true}); }, 1000), // Helps with things like Google's Autofill feature.
							$(billingAddressSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-state').on('keyup blur', calculateTax).on('cut paste', cTaxDelay),
							$(billingAddressSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-zip').on('keyup blur', calculateTax).on('cut paste', cTaxDelay),
							$(billingAddressSection + ' select#s2member-pro-stripe-' + coTypeWithDashes + '-country').on('change', calculateTax),
							calculateTax(); // Calculate immediately to deal with fields already filled in.
					}
					handleBillingMethod = function(eventTrigger /* eventTrigger is passed by jQuery for DOM events. */)
					{
						if($(submissionSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-payment-not-required-or-not-possible').length)
							$(sourceTokenInput).val('free'); // No payment required in this very special case.

						var sourceToken = $(sourceTokenInput).val(/* Source token from Stripe. */);

						if(sourceToken/* They have now supplied a source token? */)
						{
							if(sourceToken === 'free' /* Special source token value. */)
							{
								$(billingMethodSection).hide(), // Hide billing method section.
									$(billingMethodSection + ' > div.s2member-pro-stripe-' + coTypeWithDashes + '-form-div').hide(),
									$(billingMethodSection + ' > div.s2member-pro-stripe-' + coTypeWithDashes + '-form-div :input').attr(ariaFalse);
							}
							else // We need to display the billing method section in all other cases.
							{
								$(billingMethodSection).show(), // Show billing method section.
									$(billingMethodSection + ' > div.s2member-pro-stripe-' + coTypeWithDashes + '-form-div').show(),
									$(billingMethodSection + ' > div.s2member-pro-stripe-' + coTypeWithDashes + '-form-div :input').attr(ariaTrue);
							}
							if(sourceToken !== 'free' && taxMayApply && sourceToken.indexOf('btcrcv_') !== 0)
							{
								$(billingAddressSection).show(), // Show billing address section.
									$(billingAddressSection + ' > div.s2member-pro-stripe-' + coTypeWithDashes + '-form-div').show(),
									$(billingAddressSection + ' > div.s2member-pro-stripe-' + coTypeWithDashes + '-form-div :input').attr(ariaTrue);
							}
							else // There is no reason to collect tax information in this case.
							{
								$(billingAddressSection).hide(), // Hide billing address section.
									$(billingAddressSection + ' > div.s2member-pro-stripe-' + coTypeWithDashes + '-form-div').hide(),
									$(billingAddressSection + ' > div.s2member-pro-stripe-' + coTypeWithDashes + '-form-div :input').attr(ariaFalse);
							}
							if(eventTrigger) $(submissionSection + ' button#s2member-pro-stripe-' + coTypeWithDashes + '-submit').focus();
						}
						else if(!sourceToken/* Else there is no Billing Method supplied. */)
						{
							$(billingMethodSection).show(), // Show billing method section.
								$(billingMethodSection + ' > div.s2member-pro-stripe-' + coTypeWithDashes + '-form-div').show(),
								$(billingMethodSection + ' > div.s2member-pro-stripe-' + coTypeWithDashes + '-form-div :input').attr(ariaTrue);

							$(billingAddressSection).hide(), // Hide billing address section.
								$(billingAddressSection + ' > div.s2member-pro-stripe-' + coTypeWithDashes + '-form-div').hide(),
								$(billingAddressSection + ' > div.s2member-pro-stripe-' + coTypeWithDashes + '-form-div :input').attr(ariaFalse);
						}
					};
					handleBillingMethod(); // Handle billing method immediately to deal with fields already filled in.

					$(sourceTokenButton).on('click', function() // Stripe integration.
					{
						var acceptBitcoin = $(submissionSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-is-buy-now-amount-in-cents').length > 0 && $(submissionSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-is-buy-now-bitcoin-accepted').length > 0,
							acceptBitcoinAmountInCents = acceptBitcoin ? parseInt($.trim($(submissionSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-is-buy-now-amount-in-cents').val())) : 0,
							acceptBitcoinCurrency = acceptBitcoin ? $.trim($(submissionSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-is-buy-now-currency').val()).toUpperCase() : 'USD',
							acceptBitcoinDesc = acceptBitcoin ? $.trim($(submissionSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-is-buy-now-desc').val()) : '';

						if(acceptBitcoin && (isNaN(acceptBitcoinAmountInCents) || acceptBitcoinAmountInCents <= 0)) // Not possible.
							acceptBitcoin = false, acceptBitcoinAmountInCents = 0, acceptBitcoinCurrency = '', acceptBitcoinDesc = '';

						var getSourceToken = StripeCheckout.configure
						({
							 bitcoin    : acceptBitcoin, // Accept Bitcoin as a funding source in this instance?
							 amount     : acceptBitcoin ? acceptBitcoinAmountInCents : undefined, // Needed only when accepting Bitcoin.
							 currency   : acceptBitcoin ? acceptBitcoinCurrency : undefined, // Needed only when accepting Bitcoin.
							 description: acceptBitcoin ? acceptBitcoinDesc : undefined, // Needed only when accepting Bitcoin.

							 key            : '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_stripe_api_publishable_key"]); ?>',
							 zipCode        : '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_stripe_api_validate_zipcode"]); ?>' == '1',
							 image          : '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_stripe_api_image"]); ?>',
							 panelLabel     : '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("Add", "s2member-front", "s2member")); ?>',
							 email          : $(registrationSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-email').val(),
							 allowRememberMe: true, // Allow Stripe to remember the customer.

							 token: function(token) // Callback handler.
							 {
								 $(sourceTokenInput).val(token.id), $(sourceTokenSummaryInput).val(buildSourceTokenTextSummary(token)),
									 $(sourceTokenSummary).html(ws_plugin__s2member_escHtml(buildSourceTokenTextSummary(token))),
									 handleBillingMethod(); // Adjust billing methods fields now also.
							 }
						 });
						getSourceToken.open(); // Open Stripe overlay.
					});
					$coForm.on('submit', function(/* Form validation. */)
					{
						if($.inArray($(submissionNonceVerification).val(), ['option', 'apply-coupon']) === -1)
						{
							var context = this, label = '', error = '', errors = '',
								$recaptchaResponse = $(captchaSection + ' input#recaptcha_response_field, '+captchaSection+' #g-recaptcha-response'),
								$password1 = $(registrationSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-password1[aria-required="true"]'),
								$password2 = $(registrationSection + ' input#s2member-pro-stripe-' + coTypeWithDashes + '-password2');

							if(!$(sourceTokenInput).val())
							{
								alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("No Billing Method; please try again.", "s2member-front", "s2member")); ?>');
								return false; // Error; cannot continue in this scenario.
							}
							$(':input', context)
								.each(function(/* Go through them all together. */)
								      {
									      var id = $.trim($(this).attr('id')).replace(/---[0-9]+$/g, ''/* Remove numeric suffixes. */);
									      if(id && (label = $.trim($('label[for="' + id.replace(/-(month|year)/, '') + '"]', context).first().children('span').first().text().replace(/[\r\n\t]+/g, ' '))))
									      {
										      if(error = ws_plugin__s2member_validationErrors(label, this, context))
											      errors += error + '\n\n'/* Collect errors. */;
									      }
								      });
							if((errors = $.trim(errors)))
							{
								alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + errors);
								return false; // Error; cannot continue in this scenario.
							}
							else if($password1.length && $.trim($password1.val()) !== $.trim($password2.val()))
							{
								alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("Passwords do not match up. Please try again.", "s2member-front", "s2member")); ?>');
								return false; // Error; cannot continue in this scenario.
							}
							else if($password1.length && $.trim($password1.val()).length < ws_plugin__s2member_passwordMinLength())
							{
								alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(sprintf(_x("Password MUST be at least %s characters. Please try again.", "s2member-front", "s2member"), c_ws_plugin__s2member_user_securities::min_password_length())); ?>');
								return false;
							}
							else if($password1.length && ws_plugin__s2member_passwordStrengthMeter($.trim($password1.val()), $.trim($password2.val()), true) < ws_plugin__s2member_passwordMinStrengthScore())
							{
								alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(sprintf(_x("Password strength MUST be %s. Please try again.", "s2member-front", "s2member"), c_ws_plugin__s2member_user_securities::min_password_strength_label())); ?>');
								return false;
							}
							else if($recaptchaResponse.length && !$recaptchaResponse.val())
							{
								alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq(_x("Security Verification missing. Please try again.", "s2member-front", "s2member")); ?>');
								return false; // Error; cannot continue in this scenario.
							}
							// $(optionsSelect).attr(disabled); // Not an option selection.
							// Bug fix. Don't disable, because that prevents it from being submitted.
						}
						$(couponApplyButton).attr(disabled),
							$(submissionButton).attr(disabled), ws_plugin__s2member_animateProcessing($(submissionButton));
						return true; // Allow submission.
					});
				})($coForm);
			}
			var buildSourceTokenTextSummary = function(token)
			{
				if(typeof token !== 'object') return '';

				if(token.type === 'bank_account' && token.bank_account)
					return token.bank_account.bank_name + ': xxxx...' + token.bank_account.last4;

				if(token.type === 'card' && token.card)
					return token.card.brand + ': xxxx-xxxx-xxxx-' + token.card.last4;

				if(token.type === 'bitcoin_receiver' && token.inbound_address)
					return 'Bitcoin to: ' + token.inbound_address;

				return 'Token: ' + token.id;
			};
			/*
			 Jump to responses.
			 */
			$('div#s2member-pro-stripe-form-response')
				.each(function()
				      {
					      scrollTo(0, $(this).offset().top - 100);
				      });
		}
	});
