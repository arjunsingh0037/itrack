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
 * @package    local_accesscohort
 * @copyright  Prashant Yallatti<prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
require('../../config.php');
require_login(0 , FALSE);
$id = required_param('id', PARAM_INT);
$context = context_system::instance();
$createorgcap = has_capability('local/accesscohort:addorganization',$context);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_accesscohort'));
$PAGE->set_heading(get_string('formtitle', 'local_accesscohort'));
$PAGE->set_url('/local/accesscohort/delete_org_admin.php');
echo $OUTPUT->header();
if($createorgcap){
	$delete = $DB->delete_records('local_oragnization_admin', array('id' => $id));
	if($delete) {
		redirect(new moodle_url($CFG->wwwroot.'/local/accesscohort/list_admin.php'),'Delete');
	} else {
		echo "error";
	}
}else{
	echo html_writer::div(
		get_string('cap', 'local_accesscohort'),'alert alert-danger'
		);
}
echo $OUTPUT->footer();
