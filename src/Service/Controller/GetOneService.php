<?php declare(strict_types=1);

namespace App\Service\Controller;

use App\Repository\ControllerRepository;
use App\Request\Controller\GetOneRequest;
use App\Response\Controller\ControllerResponse;
use App\Serializer\ControllerSerializer;

final readonly class GetOneService
{
    public function __construct(
        private ControllerRepository $controllerRepository,
        private ControllerSerializer $controllerSerializer,
    ) {}

    public function __invoke(GetOneRequest $request): ControllerResponse
    {
        $controller = $this->controllerRepository->findByFileId($request->fileId);

        return $this->controllerSerializer->controllerResponse($controller);
    }
}
