<?php declare(strict_types=1);

namespace App\Service\Controller;

use App\Repository\ControllerRepository;
use App\Request\Controller\GetOneRequest;
use App\Response\Controller\ControllerResponse;
use App\Serializer\ControllerSerializer;
use App\Service\Filesystem\File\ParsePhpFileService;

final readonly class GetOneService
{
    public function __construct(
        private ControllerRepository $controllerRepository,
        private ControllerSerializer $controllerSerializer,
        private ParsePhpFileService $parsePhpFileService,
    ) {}

    public function __invoke(GetOneRequest $request): ControllerResponse
    {
        $controller = $this->controllerRepository->findByFileId($request->fileId);
        $content = $this->parsePhpFileService->__invoke($request->fileId);

        return $this->controllerSerializer->controllerResponse($controller, $content);
    }
}
