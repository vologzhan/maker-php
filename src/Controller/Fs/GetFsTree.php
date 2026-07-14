<?php declare(strict_types=1);

namespace App\Controller\Fs;

use App\Entity\Directory;
use App\Entity\File;
use App\Entity\Project;
use App\Enum\DirType;
use App\Repository\DirectoryRepository;
use App\Repository\FileRepository;
use App\Repository\ProjectRepository;
use App\Response\Fs\Tree\DirItem;
use App\Serializer\FsSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/fs', methods: ['GET'])]
final readonly class GetFsTree
{
    public function __construct(
        private DirectoryRepository $dirRepository,
        private FileRepository $fileRepository,
        private ProjectRepository $projectRepository,
        private FsSerializer $fsSerializer,
        private EntityManagerInterface $entityManager,
        #[Autowire(env: 'PATH_APP')] private string $pathApp,
    ) {}

    public function __invoke(): DirItem
    {
        $dir = $this->dirRepository->findRootOrNull();

        if ($dir === null) {
            $dir = $this->indexDirRecursive($this->pathApp, parent: null);
            $this->entityManager->flush();
        }

        return $this->fsSerializer->dirItem($dir);
    }

    private function indexDirRecursive(string $dirPath, ?Directory $parent): Directory
    {
        $dir = $this->dirRepository->save(
            new Directory()
                ->setParent($parent)
                ->setPath($dirPath)
        );
        $parent?->addChild($dir);

        $items = scandir($dirPath);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dirPath . '/' . $item;

            if (is_dir($path)) {
                $this->indexDirRecursive($path, $dir);
                continue;
            }

            $file = $this->fileRepository->save(
                new File()
                    ->setDirectory($dir)
                    ->setPath($path)
            );
            $dir->addFile($file);

            if ($item === DirType::Project->filename()) {
                $this->projectRepository->save(
                    new Project()
                        ->setDir($dir)
                );
            }
        }

        return $dir;
    }
}
