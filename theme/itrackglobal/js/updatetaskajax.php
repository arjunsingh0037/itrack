<?php
define('AJAX_SCRIPT', true);

require_once('../../../config.php');
global $USER,$OUTPUT,$PAGE,$DB;
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$tid = required_param('taskid', PARAM_INT);
//require_once ('lib.php');
$updated = $DB->get_record('block_todo',array('id'=>$tid),'id,done');
$content = array();
$data_array = array('id'=>$tid); 
$update_task = new stdClass();
$update_task->id = $tid;
if($updated->done == 1){
	$update_task->done = 0;
}else{
	$update_task->done = 1;
}
$comp = $DB->update_record('block_todo',$update_task);
$content = array('status'=>'Success');
echo json_encode($content);