<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Handles uploading files
 *
 * @package    local_assign_course
 * @copyright  Prashant Yallatti<prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
require_once('../../config.php');
require_once('assign_course_form.php');
require_once($CFG->libdir . '/formslib.php');
$crsid = optional_param('crsid', 0 ,PARAM_INT);
//require_once($CFG->dirroot.'/calendar/lib.php');
require_once($CFG->dirroot.'/calendar/lib.php');
require_once($CFG->dirroot.'/course/lib.php');
require_login(0,false);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_url($CFG->wwwroot . '/local/assign_course/assign_course.php');
//$PAGE->requires->css('/styles.css');
$PAGE->requires->jquery('select.js');

$title = get_string('pluginname', 'local_assign_course');
$id = $USER->id;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->navbar->add($title);
echo $OUTPUT->header();
$temp = false;
$sql = "select * from {user}";
$result = $DB->get_records_sql($sql);
// foreach($result as $user){
//  profile_load_custom_fields($user);
//  $mgr = $user->profile['manager'];
//     if($mgr==$USER->username){
//         $temp = true;
//     } 
// }

$temp = true;
if($temp || is_siteadmin()){
    $flag = '';
    $mform = new local_assign_course_form();
    $data = $mform->get_data();
    if ($mform->is_cancelled()){
        redirect(new moodle_url('/local/assign_course/assign_course.php', array()));
    } else if ($data) {
        foreach($data->users as $user) {
            $answer = explode(',',$user);
            $exists = $DB->get_record('local_assign_course',array('userid'=>$user[0],'courseid'=>$crsid));
            if(!$exists){
                $insert = new stdClass();
                $insert->courseid =$crsid;
                $insert->userid =$answer[0];
                $insert->assign_date = time();
                $insert->end_date = $data->end_date;
                $insert->created_by = $USER->username;
                $insert->created_date = time();
                $insert->modified_date = null;
                $insert->status = 1;
                $assigned = $DB->insert_record('local_assign_course',$insert);
                //creating events
                if(is_siteadmin()){
                    $courserec = $DB->get_record('course',array('id'=>$crsid));    
                    $event = new stdClass();
                    $event->eventtype ='site';
                    $event->type = 0;
                    $event->name = $courserec->fullname.','.get_string('coursename','local_assign_course');
                    $event->description =$courserec->fullname.','.get_string('coursedec','local_assign_course').','.time();
                    $event->courseid = $crsid;
                    $event->groupid = 0;
                    $event->userid =$user[0];
                    $event->modulename = 0;
                    $event->instance = 0;
                    $event->timestart = time();
                    $event->visible = 1;
                    $event->timeduration = $data->end_date;
                    calendar_event::create($event);                  
                }
                $flag = 1;
            }
        }    
        if($flag == 1){
            $msg = '<div class="alert alert-success">Record is Inserted Successfully!!!</div>';
            redirect(new moodle_url('/local/assign_course/assign_course.php'),$msg);
        }else{
            echo '<div class="alert alert-success">Record Exists!!</div>';
        }

    }
    $mform->display();
    $table = new html_table();
    $page_per_records = 5;
    $page = '';
    if(isset($_GET["page"])) {
        $page = $_GET["page"];
    }
    else {
        $page = 1;
    }
    $start_from = ($page-1)*$page_per_records;
    if(is_siteadmin()){
        $sql = "SELECT lac.id as lac,c.fullname,u.id, u.firstname,u.lastname,lac.assign_date,lac.end_date
        FROM {local_assign_course} lac
        JOIN {course} c ON c.id=lac.courseid
        JOIN {user} u ON u.id=lac.userid ORDER BY lac.id
        limit $start_from,$page_per_records";
    }else {
        $sql = "SELECT lac.id as lac,c.fullname,u.id, u.firstname,u.lastname,lac.assign_date,lac.end_date
        FROM {local_assign_course} lac
        JOIN {course} c ON c.id=lac.courseid
        JOIN {user} u ON u.id=lac.userid 
        where lac.created_by = '$USER->username'
        limit $start_from,$page_per_records ";
    }
    $result = $DB->get_records_sql($sql);
    $table->head = (array) get_strings(array('course', 'user','start','end'), 'local_assign_course');
    if($result==null) {
        echo '<div class="alert alert-danger">
        Record is not exists!!!
    </div>';
    } else {
            foreach($result as $record){
                $table->data[] = array(
                    $record->fullname,
                    $record->firstname.' '.$record->lastname,
                    userdate($record->assign_date,'%d-%m-%y'),
                    userdate($record->end_date,'%d-%m-%y')
                    );
            }
        $query ="SELECT * FROM {local_assign_course} ORDER BY id asc ";
        $page_result = $DB->get_records_sql($query);
        $total_records = count($page_result);
        $total_pages = ceil($total_records/$page_per_records);
        if($total_pages){
            $pagLink = "<nav><ul class='pagination'>";
            for ($i=1; $i<=$total_pages; $i++) {
                $pagLink .= "<li><a href='assign_course.php?page=".$i."'>".$i."</a></li>";
            }
        }
        echo html_writer::table($table);
        echo $pagLink . "</ul></nav>";
    } 
}else{
    echo '<div class="alert alert-danger">You don\'t have permission to assign a course !!!</div>';
}   
echo $OUTPUT->footer();
?>
<script type="text/javascript">
$(document).ready(function(){
    $("#id_courses").append("<option value='' selected>Select course</option>");
    $("#id_courses").change(function(){
        var crsid = $(this).val();
        $.ajax({
            url: 'courseajax.php',
            type: 'post',
            data: {id:crsid},
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#id_users").empty();
                for( var i = 0; i<len; i++){
                    var id = response[i]['id'];
                    var firstname = response[i]['firstname'];
                    $("#id_users").append("<option value='"+id+"'>"+firstname+"</option>");
                }
            }
        });
    });
});
</script>
<?php