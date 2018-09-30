<?php
class Database
{
	private $host = 'localhost';
	private $database = 'db_name';
	private $username = 'root';
	private $pass = '';
	public $conn;

	public static function instance() {
		static $inst = null;
		if($inst === null) {
			$inst = new UserFactory();
		}
		return $inst;
	}

	private function __construct() {
		try  {
			$this->conn = new PDO('mysql:host=localhost;dbname='.$this->database, $this->username, $this->pass);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->conn->prepare('USE '.$this->database)->execute(array());
		}
		catch(PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}
	}

	private function whereQuery($arrayValues) {
		$parameters = array();
		if(!empty($arrayValues)) {
			$parameters = array();
			$query = ' WHERE ';
			foreach($arrayValues as $key => $value) {
				$parameters[$key] = $value;
				$i++;
			}
			$i = 0;
			foreach($arrayValues as $key => $value) {
				if($i != 0) {
					$query .= ' AND ';
				}
				$query .= $key.' = :'.$key;
				$i++;
			}
		}
		return $query;
	}

	// Select from database
	public function selectDatabase($tableName, $arrayValues, $addon) {
		$sth = $this->conn->prepare('SELECT * FROM '.$tableName.$this->whereQuery($arrayValues).' '.$addon);
		$sth->execute($parameters);
		return $sth;
	}

	// Insert into database
	public function insertDatabase($tableName, $arrayValues) {
		$query = 'INSERT INTO '.$tableName.' ';
		$parameters = array();
		foreach($arrayValues as $key => $value) {
			$parameters[$key] = $value;
		}
		$i = 0;
		foreach($arrayValues as $key => $value) {
			if($i == 0) {
				$query .= '(';
			}
			else {
				$query .= ', ';
			}
			$query .= $key;
			$i++;
		}
		$i = 0;
		foreach($arrayValues as $key => $value) {
			if($i == 0) {
				$query .= ') VALUES (';
			}
			else {
				$query .= ', ';
			}
			$query .= ':'.$key;
			$i++;
		}
		if(!empty($arrayValues)) {
			$query .= ')';
		}
		$sth = $this->conn->prepare($query);
		$sth->execute($parameters);
	}

	// Update database
	public function updateDatabase($tableName, $arrayValuesWhere, $arrayValuesSet, $addon) {
		$query = 'UPDATE '.$tableName.' SET ';
		$parameters = array();
		foreach($arrayValuesSet as $key => $value) {
			$parameters[$key] = $value;
		}
		$i = 0;
		foreach($arrayValuesSet as $key => $value) {
			if($i != 0) {
				$query .= ', ';
			}
			$query .= $key.' = :'.$key;
			$i++;
		}
		$sth = $this->conn->prepare($query.$this->whereQuery($arrayValuesWhere).' '.$addon);
		$sth->execute($parameters);
	}

	// Delete from database
	public function deleteDatabase($tableName, $arrayValues, $addon) {
		$sth = $this->conn->prepare('DELETE FROM '.$tableName.$this->whereQuery($arrayValues).' '.$addon);
		$sth->execute();
	}
}
?>