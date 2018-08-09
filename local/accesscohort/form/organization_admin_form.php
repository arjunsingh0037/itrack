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

class local_organization_admin_form extends moodleform {
	function definition() {
        global $CFG,$DB,$USER,$PAGE,$OUTPUT;
        $mform =& $this->_form;
        $context = context_system::instance();
        //$createorgcap = has_capability('local/accesscohort:adduseraccesscohort',$context);
        $edit = optional_param('edit',null,PARAM_INT);
        $customdata = $this->_customdata['csdata'];
        if(!$edit){
            $mform->addElement('header','enrolhrd1',get_string('adduser','local_accesscohort'));
            $mform->setExpanded('enrolhrd1');
            $mform->addElement('static', 'userhelp',get_string('note','local_accesscohort'), get_string('userhelp', 'local_accesscohort'));
        }else{
           $mform->addElement('header','enrolhrd1',get_string('edit_org_admin','local_accesscohort'));
           $mform->setExpanded('enrolhrd1');
           $mform->addElement('static', 'userhelp',get_string('note','local_accesscohort'), get_string('userhelp', 'local_accesscohort'));  
       }
       $org1 = $DB->get_records_menu('local_organization',null,null,'id,org_name');
       $mform->addElement('select', 'orgid',
        get_string('organization','local_accesscohort'),
        $org1,array('single'));
       $mform->addHelpButton('orgid', 'organization', 'local_accesscohort');
       $mform->addRule('orgid', get_string('required'), 'required', null, 'client');
       $mform->setType('organization', PARAM_RAW);

        //here organization short name be fixed  
       $adminrole = [];$arr3 = array();
       $userin =  get_users_by_capability($context, 'local/accesscohort:adduseraccesscohort');
       $uservalue = [];
       if($userin){
           foreach ($userin as $key => $value) {
                //$uservalue[$key] = $value->firstname; //old line wrong so commenting Mihir 23 Feb 2018
                //$uservalue[$key] = $value->firstname.' '.$value->firstname.' - '.$value->email; //New line for complete details
                $uservalue[$key] = $value->firstname.' '.$value->lastname.' - '.$value->email; //New line for complete details
            }
        }
         if($uservalue){
             $select = $mform->addElement('searchableselector', 'userid',
                get_string('userid','local_accesscohort'),$uservalue);
        }else{
            $select = $mform->addElement('searchableselector', 'userid',
            get_string('userid','local_accesscohort'),array());
        }
        $mform->addHelpButton('userid', 'userid', 'local_accesscohort');
        $mform->addRule('userid', get_string('required'), 'required', null, 'client');
        $select->setMultiple(false);
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('submit'));
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
}

