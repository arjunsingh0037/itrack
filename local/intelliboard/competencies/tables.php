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
 * This plugin provides access to Moodle data in form of analytics and reports in real time.
 *
 *
 * @package    local_intelliboard
 * @copyright  2017 IntelliBoard, Inc
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @website    https://intelliboard.net/
 */

require_once($CFG->libdir . '/tablelib.php');
require_once($CFG->libdir . '/gradelib.php');

class intelliboard_courses_table extends table_sql {

    function __construct($uniqueid, $search = '') {
        global $CFG, $PAGE, $DB, $USER;

        parent::__construct($uniqueid);

        $headers = array();
        $columns = array();

        $columns[] =  'course';
        $headers[] =  get_string('course');

        $columns[] =  'competencies';
        $headers[] =  get_string('a1','local_intelliboard');

        $columns[] =  'learners';
        $headers[] =  get_string('a10','local_intelliboard');

        $columns[] =  'rated';
        $headers[] =  get_string('a7','local_intelliboard');

        $columns[] =  'proficiency';
        $headers[] =  get_string('a9','local_intelliboard');

        $columns[] =  'actions';
        $headers[] =  get_string('actions','local_intelliboard');

        $this->define_headers($headers);
        $this->define_columns($columns);

        $sql = "";
        $params = array('userid' => $USER->id);
        if($search){
            $sql .= " AND " . $DB->sql_like('c.fullname', ":fullname", false, false);
            $params['fullname'] = "%$search%";
        }
        $learner_roles = get_config('local_intelliboard', 'filter11');
        list($sql2, $params) = intelliboard_filter_in_sql($learner_roles, "ra.roleid", $params);

        if(!is_siteadmin()){
            list($sql1, $sql_params) = $DB->get_in_or_equal(explode(',', get_config('local_intelliboard', 'filter10')), SQL_PARAMS_NAMED, 'r');
            $params = array_merge($params,$sql_params);

            $sql .= " AND c.id IN (SELECT ctx.instanceid FROM {role_assignments} ra, {context} ctx WHERE ctx.id = ra.contextid AND ctx.contextlevel = 50 AND ra.roleid $sql1 AND ra.userid = :userid GROUP BY ctx.instanceid)";
        }

        $fields = "c.id, c.fullname AS course,
            (SELECT COUNT(DISTINCT cu.id) FROM {competency_usercompcourse} cu WHERE cu.courseid = c.id AND cu.proficiency = 1) AS proficiency,
            (SELECT COUNT(DISTINCT cc.competencyid) FROM {competency_coursecomp} cc WHERE cc.courseid = c.id) AS competencies,
            (SELECT COUNT(DISTINCT ra.userid) FROM {role_assignments} ra, {context} ctx WHERE ctx.id = ra.contextid AND ctx.contextlevel = 50 $sql2 AND ctx.instanceid = c.id) AS learners,
            (SELECT COUNT(DISTINCT cu.id) FROM {competency_usercompcourse} cu WHERE cu.courseid = c.id AND cu.grade IS NOT NULL) AS rated,
            '' AS Actions";
        $from = "{course} c";
        $where = "c.id IN (SELECT courseid FROM {competency_coursecomp}) AND c.visible = 1 $sql";
        $this->set_sql($fields, $from, $where, $params);
        $this->define_baseurl($PAGE->url);
    }
     function col_course($values) {
        global $CFG;

        return html_writer::link(new moodle_url($CFG->wwwroot.'/course/view.php', array('id'=>$values->id)), $values->course, array("target"=>"_blank"));
    }
    function col_competencies($values) {
        return intval($values->competencies);
    }
    function col_rated($values) {
        return intval($values->rated);
    }
    function col_proficiency($values) {
        return intval($values->proficiency);
    }
    function col_learners($values) {
        return intval($values->learners);
    }
    function col_actions($values) {
        global  $PAGE;

        $html = html_writer::start_tag("div",array("style"=>"width:240px; margin: 5px 0;"));
        $html .= html_writer::link(new moodle_url($PAGE->url, array('action'=>'competencies', 'id'=>$values->id)), get_string('a1','local_intelliboard'), array('class' =>'btn btn-default', 'title' => get_string('a11','local_intelliboard')));
        $html .= "&nbsp";
        $html .= html_writer::link(new moodle_url($PAGE->url, array('action'=>'proficient', 'id'=>$values->id)), get_string('a12','local_intelliboard'), array('class' =>'btn btn-default', 'title' => get_string('a12','local_intelliboard')));
        $html .= html_writer::end_tag("div");
        return $html;
    }
}

