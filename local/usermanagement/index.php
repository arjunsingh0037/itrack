<?php

require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/local/usermanagement/lib.php');
require_once($CFG->dirroot.'/local/usermanagement/user_bulk_forms.php');
require_once($CFG->dirroot.'/local/usermanagement/allcohortform.php');
require_once($CFG->dirroot.'/local/usermanagement/changepasswordcapability_form.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pagetitle', 'local_usermanagement'));
$PAGE->set_heading('User Management');
$PAGE->set_url($CFG->wwwroot . '/local/usermanagement/index.php');
if (!isset($SESSION->bulk_users)) {
    $SESSION->bulk_users = array();
}
// create the user filter form
$ufiltering = new user_filtering();

// array of bulk operations
// create the bulk operations form
$action_form = new user_bulk_action_form();
if ($data = $action_form->get_data()) {
    // check if an action should be performed and do so
    switch ($data->action) {
        case 1: redirect($CFG->wwwroot.'/local/usermanagement/user_bulk_confirm.php');
        case 2: redirect($CFG->wwwroot.'/local/usermanagement/user_bulk_message.php');
        case 3: redirect($CFG->wwwroot.'/local/usermanagement/user_bulk_delete.php');
        case 4: redirect($CFG->wwwroot.'/local/usermanagement/user_bulk_display.php');
        case 5: redirect($CFG->wwwroot.'/local/usermanagement/user_bulk_download.php');
        //case 6: redirect($CFG->wwwroot.'/'.$CFG->admin.'/user/user_bulk_enrol.php'); //TODO: MDL-24064
        case 7: redirect($CFG->wwwroot.'/local/usermanagement/user_bulk_forcepasswordchange.php');
        case 8: redirect($CFG->wwwroot.'/local/usermanagement/user_bulk_cohortadd.php');
        /*nihar chnages for custom requirement(autogenerate password.. etc) */
        case 9: redirect($CFG->wwwroot.'/local/usermanagement/user_autogeneratepassword.php');
        case 10: redirect($CFG->wwwroot.'/local/usermanagement/user_setpassword.php');
        case 11: redirect($CFG->wwwroot.'/local/usermanagement/user_suspend.php');
        case 12: redirect($CFG->wwwroot.'/local/usermanagement/user_unsuspend.php');
        case 13: redirect($CFG->wwwroot.'/local/usermanagement/user_suspendcriteria.php');
    }
}

$user_bulk_form = new user_bulk_form(null, get_data_selection($ufiltering));
$changepassword = new changepasswordcapability_form();

//Set default data (if any)
$capcheck = $DB->get_record('role_capabilities', array('contextid' => '1', 'roleid' => '7', 'capability' => 'moodle/user:changeownpassword'));
  if($capcheck) {
    $setrec = new stdClass();
    $setrec->changepassword = $capcheck->permission;
    $changepassword->set_data($setrec);
}


if($changepassworddata = $changepassword->get_data()){
    
    if( $changepassworddata->allowchangepassword && $capcheck ){
        $record = new stdClass();
        $record->id = $capcheck->id;
        $record->permission = $changepassworddata->changepassword;
        $lastupdate = $DB->update_record('role_capabilities', $record);
        if($lastupdate){
            redirect(new moodle_url($CFG->wwwroot.'/local/usermanagement/index.php'),'<strong>Change password capability has been set successfully</strong>');
        }
    }
}

if ($data = $user_bulk_form->get_data()) {
    if (!empty($data->addall)) {
        add_all_selection($ufiltering);

    } else if (!empty($data->addsel)) {
        if (!empty($data->ausers)) {
            if (in_array(0, $data->ausers)) {
                add_all_selection($ufiltering);
            } else {
                foreach($data->ausers as $userid) {
                    if ($userid == -1) {
                        continue;
                    }
                    if (!isset($SESSION->bulk_users[$userid])) {
                        $SESSION->bulk_users[$userid] = $userid;
                    }
                }
            }
        }

    } else if (!empty($data->removeall)) {
        $SESSION->bulk_users= array();

    } else if (!empty($data->removesel)) {
        if (!empty($data->susers)) {
            if (in_array(0, $data->susers)) {
                $SESSION->bulk_users= array();
            } else {
                foreach($data->susers as $userid) {
                    if ($userid == -1) {
                        continue;
                    }
                    unset($SESSION->bulk_users[$userid]);
                }
            }
        }
    }

    // reset the form selections
    unset($_POST);
    $user_bulk_form = new user_bulk_form(null, get_data_selection($ufiltering));
}
// do output
echo $OUTPUT->header();

$ufiltering->display_add();
$ufiltering->display_active();

$user_bulk_form->display();
$action_form->display();

$changepassword->display();

echo $OUTPUT->footer();
