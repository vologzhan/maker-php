<?php declare(strict_types=1);

namespace App\Enum;

enum Type: string
{
    case String = 'string';
    case Integer = 'integer';
    case Boolean = 'boolean';
    case Object = 'object';
}
