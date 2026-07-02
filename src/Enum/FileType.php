<?php declare(strict_types=1);

namespace App\Enum;

// todo кажется тут нарушается S из SOLID
enum FileType: string
{
    case PhpClass = 'php_class';
    case Controller = 'controller';
}
