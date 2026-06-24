<?php declare(strict_types=1);

namespace App\Controller\Filesystem;

use App\Request\Filesystem\GetOneRequest;
use App\Response\Filesystem\FileResponse;
use App\Service\Filesystem\GetOneService;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/filesystem', methods: ['GET'])]
final readonly class GetOneController
{
    public function __invoke(GetOneRequest $request, GetOneService $service): FileResponse
    {
        return $service->__invoke($request);
    }
}
