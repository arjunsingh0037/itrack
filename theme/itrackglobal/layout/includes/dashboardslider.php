				<?php
				echo html_writer::tag('script', '', array('type' => 'text/javascript', 'src' => $CFG->wwwroot . '/theme/defaultlms/jquery/bootstrap-carousel.js'));
				?>
				<script>
				$('.carousel').carousel()
				</script>
				<?php
					$i = 1;
					global $DB,$CFG;
					require_once $CFG->libdir . '/datalib.php';
					?>
					<style type="text/css">
					.carousel-inner>.active{height:225px;}
					.carousel-inner {height:225px!important;}
					.carousel{margin-bottom: 0px!important;}
					.carousel-inner>.item>img, .carousel-inner>.item>a>img {height: 225px;width:100%;}
					.carousel-caption{
    					background: rgba(51, 51, 51, 0.31)!important;}
    					.carousel-indicators{top: 195px!important;left: none!important;}
					</style>
 					<div class =" " id="userbanner">
                           <!-- <img src="<?php echo $CFG->wwwroot . '/theme/defaultlms/pix/slider/skilldom_blue.png' ?>" width="100%">-->
								<div id="myCarousel" class="carousel slide" data-ride="carousel">
								
								<div class="carousel-inner" role="listbox">

								<div class="item active">
							<?php 
							$imglink1 = $PAGE->theme->setting_file_url('slide1image', 'slide1image');
							$default1 = $CFG->wwwroot.'/theme/'.$CFG->theme.'/pix/slider/skilldom_blue.png';
							if(!empty($imglink1)){
								echo '<img src="'.$imglink1.'" alt="slider1" class="img-responsive "  >';
							}else{
								echo '<img src="'.$default1.'" alt="slider1" class="img-responsive "  >';
							}
							?>
								<!--<div class="carousel-caption">
								<?php echo '<h3>'.$PAGE->theme->settings->slide1.'</h3>';
								      echo '<p>'.$PAGE->theme->settings->slide1caption.'</p>';
								?>
								</div>-->
								</div>

								<div class="item">
							<?php 
							$imglink2 = $PAGE->theme->setting_file_url('slide2image', 'slide2image');
							$default2 = $CFG->wwwroot.'/theme/'.$CFG->theme.'/pix/slider/skilldom_green.png';
							if(!empty($imglink2)){
								echo '<img src="'.$imglink2.'" alt="slider2" class="img-responsive "  >';
							}else{
								echo '<img src="'.$default2.'" alt="slider2" class="img-responsive "  >';
							}
							?>
								<!--<div class="carousel-caption">
								<?php echo '<h3>'.$PAGE->theme->settings->slide2.'</h3>';
								      echo '<p>'.$PAGE->theme->settings->slide2caption.'</p>';
								?>
								</div>-->
								</div>

								<div class="item">
							<?php 
							$imglink3 = $PAGE->theme->setting_file_url('slide3image', 'slide3image');
							$default3 = $CFG->wwwroot.'/theme/'.$CFG->theme.'/pix/slider/skilldom_orange.png';
							if(!empty($imglink3)){
								echo '<img src="'.$imglink3.'" alt="slider3" class="img-responsive ">';
							}else{
								echo '<img src="'.$default3.'" alt="slider3" class="img-responsive ">';
							}
							?>
								<!--<div class="carousel-caption">
								<?php echo '<h3>'.$PAGE->theme->settings->slide3.'</h3>';
								      echo '<p>'.$PAGE->theme->settings->slide3caption.'</p>';
								?>
								</div>-->
								</div>

								</div>

								<ol class="carousel-indicators">
								<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
								<li data-target="#myCarousel" data-slide-to="1"></li>
								<li data-target="#myCarousel" data-slide-to="2"></li>
								</ol>
								
								</div> 
                        </div>


