<?php
define('AJAX_SCRIPT', true);
global $USER;
require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
// $batchtype = optional_param('btype', null, PARAM_RAW);
// $program = optional_param('program', null, PARAM_RAW);
// $stream = optional_param('stream', 0, PARAM_INT);
// $year = optional_param('year', 0, PARAM_INT);
$stream = optional_param('streamid', 0, PARAM_INT);
$sdomain = optional_param('sdomainid', 0, PARAM_INT);

$content = ''; 
if($stream != 0){
	$data_array = array('id'=>$stream); 
	$exists = $DB->record_exists('stream',$data_array);
	if($exists){
    	$str = $DB->get_record('stream',$data_array,'id,stream_name');
		$content .= $str->stream_name;
	}else{
		$content = '';
	}
}elseif($sdomain != 0){
	$data_array = array('id'=>$sdomain); 
	$exists = $DB->record_exists('domain',$data_array);
	if($exists){
    	$dom = $DB->get_record('domain',$data_array,'id,domain_name,stream_id');
		$content= array('domainid'=>$dom->id,'streamid'=>$dom->stream_id,'domainname'=>$dom->domain_name);
	}else{
		$content = '';
	}
}
echo json_encode($content);




