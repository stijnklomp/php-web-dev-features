<!-- Style the title box //////////////////////////////////////// -->
<?php
$startPos = 4;
$endPos = 15;
$countDir = false;
// Number height in pixels
$counterHeight = 20;
// Animation timing
// 1 = Linear
// 2 = Ease
// 3 = Ease-in
// 4 = Ease-out
// 5 = Ease-in-out
$counterTiming = 2;
?>
<!-- //////////////////////////////////////////////////////////// -->
<div class="counterContainer">
	<div class="counterContent">
		<?php
		if($countDir) {
			for($i = 0; $i <= ($endPos - $startPos); $i++) {
				echo '<div class="counterNumber">'.($startPos + $i).'</div>';
			}
		} else {
			for($i = $endPos; $i >= $startPos; $i--) {
				echo '<div class="counterNumber">'.$i.'</div>';
			}
		}
		?>
	</div>
</div>
<style>
.counterContainer{
	position: absolute;
	width: 45px;
	height: <?= $counterHeight; ?>px;
	border: 1px solid black;
	overflow: hidden;
}

.counterContent{
	position: absolute;
	width: 100%;
	height: auto;
	animation: changeTop 5s;
	-webkit-animation: changeTop 5s;
	<?php
	if($counterTiming == 1) {
		echo 'transition-timing-function: linear;-webkit-transition-timing-function: linear;';
	} elseif($counterTiming == 2) {
		echo 'transition-timing-function: ease;-webkit-transition-timing-function: ease;';
	} elseif($counterTiming == 3) {
		echo 'transition-timing-function: ease-in;-webkit-transition-timing-function: ease-in;';
	} elseif($counterTiming == 4) {
		echo 'transition-timing-function: ease-out;-webkit-transition-timing-function: ease-out;';
	} else {
		echo 'transition-timing-function: ease-in-out;-webkit-transition-timing-function: ease-in-out;';
	}
	?>
}

.counterNumber{
	position: relative;
	width: 100%;
	height: <?= $counterHeight; ?>px;
	text-align: center;
	font-size: 20px;
}

@keyframes changeTop {
	from {top: -<?= (($endPos - $startPos) * $counterHeight); ?>px;}
	to {top: 0;}
}
</style>