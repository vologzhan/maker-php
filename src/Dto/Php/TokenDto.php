<?php declare(strict_types=1);

namespace App\Dto\Php;

final readonly class TokenDto
{
    public function __construct(
        public int $pos,
        public int $end,
        public string $value,
        public string $type,
    ) {}
}
