<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');

$PAGE->set_context(context_system::instance());
$pname = optional_param('bname', null, PARAM_RAW);
$courseid = optional_param('courseid', 0, PARAM_INT);
//echo $OUTPUT->header(); 
if($pname != null){
	$sql = "SELECT id,program,stream from {program_stream} WHERE program = '$pname'";
	$stream = $DB->get_records_sql($sql);
	foreach ($stream as $str) {
			$streams[] = array('id'=>$str->id,'stream'=>$str->stream);
	}
}elseif($courseid != 0){
	$tpap = $DB->get_records('tp_useruploads',array('creatorid'=>$USER->id,'roletype'=>'professor'));
    foreach ($tpap as $tpa) {
        $tp_user = $DB->get_record('user',array('id'=>$tpa->userid),'id,firstname');
        $streams[] = array('id'=>$tp_user->id,'name'=>$tp_user->firstname);
    }
}

//$streams[] = array('id'=>'22','stream'=>'aadadadaddad');
echo json_encode($streams);




