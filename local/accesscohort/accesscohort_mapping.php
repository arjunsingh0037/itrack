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
require_once('form/accesscohort_mapping_form.php');
require_once($CFG->libdir . '/formslib.php');
require_login(0,false);
$context = context_system::instance();
$createmapcap = has_capability('local/accesscohort:addmapping',$context);


$PAGE->set_context(context_system::instance());
$title = get_string('formtitle', 'local_accesscohort');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin');
$PAGE->set_url('/local/accesscohort/accesscohort_mapping.php');
require_login();
$previewnode = $PAGE->navigation->add(get_string('pluginname','local_accesscohort'), new moodle_url($CFG->wwwroot.'/local/accesscohort/index.php'), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add($title, new moodle_url($CFG->wwwroot.'/local/accesscohort/accesscohort_mapping.php'));
$thingnode->make_active();
echo $OUTPUT->header();
//fordisplay user institutation field is display here 
$mform = new local_accesscohort_mapping_form();
$orgname = $DB->get_record('user',array('id'=>$USER->id),'institution');
if($orgname){
	$org_name = $DB->get_record('local_organization',array('short_name'=>$orgname->institution));
	if($org_name){
		$general = $DB->get_record('local_mapping_cohort', array('org_id' =>$org_name->id));if($general){
				$mform->set_data($general);
			}
		}
	}
	$temp = false;
	$flag = 0;
	if ($mform->is_cancelled()){
		redirect(new moodle_url('/local/accesscohort/list_mapping.php', array()));
	} else if ($data = $mform->get_data()) {
		if($data){
		//check data is already exist in table or not
		global $DB, $USER;
		$result = $DB->record_exists('local_mapping_cohort',array('org_id'=>$data->org_id));if(!$result){
			$cid = implode(',',$data->cohort_id);
			$insert = new stdClass();
			$insert->org_id = $data->org_id;
			$insert->cohort_id = $cid;
			$insertvalue = $DB->insert_record('local_mapping_cohort', $insert);
			if($insertvalue){
				$flag =1;
			}
		} else {
			$existing = $DB->get_record('local_mapping_cohort',array('org_id'=>$data->org_id));
			$cid = implode(',',$data->cohort_id);
			$update = new stdClass();
			$update->id = $existing->id;
			$update->cohort_id = $existing->cohort_id.','.$cid;
			$updatevalue = $DB->update_record('local_mapping_cohort', $update);
			if($updatevalue){
				$flag =1;
			}
		}
	}
	if($flag==1){
		redirect(new moodle_url($CFG->wwwroot.'/local/accesscohort/list_mapping.php'),get_string('mapc','local_accesscohort'));
	}
	if($flag == 0){
		echo html_writer::div(
			get_string('record_exists', 'local_accesscohort'),'alert alert-danger'
			);
	}
}
if(is_siteadmin()){
$mform->display();
}
else{
	echo html_writer::div(
		get_string('cap', 'local_accesscohort'),'alert alert-danger'
		);
}  
echo $OUTPUT->footer();
?>






