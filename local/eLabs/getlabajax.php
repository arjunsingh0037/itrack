<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
global $DB,$CFG,$USER;
$PAGE->set_context(context_system::instance());
$lid = required_param('labid',PARAM_INT);
$domain_arr = array();
$labrecord = $DB->get_record('vpl',array('id'=>$lid),'id,name');
$domain_arr = array('labid'=>$labrecord->id,'labname'=>$labrecord->name);
echo json_encode($domain_arr);




