<?php declare(strict_types=1);

namespace App\Response\Project;

final readonly class ListResponse
{
    public function __construct(
        /** @var ProjectItemResponse */
        public array $items,
    ) {}
}
