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
require_once ('batch_enrolment_form.php');
$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/course_batches/batch.php');
$PAGE->set_url($url);
$PAGE->requires->css('/local/course_batches/style/styles.css');

$addnewpromo='Batch Assignment To Students';
$PAGE->navbar->add($addnewpromo);
$PAGE->set_title($addnewpromo);
$PAGE->set_heading('Add Batches');
echo $OUTPUT->header();
$newobj = new stdClass();
echo '<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
    <div class="form-group">
        <label style="padding-right: 15px;">Add Student To : </label>
        <label class="radio-inline"><input type="radio" name="course_type" id="id_acadbatch" value="academic">Academic Batch</label>
        <label class="radio-inline"><input type="radio" name="course_type" id="id_nacadbatch" value="non-academic">Non Academic Batch</label>
    </div>
  </div>
  <div class="panel-body">
        <div class="form-group" id="acadbatch_enrol" style="display:none;">';
            $academic_enrol_form = new academicbatch_enrol_form ();
            //print_object($_POST);die();
            if ($acadenrol_data = $academic_enrol_form->get_data()) {
                global $USER;
                // print_object($acadenrol_data);
                // print_object($_POST);die();
                $insert_ac = new stdClass();
                $insert_ac->assignedby = $USER->id;
                $insert_ac->batchid = $_POST['batchid'];
                $insert_ac->timeassigned = time();
                foreach ($_POST['stu'] as $userids) {
                    $insert_ac->userid = $userids;
                    $enrol_acad = $DB->insert_record('batch_enrolstudent',$insert_ac);
                }   
            }
            $academic_enrol_form->set_data($newobj);
            $academic_enrol_form->display();
    echo '</div>';
    if(isset($enrol_acad)) {
            echo '<div id="batchsuccess">';
            echo $OUTPUT->notification('Sudents Added to Academic Batch','notifysuccess');
            echo '</div>';
            //redirect($CFG->wwwroot . "/local/course_batches/batch.php",'Batch created');
    }
    echo '<div class="form-group"  id="nacadbatch_enrol" style="display:none;">';
            $nonacademic_enrol_form = new nonacademicbatch_enrol_form();
            if ($nonacadenrol_data = $nonacademic_enrol_form->get_data()) {
                global $USER;
                // print_object($academic_data);
                // print_object($_POST);die();
                $insert_nac = new stdClass();
                $insert_nac->batchname = $nonacadenrol_data->batchname;
                $insert_nac->batchcode = $nonacadenrol_data->batchcode;
                $insert_nac->batchtype = $nonacadenrol_data->btype;
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
            
                $enrol_nacad = $DB->insert_record('batches',$insert_nac); 
            }
            $nonacademic_enrol_form->set_data($newobj);
            $nonacademic_enrol_form->display();
    echo '</div>';
    if(isset($enrol_nacad)) {
            echo '<div id="batchsuccess">';
            echo $OUTPUT->notification('Students added to Non-academic Batch','notifysuccess');
            echo '</div>';
            //redirect($CFG->wwwroot . "/local/course_batches/batch.php",'Batch created');
    }
  echo '</div>
</div>';

// $strinscriptions = get_string('ctbatch', 'local_course_batches');
// echo $OUTPUT->heading_with_help($strinscriptions, 'course_batches', 'local_course_batches','icon',get_string('ctbatch', 'local_course_batches'));
// echo $OUTPUT->box (get_string('course_batches_info', 'local_course_batches'), 'center');
//academic batch form 

//academic_mform = new academic_batches_form();
//$academic_mform->display();
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
        $("#enrol_program").append("<option value='' disabled selected>Select Program</option>");
        $("#enrol_stream").append("<option value='' disabled selected>Select Stream</option>");
        $("#enrol_year").append("<option value='' disabled selected>Select Year</option>");
        $("#enrol_semester").append("<option value='' disabled selected>Select Semester</option>");
        $("#enrol_batch").append("<option value='' disabled selected>Select Batch</option>");
        $("#enrol_program").change(function(){
            var pid = $(this).val();
            $.ajax({
                url: 'enrolajax.php',
                type: 'get',
                data: {program:pid},
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#enrol_stream").empty();
                    $("#enrol_stream").append("<option value='' disabled selected>Select Stream</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['id'];
                        var stream = response[i]['stream'];
                        $("#enrol_stream").append("<option value='"+id+"'>"+stream+"</option>");
                    }
                }
            });
        });  
        $("#enrol_stream").change(function(){
            var sid = $(this).val();
            var sp = $("#enrol_program").val();
            $.ajax({
                url: 'enrolajax.php',
                type: 'get',
                data: {stream:sid,sprogram:sp},
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#enrol_year").empty();
                    $("#enrol_year").append("<option value='' disabled selected>Select Year</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['id'];
                        var semyear = response[i]['semyear'];
                        $("#enrol_year").append("<option value='"+id+"'>"+semyear+"</option>");
                    }
                }
            });
        }); 
        $("#enrol_year").change(function(){
            var year = $(this).val();
            var syp = $("#enrol_program").val();
            var sys = $("#enrol_stream").val();
            $.ajax({
                url: 'enrolajax.php',
                type: 'get',
                data: {year:year,syp:syp,sys:sys},
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#enrol_semester").empty();
                    $("#enrol_semester").append("<option value='' disabled selected>Select Semester</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['id'];
                        var semester = response[i]['semester'];
                        $("#enrol_semester").append("<option value='"+id+"'>"+semester+"</option>");
                    }
                }
            });
        }); 
        $("#enrol_semester").change(function(){
            var semester = $(this).val();
            var semyear = $("#enrol_year").val();
            var semp = $("#enrol_program").val();
            var sems = $("#enrol_stream").val();
            $.ajax({
                url: 'enrolajax.php',
                type: 'get',
                data: {semester:semester,semyear:semyear,semp:semp,sems:sems},
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#enrol_batch").empty();
                    $("#enrol_batch").append("<option value='' disabled selected>Select Batch</option>");
                    for( var i = 0; i<len; i++){
                        var batchid = response[i]['batchid'];
                        var batchname = response[i]['batchname'];
                        $("#enrol_batch").append("<option value='"+batchid+"'>"+batchname+"</option>");
                    }
                    $("#student_enrol").append("<hr>");
                }
            });
            $.ajax({
                url: 'tpuserajax.php',
                dataType: 'html',
                data: {semester:semester,semyear:semyear,semp:semp,sems:sems},
                dataType: 'json',
                success:function(data){
                    $("#student_enrol").html(data);
                }
            });
        });  
    });
</script>