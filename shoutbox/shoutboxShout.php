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

$arrayValues = array();
if(isset($_REQUEST['shoutAdd']))
{
	$shout = $_REQUEST['shoutAdd'];
	if($shout != '')
	{
		if(strlen($shout) < 131)
		{
			if($user->loginCheck() && $user->permission > 0)
			{
				$shout = str_replace('&', '&amp;', $shout);
				$shout = str_replace('<', '&lt;', $shout);
				$sth = $db->conn->prepare('SELECT COUNT(*) FROM shoutbox');
				$sth->execute();
				$shouts = $sth->fetch();
				if(count($shouts[0]) > 19)
				{
					$db->deleteDatabase('shoutbox', '', '', ' ORDER BY createdDate ASC LIMIT 1');
				}
				$arrayValues['post_ID'] = trim($misc->getGUID(), '{}');
				$arrayValues['user_ID'] = $user->id;
				$arrayValues['Text'] = $shout;
				$arrayValues['createdDate'] = date('m-d H:i:s');
				$db->insertDatabase('shoutbox', $arrayValues);
				echo '';
			}
		}
		else
		{
			echo '';
		}
	}
}
elseif(isset($_REQUEST['shoutDel']))
{
	$sth = $db->selectDatabase('shoutbox', 'post_ID', $_REQUEST['shoutDel'], '');
	if($row = $sth->fetch())
	{
		if($user->loginCheck() && ($user->id == $row['user_ID'] || $user->permission > 7))
		{
			$arrayValues['Status'] = 2;
			$arrayValues['deletedDate'] = date('m-d H:i:s');
			$db->updateDatabase('shoutbox', 'post_ID', $_REQUEST['shoutDel'], $arrayValues, '');
		}
	}
}
elseif(isset($_REQUEST['shoutEdit']) && isset($_REQUEST['shoutText']))
{
	$sth = $db->selectDatabase('shoutbox', 'post_ID', $_REQUEST['shoutEdit'], '');
	if($row = $sth->fetch())
	{
		if($user->loginCheck() && ($user->id == $row['user_ID'] || $user->permission > 8))
		{
			if(!empty($_REQUEST['shoutText']))
			{
				$shout = str_replace('&', '&amp;', $_REQUEST['shoutText']);
				$shout = str_replace('<', '&lt;', $shout);
				$arrayValues['Text'] = $shout;
				$arrayValues['Edited'] = 2;
				$db->updateDatabase('shoutbox', 'post_ID', $row['post_ID'], $arrayValues, '');
				echo 'true';
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
?>