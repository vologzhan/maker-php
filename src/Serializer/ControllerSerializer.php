<?php declare(strict_types=1);

namespace App\Serializer;

use App\Dto\Php\FileDto;
use App\Entity\Controller;
use App\Response\Controller\ControllerResponse;
use App\Response\Project\Controller\ControllerItem;

final readonly class ControllerSerializer
{
    public function __construct(
        private FilesystemSerializer $filesystemSerializer,
    ) {}

    public function controllerResponse(Controller $controller, FileDto $file): ControllerResponse
    {
        return new ControllerResponse(
            id: $controller->getId(),
            method: $controller->getMethod(),
            path: $controller->getPath(),
            responseId: $controller->getResponse()?->getId(),
            content: $this->filesystemSerializer->tokenItemArray($file->tokens),
        );
    }

    public function controllerItem(Controller $controller): ControllerItem
    {
        return new ControllerItem(
            id: $controller->getId(),
            name: $controller->getName(),
            method: $controller->getMethod(),
            path: $controller->getPath(),
            responseId: $controller->getResponse()?->getId(),
        );
    }
}
