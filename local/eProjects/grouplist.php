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
require_once ('grouplist_form.php');
$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
//require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eProjects/grouplist.php');
$PAGE->set_url($url);
$PAGE->requires->css('/local/eProjects/style/styles.css');
//By Arjun -Permission Access
$currentuser = $USER->id;
// $user = $DB->record_exists('trainingpartners', array('userid' => $currentuser));
// if (!$user) {
//     echo $OUTPUT->header();
//     redirect($CFG->wwwroot.'/my','You do not have access to this page.',1,'error');
//     die; 
// }
$addnewpromo='Assign Group';
$PAGE->navbar->add($addnewpromo);
$PAGE->set_title($addnewpromo);
$PAGE->set_heading($addnewpromo);

$newobj = new stdClass();
$glist_form = new grouplist_form();
if ($glist_data = $glist_form->get_data()) {
   
    global $USER;
    $insert_gts = new stdClass();
    $insert_gts->groupid = $glist_data->mb_group;
    $insert_gts->program = $glist_data->mb_program;
    $insert_gts->stream = $_POST['mb_stream'];
    $insert_gts->year = $_POST['mb_semester_year'];
    $insert_gts->sem = $_POST['mb_semester'];
    $insert_gts->createdby = $USER->id;
    $insert_gts->timecreated = time();

    foreach ($_POST['mb_studentname'] as $pj) {
        $insert_gts->studentid = $pj;
        if($pj == $USER->id){
            $insert_gts->status = 1;
        }else{
            $insert_gts->status = 0;
        }
        $group_assign = $DB->insert_record('group_to_student',$insert_gts);
    }  
}
if(isset($group_assign)){
    $msg = 'Group Assigned To Students';
    redirect(new moodle_url('/local/eProjects/grouplist.php'),$msg,1,'success');
}
echo $OUTPUT->header();
$glist_form->set_data($newobj);
$glist_form->display();
echo html_writer::start_div('show',array('id'=>'showassignedusers'));
group_to_student();
echo html_writer::end_div();
echo '<div class="modal fade" id="showStudentLists" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modal-responsive">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel11">Students List</h4>

                    </div>
                    <div id="delstu" class=""></div>
                    <div id="gpstulist" class="modal-body">
                    </div>
                </div>
            </div>
        </div>';
echo $OUTPUT->footer();
?>
<script type="text/javascript">
    
    function show_students(id){
        $.ajax({
            url: 'gpstulistajax.php',
            dataType: 'json',
            type: 'get',
            data: {groupid:id},
            success:function(response){
                  $("#gpstulist").html(response);
            }
        });
    }
    function show_assignedgroups(){
        $.ajax({
            url: 'assignedgrouplistajax.php',
            dataType: 'html',
            type: 'get',
            success:function(response){
                  $("#showassignedusers").html(response);
            }
        });
    }
    function delete_student(id){
        $.ajax({
            url: 'deletestulistajax.php',
            dataType: 'json',
            type: 'get',
            data: {assignid:id},
            success:function(response){
                function implode(){
                    $("#delstu").html('<div class="alert alert-success alert-block fade in " role="alert"><button type="button" class="close" data-dismiss="alert">×</button>'+response.msg+'</div>');
                    show_students(response.group);
                    $("#showassignedusers").html(response.gtslist);
                }
                setTimeout(implode, 200);
                show_assignedgroups();
            }
        });
    }
    $(document).ready(function () {
        $("#id_groups").prepend("<option value='' disabled selected>Select Group</option>");
        $("#id_programs").prepend("<option value='' disabled selected>Select Program</option>");
        $("#id_streams").append("<option value='' disabled selected>Select Stream</option>");
        $("#id_syears").append("<option value='' disabled selected>Select Year</option>");
        $("#id_semesters").append("<option value='' disabled selected>Select Semester</option>");
        $("#new_syear").append("<option value='' disabled selected>Select Year</option>");
        $("#new_semester").append("<option value='' disabled selected>Select Semester</option>");
        
        $("#id_programs").change(function(){
            $('#spinner1').css("display","block");
            var pid = $(this).val();
            $.ajax({
                url: 'grouplistajax.php',
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
                url: 'grouplistajax.php',
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
                url: 'grouplistajax.php',
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
            var gid = $("#id_groups").val();
            var pid = $("#id_programs").val();
            var sid = $("#id_streams").val();
            var year = $("#id_syears").val();
            var sem = $(this).val();
            $.ajax({
                url: 'grouplistajax.php',
                dataType: 'json',
                type: 'get',
                data: {group:gid,program3:pid,stream3:sid,year3:year,sem3:sem},
                success:function(response){
                    function implode(){
                      $('#spinner4').css("display","none");
                      $("#batchmatch").html(response);
                    }
                    setTimeout(implode, 500);
                }
            });
        });
         /*$("#new_semester").change(function(){
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
        });       */
    });
</script>