<?php

declare(strict_types=1);

namespace App\Dto\Php;

final readonly class AnnotationVarDto
{
    public function __construct(
        public NodeDto $name,
        public NodeDto $value,
        public bool $isScalar,
    ) {}
}
