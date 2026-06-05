<?php declare(strict_types=1);

namespace App\Dto\Php;

final readonly class ClassDto
{
    public function __construct(
        public NodeDto $name,
        /** @var AttributeDto[] */
        public array $attributes,
        /** @var MethodDto[] */
        public array $methods,
    ) {}

    public function attribute(string $attrClassName): AttributeDto
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->name->value === $attrClassName) {
                return $attribute;
            }
        }

        throw new \Exception(
            sprintf("Attribute not found. Class: '%s'. Attribute class: '%s'.", $this->name->value, $attrClassName),
        );
    }
}
