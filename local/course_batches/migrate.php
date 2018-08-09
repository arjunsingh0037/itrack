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
require_once ('migrate_batch_form.php');

$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/course_batches/batch.php');
$PAGE->set_url($url);
//Changes by Arjun 
$currentuser = $USER->id;
$user = $DB->record_exists('trainingpartners', array('userid' => $currentuser));
if (!$user) {
    echo $OUTPUT->header();
    redirect($CFG->wwwroot.'/my','You do not have access to this page.',1,'error');
    die; 
}
$PAGE->requires->css('/local/course_batches/style/styles.css');
$addnewpromo='Migrate Students';
$PAGE->navbar->add($addnewpromo);
$PAGE->set_title($addnewpromo);
$PAGE->set_heading('Migrate Students');
echo $OUTPUT->header();

$newobj = new stdClass();
$flag = 0;
echo '<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading migrate-batch">
  	<div class="panel-heading">Migrate Students</h3>
  	</div>
  </div>
  <div class="panel-body">
    	<div class="form-group" id="migrate_batch" style="">';
			$migrate_mform = new migrate_batches_form();
			//print_object($_POST);die();
			if ($migration_data = $migrate_mform->get_data()) {
				global $USER;
				$update_batch = new stdClass();
				
				//$existing = $DB->record_exists('batches', array('id'=>$_POST['mb_batchname']));
				if ($existing = $DB->record_exists('batches', array('id'=>$_POST['mb_batchname']))) {
					$mig = new stdClass();
			        $mig->id = $_POST['mb_batchname'];
			        $mig->semester = $_POST['new_semester'];
			        $mig->semyear = $_POST['new_semester_year'];
			        $mig->timemigrated = time();
			        $mig->migrated = 1;
			        $migrated = $DB->update_record('batches', $mig);
			        if($migrated){
			        	$non_mig = $DB->get_records('course_batches',array('batchid'=>$_POST['mb_batchname']));
			        	foreach ($non_mig as $nmig) {
			        		$cmig = new stdClass();
				        	$cmig->id = $nmig->id;
					        $cmig->timemigrated = time();
					        $cmig->migrated = 1;
					        $migrated2 = $DB->update_record('course_batches', $cmig);
					        if($migrated2){
					        	$update_users = user_update_enrollment($USER->id,$nmig->batchid,$nmig->courseid);
					        }
				    	}
				    	if($update_users){
			        		$flag = 1;
				    	}
			        }
			    }
			}
			$migrate_mform->set_data($newobj);
			$migrate_mform->display();
	echo '</div>';
	if($flag == 1) {
			echo '<div id="batchsuccess">';
    		echo $OUTPUT->notification('One Batch Migrated','notifysuccess');
    		echo '</div>';
	}
  echo '</div>
</div>';
echo $OUTPUT->footer();
?>
<script type="text/javascript">

	$(document).ready(function () {
	    $("#id_programs").append("<option value='' disabled selected>Select Program</option>");
	    $("#id_streams").append("<option value='' disabled selected>Select Stream</option>");
	    $("#id_syears").append("<option value='' disabled selected>Select Year</option>");
	    $("#id_semesters").append("<option value='' disabled selected>Select Semester</option>");
	    $("#new_syear").append("<option value='' disabled selected>Select Year</option>");
	    $("#new_semester").append("<option value='' disabled selected>Select Semester</option>");
	    $("#id_programs").change(function(){
	    	$('#spinner1').css("display","block");
	        var pid = $(this).val();
	        $.ajax({
	            url: 'migrateajax.php',
	            dataType: 'json',
	            type: 'get',
	            data: {program:pid},
	            success:function(response){
	            	function implode(){
					  $('#spinner1').css("display","none");
					  $("#batch_streams").html(response);
					}
					setTimeout(implode, 500);
	            }
	        });
    	}); 
    	$(document).on('change', '#id_streams', function() {
    		$('#spinner2').css("display","block");
	        var pid = $("#id_programs").val();
	        var sid = $(this).val();
	        $.ajax({
	            url: 'migrateajax.php',
	            dataType: 'json',
	            type: 'get',
	            data: {program1:pid,stream1:sid},
	            success:function(response){
	            	function implode(){
					  $('#spinner2').css("display","none");
					  $("#batch_semyears").html(response);
					}
					setTimeout(implode, 500);
	            }
	        });
    	}); 
    	$(document).on('change', '#id_syears', function() {
    		$('#spinner3').css("display","block");
	        var pid = $("#id_programs").val();
	        var sid = $("#id_streams").val();
	        var year = $(this).val();
	        $.ajax({
	            url: 'migrateajax.php',
	            dataType: 'json',
	            type: 'get',
	            data: {program2:pid,stream2:sid,year2:year},
	            success:function(response){
	            	function implode(){
					  $('#spinner3').css("display","none");
					  $("#batch_semester").html(response);
					}
					setTimeout(implode, 500);
	            }
	        });
    	});
    	$(document).on('change', '#id_semesters', function() {
    		$('#spinner4').css("display","block");
	        var pid = $("#id_programs").val();
	        var sid = $("#id_streams").val();
	        var year = $("#id_syears").val();
	        var sem = $(this).val();
	        $.ajax({
	            url: 'migrateajax.php',
	            dataType: 'json',
	            type: 'get',
	            data: {program3:pid,stream3:sid,year3:year,sem3:sem},
	            success:function(response){
	            	function implode(){
					  $('#spinner4').css("display","none");
					  $("#batchmatch").html(response);
					}
					setTimeout(implode, 500);
	            }
	        });
    	});
    	$("#new_semester").change(function(){
	        var oldyear = $("#id_syears").val();
	        var oldsem = $("#id_semesters").val();
	        var newyear = $("#new_syear").val();
	        var newsem = $("#new_semester").val();
	        if(oldyear == newyear){
	        	if(oldsem == newsem){
	        		alert('Cannot choose same year and semester for migration');
	        		$(this).val('-1');
	        	}
	        }
    	});       
    });
</script>