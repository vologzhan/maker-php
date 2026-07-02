<?php declare(strict_types=1);

namespace App\Tests\Infrastructure;

use App\Service\Filesystem\FilesystemHelper;
use PHPUnit\Framework\Assert;

final readonly class Filesystem
{
    public function __construct(
        private FilesystemHelper $filesystemHelper,
    ) {}

    public function createFile(string $name, string $content): void
    {
        $this->filesystemHelper->createFile($name, $content, replaceIfExist: true);
    }

    public function assertFileContentEquals(string $filepath, string $expectedContent): void
    {
        $actual = file_get_contents($filepath);

        Assert::AssertSame($expectedContent, $actual);
    }
}
