<?php declare(strict_types=1);

namespace App\Controller\Project;

use App\Request\Project\GetFilesystemRequest;
use App\Response\Project\Filesystem\DirItemResponse;
use App\Service\Project\GetFilesystemService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/project/{projectId}/filesystem', requirements: ['projectId' => Requirement::DIGITS], methods: ['GET'])]
final readonly class GetFilesystemController
{
    public function __invoke(GetFilesystemRequest $request, GetFilesystemService $service): DirItemResponse
    {
        return $service->__invoke($request);
    }
}
