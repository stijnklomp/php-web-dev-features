<?php
header('Access-Control-Allow-Origin: *');
$domain = $_SERVER['HTTP_ORIGIN'];
$certifiedWebsites = array('http://127.0.0.1');
switch($domain) {
	case $certifiedWebsites[0]:
		$securityCode = 'ABCD1234';
		break;
}
if(in_array($domain, $certifiedWebsites)) {
	if((isset($_REQUEST['password']) || (isset($_REQUEST['passwordHash'])) && isset($_REQUEST['securityCode'])) && (isset($_REQUEST['userID']) || isset($_REQUEST['username']))) {
		include_once './classes/class.Database.php';
		include_once './classes/class.Misc.php';
		include_once './classes/class.User.php';
		$db = Database::getInstance();
		$user = new User();
		// Parsed values
		if(isset($_REQUEST['userID'])) {
			if(isset($_REQUEST['username'])) {
				// UserID, username and password
				$skipChk = 1;
				$userID = $_REQUEST['userID'];
				$username = $_REQUEST['username'];
				if(isset($_REQUEST['password'])) {
					$password = $_REQUEST['password'];
				} else {
					$password = $_REQUEST['passwordHash'];
				}
			} else {
				// UserID and password
				$skipChk = 2;
				$userID = $_REQUEST['userID'];
				$username = NULL;
				if(isset($_REQUEST['password'])) {
					$password = $_REQUEST['password'];
				} else {
					$password = $_REQUEST['passwordHash'];
				}
			}
		} else {
			// Username and password
			$skipChk = 3;
			$userID = NULL;
			$username = $_REQUEST['username'];
			if(isset($_REQUEST['password'])) {
				$password = $_REQUEST['password'];
			} else {
				$password = $_REQUEST['passwordHash'];
			}
		}

		// Parsed values check
		$errChk = true;
		function errMsgCheck($errChk, $msg) {
			if(empty($errMsg)) {
				$errMsg = $msg;
			} else {
				$errMsg .= ' | '.$msg;
			}
			return $msg;
		}
		if($skipChk == 1) {
			if(empty($userID) && empty($username)) {
				$errChk = false;
				$errMsg = 'The given userID and username are empty';
			}
		}
		if($skipChk == 2) {
			if(empty($userID)) {
				$errChk = false;
				$errMsg = errMsgCheck($errChk, 'The given userID is empty');
			}
		}
		if($skipChk == 3) {
			if(empty($username)) {
				$errChk = false;
				$errMsg = errMsgCheck($errChk, 'The given username is empty');
			}
		}
		if(empty($password)) {
			$errChk = false;
			$errMsg = errMsgCheck($errChk, 'The given password is empty');
		}
		if(isset($_REQUEST['passwordHash']) && isset($_REQUEST['securityCode'])) {
			if($_REQUEST['securityCode'] != $securityCode) {
				$errChk = false;
				$errMsg = errMsgCheck($errChk, 'The given security code is incorrect');
			}
		}

		if($errChk) {
			if($skipChk != 2) {
				$parameters = array(':username'=>$username);
				$sth = $db->conn->prepare('SELECT user_ID FROM users WHERE Username = :username');
				$sth->execute($parameters);
				if($row = $sth->fetch()) {
					$userID = $row[0];
				} elseif($skipChk == 1) {
					$userID = $userID;
				} else {
					$userID = NULL;
				}
			} else {
				$userID = $userID;
			}
			if($user->getUserByID($userID)) {
				$errChk = false;
				if(isset($_REQUEST['password'])) {
					if(password_verify($password, $user->password)) {
						$errChk = true;
					}
				} else {
					$parameters = array(':userID'=>$userID,
										':password'=>$password);
					$sth = $db->conn->prepare('SELECT * FROM users WHERE user_ID = :userID AND Password = :password');
					$sth->execute($parameters);
					if($sth->fetch()) {
						$errChk = true;
					}
				}
				if($errChk) {
					// Image
					if(!empty($user->image)) {
						$user->image = 'http://website/'.$user->image;
					}
					// Return object
					$userArray = array();
					$userArray['progress'] = true;
					foreach($user as $key => $value) {
						$userArray[$key] = $value;
					}
				} else {
					$userArray = array();
					$userArray['progress'] = false;
					$userArray['error'] = 'Given information is invalid';
				}
			} else {
				$userArray = array();
				$userArray['progress'] = false;
				$userArray['error'] = 'No user data found';
			}
		} else {
			$userArray = array();
			$userArray['progress'] = false;
			$userArray['error'] = $errMsg;
		}
		unset($db, $user);
	} else {
		$userArray = array();
		$userArray['progress'] = false;
		$userArray['error'] = 'You need to give a password, a userID and/or username';
		if(isset($_REQUEST['passwordHash']) && !isset($_REQUEST['securityCode'])) {
			$userArray['error'] .= ' and a security code corresponded to your domain';
		}
	}
} else {
	$userArray = array();
	$userArray['progress'] = false;
	$userArray['error'] = 'You do not have the proper authorization to use this API';
}
echo json_encode($userArray);
?>