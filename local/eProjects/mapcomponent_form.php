<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class mapcomponent_form extends moodleform {

    function definition() {
        global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
        $mform->addElement('header','filehdr', 'ASSIGN COMPONENTS TO PROJECTS');
        $batches = array();
        $components = array();
        $component = $DB->get_records('component',array('createdby'=>$USER->id),'id,name,streamid');
        foreach ($component as $cmp) {
                $components[$cmp->id] = $cmp->name;
        }
        $mform->addElement('select', 'componentid','Select Component', $components,array('id'=>'map_component'));
        $mform->addRule('componentid', 'Required', 'required', null, 'client');

        $courses = $mform->addElement('select', 'projectid','Select Project (s)', $batches,array('id'=>'map_project'));
        $courses->setMultiple(true);
        $mform->addRule('projectid', 'Required', 'required', null, 'client');
        $mform->addElement('submit', 'btnsave', 'Submit');
    }

    function validation($data, $files) {
        
    }   
}
?>

