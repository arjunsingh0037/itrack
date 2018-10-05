<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language file for plugin gradingform_erubric
 *
 * @package    gradingform
 * @subpackage Learinng Analytics Enriched Rubric (e-rubric)
 * @copyright  2012 John Dimopoulos
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// LA e-rubric definitions.
$string['pluginname'] = 'Learning Analytics Enriched Rubric';
$string['defineenrichedrubric'] = 'Define LA e-rubric';
$string['erubric'] = 'Learning Analytics e-rubric';
$string['gradingof'] = '{$a} grading';
$string['previewerubric'] = 'Preview LA e-rubric';

// LA e-rubric form fields and buttons.
$string['addcriterion'] = 'Add criterion';
$string['backtoediting'] = 'Back to editing';
$string['criterionaddlevel'] = 'Add level';
$string['criteriondelete'] = 'Delete criterion';
$string['criterionmovedown'] = 'Move down';
$string['criterionmoveup'] = 'Move up';
$string['deleteactivity'] = 'Delete activity';
$string['deleteresource'] = 'Delete resource';
$string['deleteassignment'] = 'Delete assignment';
$string['leveldelete'] = 'Delete level';
$string['save'] = 'Save';
$string['saverubric'] = 'Save enriched rubric and make it ready';
$string['saverubricdraft'] = 'Save as draft';

// LA e-rubric form fields prefix labels.
$string['participationin'] = 'Check:';
$string['collaborationtype'] = 'Type:';
$string['coursemoduleis'] = 'In:';
$string['participationis'] = 'Is:';
$string['participationon'] = 'Related to:';
$string['description'] = 'Description';
$string['name'] = 'Name';
$string['rubricstatus'] = 'Current LA e-rubric status';

