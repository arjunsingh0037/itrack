<?php
define('AJAX_SCRIPT', true);
global $USER,$OUTPUT;
require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$did = required_param('deleteid', PARAM_INT);
require_once ('lib.php');
$content = array();
$data_array = array('id'=>$did); 
$comp = $DB->delete_records('project_group',$data_array);
$content = array('status'=>'Success','msg'=>'Group Deleted.');
echo json_encode($content);
