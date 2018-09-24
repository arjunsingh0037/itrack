<?php
defined('MOODLE_INTERNAL') || die();

function  local_eProjects_extend_settings_navigation($settingsnav, $context){
	global $CFG, $COURSE, $courseid, $DB;
    $course = $DB->get_record('course', array('id' => $COURSE->id));	
    //require_course_login($course);
    $context = context_course::instance($course->id);
    //if (has_capability('moodle/role:assign', $context)) {
          // $coursenode = $settingsnav->get('courseadmin');
          // if($coursenode)
          // {
          //   $coursenode->add(get_string('batch', 'local_course_batches'), null,
          //      navigation_node::TYPE_CONTAINER, null, 'batch',
          //      new pix_icon('i/badge', get_string('batch', 'local_course_batches')));
          //   $url = "$CFG->wwwroot/local/course_batches/batch.php?courseid=$COURSE->id";
          //   $coursenode->get('batch')->add(get_string('addbatch', 'local_course_batches'), $url,
          //       navigation_node::TYPE_SETTING, null, 'batch');
          //   $url = "$CFG->wwwroot/local/course_batches/view_batch.php?id=$COURSE->id";
          //   $coursenode->get('batch')->add(get_string('viewbatch', 'local_course_batches'), $url,
          //     navigation_node::TYPE_SETTING, null, 'course_batches');
        //}
    //}	
}
function get_componentlist(){
  //echo 'here';
  global $CFG,$DB,$USER;
  $add_comp = $CFG->wwwroot.'/local/eProjects/add_component.php';
  $content = '<div class="proj_list">
  <button type="button" id="add_comp" data-url="'.$add_comp.'" class="btn btn-info">Add Component</button></div>';
  $content .= '<div class="panel panel-default">
              <div class="panel-heading listhead">Component Lists</div>
              <div class="panel-body"></div>';
  $table = new html_table();
  $table->head  = array('Sl.no','Stream','Name','Price', 'Action');
  $table->size  = array('10%', '25%', '25%', '10%', '10%');
  $table->colclasses = array ('leftalign','leftalign','leftalign', 'leftalign', 'centeralign');
  $table->attributes['class'] = 'admintable generaltable';
  $table->id = 'filterssetting';
  $data = array();
  $components = $DB->get_records('component',array('createdby'=>$USER->id));
  //print_object($components);
  if($components){
    $i = 1;
    foreach ($components as $ck => $cv) {
      $stream_name = $DB->get_record('stream',array('id'=>$cv->streamid),'stream_name');
      $line = array();
      $line[] = $i;
      $line[] = $stream_name->stream_name;
      $line[] = $cv->name;
      $line[] = $cv->price;
      $line[] = '<button type="button" id="'.$cv->id.'" onclick="edit_component(this.id)" class="btn btn-info crslink edit_comp" data-toggle="modal" data-target="#showEditComponentForm">&#x270e;</button><button type="button" id="'.$cv->id.'" onclick="delete_component(this.id)" class="btn btn-info crslink delete_comp" data-toggle="modal" data-target="#showDeleteComponentForm">&#x1f5d1;</button>';
      $data[] = $line;
      $i++;
    }
  }else{
    $data[] = array('','','No records found','','');
  }
  $table->data  = $data;      
  $content .=html_writer::table($table);
  $content .='</div>';
  echo $content;
}

