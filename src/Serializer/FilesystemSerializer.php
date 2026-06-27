<?php declare(strict_types=1);

namespace App\Serializer;

use App\Dto\Php\TokenDto;
use App\Entity\Controller;
use App\Entity\Directory;
use App\Entity\File;
use App\Enum\FileType;
use App\Response\Filesystem\File\ContentItemResponse;
use App\Response\Filesystem\File\TokenItem;
use App\Response\Project\Filesystem\DirectoryItemResponse;
use App\Response\Project\Filesystem\FileItem;

final readonly class FilesystemSerializer
{
    public function directoryItemResponse(Directory $dir): DirectoryItemResponse
    {
        $out = new DirectoryItemResponse(
            id: $dir->getId(),
            name: basename($dir->getPath()),
            type: $dir->getType(),
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
            type: match (true) {
                $file->getController() instanceof Controller => FileType::Controller,
                default => null,
            },
        );
    }

    /**
     * @param TokenDto[] $tokens
     */
    public function contentItemResponse(array $tokens): ContentItemResponse
    {
        return new ContentItemResponse(
            items: $this->tokenItemArray($tokens),
        );
    }

    /**
     * @param TokenDto[] $tokens
     * @return TokenItem[]
     */
    public function tokenItemArray(array $tokens): array
    {
        return array_map(fn(TokenDto $token) => $this->tokenItem($token), $tokens);
    }

    private function tokenItem(TokenDto $token): TokenItem
    {
        return new TokenItem(
            pos: $token->pos,
            end: $token->end,
            value: $token->value,
            type: $token->type,
        );
    }
}
