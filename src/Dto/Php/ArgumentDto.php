<?php declare(strict_types=1);

namespace App\Dto\Php;

final readonly class ArgumentDto
{
    public function __construct(
        public ?NodeDto $name,
        public ?NodeDto $value,
    ) {}
}
