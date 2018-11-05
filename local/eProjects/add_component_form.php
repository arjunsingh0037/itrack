<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class add_component_form extends moodleform {

    protected function definition() {
        global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
        $mform->addElement('header','filehdr', 'Enlist Components');
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
        $mform->addElement('select', 'ac_stream','Stream', $streams,array('id'=>'enrol_stream'));

        $mform->addElement('text', 'name', 'Name', $attributes);
        $mform->setType('name', PARAM_RAW);
        $mform->addRule('name', 'Name required', 'required', null, 'client');

        $mform->addElement('text', 'price', 'Price', $attributes);
        $mform->setType('price', PARAM_INT);
        $mform->addRule('price', 'Numbers only', 'numeric', null, 'client');
        $mform->addRule('price', 'Price required', 'required', null, 'client');
        // $mform->addElement('autocomplete', 'price', 'Browsers', '', array('multiple'=>'multiple'));
        // $mform->setType('price', PARAM_INT);
        $mform->addElement('submit', 'btnsave', 'Submit');
    }

    public function reset() {
        $this->_form->updateSubmission(null, null);
    }

    function validation($data, $files) {
        
    }   
}
?>

