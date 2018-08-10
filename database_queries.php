<?php
class Database
{
	private $host = 'localhost';
	private $database = 'db_name';
	private $username = 'root';
	private $pass = '';
	public $conn;

	public static function instance()
	{
		static $inst = null;
		if($inst === null)
		{
			$inst = new UserFactory();
		}
		return $inst;
	}

	private function __construct()
	{
		try 
		{
			$this->conn = new PDO("mysql:host=localhost;dbname=db_name", $this->username, $this->pass);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->conn->prepare("USE db_name")->execute(array());
		}
		catch(PDOException $e)
		{
			echo "Connection failed: " . $e->getMessage();
		}
	}

	// Select from database
	public function selectDatabase($tableName, $whereKey, $whereValue, $addon)
	{
		$parameters = NULL;
		$query = 'SELECT * FROM '.$tableName;
		if(!empty($whereKey))
		{
			$parameters = array(':whereValue'=>$whereValue);
			$query .= ' WHERE '.$whereKey.' = :whereValue ';
		}
		$query .= $addon;
		$sth = $this->conn->prepare($query);
		$sth->execute($parameters);
		return $sth;
	}

	// Insert into database
	public function insertDatabase($tableName, $arrayValues)
	{
		$query = 'INSERT INTO '.$tableName.' ';
		$parameters = array();
		foreach($arrayValues as $key => $value)
		{
			$parameters[$key] = $value;
		}
		$i = 0;
		foreach($arrayValues as $key => $value)
		{
			if($i == 0)
			{
				$query .= '(';
			}
			else
			{
				$query .= ', ';
			}
			$query .= $key;
			$i++;
		}
		$i = 0;
		foreach($arrayValues as $key => $value)
		{
			if($i == 0)
			{
				$query .= ') VALUES (';
			}
			else
			{
				$query .= ', ';
			}
			$query .= ':'.$key;
			$i++;
		}
		if(!empty($arrayValues))
		{
			$query .= ')';
		}
		$sth = $this->conn->prepare($query);
		$sth->execute($parameters);
	}

	// Update database
	public function updateDatabase($tableName, $whereValue, $whereKey, $arrayValues, $addon)
	{
		$query = 'UPDATE '.$tableName.' SET ';
		$i = 0;
		$parameters = array();
		foreach($arrayValues as $key => $value)
		{
			$parameters[$key] = $value;
		}
		foreach($arrayValues as $key => $value)
		{
			if($i != 0)
			{
				$query .= ', ';
			}
			$query .= $key.' = :'.$key;
			$i++;
		}
		$query .= ' WHERE '.$whereValue.' = "'.$whereKey.'"'.$addon;
		$sth = $this->conn->prepare($query);
		$sth->execute($parameters);
	}

	// Delete from database
	public function deleteDatabase($tableName, $whereValue, $whereKey, $addon)
	{
		$query = 'DELETE FROM '.$tableName;
		if(!empty($whereValue))
		{
			$query .= ' WHERE '.$whereValue.' = "'.$whereKey.'"';
		}
		$query .= $addon;
		$sth = $this->conn->prepare($query);
		$sth->execute();
	}
}
?>