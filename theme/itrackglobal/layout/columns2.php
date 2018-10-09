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
<!DOCTYPE html>
<html <?php echo $OUTPUT->htmlattributes(); ?> lang="en">
<head>
<title><?php echo $OUTPUT->page_title(); ?></title>
<link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<!-- Favicon icon -->

<title>iTrack</title>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>

<?php
echo '<link rel="icon" type="image/png" sizes="16x16" href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/favicon.png">

<!-- Bootstrap Core CSS -->
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- chartist CSS -->
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/css-chart/css-chart.css" rel="stylesheet">

<!--This page css - Morris CSS -->
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/morrisjs/morris.css" rel="stylesheet">

<!-- Custom CSS -->
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/css/style.css" rel="stylesheet">

<!-- You can change the theme colors from here -->
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/css/colors/blue.css" id="theme" rel="stylesheet">';
?>
<?php echo $OUTPUT->standard_head_html(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
  <body 
    <?php echo $OUTPUT->body_attributes(); ?> class="fix-header fix-sidebar card-no-border">
    <?php echo $OUTPUT->standard_top_of_body_html(); ?>
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <?php
        /*header start*/
        include_once("$CFG->dirroot/theme/itrackglobal/layout/includes/customheader.php");
        include_once("$CFG->dirroot/theme/itrackglobal/layout/includes/sidemenu.php");
        ?>
        
        
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid" style="margin-top:-23px">
            <div class="row page-titles">
                  <div class="col-md-5 col-8 align-self-center">
                      <h3 class="text-themecolor">Intelliboard Reports</h3>
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                          <li class="breadcrumb-item active">Intelliboard</li>
                      </ol>
                  </div>
                  <div class="col-md-7 col-4 align-self-center">
                      <div class="d-flex m-t-10 justify-content-end">
                          <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                              <div class="chart-text m-r-10">
                                  <h6 class="m-b-0"><small>THIS MONTH</small></h6>
                                  <h4 class="m-t-0 text-info">58,356</h4></div>
                              <div class="spark-chart">
                                  <div id="monthchart"></div>
                              </div>
                          </div>
                          <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                              <div class="chart-text m-r-10">
                                  <h6 class="m-b-0"><small>LAST MONTH</small></h6>
                                  <h4 class="m-t-0 text-primary">48,356</h4></div>
                              <div class="spark-chart">
                                  <div id="lastmonthchart"></div>
                              </div>
                          </div>
                          <div class="">
                              <button class="right-side-toggle waves-effect waves-light btn-success btn btn-circle btn-sm pull-right m-l-10"><i class="ti-settings text-white"></i></button>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row col-md-12">  
                <section class="panel">
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-body">
                          <?php 
                            echo $OUTPUT->main_content();
                          ?> 
                        </div>
                      </div>
                    </div>
                </section>
              </div>
    <?php
      echo '
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
      <!-- This page plugins -->
      <!-- ============================================================== -->
      <!-- chartist chart -->
      <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/chartist-js/dist/chartist.min.js"></script>
      <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
      <!--c3 JavaScript -->
      <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/d3/d3.min.js"></script>
      <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/c3-master/c3.min.js"></script>
      <!-- Chart JS -->
      <script src="'.$CFG->wwwroot.'/theme/itrackglobal/js/dashboard1.js"></script>
      <!-- ============================================================== -->
      <!-- Style switcher -->
      <!-- ============================================================== -->
      <script src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>';
    ?>
    </div></div></div>
  </body>
</html>
