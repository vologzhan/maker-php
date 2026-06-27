<?php declare(strict_types=1);

namespace App\Request\Controller;

final class UpdateRequest
{
    public function __construct(
        public int $id,
        public string $method,
        public string $path,
        public ?int $responseId,
    ) {}
}
