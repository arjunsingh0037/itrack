<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
$PAGE->set_context(context_system::instance());

$program1 = required_param('program1',0,PARAM_RAW);
$stream1 = required_param('stream1',0,PARAM_RAW);
$sem_year1 = required_param('sem_year1',0,PARAM_RAW);
$semester1 = required_param('semester1',0,PARAM_RAW);
$batchname1 = required_param('batchname1',0,PARAM_RAW);
$batchcode1 = required_param('batchcode1',0,PARAM_RAW);
$btype1 = required_param('btype1',0,PARAM_RAW);

// $tp = $DB->get_record('partners',array('userid'=>$USER->id));
// $tp = $DB->get_record('partners',array('userid'=>$USER->id));
$insert_academic = new stdClass();
$insert_academic->creatorid = $USER->id;
$insert_academic->batch_name = $batchname1;
$insert_academic->batch_code = $batchcode1;
$insert_academic->program = $program1;
$insert_academic->stream = $stream1;
$insert_academic->semester_year = $sem_year1;
$insert_academic->semester = $semester1;
$insert_academic->partnerid = 0;
$insert_academic->tpid = 0;
$insert_academic->batch_type = $btype1;
$insert_academic->status = 1 ;
$insert_academic->createdon = time();
$insert_academic->startdatetime = time();
$insert_academic->enddatetime = time();
$insert_academic->blimit = 10;
$insert_academic->scheduledon = time();
$DB->insert_record('batch',)




