<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$projectid = optional_param('projectid',0,PARAM_INT);
$groupid = optional_param('groupid',0,PARAM_INT);
$newgpid = optional_param('newgpid',0,PARAM_INT);
$request_stat = array();

if($projectid != 0){
	$approve = new stdClass();
	$approve->id = $projectid;
	$approve->status = 1;
	$updated = $DB->update_record('project_request',$approve);
	if($updated){
		$request_stat = array('status'=>1);
	}else{
		$request_stat = array('status'=>0);
	}
}elseif($groupid != 0) {
	$approve1 = new stdClass();
	$approve1->id = $groupid;
	$approve1->status = 1;
	$updated1 = $DB->update_record('project_group',$approve1);
	if($updated1){
		$request_stat = array('status'=>1);
	}else{
		$request_stat = array('status'=>0);
	}
}elseif($newgpid != 0) {
	$approve2 = new stdClass();
	$approve2->id = $newgpid;
	$approve2->status = 1;
	$updated2 = $DB->update_record('group_to_student',$approve2);
	if($updated2){
		$request_stat = array('status'=>1);
	}else{
		$request_stat = array('status'=>0);
	}
}
echo json_encode($request_stat);




