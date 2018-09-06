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
require_once ('add_component_form.php');
$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
//require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eProjects/add_component.php');
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
$addnewcomp='Add component';
$PAGE->navbar->add($addnewcomp);
$PAGE->set_title($addnewcomp);
$PAGE->set_heading($addnewcomp);

$component_form = new add_component_form();
if ($data = $component_form->get_data()){
	$new_domain = new stdClass();
	$new_domain->streamid = $data->ac_stream;
	$new_domain->name = $data->name;
	$new_domain->price = $data->price;
	$new_domain->createdby = $USER->id;
	$new_domain->timecreated = time(); 
	$domain_created = $DB->insert_record('component',$new_domain);
	if($domain_created){
		$msg = 'Component Created';
		redirect(new moodle_url('/local/eProjects/add_component.php'),$msg,1,'success');
	}
	//$component_form->reset();
}
echo $OUTPUT->header();
$listurl = $CFG->wwwroot.'/local/eProjects/componentlist.php';
echo '<div class="comp_list"><button type="button" id="component_list" data-url="'.$listurl.'" class="btn btn-info">Component List</button></div>';
$component_form->display();
echo $OUTPUT->footer();
?>
<script type="text/javascript">
	$('#component_list').click(function() {
		var url = $(this).data("url");
    	window.location.href = url;
    	return false;
	});
</script>
