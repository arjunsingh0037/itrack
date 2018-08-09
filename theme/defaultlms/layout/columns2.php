<?php

$left = (!right_to_left());
$standardlayout = (empty($PAGE->theme->settings->layout)) ? false : $PAGE->theme->settings->layout;

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
  <title><?php echo $OUTPUT->page_title(); ?></title>
  <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
  <?php echo $OUTPUT->standard_head_html(); ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require_once(dirname(__FILE__).'/includes/stylelinks.php');
  require_once(dirname(__FILE__).'/includes/fonts.php'); ?>
</head>

<body <?php echo $OUTPUT->body_attributes('hold-transition skin-blue sidebar-mini nopadding fixed'); ?>>

  <?php echo $OUTPUT->standard_top_of_body_html(); ?>
  <section id="container">
    <?php include_once("$CFG->dirroot/theme/defaultlms/layout/includes/customheader.php"); 
    require_once(dirname(__FILE__).'/includes/sidemenu.php');?>
    <section id="main-content">
      <section class ="wrapper">
        <div class="row">
          <div class="col-md-12">
            <section class="panel">
              <?php 
              echo $OUTPUT->main_content();
              ?> 
            </section></div></div>

          </section>
          <?php include_once("$CFG->dirroot/theme/defaultlms/layout/includes/sideblocks.php"); ?>
          <?php require_once(dirname(__FILE__).'/includes/jslinks_admin.php');?>
        </section>
      </section>
      <?php //echo $OUTPUT->standard_end_of_body_html()?>
    </body>
    </html>
