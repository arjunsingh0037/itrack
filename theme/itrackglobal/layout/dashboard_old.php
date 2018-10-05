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
 * Parent theme: Bootstrapbase by Bas navbar-brands
 * Built on: Essential by Julian Ridden
 *
 * @package   theme_defaultlms
 * @copyright 2014 redPIthemes
 *
 */

$standardlayout = (empty($PAGE->theme->settings->layout)) ? false : $PAGE->theme->settings->layout;
global $CFG, $USER,$DB;
if (right_to_left()) {
    $regionbsid = 'region-bs-main-and-post';
} else {
    $regionbsid = 'region-bs-main-and-pre';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google web fonts -->
    <?php require_once(dirname(__FILE__).'/includes/fonts.php'); 
    ?>
     
</head>

<body <?php echo $OUTPUT->body_attributes('hold-transition skin-blue sidebar-mini nopadding fixed'); ?>>

<?php echo $OUTPUT->standard_top_of_body_html(); ?>
<!-- Start Main Regions -->
<!-- <div id="page" class="container-fluid" style ="display:none">
    <div id="page-content" class="row" style ="display:none">
        <div id="<?php echo $regionbsid ?>" class="col-md-9">
            <?php
            echo $OUTPUT->course_content_header();
            echo $OUTPUT->main_content();
            echo $OUTPUT->course_content_footer();
            ?>    
        </div>
        <?php echo $OUTPUT->blocks('side-post', 'col-md-3'); ?>
    </div>
</div> -->
<!-- <div class="wrapper"> -->
    <?php

        // include_once("$CFG->dirroot/my/locallib.php");
        // include_once("$CFG->dirroot/theme/defaultlms/layout/includes/customheader.php");
        // include_once("$CFG->dirroot/theme/defaultlms/layout/includes/sidemenu.php");
    ?>
<!-- <div class="row content-wrapper contctwrper"> -->
    <!-- Content Header (Page header) -->
<!-- <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 dashboard-banner"> -->

<!-- Mihir 15/10
<img  src="<?php echo $CFG->wwwroot.'/theme/defaultlms/pix/banner-dashboard.jpg'?>" class="img-responsive hidden-xs" />
<img  src="<?php echo $CFG->wwwroot.'/theme/defaultlms/pix/banner-dashboard-mobile.jpg'?>" class="img-responsive visible-xs" /> -->

<?php //require_once('includes/dashboardslider.php'); ?>
<!-- </div> -->


    <!-- <section class="content-header"> -->
       <!-- Mihir 15/10
	   <small>Home</small>
      </h1>
	  -->
    <!-- </section> -->

    <!-- Main content -->
    <!-- <section class="content content-home"> -->

<!-- Your Page Content Here -->


<!-- <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 zeropadding nopadding box-dashboard mrgtp10"> -->

<!-- START : row - 1 -->
  <div class="row row-dashboard">
    <div class="col-sm-4 col-xs-12">
      <!-- START : Dashboard box - 1 -->
      <?php
        
           $courses = enrol_get_my_courses('id');
            $mnlcount = 0;
            foreach ($courses as $course) {
                $enrolments = user_enrolments($course->id,$USER->id);
                foreach ($enrolments as $enrol)
                {
                    if ($enrol->enrol == 'manual')
                    {
                        $mnlcount++;    
                    }
                }
            }
          ?>
       
          <div class="col-md-12 col-lg-12 col-xs-12 zeropadding nopadding small-box bg-aqua">
          <div class="box-arrow-right"><div class="box-count"><?php echo $mnlcount ?></div></div><!-- count -->
            <div class="icon-dashboard-wrapper col-sm-12 col-xs-3">
            <a href="<?php echo $assignedcourses ?>" class ="colorwhte"><i class="fa fa-pencil-square-o icon-dashboard"></i></a>
            </div>
             <div class="inner col-sm-12 col-xs-9">
              <p><a href="<?php echo $assignedcourses ?>" class ="colorwhte">Assigned Courses</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 1 -->
    </div>
    <div class="col-sm-4 col-xs-12">
<!-- START : Dashboard box - 2 -->
          <div class="col-md-12 col-lg-12 col-xs-12 zeropadding nopadding small-box bg-yellow">
           <div class="box-arrow-right"><div class="box-count">200</div></div><!-- count -->
            <div class="icon-dashboard-wrapper col-sm-12 col-xs-3">
            <a href="<?php echo $mylearningplan ?>" class ="colorwhte"><i class="fa fa-graduation-cap icon-dashboard-large"></i>
            </a></div>
             <div class="inner col-sm-12 col-xs-9">
              <p><a href="<?php echo $mylearningplan ?>" class ="colorwhte">My learning plan</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 2 -->
    </div>
  </div>
<!--/ END : row - 1 -->


<!-- START : row - 2 -->
  <div class="row row-dashboard">
    <div class="col-sm-4 col-xs-12">
      <!-- START : Dashboard box - 1 -->
          <div class="col-md-12 col-lg-12 col-xs-12 zeropadding nopadding small-box bg-dashboard-box1">
           
            <div class="icon-dashboard-wrapper col-sm-12 col-xs-3">
            <a href="<?php echo $newsandnotification ?>" class ="colorwhte"><i class="fa fa-newspaper-o icon-dashboard"></i>
            </a></div>
            <div class="inner col-sm-12 col-xs-9">
              <p><a href="<?php echo $newsandnotification ?>" class ="colorwhte">News & notifications</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 1 -->
    </div>
    <div class="col-sm-4 col-xs-12">
<!-- START : Dashboard box - 2 -->
          <div class="col-md-12 col-lg-12 col-xs-12 zeropadding nopadding small-box  bg-purple">
           
            <div class="icon-dashboard-wrapper col-sm-12 col-xs-3">
            <a href="<?php echo $event ?>" class ="colorwhte"><i class="fa fa-flag icon-dashboard"></i></a>
            </div>
             <div class="inner col-sm-12 col-xs-9">
              <p><a href="<?php echo $event ?>" class ="colorwhte">Events</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 2 -->
    </div>
    <div class="col-sm-4 col-xs-12">
<!-- START : Dashboard box - 3 -->
          <?php
           $courses = enrol_get_my_courses('id');
            $slfcount = 0;
            foreach ($courses as $course) {
                $enrolments = user_enrolments($course->id,$USER->id);
                foreach ($enrolments as $enrol)
                {
                    if ($enrol->enrol == 'self')
                    {
                        $slfcount++;    
                    }
                }
            }
          ?>
          <div class="col-md-12 col-lg-12 col-xs-12 zeropadding nopadding small-box bg-dashboard-box2">
           <div class="box-arrow-right"><div class="box-count"><?php echo  $slfcount ?></div></div><!-- count -->
            <div class="icon-dashboard-wrapper col-sm-12 col-xs-3">
            <a href="<?php echo $selfenrolled ?>" class ="colorwhte"><i class="fa fa-laptop icon-dashboard"></i></a>
            </div>
             <div class="inner col-sm-12 col-xs-9">
              <p><a href="<?php echo $selfenrolled ?>" class ="colorwhte">Self enrolled</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 3 -->
    </div>
  </div>
<!--/ END : row - 2 -->



<!-- START : row - 3 -->
  <div class="row row-dashboard">
    
  <div class="col-sm-4">

      <div class="col-sm-6 zeropadding nopadding-left-small padrft6px">
    <!-- START : Dashboard box - 3 -->
          <div class="col-md-12 col-lg-12 col-xs-6 zeropadding nopadding small-box cpassword bg-purple">
           <div class="icon-dashboard-wrapper-small">
            <a href="<?php echo $changepassword ?>" class ="colorwhte"><i class="fa fa-ellipsis-h icon-dashboard-small"></i>
            </a></div>
             <div class="inner-small ">
              <p><a href="<?php echo $changepassword ?>" class ="colorwhte">Change password</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 3 -->
    </div>
    <div class="col-sm-6 margin-small-box zeropadding nopadding-right-small mob-align-margin padlft6px">
    <!-- START : Dashboard box - 2 -->
          <div class="col-md-12 col-lg-12 col-xs-6 zeropadding nopadding small-box small-box-right bg-dashboard-box2">
           <div class="icon-dashboard-wrapper-small">
            <a href="<?php echo $summaryreport ?>" class ="colorwhte"><i class="fa fa-file-text icon-dashboard-small"></i>
            </a></div>
             <div class="inner-small">
              <p><a href="<?php echo $summaryreport ?>" class ="colorwhte">Summary report</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 2 -->
    </div>
  
      <div class="col-sm-6 margin-small-box-2-row zeropadding nopadding-left-small padrft6px">
    <!-- START : Dashboard box - 3 -->
          <div class="col-md-12 col-lg-12 col-xs-6 zeropadding nopadding small-box bg-aqua">
           <div class="icon-dashboard-wrapper-small">
             <a href="<?php echo $message ?>" class ="colorwhte"><i class="fa fa-envelope-o icon-dashboard-small"></i>
            </a></div>
             <div class="inner-small">
              <p> <a href="<?php echo $message ?>" class ="colorwhte">Message</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 3 -->
    </div>
    <div class="col-sm-6 margin-small-box-2-row zeropadding nopadding-right-small padlft6px">
    <!-- START : Dashboard box - 4 -->
          <div class="col-md-12 col-lg-12 col-xs-6 zeropadding nopadding small-box small-box-right bg-teal">
           <div class="icon-dashboard-wrapper-small">
            <a href="<?php echo $profileprivacy ?>" class ="colorwhte"><i class="fa fa-lock icon-dashboard-small-small"></i>
            </a></div>
             <div class="inner-small">
              <p><a href="<?php echo $profileprivacy ?>" class ="colorwhte">Profile privacy</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 4 -->
    </div>
 </div>


<div class="col-sm-4">
<!-- START : Dashboard box - 5 -->
          <?php
            $avcount = 0;
            $courses = get_courses('', '', 'c.id');
            foreach ($courses as $course)
            {
              $enrolments = enrol_get_instances($course->id,true);
              if (count($enrolments) > 1)
              {
                $avcount++;
              }
            }

          ?>
          <div class="col-md-12 col-lg-12 col-xs-12 zeropadding nopadding small-box  bg-dashboard-box3">
           <div class="box-arrow-right"><div class="box-count"><?php echo  $avcount ?></div></div><!-- count -->
            <div class="icon-dashboard-wrapper col-sm-12 col-xs-3">
            <a href="<?php echo $availablecourses ?>" class ="colorwhte"><i class="fa fa-pencil-square-o icon-dashboard"></i>
            </a></div>
             <div class="inner col-sm-12 col-xs-9">
              <p><a href="<?php echo $availablecourses ?>" class ="colorwhte">Available courses</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 5 -->
    </div>

   <div class="col-sm-4">

   <div class="col-sm-6 margin-small-box zeropadding nopadding-left-small padrft6px">
      <!-- START : Dashboard box - 6 -->
          <div class="col-md-12 col-lg-12 col-xs-6 zeropadding nopadding small-box bg-dashboard-box1">
           <div class="icon-dashboard-wrapper-small">
            <a href="<?php echo $coursereport ?>" class ="colorwhte"><i class="fa fa-file-text-o icon-dashboard-small"></i>
            </a></div>
             <div class="inner-small">
              <p><a href="<?php echo $coursereport ?>" class ="colorwhte">Course report</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 6 -->
    </div>
    <div class="col-sm-6 margin-small-box zeropadding nopadding-right-small padlft6px">
    <!-- START : Dashboard box - 7 -->
          <div class="col-md-12 col-lg-12 col-xs-6 zeropadding nopadding small-box small-box-right bg-yellow">
           <div class="icon-dashboard-wrapper-small">
            <a href="<?php echo $activityreport ?>" class ="colorwhte"><i class="fa fa-file-text-o icon-dashboard-small"></i>
            </a></div>
             <div class="inner-small">
              <p><a href="<?php echo $activityreport ?>" class ="colorwhte">Activity reports</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 7 -->
    </div>
  
      <div class="col-sm-6 margin-small-box-2-row zeropadding nopadding-left-small padrft6px">
    <!-- START : Dashboard box - 8 -->
          <div class="col-md-12 col-lg-12 col-xs-6 zeropadding nopadding small-box bg-purple">
           <div class="icon-dashboard-wrapper-small">
            <a href="<?php echo $survey ?>" class ="colorwhte"><i class="fa fa-files-o icon-dashboard-small"></i>
            </a></div>
             <div class="inner-small">
              <p><a href="<?php echo $survey ?>" class ="colorwhte">Surveys</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 8 -->
    </div>
    <div class="col-sm-6 margin-small-box-2-row zeropadding nopadding-right-small padlft6px">
    <!-- START : Dashboard box - 9 -->
          <div class="col-md-12 col-lg-12 col-xs-6 zeropadding nopadding small-box small-box-right bg-dashboard-box3">
           <div class="icon-dashboard-wrapper-small">
            <a href="<?php echo $coursereport ?>" class ="colorwhte"><i class="fa fa-question-circle icon-dashboard-small"></i>
            </a></div>
             <div class="inner-small">
              <p><a href="<?php echo $coursereport ?>" class ="colorwhte">FAQs</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 9 -->
    </div>
 </div>

  </div>
<!--/ END : row - 3 -->



<!-- START : row - 4-->
  <div class="row row-dashboard">
    <div class="col-sm-4">
      <!-- START : Dashboard box - 1 -->
          <div class="col-md-12 col-lg-12 col-xs-12 zeropadding nopadding small-box bg-dashboard-box1">
           
            <div class="icon-dashboard-wrapper col-sm-12 col-xs-3">
            <a href="<?php echo $forums ?>" class ="colorwhte"><i class="fa fa-comment-o icon-dashboard"></i>
            </a></div>
             <div class="inner col-sm-12 col-xs-9">
              <p><a href="<?php echo $forums ?>" class ="colorwhte">Forums</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 1 -->
    </div>

   <div class="col-sm-4">

   <div class="col-sm-6 margin-small-box zeropadding nopadding-left-small padrft6px">
      <!-- START : Dashboard box - 2 -->
          <div class="col-md-12 col-lg-12 col-xs-6 zeropadding nopadding small-box bg-aqua">
           <div class="icon-dashboard-wrapper-small">
            <a href="<?php echo $blog ?>" class ="colorwhte"><i class="fa fa-rss-square icon-dashboard-small"></i>
            </a></div>
             <div class="inner-small">
              <p><a href="<?php echo $blog ?>" class ="colorwhte">Blog</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 2 -->
    </div>
    <div class="col-sm-6 margin-small-box zeropadding nopadding-right-small padlft6px">
    <!-- START : Dashboard box - 3 -->
          <div class="col-md-12 col-lg-12 col-xs-6 zeropadding nopadding small-box small-box-right bg-yellow">
           <div class="icon-dashboard-wrapper-small">
            <a href="<?php echo $coursereport ?>" class ="colorwhte"><i class="fa fa-bullhorn icon-dashboard-small"></i>
            </a></div>
             <div class="inner-small">
              <p><a href="<?php echo $coursereport ?>" class ="colorwhte">Announcements</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 3 -->
    </div>
  
      <div class="col-sm-6 margin-small-box-2-row zeropadding nopadding-left-small padrft6px">
    <!-- START : Dashboard box - 4 -->
          <div class="col-md-12 col-lg-12 col-xs-6 zeropadding nopadding small-box bg-purple">
           <div class="icon-dashboard-wrapper-small">
            <a href="<?php echo $wiki ?>" class ="colorwhte"><i class="fa fa-wordpress icon-dashboard-small"></i>
            </a></div>
             <div class="inner-small">
              <p><a href="<?php echo $wiki ?>" class ="colorwhte">WIKI</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 4 -->
    </div>
    <div class="col-sm-6 margin-small-box-2-row zeropadding nopadding-right-small padlft6px">
    <!-- START : Dashboard box - 5 -->
          <div class="col-md-12 col-lg-12 col-xs-6 zeropadding nopadding small-box small-box-right bg-dashboard-box3">
           <div class="icon-dashboard-wrapper-small">
            <a href="<?php echo $mycertificates ?>" class ="colorwhte"><i class="fa fa-trophy icon-dashboard-small"></i>
            </a></div>
             <div class="inner-small">
              <p><a href="<?php echo $mycertificates ?>" class ="colorwhte">My certificates</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 5 -->
    </div>
 </div>

    <div class="col-sm-4">
<!-- START : Dashboard box - 6 -->
          <div class="col-md-12 col-lg-12 col-xs-12 zeropadding nopadding small-box bg-aqua">
           <div class="box-arrow-right"><div class="box-count">70</div></div><!-- count -->
            <div class="icon-dashboard-wrapper col-sm-12 col-xs-3">
            <a href="<?php echo $forums ?>" class ="colorwhte"><i class="fa fa-comments-o icon-dashboard"></i>
            </a></div>
             <div class="inner col-sm-12 col-xs-9">
              <p><a href="<?php echo $forums ?>" class ="colorwhte">Discussion forums</a></p>
            </div>
            <span class="small-box-footer"></span>
          </div>
<!--/ END : Dashboard box - 6 -->
    </div>
  </div>
<!--/ END : row - 4 -->




 </div> <!-- /box-dashboard -->



    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php
  include_once("$CFG->dirroot/theme/defaultlms/layout/includes/footer.php");
  ?>

</div>

<?php echo $OUTPUT->standard_end_of_body_html()?>



</body>
</html>



