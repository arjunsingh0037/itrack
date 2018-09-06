<?php
// $Id: inscriptions_massives.php 356 2010-02-27 13:15:34Z ppollet $
/**
 * A bulk enrolment plugin that allow teachers to massively enrol existing accounts to their courses,
 * with an option of adding every user to a group
 * Version for Moodle 1.9.x courtesy of Patrick POLLET & Valery FREMAUX  France, February 2010
 * Version for Moodle 2.x by pp@patrickpollet.net March 2012
 */

require(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once ('lib.php');
require_once ('stream_attribute_form.php');
require_once ('domain_attribute_form.php');
require_once ('streamjax.php');
$course = $DB->get_records('course');
require_login();
$context = context_system::instance();
//require_capability('moodle/role:assign', $context);
$PAGE->set_context($context);
/// Start making page
$PAGE->set_pagelayout('course');
$url = new moodle_url($CFG->wwwroot.'/local/eProjects/project-attr.php');
$PAGE->set_url($url);
$PAGE->requires->css('/local/eProjects/style/styles.css');
//By Arjun 
/*$currentuser = $USER->id;
$user = $DB->record_exists('trainingpartners', array('userid' => $currentuser));
if (!$user) {
    echo $OUTPUT->header();
    redirect($CFG->wwwroot.'/my','You do not have access to this page.',1,'error');
    die; 
}*/

$pattr='Project Attributes';
$PAGE->navbar->add($pattr);
$PAGE->set_title($pattr);
$PAGE->set_heading($pattr);
echo $OUTPUT->header();
$newobj = new stdClass();
echo '<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
  	<div class="form-group">
	    <label style="padding-right: 15px;">Attributes</label>
	    <label class="radio-inline"><input type="radio" name="attr_type" id="id_stream" value="Stream">Stream</label>
	    <label class="radio-inline"><input type="radio" name="attr_type" id="id_domain" value="Domain">Domain</label>
	</div>
  </div>
  <div class="panel-body">
  <div id="id_error_batchexists"></div>
    	<div class="form-group" id="stream_attribute" style="display:none;">';
	
    echo '<button type="button" id="add_stream" class="btn btn-info crslink add_stream" data-toggle="modal" data-target="#showStreamForm">&#x271A</button>';

    $table = new html_table();
    $table->head  = array('Sl.no','Stream Name','Created By', 'Updated By', 'Action');
    $table->colclasses = array ('leftalign','leftalign', 'leftalign', 'centeralign', 'leftalign');
    $table->attributes['class'] = 'admintable generaltable';
    $table->id = 'filterssetting';
    $data = array();
    $streams = $DB->get_records('stream');
    if($streams){
      $i = 1;
      foreach ($streams as $stream) {
        $creator = $DB->get_record('user',array('id'=>$stream->createdby),'id,firstname,lastname');
        $url = $CFG->wwwroot.'/user/profile.php';
        $crlink = new moodle_url($url, array('id' => $stream->createdby));
        $updater = $DB->get_record('user',array('id'=>$stream->updatedby),'id,firstname,lastname');
        $uplink = new moodle_url($url, array('id' => $stream->updatedby));
        $line = array();
        $line[] = $i;
        $line[] = $stream->stream_name;
        $line[] = html_writer::link($crlink, $creator->firstname);
        $line[] = html_writer::link($uplink, $updater->firstname);
        //$line[] = '<button type="button" id="'.$stream->id.'" onclick="edit_stream()" class="btn btn-info crslink">&#x270e;</button>';
        $line[] = '<button type="button" id="'.$stream->id.'" onclick="edit_stream(this.id)" class="btn btn-info crslink add_stream" data-toggle="modal" data-target="#showEditStreamForm">&#x270e;</button>';
        //$line[] = html_writer::link(new moodle_url('/local/eProjects/edit_attribute.php', array('id'=>2)), 'Edit');
        $data[] = $line;
        $i++;
      }
    }else{
      $data[] = array('','','No records found','','');
    }
    $table->data  = $data;      
    echo html_writer::table($table);
	//add stream form code lies here
    echo '<div class="modal fade" id="showStreamForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel1">Stream</h4>

                    </div>
                    <div class="modal-body">';
                      $stream_mform = new add_stream_form();
                      $flag = 0;
                      if ($stream_data = $stream_mform->get_data()) {
                        //print_object($stream_data);  
                        $new_stream = new stdClass();
                        $new_stream->stream_name = $stream_data->stream_name;
                        $new_stream->createdby = $stream_data->userid;
                        $new_stream->updatedby = $stream_data->userid;
                        $new_stream->timecreated = time();
                        $new_stream->timeupdated = time();
                        $create_new_stream = $DB->insert_record('stream',$new_stream);
                        if($create_new_stream){
                          $flag = 1;
                          echo "<meta http-equiv='refresh' content='0'>";
                          $stream_mform->set_data($newobj);
                        }
                      }
                      $stream_mform->display();

                    echo '</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    <div id="insert_success_stream" data-flag="'.$flag.'"></div>;
                </div>
            </div>
        </div>';
  echo '<div class="modal fade" id="showEditStreamForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel11">Edit Stream</h4>

                    </div>
                    <div class="modal-body">';
                      $edit_stream_mform = new edit_stream_form();
                      $flag = 0;
                      if ($edit_stream_data = $edit_stream_mform->get_data()) {
                        //print_object($edit_stream_data);  
                        $edit_stream->id = $edit_stream_data->streamid;
                        $edit_stream->stream_name = $edit_stream_data->edit_stream_name;
                        $edit_stream->updatedby = $edit_stream_data->userid;
                        $edit_stream->timeupdated = time();
                        $update_stream = $DB->update_record('stream',$edit_stream);
                        if($update_stream){
                          $flag = 1;
                          echo "<meta http-equiv='refresh' content='0'>";
                          $edit_stream_mform->set_data($newobj);
                        }
                      }
                      $edit_stream_mform->display();

                    echo '</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    <div id="edit_success_stream" data-flag="'.$flag.'"></div>;
                </div>
            </div>
        </div>';
  echo '</div>';
  //Domain Attribute list and add form appears here
	echo '<div class="form-group"  id="domain_attribute" style="display:none;">';
    echo '<button type="button" id="add_domain" class="btn btn-info crslink add_domain" data-toggle="modal" data-target="#showDomainForm">&#x271A</button>';
  	$table = new html_table();
    $table->head  = array('Sl.no','Domain Name','Stream Name','Created By', 'Updated By', 'Action');
    $table->colclasses = array ('leftalign','leftalign','leftalign', 'leftalign', 'centeralign', 'leftalign');
    $table->attributes['class'] = 'admintable generaltable';
    $table->id = 'filterssetting';
    $data = array();
    $domains = $DB->get_records('domain');
    if($domains){
      $i = 1;
      foreach ($domains as $domain) {
        $creator = $DB->get_record('user',array('id'=>$domain->createdby),'id,firstname,lastname');
        $url = $CFG->wwwroot.'/user/profile.php';
        $crlink = new moodle_url($url, array('id' => $domain->createdby));
        $updater = $DB->get_record('user',array('id'=>$domain->updatedby),'id,firstname,lastname');
        $uplink = new moodle_url($url, array('id' => $domain->updatedby));
        $stream_name = $DB->get_record('stream',array('id'=>$domain->stream_id),'id,stream_name');
        $line = array();
        $line[] = $i;
        $line[] = $domain->domain_name;
        $line[] = $stream_name->stream_name;
        $line[] = html_writer::link($crlink, $creator->firstname);
        $line[] = html_writer::link($uplink, $updater->firstname);
        // $line[] = '<button type="button" id="'.$domain->id.'" onclick="edit_domain(this.id)" class="btn btn-info crslink">&#x270e;</button>';
        $line[] = '<button type="button" id="'.$domain->id.'" onclick="edit_domain(this.id)" class="btn btn-info crslink add_stream" data-toggle="modal" data-target="#showEditDomainForm">&#x270e;</button>';
        //$line[] = html_writer::link(new moodle_url('/local/eProjects/edit_attribute.php', array('id'=>2)), 'Edit');
        $data[] = $line;
        $i++;
      }
    }else{
      $data[] = array('','','No records found','','','');
    }
    $table->data  = $data;      
    echo html_writer::table($table);

    //add domain form code lies here
    echo '<div class="modal fade" id="showDomainForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel">Domain</h4>

                    </div>
                    <div class="modal-body">';
                      $domain_mform = new add_domain_form();
                      $flag = 0;
                      if ($domain_data = $domain_mform->get_data()) {
                        //print_object($stream_data);  
                        $new_domain = new stdClass();
                        $new_domain->domain_name = $domain_data->domain_name;
                        $new_domain->stream_id = $domain_data->stream_id;
                        $new_domain->createdby = $domain_data->userid;
                        $new_domain->updatedby = $domain_data->userid;
                        $new_domain->timecreated = time();
                        $new_domain->timeupdated = time();
                        $create_new_domain = $DB->insert_record('domain',$new_domain);
                        if($create_new_domain){
                          $flag = 1;
                          $domain_mform->set_data($newobj);
                          echo "<meta http-equiv='refresh' content='0'>";
                        }
                      }
                      $domain_mform->display();

                    echo '</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    <div id="insert_success_domain" data-flag="'.$flag.'"></div>;
                </div>
            </div>
        </div>';
  echo '<div class="modal fade" id="showEditDomainForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel22" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel22">Edit Domain</h4>

                    </div>
                    <div class="modal-body">';
                      $edit_domain_mform = new edit_domain_form();
                      $flag = 0;
                      if ($edit_domain_data = $edit_domain_mform->get_data()) {
                        //print_object($edit_domain_data);  
                        $edit_domain->id = $edit_domain_data->domainid;
                        $edit_domain->domain_name = $edit_domain_data->edit_domain_name;
                        $edit_domain->stream_id = $edit_domain_data->streamid;
                        $edit_domain->updatedby = $edit_domain_data->userid;                        
                        $edit_domain->timeupdated = time();
                        $update_domain = $DB->update_record('domain',$edit_domain);
                        if($update_domain){
                          $flag = 1;
                          $edit_domain_mform->set_data($newobj);
                          echo "<meta http-equiv='refresh' content='0'>";
                        }
                      }
                      $edit_domain_mform->display();

                    echo '</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    <div id="edit_success_domain" data-flag="'.$flag.'"></div>;
                </div>
            </div>
        </div>';
	echo '</div>';
  echo '</div>
</div>';

// $strinscriptions = get_string('ctbatch', 'local_course_batches');
// echo $OUTPUT->heading_with_help($strinscriptions, 'course_batches', 'local_course_batches','icon',get_string('ctbatch', 'local_course_batches'));
// echo $OUTPUT->box (get_string('course_batches_info', 'local_course_batches'), 'center');
//academic batch form 

//academic_mform = new academic_batches_form();
//$academic_mform->display();
echo $OUTPUT->footer();
?>
<script type="text/javascript">

    $('#id_stream').click(function () {
    	$('#batchsuccess').hide();
    	$('#batchsuccess1').hide();
    	$('#batchsuccess2').hide();
        $('#stream_attribute').show('fast');
        $('#domain_attribute').hide('fast');
      
        document.getElementById("#stream_attribute").disabled = true;
        document.getElementById("#domain_attribute").disabled = false;
    });

    $('#id_domain').click(function () {
    	$('#batchsuccess').hide();
    	$('#batchsuccess1').hide();
    	$('#batchsuccess2').hide();
        $('#stream_attribute').hide('fast');
        $('#domain_attribute').show('fast');
       
        document.getElementById("#stream_attribute").disabled = false;
        document.getElementById("#domain_attribute").disabled = true;

    });

    function edit_stream(id){
      $("#editstreamid").val(id);
      $.ajax({
          url: 'getnamejax.php',
          type: 'get',
          data: {streamid:id},
          dataType: 'json',
          success:function(response){
              $("#id_edit_stream_name").val(response);
          }
      });
    }

    function edit_domain(id){
      $.ajax({
          url: 'getnamejax.php',
          type: 'get',
          data: {sdomainid:id},
          dataType: 'json',
          success:function(response){
            console.log(response);
              var streamid = response['streamid'];
              var domainid = response['domainid'];
              var domainname = response['domainname'];
              $("#editsstreamid").val(streamid);
              $("#editsdomainid").val(domainid);
              $("#id_edit_domain_name").val(domainname);
              $("#edit_id_stream").val(streamid);              
          }
      });
    }

    $(document).ready(function () {
      var v1 = $("#insert_success_stream").attr("data-flag");
      if(v1 == 1){
        alert('Stream created');
        $("#id_stream_name").val(' ');
      }

      var v2 = $("#insert_success_domain").attr("data-flag");
      if(v2 == 1){
        alert('Domain created');
        $("#id_domain_name").val(' ');
      }

      var v11 = $("#edit_success_stream").attr("data-flag");
      if(v11 == 1){
        alert('Stream updated');
        $("#id_edit_stream_name").val(' ');
      }

      var v22 = $("#edit_success_domain").attr("data-flag");
      if(v22 == 1){
        alert('Domain updated');
        $("#id_edit_domain_name").val(' ');
      }

      $("#add_domain").click(function(){
           //$('#showDomainForm').modal('show');
           //$('#showDomainForm').css('display','inline!important');

      });
    });
</script>