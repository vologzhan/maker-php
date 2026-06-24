<?php declare(strict_types=1);

namespace App\Serializer;

use App\Dto\Php\TokenDto;
use App\Entity\Directory;
use App\Entity\File;
use App\Response\Filesystem\FileResponse;
use App\Response\Filesystem\TokenItem;
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

    private function fileItem(File $file): FileItem
    {
        return new FileItem(
            id: $file->getId(),
            name: basename($file->getPath()),
        );
    }

    /**
     * @param TokenDto[] $tokens
     */
    public function fileResponse(array $tokens): FileResponse
    {
        return new FileResponse(
            tokens: array_map(static fn(TokenDto $token) => new TokenItem(
                pos: $token->pos,
                end: $token->end,
                value: $token->value,
                type: $token->type,
            ), $tokens),
        );
    }
}
