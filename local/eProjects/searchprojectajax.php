<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');

$PAGE->set_context(context_system::instance());
$sid = optional_param('streamid',0,PARAM_INT);
$gtype = optional_param('grouptype',null,PARAM_RAW);
//echo $OUTPUT->header(); 
$groups_arr = array();
if($sid != 0){
	$sql = "SELECT id,domain_name from {domain} WHERE stream_id = '$sid'";
	$domains = $DB->get_records_sql($sql);
	foreach ($domains as $dm) {
			$groups_arr[] = array('id'=>$dm->id,'domain'=>$dm->domain_name);
	}
}elseif ($gtype != null) {
	if($gtype == 'gp'){
		$sql = "SELECT id,groupname from {project_group}";
		$groups = $DB->get_records_sql($sql);
		foreach ($groups as $dm) {
				$groups_arr[] = array('id'=>$dm->id,'gpname'=>$dm->groupname);
		}
	}
}
//$streams[] = array('id'=>'22','stream'=>'aadadadaddad');
echo json_encode($groups_arr);
