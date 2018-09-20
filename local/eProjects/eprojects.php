<?php

require_once('../../config.php');
require_once('lib.php');

$PAGE->set_url('/local/eProects/eprojects.php');
require_login();
$currentuser = $USER->id;
$context = context_user::instance($currentuser, MUST_EXIST);
$PAGE->set_context($context);
$PAGE->requires->css('/local/eProjects/style/styles.css');
$PAGE->set_pagelayout('custom');
$PAGE->set_pagetype('user-profile');
// if (!(user_has_role_assignment($USER->id, 12, SYSCONTEXTID))) {
//     echo $OUTPUT->header();
//     redirect($CFG->wwwroot.'/my','You do not have access to this page.',2,null,'error');
//     die; 
// }
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">';
$title = 'Project Requests';
$PAGE->blocks->add_region('content');
$PAGE->set_title("$title");
echo $OUTPUT->header();
stu_projectlist();
echo $OUTPUT->footer();
?>
<script type="text/javascript">
    function view_synopsis(id){
        $.ajax({
            url: 'synopsisajax.php',
            dataType: 'json',
            type: 'get',
            data: {projectid:id},
            success:function(response){
                  $("#synopsisdetails").html(response);
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
    }
</script>