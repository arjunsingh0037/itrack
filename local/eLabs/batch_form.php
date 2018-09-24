<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class academic_batches_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB;
	

		$mform = & $this->_form;
		$course = $this->_customdata['course'];
		$count = $this->_customdata;

	
		 $mform->addElement('header','filehdr', get_string('course_extra', 'local_course_batches'));
	


				//$mform->addElement('static', 'batchname',get_string('bname','local_course_batches'),$i);
					//Type your startdate here

		 				$attributes=array('size'=>'20');
				        $mform->addElement('text', 'name', get_string('batchname', 'local_course_batches'),$attributes); // Add elements to your form
				        $mform->setType('name', PARAM_TEXT);                   //Set type of element

				        $attributes=array('size'=>'20');
				        $mform->addElement('date_selector', 'startdate', get_string('startdate', 'local_course_batches'),$attributes); // Add elements to your form
				        $mform->setType('startdate', PARAM_TEXT);                   //Set type of element
				       
				         
						//Type your enddate here
						$mform->addElement('date_selector', 'enddate', get_string('enddate', 'local_course_batches'), 'wrap="virtual" rows="10" cols="50"');
				        $mform->setType('enddate', PARAM_TEXT);    //Set type of element
				 	    //Type your actual cost here
				        $attributes=array('size'=>'20');
				        $mform->addElement('text', 'actualcost', get_string('actualcost', 'local_course_batches'), $attributes); // Add elements to your form
				        $mform->setType('actualcost', PARAM_FLOAT);    //Set type of element
				      
				         
						//Type your discount cost here
				       
						$mform->addElement('text', 'discountcost', get_string('discountcost', 'local_course_batches'), 'wrap="virtual" rows="10" cols="50"');
						$mform->setType('discountcost', PARAM_FLOAT); 
                                          
						//Type your duration here

						$mform->addElement('text', 'duration', get_string('duration', 'local_course_batches'), 'wrap="virtual" rows="10" cols="50"');
				       	$mform->setType('duration', PARAM_TEXT); 

						//Type your days here
				       
						$mform->addElement('text', 'days', get_string('days', 'local_course_batches'), 'wrap="virtual" rows="10" cols="50"');
				       	$mform->setType('days', PARAM_TEXT); 
	
		
		$this->add_action_buttons(true, get_string('savechanges', 'local_course_batches'));
		
		$mform->addElement('hidden', 'courseid', $course->id);
		$mform->setType('courseid', PARAM_INT);
		//var_dump($mform);
		
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

