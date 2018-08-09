<?php
include 'menulink.php';
$context = context_system::instance();
?>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="chart-responsive col-md-12">
                <canvas id="pieChart" height="150"></canvas>
                <div class="chart" style="display:none;">
                <!-- Sales Chart Canvas -->
                    <canvas id="salesChart" style="height: 150px;"></canvas>
                </div>
           </div>
            <div class="col-md-12">
              <ul class="chart-legend clearfix">
                <li><i class="fa fa-circle-o text-green"></i> Completed</li>
                <li><i class="fa fa-circle-o text-yellow"></i> In Progress</li>
                <li><i class="fa fa-circle-o text-red"></i> Not Started</li>
              </ul>
            </div>
       </div>
        <ul class="sidebar-menu">
            <li class="header">Dashboard Menu</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active treeview">
                <a href="<?php echo $home; ?>"><i class="fa fa-home"></i> <span>Home</span>
                    <span class="pull-right-container">
                        <i class="fa fa-plus pull-right"></i>
                    </span>
                </a>
                <?php if (has_capability('local/skillaccess:myhome', $context)) { ?>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo $home; ?>">My Home</a></li>
                    </ul> 
                <?php } ?>
            </li>
            <?php if (has_capability('local/skillaccess:mycourses', $context)) { ?>
                <li class="treeview">
                    <a href="<?php echo $mycourses; ?>"><i class="fa fa-graduation-cap"></i> <span>My Courses</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (has_capability('local/skillaccess:mycourses', $context)) { ?>
                            <li><a href="<?php echo $mycourses; ?>">My Courses</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:assignedcourses', $context)) { ?>
                            <li><a href="<?php echo $assignedcourses; ?>" class="active">Assigned Courses</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:mylearningplan', $context)) { ?>
                            <li><a href="<?php echo $mylearningplan; ?>">My Learning Plan</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:selfenrolled', $context)) { ?>
                            <li><a href="<?php echo $selfenrolled; ?>">Self enrolled</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:recommendedforyou', $context)) { ?>
                            <li><a href="<?php echo $recomendedforu; ?>">Recommended for you</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:availablecourses', $context)) { ?>
                            <li><a href="<?php echo $availablecourses; ?>">Available Courses</a></li>
                        <?php } ?>

                    </ul>
                </li>
            <?php } ?>
            <?php if (has_capability('local/skillaccess:myreports', $context)) { ?>
                <li class="treeview">
                    <a href="<?php echo $myreport; ?>"><i class="fa fa-file-text"></i> <span>My Reports</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (has_capability('local/skillaccess:myreports', $context)) { ?>
                            <li><a href="<?php echo $myreport; ?>">My reports</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:coursereport', $context)) { ?>
                            <li><a href="<?php echo $coursereport; ?>">Course report</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:activityreports', $context)) { ?>
                            <li><a href="<?php echo $activityreport; ?>">Activity reports</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:summaryreport', $context)) { ?>
                            <li><a href="<?php echo $summaryreport; ?>">Summary report</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:mycertificates', $context)) { ?>
                            <li><a href="<?php echo $mycertificates; ?>">My certificates</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if (has_capability('local/skillaccess:sociallearning', $context)) { ?>
                <li class="treeview">
                    <a href="#"><i class="fa fa-th"></i> <span>Social learning</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (has_capability('local/skillaccess:forums', $context)) { ?>
                            <li><a href="<?php echo $forums; ?>">Forums</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:newsandnotifications', $context)) { ?>
                            <li><a href="<?php echo $newsandnotification; ?>">News & notifications</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:surveys', $context)) { ?>
                            <li><a href="<?php echo $survey; ?>">Surveys</a></li>
                        <?php } ?>
                        <?php //if (has_capability('local/skillaccess:faqs', $context)) { ?>
                            <!--<li><a href="">FAQs</a></li>-->
                        <?php //} ?>
                        <?php if (has_capability('local/skillaccess:blog', $context)) { ?>
                            <li><a href="<?php echo $blog; ?>">Blog</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:wiki', $context)) { ?>
                            <li><a href="<?php echo $wiki; ?>">WIKI</a></li>
                        <?php } ?>
                        <?php// if (has_capability('local/skillaccess:announcements', $context)) { ?>
                            <!--<li><a href="">Announcements</a></li>-->
                        <?php //} ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if (has_capability('local/skillaccess:notification', $context)) { ?>
                <li class="treeview">
                    <a href="#"><i class="fa fa-bell"></i> <span>Notification</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (has_capability('local/skillaccess:message', $context)) { ?>
                            <li><a href="<?php echo $message; ?>">Message</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:event', $context)) { ?>
                            <li><a href="<?php echo $event; ?>">Event</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if (has_capability('local/skillaccess:myprofile', $context)) { ?>
                <li class="treeview">
                    <a href="#"><i class="fa fa-user"></i> <span>My Profile</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (has_capability('local/skillaccess:myprofile', $context)) { ?>
                            <li><a href="<?php echo $myprofile; ?>">My profile</a></li>
                        <?php } ?>

                        <?php if (has_capability('local/skillaccess:changepassword', $context)) { ?>
                            <li><a href="<?php echo $changepassword; ?>">Change password</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:profileprivacy', $context)) { ?>
                            <li><a href="<?php echo $profileprivacy; ?>">Profile privacy</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if (has_capability('local/skillaccess:clientadministration', $context)) { ?>
                <li class="treeview">
                    <a href="#"><i class="fa fa-cog"></i> <span>Client administration</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (has_capability('local/skillaccess:addanewclient', $context)) { ?>
                            <li><a href="<?php echo $addaclient; ?>">Add a new client</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:editclient', $context)) { ?>    
                            <li><a href="<?php echo $editclient; ?>">Edit client</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:manageclients', $context)) { ?>    
                            <li><a href="<?php echo $manageclient; ?>">Manage clients</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managemodule', $context)) { ?>    
                            <li><a href="<?php echo $managemodule; ?>">Manage module</a></li>
                        <?php } ?>
                        <?php //if (has_capability('local/skillaccess:managemenu', $context)) { ?>    
                            <!--<li><a href="">Manage menu </a></li>-->
                        <?php// } ?>
                        <?php if (has_capability('local/skillaccess:manageglobalcatalog', $context)) { ?>    
                            <li><a href="<?php echo $managelobalcatalog; ?>">Manage global catalog</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:loggedusers', $context)) { ?>    
                            <li><a href="<?php echo $loggeduserc; ?>">Logged users</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if (has_capability('local/skillaccess:systemadministration', $context)) { ?>
                <li class="treeview">
                    <a href="#"><i class="fa fa-wrench"></i> <span>System administration</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (has_capability('local/skillaccess:administration', $context)) { ?> 
                            <li><a href="<?php echo $administration; ?>">Administration</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:loggedusers', $context)) { ?> 
                            <li><a href="<?php echo $loggeduser; ?>">Logged users</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managesystempolicies', $context)) { ?> 
                            <li><a href="<?php echo $managesystempolicies; ?>">Manage system policies</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managelocationsettings', $context)) { ?> 
                            <li><a href="<?php echo $managelocationsettings; ?>">Manage location settings</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:languagecustomisation', $context)) { ?> 
                            <li><a href="<?php echo $languagecustomisation; ?>">Language customisation</a></li>
                        <?php } ?>
                        <?php //if (has_capability('local/skillaccess:manageloginpage', $context)) { ?> 
                            <!--<li><a href="">Manage login page</a></li>-->
                        <?php //} ?>
                        <?php //if (has_capability('local/skillaccess:manageuserinterface', $context)) { ?> 
                            <!--<li><a href="">Manage user interface</a></li>-->
                        <?php //} ?>
                        <?php if (has_capability('local/skillaccess:managetheme', $context)) { ?> 
                            <li><a href="<?php echo $managetheme; ?>">Manage theme</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:manageuserprofilefields', $context)) { ?> 
                            <li><a href="<?php echo $manageuserprofilefields; ?>">Manage user profile fields</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:manageuserfieldprivacy', $context)) { ?> 
                            <li><a href="<?php echo $manageuserfieldprivacy; ?>">Manage user field privacy</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:summaryreport', $context)) { ?> 
                            <li><a href="<?php echo $managecalendar; ?>">Manage calendar</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managedefaultmessageoutputs', $context)) { ?> 
                            <li><a href="<?php echo $managedefaultmessageoutputs; ?>">Manage default message outputs</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managecustomnotifications', $context)) { ?> 
                            <li><a href="<?php echo $managecustomnotifications; ?>">Manage custom notifications</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:manageauthentication', $context)) { ?> 
                            <li><a href="<?php echo $manageauthentication; ?>">Manage authentication</a></li>
                        <?php } ?>
                        <?php //if (has_capability('local/skillaccess:summaryreport', $context)) { ?> 
                            <!--<li><a href="">Manage course completion</a></li>-->
                        <?php //} ?>
                        <?php if (has_capability('local/skillaccess:managecourseformats', $context)) { ?> 
                            <li><a href="<?php echo $managecourseformats; ?>">Manage course formats</a></li>
                        <?php } ?>

                        <?php if (has_capability('local/skillaccess:manageenrol', $context)) { ?> 
                            <li><a href="<?php echo $manageenrol; ?>">Manage enrol</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managewebservices', $context)) { ?> 
                            <li><a href="<?php echo $managewebservices; ?>">Manage web services</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managesupportcontact', $context)) { ?> 
                            <li><a href="<?php echo $managesupportcontact; ?>">Manage support contact</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managemaintenancemode', $context)) { ?> 
                            <li><a href="<?php echo $managemaintenancemode; ?>">Manage maintenance mode</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managesystemcleanup', $context)) { ?> 
                            <li><a href="<?php echo $managesystemcleanup; ?>">Manage system cleanup </a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managecaches', $context)) { ?> 
                            <li><a href="<?php echo $managecaches; ?>">Manage caches</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managescheduledtasks', $context)) { ?> 
                            <li><a href="<?php echo $managescheduledtasks; ?>">Manage scheduled tasks</a></li>
                        <?php } ?>
                        <?php //if (has_capability('local/skillaccess:notificationdeliverystatus', $context)) { ?> 
                            <!--<li><a href="">Notification delivery status</a></li>-->
                        <?php //} ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if (has_capability('local/skillaccess:usersmanagement', $context)) { ?> 
                <li class="treeview">
                    <a href="#"><i class="fa fa-users"></i> <span>Users management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (has_capability('local/skillaccess:addanewuser', $context)) { ?> 
                            <li><a href="<?php echo $addauser; ?>">Add a new user</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:manageusers', $context)) { ?> 
                            <li><a href="<?php echo $manageuser; ?>">Manage users</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:manageenrolment', $context)) { ?> 
                            <li><a href="<?php echo $manageenrolmentu; ?>">Manage enrolment </a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managecohorts', $context)) { ?> 
                            <li><a href="<?php echo $managecohort; ?>">Manage cohorts</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:uploadusers', $context)) { ?> 
                            <li><a href="<?php echo $uploaduser; ?>">Upload users</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:changeuserspassword', $context)) { ?> 
                            <li><a href="<?php echo $changeuserpassword; ?>">Change users password</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if (has_capability('local/skillaccess:cataloguemanagement', $context)) { ?>
                <li class="treeview">
                    <a href="#"><i class="fa fa-newspaper-o"></i> <span>Catalogue management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (has_capability('local/skillaccess:managecategory', $context)) { ?> 
                            <li><a href="<?php echo $managecategory; ?>">Manage category</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managecourses', $context)) { ?> 
                            <li><a href="<?php echo $managecourse; ?>">Manage courses</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:manageenrolmentsmethods', $context)) { ?> 
                            <li><a href="<?php echo $manageenrolmentmethod; ?>">Manage enrolments methods</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:manageenrolment', $context)) { ?> 
                            <li><a href="<?php echo $manageenrolment; ?>">Manage enrolment</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if (has_capability('local/skillaccess:coursesmanagement', $context)) { ?> 
                <li class="treeview">
                    <a href="#"><i class="fa fa-graduation-cap"></i> <span>Courses management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (has_capability('local/skillaccess:addacategory', $context)) { ?> 
                            <li><a href="<?php echo $addacategory; ?>">Add a category</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:addacourses', $context)) { ?> 
                            <li><a href="<?php echo $addacourse; ?>">Add a courses</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:addactivity', $context)) { ?> 
                            <li><a href="<?php echo $addactivity; ?>">Add activity</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:manageactivity', $context)) { ?> 
                            <li><a href="<?php echo $manageactivity; ?>">Manage Activity</a></li>
                        <?php } ?>
                        <?php //if (has_capability('local/skillaccess:managerules', $context)) { ?> 
                            <!--<li><a href="">Manage rules</a></li>-->
                        <?php //} ?>
                        <?php if (has_capability('local/skillaccess:managegrades', $context)) { ?> 
                            <li><a href="<?php echo $managegrades; ?>">Manage grades</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managetags', $context)) { ?> 
                            <li><a href="<?php echo $managetags; ?>">Manage Tags</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managebadges', $context)) { ?> 
                            <li><a href="<?php echo $managebadges; ?>">Manage badges</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:manageenrolmentsmethods', $context)) { ?> 
                            <li><a href="<?php echo $manageenrolmentmethodc; ?>">Manage enrolments methods</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:manageusersenrolments', $context)) { ?> 
                            <li><a href="<?php echo $manageuserenrolment; ?>">Manage users enrolments</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <!--     <li class="treeview">
                    <a href="#"><i class="fa fa-th"></i> <span>Social learning management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo $manageforum; ?>">Manage forums</a></li>
                        <li><a href="<?php echo $managenews; ?>">Manage news and notifications</a></li>
                        <li><a href="<?php echo $managedisforums; ?>">Manage discussion forums</a></li>
                        <li><a href="<?php echo $managesurvey; ?>">Manage surveys</a></li>
                        <li><a href="<?php echo $managefaq; ?>">Manage FAQs</a></li>
                        <li><a href="<?php echo $manageblog; ?>">Manage blog</a></li>
                        <li><a href="<?php echo $managewiki; ?>">Manage WIKI</a></li>
                        <li><a href="<?php echo $manageannouncement; ?>">Manage announcements</a></li>
                    </ul>
                </li> -->
            <?php if (has_capability('local/skillaccess:assessmentmanagement', $context)) { ?> 
                <li class="treeview">
                    <a href="#"><i class="fa fa-check-square-o"></i> <span>Assessment management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (has_capability('local/skillaccess:addcategory', $context)) { ?> 
                            <li><a href="<?php echo $addcategory; ?>">Add category</a></li>
                        <?php } ?>
                        <?php //if (has_capability('local/skillaccess:addlevel', $context)) { ?> 
                            <!--<li><a href="">Add level</a></li>-->
                        <?php //} ?>
                        <?php if (has_capability('local/skillaccess:addquestion', $context)) { ?> 
                            <li><a href="<?php echo $addquestion; ?>">Add question</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:uploadquestion', $context)) { ?> 
                            <li><a href="<?php echo $uploadquestion; ?>">Upload question</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managequestionbank', $context)) { ?> 
                            <li><a href="<?php echo $managequesbank; ?>">Manage question bank</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:createassessment', $context)) { ?> 
                            <li><a href="<?php echo $createassessment; ?>">Create assessment</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if (has_capability('local/skillaccess:certificatemanagement', $context)) { ?> 
                <li class="treeview">
                    <a href="#"><i class="fa fa-trophy"></i> <span>Certificates management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (has_capability('local/skillaccess:addcertificate', $context)) { ?> 
                            <li><a href="<?php echo $addcrtfct; ?>">Add certificate</a></li>
                        <?php } ?>
                        <?php //if (has_capability('local/skillaccess:managecertificate', $context)) { ?> 
                            <!--<li><a href="">Manage certificate</a></li>-->
                        <?php //} ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if (has_capability('local/skillaccess:reportsmanagement', $context)) { ?> 
                <li class="treeview">
                    <a href="#"><i class="fa fa-file-text"></i> <span>Reports management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-plus pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (has_capability('local/skillaccess:createreport', $context)) { ?> 
                            <li><a href="<?php echo $createrpt; ?>">Create report</a></li>
                        <?php } ?>
                        <?php if (has_capability('local/skillaccess:managereport', $context)) { ?> 
                            <li><a href="<?php echo $managerpt; ?>">Manage report</a></li>
                            <li><a href="<?php echo $profilefiledmap; ?>">User field/report map</a></li>
                        <?php } ?> 
                    </ul>
                </li>
            <?php } ?> 
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>


