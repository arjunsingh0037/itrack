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
 * @package    local_accesscohort
 * @copyright  Prashant Yallatti<prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/accesscohort/lib.php');
require_once($CFG->dirroot . '/user/selector/lib.php');


/**
 * Cohort assignment candidates
 */
class accesscohort_candidate_selector extends user_selector_base {
    protected $cohortid;

    public function __construct($name, $options) {
        $this->cohortid = $options['cohortid'];
        parent::__construct($name, $options);
    }

    /**
     * Candidate users
     * @param string $search
     * @return array
     */
    public function find_users($search) {
        global $DB,$USER;
        $systemcontext= context_system::instance();
        $addusercap = has_capability('local/accesscohort:adduseraccesscohort',$systemcontext);

        // By default wherecondition retrieves all users except the deleted, not confirmed and guest.
        list($wherecondition, $params) = $this->search_sql($search, 'u');
        $params['cohortid'] = $this->cohortid;

        //$fields      = 'SELECT ' . $this->required_fields_sql('u');
        $fields      = 'SELECT  ' . $this->required_fields_sql('u').',u.institution';
        $countfields = 'SELECT COUNT(1)';
        $sql = " FROM {user} u
        LEFT JOIN {cohort_members} cm ON (cm.userid = u.id AND cm.cohortid = :cohortid)
        WHERE cm.id IS NULL AND $wherecondition";

        list($sort, $sortparams) = users_order_by_sql('u', $search, $this->accesscontext);
        $order = ' ORDER BY ' . $sort;

        if (!$this->is_validating()) {
            $potentialmemberscount = $DB->count_records_sql($countfields . $sql, $params);
            if ($potentialmemberscount > $this->maxusersperpage) {
                return $this->too_many_results($search, $potentialmemberscount);
            }
        }
        //custom query for accesscohort 


        //if is site admin we need to access all users 
        if(is_siteadmin()){
            $availableusers = $DB->get_records_sql($fields . $sql  . $order, array_merge($params, $sortparams));
            //print_object($availableusers);
            //exit();

        }else if($addusercap){
            $availableusers1 = $DB->get_records_sql($fields . $sql  . $order, array_merge($params, $sortparams));
            //print_object($availableusers);
            $sql1 = " SELECT lo.id
            from {local_organization} lo
            join {local_oragnization_admin} oa
            on oa.orgid = lo.id 
            where oa.userid = $USER->id";
            //depending upon capability we need to give data here 
            $r2 = $DB->get_records_sql($sql1);
            //check cohortids prasent in organization mapping table 
            //echo $this->cohortid;
            $sql2 = "SELECT org_id from {local_mapping_cohort} where cohort_id like '%$this->cohortid%'";
            $results = $DB->get_records_sql($sql2);
            //new code here for matching value here
            //$store = array_intersect($results,$r2); 
            $uvalue1 = [];
            foreach ($results as $key => $uvalue) {
                $uvalue1[] = $uvalue->org_id;
            }
            $rvalue1 = [];
            foreach($r2 as $rvalue){
                $rvalue1[] = $rvalue->id;
            }
            //take common organization id from both array here
            $store = array_intersect($uvalue1,$rvalue1); 
            $orgnames = [];
            foreach ($store as $key => $orgvalue) {
                $orgnames[] =   $DB->get_record('local_organization',array('id'=>$orgvalue),'org_name');
            }
            //print_object($orgnames);
            $availableusers = [];
            foreach($availableusers1 as $availableuser1){
                foreach ($orgnames as $orgname) {
                   if($availableuser1->institution == $orgname->org_name){
                        //print_object($availableuser1);
                    $availableusers[] =  $availableuser1;
                }
            }
        }
    }else{
        echo html_writer::div(
            get_string('cap', 'local_accesscohort'),'alert alert-danger'
            );
    }

    if (empty($availableusers)) {
        return array();
    }


    if ($search) {
        $groupname = get_string('potusersmatching', 'cohort', $search);
    } else {
        $groupname = get_string('potusers', 'cohort');
    }

    return array($groupname => $availableusers);
}

protected function get_options() {
    $options = parent::get_options();
    $options['cohortid'] = $this->cohortid;
    $options['file'] = 'local/accesscohort/locallib.php';
    return $options;
}
}


