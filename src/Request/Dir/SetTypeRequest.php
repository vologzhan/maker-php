<?php declare(strict_types=1);

namespace App\Request\Dir;

use App\Enum\DirType;

final readonly class SetTypeRequest
{
    public function __construct(
        public int $id,
        public DirType $type,
    ) {}
}
