<?php declare(strict_types=1);

namespace App\Tests\Infrastructure;

use PHPUnit\Framework\Assert;

final readonly class Connection
{
    public function __construct(
        private \Doctrine\DBAL\Connection $connection,
    ) {}

    public function execute(string $sql): self
    {
        $this->connection->executeStatement($sql);
        return $this;
    }

    public function assertEquals(array $expected, string $sql): self
    {
        $actual = $this->connection->executeQuery($sql)->fetchAllNumeric();
        Assert::assertEquals($expected, $actual, "sql: $sql");

        return $this;
    }
}
