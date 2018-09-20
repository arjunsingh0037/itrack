<?php

// Written at Louisiana State University

require_once $CFG->libdir . '/formslib.php';

class email_form extends moodleform {
    public function definition() {
        global $CFG;

        $mform =& $this->_form;
        $mform->addElement('text', 'subject', get_string('subject', 'block_admin_email'));
        $mform->addElement('text', 'noreply', get_string('noreply', 'block_admin_email'));
        $mform->addElement('editor', 'body',  get_string('body', 'block_admin_email'));

        $buttons = array(
            $mform->createElement('submit', 'send', get_string('send_email', 'block_admin_email')),
            $mform->createElement('cancel', 'cancel', get_string('cancel'))
        );
        $mform->addGroup($buttons, 'actions', '&nbsp;', array(' '), false);

        $mform->addRule('subject', null, 'required', 'client');
        $mform->addRule('noreply', null, 'required', 'client');
        $mform->addRule('body', null, 'required');
    }

    function validation($data) {
        $errors = array();
        foreach(array('subject', 'body', 'noreply') as $field) {
            if(empty($data[$field]))
                $errors[$field] = get_string('email_error_field', 'block_admin_email', $field);
        }
    }
}
