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
 * @package    local_assign_course
 * @copyright  Prashant Yallatti<prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); /// It must be included from a Moodle page
}
require_once($CFG->libdir.'/formslib.php');
class local_assign_course_form extends moodleform {
    function definition() {
        global $CFG,$DB,$USER,$PAGE,$OUTPUT;
        $crsid = optional_param('crsid', 0 ,PARAM_INT);
        $mform =& $this->_form;
        $users = array();
        $courses = $DB->get_records('course');
        unset($courses[1]);
        $course = [];
        foreach($courses as $key=>$course1){
            $category = $DB->get_record('course_categories',array('id'=>$course1->category));
            $course[$course1->id] = $category->name.'  -  '.$course1->fullname;
            unset($course[1]);
        }
        //echo '<label class="col-form-label d-inline ">'.get_string('course').'</label>';
        //echo $OUTPUT->single_select(new moodle_url($CFG->wwwroot . '/local/assign_course/assign_course.php'), 'crsid', $course, $crsid,'Select course');
        $mform->addElement('select', 'courses','Courses',$course ,array('id'=>'id_courses'));
        if(is_siteadmin()) {
            $users = $DB->get_records('user');
        } else {
            $sql = "SELECT u.id,u.username,u.firstname,u.lastname
            from {user} u
            LEFT join {user_info_data} un
            on u.id = un.userid 
            where data = '$USER->username'";
            $users = $DB->get_records_sql($sql);
        }
        //print_object($sql1);
        $usersnotassigned = array();
        // foreach ($users as $user) {
        //     if(!(user_has_role_assignment($user->id, 1, SYSCONTEXTID) || user_has_role_assignment($user->id, 9, SYSCONTEXTID) || user_has_role_assignment($user->id, 10, SYSCONTEXTID))) {
        //         $exists = $DB->get_record('local_assign_course',array('userid'=>$user->id,'courseid'=>$crsid));
        //         if(!$exists){
        //             $usersnotassigned[$user->id.','.$user->username] = $user->username.'  -  '.$user->firstname.' '.$user->lastname;
        //             unset( $usersnotassigned['1,'.$user->username]);
        //             unset( $usersnotassigned['2,'.$user->username]); 
        //         }
        //     }  
        // }
        $mform->addElement('hidden', 'crsid', $crsid);
        $mform->setType('crsid', PARAM_INT);

        $select = $mform->addElement('searchableselector', 'users',get_string('name'), $usersnotassigned,array('id'=>'id_users'));
        $select->setMultiple(true);
        $mform->addElement('date_selector', 'end_date', get_string('edate','local_assign_course'));
        $this->add_action_buttons();

    }
}