function get_projectlist(){
  //echo 'here';
  global $CFG,$DB,$USER;
  $add_proj = $CFG->wwwroot.'/local/eProjects/add_project.php';
  $content = '<div class="proj_list">
  <button type="button" id="map_comp" data-url="'.$add_proj.'" class="btn btn-info">Add Project</button></div>';
  $content .= '<div class="panel panel-default">
              <div class="panel-heading listhead">Project Lists</div>
              <div class="panel-body"></div>';
  $table = new html_table();
  $table->head  = array('Sl.no', 'Stream', 'Domain', 'Technology', 'Project Title', 'Component', 'Duration', 'Action');
  $table->size  = array('10%', '10%', '10%', '15%', '15%', '10%', '10%', '10%');
  $table->colclasses = array ('leftalign','leftalign','leftalign', 'leftalign','leftalign', 'leftalign','leftalign','rightalign');
  $table->attributes['class'] = 'admintable generaltable';
  $table->id = 'filterssetting';
  $data = array();
  $projects = $DB->get_records('project',array('createdby'=>$USER->id));
  //print_object($components);
  if($projects){
    $i = 1;
    foreach ($projects as $ck => $pv) {
      $stream_name = $DB->get_record('stream',array('id'=>$pv->stream),'stream_name');
      $domain_name = $DB->get_record('domain',array('id'=>$pv->domain),'domain_name');
      if($pv->has_comp == 1){
        $comp = 'Yes';
      }else{
        $comp = 'No';
      }
      $line = array();
      $line[] = $i;
      $line[] = $stream_name->stream_name;
      $line[] = $domain_name->domain_name;
      $line[] = $pv->technology;
      $line[] = $pv->title;
      $line[] = $comp;
      $line[] = $pv->duration;
      $line[] = '<button type="button" id="'.$pv->id.'" onclick="edit_project(this.id)" class="btn btn-info crslink edit_proj" data-toggle="modal" data-target="#showEditProjectForm">&#x270e;</button>
                <button type="button" id="'.$pv->id.'" onclick="delete_project(this.id)" class="btn btn-info crslink delete_proj" data-toggle="modal" data-target="#showDeleteProjectForm">&#x1f5d1;</button>';
      $data[] = $line;
      $i++;
    }
  }else{
    $data[] = array('','','','No records found','','','','');
  }
  $table->data  = $data;      
  $content .=html_writer::table($table);
  $content .='</div>';
  echo $content;
}

function get_component_maplist(){
  //echo 'here';
  global $CFG,$DB,$USER;
  $content = '<div id="component_maplist" class="panel panel-default">
              <div class="panel-heading listhead">Project Lists</div>
              <div class="panel-body"></div>';
  $table = new html_table();
  $table->head  = array('Sl.no', 'Project Title', 'Component', 'Action');
  $table->size  = array('10%', '10%', '10%', '10%');
  $table->colclasses = array ('leftalign','leftalign','leftalign','rightalign');
  $table->attributes['class'] = 'admintable generaltable';
  $table->id = 'filterssetting';
  $data = array();
  $projects = $DB->get_records('component_projects',array('createdby'=>$USER->id));
  //print_object($components);
  $flag = 0;
  if($projects){
    $i = 1;
    foreach ($projects as $ck => $pv) {
      if($DB->record_exists('project',array('id'=>$pv->projectid))){
        $project_name = $DB->get_record('project',array('id'=>$pv->projectid),'title');
        $comp_name = $DB->get_record('component',array('id'=>$pv->componentid),'name');
        $line = array();
        $line[] = $i;
        $line[] = $project_name->title;
        $line[] = $comp_name->name;
        $line[] = '<button type="button" id="'.$pv->id.'" onclick="delete_mapping(this.id)" class="btn btn-info crslink delete_proj" data-toggle="modal" data-target="#showDeleteMapping">&#x1f5d1;</button>';
        $data[] = $line;
        $i++;
      }else{
        $flag = 1;
      }
    }
  }else{
    $flag = 1;
  }
  if($flag == 1){
    $data[] = array('','No records found','','');
  }
  $table->data  = $data;      
  $content .=html_writer::table($table);
  $content .='</div>';
  echo $content;
}

function get_grouplist(){
  global $CFG,$DB,$USER;
  $content = '<div id="project_grouplist" class="panel panel-default">
              <div class="panel-heading listhead">List of project groups</div>
              <div class="panel-body"></div>';
  $table = new html_table();
  $table->head  = array('Sl.no', 'Group Name', 'Status', 'Action');
  $table->size  = array('10%', '10%', '10%', '10%', '10%');
  $table->colclasses = array ('leftalign','leftalign','leftalign','leftalign','rightalign');
  $table->attributes['class'] = 'admintable generaltable';
  $table->id = 'filterssetting';
  $data = array();
  $pgroups = $DB->get_records('project_group',array('createdby'=>$USER->id));
  //print_object($pgroups);
  $flag = 0;
  if($pgroups){
    $i = 1;
    foreach ($pgroups as $pv) {
        if($pv->status == 0){
          $status = 'Pending';
          $action = '<button type="button" id="'.$pv->id.'" onclick="delete_project_group(this.id)" class="btn btn-info crslink delete_proj_gp" data-toggle="modal">Delete</button>';
        }else if($pv->status == 1){
          $status = 'Approved';
          $action = '';
        }
        $line = array();
        $line[] = $i;
        $line[] = $pv->groupname;
        $line[] = $status;
        $line[] = $action;
        $data[] = $line;
        $i++;
    }
  }else{
    $data[] = array('','No records found','','');
  }
  $table->data  = $data;      
  $content .=html_writer::table($table);
  $content .='</div>';
  echo $content;
}

