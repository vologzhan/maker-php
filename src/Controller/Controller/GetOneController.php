<?php declare(strict_types=1);

namespace App\Controller\Controller;

use App\Request\Controller\GetOneRequest;
use App\Response\Controller\ControllerResponse;
use App\Service\Controller\GetOneService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/controller/{fileId}', requirements: ['fileId' => Requirement::DIGITS], methods: ['GET'])]
final readonly class GetOneController
{
    public function __invoke(GetOneRequest $request, GetOneService $service): ControllerResponse
    {
        return $service->__invoke($request);
    }
}
