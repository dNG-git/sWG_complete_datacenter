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
* dataport/swgap/datacenter/swg_uploads_server_http.php
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

if (!isset ($direct_settings['datacenter_https_control_objects'])) { $direct_settings['datacenter_https_control_objects'] = false; }
if (!isset ($direct_settings['datacenter_file_new_credits_onetime'])) { $direct_settings['datacenter_file_new_credits_onetime'] = 0; }
if (!isset ($direct_settings['datacenter_file_new_credits_periodically'])) { $direct_settings['datacenter_file_new_credits_periodically'] = 0; }
if (!isset ($direct_settings['datacenter_download_chunksize'])) { $direct_settings['datacenter_download_chunksize'] = 4096; }
if (!isset ($direct_settings['datacenter_uploads_serverside_mods_support'])) { $direct_settings['datacenter_uploads_serverside_mods_support'] = false; }
if (!isset ($direct_settings['datacenter_uploads_serverside'])) { $direct_settings['datacenter_uploads_serverside'] = true; }
if (!isset ($direct_settings['datacenter_uploads_sizeperfile'])) { $direct_settings['datacenter_uploads_sizeperfile'] = 1.25; }
if (!isset ($direct_settings['datacenter_uploads_sizeperupload_server'])) { $direct_settings['datacenter_uploads_sizeperupload_server'] = 2.5; }
if (!isset ($direct_settings['serviceicon_default_back'])) { $direct_settings['serviceicon_default_back'] = "mini_default_back.png"; }
if (!isset ($direct_settings['swg_web_socket_timeout'])) { $direct_settings['swg_web_socket_timeout'] = $direct_settings['timeout_core']; }
$direct_settings['additional_copyright'][] = array ("Module datacenter #echo(sWGdatacenterVersion)# - (C) ","http://www.direct-netware.de/redirect.php?swg","direct Netware Group"," - All rights reserved");

