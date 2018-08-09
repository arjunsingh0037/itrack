<?php

require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/local/usermanagement/lib.php');
require_once($CFG->dirroot.'/local/usermanagement/allcohortform.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pagetitle', 'local_usermanagement'));
$PAGE->set_heading('User Management');
$PAGE->set_url($CFG->wwwroot . '/local/usermanagement/index1.php');
$cohortform = new allcohort_form();
if ($data = $cohortform->get_data()) {
     
    $SESSION->bulk_cohort = $data->allcohort;
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

// do output
echo $OUTPUT->header();

$cohortform->display();


echo $OUTPUT->footer();
