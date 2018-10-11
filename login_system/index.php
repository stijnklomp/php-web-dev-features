<?php
session_start();
include_once 'autoloader.php';
$db = Database::getInstance();
$misc = new Misc();
$user = new User();
if($user->loginCheck())
{
	$user->getUserByID($_SESSION['user_ID']);
}

$uri = str_replace('/web_development_features/login_system/', '', $_SERVER['REQUEST_URI']);
$uri = trim($uri, '/');
?>
<!DOCTYPE html>
<head>
	<script src="index.js"></script>
</head>
<body>
<div id="mainContainer">
	<?php
	switch($uri)
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
	?>
</div>
</body>
</html>