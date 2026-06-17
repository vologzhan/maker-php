<?php declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Controller;
use App\Entity\Field;
use App\Entity\Project;
use App\Entity\Response;
use App\Response\Project\ControllerItem;
use App\Response\Project\FieldItem;
use App\Response\Project\ProjectResponse;
use App\Response\Project\ResponseItem;

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
            responses: array_map(
                fn (Response $response) => $this->responseItem($response),
                $project->getResponses(),
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

    private function responseItem(Response $response): ResponseItem
    {
        return new ResponseItem(
            id: $response->getId(),
            name: $response->getName(),
            fields: array_map(
                fn (Field $field) => $this->fieldItem($field),
                $response->getFields(),
            ),
        );
    }

    private function fieldItem(Field $field): FieldItem
    {
        return new FieldItem(
            id: $field->getId(),
            name: $field->getName(),
            type: $field->getType(),
            isArray: $field->isArray(),
            isNullable: $field->isNullable(),
            objectId: $field->getObject()?->getId(),
        );
    }
}
