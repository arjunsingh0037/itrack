<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class add_project_form extends moodleform {

    protected function definition() {
        global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
        $mform->addElement('header','filehdr', 'Create Project');
        $streams = array();
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
        $mform->addElement('select', 'ac_stream','Select Stream', $streams,array('id'=>'enrol_stream'));
        $mform->addRule('ac_stream', 'Required', 'required', null, 'client');

        $domains = array();
        $attributes=array('size'=>'20');
        $mform->addElement('select', 'ac_domain','Select  Domain', $domains,array('id'=>'enrol_domain'));
        $mform->addRule('ac_domain', 'Required', 'required', null, 'client');

        $mform->addElement('text', 'technology', 'Technology', $attributes);
        $mform->setType('technology', PARAM_RAW);
        $mform->addRule('technology', 'Required', 'required', null, 'client');

        $radioarray=array();
        $attributes=array();
        $radioarray[] = $mform->createElement('radio', 'comp', '', get_string('yes'), 1, $attributes);
        $radioarray[] = $mform->createElement('radio', 'comp', '', get_string('no'), 0, $attributes);
        $mform->addGroup($radioarray, 'comp_group', 'Has Component', array(' '), false);


        $project_type = array('in'=>'Individual','gp'=>'Group Wise');
        $attributes=array('size'=>'20');
        $mform->addElement('select', 'ptype','Select Project Type', $project_type,array('id'=>'project_type'));
        $mform->addRule('ptype', 'Required', 'required', null, 'client');

        $attributes=array('size'=>'20');
        $mform->addElement('text', 'title', 'Project Title', $attributes);
        $mform->setType('title', PARAM_RAW);
        $mform->addRule('title', 'Title required', 'required', null, 'client');
        
        $mform->addElement('textarea', 'synopsis', 'Project Synopsis', 'wrap="virtual" rows="10" cols="21"');
        $mform->addRule('synopsis', 'Required', 'required', null, 'client');

        $attributes=array('size'=>'20');
        $mform->addElement('text', 'duration', 'Project Duration Days', $attributes);
        $mform->setType('duration', PARAM_INT);
        $mform->addRule('duration', 'Numbers only', 'numeric', null, 'client');
        $mform->addRule('duration', 'Required', 'required', null, 'client');

        $attributes=array('size'=>'20');
        $mform->addElement('text', 'peer', 'Project Peer Ratings', $attributes);
        $mform->setType('peer', PARAM_INT);
        $mform->addRule('peer', 'Numbers only', 'numeric', null, 'client');
        $mform->addRule('peer', 'Required', 'required', null, 'client');

        $attributes=array('size'=>'20');
        $mform->addElement('text', 'weightage', 'Assign Upload Code Weightage', $attributes);
        $mform->setType('weightage', PARAM_INT);
        $mform->addRule('weightage', 'Numbers only', 'numeric', null, 'client');
        $mform->addRule('weightage', 'Required', 'required', null, 'client');

        $mform->addElement('submit', 'btnsave', 'Submit');
    }

    public function reset() {
        $this->_form->updateSubmission(null, null);
    }

    function validation($data, $files) {
        
    }   
}
?>

