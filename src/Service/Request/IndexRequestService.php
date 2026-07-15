<?php declare(strict_types=1);

namespace App\Service\Request;

use App\Entity\Directory;
use App\Entity\File;
use App\Entity\Project;
use App\Entity\Request;
use App\Enum\DirType;
use App\Repository\RequestRepository;
use App\Service\Php\PhpParser;

final readonly class IndexRequestService
{
    public function __construct(
        private PhpParser $phpParser,
        private RequestRepository $requestRepository,
    ) {}

    public function indexDirRecursive(Project $project, Directory $dir): void
    {
        foreach ($dir->getFiles() as $file) {
            if (basename($file->getPath()) === DirType::Request->filename()) {
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

        $request = $this->requestRepository->save(
            new Request()
                ->setClassName($class->fqn)
                ->setFile($file)
                ->setProject($project)
        );

        $project->addRequest($request);
    }
}
