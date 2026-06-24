<?php declare(strict_types=1);

namespace App\Controller\Project;

use App\Response\Project\ListResponse;
use App\Service\Project\GetListService;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/project', methods: ['GET'])]
final readonly class GetListController
{
    public function __invoke(GetListService $service): ListResponse
    {
        return $service->__invoke();
    }
}