class intelliboard_competencies_table extends table_sql {

    function __construct($uniqueid, $courseid = 0, $search = '') {
        global $CFG, $PAGE, $DB, $USER;

        parent::__construct($uniqueid);

        $headers = array();
        $columns = array();

        $columns[] =  'shortname';
        $headers[] =  get_string('a13','local_intelliboard');

        $columns[] =  'idnumber';
        $headers[] =  get_string('idnumber');

        $columns[] =  'created';
        $headers[] =  get_string('a14','local_intelliboard');

        $columns[] =  'asigned';
        $headers[] =  get_string('a15','local_intelliboard');

        $columns[] =  'activities';
        $headers[] =  get_string('a3','local_intelliboard');

        $columns[] =  'rated';
        $headers[] =  get_string('a7','local_intelliboard');

        $columns[] =  'proficient';
        $headers[] =  get_string('a9','local_intelliboard');

        $columns[] =  'actions';
        $headers[] =  get_string('actions','local_intelliboard');

        $this->define_headers($headers);
        $this->define_columns($columns);

        $sql = "";
        $params = array('courseid' => $courseid);
        if($search){
            $sql .= " AND " . $DB->sql_like('c.shortname', ":shortname", false, false);
            $params['shortname'] = "%$search%";
        }

        $learner_roles = get_config('local_intelliboard', 'filter11');
        list($sql2, $params) = intelliboard_filter_in_sql($learner_roles, "ra.roleid", $params);

        $fields = "c.id, cc.courseid, c.shortname, c.idnumber, c.timecreated AS created, cc.timecreated AS asigned,
            (SELECT COUNT(DISTINCT cu.id) FROM {competency_usercompcourse} cu WHERE cu.competencyid = c.id AND cu.courseid = cc.courseid AND cu.proficiency = 1) AS proficient,
            (SELECT COUNT(DISTINCT cu.id) FROM {competency_usercompcourse} cu WHERE cu.competencyid = c.id AND cu.courseid = cc.courseid AND cu.grade IS NOT NULL) AS rated,
            (SELECT COUNT(DISTINCT m.cmid) FROM {course_modules} cm, {competency_modulecomp} m WHERE cm.visible = 1 AND m.cmid = cm.id AND cm.course = cc.courseid AND m.competencyid = cc.competencyid) AS activities, '' AS Actions";
        $from = "{competency_coursecomp} cc
            LEFT JOIN {competency} c ON c.id = cc.competencyid";
        $where = "cc.courseid = :courseid $sql";

        $this->set_sql($fields, $from, $where, $params);
        $this->define_baseurl($PAGE->url);
    }
    function col_created($values) {
      return ($values->created) ? date("m/d/Y", $values->created) : '-';
    }
    function col_asigned($values) {
      return ($values->asigned) ? date("m/d/Y", $values->asigned) : '-';
    }
    function col_proficient($values) {
        return intval($values->proficient);
    }
    function col_rated($values) {
        return intval($values->rated);
    }
    function col_actions($values) {
        global  $PAGE;

        $html = html_writer::start_tag("div",array("style"=>"width:170px; margin: 5px 0;"));
        $html .= html_writer::link(new moodle_url($PAGE->url, array('action'=>'learners', 'id'=>$values->courseid, 'competencyid'=>$values->id)), get_string('learners','local_intelliboard'), array('class' =>'btn btn-default'));
        $html .= "&nbsp";
        $html .= html_writer::link(new moodle_url($PAGE->url, array('action'=>'activities', 'id'=>$values->courseid, 'competencyid'=>$values->id)), get_string('activities','local_intelliboard'), array('class' =>'btn btn-default'));
        $html .= html_writer::end_tag("div");
        return $html;
    }
}


class intelliboard_learner_table extends table_sql {

