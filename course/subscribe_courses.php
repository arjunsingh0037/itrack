<?php
// This file is part of Moodle - http://moodle.org/
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
 * Page for creating or editing course category name/parent/description.
 *
 * When called with an id parameter, edits the category with that id.
 * Otherwise it creates a new category with default parent from the parent
 * parameter, which may be 0.
 *
 * @package    core_course
 * @copyright  2007 Nicolas Connault
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->libdir.'/coursecatlib.php');
require_once('subscribecourses_form.php');
require_login();

$id = optional_param('id', 0, PARAM_INT);

$url = new moodle_url('/course/editcategory.php');
if ($id) {
    $coursecat = coursecat::get($id, MUST_EXIST, true);
    $category = $coursecat->get_db_record();
    $context = context_coursecat::instance($id);

    $url->param('id', $id);
    $strtitle = new lang_string('editcategorysettings');
    $itemid = 0; // Initialise itemid, as all files in category description has item id 0.
    $title = $strtitle;
    $fullname = $coursecat->get_formatted_name();

} else {
    $parent = optional_param('parent', 0,PARAM_INT);
    $url->param('parent', $parent);
    if ($parent) {
        $DB->record_exists('course_categories', array('id' => $parent), '*', MUST_EXIST);
        $context = context_coursecat::instance($parent);
    } else {
        $context = context_system::instance();
    }
    navigation_node::override_active_url(new moodle_url('/course/editcategory.php', array('parent' => $parent)));

    $category = new stdClass();
    $category->id = 0;
    $category->parent = $parent;
    $strtitle = new lang_string("addnewcategory");
    $itemid = null; // Set this explicitly, so files for parent category should not get loaded in draft area.
    $title = "$SITE->shortname: ".'ASSIGN COURSE';
    $fullname = $SITE->fullname;
}

require_capability('moodle/category:manage', $context);

$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin');
$PAGE->set_title($title);
$PAGE->set_heading($fullname);
$PAGE->requires->js_call_amd('tool_datatables/init', 'init',
                             array('.datatable', array()));
$mform = new subscribecourses_form(null, array(
    'categoryid' => $id,
    'parent' => $category->parent,
    'context' => $context,
    'itemid' => $itemid
));
$mform->set_data(file_prepare_standard_editor   (
    $category,
    'description',
    $mform->get_description_editor_options(),
    $context,
    'coursecat',
    'description',
    $itemid
));

//$manageurl = new moodle_url('/course/management.php');
$manageurl = new moodle_url('/course/editcategory.php');
if ($mform->is_cancelled()) {
    // if ($id) {
    //     $manageurl->param('categoryid', $id);
    // } else if ($parent) {
    //     $manageurl->param('categoryid', $parent);
    // }
    redirect($manageurl);
} else if ($data = $mform->get_data()) {
    $assigned_condition1 = $DB->record_exists('course_assignment',array('cat_from'=>$_POST['from_partner'],'pa_from'=>$_POST['from_partnername'],'tpa_from'=>$_POST['from_tppartnername'],'crsid'=>$_POST['from_course'],'cat_to'=>$_POST['to_partner'],'pa_to'=>$_POST['to_partnername'],'tpa_to'=>$_POST['to_tppartnername']));
    if(!$assigned_condition1){
        $new_assignment = new stdClass();
        $new_assignment->cat_from = $_POST['from_partner'];
        $new_assignment->pa_from = $_POST['from_partnername'];
        $new_assignment->tpa_from = $_POST['from_tppartnername'];
        $new_assignment->crsid = $_POST['from_course'];
        $new_assignment->cat_to = $_POST['to_partner'];
        $new_assignment->pa_to = $_POST['to_partnername'];
        $new_assignment->tpa_to = $_POST['to_tppartnername'];
        $new_assignment->timevalidfrom = $data->assignedfrom;
        $new_assignment->timevalidupto = $data->assignedtill;
        $new_assignment->assignedby = $USER->id;
        $new_assignment->timecreated = time();

        $course_assigned = $DB->insert_record('course_assignment',$new_assignment);
        //print_object($course_assigned);die();
        if($course_assigned){
            $crs_assigned = $DB->get_record('course_assignment',array('id'=>$course_assigned),'crsid,tpa_to');
            $course_extrasettings = new stdClass();
            $course_extrasettings->courseid = $crs_assigned->crsid;
            $course_extrasettings->subscribed = 2;
            $course_extrasettings->creatorid = $crs_assigned->tpa_to;
            $course_extrasettings->instructionled = 0;
            $course_extrasettings->eLabs = 0;
            $course_extrasettings->training_type = 0;
            $course_extrasettings->certification =  0;
            $course_extrasettings_inserted = $DB->insert_record('course_type',$course_extrasettings);

            $roleid = $DB->get_field('role', 'id', array('shortname' =>'trainingpartner'));
            $check_enrolment = check_enrol($crs_assigned->crsid, $crs_assigned->tpa_to, $roleid,'manual');
            if($check_enrolment){
                $manageurl = new moodle_url('/course/subscribe_courses.php');
                redirect($manageurl,'Course assigned successfully');
            }
        }
    }else{
        $manageurl = new moodle_url('/course/subscribe_courses.php');
        redirect($manageurl,'Course already assigned');
    }
}

