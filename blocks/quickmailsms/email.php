<?php

// Written at Louisiana State University

require_once('../../config.php');
require_once('../../enrol/externallib.php');
require_once('lib.php');
require_once('sms_form.php');
require_once('../../lib/weblib.php');

require_login();

$courseid = required_param('courseid', PARAM_INT);
$type = optional_param('type', '', PARAM_ALPHA);
$typeid = optional_param('typeid', 0, PARAM_INT);
$sigid = optional_param('sigid', 0, PARAM_INT);
$messageIDresend = optional_param('fmid', 0, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('no_course', 'block_quickmailsms', '', $courseid);
}

if (!empty($type) and !in_array($type, array('log', 'drafts'))) {
    print_error('no_type', 'block_quickmailsms', '', $type);
}

if (!empty($type) and empty($typeid)) {
    $string = new stdclass;
    $string->tpe = $type;
    $string->id = $typeid;

    print_error('no_typeid', 'block_quickmailsms', '', $string);
}

$config = quickmailsms::load_config($courseid);

$context = context_course::instance($courseid);
$has_permission = (
        has_capability('block/quickmailsms:cansend', $context) or
        !empty($config['allowstudents'])
        );

if (!$has_permission) {
    print_error('no_permission', 'block_quickmailsms');
}

$sigs = $DB->get_records('block_qmsms_signatures', array('userid' => $USER->id), 'default_flag DESC');

$alt_params = array('courseid' => $course->id, 'valid' => 1);
$alternates = $DB->get_records_menu('block_quickmailsms_alternate', $alt_params, '', 'id, address');

$blockname = quickmailsms::_s('pluginname');
$header = quickmailsms::_s('email');

$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->navbar->add($blockname);
$PAGE->navbar->add($header);
$PAGE->set_title($blockname . ': ' . $header);
$PAGE->set_heading($blockname . ': ' . $header);
$PAGE->set_url('/blocks/quickmailsms/email.php', array('courseid' => $courseid));
$PAGE->set_pagetype(quickmailsms::PAGE_TYPE);
$PAGE->set_pagelayout('standard');

$PAGE->requires->jquery();
$PAGE->requires->js('/blocks/quickmailsms/js/selection.js');

$course_roles = get_roles_used_in_context($context);

$filter_roles = $DB->get_records_select('role', sprintf('id IN (%s)', $config['roleselection']));

$roles = quickmailsms::filter_roles($course_roles, $filter_roles);

$allgroups = groups_get_all_groups($courseid);

$mastercap = true;
$groups = $allgroups;

$restricted_view = (
    !has_capability('moodle/site:accessallgroups', $context) and
    $config['ferpa'] == 'strictferpa'
);

$respected_view = (
    !has_capability('moodle/site:accessallgroups', $context) and
    $course->groupmode == 1 and
    $config['ferpa'] == 'courseferpa'
);

if ($restricted_view || $respected_view) {
    $mastercap = false;
    $mygroups = groups_get_user_groups($courseid);
    $gids = implode(',', array_values($mygroups['0']));
    $groups = empty($gids) ?
            array() :
            $DB->get_records_select('groups', 'id IN (' . $gids . ')');
}

$globalaccess = empty($allgroups);

// Fill the course users by
$users = array();
$users_to_roles = array();
$users_to_groups = array();

$everyone = quickmailsms::get_non_suspended_users($context, $courseid);

foreach ($everyone as $userid => $user) {
    $usergroups = groups_get_user_groups($courseid, $userid);

    $gids = ($globalaccess or $mastercap) ?
        array_values($usergroups['0']) :
        array_intersect(array_values($mygroups['0']), array_values($usergroups['0']));

    $userroles = get_user_roles($context, $userid);
    $filterd = quickmailsms::filter_roles($userroles, $roles);

    // Available groups
    if ((!$globalaccess and !$mastercap) and
        empty($gids) or empty($filterd) or $userid == $USER->id)
        continue;

    $groupmapper = function($id) use ($allgroups) { return $allgroups[$id]; };

    $users_to_groups[$userid] = array_map($groupmapper, $gids);
    $users_to_roles[$userid] = $filterd;
    if (!$user->suspended) {
        $users[$userid] = $user;
    }
}

if (empty($users)) {
    print_error('no_usergroups', 'block_quickmailsms');
}

// we are presenting the form with values populated from either the log or drafts table in the db
if (!empty($type)) {
    
    $email = $DB->get_record('block_quickmailsms_' . $type, array('id' => $typeid));
    //$emailmailto = array();
    if ($messageIDresend == 1) {
        list($email->mailto, $email->additional_emails) = quickmailsms::clean($email->failuserids);
    }
} else {
    $email = new stdClass;
    $email->id = null;
    $email->subject = optional_param('subject', '', PARAM_TEXT);
    $email->message = optional_param('message_editor[text]', '', PARAM_RAW);
    $email->mailto = optional_param('mailto', '', PARAM_TEXT);
    $email->format = $USER->mailformat;
}
$email->messageformat = $email->format;
$email->messagetext = $email->message;

