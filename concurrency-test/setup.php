<?php declare(strict_types = 1);

use spriebsch\sqlite\SqliteConnection;

require __DIR__ . '/../src/autoload.php';
require __DIR__ . '/TestSchema.php';

$db = SqliteConnection::from(__DIR__ . '/db.sqlite');

$schema = TestSchema::from($db);
$schema->createIfNotExists();

var_dump($db, $db->connection(), $schema);
