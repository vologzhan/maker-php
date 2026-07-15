<?php declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Controller;
use App\Response\Controller\ControllerItem;

final readonly class ControllerSerializer
{
    public function controllerItem(?Controller $controller): ?ControllerItem
    {
        if ($controller === null) {
            return null;
        }

        return new ControllerItem(
            id: $controller->getId(),
            method: $controller->getMethod(),
            path: $controller->getPath(),
            responseId: $controller->getResponse()?->getId(),
        );
    }
}
