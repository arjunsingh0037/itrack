<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class academicbatch_toprofessor_form extends moodleform {

    function definition() {
        global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
        $mform->addElement('header','filehdr', 'Assign Course and Batch to Professor');
        
        $professor = array();
        $course = array();
        $batches = $DB->get_records('batches',array('creatorid'=>$USER->id,'batchtype'=>'ACAD'),'id,batchname');
        foreach ($batches as $b) {
                $batch[$b->id] = $b->batchname;
        }
        $mform->addElement('select', 'batchid','Batch', $batch,array('id'=>'enrol_batch'));
        
        $courses = $mform->addElement('select', 'courseids','Course', $course,array('id'=>'enrol_course'));
        $courses->setMultiple(true);
        
        $professors = $DB->get_records('tp_useruploads',array('creatorid'=>$USER->id,'roletype'=>'professor'),'userid');
        foreach ($professors as $pv) {
            $prof = $DB->get_record('user',array('id'=>$pv->userid),'firstname');
            $professor[$pv->userid] = $prof->firstname;
        }
        $mform->addElement('select', 'profid','Professor', $professor,array('id'=>'enrol_professor'));

        $mform->addElement('html','<hr>');

        $mform->addElement('submit', 'btnsave', 'Submit');
    }

    function validation($data, $files) {
        
    }   
}
class nonacademicbatch_toprofessor_form extends moodleform {

    function definition() {
        global $CFG,$COURSE,$DB;
        $mform = & $this->_form;
        $mform->addElement('header','filehdr', 'Assign Industry Partner Course to Batch');
        $batches = array();
        $programs = array();
        $streams = array();
        $syear = array();
        $semester = array();

        $mform->addElement('select', 'ac_program','Batch', $batches,array('id'=>'nab_batch'),'Select Program');
        $mform->addElement('select', 'ac_program','Program', $programs,array('id'=>'nab_program'),'Select Program');
        //$mform->addElement('select', 'program','Program',$programs ,array('id'=>'id_programs'));
        
        $mform->addElement('select', 'ac_stream','Stream', $streams,array('id'=>'nab_stream'));
        
        $mform->addElement('select', 'ac_semester_year','Semester Year', $syear,array('id'=>'nab_syears'));
        
        $mform->addElement('select', 'ac_semester','Semester', $semester,array('id'=>'nab_semesters'));
        
        $attributes=array('size'=>'20', 'id'=>'id_btype');
        $mform->addElement('hidden', 'btype', 'Batch Type',$attributes);
        $mform->setType('btype', PARAM_RAW);
        $mform->setDefault('btype','ACAD');

        $mform->addElement('submit', 'btnsave', 'create batch');
    }

    function validation($data, $files) {
        
    }   
}
?>

