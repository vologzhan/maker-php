<?php declare(strict_types=1);

namespace App\Service\Filesystem;

use App\Request\Filesystem\GetOneRequest;
use App\Response\Filesystem\FileResponse;
use App\Serializer\FilesystemSerializer;
use App\Service\Php\PhpParser;

final readonly class GetOneService
{
    public function __construct(
        private PhpParser $phpParser,
        private FilesystemSerializer $fileSerializer,
    ) {}

    public function __invoke(GetOneRequest $request): FileResponse
    {
        $file = $this->phpParser->parseFile($request->path);

        return $this->fileSerializer->fileResponse($file->tokens);
    }
}
