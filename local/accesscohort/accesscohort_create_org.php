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
require_once('form/accesscohort_create_org_form.php');
require_once($CFG->libdir . '/formslib.php');
require_login(0,false);
$capadmin = is_siteadmin();
$context = context_system::instance();
$createorgcap = has_capability('local/accesscohort:addorganization',$context);
$PAGE->set_context(context_system::instance());
$title = get_string('org_name1', 'local_accesscohort');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin');
$PAGE->set_url('/local/accesscohort/accesscohort_create_org.php');
require_login();
$previewnode = $PAGE->navigation->add(get_string('pluginname','local_accesscohort'), new moodle_url($CFG->wwwroot.'/local/accesscohort/index.php'), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add($title, new moodle_url($CFG->wwwroot.'/local/accesscohort/accesscohort_create_org.php'));
$thingnode->make_active();
echo $OUTPUT->header();
if(is_siteadmin())  {
	$mform = new local_accesscohort_create_org_form();
	$temp = false;
	if ($mform->is_cancelled()){
		redirect(new moodle_url('/local/accesscohort/list_org.php', array()));

	} else if ($data = $mform->get_data()) {
		if($data){
		//check data is already exist in table or not
			$sql = $DB->record_exists('local_organization',array('org_name'=>$data->org_name));
			if($sql){
				echo html_writer::div(
					get_string('record_exists', 'local_accesscohort'),'alert alert-danger'
					);
		//if record is not exist then insert into database.
			}else{
				$insert = $DB->insert_record('local_organization', $data);
				if($insert){

					redirect(new moodle_url($CFG->wwwroot.'/local/accesscohort/list_org.php'),get_string('orgins','local_accesscohort'));
				}
			}
		}
	}
	$mform->display();
} else{
	echo html_writer::div(
		get_string('cap', 'local_accesscohort'),'alert alert-danger'
		);
}
echo $OUTPUT->footer();
?>






