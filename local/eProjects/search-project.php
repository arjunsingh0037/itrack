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
require_once ('search_project_form.php');
$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
//require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eProjects/search-project.php');
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
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">';
$addnewpromo='Search Projects';
$PAGE->navbar->add($addnewpromo);
$PAGE->set_title($addnewpromo);
$PAGE->set_heading($addnewpromo);

//$newobj = new stdClass();
//$search_form = new search_project_form();
echo $OUTPUT->header();
$stu_exists = $DB->get_record('tp_useruploads',array('userid'=>$USER->id,'roletype'=>'student')); 
if($stu_exists){
    $creator = $DB->get_record('tp_useruploads',array('userid'=>$USER->id,'roletype'=>'student'),'creatorid');
    $partner = $DB->get_record('trainingpartners',array('userid'=>$creator->creatorid),'createdby');
    $accountm = $DB->get_record('partners',array('userid'=>$partner->createdby),'createdby');
    //to get the streams created by the  accountmnager fof the loginned user
    if($DB->record_exists('stream',array('createdby'=>$accountm->createdby))){
        $stream_arr = $DB->get_records('stream',array('createdby'=>$accountm->createdby));
    }
    foreach ($stream_arr as $key => $value) {
        $streams[$key] = $value->stream_name;
    }
}
$procat = array('gp'=>'Group','in'=>'Individual');
//$search_form->set_data($newobj);
//$search_form->display();
echo '<div id="component_maplist" class="panel-default">
              <div class="panel-heading listhead">SEARCH PROJECTS</div>
              <div class="panel-body">
                    <form class="form-horizontal" id="searchform" onsubmit="return false">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Select Stream <span style="color:red;">*</span></label>
                                    <div class="col-lg-8">
                                        <select class="form-control" id="enrol_stream" name="stream" required="">';
                                        foreach ($streams as $key2 => $value2) {
                                            echo '<option value="'.$key2.'">'.$value2.'</option>';
                                        }
                                        echo '</select>
                                    </div>
                                </div>
                            </div>    
                            <div class="col-lg-6">                        
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Select Project Category<span style="color:red;">*</span></label>
                                    <div class="col-lg-8">           
                                        <select class="form-control" id="search_category" name="under_cat" required="">';
                                           foreach ($procat as $key3 => $value3) {
                                                echo '<option value="'.$key3.'">'.$value3.'</option>';
                                            } 
                                        echo '</select>
                                    </div>    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="domain_div">
                                <div class="col-lg-6">                        
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label">Select Domain<span style="color:red;">*</span></label>
                                        <div class="col-lg-8">  
                                            <select id="enrol_domain" name="domain" class="form-control" required="">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div id="domain_loader"></div>
                            <div id="group_div">
                               
                            </div>
                            <div id="group_loader"></div>
                        </div>                            
                        <div class="form-group">            
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn bg-light-blue" id="searchprojectbtn" name="searchprojectbtn">Search</button>
                            </div> 
                        </div> 
                    </div>
                </form>
            </div>
        </div>';
echo '<div class="loading"><div class="high-loader" id="loader"></div></div>';
echo '<div id="search_projectlist"></div>';
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
    function send_request(id){
        var grouptype = $("#gptype").val();
        var gpid = $("#gpid").val();
        $.ajax({
            url: 'projectrequestajax.php',
            dataType: 'json',
            type: 'get',
            data: {projectid:id,groupid:gpid,grouptype:grouptype},
            success:function(response){
                if(response.status == 1){
                    //var msg = '<div class="alert alert-success" role="alert">Request sent successfully !!</div>'; 
                    swal("Success!", "Request sent", "success");
                    //$("#sentRequestDetail").html(msg);
                }else{
                    //var msg = '<div class="alert alert-danger" role="alert">Error sending request !!</div>'; 
                    swal("Error!", "Request not sent", "error");
                    //$("#sentRequestDetail").html(msg);
                }  
            }
        });
    }
    $(document).ready(function () {
        $("#enrol_stream").prepend("<option value='' disabled selected>Select Stream</option>");
        $("#enrol_domain").prepend("<option value='' disabled selected>Select Domain</option>");
        $("#search_category").prepend("<option value='' disabled selected>Select Project Category</option>");
        $("#search_group").prepend("<option value='' disabled selected>Select Group</option>");

        
        $("#enrol_stream").change(function(){
        var sid = $(this).val();
            $.ajax({
                url: 'searchprojectajax.php',
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
        
        $("#search_category").change(function(){
            var gtype = $(this).val();
            if(gtype == 'gp'){
                 $.ajax({
                    url: 'searchprojectajax.php',
                    type: 'get',
                    data: {grouptype:gtype},
                    dataType: 'json',
                    success:function(response){
                        var len = response.length;
                        $("#search_group").empty();
                        var cont = ' <div class="col-lg-6"><div class="form-group"><label class="col-lg-4 control-label">Select Group <span style="color:red;">*</span></label><div class="col-lg-8"><select id="search_group" name="project_group" class="form-control" required=""></select></div></div></div>';
                        $("#group_div").html(cont);
                        $("#search_group").prepend("<option value='' disabled selected>Select Group</option>");
                        for( var i = 0; i<len; i++){
                            var id = response[i]['id'];
                            var gpname = response[i]['gpname'];
                            $("#search_group").append("<option value='"+id+"'>"+gpname+"</option>");
                        }
                    }
                });
            }else{
                $("#group_div").html('');
            }
        });

        $("#searchform").submit(function(){
            var streamid = $("#enrol_stream").val();
            var domainid = $("#enrol_domain").val();
            var catid = $("#search_category").val();
            var gpid = $("#search_group").val();
            if(!gpid){
                gpid = 0;
            }    
            $('#loader').removeClass("hide-loader");
            $('#loader').addClass("loader");
            $.ajax({
                url: 'searchlistajax.php',
                dataType: 'json',
                type: 'get',
                data: {streamid:streamid,domainid:domainid,catid:catid,gpid:gpid},
                success:function(response){
                    function implode(){
                      $('#loader').removeClass("loader");
                      $('#loader').addClass("high-loader");
                      $("#search_projectlist").html(response);
                    }
                    setTimeout(implode, 500);
                }
            });
        });
    });
</script>