<?php
define('AJAX_SCRIPT', true);

require_once('../../../config.php');
global $USER,$OUTPUT,$PAGE,$DB;
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$tid = required_param('taskid', PARAM_INT);
//require_once ('lib.php');
$content = array();
$data_array = array('id'=>$tid); 
$comp = $DB->delete_records('block_todo',$data_array);
$content = array('status'=>'Success');
echo json_encode($content);