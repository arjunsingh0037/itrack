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
require_once ('academic_batch_form.php');
require_once ('nonacademic_batch_form.php');
require_once ('subscribed_batch_form.php');

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
//By Arjun 
$currentuser = $USER->id;
$user = $DB->record_exists('trainingpartners', array('userid' => $currentuser));
if (!$user) {
    echo $OUTPUT->header();
    redirect($CFG->wwwroot.'/my','You do not have access to this page.',1,'error');
    die; 
}
/*if($generalfirst = $DB->record_exists('batch', array('courseid'=>$course->id))){
$generalalredy = new stdClass();
$generalalredy = $DB->get_records('batch', array('courseid' => $course->id));
$mform->set_data($generalalredy);
}
if(!isset($generalalredy)){
$generalalredy = new stdClass();
	$generalalredy->courseid=-1;
}
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/course'));
}else
if ($data = $mform->get_data(false)) {
	
	global $USER;
	$personalcontext = context_user::instance($USER->id);
	if($data->courseid == $generalalredy->courseid) {
		
		if($insertgen==true){
			
			$DB->update_record('batch', $data);
			}
	}else{

		$generalinsertid = $DB->insert_record('batch', $data);
	}
     
    if(isset($generalinsertid)) {
		if($generalinsertid) {
			redirect($CFG->wwwroot . "/local/course_batches/view_batch.php?id=".$course->id);
		}
	} 
	
}*/

