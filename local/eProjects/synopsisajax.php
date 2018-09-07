<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$pid = required_param('projectid', PARAM_INT);
$content = '';
$project = $DB->get_record('project',array('id'=>$pid));
$content .= $project->synopsis;
echo json_encode($content);




