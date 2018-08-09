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
$id = required_param('id',PARAM_INT);
$edit = optional_param('edit',null,PARAM_INT);
$general = $DB->get_record('local_mapping_cohort', array('id' => $id));
//print_object($general);die();
$std = new stdClass();
$std->org_id =$general->org_id;
$std->cohort_id =$general->cohort_id;
$context = context_system::instance();
$createmapcap = has_capability('local/accesscohort:addmapping',$context);
$PAGE->set_context(context_system::instance());
$title = get_string('edit_map', 'local_accesscohort');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin');
$PAGE->set_url('/local/accesscohort/edit_accesscohort_map.php');
require_login();
$previewnode = $PAGE->navigation->add(get_string('pluginname','local_accesscohort'), new moodle_url($CFG->wwwroot.'/local/accesscohort/index.php'), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add($title, new moodle_url($CFG->wwwroot.'/local/accesscohort/edit_accesscohort_map.php'));
$thingnode->make_active();
$PAGE->requires->css('/styles.css');
echo $OUTPUT->header(); 
//display selected message in edit form 
$org_name = $DB->get_record('local_organization',array('id'=>$general->org_id));
//print_object($org_name);
$sql2 = $DB->get_records_sql("SELECT name from {cohort} where id in ($general->cohort_id)");

foreach($sql2 as $v){
	$cname[] =$v->name;
} 

$cn = implode(',',$cname); 
$rtrim = rtrim($cn,','); 
if(!is_siteadmin() && $createmapcap){
echo '<div class="col-md-12" id ="accesscohort-head">
<div id ="accesscohort" class="col-md-12">
	<h5>Organization  short name is  : '.$org_name->org_name.'</h5><br>
	<h5>Selected cohort name is : '.$rtrim.'</h5> 
</div> 	
</div>';
}

if($createmapcap){
	$mform = new local_accesscohort_mapping_form(new moodle_url($CFG->wwwroot .'/local/accesscohort/edit_accesscohort_map.php',array('id'=>$id)));
	$mform->set_data($general);
	if ($mform->is_cancelled()) {
		redirect(new moodle_url('/local/accesscohort/list_mapping.php', array()));
	}else if($data = $mform->get_data()) {
		$flag = 0;
		$cid = implode(',',$data->cohort_id);	
		$editcontent = new stdClass();
		$editcontent->id = $general->id;
		$editcontent->org_id = $general->org_id;
		$editcontent->cohort_id = $cid;
		$sql1 = "SELECT id from {local_mapping_cohort} where 'org_id' = $general->org_id and 'cohort_id' in ($cid)";
		$result = $DB->get_records_sql($sql1);
		if(!$result){
			$generalupdate = $DB->update_record('local_mapping_cohort', $editcontent);
			if($generalupdate){
				redirect(new moodle_url($CFG->wwwroot.'/local/accesscohort/list_mapping.php'),'Updated Record Successfully!!');
			}
		}else{
			$flag = 1;
		}

		if($flag==1){
			echo html_writer::div(
				get_string('record_exists', 'local_accesscohort'),'alert alert-danger'
				);
		}
	}
	$mform->display();
}else{
	echo html_writer::div(
		get_string('cap', 'local_accesscohort'),'alert alert-danger'
		);
}

echo $OUTPUT->footer();
