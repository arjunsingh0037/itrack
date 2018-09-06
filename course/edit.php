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
 * Edit course settings
 *
 * @package    core_course
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../config.php');
require_once('lib.php');
require_once('edit_form.php');

$id = optional_param('id', 0, PARAM_INT); // Course id.
$categoryid = optional_param('category', 0, PARAM_INT);
//$cid = $_POST['category']; // Course category - can be changed in edit form.
$returnto = optional_param('returnto', 0, PARAM_ALPHANUM); // Generic navigation return page switch.
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL); // A return URL. returnto must also be set to 'url'.

if ($returnto === 'url' && confirm_sesskey() && $returnurl) {
    // If returnto is 'url' then $returnurl may be used as the destination to return to after saving or cancelling.
    // Sesskey must be specified, and would be set by the form anyway.
    $returnurl = new moodle_url($returnurl);
} else {
    if (!empty($id)) {
        $returnurl = new moodle_url($CFG->wwwroot . '/course/view.php', array('id' => $id));
    } else {
        $returnurl = new moodle_url($CFG->wwwroot . '/course/');
    }
    if ($returnto !== 0) {
        switch ($returnto) {
            case 'category':
                $returnurl = new moodle_url($CFG->wwwroot . '/course/index.php', array('categoryid' => $categoryid));
                break;
            case 'catmanage':
                $returnurl = new moodle_url($CFG->wwwroot . '/course/management.php', array('categoryid' => $categoryid));
                break;
            case 'topcatmanage':
                $returnurl = new moodle_url($CFG->wwwroot . '/course/management.php');
                break;
            case 'topcat':
                $returnurl = new moodle_url($CFG->wwwroot . '/course/');
                break;
            case 'pending':
                $returnurl = new moodle_url($CFG->wwwroot . '/course/pending.php');
                break;
        }
    }
}

$PAGE->set_pagelayout('admin');
if ($id) {
    $pageparams = array('id' => $id);
} else {
    $pageparams = array('category' => $categoryid);
}
if ($returnto !== 0) {
    $pageparams['returnto'] = $returnto;
    if ($returnto === 'url' && $returnurl) {
        $pageparams['returnurl'] = $returnurl;
    }
}
$PAGE->set_url('/course/edit.php', $pageparams);

