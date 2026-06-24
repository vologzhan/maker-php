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
        $result = $this->connection->executeQuery($sql);

        $columns = [];
        for ($i = 0; $i < $result->columnCount(); $i++) {
            $columns[] = $result->getColumnName($i);
        }

        $rows = $result->fetchAllNumeric();

        $actual = [$columns, ...$rows];

        Assert::assertSame($expected, $actual, "sql: $sql");

        return $this;
    }
}
