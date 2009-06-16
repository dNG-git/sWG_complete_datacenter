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
$Id: swg_datacenter_embedded.php,v 1.1 2009/03/13 16:13:07 s4u Exp $
#echo(sWGdatacenterVersion)#
sWG/#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* osets/default_etitle/swg_datacenter_embedded.php
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

//f// direct_output_oset_datacenter_embedded_ajax_selector ()
/**
* direct_output_oset_datacenter_embedded_ajax_selector ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_output_oset_datacenter_embedded_ajax_selector ()
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_output_oset_datacenter_embedded_selector ()- (#echo(__LINE__)#)"); }

	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_datacenter.php");

	if (strlen ($direct_cachedata['output_dir']['title_alt'])) { $f_dir_title = $direct_cachedata['output_dir']['title_alt']; }
	else { $f_dir_title = $direct_cachedata['output_dir']['title']; }

	if ($direct_cachedata['output_dir']['marked']) { $f_return = "<div id='swgAJAX_datacenter_selector_{$direct_cachedata['output_tid']}_point' style='text-align:left'><p class='pagecontenttitle'>$f_dir_title (".(direct_local_get ("datacenter_target_directory_selected")).")</p>"; }
	else { $f_return = "<div id='swgAJAX_datacenter_selector_{$direct_cachedata['output_tid']}_point' style='text-align:left'><p class='pagecontenttitle'>$f_dir_title</p>"; }

	if (strlen ($direct_cachedata['output_dir']['desc'])) { $f_return .= "\n<p class='pagecontent'>{$direct_cachedata['output_dir']['desc']}</p>"; }

	if (isset ($direct_cachedata['output_dir_levelup']))
	{
		$f_return .= "<p class='pageborder2' style='text-align:left'>";
		if ($direct_cachedata['output_dir_levelup']['icon']) { $f_return .= "<img src='{$direct_cachedata['output_dir_levelup']['icon']}' border='0' alt='' title='' style='float:left;padding-right:5px' />"; }

		if (strlen ($direct_cachedata['output_dir_levelup']['title_alt'])) { $f_dir_title = $direct_cachedata['output_dir_levelup']['title_alt']; }
		else { $f_dir_title = $direct_cachedata['output_dir_levelup']['title']; }

		if ($direct_cachedata['output_dir_levelup']['pageurl']) { $f_return .= "<span class='pageextracontent' style='font-weight:bold'><a href=\"{$direct_cachedata['output_dir_levelup']['pageurl']}\" target='_self'>$f_dir_title</a></span>"; }
		else { $f_return .= "<span class='pageextracontent' style='font-weight:bold'>$f_dir_title</span>"; }

		if ($direct_cachedata['output_dir_levelup']['desc']) { $f_return .= "<br />\n<span class='pageextracontent' style='font-size:10px'>{$direct_cachedata['output_dir_levelup']['desc']}</span>"; }
		$f_return .= "</p>";
	}

	if (empty ($direct_cachedata['output_objects'])) { $f_return .= "\n<p class='pagecontent' style='font-weight:bold'>".(direct_local_get ("datacenter_list_empty"))."</p>"; }
	else
	{
		if ($direct_cachedata['output_pages'] > 1) { $f_return .= "\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page'],"asis"))."</span></p>\n"; }
		$f_return .= direct_datacenter_oset_objects_parse ($direct_cachedata['output_objects'],false);
		if ($direct_cachedata['output_pages'] > 1) { $f_return .= "\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page'],"asis"))."</span></p>"; }
	}

	return $f_return."</div>";
}

//f// direct_output_oset_datacenter_embedded_ajax_selector_icons ()
/**
* direct_output_oset_datacenter_embedded_ajax_selector_icons ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_output_oset_datacenter_embedded_ajax_selector_icons ()
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_output_oset_datacenter_embedded_selector_icons ()- (#echo(__LINE__)#)"); }

	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_datacenter.php");

	if (strlen ($direct_cachedata['output_dir']['title_alt'])) { $f_dir_title = $direct_cachedata['output_dir']['title_alt']; }
	else { $f_dir_title = $direct_cachedata['output_dir']['title']; }

	if ($direct_cachedata['output_dir']['marked']) { $f_return = "<div id='swgAJAX_datacenter_selector_icons_{$direct_cachedata['output_tid']}_point' style='text-align:left'><p class='pagecontenttitle'>$f_dir_title (".(direct_local_get ("datacenter_target_directory_selected")).")</p>"; }
	else { $f_return = "<div id='swgAJAX_datacenter_selector_icons_{$direct_cachedata['output_tid']}_point' style='text-align:left'><p class='pagecontenttitle'>$f_dir_title</p>"; }

	if ($direct_cachedata['output_dir']['desc']) { $f_return .= "\n<p class='pagecontent'>{$direct_cachedata['output_dir']['desc']}</p>"; }

	if (isset ($direct_cachedata['output_dir_levelup']))
	{
		$f_return .= "<p class='pageborder2' style='text-align:left'>";
		if ($direct_cachedata['output_dir_levelup']['icon']) { $f_return .= "<img src='{$direct_cachedata['output_dir_levelup']['icon']}' border='0' alt='' title='' style='float:left;padding-right:5px' />"; }

		if (strlen ($direct_cachedata['output_dir_levelup']['title_alt'])) { $f_dir_title = $direct_cachedata['output_dir_levelup']['title_alt']; }
		else { $f_dir_title = $direct_cachedata['output_dir_levelup']['title']; }

		if ($direct_cachedata['output_dir_levelup']['pageurl']) { $f_return .= "<span class='pageextracontent' style='font-weight:bold'><a href=\"{$direct_cachedata['output_dir_levelup']['pageurl']}\" target='_self'>$f_dir_title</a></span>"; }
		else { $f_return .= "<span class='pageextracontent' style='font-weight:bold'>$f_dir_title</span>"; }

		if ($direct_cachedata['output_dir_levelup']['desc']) { $f_return .= "<br />\n<span class='pageextracontent' style='font-size:10px'>{$direct_cachedata['output_dir_levelup']['desc']}</span>"; }
		$f_return .= "</p>";
	}

	if (empty ($direct_cachedata['output_objects'])) { $f_return .= "\n<p class='pagecontent' style='font-weight:bold'>".(direct_local_get ("datacenter_icons_list_empty"))."</p>"; }
	else
	{
		if ($direct_cachedata['output_pages'] > 1) { $f_return .= "\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page'],"asis"))."</span></p>\n"; }
		$f_return .= direct_datacenter_oset_selector_icons_parse ($direct_cachedata['output_objects']);
		if ($direct_cachedata['output_pages'] > 1) { $f_return .= "\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page'],"asis"))."</span></p>"; }
	}

	return $f_return."</div>";
}

//f// direct_output_oset_datacenter_embedded_selector ()
/**
* direct_output_oset_datacenter_embedded_selector ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_output_oset_datacenter_embedded_selector ()
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_output_oset_datacenter_embedded_selector ()- (#echo(__LINE__)#)"); }

	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_datacenter.php");

	if (strlen ($direct_cachedata['output_dir']['title_alt'])) { $f_dir_title = $direct_cachedata['output_dir']['title_alt']; }
	else { $f_dir_title = $direct_cachedata['output_dir']['title']; }

	if ($direct_cachedata['output_dir']['marked']) { $f_return = "<p class='pagecontenttitle'>$f_dir_title (".(direct_local_get ("datacenter_target_directory_selected")).")</p>"; }
	else { $f_return = "<p class='pagecontenttitle'>$f_dir_title</p>"; }

	if ($direct_cachedata['output_dir']['desc']) { $f_return .= "\n<p class='pagecontent'>{$direct_cachedata['output_dir']['desc']}</p>"; }

	if (isset ($direct_cachedata['output_dir_levelup']))
	{
		$f_return .= "<p class='pageborder2' style='text-align:left'>";
		if ($direct_cachedata['output_dir_levelup']['icon']) { $f_return .= "<img src='{$direct_cachedata['output_dir_levelup']['icon']}' border='0' alt='' title='' style='float:left;padding-right:5px' />"; }

		if (strlen ($direct_cachedata['output_dir_levelup']['title_alt'])) { $f_dir_title = $direct_cachedata['output_dir_levelup']['title_alt']; }
		else { $f_dir_title = $direct_cachedata['output_dir_levelup']['title']; }

		if ($direct_cachedata['output_dir_levelup']['pageurl']) { $f_return .= "<span class='pageextracontent' style='font-weight:bold'><a href=\"{$direct_cachedata['output_dir_levelup']['pageurl']}\">$f_dir_title</a></span>"; }
		else { $f_return .= "<span class='pageextracontent' style='font-weight:bold'>$f_dir_title</span>"; }

		if ($direct_cachedata['output_dir_levelup']['desc']) { $f_return .= "<br />\n<span class='pageextracontent' style='font-size:10px'>{$direct_cachedata['output_dir_levelup']['desc']}</span>"; }
		$f_return .= "</p>";
	}

	if (empty ($direct_cachedata['output_objects'])) { $f_return .= "\n<p class='pagecontent' style='font-weight:bold'>".(direct_local_get ("datacenter_list_empty"))."</p>"; }
	else
	{
		if ($direct_cachedata['output_pages'] > 1) { $f_return .= "\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page']))."</span></p>\n"; }
		$f_return .= direct_datacenter_oset_objects_parse ($direct_cachedata['output_objects'],false);
		if ($direct_cachedata['output_pages'] > 1) { $f_return .= "\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page']))."</span></p>"; }
	}

	return $f_return;
}

//f// direct_output_oset_datacenter_embedded_selector_icons ()
/**
* direct_output_oset_datacenter_embedded_selector_icons ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_output_oset_datacenter_embedded_selector_icons ()
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_output_oset_datacenter_embedded_selector_icons ()- (#echo(__LINE__)#)"); }

	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_datacenter.php");

	if (strlen ($direct_cachedata['output_dir']['title_alt'])) { $f_dir_title = $direct_cachedata['output_dir']['title_alt']; }
	else { $f_dir_title = $direct_cachedata['output_dir']['title']; }

	if ($direct_cachedata['output_dir']['marked']) { $f_return = "<p class='pagecontenttitle'>$f_dir_title (".(direct_local_get ("datacenter_target_directory_selected")).")</p>"; }
	else { $f_return = "<p class='pagecontenttitle'>$f_dir_title</p>"; }

	if ($direct_cachedata['output_dir']['desc']) { $f_return .= "\n<p class='pagecontent'>{$direct_cachedata['output_dir']['desc']}</p>"; }

	if (isset ($direct_cachedata['output_dir_levelup']))
	{
		$f_return .= "<p class='pageborder2' style='text-align:left'>";
		if ($direct_cachedata['output_dir_levelup']['icon']) { $f_return .= "<img src='{$direct_cachedata['output_dir_levelup']['icon']}' border='0' alt='' title='' style='float:left;padding-right:5px' />"; }

		if (strlen ($direct_cachedata['output_dir_levelup']['title_alt'])) { $f_dir_title = $direct_cachedata['output_dir_levelup']['title_alt']; }
		else { $f_dir_title = $direct_cachedata['output_dir_levelup']['title']; }

		if ($direct_cachedata['output_dir_levelup']['pageurl']) { $f_return .= "<span class='pageextracontent' style='font-weight:bold'><a href=\"{$direct_cachedata['output_dir_levelup']['pageurl']}\" target='_self'>$f_dir_title</a></span>"; }
		else { $f_return .= "<span class='pageextracontent' style='font-weight:bold'>$f_dir_title</span>"; }

		if ($direct_cachedata['output_dir_levelup']['desc']) { $f_return .= "<br />\n<span class='pageextracontent' style='font-size:10px'>{$direct_cachedata['output_dir_levelup']['desc']}</span>"; }
		$f_return .= "</p>";
	}

	if (empty ($direct_cachedata['output_objects'])) { $f_return .= "\n<p class='pagecontent' style='font-weight:bold'>".(direct_local_get ("datacenter_icons_list_empty"))."</p>"; }
	else
	{
		if ($direct_cachedata['output_pages'] > 1) { $f_return .= "\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page']))."</span></p>\n"; }
		$f_return .= direct_datacenter_oset_selector_icons_parse ($direct_cachedata['output_objects']);
		if ($direct_cachedata['output_pages'] > 1) { $f_return .= "\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page']))."</span></p>"; }
	}

	return $f_return;
}

//j// Script specific commands

if (!isset ($direct_settings['theme_td_padding'])) { $direct_settings['theme_td_padding'] = "5px"; }

//j// EOF
?>