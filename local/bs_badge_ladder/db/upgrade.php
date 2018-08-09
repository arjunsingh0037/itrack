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
 * Upgrade handler for badge ladder plugin
 *
 * @package    local_bs_badge_ladder
 * @copyright  2015 onwards Matthias Schwabe {@link http://matthiasschwa.be}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_local_bs_badge_ladder_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();
    $courses = $DB->get_records('course', null, '', 'id');

    if ($oldversion < 2017080400) {

        $table = new xmldb_table('local_badge_ladder');
        $field = new xmldb_field('perpage');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'anonymize');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        foreach ($courses as $course) {

            $record = new stdClass();
            $record->id = $DB->get_field('local_badge_ladder', 'id', array('courseid' => $course->id));
            $record->courseid = $course->id;
            $record->perpage = 20;
            $DB->update_record('local_badge_ladder', $record);
        }

        upgrade_plugin_savepoint(true, 2017080400, 'local', 'local_bs_badge_ladder');

    }

    return true;
}
