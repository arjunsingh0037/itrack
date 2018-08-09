<?php
define('AJAX_SCRIPT', true);

require_once('../../../config.php');

$PAGE->set_context(context_system::instance());
$usertype = optional_param('utype',null, PARAM_RAW);
$pname = optional_param('pname',null, PARAM_RAW);
$totals = optional_param('stutotal',0, PARAM_INT);
$totalp = optional_param('proftotal',0, PARAM_INT);
//echo $OUTPUT->header(); 

if($DB->record_exists('trainingpartners',array('userid'=>$USER->id))){
    $partnerid = $DB->get_record('trainingpartners',array('userid'=>$USER->id),'createdby');
    $permission_for_tpa = $DB->get_record('partners',array('userid'=>$partnerid->createdby),'id,tp_permission,stud_permission,prof_permission,hc_permission,stud_no,prof_no');
}
if($usertype != null){
	$sql = "SELECT DISTINCT program from {program_stream}";
	$program = $DB->get_records_sql($sql);
	foreach ($program as $prg) {
			$streams[] = array('id'=>$prg->id,'program'=>$prg->program);
	}
}else if($pname != null) {
	$sql = "SELECT id,program,stream from {program_stream} WHERE program = '$pname'";
	$stream = $DB->get_records_sql($sql);
	foreach ($stream as $str) {
			$streams[] = array('id'=>$str->id,'stream'=>$str->stream);
	}
}
echo json_encode($streams);




