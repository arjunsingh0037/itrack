<?php
require(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once ('lib.php');
require_once ('elab_probdescform.php');
$course = $DB->get_records('course');
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eLabs/eLabsProblemDescription.php');
require_once($CFG->libdir.'/adminlib.php');
$PAGE->set_url($url);
$PAGE->requires->css('/local/eLabs/style/styles.css');
require_once($CFG->dirroot . '/course/modlib.php');
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">';
$addnewpromo='eLabs Student Flow';
$PAGE->navbar->add($addnewpromo);
$PAGE->set_title($addnewpromo);
$PAGE->set_heading('Problem Description');
echo $OUTPUT->header();
$course = null;
$editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 'maxbytes'=>$CFG->maxbytes, 'trusttext'=>false, 'noclean'=>true);
$overviewfilesoptions = course_overviewfiles_options($course);

// Editor should respect category context if course context is not set.
$editoroptions['context'] = $context;
$editoroptions['subdirs'] = 0;
$course = file_prepare_standard_editor($course, 'summary', $editoroptions, null, 'course', 'summary', null);
if ($overviewfilesoptions) {
	file_prepare_standard_filemanager($course, 'overviewfiles', $overviewfilesoptions, null, 'course', 'overviewfiles', 0);
}
$args = array(
    'editoroptions' => $editoroptions
);
$newobj = new stdClass();
echo '<div class="panel panel-default">
  <div class="panel-body">';
			$academic_mform = new elab_probdesc_form(null,$args);
			if ($academic_data = $academic_mform->get_data()) {
				
				/*global $USER;
				$newvpl = new stdClass();
				$newvpl->labid = $academic_data->labid;
				$newvpl->createdby = $USER->id;
				$newvpl->timecreated = time();
				$newvpl->showweightage = $academic_data->show_weightage;
				$newvpl->allocateweightage = $academic_data->weightageFor;
				$newvpl->algorithm = $academic_data->algorithm;
				$newvpl->dataio = $academic_data->dataio;
				$newvpl->coding = $academic_data->coding;
				$newvpl->resourcefile = $academic_data->resourcefile;
				$newvpl->labname = $academic_data->labname;
				$newvpl->problem = $academic_data->lab_problem['text'];
				$newvpl->concept = $academic_data->lab_problem_solution['text'];
				$newvpl->principal = $academic_data->lab_problem_principle['text'];
				$newvpl->design = $academic_data->lab_problem_design_principle['text'];
				$newvpl->temp_prob = $academic_data->lab_problem_solution_template['text'];
				$newvpl->temp_algo = $academic_data->algorithm_solution_template['text'];
				$newvpl->temp_io = $academic_data->dataio_solution_template['text'];
				$newvpl->temp_prog = $academic_data->program_solution_template['text'];				
				$newvpl_details_added = $DB->insert_record('vpl_details',$newvpl);
				if($newvpl_details_added){
					$reurl = $CFG->wwwroot.'/local/eLabs/addlabs.php';
					echo '<script>swal("Success!", "New eLab created", "success").then(function() {
						    window.location = "addlabs.php";
						});</script>';
					
				}*/
			}
			$academic_mform->set_data($newobj);
			$academic_mform->display();	
  echo '</div>
</div>';
echo $OUTPUT->footer();
purge_all_caches();
?>
<script type="text/javascript">
	$(document).ready(function () {
		$("fieldset").removeAttr('collapsed');
    	document.getElementById("id_ac_lab").disabled = true;
    	var labid = sessionStorage.getItem('labkeyid');
		document.getElementById('sendlabid').value = labid;
		$.ajax({
            url: 'getlabajax.php',
            dataType: 'json',
            type: 'get',
            data: {labid:labid},
            success:function(response){
                document.getElementById("id_ac_lab").value = response.labname;
            }
        });
    });
</script>