<?php declare(strict_types=1);

namespace App\Service\Response;

use App\Entity\Directory;
use App\Entity\File;
use App\Entity\Project;
use App\Entity\Response;
use App\Enum\DirType;
use App\Repository\ResponseRepository;
use App\Service\Php\PhpParser;

final readonly class IndexResponseService
{
    public function __construct(
        private PhpParser $phpParser,
        private ResponseRepository $responseRepository,
    ) {}

    public function indexDirRecursive(Project $project, Directory $dir): void
    {
        foreach ($dir->getFiles() as $file) {
            if (basename($file->getPath()) === DirType::Response->filename()) {
                continue;
            }

            $this->indexFile($project, $file);
        }

        foreach ($dir->getChildren() as $child) {
            $this->indexDirRecursive($project, $child);
        }
    }

    public function indexFile(Project $project, File $file): void
    {
        $fileDto = $this->phpParser->parseFile($file->getPath());
        $class = $fileDto->classes[0];

        $response = $this->responseRepository->save(
            new Response()
                ->setClassName($class->fqn)
                ->setFile($file)
                ->setProject($project)
        );

        $project->addResponse($response);
    }
}