function group_to_student(){
  //echo 'here';
  global $CFG,$DB,$USER;
  $content = '<div id="component_maplist" class="panel panel-default">
              <div class="panel-heading listhead">LIST OF PROJECT GROUPS</div>
              <div class="panel-body"></div>';
  $table = new html_table();
  $table->head  = array('Sl.no', 'Group Name', 'Students', 'Action');
  $table->size  = array('10%', '10%', '10%', '10%');
  $table->colclasses = array ('leftalign','leftalign','leftalign','rightalign');
  $table->attributes['class'] = 'admintable generaltable';
  $table->id = 'filterssetting';
  $data = array();
  $assigned_group = $DB->get_records('group_to_student',array('createdby'=>$USER->id));
  //print_object($components);
  $group_arr = array();
  foreach ($assigned_group as $agv) {
    $group_arr [$agv->groupid][]= $agv->studentid;
  }
  //print_object($group_arr);
  $flag = 0;
  $temp = 0;
  if($group_arr){
    $i = 1;
    foreach ($group_arr as $ck => $pv) {
      if($DB->record_exists('project_group',array('id'=>$ck))){
        $group_name = $DB->get_record('project_group',array('id'=>$ck),'groupname');
        $line = array();
        $line[] = $i;
        $line[] = $group_name->groupname;
        $line[] = '<button type="button" id="'.$ck.'" onclick="show_students(this.id)" class="btn btn-info crslink delete_proj" data-toggle="modal" data-target="#showStudentLists">View</button>';
        $line[] = '';
        $data[] = $line;
        $i++;
        $temp++;
      }
    }
  }else{
    $flag = 1;
  }
  if($temp == 0){
    $data[] = array('','No records found','','');
  }elseif($flag == 1){
    $data[] = array('','No records found','','');
  }
  $table->data  = $data;      
  $content .=html_writer::table($table);
  $content .='</div>';
  echo $content;
}

