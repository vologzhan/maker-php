<?php declare(strict_types=1);

namespace App\Request\Fs;

final readonly class DeleteFileRequest
{
    public function __construct(
        public int $id,
    ) {}
}
