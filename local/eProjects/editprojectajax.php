<?php
define('AJAX_SCRIPT', true);
global $USER;
require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$cid = required_param('pid', PARAM_INT);
$content = array();
$domain_arr = array();
$data_array = array('id'=>$cid); 
$project = $DB->get_record('project',$data_array);
$domains = $DB->get_records('domain',array('stream_id'=>$project->stream));
foreach ($domains as $dk => $dv) {
	$domain_arr[] = array('id'=>$dv->id,'name'=>$dv->domain_name);
}
$content = array('streamid'=>$project->stream,'domain'=>$domain_arr,'technology'=>$project->technology,'comp'=>$project->has_comp,'type'=>$project->type,'title'=>$project->title,'synopsis'=>$project->synopsis,'duration'=>$project->duration,'rating'=>$project->rating,'weightage'=>$project->weightage);
echo json_encode($content);




