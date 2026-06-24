<?php declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Directory;
use App\Entity\File;
use App\Entity\Project;
use App\Enum\DirectoryType;
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
        private DirectoryRepository $directoryRepository,
        private FileRepository $fileRepository,
        private ProjectSerializer $projectSerializer,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(IndexDirectoryRequest $request): ProjectItemResponse
    {
        $project = new Project()
            ->setName(basename($request->path));
        $this->projectRepository->save($project);

        $directory = new Directory()
            ->setProject($project)
            ->setPath($request->path)
            ->setType(DirectoryType::Project);
        $this->directoryRepository->save($directory);

        $this->scanDirRecursive($project, $directory);

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
                $children = new Directory()
                    ->setProject($project)
                    ->setParent($dir)
                    ->setPath($path);
                $this->directoryRepository->save($children);

                $this->scanDirRecursive($project, $children);

                continue;
            }

            $file = new File(
                directory: $dir,
                path: $path,
            );
            $this->fileRepository->save($file);
        }
    }
}
