<?php declare(strict_types=1);

namespace App\Controller\Project\Filesystem;

use App\Request\Project\Filesystem\GetTreeRequest;
use App\Response\Project\Filesystem\DirItemResponse;
use App\Service\Project\Filesystem\GetTreeService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/project/{projectId}/filesystem/tree', requirements: ['projectId' => Requirement::DIGITS], methods: ['GET'])]
final readonly class GetTreeController
{
    public function __invoke(GetTreeRequest $request, GetTreeService $service): DirItemResponse
    {
        return $service->__invoke($request);
    }
}
