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
		$this->db = Database::getInstance();
		$this->misc = new Misc();
	}

	// Create user object
	public function getUserByID($ID)
	{
		$arrayValues = array();
		$arrayValues['user_ID'] = $ID;
		$sth = $this->db->selectDatabase('users', $arrayValues, '');
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
	public function login($username, $password)
	{
		$arrayValues = array();
		$arrayValues['Username'] = $username;
		$sth = $this->db->selectDatabase('users', $arrayValues, ' AND (Status=1 OR Status=2)');
		if($row = $sth->fetch())
		{
			if(password_verify($password, $row['Password']))
			{
				if($row['Status'] == 2)
				{
					// Session variables
					setcookie('user_ID', $row['user_ID'], time()+60*60*24*30, '/', '', FALSE, TRUE);
					setcookie('Username', $row['Username'], time()+60*60*24*30, '/', '', FALSE, TRUE);
					setcookie('Password', $row['Password'], time()+60*60*24*30, '/', '', FALSE, TRUE);
					$_SESSION['user_ID'] = $row['user_ID'];
					$_SESSION['Username'] = $row['Username'];
					$_SESSION['Password'] = $row['Password'];
					return true;
				}
				else
				{
					echo 'This account has been deactivated';
					return false;
				}				
			}
			else
			{
				echo 'The username and/or password is incorrect';
				return false;
			}
		}
		else
		{
			echo 'The username and/or password is incorrect';
			return false;
		}
	}

	// Register user
	public function register($username, $password, $retypePass, $email)
	{
		if(isset($_POST['registerSubmit']))
		{
			$arrayValues = array();
			$arrayValues['Username'] = $username;
			$sth = $this->db->selectDatabase('users', $arrayValues, '');
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
			$arrayValues = array();
			$arrayValues['Username'] = $username;
			$sth = $this->db->selectDatabase('users', $arrayValues, '');
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
						$arrayValues = array();
						$arrayValues['email'] = $email;
						$sth = $this->db->selectDatabase('email_confirm', $arrayValues, '');
						// Put mail in db
						$randNmb = rand(100000, 99999999);
						$arrayValues = array();
						$arrayValues['user_ID'] = $this->id;
						$arrayValues['email'] = $email;
						$arrayValues['randNmb'] = $randNmb;
						$arrayValues['insertDate'] = time();
						if($sth->fetch())
						{
							$arrayValuesWhere = array();
							$arrayValuesWhere['user_ID'] = $this->id;
							$this->db->updateDatabase('email_confirm', $arrayValuesWhere, $arrayValues, '');
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
					$arrayValuesWhere = array();
					$arrayValuesWhere['user_ID'] = $this->id;
					$arrayValuesSet = array();
					$arrayValuesSet['Username'] = $username;
					$this->db->updateDatabase('users', $arrayValuesWhere, $arrayValuesSet, '');

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
		setcookie('user_ID', $this->id, time() - 3600, '/', '', FALSE, TRUE);
		setcookie('Username', $this->id, time() - 3600, '/', '', FALSE, TRUE);
		setcookie('Password', $this->id, time() - 3600, '/', '', FALSE, TRUE);
		session_destroy();
	}

	// Password confirm
	public function passConfirm()
	{
		$arrayValues = array();
		$arrayValues['user_ID'] = $this->id;
		$sth = $this->db->selectDatabase('password_confirm', $arrayValues, '');
		$randNmb = rand(100000, 99999999);
		$arrayValues = array();
		$arrayValues['user_ID'] = $this->id;
		$arrayValues['randNmb'] = $randNmb;
		$arrayValues['insertDate'] = time();
		if($sth->fetch())
		{
			$arrayValuesWhere = array();
			$arrayValuesWhere['user_ID'] = $this->id;
			$this->db->updateDatabase('password_confirm', $arrayValuesWhere, $arrayValues, '');
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
			$arrayValuesWhere = array();
			$arrayValuesWhere['user_ID'] = $this->id;
			$arrayValues = array();
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			$arrayValues['Password'] = $hashedPassword;
			$this->db->updateDatabase('users', $arrayValuesWhere, $arrayValues, '');
			return true;
		}
	}
}
?>