//j// BOS
switch ($direct_settings['a'])
{
//j// $direct_settings['a'] == "download-check"
case "download-check":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=download-check_ (#echo(__LINE__)#)"); }

	$g_tid = (isset ($direct_settings['dsd']['tid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['tid'])) : "");

	if ((isset ($direct_settings['dsd']['dtheme']))&&($direct_settings['dsd']['dtheme']))
	{
		$g_dtheme = true;

		if ($direct_settings['dsd']['dtheme'] == 2)
		{
			$g_dtheme_mode = 2;
			$g_dtheme_embedded = true;
		}
		else
		{
			$g_dtheme_mode = 1;
			$g_dtheme_embedded = false;
		}

		$direct_cachedata['page_this'] = "m=dataport&s=swgap;datacenter;uploads_server_http&a=download-check&dsd=dtheme+{$g_dtheme_mode}++tid+".$g_tid;
		$direct_cachedata['page_backlink'] = "";
		$direct_cachedata['page_homelink'] = "";

		$g_continue_check = $direct_classes['kernel']->service_init_default ();
	}
	else
	{
		$g_dtheme_mode = 0;
		$g_dtheme = false;
		$g_dtheme_embedded = false;

		$g_continue_check = $direct_classes['kernel']->service_init_rboolean ();
	}

	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_datacenter.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/swg_web_functions.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_aphandler.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php"); }

	if ($g_continue_check)
	{
	//j// BOA
	if ($g_tid == "") { $g_tid = $direct_settings['uuid']; }
	$g_task_array = direct_tmp_storage_get ("evars",$g_tid,"","task_cache");

	if (!$direct_settings['datacenter_uploads_serverside']) { $g_continue_check = false; }
	elseif (($g_task_array)&&(isset ($g_task_array['core_sid'],$g_task_array['datacenter_upload_path'],$g_task_array['datacenter_upload_preselect_done'],$g_task_array['uuid']))&&($g_task_array['datacenter_upload_preselect_done'])&&(!$g_task_array['datacenter_upload_done'])&&($g_task_array['uuid'] == $direct_settings['uuid']))
	{
		if ($g_dtheme_embedded) { direct_output_related_manager ("datacenter_uploads_server_http_download_check","pre_module_service_action_embedded"); }
		elseif ($g_dtheme) { direct_output_related_manager ("datacenter_uploads_server_http_download_check","pre_module_service_action"); }
		else { direct_output_related_manager ("datacenter_uploads_server_http_download_check","pre_module_service_action_ajax"); }

		$direct_cachedata['page_backlink'] = str_replace ("[oid]","",$g_task_array['core_back_return']);
		$direct_cachedata['page_homelink'] = $direct_cachedata['page_backlink'];

		if ((!isset ($g_task_array['datacenter_upload_downloads_unconfirmed']))||(!is_array ($g_task_array['datacenter_upload_downloads_unconfirmed']))) { $g_task_array['datacenter_upload_downloads_unconfirmed'] = array (); }
		if ((!isset ($g_task_array['datacenter_upload_results']))||(!is_array ($g_task_array['datacenter_upload_results']))) { $g_task_array['datacenter_upload_results'] = array (); }
	}
	else { $g_continue_check = false; }

	if ($g_dtheme) { $direct_classes['kernel']->service_https ($direct_settings['datacenter_https_control_objects'],$direct_cachedata['page_this']); }
	if ($g_dtheme_embedded) { direct_output_theme_subtype ("embedded"); }
	direct_local_integration ("datacenter");

	direct_class_init ("output");
	direct_class_init ("web_functions");
	if (($g_dtheme)&&($direct_cachedata['page_backlink'])) { $direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0"); }

	if ($g_continue_check)
	{
		if ($g_dtheme) { $direct_classes['output']->servicemenu ("datacenter_upload"); }

		$g_size_per_file = ($direct_settings['datacenter_uploads_sizeperfile'] * 1048576);
		$g_size_per_upload = ($direct_settings['datacenter_uploads_sizeperupload_server'] * 1048576);
		$g_timeout_time = ($direct_cachedata['core_time'] + $direct_settings['swg_web_socket_timeout']);

		if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) < 4) { $g_filters_array = direct_datacenter_uploads_filters_get (); }
		if ((!isset ($g_task_array['datacenter_upload_downloads']))||(!is_array ($g_task_array['datacenter_upload_downloads']))) { $g_task_array['datacenter_upload_downloads'] = array (); }

		$g_downloads_size = ((isset ($g_task_array['datacenter_upload_downloads_size'])) ? $g_task_array['datacenter_upload_downloads_size'] : 0);

		while ((!empty ($g_task_array['datacenter_upload_downloads_unconfirmed']))&&($g_timeout_time > time ()))
		{
			$g_continue_check = true;
			$g_download_array = array_shift ($g_task_array['datacenter_upload_downloads_unconfirmed']);
			$direct_classes['web_functions']->http_get ($g_download_array['server'],$g_download_array['port'],$g_download_array['path'],$g_download_array['data'],true);

/* -------------------------------------------------------------------------
Check for:
1. The correct HTTP response code was returned
2. The file size is given
3. Accept all files from main moderators and administrators
4. File size within limit
5. Total upload size within the defined limit (only successfully uploaded
   files)
6. The file type is accepted
------------------------------------------------------------------------- */

			$g_download_array['size'] = $direct_classes['web_functions']->get_content_size ();
			$g_download_type = $direct_classes['web_functions']->get_content_type ();
			$g_request_result_code = $direct_classes['web_functions']->get_result_code ();

			if ((!$g_download_type)||($g_download_type == "application/octet-stream"))
			{
				$g_download_file_array = pathinfo ($g_download_array['name']);
				$g_download_type = ((isset ($g_download_file_array['extension'])) ? $direct_classes['basic_functions']->mimetype_extension ($g_download_file_array['extension']) : "text/x-unknown");
			}

			if ((200 != $g_request_result_code)&&(203 != $g_request_result_code))
			{
				$g_continue_check = false;
				$g_download_array['error'] = "server_response_error:".$g_request_result_code;
			}
			elseif (!$g_download_array['size'])
			{
				$g_continue_check = false;
				$g_download_array['error'] = "file_type_unsupported";
			}
			elseif ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) < 4)
			{
				$g_download_type = $direct_classes['web_functions']->get_content_type ();

				if ($g_download_array['size'] > $g_size_per_file)
				{
					$g_continue_check = false;
					$g_download_array['error'] = "file_size_exceeds_limit";
				}
				elseif (($g_downloads_size + $g_download_array['size']) > $g_size_per_upload)
				{
					$g_continue_check = false;
					$g_download_array['error'] = "upload_exceeds_limit";
				}
				elseif (!isset ($g_filters_array[$g_download_type]))
				{
					$g_continue_check = false;
					$g_download_array['error'] = "file_type_unsupported";
				}
			}

			if ($g_continue_check)
			{
				$g_download_array['type'] = $g_download_type;
				$g_task_array['datacenter_upload_downloads'][] = $g_download_array;
				$g_downloads_size += $g_download_array['size'];
			}
			else { $g_task_array['datacenter_upload_results'][$g_download_array['key']] = $g_download_array; }
		}

		$g_task_array['datacenter_upload_downloads_size'] = $g_downloads_size;

		if (empty ($g_task_array['datacenter_upload_downloads_unconfirmed']))
		{
			unset ($g_task_array['datacenter_upload_downloads_unconfirmed']);
			unset ($g_task_array['datacenter_upload_downloads_unconfirmed_count']);
			if (($g_dtheme)&&(empty ($g_task_array['datacenter_upload_downloads']))) { $g_task_array['datacenter_upload_done'] = 1; }
			$g_task_array['datacenter_upload_downloaded_size'] = 0;
			$g_task_array['datacenter_upload_downloads_time_start'] = $direct_cachedata['core_time'];
			direct_tmp_storage_write ($g_task_array,$g_tid,$g_task_array['core_sid'],"task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));

			if (!$g_dtheme) { header ("HTTP/1.1 205 Reset Content"); }
			elseif (empty ($g_task_array['datacenter_upload_downloads'])) { $direct_classes['output']->redirect (direct_linker ("url1","m=datacenter&s=uploads&a=results&dsd=tid+".$g_tid,false)); }
			else { $direct_classes['output']->redirect (direct_linker ("url1","m=dataport&s=swgap;datacenter;uploads_server_http&a=download-run&dsd=tid+{$g_tid}++dtheme+".$g_dtheme_mode,false)); }
		}
		else
		{
			direct_tmp_storage_write ($g_task_array,$g_tid,$g_task_array['core_sid'],"task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));
			$g_downloads_confirmed = ($g_task_array['datacenter_upload_downloads_unconfirmed_count'] - (count ($g_task_array['datacenter_upload_downloads_unconfirmed'])));

			$direct_cachedata['output_job'] = (((isset ($g_task_array['datacenter_upload_overwrite_title']))&&($g_task_array['datacenter_upload_overwrite_title'])) ? $g_task_array['datacenter_upload_overwrite_title'] : direct_local_get ("datacenter_source_serverside"));

			$direct_cachedata['output_ajaxtarget'] = direct_linker ("url0","m=dataport&s=swgap;datacenter;uploads_server_http&a=download-check&dsd=tid+".$g_tid,false);
			$direct_cachedata['output_ajaxnexttarget'] = direct_linker ("url0","m=dataport&s=swgap;datacenter;uploads_server_http&a=download-run&dsd=tid+{$g_tid}++dtheme+".$g_dtheme_mode,false);
			$direct_cachedata['output_pagetarget'] = direct_linker ("url0","m=dataport&s=swgap;datacenter;uploads_server_http&a=download-check&dsd=tid+{$g_tid}++dtheme+".$g_dtheme_mode);
			$direct_cachedata['output_scripttarget'] = direct_linker ("url0","m=dataport&s=swgap;datacenter;uploads_server_http&a=download-check&dsd=tid+{$g_tid}++dtheme+{$g_dtheme_mode}++",false);

			$direct_cachedata['output_percentage'] = direct_aphandler_percentage ($g_downloads_confirmed,$g_task_array['datacenter_upload_downloads_unconfirmed_count']);
			$direct_cachedata['output_time_elapsed'] = direct_aphandler_elapsed ($direct_cachedata['core_time'] - $g_task_array['datacenter_upload_downloads_time_start']);
			$direct_cachedata['output_time_estimated'] = direct_aphandler_estimated ($g_task_array['datacenter_upload_downloads_time_start'],$g_downloads_confirmed,$g_task_array['datacenter_upload_downloads_unconfirmed_count']);
			$direct_cachedata['output_title'] = (direct_local_get ("core_time")).": ".($direct_classes['basic_functions']->datetime ("time",$direct_cachedata['core_time'],$direct_settings['user']['timezone']));
			$direct_cachedata['output_text'] = direct_local_get ("datacenter_downloads_server_checking");

			if ($g_dtheme)
			{
				if ($g_dtheme_embedded)
				{
					direct_output_related_manager ("datacenter_uploads_server_http_download_check","post_module_service_action_embedded");
					$direct_classes['output']->oset ("default_embedded","aphandler");
				}
				else
				{
					direct_output_related_manager ("datacenter_uploads_server_http_download_check","post_module_service_action");
					$direct_classes['output']->oset ("default","aphandler");
				}

				$direct_classes['output']->options_flush (true);
				$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
				$direct_classes['output']->page_show ($direct_cachedata['output_job']);
			}
			else
			{
				$direct_classes['output']->header (false);
				header ("Content-type: text/xml; charset=".$direct_local['lang_charset']);

echo ("<?xml version='1.0' encoding='$direct_local[lang_charset]' ?>
".($direct_classes['output']->oset_content ("default_embedded","ajax_aphandler")));
			}
		}
	}
	elseif ($g_dtheme) { $direct_classes['error_functions']->error_page ("standard","core_tid_invalid","sWG/#echo(__FILEPATH__)# _a=download-check_ (#echo(__LINE__)#)"); }
	//j// BOA
	}

	$direct_cachedata['core_service_activated'] = 1;
	break 1;
}
//j// $direct_settings['a'] == "download-run"
case "download-run":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=download-run (#echo(__LINE__)#)"); }

	$g_tid = (isset ($direct_settings['dsd']['tid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['tid'])) : "");

	if ((isset ($direct_settings['dsd']['dtheme']))&&($direct_settings['dsd']['dtheme']))
	{
		$g_dtheme = true;

		if ($direct_settings['dsd']['dtheme'] == 2)
		{
			$g_dtheme_mode = 2;
			$g_dtheme_embedded = true;
		}
		else
		{
			$g_dtheme_mode = 1;
			$g_dtheme_embedded = false;
		}

		$direct_cachedata['page_this'] = "m=dataport&s=swgap;datacenter;uploads_server_http&a=download-run&dsd=dtheme+{$g_dtheme_mode}++tid+".$g_tid;
		$direct_cachedata['page_backlink'] = "";
		$direct_cachedata['page_homelink'] = "";

		$g_continue_check = $direct_classes['kernel']->service_init_default ();
	}
	else
	{
		$g_dtheme_mode = 0;
		$g_dtheme = false;
		$g_dtheme_embedded = false;

		$g_continue_check = $direct_classes['kernel']->service_init_rboolean ();
	}

	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_datacenter.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter_home.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/swg_web_functions.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_aphandler.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_credits_manager.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_datacenter_uploads.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_mods_support.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php"); }

	if ($g_continue_check)
	{
	//j// BOA
	if ($g_tid == "") { $g_tid = $direct_settings['uuid']; }
	$g_task_array = direct_tmp_storage_get ("evars",$g_tid,"","task_cache");

	if (!$direct_settings['datacenter_uploads_serverside']) { $g_continue_check = false; }
	elseif (($g_task_array)&&(isset ($g_task_array['core_sid'],$g_task_array['datacenter_upload_path'],$g_task_array['datacenter_upload_preselect_done'],$g_task_array['uuid']))&&($g_task_array['datacenter_upload_preselect_done'])&&(!$g_task_array['datacenter_upload_done'])&&($g_task_array['uuid'] == $direct_settings['uuid']))
	{
		if ($g_dtheme_embedded) { direct_output_related_manager ("datacenter_uploads_server_http_download_run","pre_module_service_action_embedded"); }
		elseif ($g_dtheme) { direct_output_related_manager ("datacenter_uploads_server_http_download_run","pre_module_service_action"); }
		else { direct_output_related_manager ("datacenter_uploads_server_http_download_run","pre_module_service_action_ajax"); }

		$direct_cachedata['page_backlink'] = str_replace ("[oid]","",$g_task_array['core_back_return']);
		$direct_cachedata['page_homelink'] = $direct_cachedata['page_backlink'];

		if ((!isset ($g_task_array['datacenter_upload_downloads']))||(!is_array ($g_task_array['datacenter_upload_downloads']))) { $g_task_array['datacenter_upload_downloads'] = array (); }
		if ((!isset ($g_task_array['datacenter_upload_results']))||(!is_array ($g_task_array['datacenter_upload_results']))) { $g_task_array['datacenter_upload_results'] = array (); }
	}
	else { $g_continue_check = false; }

	if ($g_dtheme) { $direct_classes['kernel']->service_https ($direct_settings['datacenter_https_control_objects'],$direct_cachedata['page_this']); }
	if ($g_dtheme_embedded) { direct_output_theme_subtype ("embedded"); }
	direct_local_integration ("datacenter");

	direct_class_init ("output");
	if (($g_dtheme)&&($direct_cachedata['page_backlink'])) { $direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0"); }

	if ($g_continue_check)
	{
		if ($g_dtheme) { $direct_classes['output']->servicemenu ("datacenter_upload"); }

		$g_downloaded_size = $g_task_array['datacenter_upload_downloaded_size'];
		$g_timeout_time = ($direct_cachedata['core_time'] + $direct_settings['swg_web_socket_timeout']);

		if (!isset ($g_task_array['datacenter_upload_directory'])) { $g_task_array['datacenter_upload_directory'] = ""; }
		direct_datacenter_uploads_server_download ($g_task_array,$g_upload_directory);

		if (empty ($g_task_array['datacenter_upload_downloads']))
		{
			$g_datacenter_object = ((strpos ($g_task_array['datacenter_upload_target_did'],"-") === false) ? new direct_datacenter () : new direct_datacenter_home ());

			$g_datacenter_array = ($g_datacenter_object ? $g_datacenter_object->get ($g_task_array['datacenter_upload_target_did']) : NULL);

			if ((is_array ($g_datacenter_array))&&($g_datacenter_object->is_directory ())&&($g_datacenter_object->is_writable ()))
			{
				direct_credits_payment_get_specials ("datacenter_file_new",$g_task_array['datacenter_upload_target_did'],$direct_settings['datacenter_file_new_credits_onetime'],$direct_settings['datacenter_file_new_credits_periodically']);

				$g_upload_check = ((((isset ($g_task_array['datacenter_upload_only']))&&($g_task_array['datacenter_upload_only']))||((isset ($g_task_array['datacenter_upload_mode_physical']))&&($g_task_array['datacenter_upload_mode_physical']))) ? true : false);
				$g_files = direct_datacenter_uploads_save ($g_task_array['datacenter_upload_results'],$g_task_array['datacenter_upload_results'],$g_task_array,$g_datacenter_array['ddbdatalinker_id_main'],$g_upload_directory,($direct_settings['datacenter_file_new_credits_onetime'] * 0.000001),($direct_settings['datacenter_file_new_credits_periodically'] * 0.000001));
				if (($g_files)&&(!$g_upload_check)) { $g_datacenter_object->add_objects ($g_files); }
			}

			unset ($g_task_array['datacenter_upload_downloaded_size']);
			unset ($g_task_array['datacenter_upload_downloads']);
			unset ($g_task_array['datacenter_upload_downloads_size']);
			unset ($g_task_array['datacenter_upload_downloads_time_start']);
			$g_task_array['datacenter_upload_done'] = 1;
			direct_tmp_storage_write ($g_task_array,$g_tid,$g_task_array['core_sid'],"task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));

			if (!$g_dtheme) { header ("HTTP/1.1 205 Reset Content"); }
			elseif (isset ($g_task_array['datacenter_upload_serverside_return'])) { $direct_classes['output']->redirect (direct_linker ("url1",$g_task_array['datacenter_upload_serverside_return'],false)); }
			else { $direct_classes['output']->redirect (direct_linker ("url1","m=datacenter&s=uploads&a=results&dsd=tid+".$g_tid,false)); }
		}
		else
		{
			direct_tmp_storage_write ($g_task_array,$g_tid,$g_task_array['core_sid'],"task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));

			$direct_cachedata['output_job'] = (((isset ($g_task_array['datacenter_upload_overwrite_title']))&&($g_task_array['datacenter_upload_overwrite_title'])) ? $g_task_array['datacenter_upload_overwrite_title'] : direct_local_get ("datacenter_source_serverside"));

			$direct_cachedata['output_ajaxtarget'] = direct_linker ("url0","m=dataport&s=swgap;datacenter;uploads_server_http&a=download-run&dsd=tid+".$g_tid,false);
			$direct_cachedata['output_pagetarget'] = direct_linker ("url0","m=dataport&s=swgap;datacenter;uploads_server_http&a=download-run&dsd=tid+{$g_tid}++dtheme+".$g_dtheme_mode);
			$direct_cachedata['output_scripttarget'] = direct_linker ("url0","m=dataport&s=swgap;datacenter;uploads_server_http&a=download-run&dsd=tid+{$g_tid}++dtheme+{$g_dtheme_mode}++",false);
			$direct_cachedata['output_ajaxnexttarget'] = ((isset ($g_task_array['datacenter_upload_serverside_return'])) ? direct_linker ("url0",$g_task_array['datacenter_upload_serverside_return'],false) : direct_linker ("url0","m=datacenter&s=uploads&a=results&dsd=tid+".$g_tid,false));

			$direct_cachedata['output_percentage'] = direct_aphandler_percentage ($g_downloaded_size,$g_task_array['datacenter_upload_downloads_size']);
			$direct_cachedata['output_time_elapsed'] = direct_aphandler_elapsed ($direct_cachedata['core_time'] - $g_task_array['datacenter_upload_downloads_time_start']);
			$direct_cachedata['output_time_estimated'] = direct_aphandler_estimated ($g_task_array['datacenter_upload_downloads_time_start'],$g_downloaded_size,$g_task_array['datacenter_upload_downloads_size']);
			$direct_cachedata['output_title'] = (direct_local_get ("core_time")).": ".($direct_classes['basic_functions']->datetime ("time",$direct_cachedata['core_time'],$direct_settings['user']['timezone']));
			$direct_cachedata['output_text'] = direct_local_get ("datacenter_downloads_server_transfering");

			if ($g_dtheme)
			{
				if ($g_dtheme_embedded)
				{
					direct_output_related_manager ("datacenter_uploads_server_http_download_run","post_module_service_action_embedded");
					$direct_classes['output']->oset ("default_embedded","aphandler");
				}
				else
				{
					direct_output_related_manager ("datacenter_uploads_server_http_download_run","post_module_service_action");
					$direct_classes['output']->oset ("default","aphandler");
				}

				$direct_classes['output']->options_flush (true);
				$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
				$direct_classes['output']->page_show ($direct_cachedata['output_job']);
			}
			else
			{
				$direct_classes['output']->header (false);
				header ("Content-type: text/xml; charset=".$direct_local['lang_charset']);

echo ("<?xml version='1.0' encoding='$direct_local[lang_charset]' ?>
".($direct_classes['output']->oset_content ("default_embedded","ajax_aphandler")));
			}
		}
	}
	elseif ($g_dtheme) { $direct_classes['error_functions']->error_page ("standard","core_tid_invalid","sWG/#echo(__FILEPATH__)# _a=download-run_ (#echo(__LINE__)#)"); }
	//j// BOA
	}

	$direct_cachedata['core_service_activated'] = 1;
	break 1;
}
//j// EOS
}

//j// EOF
?>