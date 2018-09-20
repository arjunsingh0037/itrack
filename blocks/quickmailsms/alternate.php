<?php

require_once '../../config.php';
require_once 'lib.php';
require_once 'alt_lib.php';
require_once 'alt_form.php';

$courseid = required_param('courseid', PARAM_INT);
$action = optional_param('action', 'view', PARAM_TEXT);
$id = optional_param('id', null, PARAM_INT);
$flash = optional_param('flash', 0, PARAM_INT);

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

$context = context_course::instance($courseid);
// Permission
require_login($course);
require_capability('block/quickmailsms:allowalternate', $context);

$blockname = quickmailsms::_s('pluginname');
$heading = quickmailsms::_s('alternate');
$title = "$blockname: $heading";

$url = new moodle_url('/blocks/quickmailsms/alternate.php', array('courseid' => $courseid));

$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_course($course);

$PAGE->navbar->add($blockname);
$PAGE->navbar->add($heading);

$PAGE->set_title($title);
$PAGE->set_heading($title);

if (!method_exists('quickmailsms_alternate', $action)) {
    // Always fallback on view
    $action = 'view';
}

$body = quickmailsms_alternate::$action($course, $id);

echo $OUTPUT->header();
echo $OUTPUT->heading($heading);

if ($flash) {
    echo $OUTPUT->notification(get_string('changessaved'), 'notifysuccess');
}

echo $body;

echo $OUTPUT->footer();
