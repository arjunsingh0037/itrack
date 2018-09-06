<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');
require_once ('lib.php');
class delete_mapping_form extends moodleform {

	function definition() {
		global $CFG,$COURSE,$DB,$USER;
        $mform = & $this->_form;
		$mform->addElement('static', 'description','','Are you sure you want to delete this mapping ?');

        $attributes=array('id'=>'to_delete');
        $mform->addElement('hidden', 'mapid', '', $attributes);
        $mform->setType('mapid', PARAM_INT);

        $buttonarray=array();
		$buttonarray[] = $mform->createElement('submit', 'submitbutton','Yes');
		$buttonarray[] = $mform->createElement('Cancel');
		$mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
	}

	function validation($data, $files) {
	
	}
}
?>

