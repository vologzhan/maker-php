<?php declare(strict_types=1);

namespace App\Service\File;

use App\Request\File\GetOneRequest;
use App\Response\File\FileResponse;
use App\Serializer\FileSerializer;
use App\Service\Php\PhpParser;

final readonly class GetOneService
{
    public function __construct(
        private PhpParser $phpParser,
        private FileSerializer $fileSerializer,
    ) {}

    public function __invoke(GetOneRequest $request): FileResponse
    {
        $file = $this->phpParser->parseFile($request->path);

        return $this->fileSerializer->fileResponse($file->tokens);
    }
}
