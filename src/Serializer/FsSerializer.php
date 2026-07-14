<?php declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Directory;
use App\Entity\File;
use App\Response\Fs\DirItem;
use App\Response\Fs\FileItem;

final readonly class FsSerializer
{
    public function dirItem(Directory $dir): DirItem
    {
        return new DirItem(
            id: $dir->getId(),
            name: basename($dir->getPath()),
            dirs: array_map(fn (Directory $child) => $this->dirItem($child), $dir->getChildren()),
            files: array_map(fn (File $file) => $this->fileItem($file), $dir->getFiles()),
        );
    }

    private function fileItem(File $file): FileItem
    {
        return new FileItem(
            id: $file->getId(),
            name: basename($file->getPath()),
        );
    }
}
