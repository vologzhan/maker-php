<?php declare(strict_types=1);

namespace App\Service\Filesystem\File;

use App\Repository\FileRepository;
use App\Request\Filesystem\File\GetContentRequest;
use App\Response\Filesystem\File\ContentResponse;
use App\Serializer\FilesystemSerializer;
use App\Service\Php\PhpParser;

final readonly class GetContentService
{
    public function __construct(
        private FileRepository $fileRepository,
        private PhpParser $phpParser,
        private FilesystemSerializer $fileSerializer,
    ) {}

    public function __invoke(GetContentRequest $request): ContentResponse
    {
        $file = $this->fileRepository->findById($request->id);
        $parsed = $this->phpParser->parseFile($file->getPath());

        return $this->fileSerializer->contentResponse($parsed->tokens);
    }
}
