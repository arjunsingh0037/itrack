<?php
define('AJAX_SCRIPT', true);
global $USER,$OUTPUT;
require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
require_once ('lib.php');
$content = get_grouplist();
echo json_encode($content);
