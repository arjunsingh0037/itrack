<?php
defined('MOODLE_INTERNAL') || die();
 
function  local_course_batches_extend_settings_navigation($settingsnav, $context){
	global $CFG, $COURSE, $courseid, $DB;
	
$course = $DB->get_record('course', array('id' => $COURSE->id));	
//require_course_login($course);
$context = context_course::instance($course->id);
	if (has_capability('moodle/role:assign', $context)) {
		$coursenode = $settingsnav->get('courseadmin');
		if($coursenode)
		{
		
		
//for batch
                  $coursenode->add(get_string('batch', 'local_course_batches'), null,
                        navigation_node::TYPE_CONTAINER, null, 'batch',
                        new pix_icon('i/badge', get_string('batch', 'local_course_batches')));
            $url = "$CFG->wwwroot/local/course_batches/batch.php?courseid=$COURSE->id";

			$coursenode->get('batch')->add(get_string('addbatch', 'local_course_batches'), $url,
				navigation_node::TYPE_SETTING, null, 'batch');
   
	   
				$url = "$CFG->wwwroot/local/course_batches/view_batch.php?id=$COURSE->id";

				$coursenode->get('batch')->add(get_string('viewbatch', 'local_course_batches'), $url,
						navigation_node::TYPE_SETTING, null, 'course_batches');

             //for batch
                /* 
                 $url = "$CFG->wwwroot/local/course_batches/batch.php?courseid=$COURSE->id";

			$coursenode->get('coursepromo')->add(get_string('addbatch', 'local_course_batches'), $url,
				navigation_node::TYPE_SETTING, null, 'coursepromo');

	   
				$url = "$CFG->wwwroot/local/course_batches/view_batch.php?id=$COURSE->id";

				$coursenode->get('coursepromo')->add(get_string('viewbatch', 'local_course_batches'), $url,
						navigation_node::TYPE_SETTING, null, 'course_batches'); */
		}
	}	
}

function enrol_page_hook(stdClass $instance) {
        global $CFG, $USER, $OUTPUT, $PAGE, $DB;

        ob_start();

//        if ($DB->record_exists('user_enrolments', array('userid'=>$USER->id, 'enrolid'=>$instance->id))) {
//            return ob_get_clean();
//        }

        if ($instance->enrolstartdate != 0 && $instance->enrolstartdate > time()) {
            return ob_get_clean();
        }

        if ($instance->enrolenddate != 0 && $instance->enrolenddate < time()) {
            return ob_get_clean();
        }

        $course = $DB->get_record('course', array('id'=>$instance->courseid));
        $context = context_course::instance($course->id);

        $shortname = format_string($course->shortname, true, array('context' => $context));
        $strloginto = get_string("loginto", "", $shortname);
        $strcourses = get_string("courses");

        // Pass $view=true to filter hidden caps if the user cannot see them
        if ($users = get_users_by_capability($context, 'moodle/course:update', 'u.*', 'u.id ASC',
                                             '', '', '', '', false, true)) {
            $users = sort_by_roleassignment_authority($users, $context);
            $teacher = array_shift($users);
        } else {
            $teacher = false;
        }

        if ( (float) $instance->cost <= 0 ) {
            $cost = (float) $this->get_config('cost');
        } else {
            $cost = (float) $instance->cost;
        }

        if (abs($cost) < 0.01) { // no cost, other enrolment methods (instances) should be used
            echo '<p>'.get_string('nocost', 'enrol_paypal').'</p>';
        } else {

            // Calculate localised and "." cost, make sure we send PayPal the same value,
            // please note PayPal expects amount with 2 decimal places and "." separator.
            $localisedcost = format_float($cost, 2, true);
            $cost = format_float($cost, 2, false);

            if (isguestuser()) { // force login only for guest user, not real users with guest role
                if (empty($CFG->loginhttps)) {
                    $wwwroot = $CFG->wwwroot;
                } else {
                    // This actually is not so secure ;-), 'cause we're
                    // in unencrypted connection...
                    $wwwroot = str_replace("http://", "https://", $CFG->wwwroot);
                }
                echo '<div class="mdl-align"><p>'.get_string('paymentrequired').'</p>';
                echo '<p><b>'.get_string('cost').": $instance->currency $localisedcost".'</b></p>';
                echo '<p><a href="'.$wwwroot.'/login/">'.get_string('loginsite').'</a></p>';
                echo '</div>';
            } else {
                //Sanitise some fields before building the PayPal form
                $coursefullname  = format_string($course->fullname, true, array('context'=>$context));
                $courseshortname = $shortname;
                $userfullname    = fullname($USER);
                $userfirstname   = $USER->firstname;
                $userlastname    = $USER->lastname;
                $useraddress     = $USER->address;
                $usercity        = $USER->city;
                $instancename    = $this->get_instance_name($instance);

                include($CFG->dirroot.'/local/course_batches/batchpay_form.html');
            }

        }

        return $OUTPUT->box(ob_get_clean());
    }

