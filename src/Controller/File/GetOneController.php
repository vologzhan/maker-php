<?php declare(strict_types=1);

namespace App\Controller\File;

use App\Request\File\GetOneRequest;
use App\Response\File\FileResponse;
use App\Service\File\GetOneService;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/file', methods: ['GET'])]
final readonly class GetOneController
{
    public function __invoke(GetOneRequest $request, GetOneService $service): FileResponse
    {
        return $service->__invoke($request);
    }
}
