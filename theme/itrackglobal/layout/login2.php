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
    <!-- Google web fonts -->
    <?php require_once(dirname(__FILE__).'/includes/fonts.php');
    ?>
   <link rel="stylesheet" href="<?php echo $CFG->wwwroot.'/theme/defaultlms/style/logincs.css'?>"> 
</head>
<body <?php echo $OUTPUT->body_attributes(); ?>>
<?php echo $OUTPUT->standard_top_of_body_html(); ?>
<?php echo "<div style='display: none;'>".$OUTPUT->main_content()."</div>";  ?>
<section id="mainpage" class="slideshow">

<!-- START : Header -->
<div class="container-fluid logo" id="header">
  <?php if (!$haslogo) { 
      echo '<img src="'.$CFG->wwwroot.'/theme/defaultlms/images/logo.png" class="img-responsive">';
      }else{
        echo '<img src="'.$PAGE->theme->setting_file_url('logo', 'logo').'" class="img-responsive">';
      }
      ?>

</div>
<!--/ END : Header -->


<!-- START : Content -->
<div class="container-fluid" id="content">

<div class="bg-main hidden-xs">
<img src="<?php echo $CFG->wwwroot.'/theme/defaultlms/images/bg.png'?>" class="img-responsive">
</div>

<div class="col-lg-5 col-md-5 hidden-sm hidden-xs" id="man-on-plane">
<img src="<?php echo $CFG->wwwroot.'/theme/defaultlms/images/man-on-plane.png'?>" class="img-responsive">
</div>

<!-- START : Login Section -->
<div class="col-lg-4 col-md-4 zeropadding-mob" id="section-login">

<div class="bg-main visible-xs">
<img src="<?php echo $CFG->wwwroot.'/theme/defaultlms/images/bg.png'?>">
</div>

<!-- START : Invalid Login -->
<div style="height:50px;"> <!-- added this div to make the box constant after invalid login 20oct -->
<div class="invalid-login"  id="invalid" style="visibility: hidden;">
<div id="alerticon"><span class="glyphicon glyphicon-exclamation-sign"></span></div>
<div id="errormsg">Invalid login. Please try again.</div>
</div>
</div>

<!--/ END : Invalid Login -->

<!-- START : Login Title -->
<div id="title-login">
<div class="txt-heading mrgbtn10">Explore the enhanced Learning Programs</div>
<div class="txt-main-heading mrgbtn10">Get <span class="txt-main-heading-uppercase">Skilldomised!</span></div>

<div class="txt-sub-heading" id="login-info">Please enter login information to continue.
<div class="bg-plane-login">
<img src="<?php echo $CFG->wwwroot.'/theme/defaultlms/images/plane-login.png'?>" class="img-responsive">
</div>
</div>

</div>
<!--/ END : Login Title -->

<!-- START : Login Form -->
<div id="form-login" class="col-xs-offset-3 col-md-offset-0 col-lg-offset-0">

<form  class = "bs-example1 bs-example-form1" role = "form"action="<?php echo $CFG->httpswwwroot; ?>/login/index.php" method="post" id="login1" >
<div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group form-login-wrapper">
<span class = " input-group-addon addon-username"><span class="fa fa-user icon-white-login"></span></span>
<input type = "text" class = "form-control form-login custwidth" name="username" placeholder = "Username">
</div>
<div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group form-login-wrapper">
<span class = "input-group-addon addon-password"><span class="fa fa-key icon-white-login"></span></span>
<input type = "password" class = "form-control form-login custwidth" name="password" placeholder = "Password">
</div>
<div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<span class=" rembr"><label class ="txt-remember1"><input type="checkbox"  name="rememberusername" value="1"> Remember username</label>
</span>
</div>

<div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12 form-login-wrapper zeropadding">
<button class="btn btn-default btn-primary btn-login pull-right"id="loginbtn1">LOGIN</button>
<span><a href="<?php echo new moodle_url("/login/forgot_password.php"); ?>" class="txt-forgot pull-right">Forgot Password?</a></span>
</div>
<div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12 form-login-wrapper" id="signup">
<!-- Mihir 15/10 -->
<?php
if (!empty($CFG->registerauth)) {
?>
<a href="<?php echo $CFG->wwwroot.'/login/signup.php';?>" class="txt-signup">Sign Up!</a>
<?php
}
?>
<div class="bg-man-on-plane visible-xs visible-sm">
<img src="<?php echo $CFG->wwwroot.'/theme/defaultlms/images/man-on-plane.png'?>" class="img-responsive">
</div>

<?php
if (!empty($CFG->registerauth)) {
?>
<div class="bg-signup-pencil  hidden-xs">
<img src="<?php echo $CFG->wwwroot.'/theme/defaultlms/images/signup-pencil.png'?>" class="img-responsive">
</div>
<?php
}
?>

<div class="bg-plane-bottom-right">
<img src="<?php echo $CFG->wwwroot.'/theme/defaultlms/images/plane-bottom-right.png'?>" class="img-responsive">
</div>
</div>

</form> 
</div>
<!--/ END : Login Form -->

</div>
<!--/ END : Login Section -->

</div>
<!--/ END : Content -->



<!-- START : Footer -->
<div class="container-fluid" id="footer">
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 visible-xs" id="web-mob">
<span style="">For more details visit: </span> <a style="text-decoration:underline;" href="http://www.skilldom.co.in">http://www.skilldom.co.in</a>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 copyright" style="">
 <?php
    $hasfootnote = (empty($PAGE->theme->settings->footnote)) ? false : $PAGE->theme->settings->footnote;
    if ($hasfootnote) {
      echo strip_tags($hasfootnote);
  }else{
    echo 'Copyright &copy; SKILLDOM Learning Solutions  Pvt. Ltd. | All Rights Reserved.';
  }
    ?>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 web hidden-xs">
<span style="">For more details visit: </span><a style="text-decoration:underline;" href="http://www.skilldom.co.in">http://www.skilldom.co.in</a>
</div>
</div>
<!--/ END : Footer -->


</section>
<?php //echo $OUTPUT->standard_end_of_body_html() ?>
<script>
$(function(){ 
    $("#invalid").css("visibility", "hidden");
    var e1 = $("#loginerrormessage").text();
    if(e1.length>0)
    {
        // $("#invalid").html(e1);
        // $("#invalid").show();
        $("#invalid").css("visibility", "visible");
       setTimeout(function() { $("#invalid").hide(); }, 3000)
    }
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

</body>
</html>
