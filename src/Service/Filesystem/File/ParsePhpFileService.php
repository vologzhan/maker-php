<?php declare(strict_types=1);

namespace App\Service\Filesystem\File;

use App\Dto\Php\FileDto;
use App\Repository\FileRepository;
use App\Service\Php\PhpParser;

final readonly class ParsePhpFileService
{
    public function __construct(
        private FileRepository $fileRepository,
        private PhpParser $phpParser,
    ) {}

    public function __invoke(int $fileId): FileDto
    {
        $file = $this->fileRepository->findById($fileId);

        return $this->phpParser->parseFile($file->getPath());
    }
}
