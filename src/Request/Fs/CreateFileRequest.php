<?php declare(strict_types=1);

namespace App\Request\Fs;

use App\Enum\FileType;

final class CreateFileRequest
{
    public function __construct(
        public int $directoryId,
        public string $name,
        public ?FileType $type,
    ) {}
}
