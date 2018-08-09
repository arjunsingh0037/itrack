<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class academicbatch_enrol_form extends moodleform {

    function definition() {
        global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
        $mform->addElement('header','filehdr', 'Add Students to Batch');
        $streams = array();
        $syear = array();
        $semester = array();
        $batches = array();

        $programs = $DB->get_records('batches',array('creatorid'=>$USER->id,'batchtype'=>'ACAD'),'program');
        foreach ($programs as $prg) {
                $program[$prg->program] = $prg->program;
        }
        $mform->addElement('select', 'ac_program','Program', $program,array('id'=>'enrol_program'),'Select Program');
        //$mform->addElement('select', 'program','Program',$programs ,array('id'=>'id_programs'));
        
        $mform->addElement('select', 'ac_stream','Stream', $streams,array('id'=>'enrol_stream'));
        
        $mform->addElement('select', 'ac_semester_year','Semester Year', $syear,array('id'=>'enrol_year'));
        
        $mform->addElement('select', 'ac_semester','Semester', $semester,array('id'=>'enrol_semester'));
        
        $mform->addElement('select', 'batchid','Batch', $batches,array('id'=>'enrol_batch'));
        
        $mform->addElement('html','<hr>');
        $mform->addElement('html','<div id="student_enrol">');
        $mform->addElement('html','</div>');
        $mform->addElement('html','<hr>');

        $mform->addElement('submit', 'btnsave', 'Submit');
    }

    function validation($data, $files) {
        
    }   
}
class nonacademicbatch_enrol_form extends moodleform {

    function definition() {
        global $CFG,$COURSE,$DB;
        $mform = & $this->_form;
        $mform->addElement('header','filehdr', 'Add Students to Batch');
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

