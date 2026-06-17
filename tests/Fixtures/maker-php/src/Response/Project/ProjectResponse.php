<?php declare(strict_types=1);

namespace Fixtures\Response\Project;

use Fixtures\Response\Controller\ControllerItem;

final readonly class ProjectResponse
{
    public function __construct(
        public int $id,
        public string $name,
        /** @var ControllerItem[] */
        public array $controllers,
    ) {}
}
