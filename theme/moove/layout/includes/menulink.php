<?php

//For HOME
$home = $CFG->wwwroot . '/my';
$adminlink = $CFG->wwwroot . '/admin';

//For My Courses

$mycourses = $CFG->wwwroot . '/course';

$assignedcourses = $CFG->wwwroot . '/my/listcourse.php?section=assigned';

$mylearningplan = $CFG->wwwroot . '/my/listcourse.php?section=personallearningplan';

$selfenrolled = $CFG->wwwroot . '/my/listcourse.php?section=selfenrol';

$recomendedforu = $CFG->wwwroot . '/my/listcourse.php?section=personallearningplan';

$availablecourses = $CFG->wwwroot . '/my/listcourse.php?section=avail';

//For My Reports

$myreport = $CFG->wwwroot . '/my/mycsrpt.php?section=csrpt';

$coursereport = $CFG->wwwroot . '/my/mycsrpt.php?section=csrpt';

$activityreport = $CFG->wwwroot . '/my/mycsrpt.php?section=assignrpt';

$summaryreport = $CFG->wwwroot . '/my/courses_report.php';

$mycertificates = $CFG->wwwroot . '/my/certificate.php';

//For Social Learning

$forums = $CFG->wwwroot . '/admin/settings.php?section=modsettingforum';

$newsandnotification = $CFG->wwwroot . '/message/index.php';

$survey = $CFG->wwwroot . '/course/modedit.php?add=survey&type=&course='.$COURSE->id.'&section=0&return=0&sr=0';

// $faq = $CFG->wwwroot . '/course';

$blog = $CFG->wwwroot . '/blog/index.php';

$wiki = $CFG->wwwroot . '/course/modedit.php?add=wiki&type=&course='.$COURSE->id.'&section=0&return=0&sr=0';

// $announcement = $CFG->wwwroot . '/course';

//For Notification

$message = $CFG->wwwroot . '/message/index.php';

$event = $CFG->wwwroot . '/calendar/view.php?view=month';

//For My Profile

$myprofile = $CFG->wwwroot . '/user/profile.php';

$changepassword = $CFG->wwwroot . '/login/change_password.php';

$profileprivacy = $CFG->wwwroot . '/local/userfieldprivacy/index.php?type=user';

//For Client administration

$addaclient = $CFG->wwwroot . '/Multitenant/web/create_client.php';

$editclient = $CFG->wwwroot . '/Multitenant/web/client_settings.php';

$manageclient = $CFG->wwwroot . '/Multitenant/web/client_activeinactive.php';

$managemodule = $CFG->wwwroot . '/Multitenant/web/client_plugins.php';

$managemenu = $CFG->wwwroot . '/course';

$managelobalcatalog = $CFG->wwwroot . '/Multitenant/web/live_login_reports.php';

$loggeduserc = $CFG->wwwroot . '/Multitenant/web/live_login_reports.php';

//For System administration

$administration = $CFG->wwwroot . '/admin';

$loggeduser = $CFG->wwwroot . '/report/log/index.php?id=0';

$managesystempolicies = $CFG->wwwroot . '/admin/settings.php?section=sitepolicies';

$managelocationsettings = $CFG->wwwroot . '/admin/settings.php?section=locationsettings';

$languagecustomisation = $CFG->wwwroot . '/admin/tool/customlang/index.php';

// $manageloginpage = $CFG->wwwroot . '/course';

// $manageuserinterface = $CFG->wwwroot . '/course';

$managetheme = $CFG->wwwroot . '/admin/settings.php?section=theme_defaultlms_general';

$manageuserprofilefields = $CFG->wwwroot . '/user/profile/index.php';

$manageuserfieldprivacy = $CFG->wwwroot . '/local/userfieldprivacy/index.php?type=user';

$managecalendar = $CFG->wwwroot . '/calendar/view.php';

$managedefaultmessageoutputs = $CFG->wwwroot . '/message/index.php?id=2';

$managecustomnotifications = $CFG->wwwroot . '/message/index.php?id=2';

$manageauthentication = $CFG->wwwroot . '/admin/settings.php?section=manageauths';

// $managecoursecompletion = $CFG->wwwroot . '/course';

$managecourseformats = $CFG->wwwroot . '/admin/settings.php?section=manageformats';

$manageenrol = $CFG->wwwroot . '/admin/settings.php?section=manageenrols';

$managewebservices = $CFG->wwwroot . '/admin/settings.php?section=webservicetokens';

