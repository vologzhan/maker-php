<?php declare(strict_types=1);

namespace App\Dto\Php;

final readonly class AttributeDto
{
    public function __construct(
        public NodeDto $name,
        /** @var ArgumentDto[] */
        public array $args,
    ) {}

    public function oneOrNullArgument(string $name): ?ArgumentDto
    {
        return array_find($this->args, fn($arg) => $arg->name?->value === $name);
    }
}
