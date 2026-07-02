<?php declare(strict_types=1);

namespace App\Request\Controller;

final class CreateRequest
{
    public function __construct(
        public int $directoryId,
        public string $name,
    ) {}
}
