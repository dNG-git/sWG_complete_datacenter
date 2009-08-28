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
* Provides the iviewer which calls parser and returns standardized values for
* output.
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

//f// direct_datalinker_datacenter_iviewer ($f_viewer_data,&$f_object)
/**
* This iviewer is responsible for datacenter objects. It will check the read
* rights and return standardized values.
*
* @param  array $f_viewer_data Found iviewer entry
* @param  direct_datalinker &$f_object DataLinker object
* @uses   direct_basic_functions::include_file()
* @uses   direct_datalinker_datacenter()
* @uses   direct_datalinker_datacenter_iviewer_directory()
* @uses   direct_datalinker_datacenter_iviewer_file()
* @uses   direct_datalinker_datacenter_iviewer_link()
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return array Parsed entry (ready for output)
* @since  v0.1.00
*/
function direct_datalinker_datacenter_iviewer ($f_viewer_data,&$f_object)
{
	global $direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datalinker_datacenter_iviewer (+f_viewer_data,+f_object)- (#echo(__LINE__)#)"); }

	$f_return = array ();

	if (isset ($f_viewer_data['handler']))
	{
		if ($direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/datalinker/swg_datacenter.php")) { $f_object_iview =& direct_datalinker_datacenter ($f_object); }
		else { $f_object_iview = NULL; }

		if ($f_object_iview)
		{
			switch ($f_viewer_data['action'])
			{
			case "directory":
			{
				$f_return = direct_datalinker_datacenter_iviewer_directory ($f_viewer_data,$f_object_iview);
				break 1;
			}
			case "file":
			{
				$f_return = direct_datalinker_datacenter_iviewer_file ($f_viewer_data,$f_object_iview);
				break 1;
			}
			case "link":
			{
				$f_return = direct_datalinker_datacenter_iviewer_link ($f_viewer_data,$f_object_iview);
				break 1;
			}
			}
		}
	}

	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datalinker_datacenter_iviewer ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
}

//f// direct_datalinker_datacenter_iviewer_directory ($f_viewer_data,&$f_object)
/**
* iviewer for direct_datacenter objects which are type 1.
*
* @param  array $f_viewer_data Found iviewer entry
* @param  direct_datalinker &$f_object DataLinker object
* @uses   direct_basic_functions::include_file()
* @uses   direct_datalinker_datacenter_iviewer_universal()
* @uses   direct_debug()
* @uses   direct_local_get()
* @uses   direct_local_integration()
* @uses   USE_debug_reporting
* @return array Parsed entry (ready for output)
* @since  v0.1.00
*/
function direct_datalinker_datacenter_iviewer_directory ($f_viewer_data,&$f_object)
{
	if (USE_debug_reporting) { direct_debug (8,"sWG/#echo(__FILEPATH__)# -direct_datalinker_datacenter_iviewer_directory (+f_viewer_data,+f_object)- (#echo(__LINE__)#)"); }
	direct_local_integration ("datacenter");

$f_return = array (
"object_id" => "",
"object_title_type" => direct_local_get ("datacenter_dir"),
"object_title" => direct_local_get ("core_datasub_no_access_title"),
"object_symbol" => "",
"object_desc" => direct_local_get ("core_datasub_no_access"),
"object_entries" => "",
"object_last_username" => "",
"object_last_userpageurl" => "",
"object_last_useravatar" => "",
"object_preview" => "",
"object_content" => "",
"object_last_time" => "",
"object_url" => "",
"object_available" => false,
"object_view_url" => "",
"object_extended_available" => false,
"object_new" => false
);

	return /*#ifdef(DEBUG):direct_debug (9,"sWG/#echo(__FILEPATH__)# -direct_datalinker_datacenter_iviewer_directory ()- (#echo(__LINE__)#)",(:#*/direct_datalinker_datacenter_iviewer_universal ($f_viewer_data,$f_object,$f_return)/*#ifdef(DEBUG):),true):#*/;
}

//f// direct_datalinker_datacenter_iviewer_file ($f_viewer_data,&$f_object)
/**
* iviewer for direct_datacenter objects which are type 2.
*
* @param  array $f_viewer_data Found iviewer entry
* @param  direct_datalinker &$f_object DataLinker object
* @uses   direct_basic_functions::include_file()
* @uses   direct_datalinker_datacenter_iviewer_universal()
* @uses   direct_debug()
* @uses   direct_local_get()
* @uses   direct_local_integration()
* @uses   USE_debug_reporting
* @return array Parsed entry (ready for output)
* @since  v0.1.00
*/
function direct_datalinker_datacenter_iviewer_file ($f_viewer_data,&$f_object)
{
	if (USE_debug_reporting) { direct_debug (8,"sWG/#echo(__FILEPATH__)# -direct_datalinker_datacenter_iviewer_file (+f_viewer_data,+f_object)- (#echo(__LINE__)#)"); }
	direct_local_integration ("datacenter");

$f_return = array (
"object_id" => "",
"object_title_type" => direct_local_get ("datacenter_object"),
"object_title" => direct_local_get ("core_datasub_no_access_title"),
"object_symbol" => "",
"object_desc" => direct_local_get ("core_datasub_no_access"),
"object_entries" => "",
"object_last_username" => "",
"object_last_userpageurl" => "",
"object_last_useravatar" => "",
"object_preview" => "",
"object_content" => "",
"object_last_time" => "",
"object_url" => "",
"object_available" => false,
"object_view_url" => "",
"object_extended_available" => false,
"object_new" => false
);

	return /*#ifdef(DEBUG):direct_debug (9,"sWG/#echo(__FILEPATH__)# -direct_datalinker_datacenter_iviewer_file ()- (#echo(__LINE__)#)",(:#*/direct_datalinker_datacenter_iviewer_universal ($f_viewer_data,$f_object,$f_return)/*#ifdef(DEBUG):),true):#*/;
}

//f// direct_datalinker_datacenter_iviewer_link ($f_viewer_data,&$f_object)
/**
* iviewer for direct_datacenter objects which are type 3.
*
* @param  array $f_viewer_data Found iviewer entry
* @param  direct_datalinker &$f_object DataLinker object
* @uses   direct_basic_functions::include_file()
* @uses   direct_datalinker_datacenter_iviewer_universal()
* @uses   direct_debug()
* @uses   direct_local_get()
* @uses   direct_local_integration()
* @uses   USE_debug_reporting
* @return array Parsed entry (ready for output)
* @since  v0.1.00
*/
function direct_datalinker_datacenter_iviewer_link ($f_viewer_data,&$f_object)
{
	if (USE_debug_reporting) { direct_debug (8,"sWG/#echo(__FILEPATH__)# -direct_datalinker_datacenter_iviewer_link (+f_viewer_data,+f_object)- (#echo(__LINE__)#)"); }
	direct_local_integration ("datacenter");

$f_return = array (
"object_id" => "",
"object_title_type" => direct_local_get ("datacenter_link"),
"object_title" => direct_local_get ("core_datasub_no_access_title"),
"object_symbol" => "",
"object_desc" => direct_local_get ("core_datasub_no_access"),
"object_entries" => "",
"object_last_username" => "",
"object_last_userpageurl" => "",
"object_last_useravatar" => "",
"object_preview" => "",
"object_content" => "",
"object_last_time" => "",
"object_url" => "",
"object_available" => false,
"object_view_url" => "",
"object_extended_available" => false,
"object_new" => false
);

	return /*#ifdef(DEBUG):direct_debug (9,"sWG/#echo(__FILEPATH__)# -direct_datalinker_datacenter_iviewer_link ()- (#echo(__LINE__)#)",(:#*/direct_datalinker_datacenter_iviewer_universal ($f_viewer_data,$f_object,$f_return)/*#ifdef(DEBUG):),true):#*/;
}

//f// direct_datalinker_datacenter_iviewer_universal ($f_viewer_data,&$f_object,$f_data)
/**
* iviewer for all direct_datacenter objects.
*
* @param  array $f_viewer_data Found iviewer entry
* @param  direct_datalinker &$f_object DataLinker object
* @param  array $f_data Preset return data
* @uses   direct_basic_functions::include_file()
* @uses   direct_datacenter::get()
* @uses   direct_datacenter::is_readable()
* @uses   direct_datacenter::parse()
* @uses   direct_debug()
* @uses   direct_linker()
* @uses   direct_local_get()
* @uses   direct_local_integration()
* @uses   USE_debug_reporting
* @return mixed Parsed entry (ready for output); false on error / missing
*         rights
* @since  v0.1.00
*/
function direct_datalinker_datacenter_iviewer_universal ($f_viewer_data,&$f_object,$f_data)
{
	global $direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datalinker_datacenter_iviewer_universal (+f_viewer_data,+f_object,+f_data)- (#echo(__LINE__)#)"); }

	$f_return =& $f_data;

	if (isset ($f_viewer_data['handler']))
	{
		$f_object_array = (($direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter_home.php")) ? $f_object->get () : NULL);
		$f_parent_array = NULL;
		$f_subs_check = false;

		if (is_array ($f_object_array))
		{
			$f_parent_object = ((strpos ($f_object_array['ddbdatalinker_id_main'],"u-") === 0) ? new direct_datacenter_home () : new direct_datacenter ());

			if ($f_object_array['ddbdatalinker_id_main'])
			{
				if ($f_parent_object) { $f_parent_array = $f_parent_object->get ($f_object_array['ddbdatalinker_id_main']); }
			}
			else { $f_subs_check = true; }
		}

		if ((!$f_subs_check)&&(!is_array ($f_parent_array))) { $f_return['object_desc'] = direct_local_get ("errors_datacenter_did_invalid"); }
		elseif ($f_object->is_readable ())
		{
			$f_parsed_array = $f_object->parse ("m=datacenter&s=media&a=[a]&dsd=[oid][page]");

			$f_return['object_id'] = $f_parsed_array['oid'];
			$f_return['object_title'] = $f_parsed_array['title'];

			if (($direct_settings['datalinker_datacenter_symbols'])&&($f_viewer_data['symbol']))
			{
				$f_symbol_path = $direct_classes['basic_functions']->varfilter ($direct_settings['datalinker_datacenter_path_symbols'],"settings");
				$f_return['object_symbol'] = direct_linker_dynamic ("url0","s=cache&dsd=dfile+".$f_symbol_path.$f_viewer_data['symbol']);
			}

			$f_return['object_desc'] = $f_parsed_array['desc'];
			$f_return['object_last_username'] = $f_parsed_array['username'];
			$f_return['object_last_userpageurl'] = $f_parsed_array['userpageurl'];
			$f_return['object_last_useravatar'] = $f_parsed_array['useravatar_small'];
			$f_return['object_last_time'] = ($f_object_array['ddbdatalinker_sorting_date'] ? $direct_classes['basic_functions']->datetime ("shortdate&time",$f_object_array['ddbdatalinker_sorting_date'],$direct_settings['user']['timezone'],(direct_local_get ("datetime_dtconnect"))) : direct_local_get ("core_unknown"));
			$f_return['object_url'] = $f_parsed_array['pageurl'];
			$f_return['object_available'] = true;

			if ((!$f_subs_check)&&($f_parent_object->is_readable ()))
			{
				$f_parsed_array = $f_parent_object->parse ("m=datacenter&s=media&a=[a]&dsd=[oid][page]");
				$f_return['category_id'] = $f_parsed_array['oid'];
				$f_return['category_title_type'] = direct_local_get ("datacenter_dir");
				$f_return['category_title'] = $f_parsed_array['title'];
				$f_return['category_desc'] = $f_parsed_array['desc'];
				$f_return['category_url'] = $f_parsed_array['pageurl'];
				$f_return['category_new'] = $f_parsed_array['new'];
			}
		}
	}

	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datalinker_datacenter_iviewer_universal ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
}

//j// Script specific commands

if (!isset ($direct_settings['datalinker_datacenter_symbols'])) { $direct_settings['datalinker_datacenter_symbols'] = false; }
if (!isset ($direct_settings['datalinker_datacenter_path_symbols'])) { $direct_settings['datalinker_datacenter_path_symbols'] = $direct_settings['path_themes']."/$direct_settings[theme]/"; }

//j// EOF
?>