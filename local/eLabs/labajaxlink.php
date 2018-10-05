<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once ($CFG->libdir . '/formslib.php');
$PAGE->set_context(context_system::instance());
$gid = optional_param('alabid', 0,PARAM_INT);
$gid1 = optional_param('slabid', 0,PARAM_INT);

$content = ''; 
if($gid != 0){
  	$data = array();
  	$groupobj = $DB->get_record('vpl_details',array('labid'=>$gid),'createdby,timecreated,showweightage,allocateweightage,algorithm,dataio,coding');
  	if($groupobj){
  		$labobj = $DB->get_record('vpl',array('id'=>$gid),'id,name,runscript,worktype,startdate,duedate,course');
  		$creator = $DB->get_record('user',array('id'=>$groupobj->createdby),'id,firstname');
  		$i =0;
  		//$CFG->wwwroot.'/mod/vpl/forms/edit.php?id='.$link_obj->id.'&userid='.$USER->id
  		$hours = round((strtotime($labobj->startdate) - strtotime($labobj->duedate))/3600, 1);
  		$link_obj = $DB->get_record('course_modules',array('course'=>$labobj->course,'module'=>'24','instance'=>$labobj->id),'id');
  		$content .= '<div class="popup-content" id="table_val_desc"> 
				  		<section class="content">
								<p><strong>Valid From: </strong>'.userdate($labobj->startdate).'</p>
								<p><strong>Valid Upto: </strong> '.userdate($labobj->duedate).'</p>
								<p><strong>Total Hours Allocated: </strong> NA</p>
								<p><strong>Practice Lab Hours Used: </strong>'.$hours.'</p>
								<p><strong>Test Lab Hours Used: </strong>NA</p>
								<p><strong>Total Time Used: </strong>NA</p>
								<form action="student_flow.php" method="post">
				                    <input type="hidden" name="labid" value="'.$gid.'">
				                    <input type="hidden" name="modid" value="'.$link_obj->id.'">
				                    <button type="submit" name="workflow" class="btn bg-light-blue btn-xs">Continue to Workflow</button>
				                </form>
						</section>
					</div>';
	}else{
	   	$data[] = array('','','','No records found','','','','');
	   	$table->data  = $data;      
		$content .=html_writer::table($table);
	}
}else if($gid1 != 0){
  	$data = array();
  	$groupobj = $DB->get_record('vpl_details',array('labid'=>$gid1),'createdby,timecreated,showweightage,allocateweightage,algorithm,dataio,coding');
  	
  	if($groupobj){
  		$labobj = $DB->get_record('vpl',array('id'=>$gid1),'id,name,runscript,worktype');
  		$creator = $DB->get_record('user',array('id'=>$groupobj->createdby),'id,firstname');
  		$i =0;
  		$content .= '<div class="popup-content" id="table_val_desc"> <section class="content">
        <h3>Lab Details for <font color="navy">Lab ID '.$labobj->name.'</font> Created by <font color="navy">'.$creator->firstname.'</font> on <font color="navy">'.userdate($groupobj->timecreated).'</font> For: </h3>
        <div class="box box-default">
            <div class="row">
                <div class="box-body">';
                	$table = new html_table();
				  	$table->head  = array('Description Weightage', 'Algorithm Weightage', 'Data i/o Weightage', 'Code Weightage', 'Language', 'Level');
				  	$table->size  = array('15%', '15%', '15%', '20%', '15%', '15%');
				  	$table->attributes['class'] = 'admintable generaltable';
				  	$table->id = 'filterssetting';
				  	$data = array();
				  	$line = array();
			        $line[] = $groupobj->allocateweightage;
			        $line[] = $groupobj->algorithm;
			        $line[] = $groupobj->dataio;
			        $line[] = $groupobj->coding;
			        $line[] = $labobj->runscript;
			        if($labobj->worktype == '00'){
			        	$level = 'Begineers';
			        }else{
			        	$level = 'Expert';
			        }
			        $line[] = $level;
			        $data[] = $line;
			     	$table->data  = $data;      
				  	$content .=html_writer::table($table);
				  	$content .='</div>
				            </div>

				            <div class="row">
				                <div class="box-body">
				                    <table id="example2" class="table table-hover">
				                        <tbody>
				                            <tr>
				                                <td><font color="navy"><b>Lab Statement : </b></font></td><td><p>sdsadasd</p></td>
				                            </tr>

				                            <tr>
				                                <td><font color="navy"><b>Concepts to Know: </b></font></td><td></td>
				                            </tr>

				                            <tr>
				                                <td><font color="navy"><b>Theory to Apply: </b></font></td><td></td>
				                            </tr>

				                            <tr>
				                                <td><font color="navy"><b>Design Principles for Coding : </b></font></td><td></td>
				                            </tr>

				                            <tr>
				                                <td><font color="navy"><b>Problem Description Template: </b></font></td><td></td>
				                            </tr>

				                            <tr>
				                                <td><font color="navy"><b>Algorithm Template: </b></font></td><td></td>
				                            </tr>

				                            <tr>
				                                <td><font color="navy"><b>Input/Output Template : </b></font></td><td></td>
				                            </tr>

				                            <tr>
				                                <td><font color="navy"><b>Code Template : </b></font></td><td></td>
				                            </tr>
										</tbody>
				                    </table>
				                </div>
				            </div>
						</div>
				    </section>
				    </div>';
	}else{
	   	$data[] = array('','','','No records found','','','','');
	   	$table->data  = $data;      
		$content .=html_writer::table($table);
	}
}
echo json_encode($content);




