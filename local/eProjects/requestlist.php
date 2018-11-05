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
$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
//require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eProjects/requestlist.php');
$PAGE->set_url($url);
$PAGE->requires->css('/local/eProjects/style/styles.css');
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">';
//By Arjun -Permission Access
// $currentuser = $USER->id;
// $user = $DB->record_exists('trainingpartners', array('userid' => $currentuser));
// if (!$user) {
//     echo $OUTPUT->header();
//     redirect($CFG->wwwroot.'/my','You do not have access to this page.',1,'error');
//     die; 
// }
$addnewcomp='New Group Request List';
$PAGE->navbar->add($addnewcomp);
$PAGE->set_title($addnewcomp);
$PAGE->set_heading($addnewcomp);
echo $OUTPUT->header();
get_mygroup_requestlist();
echo $OUTPUT->footer();
?>
<script type="text/javascript">
	function approve_group_request(id){
        $.ajax({
            url: 'approvalajax.php',
            dataType: 'json',
            type: 'get',
            data: {newgpid:id},
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
    function reject_group_request(id){
        $.ajax({
            url: 'rejectajax.php',
            dataType: 'json',
            type: 'get',
            data: {newgpid:id},
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

