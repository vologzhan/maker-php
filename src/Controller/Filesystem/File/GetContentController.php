<?php declare(strict_types=1);

namespace App\Controller\Filesystem\File;

use App\Request\Filesystem\File\GetContentRequest;
use App\Response\Filesystem\File\ContentResponse;
use App\Service\Filesystem\File\GetContentService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/filesystem/file/{id}', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
final readonly class GetContentController
{
    public function __invoke(GetContentRequest $request, GetContentService $service): ContentResponse
    {
        return $service->__invoke($request);
    }
}
