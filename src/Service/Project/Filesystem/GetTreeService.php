<?php declare(strict_types=1);

namespace App\Service\Project\Filesystem;

use App\Repository\DirectoryRepository;
use App\Request\Project\Filesystem\GetTreeRequest;
use App\Response\Project\Filesystem\DirectoryItemResponse;
use App\Serializer\FilesystemSerializer;

final readonly class GetTreeService
{
    public function __construct(
        private DirectoryRepository $directoryRepository,
        private FilesystemSerializer $filesystemSerializer,
    ) {}

    public function __invoke(GetTreeRequest $request): DirectoryItemResponse
    {
        $dir = $this->directoryRepository->findByProjectId($request->projectId);

        return $this->filesystemSerializer->directoryItemResponse($dir);
    }
}
