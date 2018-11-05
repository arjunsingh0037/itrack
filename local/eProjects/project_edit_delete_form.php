<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class edit_project_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
        $attributes=array('id'=>'to_edit');
        $streams = array();
        $mform->addElement('hidden', 'pid', '', $attributes);
        $mform->setType('pid', PARAM_INT);

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

        $domains = array();
        $attributes=array('size'=>'20');
        $mform->addElement('select', 'ac_domain','Domain', $domains,array('id'=>'enrol_domain'));
        $mform->addRule('ac_domain', 'Required', 'required', null, 'client');

        $mform->addElement('text', 'technology', 'Technology', $attributes);
        $mform->setType('technology', PARAM_RAW);
        $mform->addRule('technology', 'Required', 'required', null, 'client');

        $radioarray=array();
        $attributes=array();
        $radioarray[] = $mform->createElement('radio', 'comp', '', get_string('yes'), 1, $attributes);
        $radioarray[] = $mform->createElement('radio', 'comp', '', get_string('no'), 0, $attributes);
        $mform->addGroup($radioarray, 'comp_group', 'Component', array(' '), false);


        $project_type = array('in'=>'Individual','gp'=>'Group Wise');
        $attributes=array('size'=>'20');
        $mform->addElement('select', 'ptype','Type', $project_type,array('id'=>'project_type'));
        $mform->addRule('ptype', 'Required', 'required', null, 'client');

        $attributes=array('size'=>'20');
        $mform->addElement('text', 'title', 'Title', $attributes);
        $mform->setType('title', PARAM_RAW);
        $mform->addRule('title', 'Title required', 'required', null, 'client');
        
        $mform->addElement('textarea', 'synopsis', 'Synopsis', 'wrap="virtual" rows="10" cols="21"');
        $mform->addRule('synopsis', 'Required', 'required', null, 'client');

        $attributes=array('size'=>'20');
        $mform->addElement('text', 'duration', 'Duration', $attributes);
        $mform->setType('duration', PARAM_INT);
        $mform->addRule('duration', 'Numbers only', 'numeric', null, 'client');
        $mform->addRule('duration', 'Required', 'required', null, 'client');

        $attributes=array('size'=>'20');
        $mform->addElement('text', 'peer', 'Ratings', $attributes);
        $mform->setType('peer', PARAM_INT);
        $mform->addRule('peer', 'Numbers only', 'numeric', null, 'client');
        $mform->addRule('peer', 'Required', 'required', null, 'client');

        $attributes=array('size'=>'20');
        $mform->addElement('text', 'weightage', 'Weightage', $attributes);
        $mform->setType('weightage', PARAM_INT);
        $mform->addRule('weightage', 'Numbers only', 'numeric', null, 'client');
        $mform->addRule('weightage', 'Required', 'required', null, 'client');

        $buttonarray=array();
		$buttonarray[] = $mform->createElement('submit', 'submitbutton','Save Changes');
		$buttonarray[] = $mform->createElement('Cancel');
		$mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
	}

	function validation($data, $files) {
	
	}
}
class delete_project_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
		$mform->addElement('static', 'description','','Are you sure you want to delete this project ?');

        $attributes=array('id'=>'to_delete');
        $mform->addElement('hidden', 'pid', '', $attributes);
        $mform->setType('pid', PARAM_INT);

        $buttonarray=array();
		$buttonarray[] = $mform->createElement('submit', 'submitbutton','Yes');
		$buttonarray[] = $mform->createElement('Cancel');
		$mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
	}

	function validation($data, $files) {
	
	}
}
?>

