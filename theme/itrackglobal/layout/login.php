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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <?php echo '<link rel="icon" type="image/png" sizes="16x16" href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/favicon.png">';
          echo '<title>iTrack Login</title>
              <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
            
              <link href="'.$CFG->wwwroot.'/theme/itrackglobal/css/style.css" rel="stylesheet">
            
              <link href="'.$CFG->wwwroot.'/theme/itrackglobal/css/colors/blue.css" id="theme" rel="stylesheet">';
    ?>
  <title><?php echo $OUTPUT->page_title(); ?></title>
  <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
  <?php echo $OUTPUT->standard_head_html(); ?>
</head>
<body>
    <?php 
        echo $OUTPUT->standard_top_of_body_html(); 
        echo "<div style='display:none'>".$OUTPUT->main_content()."</div>";  
    ?>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <?php
    echo '<section id="wrapper" class="login-register login-sidebar"  style="height: -webkit-fill-available;background-image:url('.$CFG->wwwroot.'/theme/itrackglobal/assets/images/background/login-register.jpg);" >';
    ?>
    <div class="advert-login">
        <div id="block1">
            <div id="title">LMS<span class="reinvent">REINVENTED</span></div>            
            <div id="subtitle"><i class="fa fa-university" aria-hidden="true"></i> COLLEGE | <i class="fa fa-industry" aria-hidden="true"></i> CORPORATE | <i class="fa fa-shopping-bag" aria-hidden="true"></i> MARKETPLACE</div>
            <div class="sidebar-clients">
                <div class="sidebar-clients-label">Trusted by:</div>
                <?php echo '<img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/background/brands_white_transparent.png" alt="clients">';?>
            </div>
        </div>
    </div>
  <div class="login-box card">
    <div class="card-body">
      <?php
      if (isloggedin() and !isguestuser()) {
          //echo $OUTPUT->box_start();
          $logout = new single_button(new moodle_url('/login/logout.php', array('sesskey'=>sesskey(),'loginpage'=>1)), get_string('logout'), 'post');
          $continue = new single_button(new moodle_url('/'), get_string('cancel'), 'get');
          echo $OUTPUT->confirm(get_string('alreadyloggedin', 'error', fullname($USER)), $logout, $continue);
          //echo $OUTPUT->box_end();
      } else {
      ?>  
      <form class="form-horizontal form-material" role = "form" action="<?php echo $CFG->httpswwwroot; ?>/login/index.php" method="post" id="login1">
        <a href="javascript:void(0)" class="text-center db" class="loginlogoicon">
          <?php echo '<img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/logo-icon.png" alt="Home" /><br/>';
          ?>
        </a>  
        
        <div class="form-group m-t-40">
          <div class="col-xs-12">
            <input class="form-control lock-username" type="text" name="username" required="" placeholder="Username">
          </div>
        </div>
        <div class="form-group">
          <div class="col-xs-12">
            <input class="form-control lock-input" type="password" name="password" id="exampleInputPassword2" required="" placeholder="Password">
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            <div class="checkbox checkbox-primary pull-left p-t-0">
              <input id="checkbox-signup" type="checkbox" name="rememberusername" value="1">
              <label for="checkbox-signup"> Remember me </label>
            </div>
            <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a> </div>
        </div>
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" id="loginbtn1">Log In</button>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
            <div class="social"><a href="javascript:void(0)" class="btn  btn-facebook" data-toggle="tooltip"  title="Login with Facebook"> <i aria-hidden="true" class="fa fa-facebook"></i> </a> <a href="javascript:void(0)" class="btn btn-googleplus" data-toggle="tooltip"  title="Login with Google"> <i aria-hidden="true" class="fa fa-google-plus"></i> </a> </div>
          </div>
        </div>
        <div class="form-group m-b-0">
          <div class="col-sm-12 text-center">
            <p>Don't have an account? <a href="<?php echo $CFG->wwwroot.'/login/signup.php';?>" class="text-primary m-l-5"><b>Sign Up</b></a></p>
          </div>
        </div>
      </form>
      <form class="form-horizontal" id="recoverform" action="index.html">
        <div class="form-group ">
          <div class="col-xs-12">
            <h3>Recover Password</h3>
            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
          </div>
        </div>
        <div class="form-group ">
          <div class="col-xs-12">
            <input class="form-control" type="text" required="" placeholder="Email">
          </div>
        </div>
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
          </div>
        </div>
      </form>
      <?php
        }
      ?>
    </div>
  </div>
</section>
<script>
$(function(){ 
    $("#loginbtn").click(function(){
        var uname = $("#login1 input[name=username]").val();
        $("#login input[name=username]").val(uname);
        
        var pwd = $("#login1 input[name=password]").val();
        $("#login input[name=password]").val(pwd);

         // $('#login').attr('action',orgid).submit();
        $("#login").submit();
    });
});
</script>
<?php
echo '<script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!--Custom JavaScript -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/js/custom.min.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>';
  ?>
</body>
</html>
