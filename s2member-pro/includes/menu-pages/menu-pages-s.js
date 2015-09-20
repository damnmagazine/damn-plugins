/**
 * Core JavaScript routines for s2Member Pro menu pages.
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
 * @package s2Member\Menu_Pages
 * @since 1.5
 */
jQuery(document).ready(
	function($)
	{
		if(location.href.match(/page\=ws-plugin--s2member-pro-coupon-codes/))
		{
			var $menuTable = $('.ws-menu-page-table'),
				$couponsTable = $menuTable.find('.coupons-table'),
				newRow = '<tr>' +
				         '<td class="-code"><input type="text" spellcheck="false" value="" /></td>' +
				         '<td class="-discount"><input type="text" spellcheck="false" value="" /></td>' +
				         '<td class="-active_time"><input type="text" spellcheck="false" value="" /></td>' +
				         '<td class="-expires_time"><input type="text" spellcheck="false" value="" /></td>' +
				         '<td class="-directive"><input type="text" spellcheck="false" value="" /></td>' +
				         '<td class="-singulars"><input type="text" spellcheck="false" value="" /></td>' +
				         '<td class="-users"><input type="text" spellcheck="false" value="" /></td>' +
				         '<td class="-max_uses"><input type="text" spellcheck="false" value="" /></td>' +
				         '<td class="-uses"><input type="text" spellcheck="false" value="0" /></td>' +
				         '<td class="-actions"><a href="#" class="-up" title="Move Up" tabindex="-1"><i class="fa fa-chevron-circle-up"></i></a><a href="#" class="-down" title="Move Down" tabindex="-1"><i class="fa fa-chevron-circle-down"></i></a><a href="#" class="-delete" title="Delete" tabindex="-1"><i class="fa fa-times-circle"></i></a></td>' +
				         '</tr>';
			$couponsTable.find('tbody').on('click', 'a.-up,a.-down', function(e)
			{
				e.preventDefault(),
					e.stopImmediatePropagation();

				var $this = $(this), $thisTr = $this.closest('tr');

				if($this.is('.-up'))
					$thisTr.insertBefore($thisTr.prev());
				else $thisTr.insertAfter($thisTr.next());
			});
			$couponsTable.find('tbody').on('click', 'a.-delete', function(e)
			{
				e.preventDefault(),
					e.stopImmediatePropagation();

				var $this = $(this), $thisTr = $this.closest('tr');

				if(confirm('Delete? Are you sure?'))
					$thisTr.remove();
			});
			$couponsTable.find('tbody').on('keypress', 'input', function(e)
			{
				return e.which !== 13;
			});
			$menuTable.find('.coupon-add > a').on('click', function(e)
			{
				e.preventDefault(),
					e.stopImmediatePropagation();

				var $this = $(this);

				$couponsTable.find('tbody').append(newRow);
			});
			$menuTable.find('form').on('submit', function(e)
			{
				var $this = $(this), list = '';

				$couponsTable.find('tbody > tr').
					each(function(i, obj)
					     {
						     $(this).find('input').
							     each(function(i, obj)
							          {
								          if(i === 2)
									          list += $(obj).val() + '~';
								          else list += $(obj).val() + '|';
							          });
						     list += '\n';
					     });
				$('#ws-plugin--s2member-pro-coupon-codes').val(list);
			});
			if(!$couponsTable.find('tbody > tr').length)
				$couponsTable.find('tbody').append(newRow);
		}
	});