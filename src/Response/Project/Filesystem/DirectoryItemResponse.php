<?php declare(strict_types=1);

namespace App\Response\Project\Filesystem;

use App\Enum\DirectoryType;

final class DirectoryItemResponse
{
    public function __construct(
        public int $id,
        public string $name,
        public ?DirectoryType $type,
        /** @var DirectoryItemResponse[] */
        public array $directories,
        /** @var FileItem[] */
        public array $files,
    ) {}
}
