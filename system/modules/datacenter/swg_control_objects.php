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
* datacenter/swg_control_objects.php
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
if (!isset ($direct_settings['datacenter_file_delete_credits_onetime'])) { $direct_settings['datacenter_files_delete_credits_onetime'] = 0; }
if (!isset ($direct_settings['datacenter_file_edit_credits_onetime'])) { $direct_settings['datacenter_files_edit_credits_onetime'] = 0; }
if (!isset ($direct_settings['datacenter_link_redirect_jsjump'])) { $direct_settings['datacenter_link_redirect_jsjump'] = 15000; }
if (!isset ($direct_settings['datacenter_path_upload'])) { $direct_settings['datacenter_path_upload'] = $direct_settings['path_data']."/uploads/"; }
if (!isset ($direct_settings['datacenter_uploads_localside'])) { $direct_settings['datacenter_uploads_localside'] = true; }
if (!isset ($direct_settings['datacenter_uploads_mods_support'])) { $direct_settings['datacenter_uploads_mods_support'] = false; }
if (!isset ($direct_settings['datacenter_uploads_serverside'])) { $direct_settings['datacenter_uploads_serverside'] = true; }
if (!isset ($direct_settings['formtags_overview_document_url'])) { $direct_settings['formtags_overview_document_url'] = "m=contentor&a=view&dsd=cdid+dng_{$direct_settings['lang']}_2_90000000001"; }
if (!isset ($direct_settings['serviceicon_datacenter_file_replace'])) { $direct_settings['serviceicon_datacenter_file_replace'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_datacenter_link_replace'])) { $direct_settings['serviceicon_datacenter_link_replace'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_default_back'])) { $direct_settings['serviceicon_default_back'] = "mini_default_back.png"; }
$direct_settings['additional_copyright'][] = array ("Module datacenter #echo(sWGdatacenterVersion)# - (C) ","http://www.direct-netware.de/redirect.php?swg","direct Netware Group"," - All rights reserved");

