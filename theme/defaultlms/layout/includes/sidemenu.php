<?php
// include 'menulink.php';
// include 'menuconfig.php';
// include 'dashboardlib.php';
$liclass = '';
$context = context_user::instance($USER->id);
//echo '<script src="'.$CFG->wwwroot.'/theme/defaultlms/javascript/Chart.js"></script>';
?>
<!-- Left side column. contains the logo and sidebar -->
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
                    <a class="active" href="<?php echo $CFG->wwwroot.'/my'?>">
                        <i class="fa fa-tachometer"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="sub-menu" href="<?php echo $CFG->wwwroot.'/user/profile.php?id='.$USER->id?>">
                        <i class="fa fa-user"></i>
                        <span>Subscriptions</span>
                    </a>
                </li>
                <li>
                    <a class="sub-menu" href="<?php echo $CFG->wwwroot.'/course/'?>">
                        <i class="fa fa-book"></i>
                        <span>Courses</span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-laptop"></i>
                        <span>Practicals</span>
                    </a>
                    <ul class="sub">
                        <li><a href="boxed_page.html">Labs</a></li>
                        <li><a href="horizontal_menu.html">Faculty Reviews</a></li>
                        <li><a href="language_switch.html">Lab Reports</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-code-fork"></i>
                        <span>Projects</span>
                    </a>
                    <ul class="sub">
                        <li><a href="general.html">Projects</a></li>
                        <li><a href="buttons.html">Group Management</a></li>
                        <li><a href="typography.html">Search</a></li>
                        <li><a href="widget.html">Access Peer</a></li>
                    </ul>
                </li>
                <li>
                    <a href="fontawesome.html">
                        <i class="fa fa-list-alt"></i>
                        <span>Assessments </span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Mentors</span>
                    </a>
                    <ul class="sub">
                        <li><a href="basic_table.html">Search</a></li>
                        <li><a href="responsive_table.html">View Schedules</a></li>
                        <li><a href="dynamic_table.html">Feedback</a></li>
                    </ul>
                </li>
                <li>
                    <a href="fontawesome.html">
                        <i class="fa fa-bar-chart"></i>
                        <span>Resume Analyser </span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-tasks"></i>
                        <span>Job Bridge</span>
                    </a>
                    <ul class="sub">
                        <li><a href="form_component.html">Apply Jobs/Internships</a></li>
                        <li><a href="advanced_form.html">Career Guidance</a></li>
                        <li><a href="form_wizard.html">Resume Writer</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-video-camera"></i>
                        <span>Conferencing </span>
                    </a>
                    <ul class="sub">
                        <li><a href="mail.html">iKonnect</a></li>
                        <li><a href="mail_compose.html">Zoom</a></li>
                    </ul>
                </li>
            </ul>            
        </div>
        <!-- sidebar menu end-->
    </div>
</aside>


