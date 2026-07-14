<?php declare(strict_types=1);

namespace App\Request\Fs;

final readonly class GetFileContentRequest
{
    public function __construct(
        public int $id,
    ) {}
}
