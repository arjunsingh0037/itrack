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
 * Library file for badge ladder plugin
 *
 * @package    local_bs_badge_ladder
 * @copyright  2015 onwards Matthias Schwabe {@link http://matthiasschwa.be}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Some file imports.
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir.'/badgeslib.php');
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/adminlib.php');

function local_bs_badge_ladder_extend_navigation ($nav) {
    global $PAGE, $DB, $CFG;

    $enabled = $DB->get_record('local_badge_ladder', array('courseid' => $PAGE->course->id, 'status' => 1));

    // Show link to course badge ladder.
    if ($PAGE->course->id
        and $PAGE->course->id != SITEID
        and $CFG->badges_allowcoursebadges
        and get_config('local_bs_badge_ladder')->enablecourseladder
        and ((has_capability('local/bs_badge_ladder:viewcourseladder', $PAGE->context)
        and $enabled) or has_capability('local/bs_badge_ladder:managecourseladder', $PAGE->context))) {

        $url = new moodle_url('/local/bs_badge_ladder/index.php', array('id' => $PAGE->course->id, 'type' => 2));
        $coursenode = $nav->find($PAGE->course->id, $nav::TYPE_COURSE);
        if ($enabled) {
            $navtext = get_string('viewcourseladder', 'local_bs_badge_ladder');
        } else {
            $navtext = get_string('viewcourseladderdisabled', 'local_bs_badge_ladder');
        }
        $coursenode->add($navtext, $url,
            navigation_node::TYPE_SETTING, null, 'viewcourseladder', new pix_icon('i/badge', get_string('badgesview', 'badges')));
    }

    // Show link to site badge ladder.
    if (isloggedin()
        and !isguestuser()
        and isset(get_config('local_bs_badge_ladder')->enablesystemladder)
        and get_config('local_bs_badge_ladder')->enablesystemladder == 1) {

        $url = new moodle_url('/local/bs_badge_ladder/index.php', array('type' => 1));
        $coursenode = $nav->find(SITEID, $nav::TYPE_COURSE);

        if (!get_config('local_bs_badge_ladder')->systemladderdisplaylink) {
            $coursenode->add(get_string('viewsystemladder', 'local_bs_badge_ladder'),
            $url, $nav::TYPE_CONTAINER, null, 'viewsystemladder', new pix_icon('i/badge', get_string('badgesview', 'badges')));
        } else { // Show link on Boost based theme.
            $node = $coursenode->add(get_string('viewsystemladder', 'local_bs_badge_ladder'), $url, $nav::TYPE_CUSTOM, null, 'viewsystemladder');
            $node->showinflatnavigation = true;
        }
    }
}

// Edit form for course badge ladder.
class local_bs_badge_ladder_form extends moodleform {

    public function definition() {
        global $COURSE;

        $mform =& $this->_form;
        $config = (isset($this->_customdata['config'])) ? $this->_customdata['config'] : false;

        $mform->addElement('advcheckbox', 'enablecoursebadgeladder',
            get_string('enablecoursebadgeladder', 'local_bs_badge_ladder'), '', null, array(0, 1));
        $mform->setDefault('enablecoursebadgeladder', get_config('local_bs_badge_ladder')->courseladderdefault);

        if (get_config('local_bs_badge_ladder')->anonymizestudentbadgeladder) {
            $mform->addElement('advcheckbox', 'anonymizestudentbadgeladder',
                get_string('anonymizestudentbadgeladder', 'local_bs_badge_ladder'), '', null, array(0, 1));
            $mform->setDefault('anonymizestudentbadgeladder',
                get_config('local_bs_badge_ladder')->anonymizestudentbadgeladderdefault);
        }

        $mform->addElement('text', 'coursebadgeladderperpage',
            get_string('coursebadgeladderperpage', 'local_bs_badge_ladder'), array('maxlength' => '3', 'size' => '3'));
        $mform->setType('coursebadgeladderperpage', PARAM_INT);
        $mform->setDefault('coursebadgeladderperpage', 20);
        $mform->addRule('coursebadgeladderperpage', get_string('notnegativenumericvalue', 'local_bs_badge_ladder'),
            'numeric', null, 'client');
        $mform->addRule('coursebadgeladderperpage', get_string('requiredvalue', 'local_bs_badge_ladder'),
            'required', null, 'client');

        $this->add_action_buttons();
        $this->set_data($config);
    }

    public function validation($data, $files) {
        $errors = array();
        if ($data['coursebadgeladderperpage'] < 1) {
            $errors['coursebadgeladderperpage'] = get_string('notnegativenumericvalue', 'local_bs_badge_ladder');
        }
        return $errors;
    }

    public function set_data($config) {
        global $COURSE, $DB;

        parent::set_data($config);

        $values = array();
        $values['enablecoursebadgeladder'] = $DB->get_field('local_badge_ladder', 'status',
            array('courseid' => $COURSE->id), MUST_EXIST);
        $values['anonymizestudentbadgeladder'] = $DB->get_field('local_badge_ladder', 'anonymize',
            array('courseid' => $COURSE->id), MUST_EXIST);
        $values['coursebadgeladderperpage'] = $DB->get_field('local_badge_ladder', 'perpage',
            array('courseid' => $COURSE->id), MUST_EXIST);

        parent::set_data($values);
    }
}
