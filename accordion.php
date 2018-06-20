<!-- Style the accordion //////////////////////////////////////// -->
<div style="background-color: black;position: relative;width: calc(80% + 2px);height: 1px;left:10%;"></div>
<style>
.accordionTextBlock{
	width: 80%;
	height: 30px;
	left: 10%;
	border-left: 1px solid black;
	border-right: 1px solid black;
	border-bottom: 1px solid black;
}

.accordionTextBlockTitle{
	height: 30px;
	text-align: center;
	font-size: 22px;
	border-bottom: 1px solid black;
}

.accordionTextBlockContentBlock{
	padding: 5px;
	font-size: 18px;
}
</style>
<?php
// Accordion title height
$accordionTextBlockTitleHeight = '30px';
// Accordion content padding in pixels
$accordionContentPadding = 5;
// Accordion content speed in seconds
$accordionContentSpeed = 0.5;
// Accordion content timing
// 1 = Linear
// 2 = Ease
// 3 = Ease-in
// 4 = Ease-out
// 5 = Ease-in-out
$accordionContentTiming = 2;
// ////////////////////////////////////////////////////////////// -->

if(empty($accordionContentPadding) || !is_int($accordionContentPadding)) {
	$accordionContentPadding = 5;
}
?>

<style>
.accordionTextBlock{
	position: relative;
	min-height: 10px;
	transition: all <?= $accordionContentSpeed; ?>s;
	-webkit-transition: all <?= $accordionContentSpeed; ?>s;
	<?php
	if(!empty($accordionTextBlockTitleHeight)) {
		echo 'height: '.$accordionTextBlockTitleHeight.';';
	}
	if($accordionContentTiming == 1) {
		echo 'transition-timing-function: linear;-webkit-transition-timing-function: linear;';
	} elseif($accordionContentTiming == 2) {
		echo 'transition-timing-function: ease;-webkit-transition-timing-function: ease;';
	} elseif($accordionContentTiming == 3) {
		echo 'transition-timing-function: ease-in;-webkit-transition-timing-function: ease-in;';
	} elseif($accordionContentTiming == 4) {
		echo 'transition-timing-function: ease-out;-webkit-transition-timing-function: ease-out;';
	} else {
		echo 'transition-timing-function: ease-in-out;-webkit-transition-timing-function: ease-in-out;';
	}
	?>
}

.accordionTextBlockTitle{
	position: relative;
	width: calc(100% + 1px);
	min-height: 10px;
}

.accordionTextBlockContentBlock{
	position: relative;
	height: 0;
	opacity: 0;
	overflow: hidden;
	transition: all <?= $accordionContentSpeed; ?>s, opacity <?= ($accordionContentSpeed / 2); ?>s;
	-webkit-transition: all <?= $accordionContentSpeed; ?>s, opacity <?= ($accordionContentSpeed / 2); ?>s;
	<?php
	echo 'padding: '.$accordionContentPadding.'px;';
	if($accordionContentTiming == 1) {
		echo 'transition-timing-function: linear;-webkit-transition-timing-function: linear;';
	} elseif($accordionContentTiming == 2) {
		echo 'transition-timing-function: ease;-webkit-transition-timing-function: ease;';
	} elseif($accordionContentTiming == 3) {
		echo 'transition-timing-function: ease-in;-webkit-transition-timing-function: ease-in;';
	} elseif($accordionContentTiming == 4) {
		echo 'transition-timing-function: ease-out;-webkit-transition-timing-function: ease-out;';
	} else {
		echo 'transition-timing-function: ease-in-out;-webkit-transition-timing-function: ease-in-out;';
	}
	?>
}
</style>
<?php
$accordionCount = 0;
?>
<div class="accordionTextBlock" onclick="unfoldAccordion(Number(<?= $accordionCount; ?>));">
	<?php
	$accordionCount++;
	?>
	<div class="accordionTextBlockTitle">
		Test Title One
	</div>
	<div class="accordionTextBlockContentBlock">
		<div class="accordionTextBlockContent">
			This is just some random text.<br/>
			This text is just to fill this div up.<br/>
			Why are you even reading this...?<br/>
			Do you seriously have nothing better to do?<br/>
			You really have to stop reading this!<br/>
			Okay, this is just getting pathetic.
		</div>
	</div>
</div>
<div class="accordionTextBlock" onclick="unfoldAccordion(Number(<?= $accordionCount; ?>));">
	<?php
	$accordionCount++;
	?>
	<div class="accordionTextBlockTitle">
		Test Title Two
	</div>
	<div class="accordionTextBlockContentBlock">
		<div class="accordionTextBlockContent">
			This is just some random text.<br/>
			This text is just to fill this div up.<br/>
			Why are you even reading this...?<br/>
			Do you seriously have nothing better to do?<br/>
			You really have to stop reading this!<br/>
			Okay, this is just getting pathetic.
		</div>
	</div>
</div>
<div class="accordionTextBlock" onclick="unfoldAccordion(Number(<?= $accordionCount; ?>));">
	<?php
	$accordionCount++;
	?>
	<div class="accordionTextBlockTitle">
		Test Title Three
	</div>
	<div class="accordionTextBlockContentBlock">
		<div class="accordionTextBlockContent">
			This is just some random text.<br/>
			This text is just to fill this div up.<br/>
			Why are you even reading this...?<br/>
			Do you seriously have nothing better to do?<br/>
			You really have to stop reading this!<br/>
			Okay, this is just getting pathetic.
		</div>
	</div>
</div>

<script>
function unfoldAccordion(accordionCount) {
	var accordionTextBlock = document.getElementsByClassName('accordionTextBlock');
	var accordionTextBlockContentBlock = document.getElementsByClassName('accordionTextBlockContentBlock');
	var accordionTextBlockContent = document.getElementsByClassName('accordionTextBlockContent');
	var i = 0;
	while(accordionTextBlock[i]) {
		if(i != accordionCount) {
			accordionTextBlock[i].style.cssText = 'height: <?= $accordionTextBlockTitleHeight; ?>;';
			accordionTextBlockContentBlock[i].style.cssText = 'height: 0;opacity: 0;';
		}
		i++;
	}
	if(accordionTextBlockContentBlock[accordionCount].style.opacity == '1') {
		accordionTextBlock[accordionCount].style.cssText = 'height: <?= $accordionTextBlockTitleHeight; ?>;';
		accordionTextBlockContentBlock[accordionCount].style.cssText = 'height: 0;opacity: 0;';
	} else {
		var accordionTextBlockContentHeight = accordionTextBlockContent[accordionCount].clientHeight + Number('<?= (intval($accordionTextBlockTitleHeight) + ($accordionContentPadding * 2)); ?>');
		accordionTextBlock[accordionCount].style.cssText = 'height: '+accordionTextBlockContentHeight+';';
		accordionTextBlockContentBlock[accordionCount].style.cssText = 'height: '+accordionTextBlockContent[accordionCount].clientHeight+';opacity: 1;';
	}
}
</script>