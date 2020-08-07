<?php

declare(strict_types=1);

namespace App\Entity;

use App\Infrastructure\Storage\DB;
use PDO;

final class StuffRepository
{
    private DB $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function load(int $id): Stuff
    {
        $result = $this->db->query(
            'SELECT * FROM stuff WHERE id = :id',
            [':id' => $id]
        );

        return Stuff::createFromTuple($result->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * @return Stuff[]
     */
    public function loadAll(): array
    {
        return array_map(
            [Stuff::class, 'createFromTuple'],
            $this->db->query("SELECT * FROM stuff")->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    /**
     * Also returns all stuff but without heavy memory consumption.
     * @return \Generator
     */
    public function oneByOne(): \Generator
    {
        $tuples = [];

        $results = $this->db->query(
            'SELECT * FROM stuff'
        );

        while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
            yield Stuff::createFromTuple($row);
        }
    }
}