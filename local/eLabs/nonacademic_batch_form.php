<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class nonacademic_batches_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB;
		$mform = & $this->_form;
		$mform->addElement('header','filehdr', 'Non Academic Batch');

		$attributes=array('size'=>'20','placeholder'=>'Batch Name');
        $mform->addElement('text', 'batchname', 'Batch Name',$attributes);
        $mform->setType('batchname', PARAM_RAW); 

        $sb_batch_exists = $DB->get_records('batches',array(),'id,batchcode');
		if($sb_batch_exists){
			foreach ($sb_batch_exists as $sb_batches) {
	        	$last_batchcode = $sb_batches->batchcode;
	        } 
	        $bsingle = explode("BA",$last_batchcode);
	        $bcode = 'BA'.($bsingle[1]+2);
		}else{
			$bcode = 'BA9999';
		}

        $attributes=array('size'=>'20');
        $batch_code = $mform->addElement('text', 'batchcode', 'Batch Code',$attributes);
        $mform->setType('batchcode', PARAM_RAW);
        $mform->setDefault('batchcode',$bcode);	
        $batch_code->freeze(); 

        $attributes=array('size'=>'20', 'id'=>'id_btype');
        $mform->addElement('hidden', 'btype', 'Batch Type',$attributes);
        $mform->setType('btype', PARAM_RAW);
        $mform->setDefault('btype','NACAD');

		$this->add_action_buttons(true, 'Create Batch');	
	}

	function validation($data, $files) {
		/* global $DB;
		
		$errors = parent :: validation($data, $files);
		if($data['radioar']['yesno'] =='1'){
			if(isset($data['promocode'])) {
				if($data['promocode']==''){
					$errors['promocode'] = 'required';
				}				
			}
			if(isset($data['discount'])) {
				if($data['discount']==''){
					$errors['discount'] = 'required';
				}				
			}			
		}
		if(isset($data['promocode'])) {	
			if ($paymentexists = $DB->record_exists('course_batches_payment', array('courseid'=>$data['courseid'],'promocode'=>$data['promocode']))) {
					$errors['promocode'] = get_string('promocodetaken', 'local_course_batches');  
			}
		}
		return $errors; */
	}
	 
	
	
	
}
?>

