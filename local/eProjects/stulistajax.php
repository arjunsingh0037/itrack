<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$gid = optional_param('groupid', 0,PARAM_INT);
$sgid = optional_param('sgroupid',null, PARAM_RAW);

$content = ''; 
if($gid != 0){
	$sql = "SELECT DISTINCT studentid from {group_to_student} WHERE id = ?";
	$users = $DB->get_records_sql($sql,array($gid));
  	$table = new html_table();
  	$table->head  = array('Sl.no', 'Login', 'Name', 'Program', 'Stream', 'Semester', 'Year');
  	//$table->size  = array('5%', '10%', '10%', '10%', '15%', '10%', '10%', '10%', '10%');
  	$table->colclasses = array ('leftalign','leftalign','leftalign','leftalign','leftalign','leftalign','rightalign');
  	$table->attributes['class'] = 'admintable generaltable';
  	$table->id = 'filterssetting';
  	$data = array();
  	$groupobj = $DB->get_records('group_to_student',array('groupid'=>$gid));
  	if($groupobj){
	    $i = 1;
	    $group_creator = array();
	    $group_creator_obj = $DB->get_records('group_to_student',array('groupid'=>$gid));
	    foreach ($group_creator_obj as $gc) {
	    	$group_creator = $gc;
	    }
	    //print_object($group_creator_obj);
	    $creator = $DB->get_record('user',array('id'=>$group_creator->createdby),'id,username,firstname');
	    $streamc = $DB->get_record('program_stream',array('id'=>$group_creator->stream),'program,stream');
	    //$data[] = array($i,$creator->username,$creator->firstname,$streamc->program,$streamc->stream,$group_creator->sem,$group_creator->year,'Active','');
	    foreach ($groupobj as $gs) {
	        $username = $DB->get_record('user',array('id'=>$gs->studentid),'id,firstname,username');
	        $stream = $DB->get_record('program_stream',array('id'=>$gs->stream),'program,stream');
	        $line = array();
	        $i++;
	        $line[] = $i;
	        $line[] = $username->username;
	        $line[] = $username->firstname;
	        $line[] = $gs->program;
	        $line[] = $stream->stream;
	        $line[] = $gs->sem;
	        $line[] = $gs->year;
	        $data[] = $line;
	    }
	}else{ 
	   $data[] = array('','','','No records found','','','','');
	}
  	$table->data  = $data;      
  	$content .=html_writer::table($table);
}elseif($sgid != null){
	$ids = explode('-', $sgid);
	$table = new html_table();
  	$table->head  = array('Sl.no', 'Login', 'Name', 'Program', 'Stream');
  	$table->colclasses = array ('leftalign','leftalign','leftalign','leftalign','rightalign');
  	$table->attributes['class'] = 'admintable generaltable';
  	$table->id = 'filterssetting';
  	$data = array();

  	$groupobj = $DB->get_record('project_request',array('requester'=>$ids[1],'type'=>'in','projectid'=>$ids[0]),'id,projectid,requester');
  	if($groupobj){
  		$username = $DB->get_record('user',array('id'=>$groupobj->requester),'id,username,firstname');
	    $proj_arr = $DB->get_record('project',array('id'=>$groupobj->projectid),'id,stream,domain,title');
	    $stream = $DB->get_record('program_stream',array('id'=>$proj_arr->stream),'program,stream');
	    $line = array();
        $i++;
        $line[] = $i;
        $line[] = $username->username;
        $line[] = $username->firstname;
        $line[] = $stream->program;
        $line[] = $stream->stream;
        $data[] = $line;
	}else{
	   $data[] = array('','','No records found','','',);
	}
  	$table->data  = $data;      
  	$content .=html_writer::table($table);
}
echo json_encode($content);




