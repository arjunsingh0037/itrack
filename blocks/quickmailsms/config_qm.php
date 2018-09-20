<?php

// Written at Louisiana State University

require_once '../../config.php';
require_once 'lib.php';
require_once 'config_qm_form.php';

require_login();

$courseid = required_param('courseid', PARAM_INT);
$reset = optional_param('reset', 0, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('no_course', 'block_quickmailsms', '', $courseid);
}

$context = context_course::instance($courseid);

require_capability('block/quickmailsms:canconfig', $context);

$blockname = quickmailsms::_s('pluginname');
$header = quickmailsms::_s('config');

$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->set_url('/blocks/quickmailsms/config_qm.php', array('courseid' => $courseid));
$PAGE->set_title($blockname . ': '. $header);
$PAGE->set_heading($blockname. ': '. $header);
$PAGE->navbar->add($blockname);
$PAGE->navbar->add($header);
$PAGE->set_pagetype(quickmailsms::PAGE_TYPE);

$changed = false;

if ($reset) {
    $changed = true;
    quickmailsms::default_config($courseid);
}

$roles = role_fix_names(get_all_roles($context), $context, ROLENAME_ALIAS, true);

$form = new config_form(null, array(
    'courseid' => $courseid,
    'roles' => $roles
));

if ($data = $form->get_data()) {
    $config = get_object_vars($data);

    unset($config['save'], $config['courseid']);

    $config['roleselection'] = implode(',', $config['roleselection']);

    quickmailsms::save_config($courseid, $config);
    $changed = true;
}

$config = quickmailsms::load_config($courseid);
$config['roleselection'] = explode(',', $config['roleselection']);

$form->set_data($config);

echo $OUTPUT->header();
echo $OUTPUT->heading($header);

echo $OUTPUT->box_start();

if ($changed) {
    echo $OUTPUT->notification(get_string('changessaved'), 'notifysuccess');
}

$form->display();
echo $OUTPUT->box_end();

echo $OUTPUT->footer();
