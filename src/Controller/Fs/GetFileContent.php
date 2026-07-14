<?php declare(strict_types=1);

namespace App\Controller\Fs;

use App\Repository\FileRepository;
use App\Request\Fs\GetFileContentRequest;
use App\Response\Fs\Content\FileContent;
use App\Serializer\FsSerializer;
use App\Service\Php\PhpParser;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path: '/api/file/{id}', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
final readonly class GetFileContent
{
    public function __construct(
        private FileRepository $fileRepository,
        private PhpParser $phpParser,
        private FsSerializer $fsSerializer,
    ) {}

    public function __invoke(GetFileContentRequest $request): FileContent
    {
        $file = $this->fileRepository->findById($request->id);
        $content = $this->phpParser->parseFile($file->getPath());

        return $this->fsSerializer->fileContent($file, $content->tokens);
    }
}
