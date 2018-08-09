<?php
define('AJAX_SCRIPT', true);
require_once('../../config.php');
$semester = optional_param('semester', 0, PARAM_INT);
$semyear = optional_param('semyear', 0, PARAM_INT);
$semp = optional_param('semp', null, PARAM_RAW);
$sems = optional_param('sems', 0, PARAM_INT);
$content = '';
if($semester != 0){
	if($DB->record_exists('tp_useruploads',array('creatorid'=>$USER->id,'program'=>$semp,'stream'=>$sems,'semyear'=>$semyear,'semester'=>$semester,'roletype'=>'student'))){
		$students = $DB->get_records('tp_useruploads',array('creatorid'=>$USER->id,'program'=>$semp,'stream'=>$sems,'semyear'=>$semyear,'semester'=>$semester,'roletype'=>'student'),'userid');
		$content .= '<table id="example" class="cell-border" style="width:100%"" cellpadding="10">
						<thead>
							<tr>
								<th>id</th>
								<th>Login</th>
								<th>Name</th>
								<th>Batch Assigned</th>
								<th>Select All</th>
							</tr>
						</thead>
						<tbody>';
		if(!empty($students)){
			$i = 1;				
			foreach($students as $stuid){
				$stuobj = $DB->get_record('user',array('id'=>$stuid->userid),'id,firstname,username');
				//if($DB->record_exists('batch_enrolstudent',array('assignedby'=>$USER->id,'userid'=>$stuid)))
				$batchname = $DB->get_record('batches',array('creatorid'=>$USER->id,'program'=>$semp,'stream'=>$sems,'semyear'=>$semyear,'semester'=>$semester),'id,batchname');
				if($batchname){
					$content .= '<tr>
								<td>'.$i.'</td>
								<td>'.$stuobj->username.'</td>
								<td>'.$stuobj->firstname.'</td>';
					if($DB->record_exists('batch_enrolstudent',array('userid'=>$stuid->userid,'assignedby'=>$USER->id,'batchid'=>$batchname->id))){
						$content .= '<td>'.$batchname->batchname.'</td>
									 <td><input type="checkbox" name="stu[]" value="'.$stuid->userid.'" checked disabled></td>';
					}else{
						$content .= '<td>NA</td>
									 <td><input type="checkbox" name="stu[]" value="'.$stuid->userid.'" ></td>';
					}
					$content .='</tr>';
				}
				$i++;
			}
			$content .= '</tbody>
					</table>';
		}
	}else{
		$url = new moodle_url($CFG->wwwroot.'/admin/tool/uploaduser/index.php');
		$link = html_writer::link($url, ' Please Upload Users', array('class' => ''));
		$content .= 'No Data Found.'.$link;
	}
}

//$streams[] = array('id'=>'22','stream'=>'aadadadaddad');
echo json_encode($content);
//print_object($streams);


