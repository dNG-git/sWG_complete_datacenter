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
* functions/datacenter/swg_filter_audio.php
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

//j// Functions and classes

//f// direct_datacenter_filter_audio_mpeg ($f_file_path,$f_original = "")
/**
* Tests the magic string for an "audio/mpeg" file.
*
* @param  boolean $f_file_path File path of the potential MPEG Audio file
* @param  integer $f_original Original file
* @uses   direct_debug()
* @uses   direct_file_functions::close()
* @uses   direct_file_functions::open()
* @uses   direct_file_functions::read()
* @uses   direct_file_functions::resource_check()
* @uses   USE_debug_reporting
* @return boolean True if the file contains the magic string
* @since  v0.1.00
*/
function direct_datacenter_filter_audio_mpeg ($f_file_path,$f_original = "")
{
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_filter_audio_mpeg ($f_file_path,$f_original)- (#echo(__LINE__)#)"); }

	$f_return = false;
	$f_file_object = new direct_file_functions ();
	$f_file_object->open ($f_file_path,true,"rb");

	if ($f_file_object->resource_check ())
	{
		$f_magic_string = $f_file_object->read (2);
		$f_mp3_array = unpack ("H2head/H2head_settings",$f_magic_string);

		if ($f_mp3_array)
		{
			switch ($f_mp3_array['head'])
			{
			case "49":
			{
				$f_magic_string = $f_file_object->read (1);
				if (($f_mp3_array['head_settings'] == "44")&&($f_magic_string == "3")) { $f_return = true; }
				break 1;
			}
			case "54":
			{
				$f_magic_string = $f_file_object->read (1);
				if (($f_mp3_array['head_settings'] == "41")&&($f_magic_string == "G")) { $f_return = true; }
				break 1;
			}
			case "ff":
			{
				if (($f_mp3_array['head_settings'][0] == "e")||($f_mp3_array['head_settings'][0] == "f"))
				{
					$f_binary = base_convert ($f_mp3_array['head_settings'],16,2);
					$f_binary_layer = substr ($f_binary,6,2);
					$f_binary = $f_binary[5];

					if (((($f_mp3_array['head_settings'][0] == "e")&&($f_binary == "0"))||($f_mp3_array['head_settings'][0] == "f"))&&(($f_binary_layer == "11")||($f_binary_layer == "10")||($f_binary_layer == "01"))) { $f_return = true; }
				}

				break 1;
			}
			}
		}

		$f_file_object->close ();
	}

	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datacenter_filter_application_xipk ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
}

//j// EOF
?>