<?php declare(strict_types=1);

namespace App\Response\Fs\Content;

final readonly class FileContent
{
    public function __construct(
        /** @var TokenItem[] */
        public array $tokens,
    ) {}
}
