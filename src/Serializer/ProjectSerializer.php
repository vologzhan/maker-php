<?php declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Field;
use App\Entity\Project;
use App\Entity\Response;
use App\Response\Project\Controller\DirItem;
use App\Response\Project\Controller\FieldItem;
use App\Response\Project\Controller\ResponseItem;
use App\Response\Project\ProjectItemResponse;
use App\Service\Controller\ControllerHelper;

final readonly class ProjectSerializer
{
    public function __construct(
        private ControllerSerializer $controllerSerializer,
    ) {}

    public function projectResponseOld(Project $project): ProjectItemResponse
    {
        $controllersDir = new DirItem();

        $controllerDirPath = sprintf('%s/src/%s/', $project->getPath(), ControllerHelper::DIR_NAME);
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

            $current->files[] = $this->controllerSerializer->controllerItem($controller);
        }

        return new ProjectItemResponse(
            id: $project->getId(),
            name: basename($project->getPath()),
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
