<?php declare(strict_types=1);

namespace Fixtures\Response\Controller;

final readonly class ControllerItem
{
    public function __construct(
        public int $id,
        public string $name,
        public string $method,
        public string $path,
    ) {}
}
