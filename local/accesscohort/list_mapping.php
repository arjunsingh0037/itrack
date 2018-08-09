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
$createmapcap = has_capability('local/accesscohort:addmapping',$context);


$PAGE->set_context(context_system::instance());
$title = get_string('list_map', 'local_accesscohort');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin'); 
$PAGE->set_url('/local/accesscohort/list_mapping.php');
require_login();
$PAGE->navbar->ignore_active();
//we can extend number of link here 
$previewnode = $PAGE->navbar->add(get_string('pluginname','local_accesscohort'),$CFG->wwwroot.'/local/accesscohort/index.php');
$previewnode = $previewnode->add(get_string('list_map','local_accesscohort'),$CFG->wwwroot.'/local/accesscohort/list_mapping.php');
// $thingnode->make_active();
echo $OUTPUT->header();
echo '<h2>'.get_string('formtitle_map','local_accesscohort').'</h2>';
echo '<br>';
echo '<p>'.get_string('maplist','local_accesscohort').'</p>';

if(is_siteadmin()){
//real data display 
  $sql1 = $DB->get_records('local_mapping_cohort');
  $cid=[];
  $cname = [];
  if($sql1){
    foreach ($sql1 as  $value) {
     $cid[$value->org_id.','.$value->id]= explode(',',$value->cohort_id);
   }
   if($cid){
    foreach ($cid as $key => $v1) {
      $vimp = implode(',', $v1);
     // print_object($vimp);
      $rtrim = rtrim($vimp,',');
      $ex = explode(',',$key);
      $orgname = $DB->get_record('local_organization', ['id' => $ex[0]], 'org_name');
      if($orgname){
        $cohortobj = $DB->get_records_sql("SELECT * FROM {cohort} WHERE id IN ($rtrim)");
        if($cohortobj){
          foreach ($cohortobj as  $value1) {
            $cname[ $orgname->org_name.','.$ex[1]][] = $value1->name;
          }
        }
      }
    }
  }
}
if($cname){
  echo html_writer::link(
    new moodle_url(
      $CFG->wwwroot.'/local/accesscohort/accesscohort_mapping.php'
      ),
    'Add new cohort mapping',
    array(
      'class' => 'btn btn-primary'
      )
    );
  echo '<br><br>';
  $table = new html_table();
  $table->head = (array) get_strings(array('org_sname', 'c_name', 'action'), 'local_accesscohort');
  foreach ($cname as $key4 => $value4) {
    $chname = implode(',', $value4);
    $ex2 = explode(',',$key4);
    $ids = html_writer::link(
      new moodle_url(
        $CFG->wwwroot.'/local/accesscohort/edit_accesscohort_map.php',
        array(
          'id'=>$ex2[1],
          'edit' =>1
          )
        ),'Edit',
      array('class' => 'btn btn-small btn-primary')
      ).'-'.
    html_writer::link(
      new moodle_url(
        $CFG->wwwroot.'/local/accesscohort/delete_map.php',
        array('id' => $ex2[1])), 'Delete',array('class' =>'btn btn-small btn-primary' 
        ));
    $table->data[] =  array($ex2[0],$chname,$ids);   
  }
  echo html_writer::table($table);
}else{
  echo html_writer::div(
    get_string('no', 'local_accesscohort'),'alert alert-danger');
}
}else{
  echo html_writer::div(
    get_string('cap', 'local_accesscohort'),'alert alert-danger'
    );
}


echo $OUTPUT->footer();
