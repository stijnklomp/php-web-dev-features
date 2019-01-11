<?php
if($user->loginCheck()) {
	$check = true;

	// Logout
	if(isset($_POST['logout'])) {
		$user->logout();
		echo '<script>window.location.href = "login";</script>';
	}

	// Change user information
	if(isset($_POST['username']) && isset($_POST['email'])) {
		$file = (file_exists($_FILES['uploadFile']['tmp_name']) || is_uploaded_file($_FILES['uploadFile']['tmp_name']))
		? $_FILES['uploadFile']
		: null;

		// Username and email
		$username = $_POST['username'];
		$email = $_POST['email'];
		if($user->update($username, $email, $file)) {
			echo ($email != $user->email)
			? '<script>window.location.href = "account?chgAcc=true&chgMail=true";</script>'
			: '<script>window.location.href = "account?chgAcc=true";</script>';
		}
	}
	if(isset($_GET['chgAcc'])) {
		if(isset($_GET['chgMail'])) {
			echo 'An email has been sent to the filled out email.<br/>Please confirm your email by clicking on the contained link.<br/><br/>';
		}
		?>
		Your account has been successfully updated
		<script>
		setTimeout(function(){
			window.location.href = 'account';
		}, <?php if(isset($_GET['chgMail'])){echo 3500;}else{echo 1000;} ?>);
		</script>
		<?php
		$check = false;
	}

	// Change password
	if(isset($_POST['chgPass'])) {
		$user->passConfirm();
		echo '<script>window.location.href = "account?chgPass=true";</script>';
	}
	if(isset($_GET['chgPass'])) {
		?>
		An email has been sent to the filled out email.<br/>Change your account by clicking on the contained link.
		<script>
		setTimeout(function(){
			window.location.href = 'account';
		}, 3500);
		</script>
		<?php
		$check = false;
	}
	?>
	<head>
		<link rel="stylesheet" type="text/css" href="account.css">
	</head>
	<?php
	if($check) {
		?>
		<form action="" method="POST">
			<input type="submit" name="logout" value="Logout">
		</form>
		<form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
			<input type="text" name="username" value="<?php if(!isset($_POST['accSubmit'])){echo $user->username;}else{echo $_POST['username'];} ?>" required>
			<input type="text" name="email" value="<?php if(!isset($_POST['accSubmit'])){echo $user->email;}else{echo $_POST['email'];} ?>" title="You will receive a confirmation mail if you enter a new email" required>
			<div class="displayField">
				<?php
				switch($user->permission) {
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
			<input type="file" name="uploadFile" id="uploadUserImage" style="display: none;">
			<label for="uploadUserImage" id="uploadUserImageLabel">
				<div style="position: relative;width: 180px;height: 180px;border: 1px solid black;">
					<?php
					if(!empty($user->image)) {
						$imgSize = getimagesize($user->image);
						$width = $imgSize[0];
						$height = $imgSize[1];
						echo '<img src="'.$user->image.'" style="position: absolute;z-index: 1;';
						echo ($width < 150 && $height < 150)
						? 'width: '.$width.'px;height: '.$height.'px;top: calc(50% - '.($height / 2).'px);left: calc(50% - '.($width / 2).'px);">'
						: 'width: 100%;height: 100%;">';
						echo '<div class="accountImageBackground"></div>';
					} else {
						echo '<div class="accountImageBackground"><i class="fa fa-user" aria-hidden="true" id="accountImageIcon"></i></div>';
					}
					?>
				</div>
			</label>

			<input type="submit" name="accSubmit" value="Apply all changes">
		</form>
		<form action="" method="POST">
			<input type="submit" name="chgPass" value="Change password">
		</form>
		<?php
	}
} else {
	echo 'You need to <a href="login">login</a> to view your account';
}
?>
