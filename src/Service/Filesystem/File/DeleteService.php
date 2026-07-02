<?php declare(strict_types=1);

namespace App\Service\Filesystem\File;

use App\Repository\ControllerRepository;
use App\Repository\FileRepository;
use App\Request\Filesystem\File\DeleteRequest;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DeleteService
{
    public function __construct(
        private FileRepository $fileRepository,
        private ControllerRepository $controllerRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(DeleteRequest $request): void
    {
        $file = $this->fileRepository->find($request->id);

        unlink($file->getPath());

        $this->fileRepository->delete($file);

        if ($file->getController()) {
            $this->controllerRepository->delete($file->getController());
        }

        $this->entityManager->flush();
    }
}
