<?php declare(strict_types=1);

namespace App\Response\Project;

final readonly class ResponseItem
{
    public function __construct(
        public int $id,
        public string $name,
        /** @var FieldItem */
        public array $fields,
    ) {}
}
