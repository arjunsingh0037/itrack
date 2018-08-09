<?php

// This file is part of MoodleofIndia - http://moodleofindia.com/
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
 * Note class is build for Manage Notes (Create/Update/Delete)
 * @desc Note class have one parameterized constructor to receive global 
 *       resources.
 * 
 * Private usermanagement functions
 * @author    Nihar Das <nihar@elearn10.com>
 * @package    local_usermanagement
 * @copyright  2016 lms of india
 * @license    http://www.lmsofindia.com GNU GPL v3 or later
*/


function local_usermanagement_extend_navigation(global_navigation $navigation) {
    global $CFG;
    if (isloggedin() && is_siteadmin()) {
        $node = $navigation->add(get_string('plugin', 'local_usermanagement'));        
        $node->add(get_string('userlevel', 'local_usermanagement'),new moodle_url($CFG->wwwroot.'/local/usermanagement/index.php'));
        $node->add(get_string('cohortlevel', 'local_usermanagement'),new moodle_url($CFG->wwwroot.'/local/usermanagement/index1.php'));
    }    
}

require_once($CFG->dirroot.'/user/filters/lib.php');

if (!defined('MAX_BULK_USERS')) {
    define('MAX_BULK_USERS', 2000);
}

function add_all_selection($ufiltering) {
    global $SESSION, $DB, $CFG;

    list($sqlwhere, $params) = $ufiltering->get_sql_filter("id<>:exguest AND deleted <> 1", array('exguest'=>$CFG->siteguest));

    $rs = $DB->get_recordset_select('user', $sqlwhere, $params, 'fullname', 'id,'.$DB->sql_fullname().' AS fullname');
    foreach ($rs as $user) {
        if (!isset($SESSION->bulk_users[$user->id])) {
            $SESSION->bulk_users[$user->id] = $user->id;
        }
    }
    $rs->close();
}

function get_data_selection($ufiltering) {
    global $SESSION, $DB, $CFG;

    // get the SQL filter
    list($sqlwhere, $params) = $ufiltering->get_sql_filter("id<>:exguest AND deleted <> 1", array('exguest'=>$CFG->siteguest));

    $total  = $DB->count_records_select('user', "id<>:exguest AND deleted <> 1", array('exguest'=>$CFG->siteguest));
    $acount = $DB->count_records_select('user', $sqlwhere, $params);
    $scount = count($SESSION->bulk_users);

    $userlist = array('acount'=>$acount, 'scount'=>$scount, 'ausers'=>false, 'susers'=>false, 'total'=>$total);
    $userlist['ausers'] = $DB->get_records_select_menu('user', $sqlwhere, $params, 'fullname', 'id,'.$DB->sql_fullname().' AS fullname', 0, MAX_BULK_USERS);

    if ($scount) {
        if ($scount < MAX_BULK_USERS) {
            $bulkusers = $SESSION->bulk_users;
        } else {
            $bulkusers = array_slice($SESSION->bulk_users, 0, MAX_BULK_USERS, true);
        }
        list($in, $inparams) = $DB->get_in_or_equal($bulkusers);
        $userlist['susers'] = $DB->get_records_select_menu('user', "id $in", $inparams, 'fullname', 'id,'.$DB->sql_fullname().' AS fullname');
    }

    return $userlist;
}

/**
     * Send an e-mail due to a user being suspended
     *
     * @param \stdClass $user
     * @param bool $automated true if a result of automated suspension, false if suspending
     *              is a result of a manual action
     * @return void
     */
    function process_user_suspended_email($user) {
        global $CFG;
        //require_once($CFG->dirroot.'/lib/moodlelib.php');
        // Prepare and send email.
        $from = \core_user::get_support_user();
        $a = new \stdClass();
        $a->name = fullname($user);
        $a->contact = $from->email;
        $a->signature = fullname($from);
        $subject = get_string('email:user:suspend:subject', 'local_usermanagement', $a->name);

        $messagehtml = get_string_manager()->get_string('email:user:suspend:manual:body', 'local_usermanagement', $a);
        $messagetext = format_text_email($messagehtml, FORMAT_HTML);
        $sentuser = email_to_user($user, $from, $subject, $messagetext, $messagehtml);

        if ($sentuser) {
            return true;
        }
        return false;
}

