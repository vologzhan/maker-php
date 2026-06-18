<?php declare(strict_types=1);

namespace App\Response\Project;

use App\Response\Project\Controller\DirItem;
use App\Response\Project\Controller\ResponseItem;

final readonly class ProjectResponse
{
    public function __construct(
        public int $id,
        public string $name,
        public DirItem $controllers,
        /** @var ResponseItem[] */
        public array $responses,
    ) {}
}
