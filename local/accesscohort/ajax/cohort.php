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
 * @author  	Prashant Yallatti<prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
define('AJAX_SCRIPT', true);
global $DB;
include '../../../config.php';
require_login(0,false);
/** 
*@param  integer $id organization id passed into local_mapping_cohort,return all cohort ids 
* by taking cohortids returns array of cohort object;
*/
$PAGE->set_context(context_system::instance());
$id = required_param('id', PARAM_INT);
$sql = "SELECT cohort_id FROM {local_mapping_cohort} WHERE org_id = $id";
$result = $DB->get_record_sql($sql);
$result2 = [];
if($result){
	$cid = $result->cohort_id;
	$sql2 = "SELECT id,name FROM {cohort} WHERE id in ($cid)";
	$result2= $DB->get_records_sql($sql2);
}
echo json_encode($result2);