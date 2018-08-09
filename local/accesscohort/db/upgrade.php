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
 * @package    local_accesscohort
 * @copyright  Prashant Yallatti<prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Quiz module upgrade function.
 * @param string $oldversion the version we are upgrading from.
 */
function xmldb_local_accesscohort_upgrade($oldversion) {
    global $CFG, $DB;
    $dbman = $DB->get_manager();
    if ($oldversion < 2017011018) {
        // Define field id to be added to local_assign_course.
        $field = [];
        $table = new xmldb_table('local_organization');
        $field[] = new xmldb_field('org_email', XMLDB_TYPE_CHAR, '255', null, null, null, null, null);
        $field[] = new xmldb_field('short_name', XMLDB_TYPE_CHAR, '255', null, null, null, null, null);
        $field[] = new xmldb_field('org_address', XMLDB_TYPE_TEXT, '255', null, null, null, null, null);        
        $field[] = new xmldb_field('org_phone', XMLDB_TYPE_INTEGER, '20',
            null, null,null, null, null);
        $field[] = new xmldb_field('org_skype', XMLDB_TYPE_CHAR, '255', null, null, null, null, null);
        $field[] = new xmldb_field('org_fb', XMLDB_TYPE_CHAR, '255', null, null, null, null, null);
        $field[] = new xmldb_field('org_tweet', XMLDB_TYPE_CHAR, '255', null, null, null, null, null);
        // Conditionally launch add field id.
        foreach ($field as $value) {
            if (!$dbman->field_exists($table, $value)) {
                $dbman->add_field($table, $value);
            }
        }
        // Assign_course savepoint reached.
        upgrade_plugin_savepoint(true, 2017011018, 'local', 'accesscohort');
    }
    if ($oldversion < 2017011024) {
        // Define field id to be added to local_assign_course.
        $field = [];
        $table = new xmldb_table('local_oragnization_admin');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '20', null,null, null, null);
        $table->add_field('orgid', XMLDB_TYPE_INTEGER, '20', null, null, null,null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        
        // Adding keys to table assign_overrides.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for assign_overrides.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Assign_course savepoint reached.
        upgrade_plugin_savepoint(true, 2017011024, 'local', 'accesscohort');
    }
    return true;
}

