<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$gid = required_param('groupid', PARAM_INT);

$content = ''; 
if($gid != 0){
	$sql = "SELECT DISTINCT studentid from {group_to_student} WHERE id = ?";
	$users = $DB->get_records_sql($sql,array($gid));
  	$table = new html_table();
  	$table->head  = array('Sl.no', 'Login', 'Name', 'Program', 'Stream', 'Semester', 'Year', 'Status', 'Action');
  	$table->size  = array('5%', '10%', '10%', '10%', '15%', '10%', '10%', '10%', '10%');
  	$table->colclasses = array ('leftalign','leftalign','leftalign','leftalign','leftalign','leftalign','leftalign','leftalign','rightalign');
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
	    $data[] = array($i,$creator->username,$creator->firstname,$streamc->program,$streamc->stream,$group_creator->sem,$group_creator->year,'Active','');
	    foreach ($groupobj as $gs) {
	        if($gs->status == 0){
	          $status = 'Pending';
	        }else if($gs->status == 1){
	          $status = 'Active';
	        }
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
	        $line[] = $status;
	        $line[] = '<button type="button" id="'.$gs->id.'" onclick="delete_student(this.id)" class="btn btn-info crslink delete_proj" data-toggle="modal">Delete</button>';
	        $data[] = $line;
	        
	    }
	}else{
	   $data[] = array('','','','No records found','','','','');
	}
  	$table->data  = $data;      
  	$content .=html_writer::table($table);
}
echo json_encode($content);




