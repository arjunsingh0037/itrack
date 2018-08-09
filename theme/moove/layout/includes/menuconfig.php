<?php

$menu = array();


//For Notification

/* $menus[] = array(
    'key' => 'myhome',
	'title' => 'Home',
	'url' => '#',
	'fa' => 'fa-bell',
	'flag' => array(
		array(
			'key'=> 'myhome',
			'title' =>'Home Dashboard',
			'url' => '/my/index.php'
		),array(
			'key'=> 'admin',
			'title' =>'Admin',
			'url' => '/admin/index.php?cache=1'
		)
	)
); */

//For My Courses
$menus[] = array(
    'key' => 'mycourses',
	'title' => 'My Course',
	'url' => '#',
	'fa' => 'fa-graduation-cap',
	'flag' => array(
		array(
			'key' => 'mycourses',
			'title' => 'My Courses',
			'url' => '/course/index.php'
		),
		array(
			'key'=> 'assignedcourses',
			'title' =>'Assigned Courses',
			'url' => '/my/listcourse.php?section=assigned'
		),
		array(
			'key' => 'mylearningplan',
			'title' => 'My Learning Plan',
			'url' => '/my/listcourse.php?section=personallearningplan'
		),
		array(
			'key'=> 'selfenrolled',
			'title' =>'Self enrolled',
			'url' => '/my/listcourse.php?section=selfenrol'
		),array(
			'key' => 'recommendedforyou',
			'title' => 'Recommended for you',
			'url' => '/my/listcourse.php?section=personallearningplan'
		),
		array(
			'key'=> 'availablecourses',
			'title' =>'Available Courses',
			'url' => '/my/listcourse.php?section=avail'
		)
	)
);
//For My Reports

$menus[] = array(
    'key' => 'myreports',
	'title' => 'My Reports',
	'url' => '#',
	'fa' => 'fa-file-text',
	'flag' => array(
		array(
			'key' => 'myreports',
			'title' => 'My reports',
			'url' => '/my/listcourse.php?section=statuscourses'
		),
		array(
			'key'=> 'coursereport',
			'title' =>'Course report',
			'url' => '/my/mycsrpt.php?section=csrpt'
		),
		array(
			'key' => 'activityreports',
			'title' => 'Activity reports',
			'url' => '/my/mycsrpt.php?section=assignrpt'
		),
		array(
			'key'=> 'summaryreport',
			'title' =>'Summary report',
			'url' => '/my/courses_report.php'
		),array(
			'key'=> 'mycertificates',
			'title' =>'My certificates',
			'url' => '/my/certificate.php'
		)
	)
);
//For Social Learning

$menus[] = array(
    'key' => 'sociallearning',
	'title' => 'Social learning',
	'url' => '#',
	'fa' => 'fa-th',
	'flag' => array(
		array(
			'key' => 'forums',
			'title' => 'Forums',
			'url' => '/admin/settings.php?section=modsettingforum'
		),
		array(
			'key'=> 'newsandnotifications',
			'title' =>'News & notifications',
			'url' => '/message/index.php'
		),
		array(
			'key' => 'surveys',
			'title' => 'Surveys',
			'url' => '/course/modedit.php?add=survey&type=&course='.$COURSE->id.'&section=0&return=0&sr=0'
		),
		array(
			'key'=> 'faqs',
			'title' =>'FAQs',
			'url' => '/course'
		),array(
			'key'=> 'blog',
			'title' =>'Blog',
			'url' => '/blog/index.php'
		),array(
			'key'=> 'wiki',
			'title' =>'Wiki',
			'url' => '/course/modedit.php?add=wiki&type=&course='.$COURSE->id.'&section=0&return=0&sr=0'
		),array(
			'key'=> 'announcements',
			'title' =>'Announcements',
			'url' => '/course'
		)
	)
);
//For Notification

$menus[] = array(
    'key' => 'notification',
	'title' => 'Notification',
	'url' => '#',
	'fa' => 'fa-bell',
	'flag' => array(
		array(
			'key'=> 'message',
			'title' =>'Message',
			'url' => '/message/index.php'
		),array(
			'key'=> 'event',
			'title' =>'Event',
			'url' => '/calendar/view.php?view=month'
		)
	)
);

//For My Profile

