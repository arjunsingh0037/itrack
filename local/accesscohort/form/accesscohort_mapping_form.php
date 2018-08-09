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

class local_accesscohort_mapping_form extends moodleform {
	function definition() {
		global $CFG,$DB,$USER,$PAGE,$OUTPUT;
		$mform =& $this->_form;
		$edit = optional_param('edit',null,PARAM_INT);
		//first section
		if(!$edit){
			$mform->addElement('header','accessinfohdr',get_string('accessinfohdr','local_accesscohort'));
			$mform->setExpanded('accessinfohdr');
			$mform->addElement('static', 'mapaccess',get_string('note','local_accesscohort'), get_string('mapaccess', 'local_accesscohort'));
		}else{
			$mform->addElement('header','accessinfohdr',get_string('edit_map','local_accesscohort'));
			$mform->setExpanded('accessinfohdr');
			$mform->addElement('static', 'mapaccess',get_string('note','local_accesscohort'), get_string('mapaccess', 'local_accesscohort'));
		}
        //organization short name should display in select box here 
		if(!is_siteadmin()){
			$org1 = $DB->get_records('local_organization',null,null,'id,org_name,short_name');
			$user_org = $DB->get_record('user',array('id' => $USER->id),'institution');
			$org = array();
			foreach ($org1 as $key => $value) {
				//print_object($value1);
				if($value->org_name == $user_org->institution){

					$org[$key] = $value->org_name;
				}
			}
		}else{
			$org = $DB->get_records_menu('local_organization',null,null,'id,org_name');
		}
		$mform->addElement('select', 'org_id',
			get_string('organization','local_accesscohort'),
			$org,array('single'));
		$mform->setType('organization', PARAM_RAW);

		$cohort = $DB->get_records_menu('cohort',null,null,'id,name');
		$select = $mform->addElement('searchableselector', 'cohort_id', get_string('cohort_name','local_accesscohort'), $cohort, array('multiple'));
		$select->setMultiple(true);
		$this->add_action_buttons();
	}
}