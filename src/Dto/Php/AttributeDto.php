<?php declare(strict_types=1);

namespace App\Dto\Php;

final readonly class AttributeDto
{
    public function __construct(
        public NodeDto $name,
        /** @var ArgumentDto[] */
        public array $args,
    ) {}
}
