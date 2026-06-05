<?php declare(strict_types=1);

namespace App\Response\Controller;

use Symfony\Component\Uid\Uuid;

final readonly class ControllerItemResponse
{
    public function __construct(
        public Uuid $uuid,
        public string $name,
        public string $method,
        public string $path,
    ) {}
}
