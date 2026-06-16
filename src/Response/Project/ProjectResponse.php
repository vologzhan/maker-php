<?php declare(strict_types=1);

namespace App\Response\Project;

use App\Response\Controller\ControllerItem;

final readonly class ProjectResponse
{
    public function __construct(
        public int $id,
        public string $name,
        public string $path,
        /** @var ControllerItem[] */
        public array $controllers,
    ) {}
}
