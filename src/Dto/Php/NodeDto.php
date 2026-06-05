<?php declare(strict_types=1);

namespace App\Dto\Php;

final readonly class NodeDto
{
    public function __construct(
        public int $pos,
        public int $end,
        public string|array $value,
    ) {}
}
