<?php declare(strict_types=1);

namespace App\Service\Controller;

use App\Service\String\StrCase;

final readonly class ControllerService
{
    public function nameToClassName(string $name): string
    {
        return StrCase::toPascalCase($name) . 'Controller';
    }
}
