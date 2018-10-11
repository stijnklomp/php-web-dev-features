<?php
final class Database {
	private static $instance;
	private $host = 'localhost';
	private $database = 'login_system';
	private $username = 'root';
	private $pass = '';
	public $conn;

	private function __construct() {
		try {
			$this->conn = new PDO('mysql:host=localhost;dbname='.$this->database, $this->username, $this->pass);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->conn->prepare('USE '.$this->database)->execute(array());
		}
		catch(PDOException $e) {
			echo 'Connection failed: '.$e->getMessage();
		}
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new Database();
		}
		return self::$instance;
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

	private function parametersValues($arrayValues) {
		$parameters = array();
		if(!empty($arrayValues)) {
			foreach($arrayValues as $key => $value) {
				$parameters[':'.$key] = $value;
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
		$sth->execute($this->parametersValues($arrayValues));
		return $sth;
	}

	// Insert into database
	public function insertDatabase($tableName, $arrayValues) {
		$query = 'INSERT INTO '.$tableName.' ';
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
		$sth->execute($this->parametersValues($arrayValues));
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
		$sth->execute($this->parametersValues($arrayValues));
	}
}
?>