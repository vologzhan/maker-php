<?php declare(strict_types=1);

namespace App\Response\Fs\Tree;

final readonly class FileItem
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
