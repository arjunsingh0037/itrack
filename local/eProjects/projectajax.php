<?php
define('AJAX_SCRIPT', true);
global $USER;
require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$cid = optional_param('componentid', 0, PARAM_INT);
$content = array();
if($cid != 0){
	$data_array = array('id'=>$cid); 
	if($DB->record_exists('component',$data_array)){
    	$str = $DB->get_record('component',$data_array,'id,streamid');
		$streamid = $str->streamid;
		$projects = $DB->get_records('project',array('stream'=>$streamid));
		if($projects){
			foreach ($projects as $pk => $pv) {
				$content []= array('id'=>$pv->id,'pname'=>$pv->title);
			}
		}else{
			$content[] = array();
		}
	}
}
echo json_encode($content);




