<?php declare(strict_types=1);

namespace App\Response\Controller;

use App\Response\Filesystem\File\TokenItem;

final readonly class ControllerResponse
{
    public function __construct(
        public int $id,
        public string $method,
        public string $path,
        public ?int $responseId,
        /** @var TokenItem[] */
        public array $content,
    ) {}
}
