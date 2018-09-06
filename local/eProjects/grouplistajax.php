<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$groupid = optional_param('group', null, PARAM_INT);
$pname = optional_param('program', null, PARAM_RAW);
$pname1 = optional_param('program1', null, PARAM_RAW);
$sname1 = optional_param('stream1', 0, PARAM_INT);
$pname2 = optional_param('program2', null, PARAM_RAW);
$sname2 = optional_param('stream2', 0, PARAM_INT);
$year2 = optional_param('year2', 0, PARAM_INT);
$pname3 = optional_param('program3', null, PARAM_RAW);
$sname3 = optional_param('stream3', 0, PARAM_INT);
$year3 = optional_param('year3', 0, PARAM_INT);
$sem3 = optional_param('sem3', 0, PARAM_INT);


$content = ''; 
if($pname != null){
	$sql = "SELECT DISTINCT stream from {batches} WHERE program = ? AND batchtype= 'ACAD'";
	$streamss = $DB->get_records_sql($sql,array($pname));
	$content .= '<div class="col-md-3">
			        <span class="pull-xs-right text-nowrap">
			        </span>
			        <label class="col-form-label d-inline " for="id_streams">
			            Stream
			        </label>
			    </div>
			    <div class="col-md-9 form-inline felement" data-fieldtype="select">
			        <select class="custom-select" name="mb_stream" id="id_streams" required="required">
			            <option value="" disabled="" selected="">Select Stream</option>';
			            foreach ($streamss as $str) {
			            	$streamname = $DB->get_record('program_stream',array('id'=>$str->stream),'stream');
							$content .= '<option value="'.$str->stream.'">'.$streamname->stream.'</option>';
						} 
	$content .= '</select>
			        <div class="form-control-feedback" id="id_error_mb_stream" style="display: none;">
			        </div>
			    </div>'; 
}elseif($sname1 != 0){
	$sql = "SELECT DISTINCT semyear from {batches} WHERE program = ? AND stream = ? AND batchtype= 'ACAD'";
	$syears = $DB->get_records_sql($sql,array($pname1,$sname1));
	$content .= '<div class="col-md-3">
			        <span class="pull-xs-right text-nowrap">
			        </span>
			        <label class="col-form-label d-inline " for="id_syears">
			            Semester Year
			        </label>
			    </div>
			    <div class="col-md-9 form-inline felement" data-fieldtype="select">
			        <select class="custom-select" name="mb_semester_year" id="id_syears" required="required">
			            <option value="" disabled="" selected="">Select Year</option>';
			            foreach ($syears as $syr) {
							$content .= '<option value="'.$syr->semyear.'">'.$syr->semyear.'</option>';
						} 
	$content .= '</select>
			        <div class="form-control-feedback" id="id_error_mb_semester_year" style="display: none;">
			        </div>
			    </div>';
}elseif($year2 != 0){
	$sql = "SELECT DISTINCT semester from {batches} WHERE program = ? AND stream = ? AND semyear = ? AND batchtype= 'ACAD'";
	$semesters = $DB->get_records_sql($sql,array($pname2,$sname2,$year2));
	$content .= '<div class="col-md-3">
			        <span class="pull-xs-right text-nowrap">
			        </span>
			        <label class="col-form-label d-inline " for="id_semesters">
			            Semester
			        </label>
			    </div>
			    <div class="col-md-9 form-inline felement" data-fieldtype="select">
			        <select class="custom-select" name="mb_semester" id="id_semesters" required="required">
			            <option value="" disabled="" selected="">Select Semester</option>';
			            foreach ($semesters as $sem) {
							$content .= '<option value="'.$sem->semester.'">'.$sem->semester.'</option>';
						} 
	$content .= '</select>
			        <div class="form-control-feedback" id="id_error_mb_semester" style="display: none;">
			        </div>
			    </div>';
}elseif($sem3 != 0){
	$students = array();
	$sql = "SELECT id,batchname,batchcode from {batches} WHERE program = ? AND stream = ? AND semyear = ? AND semester = ? AND batchtype= 'ACAD'";
	$batches = $DB->get_records_sql($sql,array($pname3,$sname3,$year3,$sem3));
	$content .= '<div class="col-md-3">
			        <span class="pull-xs-right text-nowrap">
			        </span>
			        <label class="col-form-label d-inline " for="id_batches">
			            Batch
			        </label>
			    </div>
			    <div class="col-md-9 form-inline felement" data-fieldtype="select">
			        <select multiple class="custom-select" name="mb_studentname[]" id="id_students" required="required">
			            <option value="" disabled="" selected="">Select Student (s)</option>';
			            foreach ($batches as $bat) {
			            	$students = $DB->get_records('batch_enrolstudent',array('batchid'=>$bat->id));
			            	foreach ($students as $stu) {
			            		$student_name = $DB->get_record('user',array('id'=>$stu->userid),'firstname');
			            		if($DB->record_exists('group_to_student',array('studentid'=>$stu->userid,'groupid'=>$groupid,'program'=>$pname3,'stream'=>$sname3,'year'=>$year3,'sem'=>$sem3))){
			            			$content .= '<option value="'.$stu->userid.'" disabled>'.$student_name->firstname.'</option>';
			            		}else{
			            			$content .= '<option value="'.$stu->userid.'">'.$student_name->firstname.'</option>';
			            		}
			            	}	
						} 
	$content .= '</select>
			        <div class="form-control-feedback" id="id_error_mb_studentname" style="display: none;">
			        </div>
			    </div>';
}
// elseif($courseid != 0){
// 	$tpap = $DB->get_records('tp_useruploads',array('creatorid'=>$USER->id,'roletype'=>'professor'));
//     foreach ($tpap as $tpa) {
//         $tp_user = $DB->get_record('user',array('id'=>$tpa->userid),'id,firstname');
//         $streams[] = array('id'=>$tp_user->id,'name'=>$tp_user->firstname);
//     }
// }

//$streams[] = array('id'=>'22','stream'=>'aadadadaddad');
echo json_encode($content);




