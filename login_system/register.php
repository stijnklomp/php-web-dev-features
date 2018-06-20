<head>
	<link rel="stylesheet" type="text/css" href="style/register.css">
</head>
<h2>Registreren</h2>
<form method="POST" autocomplete="off">
	<label for="username"><b>Username</b></label>
	<input type="text" name="username" value="<?php if(isset($_POST['registerSubmit'])){echo $_POST['username'];} ?>" id="username" onkeypress="return blockSpecialChar(event)" required>

	<label for="password1"><b>Password</b></label>
	<input type="Password" name="password" value="<?php if(isset($_POST['registerSubmit'])){echo $_POST['password'];} ?>" id="password1" onkeypress="return blockSpecialChar(event)" required>

	<label for="password2"><b>Repeat password</b></label>
	<input type="Password" name="password2" value="<?php if(isset($_POST['registerSubmit'])){echo $_POST['password2'];} ?>" id="password2" onkeypress="return blockSpecialChar(event)" required>

	<label for="email"><b>E-mail</b></label>
	<input type="text" name="email" value="<?php if(isset($_POST['registerSubmit'])){echo $_POST['email'];} ?>" id="email" onkeypress="return blockSpecialChar(event)" required>

	<input type="submit" value="Register" name="registerSubmit">
</form>

<a href="index.php?pageStr=login">
	Login
</a>

<?php
// Check if all values are set
if($misc->readVar('POST','username') 
	&& $misc->readVar('POST','password')
	&& $misc->readVar('POST','password2') 
	&& $misc->readVar('POST','email'))
{
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	$password2 = htmlspecialchars($_POST['password2']);
	$email = htmlspecialchars($_POST['email']);

	if($user->register($username, $password, $password2, $email))
	{
		$user = new User($username);
		?>
		<script>
		setTimeout(function(){
			window.location.href = 'index.php';
		}, 2000);
		</script>
		<?php
	}
}
?>