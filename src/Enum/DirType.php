<?php declare(strict_types=1);

namespace App\Enum;

enum DirType: string
{
    case Project = 'project';
    case Controller = 'controller';
}
