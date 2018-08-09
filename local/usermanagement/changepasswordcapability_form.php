<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/formslib.php');

class changepasswordcapability_form extends moodleform {

    function definition() {
        $mform =& $this->_form;
        $mform->addElement('header', 'general', get_string('changepasswordcapability', 'local_usermanagement'));
       	$setcap=array();
		$setcap[] = $mform->createElement('radio', 'changepassword', '', get_string('allowchangepassword', 'local_usermanagement'), 1);
		$setcap[] = $mform->createElement('radio', 'changepassword', '', get_string('preventchangepassword','local_usermanagement'), -1);
		$mform->setDefault('changepassword', 1);
        $mform->setType('changepassword', PARAM_RAW);
        $mform->addElement('hidden', 'allowchangepassword', '1');
        $mform->setType('allowchangepassword', PARAM_RAW);
        $mform->addGroup($setcap, 'radioar', '', array(' '), false);
        //normally you use add_action_buttons instead of this code
        $buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        $buttonarray[] = &$mform->createElement('reset', 'resetbutton', get_string('revert'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('general');
    }
}