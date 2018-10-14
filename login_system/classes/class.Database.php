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
				$query .= '`'.$key.'` = :'.$key;
				$i++;
			}
		}
		return $query;
	}

	private function parametersValues($arrayValues, $parameters = array()) {
		if(!empty($arrayValues)) {
			foreach($arrayValues as $key => $value) {
				$parameters[':'.$key] = $value;
			}
		}
		return $parameters;
	}

	private function querySetup($arrayValues, $whereValue, $query) {
		if(is_array($arrayValues)) {
			$parameters = $this->parametersValues($arrayValues);
			$query .= $this->whereQuery($arrayValues);
		} else {
			$parameters = array(':'.$arrayValues => $whereValue);
			$query .= ' WHERE `'.$arrayValues.'`=:'.$arrayValues;
		}
		return array($parameters, $query);
	}

	// Select from database
	public function selectDatabase($tableName, $arrayValues = null, $whereValue = null, $addon = null, $count = true) {
		$parameters = $query = null;
		$query = 'SELECT ';
		if($count) {
			$query .= '*';
		} else {
			$query .= 'COUNT(*)';
		}
		$query .= ' FROM '.$tableName;
		if(!empty($arrayValues)) {
			list($parameters, $query) = $this->querySetup($arrayValues, $whereValue, $query);
		}
		$sth = $this->conn->prepare($query.' '.$addon);
		$sth->execute($parameters);
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
			$query .= '`'.$key.'`';
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
	public function updateDatabase($tableName, $arrayValuesWhere, $whereValue = null, $arrayValuesSet, $addon = null) {
		$query = 'UPDATE '.$tableName.' SET';
		$i = 0;
		foreach($arrayValuesSet as $key => $value) {
			if($i != 0) {
				$query .= ',';
			}
			$query .= ' `'.$key.'` = :'.$key;
			$i++;
		}
		list($parameters, $query) = $this->querySetup($arrayValuesWhere, $whereValue, $query);
		$parameters = $this->parametersValues($arrayValuesSet, $parameters);
		$sth = $this->conn->prepare($query.' '.$addon);
		$sth->execute($parameters);
	}

	// Delete from database
	public function deleteDatabase($tableName, $arrayValues = null, $whereValue = null, $addon = null) {
		$parameters = $query = null;
		$query = 'DELETE FROM '.$tableName;
		if(!empty($arrayValues)) {
			list($parameters, $query) = $this->querySetup($arrayValues, $whereValue, $query);
		}
		$sth = $this->conn->prepare($query.' '.$addon);
		$sth->execute($parameters);
	}
}
?>