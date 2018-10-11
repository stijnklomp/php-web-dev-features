<?php
if($user->loginCheck())
{
	$check = false;

	// Logout
	if(isset($_POST['logout']))
	{
		$user->logout();
		echo '<script>window.location.href = "login";</script>';
	}

	// Change user information
	if(isset($_POST['username']) && isset($_POST['email']))
	{
		$username = $_POST['username'];
		$email = $_POST['email'];

		if($user->update($username, $email))
		{
			?>
			<form action="" method="POST" id="chgAccConfirmedForm" style="display: none !important;">
				<input type="hidden" name="chgAccConfirmed" value="true">
				<?php
				if($email != $user->email)
				{
					?>
					<input type="hidden" name="email" value="true">
					<?php
				}
				?>
			</form>
			<script>
			document.getElementById('chgAccConfirmedForm').submit();
			</script>
			<?php
		}
	}
	if(isset($_POST['chgAccConfirmed']))
	{
		if(isset($_POST['email']))
		{
			echo 'An email has been sent to the filled out email.<br/>Please confirm your email by clicking on the contained link.<br/><br/>';
		}
		?>
		Your account has been successfully updated
		<script>
		setTimeout(function(){
			window.location.href = 'account';
		}, <?php if(isset($_POST['email'])){echo 3500;}else{echo 1000;} ?>);
		</script>
		<?php
		$check = true;
	}

	// Change password
	if(isset($_POST['chgPass']))
	{
		$user->passConfirm();
		?>
		<form action="" method="POST" id="chgPassConfirmedForm" style="display: none !important;">
			<input type="hidden" name="chgPassConfirmed" value="true">
		</form>
		<script>
		document.getElementById('chgPassConfirmedForm').submit();
		</script>
		<?php
	}
	if(isset($_POST['chgPassConfirmed']))
	{
		?>
		An email has been sent to the filled out email.<br/>Change your account by clicking on the contained link.
		<script>
		setTimeout(function(){
			window.location.href = 'account';
		}, 3500);
		</script>
		<?php
		$check = true;
	}
	?>
	<head>
		<link rel="stylesheet" type="text/css" href="account.css">
	</head>
	<?php
	if(!$check)
	{
		?>
		<form action="" method="POST" id="logout">
			<input type="submit" name="logout" value="Logout">
		</form>
		<form action="" method="POST" autocomplete="off">
			<input type="text" name="username" value="<?php if(!isset($_POST['accSubmit'])){echo $user->username;}else{echo $_POST['username'];} ?>" required>
			<input type="text" name="email" value="<?php if(!isset($_POST['accSubmit'])){echo $user->email;}else{echo $_POST['email'];} ?>" title="You will receive a confirmation mail if you enter a new email" required>
			<div class="displayField">
				<?php
				switch($user->permission)
				{
					case 0:
						echo 'Guest account';
						break;
					case 1:
						echo 'Regular account';
						break;
					case 2:
						echo 'Admin account';
						break;
					case 3:
						echo 'Creator account';
						break;
				}
				?>
			</div>

			<input type="submit" name="accSubmit" value="Apply all changes">
		</form>
		<form action="" method="POST">
			<input type="submit" name="chgPass" value="Change password">
		</form>
		<?php
	}
}
else
{
	echo 'You need to <a href="login">login</a> to view your account';
}
?>