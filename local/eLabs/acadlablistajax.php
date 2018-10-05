<?php
define('AJAX_SCRIPT', true);
require_once('../../config.php');
$batchid = required_param('batchid', PARAM_INT);
$batchtype = required_param('batchtype', PARAM_RAW);
$content = '';
$all_labs = array();
$all_labs = $DB->get_records('vpl');
$acad_labs = array();
$subs_labs = array();
if($all_labs){
	foreach ($all_labs as $key => $value) {
		if($value->shortdescription == 'ACAD'){
			$acad_labs[] = $value;
		}else if($value->shortdescription == 'SUBS'){
			$subs_labs[] = $value;
		}		
	}
}
if($batchtype == 'ACAD'){	
	if($acad_labs){
		$table = new html_table();
        $table->head  = array('Sl.no','Lab Name','Lab Id','Lab Type','Available From', 'Available Upto','Assigned Batches', 'Action');
        $table->size  = array('5%', '10%', '10%', '10%', '15%', '15%', '15%','5%');
        $table->colclasses = array ('leftalign','leftalign','leftalign', 'leftalign','leftalign','leftalign','leftalign', 'leftalign');
        $table->attributes['class'] = 'admintable generaltable';
        $table->id = 'filterssetting';
        $data = array();
        $i = 1;			
		foreach($acad_labs as $acl){
			$line = array();
			$lab_obj = $DB->get_record('vpl',array('id'=>$acl->id),'id,name,course,startdate,duedate');
			$lab_details_obj = $DB->get_record('vpl_details',array('labid'=>$acl->id),'id,createdby,labname');
			
			$line[] = $i;
	        $line[] = $lab_details_obj->labname;
	        $line[] = $lab_obj->name;
	        $line[] = 'Academic';
	        $line[] = userdate($lab_obj->startdate);
	        $line[] = userdate($lab_obj->duedate);
	        $line[] = 'View';
	        if($DB->record_exists('vpl_assignedbatches',array('labid'=>$acl->id,'batchid'=>$batchid))){
				$line[] = '<td><input type="checkbox" name="stu[]" value="'.$acl->id.'" checked disabled></td>';
			}else{
				$line[] = '<td><input type="checkbox" name="stu[]" value="'.$acl->id.'" ></td>';
			}
			$data[] = $line;
            $i++;
		}
		$table->data  = $data;      
        $content .=html_writer::table($table);
	}else{
		$url = new moodle_url($CFG->wwwroot.'/local/eLabs/addlabs.php');
		$link = html_writer::link($url, ' Please add labs', array('class' => ''));
		$content .= 'No Data Found.'.$link;
	}
}else if($batchtype == 'SUBS'){	
	if($subs_labs){
		$table = new html_table();
        $table->head  = array('Sl.no','Lab Name','Lab Id','Lab Type','Available From', 'Available Upto','Assigned Batches', 'Action');
        $table->size  = array('5%', '10%', '10%', '10%', '15%', '15%', '15%','5%');
        $table->colclasses = array ('leftalign','leftalign','leftalign', 'leftalign','leftalign','leftalign','leftalign', 'leftalign');
        $table->attributes['class'] = 'admintable generaltable';
        $table->id = 'filterssetting';
        $data = array();
        $i = 1;			
		foreach($subs_labs as $acl){
			$line = array();
			$lab_obj = $DB->get_record('vpl',array('id'=>$acl->id),'id,name,course,startdate,duedate');
			$lab_details_obj = $DB->get_record('vpl_details',array('labid'=>$acl->id),'id,createdby,labname');
			
			$line[] = $i;
	        $line[] = $lab_details_obj->labname;
	        $line[] = $lab_obj->name;
	        $line[] = 'Academic';
	        $line[] = userdate($lab_obj->startdate);
	        $line[] = userdate($lab_obj->duedate);
	        $line[] = 'View';
	        if($DB->record_exists('vpl_assignedbatches',array('labid'=>$acl->id,'batchid'=>$batchid))){
				$line[] = '<td><input type="checkbox" name="stu[]" value="'.$acl->id.'" checked disabled></td>';
			}else{
				$line[] = '<td><input type="checkbox" name="stu[]" value="'.$acl->id.'" ></td>';
			}
			$data[] = $line;
            $i++;
		}
		$table->data  = $data;      
        $content .=html_writer::table($table);
	}else{
		$url = new moodle_url($CFG->wwwroot.'/local/eLabs/addlabs.php');
		$link = html_writer::link($url, ' Please add labs', array('class' => ''));
		$content .= 'No Data Found.'.$link;
	}
}


//$streams[] = array('id'=>'22','stream'=>'aadadadaddad');
echo json_encode($content);
//print_object($streams);


