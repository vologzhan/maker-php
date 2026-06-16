<?php declare(strict_types=1);

namespace App\Service\Controller;

use App\Service\String\StrCase;

final readonly class ControllerService
{
    private const string FILE_SUFFIX = 'Controller';

    public function nameToClassName(string $name): string
    {
        return StrCase::toPascalCase($name) . self::FILE_SUFFIX;
    }

    public function classNameToName(string $className): string
    {
        if (str_ends_with($className, self::FILE_SUFFIX)) {
            $name = substr($className, 0, -strlen(self::FILE_SUFFIX));
        } else {
            $name = $className;
        }

        return StrCase::toSentence($name);
    }
}
