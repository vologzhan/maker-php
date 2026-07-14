<?php declare(strict_types=1);

namespace App\Tests\Infrastructure;

use App\Service\Filesystem\FilesystemHelper;
use PHPUnit\Framework\Assert;

final readonly class Filesystem
{
    public function __construct(
        private FilesystemHelper $filesystemHelper,
    ) {}

    public function createFile(string $filepath, string $content = ''): Filesystem
    {
        $this->filesystemHelper->createFile($filepath, $content, replaceIfExist: true);
        return $this;
    }

    public function assertFileContentEquals(string $filepath, string $expectedContent): Filesystem
    {
        $actual = file_get_contents($filepath);
        Assert::AssertSame($expectedContent, $actual);

        return $this;
    }

    public function createDir(string $path): Filesystem
    {
        $this->filesystemHelper->createDir($path);
        return $this;
    }

    public function deleteDir(string $path): Filesystem
    {
        $this->filesystemHelper->deleteDir($path);
        return $this;
    }
}
