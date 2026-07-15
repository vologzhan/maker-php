<?php declare(strict_types=1);

namespace App\Controller\Fs;

use App\Dto\Fs\MakerStructure;
use App\Entity\Directory;
use App\Entity\File;
use App\Entity\Project;
use App\Enum\DirType;
use App\Repository\DirectoryRepository;
use App\Repository\FileRepository;
use App\Repository\ProjectRepository;
use App\Response\Fs\Tree\DirItem;
use App\Serializer\FsSerializer;
use App\Service\Controller\IndexControllerService;
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
        private IndexControllerService $indexControllerService,
        private FsSerializer $fsSerializer,
        private EntityManagerInterface $entityManager,
        #[Autowire(env: 'PATH_APP')] private string $pathApp,
    ) {}

    public function __invoke(): DirItem
    {
        $dir = $this->dirRepository->findRootOrNull();
        if ($dir === null) {
            $dir = $this->index();
        }

        return $this->fsSerializer->dirItem($dir);
    }

    private function index(): Directory
    {
        $dir = $this->indexDir($this->pathApp, parent: null);

        $structures = $this->collectMakerStructures($dir);
        foreach ($structures as $struct) {
            $this->indexMakerStructure($struct);
        }

        $this->entityManager->flush();

        return $dir;
    }

    private function indexDir(string $dirPath, ?Directory $parent): Directory
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
                $this->indexDir($path, $dir);
                continue;
            }

            $file = $this->fileRepository->save(
                new File()
                    ->setDirectory($dir)
                    ->setPath($path)
            );
            $dir->addFile($file);
        }

        return $dir;
    }

    /**
     * @return MakerStructure[]
     */
    private function collectMakerStructures(
        Directory $dir,
        MakerStructure $struct = new MakerStructure,
    ): array {
        foreach ($dir->getFiles() as $file) {
            $filename = basename($file->getPath());

            if ($filename === DirType::Project->filename()) {
                if ($struct->project === null) {
                    $struct->project = $dir;
                    break;
                }

                $otherStructs = $this->collectMakerStructures($dir);

                return [$struct, ...$otherStructs];
            }

            if ($filename === DirType::Controller->filename()) {
                $struct->controller = $dir;
                break;
            }
        }

        foreach ($dir->getChildren() as $child) {
            $this->collectMakerStructures($child, $struct);
        }

        if ($struct->project === null) {
            return [];
        }

        return [$struct];
    }

    private function indexMakerStructure(MakerStructure $struct): void
    {
        $projectDir = $struct->project;
        $project = $this->projectRepository->save(
            new Project()
                ->setDir($projectDir)
        );
        $projectDir->setProject($project);

        $this->indexControllerService->indexDirRecursive($project, $struct->controller);
    }
}
