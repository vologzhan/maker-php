<?php declare(strict_types=1);

namespace App\Response;

use App\Response\Node\NodeItemInterface;

final readonly class FileContentResponse
{
    public function __construct(
        public string $content,
        /** @var NodeItemInterface[] */
        public array $tokens,
    ) {}
}
