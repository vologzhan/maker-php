<?php declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Controller;
use App\Entity\File;
use App\Enum\FileType;
use App\Response\Project\Filesystem\FileItem;

final readonly class FilesystemSerializer
{
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
