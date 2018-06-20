<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
var timer;
</script>

<div id="testID" class="textSlideBlockTitle" onmouseover="animatedTextTitle(this.id, event, document.getElementById(this.id).innerHTML);" onmouseout="clearAnimatedTextTitle();" style="font-size: 22px;">
	Test Title With Alot Of Random Text
</div>

<!-- Style the title box //////////////////////////////////////// -->
<?php
// Title box font size in pixels
$animatedTextTitleFontSize = 14;
?>
<style>
#animatedTextTitleBox{
	color: #EBEBEB;
	background-color: #526075;
	position: absolute;
	display: none;
	padding-left: 5px;
	padding-right: 5px;
	font-size:<?= $animatedTextTitleFontSize; ?>px;
	text-align: center;
	border: 1px solid #263245;
	border-radius: 5px;
	box-shadow: 0 0 10px 5px #A6ABB3;
}

#animatedTextTitleBorderTriangle{
	position: absolute;
	top: -6px;
	left: calc(50% - 6px);
	border-left: 6px solid transparent;
	border-right: 6px solid transparent;
	border-bottom: 6px solid #263245;
}
</style>
<!-- //////////////////////////////////////////////////////////// -->
<div id="animatedTextTitleBox"><div id="animatedTextTitle"></div><div id="animatedTextTitleBorderTriangle"></div></div><div id="animatedTextTitleSizeCompare" style="position: absolute;visibility: hidden !important;font-size: <?= $animatedTextTitleFontSize; ?>px;"></div>
<script>
function animatedTextTitle(divID, event, titleText){
	if(document.getElementById('animatedTextTitle') && document.getElementById('animatedTextTitleSizeCompare')){
		var animatedTextTitleSizeCompare = document.getElementById('animatedTextTitleSizeCompare');
		timer = setTimeout(function(){
			animatedTextTitleSizeCompare.innerHTML = titleText;
			var animatedTextTitleX = event.clientX,
				animatedTextTitleY = event.clientY;
			animatedTextTitleY = $('#'+divID).offset().top + Number($('#'+divID).css('font-size').slice(0,-2));
			animatedTextTitleX = animatedTextTitleX - (animatedTextTitleSizeCompare.clientWidth / 2);
			if(animatedTextTitleX < 0){
				animatedTextTitleX = 0;
			}
			if(animatedTextTitleY < ($('#'+divID).offset().top + document.getElementById(divID).clientHeight)){
				animatedTextTitleY = event.clientHeight;
			}
			document.getElementById('animatedTextTitle').innerHTML = titleText;
			document.getElementById('animatedTextTitleBox').style.cssText = 'display: inline;top: '+animatedTextTitleY+'px;left: '+animatedTextTitleX+'px;';
		}, 1000);
	}
}

function clearAnimatedTextTitle(){
	if(document.getElementById('animatedTextTitleBox')){
		clearTimeout(timer);
		document.getElementById('animatedTextTitleBox').style.cssText = 'display: none;';
	}
}
</script>