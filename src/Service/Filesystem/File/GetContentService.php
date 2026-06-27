<?php declare(strict_types=1);

namespace App\Service\Filesystem\File;

use App\Request\Filesystem\File\GetContentRequest;
use App\Response\Filesystem\File\ContentItemResponse;
use App\Serializer\FilesystemSerializer;

final readonly class GetContentService
{
    public function __construct(
        private FilesystemSerializer $fileSerializer,
        private ParsePhpFileService $parsePhpFileService,
    ) {}

    public function __invoke(GetContentRequest $request): ContentItemResponse
    {
        $file = $this->parsePhpFileService->__invoke($request->id);

        return $this->fileSerializer->contentItemResponse($file->tokens);
    }
}
