<?php
//j// BOF

/*n// NOTE
----------------------------------------------------------------------------
secured WebGine
net-based application engine
----------------------------------------------------------------------------
(C) direct Netware Group - All rights reserved
http://www.direct-netware.de/redirect.php?swg

The following license agreement remains valid unless any additions or
changes are being made by direct Netware Group in a written form.

This program is free software; you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation; either version 2 of the License, or (at your
option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
more details.

You should have received a copy of the GNU General Public License along with
this program; if not, write to the Free Software Foundation, Inc.,
59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
----------------------------------------------------------------------------
http://www.direct-netware.de/redirect.php?licenses;gpl
----------------------------------------------------------------------------
$Id: swg_datacenter_media.php,v 1.1 2009/03/13 16:13:07 s4u Exp $
#echo(sWGdatacenterVersion)#
sWG/#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* osets/default/swg_datacenter_media.php
*
* @internal   We are using phpDocumentor to automate the documentation process
*             for creating the Developer's Manual. All sections including
*             these special comments will be removed from the release source
*             code.
*             Use the following line to ensure 76 character sizes:
* ----------------------------------------------------------------------------
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    sWG
* @subpackage datacenter
* @uses       direct_product_iversion
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;gpl
*             GNU General Public License 2
*/

/* -------------------------------------------------------------------------
All comments will be removed in the "production" packages (they will be in
all development packets)
------------------------------------------------------------------------- */

//j// Basic configuration

/* -------------------------------------------------------------------------
Direct calls will be honored with an "exit ()"
------------------------------------------------------------------------- */

if (!defined ("direct_product_iversion")) { exit (); }

//j// Functions and classes

