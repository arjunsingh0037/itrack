<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class assign_labs_form extends moodleform {

    function definition() {
        global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
        //$mform->addElement('header','filehdr', 'Add Students to Batch');
        $courses = enrol_get_users_courses($USER->id,true);
        $academic_courses = array();
        $batches = array();
        foreach ($courses as $ck => $cv) {
            $course_type = $DB->get_record('course_type',array('courseid'=>$ck),'subscribed');
            if($course_type->subscribed == 0){
                $academic_courses[$ck] = $cv->shortname; 
            }
        }
        $mform->addElement('select', 'ac_course','Academic Course', $academic_courses,array('id'=>'id_courses'),'Academic Course'); 
        $mform->addRule('ac_course', get_string('required'), 'required', null, 'client');
        
        $mform->addElement('select', 'batchid','Assign to', $batches,array('id'=>'enrol_batch'));
        
        $mform->addElement('html','<hr>');
        $mform->addElement('html','<div id="student_enrol">');
        $mform->addElement('html','</div>');

        $mform->addElement('submit', 'btnsave', 'Submit');
    }

    function validation($data, $files) {
        
    }   
}
class industry_labs_form extends moodleform {

    function definition() {
        global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
        //$mform->addElement('header','filehdr', 'Add Students to Batch');
        $batches = array();
        $courses = enrol_get_users_courses($USER->id,true);
        $subscribed_courses = array();
        $batches = array();
        foreach ($courses as $ck => $cv) {
            $course_type = $DB->get_record('course_type',array('courseid'=>$ck),'subscribed');
            if($course_type->subscribed == 1){
                $subscribed_courses[$ck] = $cv->shortname; 
            }
        }
        $mform->addElement('select', 'ac_course','Subscribed Industry Course', $subscribed_courses,array('id'=>'id_courses_sub'),'Academic Course'); 
        $mform->addRule('ac_course', get_string('required'), 'required', null, 'client');
        
        $mform->addElement('select', 'batchid','Assign to', $batches,array('id'=>'enrol_batch_sub'));
        
        $mform->addElement('html','<hr>');
        $mform->addElement('html','<div id="student_enrol2">');
        $mform->addElement('html','</div>');
        $mform->addElement('html','<hr>');

        $mform->addElement('submit', 'btnsave', 'Submit');

    }

    function validation($data, $files) {
        
    }   
}
?>

