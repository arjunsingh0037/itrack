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

/**
 * AJAX script to return a JSON-encoded row of the options for the specified
 * question type by looking up the prototype in the question_coderunner_options
 * table. Fields 'success' and 'error' are added for validation checking by
 * the caller.
 *
 * @group qtype_coderunner
 * Assumed to be run after python questions have been tested, so focuses
 * only on C-specific aspects.
 *
 * @package    qtype
 * @subpackage coderunner
 * @copyright  2015 Richard Lobb, University of Canterbury
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);

require_once('../../../config.php');
require_once($CFG->dirroot . '/question/engine/lib.php');
require_once($CFG->dirroot . '/question/type/coderunner/questiontype.php');

require_login();
require_sesskey();

$qtype  = required_param('qtype', PARAM_RAW_TRIMMED);
$courseid = required_param('courseid', PARAM_INT);

header('Content-type: application/json; charset=utf-8');

$coursecontext = context_course::instance($courseid);
$questiontype = qtype_coderunner::get_prototype($qtype, $coursecontext);
if ($questiontype === null) {
    $questiontype = new stdClass();
    $questiontype->success = false;
    $questiontype->error = "Error fetching prototype. " . $e->getMessage();
} else {
    $questiontype->success = true;
    $questiontype->error = '';
}
echo json_encode($questiontype);
die();
