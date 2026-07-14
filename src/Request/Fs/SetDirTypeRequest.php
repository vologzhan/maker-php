<?php declare(strict_types=1);

namespace App\Request\Fs;

use App\Enum\DirectoryType;

final readonly class SetDirTypeRequest
{
    public function __construct(
        public int $id,
        public ?DirectoryType $type,
    ) {}
}
