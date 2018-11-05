<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class elab_probdesc_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB,$USER;
		$mform = & $this->_form;
		$programs = array();
		
		$editoroptions = $this->_customdata['editoroptions'];		
		$mform->addElement('hidden', 'labid', '',array('id'=>'sendlabid'));
        $mform->setType('labid', PARAM_RAW);

		$mform->addElement('header','head6', 'SOLUTION TEMPLATE FOR PROBLEM DESCRIPTION:');

		$mform->addElement('editor','lab_problem_solution_template', '', null, $editoroptions);
        $mform->setType('lab_problem_solution_template', PARAM_RAW);

		$mform->addElement('header','head7', 'SOLUTION TEMPLATE FOR ALGORITHM DESCRIPTION:');

		$mform->addElement('editor','algorithm_solution_template', '', null, $editoroptions);
        $mform->setType('algorithm_solution_template', PARAM_RAW);

		$mform->addElement('header','head8', 'SOLUTION TEMPLATE FOR DATA I/O DESCRIPTION:');

		$mform->addElement('editor','dataio_solution_template', '', null, $editoroptions);
        $mform->setType('dataio_solution_template', PARAM_RAW);

		$mform->addElement('header','head9', 'SOLUTION TEMPLATE FOR PROGRAM CODE:');

		$mform->addElement('editor','program_solution_template', '', null, $editoroptions);
        $mform->setType('program_solution_template', PARAM_RAW);

        $mform->addElement('submit', 'btnsave', 'Submit');
	}

	function validation($data, $files) {
        $errors = parent::validation($data, $files);

        /*$supportedtypes = array('jpe' => 'image/jpeg',
                                'jpeIE' => 'image/pjpeg',
                                'jpeg' => 'image/jpeg',
                                'jpegIE' => 'image/pjpeg',
                                'jpg' => 'image/jpeg',
                                'jpgIE' => 'image/pjpeg');*/
        $supportedtypes = [
                'pdf'   => true
            ];

        $files = $this->get_draft_files('resourcefile');
        if ($files) {
            foreach ($files as $file) {
                if (!in_array($file->get_mimetype(), $supportedtypes)) {
                    $errors['resourcefile'] = 'File type not supported';
                }
                if($file->get_filesize() > 1048576){
                        $errors['resourcefile'] = 'File size exceeded';
                }
            }
        } else {
            $errors['resourcefile'] = 'No file selected';
        }

        return $errors;
    }	
}
?>

