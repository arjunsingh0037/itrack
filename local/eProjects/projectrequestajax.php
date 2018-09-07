<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$projectid = required_param('projectid',PARAM_INT);
$groupid = required_param('groupid',PARAM_INT);
$grouptype = required_param('grouptype',PARAM_RAW);
$request_stat = array();

$new_request = new stdClass();
$new_request->requester = $USER->id;
$new_request->type = $grouptype;
$new_request->projectid = $projectid;
$new_request->groupid = $groupid;
$new_request->timerequested = time();
$records = $DB->record_exists('project_request',array('requester'=>$USER->id,'type'=>$grouptype,'groupid'=>$groupid,'projectid'=>$projectid));
if(!$records){
	$project_request = $DB->insert_record('project_request',$new_request);
	if($project_request){
		$request_stat = array('status'=>1);
	}else{
		$request_stat = array('status'=>0);
	}
}else{
	$request_stat = array('status'=>0);
}
echo json_encode($request_stat);




