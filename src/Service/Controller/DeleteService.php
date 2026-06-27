<?php declare(strict_types=1);

namespace App\Service\Controller;

use App\Repository\FileRepository;
use App\Request\Controller\DeleteRequest;

final readonly class DeleteService
{
    public function __construct(
        private FileRepository $fileRepository,
    ) {}

    public function __invoke(DeleteRequest $request): void
    {
        $file = $this->fileRepository->find($request->id);

        unlink($file->getPath());

        $this->fileRepository->delete($file, true);
    }
}
