<?php declare(strict_types=1);

namespace App\Service\Php\Visitor;

use App\Dto\Php\ArgumentDto;
use App\Dto\Php\AttributeDto;
use App\Dto\Php\ClassDto;
use App\Dto\Php\MethodDto;
use App\Dto\Php\NodeDto;
use App\Dto\Php\TokenDto;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\Float_;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Token;

final class CollectorVisitor extends NodeVisitorAbstract
{
    /**
     * @var ClassDto[]
     */
    public array $classes = [];

    /**
     * @var TokenDto[]
     */
    public array $tokens = [];

    /**
     * @param Token[] $tokens
     */
    public function __construct(array $tokens){
        foreach ($tokens as $token) {
            $this->tokens[] = new TokenDto(
                pos: $token->pos,
                end: $token->getEndPos(),
                value: $token->text,
                type: $token->getTokenName() ?? $token->text,
            );
        }
    }

    public function leaveNode(Node $node): void
    {
        if ($node instanceof Class_) {
            $this->classes[] = $this->collectClass($node);
        }
    }

    private function collectClass(Class_ $class): ClassDto
    {
        $name = $class->name;

        $attributes = [];
        foreach ($class->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $attributes[] = $this->collectAttribute($attr);
            }
        }

        $methods = [];
        foreach ($class->getMethods() as $method) {
            $methods[] = $this->collectMethod($method);
        }

        return new ClassDto(
            name: new NodeDto(
                pos: $name->getStartTokenPos(),
                end: $name->getEndTokenPos(),
                value: $name->name,
            ),
            attributes: $attributes,
            methods: $methods
        );
    }

    private function collectMethod(ClassMethod $method): MethodDto
    {
        $name = $method->name;

        $attributes = [];
        foreach ($method->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $attributes[] = $this->collectAttribute($attr);
            }
        }

        return new MethodDto(
            name: new NodeDto(
                pos: $name->getStartTokenPos(),
                end: $name->getEndTokenPos(),
                value: $name->name,
            ),
            attributes: $attributes,
        );
    }

    private function collectAttribute(Attribute $attribute): AttributeDto
    {
        $name = $attribute->name->getAttribute('resolvedName');

        $args = [];
        foreach ($attribute->args as $arg) {
            $args[] = $this->collectArgument($arg);
        }

        return new AttributeDto(
            name: new NodeDto(
                pos: $name->getStartTokenPos(),
                end: $name->getEndTokenPos(),
                value: $name->name,
            ),
            args: $args,
        );
    }

    private function collectArgument(Arg $arg): ArgumentDto
    {
        $name = $arg->name;

        $value = $arg->value;
        $valuePos = $value->getStartTokenPos();
        $valueEnd = $value->getEndTokenPos();
        if ($value instanceof Array_) {
            $buf = [];
            /** @var ArrayItem $item */
            foreach ($value->items as $item) {
                $buf[] = $this->resolveArgumentValue($item->value);
            }
            $value = $buf;
        } else {
            $value = $this->resolveArgumentValue($value);
        }

        return new ArgumentDto(
            name: $name ? new NodeDto(
                pos: $name->getStartTokenPos(),
                end: $name->getEndTokenPos(),
                value: $name->name,
            ) : null,
            value: new NodeDto(
                pos: $valuePos,
                end: $valueEnd,
                value: $value,
            ),
        );
    }

    private function resolveArgumentValue(Expr $value): mixed
    {
        return match (true) {
            $value instanceof String_ => $value->value,
            $value instanceof Int_ => $value->value,
            $value instanceof Float_ => $value->value,
        };
    }
}
