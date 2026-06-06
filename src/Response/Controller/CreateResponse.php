<?php declare(strict_types=1);

namespace App\Response\Controller;

use Symfony\Component\Uid\Uuid;

final readonly class CreateResponse
{
    public function __construct(
        public Uuid $uuid,
    ) {}
}
