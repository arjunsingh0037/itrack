<?php

// Written at Louisiana State University

require_once('../../config.php');
require_once('lib.php');

require_login();

$courseid = required_param('courseid', PARAM_INT);
$type = optional_param('type', 'log', PARAM_ALPHA);
$typeid = optional_param('typeid', 0, PARAM_INT);
$action = optional_param('action', null, PARAM_ALPHA);
$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 10, PARAM_INT);
$userid = optional_param('userid', $USER->id, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('no_course', 'block_quickmailsms', '', $courseid);
}

$context = context_course::instance($courseid);

// Has to be in on of these
if (!in_array($type, array('log', 'drafts'))) {
    print_error('not_valid', 'block_quickmailsms', '', $type);
}

$canimpersonate = has_capability('block/quickmailsms:canimpersonate', $context);
if (!$canimpersonate and $userid != $USER->id) {
    print_error('not_valid_user', 'block_quickmailsms');
}

$config = quickmailsms::load_config($courseid);

$valid_actions = array('delete', 'confirm');

$can_send = has_capability('block/quickmailsms:cansend', $context);

$proper_permission = ($can_send or !empty($config['allowstudents']));

//managers can delete by capability 'candelete'; 
//those with 'cansend' (incl students, if $config['allowstudents']) can only delete drafts; 
$can_delete = (has_capability('block/quickmailsms:candelete', $context) or ($can_send and $type == 'drafts') or ($proper_permission and $type == 'drafts'));

// Stops students from tempering with history
if (!$proper_permission or (!$can_delete and in_array($action, $valid_actions))) {
    print_error('no_permission', 'block_quickmailsms');
}

if (isset($action) and !in_array($action, $valid_actions)) {
    print_error('not_valid_action', 'block_quickmailsms', '', $action);
}

if (isset($action) and empty($typeid)) {
    print_error('not_valid_typeid', 'block_quickmailsms', '', $action);
}

$blockname = quickmailsms::_s('pluginname');
$header = quickmailsms::_s($type);

$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->navbar->add($blockname);
$PAGE->navbar->add($header);
$PAGE->set_title($blockname . ': ' . $header);
$PAGE->set_heading($blockname . ': ' . $header);
$PAGE->set_url('/blocks/quickmailsms/emaillog.php', array('courseid' => $courseid));
$PAGE->set_pagetype(quickmailsms::PAGE_TYPE);

$dbtable = 'block_quickmailsms_' . $type;

$params = array('userid' => $userid, 'courseid' => $courseid);
$count = $DB->count_records($dbtable, $params);

switch ($action) {
    case "confirm":
        if (quickmailsms::cleanup($dbtable, $context->id, $typeid)) {
            $url = new moodle_url('/blocks/quickmailsms/emaillog.php', array(
                'courseid' => $courseid,
                'type' => $type
            ));
            redirect($url);
        } else
            print_error('delete_failed', 'block_quickmailsms', '', $typeid);
    case "delete":
        $html = quickmailsms::delete_dialog($courseid, $type, $typeid);
        break;
    default:
        $html = quickmailsms::list_entries($courseid, $type, $page, $perpage, $userid, $count, $can_delete);
}

if($courseid == SITEID) {
    $html.= html_writer::link(
        new moodle_url('/blocks/quickmailsms/admin_email.php'),
        quickmailsms::_s('composenew')
     );
} else {
    $html.= html_writer::link(
        new moodle_url(
            '/blocks/quickmailsms/email.php', 
            array('courseid' => $courseid)),
        quickmailsms::_s('composenew')
    );
}

if($canimpersonate and $USER->id != $userid) {
    $user = $DB->get_record('user', array('id' => $userid));
    // http://docs.moodle.org/dev/Additional_name_fields
    $header .= ' for '. fullname($user);

    
}

echo $OUTPUT->header();
echo $OUTPUT->heading($header);

if($canimpersonate) {
    
    $get_name_string = 'u.firstname, u.lastname';

    if($CFG->version >= 2013111800){
        $get_name_string = get_all_user_name_fields(true, 'u');
    }
    $sql = "SELECT DISTINCT(l.userid)," . $get_name_string . "
                FROM {block_quickmailsms_$type} l,
                     {user} u
                WHERE u.id = l.userid AND courseid = ? ORDER BY u.lastname";

    $users = $DB->get_records_sql($sql, array($courseid));    
    $user_options = array_map(function($user) { return fullname($user); }, $users);

    $url = new moodle_url('emaillog.php', array(
        'courseid' => $courseid,
        'type' => $type
    ));

    $default_option = array('' => quickmailsms::_s('select_users'));

    echo $OUTPUT->single_select($url, 'userid', $user_options, $userid, $default_option);
}

if(empty($count)) {
    echo $OUTPUT->notification(quickmailsms::_s('no_'.$type));

    if ($COURSE->id == 1) {
        echo $OUTPUT->continue_button('/blocks/quickmailsms/admin_email.php?courseid='.$courseid);
    } else {
        echo $OUTPUT->continue_button('/blocks/quickmailsms/email.php?courseid='.$courseid);
    }

    echo $OUTPUT->footer();
    exit;
}

echo $html;

echo $OUTPUT->footer();
