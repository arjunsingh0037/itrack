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
require_once('form/enrolment_prasent_form.php');
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot .'/enrol/cohort/lib.php');
//require_once($CFG->dirroot.'/local/access_level_org_report/csslinks.php');
require_login(0,false);
$capadmin = is_siteadmin();
$context = context_system::instance();
//$createorgcap = has_capability('local/accesscohort:addorganization',$context);
$PAGE->set_context(context_system::instance());
$title = get_string('enrolment', 'local_accesscohort');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin');
//forcapabilty aassiging here 
$systemcontext = context_system::instance();
//$myreport = has_capability('local/access_level_org_report:myreport',$systemcontext);
//$allreport = has_capability('local/access_level_org_report:allreport',$systemcontext);
$PAGE->set_url('/local/accesscohort/enrolment_prasent.php');
$PAGE->requires->jquery();		
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/accesscohort/js/select.js'));
require_login();
$PAGE->navbar->ignore_active();
$previewnode = $PAGE->navbar->add(get_string('pluginname','local_accesscohort'),new moodle_url($CFG->wwwroot.'/local/accesscohort/accesscohort_enrol.php'), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add($title, new moodle_url($CFG->wwwroot.'/local/accesscohort/accesscohort_enrol.php'));
$thingnode->make_active();
echo $OUTPUT->header();
$mform = new local_enrolment_prasent_form(null,array('csdata'=>$_POST));
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
	//fisrt need to insert data into enrol table
	/**
	* @param int courseid,$cohortid,$roleid 
	* @param  string $cohortname,
	* @param type string $enrol,
	**/
	//in single selection courseid and cohortid
	$table = new html_table();
	$table->head = (array) get_strings(array('coname', 'cname','enrolm'), 'local_accesscohort');
		$record = $DB->get_records('enrol', array('enrol'=>'cohort','customint1'=>$data->cohort_id));
		//print_object($record);
		$cohort = cohort($data->cohort_id);
		//print_object($cohort);
		echo '<br><br>';
		echo '<h4>Selected Cohort is : <b>'.$cohort->name.'</b> </h4><br>';
		echo html_writer::link(
        new moodle_url(
            $CFG->wwwroot.'/local/accesscohort/accesscohort_enrol.php',
            array(
                        'id' => $data->cohort_id
                        )
            ),
        get_string('adden','local_accesscohort'),
        array(
            'class' => 'btn btn-primary'
            )
        );
    echo '<br><br>';
		foreach ($record as $key => $value) {
			$coursename  = $DB->get_record('course',array('id'=>$value->courseid));
			//print_object($coursename);
			$table->data[] = array(
				$value->name,
				$coursename->fullname,
				$value->enrol);
		}
		if($table){
			echo html_writer::table($table);
		}else{
			echo html_writer::div(
				get_string('no', 'local_accesscohort'),'alert alert-danger'
				);
		}
	
}
echo $OUTPUT->footer();