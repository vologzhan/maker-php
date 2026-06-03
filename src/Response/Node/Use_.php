<?php declare(strict_types=1);

namespace App\Response\Node;

final readonly class Use_ implements NodeItemInterface
{
    public function __construct(
//        public string $name,
//        public string $alias,

        public int $pos,
        public int $end,
        public string $type = 'use',
    ) {}
}
