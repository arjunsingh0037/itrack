  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
      <div class="chart-responsive col-md-12">
      <canvas id="pieChart" height="150"></canvas>

<div class="chart" style="display:none;">
<!-- Sales Chart Canvas -->
<canvas id="salesChart" style="height: 150px;"></canvas>
</div>

      </div>
      <div class="col-md-12">
                  <ul class="chart-legend clearfix">
                    <li><i class="fa fa-circle-o text-green"></i> Completed</li>
                    <li><i class="fa fa-circle-o text-yellow"></i> In Progress</li>
                    <li><i class="fa fa-circle-o text-red"></i> Not Started</li>
                  </ul>
                </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">Dashboard Menu</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active treeview">
          <a href="#"><i class="fa fa-home"></i> <span>Home</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="innerpage.html">Test Link 1</a></li>
            <li><a href="innerpage2.html">Test Link 2</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-graduation-cap"></i> <span>My Courses</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link goes here</a></li>
            <li><a href="#">Link goes here</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-file-text"></i> <span>My Reports</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link goes here</a></li>
            <li><a href="#">Link goes here</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-th"></i> <span>Social learning</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <li><a href="#">Forums</a></li>
          <li><a href="#">News & notifications</a></li>
            <li><a href="#">Discussion forums</a></li>
            <li><a href="#">Sureys</a></li>
            <li><a href="#">FAQs</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">WIKI</a></li>
            <li><a href="#">Announcements</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-bell"></i> <span>Notification</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link goes here</a></li>
            <li><a href="#">Link goes here</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-user"></i> <span>My Profile</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link goes here</a></li>
            <li><a href="#">Link goes here</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-cog"></i> <span>Client administration</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link goes here</a></li>
            <li><a href="#">Link goes here</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-wrench"></i> <span>System administration</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link goes here</a></li>
            <li><a href="#">Link goes here</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-users"></i> <span>Users management</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link goes here</a></li>
            <li><a href="#">Link goes here</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-newspaper-o"></i> <span>Catalogue management</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link goes here</a></li>
            <li><a href="#">Link goes here</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-graduation-cap"></i> <span>Courses management</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link goes here</a></li>
            <li><a href="#">Link goes here</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-th"></i> <span>Social learning management</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link goes here</a></li>
            <li><a href="#">Link goes here</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-check-square-o"></i> <span>Assessment management</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link goes here</a></li>
            <li><a href="#">Link goes here</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-trophy"></i> <span>Certificates management</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link goes here</a></li>
            <li><a href="#">Link goes here</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-file-text"></i> <span>Reports management</span>
            <span class="pull-right-container">
              <i class="fa fa-plus pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link goes here</a></li>
            <li><a href="#">Link goes here</a></li>
          </ul>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>