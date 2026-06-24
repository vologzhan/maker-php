<?php declare(strict_types=1);

namespace App\Service\Project\Filesystem;

use App\Entity\Directory;
use App\Enum\DirectoryType;
use App\Enum\FileType;
use App\Repository\DirectoryRepository;
use App\Repository\FileRepository;
use App\Request\Project\Filesystem\UpdateDirectoryTypeRequest;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UpdateDirectoryTypeService
{
    public function __construct(
        private DirectoryRepository $directoryRepository,
        private FileRepository $fileRepository,
        private EntityManagerInterface $em,
    ) {}

    public function __invoke(UpdateDirectoryTypeRequest $request): void
    {
        $dir = $this->directoryRepository->findById($request->directoryId);
        
        $dirType = $request->type;
        $fileType = match ($dirType) {
            DirectoryType::Controller => FileType::Controller,
            null => null,
        };
        
        $this->recursiveUpdateType($dir, $dirType, $fileType);
        $this->em->flush();
    }
    
    private function recursiveUpdateType(Directory $dir, ?DirectoryType $dirType, ?FileType $fileType): void
    {
        $dir->setType($dirType);
        $this->directoryRepository->save($dir);

        foreach ($dir->getChildren() as $child) {
            $this->recursiveUpdateType($child, $dirType, $fileType);
        }

        foreach ($dir->getFiles() as $file) {
            $file->setType($fileType);
            $this->fileRepository->save($file);
        }
    }
}
