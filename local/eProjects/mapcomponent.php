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
require_once ('mapcomponent_form.php');
require_once ('delete_mapping_form.php');
$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
//require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eProjects/mapcomponent.php');
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
$addnewpromo='Map Component';
$PAGE->navbar->add($addnewpromo);
$PAGE->set_title($addnewpromo);
$PAGE->set_heading($addnewpromo);

$newobj = new stdClass();
$map_form = new mapcomponent_form();
if ($mapdata = $map_form->get_data()) {
    global $USER;
    $insert_mp = new stdClass();
    $insert_mp->componentid = $_POST['componentid'];
    $insert_mp->createdby = $USER->id;
    $insert_mp->timecreated = time();
    foreach ($_POST['projectid'] as $pj) {
        $insert_mp->projectid = $pj;
        $mapped = $DB->insert_record('component_projects',$insert_mp);
    }   
}
if(isset($mapped)){
    $msg = 'Component Mapped';
    redirect(new moodle_url('/local/eProjects/mapcomponent.php'),$msg,1,'success');
}
echo $OUTPUT->header();
$map_form->set_data($newobj);
$map_form->display();
get_component_maplist();
echo '<div class="modal fade" id="showDeleteMapping" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel11">Confirm</h4>

                    </div>
                    <div class="modal-body">';
                        $delete_mapping_form = new delete_mapping_form();
                        $flag = 0;
                        if ($delete_data = $delete_mapping_form->get_data()) {
                            echo "<meta http-equiv='refresh' content='0'>";
                            $deleted = $DB->delete_records('component_projects', array('id'=>$delete_data->mapid)); 
                            if($deleted){
                              $flag = 1;
                              $delete_mapping_form->set_data($newobj);
                            }
                        }
                        $delete_mapping_form->display();
                    echo '</div>
                    <div id="delete_success_mapping" data-flag="'.$flag.'"></div>;
                </div>
            </div>
        </div>';
echo $OUTPUT->footer();
?>
<script type="text/javascript">
    $(document).ready(function () {
        var v1 = $("#delete_success_mapping").attr("data-flag");
        if(v1 == 1){
            alert('Mapping deleted');
        }


        $("#map_component").prepend("<option value='' disabled selected>Select Component</option>");
        $("#map_project").prepend("<option value='' disabled selected>Select Projects(s)</option>");
        $("#map_component").change(function(){
            var pid = $(this).val();
            $.ajax({
                url: 'projectajax.php',
                type: 'get',
                data: {componentid:pid},
                dataType: 'json',
                success:function(response){
                    if(response == ''){
                        $("#map_project").empty();
                        $("#map_project").prepend("<option value='' disabled selected>No Projects Found</option>");
                    }else{
                        var len = response.length;
                        $("#map_project").empty();
                        $("#map_project").prepend("<option value='' disabled selected>Select Project(s)</option>");
                        for( var i = 0; i<len; i++){
                            var id = response[i]['id'];
                            var pname = response[i]['pname'];
                            $("#map_project").append("<option value='"+id+"'>"+pname+"</option>");
                        }
                    }
                    
                }
            });
        });   
    });

    function delete_mapping(id){
      $("#to_delete").val(id);
    }
</script>