$default_sigid = $DB->get_field('block_qmsms_signatures', 'id', array(
    'userid' => $USER->id, 'default_flag' => 1
));
$email->sigid = $default_sigid ? $default_sigid : -1;

// Some setters for the form
$email->type = $type;
$email->typeid = $typeid;

$editor_options = array(
    'trusttext' => false,
    'subdirs' => 0,
    'maxfiles' => 0,
    'accepted_types' => '',
    'noclean'=>0,
    'context' => $context
);

$email = file_prepare_standard_editor(
    $email, 'message', $editor_options, $context, 'block_quickmailsms', $type, $email->id
);

$selected = array();
if (!empty($email->mailto)) {
    foreach (explode(',', $email->mailto) as $id) {
        $selected[$id] = (object) array('id'=>$id,'firstname'=>null,'lastname'=>null,'email'=>$id,'mailformat'=>'1','suspended'=>'0','maildisplay'=>'2','status'=>'0'); 
        if(is_numeric($selected[$id]->id)) {
            $selected[$id] = $users[$id];
        } 
        unset($users[$id]);
    }
}


$form = new email_form(null, array(
    'editor_options' => $editor_options,
    'selected' => $selected,
    'users' => $users,
    'roles' => $roles,
    'groups' => $groups,
    'users_to_roles' => $users_to_roles,
    'users_to_groups' => $users_to_groups,
    'sigs' => array_map(function($sig) { return $sig->title; }, $sigs),
    'alternates' => $alternates
));

$warnings = array();

