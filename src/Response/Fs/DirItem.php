<?php declare(strict_types=1);

namespace App\Response\Fs;

final readonly class DirItem
{
    public function __construct(
        public int $id,
        public string $name,
        /** @var DirItem[] */
        public array $dirs,
        /** @var FileItem[] */
        public array $files,
    ) {}
}
