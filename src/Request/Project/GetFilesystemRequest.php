<?php declare(strict_types=1);

namespace App\Request\Project;

final readonly class GetFilesystemRequest
{
    public function __construct(
        public int $projectId,
    ) {}
}
