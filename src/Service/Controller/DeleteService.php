<?php declare(strict_types=1);

namespace App\Service\Controller;

use App\Repository\ControllerRepository;
use App\Repository\FileRepository;
use App\Request\Controller\DeleteRequest;
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
        $file = $this->fileRepository->find($request->fileId);

        unlink($file->getPath());

        $this->fileRepository->delete($file);
        $this->controllerRepository->delete($file->getController());
        $this->entityManager->flush();
    }
}
