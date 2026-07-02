<?php declare(strict_types=1);

namespace App\Controller\Filesystem\File;

use App\Request\Filesystem\File\CreateRequest;
use App\Response\SuccessResponse;
use App\Service\Filesystem\File\CreateService;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/controller', methods: ['POST'])]
final readonly class CreateController
{
    public function __invoke(CreateRequest $request, CreateService $service): SuccessResponse
    {
        return $service->__invoke($request);
    }
}