// Basic access control checks.
if ($id) {
    // Editing course.
    if ($id == SITEID){
        // Don't allow editing of  'site course' using this from.
        print_error('cannoteditsiteform');
    }
    // Login to the course and retrieve also all fields defined by course format.
    $course = get_course($id);
    require_login($course);
    $course = course_get_format($course)->get_course();

    $category = $DB->get_record('course_categories', array('id'=>$course->category), '*', MUST_EXIST);
    $coursecontext = context_course::instance($course->id);
    require_capability('moodle/course:update', $coursecontext);
    //By Arjun -Permission Access
    $currentuser = $USER->id;
    $user = $DB->record_exists('trainingpartners', array('userid' => $currentuser));
    if (!$user) {
        echo $OUTPUT->header();
        redirect($CFG->wwwroot.'/my','You do not have access to this page.',1,'error');
        die; 
    }
} else if ($categoryid) {
    $course = null;
    require_login();
    $catid = '';
    $user_cats = array();
    $ac_categories = array();
    $category = $DB->get_record('course_categories', array('id'=>$_POST['category']), '*', MUST_EXIST);
    $catcontext = context_coursecat::instance($category->id);
    require_capability('moodle/course:create', $catcontext);
    $PAGE->set_context($catcontext); 
    //By Arjun -Permission Access
    $currentuser = $USER->id;
    $user = $DB->record_exists('trainingpartners', array('userid' => $currentuser));
    if (!$user) {
        echo $OUTPUT->header();
        redirect($CFG->wwwroot.'/my','You do not have access to this page.',1,'error');
        die; 
    } 
} else {
    $course = null;
    require_login();
    $catcontext = context_system::instance();
    require_capability('moodle/course:create', $catcontext);
    $PAGE->set_context($catcontext);
    //By Arjun -Permission Access
    $currentuser = $USER->id;
    $user = $DB->record_exists('trainingpartners', array('userid' => $currentuser));
    if (!$user) {
        echo $OUTPUT->header();
        redirect($CFG->wwwroot.'/my','You do not have access to this page.',1,'error');
        die; 
    }
}
// Prepare course and the editor.
$editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 'maxbytes'=>$CFG->maxbytes, 'trusttext'=>false, 'noclean'=>true);
$overviewfilesoptions = course_overviewfiles_options($course);
if (!empty($course)) {
    // Add context for editor.
    $editoroptions['context'] = $coursecontext;
    $editoroptions['subdirs'] = file_area_contains_subdirs($coursecontext, 'course', 'summary', 0);
    $course = file_prepare_standard_editor($course, 'summary', $editoroptions, $coursecontext, 'course', 'summary', 0);
    if ($overviewfilesoptions) {
        file_prepare_standard_filemanager($course, 'overviewfiles', $overviewfilesoptions, $coursecontext, 'course', 'overviewfiles', 0);
    }

    // Inject current aliases.
    $aliases = $DB->get_records('role_names', array('contextid'=>$coursecontext->id));
    foreach($aliases as $alias) {
        $course->{'role_'.$alias->roleid} = $alias->name;
    }

    // Populate course tags.
    $course->tags = core_tag_tag::get_item_tags_array('core', 'course', $course->id);

} else {
    // Editor should respect category context if course context is not set.
    $editoroptions['context'] = $catcontext;
    $editoroptions['subdirs'] = 0;
    $course = file_prepare_standard_editor($course, 'summary', $editoroptions, null, 'course', 'summary', null);
    if ($overviewfilesoptions) {
        file_prepare_standard_filemanager($course, 'overviewfiles', $overviewfilesoptions, null, 'course', 'overviewfiles', 0);
    }
}

// First create the form.
$args = array(
    'course' => $course,
    'editoroptions' => $editoroptions,
    'returnto' => $returnto,
    'returnurl' => $returnurl
);
$editform = new course_edit_form(null, $args);
if ($editform->is_cancelled()) {
    // The form has been cancelled, take them back to what ever the return to is.
    redirect($returnurl);
} else if ($data = $editform->get_data()) {
    // Process data if submitted.
    // print_object($data);
    // print_object($_POST);die();
    if (empty($course->id)) {
        // In creating the course.
        $course = create_course($data, $editoroptions);
        //arjun-adding course extra settings as in iTrack
        if($course){
            //  print_object($_POST);
            $course_extrasettings = new stdClass();
            $course_extrasettings->courseid = $course->id;
            $course_extrasettings->subscribed = $data->subscribed;
            $course_extrasettings->program = $_POST['ac_program'];
            $course_extrasettings->stream = $_POST['ac_stream'];
            $course_extrasettings->creatorid = $USER->id;
            $course_extrasettings->instructionled = $data->instructionled;
            $course_extrasettings->eLabs = $data->haselabs;
            $course_extrasettings->training_type = $data->trainingtype;
            $course_extrasettings->certification =  $data->certification;
            $course_extrasettings_inserted = $DB->insert_record('course_type',$course_extrasettings);
        }
        

        // Get the context of the newly created course.
        $context = context_course::instance($course->id, MUST_EXIST);

        if (!empty($CFG->creatornewroleid) and !is_viewing($context, NULL, 'moodle/role:assign') and !is_enrolled($context, NULL, 'moodle/role:assign')) {
            // Deal with course creators - enrol them internally with default role.
            enrol_try_internal_enrol($course->id, $USER->id, $CFG->creatornewroleid);
        }

        // The URL to take them to if they chose save and display.
        $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));

        // If they choose to save and display, and they are not enrolled take them to the enrolments page instead.
        if (!is_enrolled($context) && isset($data->saveanddisplay)) {
            // Redirect to manual enrolment page if possible.
            $instances = enrol_get_instances($course->id, true);
            foreach($instances as $instance) {
                if ($plugin = enrol_get_plugin($instance->enrol)) {
                    if ($plugin->get_manual_enrol_link($instance)) {
                        // We know that the ajax enrol UI will have an option to enrol.
                        //$courseurl = new moodle_url('/user/index.php', array('id' => $course->id, 'newcourse' => 1));
                        $courseurl = new moodle_url('/course/courselist.php');
                        break;
                    }
                }
            }
        }
    } else {
        // Save any changes to the files used in the editor.
        update_course($data, $editoroptions);
        // Set the URL to take them too if they choose save and display.
        //$courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
        $courseurl = new moodle_url('/course/courselist.php', array());
    }

    if (isset($data->saveanddisplay)) {
        // Redirect user to newly created/updated course.
        redirect($courseurl);
    } else {
        // Save and return. Take them back to wherever.
        redirect($returnurl);
    }
}

