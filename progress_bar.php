<script src="https://use.fontawesome.com/1ae0501f24.js"></script>

<!-- Style the timeline ///////////////////////////////////////// -->
<style>
#timeline{
	position: relative;
	width: 90%;
	top: 50px;
	left: 5%;
}
</style>
<?php
$previousPage = 1;
$currentPage = 8;
$totalPages = array(1=>'fa fa-user-circle', 2=>'fa fa-telegram', 3=>'fa fa-user-circle-o', 4=>'fa fa-ban', 5=>'fa fa-adjust', 6=>'fa fa-check-circle-o', 7=>'fa fa-exclamation-circle', 8=>'fa fa-creative-commons');
// Timeline colors:
// Bar size in pixels
$timelineBoxSize = 12;
// Bar border
$timelineBorderColorBar = '#404040';
// Bar standard background
$timelineBackgroundColorBarStandard = '#CFCFCF';
// Bar changed background
$timelineBackgroundColorBarChanged = '#32819C';
// Icons border
$timelineBorderColorIcons = '#404040';
// Icons background
$timelineBackgroundColorIcons = '#619CB0';
// Icons color
$timelineColorIcons = '#619CB0';
// ////////////////////////////////////////////////////////////// -->

if($timelineBoxSize > 55 || !is_int($timelineBoxSize)) {
	$timelineBoxSize = 55;
}
if(empty($currentPage)) {
	$currentPage = 1;
}
if($currentPage > count($totalPages)) {
	$currentPage = count($totalPages);
}
if(empty($previousPage)) {
	$previousPage = count($totalPages);
}
function checkTimeline($previousPage, $currentPage) {
	if(empty($previousPage) || $previousPage >= $currentPage || $previousPage < 1) {
		return true;
	} else {
		return false;
	}
}
?>
<div id="timeline">
	<div id="timelineBox">
		<?php
		if($currentPage > 1) {
			?><div id="timelineBackground" style="width: <?php
			if(checkTimeline($previousPage, $currentPage)) {
				echo (100 / (count($totalPages) - 1)) * ($currentPage - 1).'%;"></div>';
			} else {
				echo (100 / (count($totalPages) - 1)) * ($previousPage - 1).'%;"></div>';
				echo '<script>setTimeout(function(){document.getElementById("timelineBackground").style.cssText = "width: '.(100 / (count($totalPages) - 1)) * ($currentPage - 1).'%;";}, 1);</script>';
			}
		}
		if(!checkTimeline($previousPage, $currentPage)) {
			$j = 0;
		}
		for($i=1; $i<count($totalPages) + 1; $i++) {
			$position = (100 / (count($totalPages) - 1)) * ($i - 1);
			if(!checkTimeline($previousPage, $currentPage)) {
				if($i < $currentPage && $i > $previousPage) {
					$j++;
					?>
					<script>
					setTimeout(function(){
						document.getElementsByClassName('point')[<?= ($i - 1); ?>].style.cssText = 'left: calc(<?= $position; ?>% - 36px);background-color: <?= $timelineBackgroundColorIcons; ?>;';
					}, <?= (2000 / ($currentPage - $previousPage)) * $j; ?>);
					</script>
					<?php
				}
			}
			?>
			<div class="point" style="<?php if($i > 1){echo 'left: calc('.$position.'% - 36px);';if($i < $currentPage){if(!checkTimeline($previousPage, $currentPage) && $i > $previousPage){echo 'background-color: '.$timelineBackgroundColorBarStandard.';';}else{echo 'background-color: '.$timelineBackgroundColorIcons.';';}}if(($i == count($totalPages) && $currentPage == $i) && (checkTimeline($previousPage, $currentPage) && $i < $previousPage)){echo 'background-color: '.$timelineBackgroundColorIcons.';';}elseif($i > $currentPage || ($i == count($totalPages) && $previousPage <= $currentPage)){if(($previousPage == $currentPage) && $i == $currentPage){echo 'background-color: '.$timelineBackgroundColorIcons.';';}else{echo 'background-color: '.$timelineBackgroundColorBarStandard.';';}}}else{echo 'background-color: '.$timelineBackgroundColorIcons.';left: -35px;';} ?>">
				<?php
				if($i == $currentPage && $i > 1 && $i != count($totalPages)) {
					?>
					<div id="pointHalfBackgroundLeft"></div>
					<?php
					if(!checkTimeline($previousPage, $currentPage)) {
						?>
						<div id="pointHalfBackgroundLeftAnimation"></div>
						<?php
					}
					?>
					<div id="pointHalfBackgroundRight"></div>
					<?php
				}
				?>
				<i class="<?= $totalPages[$i]; ?> pointIcon" aria-hidden="true"></i>
			</div>
			<?php
		}
		if(!checkTimeline($previousPage, $currentPage)) {
			if(($currentPage == count($totalPages)) && $previousPage < $currentPage) {
				?>
				<script>
				setTimeout(function(){
					document.getElementsByClassName('point')[<?= (count($totalPages) - 1); ?>].style.cssText = 'left: calc(<?= $position; ?>% - 36px);background-color: <?= $timelineBackgroundColorIcons; ?>;';
				}, 1850);
				</script>
				<?php
			} else {
				?>
				<script>
				setTimeout(function(){
					document.getElementById('pointHalfBackgroundLeftAnimation').style.cssText = 'opacity: 1;';
				}, 1850);
				</script>
				<?php
			}
		}
		?>
	</div>