//f// direct_output_oset_datacenter_media_details ()
/**
* direct_output_oset_datacenter_media_details ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_output_oset_datacenter_media_details ()
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_output_oset_datacenter_media_details ()- (#echo(__LINE__)#)"); }

	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_account_profile.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_datacenter.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/mods/swgi_mods_support.php");

	if (isset ($direct_cachedata['output_dir']))
	{
		$f_user_colspan = "";
		$f_user_width = "width:50%;";
	}
	else
	{
		$f_user_colspan = " colspan='2'";
		$f_user_width = "";
	}

$f_return = ("<table cellspacing='1' summary='' class='pageborder1' style='width:100%;table-layout:auto'>
<thead><tr>
<td colspan='2' align='left' class='pagetitlecellbg' style='padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>".(direct_local_get ("datacenter_object_details"))."</span></td>
</tr></thead><tbody><tr>
<td$f_user_colspan valign='middle' align='left' class='pageextrabg' style='{$f_user_width}padding:$direct_settings[theme_td_padding]'>".(direct_account_oset_parse_user_fullh ($direct_cachedata['output_object'],"page","","","user"))."</td>");

	if (isset ($direct_cachedata['output_dir']))
	{
		$f_return .= "\n<td valign='middle' align='center' class='pagebg' style='width:50%;padding:$direct_settings[theme_td_padding]'><span class='pagecontent'>";
		if ($direct_cachedata['output_dir']['icon']) { $f_return .= "<img src='{$direct_cachedata['output_dir']['icon']}' border='0' alt='' title='' /><br />\n"; }

		if (strlen ($direct_cachedata['output_dir']['title_alt'])) { $f_dir_title = $direct_cachedata['output_dir']['title_alt']; }
		else { $f_dir_title = $direct_cachedata['output_dir']['title']; }

		if ($direct_cachedata['output_dir']['pageurl']) { $f_return .= "<span style='font-weight:bold'><a href=\"{$direct_cachedata['output_dir']['pageurl']}\" target='_self'>$f_dir_title</a></span>"; }
		else { $f_return .= "<span style='font-weight:bold'>$f_dir_title</span>"; }

		if ($direct_cachedata['output_dir']['desc']) { $f_return .= "<br />\n<span style='font-size:10px'>{$direct_cachedata['output_dir']['desc']}</span>"; }
		$f_return .= "</span></td>";
	}

$f_return .= ("\n</tr></tbody>
</table><span style='font-size:8px'>&#0160;</span>".(direct_datacenter_oset_object_parse ($direct_cachedata['output_object'])));

	$f_return .= direct_oset_mods_include ("datacenter_media_details",$direct_cachedata['output_modstoview']);
	if ($direct_cachedata['output_object']['usersignature']) { $f_return .= "<p class='pageborder2' style='text-align:center'><span class='pagecontent'>{$direct_cachedata['output_object']['usersignature']}</span></p>"; }

	if (($direct_cachedata['output_object']['subs_allowed'])||($direct_cachedata['output_object']['subs_available']))
	{
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_datalinker_iview.php");
		$f_return .= "\n<p class='pagecontenttitle'>{$direct_cachedata['output_object']['subs_title']}</p>\n".(direct_datalinker_oset_iview_subs ($direct_cachedata['output_object'],7,$direct_cachedata['output_source'],"default"));
	}

	return $f_return;
}

//f// direct_output_oset_datacenter_media_list ()
/**
* direct_output_oset_datacenter_media_list ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_output_oset_datacenter_media_list ()
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_output_oset_datacenter_media_list ()- (#echo(__LINE__)#)"); }

	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_datacenter.php");

	if (strlen ($direct_cachedata['output_dir']['title_alt'])) { $f_return = "<p class='pagecontenttitle'>{$direct_cachedata['output_dir']['title_alt']}</p>"; }
	else { $f_return = "<p class='pagecontenttitle'>{$direct_cachedata['output_dir']['title']}</p>"; }

	if ($direct_cachedata['output_dir']['desc']) { $f_return .= "\n<p class='pagecontent'>{$direct_cachedata['output_dir']['desc']}</p>"; }

	if (isset ($direct_cachedata['output_dir_levelup']))
	{
		$f_return .= "\n<p class='pageborder2' style='text-align:left'>";
		if ($direct_cachedata['output_dir_levelup']['icon']) { $f_return .= "<img src='{$direct_cachedata['output_dir_levelup']['icon']}' border='0' alt='' title='' style='float:left;padding-right:5px' />"; }

		if (strlen ($direct_cachedata['output_dir_levelup']['title_alt'])) { $f_dir_levelup_title = $direct_cachedata['output_dir_levelup']['title_alt']; }
		else { $f_dir_levelup_title = $direct_cachedata['output_dir_levelup']['title']; }

		if ($direct_cachedata['output_dir_levelup']['pageurl']) { $f_return .= "<span class='pageextracontent' style='font-weight:bold'><a href=\"{$direct_cachedata['output_dir_levelup']['pageurl']}\" target='_self'>$f_dir_levelup_title</a></span>"; }
		else { $f_return .= "<span class='pageextracontent' style='font-weight:bold'>$f_dir_levelup_title</span>"; }

		if ($direct_cachedata['output_dir_levelup']['desc']) { $f_return .= "<br />\n<span class='pageextracontent' style='font-size:10px'>{$direct_cachedata['output_dir_levelup']['desc']}</span>"; }
		$f_return .= "</p>";
	}

	if (empty ($direct_cachedata['output_objects'])) { $f_return .= "\n<p class='pagecontent' style='font-weight:bold'>".(direct_local_get ("datacenter_list_empty"))."</p>"; }
	else
	{
		if ($direct_cachedata['output_pages'] > 1) { $f_return .= "\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page']))."</span></p>\n"; }
		$f_return .= direct_datacenter_oset_objects_parse ($direct_cachedata['output_objects']);
		if ($direct_cachedata['output_pages'] > 1) { $f_return .= "\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page']))."</span></p>"; }
	}

	return $f_return;
}

//j// Script specific commands

if (!isset ($direct_settings['theme_td_padding'])) { $direct_settings['theme_td_padding'] = "5px"; }

//j// EOF
?>