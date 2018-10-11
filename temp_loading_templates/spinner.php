<style>
body{
	overflow: hidden;
}
</style>
<script>
document.addEventListener('mousemove', body => {
	document.getElementsByClassName('loadAnimation')[0].style.cssText = 'top: '+body.clientY+'px;left: '+body.clientX+'px;';
});
</script>

<!-- Style the loading template ///////////////////////////////// -->
<?php
// Total box width in pixels
$loaderDimension = 500;
// Minimum lines
$minLines = 10;
// Mamimum lines
$maxLines = 10;
// Line colors in Hex
$lineColorArray = array('114E80', '3E6482', '4D6980', '213C6E', '155685');
// Line width in pixels
$lineWidth = 4;
// Minimum line animation speed in seconds
$minSpeed = 4;
// Maximum line animation speed in seconds
$maxSpeed = 6;
// Random opacity change (true/false)
$opacityChange = true;
// Random opacity change speed in seconds (min 1)
$opacityChangeSpeed = 2;
// ////////////////////////////////////////////////////////////// -->
?>
<div class="loadAnimation" style="width: <?= $loaderDimension; ?>px;height: <?= $loaderDimension; ?>px;">
	<script>
	var count = 0;
	</script>
	<?php
	function rand_float($st_num=0,$end_num=1,$mul=1000000) {
		if ($st_num>$end_num) return false;
		return mt_rand($st_num*$mul,$end_num*$mul)/$mul;
	}
	$j = rand($minLines, $maxLines);
	for($i = 0; $i < $j; $i++) {
		$lineColor = $lineColorArray[rand(0, (count($lineColorArray) - 1))];
		$randNmb = rand(0, 360);
		?>
		<style>
		@-webkit-keyframes rotate<?= $i; ?> {
			from {
				-webkit-transform: rotate(<?php if($randNmb % 2 == 0){echo $randNmb;}else{echo ($randNmb - 360);} ?>deg);
			} to { 
				-webkit-transform: rotate(<?php if($randNmb % 2 == 0){echo ($randNmb - 360);}else{echo $randNmb;} ?>deg);
			}
		}
		@keyframes rotate<?= $i; ?> {
			from {
				-webkit-transform: rotate(<?php if($randNmb % 2 == 0){echo $randNmb;}else{echo ($randNmb - 360);} ?>deg);
			} to { 
				-webkit-transform: rotate(<?php if($randNmb % 2 == 0){echo ($randNmb - 360);}else{echo $randNmb;} ?>deg);
			}
		}
		</style>
		<?php
		$randNmb = rand(1, 2);
		$dimension = ((($i * 2) * ($i + 4)) + ($loaderDimension / 2));
		?>
		<div class="loadAnimationContainer"><div class="loadAnimationRing" style="-webkit-animation-name: rotate<?= $i; ?>;-webkit-animation-duration: <?= rand_float($minSpeed, $maxSpeed); ?>s;width: <?= $dimension; ?>px;height: <?= $dimension; ?>px;top: calc(50% - <?= ($dimension / 2); ?>px);left: calc(50% - <?= ($dimension / 2); ?>px);border: <?= $lineWidth; ?>px solid transparent;border-left: <?= $lineWidth; ?>px solid #<?= $lineColor; ?>;"></div></div>
		<?php
		if($opacityChange) {
			?>
			<script>
			setInterval(function(){
				document.getElementsByClassName('loadAnimationContainer')[Number('<?= $i; ?>')].style.cssText = 'opacity: '+(Math.random() * (0.1 - 1) + 1).toFixed(4)+';';
			}, Number(<?= $opacityChangeSpeed.'000'; ?>));
			</script>
			<?php
		}
	}
	?>
</div>
<style>
.loadAnimation{
	position: absolute;
	min-width: 10px;
	min-height: 10px;
}

.loadAnimationContainer{
	transition: opacity <?= $opacityChangeSpeed; ?>s;
}

.loadAnimationRing{
	position: absolute;
	width: 100px;
	height: 100px;
	border-bottom-left-radius: 50%;
	border-top-left-radius: 50%;
	overflow: hidden;
}

.loadAnimationRing
{ 
	-webkit-animation-iteration-count:  infinite;
	-webkit-animation-timing-function: linear;
}
</style>