//j// BOS
switch ($direct_settings['a'])
{
//j// $direct_settings['a'] == "delete"
case "delete":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=delete_ (#echo(__LINE__)#)"); }

	$g_oid = (isset ($direct_settings['dsd']['doid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['doid'])) : "");
	$g_connector = (isset ($direct_settings['dsd']['connector']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['connector'])) : "");
	$g_source = (isset ($direct_settings['dsd']['source']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['source'])) : "");
	$g_target = (isset ($direct_settings['dsd']['target']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['target'])) : "");

	$g_connector_url = ($g_connector ? base64_decode ($g_connector) : "m=datacenter&s=media&a=[a]&dsd=[oid]");
	$g_source_url = ($g_source ? base64_decode ($g_source) : "m=datacenter&s=media&dsd=[oid]");

	if ($g_target) { $g_target_url = base64_decode ($g_target); }
	else
	{
		$g_target = $g_source;
		$g_target_url = ($g_source ? $g_source_url : "");
	}

	$g_back_link = (((!$g_source)&&($g_connector_url)) ? preg_replace (array ("#\[a\]#","#\[oid\]#","#\[(.*?)\]#"),(array ("view","doid+{$g_oid}++","")),$g_connector_url) : str_replace ("[oid]","doid+{$g_oid}++",$g_source_url));

	$direct_cachedata['page_this'] = "m=datacenter&s=control_objects&a=delete&dsd=doid+$g_oid++connector+".(urlencode ($g_connector))."++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
	$direct_cachedata['page_backlink'] = $g_back_link;
	$direct_cachedata['page_homelink'] = $g_back_link;

	if ($direct_classes['kernel']->service_init_default ())
	{
	//j// BOA
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter_home.php");
	direct_local_integration ("datacenter");

	$g_datacenter_object = new direct_datacenter ();
	$g_datasub_check = false;
	$g_parent_object = NULL;

	$g_datacenter_array = ($g_datacenter_object ? $g_datacenter_object->get ($g_oid) : NULL);

	if ((is_array ($g_datacenter_array))&&($g_datacenter_array['ddbdatalinker_id_parent']))
	{
		$g_parent_object = ((strpos ($g_datacenter_array['ddbdatalinker_id_parent'],"-") === false) ? new direct_datacenter () : new direct_datacenter_home ());

		if ($g_parent_object)
		{
			$g_parent_object->get ($g_datacenter_array['ddbdatalinker_id_parent']);
			if (!$g_parent_object->is_of_type ("d4d66a02daefdb2f70ff2507a78fd5ec",1)) { $g_datasub_check = $g_parent_object->is_sub_allowed (); }
		}
	}

	if ((!is_array ($g_datacenter_array))||($g_datacenter_object->is_directory ())) { $direct_classes['error_functions']->error_page ("standard","datacenter_oid_invalid","sWG/#echo(__FILEPATH__)# _a=delete_ (#echo(__LINE__)#)"); }
	elseif ($g_datacenter_object->is_writable ())
	{
		if (isset ($GLOBALS['i_dconfirm'])) { direct_output_related_manager ("datacenter_control_objects_delete_{$g_oid}_form_save","pre_module_service_action"); }
		else { direct_output_related_manager ("datacenter_control_objects_delete_{$g_oid}_form","pre_module_service_action"); }

		$direct_classes['kernel']->service_https ($direct_settings['datacenter_https_control_objects'],$direct_cachedata['page_this']);
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_credits_manager.php");

		direct_class_init ("output");
		$direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0");

		if (isset ($GLOBALS['i_dconfirm']))
		{
			if ($GLOBALS['i_dconfirm'])
			{
				$g_continue_check = true;

				if (!$g_datacenter_object->is_physical ())
				{
					$direct_classes['db']->v_transaction_begin ();
					if (isset ($g_parent_object)) { $g_continue_check = ($g_datasub_check ? $g_parent_object->remove_subs (1) : $g_parent_object->remove_objects (1)); }
				}

				if ($g_continue_check) { $g_continue_check = $g_datacenter_object->delete ($direct_settings['datacenter_path_upload']); }
				if (($g_continue_check)&&(!$g_datacenter_object->is_physical ())) { $g_continue_check = $direct_classes['db']->v_transaction_commit (); }

				if ($g_continue_check)
				{
					if ($g_datacenter_array['ddbdatacenter_type'] != "text/x-ddb-link")
					{
						direct_credits_payment_exec ("datacenter","delete",$g_datacenter_array['ddbdatalinker_id_object'],$direct_settings['user']['id'],$direct_settings['datacenter_file_delete_credits_onetime'],0);
						direct_credits_payment_exec ("datacenter","",$g_datacenter_array['ddbdatalinker_id_object'],$direct_settings['user']['id'],0,0);
					}

					$direct_cachedata['output_job'] = direct_local_get ("datacenter_object_delete");
					$direct_cachedata['output_job_desc'] = direct_local_get ("datacenter_done_object_delete");

					if ($g_target_url)
					{
						$direct_cachedata['output_jsjump'] = 2000;
						$g_target_link = str_replace ("[oid]","doid_d+{$g_oid}++",$g_target_url);
					}
					elseif ($g_connector_url)
					{
						$direct_cachedata['output_jsjump'] = 2000;
						$g_target_link = str_replace (array ("[a]","[oid]"),(array ("view","doid_d+{$g_oid}++")),$g_connector_url);
					}
					else { $direct_cachedata['output_jsjump'] = 0; }

					if ($direct_cachedata['output_jsjump'])
					{
						$direct_cachedata['output_pagetarget'] = str_replace ('"',"",(direct_linker ("url0",$g_target_link)));
						$direct_cachedata['output_scripttarget'] = str_replace ('"',"",(direct_linker ("url0",$g_target_link,false)));
					}

					direct_output_related_manager ("datacenter_control_objects_delete_{$g_oid}_form_save","post_module_service_action");
					$direct_classes['output']->oset ("default","done");
					$direct_classes['output']->options_flush (true);
					$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
					$direct_classes['output']->page_show ($direct_cachedata['output_job']);
				}
				elseif ($g_datacenter_object->is_physical ()) { $direct_classes['error_functions']->error_page ("fatal","datacenter_object_delete_physical","FATAL ERROR:<br />An unknown error occured while removing the file from the filesystem<br />sWG/#echo(__FILEPATH__)# _a=delete-save_ (#echo(__LINE__)#)"); }
				else
				{
					$direct_classes['db']->v_transaction_rollback ();
					$direct_classes['error_functions']->error_page ("fatal","core_database_error","FATAL ERROR:<br />An error occured while removing the object entry<br />sWG/#echo(__FILEPATH__)# _a=delete-save_ (#echo(__LINE__)#)");
				}
			}
			else { $direct_classes['output']->redirect (direct_linker ("url1",$direct_cachedata['page_backlink'],false)); }
		}
		else
		{
			$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formbuilder.php");
			direct_class_init ("formbuilder");

			$g_credits_periodically = 0;
			if (!$g_datasub_check) { direct_credits_payment_get_specials ("datacenter_file_delete",$g_datacenter_array['ddbdatalinker_id_parent'],$direct_settings['datacenter_file_delete_credits_onetime'],$g_credits_periodically); }
			$direct_cachedata['output_credits_information'] = direct_credits_payment_info ($direct_settings['datacenter_file_delete_credits_onetime']);
			$direct_cachedata['output_credits_payment_data'] = direct_credits_payment_check (true,$direct_settings['datacenter_file_delete_credits_onetime']);

			$g_datacenter_parsed_array = $g_datacenter_object->parse ($g_connector_url);

			$direct_cachedata['i_dobject'] = ((strlen ($g_datacenter_parsed_array['title_alt'])) ? $g_datacenter_parsed_array['title_alt'] : $g_datacenter_parsed_array['title']);
			$direct_cachedata['i_dconfirm'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>";

			$direct_classes['formbuilder']->entry_add ("info","dobject",(direct_local_get ("datacenter_object")),false);
			$direct_classes['formbuilder']->entry_add_select ("dconfirm",(direct_local_get ("datacenter_delete")),true,"s");

			$direct_cachedata['output_formbutton'] = direct_local_get ("core_continue");
			$direct_cachedata['output_formelements'] = $direct_classes['formbuilder']->form_get (false);
			$direct_cachedata['output_formtarget'] = "m=datacenter&s=control_objects&a=delete&dsd=doid+$g_oid++connector+".(urlencode ($g_connector))."++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
			$direct_cachedata['output_formtitle'] = direct_local_get ("datacenter_object_delete");

			direct_output_related_manager ("datacenter_control_objects_delete_{$g_oid}_form","post_module_service_action");
			$direct_classes['output']->oset ("default","form");
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_formtitle']);
		}
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=delete_ (#echo(__LINE__)#)"); }
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// ($direct_settings['a'] == "edit")||($direct_settings['a'] == "edit-save")
case "edit":
case "edit-save":
{
	$g_mode_save = (($direct_settings['a'] == "edit-save") ? true : false);
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }

	$g_oid = (isset ($direct_settings['dsd']['doid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['doid'])) : "");
	$g_connector = (isset ($direct_settings['dsd']['connector']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['connector'])) : "");
	$g_source = (isset ($direct_settings['dsd']['source']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['source'])) : "");
	$g_target = (isset ($direct_settings['dsd']['target']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['target'])) : "");

	$g_connector_url = ($g_connector ? base64_decode ($g_connector) : "m=datacenter&s=media&a=[a]&dsd=[oid]");
	$g_source_url = ($g_source ? base64_decode ($g_source) : "m=datacenter&s=media&dsd=[oid]");

	if ($g_target) { $g_target_url = base64_decode ($g_target); }
	else
	{
		$g_target = $g_source;
		$g_target_url = ($g_source ? $g_source_url : "");
	}

	$g_back_link = (((!$g_source)&&($g_connector_url)) ? preg_replace (array ("#\[a\]#","#\[oid\]#","#\[(.*?)\]#"),(array ("view","doid+{$g_oid}++","")),$g_connector_url) : str_replace ("[oid]","doid+{$g_oid}++",$g_source_url));

	if ($g_mode_save)
	{
		$direct_cachedata['page_this'] = "";
		$direct_cachedata['page_backlink'] = "m=datacenter&s=control_objects&a=edit&dsd=doid+$g_oid++connector+".(urlencode ($g_connector))."++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
		$direct_cachedata['page_homelink'] = $g_back_link;
	}
	else
	{
		$direct_cachedata['page_this'] = "m=datacenter&s=control_objects&a=edit&dsd=doid+$g_oid++connector+".(urlencode ($g_connector))."++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
		$direct_cachedata['page_backlink'] = $g_back_link;
		$direct_cachedata['page_homelink'] = $g_back_link;
	}

	if ($direct_classes['kernel']->service_init_default ())
	{
	//j// BOA
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter_home.php");
	direct_local_integration ("datacenter");

	$g_datacenter_object = new direct_datacenter ();

	$g_datacenter_array = ($g_datacenter_object ? $g_datacenter_object->get ($g_oid) : NULL);

	if ((!is_array ($g_datacenter_array))||($g_datacenter_object->is_directory ())||($g_datacenter_object->is_physical ())) { $direct_classes['error_functions']->error_page ("standard","datacenter_oid_invalid","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	elseif ($g_datacenter_object->is_writable ())
	{
		if ($g_mode_save) { direct_output_related_manager ("datacenter_control_objects_edit_{$g_oid}_form_save","pre_module_service_action"); }
		else
		{
			direct_output_related_manager ("datacenter_control_objects_edit_{$g_oid}_form","pre_module_service_action");
			$direct_classes['kernel']->service_https ($direct_settings['datacenter_https_control_objects'],$direct_cachedata['page_this']);
		}

		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formbuilder.php");
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formtags.php");
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_credits_manager.php");

		direct_class_init ("formbuilder");
		direct_class_init ("formtags");
		direct_class_init ("output");
		$direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0");

		$g_credits_periodically = 0;
		direct_credits_payment_get_specials ("datacenter_file_edit",$g_datacenter_array['ddbdatalinker_id_parent'],$direct_settings['datacenter_files_edit_credits_onetime'],$g_credits_periodically);
		$direct_cachedata['output_credits_information'] = direct_credits_payment_info ($direct_settings['datacenter_files_edit_credits_onetime']);
		$direct_cachedata['output_credits_payment_data'] = direct_credits_payment_check (true,$direct_settings['datacenter_files_edit_credits_onetime']);

		if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) { $g_rights_check = true; }
		else
		{
			$g_rights_check = false;

			if (($g_datacenter_array['ddbdatalinker_id_parent'])&&($g_datacenter_array['ddbdatalinker_id_main'])) { $g_parent_object = ((strpos ($g_datacenter_array['ddbdatalinker_id_parent'],"-") === false) ? new direct_datacenter () : new direct_datacenter_home ()); }
			else { $g_parent_object = NULL; }
		}

		if ($g_mode_save)
		{
/* -------------------------------------------------------------------------
We should have input in save mode
------------------------------------------------------------------------- */

			$direct_cachedata['i_dobject'] = (isset ($GLOBALS['i_dobject']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_dobject'])) : "");
			$direct_cachedata['i_dobject_desc'] = (isset ($GLOBALS['i_dobject_desc']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_dobject_desc'])) : "");

			$direct_cachedata['i_dmode_all'] = (isset ($GLOBALS['i_dmode_all']) ? (str_replace ("'","",$GLOBALS['i_dmode_all'])) : "");

			if (($g_rights_check)||($g_datacenter_array['ddbdatacenter_owner_id'] == $direct_settings['user']['id'])) { $direct_cachedata['i_dmode_last'] = (isset ($GLOBALS['i_dmode_last']) ? (str_replace ("'","",$GLOBALS['i_dmode_last'])) : ""); }

			if ($g_rights_check)
			{
				$direct_cachedata['i_dtrusted'] = (isset ($GLOBALS['i_dtrusted']) ? (str_replace ("'","",$GLOBALS['i_dtrusted'])) : "");
				$direct_cachedata['i_dtrusted'] = str_replace ("<value value='$direct_cachedata[i_dtrusted]' />","<value value='$direct_cachedata[i_dtrusted]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dmode_owner'] = (isset ($GLOBALS['i_dmode_owner']) ? (str_replace ("'","",$GLOBALS['i_dmode_owner'])) : "");

				$direct_cachedata['i_dsubs_allowed'] = (isset ($GLOBALS['i_dsubs_allowed']) ? (str_replace ("'","",$GLOBALS['i_dsubs_allowed'])) : "");
				$direct_cachedata['i_dsubs_allowed'] = str_replace ("<value value='$direct_cachedata[i_dsubs_allowed]' />","<value value='$direct_cachedata[i_dsubs_allowed]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_hidden'] = (isset ($GLOBALS['i_dsubs_hidden']) ? (str_replace ("'","",$GLOBALS['i_dsubs_hidden'])) : "");
				$direct_cachedata['i_dsubs_hidden'] = str_replace ("<value value='$direct_cachedata[i_dsubs_hidden]' />","<value value='$direct_cachedata[i_dsubs_hidden]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_type'] = (isset ($GLOBALS['i_dsubs_type']) ? (str_replace ("'","",$GLOBALS['i_dsubs_type'])) : 0);
			}
			elseif (($g_parent_object)&&($g_parent_object->get ($g_datacenter_array['ddbdatalinker_id_parent']))&&($g_parent_object->is_sub_allowed ()))
			{
				$direct_cachedata['i_dsubs_allowed'] = (isset ($GLOBALS['i_dsubs_allowed']) ? (str_replace ("'","",$GLOBALS['i_dsubs_allowed'])) : "");
				$direct_cachedata['i_dsubs_allowed'] = str_replace ("<value value='$direct_cachedata[i_dsubs_allowed]' />","<value value='$direct_cachedata[i_dsubs_allowed]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_hidden'] = (isset ($GLOBALS['i_dsubs_hidden']) ? (str_replace ("'","",$GLOBALS['i_dsubs_hidden'])) : "");
				$direct_cachedata['i_dsubs_hidden'] = str_replace ("<value value='$direct_cachedata[i_dsubs_hidden]' />","<value value='$direct_cachedata[i_dsubs_hidden]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_type'] = (isset ($GLOBALS['i_dsubs_type']) ? (str_replace ("'","",$GLOBALS['i_dsubs_type'])) : 0);
			}
		}
		else
		{
			$direct_cachedata['i_dobject'] = $g_datacenter_array['ddbdatalinker_title'];
			$direct_cachedata['i_dobject_desc'] = $direct_classes['formtags']->recode_newlines (direct_output_smiley_cleanup ($g_datacenter_array['ddbdatacenter_desc']),false);

			$direct_cachedata['i_dmode_all'] = str_replace ("'","",$g_datacenter_array['ddbdatacenter_mode_all']);

			if (($g_rights_check)||($g_datacenter_array['ddbdatacenter_owner_id'] == $direct_settings['user']['id'])) { $direct_cachedata['i_dmode_last'] = str_replace ("'","",$g_datacenter_array['ddbdatacenter_mode_last']); }

			if ($g_rights_check)
			{
				if ($g_datacenter_array['ddbdatacenter_trusted']) { $direct_cachedata['i_dtrusted'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dtrusted'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				$direct_cachedata['i_dmode_owner'] = str_replace ("'","",$g_datacenter_array['ddbdatacenter_mode_owner']);

				if ($g_datacenter_array['ddbdatalinker_datasubs_new']) { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				if ($g_datacenter_array['ddbdatalinker_datasubs_hide']) { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				$direct_cachedata['i_dsubs_type'] = (isset ($g_datacenter_array['ddbdatalinker_datasubs_type']) ? str_replace ("'","",$g_datacenter_array['ddbdatalinker_datasubs_type']) : 0);
			}
			elseif (($g_parent_object)&&($g_parent_object->get ($g_datacenter_array['ddbdatalinker_id_parent']))&&($g_parent_object->is_sub_allowed ()))
			{
				if ($g_datacenter_array['ddbdatalinker_datasubs_new']) { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				if ($g_datacenter_array['ddbdatalinker_datasubs_hide']) { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				$direct_cachedata['i_dsubs_type'] = (isset ($g_datacenter_array['ddbdatalinker_datasubs_type']) ? str_replace ("'","",$g_datacenter_array['ddbdatalinker_datasubs_type']) : 0);
			}
		}

$direct_cachedata['i_dmode_all'] = str_replace ("<value value='$direct_cachedata[i_dmode_all]' />","<value value='$direct_cachedata[i_dmode_all]' /><selected value='1' />","<evars>
<norights><value value='-' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_0"))."]]></text></norights><read><value value='r' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_r"))."]]></text></read><write><value value='w' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_w"))."]]></text></write>
</evars>");

		if (isset ($direct_cachedata['i_dmode_last']))
		{
$direct_cachedata['i_dmode_last'] = str_replace ("<value value='$direct_cachedata[i_dmode_last]' />","<value value='$direct_cachedata[i_dmode_last]' /><selected value='1' />","<evars>
<norights><value value='-' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_0"))."]]></text></norights><read><value value='r' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_r"))."]]></text></read><write><value value='w' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_w"))."]]></text></write>
</evars>");
		}

		if ($g_rights_check)
		{
$direct_cachedata['i_dmode_owner'] = str_replace ("<value value='$direct_cachedata[i_dmode_owner]' />","<value value='$direct_cachedata[i_dmode_owner]' /><selected value='1' />","<evars>
<norights><value value='-' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_0"))."]]></text></norights><read><value value='r' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_r"))."]]></text></read><write><value value='w' /><text><![CDATA[".(direct_local_get ("datacenter_mode_right_w"))."]]></text></write>
</evars>");
		}

		if (isset ($direct_cachedata['i_dsubs_type']))
		{
$direct_cachedata['i_dsubs_type'] = str_replace ("<value value='$direct_cachedata[i_dsubs_type]' />","<value value='$direct_cachedata[i_dsubs_type]' /><selected value='1' />","<evars>
<default><value value='0' /><text><![CDATA[".(direct_local_get ("core_datasub_title_default"))."]]></text></default><attachments><value value='1' /><text><![CDATA[".(direct_local_get ("core_datasub_title_attachments"))."]]></text></attachments><downloads><value value='2' /><text><![CDATA[".(direct_local_get ("core_datasub_title_downloads"))."]]></text></downloads><links><value value='3' /><text><![CDATA[".(direct_local_get ("core_datasub_title_links"))."]]></text></links>
</evars>");
		}

/* -------------------------------------------------------------------------
Build the form
------------------------------------------------------------------------- */

		$direct_classes['formbuilder']->entry_add_text ("dobject",(direct_local_get ("datacenter_object")),true,"s",3,255);

		if ($direct_settings['formtags_overview_document_url']) { $direct_classes['formbuilder']->entry_add_jfield_text ("dobject_desc",(direct_local_get ("datacenter_desc")),false,"m",0,255,(direct_local_get ("formtags_overview_document")),(direct_linker ("url0",$direct_settings['formtags_overview_document_url']))); }
		else { $direct_classes['formbuilder']->entry_add_jfield_text ("dobject_desc",(direct_local_get ("datacenter_desc")),false,"m",0,255); }

		$direct_classes['formbuilder']->entry_add ("spacer");

		if ($g_rights_check)
		{
			$direct_classes['formbuilder']->entry_add_select ("dtrusted",(direct_local_get ("datacenter_trusted")),true,"s",(direct_local_get ("datacenter_helper_trusted")),"",true);
			$direct_classes['formbuilder']->entry_add_select ("dmode_owner",(direct_local_get ("datacenter_mode_owner")),false,"s");
		}

		if (($g_rights_check)||($g_datacenter_array['ddbdatacenter_owner_id'] == $direct_settings['user']['id'])) { $direct_classes['formbuilder']->entry_add_select ("dmode_last",(direct_local_get ("datacenter_mode_last")),false,"s"); }
		$direct_classes['formbuilder']->entry_add_select ("dmode_all",(direct_local_get ("datacenter_mode_all")),true,"s",(direct_local_get ("datacenter_helper_mode_dir")),"",true);

		if (($g_rights_check)||(isset ($direct_cachedata['i_dsubs_allowed'])))
		{
			$direct_classes['formbuilder']->entry_add ("spacer");
			$direct_classes['formbuilder']->entry_add_select ("dsubs_allowed",(direct_local_get ("core_datasub_allowed")),true,"s");
			$direct_classes['formbuilder']->entry_add_select ("dsubs_hidden",(direct_local_get ("core_datasub_hide")),true,"s");
			$direct_classes['formbuilder']->entry_add_radio ("dsubs_type",(direct_local_get ("core_datasub_type")),true);
		}

		$direct_cachedata['output_formelements'] = $direct_classes['formbuilder']->form_get ($g_mode_save);

		if (($g_mode_save)&&($direct_classes['formbuilder']->check_result))
		{
/* -------------------------------------------------------------------------
Save data edited
------------------------------------------------------------------------- */

			$g_continue_check = (($g_datacenter_array['ddbdatacenter_type'] == "text/x-ddb-link") ? true : direct_credits_payment_check (false,$direct_settings['datacenter_files_edit_credits_onetime']));

			if ($g_continue_check)
			{
				$direct_cachedata['i_dobject_desc'] = direct_output_smiley_encode ($direct_classes['formtags']->encode ($direct_cachedata['i_dobject_desc']));

				if (!isset ($direct_cachedata['i_dtrusted'])) { $direct_cachedata['i_dtrusted'] = $g_datacenter_array['ddbdatacenter_trusted']; }
				if (!$direct_cachedata['i_dmode_owner']) { $direct_cachedata['i_dmode_owner'] = $g_datacenter_array['ddbdatacenter_mode_owner']; }
				if (!$direct_cachedata['i_dmode_last']) { $direct_cachedata['i_dmode_last'] = $g_datacenter_array['ddbdatacenter_mode_last']; }

				$g_datacenter_array['ddbdatalinker_sorting_date'] = $direct_cachedata['core_time'];
				$g_datacenter_array['ddbdatalinker_title'] = $direct_cachedata['i_dobject'];

				if (isset ($direct_cachedata['i_dsubs_allowed']))
				{
					if ($direct_cachedata['i_dsubs_allowed'])
					{
						$g_datacenter_array['ddbdatalinker_datasubs_type'] = $direct_cachedata['i_dsubs_type'];
						$g_datacenter_array['ddbdatalinker_datasubs_hide'] = $direct_cachedata['i_dsubs_hidden'];
						$g_datacenter_array['ddbdatalinker_datasubs_new'] = 1;
					}
					else { $g_datacenter_array['ddbdatalinker_datasubs_new'] = 0; }
				}

				$g_datacenter_array['ddbdatacenter_desc'] = $direct_cachedata['i_dobject_desc'];
				$g_datacenter_array['ddbdatacenter_mode_owner'] = $direct_cachedata['i_dmode_owner'];
				$g_datacenter_array['ddbdatacenter_mode_last'] = $direct_cachedata['i_dmode_last'];
				$g_datacenter_array['ddbdatacenter_mode_all'] = $direct_cachedata['i_dmode_all'];
				$g_datacenter_array['ddbdatacenter_trusted'] = $direct_cachedata['i_dtrusted'];

				if ($g_datacenter_object->set_update ($g_datacenter_array))
				{
					if ($g_datacenter_array['ddbdatacenter_type'] != "text/x-ddb-link") { direct_credits_payment_exec ("datacenter","file_edit",$g_oid,$direct_settings['user']['id'],$direct_settings['datacenter_files_edit_credits_onetime'],0); }

					$direct_cachedata['output_job'] = direct_local_get ("datacenter_object_edit");
					$direct_cachedata['output_job_desc'] = direct_local_get ("datacenter_done_object_edit");

					if ($g_target_url)
					{
						$direct_cachedata['output_jsjump'] = 2000;
						$g_target_link = str_replace ("[oid]","doid_d+{$g_oid}++",$g_target_url);
					}
					elseif ($g_connector_url)
					{
						$direct_cachedata['output_jsjump'] = 2000;
						$g_target_link = str_replace (array ("[a]","[oid]"),(array ("view","doid_d+{$g_oid}++")),$g_connector_url);
					}
					else { $direct_cachedata['output_jsjump'] = 0; }

					if ($direct_cachedata['output_jsjump'])
					{
						$direct_cachedata['output_pagetarget'] = str_replace ('"',"",(direct_linker ("url0",$g_target_link)));
						$direct_cachedata['output_scripttarget'] = str_replace ('"',"",(direct_linker ("url0",$g_target_link,false)));
					}

					direct_output_related_manager ("datacenter_control_objects_edit_{$g_oid}_form_save","post_module_service_action");
					$direct_classes['output']->oset ("default","done");
					$direct_classes['output']->options_flush (true);
					$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
					$direct_classes['output']->page_show ($direct_cachedata['output_job']);
				}
				else { $direct_classes['error_functions']->error_page ("fatal","core_database_error","FATAL ERROR:<br />An error occured while saving the object metadata<br />sWG/#echo(__FILEPATH__)# _a=edit-save_ (#echo(__LINE__)#)"); }
			}
			else { $direct_classes['error_functions']->error_page ("standard","core_credits_insufficient","SERVICE ERROR:<br />".(-1 * $direct_settings['datacenter_files_edit_credits_onetime'])." Credits are required but not available. This error has been reported by the sWG Credits Manager.<br />sWG/#echo(__FILEPATH__)# _a=edit-save_ (#echo(__LINE__)#)"); }
		}
		else
		{
/* -------------------------------------------------------------------------
View form
------------------------------------------------------------------------- */

			$direct_cachedata['output_formbutton'] = direct_local_get ("core_save");
			$direct_cachedata['output_formtarget'] = "m=datacenter&s=control_objects&a=edit-save&dsd=doid+$g_oid++connector+".(urlencode ($g_connector))."++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
			$direct_cachedata['output_formtitle'] = direct_local_get ("datacenter_object_edit");

			direct_output_related_manager ("datacenter_control_objects_edit_{$g_oid}_form","post_module_service_action");
			$direct_classes['output']->oset ("default","form");
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_formtitle']);
		}
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// $direct_settings['a'] == "upload"
case "upload":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=upload_ (#echo(__LINE__)#)"); }

	$g_oid = (isset ($direct_settings['dsd']['doid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['doid'])) : "");
	$g_source = (isset ($direct_settings['dsd']['source']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['source'])) : "");
	$g_target = (isset ($direct_settings['dsd']['target']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['target'])) : "");

	$g_source_url = ($g_source ? base64_decode ($g_source) : "m=datacenter&s=media&dsd=[oid]");

	if ($g_target) { $g_target_url = base64_decode ($g_target); }
	else
	{
		$g_target = $g_source;
		$g_target_url = $g_source_url;
	}

	$direct_cachedata['page_this'] = "m=datacenter&s=control_objects&a=upload&dsd=doid+{$g_oid}++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
	$direct_cachedata['page_backlink'] = str_replace ("[oid]","",$g_source_url);
	$direct_cachedata['page_homelink'] = $direct_cachedata['page_backlink'];

	if ($direct_classes['kernel']->service_init_default ())
	{
	//j// BOA
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter_home.php");
	direct_local_integration ("datacenter");

	$g_datacenter_array = NULL;
	$g_datacenter_object = ((strpos ($g_oid,"-") === false) ? new direct_datacenter () : new direct_datacenter_home ());
	$g_datasub_check = false;

	if ($g_datacenter_object)
	{
		$g_datacenter_array = $g_datacenter_object->get ($g_oid);
		if (!$g_datacenter_object->is_of_type ("d4d66a02daefdb2f70ff2507a78fd5ec",1)) { $g_datasub_check = $g_datacenter_object->is_sub_allowed (); }
	}

	if ((!$g_datasub_check)&&((!is_array ($g_datacenter_array))||(!$g_datacenter_object->is_directory ()))) { $direct_classes['error_functions']->error_page ("standard","datacenter_did_invalid","sWG/#echo(__FILEPATH__)# _a=upload_ (#echo(__LINE__)#)"); }
	elseif (($g_datacenter_object->is_writable ())&&(($direct_settings['datacenter_uploads_localside'])||($direct_settings['datacenter_uploads_serverside'])))
	{
		$direct_classes['kernel']->service_https ($direct_settings['datacenter_https_control_objects'],$direct_cachedata['page_this']);
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php");

		$g_tid = uniqid ("");
		$g_upload_path = $g_datacenter_object->get_plocation ();

		if ((get_class ($g_datacenter_object) == "direct_datacenter")&&(($g_datacenter_object->is_physical ())||($g_upload_path))) { $g_physical = 1; }
		else
		{
			$g_physical = 0;
			$g_upload_path = $direct_classes['basic_functions']->varfilter ($direct_settings['datacenter_path_upload'],"settings");
		}

$g_task_array = array (
"core_back_return" => $g_source_url,
"core_sid" => "d4d66a02daefdb2f70ff2507a78fd5ec",
// md5 ("datacenter")
"datacenter_upload_done" => 0,
"datacenter_upload_mode" => "",
"datacenter_upload_return" => $g_target_url,
"datacenter_upload_limit_relevant" => 1,
"datacenter_upload_mode_physical" => $g_physical,
"datacenter_upload_mode_owner" => $g_datacenter_array['ddbdatacenter_mode_owner'],
"datacenter_upload_mode_last" => $g_datacenter_array['ddbdatacenter_mode_last'],
"datacenter_upload_mode_all" => $g_datacenter_array['ddbdatacenter_mode_all'],
"datacenter_upload_trusted" => $g_datacenter_array['ddbdatacenter_trusted'],
"datacenter_upload_subs_allowed" => $g_datacenter_array['ddbdatalinker_datasubs_new'],
"datacenter_upload_subs_hidden" => $g_datacenter_array['ddbdatalinker_datasubs_hide'],
"datacenter_upload_subs_type" => $g_datacenter_array['ddbdatalinker_datasubs_type'],
"datacenter_upload_path" => $g_upload_path,
"datacenter_upload_target_did" => $g_oid,
"datacenter_upload_target_did_forced" => 0,
"datacenter_upload_target_quantity" => 1,
"datacenter_upload_target_quantity_forced" => 0,
"uuid" => $direct_settings['uuid']
);

		direct_tmp_storage_write ($g_task_array,$g_tid,"d4d66a02daefdb2f70ff2507a78fd5ec","task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 900));
		// md5 ("datacenter")
		direct_class_init ("output");
		$direct_classes['output']->redirect (direct_linker ("url1","m=datacenter&s=uploads&a=preselect&dsd=tid+".$g_tid,false));
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=upload_ (#echo(__LINE__)#)"); }
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// EOS
}

//j// EOF
?>