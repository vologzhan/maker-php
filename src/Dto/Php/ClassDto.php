<?php declare(strict_types=1);

namespace App\Dto\Php;

final readonly class ClassDto
{
    public function __construct(
        public string $fqn,
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

    public function method(string $methodName): MethodDto
    {
        foreach ($this->methods as $method) {
            if ($method->name->value === $methodName) {
                return $method;
            }
        }

        throw new \Exception(
            sprintf("Method not found. Class: '%s'. Method: '%s'.", $this->name->value, $methodName),
        );
    }
}
