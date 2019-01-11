<?php
class User {
	protected $db;
	public $id, $username, $password, $email, $permission, $image, $status;

	// Db connection
	public function __construct($username = NULL) {
		$this->db 	= Database::getInstance();
		$this->misc = Misc::getInstance();
	}

	// Create user object
	public function getUserByID($ID) {
		$sth = $this->db->selectDatabase('users', 'userId', $ID);
		if($row = $sth->fetch()) {
			$this->id 				= $row['userId'];
			$this->username 	= $row['Username'];
			$this->password 	= $row['Password'];
			$this->email 			= $row['Email'];
			$this->permission = $row['Permission'];
			$img = $this->misc->findProfileImage($this->id);
			$this->image 			= $img[0];
			$this->status			= $row['Status'];
			return true;
		} else {
			return false;
		}
	}

	// Login check
	public function loginCheck() {
		if((!empty($_COOKIE['userId']) && !empty($_COOKIE['Username']) && !empty($_COOKIE['Password'])) && (empty($_SESSION['userId']) && empty($_SESSION['Username']) && empty($_SESSION['Password']))) {
			$_SESSION['userId'] = $_COOKIE['userId'];
			$_SESSION['Username'] = $_COOKIE['Username'];
			$_SESSION['Password'] = $_COOKIE['Password'];
		}
		if(isset($_SESSION['userId']) AND isset($_SESSION['Username']) AND isset($_SESSION['Password'])) {
			$parameters = array(':userID' => $_SESSION['userId'],
													':username' => $_SESSION['Username'],
													':password' => $_SESSION['Password']);
			$sth = $this->db->conn->prepare('SELECT * FROM users WHERE userId=:userID AND Username=:username AND Password=:password');
			$sth->execute($parameters);
			if($row = $sth->fetch()) {
				return true;
			} else {
				return false;
			}
		}
	}

	// Login user
	public function login($username, $password) {
		$sth = $this->db->selectDatabase('users', 'Username', $username, ' AND (Status=1 OR Status=2)');
		if($row = $sth->fetch()) {
			if(password_verify($password, $row['Password'])) {
				if($row['Status'] == 2) {
					// Session variables
					setcookie('userId', $row['userId'], time()+60*60*24*30, '/', '', FALSE, TRUE);
					setcookie('Username', $row['Username'], time()+60*60*24*30, '/', '', FALSE, TRUE);
					setcookie('Password', $row['Password'], time()+60*60*24*30, '/', '', FALSE, TRUE);
					$_SESSION['userId'] = $row['userId'];
					$_SESSION['Username'] = $row['Username'];
					$_SESSION['Password'] = $row['Password'];
					return true;
				} else {
					echo 'This account has been deactivated<br/>';
					return false;
				}
			} else {
				echo 'The username and/or password is incorrect<br/>';
				return false;
			}
		} else {
			echo 'The username and/or password is incorrect<br/>';
			return false;
		}
	}

	// Register user
	public function register($username, $password, $retypePass, $email) {
		if(isset($_POST['registerSubmit'])) {
			$sth = $this->db->selectDatabase('users', 'Username', $username);
			if(!$row = $sth->fetch()) {
				$errorCheck = true;

				// Check username
				if(strlen($username) < 6) {
					echo 'Your username must contain atleast 6 characters<br/>';
					$errorCheck = false;
				}
				if(is_numeric($username)) {
					echo 'Your username must contain letters<br/>';
					$errorCheck = false;
				}

				// Check email
				if(!empty($email)) {
					if(strlen($email) < 10) {
						echo 'Your email is incorrect<br/>';
						$errorCheck = false;
					}
				}

				// Check password
				if($password != $retypePass) {
					echo 'Your passwords do not match<br/>';
					$errorCheck = false;
				}

				if($errorCheck) {
					$arrayValues['userId'] = $this->misc->createGUID('-');
					$arrayValues['Username'] = $username;
					$arrayValues['Password'] = password_hash($password, PASSWORD_DEFAULT);
					$arrayValues['Email'] = $email;
					$this->db->insertDatabase('users', $arrayValues);
					return true;
				}
			} else {
				echo 'Username already exists<br/>';
			}
		}
	}