$managesupportcontact = $CFG->wwwroot . '/admin/settings.php?section=supportcontact';

$managemaintenancemode = $CFG->wwwroot . '/admin/settings.php?section=maintenancemode';

$managesystemcleanup = $CFG->wwwroot . '/admin/settings.php?section=cleanup';

$managecaches = $CFG->wwwroot . '/admin/purgecaches.php';

$managescheduledtasks = $CFG->wwwroot . '/admin/tool/task/scheduledtasks.php';

// $notificationdeliverystatus = $CFG->wwwroot . '/course';

//For User management

$addauser = $CFG->wwwroot . '/user/editadvanced.php?id=-1';

$manageuser = $CFG->wwwroot . '/local/usermanagement/index.php';

$manageenrolmentu = $CFG->wwwroot . '/admin/settings.php?section=manageenrols';

$managecohort = $CFG->wwwroot . '/cohort/index.php';

$uploaduser = $CFG->wwwroot . '/admin/tool/uploaduser/index.php';

$changeuserpassword = $CFG->wwwroot . '/local/usermanagement/index.php';

//For Catalogue management

$managecategory = $CFG->wwwroot . '/course/management.php';

$managecourse = $CFG->wwwroot . '/course/management.php';

$manageenrolmentmethod = $CFG->wwwroot . '/course';

$manageenrolment = $CFG->wwwroot . '/course';

//For Courses management

$addacategory = $CFG->wwwroot . '/course/editcategory.php?parent=0';

$addacourse = $CFG->wwwroot . '/course/edit.php?category=1&returnto=catmanage';

$addactivity = $CFG->wwwroot . '/course';

$manageactivity = $CFG->wwwroot . '/admin/modules.php';

// $managerules = $CFG->wwwroot . '/course';

$managegrades = $CFG->wwwroot . '/admin/settings.php?section=gradeitemsettings';

$managetags = $CFG->wwwroot . '/tag/manage.php';

$managebadges = $CFG->wwwroot . '/badges/index.php?type=1';

$manageenrolmentmethodc = $CFG->wwwroot . '/enrol/instances.php?id='.$COURSE->id;

$manageuserenrolment = $CFG->wwwroot . '/enrol/users.php?id='.$COURSE->id;


//For Assessment management

$addcategory = $CFG->wwwroot . '/question/category.php?courseid='.$COURSE->id;

// $addlevel = $CFG->wwwroot . '/course';

$addquestion = $CFG->wwwroot . '/question/edit.php?courseid='.$COURSE->id;

$uploadquestion = $CFG->wwwroot . '/question/import.php?courseid='.$COURSE->id;

$managequesbank = $CFG->wwwroot . '/question/edit.php?courseid='.$COURSE->id;

$createassessment = $CFG->wwwroot . '/course/modedit.php?add=quiz&type=&course='.$COURSE->id.'&section=0&return=0&sr=0';

//For Certificates management

$addcrtfct = $CFG->wwwroot . '/course/modedit.php?add=certificate&type=&course='.$COURSE->id.'&section=2&return=0&sr=0';

// $managecrtfct = $CFG->wwwroot . '/course';

//For Reports management

$createrpt = $CFG->wwwroot . '/course';

$managerpt = $CFG->wwwroot . '/course';

$profilefiledmap = $CFG->wwwroot.'/local/profile_report_mapping/';


// end of menu links
// if you are adding any links above in future, make sure you add one if clause below to make the liclass active
$testlink = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$liclass='';

