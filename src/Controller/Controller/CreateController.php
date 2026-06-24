<?php declare(strict_types=1);

namespace App\Controller\Controller;

use App\Request\Controller\CreateRequest;
use App\Response\Project\Controller\ControllerItem;
use App\Service\Controller\CreateService;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/controller', methods: ['POST'])]
final readonly class CreateController
{
    public function __invoke(CreateRequest $request, CreateService $service): ControllerItem
    {
        return $service->__invoke($request);
    }
}
