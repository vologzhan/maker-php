<?php declare(strict_types=1);

namespace App\Controller\Project;

use App\Request\Project\IndexRequest;
use App\Response\Project\ProjectResponse;
use App\Service\Project\IndexService;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/project/index', methods: ['POST'])]
final readonly class IndexController
{
    public function __invoke(IndexRequest $request, IndexService $service): ProjectResponse
    {
        return $service->__invoke($request);
    }
}
