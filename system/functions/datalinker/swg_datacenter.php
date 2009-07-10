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
* Standardized interface to get objects based on a given DataLinker entry.
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

//f// direct_datalinker_datacenter (&$f_object)
/**
* Returns the object for the requested DataLinker type.
*
* @param  direct_datalinker &$f_object DataLinker object
* @uses   direct_basic_functions::include_file()
* @uses   direct_basic_functions::settings_get()
* @uses   direct_debug()
* @uses   direct_datacenter::get()
* @uses   USE_debug_reporting
* @return mixed Object on success; false on error
* @since  v0.1.00
*/
function &direct_datalinker_datacenter (&$f_object)
{
	global $direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datalinker_datacenter (+f_object)- (#echo(__LINE__)#)"); }

	$f_return = false;

	if (is_object ($f_object))
	{
		$f_continue_check = $direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_datacenter.php");
		$f_object_array = $f_object->get ();

		if (($f_object_array)&&($f_continue_check)&&(isset ($f_object_array['ddbdatalinker_id'])))
		{
			$f_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter.php");
			if ($f_continue_check) { $f_return = new direct_datacenter (); }

			if ($f_return)
			{
				if (!$f_return->get ($f_object_array['ddbdatalinker_id'])) { $f_return = false; }
			}
		}
	}

	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datalinker_datacenter ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
}

//j// EOF
?>