function restore_instance(restore_enrolments_structure_step $step, stdClass $data, $course, $oldid) {
    global $DB;
    if ($step->get_task()->get_target() == backup::TARGET_NEW_COURSE) {
        $merge = false;
    } else {
        $merge = array(
            'courseid'   => $data->courseid,
            'enrol'      => $this->get_name(),
            'roleid'     => $data->roleid,
            'cost'       => $data->cost,
            'currency'   => $data->currency,
        );
    }
    if ($merge and $instances = $DB->get_records('enrol', $merge, 'id')) {
        $instance = reset($instances);
        $instanceid = $instance->id;
    } else {
        $instanceid = $this->add_instance($course, (array)$data);
    }
    $step->set_mapping('enrol', $oldid, $instanceid);
}

function tpa_batchlist($userid,$roleid){
    global $OUTPUT,$CFG,$DB,$PAGE;
    $count = 0;
    $content = '';
    $msg = '';
    $content.='<div id="exTab2" class=""> 
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a  href="#1" data-toggle="tab">LIST OF ACADEMIC BATCHES</a>
                        </li>
                        <li>
                            <a href="#2" data-toggle="tab">LIST OF NON ACADEMIC BATCHES</a>
                        </li>
                        <li>
                            <a href="#3" data-toggle="tab">LIST OF GENERAL BATCHES</a>
                        </li>
                    </ul>';
    if($DB->record_exists('batches',array('creatorid'=>$userid))){
        $content .='<div class="tab-content ">
                            <div class="tab-pane active" id="1">';
                            if($DB->record_exists('batches',array('creatorid'=>$userid,'batchtype'=>'ACAD'))){
                                $academic_batches = $DB->get_records('batches',array('creatorid'=>$userid,'batchtype'=>'ACAD'));;
                                $content .=batchlist_tables($academic_batches,'ACAD');
                            }else{
                                $msg = 'You do not have any academic batches'; 
                                $content .= $OUTPUT->notification($msg,'notifysuccess');
                            }
                            $content .='</div>
                            <div class="tab-pane" id="2">';
                            if($DB->record_exists('batches',array('creatorid'=>$userid,'batchtype'=>'NACAD'))){
                                $nonacademic_batches = $DB->get_records('batches',array('creatorid'=>$userid,'batchtype'=>'NACAD'));
                                $content .=batchlist_tables($nonacademic_batches,'NACAD');
                            }else{
                                $msg = 'You do not have any non-academic batches'; 
                                $content .= $OUTPUT->notification($msg,'notifysuccess');
                            }
                            $content .= '</div>
                            <div class="tab-pane" id="3">';
                            if($DB->record_exists('batches',array('creatorid'=>$userid,'batchtype'=>'SUB'))){
                                $subscribed_batches = $DB->get_records('batches',array('creatorid'=>$userid,'batchtype'=>'SUB'));
                                $content .=batchlist_tables($subscribed_batches,'SUB');
                            }else{
                                $msg = 'You do not have subscribe batches'; 
                                $content .= $OUTPUT->notification($msg,'notifysuccess');
                            }
                            $content .= '</div>
                        </div>';
    }else{
        $content .= 'You have not created any batches';
    }
    $content .= '</div>';
    //print_object($categories2);die();
    echo $content;
}

