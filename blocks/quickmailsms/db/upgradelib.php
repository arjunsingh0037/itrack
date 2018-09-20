<?php
/**
 * Folder plugin version information
 *
 * @package  
 * @subpackage 
 * @copyright  2012 unistra  {@link http://unistra.fr}
 * @author Celine Perves <cperves@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @license    http://www.cecill.info/licences/Licence_CeCILL_V2-en.html
 */
/**
 * function to migrate quickmailsms history files attachment to the new file version from 1.9 to 2.x
 */
function migrate_quickmailsms_20(){
	global $DB;
	//migration of attachments
	$fs = get_file_storage();
	$quickmailsms_log_records=$DB->get_records_select('block_quickmailsms_log','attachment<>\'\'');
	foreach($quickmailsms_log_records as $quickmailsms_log_record){
		//searching file into mdl_files
		//analysing attachment content
		$filename=$quickmailsms_log_record->attachment;
		$filepath='';
		$notrootfile=strstr($quickmailsms_log_record->attachment,'/');		
		if($notrootfile){
			$filename=substr($quickmailsms_log_record->attachment,strrpos($quickmailsms_log_record->attachment, '/',-1)+1);
			$filepath='/'.substr($quickmailsms_log_record->attachment,0,strrpos($quickmailsms_log_record->attachment,'/',-1)+1);
		}else{
			$filepath='/';
			$filename=$quickmailsms_log_record->attachment;
		}
		$fs = get_file_storage();
                $coursecontext = context_course::instance($quickmailsms_log_record->courseid);

		$coursefile=$fs->get_file($coursecontext->id, 'course', 'legacy', 0, $filepath, $filename);
		if($coursefile){
			if($notrootfile){
				//rename
				$filename=str_replace('/', '_', $quickmailsms_log_record->attachment);
				$filepath='/';
				$quickmailsms_log_record->attachment=$filename;
				$DB->update_record('block_quickmailsms_log', $quickmailsms_log_record);
			}
			$file_record = array('contextid'=>$coursecontext->id, 'component'=>'block_quickmailsms', 'filearea'=>'attachment_log', 'itemid'=>$quickmailsms_log_record->id, 'filepath'=>$filepath, 'filename'=>$filename,
					'timecreated'=>$coursefile->get_timecreated(), 'timemodified'=>$coursefile->get_timemodified());
			if(!$fs->file_exists($coursecontext->id, 'block_quickmailsms', 'attachment_log', 0, $filepath, $filename)){
				$fs->create_file_from_storedfile($file_record, $coursefile->get_id());
			}
		}
	}
}