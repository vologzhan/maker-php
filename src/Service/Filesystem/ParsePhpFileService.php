<?php declare(strict_types=1);

namespace App\Service\Filesystem;

use App\Dto\Php\FileDto;
use App\Entity\File;
use App\Service\Php\PhpParser;

final readonly class ParsePhpFileService
{
    public function __construct(
        private PhpParser $phpParser,
    ) {}

    public function __invoke(File $file): FileDto
    {
        return $this->phpParser->parseFile($file->getPath());
    }
}
