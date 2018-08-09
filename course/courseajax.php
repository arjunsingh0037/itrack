<?php
define('AJAX_SCRIPT', true);

require_once('../config.php');

$PAGE->set_context(context_system::instance());
$program = optional_param('program', null, PARAM_RAW);

$streamed = $DB->get_records('program_stream',array('program'=>$program),'id,stream');
foreach ($streamed as $sv) {
	$streams[] = array('id'=>$sv->id,'stream'=>$sv->stream);
}
echo json_encode($streams);