    function __construct($uniqueid, $courseid = 0, $userid = 0, $search = '') {
        global $CFG, $PAGE, $DB, $USER;

        parent::__construct($uniqueid);

        $headers = array();
        $columns = array();

        $columns[] =  'shortname';
        $headers[] =  get_string('a13','local_intelliboard');

        $columns[] =  'idnumber';
        $headers[] =  get_string('idnumber');

        $columns[] =  'grade';
        $headers[] =  get_string('a17','local_intelliboard');

        $columns[] =  'proficiency';
        $headers[] =  get_string('a16','local_intelliboard');

        $columns[] =  'rated';
        $headers[] =  get_string('a19','local_intelliboard');

        $columns[] =  'usermodified';
        $headers[] =  get_string('a20','local_intelliboard');

        $this->define_headers($headers);
        $this->define_columns($columns);

        $sql = "";
        $params = array(
            'courseid' => $courseid,
            'userid' => $userid
        );
        if($search){
            $sql .= " AND " . $DB->sql_like('c.shortname', ":shortname", false, false);
            $params['shortname'] = "%$search%";
        }

        $fields = "c.id, cc.courseid, c.shortname, c.description, c.idnumber, cu.proficiency, cu.grade, cu.usermodified, CONCAT(u2.firstname, ' ', u2.lastname) AS usermodifier, cu.timemodified AS rated, cf.scaleid";
        $from = "{competency_coursecomp} cc
            LEFT JOIN {user} u ON u.id = :userid
            LEFT JOIN {competency} c ON c.id = cc.competencyid
            LEFT JOIN {competency_framework} cf ON cf.id = c.competencyframeworkid
            LEFT JOIN {competency_usercompcourse} cu ON cu.competencyid = c.id AND cu.courseid = cc.courseid AND cu.userid = u.id
            LEFT JOIN {user} u2 ON u2.id = cu.usermodified";
        $where = "cc.courseid = :courseid $sql";

        $this->set_sql($fields, $from, $where, $params);
        $this->define_baseurl($PAGE->url);
    }
    function col_grade($values) {
        if ($values->grade) {
            $scale = grade_scale::fetch(array('id'=>$values->scaleid));
            $scale->load_items();
            $grade = $scale->scale_items[(int)$values->grade-1];
            return html_writer::tag("div", $grade, array("class" => "label"));
        } else {
            return get_string('a35','local_intelliboard');
        }

    }
    function col_rated($values) {
        return date('m/d/Y', $values->rated);
    }
    function col_usermodified($values) {
        global $CFG;
        return html_writer::link(new moodle_url($CFG->wwwroot.'/user/view.php', array('id'=>$values->usermodified)), $values->usermodifier, array("target"=>"_blank"));
    }
    function col_proficiency($values) {
        $class = ($values->proficiency)?'label-success':'label-important';
        return html_writer::tag("div", get_string($values->proficiency ? 'yes' : 'no'), array("class" => "label $class"));
    }
}

class intelliboard_activities_table extends table_sql {

    function __construct($uniqueid, $courseid = 0, $competencyid = 0, $search = '') {
        global $CFG, $PAGE, $DB, $USER;

        parent::__construct($uniqueid);

        $headers = array();
        $columns = array();

        $columns[] =  'activity';
        $headers[] =  get_string('activity_name','local_intelliboard');

        $columns[] =  'module';
        $headers[] =  get_string('type','local_intelliboard');

        $columns[] =  'completed';
        $headers[] =  get_string('a25','local_intelliboard');

        $columns[] =  'grade';
        $headers[] =  get_string('in19','local_intelliboard');

        $this->define_headers($headers);
        $this->define_columns($columns);


        $params = array(
            'courseid' => $courseid,
            'competencyid' => $competencyid
        );
        $sql = "";
        if($search){
            $sql .= " AND " . $DB->sql_like('m.name', ":shortname", false, false);
            $params['shortname'] = "%$search%";
        }


        $sql_columns = "";
        $modules = $DB->get_records_sql("SELECT m.id, m.name FROM {modules} m WHERE m.visible = 1");
        foreach($modules as $module){
            $sql_columns .= " WHEN m.name='{$module->name}' THEN (SELECT name FROM {".$module->name."} WHERE id = cm.instance)";
        }
        $sql_columns =  ($sql_columns) ? ", CASE $sql_columns ELSE 'none' END AS activity" : "'' AS activity";
        $grade_avg = intelliboard_grade_sql(true);
        $completion = intelliboard_compl_sql("cmc.");

        $fields = "cm.id, m.name AS module,
            (SELECT COUNT(cmc.id) FROM {course_modules_completion} cmc WHERE cmc.coursemoduleid = cm.id $completion) AS completed,
            (SELECT $grade_avg FROM {grade_items} gi, {grade_grades} g WHERE gi.itemtype = 'mod' AND g.itemid = gi.id AND g.finalgrade IS NOT NULL AND gi.iteminstance = cm.instance AND gi.itemmodule = m.name) AS grade
            $sql_columns";
        $from = "{modules} m, {competency_modulecomp} cym, {course_modules} cm";
        $where = "m.id = cm.module AND cm.visible = 1 AND cm.course = :courseid AND cym.cmid = cm.id AND cym.competencyid = :competencyid $sql";

        $this->set_sql($fields, $from, $where, $params);
        $this->define_baseurl($PAGE->url);
    }
    function col_grade($values) {
        $html = html_writer::start_tag("div",array("class"=>"grade"));
        $html .= html_writer::tag("div", "", array("class"=>"circle-progress", "data-percent"=>(int)$values->grade));
        $html .= html_writer::end_tag("div");
        return $html;
    }
    function col_completed($values) {
        return intval($values->completed);
    }
}
class intelliboard_learners_table extends table_sql {

