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
require_once ('assign_labs_form.php');
$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
//require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eLabs/assign_labs.php');
$PAGE->set_url($url);
$PAGE->requires->css('/local/eLabs/style/styles.css');

$addnewpromo='Assign Labs';
$PAGE->navbar->add($addnewpromo);
$PAGE->set_title($addnewpromo);
$PAGE->set_heading('Assign Labs');
echo $OUTPUT->header();
$newobj = new stdClass();
echo '<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading listhead">ASSIGN LABS</div>

  <div class="panel-body">
        <div class="form-group">
            <label style="padding-right: 15px;">Course Type </label>
            <label class="radio-inline"><input type="radio" name="course_type" id="id_acadbatch" value="Private" required>Academic</label>
            <label class="radio-inline"><input type="radio" name="course_type" id="id_nacadbatch" value="Public">Subscribed Industry</label>
        </div>
        <div class="form-group" id="acadbatch_enrol" style="display:none;">';
            $academic_assign_form = new assign_labs_form ();
            //print_object($_POST);die();
            if ($acadenrol_data = $academic_assign_form->get_data()) {
                global $USER;
                $insert_ac = new stdClass();
                $insert_ac->assignedby = $USER->id;
                $insert_ac->batchid = $_POST['batchid'];
                $insert_ac->courseid = $acadenrol_data->ac_course;
                $insert_ac->timeassigned = time();
                foreach ($_POST['stu'] as $labids) {
                    $insert_ac->labid = $labids;
                    $enrol_acad = $DB->insert_record('vpl_assignedbatches',$insert_ac);
                }   
            }
            $academic_assign_form->set_data($newobj);
            $academic_assign_form->display();
    echo '</div>';
    if(isset($enrol_acad)) {
            echo '<div id="batchsuccess">';
            echo $OUTPUT->notification('Lab Assigned to Academic Batch','notifysuccess');
            echo '</div>';
            //redirect($CFG->wwwroot . "/local/course_batches/batch.php",'Batch created');
    }
    echo '<div class="form-group"  id="nacadbatch_enrol" style="display:none;">';
            $industry_assign_form = new industry_labs_form();
            if ($nonacadenrol_data = $industry_assign_form->get_data()) {
                global $USER;
                $insert_ac = new stdClass();
                $insert_ac->assignedby = $USER->id;
                $insert_ac->batchid = $_POST['batchid'];
                $insert_ac->courseid = $nonacadenrol_data->ac_course;
                $insert_ac->timeassigned = time();
                foreach ($_POST['stu'] as $labids) {
                    $insert_ac->labid = $labids;
                    $enrol_acad = $DB->insert_record('vpl_assignedbatches',$insert_ac);
                }   
            }
            $industry_assign_form->set_data($newobj);
            $industry_assign_form->display();
    echo '</div>';
    if(isset($enrol_nacad)) {
            echo '<div id="batchsuccess2">';
            echo $OUTPUT->notification('Lab Assigned to Subscribed Batch','notifysuccess');
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
        $('#batchsuccess2').hide();
        $('#nacadbatch_enrol').show('fast');
        $('#acadbatch_enrol').hide('fast');
       
        document.getElementById("nacadbatch_enrol").disabled = false;
        document.getElementById("acadbatch_enrol").disabled = true;

    });
    
    $(document).ready(function () {
        $("#id_courses").prepend("<option value='' disabled selected>Select Course</option>");
        $("#id_courses_sub").prepend("<option value='' disabled selected>Select Course</option>");
        $("#enrol_batch").prepend("<option value='' disabled selected>Select Batch</option>");
        $("#enrol_batch_sub").prepend("<option value='' disabled selected>Select Batch</option>");
        $("#id_courses").change(function(){
            var cid = $(this).val();
            $.ajax({
                url: 'acadenrolajax.php',
                type: 'get',
                data: {crsid:cid},
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#enrol_batch").empty();
                    $("#enrol_batch").prepend("<option value='' disabled selected>Select Batch</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['batchid'];
                        var batchcode = response[i]['batchcode'];
                        $("#enrol_batch").append("<option value='"+id+"'>"+batchcode+"</option>");
                    }
                }
            });
        });  
        $("#enrol_batch").change(function(){
            var batch = $(this).val();
            $.ajax({
                url: 'acadlablistajax.php',
                dataType: 'html',
                data: {batchid:batch,batchtype:'ACAD'},
                dataType: 'json',
                success:function(data){
                    $("#student_enrol").html(data);
                }
            });
        });  

        $("#id_courses_sub").change(function(){
            var cid = $(this).val();
            $.ajax({
                url: 'acadenrolajax.php',
                type: 'get',
                data: {crsid:cid},
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#enrol_batch_sub").empty();
                    $("#enrol_batch_sub").prepend("<option value='' disabled selected>Select Batch</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['batchid'];
                        var batchcode = response[i]['batchcode'];
                        $("#enrol_batch_sub").append("<option value='"+id+"'>"+batchcode+"</option>");
                    }
                }
            });
        });  
        $("#enrol_batch_sub").change(function(){
            var batch = $(this).val();
            $.ajax({
                url: 'acadlablistajax.php',
                dataType: 'html',
                data: {batchid:batch,batchtype:'SUBS'},
                dataType: 'json',
                success:function(data){
                    $("#student_enrol2").html(data);
                }
            });
        });  
    });
</script>