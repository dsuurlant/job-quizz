<?php

declare(strict_types=1);

namespace App\Entity;

use App\Infrastructure\Storage\Cache;
use App\Infrastructure\Storage\DB;
use Generator;
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
        $query = 'SELECT * FROM stuff WHERE id =  :id';
        $params = [':id' => $id];

        if (Cache::entryExists($query, $params)) {
            return Stuff::createFromTuple(
                Cache::find($query, $params)
            );
        }

        $result = $this->db->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);
        Cache::create($query, $params, $result);

        return Stuff::createFromTuple($result);
    }

    /**
     * @return Stuff[]
     */
    public function loadAll(): array
    {
        $query = 'SELECT * FROM stuff';

        if (Cache::entryExists($query, [])) {
            $result = Cache::find($query, []);
        } else {
            $result = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
            Cache::create($query, [], $result);
        }

        return array_map([Stuff::class, 'createFromTuple'], $result);
    }

    /**
     * Also returns all stuff but without heavy memory consumption.
     * Leaving caching out of the generator for now.
     *
     * @return Generator
     */
    public function oneByOne(): Generator
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