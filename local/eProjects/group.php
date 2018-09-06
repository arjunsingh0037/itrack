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
require_once ('addgroup_form.php');
$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
//require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eProjects/group.php');
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
$addnewpromo='Project Group';
$PAGE->navbar->add($addnewpromo);
$PAGE->set_title($addnewpromo);
$PAGE->set_heading($addnewpromo);

$newobj = new stdClass();
$gp_form = new addgroup_form();
if ($groupdata = $gp_form->get_data()) {
    global $USER;
    $newgroup = new stdClass();
    $newgroup->groupname = $groupdata->groupname;
    $newgroup->createdby = $USER->id;
    $newgroup->timecreated = time();
    $group_created = $DB->insert_record('project_group',$newgroup);   
}
if(isset($group_created)){
    $msg = 'Group Created';
    redirect(new moodle_url('/local/eProjects/group.php'),$msg,1,'success');
}
echo $OUTPUT->header();
$listurl = $CFG->wwwroot.'/local/eProjects/requestlist.php';
echo '<div class="comp_list"><button type="button" id="request_list" data-url="'.$listurl.'" class="btn btn-info">&#x2b; New Request For Group</button></div>';
$gp_form->set_data($newobj);
$gp_form->display();
echo html_writer::start_div('show',array('id'=>'gpdelmsg','style'=>'margin-left:20px'));
echo html_writer::end_div();
echo html_writer::start_div('show',array('id'=>'showmygroups'));
get_grouplist();
echo html_writer::end_div();
echo $OUTPUT->footer();
?>
<script type="text/javascript">
	$('#request_list').click(function() {
		var url = $(this).data("url");
    	window.location.href = url;
    	return false;
	});

	function show_my_projectgroups(){
        $.ajax({
            url: 'gpslistajax.php',
            dataType: 'html',
            type: 'get',
            success:function(response){
                  $("#showmygroups").html(response);
            }
        });
    }

	function delete_project_group(id){
        $.ajax({
            url: 'deleteprojectajax.php',
            dataType: 'json',
            type: 'get',
            data: {deleteid:id},
            success:function(response){
                function implode(){
                    $("#gpdelmsg").html('<div class="alert alert-success alert-block fade in " role="alert"><button type="button" class="close" data-dismiss="alert">×</button>'+response.msg+'</div>');
                    show_my_projectgroups();
                }
                setTimeout(implode, 200);
            }
        });
    }
</script>
