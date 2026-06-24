<?php declare(strict_types=1);

namespace App\Service\Project;

use App\Repository\DirectoryRepository;
use App\Request\Project\GetFilesystemRequest;
use App\Response\Project\Filesystem\DirItemResponse;
use App\Serializer\FilesystemSerializer;

final readonly class GetFilesystemService
{
    public function __construct(
        private DirectoryRepository $directoryRepository,
        private FilesystemSerializer $filesystemSerializer,
    ) {}

    public function __invoke(GetFilesystemRequest $request): DirItemResponse
    {
        $dir = $this->directoryRepository->findByProjectId($request->projectId);

        return $this->filesystemSerializer->dirItemResponse($dir);
    }
}
