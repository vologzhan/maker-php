<?php declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Controller;
use App\Entity\Directory;
use App\Entity\File;
use App\Enum\FileType;
use App\Response\Project\Filesystem\DirectoryItemResponse;
use App\Response\Project\Filesystem\FileItem;

final readonly class FilesystemSerializer
{
    public function directoryItemResponse(Directory $dir): DirectoryItemResponse
    {
        $out = new DirectoryItemResponse(
            id: $dir->getId(),
            name: basename($dir->getPath()),
            directories: [],
            files: [],
        );

        $out->directories = array_map(fn (Directory $child) => $this->directoryItemResponse($child), $dir->getChildren());
        $out->files = array_map(fn (File $file) => $this->fileItem($file), $dir->getFiles());

        return $out;
    }

    public function fileItem(File $file): FileItem
    {
        return new FileItem(
            id: $file->getId(),
            name: basename($file->getPath()),
            type: match (true) {
                $file->getController() instanceof Controller => FileType::Controller,
                default => null,
            },
        );
    }
}
