<?php 
$haslogo = (!empty($PAGE->theme->settings->logo));
?>
  <!-- Main Header -->
<header class="main-header">

<!-- this javascript is added by Siddharth on 18/10 for hide/show blocks 
	<script>
  $(document).ready(function(){
       $("div[role=main]").addClass('widthblock100');
       $("#block-region-side-post").hide();
       $(".sh").removeClass('fullwidth');
      $(".sh").click(function(){
         if($(".sh").hasClass('fullwidth')){
           $("div[role=main]").addClass('widthblock100');
           $("div[role=main]").removeClass('widthblock50');
           $("#block-region-side-post").hide();
           $(".sh").removeClass('fullwidth');
         }else{
           $("div[role=main]").addClass('widthblock50');
           $("div[role=main]").removeClass('widthblock100');
           $("div[role=main]").removeClass('hide');
           $(".sh").addClass('fullwidth');
           $("#block-region-side-post").show();
         }
        });
  });
  </script>
  -->
 
  <!-- // $(document).ready(function(){
  //     $(".sh").click(function(){

  //        if($(".sh").hasClass('fullwidth')){
  //          $("div[role=main]").addClass('width100');
  //          $("div[role=main]").removeClass('width50');
  //          $("#block-region-side-post").hide();
  //          $(".sh").removeClass('fullwidth');
  //        }else{
  //          $("div[role=main]").addClass('width50');
  //          $("div[role=main]").removeClass('width100');
  //          $("div[role=main]").removeClass('hide');
  //          $(".sh").addClass('fullwidth');
  //          $("#block-region-side-post").show();
  //        }
  //       });
  // }); -->

    <!-- Logo -->
    <a href="<?php echo $CFG->wwwroot.'/my' ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>SKILLDOM</b></span>
      <!-- logo for regular state and mobile devices -->

      <?php if (!$haslogo) { 
      echo '<span class="logo-lg"><img src="'.$CFG->wwwroot.'/theme/defaultlms/images/logo.png"></span>';
      }else{
        echo '<span class="logo-lg"><img src="'.$PAGE->theme->setting_file_url('logo', 'logo').'"></span>';
      }
      ?>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle visible-xs visible-sm" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

        <!-- Notifications Menu -->
      <?php 
       global $DB;
      $encourses = enrol_get_my_courses();
      $courseidarray = array();
      $courseidarray[] = 1; 
      $evcount = 0;
      foreach ($encourses as $key => $csvalue) {
        if($csvalue->visible == 1){
          $courseidarray[] = $key; 
        }
        
      }
       foreach ($courseidarray as  $value) {
        if($event = $DB->get_records('event',array('courseid'=> $value),'name,timestart')){
          foreach ($event as  $value1) {
            $evcount++;
          }
        }
       }
	   if(($PAGE->pagelayout =='coursetype') || ($PAGE->pagelayout =='mydashboard')) {
		   $showheading = 1;
	   } else {
		   $showheading = 0;
	   }
          if($showheading == 0){
			
			//echo '<li class ="btn sh fullwidth blockbts">Show/Hide Block</li>';//siddharth added this 17th oct
			// need to check for manager capability as well
			if (is_siteadmin()) {
				echo '<a href="#" class="hidden-xs" data-toggle=""><i class="sh fullwidth fa fa-gear fa-3"></i></a>';
        echo '<script>
  $(document).ready(function(){
       $("div[role=main]").addClass("widthblock100");
       $("#block-region-side-post").hide();
       $(".sh").removeClass("fullwidth");
      $(".sh").click(function(){
         if($(".sh").hasClass("fullwidth")){
           $("div[role=main]").addClass("widthblock100");
           $("div[role=main]").removeClass("widthblock50");
           $("#block-region-side-post").hide();
           $(".sh").removeClass("fullwidth");
         }else{
           $("div[role=main]").addClass("widthblock50");
           $("div[role=main]").removeClass("widthblock100");
           $("div[role=main]").removeClass("hide");
           $(".sh").addClass("fullwidth");
           $("#block-region-side-post").show();
         }
        });
  });
  </script>';
			}
            echo '<li class ="turnedtbtn1">'. $OUTPUT->page_heading_button().'</li>';
          }
          ?>
          <li class="dropdown notifications-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning"><?php echo $evcount ?></span>
            </a>
            <ul class="dropdown-menu topmenuborder">
              <?php 
             
              foreach ($courseidarray as  $value) {
                if($event = $DB->get_records('event',array('courseid'=> $value),'name,timestart')){
                  foreach ($event as  $value1) {
                    echo '<li class ="header eventclass"><a href="'.$CFG->wwwroot.'/calendar/view.php?view=day&time='.$value1->timestart.'">'.$value1->name.'</a>
                    <p>Date : '.date('D M j G:i:s ',$value1->timestart).' </p>

                    </li>';
                  }
                }
               }
              
                  // require_once($CFG->libdir.'/blocklib.php');
                  //                   $instance = new stdClass;
                  //                   $instance->id = 11;
                  //                   $object = block_instance('calendar_upcoming',$instance);
                  //                   echo $object->get_content()->text;
                                    ?>
              <li>
                <!-- Inner Menu: contains the notificationsYou have 10 notifications -->
