<?php
require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class edit_stream_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$USER,$DB;
		$mform = & $this->_form;
		$attributes=array('size'=>'20','value'=>'');
        $mform->addElement('text', 'edit_stream_name', 'Stream Name',$attributes);
        $mform->addRule('edit_stream_name', null, 'required', null, 'client');
        $mform->setType('edit_stream_name', PARAM_RAW); 

        $attributes=array();
        $mform->addElement('hidden', 'userid', $USER->id, $attributes);
        $mform->setType('userid', PARAM_INT);

        $attributes=array('id'=>'editstreamid');
        $mform->addElement('hidden', 'streamid', 0, $attributes);
        $mform->setType('streamid', PARAM_INT);

        $mform->addElement('submit', 'btnsave', 'Submit');
	}

	function validation($data, $files) {
	
	}
}

class edit_domain_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$USER,$DB;
		$mform = & $this->_form;
		
		$streams = $DB->get_records('stream');
        foreach ($streams as $stream) {
                $allstream[$stream->id] = $stream->stream_name;
        }
        $mform->addElement('select', 'stream_id','Stream', $allstream,array('id'=>'edit_id_stream'),'Select Stream');

		$attributes=array('size'=>'20','value'=>'');
        $mform->addElement('text', 'edit_domain_name', 'Domain Name',$attributes);
        $mform->addRule('edit_domain_name', null, 'required', null, 'client');
        $mform->setType('edit_domain_name', PARAM_RAW); 

        $attributes=array();
        $mform->addElement('hidden', 'userid', $USER->id, $attributes);
        $mform->setType('userid', PARAM_INT);

        $attributes=array('id'=>'editsstreamid');
        $mform->addElement('hidden', 'streamid', 0, $attributes);
        $mform->setType('streamid', PARAM_INT);

        $attributes=array('id'=>'editsdomainid');
        $mform->addElement('hidden', 'domainid', 0, $attributes);
        $mform->setType('domainid', PARAM_INT);

        $mform->addElement('submit', 'btnsave', 'Submit');
	}

	function validation($data, $files) {
	
	}
}