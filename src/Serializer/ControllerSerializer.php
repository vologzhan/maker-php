<?php declare(strict_types=1);

namespace App\Serializer;

use App\Dto\Php\ClassDto;
use App\Response\Controller\ControllerItemResponse;
use App\Service\String\StrCase;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final readonly class ControllerSerializer
{
    public function controllerItemResponse(Uuid $uuid, ClassDto $class): ControllerItemResponse
    {
        $name = $class->name;
        $suffix = 'Controller';
        if (str_ends_with($name->value, $suffix)) {
            $name = substr($class->name->value, 0, -strlen($suffix));
        }
        $name = StrCase::toSentence($name);

        $attribute = $class->attribute(Route::class);

        return new ControllerItemResponse(
            uuid: $uuid,
            name: $name,
            method: $attribute->args[1]->value->value[0],
            path: $attribute->args[0]->value->value,
        );
    }
}
