<?php declare(strict_types=1);

namespace App\Service\Filesystem;

final readonly class FilesystemHelper
{
    public function joinPath(string ...$parts): string
    {
        return implode('/', $parts);
    }

    public function createFile(string $name, string $content, bool $replaceIfExist = false): void
    {
        if (!$replaceIfExist && file_exists($name)) {
            throw new \Exception("File already exists: $name");
        }

        $dir = dirname($name);
        if (!is_dir($dir)) {
            $this->createDir($dir);
        }

        $ok = file_put_contents($name, $content);
        if ($ok === false) {
            throw new \Exception("Unable to write file: $name");
        }
    }

    public function createDir(string $path): void
    {
        $ok = mkdir($path, 0777, true);
        if (!$ok) {
            throw new \Exception("Unable to create directory: $path");
        }
    }

    public function deleteDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                $this->deleteDir($path);
            } else {
                unlink($path);
            }
        }

        rmdir($dir);
    }
}
