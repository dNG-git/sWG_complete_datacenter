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

	if ($direct_classes['kernel']->service_init_rboolean ())
	{
	if ($direct_settings['datacenter'])
	{
	//j// BOA
	if (($direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter.php"))&&(direct_class_init ("output")))
	{
		$g_datacenter_object = new direct_datacenter ();
		$g_header_check = true;

		$g_datacenter_array = ($g_datacenter_object ? $g_datacenter_object->get ($g_oid) : NULL);

		if (!is_array ($g_datacenter_array)) { header ("HTTP/1.1 404 Not Found"); }
		elseif ($g_datacenter_object->is_readable ())
		{
			ob_start ();

			if ($g_datacenter_object->is_physical ()) { $g_file_path = $g_datacenter_object->get_plocation (); }
			else
			{
				$g_file_path = $direct_classes['basic_functions']->varfilter ($direct_settings['datacenter_path_upload'],"settings");
				$g_file_path = $g_datacenter_object->get_plocation ($g_file_path);
			}

			if (strlen ($g_file_path))
			{
				$g_continue_check = true;
				$g_modification_check = true;

				if (($direct_settings['swg_content_modification_check'])&&(isset ($_SERVER['HTTP_IF_MODIFIED_SINCE'])))
				{
					$g_client_last_modified = strtotime ($_SERVER['HTTP_IF_MODIFIED_SINCE']);

					if (($g_client_last_modified > -1)&&(file_exists ($g_file_path)))
					{
						$g_server_last_modified = filemtime ($g_file_path);

						if ($g_server_last_modified <= $g_client_last_modified)
						{
							header ("HTTP/1.1 304 Not Modified");
							$g_modification_check = false;
						}
					}
				}
			}
			else { $g_continue_check = false; }

			if (($g_continue_check)&&($g_modification_check))
			{
				if ($direct_settings['swg_content_modification_check'])
				{
					if (!isset ($g_server_last_modified)) { $g_server_last_modified = filemtime ($g_file_path); }
					$direct_classes['output']->last_modified ($g_server_last_modified);
				}

				$g_bytes_offset = 0;
				$g_bytes_offset_end = 0;
				$g_bytes_unread = filesize ($g_file_path);

				if (isset ($_SERVER['HTTP_RANGE']))
				{
					$g_partitial_check = false;

					if (preg_match ("#^bytes(.*?)=(.*?)-(.*?)$#i",$_SERVER['HTTP_RANGE'],$g_result_array))
					{
						$g_bytes_offset = preg_replace ("#(\D+)#","",$g_result_array[2]);
						$g_bytes_offset_end = preg_replace ("#(\D+)#","",$g_result_array[3]);

						if ($g_bytes_offset)
						{
							if ($g_bytes_offset_end)
							{
								if (($g_bytes_offset >= 0)&&($g_bytes_offset <= $g_bytes_offset_end)&&($g_bytes_offset_end < $g_bytes_unread)) { $g_partitial_check = true; }
							}
							elseif (($g_bytes_offset >= 0)&&($g_bytes_offset < $g_bytes_unread))
							{
								$g_partitial_check = true;
								$g_bytes_offset_end = ($g_bytes_unread - 1);
							}
						}

						if ($g_partitial_check)
						{
							header ("HTTP/1.1 206 Partial Content");
							header ("Content-Range: {$g_bytes_offset}-{$g_bytes_offset_end}/".$g_bytes_unread);
/* -------------------------------------------------------------------------
We are working with offsets. Calculate the correct sSize to be read.
------------------------------------------------------------------------- */

							$g_bytes_unread = (1 + $g_bytes_offset_end - $g_bytes_offset);
						}
					}
				}

				$g_file_object = new direct_file_functions ();
				$g_file_object->open ($g_file_path,true,"rb");

				if ($g_file_object->resource_check ())
				{
					header ("Content-Length: ".$g_bytes_unread);

					if ($g_bytes_offset) { $g_file_object->seek ($g_bytes_offset); }
					$g_timeout_time = ($direct_cachedata['core_time'] + $direct_settings['timeout'] + $direct_settings['timeout_core']);

					if ($g_bytes_unread)
					{
						do
						{
							$g_part_size = (($g_bytes_unread > 4096) ? 4096 : $g_bytes_unread);
							echo $g_file_object->read ($g_part_size);
							$g_bytes_unread -= $g_part_size;
						}
						while (($g_bytes_unread > 0)&&(!$g_file_object->eof_check ())&&($g_timeout_time > (time ())));

						$g_eof_check = (($g_bytes_unread > 0) ? false : true);
					}
					else
					{
						while ((!$g_file_object->eof_check ())&&($g_timeout_time > (time ()))) { echo $g_file_object->read (4096); }
						$g_eof_check = $g_file_object->eof_check ();
					}

					if ($g_eof_check)
					{
						header ("Content-Type: ".$g_datacenter_array['ddbdatacenter_type']);

						if ($direct_settings['a'] == "transfer_dl")
						{
							$g_file_array = pathinfo ($g_file_path);
							header ("Content-Disposition: attachment; filename=\"".(str_replace ('"','\"',$g_file_array['basename']))."\"");
						}

						$direct_classes['output']->header (true,false);
						$g_header_check = false;
						ob_end_flush ();
					}
					else
					{
						header ("HTTP/1.1 504 Gateway Timeout");
						header ("Content-Length: 0");
						ob_end_clean ();
					}

					$g_file_object->close ();
				}
				else { header ("HTTP/1.1 500 Internal Server Error"); }
			}
			elseif (!$g_continue_check) { header ("HTTP/1.1 404 Not Found"); }
		}
		else { header ("HTTP/1.1 403 Forbidden"); }
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