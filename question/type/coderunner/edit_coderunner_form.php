<?php
// This file is part of CodeRunner - http://coderunner.org.nz/
//
// CodeRunner is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// CodeRunner is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with CodeRunner.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

/*
 * Defines the editing form for the coderunner question type.
 *
 * @package 	questionbank
 * @subpackage 	questiontypes
 * @copyright 	&copy; 2013 Richard Lobb
 * @author 		Richard Lobb richard.lobb@canterbury.ac.nz
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

require_once($CFG->dirroot . '/question/type/coderunner/questiontype.php');
require_once($CFG->dirroot . '/question/type/coderunner/question.php');

use qtype_coderunner\constants;

/**
 * CodeRunner editing form definition.
 */
class qtype_coderunner_edit_form extends question_edit_form {

    const NUM_TESTCASES_START = 5;  // Num empty test cases with new questions.
    const NUM_TESTCASES_ADD = 3;    // Extra empty test cases to add.
    const DEFAULT_NUM_ROWS = 18;    // Answer box rows.
    const DEFAULT_NUM_COLS = 100;   // Answer box columns.
    const TEMPLATE_PARAM_ROWS = 5;  // The number of rows of the template parameter field.
    const RESULT_COLUMNS_SIZE = 80; // The size of the resultcolumns field.

    public function qtype() {
        return 'coderunner';
    }


    private static function author_edit_keys() {
        // A list of all the language strings required by authorform.js
        return array('coderunner_question_type', 'confirm_proceed', 'template_changed',
            'info_unavailable', 'proceed_at_own_risk', 'error_loading_prototype',
            'ajax_error', 'prototype_load_failure', 'prototype_error',
            'question_type_changed');
    }

    // Define the CodeRunner question edit form.
    protected function definition() {
        global $PAGE;

        $mform = $this->_form;
        $this->make_error_div($mform);
        $this->make_questiontype_panel($mform);
        $this->make_questiontype_help_panel($mform);
        $this->make_customisation_panel($mform);
        $this->make_advanced_customisation_panel($mform);
        qtype_coderunner_util::load_ace();

        $PAGE->requires->js_call_amd('qtype_coderunner/textareas', 'setupAllTAs');

        // Define the parameters required by the JS initEditForm amd module
        $strings = array();
        foreach (self::author_edit_keys() as $key) {
            $strings[$key] = get_string($key, 'qtype_coderunner');
        }
        if (!empty($this->question->options->mergedtemplateparams)) {
            $mergedtemplateparams = $this->question->options->mergedtemplateparams;
        } else {
            $mergedtemplateparams = '';
        }

        $PAGE->requires->js_call_amd('qtype_coderunner/authorform', 'initEditForm',
                array($strings, $mergedtemplateparams));

        parent::definition($mform);  // The superclass adds the "General" stuff.
    }


    // Defines the bit of the CodeRunner question edit form after the "General"
    // section and before the footer stuff.
    public function definition_inner($mform) {
        $this->add_sample_answer_field($mform);
        $this->add_preload_answer_field($mform);

        if (isset($this->question->options->testcases)) {
            $numtestcases = count($this->question->options->testcases);
        } else {
            $numtestcases = self::NUM_TESTCASES_START;
        }

        // Confusion alert! A call to $mform->setDefault("mark[$i]", '1.0') looks
        // plausible and works to set the empty-form default, but it then
        // overrides (rather than is overridden by) the actual value. The same
        // thing happens with $repeatedoptions['mark']['default'] = 1.000 in
        // get_per_testcase_fields (q.v.).
        // I don't understand this (but see 'Evil hack alert' in the baseclass).
        // MY EVIL HACK ALERT (OLD: probably out of date ) -- setting just $numTestcases default values
        // fails when more test cases are added on the fly. So I've set up
        // enough defaults to handle 5 successive adding of more test cases.
        // I believe this is a bug in the underlying Moodle question type, not
        // mine, but ... how to be sure?
        $mform->setDefault('mark', array_fill(0, $numtestcases + 5 * self::NUM_TESTCASES_ADD, 1.0));
        $ordering = array();
        for ($i = 0; $i < $numtestcases + 5 * self::NUM_TESTCASES_ADD; $i++) {
            $ordering[] = 10 * $i;
        }
        $mform->setDefault('ordering', $ordering);

        $this->add_per_testcase_fields($mform, get_string('testcase', 'qtype_coderunner', "{no}"),
                $numtestcases);

        // Add the option to attach runtime support files, all of which are
        // copied into the working directory when the expanded template is
        // executed.The file context is that of the current course.
        $options = $this->fileoptions;
        $options['subdirs'] = false;
        $mform->addElement('header', 'fileheader',
                get_string('fileheader', 'qtype_coderunner'));
        $mform->addElement('filemanager', 'datafiles',
                get_string('datafiles', 'qtype_coderunner'), null,
                $options);
        $mform->addHelpButton('datafiles', 'datafiles', 'qtype_coderunner');
    }

    /**
     * Add a field for a sample answer to this problem (optional)
     * @param object $mform the form being built
     */
    protected function add_sample_answer_field($mform) {
        $mform->addElement('header', 'answerhdr',
                    get_string('answer', 'qtype_coderunner'), '');
        $mform->setExpanded('answerhdr', 1);
        $mform->addElement('textarea', 'answer',
                get_string('answer', 'qtype_coderunner'),
                array('rows' => 9, 'class' => 'answer edit_code'));
        $mform->addElement('advcheckbox', 'validateonsave', null,
                get_string('validateonsave', 'qtype_coderunner'));
        $mform->setDefault('validateonsave', false);
        $mform->addHelpButton('answer', 'answer', 'qtype_coderunner');
    }

