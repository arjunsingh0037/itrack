<?php
// This file is part of Moodle - http://moodle.org/
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
 * Bulk user upload forms
 *
 * @package    tool
 * @subpackage uploaduser
 * @copyright  2007 Dan Poltawski
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once $CFG->libdir.'/formslib.php';
require_once($CFG->dirroot . '/user/editlib.php');

/**
 * Upload a file CVS file with user information.
 *
 * @copyright  2007 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_uploaduser_form1 extends moodleform {
    function definition () {
        global $DB,$CFG,$USER;
        $totalusers = $liveusers = $pendingusers = 0;
        $mform = $this->_form;

        $mform->addElement('header', 'settingsheader', get_string('upload'));
        $profsample = new moodle_url('/admin/tool/uploaduser/Professor.csv');
        $studsample = new moodle_url('/admin/tool/uploaduser/Student.csv');
        $mform->addElement('html','<div style="width: 50%;overflow: auto;"><span>Download Excel Format</span><div style="width: 50%;float:right;"><a href="'.$studsample.'"><button type="button" class="btn btn-success" style="margin-right:10px">Student</button></a><a href="'.$profsample.'"><button type="button" class="btn btn-success">Professor</button></a></div></div>');
        //<div class="qheader">
        $user_category = array(
            'student' => 'Student',
            'professor' => 'Professor'
        );
        $select = $mform->addElement('select', 'usertype', 'Select Bulk Upload User Type', $user_category,array('id'=>'usercat'));
        $mform->setType('usertype', PARAM_RAW);

        $mform->addElement('html','<div id="showtostudent">');
        $program = array();
        $select = $mform->addElement('select', 'program', 'Program', $program,array('id'=>'program'));
        $mform->setType('program', PARAM_RAW);

        $stream = array();
        $select = $mform->addElement('select', 'stream', 'Stream', $program,array('id'=>'stream'));
        $mform->setType('stream', PARAM_RAW);

        $semester = array(
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 7,
            '8' => 8,
        );
        $select = $mform->addElement('select', 'semester', 'Semester', $semester,array('id'=>'semester'));
        $mform->setType('semester', PARAM_INT);

        $semyear = array(
            '2017' => 2017,
            '2018' => 2018
        );
        $select = $mform->addElement('select', 'semyear', 'Semester Year', $semyear,array('id'=>'semyear'));
        $mform->setType('semyear', PARAM_RAW);

        $mform->addElement('html','</div>');

        
        if($DB->record_exists('trainingpartners',array('userid'=>$USER->id))){
            $partnerid = $DB->get_record('trainingpartners',array('userid'=>$USER->id),'createdby');
            $permission_for_tpa = $DB->get_record('partners',array('userid'=>$partnerid->createdby),'id,tp_permission,stud_permission,prof_permission,hc_permission,stud_no,prof_no');
        }

        //max students, max professors, total number of itrack users
        $mform->addElement('static', 'totalusers','Total Number of Users',$totalusers);
        $mform->addElement('static', 'liveusers','Total Number of Users in Live',$liveusers);
        $mform->addElement('static', 'pendingusers','Total Number of Users Pending',$pendingusers);

        //print_object($permission_for_tpa);die();
        //Allocate Access Permissions for To-be-created Students, given by Account Manager to the loggined TP
        $availablefromgroup5=array();
        $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'coursemanageSTU', 'Course Management',null, array('group' => 5));
        $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'labsmanageSTU', 'Labs Management',null, array('group' => 5));
        $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'projectemanageSTU', 'Project Management',null, array('group' => 5));
        $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'assessmanageSTU', 'Assessment Management',null, array('group' => 5));
        $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'taskmanageSTU', 'Task Management',null, array('group' => 5));
        $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'jobmanageSTU', 'Job Management',null, array('group' => 5));
        $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'resumeanalyzerSTU', 'Resume Analyzer',null, array('group' => 5));
        $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'iKonnectmanageSTU', 'iKonnect Management',null, array('group' => 5));
        $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'mentormanageSTU', 'Mentor Management',null, array('group' => 5));
        $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'accountmanageSTU', 'Account Management',null, array('group' => 5));
        $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'feedbackSTU', 'Feedback',null, array('group' => 5));
        $mform->addGroup($availablefromgroup5, 'availablefromgroup5', 'Permission', ' ', false); 

        $stud_permission_check = explode(",", $permission_for_tpa->stud_permission);
        if($stud_permission_check[0] == 1){
           $mform->setDefault('coursemanageSTU', true); 
        }
        if($stud_permission_check[1] == 1){
           $mform->setDefault('labsmanageSTU', true); 
        }
        if($stud_permission_check[2] == 1){
           $mform->setDefault('projectemanageSTU', true); 
        }
        if($stud_permission_check[3] == 1){
           $mform->setDefault('assessmanageSTU', true); 
        }
        if($stud_permission_check[4] == 1){
           $mform->setDefault('taskmanageSTU', true); 
        }
        if($stud_permission_check[5] == 1){
           $mform->setDefault('jobmanageSTU', true); 
        }
        if($stud_permission_check[6] == 1){
           $mform->setDefault('resumeanalyzerSTU', true); 
        }
        if($stud_permission_check[7] == 1){
           $mform->setDefault('iKonnectmanageSTU', true); 
        }
        if($stud_permission_check[8] == 1){
           $mform->setDefault('mentormanageSTU', true); 
        }
        if($stud_permission_check[9] == 1){
           $mform->setDefault('accountmanageSTU', true); 
        }
        if($stud_permission_check[10] == 1){
           $mform->setDefault('feedbackSTU', true); 
        }
        $mform->disabledIf('coursemanageSTU','');
        $mform->disabledIf('labsmanageSTU','');
        $mform->disabledIf('projectemanageSTU','');
        $mform->disabledIf('assessmanageSTU','');
        $mform->disabledIf('taskmanageSTU','');
        $mform->disabledIf('jobmanageSTU','');
        $mform->disabledIf('resumeanalyzerSTU','');
        $mform->disabledIf('iKonnectmanageSTU','');
        $mform->disabledIf('mentormanageSTU','');
        $mform->disabledIf('accountmanageSTU','');
        $mform->disabledIf('feedbackSTU','');

        //Allocate Access Permissions for To-be-created Students, given by Account Manager to the loggined TP
        $availablefromgroup6=array();
        $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'badgemanagePROF', 'Badge Management',null, array('group' => 6));
        $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'coursemanagePROF', 'Course Management',null, array('group' => 6));
        $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'labmanagePROF', 'Labs Management',null, array('group' => 6));
        $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'hacklabmanagePROF', 'Hackathon Labs Management',null, array('group' => 6));
        $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'projectmanagePROF', 'Project Management',null, array('group' => 6));
        $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'assessmanagePROF', 'Assessmnet Management',null, array('group' => 6));
        $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'taskmanagePROF', 'Task Management',null, array('group' => 6));
        $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'iKonnectmanagePROF', 'iKonnect Management',null, array('group' => 6));
        $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'reportmanagePROF', 'Report Management',null, array('group' => 6));
        $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'feedbackPROF', 'Feedback',null, array('group' => 6));
        $mform->addGroup($availablefromgroup6, 'availablefromgroup6', 'Permission', ' ', false); 

        $prof_permission_check = explode(",", $permission_for_tpa->prof_permission);
        if($prof_permission_check[0] == 1){
            $mform->setDefault('badgemanagePROF', true); 
        }
        if($prof_permission_check[1] == 1){
            $mform->setDefault('coursemanagePROF', true); 
        }                    
        if($prof_permission_check[2] == 1){
            $mform->setDefault('labmanagePROF', true); 
        }
        if($prof_permission_check[3] == 1){
            $mform->setDefault('hacklabmanagePROF', true); 
        }
        if($prof_permission_check[4] == 1){
            $mform->setDefault('projectmanagePROF', true); 
        }
        if($prof_permission_check[5] == 1){
            $mform->setDefault('assessmanagePROF', true); 
        }
        if($prof_permission_check[6] == 1){
            $mform->setDefault('taskmanagePROF', true); 
        }
        if($prof_permission_check[7] == 1){
            $mform->setDefault('iKonnectmanagePROF', true); 
        }
        if($prof_permission_check[8] == 1){
            $mform->setDefault('reportmanagePROF', true); 
        }
        if($prof_permission_check[9] == 1){
            $mform->setDefault('feedbackPROF', true); 
        }
        $mform->disabledIf('badgemanagePROF','');
        $mform->disabledIf('coursemanagePROF','');
        $mform->disabledIf('labmanagePROF','');
        $mform->disabledIf('hacklabmanagePROF','');
        $mform->disabledIf('projectmanagePROF','');
        $mform->disabledIf('assessmanagePROF','');
        $mform->disabledIf('taskmanagePROF','');
        $mform->disabledIf('iKonnectmanagePROF','');
        $mform->disabledIf('reportmanagePROF','');
        $mform->disabledIf('feedbackPROF','');
        
        $mform->addElement('filepicker', 'userfile', 'Upload CSV File', null,
                   array('accepted_types' => '*'));
        $mform->addRule('userfile', null, 'required');

        /*$choices = csv_import_reader::get_delimiter_list();
        $mform->addElement('select', 'delimiter_name', get_string('csvdelimiter', 'tool_uploaduser'), $choices);
        if (array_key_exists('cfg', $choices)) {
            $mform->setDefault('delimiter_name', 'cfg');
        } else if (get_string('listsep', 'langconfig') == ';') {
            $mform->setDefault('delimiter_name', 'semicolon');
        } else {
            $mform->setDefault('delimiter_name', 'comma');
        }

        $choices = core_text::get_encodings();
        $mform->addElement('select', 'encoding', get_string('encoding', 'tool_uploaduser'), $choices);
        $mform->setDefault('encoding', 'UTF-8');

        $choices = array('10'=>10, '20'=>20, '100'=>100, '1000'=>1000, '100000'=>100000);
        $mform->addElement('select', 'previewrows', get_string('rowpreviewnum', 'tool_uploaduser'), $choices);
        $mform->setType('previewrows', PARAM_INT);*/

        $mform->addElement('hidden', 'delimiter_name', '', 'maxlength="100" size="10"');
        $mform->setType('delimiter_name', PARAM_RAW);
        $mform->setDefault('delimiter_name', 'comma');

        $mform->addElement('hidden', 'encoding', '', 'maxlength="100" size="10"');
        $mform->setType('encoding', PARAM_RAW);
        $mform->setDefault('encoding', 'UTF-8');

        $mform->addElement('hidden', 'previewrows', '', 'maxlength="100" size="10"');
        $mform->setType('previewrows', PARAM_INT);
        $mform->setDefault('encoding', 10);

        $this->add_action_buttons(false, 'Submit');
    }
}


