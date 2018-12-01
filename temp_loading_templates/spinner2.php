<!-- Style the loading template ///////////////////////////////// -->
<?php
// Loader width in pixels
$width = 180;
// Loader height in pixels
$height = 180;
// Line width (min: 0.05 / max: 2.5)
$lineWidth = 2;
// Line color
$color = '#00acc1';
// Loader rotate speed in seconds
$rotateSpeed = 100;
// Line rotate speed in seconds
$lineSpeed = 3;
// ////////////////////////////////////////////////////////////// -->
?>
<div class="circleRotate">
	<div class="circleContainer">
		<svg class="circleBlock" viewbox="0 0 33.83098862 33.83098862">
			<circle class="circleLine circleLine1" cx="16.91549431" cy="16.91549431" r="15.91549431">
		</svg>
		<svg class="circleBlock" viewbox="0 0 33.83098862 33.83098862" style="transform: rotateY(180deg);">
			<circle class="circleLine circleLine2" cx="16.91549431" cy="16.91549431" r="15.91549431">
		</svg>
	</div>
</div>
<style>
.circleRotate{
	width: <?= $width; ?>px;
	height: <?= $height; ?>px;
	-webkit-animation: spin <?= $rotateSpeed; ?>s linear infinite;
	-moz-animation: spin <?= $rotateSpeed; ?>s linear infinite;
	animation: spin <?= $rotateSpeed; ?>s linear infinite;
}

@keyframes spin {
	to { transform:rotate(360deg);-webkit-transform: rotate(360deg);-moz-transform: rotate(360deg); }
}

.circleBlock{
	position: absolute;
	width: <?= $width; ?>px;
	height: <?= $height; ?>px;
}

.circleLine{
	transform-origin: center;
	stroke: <?= $color; ?>;
	stroke-width: <?= $lineWidth; ?>;
	stroke-linecap: round;
	fill: none;
}

.circleLine1 {
	transform: rotate(-90deg);
	stroke-dasharray: 10 100;
	-webkit-animation: circle-chart-fill1 <?= $lineSpeed; ?>s ease-in-out infinite;
	-moz-animation: circle-chart-fill1 <?= $lineSpeed; ?>s ease-in-out infinite;
	animation: circle-chart-fill1 <?= $lineSpeed; ?>s ease-in-out infinite;
}

.circleLine2 {
	transform: rotate(-414deg);
	stroke-dasharray: 90 100;
	-webkit-animation: circle-chart-fill2 <?= $lineSpeed; ?>s ease-in-out infinite;
	-moz-animation: circle-chart-fill2 <?= $lineSpeed; ?>s ease-in-out infinite;
	animation: circle-chart-fill2 <?= $lineSpeed; ?>s ease-in-out infinite;
}

@keyframes circle-chart-fill1 {
	0% { stroke-dasharray: 10 100; }
	50% { opacity: 1;stroke-dasharray: 90 100; }
	50.1% { opacity: 0;stroke-dasharray: 90 100; }
	100% { opacity: 0;stroke-dasharray: 90 100; }
}

@keyframes circle-chart-fill2 {
	0% { opacity: 0; }
	49.9% { opacity: 0; }
	50% { opacity: 1;stroke-dasharray: 90 100; }
	100% { stroke-dasharray: 10 100; }
}

.circleContainer{
	position: absolute;
	width: <?= $width; ?>px;
	height: <?= $height; ?>px;
	-webkit-animation:  rotate <?= ($lineSpeed); ?>s linear infinite;
	-moz-animation:  rotate <?= ($lineSpeed); ?>s linear infinite;
	animation: rotate <?= ($lineSpeed); ?>s linear infinite;
}

@keyframes rotate {
	from { transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg); }
	to { transform: rotate(432deg);-webkit-transform: rotate(432deg);-moz-transform: rotate(432deg); }
}
</style>
