<?php
// Style the notify messages //////////////////////////////////// -->
// Note that there needs to be 2 miliseconds in between the display of different messages

// Notify messages direction
// 1 = Top left
// 2 = Bottom left
// 3 = Top right
// 4 = Bottom right
$notifyMessageDirection = 1;
// Notify message display duration in miliseconds
$notifyMessageDurationOne = 1000;
// Notify message transition speed in miliseconds
$notifyMessageDurationTwo = 1000;
// Notify message visibility duration in miliseconds
$notifyMessageDurationThree = 4000;
// Notify message height in pixels
$notifyMessageHeight = 50;
// Notify message margin in pixels
$notifyMessageMargin = 5;
?>
<style>
#notifyMessagesBox{
	width: 300px;
}

.notifyMessage{
	color: #F5F5F5;
	background-color: #695C07;
	padding: 10px;
	font-size: 20px;
	border: 1px solid black;
	border-radius: 5px;
}
/* /////////////////////////////////////////////////////////////// */
#notifyMessagesBox{
	position: fixed;
	min-width: 200px;
	<?php
	switch($notifyMessageDirection) {
		case 1:
			echo 'top: 0;left: 0;';
			break;
		case 2:
			echo 'bottom: 0;left: 0;';
			break;
		case 3:
			echo 'top: 0;right: 0;';
			break;
		case 4:
			echo 'bottom: 0;right: 0;';
			break;
	}
	?>
}

.notifyMessage{
	position: relative;
	width: calc(100% - <?= ($notifyMessageMargin * 2); ?>px);
	min-width: 190px;
	height: 0;
	margin: 0;
	opacity: 0;
	overflow: hidden;
	transition: all <?= $notifyMessageDurationTwo; ?>ms, left <?= ($notifyMessageDurationTwo / 2); ?>ms;
}
</style>
<div id="notifyMessagesBox">
</div>
<script>
var notifyMessagesBoxCount = 0;
function addNotifyMessage(notifyMessagesBoxCount, Message) {
	var msgBox = document.getElementById('notifyMessagesBox');
	<?php
	if($notifyMessageDirection == 2 || $notifyMessageDirection == 4) {
		?>
		msgBox.insertAdjacentHTML("afterbegin", '<div class="notifyMessage">'+Message+'</div>');
		<?php
	} else {
		?>
		msgBox.insertAdjacentHTML("beforeend", '<div class="notifyMessage">'+Message+'</div>');
		<?php
	}
	?>
	var notifyMsg = document.getElementsByClassName('notifyMessage')[notifyMessagesBoxCount];
	setTimeout(function(){
		notifyMsg.style.cssText = 'height: <?= $notifyMessageHeight; ?>px;left: -10px;margin: <?= $notifyMessageMargin; ?>px;';
	}, 1);
	setTimeout(function(){
		notifyMsg.style.cssText = 'height: <?= $notifyMessageHeight; ?>px;left: 0;margin: <?= $notifyMessageMargin; ?>px;opacity: 1;';
	}, Number('<?= $notifyMessageDurationTwo; ?>'));
	setTimeout(function(){
		removeNotifyMessage(msgBox, notifyMsg, notifyMessagesBoxCount);
	}, Number('<?= $notifyMessageDurationThree; ?>'));
	<?php
	if($notifyMessageDirection == 2 || $notifyMessageDirection == 4) {
		?>
		setTimeout(function(){
			notifyMessagesBoxCount++;
		}, 2);
		<?php
	} else {
		?>
		notifyMessagesBoxCount++;
		<?php
	}
	?>
	return notifyMessagesBoxCount;
}

function removeNotifyMessage(msgBox, notifyMsg, notifyMessagesBoxCount) {
	notifyMsg.style.cssText = 'height: <?= $notifyMessageHeight; ?>px;left: 0;margin: <?= $notifyMessageMargin; ?>px;opacity: 0;';
	setTimeout(function(){
		notifyMsg.style.cssText = 'opacity: 0;padding: 0;border: 0;';
		setTimeout(function(){
			msgBox.removeChild(notifyMsg);
		}, Number('<?= $notifyMessageDurationOne; ?>'));
	}, Number('<?= $notifyMessageDurationOne; ?>'));
}
</script>


<script>
// Example code ///////////////////////////////////////////////// -->
notifyMessagesBoxCount = addNotifyMessage(notifyMessagesBoxCount, 'Example One');
setTimeout(function(){
	notifyMessagesBoxCount = addNotifyMessage(notifyMessagesBoxCount, 'Example Two');
}, 1200);
setTimeout(function(){
	notifyMessagesBoxCount = addNotifyMessage(notifyMessagesBoxCount, 'Example Three');
}, 4000);
// /////////////////////////////////////////////////////////////// */
</script>