<?php declare(strict_types=1);

/*
 * This file is part of Sqlite.
 *
 * (c) Stefan Priebsch <stefan@priebsch.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spriebsch\sqlite;

/**
 * @codeCoverageIgnore
 */
abstract class SqliteSchema
{
    private Connection $connection;

    public static function from(Connection $connection): static
    {
        return new static($connection);
    }

    protected function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function createIfNotExists(): void
    {
        if ($this->schemaAlreadyExists($this->connection)) {
            return;
        }

        $database = $this->connection->database();

        if ($this->databaseDirectoryDoesNotExist($database)) {
            $this->createDatabaseDirectory($database);
        }

        $this->createSchema($this->connection);
    }

    private function schemaAlreadyExists(Connection $connection): bool
    {
        if (!$this->databaseExists($connection->database())) {
            return false;
        }

        return $this->schemaExists($connection);
    }

    private function databaseExists(string $database): bool
    {
        if ($this->isInMemoryDatabase($database)) {
            return false;
        }

        return is_file($database);
    }

    private function databaseDirectoryDoesNotExist(string $database): bool
    {
        if ($this->isInMemoryDatabase($database)) {
            return false;
        }

        return !is_dir($this->databaseDirectory($database));
    }

    private function createDatabaseDirectory(string $database): void
    {
        if ($this->isInMemoryDatabase($database)) {
            return;
        }

        mkdir($this->databaseDirectory($database), 0755, true);
    }

    private function databaseDirectory($database): string
    {
        return dirname($database);
    }

    private function isInMemoryDatabase(string $database): bool
    {
        return $database === ':memory:';
    }

    abstract protected function schemaExists(Connection $connection): bool;

    abstract protected function createSchema(Connection $connection): void;
}
