<?php declare(strict_types=1);

use spriebsch\sqlite\SqliteConnection;

require __DIR__ . '/../src/autoload.php';

$db = SqliteConnection::from(__DIR__ . '/db.sqlite');

$db->exec('BEGIN TRANSACTION');

$writeStatement = $db->prepare(
    'INSERT OR REPLACE INTO positions(handlerId, eventId, timestamp) VALUES(:handlerId, :eventId, :timestamp)',
);

$writeStatement->bindValue(':handlerId', 'the-handler', SQLITE3_TEXT);
$writeStatement->bindValue(':eventId', uniqid(), SQLITE3_TEXT);
$writeStatement->bindValue(':timestamp', new DateTimeImmutable('now')->format('c.u'), SQLITE3_TEXT);

$result = $writeStatement->execute();

sleep(5);

$db->exec('END TRANSACTION');