/**
 * Cohort assignment candidates
 */
class accesscohort_existing_selector extends user_selector_base {
    protected $cohortid;

    public function __construct($name, $options) {
        $this->cohortid = $options['cohortid'];
        parent::__construct($name, $options);
    }

    /**
     * Candidate users
     * @param string $search
     * @return array
     */
    public function find_users($search) {
        global $DB,$USER;
        $systemcontext= context_system::instance();
        $addusercap = has_capability('local/accesscohort:adduseraccesscohort',$systemcontext);
        // By default wherecondition retrieves all users except the deleted, not confirmed and guest.
        list($wherecondition, $params) = $this->search_sql($search, 'u');
        $params['cohortid'] = $this->cohortid;

        $fields      = 'SELECT  ' . $this->required_fields_sql('u').',u.institution';
        $countfields = 'SELECT COUNT(1)';

        $sql = " FROM {user} u
        JOIN {cohort_members} cm ON (cm.userid = u.id AND cm.cohortid = :cohortid)
        WHERE $wherecondition";

        list($sort, $sortparams) = users_order_by_sql('u', $search, $this->accesscontext);
        $order = ' ORDER BY ' . $sort;

        if (!$this->is_validating()) {
            $potentialmemberscount = $DB->count_records_sql($countfields . $sql, $params);
            if ($potentialmemberscount > $this->maxusersperpage) {
                return $this->too_many_results($search, $potentialmemberscount);
            }
        }
        if(is_siteadmin()) {
            $availableusers = $DB->get_records_sql($fields . $sql . $order, array_merge($params, $sortparams));
        } else if($addusercap) {
         $availableusers1 = $DB->get_records_sql($fields . $sql  . $order, array_merge($params, $sortparams));
         $sql1 = " SELECT lo.id
         from {local_organization} lo
         join {local_oragnization_admin} oa
         on oa.orgid = lo.id 
         where oa.userid = $USER->id";
            //depending upon capability we need to give data here 
         $r2 = $DB->get_records_sql($sql1);
            //check cohortids prasent in organization mapping table 
         $sql2 = "SELECT org_id from {local_mapping_cohort} where cohort_id like '%$this->cohortid%'";
         $results = $DB->get_records_sql($sql2);
            //new code here for matching value here
            //$store = array_intersect($results,$r2); 
         if($results){
             $uvalue1 = [];
             foreach ($results as $key => $uvalue) {
                $uvalue1[] = $uvalue->org_id;
            }
        }
        if($r2){
            $rvalue1 = [];
            foreach($r2 as $rvalue){
                $rvalue1[] = $rvalue->id;
            }
        }
            //take common organization id from both array here
        $store = array_intersect($uvalue1,$rvalue1); 
        $orgnames = [];
        foreach ($store as $key => $orgvalue) {
            $orgnames[] =   $DB->get_record('local_organization',array('id'=>$orgvalue),'org_name');
        }
            //print_object($orgnames);
        $availableusers = [];
        foreach($availableusers1 as $availableuser1){
            foreach ($orgnames as $orgname) {
               if($availableuser1->institution == $orgname->org_name){
                        //print_object($availableuser1);
                $availableusers[] =  $availableuser1;
            }
        }
    }

}
else{
    echo html_writer::div(
        get_string('cap', 'local_accesscohort'),'alert alert-danger'
        );
}

if (empty($availableusers)) {
    return array();
}


if ($search) {
    $groupname = get_string('currentusersmatching', 'cohort', $search);
} else {
    $groupname = get_string('currentusers', 'cohort');
}

return array($groupname => $availableusers);
}

protected function get_options() {
    $options = parent::get_options();
    $options['cohortid'] = $this->cohortid;
    $options['file'] = 'local/accesscohort/locallib.php';
    return $options;
}
}