    function __construct($uniqueid, $courseid = 0, $competencyid = 0, $search = '') {
        global $CFG, $PAGE, $DB, $USER;

        parent::__construct($uniqueid);

        $headers = array();
        $columns = array();

        $columns[] =  'firstname';
        $headers[] =  get_string('te12','local_intelliboard');

        $columns[] =  'lastname';
        $headers[] =  get_string('te13','local_intelliboard');

        $columns[] =  'proficiency';
        $headers[] =  get_string('a16','local_intelliboard');

        $columns[] =  'grade';
        $headers[] =  get_string('a17','local_intelliboard');

        $columns[] =  'rated';
        $headers[] =  get_string('a19','local_intelliboard');

        $columns[] =  'usermodified';
        $headers[] =  get_string('a20','local_intelliboard');

        $columns[] =  'evidences';
        $headers[] =  get_string('a6','local_intelliboard');

        $this->define_headers($headers);
        $this->define_columns($columns);


        $params = array(
            'courseid' => $courseid,
            'competencyid' => $competencyid
        );
        $learner_roles = get_config('local_intelliboard', 'filter11');
        list($sql, $params) = intelliboard_filter_in_sql($learner_roles, "ra.roleid", $params);
        if($search){
            $sql .= " AND " . $DB->sql_like('u.firstname', ":firstname", false, false);
            $params['firstname'] = "%$search%";
        }
        $fields = "u.id, u.firstname, u.lastname, u.email, cu.proficiency, cu.grade, cu.usermodified, CONCAT(u2.firstname, ' ', u2.lastname) AS usermodifier, cu.timemodified AS rated, cf.scaleid,
            (SELECT COUNT(DISTINCT ce.id) FROM {competency_usercomp} cu, {competency_evidence} ce WHERE cu.competencyid = c.id AND ce.usercompetencyid = cu.id AND cu.userid = u.id AND ce.contextid = ctx.id) AS evidences";
        $from = "{role_assignments} ra
            LEFT JOIN {context} ctx ON ctx.id = ra.contextid AND ctx.contextlevel = 50
            LEFT JOIN {user} u ON u.id = ra.userid
            LEFT JOIN {competency} c ON c.id = :competencyid
            LEFT JOIN {competency_framework} cf ON cf.id = c.competencyframeworkid
            LEFT JOIN {competency_usercompcourse} cu ON cu.courseid = ctx.instanceid AND cu.userid = u.id AND cu.competencyid = c.id
            LEFT JOIN {user} u2 ON u2.id = cu.usermodified
        ";
        $where = "ctx.instanceid = :courseid $sql";

        $this->set_sql($fields, $from, $where, $params);
        $this->define_baseurl($PAGE->url);
    }
    function col_rated($values) {
        return date('m/d/Y', $values->rated);
    }
    function col_usermodified($values) {
        global $CFG;
        return html_writer::link(new moodle_url($CFG->wwwroot.'/user/view.php', array('id'=>$values->usermodified)), $values->usermodifier, array("target"=>"_blank"));
    }
    function col_grade($values) {
        if ($values->grade) {
            $scale = grade_scale::fetch(array('id'=>$values->scaleid));
            $scale->load_items();
            $grade = $scale->scale_items[(int)$values->grade-1];
            return html_writer::tag("div", $grade, array("class" => "label"));
        } else {
            return get_string('a35','local_intelliboard');
        }

    }
    function col_proficiency($values) {
        $class = ($values->proficiency)?'label-success':'label-important';
        return html_writer::tag("div", get_string($values->proficiency ? 'yes' : 'no'), array("class" => "label $class"));
    }
}


