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
 * @package     local_access_level_org_report
 * @author      Prashant Yallatti<prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
defined('MOODLE_INTERNAL') || die();
    if(is_siteadmin()){
	$ADMIN->add('localplugins',new admin_category('local_accesscohort',get_string('pluginname','local_accesscohort')));
//Add generic settings page
	//access cohort list 
	$ADMIN->add('local_accesscohort',new admin_externalpage('accesscohort',get_string('pluginname','local_accesscohort'),
	$CFG->wwwroot . '/local/accesscohort/index.php'));
	//add organization and listing will be displayed here 
	$ADMIN->add('local_accesscohort',new admin_externalpage('organization_list',get_string('list_org','local_accesscohort'),
	$CFG->wwwroot . '/local/accesscohort/list_org.php'));
    //organization and cohort mapping code here 
    $ADMIN->add('local_accesscohort',new admin_externalpage('mapping_list',get_string('list_map','local_accesscohort'),
	$CFG->wwwroot . '/local/accesscohort/list_mapping.php'));

	$ADMIN->add('local_accesscohort',new admin_externalpage('cohort_enrolment',get_string('enrolment','local_accesscohort'),
	$CFG->wwwroot . '/local/accesscohort/enrolment_present.php'));
	/* $ADMIN->add('local_accesscohort',new admin_externalpage('',get_string('enrolment','local_accesscohort'),
	$CFG->wwwroot . '/local/accesscohort/enrolment_present.php')); */
	
	$ADMIN->add('local_accesscohort',new admin_externalpage('add_user_in_org',get_string('adduser','local_accesscohort'),
	$CFG->wwwroot . '/local/accesscohort/organization_admin.php'));

	$ADMIN->add('local_accesscohort',new admin_externalpage('list_admin_org',get_string('list_org_admin','local_accesscohort'),
	$CFG->wwwroot . '/local/accesscohort/list_admin.php'));


}