$addnewpromo='Add batch';
$PAGE->navbar->add($addnewpromo);
$PAGE->set_title($addnewpromo);
$PAGE->set_heading('Add Batches');
echo $OUTPUT->header();
$newobj = new stdClass();
echo '<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
  	<div class="form-group">
	    <label style="padding-right: 15px;">Batch Type </label>
	    <label class="radio-inline"><input type="radio" name="course_type" id="id_private" value="Private">Academic</label>
	    <label class="radio-inline"><input type="radio" name="course_type" id="id_public" value="Public">Non Academic</label>
	    <label class="radio-inline"><input type="radio" name="course_type" id="id_subscribed" value="Public">Subscribed Course Batch</label>
	</div>
  </div>
  <div class="panel-body">
  <div id="id_error_batchexists"></div>
    	<div class="form-group" id="academic_batch" style="display:none;">';
			$academic_mform = new academic_batches_form();
			//print_object($_POST);die();
			if ($academic_data = $academic_mform->get_data()) {
				global $USER;
				// print_object($academic_data);
				// print_object($_POST);die();
				$insert_ab = new stdClass();
				$insert_ab->batchname = $academic_data->ac_batchname;
				$insert_ab->batchcode = $academic_data->ac_batchcode;
				$insert_ab->batchtype = $academic_data->btype;
				$insert_ab->creatorid = $USER->id;
				$insert_ab->program = $academic_data->ac_program;
				$insert_ab->stream = $_POST['ac_stream'];
				$insert_ab->semester = $academic_data->ac_semester;
				$insert_ab->semyear = $academic_data->ac_semester_year;
				$insert_ab->course = NULL;
				$insert_ab->professor = NULL;
				$insert_ab->startdate = NULL;
				$insert_ab->enddate = NULL;
				$insert_ab->batchlimit = NULL;
				$insert_ab->timecreated = time();
				
			    $creadted_ab = $DB->insert_record('batches',$insert_ab); 
			}
			$academic_mform->set_data($newobj);
			$academic_mform->display();
	echo '</div>';
	if(isset($creadted_ab)) {
			echo '<div id="batchsuccess">';
    		echo $OUTPUT->notification('Academic Batch Created','notifysuccess');
    		echo '</div>';
			//redirect($CFG->wwwroot . "/local/course_batches/batch.php",'Batch created');
	}
	
	echo '<div class="form-group"  id="nonacademic_batch" style="display:none;">';
			$nonacademic_mform = new nonacademic_batches_form();
			if ($nonacademic_data = $nonacademic_mform->get_data()) {
				global $USER;
				// print_object($academic_data);
				// print_object($_POST);die();
				$insert_nab = new stdClass();
				$insert_nab->batchname = $nonacademic_data->batchname;
				$insert_nab->batchcode = $nonacademic_data->batchcode;
				$insert_nab->batchtype = $nonacademic_data->btype;
				$insert_nab->creatorid = $USER->id;
				$insert_nab->program = NULL;
				$insert_nab->stream = NULL;
				$insert_nab->semester = NULL;
				$insert_nab->semyear = NULL;
				$insert_nab->course = NULL;
				$insert_nab->professor = NULL;
				$insert_nab->startdate = NULL;
				$insert_nab->enddate = NULL;
				$insert_nab->batchlimit = NULL;
				$insert_nab->timecreated = time();
				
			    $creadted_nab = $DB->insert_record('batches',$insert_nab); 
			}
			$nonacademic_mform->set_data($newobj);
			$nonacademic_mform->display();
	echo '</div>';
	if(isset($creadted_nab)) {
			echo '<div id="batchsuccess">';
    		echo $OUTPUT->notification('Non-academic Batch Created','notifysuccess');
    		echo '</div>';
			//redirect($CFG->wwwroot . "/local/course_batches/batch.php",'Batch created');
	}
	echo '<div class="form-group"  id="subscribed_batch" style="display:none;">';
			$subscribed_mform = new subscribed_batches_form();
			if ($subscribed_data = $subscribed_mform->get_data()) {
				global $USER;
				$insert_sb = new stdClass();
				$insert_sb->batchname = $subscribed_data->batchname;
				$insert_sb->batchcode = $subscribed_data->batchcode;
				$insert_sb->batchtype = $subscribed_data->btype;
				$insert_sb->creatorid = $USER->id;
				$insert_sb->program = null;
				$insert_sb->stream = null;
				$insert_sb->semester = null;
				$insert_sb->semyear = null;
				$insert_sb->course = $subscribed_data->course;
				$insert_sb->professor = $_POST['professor'];
				$insert_sb->startdate = $subscribed_data->batchstart;
				$insert_sb->enddate = $subscribed_data->batchend;
				$insert_sb->batchlimit = $subscribed_data->batchlimit;
				$insert_sb->timecreated = time();
				$creadted_sb = $DB->insert_record('batches',$insert_sb); 
			}
			$subscribed_mform->set_data($newobj);
			$subscribed_mform->display();
	echo '</div>';
	if(isset($creadted_sb)) {
			$batch_obj = $DB->get_record('batches',array('id'=>$creadted_sb));
			$sub_batch_to_course = new stdClass();
			$sub_batch_to_course->createdby = $USER->id;
			$sub_batch_to_course->batchid = $creadted_sb;
			$sub_batch_to_course->timecreated = time();
			$sub_batch_to_course->courseid = $batch_obj->course;
            $sub_batch_to_course->migrated = 0;
            $tocourse_sub = $DB->insert_record('course_batches',$sub_batch_to_course);
			if($tocourse_sub){
				$courses = array($batch_obj->course);
				$professor_assigned = professor_coursebatch_enrolment($batch_obj->professor,$courses);
				if($professor_assigned){
					echo '<div id="batchsuccess">';
		    		echo $OUTPUT->notification('Subscribed Batch Created','notifysuccess');
		    		echo '</div>';
				}else{
					echo '<div id="batchsuccess1">';
		    		echo $OUTPUT->notification('Error Assigning Batch To Professor','notifydanger');
		    		echo '</div>';
				}
			}else{
				echo '<div id="batchsuccess2">';
	    		echo $OUTPUT->notification('Error Creating Batch','notifydanger');
	    		echo '</div>';
			}
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

    $('#id_public').click(function () {
    	$('#batchsuccess').hide();
    	$('#batchsuccess1').hide();
    	$('#batchsuccess2').hide();
        $('#academic_batch').hide('fast');
        $('#nonacademic_batch').show('fast');
		$('#subscribed_batch').hide('fast');
      
        document.getElementById("academic_batch").disabled = true;
        document.getElementById("nonacademic_batch").disabled = false;
		document.getElementById("subscribed_batch").disabled = true;
    });

    $('#id_private').click(function () {
    	$('#batchsuccess').hide();
    	$('#batchsuccess1').hide();
    	$('#batchsuccess2').hide();
        $('#academic_batch').show('fast');
        $('#nonacademic_batch').hide('fast');
		$('#subscribed_batch').hide('fast');
       
        document.getElementById("academic_batch").disabled = false;
        document.getElementById("nonacademic_batch").disabled = true;
		document.getElementById("subscribed_batch").disabled = true;

    });
		
	$('#id_subscribed').click(function () {
		$('#batchsuccess').hide();
		$('#batchsuccess1').hide();
		$('#batchsuccess2').hide();
        $('#subscribed_batch').show('fast');
        $('#nonacademic_batch').hide('fast');
		$('#academic_batch').hide('fast');
       
        document.getElementById("subscribed_batch").disabled = false;
        document.getElementById("nonacademic_batch").disabled = true;
		document.getElementById("academic_batch").disabled = true;

    });
	$(document).ready(function () {
	    $("#id_programs").append("<option value='' disabled selected>Select Program</option>");
	    $("#id_streams").append("<option value='' disabled selected>Select Stream</option>");
	    $("#id_syears").append("<option value='' disabled selected>Select Year</option>");
	    $("#id_semesters").append("<option value='' disabled selected>Select Semester</option>");
	    $("#id_programs").change(function(){
	        var pid = $(this).val();
	        $.ajax({
	            url: 'batchajax.php',
	            type: 'get',
	            data: {bname:pid},
	            dataType: 'json',
	            success:function(response){
	                var len = response.length;
	                $("#id_streams").empty();
	                $("#id_streams").append("<option value='' disabled selected>Select Stream</option>");
	                for( var i = 0; i<len; i++){
	                    var id = response[i]['id'];
	                    var stream = response[i]['stream'];
	                    $("#id_streams").append("<option value='"+id+"'>"+stream+"</option>");
	                }
	            }
	        });
    	});
    	$("#id_courses").append("<option value='' disabled selected>Select Course</option>");
    	$("#id_professor").append("<option value='' disabled selected>Select Professor</option>");
    	$("#id_courses").change(function(){
	        var crsid = $(this).val();
	        $.ajax({
	            url: 'batchajax.php',
	            type: 'get',
	            data: {courseid:crsid},
	            dataType: 'json',
	            success:function(response){
	                var len = response.length;
	                $("#id_professor").empty();
	                $("#id_professor").append("<option value='' disabled selected>Select Professor</option>");
	                for( var i = 0; i<len; i++){
	                    var id = response[i]['id'];
	                    var name = response[i]['name'];
	                    $("#id_professor").append("<option value='"+id+"'>"+name+"</option>");
	                }
	            }
	        });
    	});

    	$("#id_semesters").change(function(){
    		var btype = $("#id_btype").val();
    		var program = $("#id_programs").val();
    		var stream = $("#id_streams").val();
    		var year = $("#id_syears").val();
	        var semester = $(this).val();
	        $.ajax({
	            url: 'batchexistajax.php',
	            type: 'post',
	            data: {btype:btype,program:program,stream:stream,year:year,semester:semester},
	            dataType: 'json',
	            success:function(response){
	            	//alert(response);
            		if(response == ''){
                		$("#id_error_batchexists").html("");
                		$("#id_ac_batchname").removeAttr('disabled');
                		$("#id_ac_batchname").val(response);
                	}else{
                		$("#id_ac_batchname").val(response);
                		$("#id_ac_batchname").attr('disabled','disabled');
                		$("#id_error_batchexists").html('<div class="alert alert-danger"><strong>Alert! </strong>Batch already exists</div>');
                	}
	            }
	        });
    	});

    	// $("#id_streams").change(function(){
	    //     var crsid = $(this).val();
	    //     $("#stream_data").val(crsid);
    	// });
			        
    });
</script>