<?php declare(strict_types=1);

namespace App\Service\Filesystem;

final readonly class FilesystemHelper
{
    public function create(string $name, string $content, bool $replaceIfExist = false): void
    {
        if (!$replaceIfExist && file_exists($name)) {
            throw new \Exception("File already exists: $name");
        }

        $dir = dirname($name);

        if (!is_dir($dir)) {
            $ok = mkdir($dir, 0777, true);
            if (!$ok) {
                throw new \Exception("Unable to create directory: $dir");
            }
        }

        $ok = file_put_contents($name, $content);
        if (!$ok) {
            throw new \Exception("Unable to write file: $name");
        }
    }
}
