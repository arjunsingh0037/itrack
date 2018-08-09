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
require_once('form/accesscohort_enrol_form.php');
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot .'/enrol/cohort/lib.php');
//require_once($CFG->dirroot.'/local/access_level_org_report/csslinks.php');
require_login(0,false);
$id = required_param('id',PARAM_INT);
$capadmin = is_siteadmin();
$context = context_system::instance();
//$createorgcap = has_capability('local/accesscohort:addorganization',$context);
$PAGE->set_context(context_system::instance());
$title = get_string('enroladd', 'local_accesscohort');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin');
//forcapabilty aassiging here 
$systemcontext = context_system::instance();
//$myreport = has_capability('local/access_level_org_report:myreport',$systemcontext);
//$allreport = has_capability('local/access_level_org_report:allreport',$systemcontext);
$PAGE->set_url('/local/accesscohort/accesscohort_enrol.php');
$PAGE->requires->jquery();		
//$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/accesscohort/js/select.js'));
require_login();
$PAGE->navbar->ignore_active();
$previewnode = $PAGE->navbar->add(get_string('pluginname','local_accesscohort'),new moodle_url($CFG->wwwroot.'/local/accesscohort/accesscohort_enrol.php'), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add($title, new moodle_url($CFG->wwwroot.'/local/accesscohort/accesscohort_enrol.php'));
$thingnode->make_active();
echo $OUTPUT->header();
$mform = new local_accesscohort_enrol_form(new moodle_url($CFG->wwwroot.'/local/accesscohort/accesscohort_enrol.php',array('id'=>$id)),array('csdata'=>$_POST));
if(is_siteadmin()){$mform->display();}else{
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
	if(isset($data->courseid) && !empty($data->courseid)){
		foreach ($data->courseid as $key => $cid) {
			//condition write here 
			$flag = 0;
			if(!empty($data->cohort_id) && $cid == 'select-all'){
				$diff = enrol_diff_values($data->cohort_id);
				if($diff){
					foreach ($diff as $cid) {
						$record = $DB->record_exists('enrol', array('courseid'=>$cid->id, 'enrol'=>'cohort','customint1'=>$data->cohort_id));
						if(!$record){
							$cohort = cohort($data->cohort_id);
							$fields = array(
								'customint1'=>$data->cohort_id,
								'roleid'=>5,
								'name'=>$cohort->name
								);
							$cohort = enrol_get_plugin('cohort');
							$course = $DB->get_record('course',array('id'=>$cid->id));
							if($course){
								$instance = $cohort->add_instance($course,$fields);
								if($instance){
									$enrol = enrol_process($cid->id,$data->cohort_id);
									if($enrol){
										$flag = 1;
									}
									/*echo html_writer::div(
										get_string('enrol', 'local_accesscohort'),'alert alert-success'
										);*/
									
								}
							}
						}
					}
					if($flag == 1){
						echo html_writer::div(
										get_string('enrol', 'local_accesscohort'),'alert alert-success'
										);
						redirect(new moodle_url('/my', array()));
					}
				}
			}
			else{
			//here check condition weather data is present or not then procced to next step
				$record = $DB->record_exists('enrol', array('courseid'=>$cid, 'enrol'=>'cohort','customint1'=>$data->cohort_id));
				if(!$record){
			//add instances
					$cohort = $DB->get_record('cohort',array('id'=>$data->cohort_id));
					$fields = array(
						'customint1'=>$data->cohort_id,
						'roleid'=>5,
						'name'=>$cohort->name
						);
					$cohort = enrol_get_plugin('cohort');
					$course = $DB->get_record('course',array('id'=>$cid));
					if($course){
						$instance = $cohort->add_instance($course,$fields);
						if($instance){
							$enrol = enrol_process($cid,$data->cohort_id);
							//print_object($enrol);
							echo html_writer::div(
								get_string('enrol', 'local_accesscohort'),'alert alert-success'
								);
							redirect(new moodle_url('/my', array()));
						}
					}
				}
			}//end of else part here 
		}
	}
}
echo $OUTPUT->footer();