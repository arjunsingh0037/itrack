<?php
// $Id: inscriptions_massives.php 356 2010-02-27 13:15:34Z ppollet $
/**
 * A bulk enrolment plugin that allow teachers to massively enrol existing accounts to their courses,
 * with an option of adding every user to a group
 * Version for Moodle 1.9.x courtesy of Patrick POLLET & Valery FREMAUX  France, February 2010
 * Version for Moodle 2.x by pp@patrickpollet.net March 2012
 */

require(dirname(dirname(dirname(__FILE__))).'/config.php');
 
require_once ('lib.php');

$id = required_param('id', PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $id))) {
    error("Course is misconfigured");
}


/// Security and access check

require_course_login($course);
$context = context_course::instance($course->id);
$personalcontext = context_user::instance($USER->id);
///var_dump($personalcontext);
require_capability('moodle/role:assign', $context);

/// Start making page
$PAGE->set_pagelayout('course');
$PAGE->set_url('/local/course_batches/course_batches.php');
$PAGE->requires->css('/local/course_batches/style/styles.css');
$viewnewpromo=get_string('viewpromo', 'local_course_batches');
$PAGE->navbar->add($viewnewpromo);
$PAGE->set_title($viewnewpromo);
$PAGE->set_heading("$course->fullname".' course_batches');
echo $OUTPUT->header();


$strinscriptions = 'View_batch';
echo $OUTPUT->heading_with_help($strinscriptions, 'course_batches', 'local_course_batches','promoicon',get_string('course_batches', 'local_course_batches'));

		
		
$cbatch = $DB->get_records('batch',array('courseid'=>$course->id));
if($cbatch != null)
{
    $table = new html_table();
    $table->head = array('Batch','Start Date','End Date','Actual Cost','Discount Cost','Duration','No. of Days','Action' );
    foreach ($cbatch as $batch => $row) {
   
         $table->data[] = array($row->name,userdate($row->startdate,'%d-%m-%Y'),userdate($row->enddate,'%d-%m-%Y'),
         	$row->actualcost,$row->discountcost,$row->duration,$row->days,'<a class="label label-warning" href ="'.$CFG->wwwroot.'/local/course_batches/edit_batch.php?id='.$row->id.'&courseid='.$course->id.'">Edit<a/>  
                                <a class="label label-warning" href = '.$CFG->wwwroot.'/local/course_batches/delete_batch.php?id='.$row->id.'&courseid='.$course->id.'>Delete<a/>');
        }
    echo html_writer::start_tag('div');
    echo html_writer::table($table);
    echo html_writer::end_tag('div');
} else {
    echo html_writer::div(get_string("nobatch", "local_course_batches"), 'alert alert-warning');
}


echo $OUTPUT->footer();

?>
