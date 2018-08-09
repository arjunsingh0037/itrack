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
require($CFG->dirroot.'/local/accesscohort/lib.php');
require_once($CFG->libdir.'/adminlib.php');

$contextid = optional_param('contextid', 0, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);
$searchquery  = optional_param('search', '', PARAM_RAW);
$showall = optional_param('showall', false, PARAM_BOOL);

$systemcontext= context_system::instance();
$createmapcap = has_capability('local/accesscohort:adduseraccesscohort',$systemcontext);
$title = get_string('cohortplugin', 'local_accesscohort');
$PAGE->set_context(context_system::instance());
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin');
$PAGE->set_url($CFG->wwwroot . '/local/accesscohort/index.php');
//$PAGE->navbar->add($title);
$PAGE->navbar->ignore_active();
//we can extend number of link here 
$previewnode = $PAGE->navbar->add(get_string('cohortplugin', 'local_accesscohort'),$CFG->wwwroot.'/local/accesscohort/index.php');
require_login();

if ($contextid) {
    $context = context::instance_by_id($contextid, MUST_EXIST);
} else {
    $context = context_system::instance();
}

if ($context->contextlevel != CONTEXT_COURSECAT and $context->contextlevel != CONTEXT_SYSTEM) {
    print_error('invalidcontext');
}

$category = null;
if ($context->contextlevel == CONTEXT_COURSECAT) {
    $category = $DB->get_record('course_categories', array('id'=>$context->instanceid), '*', MUST_EXIST);
}
//we need to check about capability 
/*$manager = has_capability('moodle/cohort:manage', $context);
$canassign = has_capability('moodle/cohort:assign', $context);
if (!$manager) {
    require_capability('moodle/cohort:view', $context);
}*/

$strcohorts = get_string('pluginname', 'local_accesscohort');

if ($category) {
    $PAGE->set_pagelayout('admin');
    $PAGE->set_context($context);
    $PAGE->set_url('/local/accesscohort/index.php', array('contextid'=>$context->id));
    $PAGE->set_title($strcohorts);
    $PAGE->set_heading($COURSE->fullname);
    $showall = false;
} else {
  //  admin_externalpage_setup('cohorts', '', null, '', array('pagelayout'=>'report'));
}

echo $OUTPUT->header();

if ($showall) {
    $cohorts = aceesscohort_get_all_cohorts($page, 25, $searchquery);
} else {
    $cohorts = aceesscohort_get_cohorts($context->id, $page, 25, $searchquery);
}


$count = '';


echo $OUTPUT->heading(get_string('accohort', 'local_accesscohort', $context->get_context_name()).$count);

$params = array('page' => $page);
if ($contextid) {
    $params['contextid'] = $contextid;
}
if ($searchquery) {
    $params['search'] = $searchquery;
}
if ($showall) {
    $params['showall'] = true;
}
$baseurl = new moodle_url('/local/accesscohort/index.php', $params);


// Output pagination bar.
//Mihir test echo $OUTPUT->paging_bar($cohorts['totalcohorts'], $page, 25, $baseurl);

