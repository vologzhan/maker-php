<?php declare(strict_types=1);

namespace Fixtures\Controller\Project;

use Fixtures\Request\Project\IndexRequest;
use Fixtures\Response\Project\ProjectResponse;
use Fixtures\Service\Project\IndexService;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/project/index', methods: ['POST'])]
final readonly class IndexController
{
    public function __invoke(IndexRequest $request, IndexService $service): ProjectResponse
    {
        return $service->__invoke($request);
    }
}
