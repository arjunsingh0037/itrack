<?php
define('AJAX_SCRIPT', true);
require_once('../../../config.php');
global $USER,$OUTPUT,$PAGE,$DB;
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$tname = required_param('taskname', PARAM_RAW);
$uid = required_param('uid', PARAM_INT);
//require_once ('lib.php');
$new_task = new stdClass();
$new_task->usermodified = $USER->id;
$new_task->timecreated = time();
$new_task->timemodified = time();
$new_task->todotext = $tname;
$new_task->done = 0;
$task_added = $DB->insert_record('block_todo',$new_task);
if($task_added){
	if($uid != $USER->id){
		$new_assignee = new stdClass();
		$new_assignee->taskid = $task_added;
		$new_assignee->assignedto = $uid;
		$new_assignee->timeassigned = time();
		$new_assignee_added = $DB->insert_record('todo_assigneee',$new_assignee);
	}
	$task = $DB->get_record('block_todo',array('id'=>$task_added),'id,usermodified,todotext');
	$content .= "<li class='list-group-item' data-role='task' id='taskli".$task->id."'>
					<div class='checkbox checkbox-info'>
						<input type='checkbox' id='inputSchedule".$task->id."' class='inputSchedule' name='inputCheckboxesSchedule' onclick='toggleCheckbox(this)' value='".$task->id."'>
						<label for='inputSchedule".$task->id."'>
							<span>".$task->todotext."</span>
						</label>
						<button data-control='delete' type='button' class='close' aria-label='Delete' id='".$task->id."' onclick='deleteTask(this.id)'>
							<span aria-hidden='true'>×</span>
						</button>
					</div>
					<ul class='assignedto'>";
					    if($uid == $USER->id){
					    	$content .="<li>
					    					<img src='".$CFG->wwwroot."'/user/pix.php?file=/'".$USER->id."'/f1.jpg' alt='user' data-toggle='tooltip' data-placement='top' title='' data-original-title='".$USER->firstname."'>
					    				</li>";
					    }else{
					    	$other_user = $DB->get_record('user',array('id'=>$uid),'id,firstname');
					    	$content .="<li>
					    					<span class='field-tip'><img src='".$CFG->wwwroot."/user/pix.php?file=/".$USER->id."/f1.jpg' alt='user' data-toggle='tooltip' data-placement='top' title='' data-original-title=''><span class='tip-content'>".$USER->firstname."</span></span>

										</li>
					    				<li>
					    					<span class='field-tip'><img src='".$CFG->wwwroot."/user/pix.php?file=/".$uid."/f1.jpg' alt='user' data-toggle='tooltip' data-placement='top' title='' data-original-title=''><span class='tip-content'>".$other_user->firstname."</span></span>

										</li>";
					    }    
    	$content .="</ul></li>";

    echo json_encode($content);
}
