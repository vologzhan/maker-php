<?php declare(strict_types=1);

namespace App\Controller\File;

use App\Request\File\GetOneRequest;
use App\Response\File\FileResponse;
use App\Serializer\FileSerializer;
use App\Service\Php\PhpParser;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/file', methods: ['GET'])]
final readonly class GetOneController
{
    public function __construct(
        private PhpParser $phpParser,
        private FileSerializer $fileSerializer,
    ) {}

    public function __invoke(
        #[MapQueryString] GetOneRequest $request,
    ): FileResponse {
        $file = $this->phpParser->parseFile($request->path);

        return $this->fileSerializer->fileResponse($file->tokens);
    }
}
