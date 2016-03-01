/**
 * Core JavaScript routines for Google.
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
 * @package s2Member\Google
 * @since 1.5
 */
jQuery(document).ready(function($){});
var s2member_pro_google_wallet_checkout = function(btn, vars)
	{
		var $ = jQuery, $btn = $(btn), email = '';
		$btn.css({opacity: 0.5}).attr('disabled', 'disabled');

		if(!(email = S2MEMBER_CURRENT_USER_EMAIL))
			if(!(email = prompt('<?php echo _x("Email Address", "s2member-front", "s2member"); ?>', ''))) return;

		$.post('<?php echo add_query_arg("s2member_pro_google_jwt", "1", home_url("/")); ?>',
		       {'s2member_pro_google_jwt_vars': {email: email, attr: vars.jwt_attr}}, function(data)
			{
				google.payments.inapp.buy({jwt      : data,
					                          success: function(r)
						                          {
							                          if(vars.success)
								                          location.href = vars.success;
							                          else alert('<?php echo _x("Thank you! Please check your email for further details.", "s2member-front", "s2member"); ?>');
						                          },
					                          failure: function(r)
						                          {
							                          if(vars.failure)
								                          location.href = vars.failure;
						                          }});
				$btn.css({opacity: 1}).removeAttr('disabled');
			});
	};