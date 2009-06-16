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
$Id: swg_uploads.php,v 1.1 2009/03/07 12:48:09 s4u Exp $
#echo(sWGdatacenterVersion)#
sWG/#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* datacenter/swg_uploads.php
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
if (!isset ($direct_settings['datacenter_uploads_boxes'])) { $direct_settings['datacenter_uploads_boxes'] = 1; }
if (!isset ($direct_settings['datacenter_uploads_boxes_max'])) { $direct_settings['datacenter_uploads_boxes_max'] = 20; }
if (!isset ($direct_settings['datacenter_uploads_dirname_length'])) { $direct_settings['datacenter_uploads_dirname_length'] = 2; }
if (!isset ($direct_settings['datacenter_uploads_mods_support'])) { $direct_settings['datacenter_uploads_mods_support'] = false; }
if (!isset ($direct_settings['datacenter_uploads_localside'])) { $direct_settings['datacenter_uploads_localside'] = true; }
if (!isset ($direct_settings['datacenter_uploads_serverside'])) { $direct_settings['datacenter_uploads_serverside'] = true; }
if (!isset ($direct_settings['serviceicon_default_back'])) { $direct_settings['serviceicon_default_back'] = "mini_default_back.png"; }
$direct_settings['additional_copyright'][] = array ("Module datacenter #echo(sWGdatacenterVersion)# - (C) ","http://www.direct-netware.de/redirect.php?swg","direct Netware Group"," - All rights reserved");