echo $OUTPUT->header();
//echo $OUTPUT->heading($strtitle);
$mform->display();
echo $OUTPUT->footer();
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#from_pcat").append("<option value='' disabled selected>Select Partnership Category</option>");
        $("#from_pname").append("<option value='' disabled selected>Select Partner</option>");
        $("#from_tpname").append("<option value='' disabled selected>Select Training Partner</option>");
        $("#from_course").append("<option value='' disabled selected>Select Course</option>");
        $("#to_pcat").append("<option value='' disabled selected>Select Partnership Category</option>");
        $("#to_pname").append("<option value='' disabled selected>Select Partner</option>");
        $("#to_tpname").append("<option value='' disabled selected>Select Training Partner</option>");
        
        $("#from_pcat").change(function(){
            var uname = $(this).val();
            $.ajax({
                url: 'partnerjax.php',
                type: 'get',
                data: {utype:uname},
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#from_pname").empty();
                    $("#from_pname").append("<option value='' disabled selected>Select Partner</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['id'];
                        var stream = response[i]['stream'];
                        $("#from_pname").append("<option value='"+id+"'>"+stream+"</option>");
                    }
                }
            });
        });
        $("#to_pcat").change(function(){
            var unameto = $(this).val();
            $.ajax({
                url: 'partnerjax.php',
                type: 'get',
                data: {utypeto:unameto},
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#to_pname").empty();
                    $("#to_pname").append("<option value='' disabled selected>Select Partner</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['id'];
                        var stream = response[i]['stream'];
                        $("#to_pname").append("<option value='"+id+"'>"+stream+"</option>");
                    }
                }
            });
        });
        $("#from_pname").change(function(){
            var pid = $(this).val();
            $.ajax({
                url: 'partnerjax.php',
                type: 'get',
                data: {pid:pid},
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#from_tpname").empty();
                    $("#from_tpname").append("<option value='' disabled selected>Select Training Partner</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['id'];
                        var stream = response[i]['stream'];
                        $("#from_tpname").append("<option value='"+id+"'>"+stream+"</option>");
                    }
                }
            });
        });
        $("#to_pname").change(function(){
            var pidto = $(this).val();
            $.ajax({
                url: 'partnerjax.php',
                type: 'get',
                data: {pidto:pidto},
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#to_tpname").empty();
                    $("#to_tpname").append("<option value='' disabled selected>Select Training Partner</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['id'];
                        var stream = response[i]['stream'];
                        $("#to_tpname").append("<option value='"+id+"'>"+stream+"</option>");
                    }
                }
            });
        });
        $("#from_tpname").change(function(){
            var tpid = $(this).val();
            $.ajax({
                url: 'partnerjax.php',
                type: 'get',
                data: {tpid:tpid},
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#from_course").empty();
                    $("#from_course").append("<option value='' disabled selected>Select Course</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['id'];
                        var stream = response[i]['stream'];
                        $("#from_course").append("<option value='"+id+"'>"+stream+"</option>");
                    }
                }
            });
        });


        /*$("#id_streams").change(function(){
            var crsid = $(this).val();
            $("#stream_data").val(crsid);
        });

        $('#id_public').click(function () {
            $('#academic_batch').hide('fast');
            $('#nonacademic_batch').show('fast');
            $('#subscribed_batch').hide('fast');
          
            document.getElementById("academic_batch").disabled = true;
            document.getElementById("nonacademic_batch").disabled = false;
            document.getElementById("subscribed_batch").disabled = true;
        });

        $('#id_private').click(function () {
            $('#academic_batch').show('fast');
            $('#nonacademic_batch').hide('fast');
            $('#subscribed_batch').hide('fast');
           
            document.getElementById("academic_batch").disabled = false;
            document.getElementById("nonacademic_batch").disabled = true;
            document.getElementById("subscribed_batch").disabled = true;

        });
            
        $('#id_subscribed').click(function () {
            $('#subscribed_batch').show('fast');
            $('#nonacademic_batch').hide('fast');
            $('#academic_batch').hide('fast');
           
            document.getElementById("subscribed_batch").disabled = false;
            document.getElementById("nonacademic_batch").disabled = true;
            document.getElementById("academic_batch").disabled = true;

        });
        function addBatches() {
            var program = document.getElementById("id_programs").value;
            var stream = document.getElementById("id_streams").value;
            var sem_year = document.getElementById("id_syears").value;
            var semester = document.getElementById("id_semesters").value;
            var batchname = document.getElementById("id_ac_batchname").value;
            var batchcode = document.getElementById("id_ac_batchcode").value;
            var btype = document.getElementById("id_btype").value;
            // Returns successful data submission message when the entered information is stored in database.
            var dataString = 'program1=' + program + '&stream1=' + stream + '&sem_year1=' + sem_year + '&semester1=' + semester + '&batchname1=' + batchname + '&batchcode1=' + batchcode + '&btype1=' + btype;
            // AJAX code to submit form.
            $.ajax({
                type: "POST",
                url: "add_batch.php",
                data: dataString,
                cache: false,
                success: function(html) {
                alert('success');
                }
            });
            return false;
        }*/
                    
    });
</script>