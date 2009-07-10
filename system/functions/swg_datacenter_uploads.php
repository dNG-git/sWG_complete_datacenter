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
* functions/swg_datacenter_uploads.php
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

//f// direct_datacenter_uploads_filters_get ()
/**
* Returns the list of available filters.
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return array Available filters
* @since  v0.1.00
*/
function direct_datacenter_uploads_filters_get ()
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_uploads_filters_get ()- (#echo(__LINE__)#)"); }

	if (isset ($direct_cachedata['datacenter_uploads_filterlist_data'])) { $f_return = $direct_cachedata['datacenter_uploads_filterlist_data']; }
	else
	{
		$f_return = array ();

		if (file_exists ($direct_settings['path_data']."/settings/swg_datacenter_filters.xml")) { $f_file_data = $direct_classes['basic_functions']->memcache_get_file ($direct_settings['path_data']."/settings/swg_datacenter_filters.xml"); }
		else { $f_file_data = NULL; }

		if ($f_file_data) { $f_xml_array = $direct_classes['xml_bridge']->xml2array ($f_file_data,true,false); }
		else { $f_xml_array = NULL; }

		if ($f_xml_array)
		{
			if (isset ($f_xml_array['swg_datacenter_filters_file_v1']['xml.item']))
			{
				if (isset ($f_xml_array['swg_datacenter_filters_file_v1']['mimetype']['xml.mtree']))
				{
					$f_xml_array = $f_xml_array['swg_datacenter_filters_file_v1']['mimetype'];
					unset ($f_xml_array['xml.mtree']);
				}
				else
				{
					$f_xml_array = $f_xml_array['swg_datacenter_filters_file_v1'];
					unset ($f_xml_array['xml.item']);
				}

				foreach ($f_xml_array as $f_xml_node_array)
				{
					if (isset ($f_xml_node_array['value'],$f_xml_node_array['attributes']['module'],$f_xml_node_array['attributes']['function'])) { $f_return[$f_xml_node_array['value']] = $f_xml_node_array['attributes']; }
				}

				$direct_cachedata['datacenter_uploads_filterlist_data'] = $f_return;
			}
		}
	}

	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datacenter_uploads_filters_get ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
}

