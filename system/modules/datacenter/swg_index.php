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
#echo(sWGdatacenterVersion)#
sWG/#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* datacenter/swg_index.php
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

//j// Script specific commands

if (!isset ($direct_settings['datacenter_path_upload'])) { $direct_settings['datacenter_path_upload'] = $direct_settings['path_data']."/uploads/"; }
if (!isset ($direct_settings['swg_content_modification_check'])) { $direct_settings['swg_content_modification_check'] = true; }

//j// BOS
switch ($direct_settings['a'])
{
//j// $direct_settings['a'] == "transfer"
case "transfer":
case "transfer_dl":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }

	$g_oid = (isset ($direct_settings['dsd']['doid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['doid'])) : "");

	$direct_cachedata['page_this'] = "m=datacenter&a=transfer&dsd=doid+".$g_oid;
	$direct_cachedata['page_backlink'] = "";
	$direct_cachedata['page_homelink'] = "";

	$g_header_check = true;

	if ($direct_classes['kernel']->service_init_rboolean ())
	{
	if ($direct_settings['datacenter'])
	{
	//j// BOA
	if (($direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter.php"))&&($direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_datacenter_downloads.php"))&&(direct_class_init ("output")))
	{
		$g_header_check = false;

		if (isset ($_SERVER['HTTP_IF_MODIFIED_SINCE'])) { $g_client_last_modified = strtotime ($_SERVER['HTTP_IF_MODIFIED_SINCE']); }
		else { $g_client_last_modified = -1; }

		if ((isset ($_SERVER['HTTP_RANGE']))&&(preg_match ("#^bytes(.*?)=(.*?)-(.*?)$#i",$_SERVER['HTTP_RANGE'],$g_result_array)))
		{
			$g_bytes_offset = preg_replace ("#(\D+)#","",$g_result_array[2]);
			$g_bytes_offset_end = preg_replace ("#(\D+)#","",$g_result_array[3]);
		}
		else
		{
			$g_bytes_offset = 0;
			$g_bytes_offset_end = NULL;
		}

		$g_datacenter_object = new direct_datacenter ();
		$g_datacenter_object->get ($g_oid);

		if ($direct_settings['a'] == "transfer_dl") { direct_datacenter_downloads_handle ($g_datacenter_object,$g_bytes_offset,$g_bytes_offset_end,$g_client_last_modified); }
		else { direct_datacenter_downloads_handle ($g_datacenter_object,$g_bytes_offset,$g_bytes_offset_end,$g_client_last_modified,false); }
	}
	else { header ("HTTP/1.1 500 Internal Server Error"); }
	//j// EOA
	}
	else
	{
		direct_class_init ("output");
		header ("HTTP/1.1 403 Forbidden");
	}
	}
	else
	{
		direct_class_init ("output");
		header ("HTTP/1.1 500 Internal Server Error");
	}

	if ($g_header_check) { $direct_classes['output']->header (true,false); }

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// EOS
}

//j// EOF
?>