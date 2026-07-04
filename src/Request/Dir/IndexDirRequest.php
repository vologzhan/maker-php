<?php declare(strict_types=1);

namespace App\Request\Dir;

final readonly class IndexDirRequest
{
    public function __construct(
        public string $path,
    ) {}
}
