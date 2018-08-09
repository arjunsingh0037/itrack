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
 * Public Profile -- a user's public profile page
 *
 * - each user can currently have their own page (cloned from system and then customised)
 * - users can add any blocks they want
 * - the administrators can define a default site public profile for users who have
 *   not created their own public profile
 *
 * This script implements the user's view of the public profile, and allows editing
 * of the public profile.
 *
 * @package    core_user
 * @copyright  2010 Remote-Learner.net
 * @author     Hubert Chathi <hubert@remote-learner.net>
 * @author     Olav Jordan <olav.jordan@remote-learner.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../config.php');
require_once('lib.php');

$PAGE->set_url('/course/courselistlist.php');
require_login();
$userid = $USER->id;       // Owner of the page.
$user = $DB->record_exists('trainingpartners', array('userid' => $userid));
if ($user) {
    $PAGE->set_context(context_system::instance());
}else{
    echo $OUTPUT->header();
    echo $OUTPUT->notification(get_string('invaliduser', 'error'));
    echo $OUTPUT->footer();
    die;
    
}
$currentuser = $USER->id;
$context = $usercontext = context_user::instance($userid, MUST_EXIST);
$PAGE->set_context($context);
$PAGE->set_pagelayout('custom');
$PAGE->set_pagetype('user-profile');

// Start setting up the page.
$strpublicprofile = 'Course Lists';

$PAGE->blocks->add_region('content');
$PAGE->set_title("$strpublicprofile");
//$PAGE->set_heading(fullname($user));

if (!$currentuser) {
    $PAGE->navigation->extend_for_user($user);
    if ($node = $PAGE->settingsnav->get('userviewingsettings'.$user->id)) {
        $node->forceopen = true;
    }
} else if ($node = $PAGE->settingsnav->get('dashboard', navigation_node::TYPE_CONTAINER)) {
    $node->forceopen = true;
}
if ($node = $PAGE->settingsnav->get('root')) {
    $node->forceopen = false;
}
echo $OUTPUT->header();
 //account manager
userbased_courselist($USER->id,10);

echo $OUTPUT->footer();
