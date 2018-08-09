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

require('../../../config.php');
require_once($CFG->dirroot .'/local/intelliboard/locallib.php');
require_once($CFG->dirroot .'/local/intelliboard/competencies/lib.php');
require_once($CFG->dirroot .'/local/intelliboard/competencies/tables.php');

$action = optional_param('action', '', PARAM_ALPHANUMEXT);
$search = clean_raw(optional_param('search', '', PARAM_RAW));
$courseid = optional_param('id', 0, PARAM_INT);
$userid = optional_param('userid', 0, PARAM_INT);
$competencyid = optional_param('competencyid', 0, PARAM_INT);
$download = optional_param('download', '', PARAM_ALPHA);

require_login();
intelliboard_competency_access();

if ($search) {
	require_sesskey();
}

if (!get_config('local_intelliboard', 'competency_dashboard')) {
	throw new moodle_exception('invalidaccess', 'error');
}

$params = array(
	'do'=>'competencies',
	'mode'=> 3
);
$intelliboard = intelliboard($params);

if (isset($intelliboard->content)) {
    $factorInfo = json_decode($intelliboard->content);
} else {
	$factorInfo = '';
}
$params = array(
	"id"=>$courseid,
	"userid"=>$userid,
	"action"=>$action,
	"search"=> $search,
	'competencyid'=> $competencyid,
	"sesskey"=>sesskey()
);
$PAGE->set_url(new moodle_url("/local/intelliboard/competencies/courses.php",$params));
$PAGE->set_pagetype('competencies');
$PAGE->set_pagelayout('report');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('intelliboardroot', 'local_intelliboard'));
$PAGE->set_heading(get_string('intelliboardroot', 'local_intelliboard'));
$PAGE->requires->jquery();
$PAGE->requires->js('/local/intelliboard/assets/js/jquery.circlechart.js');
$PAGE->requires->css('/local/intelliboard/assets/css/style.css');

if ($action == 'learner') {
	$table = new intelliboard_learner_table('table', $courseid, $userid, $search);
	$data = intelliboard_learner_total($userid, $courseid);
	$user = $DB->get_record('user', array('id'=>$userid));
	$PAGE->navbar->add(get_string('a12','local_intelliboard'), new moodle_url('/local/intelliboard/competencies/courses.php', array(
		"id"=>$courseid,
		"action"=> 'proficient'
	)));
	$PAGE->navbar->add(get_string('a28','local_intelliboard'), new moodle_url('/local/intelliboard/competencies/courses.php', array(
		"id"=>$courseid,
		"userid"=>$userid,
		"action"=> 'learner'
	)));
} elseif($action == 'activities') {
	$table = new intelliboard_activities_table('table', $courseid, $competencyid, $search);
	$data = intelliboard_learners_total($courseid, $competencyid);
	$PAGE->navbar->add(get_string('a1','local_intelliboard'), new moodle_url('/local/intelliboard/competencies/courses.php', array(
		"id"=>$courseid,
		"action"=> 'competencies'
	)));
	$PAGE->navbar->add(get_string('activities','local_intelliboard'), new moodle_url('/local/intelliboard/competencies/courses.php', array(
		"id"=>$courseid,
		'competencyid'=> $competencyid,
		"action"=> 'activities'
	)));
} elseif($action == 'learners') {
	$table = new intelliboard_learners_table('table', $courseid, $competencyid, $search);
	$data = intelliboard_learners_total($courseid, $competencyid);
	$PAGE->navbar->add(get_string('a1','local_intelliboard'), new moodle_url('/local/intelliboard/competencies/courses.php', array(
		"id"=>$courseid,
		"action"=> 'competencies'
	)));
	$PAGE->navbar->add(get_string('learners','local_intelliboard'), new moodle_url('/local/intelliboard/competencies/courses.php', array(
		"id"=>$courseid,
		'competencyid'=> $competencyid,
		"action"=> 'learners'
	)));
} elseif ($action == 'proficient') {
	$table = new intelliboard_proficient_table('table', $courseid, $search);
	$course = intelliboard_course_total($courseid);
	$PAGE->navbar->add(get_string('a12','local_intelliboard'), new moodle_url('/local/intelliboard/competencies/courses.php', array(
		"id"=>$courseid,
		"action"=> 'proficient'
	)));
} elseif ($action == 'competencies') {
	$table = new intelliboard_competencies_table('table', $courseid, $search);
	$course = intelliboard_course_total($courseid);
	$PAGE->navbar->add(get_string('a1','local_intelliboard'), new moodle_url('/local/intelliboard/competencies/courses.php', array(
		"id"=>$courseid,
		"action"=> 'competencies'
	)));
} else {
	$table = new intelliboard_courses_table('table', $search);
}

$table->show_download_buttons_at(array());
$table->is_downloading('', '', '');

echo $OUTPUT->header();
?>
<?php if(!isset($intelliboard) || !$intelliboard->token): ?>
	<div class="alert alert-error alert-block fade in " role="alert"><?php echo get_string('intelliboardaccess', 'local_intelliboard'); ?></div>
