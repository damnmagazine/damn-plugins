/**
* Core JavaScript routines for ClickBank menu pages.
*
* Copyright: © 2009-2011
* {@link http://www.websharks-inc.com/ WebSharks, Inc.}
* (coded in the USA)
*
* This WordPress plugin (s2Member Pro) is comprised of two parts:
*
* o (1) Its PHP code is licensed under the GPL license, as is WordPress.
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
* @package s2Member\Menu_Pages
* @since 1.5
*/
jQuery(document).ready (function($)
	{
		var esc_attr = esc_html = function(string/* Convert special characters. */)
		{
			if(/[&\<\>"']/.test(string = String(string)))
				string = string.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'),
					string = string.replace(/"/g, '&quot;').replace(/'/g, '&#039;');
			return string;
		};
		if (location.href.match (/page\=ws-plugin--s2member-pro-clickbank-ops/))
			{
				$('select#ws-plugin--s2member-auto-eot-system-enabled').change (function()
					{
						var $this = $(this), val = $this.val ();
						var $viaCron = $('p#ws-plugin--s2member-auto-eot-system-enabled-via-cron');

						if /* Display Cron instructions. */ (val == 2)
							$viaCron.show ();
						else // Hide instructions.
							$viaCron.hide ();
					});
			}
		else if (location.href.match (/page\=ws-plugin--s2member-pro-clickbank-buttons/))
			{
				$('div.ws-menu-page select[id]').filter ( /* Filter all select elements with an id. */function()
					{
						return this.id.match (/^ws-plugin--s2member-pro-level[1-9][0-9]*-type$/);
					}).change (function()
					{
						var button = this.id.replace (/^ws-plugin--s2member-pro-(.+?)-type$/g, '$1');

						var termDisabled = ($(this).val () === 'recurring') ? true : false;
						var periodsDisabled = ($(this).val () === 'standard') ? true : false;

						$('p#ws-plugin--s2member-pro-' + button + '-term-line').css ('display', (termDisabled ? 'none' : ''));
						$('p#ws-plugin--s2member-pro-' + button + '-periods-line').css ('display', (periodsDisabled ? 'none' : ''));

						(termDisabled) ? $('select#ws-plugin--s2member-pro-' + button + '-term').val ('1-M') : null;
						(periodsDisabled) ? $('select#ws-plugin--s2member-pro-' + button + '-p1').val ('0-D') : null;
						(periodsDisabled) ? $('select#ws-plugin--s2member-pro-' + button + '-p3').val ('1-M') : null;

					}).trigger ('change');

				$('div.ws-menu-page input[id]').filter ( /* Filter all input elements with an id. */function()
					{
						return this.id.match (/^ws-plugin--s2member-pro-(level[1-9][0-9]*|ccap)-ccaps$/);
					}).keyup (function()
					{
						var value = this.value.replace (/^(-all|-al|-a|-)[;,]*/gi, ''), _all = (this.value.match (/^(-all|-al|-a|-)[;,]*/i)) ? '-all,' : '';
						if /* Only if there is a problem with the actual values; because this causes interruptions. */ (value.match (/[^a-z_0-9,]/))
							this.value = _all + $.trim ($.trim (value).replace (/[ \-]/g, '_').replace (/[^a-z_0-9,]/gi, '').toLowerCase ());
					});
				ws_plugin__s2member_pro_clickbankButtonGenerate = /* Handles ClickBank Button Generation. */ function(button)
					{
						var shortCodeTemplate = '[s2Member-Pro-ClickBank-Button %%attrs%% image="default" output="anchor" /]', shortCodeTemplateAttrs = '', labels = {};

						var vendor = '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_clickbank_username"]); ?>';

						eval("<?php echo c_ws_plugin__s2member_utils_strings::esc_dq($vars['labels']); ?>");

						var shortCode = $('input#ws-plugin--s2member-pro-' + button + '-shortcode');

						var level = /* Just strip the button name to get the Level number. */ button.replace (/^level/, '');
						var label = /* Labels may NOT contain any double-quotes. */ labels['level' + level].replace (/"/g, "");
						var desc = $.trim ($('input#ws-plugin--s2member-pro-' + button + '-desc').val ().replace (/"/g, "").replace (/&/g, "and"));

						var prodType = $('select#ws-plugin--s2member-pro-' + button + '-type').val ().replace (/[^A-Z]/gi, '').toLowerCase ();
						var prodItem = $('input#ws-plugin--s2member-pro-' + button + '-item-number').val ().replace (/[^A-Z0-9]/gi, '');

						if /* In this case, we used the Fixed-Term configuration. */ (prodType === 'standard')
							{
								var trialPeriod = '0', trialTerm = 'D'; // Just use the defaults in this case.
								var regPeriod = $('select#ws-plugin--s2member-pro-' + button + '-term').val ().split ('-')[0].replace (/[^0-9]/g, '');
								var regTerm = $('select#ws-plugin--s2member-pro-' + button + '-term').val ().split ('-')[1].replace (/[^A-Z]/g, '');
								var regRecur = '0'; // No, it is NOT recurring.
							}
						else if /* Here we use the ClickBank Trial Period and Re-Bill configuration. */ (prodType === 'recurring')
							{
								var trialPeriod = $('select#ws-plugin--s2member-pro-' + button + '-p1').val ().split ('-')[0].replace (/[^0-9]/g, '');
								var trialTerm = $('select#ws-plugin--s2member-pro-' + button + '-p1').val ().split ('-')[1].replace (/[^A-Z]/g, '');
								var regPeriod = $('select#ws-plugin--s2member-pro-' + button + '-p3').val ().split ('-')[0].replace (/[^0-9]/g, '');
								var regTerm = $('select#ws-plugin--s2member-pro-' + button + '-p3').val ().split ('-')[1].replace (/[^A-Z]/g, '');
								var regRecur = '1'; // Yes, it IS recurring.
							}
						var cCaps = $.trim ($.trim ($('input#ws-plugin--s2member-pro-' + button + '-ccaps').val ()).replace (/^(-all|-al|-a|-)[;,]*/gi, '').replace (/[ \-]/g, '_').replace (/[^a-z_0-9,]/gi, '').toLowerCase ());
						cCaps = ($.trim ($('input#ws-plugin--s2member-pro-' + button + '-ccaps').val ()).match (/^(-all|-al|-a|-)[;,]*/i)) ? ((cCaps) ? '-all,' : '-all') + cCaps.toLowerCase () : cCaps.toLowerCase ();

						var levelCcapsPer = (prodType === 'standard' && regTerm !== 'L') ? level + ':' + cCaps + ':' + regPeriod + ' ' + regTerm : level + ':' + cCaps;
						levelCcapsPer = /* Clean any trailing separators from this string. */ levelCcapsPer.replace (/\:+$/g, '');

						if /* Must have a Product Item Number to work with. */ (!prodItem)
							{
								alert('— Oops, a slight problem: —\n\nPlease supply a valid ClickBank Product Item #.');
								return false;
							}
						else if /* Each Button should have a Description. */ (!desc)
							{
								alert('— Oops, a slight problem: —\n\nPlease type a Description for this Button.');
								return false;
							}
						shortCodeTemplateAttrs += 'cbp="' + esc_attr(prodItem) + '" cbskin="" cbfid="" cbur="" cbf="auto" vtid="" level="' + esc_attr(level) + '" ccaps="' + esc_attr(cCaps) + '" desc="' + esc_attr(desc) + '" custom="<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (esc_attr ($_SERVER["HTTP_HOST"])); ?>"';
						shortCodeTemplateAttrs += ' tp="' + esc_attr(trialPeriod) + '" tt="' + esc_attr(trialTerm) + '" rp="' + esc_attr(regPeriod) + '" rt="' + esc_attr(regTerm) + '" rr="' + esc_attr(regRecur) + '"';
						shortCode.val (shortCodeTemplate.replace (/%%attrs%%/, shortCodeTemplateAttrs));

						alert('Your Button has been generated.\nPlease copy/paste the Shortcode into your Membership Options Page.');

						shortCode.each ( /* Focus and select the Shortcode. */function()
							{
								this.focus (), this.select ();
							});
						return false;
					};
				ws_plugin__s2member_pro_clickbankCcapButtonGenerate = /* Handles ClickBank Button Generation. */ function()
					{
						var shortCodeTemplate = '[s2Member-Pro-ClickBank-Button %%attrs%% image="default" output="anchor" /]', shortCodeTemplateAttrs = '';

						var vendor = '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_clickbank_username"]); ?>';

						var shortCode = $('input#ws-plugin--s2member-pro-ccap-shortcode');

						var desc = $.trim ($('input#ws-plugin--s2member-pro-ccap-desc').val ().replace (/"/g, "").replace (/&/g, "and"));

						var prodType = $('select#ws-plugin--s2member-pro-ccap-type').val ().replace (/[^A-Z]/gi, '').toLowerCase ();
						var prodItem = $('input#ws-plugin--s2member-pro-ccap-item-number').val ().replace (/[^A-Z0-9]/gi, '');

						if /* In this case, we used the Fixed-Term configuration. */ (prodType === 'standard')
							{
								var /* Just use the defaults in this case. */ trialPeriod = '0', trialTerm = 'D';
								var regPeriod = $('select#ws-plugin--s2member-pro-ccap-term').val ().split ('-')[0].replace (/[^0-9]/g, '');
								var regTerm = $('select#ws-plugin--s2member-pro-ccap-term').val ().split ('-')[1].replace (/[^A-Z]/g, '');
								var regRecur = /* No, it is NOT recurring. */ '0';
							}
						var cCaps = $.trim ($.trim ($('input#ws-plugin--s2member-pro-ccap-ccaps').val ()).replace (/^(-all|-al|-a|-)[;,]*/gi, '').replace (/[ \-]/g, '_').replace (/[^a-z_0-9,]/gi, '').toLowerCase ());
						cCaps = ($.trim ($('input#ws-plugin--s2member-pro-ccap-ccaps').val ()).match (/^(-all|-al|-a|-)[;,]*/i)) ? ((cCaps) ? '-all,' : '-all') + cCaps.toLowerCase () : cCaps.toLowerCase ();

						var levelCcapsPer = (prodType === 'standard' && regTerm !== 'L') ? '*:' + cCaps + ':' + regPeriod + ' ' + regTerm : '*:' + cCaps;
						levelCcapsPer = /* Clean any trailing separators from this string. */ levelCcapsPer.replace (/\:+$/g, '');

						if /* Must have a Product Item Number to work with. */ (!prodItem)
							{
								alert('— Oops, a slight problem: —\n\nPlease supply a valid ClickBank Product Item #.');
								return false;
							}
						else if /* Must have some Independent Custom Capabilities. */ (!cCaps || cCaps === '-all')
							{
								alert('— Oops, a slight problem: —\n\nPlease provide at least one Custom Capability.');
								return false;
							}
						else if /* Each Button should have a Description. */ (!desc)
							{
								alert('— Oops, a slight problem: —\n\nPlease type a Description for this Button.');
								return false;
							}
						shortCodeTemplateAttrs += 'cbp="' + esc_attr(prodItem) + '" cbskin="" cbfid="" cbur="" cbf="auto" vtid="" level="*" ccaps="' + esc_attr(cCaps) + '" desc="' + esc_attr(desc) + '" custom="<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (esc_attr ($_SERVER["HTTP_HOST"])); ?>"';
						shortCodeTemplateAttrs += ' rp="' + esc_attr(regPeriod) + '" rt="' + esc_attr(regTerm) + '" rr="' + esc_attr(regRecur) + '"';
						shortCode.val (shortCodeTemplate.replace (/%%attrs%%/, shortCodeTemplateAttrs));

						alert('Your Button has been generated.\nPlease copy/paste the Shortcode into your WordPress Editor.');

						shortCode.each ( /* Focus and select the Shortcode. */function()
							{
								this.focus (), this.select ();
							});
						return false;
					};
				ws_plugin__s2member_pro_clickbankSpButtonGenerate = /* Handles ClickBank Button Generation for Specific Post/Page Access. */ function()
					{
						var shortCodeTemplate = '[s2Member-Pro-ClickBank-Button %%attrs%% image="default" output="anchor" /]', shortCodeTemplateAttrs = '';

						var vendor = '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_clickbank_username"]); ?>';

						var shortCode = $('input#ws-plugin--s2member-pro-sp-shortcode');

						var prodItem = $('input#ws-plugin--s2member-pro-sp-item-number').val ().replace (/[^A-Z0-9]/gi, '');

						var leading = $('select#ws-plugin--s2member-pro-sp-leading-id').val ().replace (/[^0-9]/g, '');
						var additionals = $('select#ws-plugin--s2member-pro-sp-additional-ids').val () || [];
						var hours = $('select#ws-plugin--s2member-pro-sp-hours').val ().replace (/[^0-9]/g, '');
						var desc = $.trim ($('input#ws-plugin--s2member-pro-sp-desc').val ().replace (/"/g, '').replace (/&/g, "and"));

						if /* Must have a Product Item Number to work with. Otherwise, Button generation will fail. */ (!prodItem)
							{
								alert('— Oops, a slight problem: —\n\nPlease supply a valid ClickBank Product Item #.');
								return false;
							}
						else if /* Must have a Leading Post/Page ID to work with. Otherwise, Link generation will fail. */ (!leading)
							{
								alert('— Oops, a slight problem: —\n\nPlease select a Leading Post/Page.\n\n*Tip* If there are no Posts/Pages in the menu, it\'s because you\'ve not configured s2Member for Specific Post/Page Access yet. See: s2Member → Restriction Options → Specific Post/Page Access.');
								return false;
							}
						else if /* Each Button should have a Description. */ (!desc)
							{
								alert('— Oops, a slight problem: —\n\nPlease type a Description for this Button.');
								return false;
							}

						for (var i = 0, ids = leading; i < additionals.length; i++)
							if (additionals[i] && additionals[i] !== leading)
								ids += ',' + additionals[i];

						var spIdsHours = /* Combined sp:ids:expiration hours. */ 'sp:' + ids + ':' + hours;

						shortCodeTemplateAttrs += 'cbp="' + esc_attr(prodItem) + '" cbskin="" cbfid="" cbur="" cbf="auto" vtid="" sp="1" ids="' + esc_attr(ids) + '" exp="' + esc_attr(hours) + '" desc="' + esc_attr(desc) + '"';
						shortCodeTemplateAttrs += ' custom="<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (esc_attr ($_SERVER["HTTP_HOST"])); ?>"';
						shortCode.val (shortCodeTemplate.replace (/%%attrs%%/, shortCodeTemplateAttrs));

						alert('Your Button has been generated.\nPlease copy/paste the Shortcode into your WordPress Editor.');

						shortCode.each ( /* Focus and select the Shortcode. */function()
							{
								this.focus (), this.select ();
							});

						return false;
					};
				ws_plugin__s2member_pro_clickbankRegLinkGenerate = /* Handles ClickBank Link Generation. */ function()
					{
						var level = $('select#ws-plugin--s2member-pro-reg-link-level').val ().replace (/[^0-9]/g, '');
						var subscrID = $.trim ($('input#ws-plugin--s2member-pro-reg-link-subscr-id').val ());
						var custom = $.trim ($('input#ws-plugin--s2member-pro-reg-link-custom').val ());
						var cCaps = $.trim ($.trim ($('input#ws-plugin--s2member-pro-reg-link-ccaps').val ()).replace (/[ \-]/g, '_').replace (/[^a-z_0-9,]/gi, '').toLowerCase ());
						var fixedTerm = $.trim ($('input#ws-plugin--s2member-pro-reg-link-fixed-term').val ().replace (/[^A-Z 0-9]/gi, '').toUpperCase ());
						var $link = $('p#ws-plugin--s2member-pro-reg-link'), $loading = $('img#ws-plugin--s2member-pro-reg-link-loading');

						var levelCcapsPer = (fixedTerm && !fixedTerm.match (/L$/)) ? level + ':' + cCaps + ':' + fixedTerm : level + ':' + cCaps;
						levelCcapsPer = /* Clean any trailing separators from this string. */ levelCcapsPer.replace (/\:+$/g, '');

						if /* We must have a Paid Subscr. ID value. */ (!subscrID)
							{
								alert('— Oops, a slight problem: —\n\nPaid Subscr. ID is a required value.');
								return false;
							}
						else if (!custom || custom.indexOf ('<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq ($_SERVER["HTTP_HOST"]); ?>') !== 0)
							{
								alert('— Oops, a slight problem: —\n\nThe Custom Value MUST start with your domain name.');
								return false;
							}
						else if /* Check format. */ (fixedTerm && !fixedTerm.match (/^[1-9]+ (D|W|M|Y|L)$/))
							{
								alert('— Oops, a slight problem: —\n\nThe Fixed Term Length is not formatted properly.');
								return false;
							}

						$link.hide (), $loading.show (), $.post (ajaxurl, {action: 'ws_plugin__s2member_reg_access_link_via_ajax', ws_plugin__s2member_reg_access_link_via_ajax: '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (wp_create_nonce ("ws-plugin--s2member-reg-access-link-via-ajax")); ?>', s2member_reg_access_link_subscr_gateway: 'clickbank', s2member_reg_access_link_subscr_id: subscrID, s2member_reg_access_link_custom: custom, s2member_reg_access_link_item_number: levelCcapsPer}, function(response)
							{
								$link.show ().html ('<a href="' + esc_attr(response) + '" target="_blank" rel="external">' + esc_html(response) + '</a>'), $loading.hide ();
							});
						return false;
					};
				ws_plugin__s2member_pro_clickbankSpLinkGenerate = /* Handles ClickBank Link Generation. */ function()
					{
						var leading = $('select#ws-plugin--s2member-pro-sp-link-leading-id').val ().replace (/[^0-9]/g, '');
						var additionals = $('select#ws-plugin--s2member-pro-sp-link-additional-ids').val () || [];
						var hours = $('select#ws-plugin--s2member-pro-sp-link-hours').val ().replace (/[^0-9]/g, '');
						var $link = $('p#ws-plugin--s2member-pro-sp-link'), $loading = $('img#ws-plugin--s2member-pro-sp-link-loading');

						if /* Must have a Leading Post/Page ID to work with. Otherwise, Link generation will fail. */ (!leading)
							{
								alert('— Oops, a slight problem: —\n\nPlease select a Leading Post/Page.\n\n*Tip* If there are no Posts/Pages in the menu, it\'s because you\'ve not configured s2Member for Specific Post/Page Access yet. See: s2Member → Restriction Options → Specific Post/Page Access.');
								return false;
							}
						for (var i = 0, ids = leading; i < additionals.length; i++)
							if (additionals[i] && additionals[i] !== leading)
								ids += ',' + additionals[i];

						$link.hide (), $loading.show (), $.post (ajaxurl, {action: 'ws_plugin__s2member_sp_access_link_via_ajax', ws_plugin__s2member_sp_access_link_via_ajax: '<?php echo c_ws_plugin__s2member_utils_strings::esc_js_sq (wp_create_nonce ("ws-plugin--s2member-sp-access-link-via-ajax")); ?>', s2member_sp_access_link_ids: ids, s2member_sp_access_link_hours: hours}, function(response)
							{
								$link.show ().html ('<a href="' + esc_attr(response) + '" target="_blank" rel="external">' + esc_html(response) + '</a>'), $loading.hide ();
							});
						return false;
					};
			}
	});