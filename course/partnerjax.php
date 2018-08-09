<?php
define('AJAX_SCRIPT', true);

require_once('../config.php');

$PAGE->set_context(context_system::instance());
$uname = optional_param('utype',0, PARAM_RAW);
$unameto = optional_param('utypeto',0, PARAM_RAW);
$pid = optional_param('pid',0, PARAM_INT);
$pidto = optional_param('pidto',0, PARAM_INT);
$tpid = optional_param('tpid',0, PARAM_INT);

if($uname != '0'){	
	$arr1 = $arr2 = array();
	$uni_users = array();
	$cor_users = array();
	$partnerlist = $DB->get_records('partners');
	foreach ($partnerlist as $pid => $partner) {
	    $users[] = $DB->get_record('user',array('id'=>$partner->userid));
	}
	foreach ($users as $user) {
	    if($user->institution == 'university'){
	        $uni_users[] = array('id'=>$user->id,'stream'=>$user->firstname);
	    }elseif($user->institution == 'corporate'){
	        $cor_users[] = array('id'=>$user->id,'stream'=>$user->firstname);
	    }
	}
	if($uname == 'university'){
		$streams = $uni_users;
	}else{
		$streams = $cor_users;
	}
}elseif($pid != '0'){
	$trainingpartners = $DB->get_records('trainingpartners',array('createdby'=>$pid));
	foreach ($trainingpartners as $trainingpartner) {
		$tp_name = $DB->get_record('user',array('id'=>$trainingpartner->userid),'id,firstname');
	    $streams[] = array('id'=>$trainingpartner->userid,'stream'=>$tp_name->firstname);
	}
}elseif($tpid != '0'){
	$streams = array();
	$courseids = $DB->get_records('course_type',array('creatorid'=>$tpid),'courseid,subscribed');
	foreach ($courseids as $courseid) {
		$coursename = $DB->get_record('course',array('id'=>$courseid->courseid),'fullname');
		if($courseid->subscribed == '1'){
			$streams[] = array('id'=>$courseid->courseid,'stream'=>$coursename->fullname);		
		}
	}
}elseif($unameto != '0'){	
	$arr1 = $arr2 = array();
	$uni_users = array();
	$cor_users = array();
	$partnerlist = $DB->get_records('partners');
	foreach ($partnerlist as $pid => $partner) {
	    $users[] = $DB->get_record('user',array('id'=>$partner->userid));
	}
	foreach ($users as $user) {
	    if($user->institution == 'university'){
	        $uni_users[] = array('id'=>$user->id,'stream'=>$user->firstname);
	    }elseif($user->institution == 'corporate'){
	        $cor_users[] = array('id'=>$user->id,'stream'=>$user->firstname);
	    }
	}
	if($unameto == 'university'){
		$streams = $uni_users;
	}else{
		$streams = $cor_users;
	}
}elseif($pidto != '0'){
	$trainingpartners = $DB->get_records('trainingpartners',array('createdby'=>$pidto));
	foreach ($trainingpartners as $trainingpartner) {
		$tp_name = $DB->get_record('user',array('id'=>$trainingpartner->userid),'id,firstname');
	    $streams[] = array('id'=>$trainingpartner->userid,'stream'=>$tp_name->firstname);
	}
}

//$streams[] = array('id'=>'22','stream'=>'aadadadaddad');
echo json_encode($streams);




