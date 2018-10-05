<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
$PAGE->set_context(context_system::instance());
$course = optional_param('crsid', null, PARAM_INT);
$batch = array();
$batches = $DB->get_records('batches',array('batchtype'=>'ACAD'),'id,batchname,batchcode');
foreach ($batches as $ba) {
    $batch[] = array('batchid'=>$ba->id,'batchcode'=>$ba->batchcode);
}
echo json_encode($batch);


