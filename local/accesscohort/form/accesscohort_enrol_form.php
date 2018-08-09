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

  class local_accesscohort_enrol_form extends moodleform {
   function definition() {
    global $CFG,$DB,$USER,$PAGE,$OUTPUT;
    $mform =& $this->_form;	
    $id = required_param('id',PARAM_RAW);
        $customdata = $this->_customdata['csdata']; // this contains the data of this 
        $mform->addElement('header','cohortadding',get_string('cohortadding','local_accesscohort'));
        $mform->setExpanded('cohortadding');
        $mform->addElement('static', 'explainenrol1',get_string('note','local_accesscohort'), get_string('explainenrol1', 'local_accesscohort'));

        $org = '';
        $sall = array('select-all'=>'Select All');
        $cohort1 = $DB->get_records_menu('cohort',array('id'=>$id),null,'id,name');
        $cohort = ($cohort1);
        $select = $mform->addElement('select', 'cohort_id',
          get_string('cohortname','local_accesscohort'),$cohort);
        $mform->addHelpButton('cohort_id', 'cohortname', 'local_accesscohort');
        $mform->addRule('cohort_id', get_string('required'), 'required', null, 'client');
       // $select->setSelected('select-all');
        $select->setMultiple(false);
		//select course 
        $course1 = $DB->get_records_menu('course',null,null,'id,shortname');

        unset($course1[1]);
        $course = ($sall + $course1);
		//changing code here 
        $csarry = [];
        $sql = "SELECT courseid from {enrol} where enrol = 'cohort' and customint1 = $id ";
        $courseids = $DB->get_records_sql($sql);
        $cids = [];
        $csarry['select-all'] = 'Select-all';
        if($courseids){
          foreach ($courseids as $key => $course) {
            $cids = get_course($course->courseid);
            $csarry[$cids->id] = $cids->fullname;
          }
        } //print_object($csarry);
        $sall = array('select-all'=>'Select All');
        $coursevalue =$DB->get_records_menu('course',array(),NULL,'id,fullname'); 
        unset($coursevalue[1]);
        $di = array_diff_key($coursevalue,$csarry);
        if($di){
          $diff =($sall + $di);
          $select = $mform->addElement('select', 'courseid', get_string('coursename','local_accesscohort'), $diff);
        }else{
           $select = $mform->addElement('select', 'courseid', get_string('coursename','local_accesscohort'), NUll);
        }

        $mform->addHelpButton('courseid', 'coursename', 'local_accesscohort');
        $mform->addRule('courseid', get_string('required'), 'required', null, 'client');
        $select->setSelected('select-all');
        $select->setMultiple(true);
        //normally you use add_action_buttons instead of this code
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('submit'));
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
      }
    }

