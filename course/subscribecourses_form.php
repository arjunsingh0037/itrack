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
 * Edit category form.
 *
 * @package core_course
 * @copyright 2002 onwards Martin Dougiamas (http://dougiamas.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/coursecatlib.php');

/**
 * Edit category form.
 *
 * @package core_course
 * @copyright 2002 onwards Martin Dougiamas (http://dougiamas.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class subscribecourses_form extends moodleform {

    /**
     * The form definition.
     */
    public function definition() {
        global $CFG, $DB, $USER;
        $mform = $this->_form;
        //print_object($users);die();
        $mform->addElement('header', 'moodle2', 'ASSIGN COURSE TO TRAINING PARTNER');
        $from_partner_category = array(
            'university' => 'University',
            'corporate' => 'Corporate'
        );
        //course to be given from
        $select = $mform->addElement('select', 'from_partner', 'Partner Category', $from_partner_category,array('id'=>'from_pcat'));
        $mform->addRule('from_partner', get_string('required'), 'required', null, 'server');

        $from_partner_name = array();
        $select = $mform->addElement('select', 'from_partnername', 'Partner Name', $from_partner_name ,array('id'=>'from_pname'));
        $mform->addRule('from_partnername', get_string('required'), 'required', null, 'server');

        $from_training_partner_name = array();
        $select = $mform->addElement('select', 'from_tppartnername', 'Training Partner Name', $from_training_partner_name,array('id'=>'from_tpname'));
        $mform->addRule('from_tppartnername', get_string('required'), 'required', null, 'server');

        $from_courses = array();
        $select = $mform->addElement('select', 'from_course', 'Select Course', $from_courses,array('id'=>'from_course'));
        $mform->addRule('from_course', get_string('required'), 'required', null, 'server');

        //course to be given to
        $to_partner_category = array(
            'university' => 'University',
            'corporate' => 'Corporate'
        );
        $select = $mform->addElement('select', 'to_partner', 'Course Assign To Partner Category', $to_partner_category,array('id'=>'to_pcat'));
        $mform->addRule('to_partner', get_string('required'), 'required', null, 'server');

        
        $to_partner_name = array();
        $select = $mform->addElement('select', 'to_partnername', 'Course Assign To Partner Name', $to_partner_name,array('id'=>'to_pname'));
        $mform->addRule('to_partnername', get_string('required'), 'required', null, 'server');

        $to_training_partner_name = array();
        $select = $mform->addElement('select', 'to_tppartnername', 'Course Assign To Training Partner Name', $to_training_partner_name,array('id'=>'to_tpname'));
        $mform->addRule('to_tppartnername', get_string('required'), 'required', null, 'server');
        
        $date_options = array(
                            'startyear' => 2018, 
                            'stopyear'  => 2020,
                            'timezone'  => 99,
                            'optional'  => false
                        );
        $mform->addElement('date_selector', 'assignedfrom', 'Course Valid From',$date_options);
        $mform->addRule('assignedfrom', get_string('required'), 'required', null, 'server');

        $mform->addElement('date_selector', 'assignedtill', 'Course Valid Upto',$date_options);
        $mform->addRule('assignedtill', get_string('required'), 'required', null, 'server');

        /*$mform->addElement('hidden', 'idnumber', get_string('idnumbercoursecategory'), 'maxlength="100" size="10"');
        $mform->addHelpButton('idnumber', 'idnumbercoursecategory');
        $mform->setType('idnumber', PARAM_RAW);

        $total_categories= $DB->get_records('course_categories');
        $count = count($total_categories);
        $mform->setDefault('idnumber',$count.'-'.$USER->id);

        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $categoryid);*/

        $this->add_action_buttons(false, 'Assign Course');
    }

    /**
     * Returns the description editor options.
     * @return array
     */
    public function get_description_editor_options() {
        global $CFG;
        $context = $this->_customdata['context'];
        $itemid = $this->_customdata['itemid'];
        return array(
            'maxfiles'  => EDITOR_UNLIMITED_FILES,
            'maxbytes'  => $CFG->maxbytes,
            'trusttext' => true,
            'context'   => $context,
            'subdirs'   => file_area_contains_subdirs($context, 'coursecat', 'description', $itemid),
        );
    }

    /**
     * Validates the data submit for this form.
     *
     * @param array $data An array of key,value data pairs.
     * @param array $files Any files that may have been submit as well.
     * @return array An array of errors.
     */
    public function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);
        if (!empty($data['idnumber'])) {
            if ($existing = $DB->get_record('course_categories', array('idnumber' => $data['idnumber']))) {
                if (!$data['id'] || $existing->id != $data['id']) {
                    $errors['idnumber'] = get_string('categoryidnumbertaken', 'error');
                }
            }
        }
        return $errors;
    }
}