    /**
     * Add a field for a text to be preloaded into the answer box.
     * @param object $mform the form being built
     */
    protected function add_preload_answer_field($mform) {
        $mform->addElement('header', 'answerpreloadhdr',
                    get_string('answerpreload', 'qtype_coderunner'), '');
        $mform->setExpanded('answerpreloadhdr', 0);
        $mform->addElement('textarea', 'answerpreload',
                get_string('answerpreload', 'qtype_coderunner'),
                array('rows' => 5, 'class' => 'preloadanswer edit_code'));
        $mform->addHelpButton('answerpreload', 'answerpreload', 'qtype_coderunner');
    }

    /*
     * Add a set of form fields, obtained from get_per_test_fields, to the form,
     * one for each existing testcase, with some blanks for some new ones
     * This overrides the base-case version because we're dealing with test
     * cases, not answers.
     * @param object $mform the form being built.
     * @param $label the label to use for each option.
     * @param $gradeoptions the possible grades for each answer.
     * @param $minoptions the minimum number of testcase blanks to display.
     *      Default QUESTION_NUMANS_START.
     * @param $addoptions the number of testcase blanks to add. Default QUESTION_NUMANS_ADD.
     */
    protected function add_per_testcase_fields($mform, $label, $numtestcases) {
        $mform->addElement('header', 'testcasehdr',
                    get_string('testcases', 'qtype_coderunner'), '');
        $mform->setExpanded('testcasehdr', 1);
        $repeatedoptions = array();
        $repeated = $this->get_per_testcase_fields($mform, $label, $repeatedoptions);
        $this->repeat_elements($repeated, $numtestcases, $repeatedoptions,
                'numtestcases', 'addanswers', QUESTION_NUMANS_ADD,
                $this->get_more_choices_string(), true);
        $n = $numtestcases + QUESTION_NUMANS_ADD;
        for ($i = 0; $i < $n; $i++) {
            $mform->disabledIf("mark[$i]", 'allornothing', 'checked');
        }
    }


    /*
     *  A rewritten version of get_per_answer_fields specific to test cases.
     */
    public function get_per_testcase_fields($mform, $label, &$repeatedoptions) {
        $repeated = array();
        $repeated[] = $mform->createElement('textarea', 'testcode',
                $label,
                array('rows' => 3, 'class' => 'testcaseexpression edit_code'));
        $repeated[] = $mform->createElement('textarea', 'stdin',
                get_string('stdin', 'qtype_coderunner'),
                array('rows' => 3, 'class' => 'testcasestdin edit_code'));
        $repeated[] = $mform->createElement('textarea', 'expected',
                get_string('expected', 'qtype_coderunner'),
                array('rows' => 3, 'class' => 'testcaseresult edit_code'));

        $repeated[] = $mform->createElement('textarea', 'extra',
                get_string('extra', 'qtype_coderunner'),
                array('rows' => 3, 'class' => 'testcaseresult edit_code'));
        $group[] = $mform->createElement('checkbox', 'useasexample', null,
                get_string('useasexample', 'qtype_coderunner'));

        $options = array();
        foreach ($this->displayoptions() as $opt) {
            $options[$opt] = get_string($opt, 'qtype_coderunner');
        }

        $group[] = $mform->createElement('select', 'display',
                        get_string('display', 'qtype_coderunner'), $options);
        $group[] = $mform->createElement('checkbox', 'hiderestiffail', null,
                        get_string('hiderestiffail', 'qtype_coderunner'));
        $group[] = $mform->createElement('text', 'mark',
                get_string('mark', 'qtype_coderunner'),
                array('size' => 5, 'class' => 'testcasemark'));
        $group[] = $mform->createElement('text', 'ordering',
                get_string('ordering', 'qtype_coderunner'),
                array('size' => 3, 'class' => 'testcaseordering'));

        $repeated[] = $mform->createElement('group', 'testcasecontrols',
                        get_string('testcasecontrols', 'qtype_coderunner'),
                        $group, null, false);

        $typevalues = array(
            constants::TESTTYPE_NORMAL   => get_string('testtype_normal',   'qtype_coderunner'),
            constants::TESTTYPE_PRECHECK => get_string('testtype_precheck', 'qtype_coderunner'),
            constants::TESTTYPE_BOTH     => get_string('testtype_both',     'qtype_coderunner'),
        );

        $repeated[] = $mform->createElement('select', 'testtype',
                get_string('testtype', 'qtype_coderunner'),
                $typevalues,
                array('class' => 'testtype'));

        $repeatedoptions['expected']['type'] = PARAM_RAW;
        $repeatedoptions['testcode']['type'] = PARAM_RAW;
        $repeatedoptions['stdin']['type'] = PARAM_RAW;
        $repeatedoptions['extra']['type'] = PARAM_RAW;
        $repeatedoptions['mark']['type'] = PARAM_FLOAT;
        $repeatedoptions['ordering']['type'] = PARAM_INT;
        $repeatedoptions['testtype']['type'] = PARAM_RAW;

        foreach (array('testcode', 'stdin', 'expected', 'extra', 'testcasecontrols', 'testtype') as $field) {
            $repeatedoptions[$field]['helpbutton'] = array($field, 'qtype_coderunner');
        }

        // Here I expected to be able to use: $repeatedoptions['mark']['default'] = 1.000
        // but it doesn't work. See "Confusion alert" in definition_inner.

        return $repeated;
    }


