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
    public function treeResponse(array $dirs, int $depth): TreeResponse
    {
        $dirs = $this->dirItemArray($dirs, $depth);

        return new TreeResponse(
            dirs: $dirs,
        );
    }

    private function dirItem(Dir $dir, int $depth): DirItem
    {
        return new DirItem(
            id: $dir->getId(),
            name: basename($dir->getPath()),
            dirs: $this->dirItemArray($dir->getChildren(), $depth),
            files: $this->fileItemArray($dir->getFiles()),
        );
    }

    /**
     * @param Dir[] $dirs
     * @return DirItem[]
     */
    private function dirItemArray(array $dirs, int $depth): array
    {
        $depth--;
        if ($depth < 0) {
            return [];
        }

        $out = [];
        foreach ($dirs as $dir) {
            $out[] = $this->dirItem($dir, $depth);
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
