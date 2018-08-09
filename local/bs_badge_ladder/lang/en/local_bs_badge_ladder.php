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
 * Language file for badge ladder plugin
 *
 * @package    local_bs_badge_ladder
 * @copyright  2015 onwards Matthias Schwabe {@link http://matthiasschwa.be}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Badge Ladder';
$string['viewcourseladder'] = 'Course badge ladder';
$string['viewcourseladderdisabled'] = 'Course badge ladder (disabled)';
$string['viewsystemladder'] = 'Site badge ladder';
$string['systemladder'] = 'Enable site badge ladder';
$string['systemladderinfo'] = 'Authenticated users need the capability moodle/site:viewparticipants to access the site badge ladder.';
$string['courseladder'] = 'Allow course badge ladder';
$string['courseladderinfo'] = 'If course badge ladder is allowed it can be (de-)activated for each course separately.<br/>Students need the capability moodle/course:viewparticipants to access the course badge ladder.';
$string['courseladderdefault'] = 'Default value for new courses';
$string['courseladderdefaultinfo'] = 'Select if the course badge ladder is enabled or disabled by default for new courses. This setting effects only on new courses. For existing courses it is disabled by default.';
$string['anonymizestudentladderdefault'] = 'Default value for anonymization of participants in student badge ladder';
$string['anonymizestudentladderdefaultinfo'] = 'Select if the anonymization of participants in student badge ladder is enabled or disabled by default for new courses. This setting effects only on new courses. For existing courses it is disabled by default.';
$string['anonymizestudentladder'] = 'Allow anonymization of participants in course student badge ladder';
$string['anonymizestudentladderinfo'] = 'If allowed it can be (de-)activated for each course separately. When activated participants can only see their own names in the course student badge ladder.';
$string['enabled'] = 'enabled';
$string['disabled'] = 'disabled';
$string['numberstudents'] = 'Number of students owning this badge';
$string['numberbadgescourse'] = 'Number of badges from this course owned by this student';
$string['numberbadgessystem'] = 'Number of site badges owned by this student';
$string['badgestab'] = 'Badge ladder';
$string['edittab'] = 'Edit ladder';
$string['studenstab'] = 'Student ladder';
$string['enablecoursebadgeladder'] = 'Enable course badge ladder for this course';
$string['anonymizestudentbadgeladder'] = 'Anonymize names of participants on student ladder';
$string['ladderdisabled'] = 'Course badge ladder is disabled for this course';
$string['bs_badge_ladder:viewcourseladder'] = 'View the course badge ladder';
$string['bs_badge_ladder:managecourseladder'] = 'Manage the course badge ladder';
$string['anonymizedname'] = 'Someone';
$string['systemladderhead'] = 'Site badge ladder';
$string['courseladderhead'] = 'Course badge ladder';
$string['anonymizesystemstudentbadgeladder'] = 'Anonymize useres in site student badge ladder';
$string['anonymizesystemstudentbadgeladderinfo'] = 'When activated users can only see their own names in the site student badge ladder.<br/>PLEASE NOTE: Even if activated authenticated users still need the capability moodle/site:viewparticipants.';
$string['coursebadgeladderperpage'] = 'Number of displayed table rows per page';
$string['sitebadgeladderperpage'] = 'Number of displayed table rows per page on site badge ladder';
$string['sitebadgeladderperpagedesc'] = '';
$string['about'] = 'About';
$string['notnegativenumericvalue'] = 'Please enter a not negative numeric value greater than 0.';
$string['requiredvalue'] = 'Please enter a value.';
$string['configuration'] = 'Configuration';
$string['donationtext'] = 'If you like this plugin and want to support my work please feel free to use this Paypal donation button:';
$string['abouttext'] = 'This plugin has been developed by Matthias Schwabe and is part of a Moodle badges related set of plugins.<br />Other plugins of this set are {$a->badgeenrol}, {$a->badgepool} and {$a->recentbadges}.';
$string['aboutfeedbacktext'] = 'If you have any feedback or great ideas for new features, do not hesitate to leave a post on the {$a->aboutlink} or send me an e-mail to {$a->aboutmail}.<br /><br />';
$string['plugindirectory'] = 'Moodle plugin directory page';
$string['displaylink_normal'] = 'standard';
$string['displaylink_alternate'] = 'alternate';
$string['systemladderdisplaylink'] = 'Link display mode';
$string['systemladderdisplaylinkinfo'] = 'If you are using a theme based on Boost you should select \'alternate\' to display a link to the site badge ladder';
