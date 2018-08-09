<?php
// $Id: inscriptions_massives.php 356 2010-02-27 13:15:34Z ppollet $
/**
 * A bulk enrolment plugin that allow teachers to massively enrol existing accounts to their courses,
 * with an option of adding every user to a group
 * Version for Moodle 1.9.x courtesy of Patrick POLLET & Valery FREMAUX  France, February 2010
 * Version for Moodle 2.x by pp@patrickpollet.net March 2012
 */

require(dirname(dirname(dirname(__FILE__))).'/config.php');
require_login();
$context = context_system::instance();
$PAGE->set_pagelayout('admin');
$PAGE->set_url('/local/course_batches/test.php');
$PAGE->requires->css('/local/course_batches/style/styles.css');
$PAGE->set_context($context);
$viewnewpromo = 'Test Page';
$PAGE->navbar->add($viewnewpromo);
$PAGE->set_title($viewnewpromo);
$PAGE->set_heading($viewnewpromo);
echo $OUTPUT->header();
echo 'hii';
$sql = "SELECT IF(t1.tname=@previous, '', @previous:=t1.tname), t1.tmonth, t1.tsal
FROM (SELECT @previous:= '') AS emp, (SELECT emp.emp_name as tname, sal.month as tmonth, sal.salary as tsal FROM {test_employee} as emp RIGHT JOIN {test_salary} as sal ON emp.id=sal.empid) as t1";
print_object($sql);
$result = $DB->get_records_sql($sql, array());
print_object($result);
echo $OUTPUT->footer();

?>