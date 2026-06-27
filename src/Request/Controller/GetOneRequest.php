<?php declare(strict_types=1);

namespace App\Request\Controller;

final readonly class GetOneRequest
{
    public function __construct(
        public int $fileId,
    ) {}
}
