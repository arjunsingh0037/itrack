<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class addcategory_form extends moodleform {

    function definition() {
        global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
        $mform->addElement('header','filehdr', 'Add a new category');
        $catid = $this->_customdata['id']
        $programs = $DB->get_records('batches',array('creatorid'=>$USER->id,'batchtype'=>'ACAD'),'program');
        foreach ($programs as $prg) {
                $program[$prg->program] = $prg->program;
        }
        $mform->addElement('select', 'ac_program','Program', $program,array('id'=>'enrol_program'),'Select Program');
    

        $mform->addElement('submit', 'btnsave', 'Submit');
    }

    function validation($data, $files) {
        
    }   
}