//f// direct_datacenter_uploads_local_check ($f_files,$f_quantity,&$f_result_array)
/**
* Check local files for PHP upload error codes and other errors.
*
* @param  array $f_files Files array to be checked
* @param  integer $f_quantity File quantity
* @param  array &$f_result_array Result array
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return array Checked files array
* @since  v0.1.00
*/
function direct_datacenter_uploads_local_check ($f_files,$f_quantity,&$f_result_array)
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_uploads_local_check (+f_files,$f_quantity,+f_result_array)- (#echo(__LINE__)#)"); }

	$f_return = array ();
	$f_size_per_file = ($direct_settings['datacenter_uploads_sizeperfile'] * 1048576);
	$f_size_per_upload = ($direct_settings['datacenter_uploads_sizeperupload_local'] * 1048576);
	$f_timeout_time = ($direct_cachedata['core_time'] + $direct_settings['timeout']);
	$f_uploaded_size = 0;

	if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) < 4) { $f_filters_array = direct_datacenter_uploads_filters_get (); }

	if (isset ($f_result_array['*']))
	{
		$f_preparation_error = $f_result_array['*']['error'];
		unset ($f_result_array['*']);
	}
	else { $f_preparation_error = NULL; }

	for ($f_i = 0;(($f_timeout_time > time ())&&($f_i < $f_quantity));$f_i++)
	{
		if ((isset ($f_files["dfiles_".$f_i]))&&(is_array ($f_files["dfiles_".$f_i])))
		{
			$f_file_array = $f_files["dfiles_".$f_i];
			if ((!isset ($f_file_array['name']))&&(!strlen ($f_file_array['name']))) { $f_file_array['name'] = uniqid ("swg_"); }
			$f_file_failed = false;

			if (isset ($f_preparation_error))
			{
				$f_file_array['error'] = $f_preparation_error;
				$f_file_failed = true;
			}
			else
			{
/* -------------------------------------------------------------------------
Check for:
1. PHP reports that the file was within the allowed limit
2. No other PHP error occured
3. The file has been saved to a temporary location.
------------------------------------------------------------------------- */

				if (isset ($f_file_array['error']))
				{
					if ($f_file_array['error'] == 1) { $f_file_array['error'] = "file_size_exceeds_limit"; }
					elseif ($f_file_array['error'] != 0) { $f_file_array['error'] = "php_error:".$f_file_array['error']; }
					else { unset ($f_file_array['error']); }

					$f_file_failed = isset ($f_file_array['error']);
				}
			}

			if ((!$f_file_failed)&&((!isset ($f_file_array['tmp_name']))||(!file_exists ($f_file_array['tmp_name']))))
			{
				$f_file_array['error'] = "php_error:404";
				$f_file_failed = true;
			}

			if (!$f_file_failed)
			{
/* -------------------------------------------------------------------------
Check for:
1a. The file size is given or within allowed limits
1b. Accept all files from main moderators and administrators
2. Total upload size within the defined limit (only successfully uploaded
   files)
3. If the file type is given, known and valid
------------------------------------------------------------------------- */

				$f_file_size = filesize ($f_file_array['tmp_name']);

/* -------------------------------------------------------------------------
The type sent by the browser is accepted if it is not
"application/octet-stream". Otherwise we will check the extension to find a
mimetype.
------------------------------------------------------------------------- */

				if ((!isset ($f_file_array['type']))||(!$f_file_array['type'])||($f_file_array['type'] == "application/octet-stream"))
				{
					$f_path_array = pathinfo ($f_file['name']);

					if (isset ($f_path_array['extension'])) { $f_file_array['type'] = $direct_classes['basic_functions']->mimetype_extension ($f_path_array['extension']); }
					else { $f_file_array['type'] = "text/x-unknown"; }
				}

				if ((isset ($f_file_array['size']))&&($f_file_size != $f_file_array['size']))
				{
					$f_file_array['error'] = "file_type_unsupported";
					$f_file_failed = true;
				}
				elseif ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) < 4)
				{
					if ($f_file_size > $f_size_per_file)
					{
						$f_file_array['error'] = "file_size_exceeds_limit";
						$f_file_failed = true;
					}
					elseif (($f_uploaded_size + $f_file_size) > $f_size_per_upload)
					{
						$f_file_array['error'] = "upload_exceeds_limit";
						$f_file_failed = true;
					}

/* -------------------------------------------------------------------------
All files are checked by defined filters. Make sure that these filters are
strict in untrusted production environments.
------------------------------------------------------------------------- */

					if (isset ($f_filters_array[$f_file_array['type']]))
					{
						$f_filter_array = $f_filters_array[$f_file_array['type']];
						$f_function = "direct_datacenter_filter_{$f_filter_array['module']}_".$f_filter_array['function'];
						$direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/datacenter/swg_filter_{$f_filter_array['module']}.php");

						if (function_exists ($f_function))
						{
							if (!$f_function ($f_file_array['tmp_name'],$f_file_array['name'])) { $f_file_failed = true; }
						}
						else { $f_file_failed = true; }
					}
					else { $f_file_failed = true; }

					if ($f_file_failed) { $f_file_array['error'] = "file_type_unsupported"; }
				}

				$f_file_array['size'] = $f_file_size;
			}

			$f_file_array['key'] = preg_replace ("#\W#","_",$f_file_array['name']);

			if ($f_file_failed) { $f_result_array[$f_file_array['key']] = $f_file_array; }
			else
			{
				$f_return[$f_file_array['key']] = $f_file_array;
				$f_uploaded_size += $f_file_array['size'];
			}
		}
	}

	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datacenter_uploads_local_check ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
}

