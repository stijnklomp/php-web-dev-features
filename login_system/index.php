<?php
session_start();
include_once 'autoloader.php';
$db = Database::getInstance();
$misc = Misc::getInstance();
$user = new User();
if($user->loginCheck()) {
	$user->getUserByID($_SESSION['user_ID']);
}

$uri = str_replace('/web_development_features/login_system/', '', $_SERVER['REQUEST_URI']);
$uri = trim($uri, '/');
if(strpos($uri, '?')) {
	$uri = substr($uri, 0, strpos($uri, '?'));
}
?>
<!DOCTYPE html>
<head>
</head>
<body>
<div id="mainContainer">
	<?php
	switch($uri) {
		case 'login':
			include 'login_system/login.php';
			break;
		case 'register':
			include 'login_system/register.php';
			break;
		case 'account':
			include 'login_system/account.php';
			break;
		case 'accountConfirm':
			include 'login_system/accConfirm.php';
			break;
		case 'passwordConfirm':
			include 'login_system/passConfirm.php';
			break;
		default:
			include 'login_system/login.php';
	}
	?>
</div>
</body>
</html>