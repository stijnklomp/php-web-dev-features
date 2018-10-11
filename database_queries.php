<?php

final class Database
{
	private $host = 'localhost';
	private $database = 'test3_images';
	private $username = 'root';
	private $pass = '';
	public $conn;

	public static function getInstance()
	{
		static $inst = null;
		if($inst === null) {
			try {
				$this->conn = new PDO('mysql:host=localhost;dbname='.$this->database, $this->username, $this->pass);
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->conn->prepare('USE '.$this->database)->execute(array());
			}
			catch(PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
			}
			return $conn;
		} else {
			return $inst;
		}
	}

	private function whereQuery($arrayValues) {
		$query = '';
		if(!empty($arrayValues)) {
			$query = ' WHERE ';
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

	private function parametersValues($parameters, $arrayValues) {
		if(!empty($arrayValues)) {
			foreach($arrayValues as $key => $value) {
				$parameters[$key] = $value;
			}
		}
		return $parameters;
	}

	// Select from database
	public function selectDatabase($tableName, $arrayValues, $addon, $count = true) {
		if($count) {
			$query = '*';
		} else {
			$query = 'COUNT(*)';
		}
		$sth = $this->conn->prepare('SELECT '.$query.' FROM '.$tableName.$this->whereQuery($arrayValues).' '.$addon);
		$sth->execute($this->parametersValues(array(), $arrayValues));
		return $sth;
	}

	// Insert into database
	public function insertDatabase($tableName, $arrayValues) {
		$query = 'INSERT INTO '.$tableName.' ';
		$parameters = $this->parametersValues(array(), $arrayValues);
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
		$parameters = $this->parametersValues($arrayValuesWhere);
		$query = 'UPDATE '.$tableName.' SET';
		$i = 0;
		foreach($arrayValuesSet as $key => $value) {
			$parameters[':'.$key] = $value;
			if($i != 0) {
				$query .= ',';
			}
			$query .= ' '.$key.' = :'.$key;
			$i++;
		}
		$sth = $this->conn->prepare($query.$this->whereQuery($arrayValuesWhere).' '.$addon);
		$sth->execute($parameters);
	}

	// Delete from database
	public function deleteDatabase($tableName, $arrayValues, $addon) {
		$sth = $this->conn->prepare('DELETE FROM '.$tableName.$this->whereQuery($arrayValues).' '.$addon);
		$sth->execute($this->parametersValues(array(), $arrayValues));
	}
}
?>