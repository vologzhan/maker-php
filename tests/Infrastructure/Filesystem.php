<?php declare(strict_types=1);

namespace App\Tests\Infrastructure;

use App\Service\Filesystem\FilesystemHelper;

final readonly class Filesystem
{
    public function __construct(
        private FilesystemHelper $filesystemHelper,
    ) {}

    public function createFile(string $name, string $content): void
    {
        $this->filesystemHelper->create($name, $content, replaceIfExist: true);
    }
}
