<?php declare(strict_types=1);

namespace App\Request\Project\Filesystem;

use App\Enum\DirectoryType;

final readonly class UpdateDirectoryTypeRequest
{
    public function __construct(
        public int $directoryId,
        public ?DirectoryType $type,
    ) {}
}
