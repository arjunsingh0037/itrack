<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');

$PAGE->set_context(context_system::instance());
$program = optional_param('program', null, PARAM_RAW);
$stream = optional_param('stream', 0, PARAM_INT);
$sprogram = optional_param('sprogram', null, PARAM_RAW);
$year = optional_param('year', 0, PARAM_INT);
$syp = optional_param('syp', null, PARAM_RAW);
$sys = optional_param('sys', 0, PARAM_INT);
$semester = optional_param('semester', 0, PARAM_INT);
$semyear = optional_param('semyear', 0, PARAM_INT);
$semp = optional_param('semp', null, PARAM_RAW);
$sems = optional_param('sems', 0, PARAM_INT);
//echo $OUTPUT->header(); 
$streamed = array();
if($program != null){
	$sql = "SELECT id,program,stream from {program_stream} WHERE program = '$program'";
	$stream = $DB->get_records('batches',array('creatorid'=>$USER->id,'batchtype'=>'ACAD','program'=>$program),'stream');
	foreach ($stream as $str) {
			$streamname = $DB->get_record('program_stream',array('id'=>$str->stream),'stream');
			$streamed[$str->stream] = $streamname->stream;
	}
	foreach ($streamed as $sid => $sv) {
		$streams[] = array('id'=>$sid,'stream'=>$sv);
	}
	
}elseif($stream != 0){
	$stream = $DB->get_records('batches',array('creatorid'=>$USER->id,'batchtype'=>'ACAD','program'=>$sprogram,'stream'=>$stream),'id,semyear');
	foreach ($stream as $ssemy) {
        $streamed[$ssemy->semyear] = $ssemy->semyear;
    }
    foreach ($streamed as $sid => $sv) {
		$streams[] = array('id'=>$sid,'semyear'=>$sv);
	}
}elseif($year != 0){
	$stream = $DB->get_records('batches',array('creatorid'=>$USER->id,'batchtype'=>'ACAD','program'=>$syp,'stream'=>$sys,'semyear'=>$year),'semester');
    foreach ($stream as $ssemy) {
        $streamed[$ssemy->semester] = $ssemy->semester;
    }
    foreach ($streamed as $sid => $sv) {
		$streams[] = array('id'=>$sid,'semester'=>$sv);
	}
}elseif($semester != 0){
	$stream = $DB->get_records('batches',array('creatorid'=>$USER->id,'batchtype'=>'ACAD','program'=>$semp,'stream'=>$sems,'semyear'=>$semyear,'semester'=>$semester),'id,batchname');
	foreach ($stream as $ba) {
        $streams[] = array('batchid'=>$ba->id,'batchname'=>$ba->batchname);
    }
}

//$streams[] = array('id'=>'22','stream'=>'aadadadaddad');
echo json_encode($streams);

//print_object($streams);


