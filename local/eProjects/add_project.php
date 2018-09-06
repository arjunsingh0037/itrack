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
require_once ('add_project_form.php');
$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
//require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eProjects/add_project.php');
$PAGE->set_url($url);
$PAGE->requires->css('/local/eProjects/style/styles.css');
//By Arjun -Permission Access
// $currentuser = $USER->id;
// $user = $DB->record_exists('trainingpartners', array('userid' => $currentuser));
// if (!$user) {
//     echo $OUTPUT->header();
//     redirect($CFG->wwwroot.'/my','You do not have access to this page.',1,'error');
//     die; 
// }
$addnewcomp='Add Project';
$PAGE->navbar->add($addnewcomp);
$PAGE->set_title($addnewcomp);
$PAGE->set_heading($addnewcomp);

$project_form = new add_project_form();
if ($data = $project_form->get_data()){
	$new_project = new stdClass();
	$new_project->stream = $data->ac_stream;
	$new_project->domain = $_POST['ac_domain'];
	$new_project->technology = $data->technology;
	$new_project->has_comp = $data->comp;
	$new_project->type = $data->ptype;
	$new_project->title = $data->title;
	$new_project->synopsis = $data->synopsis;
	$new_project->duration = $data->duration;
	$new_project->rating = $data->peer;
	$new_project->weightage = $data->weightage;
	$new_project->createdby = $USER->id;
	$new_project->timecreated = time(); 
	$project_created = $DB->insert_record('project',$new_project);
	if($project_created){
		$msg = 'Project Created';
		redirect(new moodle_url('/local/eProjects/add_project.php'),$msg,1,'success');
	}
	$project_form->reset();
}
echo $OUTPUT->header();
$map_comp = $CFG->wwwroot.'/local/eProjects/mapcomponent.php';
$listurl = $CFG->wwwroot.'/local/eProjects/projectlist.php';
echo '<div class="proj_list">
<button type="button" id="map_comp" data-url="'.$map_comp.'" class="btn btn-info">Map Component</button>
<button type="button" id="project_list" data-url="'.$listurl.'" class="btn btn-info">Project List</button></div>';
$project_form->display();
echo $OUTPUT->footer();
?>
<script type="text/javascript">
	$('#project_list').click(function() {
		var url = $(this).data("url");
    	window.location.href = url;
    	return false;
	});
	$('#map_comp').click(function() {
		var url = $(this).data("url");
    	window.location.href = url;
    	return false;
	});

	$("#project_type").prepend("<option value='' disabled selected>Select Project Type</option>");
	$("#enrol_stream").prepend("<option value='' disabled selected>Select stream</option>");
    $("#enrol_domain").prepend("<option value='' disabled selected>Select domain</option>");
	$("#enrol_stream").change(function(){
        var sid = $(this).val();
        $.ajax({
            url: 'domainajax.php',
            type: 'get',
            data: {streamid:sid},
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#enrol_domain").empty();
                $("#enrol_domain").prepend("<option value='' disabled selected>Select domain</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['id'];
                    var domain = response[i]['domain'];
                    $("#enrol_domain").append("<option value='"+id+"'>"+domain+"</option>");
                }
            }
        });
	});
</script>
