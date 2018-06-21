<?php

class User
{
	protected $db;
	public $id;
	public $username;
	public $firstname;
	public $lastname;
	public $password;
	public $email;
	public $permission;

	// Db connection
	public function __construct($username = NULL)
	{
		$this->db = new Database();
		$this->misc = new Misc();
	}

	// Create user object
	public function getUserByID($ID)
	{
		$sth = $this->db->selectDatabase('users', 'user_ID', $ID, '');
		if($row = $sth->fetch())
		{
			$this->id 			= $row['user_ID'];
			$this->username 	= $row['Username'];
			$this->password 	= $row['Password'];
			$this->email 		= $row['Email'];
			$this->permission	= $row['Permission'];
			$this->status		= $row['Status'];
			return true;
		}
		else
		{
			return false;
		}
	}

	// Login check
	public function loginCheck()
	{
		if((!empty($_COOKIE['user_ID']) && !empty($_COOKIE['Username']) && !empty($_COOKIE['Password'])) && (empty($_SESSION['user_ID']) && empty($_SESSION['Username']) && empty($_SESSION['Password'])))
		{
			$_SESSION['user_ID'] = $_COOKIE['user_ID'];
			$_SESSION['Username'] = $_COOKIE['Username'];
			$_SESSION['Password'] = $_COOKIE['Password'];
		}
		if(isset($_SESSION['user_ID']) AND isset($_SESSION['Username']) AND isset($_SESSION['Password']))
		{
			$parameters = array(':userID'=>$_SESSION['user_ID'],
								':username'=>$_SESSION['Username'],
								':password'=>$_SESSION['Password']);
			$sth = $this->db->conn->prepare('SELECT * FROM users WHERE user_ID=:userID AND Username=:username AND Password=:password');
			$sth->execute($parameters);
			if($row = $sth->fetch())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	// Login user
	public function login($username, $password, $cookies)
	{
		$sth = $this->db->selectDatabase('users', 'Username', $username, '');
		if($row = $sth->fetch())
		{
			if(password_verify($password, $row['Password']))
			{
				if($row['Status'] == 2)
				{
					if($cookies)
					{
						// Session variables (30 days)
						setcookie('user_ID', $row['user_ID'], time()+60*60*24*30, '/', '', FALSE, TRUE);
						setcookie('Username', $row['Username'], time()+60*60*24*30, '/', '', FALSE, TRUE);
						setcookie('Password', $row['Password'], time()+60*60*24*30, '/', '', FALSE, TRUE);
					}
					$_SESSION['user_ID'] = $row['user_ID'];
					$_SESSION['Username'] = $row['Username'];
					$_SESSION['Password'] = $row['Password'];
					return true;
				}
				else
				{
					echo 'Your account has been deactivated';
				}				
			}
			else
			{
				echo 'Your username and/or password is incorrect';
				return false;
			}
		}
		else
		{
			echo 'Your username and/or password is incorrect';
			return false;
		}
	}

	// Register user
	public function register($username, $password, $retypePass, $email)
	{
		if(isset($_POST['registerSubmit']))
		{
			$sth = $this->db->selectDatabase('users', 'Username', $username, '');
			if(!$row = $sth->fetch())
			{
				$errorCheck = true;

				// Check username
				if(strlen($username) < 6)
				{
					echo 'Your username must contain atleast 6 characters';
					$errorCheck = false;
				}
				if(is_numeric($username))
				{
					echo 'Your username must contain letters';
					$errorCheck = false;
				}

				// Check email
				if(!empty($email))
				{
					if(strlen($email) < 10)
					{
						echo 'Your email is incorrect';
						$errorCheck = false;
					}
				}

				// Check password
				if($password != $retypePass)
				{
					echo 'Your passwords do not match';
					$errorCheck = false;
				}

				if($errorCheck)
				{
					$arrayValues['user_ID'] = trim(com_create_guid(), '{}');
					$arrayValues['Username'] = $username;
					$arrayValues['Password'] = password_hash($password, PASSWORD_DEFAULT);
					$arrayValues['Email'] = $email;
					$arrayValues['Permission'] = 1;
					$this->db->insertDatabase('users', $arrayValues);
					echo 'Your account has been successfully registered';
					return true;
				}
			}
			else
			{
				echo 'Username already exists';
			}
		}
	}

	// Change user
	public function update($username, $email)
	{
		if(isset($_POST['accSubmit']))
		{
			if($username == $this->username)
			{
				goto skip;
			}
			$sth = $this->db->selectDatabase('users', 'Username', $username, '');
			if(!$row = $sth->fetch())
			{
				skip:
				$errorCheck = true;

				// Check username
				if(strlen($username) < 6)
				{
					echo 'Your username must contain atleast 6 characters';
					$errorCheck = false;
				}
				if(is_numeric($username))
				{
					echo 'Your username must contain letters';
					$errorCheck = false;
				}

				// Check email
				if(!empty($email))
				{
					if(strlen($email) < 10)
					{
						echo 'Your email is incorrect';
						$errorCheck = false;
					}
				}

				if($errorCheck)
				{
					if($email != $this->email)
					{
						// Put mail in db
						$randNmb = rand(100000, 99999999);
						$arrayValues['user_ID'] = $this->id;
						$arrayValues['email'] = $email;
						$arrayValues['randNmb'] = $randNmb;
						$arrayValues['insertDate'] = time();
						$sth = $this->db->selectDatabase('email_confirm', 'email', $email, '');
						if($sth->fetch())
						{
							$this->db->updateDatabase('email_confirm', 'user_ID', $this->id, $arrayValues);
						}
						else
						{
							$this->db->insertDatabase('email_confirm', $arrayValues);
						}

						// Send mail
						$msg = "Visit this link to confirm your mail.\nindex.php?pageStr=accountConfirm&randNmb=".$randNmb;
						$msg = wordwrap($msg,70);
						mail($email,"Vault-Tec | Mail confirmation",$msg);
					}
					$arrayValues = array();
					$arrayValues['Username'] = $username;
					$this->db->updateDatabase('users', 'user_ID', $this->id, $arrayValues);

					$_SESSION['Username'] = $username;
					return true;
				}
			}
			else
			{
				echo 'Username already exists';
			}
		}
	}

	// Logout
	public function logout()
	{
		// Unset session var 
		$_SESSION = array();

		// Retrieve session parameters
		$params = session_get_cookie_params();

		// Delete session cookie
		setcookie(session_name(),
				'', time() - 42000,
				$params["path"],
				$params["domain"],
				$params["secure"],
				$params["httponly"]);

		// Destroy session
		session_destroy();
	}

	// Password confirm
	public function passConfirm()
	{
		$randNmb = rand(100000, 99999999);
		$arrayValues['user_ID'] = $this->id;
		$arrayValues['randNmb'] = $randNmb;
		$arrayValues['insertDate'] = time();
		$sth = $this->db->selectDatabase('password_confirm', 'user_ID', $this->id, '');
		if($sth->fetch())
		{
			$this->db->updateDatabase('password_confirm', 'user_ID', $this->id, $arrayValues);
		}
		else
		{
			$this->db->insertDatabase('password_confirm', $arrayValues);
		}

		// Send mail
		$msg = "Visit this link to create your new password.\nindex.php?pageStr=passwordConfirm&randNmb=".$randNmb;
		$msg = wordwrap($msg,70);
		mail($this->email,"Vault-Tec | Password change",$msg);
	}

	// Change password
	public function changePassword($password, $passwordConfirm)
	{
		if($password != $passwordConfirm)
		{
			echo 'Your passwords do not match.';
			return false;
		}
		else
		{
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			$arrayValues['Password'] = $hashedPassword;
			$this->db->updateDatabase('users', 'user_ID', $this->id, $arrayValues);
			return true;
		}
	}
}
?>