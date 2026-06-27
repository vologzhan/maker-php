<?php declare(strict_types=1);

namespace App\Response\Filesystem\File;

final readonly class ContentResponse
{
    public function __construct(
        /** @var TokenItem[] */
        public array $items,
    ) {}
}
