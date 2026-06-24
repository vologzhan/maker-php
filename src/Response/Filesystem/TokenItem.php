<?php declare(strict_types=1);

namespace App\Response\Filesystem;

readonly class TokenItem
{
    public function __construct(
        public int $pos,
        public int $end,
        public string $value,
        public string $type,
    ) {}
}
