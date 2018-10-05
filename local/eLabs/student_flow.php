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
$context = context_system::instance();
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eLabs/student_flow.php');
require_once($CFG->libdir.'/adminlib.php');
$PAGE->set_url($url);
$PAGE->requires->css('/local/eLabs/style/styles.css');
require_once($CFG->dirroot . '/course/modlib.php');
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">';
$addnewpromo='eLabs Student Flow';
$PAGE->navbar->add($addnewpromo);
$PAGE->set_title($addnewpromo);
$PAGE->set_heading('eLabs Student Flow');
echo $OUTPUT->header();
$newobj = new stdClass();
$lab = $DB->get_record('vpl',array('id'=>$_POST['labid']),'id,name');
$lab_det = $DB->get_record('vpl_details',array('labid'=>$_POST['labid']),'id,problem,concept,principal,design');
// print_object($_POST);
$url = $CFG->wwwroot.'/mod/vpl/forms/edit.php?id='.$_POST['modid'].'&userid='.$USER->id;

echo '<div class="panel panel-default">
  		<div class="panel-heading listhead">
  			PROBLEM STATEMENT
  		</div>
  		<div class="panel-body">
  			<div id="id_error_batchexists"></div>

				<div class="row">
					<div class="col-md-12">
						<div class="blockquote-box clearfix">
				    		<div class="square pull-left">
				        		<span class="glyphicon glyphicon-cog glyphicon-lg"></span>
				    		</div>
				    		<h4>For '.$lab->name.'</h4>
						    <p>'.$lab_det->problem.'</p>
						</div>
						<p><strong class="text-danger">Check You have the following Prerequisite Knowledge to arrive at a Solution for the Problem!</strong></p>
						<div class="blockquote-box blockquote-primary clearfix">
						    <div class="square pull-left">
						        <span class="glyphicon glyphicon-user glyphicon-lg"></span>
						    </div>
						    <h4>Concepts that help you arrive at Solution</h4>
						    <p>'.$lab_det->concept.'</p>
						</div>
						<div class="blockquote-box blockquote-success clearfix">
						    <div class="square pull-left">
						        <span class="glyphicon glyphicon-edit glyphicon-lg"></span>
						    </div>
						    <h4>Theoretical Foundation to consider for Problem Solution</h4>
						    <p>'.$lab_det->principal.'</p>
						</div>
						<div class="blockquote-box blockquote-info clearfix">
						    <div class="square pull-left">
						        <span class="glyphicon glyphicon-phone glyphicon-lg"></span>
						    </div>
						    <h4>Design (Coding) Principles that you must consider for Program Coding</h4>
						    <p>'.$lab_det->design.'</p>
						</div>
					</div>
				</div>
				
				<div class="box-footer">

					
					<form action="eLabsProblemDescription.php" method="post" class="pull-right" style="margin-left: 5px;">
	                    <input type="hidden" name="labid" value="'.$_POST['labid'].'">
	                    <input type="hidden" name="modid" value="'.$_POST['modid'].'">
	                    <button type="submit" name="workflow" class="btn bg-light-blue btn-xs">Continue to Workflow</button>
	                </form>

                    <button type="submit" class="btn bg-light-blue pull-right" name="startelab" onclick="timerAlert();" value="eLabsCompile"><a class="nextbutton" href="'.$url.'" >Continue Coding</a></button>	
                </div>
		</div>
	</div>';


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