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
$Id: swg_media.php,v 1.3 2009/03/16 08:27:54 s4u Exp $
#echo(sWGdatacenterVersion)#
sWG/#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* datacenter/swg_media.php
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

if (!isset ($direct_settings['datacenter_https_view'])) { $direct_settings['datacenter_https_view'] = false; }
if (!isset ($direct_settings['datacenter_objects_per_page'])) { $direct_settings['datacenter_objects_per_page'] = 25; }
if (!isset ($direct_settings['datacenter_media_mods_support'])) { $direct_settings['datacenter_media_mods_support'] = true; }
if (!isset ($direct_settings['datacenter_upload_links_allowed'])) { $direct_settings['datacenter_upload_links_allowed'] = true; }
if (!isset ($direct_settings['serviceicon_datacenter_dir_edit'])) { $direct_settings['serviceicon_datacenter_dir_edit'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_datacenter_dir_new'])) { $direct_settings['serviceicon_datacenter_dir_new'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_datacenter_dir_move'])) { $direct_settings['serviceicon_datacenter_dir_move'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_datacenter_dir_delete'])) { $direct_settings['serviceicon_datacenter_dir_delete'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_datacenter_file_new'])) { $direct_settings['serviceicon_datacenter_file_new'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_datacenter_link_new'])) { $direct_settings['serviceicon_datacenter_link_new'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_datacenter_object_delete'])) { $direct_settings['serviceicon_datacenter_object_delete'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_datacenter_object_edit'])) { $direct_settings['serviceicon_datacenter_object_edit'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_datacenter_object_move'])) { $direct_settings['serviceicon_datacenter_object_move'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_default_back'])) { $direct_settings['serviceicon_default_back'] = "mini_default_back.png"; }
$direct_settings['additional_copyright'][] = array ("Module datacenter #echo(sWGdatacenterVersion)# - (C) ","http://www.direct-netware.de/redirect.php?swg","direct Netware Group"," - All rights reserved");

if ($direct_settings['a'] == "index") { $direct_settings['a'] = "view"; }
//j// BOS
switch ($direct_settings['a'])
{
//j// $direct_settings['a'] == "view"
case "view":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=view_ (#echo(__LINE__)#)"); }

	$g_oid_d = (isset ($direct_settings['dsd']['doid_d']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['doid_d'])) : "");
	$g_oid = (isset ($direct_settings['dsd']['doid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['doid'])) : $g_oid_d);
	$direct_cachedata['output_page'] = (isset ($direct_settings['dsd']['page']) ? ($direct_classes['basic_functions']->inputfilter_number ($direct_settings['dsd']['page'])) : 1);

	$direct_cachedata['page_this'] = "m=datacenter&s=media&dsd=doid+{$g_oid}++page+".$direct_cachedata['output_page'];
	$direct_cachedata['page_backlink'] = "m=datacenter&s=media";
	$direct_cachedata['page_homelink'] = "m=datacenter&a=services";

	if ($direct_classes['kernel']->service_init_default ())
	{
	if ($direct_settings['datacenter'])
	{
	//j// BOA
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter_home.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_mods_support.php");
	direct_local_integration ("datacenter");

	$g_datacenter_array = NULL;
	$g_datacenter_entries_array = NULL;

	if ((!$g_oid)&&($direct_settings['user']['type'] != "gt"))
	{
		$g_oid = "u-".$direct_settings['user']['id'];
		$g_datacenter_object = new direct_datacenter_home ();
	}
	elseif (strpos ($g_oid,"u-") === 0) { $g_datacenter_object = new direct_datacenter_home (); }
	else { $g_datacenter_object = new direct_datacenter (); }

	if ($g_datacenter_object) { $g_datacenter_array = $g_datacenter_object->get ($g_oid); }
	else { $g_datacenter_array = NULL; }

	if ($g_datacenter_array)
	{
		if ($g_datacenter_object->is_directory ())
		{
			$direct_cachedata['output_page_url'] = "m=datacenter&s=media&dsd=doid+{$g_oid}++";
			$direct_cachedata['output_pages'] = ceil ($g_datacenter_array['ddbdatalinker_objects'] / $direct_settings['datacenter_objects_per_page']);

			if ($direct_cachedata['output_pages'] < 1) { $direct_cachedata['output_pages'] = 1; }
			if ((!$direct_cachedata['output_page'])||($direct_cachedata['output_page'] < 1)) { $direct_cachedata['output_page'] = 1; }

			$g_offset = (($direct_cachedata['output_page'] - 1) * $direct_settings['datacenter_objects_per_page']);
			$g_datacenter_entries_array = $g_datacenter_object->get_objects (1,$g_offset,$direct_settings['datacenter_objects_per_page']);
		}
	}

	if (!is_array ($g_datacenter_array)) { $direct_classes['error_functions']->error_page ("standard","datacenter_oid_invalid","sWG/#echo(__FILEPATH__)# _a=view_ (#echo(__LINE__)#)"); }
	elseif (($g_datacenter_object->is_directory ())&&(!is_array ($g_datacenter_entries_array))) { $direct_classes['error_functions']->error_page ("standard","core_unknown_error","sWG/#echo(__FILEPATH__)# _a=view_ (#echo(__LINE__)#)"); }
	elseif ($g_datacenter_object->is_readable ())
	{
		direct_output_related_manager ("datacenter_media_view_".$g_oid,"pre_module_service_action");
		$direct_classes['kernel']->service_https ($direct_settings['datacenter_https_view'],$direct_cachedata['page_this']);

		direct_class_init ("output");
		$direct_classes['output']->servicemenu ("datacenter_media");

		$direct_cachedata['output_source'] = urlencode (base64_encode ($direct_cachedata['page_this']));

		if ($g_datacenter_object->is_writable ())
		{
			if ($g_datacenter_object->is_directory ())
			{
				$direct_classes['output']->options_insert (1,"servicemenu","m=datacenter&s=control_dirs&a=new&dsd=doid+{$g_oid}++source+".$direct_cachedata['output_source'],(direct_local_get ("datacenter_dir_new")),$direct_settings['serviceicon_datacenter_dir_new'],"url0");

				if (get_class ($g_datacenter_object) == "direct_datacenter")
				{
					if (!$g_datacenter_object->is_physical ())
					{
						$direct_classes['output']->options_insert (1,"servicemenu","m=datacenter&s=control_dirs&a=edit&dsd=doid+{$g_oid}++source+".$direct_cachedata['output_source'],(direct_local_get ("datacenter_dir_edit")),$direct_settings['serviceicon_datacenter_dir_edit'],"url0");
// TODO						$direct_classes['output']->options_insert (1,"servicemenu","m=datacenter&s=control_dirs&a=move&dsd=doid+{$g_oid}++source+".$direct_cachedata['output_source'],(direct_local_get ("datacenter_dir_move")),$direct_settings['serviceicon_datacenter_dir_move'],"url0");
					}

					if (strlen ($g_datacenter_array['ddbdatalinker_id_main'])) { $g_target_url = urlencode (base64_encode ("m=datacenter&s=media&dsd=doid+".(urlencode ($g_datacenter_array['ddbdatalinker_id_parent'])))); }
					else { $g_target_url = urlencode (base64_encode ("m=datalinker&dsd=deid+".(urlencode ($g_datacenter_array['ddbdatalinker_id_parent'])))); }

					$direct_classes['output']->options_insert (1,"servicemenu","m=datacenter&s=control_dirs&a=delete&dsd=doid+{$g_oid}++source+".$direct_cachedata['output_source']."++target+".$g_target_url,(direct_local_get ("datacenter_dir_delete")),$direct_settings['serviceicon_datacenter_dir_delete'],"url0");
				}

				$direct_classes['output']->options_insert (1,"servicemenu","m=datacenter&s=control_objects&a=upload&dsd=doid+{$g_oid}++source+".$direct_cachedata['output_source'],(direct_local_get ("datacenter_file_new")),$direct_settings['serviceicon_datacenter_file_new'],"url0");

				if (get_class ($g_datacenter_object) == "direct_datacenter_home") { $g_continue_check = true; }
				elseif ((!$g_datacenter_object->get_plocation ())&&(!$g_datacenter_object->is_physical ())) { $g_continue_check = true; }
				else { $g_continue_check = false; }

				if (($g_continue_check)&&(($direct_settings['datacenter_upload_links_allowed'])||($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3))) { $direct_classes['output']->options_insert (1,"servicemenu","m=datacenter&s=control_links&a=new&dsd=doid+{$g_oid}++source+".$direct_cachedata['output_source'],(direct_local_get ("datacenter_link_new")),$direct_settings['serviceicon_datacenter_link_new'],"url0"); }
			}
			else
			{
				if (!$g_datacenter_object->is_physical ())
				{
					$direct_classes['output']->options_insert (1,"servicemenu","m=datacenter&s=control_objects&a=edit&dsd=doid+{$g_oid}++source+".$direct_cachedata['output_source'],(direct_local_get ("datacenter_object_edit")),$direct_settings['serviceicon_datacenter_object_edit'],"url0");
// TODO					$direct_classes['output']->options_insert (1,"servicemenu","m=datacenter&s=control_objects&a=move&dsd=doid+{$g_oid}++source+".$direct_cachedata['output_source'],(direct_local_get ("datacenter_object_move")),$direct_settings['serviceicon_datacenter_object_move'],"url0");
				}

				$g_inuse_check = false;

				if (strpos ($g_datacenter_array['ddbdatacenter_plocation'],"evars:") === 0)
				{
					$g_evars_array = $g_datacenter_object->get_evars ();
					if (($g_evars_array)&&(isset ($g_evars_array['core_inuse']))&&(!empty ($g_evars_array['core_inuse']))) { $g_inuse_check = true; }
				}

				if (!$g_inuse_check)
				{
					if (strlen ($g_datacenter_array['ddbdatalinker_id_main'])) { $g_target_url = urlencode (base64_encode ("m=datacenter&s=media&dsd=doid+".(urlencode ($g_datacenter_array['ddbdatalinker_id_parent'])))); }
					else { $g_target_url = urlencode (base64_encode ("m=datalinker&dsd=deid+".(urlencode ($g_datacenter_array['ddbdatalinker_id_parent'])))); }

					$direct_classes['output']->options_insert (1,"servicemenu","m=datacenter&s=control_objects&a=delete&dsd=doid+{$g_oid}++source+".$direct_cachedata['output_source']."++target+".$g_target_url,(direct_local_get ("datacenter_object_delete")),$direct_settings['serviceicon_datacenter_object_delete'],"url0");
				}
			}
		}

		if (isset ($g_datacenter_entries_array))
		{
			$direct_cachedata['output_dir'] = $g_datacenter_object->parse ("m=datacenter&s=media&a=[a]&dsd=[oid][page{$direct_cachedata['output_page']}]");
			$direct_cachedata['output_objects'] = array ();
		}
		else { $direct_cachedata['output_object'] = $g_datacenter_object->parse ("m=datacenter&s=media&a=[a]&dsd=[oid][page{$direct_cachedata['output_page']}]"); }

		if (($g_datacenter_array['ddbdatalinker_id_parent'])&&($g_datacenter_array['ddbdatalinker_id_main']))
		{
			if (strpos ($g_datacenter_array['ddbdatalinker_id_parent'],"-") === false) { $g_parent_object = new direct_datacenter (); }
			else { $g_parent_object = new direct_datacenter_home (); }

			if (($g_parent_object)&&($g_parent_object->get ($g_datacenter_array['ddbdatalinker_id_parent'])))
			{
				if (isset ($g_datacenter_entries_array)) { $direct_cachedata['output_dir_levelup'] = $g_parent_object->parse ("m=datacenter&s=media&a=[a]&dsd=[oid][page]"); }
				else { $direct_cachedata['output_dir'] = $g_parent_object->parse ("m=datacenter&s=media&a=[a]&dsd=[oid][page]"); }

				$direct_cachedata['page_backlink'] = "m=datacenter&s=media&dsd=doid+".$g_datacenter_array['ddbdatalinker_id_parent'];
			}
		}

		if (isset ($g_datacenter_entries_array))
		{
			if (!empty ($g_datacenter_entries_array))
			{
				foreach ($g_datacenter_entries_array as $g_datacenter_entry_object) { $direct_cachedata['output_objects'][] = $g_datacenter_entry_object->parse ("m=datacenter&s=media&a=[a]&dsd=[oid][page]"); }
			}
		}
		elseif (!$g_datacenter_object->is_deleted ()) { $direct_cachedata['output_modstoview'] = direct_mods_include ($direct_settings['datacenter_media_mods_support'],"datacenter_media_details","view",$g_datacenter_object); }

		direct_output_related_manager ("datacenter_media_list_".$g_datacenter_array['ddbdatalinker_id'],"post_module_service_action");

		if (isset ($g_datacenter_entries_array))
		{
			$direct_classes['output']->oset ("datacenter_media","list");
			$g_title = $direct_cachedata['output_dir']['title'];
		}
		else
		{
			$direct_classes['output']->oset ("datacenter_media","details");
			$g_title = $direct_cachedata['output_object']['title'];
		}

		$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
		$direct_classes['output']->page_show ($g_title);
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=view_ (#echo(__LINE__)#)"); }
	//j// EOA
	}
	else { $direct_classes['error_functions']->error_page ("standard","core_service_inactive","sWG/#echo(__FILEPATH__)# _a=view_ (#echo(__LINE__)#)"); }
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// EOS
}

//j// EOF
?>