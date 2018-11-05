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
require_once ('project_edit_delete_form.php');
$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
//require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eProjects/projectlist.php');
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
$addnewcomp='Project List';
$PAGE->navbar->add($addnewcomp);
$PAGE->set_title($addnewcomp);
$PAGE->set_heading($addnewcomp);
echo $OUTPUT->header();
get_projectlist();
echo '<div class="modal fade" id="showEditProjectForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel22" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel22">Edit Project</h4>

                    </div>
                    <div class="modal-body">';
                      $edit_project_form = new edit_project_form();
                      $flag = 0;
                      if ($edit_project_data = $edit_project_form->get_data()){
                      	$edit_project = new stdClass();
                      	$edit_project->id = $edit_project_data->pid;
                        $edit_project->stream = $edit_project_data->ac_stream;
						$edit_project->domain = $_POST['ac_domain'];
						$edit_project->technology = $edit_project_data->technology;
						$edit_project->has_comp = $edit_project_data->comp;
						$edit_project->type = $edit_project_data->ptype;
						$edit_project->title = $edit_project_data->title;
						$edit_project->synopsis = $edit_project_data->synopsis;
						$edit_project->duration = $edit_project_data->duration;
						$edit_project->rating = $edit_project_data->peer;
						$edit_project->weightage = $edit_project_data->weightage;
						$project_updated = $DB->update_record('project',$edit_project);
						if($project_updated){
							//$msg = 'Project Updated';
							$flag = 1;
							echo "<meta http-equiv='refresh' content='0'>";
							//redirect(new moodle_url('/local/eProjects/projectlist.php'),$msg,1,'success');
						}
                      }
                      $edit_project_form->display();
                    echo '</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    <div id="edit_success_domain" data-flag="'.$flag.'"></div>;
                </div>
            </div>
        </div>';
echo '<div class="modal fade" id="showDeleteProjectForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel11">Confirm</h4>

                    </div>
                    <div class="modal-body">';
                    $delete_project_form = new delete_project_form();
                    $flag = 0;
                    if ($delete_project = $delete_project_form->get_data()) {
                        echo "<meta http-equiv='refresh' content='0'>";
                        $deleted = $DB->delete_records('project', array('id'=>$delete_project->pid)); 
                        if($deleted){
                          $flag = 1;
                          $delete_project_form->set_data($newobj);
                        }
                    }
                    $delete_project_form->display();
                    echo '</div>
                    <div id="delete_success_project" data-flag="'.$flag.'"></div>;
                </div>
            </div>
        </div>';
echo $OUTPUT->footer();
?>
<script type="text/javascript">
	$(document).ready(function () {
        var v1 = $("#delete_success_project").attr("data-flag");
        if(v1 == 1){
            alert('Project Deleted');
        }
        var v2 = $("#edit_success_domain").attr("data-flag");
        if(v2 == 1){
            alert('Project Updated');
        }
    });
	$('#map_comp').click(function() {
		var url = $(this).data("url");
    	window.location.href = url;
    	return false;
	});
	function delete_project(id){
     $("#to_delete").val(id);
  }
  function edit_project(id){
    $("#to_edit").val(id);
    $.ajax({
        url: 'editprojectajax.php',
        type: 'get',
        data: {pid:id},
        dataType: 'json',
        success:function(response){
        	$("#enrol_stream").val(response.streamid);
        	var dm = response.domain;
        	var len = response.domain.length;
        	for( var i = 0; i<len; i++){
              var id = dm[i].id;
             	var name = dm[i].name;
              $("#enrol_domain").append("<option value='"+id+"'>"+name+"</option>");
          }
          $("#id_technology").val(response.technology);
         	$("#id_comp_"+response.comp).prop( "checked", true );
          $("#project_type").val(response.type);
		      $("#id_title").val(response.title);
          $("#id_synopsis").val(response.synopsis);
          $("#id_duration").val(response.duration);
          $("#id_peer").val(response.rating);
          $("#id_weightage").val(response.weightage);
        }
    });
  }

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
