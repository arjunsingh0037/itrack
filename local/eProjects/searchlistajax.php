<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$streamid = required_param('streamid',PARAM_INT);
$domainid = required_param('domainid',PARAM_INT);
$catid = required_param('catid',PARAM_INT);
$groupid = required_param('groupid',PARAM_INT);


$content = ''; 
$students = array();
$sql = "SELECT id,batchname,batchcode from {project} WHERE stream = ? AND domain = ? AND type = ?";
$projects = $DB->get_records_sql($sql,array($streamid,$domainid,$catid));
$content .= '<div class="modal fade" id="showStudentLists" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" 	
				aria-hidden="true">
	            <div class="modal-dialog">
	                <div class="modal-content modal-responsive">
	                    <div class="modal-header">
	                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	                        <h4 class="modal-title" id="myModalLabel11">Students List</h4>
						</div>
	                    <div id="searchresult" class="row modal-body">
	                    	<div class="col-md-3 project-grid">
							    <div class="header text-center"> <h4>testing </h4></div>
							    <div class="body">       					
									<p><b>Title : </b>group project 1</p>
									<p><b>Technology : </b>wewewqe</p>
									<p><b>Synopsis : </b><button type="button" class="btn bg-light-blue btn-xs" id="11" onclick="viewsynopsis(this.id)">view</button></p>
									<p><b>Duration Days : </b>111</p>
								</div>
											
								<div class="footer">
								  	<button type="submit" name="request_send11" id="request_send11" onclick="send_request(11)" class="btn bg-light-blue btn-sm">Send Request</button>
									<input type="hidden" name="groupinfo" id="Group" value="Group"><input type="hidden" id="11" name="individual_project" value="11">                    
								</div>
							</div>
	                    </div>
	                </div>
	            </div>
	        </div>';

echo json_encode($content);




