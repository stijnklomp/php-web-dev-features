<head>
	<link rel="stylesheet" type="text/css" href="style/login.css">
</head>
<h2>Inloggen</h2>
<?php
if($misc->readVar('POST','username') && $misc->readVar('POST','password'))
{
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	if(isset($_POST['loginCookies']))
	{
		if($_POST['loginCookies'])
		{
			$cookies = true;
		}
		else
		{
			$cookies = false;
		}
	}
	else
	{
		$cookies = false;
	}
	$user = new User();

	if($user->login($username, $password, $cookies))
	{
		echo '<script>window.location.href = "index.php?pageStr=account";</script>';
	}
}
if($user->loginCheck())
{
	echo 'U are already logged in';
}
else
{
	?>
	<form method="POST">
		<label for="username"><b>Username</b></label>
		<input name="username" type="text" id="username" onkeypress="return blockSpecialChar(event)" required>

		<label for="password"><b>Password</b></label>
		<input name="password" type="Password" id="password" onkeypress="return blockSpecialChar(event)" required>

		<label for="loginCookies"><b>Keep me logged in</b></label>
		<input type="checkbox" name="loginCookies" value="true" id="loginCookies">

		<input type="submit" value="Login" name="loginSubmit">
	</form>

	<a href="index.php?pageStr=register">
		Register
	</a>
	<?php
}
?>