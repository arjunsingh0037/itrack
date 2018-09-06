<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class search_project_form extends moodleform {

    function definition() {
        global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
        $mform->addElement('header','filehdr', 'SEARCH PROJECTS');
        $streams = array();
        $stu_exists = $DB->get_record('tp_useruploads',array('userid'=>$USER->id,'roletype'=>'student')); 
        if($stu_exists){
            $creator = $DB->get_record('tp_useruploads',array('userid'=>$USER->id,'roletype'=>'student'),'creatorid');
            $partner = $DB->get_record('trainingpartners',array('userid'=>$creator->creatorid),'createdby');
            $accountm = $DB->get_record('partners',array('userid'=>$partner->createdby),'createdby');
            //to get the streams created by the  accountmnager fof the loginned user
            if($DB->record_exists('stream',array('createdby'=>$accountm->createdby))){
                $stream_arr = $DB->get_records('stream',array('createdby'=>$accountm->createdby));
            }
            foreach ($stream_arr as $key => $value) {
                $streams[$key] = $value->stream_name;
            }
        }
        $mform->addElement('html','<div class="searchbox">');
        $mform->addElement('html','<div class="s1">');
        $mform->addElement('select', 'ac_stream','Select Stream', $streams,array('id'=>'enrol_stream'),'Select Stream');
        $mform->addElement('html','<div id="spinner1" style="display:none"><div class="col-md-3"></div><div class="col-md-9"><p><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></p></div></div>');
        $mform->addRule('ac_stream', 'Required', 'required', null, 'client');

        $domains = array();
        $attributes=array('size'=>'20');
        $mform->addElement('select', 'ac_domain','Select  Domain', $domains,array('id'=>'enrol_domain'));
        $mform->addRule('ac_domain', 'Required', 'required', null, 'client');
        
        $mform->addElement('html','</div>');
        $mform->addElement('html','<div class="s1">');
        $procat = array('gp'=>'Group','in'=>'Individual');
        $mform->addElement('select', 'ac_category','Select Project Category', $procat,array('id'=>'search_category'),'Select Project Category');
        $mform->addElement('html','<div id="spinner1" style="display:none"><div class="col-md-3"></div><div class="col-md-9"><p><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></p></div></div>');
        $mform->addRule('ac_category', 'Required', 'required', null, 'client');

        $groups = array();
        $attributes=array('size'=>'20');
        $mform->addElement('select', 'ac_group','Select Group', $groups,array('id'=>'search_group'));
        $mform->addRule('ac_group', 'Required', 'required', null, 'client');
        
        $mform->addElement('html','</div>');
        $mform->addElement('html','</div>');
        
        $mform->addElement('submit', 'btnsearch', 'Submit');
    }

    function validation($data, $files) {
        
    }   
}
?>

