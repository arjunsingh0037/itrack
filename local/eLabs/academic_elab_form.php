<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class academic_elabs_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB,$USER;
		$mform = & $this->_form;
		$programs = array();
		$courses = enrol_get_users_courses($USER->id,true);
		$academic_courses = array();
		foreach ($courses as $ck => $cv) {
			$course_type = $DB->get_record('course_type',array('courseid'=>$ck),'subscribed');
			if($course_type->subscribed == 0){
				$academic_courses[$ck] = $cv->shortname; 
			}
		}
		$mform->addElement('select', 'ac_course','Academic Course', $academic_courses,array('id'=>'id_courses'),'Academic Course'); 
		$mform->addRule('ac_course', get_string('required'), 'required', null, 'client');
		
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'testlab', '', 'Yes', 1, array());
        $radioarray[] = $mform->createElement('radio', 'testlab', '', 'No', 0, array());
        $mform->addGroup($radioarray, 'radioar', 'Test Lab', array(' '), false);
        $mform->addRule('radioar', get_string('required'), 'required', null, 'client');

        $mform->addElement('date_time_selector', 'availfrom', 'Available From');
        $mform->addElement('date_time_selector', 'availupto', 'Available Upto');

        $radioarray1=array();
        $radioarray1[] = $mform->createElement('radio', 'level', '', 'Begineers', 1, array());
        $radioarray1[] = $mform->createElement('radio', 'level', '', 'Intermediate', 0, array());
        $mform->addGroup($radioarray1, 'radioar1', 'Competency Level', array(' '), false);
	    
        $radioarray2=array();
        $radioarray2[] = $mform->createElement('radio', 'type', '', 'C', 'c', array());
        $radioarray2[] = $mform->createElement('radio', 'type', '', 'C++', 'cpp', array());
        $radioarray2[] = $mform->createElement('radio', 'type', '', 'Java', 'java', array());
        $radioarray2[] = $mform->createElement('radio', 'type', '', '.Net', 'net', array());
        $radioarray2[] = $mform->createElement('radio', 'type', '', 'Python', 'python', array());
        $mform->addGroup($radioarray2, 'radioar2', 'Programming Language', array(' '), true);

        $mform->addElement('submit', 'btnsave', 'Submit');
	}

	function validation($data, $files) {
		
	}	
}
?>

