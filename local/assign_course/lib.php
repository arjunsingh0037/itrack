<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
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
 * Handles uploading files
 *
 * @package    local_assign_course
 * @copyright  Prashant Yallatti<prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
function local_assign_course_extend_navigation(global_navigation $nav) {
    global $CFG;
    $coursename = get_string('pluginname','local_assign_course');
    $url = '#';
    $flat = new flat_navigation_node(navigation_node::create($coursename, $url), 0);
    $nav->add_node($flat);
    $abc = $nav->add(get_string('pluginname','local_assign_course'),
        $CFG->wwwroot.'/local/assign_course/assign_course.php');
    $abc->showinflatnavigation = true;
}