$menus[] = array(
    'key' => 'myprofile',
	'title' => 'My Profile',
	'url' => '#',
	'fa' => 'fa-user',
	'flag' => array(
		array(
			'key'=> 'myprofile',
			'title' =>'My profile',
			'url' => '/user/profile.php'
		),array(
			'key'=> 'changepassword',
			'title' =>'Change password',
			'url' => '/login/change_password.php'
		),array(
			'key'=> 'profileprivacy',
			'title' =>'Profile privacy',
			'url' => '/local/userfieldprivacy/index.php?type=user'
		)
	)
);

//For Client administration

$menus[] = array(
    'key' => 'clientadministration',
	'title' => 'Client administration',
	'url' => '#',
	'fa' => 'fa-cog',
	'flag' => array(
		array(
			'key' => 'addanewclient',
			'title' => 'Add a new client',
			'url' => '/Multitenant/web/create_client.php'
		),
		array(
			'key'=> 'editclient',
			'title' =>'Edit client',
			'url' =>'/Multitenant/web/client_settings.php'
		),
		array(
			'key' => 'manageclients',
			'title' => 'Manage clients',
			'url' => '/Multitenant/web/client_activeinactive.php'
		),
		array(
			'key'=> 'managemodule',
			'title' =>'Manage module',
			'url' => '/Multitenant/web/client_plugins.php'
		),array(
			'key'=> 'clientlist',
			'title' =>'Client list',
			'url' => '/Multitenant/web/clients.php'
		),array(
			'key'=> 'clientchangepassword',
			'title' =>'Client Change Password',
			'url' => '/Multitenant/web/client_changepassword.php'
		),array(
			'key'=> 'clientcurrentsettings',
			'title' =>'Client Current Settings',
			'url' => '/Multitenant/web/client_currentsettings.php'
		),array(
			'key'=> 'clientmenu',
			'title' =>'Client Admin Menu',
			'url' => '/Multitenant/web/client_advancefeatures.php'
		),array(
			'key'=> 'clientloggedusers',
			'title' =>'Client Logged users',
			'url' => '/Multitenant/web/live_login_reports.php'
		)
	)
);

//For System administration

$menus[] = array(
    'key' => 'systemadministration',
	'title' => 'System administration',
	'url' => '#',
	'fa' => 'fa-wrench',
	'flag' => array(
		array(
			'key' => 'administration',
			'title' => 'Administration',
			'url' => '/admin'
		),
		array(
			'key'=> 'loggedusers',
			'title' =>'Logged users',
			'url' =>'/report/log/index.php?id=0'
		),
		array(
			'key' => 'managesystempolicies',
			'title' => 'Manage system policies',
			'url' => '/admin/settings.php?section=sitepolicies'
		),
		array(
			'key'=> 'managelocationsettings',
			'title' =>'Manage location settings',
			'url' => '/admin/settings.php?section=locationsettings'
		),array(
			'key'=> 'languagecustomisation',
			'title' =>'Language customisation',
			'url' => '/admin/tool/customlang/index.php'
		),array(
			'key'=> 'manageloginpage',
			'title' =>'Manage login page',
			'url' => 'course'
		),array(
			'key'=> 'manageuserinterface',
			'title' =>'Manage user interface',
			'url' => 'course'
		),array(
			'key' => 'managetheme',
			'title' => 'Manage theme',
			'url' => '/admin/settings.php?section=theme_defaultlms_general'
		),
		array(
			'key'=> 'manageuserprofilefields',
			'title' =>'Manage user profile fields',
			'url' =>'/user/profile/index.php'
		),
		array(
			'key' => 'manageuserfieldprivacy',
			'title' => 'Manage user field privacy',
			'url' => '/local/userfieldprivacy/index.php?type=default'
		),
		array(
			'key'=> 'summaryreport',
			'title' =>'Manage calendar',
			'url' => '/calendar/view.php'
		),array(
			'key'=> 'managedefaultmessageoutputs',
			'title' =>'Manage default message outputs',
			'url' => '/message/index.php?id=2'
		),array(
			'key' => 'managecustomnotifications',
			'title' => 'Manage custom notifications',
			'url' => '/message/index.php?id=2'
		),
		array(
			'key'=> 'manageauthentication',
			'title' =>'Manage authentication',
			'url' =>'/admin/settings.php?section=manageauths'
		),
		array(
			'key' => 'summaryreport',
			'title' => 'Manage course completion',
			'url' => 'course'
		),
		array(
			'key'=> 'managecourseformats',
			'title' =>'Manage course formats',
			'url' => '/admin/settings.php?section=manageformats'
		),array(
			'key'=> 'manageenrol',
			'title' =>'Manage enrol',
			'url' => '/admin/settings.php?section=manageenrols'
		),array(
			'key' => 'managewebservices',
			'title' => 'Manage web services',
			'url' => '/admin/settings.php?section=webservicetokens'
		),
		array(
			'key'=> 'managesupportcontact',
			'title' =>'Manage support contact',
			'url' => '/admin/settings.php?section=supportcontact'
		),array(
			'key'=> 'managemaintenancemode',
			'title' =>'Manage maintenance mode',
			'url' => '/admin/settings.php?section=maintenancemode'
		),array(
			'key' => 'managesystemcleanup',
			'title' => 'Manage system cleanup',
			'url' => '/admin/settings.php?section=cleanup'
		),
		array(
			'key'=> 'managecaches',
			'title' =>'Manage caches',
			'url' =>'/admin/purgecaches.php'
		),
		array(
			'key'=> 'managescheduledtasks',
			'title' =>'Manage scheduled tasks',
			'url' =>'/admin/tool/task/scheduledtasks.php'
		),
		array(
			'key'=> 'mastermailcc',
			'title' =>'CC Mail Set up',
			'url' =>'/admin/settings.php?section=local_mastermail'
		),
		array(
			'key' => 'notificationdeliverystatus',
			'title' => 'Notification delivery status',
			'url' => '/course'
		)
		)
	
);
//For User management

