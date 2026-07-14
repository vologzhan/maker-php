<?php declare(strict_types=1);

namespace App\Controller\Fs;

use App\Entity\Directory;
use App\Enum\DirectoryType;
use App\Enum\FileType;
use App\Repository\DirectoryRepository;
use App\Repository\FileRepository;
use App\Request\Fs\SetDirTypeRequest;
use App\Response\SuccessResponse;
use App\Service\Controller\IndexController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path: '/api/dir/{id}/type', requirements: ['id' => Requirement::DIGITS], methods: ['POST'])]
final readonly class SetDirType
{
    public function __construct(
        private IndexController $indexController,
        private DirectoryRepository $dirRepository,
        private FileRepository $fileRepository,
        private EntityManagerInterface $em,
    ) {}

    public function __invoke(SetDirTypeRequest $request): SuccessResponse
    {
        $dir = $this->dirRepository->findById($request->id);

        $dirType = $request->type;
        $fileType = match ($dirType) {
            DirectoryType::Controller => FileType::Controller,
            DirectoryType::Project,
            null => null,
        };

        $this->recursiveUpdateType($dir, $dirType, $fileType);
        $this->em->flush();

        return new SuccessResponse();
    }

    private function recursiveUpdateType(Directory $dir, ?DirectoryType $dirType, ?FileType $fileType): void
    {
        $dir->setType($dirType);
        $this->dirRepository->save($dir);

        foreach ($dir->getChildren() as $child) {
            $this->recursiveUpdateType($child, $dirType, $fileType);
        }

        foreach ($dir->getFiles() as $file) {
            if ($dirType === DirectoryType::Controller) {
                $this->indexController->__invoke($dir->getProject(), $file);
            }
            $this->fileRepository->save($file);
        }
    }
}
