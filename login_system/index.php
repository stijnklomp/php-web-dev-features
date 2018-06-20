<?php
session_start();
include_once 'autoloader.php';
$db = new Database();
$misc = new Misc();
$user = new User();
if($user->loginCheck())
{
	$user->getUserByID($_SESSION['user_ID']);
}
?>
<!DOCTYPE html>
<head>
	<script src="index.js"></script>
</head>
<body>
	<?php

	if($misc->readVar('GET', 'pageStr'))
	{
		$pageStr = $_GET['pageStr'];
	}
	else
	{
		$pageStr = 'home';
	}

	?>
<div id="mainContainer">
	<?php

	switch($pageStr)
	{
		case 'login':
			include 'login.php';
			break;
		case 'register':
			include 'register.php';
			break;
		case 'account':
			include 'account.php';
			break;
		case 'accountConfirm':
			include 'accConfirm.php';
			break;
		case 'passwordConfirm':
			include 'passConfirm.php';
			break;
		default:
			include 'login.php';
	}

	$username = $misc->readVar('POST','username');

	?>
</div>
</body>
</html>