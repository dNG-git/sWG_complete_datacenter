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
* OOP (Object Oriented Programming) requires an abstract data
* handling. The sWG is OO (where it makes sense).
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

/* -------------------------------------------------------------------------
Testing for required classes
------------------------------------------------------------------------- */

$g_continue_check = ((defined ("CLASS_direct_datacenter")) ? false : true);
if (!defined ("CLASS_direct_datalinker")) { $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datalinker.php"); }
if (!defined ("CLASS_direct_datalinker")) { $g_continue_check = false; }

if ($g_continue_check)
{
//c// direct_datacenter
/**
* This abstraction layer provides DataCenter specific functions.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    sWG
* @subpackage datacenter
* @uses       CLASS_direct_data_handler
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;gpl
*             GNU General Public License 2
*/
class direct_datacenter extends direct_datalinker
{
/**
	* @var array $class_subs Cached subobjects
*/
	protected $class_subs;
/**
	* @var boolean $data_deleted True if the object has been deleted
*/
	protected $data_deleted;
/**
	* @var boolean $data_directory True if the object is a directory
*/
	protected $data_directory;
/**
	* @var array $data_evars Defined evars of this object
*/
	protected $data_evars;
/**
	* @var string $data_evars_id evars archive ID
*/
	protected $data_evars_id;
/**
	* @var boolean $data_locked True if this object is locked
*/
	protected $data_locked;
/**
	* @var boolean $data_trusted True if this object is trusted
*/
	protected $data_trusted;
/**
	* @var boolean $data_marked True if the object has been marked
*/
	protected $data_marked;
/**
	* @var array $data_markers Active markers
*/
	protected $data_markers;
/**
	* @var string $data_pid Parent ID to be used
*/
	protected $data_pid;
/**
	* @var boolean $data_readable True if the current user is allowed to read
	*      documents in this category
*/
	protected $data_readable;
/**
	* @var boolean $data_writable True if the current user is allowed to
	*      create new and edit his own documents in this category
*/
	protected $data_writable;
/**
	* @var string $marker_connector Connector used for the marker links
*/
	protected $marker_connector;
/**
	* @var string $marker_connector_type Connector URI type to use
*/
	protected $marker_connector_type;
/**
	* @var string $marker_title_mark Link title to mark an object
*/
	protected $marker_title_mark;
/**
	* @var string $marker_title_unmark Link title to unmark an object
*/
	protected $marker_title_unmark;
/**
	* @var integer $marker_type Marker type (1 = directories only;
	*      2 = files only; 3 = all)
*/
	protected $marker_type;
/**
	* @var string $physical Physical path appended to the "plocation" folder
*/
	protected $physical;
/**
	* @var array $physical_objects Cached folder content
*/
	protected $physical_objects;

/* -------------------------------------------------------------------------
Extend the class
------------------------------------------------------------------------- */

	//f// direct_datacenter->__construct ()
/**
	* Constructor (PHP5) __construct (direct_datacenter)
	*
	* @param mixed $f_data String containing the allowed parent ID or an array
	*        with options
	* @uses  direct_local_get()
	* @uses  USE_debug_reporting
	* @since v0.1.00
*/
	public function __construct ($f_data = "")
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->__construct (direct_datacenter)- (#echo(__LINE__)#)"); }

		if (!defined ("CLASS_direct_formtags")) { $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/swg_formtags.php"); }
		if (!isset ($direct_classes['formtags'])) { direct_class_init ("formtags"); }

		if (!function_exists ("direct_dir_create")) { $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_dir_functions.php"); }

/* -------------------------------------------------------------------------
My parent should be on my side to get the work done
------------------------------------------------------------------------- */

		parent::__construct ();

/* -------------------------------------------------------------------------
Informing the system about available functions 
------------------------------------------------------------------------- */

		$this->functions['add_objects'] = true;
		$this->functions['check'] = true;
		$this->functions['define_lock'] = true;
		$this->functions['define_marker_connector'] = true;
		$this->functions['define_marker_titles'] = true;
		$this->functions['define_marker_type'] = true;
		$this->functions['define_markers'] = true;
		$this->functions['define_pid'] = true;
		$this->functions['define_readable'] = true;
		$this->functions['define_trusted'] = true;
		$this->functions['define_writable'] = true;
		$this->functions['delete'] = true;
		$this->functions['get_evars'] = true;
		$this->functions['get_objects'] = true;
		$this->functions['get_objects_since_date'] = $this->functions['get_objects'];
		$this->functions['get_physical_objects'] = true;
		$this->functions['get_rights'] = true;
		$this->functions['get_sorted_physical_objects'] = true;
		$this->functions['get_plocation'] = true;
		$this->functions['get_upload_folder'] = function_exists ("direct_dir_create");
		$this->functions['is_deleted'] = true;
		$this->functions['is_directory'] = true;
		$this->functions['is_locked'] = true;
		$this->functions['is_physical'] = true;
		$this->functions['is_readable'] = true;
		$this->functions['is_trusted'] = true;
		$this->functions['is_writable'] = true;
		$this->functions['parse'] = isset ($direct_classes['formtags']);
		$this->functions['remove_objects'] = true;
		$this->functions['set_evars'] = true;

/* -------------------------------------------------------------------------
Set up an additional datacenter class elements :)
------------------------------------------------------------------------- */

		$this->class_subs = array ();
		$this->data_deleted = false;
		$this->data_directory = false;
		$this->data_evars = array ();
		$this->data_evars_id = "";
		$this->data_locked = false;
		$this->data_marked = false;
		$this->data_markers = array ();
		$this->data_pid = "";
		$this->data_readable = false;
		$this->data_sid = "d4d66a02daefdb2f70ff2507a78fd5ec";
		$this->data_trusted = false;
		$this->data_writable = false;
		$this->marker_connector = "";
		$this->marker_connector_type = "url0";
		$this->marker_title_mark = direct_local_get ("datacenter_object_mark");
		$this->marker_title_unmark = direct_local_get ("datacenter_object_unmark");
		$this->marker_type = 6;
		$this->physical = NULL;
		$this->physical_objects = array ();

		if (is_string ($f_data)) { $this->data_pid = $f_data; }
		else
		{
			if (isset ($f_data['pid'])) { $this->data_pid = $f_data['pid']; }
			if (isset ($f_data['data_markers'])) { $this->data_markers = $f_data['data_markers']; }
			if (isset ($f_data['marker_connector'])) { $this->marker_connector = $f_data['marker_connector']; }
			if (isset ($f_data['marker_connector_type'])) { $this->marker_connector_type = $f_data['marker_connector_type']; }
			if (isset ($f_data['marker_title_mark'])) { $this->marker_title_mark = $f_data['marker_title_mark']; }
			if (isset ($f_data['marker_title_unmark'])) { $this->marker_title_unmark = $f_data['marker_title_unmark']; }
			if (isset ($f_data['marker_type'])) { $this->marker_type = $f_data['marker_type']; }
		}
	}

	//f// direct_datacenter->add_objects ($f_count,$f_update = true)
/**
	* Increases the object counter.
	*
	* @param  number $f_count Number to be added to the object counter
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_datalinker::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function add_objects ($f_count,$f_update = true)
	{
		if (USE_debug_reporting) { direct_debug (8,"sWG/#echo(__FILEPATH__)# -datacenter->add_objects ($f_count,+f_update)- (#echo(__LINE__)#)"); }

		if ($this->physical === NULL) { return /*#ifdef(DEBUG):direct_debug (9,"sWG/#echo(__FILEPATH__)# -datacenter->add_objects ()- (#echo(__LINE__)#)",(:#*/$this->add_objects ($f_count,$f_update)/*#ifdef(DEBUG):),true):#*/; }
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->add_objects ()- (#echo(__LINE__)#)",:#*/true/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_datacenter->check ($f_path,$f_data = NULL)
/**
	* Check if a file is accepted for the given mimetype.
	*
	* @param  string $f_path Base path for the folder
	* @param  array $f_data List of available filters
	* @uses   direct_basic_functions::include_file()
	* @uses   direct_basic_functions::memcache_get_file()
	* @uses   direct_datalinker::update()
	* @uses   direct_debug()
	* @uses   direct_xml_bridge::xml2array()
	* @uses   USE_debug_reporting
	* @return boolean True if valid
	* @since  v0.1.00
*/
	public function check ($f_path,$f_data = NULL)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->check ($f_path,+f_data)- (#echo(__LINE__)#)"); }

		$f_return = false;

		if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) { $f_return = true; }
		elseif ((isset ($this->data['ddbdatalinker_type']))&&($this->data['ddbdatalinker_type'] == 1)&&($this->data_directory)) { $f_return = true; }
		elseif (isset ($this->data['ddbdatacenter_plocation']))
		{
			$f_filters_array = array ();
			$f_physical_path = $this->get_plocation ($f_path);

			if (is_array ($f_data)) { $f_filters_array = $f_data; }
			else
			{
				$f_file_data = $direct_classes['basic_functions']->memcache_get_file ($direct_settings['path_data']."/settings/swg_datacenter_filters.xml");
				if ($f_file_data) { $f_xml_array = $direct_classes['xml_bridge']->xml2array ($f_file_data,true,false); }

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
						if (isset ($f_xml_node_array['value'],$f_xml_node_array['attributes']['module'],$f_xml_node_array['attributes']['function'])) { $f_filters_array[$f_xml_node_array['value']] = $f_xml_node_array['attributes']; }
					}
				}
			}

			if (($f_physical_path)&&(isset ($f_filters_array[$this->data['ddbdatacenter_type']])))
			{
				$direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/datacenter/swg_filter_{$f_filters_array[$this->data['ddbdatacenter_type']]['module']}.php");

				$f_function = "direct_datacenter_filter_{$f_filters_array[$this->data['ddbdatacenter_type']]['module']}_".$f_filters_array[$this->data['ddbdatacenter_type']]['function'];
				if (function_exists ($f_function)) { $f_return = $f_function ($f_physical_path,$this->data['ddbdatalinker_title']); }
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->check ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->define_lock ($f_state = NULL,$f_update = false)
/**
	* Sets the locking state of this object.
	*
	* @param  mixed $f_state Boolean indicating the state or NULL to switch
	*         automatically
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	public function define_lock ($f_state = NULL,$f_update = false)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->define_lock (+f_state,+f_update)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if ((count ($this->data) > 1)&&($this->physical === NULL))
		{
			if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $f_return = true; }
			elseif (($f_state === NULL)&&(!$this->data['ddbdatacenter_locked'])) { $f_return = true; }
			$this->data_locked = $f_return;

			$this->data['ddbdatacenter_locked'] = ($f_return ? 1 : 0);
			$this->data_changed['ddbdatacenter_locked'] = true;
			if ($f_update) { $this->update (); }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->define_lock ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->define_marker_connector ($f_connector)
/**
	* Sets the marker connector for links.
	*
	* @param  string $f_connector Connector
	* @param  string $f_connector_type Linking mode: "url0" for internal links,
	*         "url1" for external ones, "form" to create hidden fields or
	*         "optical" to remove parts of a very long string.
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @since  v0.1.00
*/
	public function define_marker_connector ($f_connector,$f_connector_type = "url0")
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->define_marker_connector ($f_connector)- (#echo(__LINE__)#)"); }

		if (is_string ($f_connector)) { $this->marker_connector = $f_connector; }
		$this->marker_connector_type = ((($f_connector_type != "asis")&&(strpos ($f_connector,"javascript:") === 0)) ? "asis" : $f_connector_type);
	}

	//f// direct_datacenter->define_marker_titles ($f_title_mark,$f_title_unmark)
/**
	* Sets the marker titles.
	*
	* @param  string $f_title_mark Link title to mark an object
	* @param  string $f_title_unmark Link title to unmark an object
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @since  v0.1.00
*/
	public function define_marker_titles ($f_title_mark,$f_title_unmark)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->define_marker_titles ($f_title_mark,$f_title_unmark)- (#echo(__LINE__)#)"); }

		if (strlen ($f_title_mark)) { $this->marker_title_mark = $f_title_mark; }
		if (strlen ($f_title_unmark)) { $this->marker_title_unmark = $f_title_unmark; }
	}

	//f// direct_datacenter->define_marker_type ($f_type)
/**
	* Sets the marker type.
	*
	* @param  mixed $f_type Type definition as integer or string ("dirs-only";
	*         "files-only"; "all")
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @since  v0.1.00
*/
	public function define_marker_type ($f_type)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->define_marker_type ($f_type)- (#echo(__LINE__)#)"); }

		if (is_numeric ($f_type)) { $this->marker_type = $f_type; }
		elseif ((is_string ($f_type))&&(strlen ($f_type)))
		{
			switch ($f_type)
			{
			case "dirs-only":
			{
				$this->marker_type = 1;
				break 1;
			}
			case "dirs-writable-only":
			{
				$this->marker_type = 2;
				break 1;
			}
			case "files-only":
			{
				$this->marker_type = 3;
				break 1;
			}
			case "files-writable-only":
			{
				$this->marker_type = 4;
				break 1;
			}
			case "writable-only":
			{
				$this->marker_type = 5;
				break 1;
			}
			default: { $this->marker_type = 6; }
			}
		}
	}

	//f// direct_datacenter->define_markers ($f_markers)
/**
	* Sets the active markers.
	*
	* @param  array $f_markers Active markers
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @since  v0.1.00
*/
	public function define_markers ($f_markers)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->define_markers (+f_markers)- (#echo(__LINE__)#)"); }

		if (is_array ($f_markers))
		{
			$this->data_markers = $f_markers;
			if ((is_array ($this->data))&&(!empty ($this->data))) { $this->data_marked = in_array ($this->data['ddbdatalinker_id'],$f_markers); }
		}
	}

	//f// direct_datacenter->define_readable ($f_state = NULL)
/**
	* Sets the readableing state of this object.
	*
	* @param  mixed $f_state Boolean indicating the state or NULL to switch
	*         automatically
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	public function define_readable ($f_state = NULL)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->define_readable (+f_state)- (#echo(__LINE__)#)"); }

		if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $this->data_readable = true; }
		elseif (($f_state === NULL)&&(!$this->data_readable)) { $this->data_readable = true; }
		else { $this->data_readable = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->define_readable ()- (#echo(__LINE__)#)",:#*/$this->data_readable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->define_trusted ($f_state = NULL,$f_update = false)
/**
	* Sets the trusted state of this object.
	*
	* @param  mixed $f_state Boolean indicating the state or NULL to switch
	*         automatically
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	public function define_trusted ($f_state = NULL,$f_update = false)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->define_trusted (+f_state,+f_update)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if ((count ($this->data) > 1)&&($this->physical === NULL))
		{
			if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $f_return = true; }
			elseif (($f_state === NULL)&&(!$this->data['ddbdatacenter_trusted'])) { $f_return = true; }
			$this->data_trusted = $f_return;

			$this->data['ddbdatacenter_trusted'] = ($f_return ? 1 : 0);
			$this->data_changed['ddbdatacenter_trusted'] = true;
			if ($f_update) { $this->update (); }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->define_trust ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}


	//f// direct_datacenter->define_writable ($f_state = NULL)
/**
	* Sets the writing right state of this object.
	*
	* @param  mixed $f_state Boolean indicating the state or NULL to switch
	*         automatically
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	public function define_writable ($f_state = NULL)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->define_writable (+f_state)- (#echo(__LINE__)#)"); }

		if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $this->data_writable = true; }
		elseif (($f_state === NULL)&&(!$this->data_writable)) { $this->data_writable = true; }
		else { $this->data_writable = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->define_writable ()- (#echo(__LINE__)#)",:#*/$this->data_writable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->delete ($f_path,$f_update = true)
/**
	* Mark the object as deleted and remove the file (if the database update
	* was successful). Move all sub objects of an directory to the parent. This
	* method does not update the objects count.
	*
	* @param  string $f_path Base path for folder
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_datacenter::get_plocation()
	* @uses   direct_datacenter::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function delete ($f_path,$f_update = true)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -datacenter->delete ($f_path,+f_update)- (#echo(__LINE__)#)"); }

		$f_return = false;

		if (isset ($this->data['ddbdatacenter_deleted']))
		{
			if ($this->physical === NULL)
			{
				$f_return = true;

				if ($this->data_directory)
				{
					if ((!$this->get_plocation ())&&($this->data['ddbdatalinker_objects'] > 0))
					{
						$direct_classes['db']->init_update ($direct_settings['datalinker_table']);

						if (strlen ($this->data['ddbdatalinker_id_main'])) { $direct_classes['db']->define_set_attributes ("<sqlvalues>".($direct_classes['db']->define_set_attributes_encode ($direct_settings['datalinker_table'].".ddbdatalinker_id_parent",$this->data['ddbdatalinker_id_parent'],"string"))."</sqlvalues>"); }
						else
						{
							$direct_classes['db']->define_set_attributes ("<sqlvalues>".($direct_classes['db']->define_set_attributes_encode ($direct_settings['datalinker_table'].".ddbdatalinker_id_parent","u-".$this->data['ddbdatacenter_owner_id'],"string"))."</sqlvalues>");
							$direct_classes['db']->define_set_attributes ("<sqlvalues>".($direct_classes['db']->define_set_attributes_encode ($direct_settings['datalinker_table'].".ddbdatalinker_id_main","u-".$this->data['ddbdatacenter_owner_id'],"string"))."</sqlvalues>");
						}

						$direct_classes['db']->define_row_conditions ("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['datalinker_table'].".ddbdatalinker_id_parent",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>");
						$f_return = $direct_classes['db']->query_exec ("co");
					}

					$f_physical_path = "";
				}
				else { $f_physical_path = $this->get_plocation ($f_path); }

				if ($f_return)
				{
					$this->data['ddbdatacenter_deleted'] = 1;
					$this->data_changed['ddbdatacenter_deleted'] = true;

					if (strpos ($this->data['ddbdatacenter_plocation'],"evars:") === 0)
					{
						$this->data['ddbdatacenter_plocation'] = "";
						$this->data_changed['ddbdatacenter_plocation'] = true;
					}

					if ($f_update) { $f_return = $this->update (); }

					if (($f_return)&&($f_physical_path)) { $f_return = @unlink ($f_physical_path); }
				}
			}
			else
			{
				$f_physical_path = $this->get_plocation ();
				if (strlen ($f_physical_path)) { $f_return = ($this->data_directory ? direct_dir_remove ($f_physical_path,true) : @unlink ($f_physical_path)); }
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->delete ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->get_aid ($f_attributes = NULL,$f_values = "")
/**
	* Request and load the DataCenter object based on a custom attribute ID.
	* Please note that only attributes of type "string" are supported.
	*
	* @param  mixed $f_attributes Attribute name(s) (array or string)
	* @param  mixed $f_values Attribute value(s) (array or string)
	* @uses   direct_datacenter::get_rights()
	* @uses   direct_datalinker::define_extra_attributes()
	* @uses   direct_datalinker::define_extra_conditions()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get_aid()
	* @uses   direct_db::define_row_conditions_encode()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return mixed DataCenter object array; false on error
	* @since  v0.1.00
*/
	public function get_aid ($f_attributes = NULL,$f_values = "")
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -datacenter->get_aid (+f_attributes,+f_values)- (#echo(__LINE__)#)"); }

		$f_return = false;

		if (count ($this->data) > 1) { $f_return = $this->data; }
		elseif ((is_array ($f_values))||(is_string ($f_values)))
		{
			if (((!isset ($f_attributes))||((is_string ($f_attributes))&&($f_attributes == "ddbdatalinker_id")))&&(is_string ($f_values))&&(preg_match ("#^(.+?):(.*?)$#",(urldecode ($f_values)),$f_result_array)))
			{
				$f_path = base64_decode ($f_result_array[2]);
				if (strlen ($f_path)) { $f_path = $direct_classes['basic_functions']->inputfilter_filepath ($f_path); }

				$f_values = $f_result_array[1];
			}
			else { $f_path = ""; }

			$this->define_extra_attributes (array ($direct_settings['datacenter_table'].".*",$direct_settings['users_table'].".ddbusers_type",$direct_settings['users_table'].".ddbusers_banned",$direct_settings['users_table'].".ddbusers_deleted",$direct_settings['users_table'].".ddbusers_name",$direct_settings['users_table'].".ddbusers_title",$direct_settings['users_table'].".ddbusers_avatar",$direct_settings['users_table'].".ddbusers_signature",$direct_settings['users_table'].".ddbusers_rating"));

$f_select_joins = array (
 array ("type" => "left-outer-join","table" => $direct_settings['datacenter_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['datacenter_table']}.ddbdatacenter_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>"),
 array ("type" => "left-outer-join","table" => $direct_settings['users_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['users_table']}.ddbusers_id' value='{$direct_settings['datacenter_table']}.ddbdatacenter_last_id' type='attribute' /></sqlconditions>")
);

			$this->define_extra_joins ($f_select_joins);
			if (strlen ($this->data_pid)) { $this->define_extra_conditions ($direct_classes['db']->define_row_conditions_encode ($direct_settings['datacenter_table'].".ddbdatalinker_id_parent",$this->data_pid,"string")); }

			$f_result_array = parent::get_aid ($f_attributes,$f_values);

			if (($f_result_array)&&($f_result_array['ddbdatalinker_sid'] == $this->data_sid))
			{
				$this->data = $f_result_array;

				if (strpos ($this->data['ddbdatacenter_plocation'],"evars:") === 0)
				{
					$this->data_evars = array ();
					$this->data_evars_id = substr ($this->data['ddbdatacenter_plocation'],6);
				}

				$this->data_deleted = ($this->data['ddbdatacenter_deleted'] ? true : false);

				if ($this->data['ddbdatalinker_type'] == 1)
				{
					$f_physical_path = $this->get_plocation ();
					$this->data_directory = true;
				}
				else { $this->data_directory = false; }

				$this->data_trusted = ($this->data['ddbdatacenter_trusted'] ? true : false);
				$this->data_locked = ($this->data['ddbdatacenter_locked'] ? true : false);
				$this->data_marked = in_array ($this->data['ddbdatalinker_id'],$this->data_markers);

				$f_result_array = $this->get_rights ();
				$this->data_readable = $f_result_array[0];
				$this->data_writable = $f_result_array[1];

				if (($this->data_directory)&&($f_physical_path)&&($direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_dir_functions.php")))
				{
					if (strlen ($f_path))
					{
						$f_path_array = pathinfo ($f_path);
						$f_object_name = $f_path_array['basename'];
						$f_physical_path .= "/".$f_path;

						$f_object_type = ((is_dir ($f_physical_path)) ? "text/directory" : $direct_classes['basic_functions']->mimetype_extension ($f_path_array['extension']));

						$this->data['ddbdatalinker_id_parent'] = (($f_path_array['dirname'] == ".") ? $this->data['ddbdatalinker_id'] : $this->data['ddbdatalinker_id'].":".(base64_encode ($f_path_array['dirname'])));
						$this->data['ddbdatalinker_id'] = $this->data['ddbdatalinker_id'].":".(base64_encode ($f_path));
						$this->physical = $f_path;
					}

					if (($f_physical_path)&&(file_exists ($f_physical_path))&&(is_readable ($f_physical_path)))
					{
						if (is_dir ($f_physical_path))
						{
							$this->physical_objects = $this->get_physical_objects ($f_physical_path);
							$this->data['ddbdatalinker_objects'] = count ($this->physical_objects);
						}
						else
						{
							$this->data['ddbdatalinker_type'] = 0;
							$this->data['ddbdatalinker_objects'] = 0;
							$this->data_directory = false;
						}

						$this->data['ddbdatacenter_size'] = filesize ($f_physical_path);
						$this->data_subs_allowed = false;

						if (isset ($this->physical))
						{
							$this->data['ddbdatalinker_title_alt'] = $f_object_name;
							$this->data['ddbdatalinker_sorting_date'] = filemtime ($f_physical_path);
							$this->data['ddbdatacenter_type'] = $f_object_type;
							$this->data['ddbdatacenter_desc'] = "";
						}
					}
					else { $this->data_readable = false; }
				}

				if ($this->data_readable) { $f_return = $this->data; }
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->get_aid ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->get_evars ()
/**
	* Reads evars from the archive for this object.
	*
	* @uses   direct_datalinker::define_extra_attributes()
	* @uses   direct_datalinker::define_extra_conditions()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get()
	* @uses   direct_db::define_row_conditions_encode()
	* @uses   direct_debug()
	* @uses   direct_kernel_system::v_group_user_check_right()
	* @uses   direct_kernel_system::v_usertype_get_int()
	* @uses   USE_debug_reporting
	* @return mixed Array with key -> value pairs or false on error
	* @since  v0.1.00
*/
	public function get_evars ()
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->get_evars ()- (#echo(__LINE__)#)"); }

		$f_return = false;

		if ($this->data_evars) { $f_return = $this->data_evars; }
		elseif ($this->data_evars_id)
		{
			$direct_classes['db']->init_select ($direct_settings['evars_archive_table']);
			$direct_classes['db']->define_attributes (array ($direct_settings['evars_archive_table'].".ddbevars_archive_data"));
			$direct_classes['db']->define_row_conditions ("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['evars_archive_table'].".ddbevars_archive_id",$this->data_evars_id,"string"))."</sqlconditions>");
			$direct_classes['db']->define_limit (1);
			$f_data = $direct_classes['db']->query_exec ("ss");

			if ($f_data)
			{
				$f_return = direct_evars_get ($f_data);
				if ($f_return) { $this->data_evars = $f_return; }
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->get_evars ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->get_plocation ($f_path = "")
/**
	* Get the location of a file using the given base path.
	*
	* @param  string $f_path Base directory path
	* @uses   direct_datacenter::get_evars()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return string Path or an empty string
	* @since  v0.1.00
*/
	public function get_plocation ($f_path = "")
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->get_plocation ($f_path)- (#echo(__LINE__)#)"); }
		$f_return = "";

		if ($this->data_evars_id)
		{
			if (empty ($this->data_evars)) { $this->get_evars (); }
			if ((isset ($this->data_evars['ddbdatacenter_plocation']))&&(strlen ($this->data_evars['ddbdatacenter_plocation']))) { $f_return = $f_path.$this->data_evars['ddbdatacenter_plocation']; }
		}
		elseif ((isset ($this->data['ddbdatacenter_plocation']))&&(strlen ($this->data['ddbdatacenter_plocation']))) { $f_return = $f_path.$this->data['ddbdatacenter_plocation']; }

		if ($this->physical !== NULL) { $f_return .= "/".$this->physical; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->get_plocation ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->get_objects ($f_object_status,$f_offset = 0,$f_perpage = "",$f_sorting_mode = "title-sticky-asc")
/**
	* Returns all subobjects for the DataLinker with the given service ID and
	* type.
	*
	* @param  integer $f_object_status Object status (1 = objects that are not
	*         deleted; 2 = all)
	* @param  integer $f_offset Offset for the result list
	* @param  integer $f_perpage Object count limit for the result list
	* @param  string $f_sorting_mode Sorting algorithm
	* @uses   direct_datalinker::define_extra_attributes()
	* @uses   direct_datalinker::define_extra_conditions()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get_subs()
	* @uses   direct_db::define_row_conditions_encode()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return array Array with pointers to the documents
	* @since  v0.1.00
*/
	public function get_objects ($f_object_status,$f_offset = 0,$f_perpage = "",$f_sorting_mode = "title-sticky-asc")
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->get_objects ($f_object_status,$f_offset,$f_perpage,$f_sorting_mode,+f_frontpage_mode)- (#echo(__LINE__)#)"); }

		$f_return = array ();
		$f_cache_signature = md5 ($this->data['ddbdatalinker_id_object'].$f_object_status.$f_offset.$f_perpage.$f_sorting_mode);

		if (isset ($this->class_subs[$f_cache_signature])) { $f_return =& $this->class_subs[$f_cache_signature]; }
		elseif (isset ($this->data['ddbdatalinker_id_object']))
		{
$f_init_array = array (
"data_markers" => $this->data_markers,
"marker_connector" => $this->marker_connector,
"marker_connector_type" => $this->marker_connector_type,
"marker_title_mark" => $this->marker_title_mark,
"marker_title_unmark" => $this->marker_title_unmark,
"marker_type" => $this->marker_type
);

			if ($this->physical)
			{
				$f_logical_path = $this->physical."/";
				$f_physical_path = $this->get_plocation ();
			}
			elseif ($this->data_directory)
			{
				$f_physical_path = $this->get_plocation ();
				if ($f_physical_path) { $f_logical_path = ""; }
			}
			else { $f_logical_path = NULL; }

			if (isset ($f_logical_path))
			{
				$f_objects_array = $this->get_sorted_physical_objects ($f_physical_path,$f_logical_path,$f_sorting_mode);
				$this->class_subs[$f_cache_signature] = array ();

				if ($f_offset <= $this->data['ddbdatalinker_objects'])
				{
					$f_objects_array = array_slice ($f_objects_array,$f_offset,$f_perpage);

					foreach ($f_objects_array as $f_data_array)
					{
						if (!isset ($f_data_array['ddbdatalinker_sorting_date'])) { $f_data_array['ddbdatalinker_sorting_date'] = filemtime ($f_data_array['datacenter_physical']); }
						$f_data_array['ddbdatacenter_size'] = filesize ($f_data_array['datacenter_physical']);

						$f_datacenter_object = new direct_datacenter ($f_init_array);
						if ($f_datacenter_object->set($f_data_array)) { $this->class_subs[$f_cache_signature][] = $f_datacenter_object; }
					}
				}
			}
			else
			{
				$this->define_extra_attributes (array ($direct_settings['datacenter_table'].".*",$direct_settings['users_table'].".ddbusers_type",$direct_settings['users_table'].".ddbusers_banned",$direct_settings['users_table'].".ddbusers_deleted",$direct_settings['users_table'].".ddbusers_name",$direct_settings['users_table'].".ddbusers_title",$direct_settings['users_table'].".ddbusers_avatar",$direct_settings['users_table'].".ddbusers_signature",$direct_settings['users_table'].".ddbusers_rating"));

$f_select_joins = array (
 array ("type" => "left-outer-join","table" => $direct_settings['datacenter_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['datacenter_table']}.ddbdatacenter_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>"),
 array ("type" => "left-outer-join","table" => $direct_settings['users_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['users_table']}.ddbusers_id' value='{$direct_settings['datacenter_table']}.ddbdatacenter_last_id' type='attribute' /></sqlconditions>")
);

				$this->define_extra_joins ($f_select_joins);
				if ($f_object_status == 1) { $this->define_extra_conditions ("<element1 attribute='{$direct_settings['datacenter_table']}.ddbdatacenter_deleted' value='0' type='number' />"); }

				$this->class_subs[$f_cache_signature] = parent::get_subs ("direct_datacenter",NULL,$this->data['ddbdatalinker_id_object'],"d4d66a02daefdb2f70ff2507a78fd5ec","",$f_offset,$f_perpage,$f_sorting_mode,$f_init_array);
				// md5 ("datacenter")
			}

			$f_return =& $this->class_subs[$f_cache_signature];
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->get_objects ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->get_objects_since_date ($f_object_status,$f_date,$f_offset = 0,$f_perpage = "",$f_sorting_mode = "title-sticky-asc",$f_count_only = false)
/**
	* Returns all subobjects for the DataLinker with the given service ID and
	* type that are newer than a specific date.
	*
	* @param  integer $f_object_status Object status (1 = objects that are not
	*         deleted; 2 = all)
	* @param  integer $f_date UNIX timestamp for the oldest valid post date
	* @param  integer $f_offset Offset for the result list
	* @param  integer $f_perpage Object count limit for the result list
	* @param  string $f_sorting_mode Sorting algorithm
	* @param  boolean $f_count_only True to return the number of posts
	* @uses   direct_datalinker::define_extra_attributes()
	* @uses   direct_datalinker::define_extra_conditions()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get_subs()
	* @uses   direct_db::define_attributes()
	* @uses   direct_db::define_row_conditions()
	* @uses   direct_db::define_row_conditions_encode()
	* @uses   direct_db::init_select()
	* @uses   direct_db::query_exec()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return mixed Array with pointers to the posts since the last visit or
	*         post quantity
	* @since  v0.1.00
*/
	public function get_objects_since_date ($f_object_status,$f_date,$f_offset = 0,$f_perpage = "",$f_sorting_mode = "title-sticky-asc",$f_count_only = false)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->get_objects_since_date ($f_object_status,$f_date,$f_offset,$f_perpage,$f_sorting_mode,+f_count_only)- (#echo(__LINE__)#)"); }

		if ($f_count_only)
		{
			$f_return = 0;
			$f_cache_signature = md5 ("osdc".$this->data['ddbdatalinker_id_object'].$f_object_status);
		}
		else
		{
			$f_return = array ();
			$f_cache_signature = md5 ("osd".$this->data['ddbdatalinker_id_object'].$f_object_status.$f_offset.$f_perpage.$f_sorting_mode);
		}

		if (isset ($this->class_subs[$f_cache_signature])) { $f_return =& $this->class_subs[$f_cache_signature]; }
		elseif (($f_count_only)&&($f_object_status == 1)&&($f_date < 1)) { $f_return = $this->data['ddbdatalinker_objects']; }
		elseif (isset ($this->data['ddbdatalinker_id_object']))
		{
$f_init_array = array (
"data_markers" => $this->data_markers,
"marker_connector" => $this->marker_connector,
"marker_connector_type" => $this->marker_connector_type,
"marker_title_mark" => $this->marker_title_mark,
"marker_title_unmark" => $this->marker_title_unmark,
"marker_type" => $this->marker_type
);

			if ($this->physical)
			{
				$f_logical_path = $this->physical."/";
				$f_physical_path = $this->get_plocation ();
			}
			elseif ($this->data_directory)
			{
				$f_physical_path = $this->get_plocation ();
				if ($f_physical_path) { $f_logical_path = ""; }
			}
			else { $f_logical_path = NULL; }

			if ($f_logical_path === NULL)
			{
				if ($f_count_only) { $f_select_attributes = array ("count-rows({$direct_settings['datalinker_table']}.ddbdatalinker_id)"); }
				else { $f_select_attributes = array ($direct_settings['datacenter_table'].".*",$direct_settings['users_table'].".ddbusers_type",$direct_settings['users_table'].".ddbusers_banned",$direct_settings['users_table'].".ddbusers_deleted",$direct_settings['users_table'].".ddbusers_name",$direct_settings['users_table'].".ddbusers_title",$direct_settings['users_table'].".ddbusers_avatar",$direct_settings['users_table'].".ddbusers_signature",$direct_settings['users_table'].".ddbusers_rating"); }

				$this->define_extra_attributes ($f_select_attributes);

				if (!$f_count_only)
				{
$this->define_extra_joins (array (
 array ("type" => "left-outer-join","table" => $direct_settings['datacenter_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['datacenter_table']}.ddbdatacenter_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>"),
 array ("type" => "left-outer-join","table" => $direct_settings['users_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['users_table']}.ddbusers_id' value='{$direct_settings['datacenter_table']}.ddbdatacenter_last_id' type='attribute' /></sqlconditions>")
));
				}

				if ($f_date > 0) { $this->define_extra_conditions ($direct_classes['db']->define_row_conditions_encode ($direct_settings['datacenter_table'].".ddbdatalinker_sorting_date",$f_date,"number",">")); }
				if ($f_object_status == 1) { $this->define_extra_conditions ("<element1 attribute='{$direct_settings['datacenter_table']}.ddbdatacenter_deleted' value='0' type='number' />"); }

				if ($f_count_only) { $this->class_subs[$f_cache_signature] = parent::get_subs ("",NULL,$this->data['ddbdatalinker_id_object'],"d4d66a02daefdb2f70ff2507a78fd5ec","",0,1,"time-desc"); }
				else
				{
					$this->class_subs[$f_cache_signature] = parent::get_subs ("direct_datacenter",NULL,$this->data['ddbdatalinker_id_object'],"d4d66a02daefdb2f70ff2507a78fd5ec","",$f_offset,$f_perpage,$f_sorting_mode,$f_init_array);
					// md5 ("datacenter")
				}
			}
			else
			{
				$f_objects_array = $this->get_sorted_physical_objects ($f_physical_path,$f_logical_path,$f_sorting_mode,$f_since_date);
				$f_objects_count = count ($f_objects_array);

				if ($f_count_only) { $this->class_subs[$f_cache_signature] = $f_objects_count; }
				else
				{
					$this->class_subs[$f_cache_signature] = array ();

					if ($f_offset <= $f_objects_count)
					{
						$f_objects_array = array_slice ($f_objects_array,$f_offset,$f_perpage);

						foreach ($f_objects_array as $f_data_array)
						{
							$f_datacenter_object = new direct_datacenter ($f_init_array);
							$f_data_array['ddbdatacenter_size'] = filesize ($f_data_array['datacenter_physical']);
							if ($f_datacenter_object->set($f_data_array)) { $this->class_subs[$f_cache_signature][] = $f_datacenter_object; }
						}
					}
				}
			}

			$f_return =& $this->class_subs[$f_cache_signature];
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->get_objects_since_date ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->get_physical_objects ($f_physical_path)
/**
	* Returns all subobjects of a physical folder. Keys might not be continuous.
	*
	* @param  string $f_sorting_mode Sorting algorithm
	* @uses   direct_debug()
	* @uses   direct_dir_is_readable()
	* @uses   USE_debug_reporting
	* @return array Directory content
	* @since  v0.1.00
*/
	protected function get_physical_objects ($f_physical_path)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->get_physical_objects ($f_physical_path)- (#echo(__LINE__)#)"); }
		$f_return = array ();

		if ((strlen ($f_physical_path))&&(direct_dir_is_readable ($f_physical_path)))
		{
			$f_return = scandir ($f_physical_path);

			foreach ($f_return as $f_key => $f_value)
			{
				if ($f_value[0] == ".") { unset ($f_return[$f_key]); }
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->get_physical_objects ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->get_rights ()
/**
	* Check the user rights based on the defined object.
	*
	* @uses   direct_debug()
	* @uses   direct_kernel_system::v_usertype_get_int()
	* @uses   USE_debug_reporting
	* @return array Array with the results to read and write
	* @since  v0.1.00
*/
	protected function get_rights ()
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->get_rights ()- (#echo(__LINE__)#)"); }

		$f_return = array (false,false);

		if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3)
		{
			$f_return[0] = true;
			if (!$this->data_deleted) { $f_return[1] = true; }
		}
		elseif ($direct_settings['user']['type'] != "gt")
		{
			if (($this->data['ddbdatacenter_owner_id'] == $direct_settings['user']['id'])&&(($this->data['ddbdatacenter_mode_owner'] == "r")||($this->data['ddbdatacenter_mode_owner'] == "w")))
			{
				$f_return[0] = true;
				if ((!$this->data_deleted)&&($this->data['ddbdatacenter_mode_owner'] == "w")) { $f_return[1] = true; }
			}
			elseif (($this->data['ddbdatacenter_last_id'] == $direct_settings['user']['id'])&&(($this->data['ddbdatacenter_mode_last'] == "r")||($this->data['ddbdatacenter_mode_last'] == "w")))
			{
				$f_return[0] = true;
				if ((!$this->data_deleted)&&($this->data['ddbdatacenter_mode_last'] == "w")) { $f_return[1] = true; }
			}
			elseif ($this->data['ddbdatacenter_mode_all'] == "w")
			{
				$f_return[0] = true;
				if (!$this->data_deleted) { $f_return[1] = true; }
			}
			elseif ($direct_classes['kernel']->v_group_user_check_right ("datacenter_{$this->data['ddbdatalinker_id_object']}_write"))
			{
				$f_return[0] = true;
				if (!$this->data_deleted) { $f_return[1] = true; }
			}
			elseif ($this->data['ddbdatacenter_mode_all'] == "r") { $f_return[0] = true; }
			else { $f_return[0] = $direct_classes['kernel']->v_group_user_check_right ("datacenter_{$this->data['ddbdatalinker_id_object']}_read"); }
		}
		elseif (($this->data['ddbdatacenter_mode_all'] == "r")||($this->data['ddbdatacenter_mode_all'] == "w")) { $f_return[0] = true; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->get_rights ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->get_sorted_physical_objects ($f_physical_path,$f_logical_path,$f_sorting_mode = "title-sticky-asc",$f_since_date = 0)
/**
	* Returns all subobjects for the DataLinker with the given service ID and
	* type.
	*
	* @param  integer $f_object_status Object status (1 = objects that are not
	*         deleted; 2 = all)
	* @param  integer $f_offset Offset for the result list
	* @param  integer $f_perpage Object count limit for the result list
	* @param  string $f_sorting_mode Sorting algorithm
	* @uses   direct_datalinker::define_extra_attributes()
	* @uses   direct_datalinker::define_extra_conditions()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get_subs()
	* @uses   direct_db::define_row_conditions_encode()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return array Array with pointers to the documents
	* @since  v0.1.00
*/
	protected function get_sorted_physical_objects ($f_physical_path,$f_logical_path,$f_sorting_mode = "title-sticky-asc",$f_since_date = 0)
	{
		global $direct_classes;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->get_sorted_physical_objects ($f_physical_path,$f_logical_path,$f_sorting_mode,$f_since_date)- (#echo(__LINE__)#)"); }
		$f_return = array ();

		if ((strlen ($f_physical_path))&&(direct_dir_is_readable ($f_physical_path)))
		{
			if (empty ($this->physical_objects)) { $this->physical_objects = $this->get_physical_objects ($f_physical_path); }

			$f_dirs_array = array ();
			$f_files_array = array ();
			$f_sort_key = "ddbdatalinker_title_alt";
			$f_sort_reverse = false;

			switch ($f_sorting_mode)
			{
			case "id-desc":
			case "position-desc":
			case "title-desc":
			case "title-sticky-desc":
			{
				$f_sort_reverse = true;
				break 1;
			}
			case "time-asc":
			case "time-sticky-asc":
			{
				$f_sort_key = "ddbdatalinker_sorting_date";
				break 1;
			}
			case "time-desc":
			case "time-sticky-desc":
			{
				$f_sort_key = "ddbdatalinker_sorting_date";
				$f_sort_reverse = true;
				break 1;
			}
			}

			$f_logical_path_encoded = base64_encode ($f_logical_path);

			foreach ($this->physical_objects as $f_object)
			{
				$f_data_array = $this->data;
				$f_data_array['datacenter_physical'] = $f_physical_path."/".$f_object;
				$f_data_array['ddbdatacenter_plocation'] = $f_logical_path.$f_object;
				$f_data_array['ddbdatalinker_sorting_date'] = ((($f_since_date)||($f_sort_key == "ddbdatalinker_sorting_date")) ? filemtime ($f_data_array['datacenter_physical']) : NULL);

				if ((!$f_since_date)||($f_since_date < $f_data_array['ddbdatalinker_sorting_date']))
				{
					$f_data_array['ddbdatalinker_id'] = ((preg_match ("#^(.+?):(.*?)$#",$this->data['ddbdatalinker_id'],$f_result_array)) ? $f_result_array[1].":".(base64_encode ($f_logical_path.$f_object)) : $this->data['ddbdatalinker_id'].":".(base64_encode ($f_logical_path.$f_object)));
					$f_data_array['ddbdatalinker_objects'] = 0;
					$f_data_array['ddbdatalinker_title_alt'] = $f_object; 
					$f_data_array['ddbdatacenter_desc'] = "";

					$f_object_array = pathinfo ($f_logical_path.$f_object);

					$f_data_array['ddbdatalinker_id_parent'] = (($f_object_array['dirname'] == ".") ? $this->data['ddbdatalinker_id'] : $this->data['ddbdatalinker_id'].":".$f_logical_path_encoded);

					if (is_dir ($f_data_array['datacenter_physical']))
					{
						$f_data_array['ddbdatalinker_type'] = 1;
						$f_dirs_array[$f_data_array[$f_sort_key].$f_object] = $f_data_array;
					}
					else
					{
						$f_data_array['ddbdatalinker_type'] = 0;
						$f_data_array['ddbdatacenter_type'] = $direct_classes['basic_functions']->mimetype_extension ($f_object_array['extension']);
						$f_files_array[$f_data_array[$f_sort_key].$f_object] = $f_data_array;
					}
				}
			}

			if ($f_sort_reverse)
			{
				if ((krsort ($f_dirs_array))&&(krsort ($f_files_array))) { $f_return = array_merge ($f_dirs_array,$f_files_array); }
			}
			elseif ((ksort ($f_dirs_array))&&(ksort ($f_files_array))) { $f_return = array_merge ($f_dirs_array,$f_files_array); }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->get_sorted_physical_objects ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->get_upload_folder ($f_path,$f_name_length = 2)
/**
	* Returns the current upload folder for the given settings. If the directory
	* does not exist it will be created.
	*
	* @param  string $f_path Base path for the upload folder
	* @param  integer $f_name_length Folder name length
	* @uses   direct_debug()
	* @uses   direct_dir_create()
	* @uses   USE_debug_reporting
	* @return mixed Folder name on success; False on error
	* @since  v0.1.00
*/
	public function get_upload_folder ($f_path,$f_name_length = 2)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->get_upload_folder ($f_path,$f_name_length)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if ($f_name_length)
		{
			$f_prefix = uniqid (uniqid (""));
			$f_prefix = substr ($f_prefix,0,$f_name_length);
			if (direct_dir_create ($f_path.$f_prefix)) { $f_return = $f_prefix; }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->get_upload_folder ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->is_deleted ()
/**
	* Returns true if the object is deleted.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_deleted ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->is_deleted ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->is_deleted ()- (#echo(__LINE__)#)",:#*/$this->data_deleted/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->is_directory ()
/**
	* Returns true if the object is directory.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_directory ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->is_directory ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->is_directory ()- (#echo(__LINE__)#)",:#*/$this->data_directory/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->is_locked ()
/**
	* Returns true if the object is locked.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_locked ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->is_locked ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->is_locked ()- (#echo(__LINE__)#)",:#*/$this->data_locked/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->is_marked ()
/**
	* Returns true if the object is marked.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_marked ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->is_marked ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->is_marked ()- (#echo(__LINE__)#)",:#*/$this->data_marked/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->is_physical ()
/**
	* DataCenter entries are usually stored in upload directories. This method
	* returns true if the object is a physical one in the filesystem.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_physical ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->is_physical ()- (#echo(__LINE__)#)"); }

		if ($this->physical === NULL) { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->is_physical ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/; }
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->is_physical ()- (#echo(__LINE__)#)",:#*/true/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_datacenter->is_readable ()
/**
	* Returns true if the current user is allowed to read sub-objects of this
	* DataCenter entry.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_readable ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->is_readable ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->is_readable ()- (#echo(__LINE__)#)",:#*/$this->data_readable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->is_trusted ()
/**
	* Returns true if the current object is marked as trusted. This will hide
	* security related warnings.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_trusted ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->is_trusted ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->is_trusted ()- (#echo(__LINE__)#)",:#*/$this->data_trusted/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->is_writable ()
/**
	* Returns true if the current user is allowed to create new and edit his
	* sub-objects.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_writable ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->is_writable ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->is_writable ()- (#echo(__LINE__)#)",:#*/$this->data_writable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->parse ($f_connector,$f_connector_type = "url0",$f_prefix = "")
/**
	* Parses this DataLinker object and returns valid (X)HTML.
	*
	* @param  string $f_connector Connector for links
	* @param  string $f_connector_type Linking mode: "url0" for internal links,
	*         "url1" for external ones, "form" to create hidden fields or
	*         "optical" to remove parts of a very long string.
	* @param  string $f_prefix Key prefix
	* @uses   direct_basic_functions::datetime()
	* @uses   direct_basic_functions::mimetype_icon()
	* @uses   direct_basic_functions::varfilter()
	* @uses   direct_datacenter::get_plocation()
	* @uses   direct_datalinker::parse()
	* @uses   direct_debug()
	* @uses   direct_formtags::decode()
	* @uses   direct_html_encode_special()
	* @uses   direct_kernel_system::v_user_parse()
	* @uses   direct_kernel_system::v_usertype_get_int()
	* @uses   direct_linker()
	* @uses   direct_linker_dynamic()
	* @uses   direct_local_get()
	* @uses   USE_debug_reporting
	* @return array Output data
	* @since  v0.1.00
*/
	public function parse ($f_connector,$f_connector_type = "url0",$f_prefix = "")
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->parse ($f_connector,$f_connector_type,$f_prefix)- (#echo(__LINE__)#)"); }

		$f_return = parent::parse ($f_prefix);

		if (($f_return)&&($this->data_readable)&&(count ($this->data) > 1))
		{
			$f_return[$f_prefix."id"] = "swgdhandlerdatacenter".$f_return[$f_prefix."subs_id"];
			$f_return[$f_prefix."dir"] = $this->data_directory;
			$f_return[$f_prefix."type"] = $this->data['ddbdatacenter_type'];
			if ($this->data_deleted) { $f_return[$f_prefix."type"] .= " <span style='font-weight:bold'>(".(direct_local_get ("datacenter_object_deleted")).")</span>"; }

			if (($f_connector_type != "asis")&&(strpos ($f_connector,"javascript:") === 0)) { $f_connector_type = "asis"; }

			if ($this->data_locked)
			{
				$f_return[$f_prefix."pageurl"] = "";
				$f_return[$f_prefix."type"] .= " <span style='font-weight:bold'>(".(direct_local_get ("datacenter_object_locked")).")</span>";
			}
			else
			{
				$f_pageurl = str_replace ("[a]","view",$f_connector);
				$f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",(urlencode ($this->data['ddbdatalinker_id'])),$f_pageurl) : str_replace ("[oid]","doid+".(urlencode ($this->data['ddbdatalinker_id']))."++",$f_pageurl));
				$f_pageurl = preg_replace ("#\[(.*?)\]#","",$f_pageurl);
				$f_return[$f_prefix."pageurl"] = direct_linker ($f_connector_type,$f_pageurl);
			}

			if ($this->data['ddbdatalinker_id_parent'])
			{
				$f_pageurl = str_replace ("[a]","view",$f_connector);
				$f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",(urlencode ($this->data['ddbdatalinker_id_parent'])),$f_pageurl) : str_replace ("[oid]","doid+".(urlencode ($this->data['ddbdatalinker_id_parent']))."++",$f_pageurl));
				$f_pageurl = preg_replace ("#\[(.*?)\]#","",$f_pageurl);
				$f_return[$f_prefix."pageurl_parent"] = direct_linker ($f_connector_type,$f_pageurl);
			}
			else { $f_return[$f_prefix."pageurl_parent"] = ""; }

			$f_return[$f_prefix."pageurl_download"] = ($this->data_directory ? "" : direct_linker ($f_connector_type,"m=datacenter&a=transfer_dl&dsd=doid+".(urlencode ($this->data['ddbdatalinker_id']))));
			$f_return[$f_prefix."owner"] = $this->data['ddbdatacenter_last_id'];
			$f_return[$f_prefix."size"] = $this->data['ddbdatacenter_size'];
			$f_return[$f_prefix."icon"] = "";

			if (($direct_settings['datacenter_mimetype_iconize'])&&($this->data['ddbdatacenter_type']))
			{
				$f_icon_path = $direct_classes['basic_functions']->varfilter ($direct_settings['datacenter_path_icons'],"settings");
				$f_icon = $direct_classes['basic_functions']->mimetype_icon ($this->data['ddbdatacenter_type']);

				if ($f_icon)
				{
					if (file_exists ($f_icon_path.$f_icon)) { $f_return[$f_prefix."icon"] = direct_linker_dynamic ("url0","s=cache&dsd=dfile+".$f_icon_path.$f_icon,true,false); }
					elseif (file_exists ($f_icon_path.$direct_settings['datacenter_mimetype_unknown'])) { $f_return[$f_prefix."icon"] = direct_linker_dynamic ("url0","s=cache&dsd=dfile+".$f_icon_path.$direct_settings['datacenter_mimetype_unknown'],true,false); }
				}
				elseif (file_exists ($f_icon_path.$direct_settings['datacenter_mimetype_unknown'])) { $f_return[$f_prefix."icon"] = direct_linker_dynamic ("url0","s=cache&dsd=dfile+".$f_icon_path.$direct_settings['datacenter_mimetype_unknown'],true,false); }
			}

			$f_return[$f_prefix."desc"] = $direct_classes['formtags']->decode ($this->data['ddbdatacenter_desc']);
			$f_return[$f_prefix."time"] = ($this->data['ddbdatalinker_sorting_date'] ? $direct_classes['basic_functions']->datetime ("longdate&time",$this->data['ddbdatalinker_sorting_date'],$direct_settings['user']['timezone'],(direct_local_get ("datetime_dtconnect"))) : direct_local_get ("core_unknown"));

			$f_markable_check = false;
			$f_return[$f_prefix."marked"] = $this->data_marked;
			$f_return[$f_prefix."marker_title"] = "";
			$f_return[$f_prefix."pageurl_marker"] = "";

			switch ($this->marker_type)
			{
			case 1:
			{
				if ($this->data_directory) { $f_markable_check = true; }
				break 1;
			}
			case 2:
			{
				if (($this->data_directory)&&($this->data_writable)) { $f_markable_check = true; }
				break 1;
			}
			case 3:
			{
				if (!$this->data_directory) { $f_markable_check = true; }
				break 1;
			}
			case 4:
			{
				if ((!$this->data_directory)&&($this->data_writable)) { $f_markable_check = true; }
				break 1;
			}
			case 5:
			{
				if ($this->data_writable) { $f_markable_check = true; }
				break 1;
			}
			default: { $f_markable_check = true; }
			}

			if (($this->marker_connector)&&($f_markable_check))
			{
				$f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",(urlencode ($this->data['ddbdatalinker_id'])),$this->marker_connector) : str_replace ("[oid]","doid+".(urlencode ($this->data['ddbdatalinker_id']))."++",$this->marker_connector));
				$f_pageurl = preg_replace ("#\[(.*?)\]#","",$f_pageurl);
				$f_return[$f_prefix."pageurl_marker"] = direct_linker ($this->marker_connector_type,$f_pageurl);

				$f_return[$f_prefix."marker_title"] = ($this->data_marked ? $this->marker_title_unmark : $this->marker_title_mark);
			}

			$f_return[$f_prefix."plocation"] = $this->get_plocation ();

			if (($this->data['ddbdatacenter_last_id'])&&($this->data['ddbusers_name'])) { $f_user_parsed_array = $direct_classes['kernel']->v_user_parse ($this->data['ddbdatacenter_last_id'],$this->data,$f_prefix."user"); }
			else
			{
$f_user_parsed_array = array (
$f_prefix."userid" => "",
$f_prefix."username" => "",
$f_prefix."userpageurl" => "",
$f_prefix."usertype" => direct_local_get ("core_unknown"),
$f_prefix."usertitle" => "",
$f_prefix."useravatar" => "",
$f_prefix."useravatar_small" => "",
$f_prefix."useravatar_large" => "",
$f_prefix."userrating" => direct_local_get ("core_unknown"),
$f_prefix."usersignature" => ""
);
			}

			$f_return = array_merge ($f_return,$f_user_parsed_array);

			$f_return[$f_prefix."userip"] = (($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) ? $this->data['ddbdatacenter_last_ip'] : "");
			$f_return[$f_prefix."trusted"] =  $this->data_trusted;
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->parse ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->remove_objects ($f_count,$f_update = true)
/**
	* Decreases the object counter.
	*
	* @param  number $f_count Number to be removed from the object counter
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_datalinker::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function remove_objects ($f_count,$f_update = true)
	{
		if (USE_debug_reporting) { direct_debug (8,"sWG/#echo(__FILEPATH__)# -datacenter->remove_objects ($f_count,+f_update)- (#echo(__LINE__)#)"); }

		if ($this->physical === NULL) { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->remove_objects ()- (#echo(__LINE__)#)",(:#*/parent::remove_objects ($f_count,$f_update)/*#ifdef(DEBUG):),true):#*/; }
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->remove_objects ()- (#echo(__LINE__)#)",:#*/true/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_datacenter->set ($f_data)
/**
	* Sets (and overwrites existing) data for this entry.
	*
	* @param  array $f_data DataCenter entry data
	* @uses   direct_datacenter::get_rights()
	* @uses   direct_datalinker::set()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success (data valid and current user has read
	*         rights)
	* @since  v0.1.00
*/
	public function set ($f_data)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->set (+f_data)- (#echo(__LINE__)#)"); }

		$f_return = parent::set ($f_data);

		if (($f_return)&&(isset ($f_data['ddbdatacenter_type'],$f_data['ddbdatacenter_mode_owner'],$f_data['ddbdatacenter_mode_last'],$f_data['ddbdatacenter_mode_all'],$f_data['ddbdatacenter_deleted'],$f_data['ddbdatacenter_locked'])))
		{
			if (!isset ($f_data['ddbdatacenter_owner_id'],$f_data['ddbdatacenter_owner_ip']))
			{
				$f_data['ddbdatacenter_owner_id'] = $direct_settings['user']['id'];
				$f_data['ddbdatacenter_owner_ip'] = $direct_settings['user_ip'];
			}

			if (!isset ($f_data['ddbdatacenter_last_id'],$f_data['ddbdiscuss_posts_user_ip']))
			{
				$f_data['ddbdatacenter_last_id'] = $direct_settings['user']['id'];
				$f_data['ddbdatacenter_last_ip'] = $direct_settings['user_ip'];
			}

			if (!$direct_settings['swg_ip_save2db'])
			{
				$f_data['ddbdatacenter_owner_ip'] = "unknown";
				$f_data['ddbdatacenter_last_ip'] = "unknown";
			}

			if (!isset ($f_data['ddbusers_type'],$f_data['ddbusers_banned'],$f_data['ddbusers_deleted'],$f_data['ddbusers_name'],$f_data['ddbusers_title'],$f_data['ddbusers_avatar'],$f_data['ddbusers_signature'],$f_data['ddbusers_rating']))
			{
				$f_user_parsed_array = $direct_classes['kernel']->v_user_get ($f_data['ddbdatacenter_last_id']);
				if ($f_user_parsed_array) { $f_data = array_merge ($f_data,$f_user_parsed_array); }
			}

			if (!isset ($f_data['ddbdatacenter_size'])) { $f_data['ddbdatacenter_size'] = 0; }
			if (!isset ($f_data['ddbdatacenter_desc'])) { $f_data['ddbdatacenter_desc'] = ""; }
			if (!isset ($f_data['ddbdatacenter_plocation'])) { $f_data['ddbdatacenter_plocation'] = ""; }
			if (!isset ($f_data['ddbdatacenter_trusted'])) { $f_data['ddbdatacenter_trusted'] = (($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) ? 1 : 0); }

			$this->set_extras ($f_data,(array ("ddbdatacenter_owner_id","ddbdatacenter_owner_ip","ddbdatacenter_last_id","ddbdatacenter_last_ip","ddbdatacenter_size","ddbdatacenter_type","ddbdatacenter_desc","ddbdatacenter_plocation","ddbdatacenter_mode_owner","ddbdatacenter_mode_last","ddbdatacenter_mode_all","ddbdatacenter_trusted","ddbdatacenter_deleted","ddbdatacenter_locked","ddbusers_type","ddbusers_banned","ddbusers_deleted","ddbusers_name","ddbusers_title","ddbusers_avatar","ddbusers_signature","ddbusers_rating")));
			$this->data_pid = $this->data['ddbdatalinker_id_parent'];

			if (preg_match ("#^(.+?):(.*?)$#",(urldecode ($this->data['ddbdatalinker_id'])),$f_result_array))
			{
				$f_path = base64_decode ($f_result_array[2]);
				if (strlen ($f_path)) { $f_path = $direct_classes['basic_functions']->inputfilter_filepath ($f_path); }
			}
			else { $f_path = ""; }

			if (strpos ($this->data['ddbdatacenter_plocation'],"evars:") === 0)
			{
				$this->data_evars = array ();
				$this->data_evars_id = substr ($this->data['ddbdatacenter_plocation'],6);
			}

			$this->data_deleted = ($this->data['ddbdatacenter_deleted'] ? true : false);

			if ($this->data['ddbdatalinker_type'] == 1)
			{
				$f_physical_path = $this->get_plocation ();
				$this->data_directory = true;
			}
			else { $this->data_directory = false; }

			$this->data_trusted = ($this->data['ddbdatacenter_trusted'] ? true : false);
			$this->data_locked = ($this->data['ddbdatacenter_locked'] ? true : false);
			$this->data_marked = in_array ($this->data['ddbdatalinker_id'],$this->data_markers);

			$f_result_array = $this->get_rights ();
			$this->data_readable = $f_result_array[0];
			$this->data_writable = $f_result_array[1];

			if (($this->data_directory)&&($f_physical_path)&&($direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_dir_functions.php")))
			{
				if (isset ($f_data['datacenter_physical'])) { $f_physical_path = $f_data['datacenter_physical']; }

				if (strlen ($f_path))
				{
					if (!isset ($f_data['datacenter_physical'])) { $f_physical_path .= $f_path; }
					$this->physical = $f_path;
				}

				$this->data_subs_allowed = false;
				if ((!$f_physical_path)||(!file_exists ($f_physical_path))||(!is_readable ($f_physical_path))) { $this->data_readable = false; }
			}

			if (!$this->data_readable) { $f_return = false; }
		}
		else { $f_return = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->set ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->set_evars ($f_data)
/**
	* Adds evars to this object.
	*
	* @param  array $f_data Key -> value pairs
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return string evars archive ID or an empty one
	* @since  v0.1.00
*/
	public function set_evars ($f_data)
	{
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -datacenter->set_evars (+f_data)- (#echo(__LINE__)#)"); }
		$f_return = "";

		if (($this->physical === NULL)||($this->data_evars_id))
		{
			if ((is_array ($f_data))&&(!empty ($f_data)))
			{
				if (!$this->data_evars_id) { $this->data_evars_id = uniqid (""); }
				$f_return = $this->data_evars_id;

				if ($this->data['ddbdatacenter_plocation'] != "evars:".$this->data_evars_id)
				{
					if ((strlen ($this->data['ddbdatacenter_plocation']))&&(!isset ($f_data['ddbdatacenter_plocation']))) { $f_data['ddbdatacenter_plocation'] = $this->data['ddbdatacenter_plocation'] ; }
					$this->data['ddbdatacenter_plocation'] = "evars:".$this->data_evars_id;
				}

				$this->data_evars = $f_data;
			}
			else
			{
				if (isset ($this->data_evars['ddbdatacenter_plocation'])) { $this->data['ddbdatacenter_plocation'] = $this->data_evars['ddbdatacenter_plocation']; }
				$this->data_evars = array ();
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->set_evars ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter->set_insert ($f_data,$f_insert_mode_deactivate = true)
/**
	* Sets (and overwrites existing) the DataLinker entry and saves it to the
	* database. Note: If "set()" fails because of permission problems 
	* "update()" has to be called manually to write data to the database.
	* Please make sure that this is the intended behavior. You can use
	* "is_empty()" to check for the current data state of this object.
	*
	* @param  array $f_data DataLinker entry
	* @uses   direct_datacenter::set()
	* @uses   direct_datacenter::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function set_insert ($f_data,$f_insert_mode_deactivate = true)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->set_insert (+f_data,+f_insert_mode_deactivate)- (#echo(__LINE__)#)"); }

		if (($this->physical === NULL)&&($this->set ($f_data)))
		{
			$this->data_insert_mode = true;
			return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->set_insert ()- (#echo(__LINE__)#)",(:#*/$this->update ($f_insert_mode_deactivate)/*#ifdef(DEBUG):),true):#*/;
		}
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->set_insert ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_datacenter->set_update ($f_data)
/**
	* Updates (and overwrites) the existing DataLinker entry and saves it to the
	* database. Note: If "set()" fails because of permission problems 
	* "update()" has to be called manually to write data to the database.
	* Please make sure that this is the intended behavior. You can use
	* "is_empty()" to check for the current data state of this object.
	*
	* @param  array $f_data DataLinker entry
	* @uses   direct_datacenter::set()
	* @uses   direct_datacenter::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function set_update ($f_data)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter->set_update (+f_data)- (#echo(__LINE__)#)"); }

		if (($this->physical === NULL)&&($this->set ($f_data)))
		{
			$this->data_insert_mode = false;
			return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->set_update ()- (#echo(__LINE__)#)",(:#*/$this->update ()/*#ifdef(DEBUG):),true):#*/;
		}
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->set_update ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_datacenter->update ($f_insert_mode_deactivate = true)
/**
	* Writes the category data to the database.
	*
	* @uses   direct_db::define_values()
	* @uses   direct_db::define_values_keys()
	* @uses   direct_db::define_values_encode()
	* @uses   direct_db::init_replace()
	* @uses   direct_db::optimize_random()
	* @uses   direct_db::query_exec()
	* @uses   direct_db::v_optimize()
	* @uses   direct_db::v_transaction_begin()
	* @uses   direct_db::v_transaction_commit()
	* @uses   direct_db::v_transaction_rollback()
	* @uses   direct_dbsync_event()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function update ($f_insert_mode_deactivate = true)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -datacenter->update ()- (#echo(__LINE__)#)"); }

		if (empty ($this->data_changed)) { $f_return = true; }
		elseif ($this->physical === NULL)
		{
			$direct_classes['db']->v_transaction_begin ();
			$f_return = parent::update (true,true,false);

			if (($f_return)&&(is_array ($this->data))&&(!empty ($this->data)))
			{
				$f_delete_evars_check = ((($this->data_evars_id)&&($this->data['ddbdatacenter_plocation'] != "evars:".$this->data_evars_id)) ? true : false);

				if ($this->is_changed (array ("ddbdatacenter_owner_id","ddbdatacenter_owner_ip","ddbdatacenter_last_id","ddbdatacenter_last_ip","ddbdatacenter_size","ddbdatacenter_type","ddbdatacenter_desc","ddbdatacenter_plocation","ddbdatacenter_mode_owner","ddbdatacenter_mode_last","ddbdatacenter_mode_all","ddbdatacenter_trusted","ddbdatacenter_deleted","ddbdatacenter_locked")))
				{
					if ($this->data_insert_mode) { $direct_classes['db']->init_insert ($direct_settings['datacenter_table']); }
					else { $direct_classes['db']->init_update ($direct_settings['datacenter_table']); }

					$f_update_values = "<sqlvalues>";
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatalinker_id_object']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_id",$this->data['ddbdatalinker_id_object'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_owner_id']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_owner_id",$this->data['ddbdatacenter_owner_id'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_owner_ip']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_owner_ip",$this->data['ddbdatacenter_owner_ip'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_last_id']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_last_id",$this->data['ddbdatacenter_last_id'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_last_ip']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_last_ip",$this->data['ddbdatacenter_last_ip'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_size']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_size",$this->data['ddbdatacenter_size'],"number"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_type']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_type",$this->data['ddbdatacenter_type'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_desc']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_desc",$this->data['ddbdatacenter_desc'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_plocation']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_plocation",$this->data['ddbdatacenter_plocation'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_mode_owner']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_mode_owner",$this->data['ddbdatacenter_mode_owner'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_mode_last']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_mode_last",$this->data['ddbdatacenter_mode_last'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_mode_all']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_mode_all",$this->data['ddbdatacenter_mode_all'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_trusted']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_trusted",$this->data['ddbdatacenter_trusted'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_deleted']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_deleted",$this->data['ddbdatacenter_deleted'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatacenter_locked']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['datacenter_table'].".ddbdatacenter_locked",$this->data['ddbdatacenter_locked'],"string"); }
					$f_update_values .= "</sqlvalues>";

					$direct_classes['db']->define_set_attributes ($f_update_values);
					if (!$this->data_insert_mode) { $direct_classes['db']->define_row_conditions ("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['datacenter_table'].".ddbdatacenter_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>"); }
					$f_return = $direct_classes['db']->query_exec ("co");

					if (($f_return)&&(function_exists ("direct_dbsync_event")))
					{
						if ($this->data_insert_mode) { direct_dbsync_event ($direct_settings['datacenter_table'],"insert",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['datacenter_table'].".ddbdatacenter_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>")); }
						else { direct_dbsync_event ($direct_settings['datacenter_table'],"update",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['datacenter_table'].".ddbdatacenter_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>")); }

						if (!$direct_settings['swg_auto_maintenance']) { $direct_classes['db']->optimize_random ($direct_settings['datacenter_table']); }
					}
				}

				if (($f_insert_mode_deactivate)&&($this->data_insert_mode)) { $this->data_insert_mode = false; }

				if ($this->data_evars_id)
				{
					if ($f_delete_evars_check)
					{
						$direct_classes['db']->init_delete ($direct_settings['evars_archive_table']);

						$f_delete_criteria = "<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['evars_archive_table'].".ddbevars_archive_id",$this->data_evars_id,"string"))."</sqlconditions>";
						$direct_classes['db']->define_row_conditions ($f_delete_criteria);

						if ($direct_classes['db']->query_exec ("ar"))
						{
							if (function_exists ("direct_dbsync_event")) { direct_dbsync_event ($direct_settings['evars_archive_table'],"delete",$f_delete_criteria); }
							if (!$direct_settings['swg_auto_maintenance']) { $direct_classes['db']->v_optimize ($direct_settings['evars_archive_table']); }

							$this->data_evars_id = "";
						}
					}
					elseif (!empty ($this->data_evars))
					{
						$direct_classes['db']->init_replace ($direct_settings['evars_archive_table']);
						$direct_classes['db']->define_values_keys (array ("ddbevars_archive_id","ddbevars_archive_data"));

						$f_evars_data = direct_evars_write ($this->data_evars);

$f_replace_values = ("<sqlvalues>
".($direct_classes['db']->define_values_encode ($this->data_evars_id,"string"))."
".($direct_classes['db']->define_values_encode ($f_evars_data,"string"))."
</sqlvalues>");

						$direct_classes['db']->define_values ($f_replace_values);
						$f_return = $direct_classes['db']->query_exec ("co");

						if ($f_return)
						{
							if (function_exists ("direct_dbsync_event")) { direct_dbsync_event ($direct_settings['evars_archive_table'],"replace","<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['evars_archive_table'].".ddbevars_archive_id",$this->data_evars_id,"string"))."</sqlconditions>"); }
							if (!$direct_settings['swg_auto_maintenance']) { $direct_classes['db']->optimize_random ($direct_settings['evars_archive_table']); }
						}
					}
				}
			}

			if ($f_return) { $direct_classes['db']->v_transaction_commit (); }
			else { $direct_classes['db']->v_transaction_rollback (); }
		}
		else { $f_return = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter->update ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

define ("CLASS_direct_datacenter",true);

//j// Script specific commands

if (!isset ($direct_settings['datacenter_marker_use_imagebuttons'])) { $direct_settings['datacenter_marker_use_imagebuttons'] = false; }
if (!isset ($direct_settings['swg_auto_maintenance'])) { $direct_settings['swg_auto_maintenance'] = false; }
}

//j// EOF
?>