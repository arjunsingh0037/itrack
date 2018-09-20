<?php

$string['pluginname'] = 'QuickmailSMS';
$string['sendadmin'] = 'Admin SMS';   
$string['quickmailsms:cansend'] = "Allows users to send SMS through QuickmailSMS";
$string['quickmailsms:canconfig'] = "Allows users to configure QuickmailSMS instance.";
$string['quickmailsms:canimpersonate'] = "Allows users to log in as other users and view history.";
$string['quickmailsms:allowalternate'] = "Allows users to add an alternate SMS for courses.";
$string['quickmailsms:addinstance'] = "Add a new QuickmailSMS block to a course page";
$string['quickmailsms:myaddinstance'] = "Add a new QuickmailSMS block to the /my page";
$string['quickmailsms:candelete'] = "Allows users to delete SMS from history.";
$string['backup_history'] = 'Include QuickmailSMS History';
$string['restore_history'] = 'Restore QuickmailSMS History';
$string['overwrite_history'] = 'Overwrite QuickmailSMS History';
$string['alternate'] = 'Alternate SMSs';
$string['composenew'] = 'Compose New SMS';
$string['email'] = 'SMS';
$string['drafts'] = 'View Drafts';
$string['history'] = 'View History';
$string['log'] = 'View History';
$string['from'] = 'From';
$string['selected'] = 'Selected Recipients';
$string['add_button'] = 'Add';
$string['remove_button'] = 'Remove';
$string['add_all'] = 'Add All';
$string['remove_all'] = 'Remove All';
$string['role_filter'] = 'Role Filter';
$string['no_filter'] = 'No filter';
$string['potential_users'] = 'Potential Recipients';
$string['potential_sections'] = 'Potential Sections';
$string['no_section'] = 'Not in a section';
$string['all_sections'] = 'All Sections';
$string['attachment'] = 'Attachment(s)';
$string['subject'] = 'Subject';
$string['message'] = 'Message';
$string['send_email'] = 'Send SMS';
$string['save_draft'] = 'Save Draft';
$string['actions'] = 'Actions';
$string['signature'] = 'Signatures';
$string['delete_confirm'] = 'Are you sure you want to delete message with the following details: {$a}';
$string['title'] = 'Title';
$string['sig'] ='Signature';
$string['default_flag'] = 'Default';
$string['config'] = 'Configuration';
$string['receipt'] = 'Receive a copy';
$string['receipt_help'] = 'Receive a copy of the SMS being sent';

$string['no_alternates'] = 'No alternate SMSs found for {$a->fullname}. Continue to make one.';

$string['select_users'] = 'Select Users ...';
$string['select_groups'] = 'Select Sections ...';

$string['moodle_attachments'] = 'Moodle Attachments ({$a})';
$string['download_all'] = 'Download All';
$string['qm_contents'] = 'Download File Contents';

// Config form strings
$string['allowstudents'] = 'Allow students to use QuickmailSMS';
$string['allowstudentsdesc'] = 'Allow students to use QuickmailSMS. If you choose "Never", the block cannot be configured to allow students access at the course level.';

$string['select_roles'] = 'Roles to filter by';
$string['reset'] = 'Restore System Defaults';

$string['no_type'] = '{$a} is not in the acceptable type viewer. Please use the applciation correctly.';
$string['no_email'] = 'Could not SMS {$a->firstname} {$a->lastname}.';
$string['no_email_address'] = 'Could not SMS {$a}';
$string['no_log'] = 'You have no SMS history yet.';
$string['no_drafts'] = 'You have no SMS drafts.';
$string['no_subject'] = 'You must have a subject';
$string['no_course'] = 'Invalid Course with id of {$a}';
$string['no_permission'] = 'You do not have permission to send SMSs with QuickmailSMS.';
$string['no_usergroups'] = 'There are no users in your group capable of being SMSed.';
$string['no_users'] = 'There are no users you are capable of SMSing.';
$string['no_selected'] = 'You must select some users for SMSing.';
$string['not_valid'] = 'This is not a valid SMS log viewer type: {$a}';
$string['not_valid_user'] = 'You can not view other SMS history.';
$string['not_valid_action'] = 'You must provide a valid action: {$a}';
$string['not_valid_typeid'] = 'You must provide a valid SMS for {$a}';
$string['delete_failed'] = 'Failed to delete SMS';
$string['required'] = 'Please fill in the required fields.';
$string['prepend_class'] = 'Prepend Course name';
$string['prepend_class_desc'] = 'Prepend the course shortname to the subject of
the SMS.';
$string['ferpa'] = 'FERPA Mode';
$string['ferpa_desc'] = 'Allows the system to behave either according to the course groupmode setting, ignoring the groupmode setting but separating groups, or ignoring groups altogether.';
$string['strictferpa'] = 'Always Separate Groups';
$string['courseferpa'] = 'Respect Course Mode';
$string['noferpa'] = 'No Group Respect';
$string['courselayout'] = 'Course Layout';
$string['courselayout_desc'] = 'Use _Course_ page layout  when rendering the QuickmailSMS block pages. Enable this setting, if you are getting Moodle form fixed width issues.';

