<?php

class DB {
	
	protected $mysqli;
	
	protected function __construct () {
		$this->mysqli = new mysqli();
	}

	public static function getInstance (): self {
		static $instance = null;
		
		if (!$instance) {
			$instance = new self();
		}
		
		return $instance;
	}
	
	public function query (string $query): array {
		$tuples = [];
		
		$result = $this->mysqli->query($query);
		while ($tuple = $result->fetch_assoc()) {
			$tuples[] = $tuple;
		}
		
		return $tuples;
	}
	
}
