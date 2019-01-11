<div id="content">Loading</div>
<script>
function checkCode(checkLogin, user = null, loadedFile = null) {
	if(checkLogin) {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange=function(){
			if(this.readyState==4 && this.status==200){
				document.getElementById('content').innerHTML = this.responseText;
			}
		}
		xmlhttp.open("POST", loadedFile);
		xmlhttp.setRequestHeader("Content-Type", "application/json");
		xmlhttp.send(JSON.stringify(user));
	} else {
		document.getElementById('content').innerHTML = '<form action="" method="POST"><input type="text" name="username" placeholder="Username"><input type="password" name="password" placeholder="Password"><input type="submit" name="login" value="Login"></form><div id="error"></div>';
		<?php if(isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) { ?>
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange=function(){
				if(this.readyState==4 && this.status==200){
					var user = JSON.parse(this.responseText);
					if(user['progress']) {
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange=function(){
							if(this.readyState==4 && this.status==200){
								window.location.href = "test.php";
							}
						}
						xmlhttp.open("POST", "JSON_decoder2.php");
						xmlhttp.setRequestHeader("Content-Type", "application/json");
						xmlhttp.send(JSON.stringify(user));
					} else {
						document.getElementById('error').innerHTML = user['error'];
					}
				}
			}
			xmlhttp.open("GET","http://website.com/vt_API_user.php?username=<?= $_POST['username']; ?>&password=<?= $_POST['password']; ?>");
			xmlhttp.send();
		<?php } ?>
	}
}

var checkLogin = false;
var username = '<?php if(isset($_COOKIE['Username'])){echo $_COOKIE['Username'];} ?>';
var password = '<?php if(isset($_COOKIE['Password'])){echo $_COOKIE['Password'];} ?>';
if(username !== '' && password !== '') {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function(){
		if(this.readyState==4 && this.status==200){
			var user = JSON.parse(this.responseText);
			if(user['progress']) {
				checkLogin = true;
			} else {
				checkLogin = false;
			}
			checkCode(checkLogin, user, 'JSON_decoder1.php');
		}
	}
	xmlhttp.open("GET","http://website.com/API_user.php?username="+username+"&passwordHash="+password+"&securityCode=A92K5FmD29dWldeup84QVZy3osPv");
	xmlhttp.send();
} else {
	checkCode(checkLogin);
}
</script>
<?php
if(isset($_POST['logout']) && isset($_POST['username']) && isset($_POST['password'])) {
	setcookie('Username', $_POST['username'], time() - 3600, '/', '', FALSE, TRUE);
	setcookie('Password', $_POST['password'], time() - 3600, '/', '', FALSE, TRUE);
	echo '<script>window.location.href = "test.php";</script>';
}
?>