$string['are_you_sure'] = 'Are you sure you want to delete {$a->title}? This action
cannot be reversed.';

// Alternate SMS strings
$string['alternate_new'] = 'Add Alternate Address';
$string['sure'] = 'Are you sure you want to delete {$a->address}? This action cannot be undone.';
$string['valid'] = 'Activation Status';
$string['approved'] = 'Approved';
$string['waiting'] = 'Waiting';
$string['entry_activated'] = 'Alternate SMS {$a->address} can now be used in {$a->course}.';
$string['entry_key_not_valid'] = 'Activation link is no longer valid for {$a->address}. Continue to resend activation link.';
$string['entry_saved'] = 'Alternate address {$a->address} has been saved.';
$string['entry_success'] = 'An SMS to verify that the address is valid has been sent to {$a->address}. Instructions on how to activate the address is contained in its contents.';
$string['entry_failure'] = 'An SMS could not be sent to {$a->address}. Please verify that {$a->address} exists, and try again.';
$string['alternate_from'] = 'Moodle: QuickmailSMS';
$string['alternate_subject'] = 'Alternate SMS address verification';
$string['alternate_body'] = '
<p>
{$a->fullname} added {$a->address} as an alternate sending address for {$a->course}.
</p>

<p>
The purpose of this SMS was to verify that this address exists, and the owner
of this address has the appropriate permissions in Moodle.
</p>

<p>
If you wish to complete the verification process, please continue by directing
your browser to the following url: {$a->url}.
</p>

<p>
If the description of this SMS does not make any sense to you, then you may have
received it by mistake. Simply discard this message.
</p>

Thank you.
';


// Strings for Error Reporting
$string['sent_success'] = 'all messages sent successfully';
$string['logsuccess'] = 'all messages sent successfully';
$string['message_failure'] = 'some users did not get message';
$string['send_again'] = 'send again';
$string['status'] = 'status';
$string['failed_to_send_to'] = 'failed to send to';
$string['users'] = 'users';
$string['user'] = 'user';

$string['draftssuccess'] = "Draft";

//admin
$string['sendadmin'] = 'Send Admin SMS';
$string['noreply'] = 'No-Reply';
$string['body'] = 'Body';
$string['email_error'] = 'Could not SMS: {$a->firstname} {$a->lastname} ({$a->SMS})';
$string['email_error_field'] = 'Can not have an empty: {$a}';
$string['messageprovider:broadcast'] = 'Send broadcast messages using Admin SMS.';

$string['message_sent_to'] = 'Message sent to ';
$string['warnings'] = 'Warnings';
$string['message_body_as_follows'] = 'message body as follows ';
$string['sent_successfully_to_the_following_users'] = 'sent successfully to the following users: ' ;
$string['seconds'] = 'seconds';
$string['admin_email_send_receipt'] = 'Admin SMS Send Receipt';
$string['something_broke'] = 'It looks like you either have SMS sending disabled or things are very broken';
$string['time_elapsed'] = 'Time Elapsed: ';
$string['additional_emails'] = 'Additional SMSs';
$string['additional_emails_help'] = 'Other SMS addresses you would like the message sent to, in a comma or semicolon separated list. Example:
 
 SMS1@example.com, SMS2@example.com
 ';

//Strings for SMS Modification
$string['no_agreement'] = 'User did not agree to receive SMS messages.';