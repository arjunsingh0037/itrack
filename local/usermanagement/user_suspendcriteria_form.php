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
 * local_usermanagement plugin
 *
 * @package    local_courserecomendation
 * @copyright  2016 lmsofindia
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("$CFG->libdir/formslib.php");

class user_suspendcriteria_form extends moodleform {

    //Add elements to form
    public function definition() {
        global $DB, $CFG;
        
        $mform = $this->_form; // Don't forget the underscore! 
        $mform->addElement('date_selector', 'enddate', get_string('enddate','local_usermanagement'));
        $mform->addElement('text', 'afterdays1', get_string('afterdays1','local_usermanagement'));
        $mform->setType('afterdays1', PARAM_INT);
        $mform->addElement('text', 'afterdays2', get_string('afterdays2','local_usermanagement'));
        $mform->setType('afterdays2', PARAM_INT);
        $this->add_action_buttons($cancel = true, $submitlabel='Save');
    }

    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }

}
