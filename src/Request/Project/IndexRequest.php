<?php declare(strict_types=1);

namespace App\Request\Project;

final class IndexRequest
{
    public function __construct(
        public string $path,
    ) {}
}