    // A list of the allowed values of the DB 'display' field for each testcase.
    protected function displayoptions() {
        return array('SHOW', 'HIDE', 'HIDE_IF_FAIL', 'HIDE_IF_SUCCEED');
    }


    public function data_preprocessing($question) {
        // Preprocess the question data to be loaded into the form. Called by set_data after
        // standard stuff all loaded.
        global $COURSE;

        $question->missingprototypemessage = ''; // The optimistic assumption
        if (isset($question->options->testcases)) { // Reloading a saved question?

            // Firstly check if we're editing a question with a missing prototype
            // Set missing_prototype if so.
            $q = $this->make_question_from_form_data($question);
            if ($q->prototype === null) {
                $question->missingprototypemessage = get_string(
                        'missingprototype', 'qtype_coderunner', array('crtype' => $question->coderunnertype));
            }

            // Next flatten all the question->options down into the question itself.
            $question->testcode = array();
            $question->expected = array();
            $question->useasexample = array();
            $question->display = array();
            $question->extra = array();
            $question->hiderestifail = array();

            foreach ($question->options->testcases as $tc) {
                $question->testcode[] = $this->newline_hack($tc->testcode);
                $question->testtype[] = $tc->testtype;
                $question->stdin[] = $this->newline_hack($tc->stdin);
                $question->expected[] = $this->newline_hack($tc->expected);
                $question->extra[] = $this->newline_hack($tc->extra);
                $question->useasexample[] = $tc->useasexample;
                $question->display[] = $tc->display;
                $question->hiderestiffail[] = $tc->hiderestiffail;
                $question->mark[] = sprintf("%.3f", $tc->mark);
            }

            // The customise field isn't listed as an extra-question-field so also
            // needs to be copied down from the options here.
            $question->customise = $question->options->customise;

            // Save the prototypetype so can see if it changed on post-back.
            $question->saved_prototype_type = $question->prototypetype;
            $question->courseid = $COURSE->id;

            // Load the type-name if this is a prototype, else make it blank.
            if ($question->prototypetype != 0) {
                $question->typename = $question->coderunnertype;
            } else {
                $question->typename = '';
            }

            // Convert raw newline chars in testsplitterre into 2-char form
            // so they can be edited in a one-line entry field.
            if (isset($question->testsplitterre)) {
                $question->testsplitterre = str_replace("\n", '\n', $question->testsplitterre);
            }

            // Legacy questions may have a question.penalty but no penalty regime.
            // Dummy up a penalty regime from the question.penalty in such cases.
            if (empty($question->penaltyregime)) {
                if (empty($question->penalty) || $question->penalty == 0) {
                    $question->penaltyregime = '0';
                } else {
                    if (intval(100 * $question->penalty) == 100 * $question->penalty) {
                        $decdigits = 0;
                    } else {
                        $decdigits = 1;  // For nasty fractions like 0.33333333.
                    }
                    $penaltypercent = number_format($question->penalty * 100, $decdigits);
                    $penaltypercent2 = number_format($question->penalty * 200, $decdigits);
                    $question->penaltyregime = $penaltypercent . ', ' . $penaltypercent2 . ', ...';
                }
            }
        } else {
            // This is a new question.
            $question->penaltyregime = get_config('qtype_coderunner', 'default_penalty_regime');
        }

        $draftid = file_get_submitted_draft_itemid('datafiles');
        $options = $this->fileoptions;
        $options['subdirs'] = false;

        file_prepare_draft_area($draftid, $this->context->id,
                'qtype_coderunner', 'datafile',
                empty($question->id) ? null : (int) $question->id,
                $options);
        $question->datafiles = $draftid; // File manager needs this (and we need it when saving).
        return $question;
    }


    // A horrible horrible hack for a horrible horrible browser "feature".
    // Inserts a newline at the start of a text string that's going to be
    // displayed at the start of a <textarea> element, because all browsers
    // strip a leading newline. If there's one there, we need to keep it, so
    // the extra one ensures we do. If there isn't one there, this one gets
    // ignored anyway.
    private function newline_hack($s) {
        return "\n" . $s;
    }


    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        if ($data['coderunnertype'] == 'Undefined') {
            $errors['coderunner_type_group'] = get_string('questiontype_required', 'qtype_coderunner');
        }
        if ($data['cputimelimitsecs'] != '' &&
             (!ctype_digit($data['cputimelimitsecs']) || intval($data['cputimelimitsecs']) <= 0)) {
            $errors['sandboxcontrols'] = get_string('badcputime', 'qtype_coderunner');
        }
        if ($data['memlimitmb'] != '' &&
             (!ctype_digit($data['memlimitmb']) || intval($data['memlimitmb']) < 0)) {
            $errors['sandboxcontrols'] = get_string('badmemlimit', 'qtype_coderunner');
        }

        if ($data['precheck'] == constants::PRECHECK_EXAMPLES && $this->num_examples($data) === 0) {
            $errors['coderunner_precheck_group'] = get_string('precheckingemptyset', 'qtype_coderunner');
        }

