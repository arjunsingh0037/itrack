<?php
defined('MOODLE_INTERNAL') || die();
include_once($CFG->dirroot."/theme/itrackglobal/lib.php"); 
$standardlayout = (empty($PAGE->theme->settings->layout)) ? false : $PAGE->theme->settings->layout;
$haslogo = (!empty($PAGE->theme->settings->logo));
global $CFG, $USER, $DB;
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
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/js/twitter.js"></script>
    <script src="'.$CFG->wwwroot.'/theme/itrackglobal/js/custom.js"></script>

<!-- You can change the theme colors from here -->
    <link href="'.$CFG->wwwroot.'/theme/itrackglobal/css/colors/blue.css" id="theme" rel="stylesheet">
    ';
?>
<?php echo $OUTPUT->standard_head_html(); 
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body 
<?php echo $OUTPUT->body_attributes(); ?> class="fix-header fix-sidebar card-no-border">
<?php echo $OUTPUT->standard_top_of_body_html(); ?>

<?php echo "<div style='display: none;'>".$OUTPUT->main_content()."</div>";  ?>



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
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor">Dashboard</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
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
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round round-lg align-self-center round-info"><i class="ti-wallet"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0 font-light">249</h3>
                                        <h5 class="text-muted m-b-0">My Courses</h5></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round round-lg align-self-center round-warning"><i class="mdi mdi-cellphone-link"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0 font-lgiht">376</h3>
                                        <h5 class="text-muted m-b-0">My eLabs</h5></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round round-lg align-self-center round-primary"><i class="mdi mdi-cart-outline"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0 font-lgiht">795</h3>
                                        <h5 class="text-muted m-b-0">My eProjects</h5></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round round-lg align-self-center round-danger"><i class="mdi mdi-bullseye"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0 font-lgiht">87</h3>
                                        <h5 class="text-muted m-b-0">My Batches</h5></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <div class="card card earning-widget">
                            <div class="card-header">
                                <div class="card-actions">
                                    <a class="" data-action="collapse"><i class="ti-minus"></i></a>
                                    <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
                                    <a class="btn-close" data-action="close"><i class="ti-close"></i></a>
                                </div>
                                <h4 class="card-title m-b-0">Course Progress</h4>
                            </div>
                            <div class="card-body">  
                                <?php
                                $coursesprogress = get_my_courses_progress();
                                $color_arr = array('info','success','danger','warning');
                                if($coursesprogress){                                    
                                    foreach ($coursesprogress as $crsid => $crs_stat) {
                                        shuffle($color_arr);                                        
                                        foreach ($color_arr as $ck => $cv) {
                                            $colorclass = $cv;
                                        }
                                        $crs = $DB->get_record('course',array('id'=>$crsid));
                                        echo '<div class="row">
                                                    <div class="col-12">
                                                        <h3>'.$crs_stat["progress"].'%</h3>
                                                        <h6 class="card-subtitle">'.ucwords($crs->fullname).'</h6></div>
                                                    <div class="col-12">
                                                        <div class="progress">
                                                            <div class="progress-bar bg-'.$colorclass.'" role="progressbar" style="width: '.$crs_stat["progress"].'%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">My Enrolled Courses</h3>
                                <h6 class="card-subtitle">Type Based Course Enrollment</h6>
                                <div id="visitor" style="height:260px; width:100%;"></div>
                            </div>
                            <div>
                                <hr class="m-t-0 m-b-0">
                            </div>
                            <div class="card-body text-center ">
                                <ul class="list-inline m-b-0">
                                    <li>
                                        <h6 class="text-muted text-info"><i class="fa fa-circle font-10 m-r-10 "></i>Acad</h6> </li>
                                    <li>
                                        <h6 class="text-muted  text-primary"><i class="fa fa-circle font-10 m-r-10"></i>Subs</h6> </li>
                                    <li>
                                        <h6 class="text-muted  text-success"><i class="fa fa-circle font-10 m-r-10"></i>Industry</h6> </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <!-- Column -->
                        <div class="card earning-widget">
                            <div class="card-header">
                                <div class="card-actions">
                                    <a class="" data-action="collapse"><i class="ti-minus"></i></a>
                                    <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
                                    <a class="btn-close" data-action="close"><i class="ti-close"></i></a>
                                </div>
                                <h4 class="card-title m-b-0">Leaderboard</h4>
                            </div>
                            <div class="card-body b-t collapse show">
                                <?php
                                    $top_scorers = $DB->get_records_sql("SELECT userid,lvl,xp from {block_xp} order by xp desc LIMIT 0,5");
                                ?>
                                <table class="table v-middle no-border">
                                    <tbody>
                                    <?php 
                                        $image_arr = array(1,2,3,4,5);
                                        $color_arr = array('label label-light-success','label label-light-primary','label label-light-warning','label label-light-danger','label label-light-info');
                                        foreach ($top_scorers as $userid => $scores) {
                                            $user_arr = $DB->get_record('user',array('id'=>$userid),'id,firstname');
                                            /*<img src="'.$CFG->wwwroot.'/user/pix.php?file=/'.$user_arr->id.'/f1.jpg" alt="user" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$user_arr->firstname.'" width="50" class="img-circle">*/
                                            shuffle($image_arr);
                                            shuffle($color_arr);
                                            foreach ($image_arr as $ik => $iv) {
                                                $srcid = $iv;
                                            }
                                            foreach ($color_arr as $ck => $cv) {
                                                $colorclass = $cv;
                                            }
                                            echo '<tr>                                                    
                                                    <td>
                                                        <img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/'.$srcid.'.jpg" width="50" class="img-circle" alt="logo">
                                                    </td>
                                                    <td style="padding:0px">'.$user_arr->firstname.'</td>
                                                    <td align="right">
                                                        <span class="showpoints '.$colorclass.'">'.$scores->xp.' Points</span>
                                                    </td>
                                                    <td align="right"><span style="height: 27px;line-height: 1.85;" class="label label-rounded bg-danger" data-toggle="tooltip_custom" title="level">'.$scores->lvl.' </span></td>
                                                  </tr>';
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Column -->
                    </div>
                    
                </div>    
                <!-- Row -->
                <!--arjun1 content-->
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <select class="custom-select pull-right">
                                    <option selected="">January</option>
                                    <option value="1">February</option>
                                    <option value="2">March</option>
                                    <option value="3">April</option>
                                </select>
                                <h4 class="card-title">Projects of the Month</h4>
                                <div class="table-responsive m-t-20">
                                    <table class="table stylish-table">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Assigned</th>
                                                <th>Name</th>
                                                <th>Priority</th>
                                                <th>Budget</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="width:50px;"><span class="round">S</span></td>
                                                <td>
                                                    <h6>Sunil Joshi</h6><small class="text-muted">Web Designer</small></td>
                                                <td>Elite Admin</td>
                                                <td><span class="label label-success">Low</span></td>
                                                <td>$3.9K</td>
                                            </tr>
                                            <tr class="active">
                                                <td><span class="round"><img src="../assets/images/users/2.jpg" alt="user" width="50"></span></td>
                                                <td>
                                                    <h6>Andrew</h6><small class="text-muted">Project Manager</small></td>
                                                <td>Real Homes</td>
                                                <td><span class="label label-info">Medium</span></td>
                                                <td>$23.9K</td>
                                            </tr>
                                            <tr>
                                                <td><span class="round round-success">B</span></td>
                                                <td>
                                                    <h6>Bhavesh patel</h6><small class="text-muted">Developer</small></td>
                                                <td>MedicalPro Theme</td>
                                                <td><span class="label label-primary">High</span></td>
                                                <td>$12.9K</td>
                                            </tr>
                                            <tr>
                                                <td><span class="round round-primary">N</span></td>
                                                <td>
                                                    <h6>Nirav Joshi</h6><small class="text-muted">Frontend Eng</small></td>
                                                <td>Elite Admin</td>
                                                <td><span class="label label-danger">Low</span></td>
                                                <td>$10.9K</td>
                                            </tr>
                                            <tr>
                                                <td><span class="round round-warning">M</span></td>
                                                <td>
                                                    <h6>Micheal Doe</h6><small class="text-muted">Content Writer</small></td>
                                                <td>Helping Hands</td>
                                                <td><span class="label label-warning">High</span></td>
                                                <td>$12.9K</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <!-- Column -->
                        <div class="card"> 
                            <div class="resp-container">
                                <iframe class="resp-iframe" src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Ftransneuron&tabs=timeline&width=340&height=500&small_header=true&adapt_container_width=false&hide_cover=false&show_facepile=true&appId=2301328596820009" width="340" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
                            </div>
                            <div>
                                <!-- <script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                                <script type="IN/CompanyProfile" data-id="3855850" data-format="inline" data-related="false"></script>     -->                           
                            </div>
                            <div class="card-body little-profile text-center">
                                
                                <a target="_new" href="https://www.facebook.com/transneuron/?ref=nf&hc_ref=ARQ18KovCWjlbhKCi4SWrcmwWnzXNhznAXMZQY4U3Cp2izZMvd6_Sp4szi9mWpeqW_o" class="m-t-10 waves-effect waves-dark btn btn-primary btn-md btn-rounded">Follow</a>
                                <div class="row text-center m-t-20">
                                    <div class="col-lg-4 col-md-4 m-t-20">
                                        <h3 class="m-b-0 font-light">1099</h3><small>Articles</small></div>
                                    <div class="col-lg-4 col-md-4 m-t-20">
                                        <h3 class="m-b-0 font-light">23,469</h3><small>Followers</small></div>
                                    <div class="col-lg-4 col-md-4 m-t-20">
                                        <h3 class="m-b-0 font-light">6035</h3><small>Following</small></div>
                                    <div class="col-md-12 m-b-10"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                    </div>
                </div>
                <!-- Row -->
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body" style="padding-bottom: 0px">
                                <h4 class="card-title">Recent Tweets</h4>
                                <h6 class="card-subtitle">Follow us and get the latest updates</h6> </div>
                            <!-- ============================================================== -->
                            <!-- Comment widgets -->
                            <!-- ============================================================== -->
                            <div class="comment-widgets">
                                <!-- Comment Row -->
                                <div id="example1" class="d-flex flex-row comment-row">
                                    
                                </div> 
                                <div id="example2" class="d-flex flex-row comment-row">
                                    
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <button class="pull-right btn btn-sm btn-rounded btn-success" data-toggle="modal" data-target="#myModal">Add Task</button>
                                <h4 class="card-title">To Do list</h4>
                                <h6 class="card-subtitle">List of your next task to complete</h6>
                                <!-- ============================================================== -->
                                <!-- To do list widgets -->
                                <!-- ============================================================== -->
                                <div class="to-do-widget m-t-20">
                                    <!-- .modal for add task -->
                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Add Task</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form>
                                                        <div class="form-group">
                                                            <label>Task name</label>
                                                            <input type="text" id="todotext" class="form-control" placeholder="Enter Task Name" required> </div>
                                                        <div class="form-group">
                                                            <label>Assign to</label>
                                                            <?php
                                                                $user_arr = $DB->get_records('group_to_student',array());
                                                                foreach ($user_arr as $uk => $uv) {
                                                                    $grpups[$uv->groupid][] = $uv->studentid;
                                                                }
                                                                foreach ($grpups as $g1k => $g1v) {
                                                                    foreach ($g1v as $g2k => $g2v) {                      
                                                                        $username = $DB->get_record('user',array('id'=>$g2v),'id,firstname,lastname');
                                                                        $userss[$g2v] = $username->firstname.' '.$username->lastname;
                                                                    }
                                                                }
                                                            ?>
                                                            <select id="selecteduser" class="custom-select form-control pull-right">
                                                                <?php
                                                                $options = '';
                                                                foreach ($userss as $gk => $gv) {
                                                                    if($gk == $USER->id){
                                                                        $options .= '<option value="'.$gk.'" selected="">'.$gv.'</option>';
                                                                    }else{
                                                                        $options .= '<option value="'.$gk.'" ="">'.$gv.'</option>';
                                                                    }
                                                                }
                                                                echo $options;
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="addTask()">Submit</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->
                                    <ul class="list-task todo-list list-group m-b-0" data-role="tasklist" id="tasklistst_users">
                                        <?php
                                            global $DB,$USER;
                                            $tasklist_arr = $DB->get_records('block_todo',array('usermodified'=>$USER->id),'id,timecreated,todotext,done');
                                            foreach ($tasklist_arr as $tk => $tv) {
                                                echo '<li class="list-group-item" data-role="task" id="taskli'.$tk.'">
                                                            <div class="checkbox checkbox-info">
                                                                <input type="checkbox" id="inputSchedule'.$tk.'" name="inputCheckboxesSchedule'.$tk.'" onclick="toggleCheckbox(this)" value="'.$tk.'">
                                                                <label for="inputSchedule'.$tk.'" class=""> <span>'.$tv->todotext.'</span> </label>
                                                                <button data-control="delete" type="button" class="close" aria-label="Delete" id="'.$tk.'" onclick="deleteTask(this.id)">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <ul class="assignedto">
                                                                <li><img src="'.$CFG->wwwroot.'/user/pix.php?file=/'.$USER->id.'/f1.jpg" alt="user" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$USER->firstname.'"></li>';
                                                                if($DB->record_exists('todo_assigneee',array('taskid'=>$tk))){
                                                                    $other_user = $DB->get_record('todo_assigneee',array('taskid'=>$tk),'id,assignedto');
                                                                    $ou = $DB->get_record('user',array('id'=>$other_user->assignedto),'id,firstname');
                                                                    echo '<li><img src="'.$CFG->wwwroot.'/user/pix.php?file=/'.$other_user->assignedto.'/f1.jpg" alt="user" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$ou->firstname.'"></li>';
                                                                }
                                                            echo '</ul>
                                                        </li>';
                                            }
                                        ?>                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-theme="red" class="red-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme working">4</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme ">12</a></li>
                            </ul>
                            <?php echo '<ul class="m-t-20 chatonline">
                                <li><b>Chat option</b></li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                                </li>
                            </ul>';?>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer"> © 2017 Material Pro Admin by wrappixel.com </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
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
</body>

</html>