function prof_project_requestlist(){
    global $OUTPUT,$CFG,$DB,$PAGE,$USER;
    $count = 0;
    $content = '';
    $msg = '';
    $indi_projects = array();
    $content.='<div id="exTab_request" class=""> 
                    <ul class="nav nav-tabs nav-justified ">
                        <li class="active">
                            <a  href="#1" data-toggle="tab">INDIVIDUAL REQUEST</a>
                        </li>
                        <li>
                            <a href="#2" data-toggle="tab">GROUP REQUEST</a>
                        </li>
                    </ul>';

    if($DB->record_exists('project_request',array())){
        $content .='<div class="tab-content ">
                            <div class="tab-pane active" id="1">';
                            if($DB->record_exists('project_request',array('type'=>'in'))){
                              $individual_requests = $DB->get_records('project_request',array('type'=>'in'));
                              foreach ($individual_requests as $kir => $vir) {
                                $creator_project = $DB->get_record('project',array('createdby'=>$USER->id,'id'=>$vir->projectid));
                                if($creator_project){
                                  $indi_projects[] = array('pid'=>$vir->projectid,'requester'=>$vir->requester,'id'=>$vir->id,'status'=>$vir->status); 
                                }
                              }
                              $table = new html_table();
                              $table->head  = array('Sl.no','Login','Name','Project', 'Student Details', 'Synopsis','Action');
                              $table->size  = array('5%', '10%', '10%', '15%', '15%', '15%', '10%');
                              $table->colclasses = array ('leftalign','leftalign','leftalign', 'leftalign','leftalign','leftalign', 'centeralign');
                              $table->attributes['class'] = 'admintable generaltable';
                              $table->id = 'filterssetting';
                              $data = array();
                              $i = 1;
                              foreach ($indi_projects as $ipval) {
                                $requestedby = $DB->get_record('user',array('id'=>$ipval['requester']),'username,firstname');
                                $project_obj = $DB->get_record('project',array('id'=>$ipval['pid']),'title,synopsis');
                                $line = array();
                                $line[] = $i;
                                $line[] = $requestedby->username;
                                $line[] = $requestedby->firstname;
                                $line[] = $project_obj->title;
                                $line[] = '<button type="button" id="'.$ipval['pid'].'-'.$ipval['requester'].'" onclick="view_single_students(this.id)" class="btn bg-light-blue btn-xs" data-toggle="modal" data-target="#showSingleStudentList">view</button>';
                                $line[] = '<button type="button" id="'.$ipval ['pid'].'" onclick="view_synopsis(this.id)" class="btn bg-light-blue btn-xs" data-toggle="modal" data-target="#showSynopsis">view</button>';

                                if($ipval['status'] == 0){
                                  $line[] = '<button type="button" id="'.$ipval ['id'].'" onclick="approve_request(this.id)" class="btn btn-info requestbtn edit_comp" data-toggle="modal" data-target="#showEditComponentForm">Approve</button><button type="button" id="'.$ipval ['id'].'" onclick="reject_request(this.id)" class="btn btn-info requestbtn1 delete_comp" data-toggle="modal" data-target="#showDeleteComponentForm">Decline</button>';
                                }elseif($ipval['status'] == 1){
                                  $line[] = 'Approved';
                                }else{
                                  $line[] = 'Declined';
                                }
                                $data[] = $line;
                                $i++;
                              }
                              
                              $table->data  = $data;      
                              $content .=html_writer::table($table);
                              
                              //print_object($indi_projects);
                                //$academic_batches = $DB->get_records('batches',array('creatorid'=>$userid,'batchtype'=>'ACAD'));;
                                //$content .=batchlist_tables($academic_batches,'ACAD');

                            }else{
                                $msg = 'You do not have any academic batches'; 
                                $content .= $OUTPUT->notification($msg,'notifysuccess');
                            }
                            $content .='</div>
                            <div class="tab-pane" id="2">';
                            if($DB->record_exists('project_request',array('type'=>'gp'))){
                              $group_requests = $DB->get_records('project_request',array('type'=>'gp'));
                              foreach ($group_requests as $kg => $vg) {
                                $creatorgp_project = $DB->get_record('project',array('createdby'=>$USER->id,'id'=>$vg->projectid));
                                if($creatorgp_project){
                                  $grp_projects[] = array('pid'=>$vg->projectid,'requester'=>$vg->requester,'groupid'=>$vg->groupid,'status'=>$vg->status,'id'=>$vg->id); 
                                }
                              }
                              $table = new html_table();
                              $table->head  = array('Sl.no','Team Lead Login','Team Lead Name','Group Name','No of Students','Project', 'Student Details', 'Synopsis','Group','Project');
                              //$table->size  = array('5%', '10%', '10%', '15%', '15%', '15%', '10%');
                              //$table->colclasses = array ('leftalign','leftalign','leftalign', 'leftalign','leftalign','leftalign', 'centeralign');
                              $table->attributes['class'] = 'admintable generaltable';
                              $table->id = 'filterssetting';
                              $data = array();
                              $i = 1;
                              $count = 0;
                              foreach ($grp_projects as $ipval) {
                                $requestedby = $DB->get_record('user',array('id'=>$ipval['requester']),'username,firstname');
                                $project_obj = $DB->get_record('project',array('id'=>$ipval['pid']),'title,synopsis');
                                $group_obj = $DB->get_record('project_group',array('id'=>$ipval['groupid']),'id,groupname,status');
                                $group_members = $DB->get_records('group_to_student',array('groupid'=>$ipval['groupid'],'createdby'=>$ipval['requester']),'id,studentid');
                                $count = count($group_members);
                                $count = $count + 1;
                                $line = array();
                                $line[] = $i;
                                $line[] = $requestedby->username;
                                $line[] = $requestedby->firstname;
                                $line[] = $group_obj->groupname;
                                $line[] = $count;
                                $line[] = $project_obj->title;
                                $line[] = '<button type="button" id="'.$ipval['groupid'].'" onclick="view_group_students(this.id)" class="btn bg-light-blue btn-xs" data-toggle="modal" data-target="#showGroupStudentList">view</button>';
                                $line[] = '<button type="button" id="'.$ipval ['pid'].'" onclick="view_synopsis(this.id)" class="btn bg-light-blue btn-xs" data-toggle="modal" data-target="#showSynopsis">view</button>';
                                if($group_obj->status == 0){
                                  $line[] = '<button type="button" id="'.$group_obj->id.'" onclick="approve_group(this.id)" class="btn btn-info requestbtn edit_comp" data-toggle="modal" data-target="#showEditComponentForm">Approve</button><button type="button" id="'.$group_obj->id.'" onclick="reject_group(this.id)" class="btn btn-info requestbtn1 delete_comp" data-toggle="modal" data-target="#showDeleteComponentForm">Decline</button>';
                                }elseif($group_obj->status == 1){
                                  $line[] = 'Approved';
                                }else{
                                  $line[] = 'Declined';
                                }
                                if($ipval['status'] == 0){
                                  $line[] = '<button type="button" id="'.$ipval ['id'].'" onclick="approve_request(this.id)" class="btn btn-info requestbtn edit_comp" data-toggle="modal" data-target="#showEditComponentForm">Approve</button><button type="button" id="'.$ipval ['id'].'" onclick="reject_request(this.id)" class="btn btn-info requestbtn1 delete_comp" data-toggle="modal" data-target="#showDeleteComponentForm">Decline</button>';
                                }elseif($ipval['status'] == 1){
                                  $line[] = 'Approved';
                                }else{
                                  $line[] = 'Declined';
                                }
                                
                                $data[] = $line;
                                $i++;
                              }
                              
                              $table->data  = $data;      
                              $content .=html_writer::table($table);

                            }else{
                                $msg = 'You do not have any non-academic batches'; 
                                $content .= $OUTPUT->notification($msg,'notifysuccess');
                            }
                            $content .= '</div></div>';
    }else{
        $content .= 'No request to show';
    }
    $content .= '</div>';
    //print_object($categories2);die();
    echo $content;
}

