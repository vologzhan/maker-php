<?php declare(strict_types=1);

namespace App\Response\Project\Filesystem;

final readonly class FileItem
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