if ($form->is_cancelled()) {
    redirect(new moodle_url('/course/view.php?id=' . $courseid));
    // DWE we should check if we have selected users or emails around here. 
} else if ($data = $form->get_data()) {
    /*if (empty($data->subject)) {
        $warnings[] = get_string('no_subject', 'block_quickmailsms');
    }*/

    if (empty($data->mailto) && empty($data->additional_emails)) {
        $warnings[] = get_string('no_users', 'block_quickmailsms');
    }

    if (empty($warnings)) {

        // Submitted data
        $data->time = time();
        $data->format = $data->message_editor['format'];
        $data->message = $data->message_editor['text'];
        $data->attachment = '';//quickmailsms::attachment_names($data->attachments);
        $data->messageWithSigAndAttach = "";
        // Store data; id is needed for file storage
        if (isset($data->send)) {
            $data->id = $DB->insert_record('block_quickmailsms_log', $data);
            $table = 'log';
        } else if (isset($data->draft)) {
            $table = 'drafts';

            if (!empty($typeid) and $type == 'drafts') {
                $data->id = $typeid;
                $DB->update_record('block_quickmailsms_drafts', $data);
            } else {
                $data->id = $DB->insert_record('block_quickmailsms_drafts', $data);
            }
        }

        $data = file_postupdate_standard_editor(
            $data, 'message', $editor_options, $context, 'block_quickmailsms', $table, $data->id
        );

        $DB->update_record('block_quickmailsms_' . $table, $data);

      /*  $prepender = $config['prepend_class'];
        if (!empty($prepender) and !empty($course->$prepender)) {
            $subject = "[{$course->$prepender}] $data->subject";
        } else {
            $subject = '';//$data->subject;
        }*/
$subject = '';
        // An instance id is needed before storing the file repository
      //  file_save_draft_area_files(
      //          $data->attachments, $context->id, 'block_quickmailsms', 'attachment_' . $table, $data->id, $editor_options
       // );

        // Send emails
        if (isset($data->send)) {
            if ($type == 'drafts') {
                quickmailsms::draft_cleanup($context->id, $typeid);
            }

            if (!empty($sigs) and $data->sigid > -1) {
                $sig = $sigs[$data->sigid];

                $signaturetext = file_rewrite_pluginfile_urls($sig->signature, 'pluginfile.php', $context->id, 'block_quickmailsms', 'signature', $sig->id, $editor_options);

                
            }
            if(empty($signaturetext)){
                $data->messageWithSigAndAttach = $data->message;
            }
            else{
                $data->messageWithSigAndAttach = $data->message . "\n\n" .$signaturetext;
            }
            // Append links to attachments, if any
                $data->messageWithSigAndAttach .= quickmailsms::process_attachments(
                    $context, $data, $table, $data->id
                );
            // Prepare html content of message
            $data->message = file_rewrite_pluginfile_urls($data->message, 'pluginfile.php', $context->id, 'block_quickmailsms', $table, $data->id, $editor_options);

            // Same user, alternate email
            if (!empty($data->alternateid)) {
                $user = clone($USER);
                $user->email = $alternates[$data->alternateid];
            } else {
                $user = $USER;
            }
            $data->failuserids = array();
            if(!empty($data->mailto)) {
                foreach (explode(',', $data->mailto) as $userid) {
                    // WHERE THE ACTUAL EMAILING IS HAPPENING
                    
// BEGIN SMS MODIFICATION
// CHECK IF USER AGREES TO SMS 
		$sucess = '';
		$sms_agree_sql =	'SELECT t1.shortname, t2.data 
						FROM {user_info_field} as t1 , {user_info_data} as t2
						WHERE t1.id = t2.fieldid 
						AND t1.shortname = "opt"
						AND t2.userid = ' . $everyone[$userid]-> id;
		$sms_agree_result = $DB->get_record_sql($sms_agree_sql);
// IF USER AGREES, LOOK UP TELEPHONE NUMBER AND CARRIER IN CUSTOM PROFILE FIELDS	    
		if($sms_agree_result->data == 1) {
			$sms_number_sql =	'SELECT t1.shortname, t2.data 
							FROM {user_info_field} as t1 , {user_info_data} as t2
							WHERE t1.id = t2.fieldid 
							AND t1.shortname = "mobilephone"
							AND t2.userid = ' . $everyone[$userid]-> id;
			$sms_number_result = $DB->get_record_sql($sms_number_sql);

			$sms_number = $sms_number_result->data;
			$sms_provider_sql =	'SELECT t1.shortname, t2.data 
							FROM {user_info_field} as t1 , {user_info_data} as t2
							WHERE t1.id = t2.fieldid 
							AND t1.shortname = "mobileprovider"
							AND t2.userid = ' . $everyone[$userid]-> id;
			$sms_provider_result = $DB->get_record_sql($sms_provider_sql);
			$sms_provider_full = $sms_provider_result->data;
			$sms_provider_array = explode('~',$sms_provider_full);
			$sms_provider = $sms_provider_array[1];
			$everyone[$userid]->email = $sms_number . $sms_provider;
		
			$success = email_to_user($everyone[$userid], $user, $subject,
			strip_tags($data->message), '');
		} else {
			$warnings[] = get_string("no_agreement", 'block_quickmailsmssms', $everyone[$userid]);
		}
//END SMS MODIFICATION                    
                    
     //$success = email_to_user($everyone[$userid], $user, $subject, strip_tags($data->messageWithSigAndAttach), $data->messageWithSigAndAttach);
                    
                    
                    
                    if (!$success) {
                        $warnings[] = get_string("no_email", 'block_quickmailsms', $everyone[$userid]);
                        $data->failuserids[] = $userid;
                    }
                }
            }

        if(!empty($data->additional_emails)){
            
            $additional_email_array = preg_split('/[,;]/', $data->additional_emails);

            

                $i = 0;

                foreach ($additional_email_array as $additional_email) {
                    $additional_email = trim($additional_email); 

                    $fakeuser = new object();
                    $fakeuser->id = 99999900 + $i;
                    $fakeuser->email = $additional_email;
                    // TODO make this into a menu option
                    $fakeuser->mailformat = 1;

                    $additional_email_success = email_to_user($fakeuser, $user, $subject, strip_tags($data->messageWithSigAndAttach), $data->messageWithSigAndAttach);
                    if (!$additional_email_success) {
                        $data->failuserids[] = $additional_email;

                        // will need to notify that an email is incorrect
                        $warnings[] = get_string("no_email_address", 'block_quickmailsms', $fakeuser->email);
                    }

                    $i++;
                }
        }

            $data->failuserids = implode(',', $data->failuserids);
            $DB->update_record('block_quickmailsms_log', $data);

            if ($data->receipt) {
                email_to_user($USER, $user, $subject, strip_tags($data->messageWithSigAndAttach), $data->messageWithSigAndAttach);
            }
        }
    }
    $email = $data;
}

if (empty($email->attachments)) {
    if (!empty($type)) {
        $attachid = file_get_submitted_draft_itemid('attachment');
        file_prepare_draft_area(
            $attachid, $context->id, 'block_quickmailsms', 'attachment_' . $type, $typeid
        );
        $email->attachments = $attachid;
    }
}

$form->set_data($email);

if (empty($warnings)) {
    if (isset($email->send)) {
        redirect(new moodle_url('/blocks/quickmailsms/emaillog.php', array('courseid' => $course->id)));
    } else if (isset($email->draft)) {
        $warnings['success'] = get_string("changessaved");
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading($blockname);

foreach ($warnings as $type => $warning) {
    $class = ($type === 'success') ? 'notifysuccess' : 'notifyproblem';
    echo $OUTPUT->notification($warning, $class);
}

echo html_writer::start_tag('div', array('class' => 'no-overflow'));
$form->display();
echo html_writer::end_tag('div');
echo $OUTPUT->footer();