$menus[] = array(
    'key' => 'usersmanagement',
	'title' => 'Users management',
	'url' => '#',
	'fa' => 'fa-users',
	'flag' => array(
		array(
			'key' => 'addanewuser',
			'title' => 'Add a new user',
			'url' => '/user/editadvanced.php?id=-1'
		),
		array(
			'key'=> 'manageusers',
			'title' =>'Manage users',
			'url' =>'/local/usermanagement/index.php'
		),
		array(
			'key' => 'manageenrolment',
			'title' => 'Manage enrolment',
			'url' => '/admin/settings.php?section=manageenrols'
		),
		array(
			'key'=> 'managecohorts',
			'title' =>'Manage cohorts',
			'url' => '/cohort/index.php'
		),array(
			'key'=> 'uploadusers',
			'title' =>'Upload users',
			'url' => '/admin/tool/uploaduser/index.php'
		),array(
			'key'=> 'changeuserspassword',
			'title' =>'Change users password',
			'url' => '/local/usermanagement/index.php'
		),array(
				'key'=> 'sentinvitation',
				'title' =>'Sent invitation to new user',
				'url' => '/local/sendinvitation/index.php'
		)
		,array(
				'key'=> 'cohortfunctions',
				'title' =>'Cohort Functions',
				'url' => '/local/usermanagement/index1.php'
		),array(
				'key'=> 'userfunctions',
				'title' =>'User Functions',
				'url' => '/local/usermanagement/index.php'
		),array(
				'key'=> 'invitedlist',
				'title' =>'Sent invited list',
				'url' => '/local/sendinvitation/invitedlist.php'
		),array(
				'key'=> 'skillquickenrolment',
				'title' =>'Quick Enrolment',
				'url' => '//blocks/enrollment/enrollment.php'
		)
	)
);

//For Catalogue management

$menus[] = array(
    'key' => 'cataloguemanagement',
	'title' => 'Catalogue management',
	'url' => '#',
	'fa' => 'fa-newspaper-o',
	'flag' => array(
		array(
			'key' => 'managecategory',
			'title' => 'Manage category',
			'url' => '/course/management.php'
		),
		array(
			'key'=> 'managecourses',
			'title' =>'Manage courses',
			'url' =>'/course/management.php'
		),
		array(
			'key' => 'manageenrolmentsmethods',
			'title' => 'Manage enrolments methods',
			'url' => '/course'
		),
		array(
			'key'=> 'manageenrolment',
			'title' =>'Manage enrolment',
			'url' => '/course'

		)
	)
);

