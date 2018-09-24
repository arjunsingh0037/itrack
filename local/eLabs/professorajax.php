<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');

$PAGE->set_context(context_system::instance());
$batchid = optional_param('batch', 0, PARAM_INT);
if($batchid != 0){
	$courses = $DB->get_records('course_batches',array('createdby'=>$USER->id,'batchid'=>$batchid),'courseid');
	foreach ($courses as $crs) {
		$cs = $DB->get_record('course',array('id'=>$crs->courseid),'fullname');
        $course[] = array('id'=>$crs->courseid,'course'=>$cs->fullname);
    }
}
echo json_encode($course);


