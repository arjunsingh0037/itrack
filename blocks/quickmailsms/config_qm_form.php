<?php

// Written at Louisiana State University

require_once $CFG->libdir . '/formslib.php';

class config_form extends moodleform {
    public function definition() {
        $mform =& $this->_form;

        $reset_link = html_writer::link(
            new moodle_url('/blocks/quickmailsms/config_qm.php', array(
                'courseid' => $this->_customdata['courseid'],
                'reset' => 1
            )), quickmailsms::_s('reset')
        );
        $mform->addElement('static', 'reset', '', $reset_link);

        $student_select = array(0 => get_string('no'), 1 => get_string('yes'));

        $allowstudents = get_config('moodle', 'block_quickmailsms_allowstudents');
        if ($allowstudents != -1) {
            // If we disallow "Allow students to use QuickmailSMS" at the site
            // level, then disallow the config to be set at the course level.
            $mform->addElement('select', 'allowstudents',
                quickmailsms::_s('allowstudents'), $student_select);
        }

        $roles =& $mform->addElement('select', 'roleselection',
            quickmailsms::_s('select_roles'), $this->_customdata['roles']);

        $roles->setMultiple(true);

        $options = array(
            0 => get_string('none'),
            'idnumber' => get_string('idnumber'),
            'shortname' => get_string('shortname')
        );

        $mform->addElement('select', 'prepend_class',
            quickmailsms::_s('prepend_class'), $options);

        $mform->addElement('select', 'receipt',
            quickmailsms::_s('receipt'), $student_select);

        $mform->addElement('submit', 'save', get_string('savechanges'));
        
        $mform->addElement('hidden', 'courseid', $this->_customdata['courseid']);
        $mform->setType('courseid',PARAM_INT);

        $mform->addRule('roleselection', null, 'required');
    }
}
