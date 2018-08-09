<?php
/**
 * script for bulk user auto generated password operations
 */
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$confirm = optional_param('confirm', 0, PARAM_BOOL);
$newpassword = optional_param('setnewpassword', 0, PARAM_RAW);
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
        $user->newpassword = $newpassword;
        if (!empty($user->newpassword)) {
            $authplugin = get_auth_plugin($user->auth);
            if ($authplugin->can_change_password()) {
                $status = 1;
                if (!$authplugin->user_update_password($user, $user->newpassword)) {
                    print_error('cannotupdatepasswordonextauth', '', '', $user->auth);
                }
                unset_user_preference('create_password', $user); // Prevent cron from generating the password.

                if (!empty($CFG->passwordchangelogout)) {
                    // We can use SID of other user safely here because they are unique,
                    // the problem here is we do not want to logout admin here when changing own password.
                    \core\session\manager::kill_user_sessions($user->id, session_id());
                }
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
} else {
    list($in, $params) = $DB->get_in_or_equal($SESSION->bulk_users);
    $userlist = $DB->get_records_select_menu('user', "id $in", $params, 'fullname', 'id,' . $DB->sql_fullname() . ' AS fullname');
    $usernames = implode(', ', $userlist);

    echo $OUTPUT->heading(get_string('confirmation', 'admin'));
    //$cc= '<form>'.html_writer::empty_tag('input', array('type' => 'text')).'</form>';
    $formcontinue = new single_button(new moodle_url('user_setpassword.php', array('confirm' => 1)), get_string('yes'));
    $formcancel = new single_button(new moodle_url('user_bulk.php'), get_string('no'), 'get');


    echo $OUTPUT->confirm(get_string('setcommonpassword', 'local_usermanagement', $usernames), $formcontinue, $formcancel);
}

echo $OUTPUT->footer();
?>
<script>
    $("div.singlebutton form[action='user_setpassword.php']").prepend("<input type='text' name='setnewpassword'>");
</script>

