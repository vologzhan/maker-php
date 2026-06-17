<?php declare(strict_types=1);

namespace App\Dto\Php;

final readonly class MethodDto
{
    public function __construct(
        public NodeDto $name,
        public ?NodeDto $return,
        /** @var ParamDto[] */
        public array $params,
        /** @var AttributeDto[] */
        public array $attributes,
    ) {}
}
