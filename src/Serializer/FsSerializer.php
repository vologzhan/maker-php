<?php declare(strict_types=1);

namespace App\Serializer;

use App\Dto\Php\TokenDto;
use App\Entity\Directory;
use App\Entity\File;
use App\Response\Fs\Content\FileContent;
use App\Response\Fs\Content\TokenItem;
use App\Response\Fs\Tree\DirItem;
use App\Response\Fs\Tree\FileItem;

final readonly class FsSerializer
{
    public function __construct(
        private ControllerSerializer $controllerSerializer,
    ) {}

    public function dirItem(Directory $dir): DirItem
    {
        return new DirItem(
            id: $dir->getId(),
            name: basename($dir->getPath()),
            dirs: array_map(fn (Directory $child) => $this->dirItem($child), $dir->getChildren()),
            files: array_map(fn (File $file) => $this->fileItem($file), $dir->getFiles()),
        );
    }

    public function fileItem(File $file): FileItem
    {
        return new FileItem(
            id: $file->getId(),
            name: basename($file->getPath()),
        );
    }

    /**
     * @param TokenDto[] $tokens
     */
    public function fileContent(File $file, array $tokens): FileContent
    {
        return new FileContent(
            tokens: array_map(fn (TokenDto $token) => $this->tokenItem($token), $tokens),
            controller: $this->controllerSerializer->controllerItem($file->getController()),
        );
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