function get_mygroup_requestlist(){
  global $CFG,$DB,$USER;
  $content = '<div id="project_grouplist" class="panel panel-default">
              <div class="panel-heading listhead">LIST OF NEW GROUP REQUESTS</div>
              <div class="panel-body"></div>';
  $table = new html_table();
  $table->head  = array('Sl.no', 'Group Name', 'Status', 'Action');
  $table->size  = array('10%', '20%', '20%', '30%');
  $table->colclasses = array ('leftalign','leftalign','leftalign','centeralign');
  $table->attributes['class'] = 'admintable generaltable';
  $table->id = 'filterssetting';
  $data = array();
  $pgroups = $DB->get_records('group_to_student',array('studentid'=>$USER->id));
  $flag = 0;
  if($pgroups){
    $i = 1;
    foreach ($pgroups as $pv) {
        if($pv->status == 0){
          $status = 'Pending';
          $action = '<button type="button" id="'.$pv->id.'" onclick="approve_group_request(this.id)" class="btn btn-info requestbtn2 edit_comp" data-toggle="modal" data-target="#showEditComponentForm">Approve</button><button type="button" id="'.$pv->id.'" onclick="reject_group_request(this.id)" class="btn btn-info requestbtn21 delete_comp" data-toggle="modal" data-target="#showDeleteComponentForm">Decline</button>';
        }else if($pv->status == 1){
          $status = 'Approved';
          $action = '-';
        }else{
          $status = 'Declined';
          $action = '-';
        }
        $gpname = $DB->get_record('project_group',array('id'=>$pv->groupid),'groupname');
        $line = array();
        $line[] = $i;
        $line[] = $gpname->groupname;
        $line[] = $status;
        $line[] = $action;
        $data[] = $line;
        $i++;
    }
  }else{
    $data[] = array('','No records found','','');
  }
  $table->data  = $data;      
  $content .=html_writer::table($table);
  $content .='</div>';
  echo $content;
}
function stu_projectlist(){
    global $OUTPUT,$CFG,$DB,$PAGE,$USER;
    $count = 0;
    $content = '';
    $msg = '';
    $indi_projects = array();
    $content.='<div id="exTab_request" class=""> 
                    <ul class="nav nav-tabs nav-justified ">
                        <li class="active">
                            <a  href="#1" data-toggle="tab"><span class="stulist">ACADEMIC PROJECTS </span></a>
                        </li>
                        <li>
                            <a href="#2" data-toggle="tab"><span class="stulist">SUBSCRIBED INDUSTRY PROJECTS</span></a>
                        </li>
                    </ul>';

    if($DB->record_exists('project_request',array())){
        $indi_projects = array();
        $content .='<div class="tab-content ">
                          <div class="tab-pane active" id="1">';
                            if($DB->record_exists('project_request',array('type'=>'in','requester'=>$USER->id,'status'=>1))){
                              $individual_requests = $DB->get_records('project_request',array('requester'=>$USER->id,'status'=>1));
                              foreach ($individual_requests as $kir => $vir) {
                                $creator_project = $DB->get_record('project',array('id'=>$vir->projectid));
                                if($creator_project){
                                  $indi_projects[] = array('pid'=>$vir->projectid,'requester'=>$vir->requester,'id'=>$vir->id,'status'=>$vir->status,'type'=>$vir->type,'time'=>$vir->timerequested); 
                                }
                              }
                              $table = new html_table();
                              $table->head  = array('Sl.no','Title','Domain','Type', 'Last Date', 'View Weightage','Status');
                              $table->size  = array('5%', '10%', '10%', '15%', '15%', '15%', '10%');
                              $table->colclasses = array ('leftalign','leftalign','leftalign', 'leftalign','leftalign','leftalign', 'centeralign');
                              $table->attributes['class'] = 'admintable generaltable';
                              $table->id = 'filterssetting';
                              $data = array();
                              $i = 1;
                              foreach ($indi_projects as $ipval) {
                                $requestedby = $DB->get_record('user',array('id'=>$ipval['requester']),'username,firstname');
                                $project_obj = $DB->get_record('project',array('id'=>$ipval['pid']),'title,synopsis,domain,duration');
                                $dmn = $DB->get_record('domain',array('id'=>$project_obj->domain),'domain_name');
                                $line = array();
                                $line[] = $i;
                                $line[] = $project_obj->title;
                                $line[] = $dmn->domain_name;
                                if($ipval['type'] == 'gp'){
                                  $line[] = 'Group';
                                }else{
                                  $line[] = 'Individual';
                                }
                                $date = userdate($ipval['time'], '%Y-%m-%d', 99, true);
                                $final_date = date('d-m-Y', strtotime($date. ' + '.$project_obj->duration.' days'));
                                $line[] = $final_date;
                                $line[] = '<button type="button" id="'.$ipval ['pid'].'" onclick="view_synopsis(this.id)" class="btn bg-light-blue btn-xs" data-toggle="modal" data-target="#showSynopsis">view</button>';

                                if($ipval['status'] == 0){
                                  $line[] = 'Pending';
                                }elseif($ipval['status'] == 1){
                                  $line[] = 'Active';
                                }else{
                                  $line[] = 'Declined';
                                }
                                $data[] = $line;
                                $i++;
                              }
                              $table->data  = $data;      
                              $content .=html_writer::table($table);
                            }else{
                                $msg = '<p style="text-align:center">You do not have any academic projects</p>'; 
                                $content .= $msg;
                            }
                            //Group project list for students
                            $content .='</div>
                            <div class="tab-pane" id="2">';
                            $msg = 'You do not have any non-academic projects'; 
                            $content .= $msg;
                            $content .= '</div></div>';
    }else{
        $content .= 'No request to show';
    }
    $content .= '</div>';
    //print_object($categories2);die();
    echo $content;
}