<?php
define('AJAX_SCRIPT', true);

require_once('../../../config.php');
global $USER,$OUTPUT,$PAGE,$DB;
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$uid = required_param('uid', PARAM_INT);
$uname = required_param('uname', PARAM_RAW);
$uemail = required_param('uemail', PARAM_RAW);
$uphone = required_param('uphone', PARAM_RAW);
$ucountry = required_param('ucountry', PARAM_RAW);
//require_once ('lib.php');
$content = array();
$update_user = new stdClass();
$update_user->id = $uid;
$update_user->firstname = $uname;
$update_user->email = $uemail;
$update_user->phone1 = $uphone;
$update_user->city = $ucountry;
$comp = $DB->update_record('user',$update_user);
$content = array('status'=>'Success');
echo json_encode($content);