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
 * Course renderer.
 *
 * @package    theme_moove
 * @copyright  2017 Willian Mano - conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_moove\output\core;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use html_writer;
use coursecat;
use coursecat_helper;
use stdClass;
use course_in_list;

/**
 * Renderers to align Moove's course elements to what is expect
 *
 * @package    theme_moove
 * @copyright  2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 // For datatable.
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/js/cdn/jquery.dataTables.min.js'?>"></script>
<script src="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/js/cdn/dataTables.bootstrap.js'?>"></script>
<script src="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/js/cdn/dataTables.fixedColumns.min.js'?>"></script>
<script src="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/tabletool/js/dataTables.tableTools.js'?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/css/cdn/dataTables.bootstrap.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/css/cdn/fixedColumns.bootstrap.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/tabletool/css/dataTables.tableTools.css'?>">
<?php

class course_renderer extends \core_course_renderer {
   
    /**
     * Renders the list of courses
     *
     * This is internal function, please use {@link core_course_renderer::courses_list()} or another public
     * method from outside of the class
     *
     * If list of courses is specified in $courses; the argument $chelper is only used
     * to retrieve display options and attributes, only methods get_show_courses(),
     * get_courses_display_option() and get_and_erase_attributes() are called.
     *
     * @param coursecat_helper $chelper various display options
     * @param array $courses the list of courses to display
     * @param int|null $totalcount total number of courses (affects display mode if it is AUTO or pagination if applicable),
     *     defaulted to count($courses)
     * @return string
     */
    protected function coursecat_courses(coursecat_helper $chelper, $courses, $totalcount = null) {
        global $CFG;
        if ($totalcount === null) {
            $totalcount = count($courses);
        }

        if (!$totalcount) {
            // Courses count is cached during courses retrieval.
            return '';
        }

        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_AUTO) {
            // In 'auto' course display mode we analyse if number of courses is more or less than $CFG->courseswithsummarieslimit.
            if ($totalcount <= $CFG->courseswithsummarieslimit) {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED);
            } else {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_COLLAPSED);
            }
        }

        // Prepare content of paging bar if it is needed.
        $paginationurl = $chelper->get_courses_display_option('paginationurl');
        $paginationallowall = $chelper->get_courses_display_option('paginationallowall');
        if ($totalcount > count($courses)) {
            // There are more results that can fit on one page.
            if ($paginationurl) {
                // The option paginationurl was specified, display pagingbar.
                $perpage = $chelper->get_courses_display_option('limit', $CFG->coursesperpage);
                $page = $chelper->get_courses_display_option('offset') / $perpage;
                $pagingbar = $this->paging_bar($totalcount, $page, $perpage,
                        $paginationurl->out(false, array('perpage' => $perpage)));
                if ($paginationallowall) {
                    $pagingbar .= html_writer::tag('div', html_writer::link($paginationurl->out(false, array('perpage' => 'all')),
                            get_string('showall', '', $totalcount)), array('class' => 'paging paging-showall'));
                }
            } else if ($viewmoreurl = $chelper->get_courses_display_option('viewmoreurl')) {
                // The option for 'View more' link was specified, display more link.
                $viewmoretext = $chelper->get_courses_display_option('viewmoretext', new \lang_string('viewmore'));
                $morelink = html_writer::tag('div', html_writer::link($viewmoreurl, $viewmoretext),
                        array('class' => 'paging paging-morelink'));
            }
        } else if (($totalcount > $CFG->coursesperpage) && $paginationurl && $paginationallowall) {
            // There are more than one page of results and we are in 'view all' mode, suggest to go back to paginated view mode.
            $pagingbar = html_writer::tag(
                'div',
                html_writer::link(
                    $paginationurl->out(
                        false,
                        array('perpage' => $CFG->coursesperpage)
                    ),
                    get_string('showperpage', '', $CFG->coursesperpage)
                ),
                array('class' => 'paging paging-showperpage')
            );
        }

        // Display list of courses.
        $attributes = $chelper->get_and_erase_attributes('courses');
        $content = html_writer::start_tag('div', $attributes);

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }

        $coursecount = 1;
        $content .= html_writer::start_tag('div', array('class' => 'row'));
        foreach ($courses as $course) {
            $content .= $this->coursecat_coursebox($chelper, $course, 'col-md-3');

            if ($coursecount % 4 == 0) {
                $content .= html_writer::end_tag('div');
                $content .= html_writer::start_tag('div', array('class' => 'row'));
            }

            $coursecount ++;
        }

        $content .= html_writer::end_tag('div');

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }

        if (!empty($morelink)) {
            $content .= $morelink;
        }

        $content .= html_writer::end_tag('div'); // End courses.
        return $content;
    }

    /**
     * Displays one course in the list of courses.
     *
     * This is an internal function, to display an information about just one course
     * please use {@link core_course_renderer::course_info_box()}
     *
     * @param coursecat_helper $chelper various display options
     * @param course_in_list|stdClass $course
     * @param string $additionalclasses additional classes to add to the main <div> tag (usually
     *    depend on the course position in list - first/last/even/odd)
     * @return string
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        global $CFG;
        if (!isset($this->strings->summary)) {
            $this->strings->summary = get_string('summary');
        }
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }
        if ($course instanceof stdClass) {
            require_once($CFG->libdir. '/coursecatlib.php');
            $course = new course_in_list($course);
        }
        $content = html_writer::start_tag('div', array('class' => $additionalclasses));

        $classes = trim('card clearfix');
        if ($chelper->get_show_courses() >= self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $nametag = 'h3';
        } else {
            $classes .= ' collapsed';
            $nametag = 'div';
        }

        // End coursebox.
        $content .= html_writer::start_tag('div', array(
            'class' => $classes,
            'data-courseid' => $course->id,
            'data-type' => self::COURSECAT_TYPE_COURSE,
        ));

        $content .= $this->coursecat_coursebox_content($chelper, $course);

        $content .= html_writer::end_tag('div'); // End coursebox.

        $content .= html_writer::end_tag('div'); // End col-md-4.

        return $content;
    }

    /**
     * Returns HTML to display course content (summary, course contacts and optionally category name)
     *
     * This method is called from coursecat_coursebox() and may be re-used in AJAX
     *
     * @param coursecat_helper $chelper various display options
     * @param stdClass|course_in_list $course
     * @return string
     */
    protected function coursecat_coursebox_content(coursecat_helper $chelper, $course) {
        global $CFG;

        if ($course instanceof stdClass) {
            require_once($CFG->libdir. '/coursecatlib.php');
            $course = new course_in_list($course);
        }

        // Course name.
        $coursename = $chelper->get_course_formatted_name($course);
        $coursenamelink = html_writer::link(new moodle_url('/course/view.php', array('id' => $course->id)),
                                            $coursename, array('class' => $course->visible ? '' : 'dimmed'));

        $content = $this->get_course_summary_image($course);

        $content .= html_writer::start_tag('div', array('class' => 'card-block'));
        $content .= "<h4 class='card-title'>". $coursenamelink ."</h4>";

        // Display course summary.
        if ($course->has_summary()) {
            $content .= html_writer::start_tag('p', array('class' => 'card-text'));
            $content .= $chelper->get_course_formatted_summary($course,
                    array('overflowdiv' => true, 'noclean' => true, 'para' => false));
            $content .= html_writer::end_tag('p'); // End summary.
        }

        $content .= html_writer::end_tag('div');

        $content .= html_writer::start_tag('div', array('class' => 'card-block'));

        // Print enrolmenticons.
        if ($icons = enrol_get_course_info_icons($course)) {
            foreach ($icons as $pixicon) {
                $content .= $this->render($pixicon);
            }
        }

        $content .= html_writer::start_tag('div', array('class' => 'pull-right'));
        $content .= html_writer::link(new moodle_url('/course/view.php', array('id' => $course->id)),
                            get_string('access', 'theme_moove'), array('class' => 'card-link btn btn-default'));
        $content .= html_writer::end_tag('div'); // End pull-right.

        $content .= html_writer::end_tag('div'); // End card-block.

        // Display course contacts. See course_in_list::get_course_contacts().
        if ($course->has_course_contacts()) {
            $content .= html_writer::start_tag('ul', array('class' => 'teachers'));
            foreach ($course->get_course_contacts() as $userid => $coursecontact) {
                $name = $coursecontact['rolename'].': '.
                        html_writer::link(new moodle_url('/user/view.php',
                                array('id' => $userid, 'course' => SITEID)),
                            $coursecontact['username']);
                $content .= html_writer::tag('li', $name);
            }
            $content .= html_writer::end_tag('ul'); // End teachers.
        }

        // Display course category if necessary (for example in search results).
        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_EXPANDED_WITH_CAT) {
            require_once($CFG->libdir. '/coursecatlib.php');
            if ($cat = coursecat::get($course->category, IGNORE_MISSING)) {
                $content .= html_writer::start_tag('div', array('class' => 'coursecat'));
                $content .= get_string('category').': '.
                        html_writer::link(new moodle_url('/course/index.php', array('categoryid' => $cat->id)),
                                $cat->get_formatted_name(), array('class' => $cat->visible ? '' : 'dimmed'));
                $content .= html_writer::end_tag('div'); // End coursecat.
            }
        }

        return $content;
    }

    /**
     * Returns the first course's summary issue
     *
     * @param stdClass $course the course object
     * @return string
     */
    protected function get_course_summary_image($course) {
        global $CFG;

        $contentimage = '';
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                    '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                    $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
            if ($isimage) {
                    $contentimage = html_writer::empty_tag('img', array('src' => $url, 'alt' => 'Course Image '. $course->fullname,
                        'class' => 'card-img-top w-100'));
                    break;
            }
        }

        if (empty($contentimage)) {
            $url = $CFG->wwwroot . "/theme/moove/pix/default_course.jpg";

            $contentimage = html_writer::empty_tag('img', array('src' => $url, 'alt' => 'Course Image '. $course->fullname,
                        'class' => 'card-img-top w-100'));
        }

        return $contentimage;
    }

    public function course_category($category) {
        global $OUTPUT,$CFG,$DB,$PAGE;
        $count = 0;
        require_once($CFG->libdir . '/coursecatlib.php');
        //$systemcontext = context_system::instance();
        //  $PAGE->set_context($systemcontext);
        //$categories = coursecat::make_categories_list();
        //print_object($categories);
        //return $output;
        if(!empty(optional_param('categoryid','',PARAM_INT))){
            return parent::course_category($category); 
        }
        $content = '';
        $output = '';
        $catlist = array();
        $courselist = array();
        $categorylist = $DB->get_records_sql("Select * from {course_categories} where visible=1");

        foreach($categorylist as $cid => $catlist){
            $list[]=array('id'=>$catlist->id,'name'=>$catlist->name,'count'=>$catlist->coursecount,'parent'=>$catlist->parent);
        }
        $categories2 = array(); 
        foreach($list as $categories){ 
            $categories2 [] = $categories; 
            
        }
        //print_object($categories2);
        $content .= '<div class="addcrs"><button type="button" class="btn btn-info">&#x2b; Add Industry Courses</button></div>';
        $content.='<div id="exTab2" class=""> 
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a  href="#1" data-toggle="tab">'.$categories2[0]['name'].'</a>
                            </li>
                            <li>
                                <a href="#2" data-toggle="tab">'.$categories2[1]['name'].'</a>
                            </li>
                            <li>
                                <a href="#3" data-toggle="tab">'.$categories2[2]['name'].'</a>
                            </li>
                        </ul>

                        <div class="tab-content ">
                            <div class="tab-pane active" id="1">
                                '.$this->course_tables($categories2[0]['id'],1).'
                            </div>
                            <div class="tab-pane" id="2">
                                '.$this->course_tables($categories2[1]['id'],2).'
                            </div>
                            <div class="tab-pane" id="3">
                                '.$this->course_tables($categories2[2]['id'],3).'
                            </div>
                        </div>
                   </div>';

        
        //print_object($categories2);die();
        $output .= $content;
        return $output;
    }

    public function course_tables($catid,$id){
        global $DB,$CFG;
        $courses = array();
        $content = '';
        $i = 1;
        $category = $DB->get_record('course_categories',array('id'=>$catid),'name');
        $content .= '<table id="example'.$id.'" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Course Category</th>';
                                if($id > 1){
                                  $content .= '<th>Valid Upto</th>';  
                                }
                                $content .='<th>Action'.$id.'</th>
                            </tr>
                        </thead>';
        $content .= '<tbody>';
                        $courses = $DB->get_records('course',array('category'=>$catid));
                        foreach ($courses as $crs) {
                            $crslink = html_writer::link(new moodle_url('/course/view.php',array('id' => $crs->id)),'Start Learning');
                            $content .= '<tr>
                                            <td>'.$i.'</td>
                                            <td>'.$crs->idnumber.'</td>
                                            <td>'.$crs->fullname.'</td>
                                            <td>'.$category->name.'</td>';
                            if($id > 1){
                              $content .= '<td>'.userdate($crs->enddate).'</td>';  
                            }
                            $content .= '<td><button type="button" class="btn btn-info crslink">'.$crslink.'</button></td>';
                            $content .='</tr>';
                            $i++;
                        }  
                    //unset($i);unset($ca);                       
                    $content .= '</tbody>
                </table>';
        return $content;            
    }

}
