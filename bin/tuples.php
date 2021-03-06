#!/usr/bin/env php
<?php

declare(strict_types=1);

// Autoload
require __DIR__ .'/../src/bootstrap.php';

use App\Infrastructure\Storage\Cache;
use App\Infrastructure\Storage\DB;
use App\Entity\StuffRepository;

// Init query cache
Cache::initialize();

// Init DB
$db = DB::getInstance();

// Let's create the table
$db->query(
    'CREATE TABLE stuff (id INT NOT NULL, thing VARCHAR(255) NOT NULL, PRIMARY KEY(id))',
);

// We'll insert a LOT of data first.
$amount = 2000000;
$insertQuery = "INSERT INTO stuff (id, thing) VALUES (:id, :thing)";
for ($i=1; $i <= $amount; $i++) {
    $db->query($insertQuery,
        [
            ':id' => $i,
            ':thing' => bin2hex(random_bytes(16))
        ]
    );
}

// Let's check
$repository = new StuffRepository($db);

// Since we are using yield, this doesn't break even if we have millions of rows.
// Inserting all this data was slow because it's in-memory, but retrieving it is fast.
$rowcount = 0;
foreach ($repository->oneByOne() as $stuff) {
    $rowcount++;
}

assert($rowcount === $amount, 'We were able to store and retrieve all the stuff.');

// Let's see if our query cache works
$stuff1 = $repository->load(1);
$stuff2 = $repository->load(1);

assert(reset($stuff1) === reset($stuff2), 'Data is equal.');

// Empty cache
Cache::clear();