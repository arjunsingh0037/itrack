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
$haslogo = (!empty($PAGE->theme->settings->logo));
$left = (!right_to_left());
$standardlayout = (empty($PAGE->theme->settings->layout)) ? false : $PAGE->theme->settings->layout;

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google web fonts -->
    <?php require_once(dirname(__FILE__).'/includes/fonts.php'); ?>
    <style type="text/css">
    #header {
    background: #fff;
    min-height: 72px;
}
.logo {
    padding-top: 10px;
}
    </style>
</head>

<body <?php echo $OUTPUT->body_attributes('hold-transition skin-blue sidebar-mini nopadding fixed'); ?>>

<?php echo $OUTPUT->standard_top_of_body_html(); ?>
<div class ="wrapper">
    <div class="container-fluid logo" id="header">
<?php if (!$haslogo) { 
      echo '<img src="'.$CFG->wwwroot.'/theme/defaultlms/images/logo.png" class="img-responsive">';
      }else{
        echo '<img src="'.$PAGE->theme->setting_file_url('logo', 'logo').'" class="img-responsive">';
      }
      ?>
</div>
    <?php 
    // require_once(dirname(__FILE__).'/includes/customheader.php');
    //require_once(dirname(__FILE__).'/includes/sidemenu.php');

    
        echo '<div class="content-wrapper1">
              <section class="content-header">
                <h1>
                  <small>'.$OUTPUT->navbar().'</small>
                </h1>
              </section>';
                echo $OUTPUT->course_content_header();
                echo $OUTPUT->main_content();
                echo $OUTPUT->course_content_footer();
            echo '</div>';

    ?> 
    <?php require_once(dirname(__FILE__).'/includes/footersignup.php'); ?>
    <?php echo $OUTPUT->standard_end_of_body_html()?>
</div>
</body>
</html>




