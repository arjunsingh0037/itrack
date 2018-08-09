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
defined('MOODLE_INTERNAL') || die();

/**
 * Quiz module upgrade function.
 * @param string $oldversion the version we are upgrading from.
 */
function xmldb_local_assign_course_upgrade($oldversion) {
    global $CFG, $DB;
    $dbman = $DB->get_manager();
    if ($oldversion < 20170701027) {
        // Define field id to be added to local_assign_course.
        $field = [];
        $table = new xmldb_table('local_assign_course');
        $field[] = new xmldb_field('created_by', XMLDB_TYPE_CHAR, '255', null, null, null, null, null);
        $field[] = new xmldb_field('created_date', XMLDB_TYPE_INTEGER, '10',
            null, null,null, null, null);
        $field[] = new xmldb_field('modified_date', XMLDB_TYPE_INTEGER, '10',
            null, null,null, null, null);
        $field[] = new xmldb_field('status', XMLDB_TYPE_CHAR, '255',
            null, null,null, null, null);
        // Conditionally launch add field id.
        foreach ($field as $value) {
            if (!$dbman->field_exists($table, $value)) {
                $dbman->add_field($table, $value);
            }
        }
        // Assign_course savepoint reached.
        upgrade_plugin_savepoint(true, 20170701027, 'local', 'assign_course');
    }
    return true;
}
