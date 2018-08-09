<?php
/**
* script for bulk user auto generated password operations
*/

require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/lib/moodlelib.php');
require_once($CFG->dirroot.'/local/usermanagement/lib.php');
require_once($CFG->dirroot.'/local/usermanagement/user_suspendcriteria_form.php');
global $PAGE;
$confirm = optional_param('confirm', 0, PARAM_BOOL);
$cohort = optional_param('cohort', 0, PARAM_BOOL);
$enddate = optional_param('enddate', 0, PARAM_RAW);

if(!is_array($enddate) && $enddate) {
$enddate_explode = explode(',', $enddate);
$endatestamp = make_timestamp($enddate_explode[2], $enddate_explode[1], $enddate_explode[0]);
}
$afterdays1 = optional_param('afterdays1', 0, PARAM_RAW);
$afterdays2 = optional_param('afterdays2', 0, PARAM_RAW);

require_login();
admin_externalpage_setup('userbulk');
require_capability('moodle/user:update', context_system::instance());

$return = $CFG->wwwroot.'/local/usermanagement/user_bulk.php';
$return1 = $CFG->wwwroot . '/local/usermanagement/index1.php';
/* for cohort functionality */

if($cohort) {
if (!empty($SESSION->bulk_cohort)) {
    unset($SESSION->bulk_users);
    foreach($SESSION->bulk_cohort as $cohortid) {

        $bulkusers = $DB->get_records('cohort_members', array('cohortid' => $cohortid));
        foreach($bulkusers as $bulkuser) {
        $cohortmember[] = $bulkuser->userid;
        }
    }
    if($cohortmember) {
        $SESSION->bulk_users = $cohortmember;
    }
  
} else {
    redirect($return1);
}
}

if (empty($SESSION->bulk_users)) {
    redirect($return);
}
$PAGE->requires->jquery();
$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/local/usermanagement/css/style.css'));
echo $OUTPUT->header();

//TODO: add support for large number of users

if ($confirm and confirm_sesskey()) {
    $notifications = '';
    list($in, $params) = $DB->get_in_or_equal($SESSION->bulk_users);
    $rs = $DB->get_recordset_select('user', "id $in", $params, '', 'id, auth, email, username, firstname, lastname, suspended, lang');
    $status = 0;
     foreach ($rs as $user) {
        
        if(!$DB->get_record('usermanagement', array('suspendeduserids' => $user->id))) {
        $record = new stdClass();
        $record->suspendeduserids = $user->id;
        $record->enddate = $endatestamp;
        $record->afterdays1 = $afterdays1;
        $record->afterdays2 = $afterdays2;
        $record->timecreated = time();
        $record->timemodified = time() + 5;
        $record->mailsentstatus = 0;
        $lastinsetrec = $DB->insert_record('usermanagement', $record);
        if ($lastinsetrec) {
            $status = 1;
        }
    }
    }
    $rs->close();
    echo $OUTPUT->box_start('generalbox', 'notice');
    if ($status) { //TODO--need to give the notification properly
        echo $OUTPUT->notification(get_string('changessaved'), 'notifysuccess');
    } else {
        echo $OUTPUT->notification();
    }
    $continue = new single_button(new moodle_url($return), get_string('continue'), 'post');
    echo $OUTPUT->render($continue);
    echo $OUTPUT->box_end();
} 

$suspendcriteriaform = new user_suspendcriteria_form();

if ($suspendcriteriaform->is_cancelled()) {
    redirect($return);

} else if ($formdata = $suspendcriteriaform->get_data()) {
    
    list($in, $params) = $DB->get_in_or_equal($SESSION->bulk_users);
    $userlist = $DB->get_records_select_menu('user', "id $in", $params, 'fullname', 'id,'.$DB->sql_fullname().' AS fullname');
    $usernames = implode(', ', $userlist);
   
    echo $OUTPUT->heading(get_string('confirmation', 'admin'));
    //$cc= '<form>'.html_writer::empty_tag('input', array('type' => 'text')).'</form>';
    $formcontinue = new single_button(new moodle_url('user_suspendcriteria.php', array('confirm' => 1, 'enddate' => implode(',', $enddate), 'afterdays1' => $afterdays1, 'afterdays2' => $afterdays2)), get_string('yes'));
    $formcancel = new single_button(new moodle_url('user_bulk.php'), get_string('no'), 'get');
    
    echo $OUTPUT->confirm(get_string('suspenduser', 'local_usermanagement', $usernames), $formcontinue, $formcancel);
    echo $OUTPUT->footer();
    die;   
}
if(!$confirm) {
$suspendcriteriaform->display();
}
echo $OUTPUT->footer();
?>