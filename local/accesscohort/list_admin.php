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
//defined('MOODLE_INTERNAL') || die();

require('../../config.php');
require_login(0 , FALSE);
$context = context_system::instance();
$createorgcap = has_capability('local/accesscohort:addorganization',$context);
$PAGE->set_context(context_system::instance());

$title = get_string('list_org_admin', 'local_accesscohort');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin');
$PAGE->set_url('/local/accesscohort/list_admin.php');
//$PAGE->navbar->add($title);
$PAGE->navbar->ignore_active();
//we can extend number of link here 
$previewnode = $PAGE->navbar->add(get_string('pluginname','local_accesscohort'),$CFG->wwwroot.'/local/accesscohort/index.php');
$previewnode = $previewnode->add(get_string('list_org','local_accesscohort'),$CFG->wwwroot.'/local/accesscohort/list_org.php');
echo $OUTPUT->header();
echo '<h2>'.get_string('list_org_admin','local_accesscohort').'</h2>';
echo '<br>';
//echo '<p>'.get_string('orglist','local_accesscohort').'</p>';
if(is_siteadmin()){
    echo html_writer::link(
        new moodle_url(
            $CFG->wwwroot.'/local/accesscohort/organization_admin.php'
            ),
        'Add User in Organization',
        array(
          'class' => 'btn btn-primary'
          )
        );
    echo '<br><br>';  
    $organizations = $DB->get_records('local_oragnization_admin');
    if($organizations){        
        $table = new html_table();
        $table->head = (array) get_strings(array('name', 'username','action'), 'local_accesscohort');
        foreach($organizations as $organization) {
            $orgname = $DB->get_record('local_organization',array('id'=>$organization->orgid),'org_name');
            $username = $DB->get_record('user',array('id'=>$organization->userid),'firstname,lastname');
            $table->data[] = array(
              $orgname->org_name,
              $username->firstname.'-'.$username->lastname,

              html_writer::link(
                new moodle_url(
                    $CFG->wwwroot.'/local/accesscohort/edit_organization_admin.php',
                    array(
                        'id' => $organization->id,
                        'edit'=>1
                        )
                    ),
                'Edit',
                array(
                    'class' => 'btn btn-small btn-primary'
                    )
                ).'-'.
              html_writer::link(
                new moodle_url(
                    $CFG->wwwroot.'/local/accesscohort/delete_org_admin.php',
                    array('id' => $organization->id)), 'Delete',array('class' =>'btn btn-small btn-primary' ))
              );
        }
        echo html_writer::table($table);
    }else{
        echo html_writer::div(
            get_string('no', 'local_accesscohort'),'alert alert-danger'
            );
    }
}else{
    echo html_writer::div(
        get_string('cap', 'local_accesscohort'),'alert alert-danger'
        );
}
echo $OUTPUT->footer();
