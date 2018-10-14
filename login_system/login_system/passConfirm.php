<?php
if($user->loginCheck()) {
	if(isset($_POST['changePasswordInput'])) {
		if(isset($_POST['changePassword'])) {
			if($user->changePassword($_POST['password'], $_POST['password2'])) {
				echo '<script>window.location.href = "login";</script>';
			} else {
				include 'newPasswordForm.php';
			}
		} else {
			// Delete user from password_confirm
			$db->deleteDatabase('password_confirm', 'user_ID', $user->id);

			include 'newPasswordForm.php';
		}
	} else {
		$sth = $db->selectDatabase('password_confirm', 'user_ID', $user->id);
		if($row = $sth->fetch()) {
			if(isset($_GET['randNmb'])) {
				$checkDate = time() - 1200; // Link will no longer be valid if insertDate is longer then 1200 sec (20 min)
				if($row['insertDate'] > $checkDate) {
					if($row['randNmb'] == $_GET['randNmb']) {
						?>
						<form action="" method="post">
							<input type="submit" name="changePasswordInput" value="Click here to change your password" title="If you click on this button this link will no longer be valid">
						</form>
						<?php
					} else {
						echo 'The link is incorrect. Please make sure you paste the full link in your URL bar.<br/>If this keeps occurring we suggest you press the change password button again at your <a href="account">account</a>.<br/>';
					}
				} else {
					echo 'This link has expired and is therefor not valid anymore.<br/>Please go back to your <a href="account">account</a> and press the change password button.<br/>';
				}
			} else {
				echo 'The link is incorrect. Please make sure you paste the full link in your URL bar.<br/>If this keeps occurring we suggest you click the change password button again at your <a href="account">account</a>.<br/>';
			}
		} else {
			echo 'To change your password you need to go to your <a href="account">account</a> and click the change password button.<br/>';
		}
	}
} else {
	echo '<script>window.location.href = "login";</script>';
}
?>