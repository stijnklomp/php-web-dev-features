<!-- Style the slideshow //////////////////////////////////////// -->
<style>
#slideshow{
	width: 600px;
	height: 300px;
	top: 100px;
	left: calc(50% - 300px);
	border-radius: 10px;
}
</style>
<body>
<?php
$slideshowPictures = array(1=>'https://slodive.com/wp-content/uploads/2011/06/vintage-backgrounds/dark-vintage.jpg', 2=>'https://hdbackgroundspot.com//storage/upload/textures-background/textures-background-82.jpg', 3=>'https://cdn-images-1.medium.com/max/1600/0*qYji31AIscjHlk9a.jpg', 4=>'https://cdn.tutsplus.com/psd/uploads/legacy/psdtutsarticles/linkb_40futuristic/15-stars.jpg', 5=>'https://designmodo.com/wp-content/uploads/2011/11/Wood-Textures-2.jpg');
// Slideshow direction
// 1 = To the right
// 2 = To the left
// 3 = To the bottom
// 4 = To the top
$slideshowDirection = 4;
// Slideshow speed in miliseconds
$slideshowSpeed = 2000;
// Slideshow picture speed in seconds
$slideshowPictureSpeed = 0.5;
// Slideshow picture timing
// 1 = Linear
// 2 = Ease
// 3 = Ease-in
// 4 = Ease-out
// 5 = Ease-in-out
$slideshowPictureTiming = 2;
// ////////////////////////////////////////////////////////////// -->

if(empty($slideshowPictures)) {
	$slideshowPictures = array(1=>'https://via.placeholder.com/350x150');
}
if(empty($slideshowSpeed) || !is_int($slideshowSpeed)) {
	$slideshowSpeed = 2000;
}
if(empty($slideshowPictureSpeed) || !is_int($slideshowPictureSpeed)) {
	$slideshowPictureSpeed = 0.5;
}
if(empty($slideshowPictureTiming) || !is_int($slideshowPictureTiming)) {
	$slideshowPictureTiming = 2;
}
$slideshowTotalPictures = count($slideshowPictures);
?>
<style>
#slideshow{
	position: relative;
	<?php
	if($slideshowDirection == 1 || $slideshowDirection == 2) {
		echo 'display: inline-flex;';
	}
	?>
	overflow: hidden;
}

.slideshowPicture{
	position: relative;
	width: 100%;
	min-width: 100%;
	height: 100%;
	min-height: 100%;
	padding: 0;
	margin: 0;
	transition: all <?= $slideshowPictureSpeed; ?>s;
	-webkit-transition: all <?= $slideshowPictureSpeed; ?>s;
	<?php
	if($slideshowDirection == 1) {
		echo 'left: 0;';
	} elseif($slideshowDirection == 2) {
		echo 'left: -'.(100 * (count($slideshowPictures) - 1)).'%;';
	} elseif($slideshowDirection == 3) {
		echo 'top: 0;';
	} else {
		echo 'top: -'.(100 * (count($slideshowPictures) - 1)).'%;';
	}
	if($slideshowPictureTiming == 1) {
		echo 'transition-timing-function: linear;-webkit-transition-timing-function: linear;';
	} elseif($slideshowPictureTiming == 2) {
		echo 'transition-timing-function: ease;-webkit-transition-timing-function: ease;';
	} elseif($slideshowPictureTiming == 3) {
		echo 'transition-timing-function: ease-in;-webkit-transition-timing-function: ease-in;';
	} elseif($slideshowPictureTiming == 4) {
		echo 'transition-timing-function: ease-out;-webkit-transition-timing-function: ease-out;';
	} else {
		echo 'transition-timing-function: ease-in-out;-webkit-transition-timing-function: ease-in-out;';
	}
	?>
}
</style>
<div id="slideshow">
	<?php
	if($slideshowDirection == 1 || $slideshowDirection == 3) {
		for($i=1; $i - 1<$slideshowTotalPictures; $i++) {
			echo '<img src="'.$slideshowPictures[$i].'" class="slideshowPicture">';
		}
	}
	else {
		for($i=$slideshowTotalPictures; $i>0; $i--) {
			echo '<img src="'.$slideshowPictures[$i].'" class="slideshowPicture">';
		}
	}
	?>
</div>
<?php
if($slideshowTotalPictures > 1) {
	?>
	<script>
	var slidePict = document.getElementsByClassName('slideshowPicture');
	var j = 1;
	var l = Number('<?= $slideshowTotalPictures; ?>') - 1;
	var check = true;
	setInterval(function(){j = slideshowDisplayNewPicture(j)}, Number(<?= $slideshowSpeed; ?>));
	function slideshowDisplayNewPicture(j) {
		var k = 1;
		if(j == Number(<?= $slideshowTotalPictures; ?>)){
			for(var i = 0; i < k; i++) {
				if(slidePict[i]) {
					slidePict[i].style.cssText = '<?php if($slideshowDirection == 1 || $slideshowDirection == 2){echo 'left:';}else{echo 'top:';} ?> 0;';
					k++;
				}
			}
			j = 1;
			l = Number('<?= $slideshowTotalPictures; ?>');
		} else {
			if(check) {
				<?php
				if($slideshowTotalPictures > 2 && ($slideshowDirection == 2 || $slideshowDirection == 4)) {
					?>
					j++;
					k++;
					check = false;
					<?php
				}
				?>
			}
			for(var i = 0; i < k; i++) {
				
				if(slidePict[i]) {
					<?php
					if($slideshowDirection == 1) {
						?>
						slidePict[i].style.cssText = 'left: -'+(100 * j)+'%;';
						<?php
					} elseif($slideshowDirection == 2) {
						?>
						slidePict[i].style.cssText = 'left: '+((0 - (Number('<?= $slideshowTotalPictures; ?>') * 100)) + (100 * j))+'%;';
						<?php
					} elseif($slideshowDirection == 3) {
						?>
						slidePict[i].style.cssText = 'top: -'+(100 * j)+'%;';
						<?php
					} else {
						?>
						slidePict[i].style.cssText = 'top: '+((0 - (Number('<?= $slideshowTotalPictures; ?>') * 100)) + (100 * j))+'%;';
						<?php
					}
					?>
					k++;
				}
			}
			j++;
			l--;
		}
		return j;
	}
	</script>
	<?php
}
?>