<head>
	<link rel="stylesheet" type="text/css" href="shoutbox.css">
</head>
<div id="shoutboxContent">
	<?php
	include 'getShouts.php';
	?>
</div>
<?php
if($user->loginCheck() && $user->permission > 0)
{
	?>
	<div id="shoutboxForm">
		<input type="text" name="shoutboxText" placeholder="Enter message here..." maxlength="130" id="shoutboxText" autocomplete="off" onkeypress="if((event.which == 13 || event.keyCode == 13)){submitShout();}">
		<div id="shoutboxStopEditing" onclick="stopEditing()" title="Stop editing">X</div>
		<input type="hidden" id="shoutboxCheck" value="false">
		<button type="submit" onclick="submitShout()" style="background-color: grey;position: relative;width: 100px;height: 30px;left: calc(100% - 100px);">Shout</button>
		<div onclick="displayShoutboxIcons()" style="position: absolute;width: 30px;height: 30px;top: 0;left: calc(100% - 132px);border: 1px solid black;">
			<i class="fa fa-smile-o" aria-hidden="true"></i>
		</div>
		<div id="shoutboxIcons" style="height: 0px;">
			<div class="shoutboxIcon" style="border-bottom-left-radius: 10px;" onclick="addIconToShoutbox('&#9786;')">
				&#9786;
			</div>
			<div class="shoutboxIcon" style="left: 32px;" onclick="addIconToShoutbox('&#9785;')">
				&#9785;
			</div>
			<div class="shoutboxIcon" style="left: 64px;" onclick="addIconToShoutbox('&#9792;')">
				&#9792;
			</div>
			<div class="shoutboxIcon" style="left: 96px;" onclick="addIconToShoutbox('&#9794;')">
				&#9794;
			</div>
			<div class="shoutboxIcon" style="left: 128px;" onclick="addIconToShoutbox('&#9734;')">
				&#9734;
			</div>
			<div class="shoutboxIcon" style="left: 160px;" onclick="addIconToShoutbox('&#9728;')">
				&#9728;
			</div>
			<div class="shoutboxIcon" style="left: 192px;" onclick="addIconToShoutbox('&#9729;')">
				&#9729;
			</div>
			<div class="shoutboxIcon" style="left: 224px;" onclick="addIconToShoutbox('&#9757;')">
				&#9757;
			</div>
			<div class="shoutboxIcon" style="left: 256px;" onclick="addIconToShoutbox('&#9762;')">
				&#9762;
			</div>
			<div class="shoutboxIcon" style="left: 288px;" onclick="addIconToShoutbox('&#9774;')">
				&#9774;
			</div>
			<div class="shoutboxIcon" style="left: 320px;" onclick="addIconToShoutbox('&#9775;')">
				&#9775;
			</div>
			<div class="shoutboxIcon" style="left: 352px;" onclick="addIconToShoutbox('&#9813;')">
				&#9813;
			</div>
			<div class="shoutboxIcon" style="left: 384px;" onclick="addIconToShoutbox('&#9825;')">
				&#9825;
			</div>
			<div class="shoutboxIcon" style="left: 416px;" onclick="addIconToShoutbox('&#9836;')">
				&#9836;
			</div>
			<div class="shoutboxIcon" style="left: 416px;" onclick="addIconToShoutbox('&#9840;')">
				&#9840;
			</div>
			<div class="shoutboxIcon" style="left: 448px;" onclick="addIconToShoutbox('&#9842;')">
				&#9842;
			</div>
			<div class="shoutboxIcon" style="left: 480px;" onclick="addIconToShoutbox('&#9854;')">
				&#9854;
			</div>
			<div class="shoutboxIcon" style="left: 512px;" onclick="addIconToShoutbox('&#9861;')">
				&#9861;
			</div>
			<div class="shoutboxIcon" style="left: 544px;" onclick="addIconToShoutbox('&#9872;')">
				&#9872;
			</div>
			<div class="shoutboxIcon" style="left: 576px;" onclick="addIconToShoutbox('&#9874;')">
				&#9874;
			</div>
			<div class="shoutboxIcon" style="left: 608px;" onclick="addIconToShoutbox('&#9885;')">
				&#9885;
			</div>
			<div class="shoutboxIcon" style="left: 640px;" onclick="addIconToShoutbox('&#9888;')">
				&#9888;
			</div>
			<div class="shoutboxIcon" style="left: 672px;" onclick="addIconToShoutbox('&#9892;')">
				&#9892;
			</div>
			<div class="shoutboxIcon" style="left: 704px;" onclick="addIconToShoutbox('&#9935;')">
				&#9935;
			</div>
			<div class="shoutboxIcon" style="left: 736px;" onclick="addIconToShoutbox('&#9936;')">
				&#9936;
			</div>
			<div class="shoutboxIcon" style="left: 768px;" onclick="addIconToShoutbox('&#9940;')">
				&#9940;
			</div>
			<div class="shoutboxIcon" style="left: 800px;" onclick="addIconToShoutbox('&#9825;')">
				❤
			</div>
		</div>
	</div>
	<script>
	function displayShoutboxIcons(){
		var shoutboxIcons = document.getElementById('shoutboxIcons');
		if(shoutboxIcons.style.height == '0px'){
			shoutboxIcons.style.cssText = 'height: 30px;';
		}
		else{
			shoutboxIcons.style.height = '0px';
		}
	}

	function addIconToShoutbox(icon){
		var shoutboxText = document.getElementById('shoutboxText');
		shoutboxText.value += icon;
	}
	</script>
	<?php

	if(isset($_POST['editPost']) && ($_POST['user_ID'] == $user->id || $user->permission > 8))
	{
		$arrayValues = array();
		$arrayValues['Text'] = $_POST['shoutboxText'];
		$arrayValues['Edited'] = 2;
		$db->updateDatabase('shoutbox', 'post_ID', $_POST['post_ID'], $arrayValues, ' AND user_ID='.$user->id);
		echo '<script>window.location.href = "forum";</script>';
	}
}
else
{
	?>
	<div id="shoutboxIcons" style="display: none !important;"></div>
	<div id="shoutboxForm" style="display: none !important;"></div>
	<style>
	#shoutboxContent{
		height: 100%;
		border-bottom: none;
	}
	</style>
	<?php
}
?>
<script>
function createNewXMLObject(){
	if(window.XMLHttpRequest){
		// code for IE7+, Firefox, Chrome, Opera, Safari
		var xmlhttp = new XMLHttpRequest();
	}
	else{
		// code for IE6, IE5
		var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlhttp;
}

xmlhttp = createNewXMLObject();
// Refresh shoutbox
function refreshShoutbox(){
	xmlhttp = createNewXMLObject();
	xmlhttp.onreadystatechange=function(){
		if(this.readyState==4 && this.status==200){
			document.getElementById('shoutboxContent').innerHTML = this.responseText;
		}
	}
	xmlhttp.open("POST","getShouts.php");
	xmlhttp.send();
}
setInterval(function(){
	xmlhttp = createNewXMLObject();
	refreshShoutbox();
}, 5000);

// Submit text to shoutbox
function submitShout(){
	var sbxTxt = document.getElementById('shoutboxText');
	if(sbxTxt.value != ''){
		xmlhttp = createNewXMLObject();
		var shoutboxCheck = document.getElementById('shoutboxCheck');
		if(shoutboxCheck.value == 'false'){
			xmlhttp.onreadystatechange=function(){
				if(this.readyState==4 && this.status==200){
					document.getElementById('shoutboxText').value = this.responseText;
					refreshShoutbox();
				}
			}
			xmlhttp.open("POST","shoutboxShout.php?shoutAdd="+sbxTxt.value.replace('&', '&amp;'));
			xmlhttp.send();
		}
		else{
			submitEditedShout(shoutboxCheck.value, sbxTxt.value);
		}
	}
}

// Delete shout from shoutbox
function deleteShout(postID, postText){
	xmlhttp = createNewXMLObject();
	xmlhttp.onreadystatechange=function(){
		if(this.readyState==4 && this.status==200){
			refreshShoutbox();
		}
	}
	xmlhttp.open("POST","shoutboxShout.php?shoutDel="+postID);
	xmlhttp.send();
}

// Edit shout from shoutbox
// Receive shout text from database
function editShout(postID){
	xmlhttp = createNewXMLObject();
	document.getElementById('shoutboxCheck').value = postID;
	xmlhttp.onreadystatechange=function(){
		if(this.readyState==4 && this.status==200){
			if(this.responseText != 'false'){
				document.getElementById('shoutboxText').value = this.responseText;
				document.getElementById('shoutboxStopEditing').style.display = 'block';
			}
			else{
				window.location.href = 'forum';
			}
		}
	}
	xmlhttp.open("POST","getShoutText.php?shout="+postID);
	xmlhttp.send();
}

// Save edited shout into database
function submitEditedShout(postID, postText){
	xmlhttp = createNewXMLObject();
	xmlhttp.onreadystatechange=function(){
		if(this.readyState==4 && this.status==200){
			if(this.responseText == 'true'){
				// Text is successfully edited
				document.getElementById('shoutboxCheck').value = 'false';
				document.getElementById('shoutboxText').value = '';
				document.getElementById('shoutboxStopEditing').style.display = 'none';
				refreshShoutbox();
			}
			else{
				// Text is unsuccessfully edited
				document.getElementById('shoutboxText').style.cssText = 'border: 2px solid #A00D21;';
			}
		}
	}
	xmlhttp.open("POST","shoutboxShout.php?shoutEdit="+postID+'&shoutText='+postText);
	xmlhttp.send();
}

// Stop editing shout
function stopEditing(){
	document.getElementById('shoutboxCheck').value = 'false';
	document.getElementById('shoutboxText').value = '';
	document.getElementById('shoutboxStopEditing').style.display = 'none';
}
</script>