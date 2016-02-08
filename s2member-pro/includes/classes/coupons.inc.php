<?php
/**
 * Coupon Codes.
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
 * @package s2Member\Coupons
 * @since 150122
 */
if(!defined('WPINC')) // MUST have WordPress.
	exit ('Do not access this file directly.');

if(!class_exists('c_ws_plugin__s2member_pro_coupons'))
{
	/**
	 * Coupon Codes.
	 *
	 * @package s2Member\Coupons
	 * @since 150122
	 */
	class c_ws_plugin__s2member_pro_coupons
	{
		public $list = '';

		public $coupons = array();

		public function __construct($args = array())
		{
			$default_args = array('update' => TRUE); // Defaults.
			$args         = array_merge($default_args, (array)$args);
			$args         = array_intersect_key($args, $default_args);

			$this->list_to_coupons($GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_coupon_codes'], $args['update']);
		}

		public function list_to_coupons($list, $update = TRUE)
		{
			$list    = trim((string)$list);
			$coupons = array(); // Initialize.

			foreach(array_map('trim', preg_split('/['."\r\n".']+/', $list, NULL, PREG_SPLIT_NO_EMPTY)) as $_line)
			{
				if(!($_line = trim($_line, " \r\n\t\0\x0B|")))
					continue; // Empty line; continue.

				if(strpos($_line, '#') === 0)
					continue; // Comment line.

				$_coupon_parts = $_coupon = array(); // Initialize.
				$_coupon_parts = array_map('trim', preg_split('/\|/', $_line));

				if(!($_coupon['code'] = !empty($_coupon_parts[0]) ? $this->n_code($_coupon_parts[0]) : ''))
					continue; // Not applicable; no coupon code after sanitizing.

				$_coupon['discount']            = !empty($_coupon_parts[1]) ? $_coupon_parts[1] : '';
				$_coupon['percentage_discount'] = $_coupon['discount'] && preg_match('/%/', $_coupon['discount']) ? (float)$_coupon['discount'] : (float)0;
				$_coupon['flat_discount']       = $_coupon['discount'] && !preg_match('/%/', $_coupon['discount']) ? (float)$_coupon['discount'] : (float)0;

				$_active_time           = $_expires_time = '';
				$_coupon['active_time'] = $_coupon['expires_time'] = 0;
				if(($_coupon['dates'] = !empty($_coupon_parts[2]) ? $_coupon_parts[2] : ''))
				{
					if(strpos($_coupon['dates'], '~') !== FALSE)
						list($_active_time, $_expires_time) = array_map('trim', explode('~', $_coupon['dates'], 2));
					else $_expires_time = $_coupon['dates']; // Back compat.

					if($_active_time && ($_active_time = strtotime($_active_time)))
						$_coupon['active_time'] = (integer)$_active_time;

					if($_expires_time && ($_expires_time = strtotime($_expires_time)))
						{
							$_coupon['expires_time'] = (integer)$_expires_time;
							if(date('H:i:s', $_coupon['expires_time']) === '00:00:00')
								$_coupon['expires_time'] += 86399; // End of the day.
						}
				}
				unset($_active_time, $_expires_time); // Housekeeping.

				$_coupon['directive'] = !empty($_coupon_parts[3]) && strtolower($_coupon_parts[3]) !== 'all' ? preg_replace('/_/', '-', strtolower($_coupon_parts[3])) : '';
				$_coupon['directive'] = preg_match('/^(?:ta\-only|ra\-only)$/', $_coupon['directive']) ? $_coupon['directive'] : '';

				$_coupon['singulars'] = !empty($_coupon_parts[4]) && strtolower($_coupon_parts[4]) !== 'all' ? $_coupon_parts[4] : '';
				$_coupon['singulars'] = $_coupon['singulars'] ? array_map('intval', preg_split('/,+/', preg_replace('/[^0-9,]/', '', $_coupon['singulars']), NULL, PREG_SPLIT_NO_EMPTY)) : array();

				$_coupon['users'] = !empty($_coupon_parts[5]) && strtolower($_coupon_parts[5]) !== 'all' ? $_coupon_parts[5] : '';
				$_coupon['users'] = $_coupon['users'] ? array_map('intval', preg_split('/,+/', preg_replace('/[^0-9,]/', '', $_coupon['users']), NULL, PREG_SPLIT_NO_EMPTY)) : array();

				$_coupon['max_uses'] = !empty($_coupon_parts[6]) ? (integer)$_coupon_parts[6] : 0;

				if($update && strpos((string)$update, 'counters') !== FALSE && isset($_coupon_parts[7]))
					$this->update_uses($_coupon['code'], $_coupon_parts[7]);

				$_coupon['is_gift'] = FALSE; // Hard-coded coupons are never gifts.

				$coupons[$_coupon['code']] = $_coupon; // Add this coupon to the array now.
			}
			unset($_line, $_coupon_parts, $_coupon); // Housekeeping.

			if($update) // Update class properties?
			{
				$this->coupons = $coupons;
				$this->list    = $this->coupons_to_list($coupons, FALSE);
			}
			return $coupons;
		}

		public function coupons_to_list($coupons, $update = TRUE)
		{
			$list    = ''; // Initialize.
			$coupons = (array)$coupons;

			foreach($coupons as $_coupon)
			{
				if(!$_coupon || !is_array($_coupon))
					continue; // Not applicable.

				if(empty($_coupon['code']) || !($_coupon['code'] = trim((string)$_coupon['code'])))
					continue; // Not applicable; empty coupon code.

				# The coupon code itself.

				$list .= str_replace('|', '', $_coupon['code']).'|';

				# Discount amount; or percentage/flat rate.

				if(isset($_coupon['discount']))
					$list .= str_replace('|', '', trim((string)$_coupon['discount'])).'|';

				else if(isset($_coupon['percentage_discount']))
					$list .= str_replace('|', '', trim((string)$_coupon['percentage_discount'])).'%|';

				else if(isset($_coupon['flat_discount']))
					$list .= str_replace('|', '', trim((string)$_coupon['flat_discount'])).'|';

				else $list .= '|'; // Unspecified in this case.

				# Dates; i.e., `dates` or individual times.

				if(isset($_coupon['dates']))
					$list .= str_replace('|', '', trim((string)$_coupon['dates'])).'|';

				else if(isset($_coupon['active_time']) || isset($_coupon['expires_time']))
					$list .= str_replace('|', '', (isset($_coupon['active_time']) && (integer)$_coupon['active_time'] ? date('Y/m/d', (integer)$_coupon['active_time']) : '').
					                              '~'.(isset($_coupon['expires_time']) && (integer)$_coupon['expires_time'] ? date('Y/m/d', (integer)$_coupon['expires_time']) : '')).'|';

				else $list .= '|'; // Unspecified in this case.

				# Coupon directive; i.e., how does it apply.

				if(isset($_coupon['directive']))
					$list .= str_replace('|', '', trim((string)$_coupon['directive'])).'|';

				else $list .= '|'; // Unspecified in this case.

				# Coupon singulars; i.e., particular post IDs where it's applicable.

				if(isset($_coupon['singulars']) && is_array($_coupon['singulars']))
					$list .= str_replace('|', '', implode(',', $_coupon['singulars'])).'|';

				else if(isset($_coupon['singulars']))
					$list .= str_replace('|', '', trim((string)$_coupon['singulars'])).'|';

				else $list .= '|'; // Unspecified in this case.

				# Coupon users; i.e., particular user IDs where it's applicable.

				if(isset($_coupon['users']) && is_array($_coupon['users']))
					$list .= str_replace('|', '', implode(',', $_coupon['users'])).'|';

				else if(isset($_coupon['users']))
					$list .= str_replace('|', '', trim((string)$_coupon['users'])).'|';

				else $list .= '|'; // Unspecified in this case.

				# Coupon users; i.e., particular user IDs where it's applicable.

				if(isset($_coupon['max_uses']))
					$list .= str_replace('|', '', trim((string)$_coupon['max_uses'])).'|';

				else $list .= '|'; // Unspecified in this case.

				# Line ending; always.

				// `code|discount|dates|directive|singulars|users|max uses`.

				$list .= "\n"; // One coupon per line.
			}
			unset($_coupon); // Housekeeping.

			$list = trim($list); // Trim it up now.

			if($update) // Update class properties?
			{
				$this->list    = $list; // Update.
				$this->coupons = $this->list_to_coupons($list, FALSE);
			}
			return $list;
		}

		public function apply($attr, $coupon_code = '', $return = 'attr', $process = array())
		{
			$attr        = (array)$attr; // Force an array value.
			$process     = (array)$process; // Force an array value.
			$coupon_code = $coupon_code ? $coupon_code : $attr['coupon'];
			$coupon_code = trim((string)$coupon_code); // Force string value.

			if($coupon_code) // Only if we do have a coupon code.
				if(($coupon = $this->valid_coupon($coupon_code, $attr)))
				{
					$coupon_applies = FALSE;
					$ta             = $attr['ta'];
					$ra             = $attr['ra'];
					$desc           = $attr['desc'];

					$cs = c_ws_plugin__s2member_utils_cur::symbol($attr['cc']);
					$tx = c_ws_plugin__s2member_pro_taxes::may_apply() ? ' '._x('+ tax', 's2member-front', 's2member') : '';
					$ps = _x('%', 's2member-front percentage-symbol', 's2member');

					$full_coupon_code = $coupon_code; // Initialize.
					$affiliate_id     = ''; // Initialize; this starts as empty.
					if(strlen($_affiliate_suffix_chars = $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_affiliate_coupon_code_suffix_chars']))
						if(preg_match('/^(.+?)'.preg_quote($_affiliate_suffix_chars, '/').'([0-9]+)$/i', $coupon_code, $_m))
							list($full_coupon_code, $coupon_code, $affiliate_id) = $_m;
					unset($_affiliate_suffix_chars, $_m); // Housekeeping.

					if($coupon['flat_discount']) // If it's a flat-rate coupon.
					{
						if($attr['sp'] && (!$coupon['directive'] || $coupon['directive'] === 'ra-only'))
						{
							$coupon_applies = TRUE;

							$ta = number_format($attr['ta'], 2, '.', '');
							$ta = $ta >= 0.00 ? $ta : '0.00';

							$ra = number_format($attr['ra'] - $coupon['flat_discount'], 2, '.', '');
							$ra = $ra >= 0.00 ? $ra : '0.00';

							$desc     = sprintf(_x('Discount: %s off. (Now: %s)', 's2member-front', 's2member'), $cs.number_format($coupon['flat_discount'], 2, '.', ''), $cs.$ra.$tx);
							$response = sprintf(_x('<div>Discount: <strong>%s off</strong>. (Now: <strong>%s</strong>)</div>', 's2member-front', 's2member'), $cs.number_format($coupon['flat_discount'], 2, '.', ''), $cs.$ra.$tx);
						}
						else if(!$attr['sp'] && $attr['tp'] && $coupon['directive'] === 'ta-only')
						{
							$coupon_applies = TRUE;

							$ta = number_format($attr['ta'] - $coupon['flat_discount'], 2, '.', '');
							$ta = $ta >= 0.00 ? $ta : '0.00';

							$ra = number_format($attr['ra'], 2, '.', '');
							$ra = $ra >= 0.00 ? $ra : '0.00';

							$desc     = sprintf(_x('Discount: %s off. (Now: %s, then %s)', 's2member-front', 's2member'), $cs.number_format($coupon['flat_discount'], 2, '.', ''), $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ta, $attr['tp'].' '.$attr['tt']).$tx, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']));
							$response = sprintf(_x('<div>Discount: <strong>%s off</strong>. (Now: <strong>%s, then %s</strong>)</div>', 's2member-front', 's2member'), $cs.number_format($coupon['flat_discount'], 2, '.', ''), $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ta, $attr['tp'].' '.$attr['tt']).$tx, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']));
						}
						else if(!$attr['sp'] && $attr['tp'] && $coupon['directive'] === 'ra-only')
						{
							$coupon_applies = TRUE;

							$ta = number_format($attr['ta'], 2, '.', '');
							$ta = $ta >= 0.00 ? $ta : '0.00';

							$ra = number_format($attr['ra'] - $coupon['flat_discount'], 2, '.', '');
							$ra = $ra >= 0.00 ? $ra : '0.00';

							$desc     = sprintf(_x('Discount: %s off. (Now: %s, then %s)', 's2member-front', 's2member'), $cs.number_format($coupon['flat_discount'], 2, '.', ''), $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ta, $attr['tp'].' '.$attr['tt']).$tx, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']));
							$response = sprintf(_x('<div>Discount: <strong>%s off</strong>. (Now: <strong>%s, then %s</strong>)</div>', 's2member-front', 's2member'), $cs.number_format($coupon['flat_discount'], 2, '.', ''), $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ta, $attr['tp'].' '.$attr['tt']).$tx, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']));
						}
						else if(!$attr['sp'] && $attr['tp'] && !$coupon['directive'])
						{
							$coupon_applies = TRUE;

							$ta = number_format($attr['ta'] - $coupon['flat_discount'], 2, '.', '');
							$ta = $ta >= 0.00 ? $ta : '0.00';

							$ra = number_format($attr['ra'] - $coupon['flat_discount'], 2, '.', '');
							$ra = $ra >= 0.00 ? $ra : '0.00';

							$desc     = sprintf(_x('Discount: %s off. (Now: %s, then %s)', 's2member-front', 's2member'), $cs.number_format($coupon['flat_discount'], 2, '.', ''), $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ta, $attr['tp'].' '.$attr['tt']).$tx, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']));
							$response = sprintf(_x('<div>Discount: <strong>%s off</strong>. (Now: <strong>%s, then %s</strong>)</div>', 's2member-front', 's2member'), $cs.number_format($coupon['flat_discount'], 2, '.', ''), $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ta, $attr['tp'].' '.$attr['tt']).$tx, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']));
						}
						else if(!$attr['sp'] && !$attr['tp'] && $coupon['directive'] === 'ra-only')
						{
							$coupon_applies = TRUE;

							$ta = number_format($attr['ta'], 2, '.', '');
							$ta = $ta >= 0.00 ? $ta : '0.00';

							$ra = number_format($attr['ra'] - $coupon['flat_discount'], 2, '.', '');
							$ra = $ra >= 0.00 ? $ra : '0.00';

							$desc     = sprintf(_x('Discount: %s off. (Now: %s)', 's2member-front', 's2member'), $cs.number_format($coupon['flat_discount'], 2, '.', ''), $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']).$tx);
							$response = sprintf(_x('<div>Discount: <strong>%s off</strong>. (Now: <strong>%s</strong>)</div>', 's2member-front', 's2member'), $cs.number_format($coupon['flat_discount'], 2, '.', ''), $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']).$tx);
						}
						else if(!$attr['sp'] && !$attr['tp'] && !$coupon['directive'])
						{
							$coupon_applies = TRUE;

							$ta = number_format($attr['ta'] - $coupon['flat_discount'], 2, '.', '');
							$ta = $ta >= 0.00 ? $ta : '0.00';

							$ra = number_format($attr['ra'] - $coupon['flat_discount'], 2, '.', '');
							$ra = $ra >= 0.00 ? $ra : '0.00';

							$desc     = sprintf(_x('Discount: %s off. (Now: %s)', 's2member-front', 's2member'), $cs.number_format($coupon['flat_discount'], 2, '.', ''), $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']).$tx);
							$response = sprintf(_x('<div>Discount: <strong>%s off</strong>. (Now: <strong>%s</strong>)</div>', 's2member-front', 's2member'), $cs.number_format($coupon['flat_discount'], 2, '.', ''), $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']).$tx);
						}
						else // Otherwise, we need a default response to display.
							$response = _x('<div>Sorry, your discount code is not applicable.</div>', 's2member-front', 's2member');
					}
					else if($coupon['percentage_discount']) // Else if it's a percentage.
					{
						if($attr['sp'] && (!$coupon['directive'] || $coupon['directive'] === 'ra-only'))
						{
							$coupon_applies = TRUE;

							$p  = ($attr['ta'] / 100) * $coupon['percentage_discount'];
							$ta = number_format($attr['ta'], 2, '.', '');
							$ta = $ta >= 0.00 ? $ta : '0.00';

							$p  = ($attr['ra'] / 100) * $coupon['percentage_discount'];
							$ra = number_format($attr['ra'] - $p, 2, '.', '');
							$ra = $ra >= 0.00 ? $ra : '0.00';

							$desc     = sprintf(_x('Discount: %s off. (Now: %s)', 's2member-front', 's2member'), number_format($coupon['percentage_discount'], 0).$ps, $cs.$ra.$tx);
							$response = sprintf(_x('<div>Discount: <strong>%s off</strong>. (Now: <strong>%s</strong>)</div>', 's2member-front', 's2member'), number_format($coupon['percentage_discount'], 0).$ps, $cs.$ra.$tx);
						}
						else if(!$attr['sp'] && $attr['tp'] && $coupon['directive'] === 'ta-only')
						{
							$coupon_applies = TRUE;

							$p  = ($attr['ta'] / 100) * $coupon['percentage_discount'];
							$ta = number_format($attr['ta'] - $p, 2, '.', '');
							$ta = $ta >= 0.00 ? $ta : '0.00';

							$p  = ($attr['ra'] / 100) * $coupon['percentage_discount'];
							$ra = number_format($attr['ra'], 2, '.', '');
							$ra = $ra >= 0.00 ? $ra : '0.00';

							$desc     = sprintf(_x('Discount: %s off. (Now: %s, then %s)', 's2member-front', 's2member'), number_format($coupon['percentage_discount'], 0).$ps, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ta, $attr['tp'].' '.$attr['tt']).$tx, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']));
							$response = sprintf(_x('<div>Discount: <strong>%s off</strong>. (Now: <strong>%s, then %s</strong>)</div>', 's2member-front', 's2member'), number_format($coupon['percentage_discount'], 0).$ps, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ta, $attr['tp'].' '.$attr['tt']).$tx, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']));
						}
						else if(!$attr['sp'] && $attr['tp'] && $coupon['directive'] === 'ra-only')
						{
							$coupon_applies = TRUE;

							$p  = ($attr['ta'] / 100) * $coupon['percentage_discount'];
							$ta = number_format($attr['ta'], 2, '.', '');
							$ta = $ta >= 0.00 ? $ta : '0.00';

							$p  = ($attr['ra'] / 100) * $coupon['percentage_discount'];
							$ra = number_format($attr['ra'] - $p, 2, '.', '');
							$ra = $ra >= 0.00 ? $ra : '0.00';

							$desc     = sprintf(_x('Discount: %s off. (Now: %s, then %s)', 's2member-front', 's2member'), number_format($coupon['percentage_discount'], 0).$ps, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ta, $attr['tp'].' '.$attr['tt']).$tx, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']));
							$response = sprintf(_x('<div>Discount: <strong>%s off</strong>. (Now: <strong>%s, then %s</strong>)</div>', 's2member-front', 's2member'), number_format($coupon['percentage_discount'], 0).$ps, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ta, $attr['tp'].' '.$attr['tt']).$tx, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']));
						}
						else if(!$attr['sp'] && $attr['tp'] && !$coupon['directive'])
						{
							$coupon_applies = TRUE;

							$p  = ($attr['ta'] / 100) * $coupon['percentage_discount'];
							$ta = number_format($attr['ta'] - $p, 2, '.', '');
							$ta = $ta >= 0.00 ? $ta : '0.00';

							$p  = ($attr['ra'] / 100) * $coupon['percentage_discount'];
							$ra = number_format($attr['ra'] - $p, 2, '.', '');
							$ra = $ra >= 0.00 ? $ra : '0.00';

							$desc     = sprintf(_x('Discount: %s off. (Now: %s, then %s)', 's2member-front', 's2member'), number_format($coupon['percentage_discount'], 0).$ps, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ta, $attr['tp'].' '.$attr['tt']).$tx, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']));
							$response = sprintf(_x('<div>Discount: <strong>%s off</strong>. (Now: <strong>%s, then %s</strong>)</div>', 's2member-front', 's2member'), number_format($coupon['percentage_discount'], 0).$ps, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ta, $attr['tp'].' '.$attr['tt']).$tx, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']));
						}
						else if(!$attr['sp'] && !$attr['tp'] && $coupon['directive'] === 'ra-only')
						{
							$coupon_applies = TRUE;

							$p  = ($attr['ta'] / 100) * $coupon['percentage_discount'];
							$ta = number_format($attr['ta'], 2, '.', '');
							$ta = $ta >= 0.00 ? $ta : '0.00';

							$p  = ($attr['ra'] / 100) * $coupon['percentage_discount'];
							$ra = number_format($attr['ra'] - $p, 2, '.', '');
							$ra = $ra >= 0.00 ? $ra : '0.00';

							$desc     = sprintf(_x('Discount: %s off. (Now: %s)', 's2member-front', 's2member'), number_format($coupon['percentage_discount'], 0).$ps, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']).$tx);
							$response = sprintf(_x('<div>Discount: <strong>%s off</strong>. (Now: <strong>%s</strong>)</div>', 's2member-front', 's2member'), number_format($coupon['percentage_discount'], 0).$ps, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']).$tx);
						}
						else if(!$attr['sp'] && !$attr['tp'] && !$coupon['directive'])
						{
							$coupon_applies = TRUE;

							$p  = ($attr['ta'] / 100) * $coupon['percentage_discount'];
							$ta = number_format($attr['ta'] - $p, 2, '.', '');
							$ta = $ta >= 0.00 ? $ta : '0.00';

							$p  = ($attr['ra'] / 100) * $coupon['percentage_discount'];
							$ra = number_format($attr['ra'] - $p, 2, '.', '');
							$ra = $ra >= 0.00 ? $ra : '0.00';

							$desc     = sprintf(_x('Discount: %s off. (Now: %s)', 's2member-front', 's2member'), number_format($coupon['percentage_discount'], 0).$ps, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']).$tx);
							$response = sprintf(_x('<div>Discount: <strong>%s off</strong>. (Now: <strong>%s</strong>)</div>', 's2member-front', 's2member'), number_format($coupon['percentage_discount'], 0).$ps, $cs.c_ws_plugin__s2member_utils_time::amount_period_term($ra, $attr['rp'].' '.$attr['rt'], $attr['rr']).$tx);
						}
						else // Otherwise, we need a default response to display.
							$response = _x('<div>Sorry, your discount code is not applicable.</div>', 's2member-front', 's2member');
					}
					else // Else there was no discount applied at all.
						$response = sprintf(_x('<div>Discount: <strong>%s0.00 off</strong>.</div>', 's2member-front', 's2member'), $cs);

					if($coupon_applies) // Apply the coupon here; if applicable.
					{
						$attr['ta']   = $ta < 0.50 ? '0.00' : $ta; // Apply new amount for the initial/period.
						$attr['ra']   = $ra < 0.50 ? '0.00' : $ra; // Apply new amount for the regular/recurring period.
						$attr['desc'] = sprintf(_x('%1$s ~ ORIGINALLY: %2$s', 's2member-front', 's2member'), $desc, $attr['desc']);

						if($affiliate_id && empty($_COOKIE['idev']) && (in_array('affiliates-silent-post', $process) || in_array('affiliates-1px-response', $process)))
							foreach(preg_split('/['."\r\n\t".']+/', $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_affiliate_coupon_code_tracking_urls'], NULL, PREG_SPLIT_NO_EMPTY) as $_url)
							{
								if(($_url = preg_replace('/%%full_coupon_code%%/i', c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($full_coupon_code)), $_url)))
									if(($_url = preg_replace('/%%coupon_code%%/i', c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($coupon_code)), $_url)))
										if(($_url = preg_replace('/%%(?:coupon_affiliate_id|affiliate_id)%%/i', c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($affiliate_id)), $_url)))
											if(($_url = preg_replace('/%%user_ip%%/i', c_ws_plugin__s2member_utils_strings::esc_refs(urlencode($_SERVER['REMOTE_ADDR'])), $_url)))
												if(($_url = trim(preg_replace('/%%(.+?)%%/i', '', $_url))) /* Cleanup any remaining Replacement Codes. */)
												{
													if(!($_r = 0) && ($_url = preg_replace('/^silent-php\|/i', '', $_url, 1, $_r)) && $_r && in_array('affiliates-silent-post', $process))
														c_ws_plugin__s2member_utils_urls::remote($_url, FALSE, array('blocking' => FALSE)); // Post silently via PHP. Relies on IP tracking.

													else if(!($_r = 0) && ($_url = preg_replace('/^img-1px\|/i', '', $_url, 1, $_r)) && $_r && in_array('affiliates-1px-response', $process))
														if(!empty($response) && $return === 'response') // Now, we MUST also have a `$response`, and MUST be returning `$response`.
															$response .= "\n".'<img src="'.esc_attr($_url).'" style="width:0; height:0; border:0;" alt="" />';
												}
							}
						unset($_url, $_r); // Just a little housekeeping here. Unset these variables.
					}
				}
				else $response = _x('<div>Sorry, your discount code is N/A, invalid or expired.</div>', 's2member-front', 's2member');

			$attr['_coupon_applies']      = isset($coupon_applies) && $coupon_applies ? '1' : '0';
			$attr['_coupon_code']         = isset($coupon_applies, $coupon_code) && $coupon_applies ? $coupon_code : '';
			$attr['_full_coupon_code']    = isset($coupon_applies, $full_coupon_code) && $coupon_applies ? $full_coupon_code : '';
			$attr['_coupon_affiliate_id'] = isset($coupon_applies, $affiliate_id) && $coupon_applies && empty($_COOKIE['idev']) ? $affiliate_id : '';

			return $return === 'response' ? (!empty($response) ? $response : '') : $attr;
		}

		public function valid_coupon($coupon_code, $attr)
		{
			global $wpdb; // Global DB reference.
			/** @var $wpdb \wpdb Reference for IDEs. */

			global $current_user; // Global user reference.
			/** @var $current_user \WP_User for IDEs. */

			$current_time = time(); // UTC timestamp.

			if(!($coupon_code = trim((string)$coupon_code)))
				return array(); // Not possible.

			if(strlen($_affiliate_suffix_chars = $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_affiliate_coupon_code_suffix_chars']))
				if(preg_match('/^(.+?)'.preg_quote($_affiliate_suffix_chars, '/').'([0-9]+)$/i', $coupon_code, $_m))
					$coupon_code = $_m[1]; // Validate the underlying coupon code only.
			unset($_affiliate_suffix_chars, $_m); // Housekeeping.

			if(!($coupon_code = $this->n_code($coupon_code)))
				return array(); // Not valid at all :-)

			$attr = (array)$attr; // Force array value.

			foreach($this->coupons as $_coupon) // Iterate coupons.
			{
				if($coupon_code !== $_coupon['code'])
					continue; // Not a match here.

				if(!$_coupon['active_time'] || $current_time >= $_coupon['active_time'])
					if(!$_coupon['expires_time'] || $current_time <= $_coupon['expires_time'])
						if(!$_coupon['singulars'] || (!empty($attr['singular']) && in_array((integer)$attr['singular'], $_coupon['singulars'], TRUE)))
							if(!$_coupon['users'] || ($current_user->ID && in_array((integer)$current_user->ID, $_coupon['users'], TRUE)))
								if(!$_coupon['max_uses'] || $this->get_uses($_coupon['code']) < $_coupon['max_uses'])
									return $_coupon; // It's discount time! :-)
				return array(); // Not valid at this time.
			}
			unset($_coupon); // Housekeeping.

			if(($_coupon = $this->get_gift($coupon_code))) // It's a gift code?
			{
				if(!$_coupon['active_time'] || $current_time >= $_coupon['active_time'])
					if(!$_coupon['expires_time'] || $current_time <= $_coupon['expires_time'])
						if(!$_coupon['singulars'] || (!empty($attr['singular']) && in_array((integer)$attr['singular'], $_coupon['singulars'], TRUE)))
							if(!$_coupon['users'] || ($current_user->ID && in_array((integer)$current_user->ID, $_coupon['users'], TRUE)))
								if(!$_coupon['max_uses'] || $this->get_uses($_coupon['code']) < $_coupon['max_uses'])
									return $_coupon; // It's discount time! :-)
				return array(); // Not valid at this time.
			}
			unset($_coupon); // Housekeeping.

			return array(); // Not valid at this time.
		}

		public function get_gift($coupon_code)
		{
			if(!($coupon_code = trim((string)$coupon_code)))
				return array(); // Not possible.

			$gift = get_option($this->gift_option_key($coupon_code));

			return is_array($gift) && !empty($gift['code']) ? $gift : array();
		}

		public function generate_gifts($args)
		{
			$default_args = array(
				'quantity'  => 0,
				'discount'  => '',
				'directive' => '',
				'singulars' => '',
			);
			$args         = array_merge($default_args, $args);
			$args         = array_intersect_key($args, $default_args);

			$quantity  = (integer)$args['quantity'];
			$discount  = str_replace('|', '', trim((string)$args['discount']));
			$directive = str_replace('|', '', trim((string)$args['directive']));
			$singulars = str_replace('|', '', trim((string)$args['singulars']));

			if(!($quantity = (integer)$quantity) || $quantity < 1)
				return array(); // Not possible.

			for($_i = 0, $gifts = array(); $_i < $quantity; $_i++)
			{
				$_gift_code = c_ws_plugin__s2member_utils_encryption::uunnci_key_20_max();
				$_gift_code = $this->n_code('GC'.str_pad($_gift_code, 20, '0', STR_PAD_LEFT));
				$_gift_list = $_gift_code.'|'.$discount.'||'.$directive.'|'.$singulars.'||1';
				// `code|discount|dates|directive|singulars|users|max uses`.

				$_gift              = $this->list_to_coupons($_gift_list, FALSE);
				$_gift              = array_merge(array_pop($_gift), array('is_gift' => TRUE));
				$gifts[$_gift_code] = $_gift; // A coupon code that's a gift.

				add_option($this->gift_option_key($_gift_code), $gifts[$_gift_code], '', 'no');
			}
			unset($_i, $_gift_code, $_gift_list, $_gift); // Housekeeping.

			return $gifts;
		}

		public function get_uses($coupon_code)
		{
			if(!($coupon_code = trim((string)$coupon_code)))
				return 0; // Not possible.

			return (integer)get_option($this->uses_option_key($coupon_code));
		}

		public function update_uses($coupon_code, $to = NULL)
		{
			if(!($coupon_code = trim((string)$coupon_code)))
				return; // Not possible.

			$uses_option_key = $this->uses_option_key($coupon_code);

			if(($current_uses = get_option($uses_option_key)) === FALSE)
				add_option($uses_option_key, isset($to) ? (integer)$to : 1, '', 'no');
			else update_option($uses_option_key, isset($to) ? (integer)$to : $current_uses + 1);
		}

		public function delete_uses($coupon_code)
		{
			if(!($coupon_code = trim((string)$coupon_code)))
				return; // Not possible.

			delete_option($this->uses_option_key($coupon_code));
		}

		public function uses_option_key($coupon_code)
		{
			if(!($coupon_code = trim((string)$coupon_code)))
				return ''; // Not possible.

			return 's2m_cpc_uses_'.$this->n_code_hash($coupon_code); // 53 chars + DB prefix.
		}

		public function gift_option_key($coupon_code)
		{
			if(!($coupon_code = trim((string)$coupon_code)))
				return ''; // Not possible.

			return 's2m_cpc_gift_'.$this->n_code_hash($coupon_code); // 53 chars + DB prefix.
		}

		public function n_code_hash($coupon_code)
		{
			if(!($coupon_code = trim((string)$coupon_code)))
				return ''; // Not possible.

			return hash('sha1', $this->n_code($coupon_code)); // 40 chars.
		}

		public function n_code($coupon_code)
		{
			if(!($coupon_code = trim((string)$coupon_code)))
				return ''; // Not possible.

			return strtoupper(preg_replace('/\-+/', '', $coupon_code));
		}

		public static function after_update_all_options()
		{
			if(is_admin() && !empty($_REQUEST['page']) && $_REQUEST['page'] === 'ws-plugin--s2member-pro-coupon-codes' && !empty($_POST['ws_plugin__s2member_options_save']))
			{
				$coupons                                                     = new c_ws_plugin__s2member_pro_coupons(array('update' => 'counters'));
				$GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_coupon_codes'] = $coupons->list;
			}
		}
	}
}
