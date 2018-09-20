<?php
//
// Written at Louisiana State University
//

//library functions for the admin email part of quickmailsms. 
//

class Message {

    public  $subject,
            $text,
            $html,
            $users,
            $admins,
            $warnings,
            $noreply,
            $sentUsers,
            $failuserids,
            $startTime,
            $endTime;
    
    /*  Message constructor
     *  Constructs a message object for mailing to groups filtered by admin_email
     *  @param array $data - an array of variables related to the emailing
     *  @param array $users - an array of users to be emailed
     * @return void / nothing
     */
    public function __construct($data, $users){
        global $DB;
        $this->warnings = array();

        $this->subject  = $data->subject;
        $this->html     = $data->message_editor['text'];
        $this->text     = strip_tags($data->message_editor['text']);
        $this->noreply  = $data->noreply;
        $this->warnings = array();
        $this->users    = array_values($DB->get_records_list('user', 'id', $users));
        $this->failuserids = array();
    }
    
    /* 
     * sends the message
     * @params array $users
     * @return array $this->failuserids;
     */
    public function send($users = null){
        
        $this->startTime = time();
        $users = empty($users) ? $this->users : $users;

        $noreplyUser                = new stdClass();
        $noreplyUser->firstname     = 'Moodle';
        $noreplyUser->lastname      = 'Administrator';
        $noreplyUser->username      = 'moodleadmin';
        $noreplyUser->email         = $this->noreply;
        $noreplyUser->maildisplay   = 2;
        $noreplyUser->alternatename = "";
        $noreplyUser->firstnamephonetic = "";
        $noreplyUser->lastnamephonetic = "";
        $noreplyUser->middlename = "";
        if(empty($users)){
            $this->warnings[] = get_string('no_users', 'block_quickmailsms');
        }
        foreach($users as $user) {
            $success = email_to_user(
                    $user,          // to
                    $noreplyUser,   // from
                    $this->subject, // subj
                    $this->text,    // body in plain text
                    $this->html,    // body in HTML
                    '',             // attachment
                    '',             // attachment name
                    true,           // user true address ($USER)
                    $this->noreply, // reply-to address
                    get_string('pluginname', 'block_quickmailsms') // reply-to name
                    );
            if(!$success){
 
                $this->warnings[] = get_string('email_error', 'block_quickmailsms', $user);
                $this->failuserids[] = $user->id;
            }
            else{
                $this->sentUsers[] = $user->username;
            }
        }
        
        $this->endTime = time();
        
        return $this->failuserids;
    }

    /* builds a receipt emailed to admin that displays details of the group message
     * @return string $usersLine.$warnline.$timeLine.$msgLine.$recipLine
     */
    
    public function buildAdminReceipt(){
        global $CFG, $DB;
        $adminIds     = explode(',',$CFG->siteadmins);
        $this->admins = $DB->get_records_list('user', 'id',$adminIds);

        $usersLine      = quickmailsms::_s('message_sent_to') . " " . count($this->sentUsers) . " " . quickmailsms::_s('users') . " <br /> "; 
        $timeLine       = quickmailsms::_s('time_elapsed') . " " . ($this->endTime - $this->startTime) . " " . quickmailsms::_s('seconds') . " <br />"; 
        $warnline       = quickmailsms::_s('warnings') . " " . count($this->warnings) . " <br />";
        $msgLine        = quickmailsms::_s('message_body_as_follows') . " <br/><br/><hr/>" . $this->html . "<hr />";
        if(count($this->sentUsers) > 0) {
            $recipLine      = quickmailsms::_s("sent_successfully_to_the_following_users") . " <br/><br/> " . implode(', ', $this->sentUsers);
        } else {
            $recipLine  = quickmailsms::_s('something_broke');
        }
        return $usersLine.$timeLine.$warnline.$msgLine.$recipLine;
    }

    /*
     * sends the admin receipt
     */
    public function sendAdminReceipt(){
        $this->html = $this->buildAdminReceipt();
        $this->text = $this->buildAdminReceipt();
        $this->subject  = quickmailsms::_s("admin_email_send_receipt");
        $this->send($this->admins);
    }
}
