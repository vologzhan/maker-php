<?php declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Directory;
use App\Entity\File;
use App\Entity\Project;
use App\Repository\DirectoryRepository;
use App\Repository\FileRepository;
use App\Repository\ProjectRepository;
use App\Request\Project\IndexDirectoryRequest;
use App\Response\Project\ProjectItemResponse;
use App\Serializer\ProjectSerializer;
use Doctrine\ORM\EntityManagerInterface;

final readonly class IndexDirectoryService
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private DirectoryRepository $dirRepository,
        private FileRepository $fileRepository,
        private ProjectSerializer $projectSerializer,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(IndexDirectoryRequest $request): ProjectItemResponse
    {
        $dir = $this->dirRepository->save(
            new Directory()
                ->setPath($request->path)
        );

        $project = $this->projectRepository->save(
            new Project()
                ->setDir($dir)
        );

        $this->scanDirRecursive($project, $dir);

        $this->entityManager->flush();

        return $this->projectSerializer->projectItemResponse($project);
    }

    private function scanDirRecursive(Project $project, Directory $dir): void
    {
        $items = scandir($dir->getPath());

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir->getPath() . '/' . $item;

            if (is_dir($path)) {
                $children = $this->dirRepository->save(
                    new Directory()
                        ->setParent($dir)
                        ->setPath($path)
                );

                $this->scanDirRecursive($project, $children);

                continue;
            }

            $this->fileRepository->save(
                new File()
                    ->setDirectory($dir)
                    ->setPath($path)
            );
        }
    }
}
