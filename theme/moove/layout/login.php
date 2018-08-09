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
 * The one column layout.
 *
 * @package    theme_skilldom
 * @copyright  2016 LmsOfIndia
 * @author     HaraPrasad Rout
 * @license    LmsOfIndia {@web http://www.lmsofindia.com}
 */

$standardlayout = (empty($PAGE->theme->settings->layout)) ? false : $PAGE->theme->settings->layout;
$haslogo = (!empty($PAGE->theme->settings->logo));
global $CFG, $USER;
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
  <link rel="stylesheet" href="<?php echo $CFG->wwwroot.'/theme/moove/layout/includes/css/bootstrap-reset.css'?>">
  <link rel="stylesheet" href="<?php echo $CFG->wwwroot.'/theme/moove/layout/includes/font-awesome/css/font-awesome.css'?>">
  <link rel="stylesheet" href="<?php echo $CFG->wwwroot.'/theme/moove/layout/includes/css/bootstrap-reset.css'?>">
  <link rel="stylesheet" href="<?php echo $CFG->wwwroot.'/theme/moove/layout/includes/css/style.css'?>">
  <link rel="stylesheet" href="<?php echo $CFG->wwwroot.'/theme/moove/layout/includes/css/style-responsive.css'?>">

</head>
<body class="lock-screen">
  <?php echo $OUTPUT->standard_top_of_body_html(); 
  echo "<div style='display:none'>".$OUTPUT->main_content()."</div>";  ?>
  <div class="lock-wrapper">
   <div class="lock-box text-center">
    <?php
    if (isloggedin() and !isguestuser()) {
        //echo $OUTPUT->box_start();
        $logout = new single_button(new moodle_url('/login/logout.php', array('sesskey'=>sesskey(),'loginpage'=>1)), get_string('logout'), 'post');
        $continue = new single_button(new moodle_url('/'), get_string('cancel'), 'get');
        echo $OUTPUT->confirm(get_string('alreadyloggedin', 'error', fullname($USER)), $logout, $continue);
        //echo $OUTPUT->box_end();
    } else {
    ?>    
    <!-- START : Login Form -->

    <form role="form" class="form-inline" action="<?php echo $CFG->httpswwwroot; ?>/login/index.php" method="post" id="login1">
      <div class="lock-name">
        <div class="form-group">
          <input type="text" name="username" placeholder="Username" class="form-control lock-username">
        </div>
      </div>
      <img class="login-avatar" src="<?php echo $CFG->wwwroot.'/theme/moove/layout/includes/images/lock_thumb.png'?>" alt="lock avatar"/>
      <div class="lock-pwd">
        <div class="form-group">
          <input type="password" placeholder="Password" id="exampleInputPassword2" name="password" class="form-control lock-input">
          <button class="btn btn-lock" type="submit">
            <i class="fa fa-arrow-right"></i>
          </button>
        </div>
      </div>
      <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
        <span class=" rembr1">
          <label class ="txt-remember1">Remember Me <input type="checkbox" id="squared4"name="rememberusername" value="1"></label>
        </span>
        <span class="forgot"><a href="<?php echo new moodle_url("/login/forgot_password.php"); ?>" class="txt-forgot pull-right">Forgot Password?</a></span>
      </div>

      <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12 form-login-wrapper zeropadding">
        
      <?php
      if (!empty($CFG->registerauth)) {
        ?>
        <a href="<?php echo $CFG->wwwroot.'/login/signup.php';?>" class="txt-signup">Sign Up!</a>
        <?php
      }
      ?>
    </form> 
    <?php } ?>
    <!--/ END : Login Form -->

  </div>
</div>
<?php //echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