</div>
<style>
#timelineBox{
	background-color: <?= $timelineBackgroundColorBarStandard; ?>;
	position: relative;
	width: calc(100% - 57px);
	height: <?= $timelineBoxSize; ?>px;
	left: 26px;
	border: 1px solid <?= $timelineBorderColorBar; ?>;
	border-radius: 30px;
	z-index: -2;
}

#timelineBackground{
	background-color: <?= $timelineBackgroundColorBarChanged; ?>;
	position: absolute;
	height: 100%;
	border-radius: 30px;
	<?php
	if(!empty($previousPage) && $previousPage < $currentPage && $previousPage > 0) {
		?>width: <?= (100 / (count($totalPages) - 1)) * ($previousPage - 1); ?>%;<?php
	}
	?>
	transition: all 2s;
	-webkit-transition: all 2s;
	transition-timing-function: linear;
	-webkit-transition-timing-function: linear;
}

.point{
	position: absolute;
	width: 70px;
	height: 70px;
	top: -<?= ((70 - $timelineBoxSize) / 2); ?>px;
	border-radius: 50%;
	border: 2px solid <?= $timelineBorderColorIcons; ?>;
	transition: all 0.5s;
	-webkit-transition: all 0.5s;
}

#pointHalfBackgroundLeft:before, #pointHalfBackgroundLeftAnimation:before{
	content: "";
	<?php
	if(!checkTimeline($previousPage, $currentPage)) {
		?>background-color: <?= $timelineBackgroundColorBarStandard; ?>;border: 1px solid <?= $timelineBackgroundColorBarStandard; ?>;<?php
	} else {
		?>background-color: <?= $timelineBackgroundColorBarChanged; ?>;border: 1px solid <?= $timelineBackgroundColorBarChanged; ?>;<?php
	}
	?>
	position: absolute;
	width: 35px;
	height: 70px;
	-moz-border-radius: 50px 0px 0px 50px;
	border-radius: 50px 0px 0px 50px;
	z-index: -1;
}

#pointHalfBackgroundLeftAnimation:before{
	background-color: <?= $timelineBackgroundColorBarChanged; ?>;
	border: 1px solid <?= $timelineBackgroundColorBarChanged; ?>;
	border: 1px solid transparent;
	width: 31px;
	height: 67px;
	top: 0.5px;
	left: 1px;
}

#pointHalfBackgroundLeftAnimation{
	opacity: 0;
	transition: all 0.5s;
	border: none;
}

#pointHalfBackgroundRight:before{
	content: "";
	background-color: <?= $timelineBackgroundColorBarStandard; ?>;
	position: absolute;
	width: 35px;
	height: 70px;
	right: 0;
	-moz-border-radius: 0px 50px 50px 0px;
	border-radius: 0px 50px 50px 0px;
	z-index: -1;
}

.pointIcon{
	color: #2E2E2E;
	position: absolute;
	width: 70px;
	top: 5px;
	text-align: center;
	font-size: 60px;
}
</style>
