<?php
if(isset($_POST['changePasswordInput']))
{
	if(isset($_POST['changePassword']))
	{
		if($user->changePassword($_POST['password'], $_POST['password2']))
		{
			?>
			Your password has been successfully changed.
			<script>
			setTimeout(function(){
				window.location.href = 'index.php?pageStr=login';
			}, 2000);
			</script>
			<?php
		}
		else
		{
			include 'newPasswordForm.php';
		}
	}
	else
	{
		// Delete user from password_confirm
		$db->deleteDatabase('password_confirm', 'user_ID', $user->id);

		include 'newPasswordForm.php';
	}
}
else
{
	if($user->loginCheck())
	{
		?>
		<head>
			<link rel="stylesheet" type="text/css" href="style/accConfirm.css">
		</head>
		<?php
		$sth = $db->selectDatabase('password_confirm', 'user_ID', $user->id, '');
		if($row = $sth->fetch())
		{
			if(isset($_GET['randNmb']))
			{
				$checkDate = time() - 1200; // Link will no longer be valid if insertDate is longer then 1200 sec (20 min)
				if($row['insertDate'] > $checkDate)
				{
					if($row['randNmb'] == $_GET['randNmb'])
					{
						?>
						<form action="" method="post">
							<input type="submit" name="changePasswordInput" value="Click here to change your password" title="If you click on this button this link will no longer be valid">
						</form>
						<?php
					}
					else
					{
						echo 'The link is incorrect. Please make sure you paste the full link in your URL bar.<br/>
						If this keeps occurring we suggest you press the change password button again at your <a href="index.php?pageStr=account">account</a>.';
					}
				}
				else
				{
					echo 'This link has expired and is therefor not valid anymore.<br/>
					Please go back to your <a href="index.php?pageStr=account">account</a> and press the change password button.';
				}
			}
			else
			{
				echo 'The link is incorrect. Please make sure you paste the full link in your URL bar.<br/>
				If this keeps occurring we suggest you click the change password button again at your <a href="index.php?pageStr=account">account</a>.';
			}
		}
		else
		{
			echo 'To change your password you need to go to your <a href="index.php?pageStr=account">account</a> and click the change password button.';
		}
	}
	else
	{
		echo '<script>window.location.href = "index.php?pageStr=login";</script>';
	}
}