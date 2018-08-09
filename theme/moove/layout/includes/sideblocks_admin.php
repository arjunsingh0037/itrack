<?php
// include 'menulink.php';
// include 'menuconfig.php';
// include 'dashboardlib.php';
global $CFG,$USER,$OUTPUT,$DB;
$liclass = '';
$context = context_user::instance($USER->id);
?>
<!-- Right sidebar -->
<div class="right-sidebar">
  <div class="search-row">
    <input type="text" placeholder="Search" class="form-control">
  </div>
  <div class="right-stat-bar">
    <ul class="right-side-accordion">
      <?php echo $OUTPUT->blocks_for_region('side-post','col-md-3') ?>
    </ul>
  </div>
</div>


