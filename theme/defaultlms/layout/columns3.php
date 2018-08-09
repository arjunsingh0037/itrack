<?php

$standardlayout = (empty($PAGE->theme->settings->layout)) ? false : $PAGE->theme->settings->layout;
$haslogo = (!empty($PAGE->theme->settings->logo));
global $CFG, $USER;
//include_once("$CFG->dirroot/my/locallib.php");
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
  <?php require_once(dirname(__FILE__).'/includes/stylelinks.php');
  ?>

</head>
<body <?php echo $OUTPUT->body_attributes(); ?>>
  <?php echo $OUTPUT->standard_top_of_body_html(); ?>
  <?php //echo "<div style='display: none;'>".$OUTPUT->main_content()."</div>";  ?>
  <section id="container">
    <?php
    /*header start*/
    include_once("$CFG->dirroot/theme/defaultlms/layout/includes/customheader.php");
    /*header end*/

    /*sidebar start*/
    include_once("$CFG->dirroot/theme/defaultlms/layout/includes/sidemenu.php");
    /*sidebar end*/
    ?>
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">

        <!--mini statistics end-->
        <div class="row">
          <div class="col-md-12">
            <section class="panel">
              <?php 
                //echo $OUTPUT->main_content();
                echo $OUTPUT->course_content_header();
                echo $OUTPUT->main_content();
                echo $OUTPUT->course_content_footer();
                ?>
            </section>
            <div class="col-md-12">
            </div>
          </div>
        </section>
      </section>
      <!--main content end-->
      <!--right sidebar start-->
      <?php
      include_once("$CFG->dirroot/theme/defaultlms/layout/includes/sideblocks.php");
      ?>
      <!--right sidebar end-->
      <?php //require_once(dirname(__FILE__).'/includes/footer.php'); ?>
    </section>
    <!-- Placed js at the end of the document so the pages load faster -->
    <!--Core js-->
    <?php require_once(dirname(__FILE__).'/includes/jslinks.php');?>
    <!--script for this page-->
    <?php //echo $OUTPUT->standard_end_of_body_html()?>
  </body>
  </html>