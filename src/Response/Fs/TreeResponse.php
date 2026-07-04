<?php declare(strict_types=1);

namespace App\Response\Fs;

final readonly class TreeResponse
{
    public function __construct(
        /** @var DirItem[] */
        public array $dirs,
    ) {}
}