function batchlist_tables(array $batches, $type){
    global $CFG,$DB;
    $batchlist = '';
    $i = 1;
    $batchlist .= '<table id="example1" class="table table-striped table-bordered datatable" style="width:100%">
                        <thead>
                            <tr>';

    $batchlist .= '<th>S.No</th>';
    if($type == 'ACAD'){
        $batchlist .= '<th>Program</th>
                    <th>Stream</th>
                    <th>Current Semester Year</th>
                    <th>Current Semester</th>
                    <th>Batch Name</th>
                    <th>Batch Code</th>
                    <th>View</th>';
    }elseif ($type == 'NACAD') {
        $batchlist .= '<th>Batch Name</th>
                    <th>Batch Code</th>
                    <th>View</th>
                    <th></th>';
    }elseif ($type == 'SUB') {
        $batchlist .= '<th>Batch Name</th>
                    <th>Batch Code</th>
                    <th>Course Asssigned</th>
                    <th>Professor Assigned</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Type</th>
                    <th>Limit</th>';
    }

    $batchlist .= '</tr>
            </thead>';
    $batchlist .= '<tbody>';
                        foreach ($batches as $batch) {
                            $batchlist .='<tr>';
                            if($batch->batchtype == 'ACAD'){
                                $stream = $DB->get_record('program_stream',array('id'=>$batch->stream),'id,stream');
                                $batchlist .= '<td>'.$i.'</td>
                                            <td>'.$batch->program.'</td>
                                            <td>'.$stream->stream.'</td>
                                            <td>'.$batch->semester.'</td>
                                            <td>'.$batch->semyear.'</td>
                                            <td>'.$batch->batchname.'</td>
                                            <td>'.$batch->batchcode.'</td>
                                            <td><button type="button" class="btn btn-info crslink">View</button></td>';
                            }elseif($batch->batchtype == 'NACAD'){
                                $batchlist .= '<td>'.$i.'</td>
                                            <td>'.$batch->batchname.'</td>
                                            <td>'.$batch->batchcode.'</td>
                                            <td><button type="button" class="btn btn-info crslink">View</button></td>
                                            <td><button type="button" class="btn btn-info crslink">Inactivate</button></td>';
                            }elseif($batch->batchtype == 'SUB'){
                                $course = $DB->get_record('course',array('id'=>$batch->course),'id,fullname');
                                $professor = $DB->get_record('user',array('id'=>$batch->professor),'id,firstname');
                                $batchlist .= '<td>'.$i.'</td>
                                            <td>'.$batch->batchname.'</td>
                                            <td>'.$batch->batchcode.'</td>
                                            <td>'.$course->fullname.'</td>
                                            <td>'.$professor->firstname.'</td>
                                            <td>'.$batch->startdate.'</td>
                                            <td>'.$batch->enddate.'</td>
                                            <td>'.$batch->batchtype.'</td>
                                            <td>'.$batch->batchlimit.'</td>';
                            }
                            $batchlist .='</tr>';
                            $i++;
                        }  
                    //unset($i);unset($ca);                       
                    $batchlist .= '</tbody>
                </table>';
    return $batchlist;                
}

function user_coursebatch_enrolment($userid, array $batches, array $courses){
    global $CFG,$DB;
    $flag = 0;
    $roleid = $DB->get_field('role', 'id', array('shortname' =>'student'));
    
    foreach ($batches as $batch) {
        $users = $DB->get_records('batch_enrolstudent',array('batchid'=>$batch),'userid');
        foreach ($users as $user) {
            foreach ($courses as $course) {
                $enrol = $DB->get_record('enrol',array('courseid'=>$course,'enrol'=>'manual'),'id');
                if($DB->record_exists('user_enrolments',array('enrolid'=>$enrol->id,'userid'=>$user->userid,'status'=>1))){
                    $check_enrolment = update_enrolment($userid, $enrol->id, $user->userid,0);
                }else{
                    $check_enrolment = check_enrolment($course, $user->userid, $roleid,'manual'); 
                } 
                //print_object($check_enrolment);
                $flag = 1;          
            }
        } 
    }
    if($flag == 1){
        return true;
    }else{
        return false;
    }
}

