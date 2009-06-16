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
$Id: swg_selector.php,v 1.1 2009/03/16 07:55:58 s4u Exp $
#echo(sWGdatacenterVersion)#
sWG/#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* dataport/swgap/datacenter/swg_selector.php
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

if (!isset ($direct_settings['datacenter_https_selector'])) { $direct_settings['datacenter_https_selector'] = false; }
if (!isset ($direct_settings['datacenter_objects_per_page'])) { $direct_settings['datacenter_objects_per_page'] = 25; }
if (!isset ($direct_settings['serviceicon_datacenter_selector_marker'])) { $direct_settings['serviceicon_datacenter_selector_marker'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_default_back'])) { $direct_settings['serviceicon_default_back'] = "mini_default_back.png"; }
$direct_settings['additional_copyright'][] = array ("Module datacenter #echo(sWGdatacenterVersion)# - (C) ","http://www.direct-netware.de/redirect.php?swg","direct Netware Group"," - All rights reserved");

if ($direct_settings['a'] == "index") { $direct_settings['a'] = "list"; }
//j// BOS
switch ($direct_settings['a'])
{
//j// $direct_settings['a'] == "list"
case "list":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=list_ (#echo(__LINE__)#)"); }

	$g_oid = (isset ($direct_settings['dsd']['doid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['doid'])) : "");
	$direct_cachedata['output_tid'] = (isset ($direct_settings['dsd']['tid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['tid'])) : "");
	$direct_cachedata['output_page'] = (isset ($direct_settings['dsd']['page']) ? ($direct_classes['basic_functions']->inputfilter_number ($direct_settings['dsd']['page'])) : 1);

	if ((isset ($direct_settings['dsd']['dtheme']))&&($direct_settings['dsd']['dtheme']))
	{
		$g_dtheme = true;

		if ($direct_settings['dsd']['dtheme'] == 2)
		{
			$g_dtheme_embedded = true;
			$g_dtheme_mode = 2;
		}
		else
		{
			$g_dtheme_embedded = false;
			$g_dtheme_mode = 1;
		}

		$direct_cachedata['page_this'] = "m=dataport&s=swgap;datacenter;selector&a=list&dsd=dtheme+{$g_dtheme_mode}++doid+{$g_oid}++tid+{$direct_cachedata['output_tid']}++page+".$direct_cachedata['output_page'];
		$direct_cachedata['page_backlink'] = "";
		$direct_cachedata['page_homelink'] = "";

		$g_continue_check = $direct_classes['kernel']->service_init_default ();
	}
	else
	{
		$g_dtheme = false;
		$g_dtheme_embedded = false;
		$g_dtheme_mode = 0;

		$g_continue_check = $direct_classes['kernel']->service_init_rboolean ();
	}

	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_datacenter.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter_home.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php"); }

	if ($g_continue_check)
	{
	//j// BOA
	if ($direct_cachedata['output_tid'] == "") { $direct_cachedata['output_tid'] = $direct_settings['uuid']; }
	$g_task_array = direct_tmp_storage_get ("evars",$direct_cachedata['output_tid'],"","task_cache");

	if (($g_task_array)&&(isset ($g_task_array['core_sid'],$g_task_array['uuid']))&&(!$g_task_array['datacenter_selection_done'])&&($g_task_array['uuid'] == $direct_settings['uuid']))
	{
		if ($g_dtheme_embedded) { direct_output_related_manager ("datacenter_selector_list","pre_module_service_action_embedded"); }
		elseif ($g_dtheme) { direct_output_related_manager ("datacenter_selector_list","pre_module_service_action"); }
		else { direct_output_related_manager ("datacenter_selector_list","pre_module_service_action_ajax"); }

		$direct_cachedata['page_backlink'] = str_replace ("[oid]","",$g_task_array['core_back_return']);
		$direct_cachedata['page_homelink'] = $direct_cachedata['page_backlink'];

		if ((!$g_oid)&&(isset ($g_task_array['datacenter_selection_did']))) { $g_oid = $g_task_array['datacenter_selection_did']; }
		if ((!isset ($g_task_array['datacenter_objects_marked']))||(!is_array ($g_task_array['datacenter_objects_marked']))) { $g_task_array['datacenter_objects_marked'] = array (); }
	}
	else { $g_continue_check = false; }

	if ($g_dtheme) { $direct_classes['kernel']->service_https ($direct_settings['datacenter_https_selector'],$direct_cachedata['page_this']); }
	if ($g_dtheme_embedded) { direct_output_theme_subtype ("embedded"); }
	direct_local_integration ("datacenter");

	direct_class_init ("output");
	if (($g_dtheme)&&($direct_cachedata['page_backlink'])) { $direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0"); }

	if ($g_continue_check)
	{
		if (strpos ($g_oid,"-") === false) { $g_datacenter_object = new direct_datacenter (); }
		else { $g_datacenter_object = new direct_datacenter_home (); }

		if ($g_datacenter_object)
		{
			$g_datacenter_object->define_markers ($g_task_array['datacenter_objects_marked']);

			if ($g_dtheme) { $g_datacenter_object->define_marker_connector ("m=dataport&s=swgap;datacenter;selector&a=mark_switch&dsd=dtheme+{$g_dtheme_mode}++[oid]tid+{$direct_cachedata['output_tid']}++page+".$direct_cachedata['output_page']); }
			else { $g_datacenter_object->define_marker_connector ("javascript:djs_dataport_{$direct_cachedata['output_tid']}_call_url0('m=dataport&amp;s=swgap;datacenter;selector&amp;a=mark_switch&amp;dsd=dtheme+{$g_dtheme_mode}++tid+{$direct_cachedata['output_tid']}++doid+[oid]++page+{$direct_cachedata['output_page']}')","asis"); }

			if (isset ($g_task_array['datacenter_marker_type'])) { $g_datacenter_object->define_marker_type ($g_task_array['datacenter_marker_type']); }
			if ((isset ($g_task_array['datacenter_marker_title_0']))&&(isset ($g_task_array['datacenter_marker_title_1']))) { $g_datacenter_object->define_marker_titles ($g_task_array['datacenter_marker_title_0'],$g_task_array['datacenter_marker_title_1']); }

			$g_datacenter_array = $g_datacenter_object->get ($g_oid);
		}
		else { $g_datacenter_array = NULL; }

		if ($g_datacenter_array)
		{
			$direct_cachedata['output_pages'] = ceil ($g_datacenter_array['ddbdatalinker_objects'] / $direct_settings['datacenter_objects_per_page']);
			if ($direct_cachedata['output_pages'] < 1) { $direct_cachedata['output_pages'] = 1; }
			if ((!$direct_cachedata['output_page'])||($direct_cachedata['output_page'] < 1)) { $direct_cachedata['output_page'] = 1; }

			$g_offset = (($direct_cachedata['output_page'] - 1) * $direct_settings['datacenter_objects_per_page']);
			$g_datacenter_entries_array = $g_datacenter_object->get_objects (1,$g_offset,$direct_settings['datacenter_objects_per_page']);
		}

		if (!is_array ($g_datacenter_entries_array))
		{
			if ($g_dtheme) { $direct_classes['error_functions']->error_page ("standard","datacenter_did_invalid","sWG/#echo(__FILEPATH__)# _a=list_ (#echo(__LINE__)#)"); }
		}
		elseif ($g_datacenter_object->is_readable ())
		{
			if ($g_dtheme) { $direct_cachedata['output_dir'] = $g_datacenter_object->parse ("m=dataport&s=swgap;datacenter;selector&a=[a]&dsd=dtheme+{$g_dtheme_mode}++[oid][page{$direct_cachedata['output_page']}]tid+".$direct_cachedata['output_tid']); }
			else { $direct_cachedata['output_dir'] = $g_datacenter_object->parse ("javascript:djs_dataport_{$direct_cachedata['output_tid']}_call_url0('m=dataport&amp;s=swgap;datacenter;selector&amp;a=[a]&amp;dsd=dtheme+{$g_dtheme_mode}++tid+{$direct_cachedata['output_tid']}++doid+[oid]++page+[page{$direct_cachedata['output_page']}]')","asis"); }

			if ($g_task_array['datacenter_marker_type'] != "files-only") { $direct_classes['output']->options_insert (1,"servicemenu",$direct_cachedata['output_dir']['pageurl_marker'],$direct_cachedata['output_dir']['marker_title'],$direct_settings['serviceicon_datacenter_selector_marker'],"asis"); }

			if ($g_datacenter_array['ddbdatalinker_id_parent'])
			{
				if (strpos ($g_datacenter_array['ddbdatalinker_id_parent'],"-") === false) { $g_datacenter_parent_object = new direct_datacenter (); }
				else { $g_datacenter_parent_object = new direct_datacenter_home (); }

				if (($g_datacenter_parent_object)&&($g_datacenter_parent_object->get ($g_datacenter_array['ddbdatalinker_id_parent'])))
				{
					if ($g_dtheme) { $direct_cachedata['output_dir_levelup'] = $g_datacenter_parent_object->parse ("m=dataport&s=swgap;datacenter;selector&a=[a]&dsd=dtheme+{$g_dtheme_mode}++[oid][page]tid+".$direct_cachedata['output_tid']); }
					else { $direct_cachedata['output_dir_levelup'] = $g_datacenter_parent_object->parse ("javascript:djs_dataport_{$direct_cachedata['output_tid']}_call_url0('m=dataport&amp;s=swgap;datacenter;selector&amp;a=[a]&amp;dsd=dtheme+{$g_dtheme_mode}++doid+[oid]++tid+{$direct_cachedata['output_tid']}++page+[page]')","asis"); }
				}
			}

			if (!empty ($g_datacenter_entries_array))
			{
				foreach ($g_datacenter_entries_array as $g_datacenter_entry_object)
				{
					if ($g_dtheme) { $direct_cachedata['output_objects'][] = $g_datacenter_entry_object->parse ("m=dataport&s=swgap;datacenter;selector&a=list&dsd=dtheme+{$g_dtheme_mode}++[oid][page]tid+".$direct_cachedata['output_tid']); }
					else { $direct_cachedata['output_objects'][] = $g_datacenter_entry_object->parse ("javascript:djs_dataport_{$direct_cachedata['output_tid']}_call_url0('m=dataport&amp;s=swgap;datacenter;selector&amp;a=list&amp;dsd=dtheme+{$g_dtheme_mode}++doid+[oid]++tid+{$direct_cachedata['output_tid']}++page+[page]')","asis"); }
				}
			}

			if ((isset ($g_task_array['datacenter_selection_title']))&&($g_task_array['datacenter_selection_title'])) { $g_selector_title = $g_task_array['datacenter_selection_title']; }
			else { $g_selector_title = $direct_cachedata['output_dir']['title']; }

			if ($g_dtheme)
			{
				if ($g_dtheme_embedded)
				{
					direct_output_related_manager ("datacenter_selector_list","post_module_service_action_embedded");
					$direct_cachedata['output_page_url'] = "m=dataport&s=swgap;datacenter;selector&a=list&dsd=dtheme+2++tid+{$direct_cachedata['output_tid']}++";
					$direct_classes['output']->oset ("datacenter_embedded","selector");
				}
				else
				{
					direct_output_related_manager ("datacenter_selector_list","post_module_service_action");
					$direct_cachedata['output_page_url'] = "m=dataport&s=swgap;datacenter;selector&a=list&dsd=dtheme+1++tid+{$direct_cachedata['output_tid']}++";
					$direct_classes['output']->oset ("datacenter","selector");
				}

				$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
				$direct_classes['output']->page_show ($g_selector_title);
			}
			else
			{
				$direct_cachedata['output_page_url'] = "javascript:djs_dataport_{$direct_cachedata['output_tid']}_call_url0('m=dataport&amp;s=swgap;datacenter;selector&amp;a=list&amp;dsd=dtheme+0++tid+{$direct_cachedata['output_tid']}++page+[page]')";

				$direct_classes['output']->header (false);
				header ("Content-type: text/xml; charset=".$direct_local['lang_charset']);

echo ("<?xml version='1.0' encoding='$direct_local[lang_charset]' ?>
".(direct_output_smiley_decode ($direct_classes['output']->oset_content ("datacenter_embedded","ajax_selector"))));
			}
		}
		elseif ($g_dtheme) { $direct_classes['error_functions']->error_page ("standard","core_access_denied","sWG/#echo(__FILEPATH__)# _a=list_ (#echo(__LINE__)#)"); }
	}
	elseif ($g_dtheme) { $direct_classes['error_functions']->error_page ("standard","core_tid_invalid","sWG/#echo(__FILEPATH__)# _a=list_ (#echo(__LINE__)#)"); }
	//j// BOA
	}

	$direct_cachedata['core_service_activated'] = 1;
	break 1;
}
//j// $direct_settings['a'] == "mark_switch"
case "mark_switch":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=mark_switch_ (#echo(__LINE__)#)"); }

	$g_oid = (isset ($direct_settings['dsd']['doid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['doid'])) : "");
	$g_tid = (isset ($direct_settings['dsd']['tid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['tid'])) : "");
	$g_page = (isset ($direct_settings['dsd']['page']) ? ($direct_classes['basic_functions']->inputfilter_number ($direct_settings['dsd']['page'])) : 1);

	if ((isset ($direct_settings['dsd']['dtheme']))&&($direct_settings['dsd']['dtheme']))
	{
		$g_dtheme = true;

		if ($direct_settings['dsd']['dtheme'] == 2)
		{
			$g_dtheme_embedded = true;
			$g_dtheme_mode = 2;
		}
		else
		{
			$g_dtheme_embedded = false;
			$g_dtheme_mode = 1;
		}

		$direct_cachedata['page_this'] = "";
		$direct_cachedata['page_backlink'] = "";
		$direct_cachedata['page_homelink'] = "";

		$g_continue_check = $direct_classes['kernel']->service_init_default ();
	}
	else
	{
		$g_dtheme = false;
		$g_dtheme_embedded = false;
		$g_dtheme_mode = 0;

		$g_continue_check = $direct_classes['kernel']->service_init_rboolean ();
	}

	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_datacenter.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter_home.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php"); }

	if ($g_continue_check)
	{
	//j// BOA
	$g_task_array = direct_tmp_storage_get ("evars",$g_tid,"","task_cache");

	if (($g_task_array)&&(isset ($g_task_array['core_sid'],$g_task_array['uuid']))&&(!$g_task_array['datacenter_selection_done'])&&($g_task_array['uuid'] == $direct_settings['uuid']))
	{
		if ($g_dtheme_embedded) { direct_output_related_manager ("datacenter_selector_mark_switch","pre_module_service_action_embedded"); }
		elseif ($g_dtheme) { direct_output_related_manager ("datacenter_selector_mark_switch","pre_module_service_action"); }
		else { direct_output_related_manager ("datacenter_selector_mark_switch","pre_module_service_action_ajax"); }

		if (!isset ($g_task_array['datacenter_selection_customized'])) { $g_task_array['datacenter_selection_customized'] = "selector"; }

		$direct_cachedata['page_backlink'] = "m=dataport&s=swgap;datacenter;{$g_task_array['datacenter_selection_customized']}&a=list&dsd=dtheme+{$g_dtheme_mode}++doid+{$g_oid}++tid+{$g_tid}++page+".$g_page;
		$direct_cachedata['page_homelink'] = str_replace ("[oid]","",$g_task_array['core_back_return']);

		if ((!$g_oid)&&($g_task_array['datacenter_selection_did'])) { $g_oid = $g_task_array['datacenter_selection_did']; }
		if ((!isset ($g_task_array['datacenter_objects_marked']))||(!is_array ($g_task_array['datacenter_objects_marked']))) { $g_task_array['datacenter_objects_marked'] = array (); }
	}
	else { $g_continue_check = false; }

	if ($g_dtheme_embedded) { direct_output_theme_subtype ("embedded"); }
	direct_local_integration ("datacenter");

	direct_class_init ("output");
	if ($g_dtheme) { $direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0"); }

	if ($g_continue_check)
	{
		if (strpos ($g_oid,"-") === false) { $g_datacenter_object = new direct_datacenter (); }
		else { $g_datacenter_object = new direct_datacenter_home (); }

		if ($g_datacenter_object) { $g_datacenter_array = $g_datacenter_object->get ($g_oid); }
		else { $g_datacenter_array = NULL; }

		if (!is_array ($g_datacenter_array))
		{
			if ($g_dtheme) { $direct_classes['error_functions']->error_page ("standard","datacenter_oid_invalid","sWG/#echo(__FILEPATH__)# _a=mark_switch_ (#echo(__LINE__)#)"); }
		}
		elseif ($g_datacenter_object->is_readable ())
		{
			if (($g_task_array['datacenter_marker_type'] == "files-only")&&($g_datacenter_object->is_directory ())) { $g_filter_check = false; }
			elseif (($g_task_array['datacenter_marker_type'] == "dirs-only")&&(!$g_datacenter_object->is_directory ())) { $g_filter_check = false; }
			else { $g_filter_check = true; }

			$g_oid_id = strtolower (preg_replace ("#\W#","_",$g_datacenter_array['ddbdatalinker_id']));

			if (in_array ($g_datacenter_array['ddbdatalinker_id'],$g_task_array['datacenter_objects_marked'])) { unset ($g_task_array['datacenter_objects_marked'][$g_oid_id]); }
			elseif ($g_filter_check)
			{
				if ($g_task_array['datacenter_selection_quantity'] > count ($g_task_array['datacenter_objects_marked'])) { $g_task_array['datacenter_objects_marked'][$g_oid_id] = $g_datacenter_array['ddbdatalinker_id']; }
				else
				{
					array_shift ($g_task_array['datacenter_objects_marked']);
					$g_task_array['datacenter_objects_marked'][$g_oid_id] = $g_datacenter_array['ddbdatalinker_id'];
				}
			}

			if (($g_task_array['datacenter_marker_return'])&&($g_filter_check)) { $g_task_array['datacenter_selection_done'] = 1; }
			direct_tmp_storage_write ($g_task_array,$g_tid,$g_task_array['core_sid'],"task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));

			if (($g_task_array['datacenter_marker_return'])&&($g_filter_check)) { $direct_classes['output']->redirect (direct_linker ("url1",$g_task_array['datacenter_marker_return'],false)); }
			else { $direct_classes['output']->redirect (direct_linker ("url1","m=dataport&s=swgap;datacenter;{$g_task_array['datacenter_selection_customized']}&a=list&dsd=dtheme+{$g_dtheme_mode}++doid+{$g_datacenter_array['ddbdatalinker_id_parent']}++tid+{$g_tid}++page+{$g_page}#swgdhandlerdatacenter".$g_oid_id,false)); }
		}
		elseif ($g_dtheme) { $direct_classes['error_functions']->error_page ("standard","core_access_denied","sWG/#echo(__FILEPATH__)# _a=mark_switch_ (#echo(__LINE__)#)"); }
	}
	elseif ($g_dtheme) { $direct_classes['error_functions']->error_page ("standard","core_tid_invalid","sWG/#echo(__FILEPATH__)# _a=mark_switch_ (#echo(__LINE__)#)"); }
	//j// BOA
	}

	$direct_cachedata['core_service_activated'] = 1;
	break 1;
}
//j// EOS
}

//j// EOF
?>