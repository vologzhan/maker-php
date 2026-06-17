<?php declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Controller;
use App\Entity\Project;
use App\Response\Controller\ControllerItem;
use App\Response\Project\ProjectResponse;

final readonly class ProjectSerializer
{
    public function projectResponse(Project $project): ProjectResponse
    {
        return new ProjectResponse(
            id: $project->getId(),
            name: $project->getName(),
            controllers: array_map(
                fn (Controller $controller) => $this->controllerItem($controller),
                $project->getControllers(),
            ),
        );
    }

    private function controllerItem(Controller $controller): ControllerItem
    {
        return new ControllerItem(
            id: $controller->getId(),
            name: $controller->getName(),
            method: $controller->getMethod(),
            path: $controller->getPath(),
        );
    }
}
