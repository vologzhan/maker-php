<?php declare(strict_types=1);

namespace App\Response\File;

final readonly class FileResponse
{
    public function __construct(
        /** @var TokenItem[] */
        public array $tokens,
    ) {}
}