<?php else: ?>

<div class="intelliboard-page intelliboard-competencies">
	<?php include("views/menu.php"); ?>
		<div class="grades-table">

		<?php if(!empty($action)): ?>
			<div class="intelliboard-course-header clearfix">
			<?php if($action === 'competencies' or $action === 'proficient'): ?>
				<div class="activity"><?php echo substr($course->fullname, 0,1); ?></div>
				<div class="details">
				<h3><?php echo $course->fullname ?></h3>

				<span class="intelliboard-tooltip" title='<?php echo get_string('course_category','local_intelliboard'); ?>'><i class='ion-folder'></i> <?php echo $course->category; ?> </span>

				<?php if($course->startdate): ?>
				<span class="intelliboard-tooltip" title='<?php echo get_string('course_started','local_intelliboard'); ?>'><i class='ion-ios-calendar-outline'></i> <?php echo date("m/d/Y", $course->startdate); ?> </span>
				<?php endif; ?>
				</div>
				<ul class="totals">
					<li><?php echo (int)$course->learners; ?><span><?php echo get_string('a10','local_intelliboard'); ?></span></li>
					<li><?php echo (int)$course->competencies; ?> <span><?php echo get_string('a1','local_intelliboard'); ?></span></li>
					<li><?php echo (int)$course->proficiency; ?><span><?php echo get_string('a18','local_intelliboard'); ?></span></li>
				</ul>
			<?php elseif($action === 'learner'): ?>
				<div class="avatar">
					<?php echo $OUTPUT->user_picture($user, array('size'=>80)); ?>
				</div>
				<div class="details">
					<h3><?php echo fullname($user); ?></h3>
					<p><?php echo get_string('course'); ?>: <strong><?php echo format_string($data->course); ?></strong></p>
				</div>
				<ul class="totals">
					<li><?php echo (int)$data->competencycount; ?><span><?php echo get_string('a1', 'local_intelliboard'); ?></span></li>
					<li><?php echo (int)$data->proficientcompetencycount; ?><span><?php echo get_string('a2', 'local_intelliboard'); ?></span></li>
					<li><?php echo (int)$data->users_rated; ?><span><?php echo get_string('a5', 'local_intelliboard'); ?></span></li>
				</ul>
			<?php elseif($action === 'activities'): ?>
				<div class="activity"><?php echo substr($data->shortname, 0,1); ?></div>
				<div class="details">
					<h3><?php echo format_string($data->shortname); ?></h3>
					<p><?php echo get_string('course'); ?>: <strong><?php echo format_string($data->course); ?></strong></p>
				</div>
				<ul class="totals">
					<li><?php echo (int)$data->activities; ?><span><?php echo get_string('a21', 'local_intelliboard'); ?></span></li>
				</ul>
			<?php elseif($action === 'learners'): ?>
				<div class="activity"><?php echo substr($data->shortname, 0,1); ?></div>
				<div class="details">
					<h3><?php echo format_string($data->shortname); ?></h3>
					<p><?php echo get_string('course'); ?>: <strong><?php echo format_string($data->course); ?></strong></p>
				</div>
				<ul class="totals">
					<li><?php echo (int)$data->proficient; ?><span><?php echo get_string('a16', 'local_intelliboard'); ?></span></li>
					<li><?php echo (int)$data->rated; ?><span><?php echo get_string('a7', 'local_intelliboard'); ?></span></li>
					<li><?php echo (int)$data->activities; ?><span><?php echo get_string('a21', 'local_intelliboard'); ?></span></li>
				</ul>
			<?php endif; ?>
			</div>
		<?php else: ?>
			<div class="alert alert-info"><?php echo get_string('a26', 'local_intelliboard'); ?></div>
		<?php endif; ?>

		<div class="intelliboard-report-wrap">
			<div class="intelliboard-search clearfix">
				<form action="<?php echo $PAGE->url; ?>" method="GET">
					<input type="hidden" name="sesskey" value="<?php p(sesskey()); ?>" />
					<input name="id" type="hidden" value="<?php echo $courseid; ?>" />
					<input name="userid" type="hidden" value="<?php echo $userid; ?>" />
					<input name="competencyid" type="hidden" value="<?php echo $competencyid; ?>" />
					<input name="action" type="hidden" value="<?php echo $action; ?>" />

					<span class="pull-left">
					<input class="form-control" name="search" type="text" value="<?php echo $search; ?>" placeholder="<?php echo get_string('type_here','local_intelliboard'); ?>" />
					</span>
					<button class="btn btn-default"><?php echo get_string('search'); ?></button>
				</form>
			</div>
			<div class="clear"></div>
			<div class="progress-table">
				<?php $table->out(10, true); ?>
			</div>
		</div>
	</div>
	<?php include("../views/footer.php"); ?>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.circle-progress').percentcircle(<?php echo $factorInfo->GradesXCalculation; ?>);
		jQuery('.circle-progress-course').percentcircle(<?php echo $factorInfo->GradesZCalculation; ?>);
	});
</script>
<?php endif; ?>
<?php echo $OUTPUT->footer();
