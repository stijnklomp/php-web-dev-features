<!-- Style the loading template ///////////////////////////////// -->
<style>
.temporaryLoadingTemplateLine{
	background-color: #7A8F96;
}
</style>
<?php
// Total box width in pixels
$loadingTemplateBoxWidth = 250;
// Total lines
$loadingTemplateLineQuantity = 105;
// Line height in pixels
$loadingTemplateLineHeight = 2;
// Line margin in pixels
$loadingTemplateLineMargin = 0;
// Line animation speed in seconds
$loadingTemplateLineSpeed = 3;
// ////////////////////////////////////////////////////////////// -->
?>
<div id="temporaryLoadingTemplate">
	<center>
		<?php
		$j = 0;
		for($i=0; $i<2; $i++) {
			for($k=0; $k<$loadingTemplateLineQuantity; $k++) {
				if($i == 0) {
					$j++;
				} else {
					$j--;
				}
				if($j != 0) {
					?>
					<div class="temporaryLoadingTemplateLine" style="width: <?= (($j * (100 / $loadingTemplateLineQuantity)) * ($loadingTemplateBoxWidth / 100)); ?>px;<?php if($i == 0 && $j == 1){echo 'margin-top: 0px;';} ?>"></div>
					<?php
				}
			}
		}
		?>
	</center>
</div>
<style>
.temporaryLoadingTemplateLine{
	position: relative;
	height: <?= $loadingTemplateLineHeight; ?>;
	margin-top: <?= $loadingTemplateLineMargin; ?>px;
	transition: all <?= $loadingTemplateLineSpeed; ?>s;
	transition-timing-function: ease-in-out;
}
</style>
<script>
var check = false;
var j = Number(<?= $loadingTemplateLineQuantity; ?>);
var k = 0;
function temporaryLoadingTemplateAnimation() {
	if(check) {
		check = false;
	} else {
		check = true;
	}
	for(i=0; i<((j * 2) - 1); i++) {
		if(check) {
			var templateLineWidth = Math.floor(Math.random() * (Number(<?= $loadingTemplateLineQuantity; ?>) - 5) ) + 5;
		} else {
			if(i < j) {
				k++;
			} else {
				k--;
			}
			var templateLineWidth = ((k * (100 / Number(<?= $loadingTemplateLineQuantity; ?>))) * (Number(<?= $loadingTemplateBoxWidth; ?>) / 100));
		}
		document.getElementsByClassName('temporaryLoadingTemplateLine')[i].style.cssText = 'width: '+templateLineWidth+'px;';
	}
}
temporaryLoadingTemplateAnimation();
setInterval(temporaryLoadingTemplateAnimation, Number(<?= ($loadingTemplateLineSpeed * 1000); ?>));
</script>