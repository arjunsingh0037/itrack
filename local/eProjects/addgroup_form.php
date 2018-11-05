<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class addgroup_form extends moodleform {

    function definition() {
        global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
        $mform->addElement('header','filehdr', 'PROJECT GROUP CREATION');

        $mform->addElement('text', 'groupname','Group Name');
        $mform->addRule('groupname', 'Required', 'required', null, 'client');
        $mform->settype('groupname', PARAM_RAW);

        $attributes=array();
        $mform->addElement('hidden', 'userid', $USER->id, $attributes);
        $mform->setType('userid', PARAM_INT);
        
        $mform->addElement('submit', 'btnsave', 'Submit');
    }

    function validation($data, $files) {
        
    }   
}
?>

