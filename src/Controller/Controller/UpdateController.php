<?php declare(strict_types=1);

namespace App\Controller\Controller;

use App\Request\Controller\UpdateRequest;
use App\Response\SuccessResponse;
use App\Service\Controller\UpdateService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path: '/api/controller/{id}', requirements: ['id' => Requirement::DIGITS], methods: ['PUT'])]
final readonly class UpdateController
{
    public function __invoke(UpdateRequest $request, UpdateService $service): SuccessResponse
    {
        $service->__invoke($request);

        return new SuccessResponse();
    }
}
