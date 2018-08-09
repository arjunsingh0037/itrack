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
require_once ('course_toprofessor_form.php');
$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/course_batches/course_to_professor.php');
$PAGE->set_url($url);
$PAGE->requires->css('/local/course_batches/style/styles.css');
//By Arjun -Permission Access
$currentuser = $USER->id;
$user = $DB->record_exists('trainingpartners', array('userid' => $currentuser));
if (!$user) {
    echo $OUTPUT->header();
    redirect($CFG->wwwroot.'/my','You do not have access to this page.',1,'error');
    die; 
}
$addnewpromo='Asssign Course To professor';
$PAGE->navbar->add($addnewpromo);
$PAGE->set_title($addnewpromo);
$PAGE->set_heading('Assign Course');
echo $OUTPUT->header();
$newobj = new stdClass();
echo '<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
    <div class="form-group">
        <label style="padding-right: 15px;">Course Type : </label>
        <label class="radio-inline"><input type="radio" name="course_type" id="id_acadbatch" value="academic">Academic</label>
        <label class="radio-inline"><input type="radio" name="course_type" id="id_nacadbatch" value="non-academic">Subscribed Industry</label>
    </div>
  </div>
  <div class="panel-body">
        <div class="form-group" id="acadbatch_enrol" style="display:none;">';
            $academic_toprofessor_form = new academicbatch_toprofessor_form();
            //print_object($_POST);die();
            if ($acadenrol_toprofdata = $academic_toprofessor_form->get_data()) {
                global $USER;
                /*print_object($acadenrol_toprofdata);
                print_object($_POST);die();*/
                $insert_ac = new stdClass();
                $insert_ac->batchid = $acadenrol_toprofdata->batchid;
                $insert_ac->userid = $acadenrol_toprofdata->profid;
                $insert_ac->createdby = $USER->id;
                $insert_ac->timecreated = time();

                foreach ($_POST['courseids'] as $course) {
                    $insert_ac->courseid = $course;
                    $toprof_acad = $DB->insert_record('course_professors',$insert_ac);
                }
            }
            $academic_toprofessor_form->set_data($newobj);
            $academic_toprofessor_form->display();
            echo professor_assigned_acadcourses();
    echo '</div>';
    if(isset($toprof_acad)) {
            $enrolled_professors = professor_coursebatch_enrolment($_POST['profid'],$_POST['courseids']);
            if($enrolled_professors){
                echo '<div id="batchsuccess">';
                echo $OUTPUT->notification('Courses Assigned to Professor','notifysuccess');
                echo '</div>';
            }
            //redirect($CFG->wwwroot . "/local/course_batches/batch.php",'Batch created');
    }
    echo '<div class="form-group"  id="nacadbatch_enrol" style="display:none;">';
            $nonacademic_tocourse_form = new nonacademicbatch_toprofessor_form();
            if ($nonacadenrol_tocoursedata = $nonacademic_tocourse_form->get_data()) {
                global $USER;
                // print_object($academic_data);
                // print_object($_POST);die();
                $insert_nac = new stdClass();
                $insert_nac->batchname = $nonacadenrol_tocoursedata->batchname;
                $insert_nac->batchcode = $nonacadenrol_tocoursedata->batchcode;
                $insert_nac->batchtype = $nonacadenrol_tocoursedata->btype;
                $insert_nac->creatorid = $USER->id;
                $insert_nac->program = NULL;
                $insert_nac->stream = NULL;
                $insert_nac->semester = NULL;
                $insert_nac->semyear = NULL;
                $insert_nac->course = NULL;
                $insert_nac->professor = NULL;
                $insert_nac->startdate = NULL;
                $insert_nac->enddate = NULL;
                $insert_nac->batchlimit = NULL;
                $insert_nac->timecreated = time();
            
                $tocourse_nacad = $DB->insert_record('batches',$insert_nac); 
            }
            $nonacademic_tocourse_form->set_data($newobj);
            $nonacademic_tocourse_form->display();
    echo '</div>';
    if(isset($tocourse_nacad)) {
            echo '<div id="batchsuccess">';
            echo $OUTPUT->notification('Courses Assigned to Non-academic Batch','notifysuccess');
            echo '</div>';
            //redirect($CFG->wwwroot . "/local/course_batches/batch.php",'Batch created');
    }
  echo '</div>
</div>';

echo $OUTPUT->footer();
?>
<script type="text/javascript">

    $('#id_acadbatch').click(function () {
        $('#batchsuccess').hide();
        $('#acadbatch_enrol').show('fast');
        $('#nacadbatch_enrol').hide('fast');
      
        document.getElementById("acadbatch_enrol").disabled = false;
        document.getElementById("nacadbatch_enrol").disabled = true;
    });

    $('#id_nacadbatch').click(function () {
        $('#batchsuccess').hide();
        $('#nacadbatch_enrol').show('fast');
        $('#acadbatch_enrol').hide('fast');
       
        document.getElementById("nacadbatch_enrol").disabled = false;
        document.getElementById("acadbatch_enrol").disabled = true;

    });
    
    $(document).ready(function () {
        $("#enrol_course").append("<option value='' disabled selected>Select Course (s)</option>");
        $("#enrol_batch").append("<option value='' disabled selected>Select Batch</option>");
        $("#enrol_professor").append("<option value='' disabled selected>Select Professor</option>");
        $("#enrol_batch").change(function(){
            var bid = $(this).val();
            $.ajax({
                url: 'professorajax.php',
                type: 'get',
                data: {batch:bid},
                dataType: 'json',
                success:function(response){
                    if(response == null){
                        $("#enrol_course").empty();
                        $("#enrol_course").append("<option value='' disabled selected>No course to show</option>");
                    }else{
                        var len = response.length;
                        $("#enrol_course").empty();
                        $("#enrol_course").append("<option value='' disabled selected>Select Course (s)</option>");
                        for( var i = 0; i<len; i++){
                            var id = response[i]['id'];
                            var course = response[i]['course'];
                            $("#enrol_course").append("<option value='"+id+"'>"+course+"</option>");
                        }
                    }
                }
            });
        });
    });
</script>