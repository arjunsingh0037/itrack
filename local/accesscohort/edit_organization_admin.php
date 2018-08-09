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
require_once('../../config.php');
require_once('form/organization_admin_form.php');
require_once($CFG->libdir .'/formslib.php');
require_login(0,false);

$id = required_param('id',PARAM_INT);
$edit = optional_param('edit',null,PARAM_INT);
$general = $DB->get_record('local_oragnization_admin', array('id' => $id)) ;
$context = context_system::instance();
$createorgcap = has_capability('local/accesscohort:addorganization',$context);

$PAGE->set_context(context_system::instance());
$title = get_string('edit_org_admin', 'local_accesscohort');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin');
$PAGE->set_url('/local/accesscohort/edit_organization_admin.php');
require_login();
$previewnode = $PAGE->navigation->add(get_string('pluginname','local_accesscohort'), new moodle_url($CFG->wwwroot.'/local/accesscohort/index.php'), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add($title, new moodle_url($CFG->wwwroot.'/local/accesscohort/edit_organization_admin.php'));
$thingnode->make_active();

echo $OUTPUT->header();
if($createorgcap){
	$mform = new local_organization_admin_form(new moodle_url($CFG->wwwroot .'/local/accesscohort/edit_organization_admin.php',array('id'=>$id)));
	if(!is_siteadmin()){
	}	
	$mform->set_data($general);
	if ($mform->is_cancelled()) {
		redirect(new moodle_url('/local/accesscohort/list_admin.php', array()));
	}else if($data = $mform->get_data()) {
		$editcontent = new stdClass();
		$editcontent->id = $general->id;
		$editcontent->orgid = $data->orgid;
		$editcontent->userid =$data->userid;
		$generalupdate = $DB->update_record('local_oragnization_admin', $editcontent);
		if($generalupdate){
			redirect(new moodle_url($CFG->wwwroot.'/local/accesscohort/list_admin.php'),get_string('uporg_admin','local_accesscohort'));
		}
	}
	$mform->display();
} else{
	echo html_writer::div(
		get_string('cap', 'local_accesscohort'),'alert alert-danger'
		);
}
echo $OUTPUT->footer();
