<?php
if($uri != 'accountConfirm') {
	if(isset($_POST['username']) && isset($_POST['password'])) {
		if(!$user->loginCheck()) {
			$username = $_POST['username'];
			$password = $_POST['password'];
			$user = new User();

			if($user->login($username,$password)) {
				echo '<script>window.location.href = "account";</script>';
			}
		}
	}

	// Change password
	if(isset($_POST['chgPassConfirm']) && isset($_POST['email'])) {
		$email = $_POST['email'];
		$errMessage = NULL;
		$errorCheck = true;
		// Check mail
		if(!empty($email)) {
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errMessage = 'Your email is incorrect';
				$errorCheck = false;
			}
		} else {
			$errMessage = 'You have to provide an email';
			$errorCheck = false;
		}
		if($errorCheck) {
			$sth = $db->selectDatabase('users', 'Email', $email, '');
			if($row = $sth->fetch()) {
				$sth2 = $db->selectDatabase('password_confirm', 'user_ID', $row['user_ID'], ' AND loggedIn=1');
				if($row2 = $sth2->fetch()) {
					$checkDate = time() - 1200; // Link will no longer be valid if insertDate is longer then 1200 sec (20 min)
					if($row2['insertDate'] > $checkDate) {
						$misc->posErrMsg(false);
						$errMessage = 'This user has already asked for a password change.&&&You will need to wait '.($row2['insertDate'] - $checkDate).' seconds untill you can request to change the password again.';
						$errorCheck = false;
					}
				}
			} else {
				$errMessage = 'This email is not used on any account';
				$errorCheck = false;
			}
			if($errorCheck) {
				$randNmb = rand(100000, 99999999);
				$arrayValues = array();
				$arrayValues['randNmb'] = $randNmb;
				$arrayValues['insertDate'] = time();
				$arrayValues['loggedIn'] = 2;
				$sth = $db->selectDatabase('password_confirm', 'user_ID', $row['user_ID'], '');
				if($sth->fetch()) {
					$arrayValues['Status'] = 1;
					$db->updateDatabase('password_confirm', 'user_ID', $row['user_ID'], $arrayValues, '');
				} else {
					$arrayValues['user_ID'] = $row['user_ID'];
					$db->insertDatabase('password_confirm', $arrayValues);
				}

				// Send mail
				$msg = 'Visit this link to create your new password.\n settleman.net/passwordConfirm?userID='.$row['user_ID'].'&randNmb='.$randNmb;
				$msg = wordwrap($msg,70);
				mail($email,"Vault-Tec | Password change",$msg);
				echo '<script>window.location.href = "login?passChgConfirmed=true";</script>';
				?>
				<?php
			} else {
				echo $errMessage;
			}
		} else {
			echo $errMessage;
		}
	}
}
if(!isset($_GET['passChgConfirmed'])) {
	?>
	<head>
		<link rel="stylesheet" type="text/css" href="style/login.css">
	</head>
	<?php
	if(isset($_POST['chgPass'])) {
		?>
		<a href="Login">
			Return
		</a>
		<br/><br/>Change password
		<?php
	} else {
		echo 'Login';
	}
	if(isset($_POST['chgPass'])) {
		?>
		<form action="" method="POST" autocomplete="off">
			<input type="hidden" name="chgPass" value="true">
			<input type="hidden" name="chgPassConfirm" value="true">
			<input type="email" name="email" <?php if(isset($_POST['chgPassConfirm'])){echo 'value="'.htmlspecialchars($_POST['email']).'"';} ?> placeholder="example@mail.com" required>
			<button type="submit">Submit</button>
		</form>
		<?php
	} else {
		if($user->loginCheck()) {
			?>
			<br/><br/>U are already logged in
			<br/><a href="account">
				My account
			</a>
			<?php
		} else {
			?>
			<form action="" method="POST" autocomplete="on">
				<div class="textContainer">
					<label for="username" class="textHeader"><div class="textHeaderCenter">Username</div></label>
					<input type="text" name="username" placeholder="Username" maxlength="32" required>
				</div>
				<div class="textContainer">
					<label for="password" class="textHeader"><div class="textHeaderCenter">Password</div></label>
					<input type="Password" name="password" placeholder="Password" required><br/>
				</div>

				<button type="submit" class="submitBtnOne">Login</button>
			</form>
			<?php
			if($uri != 'accountConfirm') {
				?>
				<a href="register">
					Register
				</a>
				<form action="" method="POST" id="requestPassForm">
					<input type="hidden" name="chgPass" value="true">
				</form>
				<div onclick="document.getElementById('requestPassForm').submit();">
					Forgot password
				</div>
				<?php
			}
		}
	}
} else {
	echo 'An email has been sent to the filled out email.<br/>Change your account by clicking on the contained link.<br/>';
}
?>