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
* functions/swg_datacenter_downloads.php
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

//f// direct_datacenter_downloads_handle (&$f_datacenter_object,$f_bytes_offset = 0,$f_bytes_offset_end = NULL,$f_client_last_modified = -1,$f_download = true,$f_output_headers = true,$f_output_data = true)
/**
* Handle a download request.
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return array Available filters
* @since  v0.1.00
*/
function direct_datacenter_downloads_handle (&$f_datacenter_object,$f_bytes_offset = 0,$f_bytes_offset_end = NULL,$f_client_last_modified = -1,$f_download = true,$f_output_headers = true,$f_output_data = true)
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_downloads_handle (+f_datacenter_object,$f_bytes_offset,+f_bytes_offset_end,$f_client_last_modified,+f_download,+f_output_headers,+f_output_data)- (#echo(__LINE__)#)"); }

	$f_return = array ();

	if ($f_output_data) { $f_output_headers = true; }
	if (!$f_output_headers) { $f_output_data = false; }
	$f_datacenter_array = ($f_datacenter_object ? $f_datacenter_object->get () : NULL);

	if (!is_array ($f_datacenter_array)) { $f_return = array (404,"HTTP/1.1 404 Not Found"); }
	elseif ($f_datacenter_object->is_readable ())
	{
		if ($f_output_data) { ob_start (); }

		if ($f_datacenter_object->is_physical ()) { $f_file_path = $f_datacenter_object->get_plocation (); }
		else
		{
			$f_file_path = $direct_classes['basic_functions']->varfilter ($direct_settings['datacenter_path_upload'],"settings");
			$f_file_path = $f_datacenter_object->get_plocation ($f_file_path);
		}

		if ((strlen ($f_file_path))&&(file_exists ($f_file_path)))
		{
			$f_modification_check = true;
			$f_server_last_modified = filemtime ($f_file_path);

			if (($direct_settings['swg_content_modification_check'])&&($f_client_last_modified > -1)&&($f_server_last_modified <= $f_client_last_modified))
			{
				$f_return = array (304,"HTTP/1.1 304 Not Modified");
				$f_modification_check = false;
			}
		}
		else { $f_return = array (404,"HTTP/1.1 404 Not Found"); }

		if ((empty ($f_return))&&($f_modification_check))
		{
			if ($direct_settings['swg_content_modification_check']) { $direct_classes['output']->last_modified ($f_server_last_modified); }
			$f_bytes_unread = filesize ($f_file_path);

			if (isset ($f_bytes_offset_end))
			{
				$f_partitial_check = false;

				if ($f_bytes_offset)
				{
					if ($f_bytes_offset_end)
					{
						if (($f_bytes_offset >= 0)&&($f_bytes_offset <= $f_bytes_offset_end)&&($f_bytes_offset_end < $f_bytes_unread)) { $f_partitial_check = true; }
					}
					elseif (($f_bytes_offset >= 0)&&($f_bytes_offset < $f_bytes_unread))
					{
						$f_partitial_check = true;
						$f_bytes_offset_end = ($f_bytes_unread - 1);
					}
				}

				if ($f_partitial_check)
				{
					if ($f_output_headers)
					{
						header ("HTTP/1.1 206 Partial Content");
						header ("Content-Range: {$f_bytes_offset}-{$f_bytes_offset_end}/".$f_bytes_unread);
					}
/* -------------------------------------------------------------------------
We are working with offsets. Calculate the correct size to be read.
------------------------------------------------------------------------- */

					$f_bytes_unread = (1 + $f_bytes_offset_end - $f_bytes_offset);
				}
			}

			if (($f_bytes_unread < 1)||($f_bytes_unread > (1024 * $direct_settings['datacenter_downloads_sizeperchunk']))) { $f_return = array (416,"HTTP/1.1 416 Requested Range Not Satisfiable"); }
			else
			{
				$f_file_object = new direct_file_functions ();
				$f_file_object->open ($f_file_path,true,"rb");

				if ($f_file_object->resource_check ())
				{
					header ("Content-Length: ".$f_bytes_unread);

					if ($f_bytes_offset) { $f_file_object->seek ($f_bytes_offset); }
					$f_return = "";
					$f_timeout_time = ($direct_cachedata['core_time'] + $direct_settings['timeout'] + $direct_settings['timeout_core']);

					while (($f_bytes_unread > 0)&&(!$f_file_object->eof_check ())&&($f_timeout_time > (time ())))
					{
						$f_part_size = (($f_bytes_unread > 4096) ? 4096 : $f_bytes_unread);

						if ($f_output_data) { echo $f_file_object->read ($f_part_size); }
						else { $f_return .= $f_file_object->read ($f_part_size); }

						$f_bytes_unread -= $f_part_size;
					}

					$f_eof_check = (($f_bytes_unread > 0) ? false : true);

					if ($f_eof_check)
					{
						if ($f_output_headers)
						{
							header ("Content-Type: ".$f_datacenter_array['ddbdatacenter_type']);

							if ($f_download)
							{
								$f_file_array = pathinfo ($f_file_path);
								header ("Content-Disposition: attachment; filename=\"".(str_replace ('"','\"',$f_file_array['basename']))."\"");
							}

							$direct_classes['output']->header (true,false);
							$f_output_headers = false;
						}

						if ($f_output_data) { ob_end_flush (); }
					}
					elseif ($f_output_headers)
					{
						if ($f_output_data) { ob_end_clean (); }
						header ("HTTP/1.1 504 Gateway Timeout");
						header ("Content-Length: 0");
					}
					else { $f_return = array (504,"HTTP/1.1 504 Gateway Timeout"); }

					$f_file_object->close ();
				}
				else { $f_return = array (500,"HTTP/1.1 500 Internal Server Error"); }
			}
		}
	}
	else { $f_return = array (403,"HTTP/1.1 403 Forbidden"); }

	if ($f_output_headers)
	{
		if (!empty ($f_return)) { header ($f_return[1]); }
		$direct_classes['output']->header (true,false);
	}

	return $f_return;
}

//j// Script specific commands

if (!isset ($direct_settings['datacenter_downloads_sizeperchunk'])) { $direct_settings['datacenter_downloads_sizeperchunk'] = 16384; }

$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_file_functions.php");

//j// EOF
?>