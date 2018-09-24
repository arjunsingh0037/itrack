<?php
define('AJAX_SCRIPT', true);
global $USER;
require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$batchtype = optional_param('btype', null, PARAM_RAW);
$program = optional_param('program', null, PARAM_RAW);
$stream = optional_param('stream', 0, PARAM_INT);
$year = optional_param('year', 0, PARAM_INT);
$semester = optional_param('semester', 0, PARAM_INT);

$content = ''; 
if($semester != 0){
	$data_array = array('batchtype'=>$batchtype,'program'=>$program,'stream'=>$stream,'semyear'=>$year,'semester'=>$semester,'creatorid'=>$USER->id); 
	$exists = $DB->record_exists('batches',$data_array);
	if($exists){
    	$batch = $DB->get_record('batches',$data_array,'id,batchname');
		$content .= $batch->batchname;
	}else{
		$content = '';
	}
}
echo json_encode($content);




