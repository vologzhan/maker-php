<?php declare(strict_types=1);

namespace App\Response\Project\Filesystem;

final class DirItemResponse
{
    public function __construct(
        public int $id,
        public string $name,
        /** @var DirItemResponse[] */
        public array $directories,
        /** @var FileItem[] */
        public array $files,
    ) {}
}
