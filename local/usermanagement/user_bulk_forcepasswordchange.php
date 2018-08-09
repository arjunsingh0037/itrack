<?php
/**
* script for bulk user force password change
*/

require_once('../../config.php');
require_once('lib.php');
require_once($CFG->libdir.'/adminlib.php');

$confirm = optional_param('confirm', 0, PARAM_BOOL);
$cohort = optional_param('cohort', 0, PARAM_BOOL);

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
    //redirect($return);
}

echo $OUTPUT->header();

if ($confirm and confirm_sesskey()) {
    // only force password change if user may actually change the password
    $authsavailable = get_enabled_auth_plugins();
    $changeable = array();

    foreach($authsavailable as $authplugin) {
        if (!$auth = get_auth_plugin($authplugin)) {
            continue;
        }
       
        if ($auth->is_internal() and $auth->can_change_password()) {
            $changeable[$authplugin] = true;
        }
    }

    $parts = array_chunk($SESSION->bulk_users, 300);
    foreach ($parts as $users) {
        list($in, $params) = $DB->get_in_or_equal($users);
        $rs = $DB->get_recordset_select('user', "id $in", $params);
        foreach ($rs as $user) {
            if (!empty($changeable[$user->auth])) {
                set_user_preference('auth_forcepasswordchange', 1, $user->id);
                unset($SESSION->bulk_users[$user->id]);
            } else {
                echo $OUTPUT->notification(get_string('forcepasswordchangenot', '', fullname($user, true)));
            }
        }
        $rs->close();
    }
    echo $OUTPUT->notification(get_string('changessaved'), 'notifysuccess');
    echo $OUTPUT->continue_button($return);

} else {
    list($in, $params) = $DB->get_in_or_equal($SESSION->bulk_users);
    $userlist = $DB->get_records_select_menu('user', "id $in", $params, 'fullname', 'id,'.$DB->sql_fullname().' AS fullname', 0, MAX_BULK_USERS);
    $usernames = implode(', ', $userlist);
    if (count($SESSION->bulk_users) > MAX_BULK_USERS) {
        $usernames .= ', ...';
    }
    echo $OUTPUT->heading(get_string('confirmation', 'admin'));
    $formcontinue = new single_button(new moodle_url('/local/usermanagement/user_bulk_forcepasswordchange.php', array('confirm' => 1)), get_string('yes'));
    $formcancel = new single_button(new moodle_url('/local/usermanagement/user_bulk.php'), get_string('no'), 'get');
    echo $OUTPUT->confirm(get_string('forcepasswordchangecheckfull', '', $usernames), $formcontinue, $formcancel);
}

echo $OUTPUT->footer();
