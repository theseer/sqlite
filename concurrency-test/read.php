<?php declare(strict_types=1);

use spriebsch\sqlite\SqliteConnection;

require __DIR__ . '/../src/autoload.php';

$db = SqliteConnection::from(__DIR__ . '/db.sqlite');
$db->connection()->busyTimeout(5000);

$readStatement = $db->prepare(
    'SELECT handlerId,eventId,timestamp FROM positions WHERE handlerId=:handlerId',
);

$readStatement->bindValue(':handlerId', 'the-handler', SQLITE3_TEXT);

$row = $readStatement->execute()->fetchArray(SQLITE3_ASSOC);

var_dump($row);
