<?php declare(strict_types=1);

namespace App\Request\Filesystem\File;

final readonly class GetContentRequest
{
    public function __construct(
        public int $id,
    ) {}
}
