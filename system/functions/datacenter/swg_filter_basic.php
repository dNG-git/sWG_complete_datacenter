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
* functions/datacenter/swg_filter_basic.php
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

//f// direct_datacenter_filter_basic_notesting ($f_file_path,$f_original = "")
/**
* Accepts all files. Security Warning! Do not use this filter in unprotected
* production environments!
*
* @param  boolean $f_file_path File path
* @param  integer $f_original Original file
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return boolean True if the file exist
* @since  v0.1.00
*/
function direct_datacenter_filter_basic_notesting ($f_file_path,$f_original = "")
{
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_filter_basic_notesting ($f_file_path,$f_original)- (#echo(__LINE__)#)"); }
	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datacenter_filter_basic_notesting ()- (#echo(__LINE__)#)",(:#*/file_exists ($f_file_path)/*#ifdef(DEBUG):),true):#*/;
}

//f// direct_datacenter_filter_basic_textfiletypes ($f_file_path,$f_original)
/**
* Checks for text files (including UTF-8 ones).
*
* @param  boolean $f_file_path File path of the potential text file
* @param  integer $f_original Original file
* @uses   direct_debug()
* @uses   direct_file_get()
* @uses   USE_debug_reporting
* @return boolean True if the file was edited successfully
* @since  v0.1.00
*/
function direct_datacenter_filter_basic_textfiletypes ($f_file_path,$f_original)
{
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_filter_basic_textfiletypes ($f_file_path,$f_original)- (#echo(__LINE__)#)"); }

	$f_return = false;
	$f_file_data = direct_file_get ("s0",$f_file_path);
	if (is_string ($f_file_data)) { $f_return = preg_match ("#^([\\x00-\\x7F]|[\\xC2-\\xDF][\\x80-\\xBF]|[\\xE0-\\xEF][\\x80-\\xBF]{2}|[\\xF0-\\xF4][\\x80-\\xBF]{3})*$#",$f_file_data); }

	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datacenter_filter_basic_textfiletypes ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
}

//j// EOF
?>