// LA e-rubric pre-defined select fields values desription
$string['selectstudy'] = 'study';
$string['selectcollaboration'] = 'collaboration';
$string['selectgrade'] = 'grade';
$string['criterionoperatorequals'] = 'equal (=)';
$string['criterionoperatormorethan'] = 'more than (>=)';
$string['referencetypenumber'] = 'student';
$string['referencetypepercentage'] = 'students';
$string['collaborationtypeentries'] = 'posts & talks';
$string['collaborationtypefileadds'] = 'file submissions';
$string['collaborationtypereplies'] = 'forum replies';
$string['collaborationtypeinteractions'] = 'people interacted';
$string['addnew'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add (+)'; // Added spaces in order to move string to the middle of the option select field

// LA e-rubric confirmation dialogs.
$string['confirmdeletecriterion'] = 'Are you sure you want to delete this criterion?';
$string['confirmdeletelevel'] = 'Are you sure you want to delete this level?';
$string['confirmdeleteactivity'] = 'Are you sure you want to delete this activity?';
$string['confirmdeleteresource'] = 'Are you sure you want to delete this resource?';
$string['confirmdeleteassignment'] = 'Are you sure you want to delete this assignment?';
$string['confirmchangecriteriontype'] = 'Are you sure you want to change the criterion type? Current criterion course modules will be reset.';

// LA e-rubric level enrichment value suffixes and score postfix.
$string['enrichedvaluesuffixpoints'] = '%points';
$string['enrichedvaluesuffixtimes'] = 'times';
$string['enrichedvaluesuffixfiles'] = 'files';
$string['enrichedvaluesuffixpercent'] = 'percent';
$string['enrichedvaluesuffixstudents'] = 'people';
$string['enrichedvaluesuffixnothing'] = '<font color="red"><b>!!!</b></font>';
$string['scorepostfix'] = '{$a}points';

// LA e-rubric empty form fields description.
$string['criterionempty'] = 'Click to edit criterion';
$string['levelempty'] = 'Click to edit level';
$string['intercactionempty'] = 'Criterion Type';
$string['coursemoduleempty'] = 'Add Course Module';
$string['operatorempty'] = 'Choose Operator';
$string['referencetypeempty'] = 'One or All';
$string['enrichedvalueempty'] = 'Add value';
$string['collaborationempty'] = 'Collaboration Type';

// LA e-rubric info explained.
$string['rubricmapping'] = 'Score to grade mapping rules';
$string['rubricmappingexplained'] = 'The minimum possible score for this enriched rubric is <b>{$a->minscore} points</b> 
    and it will be converted to the minimum grade available in this module (which is zero unless the scale is used).
    The maximum score <b>{$a->maxscore} points</b> will be converted to the maximum grade.<br />
    Intermediate scores will be converted respectively and rounded to the nearest available grade.<br />
    If a scale is used instead of a grade, the score will be converted to the scale elements as if they were consecutive integers.<br /><br />';
$string['enrichedrubricinfo'] = 'Rubric criteria enrichment rules';
$string['enrichedrubricinfoexplained'] = 'All enriched criteria will be calculated automatically and level selection will be carried out by the system.
                                            When that happens the evaluator can’t change the selection.<br />
                                            In case of an enrichment criteria logical error, no level will be selected by the system,
                                            thus specific criterion score will not be included in the grade and
                                            only if override is enabled the evaluator can make his own choice.<br />';
$string['enrichshareconfirm'] = '<font color="red"><b>WARNING!</b></font><br /><br />
<b>Learning Analytics Enriched Rubric</b> plugin can be used as an advanced grading form template, ONLY FOR THE PRESENT COURSE!
If other users at your site use this form in any other course, <b>it will not work as is</b>! The rubric\'s main structure will remain intact,
but you will have to replace all embedded course modules with similar modules from the destination course.';

// LA e-rubric enrichment help icon.
$string['enrichment'] = 'Enrichment';
$string['enrichment_help'] = 'Watch the following tutorial on how to Create Criteria in Learning Analytics Enriched Rubric:
    <br /><br />
    <a target="_blank" href="http://www.youtube.com/watch?v=8w6yreB1geI&hd=1">Create Criteria in Learning Analytics Enriched Rubric</a><br /><br />';

// LA e-rubric description of form errors and alerts.
$string['err_missinglogstores'] = '<p><font color="red"><b>Logging System Error!</b></font></p>
    This tool can only function if the <b>Standard</b> and/or <b>Legacy</b> logs are enabled. Non of them appear to be.
    Please contact your site Administrator in order to activate either one of the previous log stores, if you want to use this tool.
    For more information check the <a target="_blank" href="https://docs.moodle.org/29/en/Learning_Analytics_Enriched_Rubric">plugin\'s documentation</a>.';
$string['err_criteriontypeid'] = 'You must choose one or all Resource(s) or Activity(ies).';
$string['err_criteriontypeid'] = 'You must choose an operator for the enriched criterion.';
$string['err_criterionmethod'] = 'You must choose the numerical reference for the enriched criterion.';
$string['collaborationochoice'] = 'You must choose collaboration type before adding modules!';
$string['err_enrichedvalueformat'] = 'Number of check values for each enriched level must be a valid non-negative integer number.';
$string['err_enrichedvaluemissing'] = 'Enriched criteria, must have check values on all levels.';
$string['err_enrichedcriterionmissing'] = 'All enriched criteria must be selected, or none.';
$string['err_enrichedmoduleselection'] = 'Selected course modules must be of the same enriched criterion type.';
$string['err_collaborationhoice'] = 'Chat modules can not be chosen to check file submissions or forum replies.';
$string['err_collaborationtypeneedless'] = 'Type field should be selected only for collaboration check.';
$string['err_missingcoursemodule'] = 'Missing module!';
$string['err_missingcoursemodules'] = '<p><font color="red"><b>WARNING!</b></font></p>
    At least one course module is missing from the criteria!
    Maybe the course module was deleted or this gradding form was imported from another course (or backup)(or shared form).
    Edit the current form in order to re-enrich (or not) these criteria. Otherwise <b>student evaluation may not be possible</b>!';
$string['err_missingcoursemodulesedit'] = '<p><font color="red"><b>WARNING!</b></font></p>
    At least one course module is missing from the criteria!
    You may delete these criteria or make them \'simple\' by resetting enrichment fields or enrich them again.
    <b>If you don\'t update this form and leave it as is, student evaluation may not be possible!</b>';
$string['err_mintwolevels'] = 'Each criterion must have at least two levels.';
$string['err_nocriteria'] = 'Rubric must contain at least one criterion.';
$string['err_nodefinition'] = 'Level definition can not be empty.';
$string['err_nodescription'] = 'Criterion description can not be empty.';
$string['err_scoreformat'] = 'Number of points for each level must be a valid non-negative integer number.';
$string['err_totalscore'] = 'Maximum number of points possible when graded by the rubric must be more than zero.';
$string['needregrademessage'] = 'The enriched rubric definition was changed after this student had been graded.
    The student can not see this enriched rubric until you check the enriched rubric and update the grade.';
$string['regrademessage1'] = 'You are about to save changes to an enriched rubric that has already been used for grading.
    Please indicate if existing grades need to be reviewed.
    If you set this then the  enriched rubric will be hidden from students until their item is regraded.';
$string['regrademessage5'] = 'You are about to save significant changes to an enriched rubric that has already been used for grading.
    The gradebook value will be unchanged, but the enriched rubric will be hidden from students until their item is regraded.';
$string['regradeoption0'] = 'Do not mark for regrade';
$string['regradeoption1'] = 'Mark for regrade';
$string['restoredfromdraft'] = 'NOTE: The last attempt to grade this person was not saved properly so draft grades have been restored.
    If you want to cancel these changes use the \'Cancel\' button below.';
$string['rubricnotcompleted'] = 'An appropriate level for each criterion should be chosen.';

// LA e-rubric evaluation results.
$string['benchmarkinfo'] = 'Results from Learning Analytics';
$string['benchmarkfinal'] = 'Student final benchmark result';
$string['studentbenchmarkinfo'] = 'Student benchmark';
$string['studentsbenchmarkinfo'] = 'Students benchmark';
$string['benchmarkinfonull'] = 'No results from Learning Analytics';

// LA e-rubric simple rubric options.
$string['rubricoptions'] = 'Rubric options';
$string['sortlevelsasc'] = 'Sort order for levels:';
$string['sortlevelsasc0'] = 'Descending by number of points';
$string['sortlevelsasc1'] = 'Ascending by number of points';
$string['alwaysshowdefinition'] = 'Allow users to preview rubric used in the module (otherwise rubric will only become visible after grading)';
$string['showdescriptionstudent'] = 'Display rubric description to those being graded';
$string['showdescriptionteacher'] = 'Display rubric description during evaluation';
$string['showscorestudent'] = 'Display points for each level to those being graded';
$string['showscoreteacher'] = 'Display points for each level during evaluation';
$string['enableremarks'] = 'Allow grader to add text remarks for each criterion';
$string['showremarksstudent'] = 'Show remarks to those being graded';

// LA e-rubric enrichment options.
$string['enrichmentoptions'] = 'Enriched criteria options';
$string['showenrichedvaluestudent'] = 'Display enrichment check points for each level to those being graded';
$string['showenrichedvalueteacher'] = 'Display enrichment check points for each level during evaluation';
$string['showenrichedcriteriastudent'] = 'Display enrichment of criteria to those being graded';
$string['showenrichedcriteriateacher'] = 'Display enrichment of criteria during evaluation';
$string['timestampenrichmentend'] = 'Enrichment calculations are conducted until submission due date (if enabled)';
$string['timestampenrichmentstart'] = 'Enrichment calculations are conducted from assignment available date (if enabled)';
$string['overideenrichmentevaluation'] = 'Override automatic criterion evaluation in case of enrichment logical error<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          (<i>If enrichment logical error exists, evaluation is not possible without overriding it!)</i>';
$string['showenrichedbenchmarkteacher'] = 'Display calculated enrichment benchmark during evaluation';
$string['showenrichedbenchmarkstudent'] = 'Display calculated enrichment benchmark to those being graded';