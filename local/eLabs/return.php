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
 * course_batch utility script
 *
 * @package    local_course_batches
 * @copyright  2004 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require("../../config.php");
//require_once("$CFG->dirroot/enrol/paybatch/lib.php");
//print_object($_POST);
$id = required_param('id', PARAM_INT);
$cbatchid = required_param('batchid', PARAM_INT);
if (!$course = $DB->get_record("course", array("id"=>$id))) {
    redirect($CFG->wwwroot);
}

$context = context_course::instance($course->id, MUST_EXIST);
$PAGE->set_context($context);

require_login();

if (!empty($SESSION->wantsurl)) {
    $destination = $SESSION->wantsurl;
    unset($SESSION->wantsurl);
} else {
    $destination = "$CFG->wwwroot/my/coursedetails.php?id=$course->id";
}
$batch = $DB->get_record('batch', array('courseid' => $course->id, 'id'=>$cbatchid));
$batchname = $batch->name;
//$fullname = format_string($course->fullname, true, array('context' => $context));
if($_POST['payment_status']=='Completed')
{
$a = new stdClass();
$a->batchname = $batchname;
redirect($destination, get_string('paymentthank', 'local_course_batches', $a));
}else{
 $PAGE->set_url($destination);
    echo $OUTPUT->header();
    $a = new stdClass();
    $a->batchname = $batchname;
    notice(get_string('paymentsorries', 'local_course_batches', $a), $destination);
}

/*
if (is_enrolled($context, NULL, '', true)) { // TODO: use real paybatch check
    redirect($destination, get_string('paymentthank', 'local_course_batches', $fullname));

} else {   /// Somehow they aren't enrolled yet!  :-(
    $PAGE->set_url($destination);
    echo $OUTPUT->header();
    $a = new stdClass();
    //$a->teacher = get_string('defaultcourseteacher');
    $a->fullname = $fullname;
    notice(get_string('paymentsorries', 'local_course_batches', $a), $destination);
}
*/

