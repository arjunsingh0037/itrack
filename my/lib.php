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
 * My Moodle -- a user's personal dashboard
 *
 * This file contains common functions for the dashboard and profile pages.
 *
 * @package    moodlecore
 * @subpackage my
 * @copyright  2010 Remote-Learner.net
 * @author     Hubert Chathi <hubert@remote-learner.net>
 * @author     Olav Jordan <olav.jordan@remote-learner.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('MY_PAGE_PUBLIC', 0);
define('MY_PAGE_PRIVATE', 1);

require_once("$CFG->libdir/blocklib.php");

/*
 * For a given user, this returns the $page information for their My Moodle page
 *
 */
function my_get_page($userid, $private=MY_PAGE_PRIVATE) {
    global $DB, $CFG;

    if (empty($CFG->forcedefaultmymoodle) && $userid) {  // Ignore custom My Moodle pages if admin has forced them
        // Does the user have their own page defined?  If so, return it.
        if ($customised = $DB->get_record('my_pages', array('userid' => $userid, 'private' => $private))) {
            return $customised;
        }
    }

    // Otherwise return the system default page
    return $DB->get_record('my_pages', array('userid' => null, 'name' => '__default', 'private' => $private));
}


/*
 * This copies a system default page to the current user
 *
 */
function my_copy_page($userid, $private=MY_PAGE_PRIVATE, $pagetype='my-index') {
    global $DB;

    if ($customised = $DB->get_record('my_pages', array('userid' => $userid, 'private' => $private))) {
        return $customised;  // We're done!
    }

    // Get the system default page
    if (!$systempage = $DB->get_record('my_pages', array('userid' => null, 'private' => $private))) {
        return false;  // error
    }

    // Clone the basic system page record
    $page = clone($systempage);
    unset($page->id);
    $page->userid = $userid;
    $page->id = $DB->insert_record('my_pages', $page);

    // Clone ALL the associated blocks as well
    $systemcontext = context_system::instance();
    $usercontext = context_user::instance($userid);

    $blockinstances = $DB->get_records('block_instances', array('parentcontextid' => $systemcontext->id,
                                                                'pagetypepattern' => $pagetype,
                                                                'subpagepattern' => $systempage->id));
    $newblockinstanceids = [];
    foreach ($blockinstances as $instance) {
        $originalid = $instance->id;
        unset($instance->id);
        $instance->parentcontextid = $usercontext->id;
        $instance->subpagepattern = $page->id;
        $instance->timecreated = time();
        $instance->timemodified = $instance->timecreated;
        $instance->id = $DB->insert_record('block_instances', $instance);
        $newblockinstanceids[$originalid] = $instance->id;
        $blockcontext = context_block::instance($instance->id);  // Just creates the context record
        $block = block_instance($instance->blockname, $instance);
        if (!$block->instance_copy($originalid)) {
            debugging("Unable to copy block-specific data for original block instance: $originalid
                to new block instance: $instance->id", DEBUG_DEVELOPER);
        }
    }

    // Clone block position overrides.
    if ($blockpositions = $DB->get_records('block_positions',
            ['subpage' => $systempage->id, 'pagetype' => $pagetype, 'contextid' => $systemcontext->id])) {
        foreach ($blockpositions as &$positions) {
            $positions->subpage = $page->id;
            $positions->contextid = $usercontext->id;
            if (array_key_exists($positions->blockinstanceid, $newblockinstanceids)) {
                // For block instances that were defined on the default dashboard and copied to the user dashboard
                // use the new blockinstanceid.
                $positions->blockinstanceid = $newblockinstanceids[$positions->blockinstanceid];
            }
            unset($positions->id);
        }
        $DB->insert_records('block_positions', $blockpositions);
    }

    return $page;
}

/*
 * For a given user, this deletes their My Moodle page and returns them to the system default.
 *
 * @param int $userid the id of the user whose page should be reset
 * @param int $private either MY_PAGE_PRIVATE or MY_PAGE_PUBLIC
 * @param string $pagetype either my-index or user-profile
 * @return mixed system page, or false on error
 */
function my_reset_page($userid, $private=MY_PAGE_PRIVATE, $pagetype='my-index') {
    global $DB, $CFG;

    $page = my_get_page($userid, $private);
    if ($page->userid == $userid) {
        $context = context_user::instance($userid);
        if ($blocks = $DB->get_records('block_instances', array('parentcontextid' => $context->id,
                'pagetypepattern' => $pagetype))) {
            foreach ($blocks as $block) {
                if (is_null($block->subpagepattern) || $block->subpagepattern == $page->id) {
                    blocks_delete_instance($block);
                }
            }
        }
        $DB->delete_records('block_positions', ['subpage' => $page->id, 'pagetype' => $pagetype, 'contextid' => $context->id]);
        $DB->delete_records('my_pages', array('id' => $page->id));
    }

    // Get the system default page
    if (!$systempage = $DB->get_record('my_pages', array('userid' => null, 'private' => $private))) {
        return false; // error
    }

    // Trigger dashboard has been reset event.
    $eventparams = array(
        'context' => context_user::instance($userid),
        'other' => array(
            'private' => $private,
            'pagetype' => $pagetype,
        ),
    );
    $event = \core\event\dashboard_reset::create($eventparams);
    $event->trigger();
    return $systempage;
}

/**
 * Resets the page customisations for all users.
 *
 * @param int $private Either MY_PAGE_PRIVATE or MY_PAGE_PUBLIC.
 * @param string $pagetype Either my-index or user-profile.
 * @return void
 */
function my_reset_page_for_all_users($private = MY_PAGE_PRIVATE, $pagetype = 'my-index') {
    global $DB;

    // This may take a while. Raise the execution time limit.
    core_php_time_limit::raise();

    // Find all the user pages and all block instances in them.
    $sql = "SELECT bi.id
        FROM {my_pages} p
        JOIN {context} ctx ON ctx.instanceid = p.userid AND ctx.contextlevel = :usercontextlevel
        JOIN {block_instances} bi ON bi.parentcontextid = ctx.id AND
            bi.pagetypepattern = :pagetypepattern AND
            (bi.subpagepattern IS NULL OR bi.subpagepattern = " . $DB->sql_concat("''", 'p.id') . ")
        WHERE p.private = :private";
    $params = array('private' => $private,
        'usercontextlevel' => CONTEXT_USER,
        'pagetypepattern' => $pagetype);
    $blockids = $DB->get_fieldset_sql($sql, $params);

    // Wrap the SQL queries in a transaction.
    $transaction = $DB->start_delegated_transaction();

    // Delete the block instances.
    if (!empty($blockids)) {
        blocks_delete_instances($blockids);
    }

    // Finally delete the pages.
    $DB->delete_records_select('my_pages', 'userid IS NOT NULL AND private = :private', ['private' => $private]);

    // We should be good to go now.
    $transaction->allow_commit();

    // Trigger dashboard has been reset event.
    $eventparams = array(
        'context' => context_system::instance(),
        'other' => array(
            'private' => $private,
            'pagetype' => $pagetype,
        ),
    );
    $event = \core\event\dashboards_reset::create($eventparams);
    $event->trigger();
}

//function to show student account

//function to sho user's enrolled  courses
function student_courselist($userid,$creatorid,$roleid){
    global $OUTPUT,$CFG,$DB,$PAGE;
    $count = 0;$flag = 0; 
    $content = '';
    $msg = '';
    $content.='<div id="exTab2" class=""> 
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a  href="#1" data-toggle="tab">ACADEMIC COURSES</a>
                        </li>
                        <li>
                            <a href="#2" data-toggle="tab">SUBSCRIBED INDUSTRY COURSES</a>
                        </li>
                        <li>
                            <a href="#3" data-toggle="tab">INDUSTRY PARTNER COURSES</a>
                        </li>
                    </ul>';
    $courses = enrol_get_users_courses($userid,true);
    if($courses){
        //print_object($courses);
        $content .='<div class="tab-content ">
                <div class="tab-pane active" id="1">';
                 $content .= '<table id="example1" class="table table-striped table-bordered datatable" style="width:100%">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Course Category</th>
                        <th>Action</th>
                    </tr>
                </thead><tbody>';
                foreach ($courses as $course) {
                    if($DB->record_exists('course_type',array('creatorid'=>$creatorid,'courseid'=>$course->id,'subscribed'=>0))){
                        $academic_courses = $DB->get_records('course_type',array('creatorid'=>$creatorid,'courseid'=>$course->id,'subscribed'=>0));;
                        $content .=usercourselist($roleid,$academic_courses,1);
                    }else{
                        $flag = 1;
                    }
                }
                $content .= '</tbody></table>';
                if($flag == 1){
                    $content .= '<p class="nocourse">You do not  have any academic courses</p>'; 
                }
                $content .='</div>
                <div class="tab-pane" id="2">';
                $content .= '<table id="example1" class="table table-striped table-bordered datatable" style="width:100%">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Course Category</th>
                        <th>Valid Upto</th>
                        <th>Action</th>
                    </tr>
                </thead><tbody>';
                foreach ($courses as $cid => $course) {
                    if($DB->record_exists('course_type',array('creatorid'=>$creatorid,'courseid'=>$course->id,'subscribed'=>1))){
                        $subscibed_courses = $DB->get_records('course_type',array('creatorid'=>$creatorid,'courseid'=>$course->id,'subscribed'=>1));;
                        $content .=usercourselist($roleid,$subscibed_courses,2);
                    }else{
                        $flag = 2;
                    }
                }
                $content .= '</tbody></table>';
                if($flag == 2){
                    $content .= '<p class="nocourse">You do not  have any subscribed industry courses</p>'; 
                }
                $content .= '</div><div class="tab-pane" id="3">';
                $content .= '<table id="example1" class="table table-striped table-bordered datatable" style="width:100%">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Course Category</th>
                        <th>Valid Upto</th>
                        <th>Action</th>
                    </tr>
                </thead><tbody>';
                foreach ($courses as $cid => $course) {
                    if($DB->record_exists('course_type',array('creatorid'=>$creatorid,'courseid'=>$course->id,'subscribed'=>2))){
                        $industry_courses = $DB->get_records('course_type',array('creatorid'=>$creatorid,'courseid'=>$course->id,'subscribed'=>2));;
                        $content .=usercourselist($roleid,$industry_courses,2);
                    }else{
                        $flag = 3;
                    }
                }
                
                $content .= '</tbody></table>';
                if($flag == 3){
                    $content .= '<p class="nocourse">You do not  have any industry partner courses</p>'; 
                }
                $content .= '</div></div>';
    }else{
        $content .= 'You do not have any courses'; 
    }
    $content .= '</div>';
    //print_object($categories2);die();
    echo $content;
}

function usercourselist($role,array $courses, $coursetype){
    global $CFG,$DB,$USER;
    $crslist = '';
    $i = 1;
    foreach ($courses as $course) {
        $crs = $DB->get_record('course',array('id'=>$course->courseid),'id,shortname,fullname,category');
        $crs_cat = $DB->get_record('course_categories',array('id'=>$crs->category),'id,name');
        $viewcourse = html_writer::link(new moodle_url('/course/view.php',array('id' => $crs->id)),'Start Learning',array('class'=>'courselistlink'));
        if($coursetype == 1){
            $crslist .= '<tr>
                            <td>'.$i.'</td>
                            <td>'.$crs->shortname.'</td>
                            <td>'.$crs->fullname.'</td>
                            <td>'.$crs_cat->name.'</td>
                            <td>'.$viewcourse.'</td>';
            $crslist .='</tr>';
        }else{
            $enrolid = $DB->get_record('enrol',array('courseid'=>$course->courseid,'enrol'=>'manual'),'id');
            $enrolment = $DB->get_record('user_enrolments',array('enrolid'=>$enrolid->id,'userid'=>$USER->id),'timeend');
            $crslist .= '<tr>
                            <td>'.$i.'</td>
                            <td>'.$crs->shortname.'</td>
                            <td>'.$crs->fullname.'</td>
                            <td>'.$crs_cat->name.'</td>
                            <td>'.$enrolment->timeend.'</td>
                            <td>'.$viewcourse.'</td>';
            $crslist .='</tr>';
        }
        $i++;
    }  
    return $crslist;                
}


class my_syspage_block_manager extends block_manager {
    // HACK WARNING!
    // TODO: figure out a better way to do this
    /**
     * Load blocks using the system context, rather than the user's context.
     *
     * This is needed because the My Moodle pages set the page context to the
     * user's context for access control, etc.  But the blocks for the system
     * pages are stored in the system context.
     */
    public function load_blocks($includeinvisible = null) {
        $origcontext = $this->page->context;
        $this->page->context = context_system::instance();
        parent::load_blocks($includeinvisible);
        $this->page->context = $origcontext;
    }
}
