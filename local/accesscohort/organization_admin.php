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
 * @author  Prashant Yallatti<prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
require_once('../../config.php');
require_once('form/organization_admin_form.php');
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot .'/enrol/cohort/lib.php');
//require_once($CFG->dirroot.'/local/access_level_org_report/csslinks.php');
require_login(0,false);
$capadmin = is_siteadmin();
$context = context_system::instance();
//$createorgcap = has_capability('local/accesscohort:addorganization',$context);
$PAGE->set_context(context_system::instance());
$title = get_string('adduser', 'local_accesscohort');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin');
//forcapabilty aassiging here 
$systemcontext = context_system::instance();
//$myreport = has_capability('local/access_level_org_report:myreport',$systemcontext);
//$allreport = has_capability('local/access_level_org_report:allreport',$systemcontext);
$PAGE->set_url('/local/accesscohort/organization_admin.php');
require_login();
$PAGE->navbar->ignore_active();
$previewnode = $PAGE->navbar->add(get_string('pluginname','local_accesscohort'),new moodle_url($CFG->wwwroot.'/local/accesscohort/organization_admin.php'), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add($title, new moodle_url($CFG->wwwroot.'/local/accesscohort/organization_admin.php'));
$thingnode->make_active();
echo $OUTPUT->header();
$mform = new local_organization_admin_form();
if(is_siteadmin()){
	$mform->display();
}else{
	echo html_writer::div(
		get_string('cap', 'local_accesscohort'),'alert alert-danger'
		);
}
if ($mform->is_cancelled()){
	redirect(new moodle_url('/my', array()));
} else if ($data = $mform->get_data()) {
	$sql = $DB->record_exists('local_oragnization_admin',array('orgid'=>$data->orgid,'userid'=>$data->userid));
	if($sql){
		echo html_writer::div(
			get_string('org_record_exists', 'local_accesscohort'),'alert alert-danger'
			);

	}else{
		$insert = $DB->insert_record('local_oragnization_admin', $data);
		if($insert){
			redirect(new moodle_url($CFG->wwwroot.'/local/accesscohort/list_admin.php'),get_string('user','local_accesscohort'));
		}
	}
}
echo $OUTPUT->footer();