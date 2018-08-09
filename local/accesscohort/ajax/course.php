	<?php
	// This file is part of the Certificate module for Moodle - http://moodle.org/
	//
	// Moodle is free software: you can redistribute it and/or modify
	// it under the terms of the GNU General Public License as published by
	// the Free Software Foundation, either version 3 of the License, or
	// (at your option) any later version.
	//
	// Moodle is distributed in the hope that it will be useful,
	// but WITHOUT ANY WARRANTY; without even the implied warranty of
	// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	// GNU General Public License for more details.
	//
	// You should have received a copy of the GNU General Public License
	// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

	/**
	 * Handles uploading files
	 *
	 * @package    local_access_level_org_report
	 * @author  Prashant Yallatti<prashant@elearn10.com>
	 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
	 * @license    http://www.lmsofindia.com 2017 or later
	 */
	define('AJAX_SCRIPT', true);
	global $DB;
	include '../../../config.php';
	require_login(0,false);
	$PAGE->set_context(context_system::instance());
	/**
	* in this function will take int $id and $orgid.
	* int $cohortid return enrol table array of object
	* int $orgid return in  local mapping int cohortids
	* after cliking cohort name in which course  cohort enrol method added is listed below in     * below select box
	*/
	$id = required_param('id', PARAM_RAW);
	$orgid = optional_param('orgid', '',PARAM_RAW);

	$chids = explode(',',$id);
	//$courseids = array();
	if($id !='select-all'){
		foreach($chids as $chid){
			$sql = "SELECT courseid from {enrol} where enrol = 'cohort' and customint1 in ($id)";
			$courseids = $DB->get_records_sql($sql);
			$cids = [];
			if($courseids){
				foreach ($courseids as $key => $course) {
					$cids[$key] = $DB->get_record('course',array('id'=>$course->courseid),'id,fullname');
				}
			}
			
		}
	}else{
		$cohortvalues = $DB->get_records('local_mapping_cohort',array('org_id'=>$orgid),'cohort_id');
		foreach ($cohortvalues as $key => $value) {
			$cid = $value->cohort_id;
		}
		$sql = "SELECT courseid from {enrol} where enrol = 'cohort' and customint1 in ($cid)";
		$courseids = $DB->get_records_sql($sql);
		$cids = [];
			if($courseids){
				foreach ($courseids as $key => $course) {
					$cids[$key] = $DB->get_record('course',array('id'=>$course->courseid),'id,fullname');
				}
			}
	}
	$coursevalue =$DB->get_records('course',array(),NULL,'id,fullname'); 
	 unset($coursevalue[1]);
	$diff = array_diff_key($coursevalue,$cids);
	echo json_encode($diff);