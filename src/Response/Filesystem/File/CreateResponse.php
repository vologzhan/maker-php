<?php declare(strict_types=1);

namespace App\Response\Filesystem\File;

final readonly class CreateResponse
{
    public function __construct(
        public int $id,
    ) {}
}
