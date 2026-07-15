<?php declare(strict_types=1);

namespace App\Enum;

enum FileType: string
{
    case PhpClass = 'php_class';
    case Controller = 'controller';
}
