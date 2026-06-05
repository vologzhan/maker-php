<?php declare(strict_types=1);

namespace App\Response;

final readonly class SuccessResponse
{
    public function __construct(
        public bool $success = true,
    ) {}
}