//For Courses management

$menus[] = array(
    'key' => 'coursesmanagement',
	'title' => 'Courses management',
	'url' => '#',
	'fa' => 'fa-graduation-cap',
	'flag' => array(
		array(
			'key' => 'addacategory',
			'title' => 'Add a category',
			'url' => '/course/editcategory.php?parent=0'
		),
		array(
			'key'=> 'addacourses',
			'title' =>'Add a courses',
			'url' =>'/course/edit.php?category=1&returnto=catmanage'
		),
		array(
			'key' => 'addactivity',
			'title' => 'Add activity',
			'url' => '/course'
		),
		array(
			'key'=> 'manageactivity',
			'title' =>'Manage Activity',
			'url' => '/admin/modules.php'

		),
		array(
			'key' => 'managerules',
			'title' => 'Manage rules',
			'url' => '/course'
		),
		array(
			'key'=> 'managegrades',
			'title' =>'Manage grades',
			'url' =>'/admin/settings.php?section=gradeitemsettings'
		),
		array(
			'key' => 'managetags',
			'title' => 'Manage Tags',
			'url' => '/tag/manage.php'
		),
		array(
			'key'=> 'managebadges',
			'title' =>'Manage badges',
			'url' => '/badges/index.php?type=1'

		),
		array(
			'key'=> 'manageenrolmentsmethods',
			'title' =>'Manage enrolments methods',
			'url' =>'/enrol/instances.php?id='.$COURSE->id
		),
		array(
			'key' => 'manageusersenrolments',
			'title' => 'Manage users enrolments',
			'url' => '/enrol/users.php?id='.$COURSE->id
		),
		array(
			'key' => 'scormupdate',
			'title' => 'Update Scorm Status',
			'url' => '/my/scormupdate.php'
		)
		
	)
);

//For Assessment management

$menus[] = array(
    'key' => 'assessmentmanagement',
	'title' => 'Assessment management',
	'url' => '#',
	'fa' => 'fa-check-square-o',
	'flag' => array(
		array(
			'key' => 'addcategory',
			'title' => 'Add category',
			'url' => '/question/category.php?courseid='.$COURSE->id
		),
		array(
			'key'=> 'addlevel',
			'title' =>'Add level',
			'url' =>'/course'
		),
		array(
			'key' => 'addquestion',
			'title' => 'Add question',
			'url' => '/question/edit.php?courseid='.$COURSE->id
		),
		array(
			'key'=> 'uploadquestion',
			'title' =>'Upload question',
			'url' => '/question/import.php?courseid='.$COURSE->id

		),
		array(
			'key' => 'managequestionbank',
			'title' => 'Manage question bank',
			'url' => '/question/edit.php?courseid='.$COURSE->id
		),
		array(
			'key'=> 'createassessment',
			'title' =>'UCreate assessment',
			'url' => '/course/modedit.php?add=quiz&type=&course='.$COURSE->id.'&section=0&return=0&sr=0'

		)
	)
);
//For Certificates management

$menus[] = array(
    'key' => 'certificatemanagement',
	'title' => 'Certificates management',
	'url' => '#',
	'fa' => 'fa-trophy',
	'flag' => array(
		array(
			'key' => 'addcertificate',
			'title' => 'Add certificate',
			'url' => '/course/modedit.php?add=certificate&type=&course='.$COURSE->id.'&section=2&return=0&sr=0'
		),
		array(
			'key'=> 'managecertificate',
			'title' =>'Manage certificate',
			'url' =>'/course'
		),

	)
);

//For Reports management

$menus[] = array(
    'key' => 'reportsmanagement',
	'title' => 'Reports management',
	'url' => '#',
	'fa' => 'fa-file-text',
	'flag' => array(
		array(
			'key' => 'createreport',
			'title' => 'Create report',
			'url' => '/blocks/configurable_reports/managereport.php'
		),
		array(
			'key'=> 'managereport',
			'title' =>'Manage report',
			'url' =>'/local/profile_report_mapping'
		),array(
			'key'=> 'schedulemailreport',
			'title' =>'Schedule Mail report',
			'url' =>'/local/report12/index.php'
		)
	)
);

// print_object($menus);
// die;
