<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$lid = optional_param('labid', 0,PARAM_INT);

$content = ''; 
$table = new html_table();
$table->head  = array('Sl.no', 'Batch Name', 'Batch Code', 'Number of students');
//$table->size  = array('5%', '10%', '10%', '10%', '15%', '10%', '10%', '10%', '10%');
$table->colclasses = array ('leftalign','leftalign','leftalign','leftalign');
$table->attributes['class'] = 'admintable generaltable';
$table->id = 'filterssetting';

$batches = $DB->get_records('vpl_assignedbatches',array('labid'=>$lid));
$data = array();
if($batches){
    $i = 1;
    foreach ($batches as $bs) {
        $username = $DB->get_record('user',array('id'=>$gs->studentid),'id,firstname,username');
        $stream = $DB->get_record('program_stream',array('id'=>$gs->stream),'program,stream');

        $batch_obj = $DB->get_record('batches',array('id'=>$bs->batchid),'batchname,batchcode');
        $users = $DB->get_records('batch_enrolstudent',array('batchid'=>$bs->batchid));
       
        $nousers = count($users);
        $line = array();
        
        $line[] = $i;
        $line[] = $batch_obj->batchname;
        $line[] = $batch_obj->batchcode;
        $line[] = $nousers;
        $data[] = $line;
        $i++;
    }
}else{ 
   $data[] = array('','','No records found','');
}
$table->data  = $data;      
$content .=html_writer::table($table);
echo json_encode($content);