//f// direct_datacenter_uploads_prepare (&$f_datacenter_object,&$f_result_array,$f_path,$f_name_length = 2)
/**
* Creates a sub-directory with the given name length to store uploaded
* files.
*
* @param  direct_datacenter* &$f_datacenter_object DataCenter object
* @param  array &$f_result_array Result array
* @param  string $f_path Upload path
* @param  integer $f_name_length Length for the upload storage sub-directory
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return mixed Folder name on success; False on error
* @since  v0.1.00
*/
function direct_datacenter_uploads_prepare (&$f_datacenter_object,&$f_result_array,$f_path,$f_name_length = 2)
{
	global $direct_classes;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_uploads_prepare (+f_datacenter_object,+f_result_array,$f_path,$f_name_length)- (#echo(__LINE__)#)"); }

	$f_return = false;

	if (is_object ($f_datacenter_object))
	{
		$f_path = preg_replace ("#[\/]+$#","",($direct_classes['basic_functions']->inputfilter_filepath ($f_path,true)));

		if ($f_name_length < 1) { $f_return = ""; }
		else
		{
			if (strlen ($f_path)) { $f_path .= "/"; }
			$f_return = $f_datacenter_object->get_upload_folder ($f_path,$f_name_length);
			if (!$f_return) { $f_result_array['*'] = array ("name" => "*","error" => "directory_error"); }
		}
	}
	else { $f_result_array['*'] = array ("name" => "*","error" => "access_denied"); }

	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datacenter_uploads_prepare ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
}

//f// direct_datacenter_uploads_save ($f_files,&$f_result_array,$f_task_array,$f_target_did_main,$f_upload_directory,$f_credits_onetime,$f_credits_periodically)
/**
* This function checks the credits limit and saves metadata to the database.
*
* @param  array $f_files Files array to be saved to the database
* @param  array &$f_result_array Result array
* @param  array $f_task_array Task data
* @param  string $f_target_did_main Main directory ID (used for structure
*         analysis and rights management). It is empty for DataSub entries.
* @param  string $f_upload_directory Upload storage sub-directory
* @param  integer $f_credits_onetime Onetime credits per byte
* @param  integer $f_credits_periodically Periodic credits per byte
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return integer Successfully saved item count
* @since  v0.1.00
*/
function direct_datacenter_uploads_save ($f_files,&$f_result_array,$f_task_array,$f_target_did_main,$f_upload_directory,$f_credits_onetime,$f_credits_periodically)
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_uploads_local_check (+f_files,+f_result_array,+f_task_array,$f_target_did_main,$f_upload_directory,$f_credits_onetime,$f_credits_periodically)- (#echo(__LINE__)#)"); }

	$f_return = 0;
	$f_datacenter_object = new direct_datacenter ();
	$f_timeout_time = ($direct_cachedata['core_time'] + $direct_settings['timeout'] + $direct_settings['timeout_core']);
	$f_total_credits_onetime = 0;
	$f_upload_path = preg_replace ("#[\/]+$#","",($direct_classes['basic_functions']->inputfilter_filepath ($f_task_array['datacenter_upload_path'],true)));

	if ((!isset ($f_task_array['datacenter_upload_limit_relevant']))||($f_task_array['datacenter_upload_limit_relevant'])) { $f_limit_relevant_check = true; }
	else { $f_limit_relevant_check = false; }

	if ((isset ($f_task_array['datacenter_upload_mode_physical']))&&($f_task_array['datacenter_upload_mode_physical'])) { $f_physical_check = true; }
	else { $f_physical_check = false; }

	if ((isset ($f_task_array['datacenter_upload_only']))&&($f_task_array['datacenter_upload_only'])) { $f_upload_check = true; }
	else { $f_upload_check = false; }

	if (strlen ($f_upload_directory)) { $f_upload_directory .= "/"; }
	if (strlen ($f_upload_path)) { $f_upload_path .= "/"; }

	foreach ($f_files as $f_file_array)
	{
		$f_file_failed = isset ($f_file_array['error']);
		$f_file_name = $f_file_array['name'];

		if ($f_timeout_time > time ())
		{
			if ($f_limit_relevant_check)
			{
				$f_file_credits_onetime = ceil ($f_credits_onetime * $f_file_array['size']);
				if (($f_credits_onetime)&&(!$f_file_credits_onetime)) { $f_file_credits_onetime = -1; }

				if (!direct_credits_payment_check (false,($f_total_credits_onetime + $f_file_credits_onetime)))
				{
					$f_file_array['error'] = "credits:".(-1 * $f_file_credits_onetime);
					$f_file_failed = true;
				}
			}
		}
		else
		{
			$f_file_array['error'] = "php_error:408";
			$f_file_failed = true;
		}

/* -------------------------------------------------------------------------
Prepare database entry
------------------------------------------------------------------------- */

		if (!$f_file_failed)
		{
			$f_object_id = uniqid ("");

$f_insert_array = array (
"ddbdatalinker_id" => $f_object_id,
"ddbdatalinker_sid" => "d4d66a02daefdb2f70ff2507a78fd5ec",
// md5 ("datacenter")
"ddbdatalinker_type" => 2,
"ddbdatalinker_position" => 0,
"ddbdatalinker_subs" => 0,
"ddbdatalinker_objects" => 0,
"ddbdatalinker_sorting_date" => $direct_cachedata['core_time'],
"ddbdatalinker_symbol" => "",
"ddbdatalinker_title" => $f_file_array['name'],
"ddbdatalinker_datasubs_type" => $f_task_array['datacenter_upload_subs_type'],
"ddbdatalinker_datasubs_hide" => $f_task_array['datacenter_upload_subs_hidden'],
"ddbdatalinker_datasubs_new" => $f_task_array['datacenter_upload_subs_allowed'],
"ddbdatacenter_size" => $f_file_array['size'],
"ddbdatacenter_type" => $f_file_array['type'],
"ddbdatacenter_plocation" => $f_file_array['tmp_name'],
"ddbdatacenter_mode_owner" => "w",
"ddbdatacenter_mode_last" => $f_task_array['datacenter_upload_mode_last'],
"ddbdatacenter_mode_all" => $f_task_array['datacenter_upload_mode_all'],
"ddbdatacenter_trusted" => $f_task_array['datacenter_upload_trusted'],
"ddbdatacenter_deleted" => 0,
"ddbdatacenter_locked" => 0
);

			if ((isset ($f_task_array['datacenter_upload_datasub']))&&($f_task_array['datacenter_upload_datasub'])) { $f_insert_array['ddbdatalinker_id_parent'] = $f_task_array['datacenter_upload_datasub']; }
			else
			{
				$f_insert_array['ddbdatalinker_id_parent'] = $f_task_array['datacenter_upload_target_did'];
				$f_insert_array['ddbdatalinker_id_main'] = $f_target_did_main;
			}

			if ($f_datacenter_object->set ($f_insert_array))
			{
				if (($f_task_array['datacenter_upload_mode'] == "local")&&($direct_settings['datacenter_uploads_localside_mods_support'])&&(!direct_mods_include ($direct_settings['datacenter_uploads_localside_mods_support'],"datacenter_uploads_local","files_check",$f_datacenter_object,$f_file_array,$f_task_array)))
				{
					if (!isset ($f_file_array['error'])) { $f_file_array['error'] = "file_type_unsupported"; }
					$f_file_failed = true;
				}
				elseif (($f_task_array['datacenter_upload_mode'] == "server")&&($direct_settings['datacenter_uploads_serverside_mods_support'])&&(!direct_mods_include ($direct_settings['datacenter_uploads_serverside_mods_support'],"datacenter_uploads_server","files_check",$f_datacenter_object,$f_file_array,$f_task_array)))
				{
					if (!isset ($f_file_array['error'])) { $f_file_array['error'] = "file_type_unsupported"; }
					$f_file_failed = true;
				}
			}
			else
			{
				$f_file_array['error'] = "database_error";
				$f_file_failed = true;
			}
		}

/* -------------------------------------------------------------------------
Prepare final upload location
------------------------------------------------------------------------- */

		if (!$f_file_failed)
		{
			$f_file_path = str_replace (" ","_",$f_file_array['name']);
			$f_file_prefix = "";

			if (!$f_physical_check)
			{
				while (file_exists ($f_upload_path.$f_upload_directory.$f_file_prefix.$f_file_path)) { $f_file_prefix = (uniqid ("")."."); }
				$f_file_path = $f_file_prefix.$f_file_path;
			}
			elseif (file_exists ($f_upload_path.$f_upload_directory.$f_file_path)) { $f_file_path = NULL; }

			if (@$direct_settings['swg_umask_change']) { umask (intval ($direct_settings['swg_umask_change'],8)); }

			if ((isset ($f_file_path))&&((($f_task_array['datacenter_upload_mode'] == "local")&&(@move_uploaded_file ($f_file_array['tmp_name'],$f_upload_path.$f_upload_directory.$f_file_path)))||(($f_task_array['datacenter_upload_mode'] == "server")&&(@rename ($f_file_array['tmp_name'],$f_upload_path.$f_upload_directory.$f_file_path)))))
			{
				$f_insert_array['ddbdatacenter_plocation'] = $f_upload_directory.$f_file_path;
				$f_file_path = $f_upload_path.$f_upload_directory.$f_file_path;
				$f_file_array['location'] = $f_file_path;
				if (@$direct_settings['swg_chmod_files_change']) { chmod ($f_file_path,(intval ($direct_settings['swg_chmod_files_change'],8))); }
			}
			else
			{
				$f_file_array['error'] = "transfer_error";
				$f_file_failed = true;
			}
		}

		if ((!$f_file_failed)&&(!$f_physical_check)&&(!$f_upload_check))
		{
/* -------------------------------------------------------------------------
Insert database entry
------------------------------------------------------------------------- */

			$direct_classes['db']->v_transaction_begin ();
			$f_continue_check = $f_datacenter_object->set_insert ($f_insert_array);
			if ($f_continue_check) { $f_return += 1; }

			if (($f_task_array['datacenter_upload_mode'] == "local")&&($direct_settings['datacenter_uploads_localside_mods_support'])) { $f_continue_check = direct_mods_include ($direct_settings['datacenter_uploads_localside_mods_support'],"datacenter_uploads_local","files_save",$f_datacenter_object,$f_task_array); }
			elseif (($f_task_array['datacenter_upload_mode'] == "server")&&($direct_settings['datacenter_uploads_serverside_mods_support'])) { $f_continue_check = direct_mods_include ($direct_settings['datacenter_uploads_serverside_mods_support'],"datacenter_uploads_server","files_save",$f_datacenter_object,$f_task_array); }

			if ((!$f_continue_check)||(!$direct_classes['db']->v_transaction_commit ()))
			{
				$direct_classes['db']->v_transaction_rollback ();
				$f_file_array['error'] = "database_error";
				$f_file_failed = true;
			}
		}
		else { $f_continue_check = true; }

		if (($f_continue_check)&&(!$f_file_failed)&&($f_limit_relevant_check))
		{
			$f_total_credits_onetime += $f_file_credits_onetime;
			$f_file_credits_periodically = ceil ($f_credits_periodically * $f_file_array['size']);
			if (($f_credits_periodically)&&(!$f_file_credits_periodically)) { $f_file_credits_periodically = -1; }

			direct_credits_payment_exec ("datacenter","file_new",$f_object_id,$direct_settings['user']['id'],$f_total_credits_onetime,$f_file_credits_periodically);
			$f_file_array['oid'] = $f_object_id;
		}

		if (($f_task_array['datacenter_upload_mode'] == "server")&&(isset ($f_file_array['tmp_name']))) { @unlink ($f_file_array['tmp_name']); }
		$f_result_array[$f_file_array['key']] = $f_file_array;
	}

	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datacenter_uploads_save ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
}

//f// direct_datacenter_uploads_server_definition_check ($f_quantity,&$f_result_array)
/**
* Checks given URL definitions.
*
* @param  integer $f_quantity Download quantity
* @param  array &$f_result_array Result array
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return array File array accepted for download
* @since  v0.1.00
*/
function direct_datacenter_uploads_server_definition_check ($f_quantity,&$f_result_array)
{
	global $direct_classes;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_uploads_server_definition_check ($f_quantity,+f_result_array)- (#echo(__LINE__)#)"); }

	$f_return = array ();

	if (isset ($f_result_array['*']))
	{
		$f_preparation_error = $f_result_array['*']['error'];
		unset ($f_result_array['*']);
	}
	else { $f_preparation_error = NULL; }

	for ($f_i = 0;$f_i < $f_quantity;$f_i++)
	{
		if (isset ($GLOBALS["i_dfiles_".$f_i]))
		{
			$f_file_array = array ("name" => $GLOBALS["i_dfiles_".$f_i]);
			$f_file_failed = false;

			$f_url = $direct_classes['basic_functions']->inputfilter_basic ($f_file_array['name']);
			$f_url_id = md5 ($f_url);

			if (!isset ($f_return[$f_url_id]))
			{
				if (preg_match ("#^http(s|):\/\/(.+?).(.+?)$#i",$f_url))
				{
					$f_url_array = parse_url ($f_url);
					$f_url_server = $f_url_array['scheme']."://";

					if ((isset ($f_url_array['user']))||(isset ($f_url_array['pass'])))
					{
						if (isset ($f_url_array['user'])) { $f_url_server .= $f_url_array['user']; }
						$f_url_server .= ":";
						if (isset ($f_url_array['pass'])) { $f_url_server .= $f_url_array['pass']; }
						$f_url_server .= "@";
					}

					$f_url_server .= $f_url_array['host'];

					if (isset ($f_url_array['port'])) { $f_url_port = $f_url_array['port']; }
					else { $f_url_port = 80; }

					if (isset ($f_url_array['path']))
					{
						$f_path_array = pathinfo ($f_url_array['path']);
						$f_url_path = $f_url_array['path'];

						if (($f_path_array)&&(isset ($f_path_array['basename']))&&(strlen ($f_path_array['basename']))) { $f_file_array['name'] = $f_path_array['basename']; }
						else { $f_file_array['name'] = uniqid ("swg_"); }
					}
					else { $f_url_path = "/"; }

					$f_url_query = array ();
					if (isset ($f_url_array['query'])) { parse_str ($f_url_array['query'],$f_url_query); }
					if (isset ($f_url_array['fragment'])) { $f_url_query['#'] = $f_url_array['fragment']; }

					$f_file_array['server'] = $f_url_server;
					$f_file_array['port'] = $f_url_port;
					$f_file_array['path'] = $f_url_path;
					$f_file_array['data'] = $f_url_query;
				}
				else
				{
					$f_path_array = pathinfo ($f_file['name']);

					$f_file_array['error'] = "url_invalid";
					if (($f_path_array)&&(isset ($f_path_array['basename']))) { $f_file_array['name'] = $f_path_array['basename']; }
					$f_file_failed = true;
				}

				if (isset ($f_preparation_error))
				{
					$f_file_array['error'] = $f_preparation_error;
					$f_file_failed = true;
				}

				$f_file_array['key'] = $f_url_id;

				if ($f_file_failed) { $f_result_array[$f_file_array['key']] = $f_file_array; }
				else { $f_return[$f_file_array['key']] = $f_file_array; }
			}
		}
	}

	return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datacenter_uploads_server_definition_check ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
}

//f// direct_datacenter_uploads_server_download (&$f_task_array,$f_upload_directory)
/**
* Downloads, stores and validates files saved on a remote server.
*
* @param  array &$f_task_array Task data
* @param  string $f_upload_directory Upload storage sub-directory
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @since  v0.1.00
*/
function direct_datacenter_uploads_server_download (&$f_task_array,$f_upload_directory)
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datacenter_uploads_server_download (+f_task_array,$f_upload_directory)- (#echo(__LINE__)#)"); }

	$f_bytes_unread = 0;
	$f_continue_check = direct_class_init ("web_functions");
	$f_task_array['datacenter_upload_downloaded_size'] = (int)$f_task_array['datacenter_upload_downloaded_size'];
	$f_timeout_time = ($direct_cachedata['core_time'] + $direct_settings['swg_web_socket_timeout']);
	$f_upload_path = preg_replace ("#[\/]+$#","",($direct_classes['basic_functions']->inputfilter_filepath ($f_task_array['datacenter_upload_path'],true)));
	$f_web_functions_check = $f_continue_check;

	if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) < 4) { $f_filters_array = direct_datacenter_uploads_filters_get (); }

	if (strlen ($f_upload_directory)) { $f_upload_directory .= "/"; }
	if (strlen ($f_upload_path)) { $f_upload_path .= "/"; }

	while ((($f_bytes_unread)||(!empty ($f_task_array['datacenter_upload_downloads'])))&&($f_timeout_time > time ()))
	{
		if (!$f_bytes_unread)
		{
			$f_download_array = array_shift ($f_task_array['datacenter_upload_downloads']);

			if (isset ($f_download_array['size_completed'])) { $f_download_array['size_completed'] = (int)$f_download_array['size_completed']; }
			else { $f_download_array['size_completed'] = 0; }

			if (isset ($f_download_array['size_unread'])) { $f_download_array['size_unread'] = (int)$f_download_array['size_unread']; }
			else
			{
				$f_download_array['size_unread'] = $f_download_array['size'];
				$f_download_array['tmp_name'] = $f_upload_path.$f_upload_directory.(uniqid ("swg_transfer_"));
			}

			if ($f_web_functions_check)
			{
				$f_bytes_unread = $f_download_array['size_unread'];
				$f_file_object = new direct_file_functions ();
				$f_continue_check = $f_file_object->open ($f_download_array['tmp_name']);
			}
		}

		if ($f_continue_check)
		{
			if ($f_bytes_unread < 4096)
			{
				$f_bytes_unread = 0;
				$f_file_data = $direct_classes['web_functions']->http_get ($f_download_array['server'],$f_download_array['port'],$f_download_array['path'],$f_download_array['data']);

				if (is_bool ($f_file_data)) { $f_download_array['error'] = "transfer_error"; }
				elseif (!$f_file_object->write ($f_file_data)) { $f_download_array['error'] = "transfer_error"; }
				else { $f_task_array['datacenter_upload_downloaded_size'] += strlen ($f_file_data); }
			}
			else
			{
				if ($f_bytes_unread > 4096) { $f_part_size = 4096; }
				else { $f_part_size = $f_bytes_unread; }

				$f_file_data = $direct_classes['web_functions']->http_range ($f_download_array['server'],$f_download_array['port'],$f_download_array['path'],$f_download_array['data'],$f_download_array['size_completed'],($f_download_array['size_completed'] + $f_part_size));
				$f_request_result_code = $direct_classes['web_functions']->get_result_code ();

				if ("error::timeout" != $f_request_result_code)
				{
					if ((is_bool ($f_file_data))||((200 != $f_request_result_code)&&(206 != $f_request_result_code))||(!$f_file_object->write ($f_file_data)))
					{
						$f_bytes_unread = 0;
						$f_download_array['error'] = "transfer_error";
					}
					else
					{
						$f_part_size = strlen ($f_file_data);

						$f_bytes_unread -= $f_part_size;
						$f_download_array['size_completed'] += $f_part_size;
						$f_task_array['datacenter_upload_downloaded_size'] += $f_part_size;
					}
				}
			}
		}
		else
		{
			$f_bytes_unread = 0;
			$f_download_array['error'] = "directory_error";
		}

		$f_download_array['size_unread'] = $f_bytes_unread;

		if (!$f_bytes_unread)
		{
			if ($f_continue_check)
			{
				$f_file_object->close (false);

				if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) < 4)
				{
					if (isset ($f_filters_array[$f_download_array['type']]))
					{
						$f_filter_array = $f_filters_array[$f_download_array['type']];
						$f_function = "direct_datacenter_filter_{$f_filter_array['module']}_".$f_filter_array['function'];
						$direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/datacenter/swg_filter_{$f_filter_array['module']}.php");

						if (function_exists ($f_function))
						{
							if (!$f_function ($f_download_array['tmp_name'],$f_download_array['name'])) { $f_download_array['error'] = "file_type_unsupported"; }
						}
						else { $f_download_array['error'] = "file_type_unsupported"; }
					}
					else { $f_download_array['error'] = "file_type_unsupported"; }
				}
			}
			else { unset ($f_download_array['tmp_name']); }

			$f_task_array['datacenter_upload_results'][$f_download_array['key']] = $f_download_array;
		}
	}

	if ($f_bytes_unread)
	{
		if ($f_continue_check) { $f_file_object->close (false); }
		$f_task_array['datacenter_upload_downloads'][$f_download_array['key']] = $f_download_array;
	}
}

//j// Script specific commands

if (!isset ($direct_settings['datacenter_uploads_localside_mods_support'])) { $direct_settings['datacenter_uploads_localside_mods_support'] = false; }
if (!isset ($direct_settings['datacenter_uploads_serverside_mods_support'])) { $direct_settings['datacenter_uploads_serverside_mods_support'] = false; }
if (!isset ($direct_settings['datacenter_uploads_sizeperfile'])) { $direct_settings['datacenter_uploads_sizeperfile'] = 1.25; }
if (!isset ($direct_settings['datacenter_uploads_sizeperupload_local'])) { $direct_settings['datacenter_uploads_sizeperupload_local'] = 2.5; }
if (!isset ($direct_settings['swg_web_socket_timeout'])) { $direct_settings['swg_web_socket_timeout'] = $direct_settings['timeout_core']; }

$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_file_functions.php");
$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_credits_manager.php");
$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_mods_support.php");

//j// EOF
?>