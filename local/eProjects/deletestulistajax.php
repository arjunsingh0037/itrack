<?php
define('AJAX_SCRIPT', true);
global $USER,$OUTPUT;
require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$aid = required_param('assignid', PARAM_INT);
require_once ('lib.php');
$content = array();
$data_array = array('id'=>$aid); 
$group = $DB->get_record('group_to_student',$data_array,'groupid');
$comp = $DB->delete_records('group_to_student',$data_array);
$content = array('status'=>'Success','msg'=>'Student deleted from group','group'=>$group->groupid);
echo json_encode($content);