class intelliboard_proficient_table extends table_sql {

    function __construct($uniqueid, $courseid = 0, $search = '') {
        global $CFG, $PAGE, $DB, $USER;

        parent::__construct($uniqueid);

        $headers = array();
        $columns = array();

        $columns[] =  'firstname';
        $headers[] =  get_string('te12','local_intelliboard');

        $columns[] =  'lastname';
        $headers[] =  get_string('te13','local_intelliboard');

        $columns[] =  'users_rated';
        $headers[] =  get_string('a23','local_intelliboard');

        $columns[] =  'proficientcompetencycount';
        $headers[] =  '% ' . get_string('a16','local_intelliboard');

        $columns[] =  'competencycount';
        $headers[] =  get_string('a22','local_intelliboard');

        $columns[] =  'users_evidences';
        $headers[] =  get_string('a24','local_intelliboard');

        $columns[] =  'actions';
        $headers[] =  get_string('actions','local_intelliboard');

        $this->define_headers($headers);
        $this->define_columns($columns);


        $params = array('courseid' => $courseid);
        $learner_roles = get_config('local_intelliboard', 'filter11');
        list($sql, $params) = intelliboard_filter_in_sql($learner_roles, "ra.roleid", $params);
        if($search){
            $sql .= " AND " . $DB->sql_like('u.firstname', ":firstname", false, false);
            $params['firstname'] = "%$search%";
        }

        $fields = "u.id, u.firstname,  u.lastname, u.email, ctx.instanceid AS courseid, '' AS actions,
            (SELECT COUNT(DISTINCT comp.id)
                FROM {competency_coursecomp} coursecomp
                    JOIN {competency} comp ON coursecomp.competencyid = comp.id
                WHERE coursecomp.courseid = ctx.instanceid) AS competencycount,
            (SELECT COUNT(DISTINCT cu.competencyid)
                FROM {competency_usercompcourse} cu WHERE cu.courseid = ctx.instanceid AND cu.userid = u.id AND cu.proficiency = 1) AS proficientcompetencycount,
            (SELECT COUNT(DISTINCT cu.id) FROM {competency_usercompcourse} cu WHERE cu.courseid = ctx.instanceid AND cu.userid = u.id AND cu.grade IS NOT NULL) AS users_rated,
            (SELECT COUNT(DISTINCT ce.id) FROM {competency_coursecomp} c, {competency_usercomp} cu, {competency_evidence} ce WHERE c.courseid = ctx.instanceid AND cu.competencyid = c.competencyid AND ce.usercompetencyid = cu.id AND cu.userid = u.id AND ce.contextid = ctx.id) AS users_evidences";
        $from = "{role_assignments} ra
            LEFT JOIN {context} ctx ON ctx.id = ra.contextid AND ctx.contextlevel = 50
            LEFT JOIN {user} u ON u.id = ra.userid";
        $where = "ctx.instanceid = :courseid $sql";

        $this->set_sql($fields, $from, $where, $params);
        $this->define_baseurl($PAGE->url);
    }
    function col_competencycount($values) {
        return $values->proficientcompetencycount . get_string('a27','local_intelliboard') . $values->competencycount;
    }
    function col_proficientcompetencycount($values) {
        $proficient = ($values->proficientcompetencycount) ? number_format((($values->proficientcompetencycount / $values->competencycount) * 100), 1) : 0;
        $html = html_writer::start_tag("div",array("class"=>"progress"));
        $html .= html_writer::tag("div", $proficient, array(
            "class" => "bar",
            "aria-valuenow" => "$proficient",
            "aria-valuemin" => "0",
            "aria-valuemax" => "100",
            "role" => "progressbar",
            "style" => "width: $proficient%"));
        $html .= html_writer::end_tag("div");
        return $html;
    }

    function col_actions($values) {
        global  $PAGE;

        return html_writer::link(new moodle_url($PAGE->url, array('action'=>'learner', 'id'=>$values->courseid, 'userid'=>$values->id)), get_string('a28','local_intelliboard'), array('class' =>'btn btn-default'));

    }
}
