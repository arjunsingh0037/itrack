<?php
include 'menulink.php';
include 'menuconfig.php';
include 'dashboardlib.php';
$liclass = '';
$context = context_user::instance($USER->id);
echo '<script src="'.$CFG->wwwroot.'/theme/defaultlms/javascript/Chart.js"></script>';
?>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel (optional) -->
        <?php
        $csinfo = my_courses_status();
        //print_object(count($csinfo['inprogress']));
        $courses1 = enrol_get_my_courses();
        //print_object(count($courses1));
        if(count($courses1) > 0){
          $cousecomplte =  count($csinfo['complete']) / count($courses1)*100;
          if(is_float($cousecomplte)){
            $cousecomplte = round($cousecomplte, 2);
          }
          $coursepgrs =  count($csinfo['inprogress']) / count($courses1)*100;
          if(is_float($coursepgrs)){
            $coursepgrs = round($coursepgrs, 2);
          }
          $coursenotstrated =  count($csinfo['notstarted']) / count($courses1)*100;
          if(is_float($coursenotstrated)){
            $coursenotstrated = round($coursenotstrated, 2);
          }
        }
        ?>
<!-- added user-panel course count (id=>user-panel) -->
        <ul class="sidebar-menu">
		<?php
			$cmyurl = $currenturl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$urltocheck = $CFG->wwwroot.'/my/';
			if ($cmyurl == $urltocheck) {
		?>
            <li class="treeview active">
			<?php } else {	?>
			<li class="treeview">
			<?php	}	?>
			
				<a href="<?php echo $home; ?>"><i class="fa fa-home"></i>Dashboard
                </a>
			</li>
            <!-- Optionally, you can add icons to the links -->

        <?php
		$currenturl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
 		foreach($menus as $menu) {
			$flag = '';
			foreach($menu['flag'] as $xmenu) {
				if($CFG->wwwroot.$xmenu['url'] == $currenturl ) {
					$flag = 'active';
				}

			}	
			if (has_capability('local/skillaccess:'.$menu['key'], $context)) {
				echo '<li class="treeview '.$flag.'">'.
                    	'<a href="'.$menu['url'].'"><i class="fa '.$menu['fa'].'"></i> <span>'.$menu['title'].'</span>'.
                        '<span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>'; 
	            if(count($menu['flag']) > 0) {
					echo '<ul class="treeview-menu">';
					foreach($menu['flag'] as $submenu) {
						$flag2 = ($currenturl == $CFG->wwwroot.$submenu['url']) ? 'active' : '';
						if (has_capability('local/skillaccess:'.$submenu['key'], $context)) {
                        	echo '<li class="'.$flag2.'"><a href="'.$CFG->wwwroot.$submenu['url'].'">'.$submenu['title'].'</a></li>';
               			}	 
					}
					echo  '</ul>';
				}    
			} 
		}
		
		// menu start
	?>	
	
        </ul>

        <div class="userpanel" style="min-height:50px">
        <div class="col-md-12">
        
        <ul class="chart-legend clearfix">
          <li></li>
        </ul>
        </div>
         </div>

    </section>
    <!-- /.sidebar -->
</aside>

<script type="text/javascript">
$(".treeview-menu li ").on('click', function(e){
    $(".treeview .active").removeClass('active');
    $(this).parent().addClass('active');
    $(this).addClass('active');
   // e.preventDefault();
});
</script>

<script type="text/javascript">
  var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
  var pieChart = new Chart(pieChartCanvas);
  var PieData = [
    {
      value: <?php echo $coursenotstrated ?>,
      color: "#f56954",
      highlight: "#f56954",
      label: "Not Started"
    },
    {
      value:<?php echo $cousecomplte ?>,
      color: "#00a65a",
      highlight: "#00a65a",
      label: "Completed"
    },
    {
      value: <?php echo $coursepgrs ?>,
      color: "#f39c12",
      highlight: "#f39c12",
      label: "In Progress"
    }
  ];
  var pieOptions = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke: true,
    //String - The colour of each segment stroke
    segmentStrokeColor: "#fff",
    //Number - The width of each segment stroke
    segmentStrokeWidth: 2,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
    //Number - Amount of animation steps
    animationSteps: 100,
    //String - Animation easing effect
    animationEasing: "easeOutBounce",
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate: true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale: false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive: true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: false,
    //String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
    //String - A tooltip template
    tooltipTemplate: "<%=value %>% <%=label%>"
  };
  pieChart.Doughnut(PieData, pieOptions);
</script>
