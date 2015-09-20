/**
* Core JavaScript routines for PayPal®.
*
* Copyright: © 2009-2011
* {@link http://www.websharks-inc.com/ WebSharks, Inc.}
* (coded in the USA)
*
* This WordPress® plugin (s2Member Pro) is comprised of two parts:
*
* o (1) Its PHP code is licensed under the GPL license, as is WordPress®.
* 	You should have received a copy of the GNU General Public License,
* 	along with this software. In the main directory, see: /licensing/
* 	If not, see: {@link http://www.gnu.org/licenses/}.
*
* o (2) All other parts of (s2Member Pro); including, but not limited to:
* 	the CSS code, some JavaScript code, images, and design;
* 	are licensed according to the license purchased.
* 	See: {@link http://www.s2member.com/prices/}
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
* @package s2Member\PayPal
* @since 1.5
*/
jQuery(document).ready (function($)
	{
		var $clForm, $upForm, $rgForm, $spForm, $coForm, jumpToResponses, preloadAjaxLoader, ariaTrue = {'aria-required': 'true'}, ariaFalse = {'aria-required': 'false'}, disabled = {'disabled': 'disabled'}, ariaFalseDis = {'aria-required': 'false', 'disabled': 'disabled'};

		preloadAjaxLoader = new Image (), preloadAjaxLoader.src = '<?php echo $vars["i"]; ?>/ajax-loader.gif';

		if($('form.s2member-pro-paypal-registration-form').length > 1 || $('form.s2member-pro-paypal-checkout-form').length > 1 || $('form.s2member-pro-paypal-sp-checkout-form').length > 1)
			return alert('Detected more than one s2Member Pro-Form.\n\nPlease use only ONE s2Member Pro-Form Shortcode on each Post/Page. Attempting to serve more than one Pro-Form on each Post/Page (even w/ DHTML) may result in unexpected/broken functionality.');

		if (($clForm = $('form#s2member-pro-paypal-cancellation-form')).length === 1)
			{
				var captchaSection = 'div#s2member-pro-paypal-cancellation-form-captcha-section', submissionSection = 'div#s2member-pro-paypal-cancellation-form-submission-section', $submissionButton = $(submissionSection + ' button#s2member-pro-paypal-cancellation-submit');

				ws_plugin__s2member_animateProcessing($submissionButton, 'reset'), $submissionButton.removeAttr ('disabled');

				$clForm.submit ( /* Form validation. */function()
					{
						var context = this, label = '', error = '', errors = '';

						var $recaptchaResponse = $(captchaSection + ' input#recaptcha_response_field, '+captchaSection+' #g-recaptcha-response');

						$(':input', context).each ( /* Go through them all together. */function()
							{
								var id = /* Remove numeric suffixes. */ $.trim ($(this).attr ('id')).replace (/---[0-9]+$/g, '');

								if (id && (label = $.trim ($('label[for="' + id + '"]', context).first ().children ('span').first ().text ().replace (/[\r\n\t]+/g, ' '))))
									{
										if (error = ws_plugin__s2member_validationErrors(label, this, context))
											errors += /* Collect errors. */ error + '\n\n';
									}
							});

						if (errors = $.trim (errors))
							{
								alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + errors);

								return false;
							}

						else if ($recaptchaResponse.length && !$recaptchaResponse.val ())
							{
								alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Security Verification missing. Please try again.", "s2member-front", "s2member")); ?>');

								return false;
							}

						$submissionButton.attr (disabled), ws_plugin__s2member_animateProcessing($submissionButton);

						return true;
					});
			}

		else if (($upForm = $('form#s2member-pro-paypal-update-form')).length === 1)
			{
				var handleBillingMethod, billingMethodSection = 'div#s2member-pro-paypal-update-form-billing-method-section', billingAddressSection = 'div#s2member-pro-paypal-update-form-billing-address-section', cardType = billingMethodSection + ' input[name="s2member_pro_paypal_update\[card_type\]"]', captchaSection = 'div#s2member-pro-paypal-update-form-captcha-section', submissionSection = 'div#s2member-pro-paypal-update-form-submission-section', $submissionButton = $(submissionSection + ' button#s2member-pro-paypal-update-submit');

				ws_plugin__s2member_animateProcessing($submissionButton, 'reset'), $submissionButton.removeAttr ('disabled');

				(handleBillingMethod = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						var billingMethod = /* Billing Method. */ $(cardType + ':checked').val ();

						if ($.inArray (billingMethod, ['Visa', 'MasterCard', 'Amex', 'Discover']) !== -1)
							{
								$(billingMethodSection + ' > div.s2member-pro-paypal-update-form-div').show ();
								$(billingMethodSection + ' > div.s2member-pro-paypal-update-form-div :input').attr (ariaTrue);

								$(billingMethodSection + ' > div#s2member-pro-paypal-update-form-card-start-date-issue-number-div').hide ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-update-form-card-start-date-issue-number-div :input').attr (ariaFalse);

								$(billingAddressSection + ' > div.s2member-pro-paypal-update-form-div').show ();
								$(billingAddressSection + ' > div.s2member-pro-paypal-update-form-div :input').attr (ariaTrue);

								$(billingAddressSection).show (), (eventTrigger) ? $(billingMethodSection + ' input#s2member-pro-paypal-update-card-number').focus () : null;
							}
						else if ($.inArray (billingMethod, ['Maestro', 'Solo']) !== -1)
							{
								$(billingMethodSection + ' > div.s2member-pro-paypal-update-form-div').show ();
								$(billingMethodSection + ' > div.s2member-pro-paypal-update-form-div :input').attr (ariaTrue);

								$(billingAddressSection + ' > div.s2member-pro-paypal-update-form-div').show ();
								$(billingAddressSection + ' > div.s2member-pro-paypal-update-form-div :input').attr (ariaTrue);

								$(billingAddressSection).show (), (eventTrigger) ? $(billingMethodSection + ' input#s2member-pro-paypal-update-card-number').focus () : null;
							}
						else if /* Else there was no Billing Method supplied. */ (!billingMethod)
							{
								$(billingMethodSection + ' > div.s2member-pro-paypal-update-form-div').hide ();
								$(billingMethodSection + ' > div.s2member-pro-paypal-update-form-div :input').attr (ariaFalse);

								$(billingMethodSection + ' > div#s2member-pro-paypal-update-form-card-type-div').show ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-update-form-card-type-div :input').attr (ariaTrue);

								$(billingAddressSection + ' > div.s2member-pro-paypal-update-form-div').hide ();
								$(billingAddressSection + ' > div.s2member-pro-paypal-update-form-div :input').attr (ariaFalse);

								$(billingAddressSection).hide (), (eventTrigger) ? $(submissionSection + ' button#s2member-pro-paypal-update-submit').focus () : null;
							}
					}) ();

				$(cardType).click (handleBillingMethod).change (handleBillingMethod);

				$upForm.submit ( /* Form validation. */function()
					{
						var context = this, label = '', error = '', errors = '';

						var $recaptchaResponse = $(captchaSection + ' input#recaptcha_response_field, '+captchaSection+' #g-recaptcha-response');

						if (!$(cardType + ':checked').val ())
							{
								alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Please choose a Billing Method.", "s2member-front", "s2member")); ?>');

								return false;
							}

						$(':input', context).each ( /* Go through them all together. */function()
							{
								var id = /* Remove numeric suffixes. */ $.trim ($(this).attr ('id')).replace (/---[0-9]+$/g, '');

								if (id && (label = $.trim ($('label[for="' + id.replace(/-(month|year)/, '') + '"]', context).first ().children ('span').first ().text ().replace (/[\r\n\t]+/g, ' '))))
									{
										if (error = ws_plugin__s2member_validationErrors(label, this, context))
											errors += /* Collect errors. */ error + '\n\n';
									}
							});

						if (errors = $.trim (errors))
							{
								alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + errors);

								return false;
							}

						else if ($recaptchaResponse.length && !$recaptchaResponse.val ())
							{
								alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Security Verification missing. Please try again.", "s2member-front", "s2member")); ?>');

								return false;
							}

						$submissionButton.attr (disabled), ws_plugin__s2member_animateProcessing($submissionButton);

						return true;
					});
			}

		else if (($rgForm = $('form#s2member-pro-paypal-registration-form')).length === 1)
			{
				var handleOptions, handleNameIssues, handlePasswordIssues, optionsSection = 'div#s2member-pro-paypal-registration-form-options-section', descSection = 'div#s2member-pro-paypal-registration-form-description-section', registrationSection = 'div#s2member-pro-paypal-registration-form-registration-section', captchaSection = 'div#s2member-pro-paypal-registration-form-captcha-section', submissionSection = 'div#s2member-pro-paypal-registration-form-submission-section', submissionNonceVerification = submissionSection + ' input#s2member-pro-paypal-registration-nonce', $submissionButton = $(submissionSection + ' button#s2member-pro-paypal-registration-submit');

				ws_plugin__s2member_animateProcessing($submissionButton, 'reset'), $submissionButton.removeAttr ('disabled');

				(handleOptions = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if (!$(optionsSection + ' select#s2member-pro-paypal-registration-options option').length)
							{
								$(optionsSection).hide /* No options on this particular form. */ ();
								$(descSection).show /* Show description on this particular form. */ ();
							}
						else // This is turned off by default for smoother loading. (via: display:none).
							{
								$(optionsSection).show /* OK. So we need to display this now. */ ();
								$(descSection).hide /* OK. So we need to hide this now. */ ();
								$(optionsSection + ' select#s2member-pro-paypal-registration-options').change
									(function() // Handle option changes.
									 {
										$(submissionNonceVerification).val ('option');
										 $rgForm.attr('action', $rgForm.attr('action').replace(/#.*$/, '')+'#s2p-form');
										 $rgForm.submit ();
									 });
							}
					}) ();

				(handleNameIssues = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if ($(submissionSection + ' input#s2member-pro-paypal-registration-names-not-required-or-not-possible').length)
							{
								$(registrationSection + ' > div#s2member-pro-paypal-registration-form-first-name-div').hide ();
								$(registrationSection + ' > div#s2member-pro-paypal-registration-form-first-name-div :input').attr (ariaFalseDis);

								$(registrationSection + ' > div#s2member-pro-paypal-registration-form-last-name-div').hide ();
								$(registrationSection + ' > div#s2member-pro-paypal-registration-form-last-name-div :input').attr (ariaFalseDis);
							}
					}) ();

				(handlePasswordIssues = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if ($(submissionSection + ' input#s2member-pro-paypal-registration-password-not-required-or-not-possible').length)
							{
								$(registrationSection + ' > div#s2member-pro-paypal-registration-form-password-div').hide ();
								$(registrationSection + ' > div#s2member-pro-paypal-registration-form-password-div :input').attr (ariaFalseDis);
							}
					}) ();

				$(registrationSection + ' > div#s2member-pro-paypal-registration-form-password-div :input').on('keyup initialize.s2', function()
					{
						ws_plugin__s2member_passwordStrength($(registrationSection + ' input#s2member-pro-paypal-registration-username'), $(registrationSection + ' input#s2member-pro-paypal-registration-password1'), $(registrationSection + ' input#s2member-pro-paypal-registration-password2'), $(registrationSection + ' div#s2member-pro-paypal-registration-form-password-strength'));
					}).trigger('initialize.s2');

				$rgForm.submit ( /* Form validation. */function()
					{
						if ($.inArray($(submissionNonceVerification).val (), ['option']) === -1)
							{
								var context = this, label = '', error = '', errors = '';

								var $recaptchaResponse = $(captchaSection + ' input#recaptcha_response_field, '+captchaSection+' #g-recaptcha-response');

								var $password1 = $(registrationSection + ' input#s2member-pro-paypal-registration-password1[aria-required="true"]');
								var $password2 = $(registrationSection + ' input#s2member-pro-paypal-registration-password2');

								$(':input', context).each ( /* Go through them all together. */function()
									{
										var id = /* Remove numeric suffixes. */ $.trim ($(this).attr ('id')).replace (/---[0-9]+$/g, '');

										if (id && (label = $.trim ($('label[for="' + id + '"]', context).first ().children ('span').first ().text ().replace (/[\r\n\t]+/g, ' '))))
											{
												if (error = ws_plugin__s2member_validationErrors(label, this, context))
													errors += /* Collect errors. */ error + '\n\n';
											}
									});

								if (errors = $.trim (errors))
									{
										alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + errors);

										return false;
									}

								else if ($password1.length && $.trim ($password1.val ()) !== $.trim ($password2.val ()))
									{
										alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Passwords do not match up. Please try again.", "s2member-front", "s2member")); ?>');

										return false;
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

								else if ($recaptchaResponse.length && !$recaptchaResponse.val ())
									{
										alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Security Verification missing. Please try again.", "s2member-front", "s2member")); ?>');

										return false;
									}
							}
						$submissionButton.attr (disabled), ws_plugin__s2member_animateProcessing($submissionButton);

						return true;
					});
			}

		else if (($spForm = $('form#s2member-pro-paypal-sp-checkout-form')).length === 1)
			{
				var handleOptions, handleCouponIssues, handleTaxIssues, taxMayApply = true, calculateTax, cTaxDelay, cTaxTimeout, cTaxReq, cTaxLocation, handleExistingUsers, handleBillingMethod, optionsSection = 'div#s2member-pro-paypal-sp-checkout-form-options-section', descSection = 'div#s2member-pro-paypal-sp-checkout-form-description-section', couponSection = 'div#s2member-pro-paypal-sp-checkout-form-coupon-section', couponApplyButton = couponSection + ' input#s2member-pro-paypal-sp-checkout-coupon-apply', registrationSection = 'div#s2member-pro-paypal-sp-checkout-form-registration-section', billingMethodSection = 'div#s2member-pro-paypal-sp-checkout-form-billing-method-section', cardType = billingMethodSection + ' input[name="s2member_pro_paypal_sp_checkout\[card_type\]"]', billingAddressSection = 'div#s2member-pro-paypal-sp-checkout-form-billing-address-section', $ajaxTaxDiv = $(billingAddressSection + ' > div#s2member-pro-paypal-sp-checkout-form-ajax-tax-div'), captchaSection = 'div#s2member-pro-paypal-sp-checkout-form-captcha-section', submissionSection = 'div#s2member-pro-paypal-sp-checkout-form-submission-section', submissionNonceVerification = submissionSection + ' input#s2member-pro-paypal-sp-checkout-nonce', submissionButton = submissionSection + ' #s2member-pro-paypal-sp-checkout-submit';

				var paypalLangCode = $.trim($('input#s2member-pro-paypal-lang-attr').val());
				var submitButton = '<button type="submit" id="s2member-pro-paypal-sp-checkout-submit" class="s2member-pro-paypal-submit s2member-pro-paypal-sp-checkout-submit btn btn-primary" tabindex="500"><?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (esc_html (_x ("Submit Form", "s2member-front", "s2member"))); ?></button>';
				var submitExpressCheckoutButton = '<input type="image" src="https://www.paypal.com/'+((paypalLangCode) ? paypalLangCode : '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (esc_attr (_x ("en_US", "s2member-front paypal-button-lang-code", "s2member"))); ?>')+'/i/btn/btn_xpressCheckout.gif" id="s2member-pro-paypal-sp-checkout-submit" class="s2member-pro-paypal-submit s2member-pro-paypal-sp-checkout-submit" value="<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (esc_attr (_x ("Submit Form", "s2member-front", "s2member"))); ?>" tabindex="500" />';

				ws_plugin__s2member_animateProcessing($(submissionButton), 'reset'), $(submissionButton).removeAttr ('disabled'), $(couponApplyButton).removeAttr ('disabled');

				(handleOptions = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if (!$(optionsSection + ' select#s2member-pro-paypal-sp-checkout-options option').length)
							{
								$(optionsSection).hide /* No options on this particular form. */ ();
								$(descSection).show /* Show description on this particular form. */ ();
							}
						else // This is turned off by default for smoother loading. (via: display:none).
							{
								$(optionsSection).show /* OK. So we need to display this now. */ ();
								$(descSection).hide /* OK. So we need to hide this now. */ ();
								$(optionsSection + ' select#s2member-pro-paypal-sp-checkout-options').change
									(function() // Handle option changes.
									 {
										$(submissionNonceVerification).val ('option');
										 $spForm.attr('action', $spForm.attr('action').replace(/#.*$/, '')+'#s2p-form');
										 $spForm.submit ();
									 });
							}
					}) ();

				(handleCouponIssues = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if ($(submissionSection + ' input#s2member-pro-paypal-sp-checkout-coupons-not-required-or-not-possible').length)
							{
								$(couponSection).hide /* Not accepting Coupons on this particular form. */ ();
							}
						else // This is turned off by default for smoother loading. (via: display:none).
							$(couponSection).show /* OK. So we need to display this now. */ ();
					}) ();

				(handleTaxIssues = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if ($(submissionSection + ' input#s2member-pro-paypal-sp-checkout-tax-not-required-or-not-possible').length)
							{
								$ajaxTaxDiv.hide (), taxMayApply = /* Tax does NOT even apply. */ false;
							}
					}) ();

				(calculateTax = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if (taxMayApply && !(eventTrigger && eventTrigger.interval && document.activeElement.id === 's2member-pro-paypal-sp-checkout-country'))
							{
								var attr = $(submissionSection + ' input#s2member-pro-paypal-sp-checkout-attr').val ();
								var state = $.trim ($(billingAddressSection + ' input#s2member-pro-paypal-sp-checkout-state').val ());
								var country = $(billingAddressSection + ' select#s2member-pro-paypal-sp-checkout-country').val ();
								var zip = $.trim ($(billingAddressSection + ' input#s2member-pro-paypal-sp-checkout-zip').val ());
								var thisTaxLocation = /* Three part location. */ state + '|' + country + '|' + zip;

								if (state && country && zip && thisTaxLocation && (!cTaxLocation || cTaxLocation !== thisTaxLocation) && (cTaxLocation = thisTaxLocation))
									{
										(cTaxReq) ? /* Abort. */ cTaxReq.abort () : null, /* Clear. */ clearTimeout(cTaxTimeout), cTaxTimeout = null;

										$ajaxTaxDiv.html ('<div><img src="<?php echo $vars["i"]; ?>/ajax-loader.gif" alt="<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (esc_attr (_x ("Calculating Sales Tax...", "s2member-front", "s2member"))); ?>" /> <?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("calculating sales tax...", "s2member-front", "s2member")); ?></div>');

										cTaxTimeout = setTimeout( /* Create a new cTaxTimeout with a one second delay. */function()
											{
												cTaxReq = $.post ('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (admin_url ("/admin-ajax.php")); ?>', {'action': 'ws_plugin__s2member_pro_paypal_ajax_tax', 'ws_plugin__s2member_pro_paypal_ajax_tax': '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (c_ws_plugin__s2member_utils_encryption::encrypt ("ws-plugin--s2member-pro-paypal-ajax-tax")); ?>', 'ws_plugin__s2member_pro_paypal_ajax_tax_vars[attr]': attr, 'ws_plugin__s2member_pro_paypal_ajax_tax_vars[state]': state, 'ws_plugin__s2member_pro_paypal_ajax_tax_vars[country]': country, 'ws_plugin__s2member_pro_paypal_ajax_tax_vars[zip]': zip}, function(response)
													{
														clearTimeout /* Clear cTaxTimeout. */(cTaxTimeout), cTaxTimeout = null;

														try // Try/catch here. jQuery will sometimes return a successful response in IE, whenever the connection is aborted with a null response.
															{
																/* translators: `Sales Tax (Today)` and `Total (Today)` The word `Today` is displayed when/if a trial period is offered. The word `Today` is translated elsewhere. */
																$ajaxTaxDiv.html ('<div>' + $.sprintf ('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("<strong>Sales Tax%s:</strong> %s<br /><strong>— Total%s:</strong> %s", "s2member-front", "s2member")); ?>', ((response.trial) ? ' ' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Today", "s2member-front", "s2member")); ?>' : ''), ((response.tax_per) ? '<em>' + response.tax_per + '</em> ( ' + response.cur_symbol + '' + response.tax + ' )' : response.cur_symbol + '' + response.tax), ((response.trial) ? ' ' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Today", "s2member-front", "s2member")); ?>' : ''), response.cur_symbol + '' + response.total) + '</div>');
															}
														catch (e) {}

													}, 'json');

											}, ((eventTrigger && eventTrigger.keyCode) ? 1000 : 100));
									}

								else if (!state || !country || !zip || !thisTaxLocation)
									$ajaxTaxDiv.html (''), cTaxLocation = null;
							}
					}) ();

				cTaxDelay = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						setTimeout( /* Trigger event handler with a brief delay. */function()
							{
								calculateTax(eventTrigger);
							}, 10); // Brief delay.
					};

				$(billingAddressSection + ' input#s2member-pro-paypal-sp-checkout-state').bind ('keyup blur', calculateTax).bind ('cut paste', cTaxDelay);
				$(billingAddressSection + ' input#s2member-pro-paypal-sp-checkout-zip').bind ('keyup blur', calculateTax).bind ('cut paste', cTaxDelay);
				$(billingAddressSection + ' select#s2member-pro-paypal-sp-checkout-country').bind ('change', calculateTax);

				setInterval( /* Helps with things like Google's Autofill feature. */function()
					{
						calculateTax /* Identify as interval trigger. */({interval: true});
					}, 1000);

				(handleExistingUsers = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if /* If User/Member is already logged in. */ (S2MEMBER_CURRENT_USER_IS_LOGGED_IN)
							{
								$(registrationSection + ' input#s2member-pro-paypal-sp-checkout-first-name').each (function()
									{
										var $this = $(this), val = $this.val ();
										(!val) ? $this.val (S2MEMBER_CURRENT_USER_FIRST_NAME) : null;
									});

								$(registrationSection + ' input#s2member-pro-paypal-sp-checkout-last-name').each (function()
									{
										var $this = $(this), val = $this.val ();
										(!val) ? $this.val (S2MEMBER_CURRENT_USER_LAST_NAME) : null;
									});

								$(registrationSection + ' input#s2member-pro-paypal-sp-checkout-email').each (function()
									{
										var $this = $(this), val = $this.val ();
										(!val) ? $this.val (S2MEMBER_CURRENT_USER_EMAIL) : null;
									});
							}
					}) ();

				(handleBillingMethod = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if ($(submissionSection + ' input#s2member-pro-paypal-sp-checkout-payment-not-required-or-not-possible').length)
								$(cardType).val (['Free']); // No payment required in this VERY special case.

						var billingMethod = /* Billing Method. */ $(cardType + ':checked').val ();

						if ($.inArray (billingMethod, ['Free']) !== -1)
							{
								$(billingMethodSection).hide (), $(billingAddressSection).hide ();

								$(billingMethodSection + ' > div.s2member-pro-paypal-sp-checkout-form-div').hide ();
								$(billingMethodSection + ' > div.s2member-pro-paypal-sp-checkout-form-div :input').attr (ariaFalse);

								$(billingAddressSection + ' > div.s2member-pro-paypal-sp-checkout-form-div').hide ();
								$(billingAddressSection + ' > div.s2member-pro-paypal-sp-checkout-form-div :input').attr (ariaFalse);

								(!taxMayApply) ? /* Tax does NOT even apply. */ $ajaxTaxDiv.hide () : null;

								$(submissionSection + ' #s2member-pro-paypal-sp-checkout-submit').replaceWith (submitButton);

								(eventTrigger) ? $(submissionSection + ' #s2member-pro-paypal-sp-checkout-submit').focus () : null;
							}
						else if ($.inArray (billingMethod, ['Visa', 'MasterCard', 'Amex', 'Discover']) !== -1)
							{
								$(billingMethodSection).show (), $(billingAddressSection).show ();

								$(billingMethodSection + ' > div.s2member-pro-paypal-sp-checkout-form-div').show ();
								$(billingMethodSection + ' > div.s2member-pro-paypal-sp-checkout-form-div :input').attr (ariaTrue);

								$(billingMethodSection + ' > div#s2member-pro-paypal-sp-checkout-form-card-start-date-issue-number-div').hide ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-sp-checkout-form-card-start-date-issue-number-div :input').attr (ariaFalse);

								$(billingAddressSection + ' > div.s2member-pro-paypal-sp-checkout-form-div').show ();
								$(billingAddressSection + ' > div.s2member-pro-paypal-sp-checkout-form-div :input').attr (ariaTrue);

								(!taxMayApply) ? /* Tax does NOT even apply. */ $ajaxTaxDiv.hide () : null;

								$(submissionSection + ' #s2member-pro-paypal-sp-checkout-submit').replaceWith (submitButton);

								(eventTrigger) ? $(billingMethodSection + ' input#s2member-pro-paypal-sp-checkout-card-number').focus () : null;
							}
						else if ($.inArray (billingMethod, ['Maestro', 'Solo']) !== -1)
							{
								$(billingMethodSection).show (), $(billingAddressSection).show ();

								$(billingMethodSection + ' > div.s2member-pro-paypal-sp-checkout-form-div').show ();
								$(billingMethodSection + ' > div.s2member-pro-paypal-sp-checkout-form-div :input').attr (ariaTrue);

								$(billingAddressSection + ' > div.s2member-pro-paypal-sp-checkout-form-div').show ();
								$(billingAddressSection + ' > div.s2member-pro-paypal-sp-checkout-form-div :input').attr (ariaTrue);

								(!taxMayApply) ? /* Tax does NOT even apply. */ $ajaxTaxDiv.hide () : null;

								$(submissionSection + ' #s2member-pro-paypal-sp-checkout-submit').replaceWith (submitButton);

								(eventTrigger) ? $(billingMethodSection + ' input#s2member-pro-paypal-sp-checkout-card-number').focus () : null;
							}
						else if ((!billingMethod || billingMethod === 'PayPal') && taxMayApply)
							{
								if($(billingMethodSection).attr('data-paypal-only'))
									$(billingMethodSection).hide();
								else $(billingMethodSection).show ();

								$(billingAddressSection).show (); // Need this when tax may apply.

								$(billingMethodSection + ' > div.s2member-pro-paypal-sp-checkout-form-div').show ();
								$(billingMethodSection + ' > div.s2member-pro-paypal-sp-checkout-form-div :input').attr (ariaTrue);

								$(billingMethodSection + ' > div#s2member-pro-paypal-sp-checkout-form-card-number-div').hide ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-sp-checkout-form-card-number-div :input').attr (ariaFalse);

								$(billingMethodSection + ' > div#s2member-pro-paypal-sp-checkout-form-card-expiration-div').hide ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-sp-checkout-form-card-expiration-div :input').attr (ariaFalse);

								$(billingMethodSection + ' > div#s2member-pro-paypal-sp-checkout-form-card-verification-div').hide ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-sp-checkout-form-card-verification-div :input').attr (ariaFalse);

								$(billingMethodSection + ' > div#s2member-pro-paypal-sp-checkout-form-card-start-date-issue-number-div').hide ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-sp-checkout-form-card-start-date-issue-number-div :input').attr (ariaFalse);

								$(billingAddressSection + ' > div.s2member-pro-paypal-sp-checkout-form-div').show ();
								$(billingAddressSection + ' > div.s2member-pro-paypal-sp-checkout-form-div :input').attr (ariaTrue);

								$(billingAddressSection + ' > div#s2member-pro-paypal-sp-checkout-form-street-div').hide ();
								$(billingAddressSection + ' > div#s2member-pro-paypal-sp-checkout-form-street-div :input').attr (ariaFalse);

								$(billingAddressSection + ' > div#s2member-pro-paypal-sp-checkout-form-city-div').hide ();
								$(billingAddressSection + ' > div#s2member-pro-paypal-sp-checkout-form-city-div :input').attr (ariaFalse);

								$(submissionSection + ' #s2member-pro-paypal-sp-checkout-submit').replaceWith (submitExpressCheckoutButton);

								(eventTrigger) ? $(billingAddressSection + ' input#s2member-pro-paypal-sp-checkout-state').focus () : null;
							}
						else if (!billingMethod || billingMethod === 'PayPal')
							{
								if($(billingMethodSection).attr('data-paypal-only'))
									$(billingMethodSection).hide();
								else $(billingMethodSection).show ();

								$(billingAddressSection).hide (); // Don't need this either.

								$(billingMethodSection + ' > div.s2member-pro-paypal-sp-checkout-form-div').hide ();
								$(billingMethodSection + ' > div.s2member-pro-paypal-sp-checkout-form-div :input').attr (ariaFalse);

								$(billingMethodSection + ' > div#s2member-pro-paypal-sp-checkout-form-card-type-div').show ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-sp-checkout-form-card-type-div :input').attr (ariaTrue);

								$(billingAddressSection + ' > div.s2member-pro-paypal-sp-checkout-form-div').hide ();
								$(billingAddressSection + ' > div.s2member-pro-paypal-sp-checkout-form-div :input').attr (ariaFalse);

								$(submissionSection + ' #s2member-pro-paypal-sp-checkout-submit').replaceWith (submitExpressCheckoutButton);

								(eventTrigger) ? $(submissionSection + ' #s2member-pro-paypal-sp-checkout-submit').focus () : null;
							}

						handleTaxIssues /* Tax issues. */ ();
					}) ();

				$(cardType).click (handleBillingMethod).change (handleBillingMethod);

				$(couponApplyButton).click ( /* Only applying coupon. */function()
					{
						$(submissionNonceVerification).val ('apply-coupon'), $spForm.submit ();
					});

				$spForm.submit ( /* Form validation. */function()
					{
						if ($.inArray($(submissionNonceVerification).val (), ['option', 'apply-coupon']) === -1)
							{
								var context = this, label = '', error = '', errors = '';

								var $recaptchaResponse = $(captchaSection + ' input#recaptcha_response_field, '+captchaSection+' #g-recaptcha-response');

								if (!$(cardType + ':checked').val ())
									{
										$(cardType).val (['PayPal']);
									}

								$(':input', context).each ( /* Go through them all together. */function()
									{
										var id = /* Remove numeric suffixes. */ $.trim ($(this).attr ('id')).replace (/---[0-9]+$/g, '');

										if (id && (label = $.trim ($('label[for="' + id.replace(/-(month|year)/, '') + '"]', context).first ().children ('span').first ().text ().replace (/[\r\n\t]+/g, ' '))))
											{
												if (error = ws_plugin__s2member_validationErrors(label, this, context))
													errors += /* Collect errors. */ error + '\n\n';
											}
									});

								if (errors = $.trim (errors))
									{
										alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + errors);

										return false;
									}

								else if ($recaptchaResponse.length && !$recaptchaResponse.val ())
									{
										alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Security Verification missing. Please try again.", "s2member-front", "s2member")); ?>');

										return false;
									}
							}

						$(submissionButton).attr (disabled), ws_plugin__s2member_animateProcessing($(submissionButton)), $(couponApplyButton).attr (disabled);

						return true;
					});
			}

		else if (($coForm = $('form#s2member-pro-paypal-checkout-form')).length === 1)
			{
				var handleOptions, handleCouponIssues, handleTaxIssues, taxMayApply = true, calculateTax, cTaxDelay, cTaxTimeout, cTaxReq, cTaxLocation, handlePasswordIssues, handleBillingMethod, handleExistingUsers, optionsSection = 'div#s2member-pro-paypal-checkout-form-options-section', descSection = 'div#s2member-pro-paypal-checkout-form-description-section', couponSection = 'div#s2member-pro-paypal-checkout-form-coupon-section', couponApplyButton = couponSection + ' input#s2member-pro-paypal-checkout-coupon-apply', registrationSection = 'div#s2member-pro-paypal-checkout-form-registration-section', customFieldsSection = 'div#s2member-pro-paypal-checkout-form-custom-fields-section', billingMethodSection = 'div#s2member-pro-paypal-checkout-form-billing-method-section', cardType = billingMethodSection + ' input[name="s2member_pro_paypal_checkout\[card_type\]"]', billingAddressSection = 'div#s2member-pro-paypal-checkout-form-billing-address-section', $ajaxTaxDiv = $(billingAddressSection + ' > div#s2member-pro-paypal-checkout-form-ajax-tax-div'), captchaSection = 'div#s2member-pro-paypal-checkout-form-captcha-section', submissionSection = 'div#s2member-pro-paypal-checkout-form-submission-section', submissionNonceVerification = submissionSection + ' input#s2member-pro-paypal-checkout-nonce', submissionButton = submissionSection + ' #s2member-pro-paypal-checkout-submit';

				var paypalLangCode = $.trim($('input#s2member-pro-paypal-lang-attr').val());
				var submitButton = '<button type="submit" id="s2member-pro-paypal-checkout-submit" class="s2member-pro-paypal-submit s2member-pro-paypal-checkout-submit btn btn-primary" tabindex="600"><?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (esc_html (_x ("Submit Form", "s2member-front", "s2member"))); ?></button>';
				var submitExpressCheckoutButton = '<input type="image" src="https://www.paypal.com/'+((paypalLangCode) ? paypalLangCode : '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (esc_attr (_x ("en_US", "s2member-front paypal-button-lang-code", "s2member"))); ?>')+'/i/btn/btn_xpressCheckout.gif" id="s2member-pro-paypal-checkout-submit" class="s2member-pro-paypal-submit s2member-pro-paypal-checkout-submit" value="<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (esc_attr (_x ("Submit Form", "s2member-front", "s2member"))); ?>" tabindex="600" />';

				ws_plugin__s2member_animateProcessing($(submissionButton), 'reset'), $(submissionButton).removeAttr ('disabled'), $(couponApplyButton).removeAttr ('disabled');

				(handleOptions = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if (!$(optionsSection + ' select#s2member-pro-paypal-checkout-options option').length)
							{
								$(optionsSection).hide /* No options on this particular form. */ ();
								$(descSection).show /* Show description on this particular form. */ ();
							}
						else // This is turned off by default for smoother loading. (via: display:none).
							{
								$(optionsSection).show /* OK. So we need to display this now. */ ();
								$(descSection).hide /* OK. So we need to hide this now. */ ();
								$(optionsSection + ' select#s2member-pro-paypal-checkout-options').change
									(function() // Handle option changes.
									 {
										$(submissionNonceVerification).val ('option');
										 $coForm.attr('action', $coForm.attr('action').replace(/#.*$/, '')+'#s2p-form');
										 $coForm.submit ();
									 });
							}
					}) ();

				(handleCouponIssues = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if ($(submissionSection + ' input#s2member-pro-paypal-checkout-coupons-not-required-or-not-possible').length)
							{
								$(couponSection).hide /* Not accepting Coupons on this particular form. */ ();
							}
						else // This is turned off by default for smoother loading. (via: display:none).
							$(couponSection).show /* OK. So we need to display this now. */ ();
					}) ();

				(handleTaxIssues = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if ($(submissionSection + ' input#s2member-pro-paypal-checkout-tax-not-required-or-not-possible').length)
							{
								$ajaxTaxDiv.hide (), taxMayApply = /* Tax does NOT even apply. */ false;
							}
					}) ();

				(calculateTax = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if (taxMayApply && !(eventTrigger && eventTrigger.interval && document.activeElement.id === 's2member-pro-paypal-checkout-country'))
							{
								var attr = $(submissionSection + ' input#s2member-pro-paypal-checkout-attr').val ();
								var state = $.trim ($(billingAddressSection + ' input#s2member-pro-paypal-checkout-state').val ());
								var country = $(billingAddressSection + ' select#s2member-pro-paypal-checkout-country').val ();
								var zip = $.trim ($(billingAddressSection + ' input#s2member-pro-paypal-checkout-zip').val ());
								var thisTaxLocation = /* Three part location. */ state + '|' + country + '|' + zip;

								if (state && country && zip && thisTaxLocation && (!cTaxLocation || cTaxLocation !== thisTaxLocation) && (cTaxLocation = thisTaxLocation))
									{
										(cTaxReq) ? /* Abort. */ cTaxReq.abort () : null, /* Clear. */ clearTimeout(cTaxTimeout), cTaxTimeout = null;

										$ajaxTaxDiv.html ('<div><img src="<?php echo $vars["i"]; ?>/ajax-loader.gif" alt="<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (esc_attr (_x ("Calculating Sales Tax...", "s2member-front", "s2member"))); ?>" /> <?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("calculating sales tax...", "s2member-front", "s2member")); ?></div>');

										cTaxTimeout = setTimeout( /* Create a new cTaxTimeout with a one second delay. */function()
											{
												cTaxReq = $.post ('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (admin_url ("/admin-ajax.php")); ?>', {'action': 'ws_plugin__s2member_pro_paypal_ajax_tax', 'ws_plugin__s2member_pro_paypal_ajax_tax': '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (c_ws_plugin__s2member_utils_encryption::encrypt ("ws-plugin--s2member-pro-paypal-ajax-tax")); ?>', 'ws_plugin__s2member_pro_paypal_ajax_tax_vars[attr]': attr, 'ws_plugin__s2member_pro_paypal_ajax_tax_vars[state]': state, 'ws_plugin__s2member_pro_paypal_ajax_tax_vars[country]': country, 'ws_plugin__s2member_pro_paypal_ajax_tax_vars[zip]': zip}, function(response, textStatus)
													{
														clearTimeout /* Clear cTaxTimeout. */(cTaxTimeout), cTaxTimeout = null;

														try // Try/catch here. jQuery will sometimes return a successful response in IE, whenever the connection is aborted with a null response.
															{
																/* translators: `Sales Tax (Today)` and `Total (Today)` The word `Today` is displayed when/if a trial period is offered. The word `Today` is translated elsewhere. */
																$ajaxTaxDiv.html ('<div>' + $.sprintf ('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("<strong>Sales Tax%s:</strong> %s<br /><strong>— Total%s:</strong> %s", "s2member-front", "s2member")); ?>', ((response.trial) ? ' ' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Today", "s2member-front", "s2member")); ?>' : ''), ((response.tax_per) ? '<em>' + response.tax_per + '</em> ( ' + response.cur_symbol + '' + response.tax + ' )' : response.cur_symbol + '' + response.tax), ((response.trial) ? ' ' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Today", "s2member-front", "s2member")); ?>' : ''), response.cur_symbol + '' + response.total) + '</div>');
															}
														catch (e) {}

													}, 'json');

											}, ((eventTrigger && eventTrigger.keyCode) ? 1000 : 100));
									}

								else if (!state || !country || !zip || !thisTaxLocation)
									$ajaxTaxDiv.html (''), cTaxLocation = null;
							}
					}) ();

				cTaxDelay = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						setTimeout( /* Trigger event handler with a brief delay. */function()
							{
								calculateTax(eventTrigger);
							}, 10); // Brief delay.
					};

				$(billingAddressSection + ' input#s2member-pro-paypal-checkout-state').bind ('keyup blur', calculateTax).bind ('cut paste', cTaxDelay);
				$(billingAddressSection + ' input#s2member-pro-paypal-checkout-zip').bind ('keyup blur', calculateTax).bind ('cut paste', cTaxDelay);
				$(billingAddressSection + ' select#s2member-pro-paypal-checkout-country').bind ('change', calculateTax);

				setInterval( /* Helps with things like Google's Autofill feature. */function()
					{
						calculateTax /* Identify as interval trigger. */({interval: true});
					}, 1000);

				(handlePasswordIssues = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if ($(submissionSection + ' input#s2member-pro-paypal-checkout-password-not-required-or-not-possible').length)
							{
								$(registrationSection + ' > div#s2member-pro-paypal-checkout-form-password-div').hide ();
								$(registrationSection + ' > div#s2member-pro-paypal-checkout-form-password-div :input').attr (ariaFalseDis);
							}
					}) ();

				(handleExistingUsers = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if /* If User/Member is already logged in. */ (S2MEMBER_CURRENT_USER_IS_LOGGED_IN)
							{
								$(registrationSection + ' input#s2member-pro-paypal-checkout-first-name').each (function()
									{
										var $this = $(this), val = $this.val ();
										(!val) ? $this.val (S2MEMBER_CURRENT_USER_FIRST_NAME) : null;
									});

								$(registrationSection + ' input#s2member-pro-paypal-checkout-last-name').each (function()
									{
										var $this = $(this), val = $this.val ();
										(!val) ? $this.val (S2MEMBER_CURRENT_USER_LAST_NAME) : null;
									});

								$(registrationSection + ' input#s2member-pro-paypal-checkout-email').val (S2MEMBER_CURRENT_USER_EMAIL).attr (ariaFalseDis);
								$(registrationSection + ' input#s2member-pro-paypal-checkout-username').val (S2MEMBER_CURRENT_USER_LOGIN).attr (ariaFalseDis);

								$(registrationSection + ' > div#s2member-pro-paypal-checkout-form-password-div').hide ();
								$(registrationSection + ' > div#s2member-pro-paypal-checkout-form-password-div :input').attr (ariaFalseDis);

								if ($.trim ($(registrationSection + ' > div#s2member-pro-paypal-checkout-form-registration-section-title').html ()) === '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Create Profile", "s2member-front", "s2member")); ?>')
									$(registrationSection + ' > div#s2member-pro-paypal-checkout-form-registration-section-title').html ('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Your Profile", "s2member-front", "s2member")); ?>');

								$(customFieldsSection).hide (), $(customFieldsSection + ' :input').attr (ariaFalseDis);
							}
					}) ();

				(handleBillingMethod = /* eventTrigger is passed by jQuery for DOM events. */ function(eventTrigger)
					{
						if ($(submissionSection + ' input#s2member-pro-paypal-checkout-payment-not-required-or-not-possible').length)
								$(cardType).val (['Free']); // No payment required in this VERY special case.

						var billingMethod = /* Billing Method. */ $(cardType + ':checked').val ();

						if ($.inArray (billingMethod, ['Free']) !== -1)
							{
								$(billingMethodSection).hide (), $(billingAddressSection).hide ();

								$(billingMethodSection + ' > div.s2member-pro-paypal-checkout-form-div').hide ();
								$(billingMethodSection + ' > div.s2member-pro-paypal-checkout-form-div :input').attr (ariaFalse);

								$(billingAddressSection + ' > div.s2member-pro-paypal-checkout-form-div').hide ();
								$(billingAddressSection + ' > div.s2member-pro-paypal-checkout-form-div :input').attr (ariaFalse);

								(!taxMayApply) ? /* Tax does NOT even apply. */ $ajaxTaxDiv.hide () : null;

								$(submissionSection + ' #s2member-pro-paypal-checkout-submit').replaceWith (submitButton);

								(eventTrigger) ? $(submissionSection + ' #s2member-pro-paypal-checkout-submit').focus () : null;
							}
						else if ($.inArray (billingMethod, ['Visa', 'MasterCard', 'Amex', 'Discover']) !== -1)
							{
								$(billingMethodSection).show (), $(billingAddressSection).show ();

								$(billingMethodSection + ' > div.s2member-pro-paypal-checkout-form-div').show ();
								$(billingMethodSection + ' > div.s2member-pro-paypal-checkout-form-div :input').attr (ariaTrue);

								$(billingMethodSection + ' > div#s2member-pro-paypal-checkout-form-card-start-date-issue-number-div').hide ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-checkout-form-card-start-date-issue-number-div :input').attr (ariaFalse);

								$(billingAddressSection + ' > div.s2member-pro-paypal-checkout-form-div').show ();
								$(billingAddressSection + ' > div.s2member-pro-paypal-checkout-form-div :input').attr (ariaTrue);

								(!taxMayApply) ? /* Tax does NOT even apply. */ $ajaxTaxDiv.hide () : null;

								$(submissionSection + ' #s2member-pro-paypal-checkout-submit').replaceWith (submitButton);

								(eventTrigger) ? $(billingMethodSection + ' input#s2member-pro-paypal-checkout-card-number').focus () : null;
							}
						else if ($.inArray (billingMethod, ['Maestro', 'Solo']) !== -1)
							{
								$(billingMethodSection).show (), $(billingAddressSection).show ();

								$(billingMethodSection + ' > div.s2member-pro-paypal-checkout-form-div').show ();
								$(billingMethodSection + ' > div.s2member-pro-paypal-checkout-form-div :input').attr (ariaTrue);

								$(billingAddressSection + ' > div.s2member-pro-paypal-checkout-form-div').show ();
								$(billingAddressSection + ' > div.s2member-pro-paypal-checkout-form-div :input').attr (ariaTrue);

								(!taxMayApply) ? /* Tax does NOT even apply. */ $ajaxTaxDiv.hide () : null;

								$(submissionSection + ' #s2member-pro-paypal-checkout-submit').replaceWith (submitButton);

								(eventTrigger) ? $(billingMethodSection + ' input#s2member-pro-paypal-checkout-card-number').focus () : null;
							}
						else if ((!billingMethod || billingMethod === 'PayPal') && taxMayApply)
							{
								if($(billingMethodSection).attr('data-paypal-only'))
									$(billingMethodSection).hide();
								else $(billingMethodSection).show ();

								$(billingAddressSection).show (); // Need this when tax may apply.

								$(billingMethodSection + ' > div.s2member-pro-paypal-checkout-form-div').show ();
								$(billingMethodSection + ' > div.s2member-pro-paypal-checkout-form-div :input').attr (ariaTrue);

								$(billingMethodSection + ' > div#s2member-pro-paypal-checkout-form-card-number-div').hide ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-checkout-form-card-number-div :input').attr (ariaFalse);

								$(billingMethodSection + ' > div#s2member-pro-paypal-checkout-form-card-expiration-div').hide ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-checkout-form-card-expiration-div :input').attr (ariaFalse);

								$(billingMethodSection + ' > div#s2member-pro-paypal-checkout-form-card-verification-div').hide ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-checkout-form-card-verification-div :input').attr (ariaFalse);

								$(billingMethodSection + ' > div#s2member-pro-paypal-checkout-form-card-start-date-issue-number-div').hide ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-checkout-form-card-start-date-issue-number-div :input').attr (ariaFalse);

								$(billingAddressSection + ' > div.s2member-pro-paypal-checkout-form-div').show ();
								$(billingAddressSection + ' > div.s2member-pro-paypal-checkout-form-div :input').attr (ariaTrue);

								$(billingAddressSection + ' > div#s2member-pro-paypal-checkout-form-street-div').hide ();
								$(billingAddressSection + ' > div#s2member-pro-paypal-checkout-form-street-div :input').attr (ariaFalse);

								$(billingAddressSection + ' > div#s2member-pro-paypal-checkout-form-city-div').hide ();
								$(billingAddressSection + ' > div#s2member-pro-paypal-checkout-form-city-div :input').attr (ariaFalse);

								$(submissionSection + ' #s2member-pro-paypal-checkout-submit').replaceWith (submitExpressCheckoutButton);

								(eventTrigger) ? $(billingAddressSection + ' input#s2member-pro-paypal-checkout-state').focus () : null;
							}
						else if (!billingMethod || billingMethod === 'PayPal')
							{
								if($(billingMethodSection).attr('data-paypal-only'))
									$(billingMethodSection).hide();
								else $(billingMethodSection).show ();

								$(billingAddressSection).hide (); // Don't need this either.

								$(billingMethodSection + ' > div.s2member-pro-paypal-checkout-form-div').hide ();
								$(billingMethodSection + ' > div.s2member-pro-paypal-checkout-form-div :input').attr (ariaFalse);

								$(billingMethodSection + ' > div#s2member-pro-paypal-checkout-form-card-type-div').show ();
								$(billingMethodSection + ' > div#s2member-pro-paypal-checkout-form-card-type-div :input').attr (ariaTrue);

								$(billingAddressSection + ' > div.s2member-pro-paypal-checkout-form-div').hide ();
								$(billingAddressSection + ' > div.s2member-pro-paypal-checkout-form-div :input').attr (ariaFalse);

								$(submissionSection + ' #s2member-pro-paypal-checkout-submit').replaceWith (submitExpressCheckoutButton);

								(eventTrigger) ? $(submissionSection + ' #s2member-pro-paypal-checkout-submit').focus () : null;
							}
					}) ();

				$(cardType).click (handleBillingMethod).change (handleBillingMethod);

				$(couponApplyButton).click ( /* Only applying coupon. */function()
					{
						$(submissionNonceVerification).val ('apply-coupon'), $coForm.submit ();
					});

				$(registrationSection + ' > div#s2member-pro-paypal-checkout-form-password-div :input').on('keyup initialize.s2', function()
					{
						ws_plugin__s2member_passwordStrength($(registrationSection + ' input#s2member-pro-paypal-checkout-username'), $(registrationSection + ' input#s2member-pro-paypal-checkout-password1'), $(registrationSection + ' input#s2member-pro-paypal-checkout-password2'), $(registrationSection + ' div#s2member-pro-paypal-checkout-form-password-strength'));
					}).trigger('initialize.s2');

				$coForm.submit ( /* Form validation. */function()
					{
						if ($.inArray($(submissionNonceVerification).val (), ['option', 'apply-coupon']) === -1)
							{
								var context = this, label = '', error = '', errors = '';

								var $recaptchaResponse = $(captchaSection + ' input#recaptcha_response_field, '+captchaSection+' #g-recaptcha-response');

								var $password1 = $(registrationSection + ' input#s2member-pro-paypal-checkout-password1[aria-required="true"]');
								var $password2 = $(registrationSection + ' input#s2member-pro-paypal-checkout-password2');

								if (!$(cardType + ':checked').val ())
									{
										$(cardType).val (['PayPal']);
									}

								$(':input', context).each ( /* Go through them all together. */function()
									{
										var id = /* Remove numeric suffixes. */ $.trim ($(this).attr ('id')).replace (/---[0-9]+$/g, '');

										if (id && (label = $.trim ($('label[for="' + id.replace(/-(month|year)/, '') + '"]', context).first ().children ('span').first ().text ().replace (/[\r\n\t]+/g, ' '))))
											{
												if (error = ws_plugin__s2member_validationErrors(label, this, context))
													errors += /* Collect errors. */ error + '\n\n';
											}
									});

								if (errors = $.trim (errors))
									{
										alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + errors);

										return false;
									}

								else if ($password1.length && $.trim ($password1.val ()) !== $.trim ($password2.val ()))
									{
										alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Passwords do not match up. Please try again.", "s2member-front", "s2member")); ?>');

										return false;
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

								else if ($recaptchaResponse.length && !$recaptchaResponse.val ())
									{
										alert('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("— Oops, you missed something: —", "s2member-front", "s2member")); ?>' + '\n\n' + '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (_x ("Security Verification missing. Please try again.", "s2member-front", "s2member")); ?>');

										return false;
									}
							}

						$(submissionButton).attr (disabled), ws_plugin__s2member_animateProcessing($(submissionButton)), $(couponApplyButton).attr (disabled);

						return true;
					});
			}

		(jumpToResponses = /* Jump to form responses. */ function()
			{
				$('div#s2member-pro-paypal-form-response').each (function()
					{
						var offset = $(this).offset ();
						window.scrollTo (0, offset.top - 100);
					});
			}) ();
	});
