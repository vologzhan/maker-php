<?php declare(strict_types=1);

namespace App\Request\Project\Filesystem;

final readonly class GetTreeRequest
{
    public function __construct(
        public int $projectId,
    ) {}
}
