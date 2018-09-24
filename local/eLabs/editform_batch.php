<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class course_batches_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB;


		$mform = & $this->_form;
		$course = $this->_customdata['course'];
		//$general = $this->_customdata['general'];
		//$payment = $this->_customdata['payment'];
		
		 $mform->addElement('header','generals', get_string('course_extra', 'local_course_batches'));

		$attributes=array('size'=>'20');
		$mform->addElement('text', 'name', get_string('batchname', 'local_course_batches'),$attributes); // Add elements to your form
		$mform->setType('name', PARAM_TEXT);                   //Set type of element	 


		//Type your startdate here
        $mform->addElement('date_selector', 'startdate', get_string('startdate', 'local_course_batches')); // Add elements to your form
        $mform->setType('startdate', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('startdate', '');        //Default value
         
		//Type your enddate here
		$mform->addElement('date_selector', 'enddate', get_string('enddate', 'local_course_batches'), 'wrap="virtual" rows="10" cols="50"');
 		
 		//Type your actual cost here		
		$mform->addElement('text', 'actualcost', get_string('actualcost', 'local_course_batches')); // Add elements to your form
        $mform->setType('actualcost', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('actualcost', '');        //Default value
         
		//Type your discount cost here
		$mform->addElement('text', 'discountcost', get_string('discountcost', 'local_course_batches'), 'wrap="virtual" rows="10" cols="50"');

		//Type your duration here
       
		$mform->addElement('text', 'duration', get_string('duration', 'local_course_batches'), 'wrap="virtual" rows="10" cols="50"');

		//Type your days here
       
		$mform->addElement('text', 'days', get_string('days', 'local_course_batches'), 'wrap="virtual" rows="10" cols="50"');
		
		$this->add_action_buttons(true, get_string('savechanges', 'local_course_batches'));		
		$mform->addElement('hidden', 'id');
		$mform->setType('courseid', PARAM_INT);
		
		
	}

	function validation($data, $files) {
		global $DB;
		$errors = parent :: validation($data, $files);
		return $errors;
	}
}
?>
