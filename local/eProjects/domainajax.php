<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');

$PAGE->set_context(context_system::instance());
$sid = optional_param('streamid',0,PARAM_INT);
//echo $OUTPUT->header(); 
$domain_arr = array();
if($sid != 0){
	$sql = "SELECT id,domain_name from {domain} WHERE stream_id = '$sid'";
	$domains = $DB->get_records_sql($sql);
	foreach ($domains as $dm) {
			$domain_arr[] = array('id'=>$dm->id,'domain'=>$dm->domain_name);
	}
}
//$streams[] = array('id'=>'22','stream'=>'aadadadaddad');
echo json_encode($domain_arr);