$data = array();
$editcolumnisempty = true;
//print_object($cohorts);
if($cohorts){
    foreach($cohorts['cohorts'] as $cohort) {
        $line = array();

        $cohortcontext = context::instance_by_id($cohort->contextid);
        $cohort->description = file_rewrite_pluginfile_urls($cohort->description, 'pluginfile.php', $cohortcontext->id,
            'cohort', 'description', $cohort->id);
        if ($showall) {
            if ($cohortcontext->contextlevel == CONTEXT_COURSECAT) {
                $line[] = html_writer::link(new moodle_url('/local/accesscohort/index.php' ,
                    array('contextid' => $cohort->contextid)), $cohortcontext->get_context_name(false));
            } else {
                $line[] = $cohortcontext->get_context_name(false);
            }
        }
        $tmpl = new \core_cohort\output\cohortname($cohort);
        $line[] = $OUTPUT->render_from_template('core/inplace_editable', $tmpl->export_for_template($OUTPUT));
        $tmpl = new \core_cohort\output\cohortidnumber($cohort);
        $line[] = $OUTPUT->render_from_template('core/inplace_editable', $tmpl->export_for_template($OUTPUT));
        $line[] = format_text($cohort->description, $cohort->descriptionformat);

        $line[] = $DB->count_records('cohort_members', array('cohortid'=>$cohort->id));

        if (empty($cohort->component)) {
            $line[] = get_string('nocomponent', 'cohort');
        } else {
            $line[] = get_string('pluginname', $cohort->component);
        }

        $buttons = array();
        if (empty($cohort->component)) {
        //we need to change has capability
            $cohortmanager = has_capability('moodle/cohort:manage', $cohortcontext);
        //$cohortcanassign = has_capability('moodle/cohort:assign', $cohortcontext);

            $urlparams = array('id' => $cohort->id, 'returnurl' => $baseurl->out_as_local_url());
            $showhideurl = new moodle_url('/local/accesscohort/edit.php', $urlparams + array('sesskey' => sesskey()));
            if ($cohortmanager) {
                if ($cohort->visible) {
                    $showhideurl->param('hide', 1);
                    $visibleimg = $OUTPUT->pix_icon('t/hide', get_string('hide'));
                    $buttons[] = html_writer::link($showhideurl, $visibleimg, array('title' => get_string('hide')));
                } else {
                    $showhideurl->param('show', 1);
                    $visibleimg = $OUTPUT->pix_icon('t/show', get_string('show'));
                    $buttons[] = html_writer::link($showhideurl, $visibleimg, array('title' => get_string('show')));
                }
                $buttons[] = html_writer::link(new moodle_url('/local/accesscohort/edit.php', $urlparams + array('delete' => 1)),
                    $OUTPUT->pix_icon('t/delete', get_string('delete')),
                    array('title' => get_string('delete')));
                $buttons[] = html_writer::link(new moodle_url('/local/accesscohort/edit.php', $urlparams),
                    $OUTPUT->pix_icon('t/edit', get_string('edit')),
                    array('title' => get_string('edit')));
                $editcolumnisempty = false;
            }
        //added by prashant here 
            if($createmapcap){
                /*if ($cohortcanassign) {*/
                    $buttons[] = html_writer::link(new moodle_url('/local/accesscohort/assign.php', $urlparams),
                        $OUTPUT->pix_icon('i/users', get_string('assign', 'core_cohort')),
                        array('title' => get_string('assign', 'core_cohort')));
                    $editcolumnisempty = false;
            //}
                }
            }
            $line[] = implode(' ', $buttons);

            $data[] = $row = new html_table_row($line);
            if (!$cohort->visible) {
                $row->attributes['class'] = 'dimmed_text';
            }
        }
        $table = new html_table();
        $table->head  = array(get_string('name', 'cohort'),
         get_string('idnumber', 'cohort'), get_string('description', 'cohort'),
         get_string('memberscount', 'cohort'), get_string('component', 'cohort'));
        $table->colclasses = array('leftalign name', 'leftalign id', 'leftalign description', 'leftalign size','centeralign source');
        if ($showall) {
            array_unshift($table->head, get_string('category'));
            array_unshift($table->colclasses, 'leftalign category');
        }
        if (!$editcolumnisempty) {
            $table->head[] = get_string('edit');
            $table->colclasses[] = 'centeralign action';
        } else {
    // Remove last column from $data.
            foreach ($data as $row) {
                array_pop($row->cells);
            }
        }
        $table->id = 'cohorts';
        $table->attributes['class'] = 'admintable generaltable';
        $table->data  = $data;     
        echo html_writer::table($table);
        echo $OUTPUT->paging_bar($cohorts['totalcohorts'], $page, 25, $baseurl);
    }else{
        echo html_writer::div(
            get_string('no', 'local_accesscohort'),'alert alert-danger'
            );
    }
    echo $OUTPUT->footer();
