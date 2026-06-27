<?php declare(strict_types=1);

namespace App\Request\Controller;

final readonly class DeleteRequest
{
    public function __construct(
        public int $id,
    ) {}
}
