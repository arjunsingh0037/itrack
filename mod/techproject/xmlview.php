<?php
// This file is part of Moodle - http://moodle.org/
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
 * A document generator, using XML -> XSLT transform to HTML
 *
 * @package mod-techproject
 * @category mod
 * @author Valery Fremaux (France) (admin@www.ethnoinformatique.fr)
 * @date 2008/03/03
 * @version phase1
 * @contributors LUU Tao Meng, So Gerard (parts of treelib.php), Guillaume Magnien, Olivier Petit
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
require('../../config.php');
require_once($CFG->dirroot.'/mod/techproject/lib.php');
require_once($CFG->dirroot.'/mod/techproject/locallib.php');

// fixes locale for all date printing.

setLocale(LC_TIME, substr(current_language(), 0, 2));

// get context information

$id = required_param('id', PARAM_INT);   // module id
$view = optional_param('view', '', PARAM_CLEAN);   // viewed page id
$accesskey = optional_param('accesskey', '', PARAM_TEXT);

$timenow = time();

// get some useful stuff...

if (! $cm = $DB->get_record('course_modules', array("id" => $id))) {
    print_error('invalidcoursemodule');
}
if (! $course = $DB->get_record('course', array('id' => $cm->course))) {
    print_error('coursemisconf');
}
if (! $project = $DB->get_record('techproject', array('id' => $cm->instance))) {
    print_error('invalidtechprojectid', 'techproject');
}

// Security.
// Allow anonymized access (document reading) with access key.
if (empty($project->accesskey) || $accesskey != $project->accesskey) {
    require_login($course->id, false, $cm);
} else {
    $COURSE = $course;
}

$context = context_module::instance($cm->id);

// Check current group and change, for anyone who could.
if (!$groupmode = groupmode($course, $cm)) {
    // Are groups are being used ?
    $currentgroupid = 0;
} else {
    $changegroup = isset($_GET['group']) ? $_GET['group'] : -1;
    // Group change requested ?
    if (isguestuser()){ // for guests, use session
        if ($changegroup >= 0) {
            $_SESSION['guestgroup'] = $changegroup;
        }
        $currentgroupid = 0 + @$_SESSION['guestgroup'];
    } else {
        // For normal users, change current group.
        $currentgroupid = 0 + groups_get_course_group($course, true);
        if (!groups_is_member($currentgroupid , $USER->id) && !is_siteadmin()) {
            $USER->editmode = "off";
        }
    }
}

// Get all information about the current project.

$xml = techproject_get_full_xml($project, $currentgroupid);

// Invoke XSLT transformation for making the output document.

if (phpversion() >= 5.0) {
    $xsl = new XSLTProcessor();
    $doc = new DOMDocument();
    $xsl_sheet = $project->xslfilter;
    $doc->load($xsl_sheet);
    $xsl->importStyleSheet($doc);
    $doc->loadXML($xml);
    if (is_object($doc)) {
        $html = $xsl->transformToXML($doc);
    } else {
        $formattedxml = htmlentities($xml, ENT_QUOTES, 'UTF-8');
        $formattedxmllines = explode("\n", $formattedxml);
        $html = "XML Generation Error";
        $html .="<hr/><pre>";
        $i = 1;
        foreach ($formattedxmllines as $line) {
            $html .= $i . " " . $line."\n";
            $i++;
        }
        $html .="</pre><hr/>";
    }
} else {
    /*
    $arguments = array(
         '/_xml' => $xml,
    );
    $procesor = xslt_create();
    if ($html = xslt_process($processor, "arg:/_xml", $project->xslfilter, null, $arguments)){
    }
    else{
       $html = xslt_error($processor);
    }
    */
    echo $OUTPUT->notification("Php 4 implementation of XSL processing code has not been experimented.");
}

// Deliver the document.

header("Content-Type:text/html\n\n");
echo $html;
