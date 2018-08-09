<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class academic_batches_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB;
		$mform = & $this->_form;
		$mform->addElement('header','filehdr', 'Academic Batch');
		//$programs = array('BTECH'=>'BTECH','MBA'=>'MBA','SCoE'=>'SCoE','Corporate_Program'=>'Corporate_Program','BCA'=>'BCA','class XI'=>'class XI','class XII'=>'class XII');
		$programs = array();
		$sql = "SELECT DISTINCT program from {program_stream}";
		$program = $DB->get_records_sql($sql);
		foreach ($program as $prg) {
				$programs[$prg->program] = $prg->program;
		}
		$streams = array();
		$syear = array('2017'=>'2017','2018'=>'2018');
		$semester = array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8');

		$mform->addElement('select', 'ac_program','Program', $programs,array('id'=>'id_programs'),'Select Program');
		//$mform->addElement('select', 'program','Program',$programs ,array('id'=>'id_programs'));
		
		$mform->addElement('select', 'ac_stream','Stream', $streams,array('id'=>'id_streams'));
		
		$mform->addElement('select', 'ac_semester_year','Semester Year', $syear,array('id'=>'id_syears'));
		
		$mform->addElement('select', 'ac_semester','Semester', $semester,array('id'=>'id_semesters'));
		
		$attributes=array('size'=>'20','placeholder'=>'Batch Name');
        $mform->addElement('text', 'ac_batchname', 'Batch Name',$attributes);
        $mform->setType('ac_batchname', PARAM_RAW); 

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
	    // if($ac_batch_exists){
	    // 	$bcode = $ac_batch_exists->batchcode.$j;
	    // }

        $attributes=array('size'=>'20');
        $batch_code = $mform->addElement('text', 'ac_batchcode', 'Batch Code',$attributes);
        $mform->setType('ac_batchcode', PARAM_RAW);
        $mform->setDefault('ac_batchcode',$bcode);
        $batch_code->freeze(); 

        $attributes=array('size'=>'20', 'id'=>'id_btype');
        $mform->addElement('hidden', 'btype', 'Batch Type',$attributes);
        $mform->setType('btype', PARAM_RAW);
        $mform->setDefault('btype','ACAD');

        $mform->addElement('submit', 'btnsave', 'create batch');
        //$mform->addElement('cancel', 'btnsave', 'cancel', array('class'=>''));
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

