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
 * Displays the badge ladder
 *
 * @package    local_bs_badge_ladder
 * @copyright  2015 onwards Matthias Schwabe {@link http://matthiasschwa.be}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__).'/lib.php');

$type = required_param('type', PARAM_INT);
$mode = optional_param('mode', 1, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);
$courseid = optional_param('id', SITEID, PARAM_INT);

$params = array('id' => $courseid, 'type' => $type, 'mode' => $mode);
$PAGE->set_url(new moodle_url('/local/bs_badge_ladder/index.php', $params));
$PAGE->navbar->add(get_string('pluginname', 'local_bs_badge_ladder'));

$perpage = $type == 1 ? get_config('local_bs_badge_ladder')->systembadgeladderperpage :
    $DB->get_field('local_badge_ladder', 'perpage', array('courseid' => $courseid));
$start = $page == 0 ? 0 : $page * $perpage;
$tabs = array();

// Site badge ladder.
if ($type == 1) {

    require_login();
    $context = context_system::instance();

    $PAGE->set_context($context);
    $PAGE->set_pagelayout('admin');
    $PAGE->set_heading($SITE->fullname);
    $PAGE->set_title($SITE->fullname. ': '. get_string('pluginname', 'local_bs_badge_ladder'));
    echo $OUTPUT->header();

    $coursesql = '';

// Course badge ladder.
} else if ($type == 2) {

    $course = $DB->get_record('course', array('id' => $courseid));
    require_login($course);
    $context = context_course::instance($courseid);
    require_capability('local/bs_badge_ladder:viewcourseladder', $context);

    $PAGE->set_pagelayout('incourse');
    $PAGE->set_heading($course->fullname);
    $PAGE->set_title($course->fullname. ': ' .get_string('pluginname', 'local_bs_badge_ladder'));

    $config = $DB->get_record('local_badge_ladder', array('courseid' => $courseid), '*', MUST_EXIST);
    $form = new local_bs_badge_ladder_form($PAGE->url, array('config' => $config));

    if ($data = $form->get_data()) {

        $config->status = $data->enablecoursebadgeladder;
        $config->perpage = $data->coursebadgeladderperpage;
        if (get_config('local_bs_badge_ladder')->anonymizestudentbadgeladder) {
            $config->anonymize = $data->anonymizestudentbadgeladder;
        } else {
            $config->anonymize = 0;
        }
        $DB->update_record('local_badge_ladder', $config);

        redirect($PAGE->url);
    }

    echo $OUTPUT->header();

    $coursesql = ' and courseid = '.$courseid;

    if (has_capability('local/bs_badge_ladder:managecourseladder', $PAGE->context)) {
        $tabs[] = new tabobject(3, new moodle_url('/local/bs_badge_ladder/index.php',
            array('id' => $courseid, 'type' => $type, 'mode' => 3)), get_string('edittab', 'local_bs_badge_ladder'));
    }

    // Print notification for trainer if course badge ladder is disbaled for course.
    if ($DB->get_field('local_badge_ladder', 'status', array('courseid' => $courseid)) != 1) {
        echo $OUTPUT->notification(get_string('ladderdisabled', 'local_bs_badge_ladder'), '');
    }

} else {
    die;
}

// Tab navigation.
$tabs[] = new tabobject(1, new moodle_url('/local/bs_badge_ladder/index.php',
    array('id' => $courseid, 'type' => $type, 'mode' => 1)), get_string('badgestab', 'local_bs_badge_ladder'));
$tabs[] = new tabobject(2, new moodle_url('/local/bs_badge_ladder/index.php',
    array('id' => $courseid, 'type' => $type, 'mode' => 2)), get_string('studenstab', 'local_bs_badge_ladder'));

echo $OUTPUT->tabtree($tabs, $mode);

$table = new html_table();
$table->attributes['class'] = 'collection';

// Print ladder 'number of students owning this badge'.
if ($mode == 1) {

    $table->head = array(
        get_string('name'),
        get_string('numberstudents', 'local_bs_badge_ladder')
    );

    $table->colclasses = array('name', 'number');

    // Get available badges.
    $sql = "SELECT id
              FROM {badge}
             WHERE status IN (1,3) and
                   type = ?"
                   .$coursesql;

    $params = array($type);
    $badges = $DB->get_records_sql($sql, $params);
    $courseid = $type == 1 ? null : $courseid;

    $count = 0;
    foreach ($badges as $badge) {
        $count++;
        $numbercount = $DB->count_records('badge_issued', array('badgeid' => $badge->id));
        $badge->badgescount = $numbercount;
    }

    usort($badges, function($b, $a) {
        if ((int)$a->badgescount == (int)$b->badgescount) { return  0; }
        if ((int)$a->badgescount  > (int)$b->badgescount) { return  1; }
        if ((int)$a->badgescount  < (int)$b->badgescount) { return -1; }
    });

    $badgesubset = array_slice($badges, $start, $perpage);
    unset($badges); // Release some memory.

    foreach ($badgesubset as $b) {

        $badge = new badge($b->id);
        $linktext = print_badge_image($badge, $context). ' '.
            html_writer::start_tag('span').$badge->name.html_writer::end_tag('span');
        $name = html_writer::link(new moodle_url('/badges/view.php',
            array('id' => $courseid, 'type' => $type)), $linktext, null);
        $numbercount = $DB->count_records('badge_issued', array('badgeid' => $badge->id));
        $number = html_writer::div($numbercount, 'number');
        $row = array($name, $number);
        $table->data[] = $row;
    }

// Print ladder 'number of badges owned by students'.
} else if ($mode == 2) {

    if ($type == 1) {

        require_capability('moodle/site:viewparticipants', $context);

        // To prevent an out of memory error on large installations, we call raise_memory_limit(MEMORY_HUGE).
        raise_memory_limit(MEMORY_HUGE);

        // Ninth parameter $recordsperpage is set to 1000000 to prevent debug message when set $recordsperpage = 0 and first
        // parameter $get = true. We only get user ids with get_users() so debug message would be obsolete.
        $students = get_users(true, '', true, null, 'firstname ASC', '', '', 0, 1000000, '(id)', 'deleted = 0');
        $count = get_users(false, '', true, null, '', '', '', 0, 0, '(id)', 'deleted = 0');
        $badgecourseid = null;
        $courseid = 1;
    } else {
        require_capability('moodle/course:viewparticipants', $context);
        $students = get_enrolled_users($context, '', 0, 'u.id', 0);
        $count = count_enrolled_users($context);
        $badgecourseid = $courseid;
    }

    foreach ($students as $student) {

        if (is_siteadmin($student) or isguestuser()) {
            unset($students[$student->id]); // No admins or guests in student badge ladder.
        } else {
            $studentbadges = $DB->get_records('badge_issued', array('userid' => $student->id));
            $badgescount = 0;
            foreach ($studentbadges as $sb) {
                if ($DB->get_record('badge', array('id' => $sb->badgeid, 'courseid' => $badgecourseid))) {
                    $badgescount++;
                }
            }
            $student->badgescount = $badgescount;
        }
    }

    usort($students, function($b, $a) {
        if ((int)$a->badgescount == (int)$b->badgescount) { return  0; }
        if ((int)$a->badgescount  > (int)$b->badgescount) { return  1; }
        if ((int)$a->badgescount  < (int)$b->badgescount) { return -1; }
    });

    $studentsubset = array_slice($students, $start, $perpage);
    unset($students); // Release some memory.

    $table->head = array(get_string('name'));
    if ($type == 1) {
        $table->head[] = get_string('numberbadgessystem', 'local_bs_badge_ladder');
    } else {
        $table->head[] = get_string('numberbadgescourse', 'local_bs_badge_ladder');
    }

    $table->colclasses = array('name', 'number');

    foreach ($studentsubset as $s) {

        $student = $DB->get_record('user', array('id' => $s->id));

        if (!is_siteadmin($student) and !isguestuser()) {

            $studentbadges = $DB->get_records('badge_issued', array('userid' => $student->id));
            $badgescount = 0;
            foreach ($studentbadges as $sb) {
                if ($DB->get_record('badge', array('id' => $sb->badgeid, 'courseid' => $badgecourseid))) {
                    $badgescount++;
                }
            }

            if ($PAGE->course->id != SITEID) {
                $anonymize = $DB->get_record('local_badge_ladder', array('courseid' => $PAGE->course->id, 'anonymize' => 1))
                    and get_config('local_bs_badge_ladder')->anonymizestudentbadgeladder;
            } else {
                $anonymize = get_config('local_bs_badge_ladder')->anonymizesystemstudentbadgeladder;
            }

            if ($type == 1 ) {
                $capability = has_capability('moodle/site:viewparticipants', $context);
            } else {
                $capability = has_capability('moodle/course:viewparticipants', $context);
            }

            if (($anonymize or !$capability)
                and $student->id != $USER->id) {
                    $name = get_string('anonymizedname', 'local_bs_badge_ladder');
            } else {
                $userpic = $OUTPUT->user_picture($student, array('size' => 35, 'courseid' => $courseid));
                $fullnameclass = $student->id == $USER->id ? 'fullnameself' : 'fullname';
                $linktext = html_writer::start_tag('span', array('class' => $fullnameclass)).fullname($student)
                    .html_writer::end_tag('span');
                if (has_capability('moodle/user:viewdetails', $context)) {
                    $name = html_writer::link(new moodle_url('/user/view.php',
                        array('id' => $student->id, 'course' => $courseid)), $userpic, null);
                    $name .= ' ';
                    $name .= html_writer::link(new moodle_url('/user/view.php',
                        array('id' => $student->id, 'course' => $courseid)), $linktext, null);
                } else {
                    $name = $userpic.' '.$linktext;
                }
            }
            $number = html_writer::div($badgescount, 'number');
            $row = array($name, $number);
            $table->data[] = $row;
        }
    }

// Show badge ladder options.
} else if ($mode == 3 and $type == 2) {

    require_capability('local/bs_badge_ladder:managecourseladder', $context);

    echo $OUTPUT->box_start('generalbox boxaligncenter boxwidthwide');
    $form->display();
    echo $OUTPUT->box_end();

} else {
    die;
}

if ($mode != 3) {
    // True = links for prev, next, first & last page.
    $paging = new paging_bar($count, $page, $perpage, $PAGE->url, 'page', true, true, true, true);
    $htmlpagingbar = $OUTPUT->render($paging);
    $htmltable = html_writer::table($table);
    echo $OUTPUT->box($htmlpagingbar.$htmltable.$htmlpagingbar, 'generalbox');
}

echo $OUTPUT->footer();
