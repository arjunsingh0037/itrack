<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class edit_component_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
        $attributes=array('id'=>'to_edit_comp');
        $streams = array();
        $mform->addElement('hidden', 'cid', '', $attributes);
        $mform->setType('cid', PARAM_INT);

        $prof_exists = $DB->get_record('tp_useruploads',array('userid'=>$USER->id,'roletype'=>'professor'));
        if($prof_exists){
            $creator = $DB->get_record('tp_useruploads',array('userid'=>$USER->id,'roletype'=>'professor'),'creatorid');
            $partner = $DB->get_record('trainingpartners',array('userid'=>$creator->creatorid),'createdby');
            $accountm = $DB->get_record('partners',array('userid'=>$partner->createdby),'createdby');
            if($DB->record_exists('stream',array('createdby'=>$accountm->createdby))){
                $stream_arr = $DB->get_records('stream',array('createdby'=>$accountm->createdby));
            }
            foreach ($stream_arr as $key => $value) {
                $streams[$key] = $value->stream_name;
            }
        }
        
        $attributes=array('size'=>'20');
        $mform->addElement('select', 'ac_stream','Stream', $streams,array('id'=>'enrol_stream'));
        $mform->addRule('ac_stream', 'Required', 'required', null, 'client');

        $mform->addElement('text', 'name', 'Name', $attributes);
        $mform->setType('name', PARAM_RAW);
        $mform->addRule('name', 'Name required', 'required', null, 'client');

        $mform->addElement('text', 'price', 'Price', $attributes);
        $mform->setType('price', PARAM_INT);
        $mform->addRule('price', 'Numbers only', 'numeric', null, 'client');
        $mform->addRule('price', 'Price required', 'required', null, 'client');

        $buttonarray=array();
		$buttonarray[] = $mform->createElement('submit', 'submitbutton','Save Changes');
		$buttonarray[] = $mform->createElement('Cancel');
		$mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
	}

	function validation($data, $files) {
	
	}
}
class delete_component_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
		$mform->addElement('static', 'description','','Are you sure you want to delete this component ?');

        $attributes=array('id'=>'to_delete_comp');
        $mform->addElement('hidden', 'cid', '', $attributes);
        $mform->setType('cid', PARAM_INT);

        $buttonarray=array();
		$buttonarray[] = $mform->createElement('submit', 'submitbutton','Yes');
		$buttonarray[] = $mform->createElement('Cancel');
		$mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
	}

	function validation($data, $files) {
	
	}
}
?>

