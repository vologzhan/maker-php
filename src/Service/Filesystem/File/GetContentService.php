<?php declare(strict_types=1);

namespace App\Service\Filesystem\File;

use App\Repository\FileRepository;
use App\Request\Filesystem\File\GetContentRequest;
use App\Response\Filesystem\File\ContentItemResponse;
use App\Serializer\FilesystemSerializer;

final readonly class GetContentService
{
    public function __construct(
        private FileRepository $fileRepository,
        private FilesystemSerializer $fileSerializer,
        private ParsePhpFileService $parsePhpFileService,
    ) {}

    public function __invoke(GetContentRequest $request): ContentItemResponse
    {
        $file = $this->fileRepository->findById($request->id);
        $fileDto = $this->parsePhpFileService->__invoke($file);

        return $this->fileSerializer->contentItemResponse($fileDto->tokens);
    }
}
