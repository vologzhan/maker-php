<?php declare(strict_types=1);

namespace App\Controller\Project\Filesystem;

use App\Request\Project\Filesystem\UpdateDirectoryTypeRequest;
use App\Response\SuccessResponse;
use App\Service\Project\Filesystem\UpdateDirectoryTypeService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path: '/api/filesystem/directory/{directoryId}/type', requirements: ['directoryId' => Requirement::DIGITS], methods: ['PUT'])]
final readonly class UpdateDirectoryTypeController
{
    public function __invoke(UpdateDirectoryTypeRequest $request, UpdateDirectoryTypeService $service): SuccessResponse
    {
        $service->__invoke($request);

        return new SuccessResponse();
    }
}
