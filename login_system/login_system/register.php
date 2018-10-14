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

<a href="login">
	Login
</a>

<?php
// Check if all values are set
if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password2']) && isset($_POST['email'])) {
	if($user->register($_POST['username'], $_POST['password'], $_POST['password2'], $_POST['email'])) {
		echo '<script>window.location.href = "login";</script>';
	}
}
?>