//j// BOS
switch ($direct_settings['a'])
{
//j// ($direct_settings['a'] == "preselect")||($direct_settings['a'] == "preselect-save")
case "preselect":
case "preselect-save":
{
	if ($direct_settings['a'] == "preselect-save") { $g_mode_save = true; }
	else { $g_mode_save = false; }

	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }

	$g_tid = (isset ($direct_settings['dsd']['tid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['tid'])) : "");

	if ($g_mode_save)
	{
		$direct_cachedata['page_this'] = "";
		$direct_cachedata['page_backlink'] = "m=datacenter&s=uploads&a=preselect&dsd=tid+".$g_tid;
		$direct_cachedata['page_homelink'] = "";
	}
	else
	{
		$direct_cachedata['page_this'] = "m=datacenter&s=uploads&a=preselect&dsd=tid+".$g_tid;
		$direct_cachedata['page_backlink'] = "";
		$direct_cachedata['page_homelink'] = "";
	}

	if ($direct_classes['kernel']->service_init_default ())
	{
	//j// BOA
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php");

	if ($g_tid == "") { $g_tid = $direct_settings['uuid']; }
	$g_task_array = direct_tmp_storage_get ("evars",$g_tid,"","task_cache");

	if ((!$direct_settings['datacenter_uploads_localside'])&&(!$direct_settings['datacenter_uploads_serverside'])) { $g_continue_check = false; }
	elseif (($g_task_array)&&(isset ($g_task_array['core_sid'],$g_task_array['datacenter_upload_path'],$g_task_array['uuid']))&&(!$g_task_array['datacenter_upload_done'])&&($g_task_array['uuid'] == $direct_settings['uuid']))
	{
		$g_back_link = str_replace ("[oid]","",$g_task_array['core_back_return']);
		$g_continue_check = true;

		if ($g_mode_save) { direct_output_related_manager ("datacenter_uploads_preselect_form_save","pre_module_service_action"); }
		else
		{
			direct_output_related_manager ("datacenter_uploads_preselect_form","pre_module_service_action");
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
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formbuilder.php");
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_mods_support.php");

		direct_class_init ("formbuilder");
		$direct_classes['output']->servicemenu ("datacenter_upload");

		if (((isset ($g_task_array['datacenter_upload_only']))&&($g_task_array['datacenter_upload_only']))||((isset ($g_task_array['datacenter_upload_mode_physical']))&&($g_task_array['datacenter_upload_mode_physical']))) { $g_upload_check = true; }
		else { $g_upload_check = false; }

		if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) { $g_rights_check = true; }
		else { $g_rights_check = false; }

		if ($g_mode_save)
		{
/* -------------------------------------------------------------------------
We should have input in save mode
------------------------------------------------------------------------- */

			if ((!isset ($g_task_array['datacenter_upload_target_quantity_forced']))||(!$g_task_array['datacenter_upload_target_quantity_forced']))
			{
				$direct_cachedata['i_dquantity'] = (isset ($GLOBALS['i_dquantity']) ? ($direct_classes['basic_functions']->inputfilter_number ($GLOBALS['i_dquantity'])) : "");
				if ($direct_settings['datacenter_uploads_boxes_max'] < $direct_cachedata['i_dquantity']) { $direct_cachedata['i_dquantity'] = $direct_settings['datacenter_uploads_boxes_max']; }
			}

			if (((!isset ($g_task_array['datacenter_upload_mode_forced']))||(!$g_task_array['datacenter_upload_mode_forced']))&&($direct_settings['datacenter_uploads_localside'])&&($direct_settings['datacenter_uploads_serverside'])) { $direct_cachedata['i_dupload_mode'] = (isset ($GLOBALS['i_dupload_mode']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_dupload_mode'])) : ""); }

			if (!$g_upload_check)
			{
				if (((!isset ($g_task_array['datacenter_upload_target_did_forced']))||(!$g_task_array['datacenter_upload_target_did_forced']))&&(!$g_upload_check)) { $direct_cachedata['i_dtarget'] = $g_tid; }
				$direct_cachedata['i_dmode_all'] = (isset ($GLOBALS['i_dmode_all']) ? (str_replace ("'","",$GLOBALS['i_dmode_all'])) : "");
				$direct_cachedata['i_dmode_last'] = (isset ($GLOBALS['i_dmode_last']) ? (str_replace ("'","",$GLOBALS['i_dmode_last'])) : "");

				if ($g_rights_check)
				{
					$direct_cachedata['i_dtrusted'] = (isset ($GLOBALS['i_dtrusted']) ? (str_replace ("'","",$GLOBALS['i_dtrusted'])) : "");
					$direct_cachedata['i_dtrusted'] = str_replace ("<value value='$direct_cachedata[i_dtrusted]' />","<value value='$direct_cachedata[i_dtrusted]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

					$direct_cachedata['i_dsubs_allowed'] = (isset ($GLOBALS['i_dsubs_allowed']) ? (str_replace ("'","",$GLOBALS['i_dsubs_allowed'])) : "");
					$direct_cachedata['i_dsubs_allowed'] = str_replace ("<value value='$direct_cachedata[i_dsubs_allowed]' />","<value value='$direct_cachedata[i_dsubs_allowed]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

					$direct_cachedata['i_dsubs_hidden'] = (isset ($GLOBALS['i_dsubs_hidden']) ? (str_replace ("'","",$GLOBALS['i_dsubs_hidden'])) : "");
					$direct_cachedata['i_dsubs_hidden'] = str_replace ("<value value='$direct_cachedata[i_dsubs_hidden]' />","<value value='$direct_cachedata[i_dsubs_hidden]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

					$direct_cachedata['i_dsubs_type'] = (isset ($GLOBALS['i_dsubs_type']) ? (str_replace ("'","",$GLOBALS['i_dsubs_type'])) : 0);
				}
				elseif (isset ($g_task_array['datacenter_upload_subs_allowed']))
				{
					$direct_cachedata['i_dsubs_allowed'] = (isset ($GLOBALS['i_dsubs_allowed']) ? (str_replace ("'","",$GLOBALS['i_dsubs_allowed'])) : "");
					$direct_cachedata['i_dsubs_allowed'] = str_replace ("<value value='$direct_cachedata[i_dsubs_allowed]' />","<value value='$direct_cachedata[i_dsubs_allowed]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

					$direct_cachedata['i_dsubs_hidden'] = (isset ($GLOBALS['i_dsubs_hidden']) ? (str_replace ("'","",$GLOBALS['i_dsubs_hidden'])) : "");
					$direct_cachedata['i_dsubs_hidden'] = str_replace ("<value value='$direct_cachedata[i_dsubs_hidden]' />","<value value='$direct_cachedata[i_dsubs_hidden]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

					$direct_cachedata['i_dsubs_type'] = (isset ($GLOBALS['i_dsubs_type']) ? (str_replace ("'","",$GLOBALS['i_dsubs_type'])) : 0);
				}
			}

			$direct_cachedata['i_dtos'] = (isset ($GLOBALS['i_dtos']) ? (str_replace ("'","",$GLOBALS['i_dtos'])) : "");
			$direct_cachedata['i_dtos'] = str_replace ("<value value='$direct_cachedata[i_dtos]' />","<value value='$direct_cachedata[i_dtos]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");
		}
		else
		{
			if ((!isset ($g_task_array['datacenter_upload_target_quantity_forced']))||(!$g_task_array['datacenter_upload_target_quantity_forced']))
			{
				if (!isset ($g_task_array['datacenter_upload_target_quantity'])) { $g_task_array['datacenter_upload_target_quantity'] = $direct_settings['datacenter_uploads_boxes']; }
				if ($direct_settings['datacenter_uploads_boxes_max'] < $g_task_array['datacenter_upload_target_quantity']) { $g_task_array['datacenter_upload_target_quantity'] = $direct_settings['datacenter_uploads_boxes_max']; }
				$direct_cachedata['i_dquantity'] = $g_task_array['datacenter_upload_target_quantity'];
			}

			if (((!isset ($g_task_array['datacenter_upload_mode_forced']))||(!$g_task_array['datacenter_upload_mode_forced']))&&($direct_settings['datacenter_uploads_localside'])&&($direct_settings['datacenter_uploads_serverside']))
			{
				if (isset ($g_task_array['datacenter_upload_mode'])) { $direct_cachedata['i_dupload_mode'] = $g_task_array['datacenter_upload_mode']; }
				else { $direct_cachedata['i_dupload_mode'] = "local"; }
			}

			if (!$g_upload_check)
			{
				if (((!isset ($g_task_array['datacenter_upload_target_did_forced']))||(!$g_task_array['datacenter_upload_target_did_forced']))&&(!$g_upload_check))
				{
					$direct_cachedata['i_dtarget'] = $g_tid;

					if (!isset ($g_task_array['datacenter_selection_did']))
					{
						$g_task_array['datacenter_marker_title_0'] = direct_local_get ("datacenter_target_directory_select");
						$g_task_array['datacenter_marker_title_1'] = direct_local_get ("datacenter_target_directory_selected");
						$g_task_array['datacenter_marker_type'] = "dirs-writable-only";
						$g_task_array['datacenter_selection_done'] = 0;
						$g_task_array['datacenter_selection_quantity'] = 1;
						if (!isset ($g_task_array['datacenter_upload_target_did'])) { $g_task_array['datacenter_upload_target_did'] = "u-".$direct_settings['user']['id']; }
						$g_task_array['datacenter_objects_marked'] = array ($g_task_array['datacenter_upload_target_did'] => $g_task_array['datacenter_upload_target_did']);
						$g_task_array['datacenter_selection_did'] = $g_task_array['datacenter_upload_target_did'];

						direct_tmp_storage_write ($g_task_array,$g_tid,$g_task_array['core_sid'],"task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));
					}
				}

				$direct_cachedata['i_dmode_last'] = (isset ($g_task_array['datacenter_upload_mode_last']) ? (str_replace ("'","",$g_task_array['datacenter_upload_mode_last'])) : "r");
				$direct_cachedata['i_dmode_all'] = (isset ($g_task_array['datacenter_upload_mode_all']) ? (str_replace ("'","",$g_task_array['datacenter_upload_mode_all'])) : "r");

				if ($g_rights_check)
				{
					if ((isset ($g_task_array['datacenter_upload_trusted']))&&($g_task_array['datacenter_upload_trusted'])) { $direct_cachedata['i_dtrusted'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
					else { $direct_cachedata['i_dtrusted'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

					if ((isset ($g_task_array['datacenter_upload_subs_allowed']))&&($g_task_array['datacenter_upload_subs_allowed'])) { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
					else { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

					if ((isset ($g_task_array['datacenter_upload_subs_hidden']))&&($g_task_array['datacenter_upload_subs_hidden'])) { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
					else { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

					$direct_cachedata['i_dsubs_type'] = (isset ($g_task_array['datacenter_upload_subs_type']) ? str_replace ("'","",$g_task_array['datacenter_upload_subs_type']) : 0);
				}
				elseif (isset ($g_task_array['datacenter_upload_subs_allowed']))
				{
					if ((isset ($g_task_array['datacenter_upload_subs_allowed']))&&($g_task_array['datacenter_upload_subs_allowed'])) { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
					else { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

					if ((isset ($g_task_array['datacenter_upload_subs_hidden']))&&($g_task_array['datacenter_upload_subs_hidden'])) { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
					else { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

					$direct_cachedata['i_dsubs_type'] = (isset ($g_task_array['datacenter_upload_subs_type']) ? str_replace ("'","",$g_task_array['datacenter_upload_subs_type']) : 0);
				}
			}

			if (isset ($g_task_array['datacenter_upload_preselect_done'])) { $direct_cachedata['i_dtos'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
			else { $direct_cachedata['i_dtos'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
		}

		if (!$g_upload_check)
		{
$direct_cachedata['i_dmode_last'] = str_replace ("<value value='$direct_cachedata[i_dmode_last]' />","<value value='$direct_cachedata[i_dmode_last]' /><selected value='1' />","<evars>
<norights><value value='-' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_0"))."]]></text></norights><read><value value='r' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_r"))."]]></text></read><write><value value='w' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_w"))."]]></text></write>
</evars>");

$direct_cachedata['i_dmode_all'] = str_replace ("<value value='$direct_cachedata[i_dmode_all]' />","<value value='$direct_cachedata[i_dmode_all]' /><selected value='1' />","<evars>
<norights><value value='-' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_0"))."]]></text></norights><read><value value='r' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_r"))."]]></text></read><write><value value='w' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_w"))."]]></text></write>
</evars>");

			if (isset ($direct_cachedata['i_dsubs_type']))
			{
$direct_cachedata['i_dsubs_type'] = str_replace ("<value value='$direct_cachedata[i_dsubs_type]' />","<value value='$direct_cachedata[i_dsubs_type]' /><selected value='1' />","<evars>
<default><value value='0' /><text><![CDATA[".(direct_local_get ("core_datasub_title_default"))."]]></text></default><attachments><value value='1' /><text><![CDATA[".(direct_local_get ("core_datasub_title_attachments"))."]]></text></attachments><downloads><value value='2' /><text><![CDATA[".(direct_local_get ("core_datasub_title_downloads"))."]]></text></downloads><links><value value='3' /><text><![CDATA[".(direct_local_get ("core_datasub_title_links"))."]]></text></links>
</evars>");
			}
		}

		if (isset ($direct_cachedata['i_dupload_mode'])) { $direct_cachedata['i_dupload_mode'] = str_replace ("<value value='$direct_cachedata[i_dupload_mode]' />","<value value='$direct_cachedata[i_dupload_mode]' /><selected value='1' />","<evars><local><value value='local' /><text><![CDATA[".(direct_local_get ("datacenter_source_localside"))."]]></text></local><server><value value='server' /><text><![CDATA[".(direct_local_get ("datacenter_source_serverside"))."]]></text></server></evars>"); }

/* -------------------------------------------------------------------------
Build the form
------------------------------------------------------------------------- */

		if (isset ($direct_cachedata['i_dquantity']))
		{
			$direct_classes['formbuilder']->entry_add_number ("dquantity",(direct_local_get ("datacenter_upload_fields")),true,"s",1,$direct_settings['datacenter_uploads_boxes_max']);
			$direct_classes['formbuilder']->entry_add ("spacer");
		}

		if (isset ($direct_cachedata['i_dupload_mode']))
		{
			$direct_classes['formbuilder']->entry_add_radio ("dupload_mode",(direct_local_get ("datacenter_source_select")),true);
			$direct_classes['formbuilder']->entry_add ("spacer");
		}

		if (!$g_upload_check)
		{
			if (isset ($direct_cachedata['i_dtarget']))
			{
				$direct_classes['formbuilder']->entry_add_embed ("dtarget",(direct_local_get ("datacenter_target_directory")),true,"m=dataport&s=swgap;datacenter;selector&a=list&dsd=","s");
				$direct_classes['formbuilder']->entry_add ("spacer");
			}

			if ($g_rights_check) { $direct_classes['formbuilder']->entry_add_select ("dtrusted",(direct_local_get ("datacenter_trusted")),true,"s",(direct_local_get ("datacenter_helper_trusted")),"",true); }

			$direct_classes['formbuilder']->entry_add_select ("dmode_last",(direct_local_get ("datacenter_mode_last")),false,"s");
			$direct_classes['formbuilder']->entry_add_select ("dmode_all",(direct_local_get ("datacenter_mode_all")),true,"s",(direct_local_get ("datacenter_helper_mode_dir")),"",true);

			if (($g_rights_check)||(isset ($direct_cachedata['i_dsubs_allowed'])))
			{
				$direct_classes['formbuilder']->entry_add ("spacer");
				$direct_classes['formbuilder']->entry_add_select ("dsubs_allowed",(direct_local_get ("core_datasub_allowed")),true,"s");
				$direct_classes['formbuilder']->entry_add_select ("dsubs_hidden",(direct_local_get ("core_datasub_hide")),true,"s");
				$direct_classes['formbuilder']->entry_add_radio ("dsubs_type",(direct_local_get ("core_datasub_type")),true);
			}

			$direct_classes['formbuilder']->entry_add ("spacer");
		}

		$direct_classes['formbuilder']->entry_add_file_ftg ("datacenter_uploads_tos",(direct_local_get ("datacenter_uploads_tos")),$direct_settings['path_data']."/settings/swg_datacenter_uploads_tos.ftf","l");
		$direct_classes['formbuilder']->entry_add_select ("dtos",(direct_local_get ("datacenter_uploads_tos_accept")),true,"s");

		if ($g_mode_save) { direct_mods_include ($direct_settings['datacenter_uploads_mods_support'],"datacenter_uploads","preselect_check",$g_task_array); }
		else { direct_mods_include ($direct_settings['datacenter_uploads_mods_support'],"datacenter_uploads","preselect",$g_task_array); }

		$direct_cachedata['output_formelements'] = $direct_classes['formbuilder']->form_get ($g_mode_save);

		if (($g_mode_save)&&($direct_classes['formbuilder']->check_result))
		{
/* -------------------------------------------------------------------------
Save data edited
------------------------------------------------------------------------- */

			if ($direct_cachedata['i_dtos'])
			{
				if (direct_mods_include ($direct_settings['datacenter_uploads_mods_support'],"datacenter_uploads","test")) { $g_task_array = direct_mods_include (true,"datacenter_uploads","preselect_save",$g_task_array); }

				if (isset ($g_task_array['datacenter_upload_path']))
				{
					$g_task_array['datacenter_upload_preselect_done'] = 1;

					if (isset ($direct_cachedata['i_dquantity'])) { $g_task_array['datacenter_upload_target_quantity'] = $direct_cachedata['i_dquantity']; }
					elseif (!isset ($g_task_array['datacenter_upload_target_quantity'])) { $g_task_array['datacenter_upload_target_quantity'] = $direct_settings['datacenter_uploads_boxes']; }

					if (isset ($direct_cachedata['i_dupload_mode'])) { $g_task_array['datacenter_upload_mode'] = $direct_cachedata['i_dupload_mode']; }
					elseif (!isset ($g_task_array['datacenter_upload_mode']))
					{
						if ($direct_settings['datacenter_uploads_localside']) { $g_task_array['datacenter_upload_mode'] = "local"; }
						else { $g_task_array['datacenter_upload_mode'] = "server"; }
					}

					if ((isset ($g_task_array['datacenter_objects_marked']))&&(is_array ($g_task_array['datacenter_objects_marked']))&&(!empty ($g_task_array['datacenter_objects_marked'])))
					{
						$g_objects_marked_array = $g_task_array['datacenter_objects_marked'];
						$g_task_array['datacenter_upload_target_did'] = array_shift ($g_objects_marked_array);
					}
					else { $g_task_array['datacenter_upload_target_did'] = "u-".$direct_settings['user']['id']; }

					if (isset ($direct_cachedata['i_dmode_last'])) { $g_task_array['datacenter_upload_mode_last'] = $direct_cachedata['i_dmode_last']; }
					elseif (!isset ($g_task_array['datacenter_upload_mode_last'])) { $g_task_array['datacenter_upload_mode_last'] = "r"; }

					if (isset ($direct_cachedata['i_dmode_all'])) { $g_task_array['datacenter_upload_mode_all'] = $direct_cachedata['i_dmode_all']; }
					elseif (!isset ($g_task_array['datacenter_upload_mode_all'])) { $g_task_array['datacenter_upload_mode_all'] = "r"; }

					$g_task_array['datacenter_upload_trusted'] = 0;

					if ($g_rights_check)
					{
						$g_task_array['datacenter_upload_trusted'] = (isset ($direct_cachedata['i_dtrusted']) ? $direct_cachedata['i_dtrusted'] : 0);
						$g_task_array['datacenter_upload_subs_allowed'] = (isset ($direct_cachedata['i_dsubs_allowed']) ? $direct_cachedata['i_dsubs_allowed'] : 0);
						$g_task_array['datacenter_upload_subs_hidden'] = (isset ($direct_cachedata['i_dsubs_hidden']) ? $direct_cachedata['i_dsubs_hidden'] : 0);
						$g_task_array['datacenter_upload_subs_type'] = (isset ($direct_cachedata['i_dsubs_type']) ? $direct_cachedata['i_dsubs_type'] : 0);
					}
					elseif (isset ($g_task_array['datacenter_upload_subs_allowed']))
					{
						$g_task_array['datacenter_upload_subs_allowed'] = (isset ($direct_cachedata['i_dsubs_allowed']) ? $direct_cachedata['i_dsubs_allowed'] : 0);
						$g_task_array['datacenter_upload_subs_hidden'] = (isset ($direct_cachedata['i_dsubs_hidden']) ? $direct_cachedata['i_dsubs_hidden'] : 0);
						$g_task_array['datacenter_upload_subs_type'] = (isset ($direct_cachedata['i_dsubs_type']) ? $direct_cachedata['i_dsubs_type'] : 0);
					}

					if (!isset ($g_task_array['datacenter_upload_dirname_length'])) { $g_task_array['datacenter_upload_dirname_length'] = $direct_settings['datacenter_uploads_dirname_length']; }
					direct_tmp_storage_write ($g_task_array,$g_tid,$g_task_array['core_sid'],"task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));

					if ($g_task_array['datacenter_upload_mode'] == "server") { $direct_classes['output']->redirect (direct_linker ("url1","m=datacenter&s=uploads_server&a=files&dsd=tid+".$g_tid,false)); }
					else { $direct_classes['output']->redirect (direct_linker ("url1","m=datacenter&s=uploads_local&a=files&dsd=tid+".$g_tid,false)); }
				}
				else { $direct_classes['error_functions']->error_page ("standard","core_unknown_error","sWG/#echo(__FILEPATH__)# _a=preselect-save_ (#echo(__LINE__)#)"); }
			}
			else { $direct_classes['error_functions']->error_page ("standard","datacenter_uploads_tos_required","sWG/#echo(__FILEPATH__)# _a=preselect-save_ (#echo(__LINE__)#)"); }
		}
		else
		{
/* -------------------------------------------------------------------------
View form
------------------------------------------------------------------------- */

			$direct_cachedata['output_formbutton'] = direct_local_get ("core_continue");
			$direct_cachedata['output_formtarget'] = "m=datacenter&s=uploads&a=preselect-save&dsd=tid+".$g_tid;

			if ((isset ($g_task_array['datacenter_upload_overwrite_title']))&&($g_task_array['datacenter_upload_overwrite_title'])) { $direct_cachedata['output_formtitle'] = $g_task_array['datacenter_upload_overwrite_title']; }
			else { $direct_cachedata['output_formtitle'] = direct_local_get ("datacenter_file_new"); }

			direct_output_related_manager ("datacenter_uploads_preselect_form","post_module_service_action");
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
//j// $direct_settings['a'] == "results"
case "results":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=results_ (#echo(__LINE__)#)"); }

	$g_tid = (isset ($direct_settings['dsd']['tid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['tid'])) : "");

	$direct_cachedata['page_this'] = "m=datacenter&s=uploads&a=preselect&dsd=tid+".$g_tid;
	$direct_cachedata['page_backlink'] = "";
	$direct_cachedata['page_homelink'] = "";

	if ($direct_classes['kernel']->service_init_default ())
	{
	//j// BOA
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php");

	if ($g_tid == "") { $g_tid = $direct_settings['uuid']; }
	$g_task_array = direct_tmp_storage_get ("evars",$g_tid,"","task_cache");

	if (($g_task_array)&&(isset ($g_task_array['core_sid'],$g_task_array['datacenter_upload_results'],$g_task_array['uuid']))&&($g_task_array['datacenter_upload_done'])&&($g_task_array['uuid'] == $direct_settings['uuid']))
	{
		$g_back_link = str_replace ("[oid]","",$g_task_array['core_back_return']);
		$g_continue_check = true;

		direct_output_related_manager ("datacenter_uploads_results","pre_module_service_action");
		$direct_cachedata['page_backlink'] = $g_back_link;
		$direct_cachedata['page_homelink'] = $g_back_link;
	}
	else { $g_continue_check = false; }

	$direct_classes['kernel']->service_https ($direct_settings['datacenter_https_control_objects'],$direct_cachedata['page_this']);
	direct_local_integration ("datacenter");

	direct_class_init ("output");
	if ($direct_cachedata['page_backlink']) { $direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0"); }

	if ($g_continue_check)
	{
		if ((isset ($g_task_array['datacenter_upload_return_invisible']))&&($g_task_array['datacenter_upload_return_invisible'])) { $direct_classes['output']->redirect (direct_linker ("url1",$g_task_array['datacenter_upload_return'],false)); }
		elseif (is_array ($g_task_array['datacenter_upload_results']))
		{
			if ((isset ($g_task_array['datacenter_upload_overwrite_title']))&&($g_task_array['datacenter_upload_overwrite_title'])) { $direct_cachedata['output_job'] = $g_task_array['datacenter_upload_overwrite_title']; }
			elseif ($g_task_array['datacenter_upload_mode'] == "server") { $direct_cachedata['output_job'] = direct_local_get ("datacenter_source_serverside"); }
			else { $direct_cachedata['output_job'] = direct_local_get ("datacenter_source_localside"); }

			$direct_cachedata['output_job_desc'] = direct_local_get ("datacenter_done_file_new");
			$direct_cachedata['output_job_extension'] = "";
			$direct_cachedata['output_pagetarget'] = direct_linker ("url0",$g_task_array['datacenter_upload_return']);

			foreach ($g_task_array['datacenter_upload_results'] as $g_result_array)
			{
				if ($direct_cachedata['output_job_extension']) { $direct_cachedata['output_job_extension'] .= "<br />\n"; }

				if (isset ($g_result_array['error']))
				{
					$direct_cachedata['output_job_extension'] .= (direct_html_encode_special ($g_result_array['name'])).": ";
					$g_error_array = explode (":",$g_result_array['error'],2);

					switch ($g_error_array[0])
					{
					case "credits":
					{
/* -------------------------------------------------------------------------
x Credits are required but not available
------------------------------------------------------------------------- */

						$direct_cachedata['output_job_extension'] .= (direct_local_get ("datacenter_upload_failed_credits_1")).$g_error_array[1].(direct_local_get ("datacenter_upload_failed_credits_2"));
						break 1;
					}
					case "php_error":
					{
/* -------------------------------------------------------------------------
System reports HTML Error Code x
------------------------------------------------------------------------- */

						$direct_cachedata['output_job_extension'] .= (direct_local_get ("datacenter_upload_failed_php_error_1")).$g_error_array[1].(direct_local_get ("datacenter_upload_failed_php_error_2"));
						break 1;
					}
					case "server_response_error":
					{
/* -------------------------------------------------------------------------
System reports HTML Error Code x
------------------------------------------------------------------------- */

						$direct_cachedata['output_job_extension'] .= (direct_local_get ("datacenter_upload_failed_server_response_error_1")).$g_error_array[1].(direct_local_get ("datacenter_upload_failed_server_response_error_2"));
						break 1;
					}
					default: { $direct_cachedata['output_job_extension'] .= (direct_local_get ("datacenter_upload_failed_".$g_error_array[0])); }
/* -------------------------------------------------------------------------
Other error messages ...
------------------------------------------------------------------------- */

					}
				}
				else
				{
					$direct_cachedata['output_job_extension'] .= (direct_html_encode_special ($g_result_array['name'])).": ";
					$direct_cachedata['output_job_extension'] .= direct_local_get ("datacenter_upload_completed");
				}
			}

			direct_output_related_manager ("datacenter_uploads_results","post_module_service_action");
			$direct_classes['output']->oset ("default","done_extended");
			$direct_classes['output']->options_flush (true);
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_job']);
		}
		else { $direct_classes['error_functions']->error_page ("standard","core_unknown_error","SERVICE ERROR:<br />Result list has no entries.<br /><br />sWG/#echo(__FILEPATH__)# _a=results_ (#echo(__LINE__)#)"); }
	}
	else { $direct_classes['error_functions']->error_page ("standard","core_tid_invalid","sWG/#echo(__FILEPATH__)# _a=results_ (#echo(__LINE__)#)"); }
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// EOS
}

//j// EOF
?>