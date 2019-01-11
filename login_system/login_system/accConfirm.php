<?php
if($user->loginCheck()) {
	?>
	<head>
		<link rel="stylesheet" type="text/css" href="style/accConfirm.css">
	</head>
	<?php
	$sth = $db->selectDatabase('email_confirm', 'user_ID', $user->id);
	if($row = $sth->fetch()) {
		if(isset($_GET['randNmb'])) {
			$checkDate = time() - 1200; // Link will no longer be valid if insertDate is longer then 1200 sec (20 min)
			if($row['insertDate'] > $checkDate) {
				if($row['randNmb'] == $_GET['randNmb']) {
					// Delete user from email_confirm
					$db->deleteDatabase('email_confirm', 'user_ID', $user->id, '');

					// Change users email
					$arrayValues = array();
					$arrayValues['Email'] = $row['Email'];
					$db->updateDatabase('users', 'user_ID', $user->id, $arrayValues, '');

					echo '<script>window.location.href = "account";</script>';
				} else {
					?>
					The link is incorrect. Please make sure you paste the full link in your URL bar.
					<br/>If this keeps occurring we suggest you submit your email again at your <a href="account">account</a>.<br/>
					<?php
				}
			} else {
				?>
				This link has expired and is therefor not valid anymore.<br/>
				Please resubmit your new email at your <a href="account">account</a> if you still wish to change your current email.<br/>
				<?php
			}
		} else {
			?>
			The link is incorrect. Please make sure you paste the full link in your URL bar.<br/>
			If this keeps occurring we suggest you submit your email again at your <a href="account">account</a>.<br/>
			<?php
		}
	} else {
		?>
		To change your email you need to go to your <a href="account">account</a> and fill out a new email.<br/>
		<?php
	}
} else {
	echo '<script>window.location.href = home";</script>';
}
