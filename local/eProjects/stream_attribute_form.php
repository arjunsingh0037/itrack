<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class add_stream_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$USER;
		$mform = & $this->_form;
		$attributes=array('size'=>'20','placeholder'=>'Stream');
        $mform->addElement('text', 'stream_name', 'Stream Name',$attributes);
        $mform->addRule('stream_name', null, 'required', null, 'client');
        $mform->setType('stream_name', PARAM_RAW); 

        $attributes=array();
        $mform->addElement('hidden', 'userid', $USER->id, $attributes);
        $mform->setType('userid', PARAM_INT);

        $mform->addElement('submit', 'btnsave', 'Submit');
	}

	function validation($data, $files) {
	
	}
}
?>

