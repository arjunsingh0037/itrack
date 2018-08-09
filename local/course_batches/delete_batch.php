<?php
require(dirname(dirname(dirname(__FILE__))).'/config.php');

$id = required_param('id',PARAM_INT);
$courseid = required_param('courseid',PARAM_INT);
$PAGE->set_pagelayout('course');
$PAGE->set_context(context_course::instance($courseid));
//echo $courseid;
//$paymentid = optional_param('payment',0,PARAM_INT);
if ($id) {	
	//$course = $DB->get_record('course', array('id' => $courseid));
	$generaldel = $DB->delete_records('batch', array('id' => $id));
	//$paymentdel = $DB->delete_records('course_batches_payment', array('courseid' => $courseid));  
}
if(isset($generaldel)) {
		redirect($CFG->wwwroot . "/local/course_batches/view_batch.php?id=$courseid");
	}

/* if($paymentid) {
		$payments = $DB->get_record('batch', array('id' => $paymentid));
		$paymentdel = $DB->delete_records('course_batches_payment', array('id' => $paymentid));
}	
	if($paymentdel) {
		redirect($CFG->wwwroot . "/local/course_batches/view_batch.php?id=$payments->courseid");
	} */
echo $OUTPUT->footer();

?>
