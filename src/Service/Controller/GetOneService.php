<?php declare(strict_types=1);

namespace App\Service\Controller;

use App\Repository\ControllerRepository;
use App\Request\Controller\GetOneRequest;
use App\Request\Filesystem\File\GetContentRequest;
use App\Response\Controller\ControllerResponse;
use App\Serializer\ControllerSerializer;
use App\Service\Filesystem\File\GetContentService;

final readonly class GetOneService
{
    public function __construct(
        private ControllerRepository $controllerRepository,
        private ControllerSerializer $controllerSerializer,
        private GetContentService $getContentService,
    ) {}

    public function __invoke(GetOneRequest $request): ControllerResponse
    {
        $controller = $this->controllerRepository->findByFileId($request->fileId);
        $content = $this->getContentService->__invoke(new GetContentRequest($request->fileId));

        return $this->controllerSerializer->controllerResponse($controller, $content);
    }
}