/**
 * Specify user upload details
 *
 * @copyright  2007 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_uploaduser_form2 extends moodleform {
    function definition () {
        global $CFG, $USER;

        $mform   = $this->_form;
        $columns = $this->_customdata['columns'];
        $data    = $this->_customdata['data'];

        // I am the template user, why should it be the administrator? we have roles now, other ppl may use this script ;-)
        $templateuser = $USER;

        // upload settings and file
        $mform->addElement('header', 'settingsheader', get_string('settings'));

        $choices = array(UU_USER_ADDNEW     => get_string('uuoptype_addnew', 'tool_uploaduser'),
                         UU_USER_ADDINC     => get_string('uuoptype_addinc', 'tool_uploaduser'),
                         UU_USER_ADD_UPDATE => get_string('uuoptype_addupdate', 'tool_uploaduser'),
                         UU_USER_UPDATE     => get_string('uuoptype_update', 'tool_uploaduser'));
        $mform->addElement('select', 'uutype', get_string('uuoptype', 'tool_uploaduser'), $choices);

        $choices = array(0 => get_string('infilefield', 'auth'), 1 => get_string('createpasswordifneeded', 'auth'));
        $mform->addElement('select', 'uupasswordnew', get_string('uupasswordnew', 'tool_uploaduser'), $choices);
        $mform->setDefault('uupasswordnew', 1);
        $mform->disabledIf('uupasswordnew', 'uutype', 'eq', UU_USER_UPDATE);

        $choices = array(UU_UPDATE_NOCHANGES    => get_string('nochanges', 'tool_uploaduser'),
                         UU_UPDATE_FILEOVERRIDE => get_string('uuupdatefromfile', 'tool_uploaduser'),
                         UU_UPDATE_ALLOVERRIDE  => get_string('uuupdateall', 'tool_uploaduser'),
                         UU_UPDATE_MISSING      => get_string('uuupdatemissing', 'tool_uploaduser'));
        $mform->addElement('select', 'uuupdatetype', get_string('uuupdatetype', 'tool_uploaduser'), $choices);
        $mform->setDefault('uuupdatetype', UU_UPDATE_NOCHANGES);
        $mform->disabledIf('uuupdatetype', 'uutype', 'eq', UU_USER_ADDNEW);
        $mform->disabledIf('uuupdatetype', 'uutype', 'eq', UU_USER_ADDINC);

        $choices = array(0 => get_string('nochanges', 'tool_uploaduser'), 1 => get_string('update'));
        $mform->addElement('select', 'uupasswordold', get_string('uupasswordold', 'tool_uploaduser'), $choices);
        $mform->setDefault('uupasswordold', 0);
        $mform->disabledIf('uupasswordold', 'uutype', 'eq', UU_USER_ADDNEW);
        $mform->disabledIf('uupasswordold', 'uutype', 'eq', UU_USER_ADDINC);
        $mform->disabledIf('uupasswordold', 'uuupdatetype', 'eq', 0);
        $mform->disabledIf('uupasswordold', 'uuupdatetype', 'eq', 3);

        $choices = array(UU_PWRESET_WEAK => get_string('usersweakpassword', 'tool_uploaduser'),
                         UU_PWRESET_NONE => get_string('none'),
                         UU_PWRESET_ALL  => get_string('all'));
        if (empty($CFG->passwordpolicy)) {
            unset($choices[UU_PWRESET_WEAK]);
        }
        $mform->addElement('select', 'uuforcepasswordchange', get_string('forcepasswordchange', 'core'), $choices);


        $mform->addElement('selectyesno', 'uuallowrenames', get_string('allowrenames', 'tool_uploaduser'));
        $mform->setDefault('uuallowrenames', 0);
        $mform->disabledIf('uuallowrenames', 'uutype', 'eq', UU_USER_ADDNEW);
        $mform->disabledIf('uuallowrenames', 'uutype', 'eq', UU_USER_ADDINC);

        $mform->addElement('selectyesno', 'uuallowdeletes', get_string('allowdeletes', 'tool_uploaduser'));
        $mform->setDefault('uuallowdeletes', 0);
        $mform->disabledIf('uuallowdeletes', 'uutype', 'eq', UU_USER_ADDNEW);
        $mform->disabledIf('uuallowdeletes', 'uutype', 'eq', UU_USER_ADDINC);

        $mform->addElement('selectyesno', 'uuallowsuspends', get_string('allowsuspends', 'tool_uploaduser'));
        $mform->setDefault('uuallowsuspends', 1);
        $mform->disabledIf('uuallowsuspends', 'uutype', 'eq', UU_USER_ADDNEW);
        $mform->disabledIf('uuallowsuspends', 'uutype', 'eq', UU_USER_ADDINC);

        if (!empty($CFG->allowaccountssameemail)) {
            $mform->addElement('selectyesno', 'uunoemailduplicates', get_string('uunoemailduplicates', 'tool_uploaduser'));
            $mform->setDefault('uunoemailduplicates', 1);
        } else {
            $mform->addElement('hidden', 'uunoemailduplicates', 1);
        }
        $mform->setType('uunoemailduplicates', PARAM_BOOL);

        $mform->addElement('selectyesno', 'uustandardusernames', get_string('uustandardusernames', 'tool_uploaduser'));
        $mform->setDefault('uustandardusernames', 1);

        $choices = array(UU_BULK_NONE    => get_string('no'),
                         UU_BULK_NEW     => get_string('uubulknew', 'tool_uploaduser'),
                         UU_BULK_UPDATED => get_string('uubulkupdated', 'tool_uploaduser'),
                         UU_BULK_ALL     => get_string('uubulkall', 'tool_uploaduser'));
        $mform->addElement('select', 'uubulk', get_string('uubulk', 'tool_uploaduser'), $choices);
        $mform->setDefault('uubulk', 0);

        // roles selection
        $showroles = false;
        foreach ($columns as $column) {
            if (preg_match('/^type\d+$/', $column)) {
                $showroles = true;
                break;
            }
        }
        if ($showroles) {
            $mform->addElement('header', 'rolesheader', get_string('roles'));

            $choices = uu_allowed_roles(true);

            $mform->addElement('select', 'uulegacy1', get_string('uulegacy1role', 'tool_uploaduser'), $choices);
            if ($studentroles = get_archetype_roles('student')) {
                foreach ($studentroles as $role) {
                    if (isset($choices[$role->id])) {
                        $mform->setDefault('uulegacy1', $role->id);
                        break;
                    }
                }
                unset($studentroles);
            }

            $mform->addElement('select', 'uulegacy2', get_string('uulegacy2role', 'tool_uploaduser'), $choices);
            if ($editteacherroles = get_archetype_roles('editingteacher')) {
                foreach ($editteacherroles as $role) {
                    if (isset($choices[$role->id])) {
                        $mform->setDefault('uulegacy2', $role->id);
                        break;
                    }
                }
                unset($editteacherroles);
            }

            $mform->addElement('select', 'uulegacy3', get_string('uulegacy3role', 'tool_uploaduser'), $choices);
            if ($teacherroles = get_archetype_roles('teacher')) {
                foreach ($teacherroles as $role) {
                    if (isset($choices[$role->id])) {
                        $mform->setDefault('uulegacy3', $role->id);
                        break;
                    }
                }
                unset($teacherroles);
            }
        }

        // default values
        $mform->addElement('header', 'defaultheader', get_string('defaultvalues', 'tool_uploaduser'));

        $mform->addElement('text', 'username', get_string('uuusernametemplate', 'tool_uploaduser'), 'size="20"');
        $mform->setType('username', PARAM_RAW); // No cleaning here. The process verifies it later.
        $mform->addRule('username', get_string('requiredtemplate', 'tool_uploaduser'), 'required', null, 'client');
        $mform->disabledIf('username', 'uutype', 'eq', UU_USER_ADD_UPDATE);
        $mform->disabledIf('username', 'uutype', 'eq', UU_USER_UPDATE);
        $mform->setForceLtr('username');

        $mform->addElement('text', 'email', get_string('email'), 'maxlength="100" size="30"');
        $mform->setType('email', PARAM_RAW); // No cleaning here. The process verifies it later.
        $mform->disabledIf('email', 'uutype', 'eq', UU_USER_ADD_UPDATE);
        $mform->disabledIf('email', 'uutype', 'eq', UU_USER_UPDATE);
        $mform->setForceLtr('email');

        // only enabled and known to work plugins
        $choices = uu_supported_auths();
        $mform->addElement('select', 'auth', get_string('chooseauthmethod','auth'), $choices);
        $mform->setDefault('auth', 'manual'); // manual is a sensible backwards compatible default
        $mform->addHelpButton('auth', 'chooseauthmethod', 'auth');
        $mform->setAdvanced('auth');

        $choices = array(0 => get_string('emaildisplayno'), 1 => get_string('emaildisplayyes'), 2 => get_string('emaildisplaycourse'));
        $mform->addElement('select', 'maildisplay', get_string('emaildisplay'), $choices);
        $mform->setDefault('maildisplay', core_user::get_property_default('maildisplay'));

        $choices = array(0 => get_string('textformat'), 1 => get_string('htmlformat'));
        $mform->addElement('select', 'mailformat', get_string('emailformat'), $choices);
        $mform->setDefault('mailformat', core_user::get_property_default('mailformat'));
        $mform->setAdvanced('mailformat');

        $choices = array(0 => get_string('emaildigestoff'), 1 => get_string('emaildigestcomplete'), 2 => get_string('emaildigestsubjects'));
        $mform->addElement('select', 'maildigest', get_string('emaildigest'), $choices);
        $mform->setDefault('maildigest', core_user::get_property_default('maildigest'));
        $mform->setAdvanced('maildigest');

        $choices = array(1 => get_string('autosubscribeyes'), 0 => get_string('autosubscribeno'));
        $mform->addElement('select', 'autosubscribe', get_string('autosubscribe'), $choices);
        $mform->setDefault('autosubscribe', core_user::get_property_default('autosubscribe'));

        $mform->addElement('text', 'city', get_string('city'), 'maxlength="120" size="25"');
        $mform->setType('city', PARAM_TEXT);
        if (empty($CFG->defaultcity)) {
            $mform->setDefault('city', $templateuser->city);
        } else {
            $mform->setDefault('city', core_user::get_property_default('city'));
        }

        $choices = get_string_manager()->get_list_of_countries();
        $choices = array(''=>get_string('selectacountry').'...') + $choices;
        $mform->addElement('select', 'country', get_string('selectacountry'), $choices);
        if (empty($CFG->country)) {
            $mform->setDefault('country', $templateuser->country);
        } else {
            $mform->setDefault('country', core_user::get_property_default('country'));
        }
        $mform->setAdvanced('country');

        $choices = core_date::get_list_of_timezones($templateuser->timezone, true);
        $mform->addElement('select', 'timezone', get_string('timezone'), $choices);
        $mform->setDefault('timezone', $templateuser->timezone);
        $mform->setAdvanced('timezone');

        $mform->addElement('select', 'lang', get_string('preferredlanguage'), get_string_manager()->get_list_of_translations());
        $mform->setDefault('lang', $templateuser->lang);
        $mform->setAdvanced('lang');

        $editoroptions = array('maxfiles'=>0, 'maxbytes'=>0, 'trusttext'=>false, 'forcehttps'=>false);
        $mform->addElement('editor', 'description', get_string('userdescription'), null, $editoroptions);
        $mform->setType('description', PARAM_CLEANHTML);
        $mform->addHelpButton('description', 'userdescription');
        $mform->setAdvanced('description');

        $mform->addElement('text', 'url', get_string('webpage'), 'maxlength="255" size="50"');
        $mform->setType('url', PARAM_URL);
        $mform->setAdvanced('url');

        $mform->addElement('text', 'idnumber', get_string('idnumber'), 'maxlength="255" size="25"');
        $mform->setType('idnumber', PARAM_NOTAGS);
        $mform->setForceLtr('idnumber');

        $mform->addElement('text', 'institution', get_string('institution'), 'maxlength="255" size="25"');
        $mform->setType('institution', PARAM_TEXT);
        $mform->setDefault('institution', $templateuser->institution);

        $mform->addElement('text', 'department', get_string('department'), 'maxlength="255" size="25"');
        $mform->setType('department', PARAM_TEXT);
        $mform->setDefault('department', $templateuser->department);

        $mform->addElement('text', 'phone1', get_string('phone1'), 'maxlength="20" size="25"');
        $mform->setType('phone1', PARAM_NOTAGS);
        $mform->setAdvanced('phone1');
        $mform->setForceLtr('phone1');

        $mform->addElement('text', 'phone2', get_string('phone2'), 'maxlength="20" size="25"');
        $mform->setType('phone2', PARAM_NOTAGS);
        $mform->setAdvanced('phone2');
        $mform->setForceLtr('phone2');

        $mform->addElement('text', 'address', get_string('address'), 'maxlength="255" size="25"');
        $mform->setType('address', PARAM_TEXT);
        $mform->setAdvanced('address');

        // Next the profile defaults
        profile_definition($mform);

        // hidden fields
        $mform->addElement('hidden', 'iid');
        $mform->setType('iid', PARAM_INT);

        $mform->addElement('hidden', 'previewrows');
        $mform->setType('previewrows', PARAM_INT);

        $this->add_action_buttons(true, get_string('uploadusers', 'tool_uploaduser'));

        $this->set_data($data);
    }

    /**
     * Form tweaks that depend on current data.
     */
    function definition_after_data() {
        $mform   = $this->_form;
        $columns = $this->_customdata['columns'];

        foreach ($columns as $column) {
            if ($mform->elementExists($column)) {
                $mform->removeElement($column);
            }
        }

        if (!in_array('password', $columns)) {
            // password resetting makes sense only if password specified in csv file
            if ($mform->elementExists('uuforcepasswordchange')) {
                $mform->removeElement('uuforcepasswordchange');
            }
        }
    }

    /**
     * Server side validation.
     */
    function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $columns = $this->_customdata['columns'];
        $optype  = $data['uutype'];
        $updatetype = $data['uuupdatetype'];

        // detect if password column needed in file
        if (!in_array('password', $columns)) {
            switch ($optype) {
                case UU_USER_UPDATE:
                    if (!empty($data['uupasswordold'])) {
                        $errors['uupasswordold'] = get_string('missingfield', 'error', 'password');
                    }
                    break;

                case UU_USER_ADD_UPDATE:
                    if (empty($data['uupasswordnew'])) {
                        $errors['uupasswordnew'] = get_string('missingfield', 'error', 'password');
                    }
                    if  (!empty($data['uupasswordold'])) {
                        $errors['uupasswordold'] = get_string('missingfield', 'error', 'password');
                    }
                    break;

                case UU_USER_ADDNEW:
                    if (empty($data['uupasswordnew'])) {
                        $errors['uupasswordnew'] = get_string('missingfield', 'error', 'password');
                    }
                    break;
                case UU_USER_ADDINC:
                    if (empty($data['uupasswordnew'])) {
                        $errors['uupasswordnew'] = get_string('missingfield', 'error', 'password');
                    }
                    break;
             }
        }

        // If the 'Existing user details' value is set we need to ensure that the
        // 'Upload type' is not set to something invalid.
        if (!empty($updatetype) && ($optype == UU_USER_ADDNEW || $optype == UU_USER_ADDINC)) {
            $errors['uuupdatetype'] = get_string('invalidupdatetype', 'tool_uploaduser');
        }

        // look for other required data
        if ($optype != UU_USER_UPDATE) {
            $requiredusernames = useredit_get_required_name_fields();
            $missing = array();
            foreach ($requiredusernames as $requiredusername) {
                if (!in_array($requiredusername, $columns)) {
                    $missing[] = get_string('missingfield', 'error', $requiredusername);;
                }
            }
            if ($missing) {
                $errors['uutype'] = implode('<br />',  $missing);
            }
            if (!in_array('email', $columns) and empty($data['email'])) {
                $errors['email'] = get_string('requiredtemplate', 'tool_uploaduser');
            }
        }
        return $errors;
    }

    /**
     * Used to reformat the data from the editor component
     *
     * @return stdClass
     */
    function get_data() {
        $data = parent::get_data();

        if ($data !== null and isset($data->description)) {
            $data->descriptionformat = $data->description['format'];
            $data->description = $data->description['text'];
        }

        return $data;
    }
}
?>
