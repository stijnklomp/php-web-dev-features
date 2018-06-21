<?php
session_start();
include_once 'autoloader.php';
$db = new Database();
$user = new User();
if($_REQUEST['shout'])
{
	$sth = $db->selectDatabase('shoutbox', 'post_ID', $_REQUEST['shout'], '');
	if($row = $sth->fetch())
	{
		if($user->loginCheck())
		{
			$user->getUserByID($_SESSION['user_ID']);
			if($user->id == $row['user_ID'] || $user->permission > 8)
			{
				echo $row['Text'];
			}
			else
			{
				echo 'false';
			}
		}
		else
		{
			echo 'false';
		}
	}
	else
	{
		echo 'false';
	}
}
else
{
	echo 'false';
}
?>