<?php

require_once('../../config.php');
require_once('lib.php');

$PAGE->set_url('/local/eProects/request.php');
require_login();
$currentuser = $USER->id;
$context = context_user::instance($currentuser, MUST_EXIST);
$PAGE->set_context($context);
$PAGE->requires->css('/local/eProjects/style/styles.css');
$PAGE->set_pagelayout('custom');
$PAGE->set_pagetype('user-profile');
if (!(user_has_role_assignment($USER->id, 12, SYSCONTEXTID))) {
    echo $OUTPUT->header();
    redirect($CFG->wwwroot.'/my','You do not have access to this page.',2,null,'error');
    die; 
}
$title = 'Project Requests';
$PAGE->blocks->add_region('content');
$PAGE->set_title("$title");
echo $OUTPUT->header();
prof_project_requestlist();
echo '<div class="modal fade" id="showSynopsis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modal-responsive">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel11">Synopsis Detail</h4>

                    </div>
                    <div id="delstu" class=""></div>
                    <div id="synopsisdetails" class="modal-body">
                    </div>
                </div>
            </div>
        </div>';
echo '<div class="modal fade" id="showSingleStudentList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel66" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-responsive">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel66">Student Detail</h4>

            </div>
            <div id="gpsinglestulist" class="modal-body">
            </div>
        </div>
    </div>
</div>';
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
        // $.ajax({
        //     url: 'gpstulistajax.php',
        //     dataType: 'json',
        //     type: 'get',
        //     data: {groupid:id},
        //     success:function(response){
        //           $("#gpsinglestulist").html(response);
        //     }
        // });
    }
</script>