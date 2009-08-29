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
* datacenter/swg_uploads_local.php
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
if (!isset ($direct_settings['datacenter_uploads_localside'])) { $direct_settings['datacenter_uploads_localside'] = true; }
if (!isset ($direct_settings['datacenter_uploads_localside_mods_support'])) { $direct_settings['datacenter_uploads_localside_mods_support'] = false; }
if (!isset ($direct_settings['serviceicon_default_back'])) { $direct_settings['serviceicon_default_back'] = "mini_default_back.png"; }
if (!isset ($direct_settings['theme_form_fileinput_size'])) { $direct_settings['theme_form_fileinput_size'] = 26; }
$direct_settings['additional_copyright'][] = array ("Module datacenter #echo(sWGdatacenterVersion)# - (C) ","http://www.direct-netware.de/redirect.php?swg","direct Netware Group"," - All rights reserved");

//j// BOS
switch ($direct_settings['a'])
{
//j// ($direct_settings['a'] == "files")||($direct_settings['a'] == "files-save")
case "files":
case "files-save":
{
	$g_mode_save = (($direct_settings['a'] == "files-save") ? true : false);
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }

	$g_tid = (isset ($direct_settings['dsd']['tid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['tid'])) : "");

	if ($g_mode_save)
	{
		$direct_cachedata['page_this'] = "";
		$direct_cachedata['page_backlink'] = "m=datacenter&s=uploads_local&a=files&dsd=tid+".$g_tid;
		$direct_cachedata['page_homelink'] = "";
	}
	else
	{
		$direct_cachedata['page_this'] = "m=datacenter&s=uploads_local&a=files&dsd=tid+".$g_tid;
		$direct_cachedata['page_backlink'] = "";
		$direct_cachedata['page_homelink'] = "";
	}

	if ($direct_classes['kernel']->service_init_default ())
	{
	//j// BOA
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php");

	if ($g_tid == "") { $g_tid = $direct_settings['uuid']; }
	$g_task_array = direct_tmp_storage_get ("evars",$g_tid,"","task_cache");

	if (!$direct_settings['datacenter_uploads_localside']) { $g_continue_check = false; }
	elseif (($g_task_array)&&(isset ($g_task_array['core_sid'],$g_task_array['datacenter_upload_path'],$g_task_array['datacenter_upload_preselect_done'],$g_task_array['uuid']))&&($g_task_array['datacenter_upload_preselect_done'])&&(!$g_task_array['datacenter_upload_done'])&&($g_task_array['uuid'] == $direct_settings['uuid']))
	{
		$g_back_link = str_replace ("[oid]","",$g_task_array['core_back_return']);
		$g_continue_check = true;

		if ($g_mode_save) { direct_output_related_manager ("datacenter_uploads_local_files_form_save","pre_module_service_action"); }
		else
		{
			direct_output_related_manager ("datacenter_uploads_local_files_form","pre_module_service_action");
			$direct_cachedata['page_backlink'] = $g_back_link;
		}

		$direct_cachedata['page_homelink'] = $g_back_link;
	}
	else { $g_continue_check = false; }

	if (!$g_mode_save) { $direct_classes['kernel']->service_https ($direct_settings['datacenter_https_control_objects'],$direct_cachedata['page_this']); }
	direct_local_integration ("datacenter");

	direct_class_init ("output");
	if ($direct_cachedata['page_backlink']) { $direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0"); }

	if ($g_continue_check)
	{
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter_home.php");
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formbuilder.php");
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_datacenter_uploads.php");
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_mods_support.php");

		direct_class_init ("formbuilder");
		$direct_classes['output']->servicemenu ("datacenter_upload");

		$direct_cachedata['i_dfiles'] = "";
		$g_rights_check = (($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) ? true : false);
		$g_upload_check = ((((isset ($g_task_array['datacenter_upload_only']))&&($g_task_array['datacenter_upload_only']))||((isset ($g_task_array['datacenter_upload_mode_physical']))&&($g_task_array['datacenter_upload_mode_physical']))) ? true : false);

		for ($g_i = 0;$g_i < $g_task_array['datacenter_upload_target_quantity'];$g_i++)
		{
			if ($direct_cachedata['i_dfiles']) { $direct_cachedata['i_dfiles'] .= "<br />\n"; }
			$direct_cachedata['i_dfiles'] .= "<input type='file' name='dfiles_$g_i' size='$direct_settings[theme_form_fileinput_size]' class='pagecontentfileinput' />";
		}

/* -------------------------------------------------------------------------
Build the form
------------------------------------------------------------------------- */

		$direct_classes['formbuilder']->entry_add ("element","dfiles",NULL,true);

		if (!$g_mode_save) { direct_mods_include ($direct_settings['datacenter_uploads_localside_mods_support'],"datacenter_uploads_local","files",$g_task_array); }
		$direct_cachedata['output_formelements'] = $direct_classes['formbuilder']->form_get ($g_mode_save);

		if (($g_mode_save)&&($direct_classes['formbuilder']->check_result))
		{
/* -------------------------------------------------------------------------
Save data edited
------------------------------------------------------------------------- */

			$g_datacenter_object = ((strpos ($g_task_array['datacenter_upload_target_did'],"-") === false) ? new direct_datacenter () : new direct_datacenter_home ());

			$g_datacenter_array = ($g_datacenter_object ? $g_datacenter_object->get ($g_task_array['datacenter_upload_target_did']) : NULL);

			if ((is_array ($g_datacenter_array))&&($g_datacenter_object->is_directory ())&&($g_datacenter_object->is_writable ())) { $g_result_array = array (); }
			else { $g_result_array = array ("*" => array ("name" => "*","error" => "access_denied")); }

			$g_upload_dirname_length = (((isset ($g_task_array['datacenter_upload_mode_physical']))&&($g_task_array['datacenter_upload_mode_physical'])) ? 0 : $g_task_array['datacenter_upload_dirname_length']);

			if (empty ($g_result_array)) { $g_upload_directory = direct_datacenter_uploads_prepare ($g_datacenter_object,$g_result_array,$g_task_array['datacenter_upload_path'],$g_upload_dirname_length); }
			$g_files = direct_datacenter_uploads_local_check ($_FILES,$g_task_array['datacenter_upload_target_quantity'],$g_result_array);

			if (!empty ($g_files))
			{
				direct_credits_payment_get_specials ("datacenter_file_new",$g_task_array['datacenter_upload_target_did'],$direct_settings['datacenter_file_new_credits_onetime'],$direct_settings['datacenter_file_new_credits_periodically']);

				$g_files = direct_datacenter_uploads_save ($g_files,$g_result_array,$g_task_array,$g_datacenter_array['ddbdatalinker_id_main'],$g_upload_directory,($direct_settings['datacenter_file_new_credits_onetime'] * 0.000001),($direct_settings['datacenter_file_new_credits_periodically'] * 0.000001));
				if (($g_files)&&(!$g_upload_check)) { $g_datacenter_object->add_objects ($g_files); }
			}

			$g_task_array['datacenter_upload_done'] = 1;
			$g_task_array['datacenter_upload_results'] = $g_result_array;
			direct_tmp_storage_write ($g_task_array,$g_tid,$g_task_array['core_sid'],"task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));

			$direct_classes['output']->redirect (direct_linker ("url1","m=datacenter&s=uploads&a=results&dsd=tid+".$g_tid,false));
		}
		else
		{
/* -------------------------------------------------------------------------
View form
------------------------------------------------------------------------- */

			$direct_cachedata['output_formbutton'] = direct_local_get ("core_save");
			$direct_cachedata['output_formtarget'] = "m=datacenter&s=uploads_local&a=files-save&dsd=tid+".$g_tid;
			$direct_cachedata['output_formtitle'] = (((isset ($g_task_array['datacenter_upload_overwrite_title']))&&($g_task_array['datacenter_upload_overwrite_title'])) ? $g_task_array['datacenter_upload_overwrite_title'] : direct_local_get ("datacenter_source_localside"));

			direct_output_related_manager ("datacenter_uploads_local_files_form","post_module_service_action");
			$direct_classes['output']->oset ("default","form");
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_formtitle']);
		}
	}
	else { $direct_classes['error_functions']->error_page ("standard","core_tid_invalid","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// EOS
}

//j// EOF
?>