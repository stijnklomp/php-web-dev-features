<?php

class Database
{
	private $host = 'localhost';
	private $database = 'dbName';
	private $username = 'root';
	private $pass = '';
	public $conn;

	public function __construct()
	{
		try 
		{
		    $this->conn = new PDO("mysql:host=localhost;dbname=".$this->database, $this->username, $this->pass);
		    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $this->conn->prepare("USE ".$this->database)->execute(array());
		}
		catch(PDOException $e)
		{
			echo "Connection failed: " . $e->getMessage();
		}
	}

	// Select from database
	public function selectDatabase($tableName, $whereKey, $whereValue, $addon)
	{
		$query = 'SELECT * FROM '.$tableName;
		if(!empty($whereKey))
		{
			$query .= ' WHERE '.$whereKey.' = "'.$whereValue.'" ';
		}
		$query .= $addon;
		$sth = $this->conn->prepare($query);
		$sth->execute();
		return $sth;
	}

	// Insert into database
	public function insertDatabase($tableName, $arrayValues)
	{
		$query = 'INSERT INTO '.$tableName.' ';
		$i = 0;
		foreach($arrayValues as $key => $value)
		{
			if($i == 0)
			{
				$query .= '(';
			}
			if($i != 0)
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
			if($i != 0)
			{
				$query .= ', ';
			}
			$query .= '"'.$value.'"';
			$i++;
		}
		if(!empty($arrayValues))
		{
			$query .= ')';
		}
		$sth = $this->conn->prepare($query);
		$sth->execute();
	}

	// Update the database
	public function updateDatabase($tableName, $whereValue, $whereKey, $arrayValues)
	{
		$query = 'UPDATE '.$tableName.' SET ';
		$i = 0;
		foreach($arrayValues as $key => $value)
		{
			if($i != 0)
			{
				$query .= ', ';
			}
			$query .= $key.' = "'.$value.'"';
			$i++;
		}
		$query .= ' WHERE '.$whereValue.' = "'.$whereKey.'"';
		$sth = $this->conn->prepare($query);
		$sth->execute();
	}

	// Delete from database
	public function deleteDatabase($tableName, $whereValue, $whereKey)
	{
		$query = 'DELETE FROM '.$tableName;
		if(!empty($whereValue))
		{
			$query .= ' WHERE '.$whereValue.' = "'.$whereKey.'"';
		}
		$sth = $this->conn->prepare($query);
		$sth->execute();
	}
}
?>