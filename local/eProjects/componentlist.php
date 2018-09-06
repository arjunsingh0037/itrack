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
require_once ('component_edit_delete_form.php');
$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
//require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eProjects/componentlist.php');
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
$addnewcomp='Component List';
$PAGE->navbar->add($addnewcomp);
$PAGE->set_title($addnewcomp);
$PAGE->set_heading($addnewcomp);
echo $OUTPUT->header();
get_componentlist();
echo '<div class="modal fade" id="showEditComponentForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel22" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel22">Edit Project</h4>

                    </div>
                    <div class="modal-body">';
                      $edit_component_form = new edit_component_form();
                      $flag = 0;
                      if ($edit_component_data = $edit_component_form->get_data()){
                      		print_object($edit_component_data);
	                      	$edit_comp = new stdClass();
	                      	$edit_comp->id = $edit_component_data->cid;
	                        $edit_comp->streamid = $edit_component_data->ac_stream;
							$edit_comp->name = $edit_component_data->name;
							$edit_comp->price = $edit_component_data->price;
							$comp_updated = $DB->update_record('component',$edit_comp);
							if($comp_updated){
								//$msg = 'Project Updated';
								$flag = 1;
								echo "<meta http-equiv='refresh' content='0'>";
								//redirect(new moodle_url('/local/eProjects/projectlist.php'),$msg,1,'success');
							}
                      }
                      $edit_component_form->display();
                    echo '</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    <div id="edit_success_component" data-flag="'.$flag.'"></div>;
                </div>
            </div>
        </div>';
echo '<div class="modal fade" id="showDeleteComponentForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel11">Confirm</h4>

                    </div>
                    <div class="modal-body">';
                    $delete_component_form = new delete_component_form();
                    $flag = 0;
                    if ($delete_component = $delete_component_form->get_data()) {
                        echo "<meta http-equiv='refresh' content='0'>";
                        $deleted = $DB->delete_records('component', array('id'=>$delete_component->cid)); 
                        if($deleted){
                          $flag = 1;
                          $delete_component_form->set_data($newobj);
                        }
                    }
                    $delete_component_form->display();
                    echo '</div>
                    <div id="delete_success_component" data-flag="'.$flag.'"></div>;
                </div>
            </div>
        </div>';
echo $OUTPUT->footer();
?>
<script type="text/javascript">
	$(document).ready(function () {
        var v1 = $("#edit_success_component").attr("data-flag");
        if(v1 == 1){
            alert('Component Updated');
        }
        var v2 = $("#delete_success_component").attr("data-flag");
        if(v2 == 1){
            alert('Component Deleted');
        }
    });
	$('#add_comp').click(function() {
		var url = $(this).data("url");
    	window.location.href = url;
    	return false;
	});
	function edit_component(id){
	    $("#to_edit_comp").val(id);
	    $.ajax({
	        url: 'editcomponentajax.php',
	        type: 'get',
	        data: {cid:id},
	        dataType: 'json',
	        success:function(response){
	          $("#enrol_stream").val(response.streamid);
	          $("#id_name").val(response.name);
	          $("#id_price").val(response.price);
	        }
	    });
  	}
  	function delete_component(id){
     	$("#to_delete_comp").val(id);
  	}
</script>

