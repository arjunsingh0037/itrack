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

class local_enrolment_prasent_form extends moodleform {
	function definition() {
        global $CFG,$DB,$USER,$PAGE,$OUTPUT;
		$mform =& $this->_form;
        $customdata = $this->_customdata['csdata'];
        $mform->addElement('header','enrolhrd1',get_string('enrolhrd1','local_accesscohort'));
        $mform->setExpanded('enrolhrd1');
        $mform->addElement('static', 'explainenrol',get_string('note','local_accesscohort'), get_string('explainenrol', 'local_accesscohort'));
        $org = '';
        $sall = array('select-all'=>'Select All');
            //organization short name should display in select box here 
        if(!is_siteadmin()){
            $org1 = $DB->get_records('local_organization',null,null,'id,org_name,short_name');
            $user_org = $DB->get_record('user',array('id' => $USER->id),'institution');
            $org = array();
            $org[0] = 'Please Select Organization';
            foreach ($org1 as $key => $value) {
                if($value->short_name == $user_org->institution){
                    $org[$key] = $value->org_name;
                }
            }
        }else{  
    $org[0] = 'Please Select Organization';
    $orgde = $DB->get_records('local_organization',null,null,'id,org_name');
    if($orgde){
        foreach ($orgde as $key => $orgvalue) {
            $org[$key] = $orgvalue->org_name;
        }
    }
}
$mform->addElement('select', 'org_id',
    get_string('organization','local_accesscohort'),
    $org,array('single'));
$mform->addHelpButton('org_id', 'organization', 'local_accesscohort');

$mform->addRule('org_id', get_string('required'), 'required', null, 'client');
$mform->setType('organization', PARAM_RAW);

$cohort1 = $DB->get_records_menu('cohort',null,null,'id,name');
$cohort = ($cohort1);
if(isset($customdata['org_id'])){
    if(!empty($customdata['org_id'])){
        $sql = 'SELECT cohort_id FROM {local_mapping_cohort} WHERE org_id = '.$customdata['org_id'].'';
        $result = $DB->get_record_sql($sql);
        $chtresult = [];
        if($result->cohort_id){
            $cid = $result->cohort_id;
            $sql2 = "SELECT id,name FROM {cohort} WHERE id in ($cid)";
            $result2= $DB->get_records_sql($sql2);
            if($result2){
                foreach ($result2 as $key => $chsvalue) {
                    $chtresult[$chsvalue->id] = $chsvalue->name;
                }
            }
        }
        $select = $mform->addElement('select', 'cohort_id',
            get_string('cohortname','local_accesscohort'),
            $chtresult);
    }

}else{
    $select = $mform->addElement('select', 'cohort_id',
        get_string('cohortname','local_accesscohort'),
        array());
}
$mform->addHelpButton('cohort_id', 'cohortname', 'local_accesscohort');
$mform->addRule('cohort_id', get_string('required'), 'required', null, 'client');
   // $select->setSelected('select-all');
$select->setMultiple(false);
$buttonarray=array();
$buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('submit'));
$buttonarray[] = $mform->createElement('cancel');
$mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
}
}

