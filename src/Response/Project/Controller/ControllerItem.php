<?php declare(strict_types=1);

namespace App\Response\Project\Controller;

final readonly class ControllerItem
{
    public function __construct(
        public int $id,
        public string $name,
        public string $method,
        public string $path,
        public ?int $responseId,
    ) {}
}