        if ($data['sandboxparams'] != '' &&
                json_decode($data['sandboxparams']) === null) {
            $errors['sandboxcontrols'] = get_string('badsandboxparams', 'qtype_coderunner');
        }

        $template_status = $this->validate_template_params($data);
        if ($template_status['error']) {
            $errors['templateparams'] = $template_status['error'];
        }

        if ($data['prototypetype'] == 0 && ($data['grader'] !== 'TemplateGrader'
                || $data['iscombinatortemplate'] === false)) {
            // Unless it's a prototype or uses a combinator-template grader,
            // it needs at least one testcase.
            $testcaseerrors = $this->validate_test_cases($data);
            $errors = array_merge($errors, $testcaseerrors);
        }

        if ($data['iscombinatortemplate'] && empty($data['testsplitterre'])) {
            $errors['templatecontrols'] = get_string('bad_empty_splitter', 'qtype_coderunner');
        }

        if ($data['prototypetype'] == 2 && ($data['saved_prototype_type'] != 2 ||
                   $data['typename'] != $data['coderunnertype'])) {
            // User-defined prototype, either newly created or undergoing a name change.
            $typename = trim($data['typename']);
            if ($typename === '') {
                $errors['prototypecontrols'] = get_string('empty_new_prototype_name', 'qtype_coderunner');
            } else if (!$this->is_valid_new_type($typename)) {
                $errors['prototypecontrols'] = get_string('bad_new_prototype_name', 'qtype_coderunner');
            }
        }

        if (trim($data['penaltyregime']) == '') {
            $errors['markinggroup'] = get_string('emptypenaltyregime', 'qtype_coderunner');
        } else {
            $bits = explode(',', $data['penaltyregime']);
            $n = count($bits);
            for ($i = 0; $i < $n; $i++) {
                $bit = trim($bits[$i]);
                if ($bit === '...') {
                    if ($i != $n - 1 || $n < 3 || floatval($bits[$i - 1]) <= floatval($bits[$i - 2])) {
                        $errors['markinggroup'] = get_string('bad_dotdotdot', 'qtype_coderunner');
                    }
                }
            }
        }

        $resultcolumnsjson = trim($data['resultcolumns']);
        if ($resultcolumnsjson !== '') {
            $resultcolumns = json_decode($resultcolumnsjson);
            if ($resultcolumns === null) {
                $errors['resultcolumns'] = get_string('resultcolumnsnotjson', 'qtype_coderunner');
            } else if (!is_array($resultcolumns)) {
                $errors['resultcolumns'] = get_string('resultcolumnsnotlist', 'qtype_coderunner');
            } else {
                foreach ($resultcolumns as $col) {
                    if (!is_array($col) || count($col) < 2) {
                        $errors['resultcolumns'] = get_string('resultcolumnspecbad', 'qtype_coderunner');
                        break;
                    }
                    foreach ($col as $el) {
                        if (!is_string($el)) {
                            $errors['resultcolumns'] = get_string('resultcolumnspecbad', 'qtype_coderunner');
                            break;
                        }
                    }
                }
            }
        }

        if (count($errors) == 0 && $template_status['istwigged']) {
            $errors = $this->validate_twigables($data, $template_status['renderedparams']);
        }

        if (count($errors) == 0 && !empty($data['validateonsave'])) {
            $testresult = $this->validate_sample_answer($data);
            if ($testresult) {
                $errors['answer'] = $testresult;
            }
        }

        $acelangs = trim($data['acelang']);
        if ($acelangs !== '' && strpos($acelangs, ',') !== false) {
            $parsedlangs = qtype_coderunner_util::extract_languages($acelangs);
            if ($parsedlangs === false) {
                $errors['languages'] = get_string('multipledefaults', 'qtype_coderunner');
            } else if (count($parsedlangs[0]) === 0) {
                $errors['languages'] = get_string('badacelangstring', 'qtype_coderunner');
            }
        }

