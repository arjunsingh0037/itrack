<?php

/**
 * script for bulk user auto generated password operations
 */
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$confirm = optional_param('confirm', 0, PARAM_BOOL);
$cohort = optional_param('cohort', 0, PARAM_BOOL);

require_login();
admin_externalpage_setup('userbulk');
require_capability('moodle/user:update', context_system::instance());

$return = $CFG->wwwroot . '/local/usermanagement/user_bulk.php';
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

echo $OUTPUT->header();

//TODO: add support for large number of users

if ($confirm and confirm_sesskey()) {
    $notifications = '';
    list($in, $params) = $DB->get_in_or_equal($SESSION->bulk_users);
    $rs = $DB->get_recordset_select('user', "id $in", $params, '', 'id, auth, email, username');
    foreach ($rs as $user) {

       $status = 'set';
        $prefcheck = $DB->get_record_sql("SELECT * FROM {user_preferences} WHERE userid = $user->id AND name = 'create_password' AND value = 1");
        if (!$prefcheck) {
            set_user_preference('create_password', 1, $user);
            $sentstatus = autogenerate_password_and_mail($user);
           // var_dump($sentstatus);
            if ($sentstatus) {
                $status = 1;
            } else {
                $status = 0;
            }
            unset_user_preference('create_password', $user);
            set_user_preference('auth_forcepasswordchange', 1, $user);
        }
    }
    
    $rs->close();
    unset($SESSION->bulk_cohort);
    echo $OUTPUT->box_start('generalbox', 'notice');
    if ($status == 1) { //TODO--need to put status message
        echo $OUTPUT->notification(get_string('changessaved'), 'notifysuccess');
    } else if($status == 0){
        echo $OUTPUT->notify_problem('Password not auto generated and message not sent');
    } else {
         echo $OUTPUT->notify_problem('password already requested');
    }
    $continue = new single_button(new moodle_url($return), get_string('continue'), 'post');
    echo $OUTPUT->render($continue);
    echo $OUTPUT->box_end();
} else {
    list($in, $params) = $DB->get_in_or_equal($SESSION->bulk_users);
    $userlist = $DB->get_records_select_menu('user', "id $in", $params, 'fullname', 'id,' . $DB->sql_fullname() . ' AS fullname');
    $usernames = implode(', ', $userlist);
    echo $OUTPUT->heading(get_string('confirmation', 'admin'));
    $formcontinue = new single_button(new moodle_url('user_autogeneratepassword.php', array('confirm' => 1)), get_string('yes'));
    $formcancel = new single_button(new moodle_url('user_bulk.php'), get_string('no'), 'get');
    echo $OUTPUT->confirm(get_string('autogeneratepasswordconfirm', 'local_usermanagement', $usernames), $formcontinue, $formcancel);
}

echo $OUTPUT->footer();
