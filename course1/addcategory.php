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
 * Page for creating or editing course category name/parent/description.
 *
 * When called with an id parameter, edits the category with that id.
 * Otherwise it creates a new category with default parent from the parent
 * parameter, which may be 0.
 *
 * @package    core_course
 * @copyright  2007 Nicolas Connault
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->libdir.'/coursecatlib.php');
require_once ('add_category_form.php');
$id = optional_param('id', 0, PARAM_INT);
require_login();
$url = new moodle_url('/course/addcategory.php');
require_capability('moodle/category:manage', $context);

$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin');
$PAGE->set_title($title);
$PAGE->set_heading($fullname);

$cats = $DB->get_records('course_categories');
$optionlist = array();
$optionlist['none'] = '-- Select --';
if (!empty($cats)) {
    foreach ($cats as $list) {
        $optionlist[$list->id] = $list->name;
    }
}

echo $OUTPUT->single_select($url, 'id', $optionlist, $clientid, null, 'rolesform');
$mform = new addcategory_form();
$manageurl = new moodle_url('/course/addcategory.php');
if ($mform->is_cancelled()) {
    redirect($manageurl);
} else if ($data = $mform->get_data()) {
    $new_cat = new stdClass();
    $new_cat->name = $data->catname;
    $new_cat->parent = $parent;
    $new_category = $DB->insert_record('course_categories',$new_cat);
    redirect($manageurl);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($strtitle);
$mform->display();
echo $OUTPUT->footer();