<!--                 <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>

                </ul> -->
              </li>
              <li class="footer eventfoot"><a href="<?php echo $CFG->wwwroot.'/moodle32/calendar/view.php?view=month'?>">View all</a></li>
            </ul>
          </li>
          
          <!-- Tasks Menu -->
          <li class="dropdown tasks-menu">
            <!-- Menu Toggle Button -->
            <a href="<?php echo $CFG->wwwroot . '/user/profile.php'?>" class="dropdown-toggle" >
              <i class="fa fa-pencil" aria-hidden="true"></i>
              <!-- <span class="label label-danger">9</span> -->
            </a>
<!--             <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>

                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-briefcase  text-aqua" aria-hidden="true"></i> Tasks are listed here
                    </a>
                  </li>

                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul> -->
          </li>



          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <?php 
                require_once($CFG->dirroot.'/message/lib.php');
                $msgcount = message_count_unread_messages();
                echo '<span class="label label-success">'.$msgcount.'</span>';
              ?>
            </a>
            <ul class="dropdown-menu topmenuborder">
              <li class="header">You have <?php echo $msgcount?> messages</li>
              <li>
                <!-- inner menu: contains the messages -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <!-- <div class="pull-left">
  
                        <img src="images/user.png" class="img-circle" alt="User Image">
                      </div> -->
                      <!-- Message title and timestamp -->
                      <h4>
                        <?php
                        $msg = message_get_messages($USER->id);
                        if(!empty($msg)){
                           foreach ($msg as  $value) {
                             echo $value->fullmessage;
                           }
                        }
                          //print_object($msg);
                         
                        ?>
                       
                      </h4>
                      <!-- The message -->
                <!--       <p>Message description goes here</p> -->
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
                <!-- /.menu -->
              </li>
              <li class="footer"><a href="<?php echo $CFG->wwwroot.'/message/index.php'?>">See All Messages</a></li>
            </ul>
          </li>
          <!-- /.messages-menu -->

            <?php  if (isloggedin() or !isguestuser()) { 
          echo '<li class="dropdown user user-menu">
            <!-- Menu Toggle Button
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> -->
              
			  <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <div class="hidden-xs pull-left usermname">'.'Welcome!'.'</br>'.$USER->firstname.' '.$USER->lastname.'</div>
			  <!-- The user image in the navbar-->
              <span class="hidden-xs"><img src="'.$CFG->wwwroot.'/user/pix.php?file=/'.$USER->id.'/f1.jpg" title="'.$USER->firstname.' '.$USER->lastname.'" alt="'.$USER->firstname.' '.$USER->lastname.'" " class="user-image" alt=""></span>
            <!--</a> -->
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li class="logout">
            <a href="'.$CFG->wwwroot.'/login/logout.php?sesskey='.sesskey().'" > <i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
          </li>';

           } else {

            echo '<li class="logout">
               <a href="'.$CFG->wwwroot.'/login/index.php" data-toggle="control-sidebar"><i class="fa fa-sign-out" aria-hidden="true"></i>Login</a>
              </li>';
           }
           ?>
        </ul>
      </div>
    </nav>
  </header>