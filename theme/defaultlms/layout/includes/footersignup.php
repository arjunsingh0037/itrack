  <footer class="main-footer1" style="background: #a6cae9;">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      <span style="" >For more details visit: </span> <a style="text-decoration:underline; color:#444;" class ="padrg5px" href="http://www.skilldom.co.in">http://www.skilldom.co.in</a>
    </div>
<?php
    $hasfootnote = (empty($PAGE->theme->settings->footnote)) ? false : $PAGE->theme->settings->footnote;
    if ($hasfootnote) {
    	echo '<span style=" class ="padlf5px">
		'.strip_tags($hasfootnote).' </span>';
	}else{
		echo '<span style="" class ="padlf5px">
		Copyright &copy; SKILLDOM Learning Solutions  Pvt. Ltd. | All Rights Reserved. </span>';
	}
    ?>
  </footer>