<?php
/**
 * Menu page for s2Member Pro (Import/Export page).
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
if(!defined('WPINC')) // MUST have WordPress.
	exit ("Do not access this file directly.");

if(!class_exists("c_ws_plugin__s2member_pro_menu_page_import_export"))
{
	/**
	 * Menu page for s2Member Pro (Import/Export page).
	 *
	 * @package s2Member\Menu_Pages
	 * @since 110531
	 */
	class c_ws_plugin__s2member_pro_menu_page_import_export
	{
		public function __construct()
		{
			echo '<div class="wrap ws-menu-page">'."\n";

			echo '<div class="ws-menu-page-toolbox">'."\n";
			c_ws_plugin__s2member_menu_pages_tb::display();
			echo '</div>'."\n";

			echo '<h2>Imports / Exports</h2>'."\n";

			echo '<table class="ws-menu-page-table">'."\n";
			echo '<tbody class="ws-menu-page-table-tbody">'."\n";
			echo '<tr class="ws-menu-page-table-tr">'."\n";
			echo '<td class="ws-menu-page-table-l">'."\n";

			if(isset($_REQUEST['enable_advanced_tools']))
				c_ws_plugin__s2member_menu_pages::update_all_options(array('ws_plugin__s2member_pro_import_export_advanced_mode' => (string)(integer)$_REQUEST['enable_advanced_tools']), TRUE, FALSE, FALSE, FALSE, FALSE);
			$enable_advanced_tools = $GLOBALS['WS_PLUGIN__']['s2member']['o']['pro_import_export_advanced_mode'];

			if(!is_multisite() || !c_ws_plugin__s2member_utils_conds::is_multisite_farm() || is_main_site())
			{
				if(!$enable_advanced_tools)
					echo '<p><i class="fa fa-eye"></i> <a href="'.esc_attr(add_query_arg('enable_advanced_tools', '1')).'">click here to enable the Advanced Import/Export Tools</a> <i class="fa fa-eye"></i></p>';
				else echo '<p><i class="fa fa-eye-slash"></i> <a href="'.esc_attr(add_query_arg('enable_advanced_tools', '0')).'">click here to disable the Advanced Import/Export Tools</a> <i class="fa fa-eye-slash"></i></p>';
			}
			/*
			 * Advanced version.
			 */
			if($enable_advanced_tools)
			{
				echo '<div class="ws-menu-page-group" title="Advanced User/Member CSV Importation"'.((isset($_POST["ws_plugin__s2member_pro_import_users"])) ? ' default-state="open"' : '').'>'."\n";

				echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-user-importation-section">'."\n";
				echo '<h3>User/Member Importation (upload file / or direct input)</h3>'."\n";
				echo '<p>Import files can be uploaded in CSV format, or you can copy/paste data into the form field provided. In either case, you must follow the <a href="http://www.s2member.com/kb/advanced-import-tools/" target="_blank" rel="external">examples given here</a>. Please double-check your data before clicking the Import button. Make sure that all data fields are properly encapsulated by double-quotes, and separated by commas. After importing Users/Members, you can inspect your work by going to: <strong>WordPress → Users</strong>.</p>'."\n";
				echo '<p><em><strong>No Email Notification:</strong> This import routine works silently. Users/Members will not be contacted by s2Member—that is, unless you have another plugin installed that conflicts with s2Member\'s ability to perform the Import properly. You should always test one or two accounts before importing a large number of Users all at once. If you want Users/Members to be contacted, you can add them manually, by going to <strong>WordPress → Users → Add New</strong>, and selecting one of the s2Member Roles from the drop-down menu.</em></p>'."\n";
				echo (version_compare(PHP_VERSION, "5.3", "<")) ? '<p><em><strong>PHP v5.3+ recommended:</strong> In order for s2Member to properly import CSV files containing escape sequences, PHP v5.3 or higher is required. While s2Member may be able to parse import files in most cases, PHP v5.3 provides the best stability.</em></p>'."\n" : '';
				echo '<p class="info"><em><strong>Advanced:</strong> This importer uses an entirely different format. See <a href="http://www.s2member.com/kb/advanced-import-tools/" target="_blank" rel="external">this KB article for details</a>.</em></p>'."\n";
				echo '<p class="warning"><em><strong>Important Warning:</strong> Please do not attempt to import a file that you originally exported with the default s2Member User Export system; it is not compatible. This tool uses an entirely different format; i.e., the Advanced Export Tool format is required here. Importing with the wrong format may result in User database table corruption.</em></p>'."\n";

				echo '<table class="form-table">'."\n";
				echo '<tbody>'."\n";
				echo '<tr>'."\n";

				echo '<td>'."\n";
				echo '<form method="post" enctype="multipart/form-data" name="ws_plugin__s2member_pro_import_users_form" id="ws-plugin--s2member-pro-import-users-form" autocomplete="off">'."\n";
				echo '<input type="hidden" name="ws_plugin__s2member_pro_import_users" id="ws-plugin--s2member-pro-import-users" value="'.esc_attr(wp_create_nonce("ws-plugin--s2member-pro-import-users")).'" />'."\n";

				echo '<input type="file" name="ws_plugin__s2member_pro_import_users_file" id="ws-plugin--s2member-pro-import-users-file" />&nbsp;&nbsp;&nbsp;(up to 1000 lines per file)&nbsp;&nbsp;&nbsp;<input type="submit" value="Import Now" style="font-size:120%; font-weight:normal;" /><br /><br />'."\n";
				echo '<textarea name="ws_plugin__s2member_pro_import_users_direct_input" id="ws-plugin--s2member-pro-import-users-direct-input" rows="10" wrap="off" spellcheck="false" style="width:99%;">'.format_to_edit(trim(stripslashes(@$_POST["ws_plugin__s2member_pro_import_users_direct_input"]))).'</textarea><br />'."\n";

				echo 'One User/Member per line please. See <a href="http://www.s2member.com/kb/advanced-import-tools/" target="_blank" rel="external">this KB article for details</a>.'."\n";
				echo '</form>'."\n";
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '</tbody>'."\n";
				echo '</table>'."\n";
				echo '</div>'."\n";

				echo '</div>'."\n";
			}
			/*
			 * Simple version.
			 */
			if(!$enable_advanced_tools)
			{
				if(is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site())
				{
					echo '<div class="ws-menu-page-group" title="User/Member CSV Importation"'.((isset($_POST["ws_plugin__s2member_pro_import_simple_users"])) ? ' default-state="open"' : '').'>'."\n";

					echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-user-importation-section">'."\n";
					echo '<h3>User/Member Importation (upload file / or direct input)</h3>'."\n";
					echo '<p>Import files can be uploaded in CSV format, or you can copy/paste data into the form field provided. In either case, you must follow the examples given below. Please double-check your data before clicking the Import button. Make sure that all data fields are properly encapsulated by double-quotes, and separated by commas. You\'ll also need to make sure that all of your data fields are in the proper order, based on the examples given below. After importing Users/Members, you can inspect your work by going to: <strong>WordPress → Users</strong>.</p>'."\n";
					echo '<p><em><strong>No Email Notification:</strong> This import routine works silently. Users/Members will not be contacted by s2Member—that is, unless you have another plugin installed that conflicts with s2Member\'s ability to perform the Import properly. You should always test one or two accounts before importing a large number of Users all at once. If you want Users/Members to be contacted, you can add them manually, by going to <strong>WordPress → Users → Add New</strong>, and selecting one of the s2Member Roles from the drop-down menu.</em></p>'."\n";
					echo (version_compare(PHP_VERSION, "5.3", "<")) ? '<p><em><strong>PHP v5.3+ recommended:</strong> In order for s2Member to properly import CSV files containing escape sequences, PHP v5.3 or higher is required. While s2Member may be able to parse import files in most cases, PHP v5.3 provides the best stability.</em></p>'."\n" : '';

					echo '<table class="form-table">'."\n";
					echo '<tbody>'."\n";
					echo '<tr>'."\n";

					echo '<td>'."\n";
					echo '<form method="post" enctype="multipart/form-data" name="ws_plugin__s2member_pro_import_users_form" id="ws-plugin--s2member-pro-import-users-form" autocomplete="off">'."\n";
					echo '<input type="hidden" name="ws_plugin__s2member_pro_import_simple_users" id="ws-plugin--s2member-pro-import-users" value="'.esc_attr(wp_create_nonce("ws-plugin--s2member-pro-import-users")).'" />'."\n";

					echo '<input type="file" name="ws_plugin__s2member_pro_import_users_file" id="ws-plugin--s2member-pro-import-users-file" />&nbsp;&nbsp;&nbsp;(up to 1000 lines per file)&nbsp;&nbsp;&nbsp;<input type="submit" value="Import Now" style="font-size:120%; font-weight:normal;" /><br /><br />'."\n";
					echo '<textarea name="ws_plugin__s2member_pro_import_users_direct_input" id="ws-plugin--s2member-pro-import-users-direct-input" rows="10" wrap="off" spellcheck="false" style="width:99%;">'.format_to_edit(trim(stripslashes(@$_POST["ws_plugin__s2member_pro_import_users_direct_input"]))).'</textarea><br />'."\n";

					echo 'One User/Member per line please. Here is a quick example:<br />'."\n";
					echo '<code>"ID","Username","First Name","Last Name","Display Name","Email"</code>'."\n";

					echo '<div class="ws-menu-page-hr"></div>'."\n";

					echo 'If you fill the ID field, the Import routine will update an account matching the ID you specify (so long as the account ID does not belong to an Administrator, this is for security). When importing new Users/Members, you can leave the ID field empty Don\'t remove it, just leave it empty(i.e., <code>""</code>).<br /><br />'."\n";

					echo 'Example: <code>"","Username","First Name","Last Name","Display Name","Email"</code><br /><br />'."\n";

					echo 'Additional extended information can also be included; even Custom Registration/Profile Fields:<br />'."\n";
					echo '<code>"ID","Username","First Name","Last Name","Display Name","Email","Website","Level[0-9]+ or Role ID","Custom Capabilities","Registration Date (mm/dd/yyyy)","First Payment Date (mm/dd/yyyy)","Last Payment Date (mm/dd/yyyy)","Auto-EOT Date (mm/dd/yyyy)","Custom Value (starts w/domain)","Paid Subscr. ID","Paid Subscr. Gateway","Custom Registration/Profile Field Value","another Custom Registration/Profile Field Value", ... and so on—for as many Custom Registration/Profile Fields that you\'d like import.</code><br /><br />'."\n";

					echo 'Here is a full example with all fields filled in, including extended details; and two Custom Field values:<br />'."\n";
					echo '<code>"","johnsmith22","John","Smith","John Smith","john.smith@example.com","http://www.example.com/","2","music,videos","12/31/2000","01/10/2001","12/31/2020","12/31/2021","www.example.com|123.357.125.654","I-2342934SSER243","paypal","fishing,biking,computers","xx-large"</code><br /><br />'."\n";

					echo 'Here is a full example with some fields left empty:<br />'."\n";
					echo '<code>"","johnsmith22","John","Smith","John Smith","john.smith@example.com","","s2member_level2","","","","","12/31/2021"</code>'."\n";

					echo '<div class="ws-menu-page-hr"></div>'."\n";

					echo '<em>* If you supply a Paid Subscr. Gateway, you must use one of these values: <code>paypal</code>, <code>alipay</code>, <code>authnet</code>, <code>ccbill</code>, <code>clickbank</code>, <code>google</code>.</em><br /><br />'."\n";

					echo '<em>* If you supply Custom Fields, your Custom Field values should be listed in alphabetical order, based on your Custom Field IDs (i.e., values listed in the order of your alphabetized Custom Field IDs). This is based on the Custom Field IDs you\'ve configured with s2Member. See: <strong>s2Member → General Options → Registration/Profile Fields</strong>. In the example above, you will see two Custom Field values (<code>... "fishing,biking,computers","xx-large"</code>). The first value comes first, because it maps to the Custom Field ID <code>interests</code>, and the second one comes last, because it maps to the Custom Field ID <code>t_shirt_size</code> (i.e., alphabetical order—based on the underlying Custom Field IDs that you\'re mapping this data to).</em><br /><br />'."\n";
					echo '<em>* If you have a Custom Field that contains an array of multiple values, you can import the array using PHP\'s <a href="http://php.net/manual/en/function.serialize.php" target="_blank" rel="external">serialize()</a> function. This allows you to convert the array into a string representation. s2Member will automatically unserialize the value during importation. If you have any trouble, please perform an export first. s2Member\'s export files are already formatted for easy re-importation. In other words, you can use them as a guideline for building your own import files.</em><br /><br />'."\n";

					echo '<em>* If you supply "First Payment Date", you have two options available. You can either supply a simple date in this format (mm/dd/yyyy), or you can import an array of First Payment Dates, in the form of Unix timestamps. s2Member has the ability to record and monitor First Payment Dates at each specific Membership Level. The array it expects, consists of the following: <code>array("level" => [timestamp of first payment date regardless of level], "level1" => [timestamp of first payment date at level #1], "level2" => [timestamp], "level3" => [timestamp], "level4" => [timestamp])</code>. Of course, if you decide to import an array with some of these timestamps, you will need to use PHP\'s <a href="http://php.net/manual/en/function.serialize.php" target="_blank" rel="external">serialize()</a> function to convert the array into a string representation. If you have any trouble, please perform an export first. s2Member\'s export files are already formatted for easy re-importation. In other words, you can use them as a guideline for building your own import files. By default, s2Member exports an array of timestamps.</em>'."\n";

					echo '</form>'."\n";
					echo '</td>'."\n";

					echo '</tr>'."\n";
					echo '</tbody>'."\n";
					echo '</table>'."\n";
					echo '</div>'."\n";

					echo '</div>'."\n";
				}
				else // Otherwise, we use the standardized format for Importation.
				{
					echo '<div class="ws-menu-page-group" title="User/Member CSV Importation"'.((isset ($_POST["ws_plugin__s2member_pro_import_simple_users"])) ? ' default-state="open"' : '').'>'."\n";

					echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-user-importation-section">'."\n";
					echo '<h3>User/Member Importation (upload file / or direct input)</h3>'."\n";
					echo '<p>Import files can be uploaded in CSV format, or you can copy/paste data into the form field provided. In either case, you must follow the examples given below. Please double-check your data before clicking the Import button. Make sure that all data fields are properly encapsulated by double-quotes, and separated by commas. You\'ll also need to make sure that all of your data fields are in the proper order, based on the examples given below. After importing Users/Members, you can inspect your work by going to: <strong>WordPress → Users</strong>.</p>'."\n";
					echo '<p><em><strong>No Email Notification:</strong> This import routine works silently. Users/Members will not be contacted by s2Member—that is, unless you have another plugin installed that conflicts with s2Member\'s ability to perform the Import properly. You should always test one or two accounts before importing a large number of Users all at once. If you want Users/Members to be contacted, you can add them manually, by going to <strong>WordPress → Users → Add New</strong>, and selecting one of the s2Member Roles from the drop-down menu.</em></p>'."\n";
					echo (version_compare(PHP_VERSION, "5.3", "<")) ? '<p><em><strong>PHP v5.3+ recommended:</strong> In order for s2Member to properly import CSV files containing escape sequences, PHP v5.3 or higher is required. While s2Member may be able to parse import files in most cases, PHP v5.3 provides the best stability.</em></p>'."\n" : '';

					echo '<table class="form-table">'."\n";
					echo '<tbody>'."\n";
					echo '<tr>'."\n";

					echo '<td>'."\n";
					echo '<form method="post" enctype="multipart/form-data" name="ws_plugin__s2member_pro_import_users_form" id="ws-plugin--s2member-pro-import-users-form" autocomplete="off">'."\n";
					echo '<input type="hidden" name="ws_plugin__s2member_pro_import_simple_users" id="ws-plugin--s2member-pro-import-users" value="'.esc_attr(wp_create_nonce("ws-plugin--s2member-pro-import-users")).'" />'."\n";

					echo '<input type="file" name="ws_plugin__s2member_pro_import_users_file" id="ws-plugin--s2member-pro-import-users-file" />&nbsp;&nbsp;&nbsp;(up to 1000 lines per file)&nbsp;&nbsp;&nbsp;<input type="submit" value="Import Now" style="font-size:120%; font-weight:normal;" /><br /><br />'."\n";
					echo '<textarea name="ws_plugin__s2member_pro_import_users_direct_input" id="ws-plugin--s2member-pro-import-users-direct-input" rows="10" wrap="off" spellcheck="false" style="width:99%;">'.format_to_edit(trim(stripslashes(@$_POST["ws_plugin__s2member_pro_import_users_direct_input"]))).'</textarea><br />'."\n";

					echo 'One User/Member per line please. Here is a quick example:<br />'."\n";
					echo '<code>"ID","Username","Password","First Name","Last Name","Display Name","Email"</code><br /><br />'."\n";

					echo 'If you fill the ID field, the Import routine will update an account matching the ID you specify (so long as the account ID does not belong to an Administrator, this is for security). When importing new Users/Members, you can leave the ID field empty Don\'t remove it, just leave it empty(i.e., <code>""</code>).<br /><br />'."\n";

					echo 'Example: <code>"","Username","Password","First Name","Last Name","Display Name","Email"</code>'."\n";

					echo '<div class="ws-menu-page-hr"></div>'."\n";

					echo 'Additional extended information can also be included; even Custom Registration/Profile Fields:<br />'."\n";
					echo 'Please see this KB article for more detailed instructions: <a href="http://www.s2member.com/kb/importing-updating-users/" target="_blank" rel="external nofollow x-link">Import (Or Mass Update Users)</a>'."\n";
					echo '</form>'."\n";
					echo '</td>'."\n";

					echo '</tr>'."\n";
					echo '</tbody>'."\n";
					echo '</table>'."\n";
					echo '</div>'."\n";

					echo '</div>'."\n";
				}
			}
			/*
			 * Advanced version.
			 */
			if($enable_advanced_tools)
			{
				echo '<div class="ws-menu-page-group" title="Advanced User/Member CSV Exportation">'."\n";

				echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-user-exportation-section">'."\n";
				echo '<h3>User/Member Exportation (download CSV export files)</h3>'."\n";
				echo '<p class="info"><em><strong>Advanced:</strong> This exporter uses an entirely different format. See <a href="http://www.s2member.com/kb/advanced-import-tools/" target="_blank" rel="external">this KB article for details</a>.</em></p>'."\n";
				echo '<p class="warning"><em><strong>Important Warning:</strong> The export you receive from this Advanced Tool is not compatible with the default s2Member User Import system. This tool uses an entirely different format; i.e., files exported here are in the Advanced Import Tool format. If you plan to reimport and/or mass update existing Users/Members based on these Advanced files, you must use the Advanced Import Tool to do so. Attempting to import an Advanced Export file with the Default Import Tool may result in User database table corruption.</em></p>'."\n";

				echo '<form method="post" name="ws_plugin__s2member_pro_export_users_form" id="ws-plugin--s2member-pro-export-users-form" autocomplete="off">'."\n";
				echo '<input type="hidden" name="ws_plugin__s2member_pro_export_users" id="ws-plugin--s2member-pro-export-users" value="'.esc_attr(wp_create_nonce("ws-plugin--s2member-pro-export-users")).'" />'."\n";

				echo '<table class="form-table">'."\n";
				echo '<tbody>'."\n";
				echo '<tr>'."\n";

				echo '<th>'."\n";
				echo '<label for="ws-plugin--s2member-pro-export-users-format">'."\n";
				echo 'CSV File Preference:'."\n";
				echo '</label>'."\n";
				echo '</th>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<td>'."\n";
				echo '<select name="ws_plugin__s2member_pro_export_users_format" id="ws-plugin--s2member-pro-export-users-format">'."\n";
				echo '<option value="" selected="selected">Default (CSV, perfectly formatted for easy re-importation)</option>'."\n";
				echo '<option value="readable">Easy-Read (CSV w/ improved readability; cannot be re-imported)</option>'."\n";
				echo '</select><br />'."\n";
				echo '<em>Open CSV files with Notepad, TextEdit (Mac), Numbers (Mac), MS Excel, or <a href="http://s2member.com/r/openoffice/" target="_blank" rel="external">OpenOffice</a> (recommended).</em>';
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<th>'."\n";
				echo '<label for="ws-plugin--s2member-pro-export-users-utf8-bom">'."\n";
				echo 'Add UTF-8 BOM (Byte Order Marker)?'."\n";
				echo '</label>'."\n";
				echo '</th>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<td>'."\n";
				echo '<select name="ws_plugin__s2member_pro_export_users_utf8_bom" id="ws-plugin--s2member-pro-export-users-utf8-bom">'."\n";
				echo '<option value="0" selected="selected">Default (no, I will choose UTF-8 encoding when I open the file)</option>'."\n";
				echo '<option value="1">Yes (add a UTF-8 Byte Order Marker so that UTF-8 encoding is always in use for this file)</option>'."\n";
				echo '</select><br />'."\n";
				echo '<em>If <code>Yes</code>—please open CSV files with Numbers (Mac) or <a href="http://s2member.com/r/openoffice/" target="_blank" rel="external">OpenOffice</a> (recommended).</em>';
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<th>'."\n";
				echo '<label for="ws-plugin--s2member-pro-export-users-start">'."\n";
				echo 'CSV File Exportation:'."\n";
				echo '</label>'."\n";
				echo '</th>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<td>'."\n";
				echo 'You have a total of '.number_format(c_ws_plugin__s2member_utils_users::users_in_database()).' User/Member rows in the database'.((is_multisite()) ? ' for this site' : '').'.<br />'."\n";
				if(is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site())
				{
					echo 'You can export up to 1000 database rows in each file; starting from a particular row that you specify.<br />'."\n";
					echo 'Export, starting with row#: <input type="text" autocomplete="off" name="ws_plugin__s2member_pro_export_users_start" id="ws-plugin--s2member-pro-export-users-start" style="width:100px;" value="1" /> <input type="submit" value="Export Now" style="font-size:120%; font-weight:normal;" />'."\n";
				}
				else echo 'Export, starting with row#: <input type="text" autocomplete="off" name="ws_plugin__s2member_pro_export_users_start" id="ws-plugin--s2member-pro-export-users-start" style="width:100px;" value="1" /> limit to: <input type="text" autocomplete="off" name="ws_plugin__s2member_pro_export_users_limit" id="ws-plugin--s2member-pro-export-users-limit" style="width:100px;" value="1000" /> rows <input type="submit" value="Export Now" style="font-size:120%; font-weight:normal;" />'."\n";

				echo '<div class="ws-menu-page-hr"></div>'."\n";

				echo '<em>Please note. Export files do not contain Passwords. Passwords are stored by WordPress with one-way encryption. In other words, it\'s not possible for s2Member to include them in the export file. However, this does not create a problem, because when/if you re-import existing Users/Members with the Password field empty, s2Member will simply keep the existing Password that is already on file. For further information, please read all Import instructions, regarding Passwords.</em>'."\n";
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '</tbody>'."\n";
				echo '</table>'."\n";

				echo '</form>'."\n";
				echo '</div>'."\n";

				echo '</div>'."\n";
			}
			/*
			 * Simple version.
			 */
			if(!$enable_advanced_tools)
			{
				echo '<div class="ws-menu-page-group" title="User/Member CSV Exportation">'."\n";

				echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-user-exportation-section">'."\n";
				echo '<h3>User/Member Exportation (download CSV export files)</h3>'."\n";

				echo '<form method="post" name="ws_plugin__s2member_pro_export_users_form" id="ws-plugin--s2member-pro-export-users-form" autocomplete="off">'."\n";
				echo '<input type="hidden" name="ws_plugin__s2member_pro_export_simple_users" id="ws-plugin--s2member-pro-export-users" value="'.esc_attr(wp_create_nonce("ws-plugin--s2member-pro-export-users")).'" />'."\n";

				echo '<table class="form-table">'."\n";
				echo '<tbody>'."\n";
				echo '<tr>'."\n";

				echo '<th>'."\n";
				echo '<label for="ws-plugin--s2member-pro-export-users-format">'."\n";
				echo 'CSV File Preference:'."\n";
				echo '</label>'."\n";
				echo '</th>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<td>'."\n";
				echo '<select name="ws_plugin__s2member_pro_export_users_format" id="ws-plugin--s2member-pro-export-users-format">'."\n";
				echo '<option value="" selected="selected">Default (CSV, perfectly formatted for easy re-importation)</option>'."\n";
				echo '<option value="readable">Easy-Read (CSV w/ improved readability; cannot be re-imported)</option>'."\n";
				echo '</select><br />'."\n";
				echo '<em>Open CSV files with Notepad, TextEdit (Mac), Numbers (Mac), MS Excel, or <a href="http://s2member.com/r/openoffice/" target="_blank" rel="external">OpenOffice</a> (recommended).</em>';
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<th>'."\n";
				echo '<label for="ws-plugin--s2member-pro-export-users-utf8-bom">'."\n";
				echo 'Add UTF-8 BOM (Byte Order Marker)?'."\n";
				echo '</label>'."\n";
				echo '</th>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<td>'."\n";
				echo '<select name="ws_plugin__s2member_pro_export_users_utf8_bom" id="ws-plugin--s2member-pro-export-users-utf8-bom">'."\n";
				echo '<option value="0" selected="selected">Default (no, I will choose UTF-8 encoding when I open the file)</option>'."\n";
				echo '<option value="1">Yes (add a UTF-8 Byte Order Marker so that UTF-8 encoding is always in use for this file)</option>'."\n";
				echo '</select><br />'."\n";
				echo '<em>If <code>Yes</code>—please open CSV files with Numbers (Mac) or <a href="http://s2member.com/r/openoffice/" target="_blank" rel="external">OpenOffice</a> (recommended).</em>';
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<th>'."\n";
				echo '<label for="ws-plugin--s2member-pro-export-users-start">'."\n";
				echo 'CSV File Exportation:'."\n";
				echo '</label>'."\n";
				echo '</th>'."\n";

				echo '</tr>'."\n";
				echo '<tr>'."\n";

				echo '<td>'."\n";
				echo 'You have a total of '.number_format(c_ws_plugin__s2member_utils_users::users_in_database()).' User/Member rows in the database'.((is_multisite()) ? ' for this site' : '').'.<br />'."\n";
				if(is_multisite() && c_ws_plugin__s2member_utils_conds::is_multisite_farm() && !is_main_site())
				{
					echo 'You can export up to 1000 database rows in each file; starting from a particular row that you specify.<br />'."\n";
					echo 'Export, starting with row#: <input type="text" autocomplete="off" name="ws_plugin__s2member_pro_export_users_start" id="ws-plugin--s2member-pro-export-users-start" style="width:100px;" value="1" /> <input type="submit" value="Export Now" style="font-size:120%; font-weight:normal;" />'."\n";
				}
				else echo 'Export, starting with row#: <input type="text" autocomplete="off" name="ws_plugin__s2member_pro_export_users_start" id="ws-plugin--s2member-pro-export-users-start" style="width:100px;" value="1" /> limit to: <input type="text" autocomplete="off" name="ws_plugin__s2member_pro_export_users_limit" id="ws-plugin--s2member-pro-export-users-limit" style="width:100px;" value="1000" /> rows <input type="submit" value="Export Now" style="font-size:120%; font-weight:normal;" />'."\n";

				echo '<div class="ws-menu-page-hr"></div>'."\n";

				echo '<em>Please note. Export files do not contain Passwords. Passwords are stored by WordPress with one-way encryption. In other words, it\'s not possible for s2Member to include them in the export file. However, this does not create a problem, because when/if you re-import existing Users/Members with the Password field empty, s2Member will simply keep the existing Password that is already on file. For further information, please read all Import instructions, regarding Passwords.</em>'."\n";
				echo '</td>'."\n";

				echo '</tr>'."\n";
				echo '</tbody>'."\n";
				echo '</table>'."\n";

				echo '</form>'."\n";
				echo '</div>'."\n";

				echo '</div>'."\n";
			}
			/*
			 * There is no variation on this one; always the same.
			 */
			echo '<div class="ws-menu-page-group" title="s2Member Options (Import/Export)"'.((isset ($_POST["ws_plugin__s2member_pro_import_ops"])) ? ' default-state="open"' : '').'>'."\n";

			echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-ops-importation-exportation-section">'."\n";

			echo '<h3 style="margin-bottom:5px;">s2Member Options Export <small>(<a href="'.esc_attr(home_url("/?ws_plugin__s2member_pro_export_ops=".urlencode(wp_create_nonce("ws-plugin--s2member-pro-export-ops")))).'">download serialized export file</a>)</small></h3>'."\n";
			echo '<p style="margin-top:5px;">This allows you to export your current s2Member configuration, and then import it into another instance of WordPress.'."\n";

			echo '<div class="ws-menu-page-hr"></div>'."\n";

			echo '<h3 style="margin-bottom:5px;">s2Member Options Import <small>(upload your serialized export file)</small></h3>'."\n";
			echo '<p style="margin-top:5px;">This allows you to import your s2Member configuration export file, from another instance of WordPress.'."\n";

			echo '<table class="form-table">'."\n";
			echo '<tbody>'."\n";
			echo '<tr>'."\n";

			echo '<td>'."\n";
			echo '<form method="post" enctype="multipart/form-data" name="ws_plugin__s2member_pro_import_ops_form" id="ws-plugin--s2member-pro-import-ops-form" autocomplete="off">'."\n";
			echo '<input type="hidden" name="ws_plugin__s2member_pro_import_ops" id="ws-plugin--s2member-pro-import-ops" value="'.esc_attr(wp_create_nonce("ws-plugin--s2member-pro-import-ops")).'" />'."\n";
			echo '<input type="file" name="ws_plugin__s2member_pro_import_ops_file" id="ws-plugin--s2member-pro-import-ops-file" />&nbsp;&nbsp;&nbsp;<input type="submit" value="Import Now" style="font-size:120%; font-weight:normal;" />'."\n";
			echo '</form>'."\n";
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '</div>'."\n";

			echo '</div>'."\n";

			echo '</td>'."\n";

			echo '<td class="ws-menu-page-table-r">'."\n";
			c_ws_plugin__s2member_menu_pages_rs::display();
			echo '</td>'."\n";

			echo '</tr>'."\n";
			echo '</tbody>'."\n";
			echo '</table>'."\n";

			echo '</div>'."\n";
		}
	}
}

new c_ws_plugin__s2member_pro_menu_page_import_export ();