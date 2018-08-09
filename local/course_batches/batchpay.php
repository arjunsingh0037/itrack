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

global  $USER;

$id = required_param('id', PARAM_INT);
$cbatch = $DB->get_record('batch',array('id'=>$id));
if (!$course = $DB->get_record('course', array('id' => $cbatch->courseid))) {
    error("Course is misconfigured");
}
$userfullname = $USER->firstname.' '.$USER->lastname;

/// Security and access check

require_login();
$context = context_course::instance($course->id);

$PAGE->set_context($context);
$PAGE->set_pagelayout('course');
$PAGE->set_url('/local/course_batches/batchpay.php');

$viewpay=get_string('batchpayment', 'local_course_batches');
$PAGE->navbar->add($viewpay);
$PAGE->set_title($viewpay);
$PAGE->set_heading("$course->fullname".' '.$viewpay);
$PAGE->requires->css('/local/course_batches/style/styles.css');
echo $OUTPUT->header();

//$course = $DB->get_record('course', array('id' => $id));
//$strinscriptions = 'View_batch';
//echo $OUTPUT->heading_with_help($strinscriptions, 'course_batches', 'local_course_batches','promoicon',get_string('course_batches', 'local_course_batches'));
		
//$cbatch = $DB->get_record('batch',array('id'=>$id));
//echo $cbatch->id;

if($cbatch != null)
{
?>
<div class="col-lg-12">
    <div class="col-lg-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <h5> Personal Information</h5>
                    <span class="avatar">
                                    <img src="<?php echo $CFG->wwwroot.'/user/pix.php?file=/'.$USER->id.'/f1.jpg';?>" class="uimgcircle" class="avatar avatar-90 photo avatar-default" height="40" width="40"/> 
                    </span>
                </div>
                 <div class="panel-body">
                       <div class="list-group">
                            <a href="#" class="list-group-item">
                                <span class="glyphicon glyphicon-user"></span> Name <span class="badge"><?php echo $userfullname;?></span>
                            </a>
                            <a href="#" class="list-group-item">
                                <span class="glyphicon glyphicon-globe"></span> City <span class="badge"><?php echo $USER->city;?></span>
                            </a>
                            <a href="#" class="list-group-item">
                                <span class="glyphicon glyphicon-phone"></span> Contact <span class="badge"><?php echo $USER->phone1;?></span>
                            </a>
                            <a href="#" class="list-group-item">
                                <span class="glyphicon glyphicon-envelope"></span> Email <span class="badge"><?php echo $USER->email;?></span>
                            </a>
                        </div>
                  </div>
             </div>
    </div>
    <div class="col-lg-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <h5> You are paying </h5>
                        <h4 style="float:right;margin-top:-35px">$<?php echo $cbatch->discountcost;?></h4>
                    </div>
                    <div class="panel-body">
                       <div class="list-group">
                            <a href="#" class="list-group-item">
                                <span class="glyphicon glyphicon-check"></span> Batch Name <span class="badge"><?php echo $cbatch->name;?></span>
                            </a>
                            <a href="#" class="list-group-item">
                                <span class="glyphicon glyphicon-book"></span> Course Name <span class="badge"><?php echo $course->fullname;?></span>
                            </a>
                            <a href="#" class="list-group-item">
                                <span class="glyphicon glyphicon-calendar"></span> Start Date <span class="badge"><?php echo userdate($cbatch->startdate,'%d-%m-%Y');?></span>
                            </a>
                            <a href="#" class="list-group-item">
                                <span class="glyphicon glyphicon-calendar"></span> End Date <span class="badge"><?php echo userdate($cbatch->enddate,'%d-%m-%Y');?></span>
                            </a>
                        </div>
                    </div>
                </div>
    </div>
</div>

<?php  
        
} else {
    echo html_writer::div(get_string("nobatch", "local_course_batches"), 'alert alert-warning');
}

require_once ('batchpay_form.html');

echo $OUTPUT->footer();

?>
