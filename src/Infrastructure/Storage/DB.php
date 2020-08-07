<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage;

use PDO;
use PDOStatement;

final class DB
{
	private PDO $pdo;

    private function __construct()
    {
		$this->pdo = new PDO('sqlite::memory:');
	}

	public static function getInstance(): self
    {
		static $instance = null;
		
		if (!$instance) {
			$instance = new self();
		}
		
		return $instance;
	}

	public function query(string $query, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($query);
        if (!$stmt) {
            throw new \PDOException('Unable to execute query.');
        }
        $stmt->execute($params);

        return $stmt;
	}

}
