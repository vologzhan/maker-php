<?php declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Controller;
use App\Response\Project\Controller\ControllerItem;

final readonly class ControllerSerializer
{
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