/**
     * Send an e-mail due to a user being suspended
     *
     * @param \stdClass $user
     * @param bool $automated true if a result of automated suspension, false if suspending
     *              is a result of a manual action
     * @return void
     */
    function informed_admin_about_suspended_user(array $user) {

        // Prepare and send email.
        $from = \core_user::get_support_user();
        $userlist = rtrim(implode(', ', $user), ', ');
        $a = new \stdClass();
        $a->userlist = $userlist;
        $subject = get_string('email:admin:suspend:subject', 'local_usermanagement');

        $messagehtml = get_string_manager()->get_string('email:adminuser:suspend:manual:body', 'local_usermanagement', $a);
        $messagetext = format_text_email($messagehtml, FORMAT_HTML);

        $sentuser = email_to_user($from, $from, $subject, $messagetext, $messagehtml);

        if ($sentuser) {
            return true;
        }
        return false;
}


    /**
     * Performs the actual user suspension by updating the users table
     *
     * @param \stdClass $user
     * @param bool $automated true if a result of automated suspension, false if suspending
     *              is a result of a manual action
     */
    function do_suspend_user($user) {
        global $USER, $CFG;
        require_once($CFG->dirroot.'/lib/moodlelib.php');
        require_once($CFG->dirroot . '/user/lib.php');
        
        // Piece of code taken from /admin/user.php so we dance just like moodle does.
        if (!is_siteadmin($user) and $USER->id != $user->id and $user->suspended != 1) {
            $user->suspended = 1;
            // Force logout.
            \core\session\manager::kill_user_sessions($user->id);
            user_update_user($user, false, true);
            $user->suspended = 0;
            $emailsent = process_user_suspended_email($user);
            
            return true;
        }
        return false;
    }
    
    /**
     * Send an e-mail due to a user being unsuspended
     *
     * @param \stdClass $user
     * @return void
     */
    function process_user_unsuspended_email($user) {
        
        // Prepare and send email.
        $from = \core_user::get_support_user();
        $a = new \stdClass();
        $a->name = fullname($user);
        $a->contact = $from->email;
        $a->signature = fullname($from);
        $subject = get_string_manager()->get_string('email:user:unsuspend:subject',
                'local_usermanagement', $a);
        $messagehtml = get_string_manager()->get_string('email:user:unsuspend:manual:body',
                'local_usermanagement', $a);
        $messagetext = format_text_email($messagehtml, FORMAT_HTML);
        return email_to_user($user, $from, $subject, $messagetext, $messagehtml);
    }
    
    /**
     * Send an e-mail due to a user being suspended
     *
     * @param \stdClass $user
     * @param bool $automated true if a result of automated suspension, false if suspending
     *              is a result of a manual action
     * @return void
     */
    function informed_admin_about_unsuspended_user(array $user) {

        // Prepare and send email.
        $from = \core_user::get_support_user();
        $userlist = rtrim(implode(', ', $user), ', ');
        $a = new \stdClass();
        $a->userlist = $userlist;
        $subject = get_string('email:admin:unsuspend:subject', 'local_usermanagement');

        $messagehtml = get_string_manager()->get_string('email:adminuser:unsuspend:manual:body', 'local_usermanagement', $a);
        $messagetext = format_text_email($messagehtml, FORMAT_HTML);

        $sentuser = email_to_user($from, $from, $subject, $messagetext, $messagehtml);

        if ($sentuser) {
            return true;
        }
        return false;
}
    
    /**
     * Performs the actual user unsuspension by updating the users table
     *
     * @param \stdClass $user
     */
    function do_unsuspend_user($user) {
        global $CFG, $DB;
        require_once($CFG->dirroot.'/lib/moodlelib.php');
        require_once($CFG->dirroot . '/user/lib.php');
        // Piece of code taken from /admin/user.php so we dance just like moodle does.
        if ($user = $DB->get_record('user', array('id' => $user->id,
                'mnethostid' => $CFG->mnet_localhost_id, 'deleted' => 0, 'suspended' => 1))) {
            if ($user->suspended != 0) {
                $user->suspended = 0;
                user_update_user($user, false, true);
                // Process email id applicable.
                $emailsent = process_user_unsuspended_email($user);
                return true;
            }
        }
        return false;
    }

        
