<?php declare(strict_types=1);

namespace App\Dto\Php;

final readonly class ParamDto
{
    public function __construct(
        public NodeDto $type,
        public NodeDto $name,
        public ?NodeDto $comment,
        public bool $nullable,
        public ?AnnotationVarDto $annotationVar,
    ) {}
}