        return $errors;
    }

    // FUNCTIONS TO BUILD PARTS OF THE MAIN FORM
    // =========================================.

    // Create an empty div with id id_qtype_coderunner_error_div for use by
    // JavaScript error handling code.
    private function make_error_div($mform) {
        $mform->addElement('html', "<div id='id_qtype_coderunner_error_div' class='qtype_coderunner_error_message'></div>");
    }

    // Add to the supplied $mform the panel "Coderunner question type".
    private function make_questiontype_panel($mform) {
        list($languages, $types) = $this->get_languages_and_types();

        $mform->addElement('header', 'questiontypeheader', get_string('type_header', 'qtype_coderunner'));
        // Insert the (possible) missing prototype message as a hidden field. JavaScript
        // will be used to show it if non-empty
        $mform->addElement('hidden', 'missingprototypemessage', '',
                array('id' => 'id_missing_prototype', 'class'=>'missingprototypeerror'));
        $mform->setType('missingprototypemessage', PARAM_RAW);

        // The Question Type controls (a group with just a single member).
        $typeselectorelements = array();
        $expandedtypes = array_merge(array('Undefined' => 'Undefined'), $types);
        $typeselectorelements[] = $mform->createElement('select', 'coderunnertype',
                null, $expandedtypes);
        $mform->addElement('group', 'coderunner_type_group',
                get_string('coderunnertype', 'qtype_coderunner'), $typeselectorelements, null, false);
        $mform->addHelpButton('coderunner_type_group', 'coderunnertype', 'qtype_coderunner');

        // Customisation checkboxes.
        $typeselectorcheckboxes = array();
        $typeselectorcheckboxes[] = $mform->createElement('advcheckbox', 'customise', null,
                get_string('customise', 'qtype_coderunner'));
        $typeselectorcheckboxes[] = $mform->createElement('advcheckbox', 'showsource', null,
                get_string('showsource', 'qtype_coderunner'));
        $mform->setDefault('showsource', false);
        $mform->addElement('group', 'coderunner_type_checkboxes',
                get_string('questioncheckboxes', 'qtype_coderunner'), $typeselectorcheckboxes, null, false);
        $mform->addHelpButton('coderunner_type_checkboxes', 'questioncheckboxes', 'qtype_coderunner');

        // Answerbox controls.
        $answerboxelements = array();
        $answerboxelements[] = $mform->createElement('text', 'answerboxlines',
                get_string('answerboxlines', 'qtype_coderunner'),
                array('size' => 3, 'class' => 'coderunner_answerbox_size'));
        $mform->setType('answerboxlines', PARAM_INT);
        $mform->setDefault('answerboxlines', self::DEFAULT_NUM_ROWS);
        $mform->addElement('group', 'answerbox_group', get_string('answerbox_group', 'qtype_coderunner'),
                $answerboxelements, null, false);
        $mform->addHelpButton('answerbox_group', 'answerbox_group', 'qtype_coderunner');

        // Precheck control (currently a group with only one element).
        $precheckelements = array();
        $precheckvalues = array(
            constants::PRECHECK_DISABLED => get_string('precheck_disabled', 'qtype_coderunner'),
            constants::PRECHECK_EMPTY    => get_string('precheck_empty', 'qtype_coderunner'),
            constants::PRECHECK_EXAMPLES => get_string('precheck_examples', 'qtype_coderunner'),
            constants::PRECHECK_SELECTED => get_string('precheck_selected', 'qtype_coderunner'),
            constants::PRECHECK_ALL      => get_string('precheck_all', 'qtype_coderunner')
        );
        $precheckelements[] = $mform->createElement('select', 'precheck', null, $precheckvalues);
        $mform->addElement('group', 'coderunner_precheck_group',
                get_string('precheck', 'qtype_coderunner'), $precheckelements, null, false);
        $mform->addHelpButton('coderunner_precheck_group', 'precheck', 'qtype_coderunner');

        // Marking controls.
        $markingelements = array();
        $markingelements[] = $mform->createElement('advcheckbox', 'allornothing', null,
                get_string('allornothing', 'qtype_coderunner'));
        $markingelements[] = $mform->CreateElement('text', 'penaltyregime',
            get_string('penaltyregimelabel', 'qtype_coderunner'),
            array('size' => 20));
        $mform->addElement('group', 'markinggroup', get_string('markinggroup', 'qtype_coderunner'),
                $markingelements, null, false);
        $mform->setDefault('allornothing', true);
        $mform->setType('penaltyregime', PARAM_RAW);
        $mform->addHelpButton('markinggroup', 'markinggroup', 'qtype_coderunner');

        // Template params.
        $mform->addElement('textarea', 'templateparams',
            get_string('templateparams', 'qtype_coderunner'),
            array('rows' => self::TEMPLATE_PARAM_ROWS,
                  'class' => 'edit_code'));
        $mform->setType('templateparams', PARAM_RAW);
        $mform->addHelpButton('templateparams', 'templateparams', 'qtype_coderunner');

        // Twig controls
        $twigelements = array();
        $twigelements[] = $mform->createElement('advcheckbox', 'hoisttemplateparams', null,
                get_string('hoisttemplateparams', 'qtype_coderunner'));
        $twigelements[] = $mform->createElement('advcheckbox', 'twigall', null,
                get_string('twigall', 'qtype_coderunner'));
        $mform->addElement('group', 'twigcontrols', get_string('twigcontrols', 'qtype_coderunner'),
                $twigelements, null, false);
        $mform->setDefault('twigall', false);
        // Although hoisttemplateparams defaults to true in the database,
        // it defaults to true in this form. This ensures that legacy questions are
        // not affected, while new questions default to true.
        $mform->setDefault('hoisttemplateparams', true);
        $mform->addHelpButton('twigcontrols', 'twigcontrols', 'qtype_coderunner');
    }


    // Add to the supplied $mform the question-type help panel.
    // This displays the text of the currently-selected prototype.
    private function make_questiontype_help_panel($mform) {
        $mform->addElement('header', 'questiontypehelpheader',
                get_string('questiontypedetails', 'qtype_coderunner'));
        $nodetailsavailable = '<span id="qtype-help">' . get_string('nodetailsavailable', 'qtype_coderunner') . '</span>';
        $mform->addElement('html', $nodetailsavailable);
    }

    // Add to the supplied $mform the Customisation Panel
    // The panel is hidden by default but exposed when the user clicks
    // the 'Customise' checkbox in the question-type panel.
    private function make_customisation_panel($mform) {
        // The following fields are used to customise a question by overriding
        // values from the base question type. All are hidden
        // unless the 'customise' checkbox is checked.

        $mform->addElement('header', 'customisationheader',
                get_string('customisation', 'qtype_coderunner'));

        $mform->addElement('textarea', 'template',
                get_string('template', 'qtype_coderunner'),
                array('rows'  => 8,
                      'class' => 'template edit_code',
                      'name'  => 'template'));
        $mform->addHelpButton('template', 'template', 'qtype_coderunner');

        $templatecontrols = array();
        $templatecontrols[] = $mform->createElement('advcheckbox', 'iscombinatortemplate', null,
                get_string('iscombinatortemplate', 'qtype_coderunner'));
        $templatecontrols[] = $mform->createElement('advcheckbox', 'allowmultiplestdins', null,
                get_string('allowmultiplestdins', 'qtype_coderunner'));

        $templatecontrols[] = $mform->createElement('text', 'testsplitterre',
                get_string('testsplitterre', 'qtype_coderunner'),
                array('size' => 45));
        $mform->setType('testsplitterre', PARAM_RAW);
        $mform->addElement('group', 'templatecontrols', get_string('templatecontrols', 'qtype_coderunner'),
                $templatecontrols, null, false);
        $mform->addHelpButton('templatecontrols', 'templatecontrols', 'qtype_coderunner');

        $gradingcontrols = array();
        $gradertypes = array('EqualityGrader' => get_string('equalitygrader', 'qtype_coderunner'),
                'NearEqualityGrader' => get_string('nearequalitygrader', 'qtype_coderunner'),
                'RegexGrader'    => get_string('regexgrader', 'qtype_coderunner'),
                'TemplateGrader' => get_string('templategrader', 'qtype_coderunner'));
        $gradingcontrols[] = $mform->createElement('select', 'grader', null, $gradertypes);
        $mform->addElement('group', 'gradingcontrols',
                get_string('grading', 'qtype_coderunner'), $gradingcontrols,
                null, false);
        $mform->addHelpButton('gradingcontrols', 'gradingcontrols', 'qtype_coderunner');

        $mform->addElement('text', 'resultcolumns',
            get_string('resultcolumns', 'qtype_coderunner'),
            array('size' => self::RESULT_COLUMNS_SIZE));
        $mform->setType('resultcolumns', PARAM_RAW);
        $mform->addHelpButton('resultcolumns', 'resultcolumns', 'qtype_coderunner');

        $uicontrols = array();
        $uitypes = $this->get_ui_plugins();

        $uicontrols[] = $mform->createElement('select', 'uiplugin',
                get_string('student_answer', 'qtype_coderunner'), $uitypes);
        $mform->setDefault('uiplugin', 'ace');
        $uicontrols[] = $mform->createElement('advcheckbox', 'useace', null,
                get_string('useace', 'qtype_coderunner'));
        $mform->setDefault('useace', true);
        $mform->addElement('group', 'uicontrols',
                get_string('uicontrols', 'qtype_coderunner'), $uicontrols,
                null, false);
        $mform->addHelpButton('uicontrols', 'uicontrols', 'qtype_coderunner');

        $mform->setExpanded('customisationheader');  // Although expanded it's hidden until JavaScript unhides it .
    }


    // Get a list of all available UI plugins, namely all files of the form
    // ui_pluginname.js in the amd/src directory.
    // Returns an associative array with a uiname => uiname entry for each
    // available ui plugin.
    private function get_ui_plugins() {
        global $CFG;
        $uiplugins = array('None' => 'None');
        $files = scandir($CFG->dirroot . '/question/type/coderunner/amd/src');
        foreach ($files as $file) {
            if (substr($file, 0, 3) === 'ui_' && substr($file, -3) === '.js') {
                $uiname = substr($file, 3, -3);
                $uiplugins[$uiname] = ucfirst($uiname);
            }
        }
        return $uiplugins;
    }


    // Make the advanced customisation panel, also hidden until the user
    // customises the question. The fields in this part of the form are much more
    // advanced and not recommended for most users.
    private function make_advanced_customisation_panel($mform) {
        $mform->addElement('header', 'advancedcustomisationheader',
                get_string('advanced_customisation', 'qtype_coderunner'));

        $prototypecontrols = array();

        $prototypeselect = $mform->createElement('select', 'prototypetype',
                get_string('prototypeQ', 'qtype_coderunner'));
        $prototypeselect->addOption('No', '0');
        $prototypeselect->addOption('Yes (built-in)', '1', array('disabled' => 'disabled'));
        $prototypeselect->addOption('Yes (user defined)', '2');
        $prototypecontrols[] = $prototypeselect;
        $prototypecontrols[] = $mform->createElement('text', 'typename',
                get_string('typename', 'qtype_coderunner'), array('size' => 30));
        $mform->addElement('group', 'prototypecontrols',
                get_string('prototypecontrols', 'qtype_coderunner'),
                $prototypecontrols, null, false);
        $mform->setDefault('is_prototype', false);
        $mform->setType('typename', PARAM_RAW_TRIMMED);
        $mform->addElement('hidden', 'saved_prototype_type');
        $mform->setType('saved_prototype_type', PARAM_RAW_TRIMMED);
        $mform->addHelpButton('prototypecontrols', 'prototypecontrols', 'qtype_coderunner');

        $sandboxcontrols = array();

        $sandboxes = array('DEFAULT' => 'DEFAULT');
        foreach (qtype_coderunner_sandbox::available_sandboxes() as $ext => $class) {
            $sandboxes[$ext] = $ext;
        }

        $sandboxcontrols[] = $mform->createElement('select', 'sandbox', null, $sandboxes);

        $sandboxcontrols[] = $mform->createElement('text', 'cputimelimitsecs',
                get_string('cputime', 'qtype_coderunner'), array('size' => 3));
        $sandboxcontrols[] = $mform->createElement('text', 'memlimitmb',
                get_string('memorylimit', 'qtype_coderunner'), array('size' => 5));
        $sandboxcontrols[] = $mform->createElement('text', 'sandboxparams',
                get_string('sandboxparams', 'qtype_coderunner'), array('size' => 15));
        $mform->addElement('group', 'sandboxcontrols',
                get_string('sandboxcontrols', 'qtype_coderunner'),
                $sandboxcontrols, null, false);
        $mform->setType('cputimelimitsecs', PARAM_RAW);
        $mform->setType('memlimitmb', PARAM_RAW);
        $mform->setType('sandboxparams', PARAM_RAW);
        $mform->addHelpButton('sandboxcontrols', 'sandboxcontrols', 'qtype_coderunner');

        $languages = array();
        $languages[]  = $mform->createElement('text', 'language',
            get_string('language', 'qtype_coderunner'),
            array('size' => 10));
        $mform->setType('language', PARAM_RAW_TRIMMED);
        $languages[]  = $mform->createElement('text', 'acelang',
            get_string('ace-language', 'qtype_coderunner'),
            array('size' => 20));
        $mform->setType('acelang', PARAM_RAW_TRIMMED);
        $mform->addElement('group', 'languages',
            get_string('languages', 'qtype_coderunner'),
            $languages, null, false);
        $mform->addHelpButton('languages', 'languages', 'qtype_coderunner');

        // IMPORTANT: authorform.js has to set the initial enabled/disabled
        // status of the testsplitterre and allowmultiplestdins elements
        // after loading a new question type as the following code apparently
        // sets up event handlers only for clicks on the iscombinatortemplate
        // checkbox.
        $mform->disabledIf('typename', 'prototypetype', 'neq', '2');
        $mform->disabledIf('testsplitterre', 'iscombinatortemplate', 'eq', 0);
        $mform->disabledIf('allowmultiplestdins', 'iscombinatortemplate', 'eq', 0);
    }

    // UTILITY FUNCTIONS.
    // =================.

    // True iff the given name is valid for a new type, i.e., it's not in use
    // in the current context (Currently only a single global context is
    // implemented).
    private function is_valid_new_type($typename) {
        list($langs, $types) = $this->get_languages_and_types();
        return !array_key_exists($typename, $types);
    }


    /**
     * Return a count of the number of test cases set as examples.
     * @param array $data data from the form
     */
    private function num_examples($data) {
        return isset($data['useasexample']) ? count($data['useasexample']) : 0;
    }

    private function get_languages_and_types() {
        // Return two arrays (language => language_upper_case) and (type => subtype) of
        // all the coderunner question types available in the current course
        // context.
        // The subtype is the suffix of the type in the database,
        // e.g. for java_method it is 'method'. The language is the bit before
        // the underscore, and language_upper_case is a capitalised version,
        // e.g. Java for java. For question types without a
        // subtype the word 'Default' is used.

        $records = qtype_coderunner::get_all_prototypes();
        $types = array();
        foreach ($records as $row) {
            if (($pos = strpos($row->coderunnertype, '_')) !== false) {
                $subtype = substr($row->coderunnertype, $pos + 1);
                $language = substr($row->coderunnertype, 0, $pos);
            } else {
                $subtype = 'Default';
                $language = $row->coderunnertype;
            }
            $types[$row->coderunnertype] = $row->coderunnertype;
            $languages[$language] = ucwords($language);
        }
        asort($types);
        asort($languages);
        return array($languages, $types);
    }


    // Validate the test cases.
    private function validate_test_cases($data) {
        $errors = array(); // Return value.
        $testcodes = $data['testcode'];
        $stdins = $data['stdin'];
        $expecteds = $data['expected'];
        $marks = $data['mark'];
        $count = 0;
        $numnonemptytests = 0;
        $num = max(count($testcodes), count($stdins), count($expecteds));
        for ($i = 0; $i < $num; $i++) {
            $testcode = trim($testcodes[$i]);
            if ($testcode != '') {
                $numnonemptytests++;
            }
            $stdin = trim($stdins[$i]);
            $expected = trim($expecteds[$i]);
            if ($testcode !== '' || $stdin != '' || $expected !== '') {
                $count++;
                $mark = trim($marks[$i]);
                if ($mark != '') {
                    if (!is_numeric($mark)) {
                        $errors["testcode[$i]"] = get_string('nonnumericmark', 'qtype_coderunner');
                    } else if (floatval($mark) <= 0) {
                        $errors["testcode[$i]"] = get_string('negativeorzeromark', 'qtype_coderunner');
                    }
                }
            }
        }

        if ($count == 0) {
            $errors["testcode[0]"] = get_string('atleastonetest', 'qtype_coderunner');
        } else if ($numnonemptytests != 0 && $numnonemptytests != $count) {
            $errors["testcode[0]"] = get_string('allornone', 'qtype_coderunner');
        }
        return $errors;
    }


    // Check the templateparameters value, if given. Return value is
    // an associative array with an error message 'error', a boolean
    // 'istwigged' and a string 'renderedparams'.
    // Error is the empty string if the template parameters are
    // OK. istwigged is true if twigging the template parameters changed them.
    // 'renderedparams' is the result of twig expanding the params.
    private function validate_template_params($data) {
        global $USER;
        $errormessage = '';
        $istwiggedparams = false;
        $renderedparams = '';
        if ($data['templateparams'] != '') {
            // Try Twigging the template params to make sure they parse
            $ok = true;
            try {
                $twig = qtype_coderunner_twig::get_twig_environment(array('strict_variables' => true));
                $twigparams = array('STUDENT' => new qtype_coderunner_student($USER));
                $renderedparams = $twig->render($data['templateparams'], $twigparams);
                if (str_replace($renderedparams, "\r", '') !==
                        str_replace($data['templateparams'], "\r", '')) {
                    // Twig loses '\r' chars, so must strip them before checking.
                    $istwiggedparams = true;
                }
            } catch (Exception $ex) {
                $errormessage = $ex->getMessage();
                $ok = false;
            }
            if ($ok) {
                $decoded = json_decode($renderedparams);
                if ($decoded === null) {
                    if ($istwiggedparams) {
                        $badjsonhtml = str_replace("\n", '<br>', $renderedparams);
                        $errormessage = get_string('badtemplateparamsaftertwig',
                                'qtype_coderunner', $badjsonhtml);
                    } else {
                        $errormessage = get_string('badtemplateparams', 'qtype_coderunner');
                    }
                }
            }

        }
        return array('error' => $errormessage,
                    'istwigged' => $istwiggedparams,
                    'renderedparams' => $renderedparams);
    }

    // If the template parameters contain twig code, in which case the
    // other question fields will need twig expansion, check for twig errors
    // in all other fields. Return value is an associative array mapping from
    // form fields to error messages.
    private function validate_twigables($data, $renderedparams) {
        $errors = array();
        if (!empty($renderedparams)) {
            $parameters = json_decode($renderedparams, true);
        } else {
            $parameters = array();
        }
        $twig = qtype_coderunner_twig::get_twig_environment(array('strict_variables' => true));

        // Try twig expanding everything (see question::twig_all).
        foreach (['questiontext', 'answer', 'answerpreload'] as $field) {
            $text = $data[$field];
            if (is_array($text)) {
                $text = $text['text'];
            }
            try {
                $twig->render($text, $parameters);
            } catch (Twig_Error $ex) {
                $errors[$field] = get_string('twigerror', 'qtype_coderunner',
                        $ex->getMessage());
            }
        }

        // Now all test cases
        if (!empty($data['testcode'])) {
            $num = max(count($data['testcode']), count($data['stdin']),
                    count($data['expected']), count($data['extra']));

            for ($i = 0; $i < $num; $i++) {
                foreach (['testcode', 'stdin', 'expected', 'extra'] as $fieldname) {
                    $text = $data[$fieldname][$i];
                    try {
                        $twig->render($text, $parameters);
                    } catch (Twig_Error $ex) {
                        $errors["testcode[$i]"] = get_string('twigerrorintest',
                                'qtype_coderunner', $ex->getMessage());
                    }
                }
            }
        }
        return $errors;
    }


    private function make_question_from_form_data($data) {
        // Construct a question object containing all the fields from $data.
        global $DB;
        $question = new qtype_coderunner_question();
        foreach ($data as $key => $value) {
            if ($key === 'questiontext') {
                // Question text is an associative array.
                $question->$key = $value['text'];
            } else {
                $question->$key = $value;
            }
        }
        $question->isnew = true;
        $question->filemanagerdraftid = $this->get_file_manager();

        // Clean the question object, get inherited fields and run the sample answer.
        $qtype = new qtype_coderunner();
        $qtype->clean_question_form($question, true);
        $questiontype = $question->coderunnertype;
        list($category) = explode(',', $question->category);
        $contextid = $DB->get_field('question_categories', 'contextid', array('id' => $category));
        $question->contextid = $contextid;
        $context = context::instance_by_id($contextid, IGNORE_MISSING);
        $question->prototype = $qtype->get_prototype($questiontype, $context);
        $qtype->set_inherited_fields($question, $question->prototype);
        return $question;
    }

    // Check the sample answer (if there is one)
    // Return an empty string if there is no sample answer or if the sample
    // answer passes all the tests.
    // Otherwise return a suitable error message for display in the form.
    private function validate_sample_answer($data) {

        if (trim($data['answer']) === '') {
            return '';
        }

        // Check if it's a multilanguage question; if so need to determine
        // what language (either the default or the first).
        $acelangs = trim($data['acelang']);
        if ($acelangs !== '' && strpos($acelangs, ',') !== false) {
            list($languages, $defaultlang) = qtype_coderunner_util::extract_languages($acelangs);
            if ($defaultlang === '') {
                $defaultlang = $languages[0];
            }
        }

        $question = $this->make_question_from_form_data($data);
        $question->start_attempt();
        $response = array('answer' => $question->answer);
        if (!empty($defaultlang)) {
            $response['language'] = $defaultlang;
        }

        list($mark, $state, $cachedata) = $question->grade_response($response);

        // Return either an empty string if run was good or an error message.
        if ($mark == 1.0) {
            return '';
        } else {
            $outcome = unserialize($cachedata['_testoutcome']);
            $error = $outcome->validation_error_message();
            return $error;
        }
    }


    // Find the filemanager element draftid.
    private function get_file_manager() {
        $mform = $this->_form;
        $draftid = null;
        foreach ($mform->_elements as $element) {
            if ($element->_type == 'filemanager') {
                $draftid = (int)$element->getValue();
                break;
            }
        }
        return $draftid;
    }

}
