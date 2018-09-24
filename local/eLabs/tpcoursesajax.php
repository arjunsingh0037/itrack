<?php
define('AJAX_SCRIPT', true);
require_once('../../config.php');
$semp = optional_param('semp', null, PARAM_RAW);
$sems = optional_param('sems', 0, PARAM_INT);
$batches = optional_param('batch', null, PARAM_RAW);

$content = '';
if($sems != 0){
	if($DB->record_exists('course_type',array('creatorid'=>$USER->id,'program'=>$semp,'stream'=>$sems))){
		$courses = $DB->get_records('course_type',array('creatorid'=>$USER->id,'program'=>$semp,'stream'=>$sems),'courseid');
		$content .= '<table id="example" class="cell-border" style="width:100%"" cellpadding="10">
						<thead>
							<tr>
								<th>id</th>
								<th>Course</th>
								<th>Batch Assigned</th>
								<th>Select All</th>
							</tr>
						</thead>
						<tbody>';
		$i = 1;				
		foreach($courses as $crsid){
			$courseobj = $DB->get_record('course',array('id'=>$crsid->courseid),'id,fullname');	
			if($batches){
				foreach ($batches as $batchid) {
					if($DB->record_exists('course_batches',array('createdby'=>$USER->id,'courseid'=>$crsid->courseid,'batchid'=>$batchid,'migrated'=>0))){
						$content .= '<tr>
							<td>'.$i.'</td>
							<td>'.$courseobj->fullname.'</td>
							<td><button type="button" class="btn btn-info crslink">View</button></td>
							<td><input type="checkbox" name="courses[]" value="'.$crsid->courseid.'" checked disabled></td>';
					}else{
						$content .= '<tr>
							<td>'.$i.'</td>
							<td>'.$courseobj->fullname.'</td>
							<td><button type="button" class="btn btn-info crslink">View</button></td>
							<td><input type="checkbox" name="courses[]" value="'.$crsid->courseid.'" ></td>';
					}
				}
				
			}else{
				$content .= '<tr>
							<td>'.$i.'</td>
							<td>'.$courseobj->fullname.'</td>
							<td><button type="button" class="btn btn-info crslink">View</button></td>
							<td><input type="checkbox" name="courses[]" value="'.$crsid->courseid.'" ></td>';				
			}
			$content .='</tr>';
			$i++;
		}
		$content .= '</tbody>
				</table>';
		
	}else{
		$url = new moodle_url($CFG->wwwroot.'/course/edit.php');
		$link = html_writer::link($url, ' Please Create a Course', array('class' => ''));
		$content .= 'No Data Found.'.$link;
	}
}

//$streams[] = array('id'=>'22','stream'=>'aadadadaddad');
echo json_encode($content);
//print_object($streams);


