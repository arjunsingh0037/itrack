<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class academic_elabs_form2 extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB,$USER;
		$mform = & $this->_form;
		$programs = array();
		
		$editoroptions = $this->_customdata['editoroptions'];

		$mform->addElement('header','head1', 'CREATE LAB PROBLEM DEFINITION');
		
		$mform->addElement('hidden', 'labid', '',array('id'=>'sendlabid'));
        $mform->setType('labid', PARAM_RAW);

		$mform->addElement('text', 'ac_lab','Lab Id', '',array('id'=>'newlab'),'Lab Id'); 
		$mform->setType('ac_lab', PARAM_RAW);

		$radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'show_weightage', '', 'Yes', 1, array());
        $radioarray[] = $mform->createElement('radio', 'show_weightage', '', 'No', 0, array());
        $mform->addGroup($radioarray, 'radioar', 'Show weightage to students', array(' '), false);
        $mform->addRule('radioar', get_string('required'), 'required', null, 'client');
		
		$mform->addElement('text','weightageFor','Allocate Weightage For',array('placeholder'=>'max marks'),'Allocate Weigtage For');
		$mform->addRule('weightageFor', get_string('required'), 'required', null, 'client');
		$mform->addRule('weightageFor', 'Only numbers allowed', 'numeric', null, 'server');
		$mform->setType('weightageFor', PARAM_INT);

		$mform->addElement('text','algorithm','Algorithm',array('placeholder'=>'max marks'),'Algorithm');
		$mform->addRule('algorithm', get_string('required'), 'required', null, 'client');
		$mform->addRule('algorithm', 'Only numbers allowed', 'numeric', null, 'server');
		$mform->setType('algorithm', PARAM_INT);

		$mform->addElement('text','dataio','Data I/O',array('placeholder'=>'max marks'),'Data I/O');
		$mform->addRule('dataio', get_string('required'), 'required', null, 'client');
		$mform->addRule('dataio', 'Only numbers allowed', 'numeric', null, 'server');
		$mform->setType('dataio', PARAM_INT);

		$mform->addElement('text','coding','Coding',array('placeholder'=>'max marks'),'Coding');
		$mform->addRule('coding', get_string('required'), 'required', null, 'client');
		$mform->addRule('coding', 'Only numbers allowed', 'numeric', null, 'server');
		$mform->setType('coding', PARAM_INT);

		$mform->addElement('filepicker', 'resourcefile', '');
        $mform->addRule('resourcefile', null, 'required', null, 'client');

		$mform->addElement('text','labname','Lab Name',array(),'Lab Name');
		$mform->addRule('labname', get_string('required'), 'required', null, 'client');
		$mform->setType('labname', PARAM_RAW);

		$mform->addElement('header','head2', 'LAB PROBLEM STATEMENT :');

		$mform->addElement('editor','lab_problem', '', null, $editoroptions);
        $mform->setType('lab_problem', PARAM_RAW);

		$mform->addElement('header','head3', 'KEY CONCEPTS FOR LAB PROBLEM SOLUTIONING :');

		$mform->addElement('editor','lab_problem_solution', '', null, $editoroptions);
        $mform->setType('lab_problem_solution', PARAM_RAW);

		$mform->addElement('header','head4', 'KEY THEORETICAL PRINCIPLES FOR LAB PROBLEM SOLUTIONING :');

		$mform->addElement('editor','lab_problem_principle', '', null, $editoroptions);
        $mform->setType('lab_problem_theory_principle', PARAM_RAW);

		$mform->addElement('header','head5', 'KEY DESIGN/CODING PRINCIPLES FOR LAB PROBLEM SOLUTIONING :');

		$mform->addElement('editor','lab_problem_design_principle', '', null, $editoroptions);
        $mform->setType('lab_problem_design_principle', PARAM_RAW);

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

