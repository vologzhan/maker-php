<?php declare(strict_types=1);

namespace App\Controller\Controller;

use App\Request\Controller\DeleteRequest;
use App\Response\SuccessResponse;
use App\Service\Controller\DeleteService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path: '/api/controller/{fileId}', requirements: ['fileId' => Requirement::DIGITS], methods: ['DELETE'])]
final readonly class DeleteController
{
    public function __invoke(DeleteRequest $request, DeleteService $service): SuccessResponse
    {
        $service->__invoke($request);

        return new SuccessResponse();
    }
}
