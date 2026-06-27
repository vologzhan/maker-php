<?php declare(strict_types=1);

namespace App\Service\Controller;

use App\Service\String\StrCase;

final readonly class ControllerHelper
{
    public const string DIR_NAME = 'Controller';
    private const string FILE_SUFFIX = 'Controller';

    public function nameToClassName(string $name): string
    {
        return StrCase::toPascalCase($name) . self::FILE_SUFFIX;
    }
}
