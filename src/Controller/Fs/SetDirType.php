<?php declare(strict_types=1);

namespace App\Controller\Fs;

use App\Entity\Directory;
use App\Entity\File;
use App\Entity\Project;
use App\Enum\DirType;
use App\Repository\DirectoryRepository;
use App\Repository\FileRepository;
use App\Repository\ProjectRepository;
use App\Request\Fs\SetDirTypeRequest;
use App\Response\Fs\Tree\FileItem;
use App\Serializer\FsSerializer;
use App\Service\Controller\IndexControllerService;
use App\Service\Fs\DirHelper;
use App\Service\Fs\FsHelper;
use App\Service\Response\IndexResponseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path: '/api/dir/{id}/type', requirements: ['id' => Requirement::DIGITS], methods: ['POST'])]
final readonly class SetDirType
{
    public function __construct(
        private DirectoryRepository $dirRepository,
        private FileRepository $fileRepository,
        private ProjectRepository $projectRepository,
        private FsHelper $fsHelper,
        private FsSerializer $fsSerializer,
        private IndexControllerService $indexControllerService,
        private IndexResponseService $indexResponseService,
        private DirHelper $dirHelper,
        private EntityManagerInterface $em,
    ) {}

    public function __invoke(SetDirTypeRequest $request): FileItem
    {
        $dir = $this->dirRepository->findById($request->id);

        $filepath = $dir->getPath() . '/' . $request->type->filename();
        $this->fsHelper->createFile($filepath, '');

        $file = $this->fileRepository->save(
            new File()
                ->setPath($filepath)
                ->setDirectory($dir)
        );

        match ($request->type) {
            DirType::Project => $this->setProject($dir),
            DirType::Controller => $this->setController($dir),
            DirType::Response => $this->setResponse($dir),
        };

        $this->em->flush();

        return $this->fsSerializer->fileItem($file);
    }

    private function setProject(Directory $dir): void
    {
        $this->projectRepository->save(
            new Project()
                ->setDir($dir)
        );
    }

    private function setController(Directory $dir): void
    {
        $projectDir = $this->dirHelper->getProjectDir($dir);

        $this->indexControllerService->indexDirRecursive($projectDir->getProject(), $dir);
    }

    private function setResponse(Directory $dir): void
    {
        $projectDir = $this->dirHelper->getProjectDir($dir);

        $this->indexResponseService->indexDirRecursive($projectDir->getProject(), $dir);
    }
}
