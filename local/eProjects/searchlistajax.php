<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$streamid = required_param('streamid',PARAM_INT);
$domainid = required_param('domainid',PARAM_INT);
$catid = required_param('catid',PARAM_RAW);
$gpid = required_param('gpid',PARAM_INT);

$content = ''; 
$students = array();
$sql = "SELECT id,title,technology,synopsis,duration,stream,domain,type from {project} WHERE stream = ? AND domain = ? AND type = ?";
$projects = $DB->get_records_sql($sql,array($streamid,$domainid,$catid));
// print_object($streamid.'--'.$domainid.'--'.$catid);
if($projects){
	$content .= '<div id="component_maplist" class="panel-default">
              <div class="panel-heading listhead">PROJECTS LIST</div>
              <div class="panel-body">';
	foreach ($projects as $key => $value) {
		$head = $DB->get_record('stream',array('id'=>$value->stream),'stream_name');
		$title = $value->title;
		$synopsis = $value->synopsis;
		$technology = $value->technology;
		$duration =  $value->duration;
		$content .= '<div class="col-md-3 project-grid">
		                <div class="header text-center"> <h4>'.$head->stream_name.'</h4></div>
		                <div class="body">			
							<p><b>Title : </b>'.$title.'</p>
							<p><b>Technology : </b>'.$technology.'</p>
							<p><b>Synopsis : </b><button type="button" id="'.$value->id.'" onclick="view_synopsis(this.id)" class="btn bg-light-blue btn-xs" data-toggle="modal" data-target="#showSynopsis">view</button></p>
							<p><b>Duration Days : </b>'.$duration.'</p>
					    </div>
						<div class="footer">
		                  	<button type="button" name="request_send" id="send_request" onclick="send_request('.$value->id.')" class="btn bg-light-blue btn-sm">Send Request</button>
							<input type="hidden" name="grouptype" id="gptype" value="'.$value->type.'">';
		if($value->type == 'gp'){
			$content .= '<input type="hidden" name="each_group" id="gpid" value="'.$gpid.'">';  
		}else{
			$content .= '<input type="hidden" name="each_group" id="gpid" value="0">';  
		}                 
		$content .= '</div>
		            </div>';
	}           
	$content .= '</div>
	         </div>
	        </div>';
}


echo json_encode($content);




