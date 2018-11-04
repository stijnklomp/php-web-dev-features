<?php
header('Access-Control-Allow-Origin: *');
$domain = $_SERVER['HTTP_ORIGIN'];
$certifiedWebsites = array('http://127.0.0.1');
switch($domain) {
	case $certifiedWebsites[0]:
		$securityCode = 'A92K5FmD29dWldeup84QVZy3osPv';
		break;
}
if(in_array($domain, $certifiedWebsites)) {
	include_once 'classes/class.Database.php';
	include_once 'classes/class.Misc.php';
	include_once 'classes/class.User.php';
	$db = Database::getInstance();
	$misc = Misc::getInstance();
	$user = new User();
	$userArray = array();
	if((isset($_REQUEST['password']) || (isset($_REQUEST['passwordHash'])) && isset($_REQUEST['securityCode'])) && (isset($_REQUEST['userID']) || isset($_REQUEST['username']))) {
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
				$sth = $db->selectDatabase('users', 'Username', $username);
				if($row = $sth->fetch()) {
					$userID = $row['user_ID'];
				} elseif($skipChk == 1) {
					$userID = $userID;
				} else {
					$userID = NULL;
				}
			} else {
				$userID = $userID;
			}
			if($user->getUserByID($userID)) {
				if($user->status != 0) {
					$errChk = false;
					if(isset($_REQUEST['password'])) {
						if(password_verify($password, $user->password)) {
							$errChk = true;
						}
					} else {
						$arrayValues = array();
						$arrayValues['user_ID'] = $userID;
						$arrayValues['password'] = $password;
						$sth = $db->selectDatabase('users', $arrayValues);
						if($sth->fetch()) {
							$errChk = true;
						}
					}
					if($errChk) {
						// Image
						if(!empty($user->image)) {
							$user->image = 'http://website.com/'.$user->image;
						}
						// Check if user allows the display of their email and Discord name
						if($user->emailPrivacy == 1) {
							$user->email = 'Confidential';
						}
						if($user->discordNamePrivacy == 1) {
							$user->discordName = 'Confidential';
						}
						// Change timezone to a readable string
						$timezones = array(0=>'-', 1=>'-12:00', 2=>'-11:00', 3=>'-10:00', 4=>'-09:30', 5=>'-09:00', 6=>'-08:00', 7=>'-07:00', 8=>'-06:00', 9=>'-05:00', 10=>'-04:00', 11=>'-03:30', 12=>'-03:00', 13=>'-02:00', 14=>'-01:00', 15=>'+00:00', 16=>'+01:00', 17=>'+02:00', 18=>'+03:00', 19=>'+03:30', 20=>'+04:00', 21=>'+04:30', 22=>'+05:00', 23=>'+05:30', 24=>'+05:45', 25=>'+06:00', 26=>'+06:30', 27=>'+07:00', 28=>'+08:00', 29=>'+08:45', 30=>'+09:00', 31=>'+09:30', 32=>'+10:00', 33=>'+10:30', 34=>'+11:00', 35=>'+12:00', 36=>'+12:45', 37=>'+13:00', 38=>'+14:00');
						$user->timezone = 'GTM'.$timezones[$user->timezone];
						// Remove object attributes
						unset($user->emailPrivacy);
						unset($user->discordNamePrivacy);
						// Return object
						$userArray['progress'] = true;
						foreach($user as $key => $value)
						{
							$userArray[$key] = $value;
						}
					} else {
						$userArray['progress'] = false;
						$userArray['error'] = 'Given information is invalid';
					}
				} else {
					$userArray['progress'] = false;
					$userArray['error'] = 'This account has been removed';
				}
			} else {
				$userArray['progress'] = false;
				$userArray['error'] = 'No user data found';
			}
		} else {
			$userArray['progress'] = false;
			$userArray['error'] = $errMsg;
		}
	} else {
		function switchStatementCheck($key) {
			switch($key) {
				case 0:
				case 'id':
					return 'user_ID';
					break;
				case 1:
				case 'username':
					return 'Username';
					break;
				case 2:
				case 'password':
					return 'Password';
					break;
				case 3:
				case 'Email':
					return 'Email';
					break;
				case 4:
				case 'discordname':
					return 'Discordname';
				case 5:
				case 'timezone':
					return 'Timezone';
					break;
				case 6:
				case 'permission':
					return 'Permission';
					break;
				default:
					return false;
			}
		}
		$parameters = array();
		$query = '';
		$rows = 0;
		$count = 2;
		// Add where to qeury
		for($i = 1; $i < $count; $i++) {
			if(isset($_REQUEST['whereKey'.$i]) && isset($_REQUEST['whereValue'.$i])) {
				$whereKey = switchStatementCheck($_REQUEST['whereKey'.$i]);
				if($whereKey) {
					if($i > 1) {
						$query .= ' AND `'.$whereKey.'` ';
					} else {
						$query = ' WHERE `'.$whereKey.'` ';
					}
					if(isset($_REQUEST['whereSearch'.$i])) {
						$parameters[':whereKey'.$i] = '%'.$_REQUEST['whereValue'.$i].'%';
						$query .= 'LIKE';
					} else {
						$parameters[':whereKey'.$i] = $_REQUEST['whereValue'.$i];
						$query .= '=';
					}
					$query .= ' :whereKey'.$i;
				}
				$count++;
			}
		}
		if($i > 1) {
			$query .= ' AND `Satus` <> 0';
		} else {
			$query = ' WHERE `Status` <> 0';
		}
		// Add order by to query
		if(isset($_REQUEST['orderbyKey'])) {
			$orderByKey = switchStatementCheck($_REQUEST['orderbyKey']);
			if($orderByKey) {
				$orderByValue = 'DESC';
				if(isset($_REQUEST['orderbyValue'])) {
					if($_REQUEST['orderbyValue'] == 1) {
						$orderByValue = 'DESC';
					} elseif($_REQUEST['orderbyValue'] == 2) {
						$orderByValue = 'ASC';
					}
				}
				$query .= ' ORDER BY `'.$orderByKey.'` '.$orderByValue;
			} else {
				$query = ' ORDER BY `Permission` DESC';
			}
		} else {
			$query = ' ORDER BY `Permission` DESC';
		}
		$query .= ' LIMIT 30';
		$sth = $db->conn->prepare('SELECT * FROM `users`'.$query);
		$sth->execute($parameters);
		$userArray['progress'] = true;
		while($row = $sth->fetch()) {
			$user->getUserByID($row['user_ID']);
			// Image
			$img = $misc->findProfileImage($row['user_ID']);
			if(!empty($img[0])) {
				$user->image = 'http://website.com/'.$img[0];
			} else {
				$user->image = '';
			}
			// Check if user allows the display of their email and Discord name
			if($user->emailPrivacy == 1) {
				$user->email = 'Confidential';
			}
			if($user->discordNamePrivacy == 1) {
				$user->discordName = 'Confidential';
			}
			foreach($user as $key => $value)
			{
				switch($key) {
					// Parsed object attributes
					case 'id':
					case 'username':
					case 'email':
					case 'discordName':
					case 'image':
					case 'permission':
					case 'gameName':
					case 'handleName':
					case 'ships':
						$userArray[$key][$rows] = $value;
						break;
				}
			}
			$rows++;
		}
		// Return object
		$userArray['rows'] = $rows;
		$userArray['displayedRows'] = $rows;
		$sth = $db->conn->prepare('SELECT COUNT(*) FROM `users`'.$query);
		$sth->execute($parameters);
		$row = $sth->fetch();
		$userArray['totalRows'] = $row[0];
	}
	unset($db, $user);
} else {
	$userArray['progress'] = false;
	$userArray['error'] = 'You do not have the proper authorization to use this API';
}
echo json_encode($userArray);
?>