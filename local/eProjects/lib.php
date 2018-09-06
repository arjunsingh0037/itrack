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


