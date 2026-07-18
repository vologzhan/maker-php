<?php declare(strict_types=1);

namespace App\Dto\Php;

final readonly class FileDto
{
    public function __construct(
        public string $path,
        /** @var ClassDto[] */
        public array $classes,
        /** @var TokenDto[] */
        public array $tokens,
        /** @var UseDto[] */
        public array $uses,
    ) {}
}
