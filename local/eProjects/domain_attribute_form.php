<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class add_domain_form extends moodleform {

	function definition() {
	       global $CFG,$COURSE,$DB, $USER;
		$mform = & $this->_form;

		$streams = $DB->get_records('stream');
                foreach ($streams as $stream) {
                        $allstream[$stream->id] = $stream->stream_name;
                }
                $mform->addElement('select', 'stream_id','Stream', $allstream,array('id'=>'id_stream'),'Select Stream');

        		$attributes=array('size'=>'20','placeholder'=>'Domain');
                $mform->addElement('text', 'domain_name', 'Domain Name',$attributes);
                $mform->addRule('domain_name', null, 'required', null, 'client');
                $mform->setType('domain_name', PARAM_RAW); 

                $attributes=array();
                $mform->addElement('hidden', 'userid', $USER->id, $attributes);
                $mform->setType('userid', PARAM_INT);

                $mform->addElement('submit', 'btnsave', 'Submit');	
	}

	function validation($data, $files) {
	
	}	
}
?>

