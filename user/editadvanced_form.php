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
 * Form for editing a users profile
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package core_user
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    //  It must be included from a Moodle page.
}

require_once($CFG->dirroot.'/lib/formslib.php');


/**
 * Class user_editadvanced_form.
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user_editadvanced_form extends moodleform {

    /**
     * Define the form.
     */
    public function definition() {
        global $USER, $CFG, $COURSE,$DB;

        $mform = $this->_form;
        $editoroptions = null;
        $filemanageroptions = null;
        $rolename = optional_param('role', 0, PARAM_RAW);  
        if (!is_array($this->_customdata)) {
            throw new coding_exception('invalid custom data for user_edit_form');
        }
        $editoroptions = $this->_customdata['editoroptions'];
        $filemanageroptions = $this->_customdata['filemanageroptions'];
        $user = $this->_customdata['user'];
        $userid = $user->id;

        // Accessibility: "Required" is bad legend text.
        if(is_siteadmin()){
            $strgeneral  = 'Add Account Manager';
        }elseif (user_has_role_assignment($USER->id, 9, SYSCONTEXTID)) {
            if($rolename == 'partner'){
                $strgeneral  = 'Add Partner';
            }elseif($rolename == 'mentor'){
                $strgeneral  = 'Add Mentor';
            }else{
                $strgeneral  = 'Add Partner';
            }
        }elseif (user_has_role_assignment($USER->id, 10, SYSCONTEXTID)) {
            $strgeneral  = 'Add Training Partner';
        }else{
            $strgeneral  = get_string('general');
        }
        $strrequired = get_string('required');

        // Add some extra hidden fields.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', core_user::get_property_type('id'));
        $mform->addElement('hidden', 'course', $COURSE->id);
        $mform->setType('course', PARAM_INT);

        // Print the required moodle fields first.
        if (!(user_has_role_assignment($USER->id, 10, SYSCONTEXTID))){ 
            $mform->addElement('header', 'moodle', $strgeneral);
        }else{
            $mform->addElement('header', 'moodle1', $strgeneral,array('stye' =>'display:none'));
        }
        $auths = core_component::get_plugin_list('auth');
        $enabled = get_string('pluginenabled', 'core_plugin');
        $disabled = get_string('plugindisabled', 'core_plugin');
        $authoptions = array($enabled => array(), $disabled => array());
        $cannotchangepass = array();
        $cannotchangeusername = array();
        foreach ($auths as $auth => $unused) {
            $authinst = get_auth_plugin($auth);

            if (!$authinst->is_internal()) {
                $cannotchangeusername[] = $auth;
            }

            $passwordurl = $authinst->change_password_url();
            if (!($authinst->can_change_password() && empty($passwordurl))) {
                if ($userid < 1 and $authinst->is_internal()) {
                    // This is unlikely but we can not create account without password
                    // when plugin uses passwords, we need to set it initially at least.
                } else {
                    //$cannotchangepass[] = $auth;
                }
            }
            if (is_enabled_auth($auth)) {
                $authoptions[$enabled][$auth] = get_string('pluginname', "auth_{$auth}");
            } else {
                $authoptions[$disabled][$auth] = get_string('pluginname', "auth_{$auth}");
            }
        }

        //category of partnership
        
            if (is_siteadmin()) {
                $mform->addElement('text', 'username', get_string('username'), 'size="20"');
                $mform->addHelpButton('username', 'username', 'auth');
                $mform->setType('username', PARAM_RAW);
                if ($userid !== -1) {
                    $mform->disabledIf('username', 'auth', 'in', $cannotchangeusername);
                }

                $mform->addElement('passwordunmask', 'newpassword', 'Password', 'size="20"');
                $mform->addHelpButton('newpassword', 'newpassword');
                $mform->setType('newpassword', core_user::get_property_type('password'));
                $mform->disabledIf('newpassword', 'createpassword', 'checked');
                $mform->disabledIf('newpassword', 'auth', 'in', $cannotchangepass);

                useredit_shared_definition($mform, $editoroptions, $filemanageroptions, $user);

                $mform->addElement('text', 'phone1', 'Mobile Number', 'maxlength="20" size="20"');
                $mform->setType('phone1', core_user::get_property_type('phone1'));
                $mform->setForceLtr('phone1');

                //user role check
                $mform->addElement('hidden', 'idnumber', get_string('idnumber'), 'maxlength="255" size="15"');
                $mform->setDefault('idnumber', 1);
                $mform->setType('idnumber', PARAM_RAW);

            }elseif (user_has_role_assignment($USER->id, 9, SYSCONTEXTID)) {
                $role  = required_param('role', PARAM_RAW); 
                if($role == 'partner'){
                    $choices = array('corporate'=>'Corporate','university'=>'University');
                    $select = $mform->addElement('select', 'institution', 'Category of Partnership', $choices);
                    $select->setSelected('university');
                }
                $mform->addElement('text', 'username', get_string('username'), 'size="20"');
                $mform->addHelpButton('username', 'username', 'auth');
                $mform->setType('username', PARAM_RAW);
                if ($userid !== -1) {
                    $mform->disabledIf('username', 'auth', 'in', $cannotchangeusername);
                }

                $mform->addElement('passwordunmask', 'newpassword', 'Password', 'size="20"');
                $mform->addHelpButton('newpassword', 'newpassword');
                $mform->setType('newpassword', core_user::get_property_type('password'));
                $mform->disabledIf('newpassword', 'createpassword', 'checked');
                $mform->disabledIf('newpassword', 'auth', 'in', $cannotchangepass);

                useredit_shared_definition($mform, $editoroptions, $filemanageroptions, $user);
                //mobile number
                $mform->addElement('text', 'phone1', 'Mobile Number', 'maxlength="20" size="15"');
                $mform->setType('phone1', core_user::get_property_type('phone1'));
                $mform->setForceLtr('phone1');

                if($role == 'partner'){
                   //authorised person
                    $mform->addElement('text', 'department', 'Authorized  Person', 'maxlength="255" size="15"');
                    $mform->setType('department', core_user::get_property_type('department'));
                    $mform->addRule('department', $strrequired, 'required', null, 'client');

                    //designation
                    $mform->addElement('text', 'msn', 'Designation', 'maxlength="50" size="15"');
                    $mform->setType('msn', core_user::get_property_type('msn'));
                    $mform->setForceLtr('msn');

                    //landline
                    $mform->addElement('text', 'phone2', 'Landline', 'maxlength="20" size="15"');
                    $mform->setType('phone2', core_user::get_property_type('phone2'));
                    

                    $mform->addElement('html', '<hr>');
                    //access permission for partners
                    $availablefromgroup=array();
                    $availablefromgroup[] =& $mform->createElement('checkbox', 'availablefromenabled', '', '');
                    $partner_name = $mform->createElement('text', 'partnername','', 'maxlength="20" size="15"');
                    $availablefromgroup[] =& $partner_name;
                    $mform->setType('partnername', PARAM_RAW);
                    $mform->setDefault('partnername', 'Partner');
                    $partner_name->freeze();
                    //$mform->disabledIf('availablefromgroup', 'availablefromenabled');
                    $mform->disabledIf('availablefromgroup2', 'availablefromenabled');
                    $mform->addGroup($availablefromgroup, 'availablefromgroup', 'Access Permission for Partner', ' ', false);

                    $availablefromgroup2=array();   
                    $availablefromgroup2[] =& $mform->createElement('advcheckbox', 'usermanageP', 'User Management',null, array('group' => 1));
                    $availablefromgroup2[] =& $mform->createElement('advcheckbox', 'reportmanageP', 'Report Management',null, array('group' => 1));
                    $availablefromgroup2[] =& $mform->createElement('advcheckbox', 'newsmanageP', 'News Management',null, array('group' => 1));
                    $availablefromgroup2[] =& $mform->createElement('advcheckbox', 'feedbackP', 'Feedback',null, array('group' => 1));
                    $this->add_checkbox_controller(1,'Select All / None');

                    $mform->addGroup($availablefromgroup2, 'availablefromgroup2', 'Allocate Access Permissions for the Partner', ' ', false);
                    $mform->addElement('html', '<p>.<hr></p>');
            
                    //permissions for training partner role
                    $availablefromgroup3=array();
                    $availablefromgroup3[] =& $mform->createElement('checkbox', 'availablefromenabled2', '', '');
                    $trainingpartner_name = $mform->createElement('text', 'trainingpartnername','', 'maxlength="20" size="15"');
                    $availablefromgroup3[] =& $trainingpartner_name;
                    $availablefromgroup3[] =& $mform->createElement('text', 'nooftpa','', 'maxlength="20" size="15"');
                    $mform->setType('trainingpartnername', PARAM_RAW);
                    $trainingpartner_name->freeze();
                    $mform->setDefault('trainingpartnername', 'Training Partner');
                    $mform->setType('nooftpa', PARAM_INT);
                    $mform->disabledIf('availablefromgroup3', 'availablefromenabled2');
                    $mform->disabledIf('availablefromgroup4', 'availablefromenabled2');
                    $mform->addGroup($availablefromgroup3, 'availablefromgroup3', 'Permissions for Training Partner Role', ' ', false);
                    $availablefromgroup4=array();
                    $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'usermanageTPA', 'User Management',null, array('group' => 2));
                    $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'badgemanageTPA', 'Badge Management',null, array('group' => 2));
                    $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'coursemanageTPA', 'Course Management',null, array('group' => 2));
                    $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'projectmanageTPA', 'Project Management',null, array('group' => 2));
                    $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'taskmanageTPA', 'Task Management',null, array('group' => 2));
                    $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'reportmanageTPA', 'Report Management',null, array('group' => 2));
                    $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'feedbackTPA', 'Feedback',null, array('group' => 2));
                    $this->add_checkbox_controller(2,'Select All / None');
                    $mform->addGroup($availablefromgroup4, 'availablefromgroup4', 'Allocate Access Permissions As Training Partner', ' ', false);   
                    $mform->addElement('html', '<hr>');


                    //permissions for hiring companies

                    $availablefromgroup11=array();

                    $availablefromgroup11[] =& $mform->createElement('checkbox', 'availablefromenabled3', '', '');
                    $hiring_company = $mform->createElement('text', 'hcname','', 'maxlength="20" size="15"');
                    $availablefromgroup11[] =& $hiring_company;
                    $availablefromgroup11[] =& $mform->createElement('text', 'noofhc','', 'maxlength="20" size="15"');
                    $mform->setType('hcname', PARAM_RAW);
                    $mform->setDefault('hcname', 'Hiring Company');
                    $hiring_company->freeze();
                    $mform->setType('noofhc', PARAM_INT);
                    $mform->disabledIf('availablefromgroup11', 'availablefromenabled3');
                    $mform->disabledIf('availablefromgroup12', 'availablefromenabled3');
                    $mform->addGroup($availablefromgroup11, 'availablefromgroup11', 'Permissions for Hiring Company Role', ' ', false);
                    $availablefromgroup12=array();
                    $availablefromgroup12[] =& $mform->createElement('advcheckbox', 'jobmanageHC', 'Job Management',null, array('group' => 3));
                    $availablefromgroup12[] =& $mform->createElement('advcheckbox', 'reportmanageHC', 'Report Management',null, array('group' => 3));
                    $availablefromgroup12[] =& $mform->createElement('advcheckbox', 'feedbackHC', 'Feedback',null, array('group' => 3));
                    $this->add_checkbox_controller(3,'Select All / None');
                    $mform->addGroup($availablefromgroup12, 'availablefromgroup12', 'Allocate Access Permissions As Hiring Company', ' ', false);   
                    $mform->addElement('html', '<hr>');

                    //permissions for students of Partners

                    $availablefromgroup21=array();

                    $availablefromgroup21[] =& $mform->createElement('checkbox', 'availablefromenabled4', '', '');
                    $student_name = $mform->createElement('text', 'stuname','','maxlength="20" size="15"');
                    $availablefromgroup21[] =& $student_name;
                    $availablefromgroup21[] =& $mform->createElement('text', 'noofstu','', 'maxlength="20" size="15"');
                    $mform->setType('stuname', PARAM_RAW);
                    $mform->setDefault('stuname', 'Student');
                    $student_name->freeze();
                    $mform->setType('noofstu', PARAM_INT);
                    $mform->disabledIf('availablefromgroup21', 'availablefromenabled4');
                    $mform->disabledIf('availablefromgroup22', 'availablefromenabled4');
                    $mform->addGroup($availablefromgroup21, 'availablefromgroup21', 'Permissions for Students of Partners', ' ', false);
                    $availablefromgroup22=array();
                    $availablefromgroup22[] =& $mform->createElement('advcheckbox', 'coursemanageSTU', 'Course Management',null, array('group' => 4));
                    $availablefromgroup22[] =& $mform->createElement('advcheckbox', 'labsmanageSTU', 'Labs Management',null, array('group' => 4));
                    $availablefromgroup22[] =& $mform->createElement('advcheckbox', 'projectmanageSTU', 'Project Management',null, array('group' => 4));
                    $availablefromgroup22[] =& $mform->createElement('advcheckbox', 'assessmanageSTU', 'Assessment Management',null, array('group' => 4));
                    $availablefromgroup22[] =& $mform->createElement('advcheckbox', 'taskmanageSTU', 'Task Management',null, array('group' => 4));
                    $availablefromgroup22[] =& $mform->createElement('advcheckbox', 'jobmanageSTU', 'Job Management',null, array('group' => 4));
                    $availablefromgroup22[] =& $mform->createElement('advcheckbox', 'resumeSTU', 'Resume Analyzer',null, array('group' => 4));
                    $availablefromgroup22[] =& $mform->createElement('advcheckbox', 'vcmanageSTU', 'Video Conferences',null, array('group' => 4));
                    $availablefromgroup22[] =& $mform->createElement('advcheckbox', 'mentormanageSTU', 'Mentor Management',null, array('group' => 4));
                    $availablefromgroup22[] =& $mform->createElement('advcheckbox', 'accountmanageSTU', 'Account Management',null, array('group' => 4));
                    $availablefromgroup22[] =& $mform->createElement('advcheckbox', 'feedbackSTU', 'Feedback',null, array('group' => 4));
                    $this->add_checkbox_controller(4,'Select All / None');
                    $mform->addGroup($availablefromgroup22, 'availablefromgroup22', 'Access Permissions for Students', ' ', false);   
                    $mform->addElement('html', '<hr>');

                    //permissions for Professors of Partners

                    $availablefromgroup31=array();

                    $availablefromgroup31[] =& $mform->createElement('checkbox', 'availablefromenabled5', '', '');
                    $professor_name = $mform->createElement('text', 'profname','','maxlength="20" size="15"');
                    $availablefromgroup31[] =& $professor_name;
                    $availablefromgroup31[] =& $mform->createElement('text', 'noofprof','', 'maxlength="20" size="15"');
                    $mform->setType('profname', PARAM_RAW);
                    $mform->setDefault('profname', 'Professor');
                    $professor_name->freeze();
                    $mform->setType('noofprof', PARAM_INT);
                    $mform->disabledIf('availablefromgroup31', 'availablefromenabled5');
                    $mform->disabledIf('availablefromgroup32', 'availablefromenabled5');
                    $mform->addGroup($availablefromgroup31, 'availablefromgroup31', 'Permissions for Professors of Partners', ' ', false);
                    $availablefromgroup32=array();
                    $availablefromgroup32[] =& $mform->createElement('advcheckbox', 'badgemanagePROF', 'Badge Management',null, array('group' => 5));
                    $availablefromgroup32[] =& $mform->createElement('advcheckbox', 'coursemanagePROF', 'Course Management',null, array('group' => 5));
                    $availablefromgroup32[] =& $mform->createElement('advcheckbox', 'labsmanagePROF', 'Labs Management',null, array('group' => 5));
                    $availablefromgroup32[] =& $mform->createElement('advcheckbox', 'projectmanagePROF', 'Project Management',null, array('group' => 5));
                    $availablefromgroup32[] =& $mform->createElement('advcheckbox', 'assessmanagePROF', 'Assessment Management',null, array('group' => 5));
                    $availablefromgroup32[] =& $mform->createElement('advcheckbox', 'taskmanagePROF', 'Task Management',null, array('group' => 5));
                    $availablefromgroup32[] =& $mform->createElement('advcheckbox', 'vcmanagePROF', 'Video Conferences',null, array('group' => 5));
                    $availablefromgroup32[] =& $mform->createElement('advcheckbox', 'reportmanagePROF', 'Report Management',null, array('group' => 5));
                    $availablefromgroup32[] =& $mform->createElement('advcheckbox', 'feedbackPROF', 'Feedback',null, array('group' => 5));
                    $this->add_checkbox_controller(5,'Select All / None');
                    $mform->addGroup($availablefromgroup32, 'availablefromgroup32', 'Access Permissions for Professors', ' ', false); 
                }elseif($role == 'mentor'){
                    //access permission for partners
                    $availablefromgroup=array();
                    $availablefromgroup[] =& $mform->createElement('checkbox', 'availablefromenabled', '', '');
                    $mentor_name = $mform->createElement('text', 'mentorname','', 'maxlength="20" size="15"');
                    $availablefromgroup[] =& $mentor_name;
                    $mform->setType('mentorname', PARAM_RAW);
                    $mform->setDefault('mentorname', 'Mentor');
                    $mentor_name->freeze();
                    //$mform->disabledIf('availablefromgroup', 'availablefromenabled');
                    $mform->disabledIf('availablefromgroup2', 'availablefromenabled');
                    $mform->addGroup($availablefromgroup, 'availablefromgroup', 'Access Permission for Mentor', ' ', false);

                    $availablefromgroup2=array();   
                    $availablefromgroup2[] =& $mform->createElement('advcheckbox', 'usermanageM', 'User Management',null, array('group' => 1));
                    $availablefromgroup2[] =& $mform->createElement('advcheckbox', 'reportmanageM', 'Report Management',null, array('group' => 1));
                    $availablefromgroup2[] =& $mform->createElement('advcheckbox', 'newsmanageM', 'News Management',null, array('group' => 1));
                    $availablefromgroup2[] =& $mform->createElement('advcheckbox', 'feedbackM', 'Feedback',null, array('group' => 1));
                    $this->add_checkbox_controller(1,'Select All / None');

                    $mform->addGroup($availablefromgroup2, 'availablefromgroup2', 'Allocate Access Permissions for the Mentor', ' ', false);
                    $mform->addElement('html', '<p>.<hr></p>');
            
                }
                //user role check
                $mform->addElement('hidden', 'idnumber', get_string('idnumber'), 'maxlength="255" size="15"');
                $mform->setDefault('idnumber', 9);
                $mform->setType('idnumber', PARAM_RAW);

                $mform->addElement('hidden', 'role', 'Role', 'maxlength="255" size="15"');
                $mform->setDefault('role', $role);
                $mform->setType('role', PARAM_RAW);
                
            }elseif (user_has_role_assignment($USER->id, 10, SYSCONTEXTID)) { //partner admin user creation form
                //checking current user's permissions assigned by account manager on various roles
                if($DB->record_exists('partners',array('userid'=>$USER->id))){
                    $permission_for_tpa = $DB->get_record('partners',array('userid'=>$USER->id),'id,tp_permission,stud_permission,prof_permission,hc_permission');
                }
                $html1 ='<div id="exTab3" class=""> 
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a  href="#1" data-toggle="tab">Training Partner</a>
                                    </li>
                                    <li>
                                        <a href="#2" data-toggle="tab">Hiring Company</a>
                                    </li>
                                </ul>
                                <div class="tab-content ">
                                    <div class="tab-pane active trainingpartner_tab" id="1">';
                $mform->addElement('html', $html1);
                $mform->addElement('text', 'username', get_string('username'), 'size="15"');
                $mform->addHelpButton('username', 'username', 'auth');
                $mform->setType('username', PARAM_RAW);
                if ($userid !== -1) {
                    $mform->disabledIf('username', 'auth', 'in', $cannotchangeusername);
                }

                $mform->addElement('passwordunmask', 'newpassword', 'Password', 'size="15"');
                $mform->addHelpButton('newpassword', 'newpassword');
                $mform->setType('newpassword', core_user::get_property_type('password'));
                $mform->disabledIf('newpassword', 'createpassword', 'checked');
                $mform->disabledIf('newpassword', 'auth', 'in', $cannotchangepass);

                useredit_shared_definition($mform, $editoroptions, $filemanageroptions, $user);
                //mobile number
                $mform->addElement('text', 'phone1', 'Mobile Number', 'maxlength="20" size="15"');
                $mform->setType('phone1', core_user::get_property_type('phone1'));
                $mform->setForceLtr('phone1');

                
                //authorised person
                $mform->addElement('text', 'department', 'Authorized  Person', 'maxlength="255" size="15"');
                $mform->setType('department', PARAM_RAW);

                $mform->addElement('text', 'institution', 'Authorized  Person Email', 'maxlength="255" size="15"');
                $mform->setType('institution', PARAM_RAW);

                $mform->addElement('text', 'url', 'Training Partners / College Website URL', 'maxlength="255" size="15"');
                $mform->setType('url', PARAM_RAW);

                //designation
                $mform->addElement('text', 'msn', 'Designation', 'maxlength="50" size="15"');
                $mform->setType('msn',PARAM_RAW);
                $mform->setForceLtr('msn');


                //landline
                $mform->addElement('text', 'phone2', 'Landline', 'maxlength="20" size="15"');
                $mform->setType('phone2', PARAM_INT);
                $mform->setForceLtr('phone2');  

                $mform->addElement('html', '<hr>');


                //max students, max professors, total number of itrack users
                $mform->addElement('text', 'maxavailStud', 'Max. Students', array('maxlength'=>'20','size'=>'15','placeholder'=>'100'));
                $mform->setType('maxavailStud', PARAM_INT);
                $mform->addElement('text', 'maxavailProf', 'Max. Professors', array('maxlength'=>'20','size'=>'15','placeholder'=>'100'));
                $mform->setType('maxavailProf', PARAM_INT);
                $tot = $mform->addElement('text', 'itracktotal', 'Total Number of iTrack Users', 'maxlength="20" size="15"');
                $mform->setType('itracktotal', PARAM_INT);
                $mform->setDefault('itracktotal',0);
                $tot->freeze();
                $mform->addElement('html', '<hr>');
                //Allocate Access Permissions for This Training Partner/College
                
                $availablefromgroup4=array();
                $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'usermanageTPA', 'User Management',null, array('group' => 2));
                $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'badgemanageTPA', 'Badge Management',null, array('group' => 2));
                $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'coursemanageTPA', 'Course Management',null, array('group' => 2));
                $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'projectmanageTPA', 'Project Management',null, array('group' => 2));
                $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'taskmanageTPA', 'Task Management',null, array('group' => 2));
                $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'reportmanageTPA', 'Report Management',null, array('group' => 2));
                $availablefromgroup4[] =& $mform->createElement('advcheckbox', 'feedbackTPA', 'Feedback',null, array('group' => 2));
                $mform->addGroup($availablefromgroup4, 'availablefromgroup4', 'Allocated Access Permissions for This Training Partner / College', ' ', false);   
                $mform->addElement('html', '<hr>');

                $tpa_permission_check = explode(",", $permission_for_tpa->tp_permission);
                if($tpa_permission_check[0] == 1){
                   $mform->setDefault('usermanageTPA', true); 
                }
                if($tpa_permission_check[1] == 1){
                   $mform->setDefault('badgemanageTPA', true); 
                }
                if($tpa_permission_check[2] == 1){
                   $mform->setDefault('coursemanageTPA', true); 
                }
                if($tpa_permission_check[3] == 1){
                   $mform->setDefault('projectmanageTPA', true); 
                }
                if($tpa_permission_check[4] == 1){
                   $mform->setDefault('taskmanageTPA', true); 
                }
                if($tpa_permission_check[5] == 1){
                   $mform->setDefault('reportmanageTPA', true); 
                }
                if($tpa_permission_check[6] == 1){
                   $mform->setDefault('feedbackTPA', true); 
                }
                $mform->disabledIf('usermanageTPA','');
                $mform->disabledIf('badgemanageTPA','');
                $mform->disabledIf('coursemanageTPA','');
                $mform->disabledIf('projectmanageTPA','');
                $mform->disabledIf('taskmanageTPA','');
                $mform->disabledIf('reportmanageTPA','');
                $mform->disabledIf('feedbackTPA','');
                //Allocate Access Permissions for Student by Account Manager
                $availablefromgroup5=array();
                $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'coursemanageSTU', 'Course Management',null, array('group' => 5));
                $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'labsmanageSTU', 'Labs Management',null, array('group' => 5));
                $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'projectemanageSTU', 'Project Management',null, array('group' => 5));
                $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'assessmanageSTU', 'Assessment Management',null, array('group' => 5));
                $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'taskmanageSTU', 'Task Management',null, array('group' => 5));
                $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'jobmanageSTU', 'Job Management',null, array('group' => 5));
                $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'resumeanalyzerSTU', 'Resume Analyzer',null, array('group' => 5));
                $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'vcmanageSTU', 'Video Conferences',null, array('group' => 5));
                $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'mentormanageSTU', 'Mentor Management',null, array('group' => 5));
                $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'accountmanageSTU', 'Account Management',null, array('group' => 5));
                $availablefromgroup5[] =& $mform->createElement('advcheckbox', 'feedbackSTU', 'Feedback',null, array('group' => 5));
                $mform->addGroup($availablefromgroup5, 'availablefromgroup5', 'Allocated Access Permissions for Student - By Account Manager', ' ', false);   
                $mform->addElement('html', '<hr>');

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
                   $mform->setDefault('vcmanageSTU', true); 
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
                $mform->disabledIf('vcmanageSTU','');
                $mform->disabledIf('mentormanageSTU','');
                $mform->disabledIf('accountmanageSTU','');
                $mform->disabledIf('feedbackSTU','');

                //Allocate Access Permissions for Professor By Account Manager
                $availablefromgroup6=array();
                $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'badgemanagePROF', 'Badge Management',null, array('group' => 6));
                $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'coursemanagePROF', 'Course Management',null, array('group' => 6));
                $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'labmanagePROF', 'Labs Management',null, array('group' => 6));
                $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'projectmanagePROF', 'Project Management',null, array('group' => 6));
                $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'assessmanagePROF', 'Assessmnet Management',null, array('group' => 6));
                $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'taskmanagePROF', 'Task Management',null, array('group' => 6));
                $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'vcmanagePROF', 'Video Conferences',null, array('group' => 6));
                $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'reportmanagePROF', 'Report Management',null, array('group' => 6));
                $availablefromgroup6[] =& $mform->createElement('advcheckbox', 'feedbackPROF', 'Feedback',null, array('group' => 6));
                $mform->addGroup($availablefromgroup6, 'availablefromgroup6', 'Allocated Access Permissions for Professor - By Account Manager', ' ', false);   
                $mform->addElement('html', '<hr>');
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
                    $mform->setDefault('projectmanagePROF', true); 
                }
                if($prof_permission_check[4] == 1){
                    $mform->setDefault('assessmanagePROF', true); 
                }
                if($prof_permission_check[5] == 1){
                    $mform->setDefault('taskmanagePROF', true); 
                }
                if($prof_permission_check[6] == 1){
                    $mform->setDefault('vcmanagePROF', true); 
                }
                if($prof_permission_check[7] == 1){
                    $mform->setDefault('reportmanagePROF', true); 
                }
                if($prof_permission_check[8] == 1){
                    $mform->setDefault('feedbackPROF', true); 
                }
                $mform->disabledIf('badgemanagePROF','');
                $mform->disabledIf('coursemanagePROF','');
                $mform->disabledIf('labmanagePROF','');
                $mform->disabledIf('projectmanagePROF','');
                $mform->disabledIf('assessmanagePROF','');
                $mform->disabledIf('taskmanagePROF','');
                $mform->disabledIf('vcmanagePROF','');
                $mform->disabledIf('reportmanagePROF','');
                $mform->disabledIf('feedbackPROF','');
                
                //user role check
                $mform->addElement('hidden', 'idnumber', get_string('idnumber'), 'maxlength="255" size="15"');
                $mform->setDefault('idnumber', 10);
                $mform->setType('idnumber', PARAM_RAW);

                $mform->addElement('hidden', 'ishc', 'is hiring company', 'maxlength="255" size="15"');
                $mform->setDefault('ishc', 0);
                $mform->setType('ishc', PARAM_INT);

                $html3 = '</div><div class="tab-pane hiringcompany_tab" id="2">';
                $mform->addElement('html', $html3);
                //hiring company form starts
                $mform->addElement('text', 'firstnamehc', 'Hiring Company\'s Name', 'maxlength="255" size="15"');
                $mform->setType('firstnamehc', PARAM_RAW);
                //authorised person
                $mform->addElement('text', 'departmenthc', 'Hiring Company\'s Authorized Person Name', 'maxlength="255" size="15"');
                $mform->setType('departmenthc', PARAM_RAW);

                $mform->addElement('text', 'urlhc', 'Hiring Company\'s Website URL', 'maxlength="255" size="15"');
                $mform->setType('urlhc', PARAM_RAW);

                //Hiring Division's Official Email
                $mform->addElement('text', 'msnhc', 'Hiring Division\'s Official Email', 'maxlength="50" size="15"');
                $mform->setType('msnhc',PARAM_RAW);
                $mform->setForceLtr('msnhc');
                //mobile number
                $mform->addElement('text', 'phone1hc', 'Authorized Person\'s Mobile Number', 'maxlength="20" size="15"');
                $mform->setType('phone1hc', PARAM_INT);
                $mform->setForceLtr('phone1hc');

                $mform->addElement('text', 'usernamehc', 'iTrack Username for Hiring' , 'size="15"');
                $mform->addHelpButton('usernamehc', 'username', 'auth');
                $mform->setType('usernamehc', PARAM_RAW);
                if ($userid !== -1) {
                    $mform->disabledIf('usernamehc', 'auth', 'in', $cannotchangeusername);
                }
                //landline
                $mform->addElement('text', 'phone2hc', 'Hiring Company\'s Official Landline', 'maxlength="20" size="15"');
                $mform->setType('phone2hc', PARAM_INT);
                $mform->setForceLtr('phone2hc');  

                $mform->addElement('text', 'institutionhc', 'Authorized  Person Email', 'maxlength="255" size="15"');
                $mform->setType('institutionhc', PARAM_RAW);

                $mform->addElement('passwordunmask', 'newpasswordhc', 'iTrack Password for Hiring', 'size="20"');
                $mform->setType('newpasswordhc', PARAM_RAW);
                $mform->disabledIf('newpasswordhc', 'createpassword', 'checked');
                $mform->disabledIf('newpasswordhc', 'auth', 'in', $cannotchangepass);
                $mform->addElement('html', '<hr>');

                //Allocate Access Permissions for This Training Partner/College

                $availablefromgroup7=array();
                $availablefromgroup7[] =& $mform->createElement('advcheckbox', 'jobmanageHC', 'Job Management',null, array('group' => 7));
                $availablefromgroup7[] =& $mform->createElement('advcheckbox', 'reportmanageHC', 'Report Management',null, array('group' => 7));
                $availablefromgroup7[] =& $mform->createElement('advcheckbox', 'feedbackHC', 'Feedback',null, array('group' =>7));
                $mform->addGroup($availablefromgroup7, 'availablefromgroup7', 'Allocated Access Permissions for This Hiring', ' ', false);   
                $mform->addElement('html', '<hr>');
                $hc_permission_check = explode(",", $permission_for_tpa->hc_permission);
                if($hc_permission_check[0] == 1){
                   $mform->setDefault('jobmanageHC', true); 
                }
                if($hc_permission_check[1] == 1){
                   $mform->setDefault('reportmanageHC', true); 
                }
                if($hc_permission_check[2] == 1){
                   $mform->setDefault('feedbackHC', true); 
                }
                $mform->disabledIf('jobmanageHC','');
                $mform->disabledIf('reportmanageHC','');
                $mform->disabledIf('feedbackHC','');
                //user role check
                $mform->addElement('hidden', 'idnumberhc', get_string('idnumber'), 'maxlength="255" size="15"');
                $mform->setDefault('idnumberhc', 11);
                $mform->setType('idnumberhc', PARAM_RAW);

                $mform->addElement('hidden', 'ishc', 'is hiring company', 'maxlength="255" size="15"');
                $mform->setDefault('ishc', 0);
                $mform->setType('ishc', PARAM_INT);

                $html4 = '</div></div>';
                $mform->addElement('html', $html4);
            }elseif (user_has_role_assignment($USER->id, 11, SYSCONTEXTID)) { 
                
            }
        

        // Next the customisable profile fields.
        //profile_definition($mform, $userid);
        //arjun-itrack
        if ($userid == -1) { 
            //$btnstring = get_string('createuser');
            $btnstring = 'Submit';
        } else {
            $btnstring = get_string('updatemyprofile');
        }

        $this->add_action_buttons(false, $btnstring);

        $this->set_data($user);
    }

    /**
     * Extend the form definition after data has been parsed.
     */
    public function definition_after_data() {
        global $USER, $CFG, $DB, $OUTPUT;

        $mform = $this->_form;

        // Trim required name fields.
        foreach (useredit_get_required_name_fields() as $field) {
            $mform->applyFilter($field, 'trim');
        }

        if ($userid = $mform->getElementValue('id')) {
            $user = $DB->get_record('user', array('id' => $userid));
        } else {
            $user = false;
        }

        // User can not change own auth method.
        // if ($userid == $USER->id) {
        //     $mform->hardFreeze('auth');
        //     $mform->hardFreeze('preference_auth_forcepasswordchange');
        // }

        // Admin must choose some password and supply correct email.
        if (!empty($USER->newadminuser)) {
            $mform->addRule('newpassword', get_string('required'), 'required', null, 'client');
            if ($mform->elementExists('suspended')) {
                $mform->removeElement('suspended');
            }
        }

        // Require password for new users.
        if ($userid > 0) {
            if ($mform->elementExists('createpassword')) {
                $mform->removeElement('createpassword');
            }
        }

        if ($user and is_mnet_remote_user($user)) {
            // Only local accounts can be suspended.
            if ($mform->elementExists('suspended')) {
                $mform->removeElement('suspended');
            }
        }
        if ($user and ($user->id == $USER->id or is_siteadmin($user))) {
            // Prevent self and admin mess ups.
            if ($mform->elementExists('suspended')) {
                $mform->hardFreeze('suspended');
            }
        }

        // Print picture.
        /*if (empty($USER->newadminuser)) {
            if ($user) {
                $context = context_user::instance($user->id, MUST_EXIST);
                $fs = get_file_storage();
                $hasuploadedpicture = ($fs->file_exists($context->id, 'user', 'icon', 0, '/', 'f2.png') || $fs->file_exists($context->id, 'user', 'icon', 0, '/', 'f2.jpg'));
                if (!empty($user->picture) && $hasuploadedpicture) {
                    $imagevalue = $OUTPUT->user_picture($user, array('courseid' => SITEID, 'size' => 64));
                } else {
                    $imagevalue = get_string('none');
                }
            } else {
                $imagevalue = get_string('none');
            }
            $imageelement = $mform->getElement('currentpicture');
            $imageelement->setValue($imagevalue);

            if ($user && $mform->elementExists('deletepicture') && !$hasuploadedpicture) {
                $mform->removeElement('deletepicture');
            }
        }*/

        // Next the customisable profile fields.
        profile_definition_after_data($mform, $userid);
    }

    /**
     * Validate the form data.
     * @param array $usernew
     * @param array $files
     * @return array|bool
     */
    public function validation($usernew, $files) {
        global $CFG, $DB;

        $usernew = (object)$usernew;
        $usernew->username = trim($usernew->username);

        $user = $DB->get_record('user', array('id' => $usernew->id));
        $err = array();

        if (!$user and !empty($usernew->createpassword)) {
            if ($usernew->suspended) {
                // Show some error because we can not mail suspended users.
                $err['suspended'] = get_string('error');
            }
        } else {
            if (!empty($usernew->newpassword)) {
                $errmsg = ''; // Prevent eclipse warning.
                if (!check_password_policy($usernew->newpassword, $errmsg)) {
                    $err['newpassword'] = $errmsg;
                }
            } 
            // else if (!$user) {
            //     $auth = get_auth_plugin($usernew->auth);
            //     if ($auth->is_internal()) {
            //         // Internal accounts require password!
            //         $err['newpassword'] = get_string('required');
            //     }
            // }
        }

        if (empty($usernew->username)) {
            // Might be only whitespace.
            $err['username'] = get_string('required');
        } else if (!$user or $user->username !== $usernew->username) {
            // Check new username does not exist.
            if ($DB->record_exists('user', array('username' => $usernew->username, 'mnethostid' => $CFG->mnet_localhost_id))) {
                $err['username'] = get_string('usernameexists');
            }
            // Check allowed characters.
            if ($usernew->username !== core_text::strtolower($usernew->username)) {
                $err['username'] = get_string('usernamelowercase');
            } else {
                if ($usernew->username !== core_user::clean_field($usernew->username, 'username')) {
                    $err['username'] = get_string('invalidusername');
                }
            }
        }

        if (!$user or (isset($usernew->email) && $user->email !== $usernew->email)) {
            if (!validate_email($usernew->email)) {
                $err['email'] = get_string('invalidemail');
            } else if (empty($CFG->allowaccountssameemail)
                    and $DB->record_exists('user', array('email' => $usernew->email, 'mnethostid' => $CFG->mnet_localhost_id))) {
                $err['email'] = get_string('emailexists');
            }
        }

        // Next the customisable profile fields.
        $err += profile_validation($usernew, $files);

        if (count($err) == 0) {
            return true;
        } else {
            return $err;
        }
    }
    function add_checkbox_controller($groupid, $text = null, $attributes = null, $originalValue = 0) {
        global $CFG, $PAGE;

        // Name of the controller button
        $checkboxcontrollername = 'nosubmit_checkbox_controller' . $groupid;
        $checkboxcontrollerparam = 'checkbox_controller'. $groupid;
        $checkboxgroupclass = 'checkboxgroup'.$groupid;

        // Set the default text if none was specified
        if (empty($text)) {
            $text = get_string('selectallornone', 'form');
        }

        $mform = $this->_form;
        $selectvalue = optional_param($checkboxcontrollerparam, null, PARAM_INT);
        $contollerbutton = optional_param($checkboxcontrollername, null, PARAM_ALPHAEXT);

        $newselectvalue = $selectvalue;
        if (is_null($selectvalue)) {
            $newselectvalue = $originalValue;
        } else if (!is_null($contollerbutton)) {
            $newselectvalue = (int) !$selectvalue;
        }
        // set checkbox state depending on orignal/submitted value by controoler button
        if (!is_null($contollerbutton) || is_null($selectvalue)) {
            foreach ($mform->_elements as $element) {
                if (($element instanceof MoodleQuickForm_advcheckbox) &&
                        $element->getAttribute('class') == $checkboxgroupclass &&
                        !$element->isFrozen()) {
                    $mform->setConstants(array($element->getName() => $newselectvalue));
                }
            }
        }

        $mform->addElement('hidden', $checkboxcontrollerparam, $newselectvalue, array('id' => "id_".$checkboxcontrollerparam));
        $mform->setType($checkboxcontrollerparam, PARAM_INT);
        $mform->setConstants(array($checkboxcontrollerparam => $newselectvalue));

        $PAGE->requires->yui_module('moodle-form-checkboxcontroller', 'M.form.checkboxcontroller',
                array(
                    array('groupid' => $groupid,
                        'checkboxclass' => $checkboxgroupclass,
                        'checkboxcontroller' => $checkboxcontrollerparam,
                        'controllerbutton' => $checkboxcontrollername)
                    )
                );

        require_once("$CFG->libdir/form/submit.php");
        $submitlink = new MoodleQuickForm_submit($checkboxcontrollername, $attributes);
        $mform->addElement($submitlink);
        $mform->registerNoSubmitButton($checkboxcontrollername);
        $mform->setDefault($checkboxcontrollername, $text);
    }
}


