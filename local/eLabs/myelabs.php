<?php

require_once('../../config.php');
require_once('lib.php');

$PAGE->set_url('/local/eLabs/lablists.php');
require_login();
$currentuser = $USER->id;
$context = context_user::instance($currentuser, MUST_EXIST);
$PAGE->set_context($context);
$PAGE->requires->css('/local/eLabs/style/styles.css');
$PAGE->set_pagelayout('custom');
$PAGE->set_pagetype('user-profile');
/*if (!(user_has_role_assignment($USER->id, 12, SYSCONTEXTID))) {
    echo $OUTPUT->header();
    redirect($CFG->wwwroot.'/my','You do not have access to this page.',2,null,'error');
    die; 
}*/
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
<script type="text/javascript">
    function view_acadlab_details(id){
        $.ajax({
            url: 'labajax.php',
            dataType: 'json',
            type: 'get',
            data: {alabid:id},
            success:function(response){
                  $("#acad_viewdetails_list").html(response);
            }
        });
    }
    function view_subslab_details(id){
        $.ajax({
            url: 'labajax.php',
            dataType: 'json',
            type: 'get',
            data: {slabid:id},
            success:function(response){
                  $("#subs_viewdetails_list").html(response);
            }
        });
    }
    function view_acadlab_students(id){
        $.ajax({
            url: 'labajaxlink.php',
            dataType: 'json',
            type: 'get',
            data: {alabid:id},
            success:function(response){
                  $("#gpsinglestudent").html(response);
            }
        });
    }
</script>
<?php
$title = 'eLabs List';
$PAGE->blocks->add_region('content');
$PAGE->set_title("$title");
echo $OUTPUT->header();
student_elabsist();
//Academic button click views
echo '<div class="modal fade" id="acad_assignedBatchStudent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel66" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-responsive">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel66">'.$USER->username.' - eLabs Access Details : </h4>

            </div>
            <div id="gpsinglestudent" class="modal-body">
            </div>
        </div>
    </div>
</div>';

echo '<div class="modal fade" id="acad_viewdetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel77" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-responsive" style="width:150%">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel77">Academic Lab Details</h4>

            </div>
            <div id="acad_viewdetails_list" class="modal-body">
            </div>
        </div>
    </div>
</div>';

//Subscribed elab button view
echo '<div class="modal fade" id="subs_assignedBatch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel66" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-responsive">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel66">Subscribed Lab Assigned Batch</h4>

            </div>
            <div id="gpsinglestulist" class="modal-body">
            </div>
        </div>
    </div>
</div>';

echo '<div class="modal fade" id="subs_viewdetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel77" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-responsive" style="width:150%">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel77">Subscribed Lab Details</h4>

            </div>
            <div id="subs_viewdetails_list" class="modal-body">
            </div>
        </div>
    </div>
</div>';

// Acad Edit/deleyte lab button clicks
echo '<div class="modal fade" id="showEditACADForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel22" aria-hidden="true">
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
echo '<div class="modal fade" id="showDeleteACADForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" aria-hidden="true">
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
// Subs Edit/deleyte lab button clicks
echo '<div class="modal fade" id="showEditSUBSForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel22" aria-hidden="true">
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
echo '<div class="modal fade" id="showDeleteSUBSForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" aria-hidden="true">
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
    /*function view_acadlab_details(id){
        $.ajax({
            url: 'labajax.php',
            dataType: 'json',
            type: 'get',
            data: {labid:id},
            success:function(response){
                  $("#acad_viewdetails_list").html(response);
            }
        });
    }
    function view_single_students(id){
        $.ajax({
            url: 'stulistajax.php',
            dataType: 'json',
            type: 'get',
            data: {sgroupid:id},
            success:function(response){
                  $("#gpsinglestulist").html(response);
            }
        });
    }
    function view_group_students(id){
        $.ajax({
            url: 'stulistajax.php',
            dataType: 'json',
            type: 'get',
            data: {groupid:id},
            success:function(response){
                  $("#gpgroupstulist").html(response);
            }
        });
    }
    function approve_group(id){
        $.ajax({
            url: 'approvalajax.php',
            dataType: 'json',
            type: 'get',
            data: {groupid:id},
            success:function(response){
                if(response.status == 1){
                    swal("Success!", "Group Request Approved", "success");
                    setTimeout(function() {
					    location.reload();
					}, 2000);
                }else{
                    swal("Error!", "Cannot Approve Request", "error");
                }
            }
        });
    }
    function reject_group(id){
        $.ajax({
            url: 'rejectajax.php',
            dataType: 'json',
            type: 'get',
            data: {groupid:id},
            success:function(response){
                if(response.status == 2){
                    swal("Success!", "Group Request Declined", "success");
                    setTimeout(function() {
					    location.reload();
					}, 2000);
                }else{
                    swal("Error!", "Cannot Decline Request", "error");
                }
            }
        });
    }
    function approve_request(id){
        $.ajax({
            url: 'approvalajax.php',
            dataType: 'json',
            type: 'get',
            data: {projectid:id},
            success:function(response){
                if(response.status == 1){
                    swal("Success!", "Project Request Approved", "success");
                    setTimeout(function() {
					    location.reload();
					}, 2000);
                }else{
                    swal("Error!", "Cannot Approve Project", "error");
                }
            }
        });
    }
    function reject_request(id){
        $.ajax({
            url: 'rejectajax.php',
            dataType: 'json',
            type: 'get',
            data: {projectid:id},
            success:function(response){
                if(response.status == 2){
                    swal("Success!", "Group Request Declined", "success");
                    setTimeout(function() {
					    location.reload();
					}, 2000);
                }else{
                    swal("Error!", "Cannot Decline Request", "error");
                }
            }
        });
    }*/
</script>
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