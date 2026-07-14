<?php declare(strict_types=1);

namespace App\Response\Fs\Content;

use App\Response\Controller\ControllerItem;

final readonly class FileContent
{
    public function __construct(
        /** @var TokenItem[] */
        public array $tokens,
        public ?ControllerItem $controller,
    ) {}
}
