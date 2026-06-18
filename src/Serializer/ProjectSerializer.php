<?php declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Field;
use App\Entity\Project;
use App\Entity\Response;
use App\Response\Project\Controller\ControllerItem;
use App\Response\Project\Controller\DirItem;
use App\Response\Project\Controller\FieldItem;
use App\Response\Project\Controller\ResponseItem;
use App\Response\Project\ProjectResponse;
use App\Service\Controller\ControllerService;

final readonly class ProjectSerializer
{
    public function projectResponse(Project $project): ProjectResponse
    {
        $controllersDir = new DirItem();

        $controllerDirPath = sprintf('%s/src/%s/', $project->getPath(), ControllerService::DIR_NAME);
        $prefixLen = strlen($controllerDirPath);
        foreach ($project->getControllers() as $controller) {
            $relativePath = substr($controller->getFilepath(), $prefixLen);

            $dirs = [];
            $pathToFile = dirname($relativePath);
            if ($pathToFile !== '.') {
                $dirs = explode('/', $pathToFile);
            }

            $current = $controllersDir;
            foreach ($dirs as $dir) {
                $current = $current->getOrCreateDir($dir);
            }

            $current->files[] = new ControllerItem(
                id: $controller->getId(),
                name: $controller->getName(),
                method: $controller->getMethod(),
                path: $controller->getPath(),
                responseId: $controller->getResponse()?->getId(),
            );
        }

        return new ProjectResponse(
            id: $project->getId(),
            name: $project->getName(),
            controllers: $controllersDir,
            responses: array_map(
                fn (Response $response) => $this->responseItem($response),
                $project->getResponses(),
            ),
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
