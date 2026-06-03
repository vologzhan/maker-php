<?php declare(strict_types=1);

namespace App\Response\Node;

final readonly class Class_ implements NodeItemInterface
{
    public function __construct(
        public int $pos,
        public int $end,
        public string $type = 'class',
    ) {}
}
