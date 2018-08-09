<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');

$PAGE->set_context(context_system::instance());
$id = required_param('id', PARAM_INT);
$sql = "SELECT ac.userid
from {course} c
left join {local_assign_course} ac 
on c.id = ac.courseid
where ac.courseid = $id";
$courses = $DB->get_records_sql($sql);
//$courses = $DB->get_records('course',array('id'=>$id),'id,fullname');
//print_object($courses);
$c = array();
foreach($courses as $course){	
	$c[] = $course->userid;
}
// //print_object($c);
echo $OUTPUT->header(); 

$result = implode(',', $c);
//if($result){
	// if(is_siteadmin()){
	// 	$sql = "SELECT id,firstname,username from {user} where id  not in ($result)";
	// }else{
	// 	$sql = "SELECT u.id,u.firstname,u.username
	//             from {user} u
	//             LEFT join {user_info_data} un
	//             on u.id = un.userid 
	//             where un.userid not in ($result)";
	// }
	//$sql = "SELECT id,firstname,username from {user} where id  not in ($result)";
	//$sql = "SELECT id,firstname,username from {user} where id  not in ($result)";
	// $user = $DB->get_records_sql($sql);
	// print_object($user);
	// echo json_encode($user);

//}
	//print_object($user);
$sql = "SELECT id,firstname,username from {user}";
$user = $DB->get_records_sql($sql);
foreach ($user as $usr) {
	if($usr->id > 2){
		if(!(user_has_role_assignment($usr->id, 9, SYSCONTEXTID) || user_has_role_assignment($usr->id, 10, SYSCONTEXTID) || user_has_role_assignment($usr->id, 11, SYSCONTEXTID) || user_has_role_assignment($usr->id, 1, SYSCONTEXTID) || user_has_role_assignment($usr->id, 3, SYSCONTEXTID) || user_has_role_assignment($usr->id, 4, SYSCONTEXTID))){
			$user1[] = array('id'=>$usr->id,'firstname'=>$usr->firstname);
		}
	}
}
echo json_encode($user1);




