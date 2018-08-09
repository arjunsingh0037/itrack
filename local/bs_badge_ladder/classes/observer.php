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
 * Event observer file for badge ladder plugin
 *
 * @package    local_bs_badge_ladder
 * @copyright  2015 onwards Matthias Schwabe {@link http://matthiasschwa.be}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Event observer.
 */
class local_bs_badge_ladder_observer {

    public static function course_created(\core\event\course_created $event) {
        global $DB;

        $config = new stdClass();
        $config->courseid = $event->objectid;
        $config->status = get_config('local_bs_badge_ladder')->courseladderdefault;
        $config->anonymize = get_config('local_bs_badge_ladder')->anonymizestudentbadgeladderdefault;
        $config->perpage = 10;
        $DB->insert_record('local_badge_ladder', $config);
    }
}
