<?php declare(strict_types=1);

use spriebsch\sqlite\Connection;
use spriebsch\sqlite\SqliteSchema;

final class TestSchema extends SqliteSchema
{
    protected function schemaExists(Connection $connection): bool
    {
        $result = $connection->query("SELECT sql FROM sqlite_master WHERE name='positions'");
        $row = $result->fetchArray(SQLITE3_ASSOC);

        if ($row === false) {
            return false;
        }

        return $row['sql'] !== $this->sql();
    }

    protected function createSchema(Connection $connection): void
    {
        $connection->exec($this->sql());
    }

    private function sql(): string
    {
        return 'CREATE TABLE `positions` (
            `id` INTEGER PRIMARY KEY,
            `handlerId` TEXT UNIQUE,
            `eventId` TEXT,
            `timestamp` TEXT
        );';
    }
}