if ($testlink == $mycourses) { $liclass = 'active'; }  
if ($testlink == $assignedcourses) { $liclass = 'active'; }  
if ($testlink == $mylearningplan) { $liclass = 'active'; }  
if ($testlink == $selfenrolled) { $liclass = 'active'; }  
if ($testlink == $recomendedforu) { $liclass = 'active'; }  
if ($testlink == $availablecourses) { $liclass = 'active'; }  
if ($testlink == $myreport) { $liclass = 'active'; }  
if ($testlink == $coursereport) { $liclass = 'active'; }  
if ($testlink == $activityreport) { $liclass = 'active'; }  
if ($testlink == $summaryreport) { $liclass = 'active'; }  
if ($testlink == $mycertificates) { $liclass = 'active'; }  
if ($testlink == $forums) { $liclass = 'active'; }  
if ($testlink == $newsandnotification) { $liclass = 'active'; }  
if ($testlink == $survey) { $liclass = 'active'; }  
if ($testlink == $blog) { $liclass = 'active'; }  
if ($testlink == $wiki) { $liclass = 'active'; }  
if ($testlink == $message) { $liclass = 'active'; }  
if ($testlink == $event) { $liclass = 'active'; }  
if ($testlink == $myprofile) { $liclass = 'active'; }  
if ($testlink == $changepassword) { $liclass = 'active'; }  
if ($testlink == $profileprivacy) { $liclass = 'active'; }  
if ($testlink == $addaclient) { $liclass = 'active'; }  
if ($testlink == $editclient) { $liclass = 'active'; }  
if ($testlink == $manageclient) { $liclass = 'active'; }  
if ($testlink == $managemodule) { $liclass = 'active'; }  
if ($testlink == $managemenu) { $liclass = 'active'; }  
if ($testlink == $managelobalcatalog) { $liclass = 'active'; }  
if ($testlink == $loggeduserc) { $liclass = 'active'; }  
if ($testlink == $administration) { $liclass = 'active'; }  
if ($testlink == $loggeduser) { $liclass = 'active'; }  
if ($testlink == $managesystempolicies) { $liclass = 'active'; }  
if ($testlink == $managelocationsettings) { $liclass = 'active'; }  
if ($testlink == $languagecustomisation) { $liclass = 'active'; }  
if ($testlink == $managetheme) { $liclass = 'active'; }  
if ($testlink == $manageuserprofilefields) { $liclass = 'active'; }  
if ($testlink == $manageuserfieldprivacy) { $liclass = 'active'; }  
if ($testlink == $managecalendar) { $liclass = 'active'; }  
if ($testlink == $managedefaultmessageoutputs) { $liclass = 'active'; }  
if ($testlink == $managecustomnotifications) { $liclass = 'active'; }  
if ($testlink == $manageauthentication) { $liclass = 'active'; }  
if ($testlink == $managecourseformats) { $liclass = 'active'; }  
if ($testlink == $manageenrol) { $liclass = 'active'; }  
if ($testlink == $managewebservices) { $liclass = 'active'; }  
if ($testlink == $managesupportcontact) { $liclass = 'active'; }  
if ($testlink == $managemaintenancemode) { $liclass = 'active'; }  
if ($testlink == $managesystemcleanup) { $liclass = 'active'; }  
if ($testlink == $managecaches) { $liclass = 'active'; }  
if ($testlink == $managescheduledtasks) { $liclass = 'active'; }  
if ($testlink == $addauser) { $liclass = 'active'; }  
if ($testlink == $manageuser) { $liclass = 'active'; }  
if ($testlink == $manageenrolmentu) { $liclass = 'active'; }  
if ($testlink == $managecohort) { $liclass = 'active'; }  
if ($testlink == $uploaduser) { $liclass = 'active'; }  
if ($testlink == $changeuserpassword) { $liclass = 'active'; }  
if ($testlink == $managecategory) { $liclass = 'active'; }  
if ($testlink == $managecourse) { $liclass = 'active'; }  
if ($testlink == $manageenrolmentmethod) { $liclass = 'active'; }  
if ($testlink == $manageenrolment) { $liclass = 'active'; }  
if ($testlink == $addacategory) { $liclass = 'active'; }  
if ($testlink == $addacourse) { $liclass = 'active'; }  
if ($testlink == $addactivity) { $liclass = 'active'; }  
if ($testlink == $manageactivity) { $liclass = 'active'; }  
if ($testlink == $managegrades) { $liclass = 'active'; }  
if ($testlink == $managetags) { $liclass = 'active'; }  
if ($testlink == $managebadges) { $liclass = 'active'; }  
if ($testlink == $manageenrolmentmethodc) { $liclass = 'active'; }  
if ($testlink == $manageuserenrolment) { $liclass = 'active'; }  
if ($testlink == $addcategory) { $liclass = 'active'; }  
if ($testlink == $uploadquestion) { $liclass = 'active'; }  
if ($testlink == $managequesbank) { $liclass = 'active'; }  
if ($testlink == $createassessment) { $liclass = 'active'; }  
if ($testlink == $addcrtfct) { $liclass = 'active'; }  
if ($testlink == $createrpt) { $liclass = 'active'; }  
if ($testlink == $managerpt) { $liclass = 'active'; }  
if ($testlink == $profilefiledmap) { $liclass = 'active'; }  


//print_object($testlink);
//var_dump($liclass);
