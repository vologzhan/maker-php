<?php declare(strict_types=1);

namespace App\Dto\Fs;

use App\Entity\Directory;

final class MakerStructure
{
    public function __construct(
        public ?Directory $project = null,
        public ?Directory $controller = null,
    ) {}
}
