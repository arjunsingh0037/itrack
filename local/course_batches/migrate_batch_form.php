<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class migrate_batches_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB,$USER;	
		$mform = & $this->_form;

		$sql = "SELECT DISTINCT program from {batches} WHERE creatorid = ? AND batchtype= 'ACAD'";
		$program = $DB->get_records_sql($sql,array($USER->id));
		foreach ($program as $prg) {
				$programs[$prg->program] = $prg->program;
		}
		$streams = array();
		$syear = array('2017'=>'2017','2018'=>'2018');
		$semester = array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8');

		$mform->addElement('select', 'mb_program','Program', $programs,array('id'=>'id_programs'),'Select Program');
		$mform->addElement('html','<div id="spinner1" style="display:none"><div class="col-md-3"></div><div class="col-md-9"><p><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></p></div></div>');
		
		$mform->addElement('html','<div id="batch_streams" class="form-group row  fitem"></div><div id="spinner2" style="display:none"><div class="col-md-3"></div><div class="col-md-9"><p><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></p></div></div>');
		
		$mform->addElement('html','<div id="batch_semyears" class="form-group row  fitem"></div><div id="spinner3" style="display:none"><div class="col-md-3"></div><div class="col-md-9"><p><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></p></div></div>');

		$mform->addElement('html','<div id="batch_semester" class="form-group row  fitem"></div><div id="spinner4" style="display:none"><div class="col-md-3"></div><div class="col-md-9"><p><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></p></div></div>');

		$mform->addElement('html','<div id="batchmatch" class="form-group row  fitem"></div><div id="spinner4" style="display:none"><div class="col-md-3"></div><div class="col-md-9"><p><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></p></div></div>');
		$mform->addElement('html','<div class="col-md-3"></div><div id="arrowdown" class="arrow bounce col-md-9"><a class="fa fa-arrow-down fa-2x" href="#"></a></div>');
		
		$mform->addElement('select', 'new_semester_year','Migrate To', $syear,array('id'=>'new_syear'));
		
		$mform->addElement('select', 'new_semester','Semester', $semester,array('id'=>'new_semester'));
		
		$mform->addElement('submit', 'btnsave', 'Migrate batch');
	}

	function validation($data, $files) {

	}
}
?>

