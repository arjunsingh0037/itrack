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
require_once ('academic_elab_form.php');
require_once ('subscribed_elab_form.php');

$course = $DB->get_records('course');
$context = context_system::instance();
/*require_capability('moodle/role:assign', $context);*/
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eLabs/addlabs.php');
require_once($CFG->libdir.'/adminlib.php');
$PAGE->set_url($url);
$PAGE->requires->css('/local/eLabs/style/styles.css');
require_once($CFG->dirroot . '/course/modlib.php');
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">';
$addnewpromo='Add eLabs';
$PAGE->navbar->add($addnewpromo);
$PAGE->set_title($addnewpromo);
$PAGE->set_heading('Add eLabs');
echo $OUTPUT->header();
$newobj = new stdClass();

echo '<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading listhead">LAB DETAILS</div>
  <div class="panel-body">
	  	<div class="form-group">
		    <label style="padding-right: 15px;">Course Type </label>
		    <label class="radio-inline"><input type="radio" name="course_type" id="id_private" value="Private" required>Academic</label>
		    <label class="radio-inline"><input type="radio" name="course_type" id="id_subscribed" value="Public">Subscribed Industry</label>
		</div>
  		<div id="id_error_batchexists"></div>
    	<div class="form-group" id="academic_batch" style="display:none;">';
			$academic_mform = new academic_elabs_form();
			//print_object($_POST);die();
			if ($academic_data = $academic_mform->get_data()) {
				global $USER;
				$course = $DB->get_record('course',array('id'=>$academic_data->ac_course));
				$intanceob = $DB->get_records('vpl');
				foreach ($intanceob as $key => $value) {
					$currentkey = $value->id;
				}
				//print_object($academic_data->radioar2['type']);
				//creating new instance  of mdl_vpl in course
				$newvpl = new stdClass();
				$newvpl->course = $academic_data->ac_course;
				$newvpl->name = 'LAB_00'.($currentkey+1);
				$newvpl->shortdescription = 'ACAD';
				$newvpl->introformat = 1;
				$newvpl->startdate = $academic_data->availfrom;
				$newvpl->duedate = $academic_data->availupto;
				$newvpl->maxfiles = 1;
				$newvpl->maxfilesize = 0;
				$newvpl->grade = 100;
				$newvpl->visiblegrade = 1;
				$newvpl->usevariations = 0;
				$newvpl->basedon = 0;
				$newvpl->run = 1;
				$newvpl->debug = 1;
				$newvpl->evaluate = 1;
				$newvpl->evaluateonsubmission = 0;
				$newvpl->automaticgrading = 0;
				$newvpl->restrictededitor = 0;
				$newvpl->example = $academic_data->testlab;
				//arjun-later - for begineer/expert level
				$newvpl->worktype = 0;
				$newvpl->emailteachers = 0;
				$newvpl->timemodified = time();
				$newvpl->freeevaluations = 0;
				$newvpl->reductionbyevaluation = 0;
				$newvpl->runscript = $academic_data->radioar2['type'];
				$newvpl->debugscript = $academic_data->radioar2['type'];
				$newvpl_created = $DB->insert_record('vpl',$newvpl);
				if($newvpl_created){
					$vpl = $DB->get_record('vpl',array('id'=>$newvpl_created),'id,course');
					$sec_obj = $DB->get_record('course_sections',array('course'=>$vpl->course,'section'=>0),'id,section');
					$newcrsmodule = new stdClass();
					$newcrsmodule->course = $vpl->course;
					$newcrsmodule->module = 24;
					$newcrsmodule->instance = $vpl->id;
					$newcrsmodule->section = $sec_obj->id;
					$newcrsmodule->added = time(); 
					$newcrsmodule->score = 0;
					$newcrsmodule->indent = 0;
					$newcrsmodule->visible = 1; 
					$newcrsmodule->visibleoncoursepage = 1;
					$newcrsmodule->visibleold = 1; 
					$newcrsmodule->groupmode = 0; 
					$newcrsmodule->groupingid = 0; 
					$newcrsmodule->completion = 1; 
					$newcrsmodule->completionview = 0; 
					$newcrsmodule->completionexpected = 0; 
					$newcrsmodule->showdescription = 0; 
					$newcrsmodule->deletioninprogress = 0; 
					$newcrsmodule->visible = 1; 
					$newcrsmodule_created = $DB->insert_record('course_modules',$newcrsmodule);
					if($newcrsmodule_created){
						$crsmodule_data = $DB->get_record('course_modules',array('id'=>$newcrsmodule_created),'id,course');
						//creating new context & updating mdl_context
						$ctx_obj = $DB->get_record('context',array('contextlevel'=>50,'instanceid'=>$crsmodule_data->course),'id,path,depth');
						$new_ctx = new stdClass();
						$new_ctx->contextlevel = 70;
						$new_ctx->instanceid = $crsmodule_data->id;
						$new_ctx->path = $ctx_obj->path;
						$new_ctx->depth = $ctx_obj->depth;
						$new_ctx_created = $DB->insert_record('context',$new_ctx);
						if($new_ctx_created){
							$ctxobj = $DB->get_record('context',array('id'=>$new_ctx_created),'id,path,depth');
							$update_ctx = new stdClass();
							$update_ctx->id = $new_ctx_created;
							$update_ctx->path = $ctxobj->path.'/'.$new_ctx_created;
							$update_ctx->depth = $ctxobj->depth + 1;
							$updated_ctx = $DB->update_record('context',$update_ctx);
						}

						// updating mdl_course_section sequence column
						$mod_obj = $DB->get_record('course_modules',array('id'=>$newcrsmodule_created),'id,section');
						$sect_obj = $DB->get_record('course_sections',array('id'=>$mod_obj->section),'id,sequence');
						$newsequence = new stdClass();
						$newsequence->id = $mod_obj->section;
						$newsequence->sequence = $sect_obj->sequence.','.$mod_obj->id;
						$update_section = $DB->update_record('course_sections',$newsequence);
						if($update_section){
							/*echo '<script>swal("Success!", "New eLab created", "success")</script>';*/
							echo "<script>sessionStorage.setItem('labkeyid', '".$newvpl_created."');</script>";
							$url = $CFG->wwwroot.'/local/eLabs/alabdetails.php';
							redirect($url);
						}
					}
				}
			}
			$academic_mform->set_data($newobj);
			$academic_mform->display();

	echo '</div>';
	/*if(isset($creadted_ab)) {
			echo '<div id="batchsuccess">';
    		echo $OUTPUT->notification('Academic Batch Created','notifysuccess');
    		echo '</div>';
			//redirect($CFG->wwwroot . "/local/course_batches/batch.php",'Batch created');
	}*/
	echo '<div class="form-group"  id="subscribed_batch" style="display:none;">';
			$args = array(
			    'labid' => 2
			);
			//$reurl = new moodle_url('/local/eLabs/labdetails.php');
			$subscribed_mform = new subscribed_elabs_form();
			if ($subscribed_data = $subscribed_mform->get_data()) {
				global $USER;
				$course = $DB->get_record('course',array('id'=>$subscribed_data->ac_course));
				$intanceob = $DB->get_records('vpl');
				foreach ($intanceob as $key => $value) {
					$currentkey = $value->id;
				}
				//creating new instance  of mdl_vpl in course
				$newvpl = new stdClass();
				$newvpl->course = $subscribed_data->ac_course;
				$newvpl->name = 'LAB_00'.($currentkey+1);
				$newvpl->shortdescription = 'SUBS';
				$newvpl->introformat = 1;
				$newvpl->startdate = $subscribed_data->availfrom;
				$newvpl->duedate = $subscribed_data->availupto;
				$newvpl->maxfiles = 1;
				$newvpl->maxfilesize = 0;
				$newvpl->grade = 100;	
				$newvpl->visiblegrade = 1;
				$newvpl->usevariations = 0;
				$newvpl->basedon = 0;
				$newvpl->run = 1;
				$newvpl->debug = 1;
				$newvpl->evaluate = 1;
				$newvpl->evaluateonsubmission = 0;
				$newvpl->automaticgrading = 0;
				$newvpl->restrictededitor = 0;
				$newvpl->example = 0;
				$newvpl->worktype = 0;
				$newvpl->emailteachers = 0;
				$newvpl->timemodified = time();
				$newvpl->freeevaluations = 0;
				$newvpl->reductionbyevaluation = 0;
				$newvpl->runscript = $subscribed_data->radioar2['type'];
				$newvpl->debugscript = $subscribed_data->radioar2['type'];
				$newvpl_created = $DB->insert_record('vpl',$newvpl);
				if($newvpl_created){
					$vpl = $DB->get_record('vpl',array('id'=>$newvpl_created),'id,course');
					$sec_obj = $DB->get_record('course_sections',array('course'=>$vpl->course,'section'=>0),'id,section');
					$newcrsmodule = new stdClass();
					$newcrsmodule->course = $vpl->course;
					$newcrsmodule->module = 24;
					$newcrsmodule->instance = $vpl->id;
					$newcrsmodule->section = $sec_obj->id;
					$newcrsmodule->added = time(); 
					$newcrsmodule->score = 0;
					$newcrsmodule->indent = 0;
					$newcrsmodule->visible = 1; 
					$newcrsmodule->visibleoncoursepage = 1;
					$newcrsmodule->visibleold = 1; 
					$newcrsmodule->groupmode = 0; 
					$newcrsmodule->groupingid = 0; 
					$newcrsmodule->completion = 1; 
					$newcrsmodule->completionview = 0; 
					$newcrsmodule->completionexpected = 0; 
					$newcrsmodule->showdescription = 0; 
					$newcrsmodule->deletioninprogress = 0; 
					$newcrsmodule->visible = 1; 
					$newcrsmodule_created = $DB->insert_record('course_modules',$newcrsmodule);
					if($newcrsmodule_created){
						$crsmodule_data = $DB->get_record('course_modules',array('id'=>$newcrsmodule_created),'id,course');
						//creating new context & updating mdl_context
						$ctx_obj = $DB->get_record('context',array('contextlevel'=>50,'instanceid'=>$crsmodule_data->course),'id,path,depth');
						$new_ctx = new stdClass();
						$new_ctx->contextlevel = 70;
						$new_ctx->instanceid = $crsmodule_data->id;
						$new_ctx->path = $ctx_obj->path;
						$new_ctx->depth = $ctx_obj->depth;
						$new_ctx_created = $DB->insert_record('context',$new_ctx);
						if($new_ctx_created){
							$ctxobj = $DB->get_record('context',array('id'=>$new_ctx_created),'id,path,depth');
							$update_ctx = new stdClass();
							$update_ctx->id = $new_ctx_created;
							$update_ctx->path = $ctxobj->path.'/'.$new_ctx_created;
							$update_ctx->depth = $ctxobj->depth + 1;
							$updated_ctx = $DB->update_record('context',$update_ctx);
						}

						// updating mdl_course_section sequence column
						$mod_obj = $DB->get_record('course_modules',array('id'=>$newcrsmodule_created),'id,section');
						$sect_obj = $DB->get_record('course_sections',array('id'=>$mod_obj->section),'id,sequence');
						$newsequence = new stdClass();
						$newsequence->id = $mod_obj->section;
						$newsequence->sequence = $sect_obj->sequence.','.$mod_obj->id;
						$update_section = $DB->update_record('course_sections',$newsequence);
						if($update_section){
							/*echo '<script>swal("Success!", "New eLab created", "success")</script>';*/
							echo "<script>sessionStorage.setItem('labkeyid', '".$newvpl_created."');</script>";
							$url = $CFG->wwwroot.'/local/eLabs/alabdetails.php';
							redirect($url);
						}
					}
				}	 
			}
			$subscribed_mform->set_data($newobj);
			$subscribed_mform->display();
	echo '</div>';
  echo '</div>
