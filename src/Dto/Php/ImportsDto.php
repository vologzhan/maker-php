<?php declare(strict_types=1);

namespace App\Dto\Php;

final readonly class ImportsDto extends NodeDto
{
    /**
     * @param ImportDto[] $value
     */
    public function __construct(
        int $pos,
        int $end,
        /** @param ImportDto[] */
        array $value,
    ) {
        parent::__construct($pos, $end, $value);
    }
}
