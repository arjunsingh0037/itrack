<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Handles uploading files
 *
 * @package    local_accesscohort
 * @copyright  Prashant Yallatti<prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); /// It must be included from a Moodle page
}
require_once($CFG->libdir.'/formslib.php');
class local_accesscohort_create_org_form extends moodleform {
	function definition() {
		$mform = $this->_form; 
		//orgaziation name field
		$edit = optional_param('edit',null,PARAM_INT);
		if(!$edit){
			$mform->addElement('header','accessinfohdr',get_string('org_name1','local_accesscohort'));
			$mform->setExpanded('accessinfohdr');
			$mform->addElement('static', 'createorg',get_string('note','local_accesscohort'), get_string('createorg', 'local_accesscohort'));
		}else{
			$mform->addElement('header','accessinfohdr',get_string('edit_org','local_accesscohort'));
			$mform->setExpanded('accessinfohdr');
			$mform->addElement('static', 'createorg',get_string('note','local_accesscohort'), get_string('createorg', 'local_accesscohort'));
		}

		$mform->addElement('text', 'org_name',
         get_string('org_name','local_accesscohort')); // Add elements to your form
		 $mform->addHelpButton('org_name', 'org_name', 'local_accesscohort');
		$mform->addRule('org_name', get_string('required'), 'required', null, 'client');
		$mform->setType('org_name', PARAM_TEXT);
		//organization short_name
		$mform->addElement('text', 'short_name',
         get_string('short_name','local_accesscohort')); // Add elements to your form
		$mform->setType('short_name', PARAM_TEXT);

        //organization email
		$mform->addElement('text', 'org_email',
         get_string('org_email','local_accesscohort')); // Add elements to your form
		$mform->setType('org_email', PARAM_TEXT);
        //organization address
		$mform->addElement('textarea', 'org_address',
         get_string('org_address','local_accesscohort')); // Add elements to your form
		$mform->setType('org_address', PARAM_TEXT);
        //organization phone  
		$mform->addElement('text', 'org_phone',
         get_string('org_phone','local_accesscohort')); // Add elements to your form
		$mform->setType('org_phone', PARAM_TEXT);

		$mform->addElement('text', 'org_skype',
         get_string('org_skype','local_accesscohort')); // Add elements to your form
		$mform->setType('org_skype', PARAM_TEXT);

		$mform->addElement('text', 'org_fb',
         get_string('org_fb','local_accesscohort')); // Add elements to your form
		$mform->setType('org_fb', PARAM_TEXT);

		$mform->addElement('text', 'org_tweet',
         get_string('org_tweet','local_accesscohort')); // Add elements to your form
		$mform->setType('org_tweet', PARAM_TEXT);

		$this->add_action_buttons();
	}
}