/**
 * Sets specified user's password and send the new password to the user via email.
 *
 * @param stdClass $user A {@link $USER} object
 * @param bool $fasthash If true, use a low cost factor when generating the hash for speed.
 * @return bool|string Returns "true" if mail was sent OK and "false" if there was an error
 */
function autogenerate_password_and_mail($user, $fasthash = false) {
    global $CFG, $DB;

    // We try to send the mail in language the user understands,
    // unfortunately the filter_string() does not support alternative langs yet
    // so multilang will not work properly for site->fullname.
    $lang = empty($user->lang) ? $CFG->lang : $user->lang;

    $site  = get_site();

    $supportuser = core_user::get_support_user();

    $newpassword = generate_password();

    update_internal_user_password($user, $newpassword, $fasthash);

    $a = new stdClass();
    $a->firstname   = fullname($user, true);
    $a->sitename    = format_string($site->fullname);
    $a->username    = $user->username;
    $a->newpassword = $newpassword;
    $a->link        = $CFG->wwwroot .'/login/';
    $a->signoff     = generate_email_signoff();

    $message = (string)new lang_string('autogeneratepasswordtext', '', $a, $lang);

    $subject = format_string($site->fullname) .': '. (string)new lang_string('autogeneratepasswordsub', '', $a, $lang);

    // Directly email rather than using the messaging system to ensure its not routed to a popup or jabber.
    return email_to_user($user, $supportuser, $subject, $message);

}
function local_usermanagement_cron() {
    global $CFG, $DB;
    $gettobesuspended = $DB->get_records('usermanagement',array('mailsentstatus' => 0));
    foreach ($gettobesuspended as $users) {
        $listid = $users->suspendeduserids;
        $enddate = $users->enddate;
        $afterdays1 = $users->afterdays1;
        $afterdays2 = $users->afterdays2;
        $selectedcriteria = null;
        $base = null;
        $userobj = $DB->get_record('user', array('id' => $listid));
        $suspendedlist[] = $userobj->username;
        if (isset($enddate)) {
            $selectedcriteria = $enddate;
            $base = 'notreq';
            $nextdaytimestamp = strtotime('+1 day', $enddate);
            if (time() > $enddate && time() < $nextdaytimestamp) {
                mtrace('suspending users through usermanagement cron');
                do_suspend_user($userobj);
                $record = new stdClass();
                $record->id  = $users->id;
                $record->mailsentstatus  = 1;
                $DB->update_record('usermanagement', $record);
            }
        } else if (isset($afterdays1)) {
            $numday = '+' . $afterdays1 . ' day';
            $selectedcriteria = strtotime($numday, $users->timecreated);
            $nextdaytimestamp = strtotime('+1 day', $selectedcriteria);
            $base = $users->timecreated;
            if (time() >= $selectedcriteria && time() <= $nextdaytimestamp) {
                mtrace('suspending users through usermanagement cron');
                do_suspend_user($userobj);
                $record = new stdClass();
                $record->id  = $users->id;
                $record->mailsentstatus  = 1;
                $DB->update_record('usermanagement', $record);
            }
        } else if ($afterdays2) {
            $numday = '+' . $afterdays2 . ' day';
            $selectedcriteria = strtotime($numday, $userobj->timecreated);
            $nextdaytimestamp = strtotime('+1 day', $selectedcriteria);
            $base = $userobj->timecreated;
            if (time() >= $selectedcriteria && time() <= $nextdaytimestamp) {
                mtrace('suspending users through usermanagement cron');
                do_suspend_user($userobj);
                $record = new stdClass();
                $record->id  = $users->id;
                $record->mailsentstatus  = 1;
                $DB->update_record('usermanagement', $record);
            }
        }
    }

    informed_admin_about_suspended_user($suspendedlist);
}
