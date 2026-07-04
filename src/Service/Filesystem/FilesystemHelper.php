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
        if (file_exists($name)) {
            if (!$replaceIfExist) {
                throw new \Exception("File already exists: $name");
            }

            $this->delete($name);
        }

        $dir = dirname($name);

        if (!is_dir($dir)) {
            $ok = mkdir($dir, 0777, true);
            if (!$ok) {
                throw new \Exception("Unable to create directory: $dir");
            }
        }

        $ok = file_put_contents($name, $content);
        if ($ok === false) {
            throw new \Exception("Unable to write file: $name");
        }
    }

    public function createDir(string $name, bool $replaceIfExist = false): void
    {
        if (file_exists($name)) {
            if (!$replaceIfExist) {
                throw new \Exception("Directory already exists: $name");
            }

            $this->delete($name);
        }

        $ok = mkdir($name, recursive: true);
        if (!$ok) {
            throw new \Exception("Unable to create directory: $name");
        }
    }

    private function delete(string $dir): void
    {
        if (is_file($dir)) {
            unlink($dir);
            return;
        }

        if (!is_dir($dir)) {
            throw new \Exception("Unable to delete file or directory: $dir");
        }

        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            $this->delete($path);
        }

        $ok = rmdir($dir);
        if (!$ok) {
            throw new \Exception("Unable to delete directory: $dir");
        }
    }
}
