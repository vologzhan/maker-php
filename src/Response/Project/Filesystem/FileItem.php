<?php declare(strict_types=1);

namespace App\Response\Project\Filesystem;

use App\Enum\FileType;

final readonly class FileItem
{
    public function __construct(
        public int $id,
        public string $name,
        public ?FileType $type,
    ) {}
}