function professor_coursebatch_enrolment($profid, array $courses){
    global $CFG,$DB;
    $flag = 0;
    $roleid = $DB->get_field('role', 'id', array('shortname' =>'professor'));
    
    foreach ($courses as $course) {
        $check_enrolment = check_enrolment($course, $profid, $roleid,'manual');
        $flag = 1; 
    }
    if($flag == 1){
        return true;
    }else{
        return false;
    }
}
function check_enrolment($courseid, $userid, $roleid, $enrolmethod = 'manual') {
    global $DB;
    $user = $DB->get_record('user', array('id' => $userid, 'deleted' => 0), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
    $context = context_course::instance($course->id);
    if (!is_enrolled($context, $user)) {
        $enrol = enrol_get_plugin($enrolmethod);
        if ($enrol === null) {
            return false;
        }
        $instances = enrol_get_instances($course->id, true);
        $manualinstance = null;
        foreach ($instances as $instance) {
            if ($instance->name == $enrolmethod) {
                $manualinstance = $instance;
                break;
            }
        }
        if ($manualinstance !== null) {
            $instanceid = $enrol->add_default_instance($course);
            if ($instanceid === null) {
                $instanceid = $enrol->add_instance($course);
            }
            $instance = $DB->get_record('enrol', array('id' => $instanceid));
        }
        $enrol->enrol_user($instance, $userid, $roleid);
    }
    return true;
}
function professor_assigned_acadcourses(){
    global $CFG,$DB,$USER,$OUTPUT;
    $i = 1;
    $content = '';
    $content .= '<table id="example1" class="table table-striped table-bordered datatable" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Professor</th>
                            <th>Batch Name</th>
                            <th>Batch Code</th>
                            <th>Course</th>
                        </tr>
                    </thead>';
    $content .= '<tbody>';
    if($DB->record_exists('course_professors',array('createdby'=>$USER->id))){
        $batches_assigned = $DB->get_records('course_professors',array('createdby'=>$USER->id),'id,batchid,courseid,userid');
        foreach ($batches_assigned as $btf) {
            $content .= '<tr>';
            $batch = $DB->get_record('batches',array('id'=>$btf->batchid),'batchname,batchcode');
            $user = $DB->get_record('user',array('id'=>$btf->userid),'id,firstname');
            $course = $DB->get_record('course',array('id'=>$btf->courseid),'id,fullname');
            $content .= '<td>'.$i.'</td>
                        <td>'.$user->firstname.'</td>
                        <td>'.$batch->batchname.'</td>
                        <td>'.$batch->batchcode.'</td>
                        <td>'.$course->fullname.'</td>';
            $content .='</tr>';
            $i++;
        }  
    }else{
        echo '<div id="batchsuccess">';
        echo $OUTPUT->notification('No courses assigned to professors','notifysuccess');
        echo '</div>';
    }                    
    $content .= '</tbody></table>';
    return $content;
}

function user_update_enrollment($tpid,$batchid,$courseid){
    global $CFG,$DB;
    $flag = 0;
    $roleid = $DB->get_field('role', 'id', array('shortname' =>'student'));
    $enrol = $DB->get_record('enrol',array('courseid'=>$courseid,'enrol'=>'manual'),'id');
    $users = $DB->get_records('batch_enrolstudent',array('batchid'=>$batchid),'userid');
    foreach ($users as $user) {
        $check_status = update_enrolment($tpid, $enrol->id, $user->userid, 1);
        if($check_status){
            $flag = 1;
        }
    }
    if($flag == 0){
         return false;
    }else{
         return true;
    }
}
function update_enrolment($tpid, $enrolid, $userid, $status){
    global $DB,$USER,$CFG;
    $ue = $DB->get_record('user_enrolments',array('enrolid'=>$enrolid,'userid'=>$userid),'id'); 
    $update = new stdClass();
    $update->id = $ue->id;
    $update->status = $status;
    $update->enrolid = $enrolid;
    $update->userid = $userid;
    $update->timestart = time();
    $update->modifierid = $tpid;
    $updated = $DB->update_record('user_enrolments',$update);  
    if($updated){
        return true;
    }else{
        return false;
    }
}





  
 
