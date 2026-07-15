<?php declare(strict_types=1);

namespace App\Controller\Fs;

use App\Repository\ControllerRepository;
use App\Repository\FileRepository;
use App\Request\Fs\DeleteFileRequest;
use App\Response\SuccessResponse;
use App\Service\Fs\FsHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path: '/api/file/{id}', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
final readonly class DeleteFile
{
    public function __construct(
        private FileRepository $fileRepository,
        private ControllerRepository $controllerRepository,
        private EntityManagerInterface $entityManager,
        private FsHelper $fsHelper,
    ) {}

    public function __invoke(DeleteFileRequest $request): SuccessResponse
    {
        $file = $this->fileRepository->find($request->id);

        $this->fsHelper->deleteFile($file->getPath());

        $this->fileRepository->delete($file);

        if ($file->getController()) {
            $this->controllerRepository->delete($file->getController());
        }

        $this->entityManager->flush();

        return new SuccessResponse();
    }
}