	// Change user
	public function update($username, $email, $file) {
		if(isset($_POST['accSubmit'])) {
			if($username == $this->username) {
				goto skip;
			}
			$sth = $this->db->selectDatabase('users', 'Username', $username);
			if(!$row = $sth->fetch()) {
				skip:
				$errorCheck = true;

				// Check username
				if(strlen($username) < 6) {
					echo 'Your username must contain atleast 6 characters<br/>';
					$errorCheck = false;
				}
				if(is_numeric($username)) {
					echo 'Your username must contain letters<br/>';
					$errorCheck = false;
				}

				// Check email
				if(!empty($email)) {
					if(strlen($email) < 10) {
						echo 'Your email is incorrect<br/>';
						$errorCheck = false;
					}
				}

				// Check image
				if(!empty($file)) {
					if($file['size'] > 10485760) {
						echo 'Your file cannot be bigger than 10 Megabytes<br/>';
						$errorCheck = false;
					} elseif($file['size'] == 0) {
						echo 'You cannot upload an empty file<br/>';
						$errorCheck = false;
					} else {
						$fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
						$acceptedFileTypes = array('png', 'jpg', 'jpeg', 'gif');
						if(!in_array($fileType, $acceptedFileTypes)) {
							echo 'You can only upload the following files: &apos;png - jpg - jpeg - gif&apos;<br/>';
							$errorCheck = false;
						}
					}
				}

				if($errorCheck) {
					// Confirm new mail
					if($email != $this->email) {
						$sth = $this->db->selectDatabase('email_confirm', 'Email', $email);
						// Put mail in db
						$randNmb = rand(100000, 99999999);
						$arrayValues = array();
						$arrayValues['userId'] = $this->id;
						$arrayValues['Email'] = $email;
						$arrayValues['randNmb'] = $randNmb;
						$arrayValues['insertDate'] = time();
						($sth->fetch())
						? $this->db->updateDatabase('email_confirm', 'userId', $this->id, $arrayValues)
						: $this->db->insertDatabase('email_confirm', $arrayValues);

						// Send mail
						$msg = 'Visit this link to confirm your mail.<br/>accountConfirm&randNmb='.$randNmb;
						$msg = wordwrap($msg, 70);
						mail($email,'Mail confirmation', $msg);
					}

					// Save image
					if(!empty($file)) {
						$img = $this->misc->findProfileImage($this->id);
						unlink($img[0]);
						($img[1] == 1)
						? $img[1] = 2
						: $img[1] = 1;
						$this->misc->saveUploadedFile($this->id.$img[1].'.'.$fileType, 'uploadFile', './images/users/');
					}

					// Save user data
					$arrayValues = array();
					$arrayValues['Username'] = $username;
					$this->db->updateDatabase('users', 'userId', $this->id, $arrayValues);

					$_SESSION['Username'] = $username;
					return true;
				}
			} else {
				echo 'Username already exists';
			}
		}
	}

	// Logout
	public function logout() {
		setcookie('userId', $this->id, time() - 3600, '/', '', FALSE, TRUE);
		setcookie('Username', $this->id, time() - 3600, '/', '', FALSE, TRUE);
		setcookie('Password', $this->id, time() - 3600, '/', '', FALSE, TRUE);
		session_destroy();
	}

	// Password confirm
	public function passConfirm() {
		$sth = $this->db->selectDatabase('password_confirm', 'userId', $this->id);
		$randNmb = rand(100000, 99999999);
		$arrayValues = array();
		$arrayValues['userId'] = $this->id;
		$arrayValues['randNmb'] = $randNmb;
		$arrayValues['insertDate'] = time();
		($sth->fetch())
		? $this->db->updateDatabase('password_confirm', 'userId', $this->id, $arrayValues)
		: $this->db->insertDatabase('password_confirm', $arrayValues);

		// Send mail
		$msg = 'Visit this link to create your new password.<br/>passwordConfirm&randNmb='.$randNmb;
		$msg = wordwrap($msg, 70);
		mail($this->email,'Password change',$msg);
	}

	// Change password
	public function changePassword($password, $passwordConfirm) {
		if($password != $passwordConfirm) {
			echo 'Your passwords do not match.<br/>';
			return false;
		} else {
			$arrayValues = array();
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			$arrayValues['Password'] = $hashedPassword;
			$this->db->updateDatabase('users', 'userId', $this->id, $arrayValues);
			return true;
		}
	}
}
?>
