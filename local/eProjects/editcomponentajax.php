<?php
define('AJAX_SCRIPT', true);
global $USER;
require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$cid = required_param('cid', PARAM_INT);
$content = array();
$data_array = array('id'=>$cid); 
$comp = $DB->get_record('component',$data_array);
$content = array('streamid'=>$comp->streamid,'name'=>$comp->name,'price'=>$comp->price);
echo json_encode($content);




