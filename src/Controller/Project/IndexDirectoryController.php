<?php declare(strict_types=1);

namespace App\Controller\Project;

use App\Request\Project\IndexDirectoryRequest;
use App\Response\Project\ProjectItemResponse;
use App\Service\Project\IndexDirectoryService;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/project/index', methods: ['POST'])]
final readonly class IndexDirectoryController
{
    public function __invoke(IndexDirectoryRequest $request, IndexDirectoryService $service): ProjectItemResponse
    {
        return $service->__invoke($request);
    }
}
