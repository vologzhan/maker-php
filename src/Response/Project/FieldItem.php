<?php declare(strict_types=1);

namespace App\Response\Project;

use App\Enum\Type;

final readonly class FieldItem
{
    public function __construct(
        public int $id,
        public string $name,
        public Type $type,
        public bool $isArray,
        public bool $isNullable,
        public ?int $objectId,
    ) {}
}