</div>';

// $strinscriptions = get_string('ctbatch', 'local_course_batches');
// echo $OUTPUT->heading_with_help($strinscriptions, 'course_batches', 'local_course_batches','icon',get_string('ctbatch', 'local_course_batches'));
// echo $OUTPUT->box (get_string('course_batches_info', 'local_course_batches'), 'center');
//academic batch form 

//academic_mform = new academic_batches_form();
//$academic_mform->display();
echo $OUTPUT->footer();
purge_all_caches();
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
	    /*$("#id_programs").append("<option value='' disabled selected>Select Program</option>");
	    $("#id_streams").append("<option value='' disabled selected>Select Stream</option>");
	    $("#id_syears").append("<option value='' disabled selected>Select Year</option>");
	    $("#id_semesters").append("<option value='' disabled selected>Select Semester</option>");*/
	    /*$("#id_programs").change(function(){
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
    	});*/
    	$("#id_courses").prepend("<option value='' disabled selected>Select Course</option>");
    	$("#id_subcourses").prepend("<option value='' disabled selected>Select Course</option>");
    	/*$("#id_courses").change(function(){
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
    	});*/

    	/*$("#id_semesters").change(function(){
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
    	});*/

    	// $("#id_streams").change(function(){
	    //     var crsid = $(this).val();
	    //     $("#stream_data").val(crsid);
    	// });
			        
    });
</script>