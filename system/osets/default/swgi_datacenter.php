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
$Id: swgi_datacenter.php,v 1.1 2009/03/13 16:13:07 s4u Exp $
#echo(sWGdatacenterVersion)#
sWG/#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* osets/default/swgi_datacenter.php
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

//f// direct_datacenter_oset_object_parse ($f_object)
/**
* direct_datacenter_oset_object_parse ()
*
* @param  array $f_object DataCenter object
* @uses   direct_debug()
* @uses   direct_local_get()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_datacenter_oset_object_parse ($f_object)
{
	global $direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_oset_object_parse (+f_object)- (#echo(__LINE__)#)"); }

	$f_return = "";

	if (!empty ($f_object))
	{
$f_return = ("<table cellspacing='1' summary='' class='pageborder1' style='width:100%;table-layout:auto'>
<thead class='pagehide'><tr>
<td colspan='2' align='left' class='pagetitlecellbg' style='padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>".(direct_local_get ("datacenter_object"))."</span></td>
</tr></thead><tbody><tr>
<td valign='top' align='right' class='pageextrabg' style='width:25%;padding:$direct_settings[theme_form_td_padding]'><span class='pageextracontent' style='font-weight:bold'>".(direct_local_get ("datacenter_object")).":</span></td>
<td valign='middle' align='center' class='pagebg' style='width:75%;padding:$direct_settings[theme_form_td_padding]'><span class='pagecontent'>");

		if ($f_object['icon']) { $f_return .= "<img src='{$f_object['icon']}' border='0' alt='' title='' /><br />"; }

		if (strlen ($f_object['title_alt'])) { $f_object_title = $f_object['title_alt']; }
		else { $f_object_title = $f_object['title']; }

		if (($f_object['dir'])&&($f_object['pageurl'])) { $f_return .= "<a href=\"{$f_object['pageurl']}\" target='_self'>$f_object_title</a>"; }
		else { $f_return .= $f_object_title; }

$f_return .= ("</span></td>
</tr><tr>
<td valign='middle' align='right' class='pageextrabg' style='width:25%;padding:$direct_settings[theme_form_td_padding]'><span class='pageextracontent' style='font-size:10px;font-weight:bold'>".(direct_local_get ("datacenter_type")).":</span></td>
<td valign='middle' align='center' class='pagebg' style='width:75%;padding:$direct_settings[theme_form_td_padding]'><span class='pagecontent' style='font-size:10px'>{$f_object['type']}</span></td>
</tr><tr>
<td valign='middle' align='right' class='pageextrabg' style='width:25%;padding:$direct_settings[theme_form_td_padding]'><span class='pageextracontent' style='font-size:10px;font-weight:bold'>".(direct_local_get ("datacenter_lastedit")).":</span></td>
<td valign='middle' align='center' class='pagebg' style='width:75%;padding:$direct_settings[theme_form_td_padding]'><span class='pagecontent' style='font-size:10px'>{$f_object['time']}</span></td>
</tr><tr>
<td valign='middle' align='right' class='pageextrabg' style='width:25%;padding:$direct_settings[theme_form_td_padding]'><span class='pageextracontent' style='font-size:10px;font-weight:bold'>".(direct_local_get ("datacenter_size")).":</span></td>
<td valign='middle' align='center' class='pagebg' style='width:75%;padding:$direct_settings[theme_form_td_padding]'><span class='pagecontent' style='font-size:10px'>{$f_object['size']} ".(direct_local_get ("datacenter_bytes"))."</span></td>
</tr>");

		if ($f_object['desc'])
		{
$f_return .= ("<tr>
<td valign='top' align='right' class='pageextrabg' style='width:25%;padding:$direct_settings[theme_form_td_padding]'><span class='pageextracontent' style='font-weight:bold'>".(direct_local_get ("datacenter_desc")).":</span></td>
<td valign='middle' align='center' class='pagebg' style='width:75%;padding:$direct_settings[theme_form_td_padding]'>
<table border='0' cellspacing='0' cellpadding='0' summary=''>
<tbody><tr>
<td align='left'><div class='pagecontent'>{$f_object['desc']}</div></td>
</tr></tbody>
</table>
</td>
</tr>");
		}

		$f_return .= "</tbody>\n</table>";
	}

	return $f_return;
}

//f// direct_datacenter_oset_objects_parse ($f_objects,$f_link_files = true)
/**
* direct_datacenter_oset_object_parse ()
*
* @param  array $f_object DataCenter objects
* @param  boolean $f_link_files Link file objects (to view them)
* @uses   direct_debug()
* @uses   direct_linker_dynamic()
* @uses   direct_local_get()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_datacenter_oset_objects_parse ($f_objects,$f_link_files = true)
{
	global $direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_oset_objects_parse (+f_objects,+f_link_files)- (#echo(__LINE__)#)"); }

	$f_return = "";

	if (!empty ($f_objects))
	{
$f_return = ("<table cellspacing='1' summary='' class='pageborder1' style='width:100%'>
<thead><tr>
<td valign='middle' align='center' class='pagetitlecellbg' style='width:60%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>".(direct_local_get ("datacenter_object"))."</span></td>
<td valign='middle' align='center' class='pagetitlecellbg' style='width:25%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent' style='font-size:10px'>".(direct_local_get ("datacenter_lastedit"))."</span></td>
<td valign='middle' align='center' class='pagetitlecellbg' style='width:15%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent' style='font-size:10px'>".(direct_local_get ("datacenter_size"))."</span></td>
</tr></thead><tbody>");

		foreach ($f_objects as $f_object_array)
		{
			if ($f_object_array['marked']) { $f_css_class = "extra"; }
			else { $f_css_class = ""; }

$f_return .= ("<tr>
<td valign='middle' align='left' class='page{$f_css_class}bg' style='width:60%;padding:$direct_settings[theme_td_padding]'><a id=\"{$f_object_array['id']}\" name=\"{$f_object_array['id']}\"></a><span class='page{$f_css_class}content'>");

			if ($f_object_array['icon']) { $f_return .= "<img src='{$f_object_array['icon']}' border='0' alt='' title='' style='float:left;padding-right:5px' />"; }

			if (($direct_settings['datacenter_marker_use_imagebuttons'])&&($f_object_array['pageurl_marker']))
			{
				if ($f_object_array['marked']) { $f_return .= "<a href=\"{$f_object_array['pageurl_marker']}\" target='_self'><img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+data/themes/$direct_settings[theme]/mini_datacenter_object_unmark.png",true,false))."' border='0' alt='' title='' style='float:left' /></a>"; }
				else { $f_return .= "<a href=\"{$f_object_array['pageurl_marker']}\" target='_self'><img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+data/themes/$direct_settings[theme]/mini_datacenter_object_mark.png",true,false))."' border='0' alt='' title='' style='float:left' /></a>"; }
			}

			if (strlen ($f_object_array['title_alt'])) { $f_object_title = $f_object_array['title_alt']; }
			else { $f_object_title = $f_object_array['title']; }

			if (($f_link_files)&&($f_object_array['pageurl'])) { $f_return .= "<span style='font-weight:bold'><a href=\"{$f_object_array['pageurl']}\" target='_self'>$f_object_title</a></span>"; }
			else
			{
				if (($f_object_array['pageurl'])&&($f_object_array['dir'])) { $f_return .= "<span style='font-weight:bold'><a href=\"{$f_object_array['pageurl']}\" target='_self'>$f_object_title</a></span>"; }
				else { $f_return .= "<span style='font-weight:bold'>$f_object_title</span>"; }
			}

			if ($f_object_array['type']) { $f_return .= " <span style='font-size:10px'>({$f_object_array['type']})</span>"; }
			if ((!$direct_settings['datacenter_marker_use_imagebuttons'])&&($f_object_array['pageurl_marker'])) { $f_return .= " <span style='font-size:10px'>(<a href=\"{$f_object_array['pageurl_marker']}\" target='_self'>{$f_object_array['marker_title']}</a>)</span>"; }

			if ($f_object_array['desc']) { $f_return .= "<br />\n<span style='font-size:10px'>{$f_object_array['desc']}</span>"; }

$f_return .= ("</span></td>
<td valign='middle' align='center' class='pageextrabg' style='width:25%;padding:$direct_settings[theme_td_padding]'><span class='pageextracontent' style='font-size:10px'>{$f_object_array['time']}</span></td>
<td valign='middle' align='center' class='pageextrabg' style='width:25%;padding:$direct_settings[theme_td_padding]'><span class='pageextracontent' style='font-size:10px'>{$f_object_array['size']} ".(direct_local_get ("datacenter_bytes"))."</span></td>
</tr>");
		}

		$f_return .= "</tbody>\n</table>";
	}

	return $f_return;
}

//f// direct_datacenter_oset_selector_icons_parse ($f_objects)
/**
* direct_datacenter_oset_object_parse ()
*
* @param  array $f_object DataCenter icon objects
* @uses   direct_debug()
* @uses   direct_local_get()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_datacenter_oset_selector_icons_parse ($f_objects)
{
	global $direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_output_oset_datacenter_embedded_selector_icons ()- (#echo(__LINE__)#)"); }

	$f_return = "";

	if (!empty ($f_objects))
	{
$f_return = ("<table cellspacing='1' summary='' class='pageborder1' style='width:100%;table-layout:auto'>
<thead class='pagehide'><tr>
<td colspan='2' align='left' class='pagetitlecellbg' style='padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>".(direct_local_get ("datacenter_object"))."</span></td>
</tr></thead><tbody>");

		foreach ($f_objects as $f_object_array)
		{
			if ($f_object_array['marked']) { $f_css_class = "extra"; }
			else { $f_css_class = ""; }

			if (isset ($f_right_switch))
			{
				if ($f_right_switch)
				{
					$f_return .= "</td>\n<td valign='middle' align='center' class='page{$f_css_class}bg' style='width:50%;padding:$direct_settings[theme_td_padding]'>";
					$f_right_switch = false;
				}
				else
				{
					$f_return .= "</td>\n</tr><tr>\n<td valign='middle' align='center' class='page{$f_css_class}bg' style='width:50%;padding:$direct_settings[theme_td_padding]'>";
					$f_right_switch = true;
				}
			}
			else
			{
				$f_return .= "\n<tr>\n<td valign='middle' align='center' class='page{$f_css_class}bg' style='width:50%;padding:$direct_settings[theme_td_padding]'>";
				$f_right_switch = true;
			}

			$f_return .= "<a id=\"{$f_object_array['id']}\" name=\"{$f_object_array['id']}\"></a><span class='page{$f_css_class}content'>";

			if (strlen ($f_object_array['title_alt'])) { $f_object_title = $f_object_array['title_alt']; }
			else { $f_object_title = $f_object_array['title']; }

			if (($f_object_array['type'] == "image/gif")||($f_object_array['type'] == "image/jpeg")||($f_object_array['type'] == "image/png")||($f_object_array['type'] == "image/svg+xml")) { $f_return .= "<img src='{$f_object_array['icon']}' border='0' alt=\"$f_object_title\" title=\"$f_object_title\" />"; }
			else
			{
				if (($f_object_array['dir'])&&($f_object_array['pageurl'])) { $f_return .= "<a href=\"{$f_object_array['pageurl']}\" target='_self'>$f_object_title</a>"; }
				else { $f_return .= $f_object_title; }

				if (isset ($f_object_array['icon'])) { $f_return .= "<br />\n<img src='{$f_object_array['icon']}' border='0' alt=\"$f_object_title\" title=\"$f_object_title\" />"; }
			}

			$f_return .= "<br />\n<span style='font-size:10px'><a href=\"{$f_object_array['pageurl_marker']}\" target='_self'>{$f_object_array['marker_title']}</a></span></span>";
		}

		if ($f_right_switch) { $f_return .= "</td>\n<td class='page{$f_css_class}bg' style='width:50%'><span style='font-size:8px'>&#0160;</span></td>\n</tr></tbody>\n</table>"; }
		else { $f_return .= "</td>\n</tr></tbody>\n</table>"; }
	}

	return $f_return;
}

//j// Script specific commands

if (!isset ($direct_settings['theme_td_padding'])) { $direct_settings['theme_td_padding'] = "5px"; }
if (!isset ($direct_settings['theme_form_td_padding'])) { $direct_settings['theme_form_td_padding'] = "3px"; }

//j// EOF
?>