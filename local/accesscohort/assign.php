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

require('../../config.php');
//changes can be done by prashant
require_once($CFG->dirroot.'/local/accesscohort/locallib.php');
require_once($CFG->dirroot.'/cohort/lib.php');

$id = required_param('id', PARAM_INT);
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);

require_login();

$cohort = $DB->get_record('cohort', array('id'=>$id), '*', MUST_EXIST);
$context = context::instance_by_id($cohort->contextid, MUST_EXIST);
$systemcontext= context_system::instance();
$addusercap = has_capability('local/accesscohort:adduseraccesscohort',$systemcontext);require_login();

  $PAGE->set_context($context);
//changes can be done by prashant
  $PAGE->set_url('/local/accesscohort/assign.php', array('id'=>$id));
  $PAGE->set_pagelayout('admin');

  if ($returnurl) {
    $returnurl = new moodle_url($returnurl);
  } else {
  //changes can be done by prashant
    $returnurl = new moodle_url('/local/accesscohort/index.php', array('contextid' => $cohort->contextid));
  }

  if (!empty($cohort->component)) {
    // We can not manually edit cohorts that were created by external systems, sorry.
    redirect($returnurl);
  }

  if (optional_param('cancel', false, PARAM_BOOL)) {
    redirect($returnurl);
  }

  if ($context->contextlevel == CONTEXT_COURSECAT) {
    $category = $DB->get_record('course_categories', array('id'=>$context->instanceid), '*', MUST_EXIST);
    navigation_node::override_active_url(new moodle_url('/local/accesscohort/index.php', array('contextid'=>$cohort->contextid)));
  } else {
    navigation_node::override_active_url(new moodle_url('/local/accesscohort/index.php', array()));
  }

  //page information is adding here 
$title = get_string('assigncohorts', 'cohort');
$PAGE->set_context(context_system::instance());
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin');
//$PAGE->set_url($CFG->wwwroot . '/local/accesscohort/assign.php');

//$PAGE->navbar->add($title);
$PAGE->navbar->ignore_active();
//we can extend number of link here 
$previewnode = $PAGE->navbar->add('Access cohort',$CFG->wwwroot.'/local/accesscohort/index.php');

$thingnode = $previewnode->add($title);
$thingnode->make_active();
echo $OUTPUT->header();
if($addusercap){
echo $OUTPUT->heading(get_string('assignto', 'cohort', format_string($cohort->name)));
echo $OUTPUT->notification(get_string('removeuserwarning', 'core_cohort'));

// Get the user_selector we will need.
  $potentialuserselector = new accesscohort_candidate_selector('addselect', array('cohortid'=>$cohort->id, 'accesscontext'=>$context));
  $existinguserselector = new accesscohort_existing_selector('removeselect', array('cohortid'=>$cohort->id, 'accesscontext'=>$context));
  
 // $existinguserselector->display();
// Process incoming user assignments to the cohort

  if (optional_param('add', false, PARAM_BOOL) && confirm_sesskey()) {
    $userstoassign = $potentialuserselector->get_selected_users();
    if (!empty($userstoassign)) {

      foreach ($userstoassign as $adduser) {
        accesscohort_add_member($cohort->id, $adduser->id);
      }

      $potentialuserselector->invalidate_selected_users();
      $existinguserselector->invalidate_selected_users();
    }
  }

// Process removing user assignments to the cohort
  if (optional_param('remove', false, PARAM_BOOL) && confirm_sesskey()) {
    $userstoremove = $existinguserselector->get_selected_users();
      print_object($cohort->id);
     print_object('yu57uy');
    if (!empty($userstoremove)) {
      foreach ($userstoremove as $removeuser) {
       cohort_remove_member($cohort->id, $removeuser->id);
     }
     $potentialuserselector->invalidate_selected_users();
     $existinguserselector->invalidate_selected_users();
   }

 }


// Print the form.
 ?>
 <form id="assignform" method="post" action="<?php echo $PAGE->url ?>"><div>
  <input type="hidden" name="sesskey" value="<?php echo sesskey() ?>" />
  <input type="hidden" name="returnurl" value="<?php echo $returnurl->out_as_local_url() ?>" />

  <table summary="" class="generaltable generalbox boxaligncenter" cellspacing="0">
    <tr>
      <td id="existingcell">
        <p><label for="removeselect"><?php print_string('currentusers', 'cohort'); ?></label></p>
       <?php $existinguserselector->display() ?>
      </td>
      <td id="buttonscell">
        <div id="addcontrols">
          <input name="add" id="add" type="submit" value="<?php echo $OUTPUT->larrow().'&nbsp;'.s(get_string('add')); ?>" title="<?php p(get_string('add')); ?>" /><br />
        </div>

        <div id="removecontrols">
          <input name="remove" id="remove" type="submit" value="<?php echo s(get_string('remove')).'&nbsp;'.$OUTPUT->rarrow(); ?>" title="<?php p(get_string('remove')); ?>" />
        </div>
      </td>
      <td id="potentialcell">
        <p><label for="addselect"><?php print_string('potusers', 'cohort'); ?></label></p>
        <?php $potentialuserselector->display() ?>
      </td>
    </tr>
    <tr><td colspan="3" id='backcell'>
      <input type="submit" name="cancel" value="<?php p(get_string('backtocohorts', 'cohort')); ?>" />
    </td></tr>
  </table>
</div></form>

<?php
}else{
  echo html_writer::div(
    get_string('cap', 'local_accesscohort'),'alert alert-danger'
    );
}

echo $OUTPUT->footer();
