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

$g_continue_check = ((defined ("CLASS_direct_datacenter_home")) ? false : true);
if (!defined ("CLASS_direct_datalinker_uhome")) { $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datalinker_uhome.php"); }
if (!defined ("CLASS_direct_datalinker_uhome")) { $g_continue_check = false; }

if ($g_continue_check)
{
//c// direct_datacenter_home
/**
* This is the virtual home implementation for DataCenter specific functions.
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
class direct_datacenter_home extends direct_datalinker_uhome
{
/**
	* @var array $class_subs Cached subobjects
*/
	protected $class_subs;
/**
	* @var boolean $data_writable_as_submission True if the current user is
	*      allowed to submit new documents in this category
*/
	protected $data_deleted;
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

/* -------------------------------------------------------------------------
Extend the class
------------------------------------------------------------------------- */

	//f// direct_datacenter_home->__construct
/**
	* Constructor (PHP5) __construct (direct_datacenter_home)
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->__construct (direct_datacenter_home)- (#echo(__LINE__)#)"); }

		if (!defined ("CLASS_direct_formtags")) { $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/swg_formtags.php"); }
		if (!isset ($direct_classes['formtags'])) { direct_class_init ("formtags"); }

		if (!defined ("CLASS_direct_datacenter")) { $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter.php"); }
		direct_local_integration ("datacenter");

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
		$this->functions['get_objects'] = defined ("CLASS_direct_datacenter");
		$this->functions['get_objects_since_date'] = $this->functions['get_objects'];
		$this->functions['get_physical_objects'] = false;
		$this->functions['get_sorted_physical_objects'] = false;
		$this->functions['get_plocation'] = true;
		$this->functions['get_upload_folder'] = function_exists ("direct_dir_create");
		$this->functions['is_deleted'] = true;
		$this->functions['is_directory'] = true;
		$this->functions['is_locked'] = true;
		$this->functions['is_physical'] = false;
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
		$this->data_marked = false;
		$this->data_markers = array ();
		$this->data_pid = "";
		$this->data_sid = "d4d66a02daefdb2f70ff2507a78fd5ec";
		$this->data_writable = false;
		$this->marker_connector = "";
		$this->marker_connector_type = "url0";
		$this->marker_title_mark = direct_local_get ("datacenter_object_mark");
		$this->marker_title_unmark = direct_local_get ("datacenter_object_unmark");
		$this->marker_type = 6;

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

	//f// direct_datacenter_home->add_objects ($f_count,$f_update = true)
/**
	* Increases the object counter.
	*
	* @param  number $f_count Number to be added to the object counter
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function add_objects ($f_count,$f_update = true)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->add_objects ($f_count,+f_update)- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->add_objects ()- (#echo(__LINE__)#)",:#*/true/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->check ($f_path,$f_data = NULL)
/**
	* Check if a file is accepted for the given mimetype.
	*
	* @param  array $f_data List of available filters
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True if valid
	* @since  v0.1.00
*/
	public function check ($f_path,$f_data = NULL)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->check ($f_path,+f_data)- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->check ()- (#echo(__LINE__)#)",:#*/true/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->define_lock ($f_state = NULL,$f_update = false)
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->define_lock (+f_state,+f_update)- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->define_lock ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->define_marker_connector ($f_connector,$f_connector_type = "url0")
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->define_marker_connector ($f_connector,$f_connector_type)- (#echo(__LINE__)#)"); }

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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->define_marker_titles ($f_title_mark,$f_title_unmark)- (#echo(__LINE__)#)"); }

		if (strlen ($f_title_mark)) { $this->marker_title_mark = $f_title_mark; }
		if (strlen ($f_title_unmark)) { $this->marker_title_unmark = $f_title_unmark; }
	}

	//f// direct_datacenter_home->define_marker_type ($f_type)
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->define_marker_type ($f_type)- (#echo(__LINE__)#)"); }
	
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

	//f// direct_datacenter_home->define_markers ($f_markers)
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->define_markers (+f_markers)- (#echo(__LINE__)#)"); }

		if (is_array ($f_markers))
		{
			$this->data_markers = $f_markers;
			if ((is_array ($this->data))&&(!empty ($this->data))) { $this->data_marked = in_array ($this->data['ddbdatalinker_id'],$f_markers); }
		}
	}

	//f// direct_datacenter_home->define_readable ($f_state = NULL)
/**
	* Sets the reading right state of this object.
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->define_readable (+f_state)- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->define_readable ()- (#echo(__LINE__)#)",:#*/true/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->define_trusted ($f_state = NULL)
/**
	* Sets the trusted state of this object.
	*
	* @param  mixed $f_state Boolean indicating the state or NULL to switch
	*         automatically
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	public function define_trusted ($f_state = NULL)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->define_trusted (+f_state)- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->define_trusted ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->define_writable ($f_state = "")
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->define_writable (+f_state)- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->define_writable ()- (#echo(__LINE__)#)",:#*/$this->data_writable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->delete ()
/**
	* Mark the object as deleted and remove the file.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean Always false (you can't delete home - it's virtual)
	* @since  v0.1.00
*/
	public function delete ()
	{
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -datacenter_home->delete ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->delete ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->get ($f_uid = "",$f_load = true)
/**
	* Returns a virtual user's home dataset.
	*
	* @param  string $f_uid DataLinker (user) ID
	* @param  boolean $f_load Load DataLinker data from the database
	* @uses   direct_datalinker_uhome::get()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return array Found result or empty array
	* @since  v0.1.00
*/
	public function get ($f_uid = "",$f_load = true)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -datalinker_home->get ($f_uid,+f_load)- (#echo(__LINE__)#)"); }

		$f_return = false;

		if (count ($this->data) > 1) { $f_return = $this->data; }
		elseif (strlen ($f_uid))
		{
			if ($f_load)
			{
				if (preg_match ("#^(.+?):(.*?)$#",$f_uid,$f_result_array)) { $f_uid = $f_result_array[1]; }
				if (strpos ($f_uid,"-") !== false) { $f_uid = substr ($f_uid,2); }

				if (parent::get ($f_uid))
				{
					$f_user_array = $direct_classes['kernel']->v_user_get ($f_uid,"",true);

					$this->data['ddbdatalinker_sid'] = $this->data_sid;
					$this->data['ddbdatalinker_type'] = 1;
					$this->data['ddbdatalinker_position'] = 100;
					$this->data['ddbdatalinker_objects'] = $this->get_objects_since_date (-1,0,0,1,"title-sticky-asc",true);
					$this->data['ddbdatalinker_title'] = ((direct_local_get ("datacenter_home_1","text")).$f_user_array['ddbusers_name'].(direct_local_get ("datacenter_home_2","text")));

					$this->data['ddbdatacenter_id'] = "u-".$f_uid;
					$this->data['ddbdatacenter_owner_id'] = $f_uid;
					$this->data['ddbdatacenter_owner_ip'] = "";
					$this->data['ddbdatacenter_last_id'] = $f_uid;
					$this->data['ddbdatacenter_last_ip'] = "";
					$this->data['ddbdatacenter_size'] = 0;
					$this->data['ddbdatacenter_type'] = "text/x-directory-home";
					$this->data['ddbdatacenter_desc'] = "";
					$this->data['ddbdatacenter_plocation'] = "";
					$this->data['ddbdatacenter_mode_owner'] = "w";
					$this->data['ddbdatacenter_mode_last'] = "w";
					$this->data['ddbdatacenter_mode_all'] = "r";
					$this->data['ddbdatacenter_trusted'] = 0;
					$this->data['ddbdatacenter_deleted'] = $f_user_array['ddbusers_deleted'];
					$this->data['ddbdatacenter_locked'] = 0;

					$this->data_marked = in_array ("u-".$f_uid,$this->data_markers);

					if ($f_user_array['ddbusers_deleted'])
					{
						$this->data_deleted = true;
						$this->data_writable = (($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) ? true : false);
					}
					else
					{
						$this->data_deleted = false;

						if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) { $this->data_writable = true; }
						elseif (($direct_settings['user']['type'] != "gt")&&($f_uid == $direct_settings['user']['id'])) { $this->data_writable = true; }
						else { $this->data_writable = false; }
					}

					$f_return = $this->data;
				}
			}
			else { $f_return = parent::get ($f_uid,false); }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->get ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->get_evars ()
/**
	* Reads evars from the archive for this object.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return mixed Array with key -> value pairs or false on error
	* @since  v0.1.00
*/
	public function get_evars ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->get_evars ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->get_evars ()- (#echo(__LINE__)#)",(:#*/array ()/*#ifdef(DEBUG):),true):#*/;
	}

	//f// direct_datacenter_home->get_plocation ($f_path = "")
/**
	* Get the location of a file using the given base path.
	*
	* @param  string $f_path Base directory path
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return string Path or an empty string
	* @since  v0.1.00
*/
	public function get_plocation ($f_path = "")
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->get_plocation ($f_path)- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->get_plocation ()- (#echo(__LINE__)#)",:#*/""/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->get_objects ($f_object_status,$f_offset = 0,$f_perpage = "",$f_sorting_mode = "title-sticky-asc")
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->get_objects ($f_object_status,$f_offset,$f_perpage,$f_sorting_mode,+f_frontpage_mode)- (#echo(__LINE__)#)"); }

		$f_return = array ();
		$f_cache_signature = md5 ($this->data['ddbdatalinker_id_object'].$f_object_status.$f_offset.$f_perpage.$f_sorting_mode);

		if (isset ($this->class_subs[$f_cache_signature])) { $f_return =& $this->class_subs[$f_cache_signature]; }
		elseif (isset ($this->data['ddbdatalinker_id_object']))
		{
			$this->define_extra_attributes (array ($direct_settings['datacenter_table'].".*",$direct_settings['users_table'].".ddbusers_type",$direct_settings['users_table'].".ddbusers_banned",$direct_settings['users_table'].".ddbusers_deleted",$direct_settings['users_table'].".ddbusers_name",$direct_settings['users_table'].".ddbusers_title",$direct_settings['users_table'].".ddbusers_avatar",$direct_settings['users_table'].".ddbusers_signature",$direct_settings['users_table'].".ddbusers_rating"));

$f_select_joins = array (
 array ("type" => "left-outer-join","table" => $direct_settings['datacenter_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['datacenter_table']}.ddbdatacenter_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>"),
 array ("type" => "left-outer-join","table" => $direct_settings['users_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['users_table']}.ddbusers_id' value='{$direct_settings['datacenter_table']}.ddbdatacenter_last_id' type='attribute' /></sqlconditions>")
);

			$this->define_extra_joins ($f_select_joins);
			if ($f_object_status == 1) { $this->define_extra_conditions ("<element1 attribute='{$direct_settings['datacenter_table']}.ddbdatacenter_deleted' value='0' type='number' />"); }

$f_init_array = array (
"data_markers" => $this->data_markers,
"marker_connector" => $this->marker_connector,
"marker_connector_type" => $this->marker_connector_type,
"marker_title_mark" => $this->marker_title_mark,
"marker_title_unmark" => $this->marker_title_unmark,
"marker_type" => $this->marker_type
);

			$this->class_subs[$f_cache_signature] = parent::get_subs ("direct_datacenter",NULL,$this->data['ddbdatalinker_id_object'],"d4d66a02daefdb2f70ff2507a78fd5ec","",$f_offset,$f_perpage,$f_sorting_mode,$f_init_array);
			// md5 ("datacenter")

			$f_return =& $this->class_subs[$f_cache_signature];
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->get_objects ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->get_objects_since_date ($f_object_status,$f_date,$f_offset = 0,$f_perpage = "",$f_sorting_mode = "title-sticky-asc",$f_count_only = false)
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->get_objects_since_date ($f_object_status,$f_date,$f_offset,$f_perpage,$f_sorting_mode,+f_count_only)- (#echo(__LINE__)#)"); }

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
		elseif (($f_count_only)&&($f_object_status == 1)&&($f_date == 0)) { $f_return = $this->data['ddbdatalinker_objects']; }
		elseif (isset ($this->data['ddbdatalinker_id_object']))
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
$f_init_array = array (
"data_markers" => $this->data_markers,
"marker_connector" => $this->marker_connector,
"marker_connector_type" => $this->marker_connector_type,
"marker_title_mark" => $this->marker_title_mark,
"marker_title_unmark" => $this->marker_title_unmark,
"marker_type" => $this->marker_type
);

				$this->class_subs[$f_cache_signature] = parent::get_subs ("direct_datacenter",NULL,$this->data['ddbdatalinker_id_object'],"d4d66a02daefdb2f70ff2507a78fd5ec","",$f_offset,$f_perpage,$f_sorting_mode,$f_init_array);
				// md5 ("datacenter")
			}

			$f_return =& $this->class_subs[$f_cache_signature];
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->get_objects_since_date ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->get_upload_folder ($f_path,$f_name_length = 2)
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->get_upload_folder ($f_path,$f_name_length)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if ($f_name_length)
		{
			$f_prefix = uniqid (uniqid (""));
			$f_prefix = substr ($f_prefix,0,$f_name_length);
			if (direct_dir_create ($f_path.$f_prefix)) { $f_return = $f_prefix; }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->get_upload_folder ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->is_deleted ()
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_deleted ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_deleted ()- (#echo(__LINE__)#)",:#*/$this->data_deleted/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->is_directory ()
/**
	* Returns true if the object is a directory.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_directory ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_directory ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_directory ()- (#echo(__LINE__)#)",:#*/true/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->is_locked ()
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_locked ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_locked ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->is_marked ()
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_marked ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_marked ()- (#echo(__LINE__)#)",:#*/$this->data_marked/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->is_readable ()
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_readable ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_readable ()- (#echo(__LINE__)#)",:#*/true/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->is_trusted ()
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_trusted ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_trusted ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->is_writable ()
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_writable ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->is_writable ()- (#echo(__LINE__)#)",:#*/$this->data_writable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->parse ($f_connector,$f_connector_type = "url0",$f_prefix = "")
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
	* @uses   direct_datalinker::parse()
	* @uses   direct_debug()
	* @uses   direct_kernel_system::v_user_parse()
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->parse ($f_connector,$f_connector_type,$f_prefix)- (#echo(__LINE__)#)"); }

		$f_return = parent::parse ($f_prefix);

		if (($f_return)&&(is_array ($this->data))&&(!empty ($this->data)))
		{
			$f_return[$f_prefix."id"] = "swgdhandlerdatacenter".$this->data['ddbdatalinker_id'];
			$f_return[$f_prefix."dir"] = true;
			$f_return[$f_prefix."type"] = "text/x-directory-home";

			if (($f_connector_type != "asis")&&(strpos ($f_connector,"javascript:") === 0)) { $f_connector_type = "asis"; }

			$f_pageurl = str_replace ("[a]","view",$f_connector);
			$f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",$this->data['ddbdatalinker_id'],$f_pageurl) : str_replace ("[oid]","doid+{$this->data['ddbdatalinker_id']}++",$f_pageurl));
			$f_pageurl = preg_replace ("#\[(.*?)\]#","",$f_pageurl);
			$f_return[$f_prefix."pageurl"] = direct_linker ($f_connector_type,$f_pageurl);

			$f_return[$f_prefix."pageurl_download"] = "";
			$f_return[$f_prefix."pageurl_parent"] = "";
			$f_return[$f_prefix."owner"] = $this->data['ddbdatacenter_last_id'];
			$f_return[$f_prefix."size"] = 0;
			$f_return[$f_prefix."icon"] = "";

			if ($direct_settings['datacenter_mimetype_iconize'])
			{
				$f_icon_path = $direct_classes['basic_functions']->varfilter ($direct_settings['datacenter_path_icons'],"settings");
				$f_icon = $direct_classes['basic_functions']->mimetype_icon ("text/x-directory-home");

				if ($f_icon)
				{
					if (file_exists ($f_icon_path.$f_icon)) { $f_return[$f_prefix."icon"] = direct_linker_dynamic ("url0","s=cache&dsd=dfile+".$f_icon_path.$f_icon,true,false); }
					elseif (file_exists ($f_icon_path.$direct_settings['datacenter_mimetype_unknown'])) { $f_return[$f_prefix."icon"] = direct_linker_dynamic ("url0","s=cache&dsd=dfile+".$f_icon_path.$direct_settings['datacenter_mimetype_unknown'],true,false); }
				}
				elseif (file_exists ($f_icon_path.$direct_settings['datacenter_mimetype_unknown'])) { $f_return[$f_prefix."icon"] = direct_linker_dynamic ("url0","s=cache&dsd=dfile+".$f_icon_path.$direct_settings['datacenter_mimetype_unknown'],true,false); }
			}

			$f_return[$f_prefix."desc"] = "";
			$f_return[$f_prefix."time"] = $direct_classes['basic_functions']->datetime ("longdate&time",$this->data['ddbdatalinker_sorting_date'],$direct_settings['user']['timezone'],(direct_local_get ("datetime_dtconnect")));

			$f_return[$f_prefix."marked"] = $this->data_marked;
			$f_return[$f_prefix."marker_title"] = "";
			$f_return[$f_prefix."pageurl_marker"] = "";

			switch ($this->marker_type)
			{
			case 2:
			case 5:
			{
				if ($this->data_writable) { $f_markable_check = true; }
				break 1;
			}
			case 3:
			case 4:
			{
				$f_markable_check = false;
				break 1;
			}
			default: { $f_markable_check = true; }
			}

			if (($this->marker_connector)&&($f_markable_check))
			{
				$f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",$this->data['ddbdatalinker_id'],$this->marker_connector) : str_replace ("[oid]","doid+{$this->data['ddbdatalinker_id']}++",$this->marker_connector));
				$f_pageurl = preg_replace ("#\[(.*?)\]#","",$f_pageurl);
				$f_return[$f_prefix."pageurl_marker"] = direct_linker ($this->marker_connector_type,$f_pageurl);

				$f_return[$f_prefix."marker_title"] = ($this->data_marked ? $this->marker_title_unmark : $this->marker_title_mark);
			}

			$f_return[$f_prefix."plocation"] = "";

			$f_user_parsed_array = $direct_classes['kernel']->v_user_parse ($this->data['ddbdatacenter_last_id'],$this->data,$f_prefix."user");
			$f_return = array_merge ($f_return,$f_user_parsed_array);

			$f_return[$f_prefix."userip"] = "";
			$f_return[$f_prefix."trusted"] = false;
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->parse ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->remove_objects ($f_count,$f_update = true)
/**
	* Decreases the objects counter.
	*
	* @param  number $f_count Number to be removed from the object counter
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function remove_objects ($f_count,$f_update = true)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->remove_objects ($f_count,+f_update)- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->remove_objects ()- (#echo(__LINE__)#)",:#*/true/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_datacenter_home->set_evars ($f_data)
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -datacenter_home->set_evars (+f_data)- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -datacenter_home->set_evars ()- (#echo(__LINE__)#)",:#*/""/*#ifdef(DEBUG):,true):#*/;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

define ("CLASS_direct_datacenter_home",true);
}

//j// EOF
?>