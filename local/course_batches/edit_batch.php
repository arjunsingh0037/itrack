<?php


require(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once ('lib.php');
require_once ('editform_batch.php');

$id = required_param('id',PARAM_INT);
$courseid = required_param('courseid',PARAM_INT);

/// Start making page
$general = $DB->get_record('batch', array('id' => $id)) ;

$PAGE->set_context(context_course::instance($courseid));
$PAGE->set_pagelayout('course');
$PAGE->set_url('/local/course_batches/edit_batch.php');
$PAGE->requires->css('/local/course_batches/style/styles.css');
$mform = new course_batches_form(new moodle_url($CFG->wwwroot . '/local/course_batches/edit_batch.php',array('id'=>$id,'courseid'=>$courseid)));

$mform->set_data($general);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/course'));
}else if($data = $mform->get_data()) {
			
		if($DB->update_record('batch', $data)){
			redirect($CFG->wwwroot . "/local/course_batches/view_batch.php?id=$courseid");
		}

}	
echo $OUTPUT->header();
$strinscriptions = get_string('ctbatch', 'local_course_batches');
echo $OUTPUT->heading_with_help($strinscriptions, 'course_batches', 'local_course_batches','icon',get_string('ctbatch', 'local_course_batches'));
echo $OUTPUT->box (get_string('course_batches_info', 'local_course_batches'), 'center');
$mform->display();
echo $OUTPUT->footer();
?>