// Print the form.

$site = get_site();

$streditcoursesettings = get_string("editcoursesettings");
$straddnewcourse = get_string("addnewcourse");
$stradministration = get_string("administration");
$strcategories = get_string("categories");

if (!empty($course->id)) {
    // Navigation note: The user is editing a course, the course will exist within the navigation and settings.
    // The navigation will automatically find the Edit settings page under course navigation.
    $pagedesc = $streditcoursesettings;
    $title = $streditcoursesettings;
    $fullname = $course->fullname;
} else {
    // The user is adding a course, this page isn't presented in the site navigation/admin.
    // Adding a new course is part of course category management territory.
    // We'd prefer to use the management interface URL without args.
    $managementurl = new moodle_url('/course/management.php');
    // These are the caps required in order to see the management interface.
    $managementcaps = array('moodle/category:manage', 'moodle/course:create');
    if ($categoryid && !has_any_capability($managementcaps, context_system::instance())) {
        // If the user doesn't have either manage caps then they can only manage within the given category.
        $managementurl->param('categoryid', $categoryid);
    }
    // Because the course category management interfaces are buried in the admin tree and that is loaded by ajax
    // we need to manually tell the navigation we need it loaded. The second arg does this.
    navigation_node::override_active_url($managementurl, true);

    $pagedesc = $straddnewcourse;
    $title = "$site->shortname: $straddnewcourse";
    $fullname = $site->fullname;
    $PAGE->navbar->add($pagedesc);
}

$PAGE->set_title($title);
$PAGE->set_heading($fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading($pagedesc);

$editform->display();

echo $OUTPUT->footer();
?>

<script type="text/javascript">
    $(document).ready(function () {
        
        $('#id_public').click(function () {
            $('#academic_course').hide('fast');
            $('#subscribed_course').show('fast');
          
            document.getElementById("academic_course").disabled = true;
            document.getElementById("subscribed_batch").disabled = false;
        });

        $('#id_private').click(function () {
            $('#academic_course').show('fast');
            $('#subscribed_course').hide('fast');
           
            document.getElementById("academic_course").disabled = false;
            document.getElementById("subscribed_course").disabled = true;

        }); 

        $("#id_programs").append("<option value='' disabled selected>Select Program</option>");
        $("#id_streams").append("<option value='' disabled selected>Select Stream</option>");
        $("#id_programs").change(function(){
            var pid = $(this).val();
            $.ajax({
                url: 'courseajax.php',
                type: 'get',
                data: {program:pid},
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#id_streams").empty();
                    $("#id_streams").append("<option value='' disabled selected>Select Stream</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['id'];
                        var stream = response[i]['stream'];
                        $("#id_streams").append("<option value='"+id+"'>"+stream+"</option>");
                    }
                }
            });
        });                   
    });
</script>
