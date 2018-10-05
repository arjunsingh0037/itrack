<?php
defined('MOODLE_INTERNAL') || die();

function  local_eLabs_extend_settings_navigation($settingsnav, $context){
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

function prof_elabs_requestlist(){
    global $OUTPUT,$CFG,$DB,$PAGE,$USER;
    $count = 0;
    $content = '';
    $msg = '';
    $indi_projects = array();
    $context = context_user::instance($USER->id, MUST_EXIST);
    $content.='<div id="exTab_request" class=""> 
                    <ul class="nav nav-tabs nav-justified ">
                        <li class="active">
                            <a  href="#1" data-toggle="tab">ACADEMIC LABS</a>
                        </li>
                        <li>
                            <a href="#2" data-toggle="tab">SUBSCRIBED INDUSTRY LABS</a>
                        </li>
                    </ul>';

        $all_elabs = $DB->get_records('vpl');
        foreach ($all_elabs as $allk => $allv){
            if($allv->shortdescription == 'ACAD'){
              $acad_elabs[] = $allv;
            }else if($allv->shortdescription == 'SUBS'){
              $subs_elabs[] = $allv;
            }
        }
        $content .='<div class="tab-content ">
                      <div class="tab-pane active" id="1">';
                      if($acad_elabs){                              
                        $table = new html_table();
                        $table->head  = array('Sl.no','Course','Labid','Lab Name', 'Available Upto', 'Assigned Batch','Resource', 'View Details', 'Edit', 'Delete');
                        $table->size  = array('5%', '10%', '10%', '10%', '15%', '15%', '10%','10%','5%','5%');
                        $table->colclasses = array ('leftalign','leftalign','leftalign', 'leftalign','leftalign','leftalign','leftalign','leftalign','leftalign', 'centeralign');
                        $table->attributes['class'] = 'admintable generaltable';
                        $table->id = 'filterssetting';
                        $data = array();
                        $i = 1;
                        foreach ($acad_elabs as $ipval) {
                          $course_obj = $DB->get_record('course',array('id'=>$ipval->course),'id,fullname,shortname');
                          $labdetails_obj = $DB->get_record('vpl_details',array('labid'=>$ipval->id),'labname,resourcefile');
                          $line = array();

                          $line[] = $i;
                          $line[] = $course_obj->fullname;
                          $line[] = $ipval->name;
                          $line[] = $labdetails_obj->labname;
                          $line[] = userdate($ipval->duedate);
                          $lab_assigned = $DB->get_records('vpl_assignedbatches',array('labid'=>$ipval->id));
                          if($lab_assigned){
                            $line[] = '<button type="button" id="'.$ipval->id.'" onclick="view_acadlab_assigned(this.id)" class="btn bg-light-blue btn-xs" data-toggle="modal" data-target="#acad_assignedBatch">view</button>';
                          }else{
                            $line[] = '<div class="alert alert-danger" role="alert">Not assigned</div>';
                          }
                          $fl = $DB->get_records('files',array('itemid'=>$labdetails_obj->resourcefile));
                          foreach ($fl as $fk => $fv) {
                            if(!($fv->filesize == 0)){
                              $filename = $fv->filename;
                              $filearea = $fv->filearea;
                              $itemid = $fv->itemid;
                              $draftid = $fv->id;
                              $filepath = $fv->filepath;
                            }
                          }
                          if (!empty($itemid)) {
                              $fs = get_file_storage();
                              $usercontext = context_user::instance($USER->id);
                              if (empty($filename)) {
                                  if ($files = $fs->get_area_files($usercontext->id, 'user', 'draft', $itemid, 'id DESC', false)) {
                                      $file = reset($files);
                                  }
                              } else {
                                  $file = $fs->get_file($usercontext->id, 'user', 'draft', $itemid, $filepath, $filename);
                              }
                              if (!empty($file)) {
                                  $url = html_writer::link(moodle_url::make_draftfile_url($file->get_itemid(), $file->get_filepath(), $file->get_filename()), 'View',array('class'=>'resurl'));
                              }
                          }
                          $line[] = '<button type="button" class="btn bg-light-blue btn-xs resourceurl" data-toggle="modal" data-target="#acad_resource">'.$url.'</button>';

                          $line[] = '<button type="button" id="'.$ipval->id.'" onclick="view_acadlab_details(this.id)" class="btn bg-light-blue btn-xs" data-toggle="modal" data-target="#acad_viewdetails">view Details</button>';

                          $line[] = '<button type="button" id="" onclick="edit_acadlab(this.id)" class="btn btn-info crslink edit_comp" data-toggle="modal" data-target="#showEditACADForm">&#x270e;</button>';

                          $line[] = '<button type="button" id="" onclick="delete_acadlab(this.id)" class="btn btn-info crslink delete_comp" data-toggle="modal" data-target="#showDeleteACADForm">&#x1f5d1;</button>';
                          
                          $data[] = $line;
                          $i++;
                        }
                        $table->data  = $data;      
                        $content .=html_writer::table($table);
                        
                        //print_object($indi_projects);
                          //$academic_batches = $DB->get_records('batches',array('creatorid'=>$userid,'batchtype'=>'ACAD'));;
                          //$content .=batchlist_tables($academic_batches,'ACAD');

                      }else{
                          $msg = 'You do not have any academic elabs'; 
                          $content .= $OUTPUT->notification($msg,'notifysuccess');
                      }
                      $content .='</div>
                      <div class="tab-pane" id="2">';
                      if($subs_elabs){                              
                        $table = new html_table();
                        $table->head  = array('Sl.no','View Details','Course','Labid','Lab Name', 'Available Upto', 'Assigned Batch','Resource', 'Edit', 'Delete');
                        $table->size  = array('5%', '10%', '10%', '10%', '15%', '10%', '10%','10%','10%','10%');
                        $table->colclasses = array ('leftalign','leftalign','leftalign', 'leftalign','leftalign','leftalign','leftalign','leftalign','leftalign', 'centeralign');
                        $table->attributes['class'] = 'admintable generaltable';
                        $table->id = 'filterssetting';
                        $data = array();
                        $i = 1;
                        foreach ($subs_elabs as $ipval) {
                          $course_obj = $DB->get_record('course',array('id'=>$ipval->course),'id,fullname,shortname');
                          $labdetails_obj = $DB->get_record('vpl_details',array('labid'=>$ipval->id),'labname,resourcefile');
                          $line = array();

                          $line[] = $i;

                          $line[] = '<button type="button" id="'.$ipval->id.'" onclick="view_subslab_details(this.id)" class="btn bg-light-blue btn-xs" data-toggle="modal" data-target="#subs_viewdetails">view Details</button>';

                          $line[] = $course_obj->fullname;
                          $line[] = $ipval->name;
                          $line[] = $labdetails_obj->labname;
                          $line[] = userdate($ipval->duedate);

                          $lab_assigned = $DB->get_records('vpl_assignedbatches',array('labid'=>$ipval->id));
                          if($lab_assigned){
                            $line[] = '<button type="button" id="" onclick="view_subslab_assigned(this.id)" class="btn bg-light-blue btn-xs" data-toggle="modal" data-target="#acad_assignedBatch">view</button>';
                          }else{
                            $line[] = '<div class="alert alert-danger" role="alert">Not assigned</div>';
                          }
                          $fl = $DB->get_records('files',array('itemid'=>$labdetails_obj->resourcefile));
                          foreach ($fl as $fk => $fv) {
                            if(!($fv->filesize == 0)){
                              $filename = $fv->filename;
                              $filearea = $fv->filearea;
                              $itemid = $fv->itemid;
                              $draftid = $fv->id;
                              $filepath = $fv->filepath;
                            }
                          }
                          if (!empty($itemid)) {
                              $fs = get_file_storage();
                              $usercontext = context_user::instance($USER->id);
                              if (empty($filename)) {
                                  if ($files = $fs->get_area_files($usercontext->id, 'user', 'draft', $itemid, 'id DESC', false)) {
                                      $file = reset($files);
                                  }
                              } else {
                                  $file = $fs->get_file($usercontext->id, 'user', 'draft', $itemid, $filepath, $filename);
                              }
                              if (!empty($file)) {
                                  $url = html_writer::link(moodle_url::make_draftfile_url($file->get_itemid(), $file->get_filepath(), $file->get_filename()), 'View',array('class'=>'resurl'));
                              }
                          }
                          $line[] = '<button type="button" class="btn bg-light-blue btn-xs resourceurl" data-toggle="modal" data-target="#acad_resource">'.$url.'</button>';

                          
                          $line[] = '<button type="button" id="" onclick="edit_subslab(this.id)" class="btn btn-info crslink edit_comp" data-toggle="modal" data-target="#showEditSUBSForm">&#x270e;</button>';

                          $line[] = '<button type="button" id="" onclick="delete_subslab(this.id)" class="btn btn-info crslink delete_comp" data-toggle="modal" data-target="#showDeleteSUBSForm">&#x1f5d1;</button>';
                          
                          $data[] = $line;
                          $i++;
                        }
                        $table->data  = $data;      
                        $content .=html_writer::table($table);
                      }else{
                          $msg = 'You do not have any subscribed elabs'; 
                          $content .= $OUTPUT->notification($msg,'notifysuccess');
                      }
                      $content .= '</div></div>';
   
    $content .= '</div>';
    //print_object($categories2);die();
    echo $content;
}
function make_draftfile_url($contextid, $draftid, $pathname, $filename, $forcedownload = false) {
        global $CFG, $USER;
        $urlbase = "$CFG->httpswwwroot/draftfile.php";
        return make_file_url($urlbase, "/$contextid/user/draft/$draftid".$pathname.$filename, $forcedownload);
}
function make_file_url($urlbase, $path, $forcedownload = false) {
        $params = array();
        if ($forcedownload) {
            $params['forcedownload'] = 1;
        }

        $url = new moodle_url($urlbase, $params);
        $url->set_slashargument($path);

        return $url;
} 

function student_elabsist(){
    global $OUTPUT,$CFG,$DB,$PAGE,$USER;
    $count = 0;
    $content = '';
    $msg = '';
    $indi_projects = array();
    $context = context_user::instance($USER->id, MUST_EXIST);
    $content.='<div id="exTab_request2" class=""> 
                    <ul class="nav nav-tabs nav-justified ">
                        <li class="active">
                            <a  href="#1" data-toggle="tab">ACADEMIC LABS</a>
                        </li>
                        <li>
                            <a href="#2" data-toggle="tab">SUBSCRIBED INDUSTRY COURSE LABS</a>
                        </li>
                        <li>
                            <a href="#3" data-toggle="tab">SUBSCRIBED LABS</a>
                        </li>
                    </ul>';

        $all_elabs = $DB->get_records('vpl');
        $acad_elabs = array();
        $subs_elabs = array();
        $subs_industry = array();
        foreach ($all_elabs as $allk => $allv){
            if($allv->shortdescription == 'ACAD'){
              $acad_elabs[] = $allv;
            }else if($allv->shortdescription == 'SUBS'){
              $subs_elabs[] = $allv;
            }else{
              $subs_industry[] = array();
            }
        }
        $user_labs = array();
        $content .='<div class="tab-content ">
                      <div class="tab-pane active" id="1">';
                      if($acad_elabs){      
                          $user_batch = $DB->get_records('batch_enrolstudent',array('userid'=>$USER->id));
                          if($user_batch){
                            foreach ($user_batch as $uk => $ub) {
                              $batches[$ub->batchid] = $ub;
                            }                        
                            foreach ($batches as $bk2 => $bv2) {
                              $check_assigned_labs = $DB->get_records('vpl_assignedbatches',array('batchid'=>$bk2));
                            }
                            //print_object($check_assigned_labs);
                            foreach ($check_assigned_labs as $lk => $lv) {
                              $btype = $DB->get_record('vpl',array('id'=>$lv->labid),'shortdescription');
                              if($btype->shortdescription == 'ACAD'){
                                $user_labs[$lv->labid] = $lv;
                              }
                              
                            }
                          }

                          //print_object($user_labs);
                          $table = new html_table();
                          $table->head  = array('Sl.no','Course','Labid','Lab Name', 'Level','Language','Start Date','End Date' ,'Action','Resource');
                          $table->size  = array('5%', '10%', '10%', '10%', '15%', '15%', '10%','10%','5%','5%');
                          $table->colclasses = array ('leftalign','leftalign','leftalign', 'leftalign','leftalign','leftalign','leftalign','leftalign','leftalign', 'centeralign');
                          $table->attributes['class'] = 'admintable generaltable';
                          $table->id = 'filterssetting';
                          $data = array();
                          $i = 1;
                          if($user_labs){
                            foreach ($user_labs as $ipval) {

                              $course_obj = $DB->get_record('course',array('id'=>$ipval->courseid),'id,fullname,shortname');
                              $lab_obj = $DB->get_record('vpl',array('id'=>$ipval->labid),'id,name,startdate,duedate,worktype,runscript');
                              $labdetails_obj = $DB->get_record('vpl_details',array('labid'=>$ipval->labid),'labname,resourcefile,createdby');
                              
                              $line = array();
                              $line[] = $i;
                              $line[] = $course_obj->fullname;
                              $line[] = $lab_obj->name;
                              $line[] = $labdetails_obj->labname;
                              if($lab_obj->worktype == 0){
                                $level = 'Begineers';
                              }else if($lab_obj->worktype == 1){
                                $level = 'Intermediate';
                              }else if($lab_obj->worktype == 2){
                                $level = 'Expert';
                              }
                              $line[] = $level;
                              $line[] = ucwords($lab_obj->runscript);
                              $line[] = userdate($lab_obj->startdate);
                              $line[] = userdate($lab_obj->duedate);
                              $line[] = '<button type="button" id="'.$ipval->labid.'" onclick="view_acadlab_students(this.id)" class="btn bg-light-blue btn-xs" data-toggle="modal" data-target="#acad_assignedBatchStudent">Start Lab</button>';
                              if($labdetails_obj->resourcefile){
                                  $fl = $DB->get_records('files',array('itemid'=>$labdetails_obj->resourcefile));
                                
                                  foreach ($fl as $fk => $fv) {
                                    if(!($fv->filesize == 0)){
                                      $filename = $fv->filename;
                                      $filearea = $fv->filearea;
                                      $itemid = $fv->itemid;
                                      $draftid = $fv->id;
                                      $filepath = $fv->filepath;
                                    }
                                  }
                                  if (!empty($itemid)) {
                                      $fs = get_file_storage();
                                      $usercontext1 = $DB->get_record('context',array('contextlevel'=>'30','instanceid'=>$labdetails_obj->createdby),'id');
                                      if (empty($filename)) {
                                          if ($files = $fs->get_area_files($usercontext1->id, 'user', 'draft', $itemid, 'id DESC', true)) {
                                              $file = reset($files);
                                          }
                                      } else {
                                          $file = $fs->get_file($usercontext1->id, 'user', 'draft', $itemid, $filepath, $filename);
                                      }
                                      if (!empty($file)) {
                                          $url = html_writer::link(moodle_url::make_draftfile_url($file->get_itemid(), $file->get_filepath(), $file->get_filename()), 'View',array('class'=>'resurl'));
                                      }
                                  }
                                  $usercontext11 = context_user::instance($USER->id);
                                  $usercontext22 = $DB->get_record('context',array('contextlevel'=>'30','instanceid'=>$labdetails_obj->createdby),'id');

                                  $url2 = str_replace($usercontext11->id,$usercontext22->id,$url);
                                  $line[] = '<button type="button" class="btn bg-light-blue btn-xs resourceurl" data-toggle="modal" data-target="#acad_resource">'.$url2.'</button>';
                              }else{
                                $line[] = '<div class="alert alert-danger" role="alert">No Resource Uploaded</div>';
                              }
                              
                              $data[] = $line;
                              $i++;
                            }
                          }else{
                            $data[] = 'No records found';
                          }
                          $table->data  = $data;      
                          $content .=html_writer::table($table);
                          
                          //print_object($indi_projects);
                            //$academic_batches = $DB->get_records('batches',array('creatorid'=>$userid,'batchtype'=>'ACAD'));;
                            //$content .=batchlist_tables($academic_batches,'ACAD');

                        }else{
                            $msg = 'You do not have any academic elabs'; 
                            $content .= $OUTPUT->notification($msg,'notifysuccess');
                        }
                      $content .='</div>';
                      
                      ///Tab 2 starts
                      $content .='<div class="tab-pane" id="2">';
                      if($subs_industry){                              
                       

                      }else{
                          $msg = '<p style="text-align:center">You do not have any subscribed industry elabs</p>'; 
                          $content .= $msg;
                      }
                      $content .='</div>';
                      
                      //Tab3 Starts
                      $content .='<div class="tab-pane " id="3">';
                      if($subs_elabs){
                          $user_labs1 = array();      
                          $user_batch1 = $DB->get_records('batch_enrolstudent',array('userid'=>$USER->id));
                          if($user_batch1){
                            foreach ($user_batch1 as $uk => $ub) {
                              $batches1[$ub->batchid] = $ub;
                            }                        
                            foreach ($batches as $bk2 => $bv2) {
                              $check_assigned_labs1 = $DB->get_records('vpl_assignedbatches',array('batchid'=>$bk2));
                            }
                            foreach ($check_assigned_labs1 as $lk => $lv) {
                              $btype = $DB->get_record('vpl',array('id'=>$lv->labid),'shortdescription');
                              if($btype->shortdescription == 'SUBS'){
                                $user_labs1[$lv->labid] = $lv;
                              }
                              
                            }
                          }

                          //print_object($user_labs);
                          $table = new html_table();
                          $table->head  = array('Sl.no','Course','Labid','Lab Name', 'Level','Language','Start Date','End Date' ,'Action','Resource');
                          $table->size  = array('5%', '10%', '10%', '10%', '15%', '15%', '10%','10%','5%','5%');
                          $table->colclasses = array ('leftalign','leftalign','leftalign', 'leftalign','leftalign','leftalign','leftalign','leftalign','leftalign', 'centeralign');
                          $table->attributes['class'] = 'admintable generaltable';
                          $table->id = 'filterssetting';
                          $data = array();
                          $i = 1;
                          if($user_labs1){
                            foreach ($user_labs1 as $ipval) {

                              $course_obj = $DB->get_record('course',array('id'=>$ipval->courseid),'id,fullname,shortname');
                              $lab_obj = $DB->get_record('vpl',array('id'=>$ipval->labid),'id,name,startdate,duedate,worktype,runscript');
                              $labdetails_obj = $DB->get_record('vpl_details',array('labid'=>$ipval->labid),'labname,resourcefile,createdby');
                              
                              $line = array();
                              $line[] = $i;
                              $line[] = $course_obj->fullname;
                              $line[] = $lab_obj->name;
                              $line[] = $labdetails_obj->labname;
                              if($lab_obj->worktype == 0){
                                $level = 'Begineers';
                              }else if($lab_obj->worktype == 1){
                                $level = 'Intermediate';
                              }else if($lab_obj->worktype == 2){
                                $level = 'Expert';
                              }
                              $line[] = $level;
                              $line[] = ucwords($lab_obj->runscript);
                              $line[] = userdate($lab_obj->startdate);
                              $line[] = userdate($lab_obj->duedate);
                              $line[] = '<button type="button" id="" onclick="view_acadlab_students(this.id)" class="btn bg-light-blue btn-xs" data-toggle="modal" data-target="#acad_assignedBatchStudent">Start Lab</button>';
                              if($labdetails_obj->resourcefile){
                                  $fl = $DB->get_records('files',array('itemid'=>$labdetails_obj->resourcefile));
                                
                                  foreach ($fl as $fk => $fv) {
                                    if(!($fv->filesize == 0)){
                                      $filename = $fv->filename;
                                      $filearea = $fv->filearea;
                                      $itemid = $fv->itemid;
                                      $draftid = $fv->id;
                                      $filepath = $fv->filepath;
                                    }
                                  }
                                  if (!empty($itemid)) {
                                      $fs = get_file_storage();
                                      $usercontext1 = $DB->get_record('context',array('contextlevel'=>'30','instanceid'=>$labdetails_obj->createdby),'id');
                                      if (empty($filename)) {
                                          if ($files = $fs->get_area_files($usercontext1->id, 'user', 'draft', $itemid, 'id DESC', true)) {
                                              $file = reset($files);
                                          }
                                      } else {
                                          $file = $fs->get_file($usercontext1->id, 'user', 'draft', $itemid, $filepath, $filename);
                                      }
                                      if (!empty($file)) {
                                          $url = html_writer::link(moodle_url::make_draftfile_url($file->get_itemid(), $file->get_filepath(), $file->get_filename()), 'View',array('class'=>'resurl'));
                                      }
                                  }
                                  $usercontext11 = context_user::instance($USER->id);
                                  $usercontext22 = $DB->get_record('context',array('contextlevel'=>'30','instanceid'=>$labdetails_obj->createdby),'id');

                                  $url2 = str_replace($usercontext11->id,$usercontext22->id,$url);
                                  $line[] = '<button type="button" class="btn bg-light-blue btn-xs resourceurl" data-toggle="modal" data-target="#acad_resource">'.$url2.'</button>';
                              }else{
                                $line[] = '<div class="alert alert-danger" role="alert">No Resource Uploaded</div>';
                              }
                              
                              $data[] = $line;
                              $i++;
                            }
                          }else{
                            $data[] = 'No records found';
                          }
                          $table->data  = $data;      
                          $content .=html_writer::table($table);
                          
                          //print_object($indi_projects);
                            //$academic_batches = $DB->get_records('batches',array('creatorid'=>$userid,'batchtype'=>'ACAD'));;
                            //$content .=batchlist_tables($academic_batches,'ACAD');

                        }else{
                            $msg = 'You do not have any academic elabs'; 
                            $content .= $OUTPUT->notification($msg,'notifysuccess');
                        }
                      $content .='</div></div>';
   
    $content .= '</div>';
    //print_object($categories2);die();
    echo $content;
}

