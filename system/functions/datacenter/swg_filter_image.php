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
* functions/datacenter/swg_filter_image.php
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

//f// direct_datacenter_filter_image_gif ($f_file_path,$f_original = "")
/**
* Tests the magic string for an "image/gif" file.
*
* @param  boolean $f_file_path File path of the potential GIF file
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
function direct_datacenter_filter_image_gif ($f_file_path,$f_original = "")
{
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_filter_image_gif ($f_file_path,$f_original)- (#echo(__LINE__)#)"); }

	$f_return = false;
	$f_file_object = new direct_file_functions ();
	$f_file_object->open ($f_file_path,true,"rb");

	if ($f_file_object->resource_check ())
	{
		$f_magic_string = $f_file_object->read (4);
		$f_file_object->close ();
		if ($f_magic_string == "GIF8") { $f_return = true; }
	}

	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datacenter_filter_image_gif ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
}

//f// direct_datacenter_filter_image_jpeg ($f_file_path,$f_original = "")
/**
* Tests the magic string for an "image/jpeg" file.
*
* @param  boolean $f_file_path File path of the potential JPEG file
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
function direct_datacenter_filter_image_jpeg ($f_file_path,$f_original = "")
{
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_filter_image_jpeg ($f_file_path,$f_original)- (#echo(__LINE__)#)"); }

	$f_return = false;
	$f_file_object = new direct_file_functions ();
	$f_file_object->open ($f_file_path,true,"rb");

	if ($f_file_object->resource_check ())
	{
		$f_magic_string = $f_file_object->read (2);
		$f_file_object->close ();
		if ($f_magic_string == "\xff\xd8") { $f_return = true; }
	}

	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datacenter_filter_image_jpeg ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
}

//f// direct_datacenter_filter_image_png ($f_file_path,$f_original = "")
/**
* Tests the magic string for an "image/png" file.
*
* @param  boolean $f_file_path File path of the potential PNG file
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
function direct_datacenter_filter_image_png ($f_file_path,$f_original = "")
{
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_filter_image_png ($f_file_path,$f_original)- (#echo(__LINE__)#)"); }

	$f_return = false;
	$f_file_object = new direct_file_functions ();
	$f_file_object->open ($f_file_path,true,"rb");

	if ($f_file_object->resource_check ())
	{
		$f_magic_string = $f_file_object->read (4);
		$f_file_object->close ();
		if ($f_magic_string == "\x89PNG") { $f_return = true; }
	}

	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datacenter_filter_image_png ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
}

//j// EOF
?>