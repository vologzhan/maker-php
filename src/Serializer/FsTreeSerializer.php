<?php declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Dir;
use App\Entity\File;
use App\Response\Fs\DirItem;
use App\Response\Fs\FileItem;
use App\Response\Fs\TreeResponse;

final readonly class FsTreeSerializer
{
    /**
     * @param Dir[] $dirs
     */
    public function treeResponse(array $dirs): TreeResponse
    {
        return new TreeResponse(
            dirs: $this->dirItemArray($dirs),
        );
    }

    private function dirItem(Dir $dir): DirItem
    {
        return new DirItem(
            id: $dir->getId(),
            name: basename($dir->getPath()),
            dirs: $this->dirItemArray($dir->getChildren()),
            files: $this->fileItemArray($dir->getFiles()),
        );
    }

    /**
     * @param Dir[] $dirs
     * @return DirItem[]
     */
    private function dirItemArray(array $dirs): array
    {
        $out = [];
        foreach ($dirs as $dir) {
            $out[] = $this->dirItem($dir);
        }
        return $out;
    }

    private function fileItem(File $file): FileItem
    {
        return new FileItem(
            id: $file->getId(),
            name: basename($file->getPath()),
        );
    }

    /**
     * @param File[] $files
     * @return FileItem[]
     */
    private function fileItemArray(array $files): array
    {
        $out = [];
        foreach ($files as $file) {
            $out[] = $this->fileItem($file);
        }
        return $out;
    }
}
